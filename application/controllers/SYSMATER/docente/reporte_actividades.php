<?php
defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class reporte_actividades extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('/SYSMATER/shared/instrumento_model');
        $this->load->model('/SYSMATER/shared/actividades_model');
    }

    public function index()
    {
        if ($this->session->id_tipo == 4) {


            $vchPeriodo = $this->determinarPeriodo();
            $vchClvTrabajador = $this->session->Matricula;


            $data['cuatrimestres'] = $this->instrumento_model->obtener_cuatrimestres_docente($vchClvTrabajador, $vchPeriodo);


            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/reportes_actividades_view", $data);
            $this->load->view("templates/shared/footer_view");
        } else {
            $this->session->set_flashdata(
                'msg',
                '<div class="alert alert-danger text-center"> 
				!Sesión no iniciada!
				Verifique que el usuario y contraseña sean correctos
			</div>'
            );
            redirect("login");
        }
    }


    public function exportar_reporte()
    {
        $tipo = $this->input->post('tipo');
        $params = $this->input->post();
        $periodo = $this->determinarPeriodo();

        // Validación
        if (
            empty($params['cuatrimestre']) ||
            empty($params['materia']) ||
            empty($params['grupo']) ||
            empty($params['parcial']) ||
            empty($params['tipo'])
        ) {
            echo json_encode([
                'status' => 'error',
                'mensaje' => 'Faltan datos para generar el reporte detallado.'
            ]);
            return;
        }

        //$datos = $this->procesarReporteDetallado($params, $periodo);

        $original = $this->actividades_model->obtener_concentrado_completo($params, $periodo);



        $procesado = [];

        foreach ($original as $fila) {
            $alumno = [
                'Matricula' => $fila['Matricula'],
                'Nombre_Alumno' => $fila['Nombre_Alumno'],
                'Grupo' => $fila['Grupo'],
                'Docente' => $fila['Docente'],
                'Nombre_materia' => $fila['Nombre_materia'],
                'Periodo' => $fila['Periodo'],
                'Parcial' => $fila['Parcial'],
                'Cuatrimestre' => $fila['Cuatrimestre'],
                'actividades' => [],
                'Calificacion Final' => $fila['Calificacion_Final']
            ];

            foreach ($fila as $columna => $valor) {
                // Ignorar campos que no son actividades
                if (in_array($columna, ['No.', 'Matricula', 'Nombre_Alumno', 'Grupo', 'Docente', 'Nombre_materia', 'Periodo', 'Parcial', 'Cuatrimestre', 'Calificacion_Final'])) {
                    continue;
                }

                // Detectar componente (parte antes del primer "-")
                if (strpos($columna, '-') !== false) {
                    [$componente, $actividad] = array_map('trim', explode('-', $columna, 2));
                    $alumno['actividades'][$componente][$columna] = $valor;
                } elseif (strpos($columna, 'Prom_') === 0) {
                    $componente = str_replace('Prom_', '', $columna);
                    $alumno['actividades'][$componente][$columna] = $valor;
                } elseif (strpos($columna, 'CalPond_') === 0) {
                    preg_match('/CalPond_(.*?) \(/', $columna, $match);
                    $componente = $match[1] ?? 'Desconocido';
                    $alumno['actividades'][$componente][$columna] = $valor;
                }
            }

            $procesado[] = $alumno;
        }

        //log_message('debug', 'Contenido de $datos: ' . print_r($procesado, true));

        if (empty($procesado)) {

            echo json_encode([
                'status' => 'error',
                'mensaje' => 'No se encontraron datos para el reporte final.'
            ]);
            return;
        }

        // Aquí normalmente generarías el archivo físico (Excel o PDF), por ahora solo simulamos
        if ($tipo === 'excel') {
            $this->generar_excel_calificaciones($procesado);

            echo json_encode([
                'status' => 'success',
                'mensaje' => '¡Excel del reporte detallado generado correctamente!',
                'datos' => $procesado // solo si quieres visualizar los datos en JS, si no, omítelo
            ]);
        } elseif ($tipo === 'pdf') {
            $this->generar_pdf_calificaciones($procesado);

            echo json_encode([
                'status' => 'success',
                'mensaje' => '¡PDF del reporte detallado generado correctamente!',
                'datos' => $procesado
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'mensaje' => 'Tipo de archivo no válido.'
            ]);
        }
    }

    function determinarPeriodo()
    {
        $fechaActual = new DateTime();
        $mes = (int) $fechaActual->format('m');
        $año = $fechaActual->format('Y');
        $periodo = 0;

        if ($mes >= 1 && $mes <= 4) {
            $periodo = 1; // Enero - Abril
        } elseif ($mes >= 5 && $mes <= 8) {
            $periodo = 2; // Mayo - Agosto
        } elseif ($mes >= 9 && $mes <= 12) {
            $periodo = 3; // Septiembre - Diciembre
        } else {
            $periodo = 4; // Caso muy raro
        }

        return $año . $periodo;
    }

    public function generar_excel_calificaciones($datos_calificaciones = null)
    {
        if (empty($datos_calificaciones)) {
            echo json_encode([
                'status' => 'error',
                'mensaje' => 'No hay datos para generar el Excel.'
            ]);
            return;
        }

        // Crear nueva instancia de Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Obtener datos del primer alumno para la información general
        $primerAlumno = $datos_calificaciones[0];

        // Configurar el encabezado principal
        $sheet->setCellValue('A1', 'UNIVERSIDAD TECNOLÓGICA DE LA');
        $sheet->setCellValue('A2', 'HUASTECA HIDALGUENSE');
        $sheet->mergeCells('A1:O1');
        $sheet->mergeCells('A2:O2');

        // Aplicar estilo al título
        $sheet->getStyle('A1:A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);

        // Crear tabla de información (filas 4-8) - IGUAL AL PDF
        $info_data = [
            ['Materia:', strtoupper($primerAlumno['Nombre_materia'])],
            ['Docente:', strtoupper($primerAlumno['Docente'])],
            ['Período:', $primerAlumno['Periodo']],
            ['Evaluación:', 'PARCIAL ' . $primerAlumno['Parcial']],
            ['Grado y Grupo:', $primerAlumno['Cuatrimestre'] . ' "' . $primerAlumno['Grupo'] . '"']
        ];

        $fila_info = 4;
        foreach ($info_data as $info) {
            $sheet->setCellValue('A' . $fila_info, $info[0]);
            $sheet->setCellValue('B' . $fila_info, $info[1]);
            $sheet->mergeCells('B' . $fila_info . ':O' . $fila_info);
            $fila_info++;
        }

        // Aplicar bordes y estilos a la tabla de información
        $sheet->getStyle('A4:O8')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Estilo para las etiquetas (columna A) - gris como en PDF
        $sheet->getStyle('A4:A8')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9D9D9']
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);

        // Estilo para los valores (columnas B:O)
        $sheet->getStyle('B4:O8')->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);

        // ANÁLISIS DE ACTIVIDADES - DETECTAR EJERCICIOS Y PRÁCTICAS
        $actividades = [];

        foreach ($datos_calificaciones as $alumno) {
            foreach ($alumno['actividades'] as $nombre_actividad => $datos_actividad) {
                if (!isset($actividades[$nombre_actividad])) {
                    $actividades[$nombre_actividad] = [
                        'practicas' => [],
                        'porcentaje' => null
                    ];

                    // Encontrar prácticas/ejercicios (cualquier cosa después de " - ")
                    foreach ($datos_actividad as $key => $value) {
                        if (
                            strpos($key, ' - ') !== false &&
                            strpos($key, 'Prom_') !== 0 &&
                            strpos($key, 'CalPond_') !== 0
                        ) {
                            $actividades[$nombre_actividad]['practicas'][] = $key;
                        }
                    }

                    // Ordenar las prácticas/ejercicios para mantener consistencia
                    sort($actividades[$nombre_actividad]['practicas']);

                    // Encontrar porcentaje
                    foreach ($datos_actividad as $key => $value) {
                        if (strpos($key, 'CalPond_') === 0) {
                            if (preg_match('/\((\d+\.\d+)%\)/', $key, $matches)) {
                                $actividades[$nombre_actividad]['porcentaje'] = $matches[1];
                            }
                            break;
                        }
                    }
                }
            }
        }

        // CREAR ENCABEZADOS DE LA TABLA PRINCIPAL
        $fila_encabezado_principal = 10;
        $fila_encabezado_secundario = 11;

        // Matrícula y Nombre (se extienden 2 filas) - IGUAL AL PDF
        $sheet->setCellValue('A' . $fila_encabezado_principal, 'MATRÍCULA');
        $sheet->setCellValue('B' . $fila_encabezado_principal, 'NOMBRE DEL ALUMNO');
        $sheet->mergeCells('A' . $fila_encabezado_principal . ':A' . $fila_encabezado_secundario);
        $sheet->mergeCells('B' . $fila_encabezado_principal . ':B' . $fila_encabezado_secundario);

        $columna_actual = 'C';
        $mapeo_columnas = [];

        // Crear encabezados para cada actividad - ESTRUCTURA DEL PDF
        foreach ($actividades as $nombre_actividad => $info_actividad) {
            $columna_inicio = $columna_actual;
            $num_practicas = count($info_actividad['practicas']);

            // Determinar cuántas columnas necesita
            if ($num_practicas > 0) {
                $columnas_necesarias = $num_practicas + 2; // prácticas + promedio + valor pts
            } else {
                $columnas_necesarias = 2; // cal + valor pts
            }

            $columna_fin = chr(ord($columna_inicio) + $columnas_necesarias - 1);

            // Encabezado principal de la actividad
            $sheet->setCellValue($columna_inicio . $fila_encabezado_principal, strtoupper($nombre_actividad));
            $sheet->mergeCells($columna_inicio . $fila_encabezado_principal . ':' . $columna_fin . $fila_encabezado_principal);

            // Sub-encabezados - IGUAL AL PDF
            if ($num_practicas > 0) {
                // Números de las prácticas (1, 2, 3, etc.)
                for ($i = 1; $i <= $num_practicas; $i++) {
                    $sheet->setCellValue($columna_actual . $fila_encabezado_secundario, $i);
                    $mapeo_columnas[$nombre_actividad]['practica_' . $i] = $columna_actual;
                    $columna_actual++;
                }

                // Columna Promedio
                $sheet->setCellValue($columna_actual . $fila_encabezado_secundario, 'Promedio');
                $mapeo_columnas[$nombre_actividad]['promedio'] = $columna_actual;
                $columna_actual++;
            } else {
                // Sin prácticas, solo Cal
                $sheet->setCellValue($columna_actual . $fila_encabezado_secundario, 'Cal');
                $mapeo_columnas[$nombre_actividad]['cal'] = $columna_actual;
                $columna_actual++;
            }

            // Columna Valor pts - FORMATO DEL PDF
            $valor_pts_text = 'Valor ';
            if (isset($info_actividad['porcentaje'])) {
                $valor_pts_text .= $info_actividad['porcentaje'] . ' pts';
            } else {
                $valor_pts_text .= 'pts';
            }

            $sheet->setCellValue($columna_actual . $fila_encabezado_secundario, $valor_pts_text);
            $mapeo_columnas[$nombre_actividad]['valor_pts'] = $columna_actual;
            $columna_actual++;
        }

        // Columna Resultados - IGUAL AL PDF
        $sheet->setCellValue($columna_actual . $fila_encabezado_principal, 'Resultados');
        $columna_decimal = $columna_actual;
        $sheet->setCellValue($columna_actual . $fila_encabezado_secundario, 'Decimal');
        $columna_actual++;

        $columna_redondeo = $columna_actual;
        $sheet->setCellValue($columna_actual . $fila_encabezado_secundario, 'Redondeo');

        // Merge para Resultados
        $sheet->mergeCells($columna_decimal . $fila_encabezado_principal . ':' . $columna_redondeo . $fila_encabezado_principal);

        $ultima_columna = $columna_redondeo;

        // APLICAR ESTILOS A LOS ENCABEZADOS - COLOR VERDE COMO PDF
        $sheet->getStyle('A' . $fila_encabezado_principal . ':' . $ultima_columna . $fila_encabezado_secundario)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 9],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '278104'] // Verde como en PDF
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // LLENAR DATOS DE LOS ALUMNOS - LÓGICA DEL PDF
        $fila_datos = $fila_encabezado_secundario + 1;

        foreach ($datos_calificaciones as $index => $alumno) {
            // Matrícula
            $sheet->setCellValue('A' . $fila_datos, $alumno['Matricula']);

            // Nombre (en mayúsculas como PDF)
            $sheet->setCellValue('B' . $fila_datos, strtoupper($alumno['Nombre_Alumno']));

            // Llenar datos de actividades - LÓGICA DEL PDF
            foreach ($actividades as $nombre_actividad => $info_actividad) {
                $datos_actividad = isset($alumno['actividades'][$nombre_actividad]) ? $alumno['actividades'][$nombre_actividad] : [];
                $num_practicas = count($info_actividad['practicas']);

                if ($num_practicas > 0) {
                    // Llenar prácticas/ejercicios por orden
                    for ($i = 1; $i <= $num_practicas; $i++) {
                        if (isset($mapeo_columnas[$nombre_actividad]['practica_' . $i])) {
                            $columna = $mapeo_columnas[$nombre_actividad]['practica_' . $i];

                            // Buscar la práctica/ejercicio correspondiente
                            $valor = '';
                            if (isset($info_actividad['practicas'][$i - 1])) {
                                $practica_key = $info_actividad['practicas'][$i - 1];

                                $valor = isset($datos_actividad[$practica_key]) && $datos_actividad[$practica_key] !== ''
                                    ? $datos_actividad[$practica_key]
                                    : 0;
                            }

                            $sheet->setCellValue($columna . $fila_datos, $valor);
                        }
                    }

                    // Promedio (usando Prom_ como en PDF)
                    if (isset($mapeo_columnas[$nombre_actividad]['promedio'])) {
                        $columna = $mapeo_columnas[$nombre_actividad]['promedio'];
                        $prom_key = 'Prom_' . $nombre_actividad;
                        $promedio = isset($datos_actividad[$prom_key]) ? $datos_actividad[$prom_key] : 0;
                        $sheet->setCellValue($columna . $fila_datos, $promedio);
                    }
                } else {
                    // Sin prácticas, solo Cal
                    if (isset($mapeo_columnas[$nombre_actividad]['cal'])) {
                        $columna = $mapeo_columnas[$nombre_actividad]['cal'];
                        $cal_key = 'Cal_' . $nombre_actividad;
                        $calificacion = isset($datos_actividad[$cal_key]) ? $datos_actividad[$cal_key] : 0;
                        $sheet->setCellValue($columna . $fila_datos, $calificacion);
                    }
                }

                // Valor ponderado - IGUAL AL PDF
                if (isset($mapeo_columnas[$nombre_actividad]['valor_pts'])) {
                    $columna = $mapeo_columnas[$nombre_actividad]['valor_pts'];
                    $valor_ponderado = 0;

                    foreach ($datos_actividad as $key => $value) {
                        if (strpos($key, 'CalPond_') === 0) {
                            $valor_ponderado = $value;
                            break;
                        }
                    }

                    // Formatear con 1 decimal como en PDF
                    $sheet->setCellValue($columna . $fila_datos, number_format($valor_ponderado, 1));
                }
            }

            // Resultados - IGUAL AL PDF
            $calificacion_final = $alumno['Calificacion Final'];
            $sheet->setCellValue($columna_decimal . $fila_datos, number_format($calificacion_final, 1));
            $sheet->setCellValue($columna_redondeo . $fila_datos, round($calificacion_final));

            $fila_datos++;
        }

        // APLICAR ESTILOS A LOS DATOS - DISEÑO DEL PDF
        $total_filas_datos = count($datos_calificaciones);
        $fila_inicio_datos = $fila_encabezado_secundario + 1;
        $fila_fin_datos = $fila_inicio_datos + $total_filas_datos - 1;

        // Bordes para toda la tabla
        $sheet->getStyle('A' . $fila_inicio_datos . ':' . $ultima_columna . $fila_fin_datos)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'font' => ['size' => 9]
        ]);

        // Nombres alineados a la izquierda como en PDF
        $sheet->getStyle('B' . $fila_inicio_datos . ':B' . $fila_fin_datos)->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);

        // Alternar colores de filas - VERDE CLARO COMO PDF
        for ($i = $fila_inicio_datos; $i <= $fila_fin_datos; $i++) {
            if (($i - $fila_inicio_datos) % 2 == 0) {
                $sheet->getStyle('A' . $i . ':' . $ultima_columna . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E8F5E8'] // Verde muy claro como PDF
                    ]
                ]);
            }
        }

        // AJUSTAR ANCHO DE COLUMNAS - SIMILAR AL PDF
        $sheet->getColumnDimension('A')->setWidth(15); // Matrícula
        $sheet->getColumnDimension('B')->setWidth(40); // Nombre (más ancho como PDF)

        // Ajustar columnas de actividades
        for ($col = 'C'; $col <= $ultima_columna; $col++) {
            $sheet->getColumnDimension($col)->setWidth(10);
        }

        // FIRMA - IGUAL AL PDF
        $fila_firma = $fila_fin_datos + 3;
        $sheet->setCellValue('A' . $fila_firma, 'Nombre y firma del Jefe de Grupo');
        $sheet->mergeCells('A' . $fila_firma . ':' . $ultima_columna . $fila_firma);
        $sheet->getStyle('A' . $fila_firma)->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'font' => ['bold' => true, 'size' => 11]
        ]);

        // Línea para la firma
        $fila_linea = $fila_firma + 2;
        $sheet->setCellValue('E' . $fila_linea, '________________________________________________');
        $sheet->mergeCells('E' . $fila_linea . ':' . chr(ord($ultima_columna) - 2) . $fila_linea);
        $sheet->getStyle('E' . $fila_linea)->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Generar y descargar el archivo
        $writer = new Xlsx($spreadsheet);

        $filename = 'Reporte_' . ($primerAlumno['Clave_Materia'] ?? 'Materia') . '_' .
            $primerAlumno['Grupo'] . '_P' . $primerAlumno['Parcial'] . '_' .
            date('Y-m-d_H-i-s') . '.xlsx';

        // Headers para descarga
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }



    // Agregar esta función a tu clase reporte_actividades

    public function generar_pdf_calificaciones($datos_calificaciones = null)
    {
        if (empty($datos_calificaciones)) {
            echo json_encode([
                'status' => 'error',
                'mensaje' => 'No hay datos para generar el PDF.'
            ]);
            return;
        }

        require_once(APPPATH . 'third_party/tcpdf/tcpdf.php');
        $primerAlumno = $datos_calificaciones[0];

        // Analizar estructura de actividades REAL de los datos
        $actividades = [];
        foreach ($datos_calificaciones as $alumno) {
            foreach ($alumno['actividades'] as $nombre_actividad => $datos_actividad) {
                if (!isset($actividades[$nombre_actividad])) {
                    $actividades[$nombre_actividad] = [
                        'practicas' => [],
                        'porcentaje' => null
                    ];

                    // Buscar todas las claves que no sean Cal_, Prom_, CalPond_
                    foreach ($datos_actividad as $key => $value) {
                        if (
                            strpos($key, 'Cal_') !== 0 &&
                            strpos($key, 'Prom_') !== 0 &&
                            strpos($key, 'CalPond_') !== 0
                        ) {
                            $actividades[$nombre_actividad]['practicas'][] = $key;
                        }
                    }

                    // Buscar porcentaje
                    foreach ($datos_actividad as $key => $value) {
                        if (strpos($key, 'CalPond_') === 0 && preg_match('/\((\d+(?:\.\d+)?)%\)/', $key, $matches)) {
                            $actividades[$nombre_actividad]['porcentaje'] = $matches[1];
                            break;
                        }
                    }
                }
            }
        }

        // Determinar orientación
        $total_columnas = 0;
        foreach ($actividades as $info) {
            $num_practicas = count($info['practicas']);
            $total_columnas += max(1, $num_practicas) + 2; // prácticas + promedio + valor
        }

        $horizontal = ($total_columnas > 6);

        // Crear PDF tamaño carta
        $pdf = new TCPDF($horizontal ? 'L' : 'P', 'mm', 'LETTER', true, 'UTF-8', false);
        $pdf->SetCreator('Sistema SYSMATER');
        $pdf->SetTitle('Reporte de Calificaciones');
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Paginación - MÁS estudiantes por página
        $estudiantes_por_pagina = $horizontal ? 40 : 35;
        $paginas = array_chunk($datos_calificaciones, $estudiantes_por_pagina);

        foreach ($paginas as $num_pagina => $estudiantes_pagina) {
            $pdf->AddPage();

            $ancho_pagina = $pdf->getPageWidth() - 20;
            $margen_izquierdo = 10; // Definir margen izquierdo constante

            // LOGO - Corregir proporción y posición
            $logo_w = $horizontal ? 120 : 100;
            $logo_h = $horizontal ? 25 : 20;
            $logo_x = ($pdf->getPageWidth() - $logo_w) / 2;
            $pdf->Image(base_url() . '/assets/img/logo_uthh_c.jpg', $logo_x, 8, $logo_w, $logo_h);
            $pdf->SetY($pdf->GetY() + $logo_h + 5);

            if ($num_pagina == 0) {
                // Tabla información completa - POSICIÓN FIJA Y ANCHO COMPLETO
                $y_actual = $pdf->GetY();
                $pdf->SetXY($margen_izquierdo, $y_actual);

                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->SetFillColor(217, 217, 217);

                $info_filas = [
                    ['Materia:', strtoupper($primerAlumno['Nombre_materia'])],
                    ['Docente:', strtoupper($primerAlumno['Docente'])],
                    ['Período:', $primerAlumno['Periodo']],
                    ['Evaluación:', 'PARCIAL ' . $primerAlumno['Parcial']],
                    ['Grado y Grupo:', $primerAlumno['Cuatrimestre'] . ' "' . $primerAlumno['Grupo'] . '"']
                ];

                foreach ($info_filas as $fila) {
                    $pdf->SetX($margen_izquierdo); // Posición X fija
                    $pdf->Cell(30, 6, $fila[0], 1, 0, 'L', true);
                    $pdf->SetFont('helvetica', '', 9);
                    $pdf->Cell($ancho_pagina - 30, 6, $fila[1], 1, 1, 'L');
                    $pdf->SetFont('helvetica', 'B', 9);
                }
                $pdf->Ln(5);
            }

            // CÁLCULO DINÁMICO DE ANCHOS - APROVECHA TODO EL ESPACIO
            $ancho_matricula = 25; // Más ancho para matrícula
            $ancho_nombre = $horizontal ? 70 : 75; // Más espacio para nombres
            $ancho_resultados = 35; // Más espacio para resultados
            $ancho_actividades = $ancho_pagina - $ancho_matricula - $ancho_nombre - $ancho_resultados;

            // Contar TOTAL de columnas de actividades
            $total_columnas_actividades = 0;
            $num_actividades = count($actividades);

            foreach ($actividades as $info) {
                $num_practicas = count($info['practicas']);
                if ($num_practicas > 1) {
                    $total_columnas_actividades += $num_practicas + 2; // prácticas + promedio + valor
                } else {
                    $total_columnas_actividades += 3; // cal + promedio + valor
                }
            }

            // DISTRIBUCIÓN PROPORCIONAL del espacio disponible
            $ancho_por_columna = $ancho_actividades / $total_columnas_actividades;

            // Anchos mínimos y máximos para mantener legibilidad
            $ancho_min_numero = 8;
            $ancho_max_numero = 15;
            $ancho_min_cal = 12;
            $ancho_max_cal = 25;
            $ancho_min_promedio = 15;
            $ancho_max_promedio = 30;
            $ancho_min_valor = 20;
            $ancho_max_valor = 40;

            // Aplicar límites
            $ancho_numero = max($ancho_min_numero, min($ancho_max_numero, $ancho_por_columna));
            $ancho_cal = max($ancho_min_cal, min($ancho_max_cal, $ancho_por_columna));
            $ancho_promedio = max($ancho_min_promedio, min($ancho_max_promedio, $ancho_por_columna * 1.2));
            $ancho_valor = max($ancho_min_valor, min($ancho_max_valor, $ancho_por_columna * 1.3));

            // Si hay pocas actividades, redistribuir el espacio extra
            if ($num_actividades <= 3) {
                $factor_expansion = 1.5;
                $ancho_numero *= $factor_expansion;
                $ancho_cal *= $factor_expansion;
                $ancho_promedio *= $factor_expansion;
                $ancho_valor *= $factor_expansion;

                // Recalcular para que no se salga del espacio
                $espacio_total_usado = 0;
                foreach ($actividades as $info) {
                    $num_practicas = count($info['practicas']);
                    if ($num_practicas > 1) {
                        $espacio_total_usado += ($num_practicas * $ancho_numero) + $ancho_promedio + $ancho_valor;
                    } else {
                        $espacio_total_usado += $ancho_cal + $ancho_promedio + $ancho_valor;
                    }
                }

                if ($espacio_total_usado > $ancho_actividades) {
                    $factor_ajuste = $ancho_actividades / $espacio_total_usado;
                    $ancho_numero *= $factor_ajuste;
                    $ancho_cal *= $factor_ajuste;
                    $ancho_promedio *= $factor_ajuste;
                    $ancho_valor *= $factor_ajuste;
                }
            }

            // ENCABEZADOS - TAMAÑO DE FUENTE DINÁMICO
            $y_tabla = $pdf->GetY();
            $pdf->SetXY($margen_izquierdo, $y_tabla);

            // Fuente más grande si hay pocas actividades
            $font_size = ($num_actividades <= 3) ? 8 : 7;
            $pdf->SetFont('helvetica', 'B', $font_size);
            $pdf->SetFillColor(39, 129, 4);
            $pdf->SetTextColor(255, 255, 255);

            // Altura de celdas dinámicas
            $altura_encabezado = ($num_actividades <= 3) ? 12 : 10;
            $altura_subencabezado = ($num_actividades <= 3) ? 6 : 5;

            // Primera fila encabezados
            $pdf->Cell($ancho_matricula, $altura_encabezado, 'MATRÍCULA', 1, 0, 'C', true);
            $pdf->Cell($ancho_nombre, $altura_encabezado, 'NOMBRE DEL ALUMNO', 1, 0, 'C', true);

            // Calcular posición X para actividades
            $x_actividades = $margen_izquierdo + $ancho_matricula + $ancho_nombre;
            $x_actual = $x_actividades;

            foreach ($actividades as $nombre => $info) {
                $num_practicas = count($info['practicas']);

                $ancho_total_actividad = 0;
                if ($num_practicas > 1) {
                    $ancho_total_actividad = ($num_practicas * $ancho_numero) + $ancho_promedio + $ancho_valor;
                } else {
                    $ancho_total_actividad = $ancho_cal + $ancho_promedio + $ancho_valor;
                }

                $pdf->Cell($ancho_total_actividad, $altura_subencabezado, strtoupper($nombre), 1, 0, 'C', true);
                $x_actual += $ancho_total_actividad;
            }
            $pdf->Cell($ancho_resultados, $altura_subencabezado, 'Resultados', 1, 1, 'C', true);

            // Segunda fila encabezados - POSICIÓN EXACTA
            $pdf->SetXY($x_actividades, $y_tabla + $altura_subencabezado); // Posición exacta después de matrícula y nombre

            foreach ($actividades as $nombre => $info) {
                $num_practicas = count($info['practicas']);

                if ($num_practicas > 1) {
                    // Múltiples prácticas - numerar con ancho compacto
                    for ($i = 1; $i <= $num_practicas; $i++) {
                        $pdf->Cell($ancho_numero, 5, $i, 1, 0, 'C', true);
                    }
                    $pdf->Cell($ancho_promedio, 5, 'Promedio', 1, 0, 'C', true);
                } else {
                    // Una sola calificación
                    $pdf->Cell($ancho_cal, 5, 'Cal', 1, 0, 'C', true);
                    $pdf->Cell($ancho_promedio, 5, 'Promedio', 1, 0, 'C', true);
                }

                $valor_texto = 'Valor ';
                if ($info['porcentaje']) {
                    $valor_texto .= $info['porcentaje'] . ' pts';
                } else {
                    $valor_texto .= 'pts';
                }
                $pdf->Cell($ancho_valor, 5, $valor_texto, 1, 0, 'C', true);
            }

            $pdf->Cell($ancho_resultados / 2, 5, 'Decimal', 1, 0, 'C', true);
            $pdf->Cell($ancho_resultados / 2, 5, 'Redondeo', 1, 1, 'C', true);

            // FILAS ESTUDIANTES - ASEGURAR ALINEACIÓN
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('helvetica', '', 6);

            $par = true;
            foreach ($estudiantes_pagina as $alumno) {
                $pdf->SetFillColor($par ? 232 : 255, $par ? 245 : 255, $par ? 232 : 255);

                // FIJAR POSICIÓN X AL INICIO DE CADA FILA
                $pdf->SetX($margen_izquierdo);

                $pdf->Cell($ancho_matricula, 5, $alumno['Matricula'], 1, 0, 'C', true);
                $nombre_truncado = strlen($alumno['Nombre_Alumno']) > 32 ?
                    substr($alumno['Nombre_Alumno'], 0, 29) . '...' : $alumno['Nombre_Alumno'];
                $pdf->Cell($ancho_nombre, 5, strtoupper($nombre_truncado), 1, 0, 'L', true);

                foreach ($actividades as $nombre => $info) {
                    $datos = isset($alumno['actividades'][$nombre]) ? $alumno['actividades'][$nombre] : [];
                    $num_practicas = count($info['practicas']);

                    if ($num_practicas > 1) {
                        // Mostrar cada práctica con ancho compacto
                        foreach ($info['practicas'] as $practica) {
                            $valor = isset($datos[$practica]) && $datos[$practica] !== '' ?
                                intval($datos[$practica]) : 0;
                            $pdf->Cell($ancho_numero, 5, $valor, 1, 0, 'C', true);
                        }

                        // Promedio
                        $prom_key = 'Prom_' . $nombre;
                        $promedio = isset($datos[$prom_key]) ? $datos[$prom_key] : 0;
                        $pdf->Cell($ancho_promedio, 5, $promedio, 1, 0, 'C', true);
                    } else {
                        // Una calificación
                        $cal_key = 'Cal_' . $nombre;
                        $valor = isset($datos[$cal_key]) ? $datos[$cal_key] : 0;
                        $pdf->Cell($ancho_cal, 5, $valor, 1, 0, 'C', true);
                        $pdf->Cell($ancho_promedio, 5, $valor, 1, 0, 'C', true);
                    }

                    // Valor ponderado
                    $ponderado = 0;
                    foreach ($datos as $key => $val) {
                        if (strpos($key, 'CalPond_') === 0) {
                            $ponderado = $val;
                            break;
                        }
                    }
                    $pdf->Cell($ancho_valor, 5, number_format($ponderado, 1), 1, 0, 'C', true);
                }

                // Resultados
                $final = $alumno['Calificacion Final'];
                $pdf->Cell($ancho_resultados / 2, 5, number_format($final, 1), 1, 0, 'C', true);
                $pdf->Cell($ancho_resultados / 2, 5, round($final), 1, 1, 'C', true);

                $par = !$par;
            }

            // Firma en última página
            if ($num_pagina == count($paginas) - 1) {
                $pdf->Ln(10);
                $pdf->SetFont('helvetica', 'B', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell(0, 8, 'Nombre y firma del Jefe de Grupo', 0, 1, 'C');
                $pdf->Ln(5);
                $pdf->Cell(0, 8, '________________________________________________', 0, 1, 'C');
            }
        }

        $filename = 'Reporte_' . $primerAlumno['Clave_Materia'] . '_' .
            $primerAlumno['Grupo'] . '_P' . $primerAlumno['Parcial'] . '_' .
            date('Y-m-d_H-i-s') . '.pdf';

        $pdf->Output($filename, 'D');
        exit();
    }
}
