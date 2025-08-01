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
                                $valor = isset($datos_actividad[$practica_key]) ? $datos_actividad[$practica_key] : '';
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

        // Cargar librería TCPDF
        require_once(APPPATH . 'third_party/tcpdf/tcpdf.php');

        // Obtener datos del primer alumno
        $primerAlumno = $datos_calificaciones[0];

        // Crear instancia de TCPDF en orientación horizontal
        $pdf = new TCPDF('L', PDF_UNIT, 'LETTER', true, 'UTF-8', false);

        // Configuración del documento
        $pdf->SetCreator('Sistema SYSMATER');
        $pdf->SetAuthor('Universidad Tecnológica de la Huasteca Hidalguense');
        $pdf->SetTitle('Reporte de Calificaciones');
        $pdf->SetSubject('Reporte Académico');

        // Configurar márgenes
        $pdf->SetMargins(10, 15, 10);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(5);

        // Desactivar header y footer automáticos
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Agregar página
        $pdf->AddPage();

        // Configurar fuente
        $pdf->SetFont('helvetica', 'B', 12);

        // Encabezado principal
        $pdf->Cell(0, 8, 'UNIVERSIDAD TECNOLÓGICA DE LA', 0, 1, 'C');
        $pdf->Cell(0, 8, 'HUASTECA HIDALGUENSE', 0, 1, 'C');
        $pdf->Ln(5);

        // Crear tabla de información
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetFillColor(217, 217, 217); // Gris claro

        // Configurar dimensiones de la tabla de información
        $label_width = 25;
        $content_width = 235; // Ancho total menos el label

        // Fila 1: Materia
        $pdf->Cell($label_width, 7, 'Materia:', 1, 0, 'L', true);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell($content_width, 7, strtoupper($primerAlumno['Nombre_materia']), 1, 1, 'L');

        // Fila 2: Docente
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell($label_width, 7, 'Docente:', 1, 0, 'L', true);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell($content_width, 7, strtoupper($primerAlumno['Docente']), 1, 1, 'L');

        // Fila 3: Período
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell($label_width, 7, 'Período:', 1, 0, 'L', true);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell($content_width, 7, $primerAlumno['Periodo'], 1, 1, 'L');

        // Fila 4: Evaluación
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell($label_width, 7, 'Evaluación:', 1, 0, 'L', true);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell($content_width, 7, 'PARCIAL ' . $primerAlumno['Parcial'], 1, 1, 'L');

        // Fila 5: Grado y Grupo
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell($label_width, 7, 'Grado y Grupo:', 1, 0, 'L', true);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell($content_width, 7, $primerAlumno['Cuatrimestre'] . ' "' . $primerAlumno['Grupo'] . '"', 1, 1, 'L');

        $pdf->Ln(5);

        // Analizar actividades para crear la estructura de la tabla
        $actividades = [];
        $columnas_por_actividad = [];

        // Obtener todas las actividades únicas y sus estructuras
        foreach ($datos_calificaciones as $alumno) {
            foreach ($alumno['actividades'] as $nombre_actividad => $datos_actividad) {
                if (!isset($actividades[$nombre_actividad])) {
                    $actividades[$nombre_actividad] = [];

                    // Contar prácticas
                    $actividades[$nombre_actividad]['practicas'] = [];
                    foreach ($datos_actividad as $key => $value) {
                        if (strpos($key, ' - Practica') !== false || strpos($key, ' - ') !== false) {
                            $actividades[$nombre_actividad]['practicas'][] = $key;
                        }
                    }

                    // Determinar si tiene calificación ponderada y extraer porcentaje
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

        // Calcular anchos de columnas dinámicamente
        $ancho_matricula = 20;
        $ancho_nombre = 45;
        $ancho_total_disponible = 260 - $ancho_matricula - $ancho_nombre - 20; // 20 para resultados
        $num_actividades = count($actividades);

        // Calcular columnas totales necesarias para actividades
        $total_columnas_actividades = 0;
        foreach ($actividades as $info_actividad) {
            $num_practicas = count($info_actividad['practicas']);
            if ($num_practicas > 0) {
                $total_columnas_actividades += $num_practicas + 2; // prácticas + promedio + valor pts
            } else {
                $total_columnas_actividades += 2; // cal + valor pts
            }
        }

        $ancho_columna_actividad = $total_columnas_actividades > 0 ? $ancho_total_disponible / $total_columnas_actividades : 10;
        if ($ancho_columna_actividad < 8) $ancho_columna_actividad = 8;

        // Crear encabezados de la tabla principal
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetFillColor(39, 129, 4); // Verde
        $pdf->SetTextColor(255, 255, 255); // Texto blanco

        // Primera fila de encabezados (nombres de actividades)
        $y_pos = $pdf->GetY();

        // Matrícula y Nombre (se extienden 2 filas)
        $pdf->Cell($ancho_matricula, 14, 'MATRÍCULA', 1, 0, 'C', true);
        $pdf->Cell($ancho_nombre, 14, 'NOMBRE DEL ALUMNO', 1, 0, 'C', true);

        // Encabezados de actividades
        foreach ($actividades as $nombre_actividad => $info_actividad) {
            $num_practicas = count($info_actividad['practicas']);
            $columnas_actividad = ($num_practicas > 0) ? $num_practicas + 2 : 2;
            $ancho_total_actividad = $ancho_columna_actividad * $columnas_actividad;

            $pdf->Cell($ancho_total_actividad, 7, strtoupper($nombre_actividad), 1, 0, 'C', true);
        }

        // Resultados
        $pdf->Cell(20, 7, 'Resultados', 1, 1, 'C', true);

        // Segunda fila de encabezados (sub-columnas)
        $pdf->SetX(10 + $ancho_matricula + $ancho_nombre); // Posicionarse después de matrícula y nombre

        foreach ($actividades as $nombre_actividad => $info_actividad) {
            $num_practicas = count($info_actividad['practicas']);

            if ($num_practicas > 0) {
                // Números de prácticas
                for ($i = 1; $i <= $num_practicas; $i++) {
                    $pdf->Cell($ancho_columna_actividad, 7, $i, 1, 0, 'C', true);
                }
                // Promedio
                $pdf->Cell($ancho_columna_actividad, 7, 'Promedio', 1, 0, 'C', true);
            } else {
                // Solo Cal
                $pdf->Cell($ancho_columna_actividad, 7, 'Cal', 1, 0, 'C', true);
            }

            // Valor pts
            $valor_text = 'Valor ';
            if (isset($info_actividad['porcentaje'])) {
                $valor_text .= $info_actividad['porcentaje'] . ' pts';
            } else {
                $valor_text .= 'pts';
            }
            $pdf->Cell($ancho_columna_actividad, 7, $valor_text, 1, 0, 'C', true);
        }

        // Sub-encabezados de Resultados
        $pdf->Cell(10, 7, 'Decimal', 1, 0, 'C', true);
        $pdf->Cell(10, 7, 'Redondeo', 1, 1, 'C', true);

        // Datos de los estudiantes
        $pdf->SetTextColor(0, 0, 0); // Texto negro
        $pdf->SetFont('helvetica', '', 7);

        $fila_par = true;
        foreach ($datos_calificaciones as $alumno) {
            // Alternar colores de fila
            if ($fila_par) {
                $pdf->SetFillColor(232, 245, 232); // Verde muy claro
            } else {
                $pdf->SetFillColor(255, 255, 255); // Blanco
            }

            // Matrícula
            $pdf->Cell($ancho_matricula, 8, $alumno['Matricula'], 1, 0, 'C', true);

            // Nombre (alineado a la izquierda)
            $pdf->Cell($ancho_nombre, 8, strtoupper($alumno['Nombre_Alumno']), 1, 0, 'L', true);


            // Datos de actividades
            foreach ($actividades as $nombre_actividad => $info_actividad) {
                $datos_actividad = isset($alumno['actividades'][$nombre_actividad]) ? $alumno['actividades'][$nombre_actividad] : [];
                $num_practicas = count($info_actividad['practicas']);

                if ($num_practicas > 0) {
                    // Mostrar prácticas
                    foreach ($info_actividad['practicas'] as $nombre_practica) {
                        $valor = isset($datos_actividad[$nombre_practica]) ? $datos_actividad[$nombre_practica] : '';
                        $pdf->Cell($ancho_columna_actividad, 8, $valor, 1, 0, 'C', true);
                    }

                    // Promedio (Cal_)
                    $cal_key = 'Prom_' . $nombre_actividad;
                    $calificacion = isset($datos_actividad[$cal_key]) ? $datos_actividad[$cal_key] : 0;
                    $pdf->Cell($ancho_columna_actividad, 8, $calificacion, 1, 0, 'C', true);
                } else {
                    // Solo Cal
                    $cal_key = 'Cal_' . $nombre_actividad;
                    $calificacion = isset($datos_actividad[$cal_key]) ? $datos_actividad[$cal_key] : 0;
                    $pdf->Cell($ancho_columna_actividad, 8, $calificacion, 1, 0, 'C', true);
                }

                // Valor ponderado (CalPond_)
                $valor_ponderado = 0;
                foreach ($datos_actividad as $key => $value) {
                    if (strpos($key, 'CalPond_') === 0) {
                        $valor_ponderado = $value;
                        break;
                    }
                }
                $pdf->Cell($ancho_columna_actividad, 8, number_format($valor_ponderado, 1), 1, 0, 'C', true);
            }

            // Resultados
            $calificacion_final = $alumno['Calificacion Final'];
            $pdf->Cell(10, 8, number_format($calificacion_final, 1), 1, 0, 'C', true);
            $pdf->Cell(10, 8, round($calificacion_final), 1, 1, 'C', true);

            $fila_par = !$fila_par;
        }

        // Línea para firma
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(0, 8, 'Nombre y firma del Jefe de Grupo', 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->Cell(0, 8, '________________________________________________', 0, 1, 'C');

        // Generar nombre del archivo
        $filename = 'Reporte_' . $primerAlumno['Clave_Materia'] . '_' .
            $primerAlumno['Grupo'] . '_P' . $primerAlumno['Parcial'] . '_' .
            date('Y-m-d_H-i-s') . '.pdf';

        // Salida del PDF
        $pdf->Output($filename, 'D'); // 'D' para descarga directa
        exit();
    }
}
