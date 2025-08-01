<?php
defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Reporte_examen_parciales extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('/SYSMATER/shared/reporte_model');
		$this->load->model('/SYSMATER/shared/examen_model');
		$this->load->model('/SYSMATER/shared/docente_model');
		$this->load->model('/SYSMATER/shared/materia_model');
	}

	public function index()
	{
		if ($this->session->id_tipo == 4) {

			$this->validation();
			
			$vchClvTrabajador = $this->session->Matricula;
			$periodo = $this->determinarPeriodo();
			$data['materias'] = $this->docente_model->lista_docentes_asignacion($vchClvTrabajador, $periodo);

			if ($this->form_validation->run() == FALSE) {

				if ($data['materias'] === NULL) {
					$this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
					$this->load->view("templates/views_sysmater/docente_views/Sin_asignacion");
					$this->load->view("templates/shared/footer_view");
				} else {
					$this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
					$this->load->view("templates/views_sysmater/docente_views/reporte_examen_parciales_view", $data);
					$this->load->view("templates/shared/footer_view");
				}
			} else {
				$tipo = $this->input->post('tipo');

				if ($tipo == "excel" || $tipo == "pdf") {
					$materia = $this->input->post("materia");
					$periodo = $this->determinarPeriodo();
					$vchClvTrabajador = $this->session->Matricula;
					$grupos= $this->input->post('grupo');
					$this->exportar($tipo, $materia, $periodo,$vchClvTrabajador,$grupos);
				}
			}
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

	private function validation()
	{
		$config = array(
			array(
				'field' => 'materia',
				'label' => 'materia',
				'rules' => 'trim|required'
			)
		);
		$this->form_validation->set_rules($config);
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

	private function exportar($tipo, $materia, $periodo,$vchClvTrabajador,$grupos)
	{
		if ($grupos === 'TODOS' || $grupos === '00') {
			$datosTodosAlumnos = $this->reporte_model->obtener_resultados_parciales_materia_docente($materia, $periodo,$vchClvTrabajador);
			
			if ($datosTodosAlumnos !== NULL) {

				$datosTodosDocentes = $this->reporte_model->obtener_resultados_materia_docente($materia, $periodo,$vchClvTrabajador);

				if ($tipo == "excel") {
					$this->crearExcel($datosTodosAlumnos, $datosTodosDocentes);
				} elseif ($tipo == "pdf") {
					$this->crearPDF($datosTodosAlumnos, $datosTodosDocentes);
				}

			} else {
				echo '<script type="text/javascript">';
				echo 'alert("No se encontraron registros");';
				echo 'window.location.href = "'.site_url('sysmater/docente/docente/reporte_examen_parciales').'";';
				echo '</script>';
				exit();
			}
		}else {
			$datosTodosAlumnos = $this->reporte_model->obtener_resultados_parciales_materia_docente_grupo($materia, $periodo,$vchClvTrabajador,$grupos);
			
			if ($datosTodosAlumnos !== NULL) {

				$datosTodosDocentes = $this->reporte_model->obtener_resultados_materia_docente($materia, $periodo,$vchClvTrabajador);

				if ($tipo == "excel") {
					$this->crearExcelGrupo($datosTodosAlumnos, $datosTodosDocentes);
				} elseif ($tipo == "pdf") {
					$this->crearPDFGrupo($datosTodosAlumnos, $datosTodosDocentes);
				}

			} else {
				echo '<script type="text/javascript">';
				echo 'alert("No se encontraron registros");';
				echo 'window.location.href = "'.site_url('sysmater/docente/docente/reporte_examen_parciales').'";';
				echo '</script>';
				exit();
			}
		}
		
	}

	private function crearPDF($datos, $datosTodosDocentes)
	{

		$this->load->library('cPDF');

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LETTER', true, 'UTF-8', false);
		$pdf->AddPage();

		// Obtener el ancho de la página
		$pageWidth = $pdf->getPageWidth();
		$imageWidth = 150; // nuevo ancho de la imagen
		$imageHeight = 18; // nuevo alto de la imagen
		$x = ($pageWidth - $imageWidth) / 2;
		$y = ''; // La posición y permanece la misma
		$pdf->Image(base_url() . '/assets/img/logo_uthh_c.jpg', $x, $y, $imageWidth, $imageHeight, '', '', '', false, 300, '', false, false, 0, 'CM', false, false);

		if (!empty($datos) || $datos !== NULL) {
			$primer_elemento = $datos[0];

			$filename = $primer_elemento['vchNomMateria'] .'_'. $primer_elemento['periodo'];

			// Añadir títulos e información subrayada
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Ln(20);

			// Materia
			$pdf->Cell(20, 7, 'Materia: ', 1);
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Cell(40, 7, $primer_elemento['vchNomMateria'], 'B');
			$pdf->Cell(40, 7, ' ', 'B');
			$pdf->Cell(40, 7, ' ', 'B');
			$pdf->Ln();

			foreach ($datosTodosDocentes as $docente) {
				$pdf->Cell(20, 7, 'Docente: ', 1);
				$pdf->SetFont('helvetica', 'B', 12);
				$pdf->Cell(40, 7, $docente['NombreCompletoDocente'], 'B');
				$pdf->Cell(40, 7, ' ', 'B');
				$pdf->Cell(40, 7, ' ', 'B');
				$pdf->Ln();
			}

			// Periodo
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Cell(20, 7, 'Periodo: ', 1);
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Cell(40, 7, $primer_elemento['periodo'], 'B');

			// Parcial
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Cell(25, 7, 'Parciales: ', 1);
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Cell(40, 7, '1,2,3', 'B');
			$pdf->Cell(20, 7, ' ', 'B');
			$pdf->Ln();
			$pdf->Ln();

			// Función para agregar el encabezado de la tabla
			function addTableHeader($pdf)
			{
				$pdf->SetFillColor(0, 152, 68); // Color de fondo verde oscuro
				$pdf->SetTextColor(0, 0, 0); // Color de texto blanco
				$pdf->SetFont('helvetica', 'B', 10);
				$pdf->Cell(10, 6, 'N.O', 1, 0, 'C', true);
				$pdf->Cell(30, 6, 'MATRICULA', 1, 0, 'C', true);
				$pdf->Cell(80, 6, 'NOMBRE DEL ALUMNO', 1, 0, 'C', true);
				$pdf->Cell(20, 6, 'GRUPO', 1, 0, 'C', true);
				$pdf->Cell(15, 6, 'P1', 1, 0, 'C', true);
				$pdf->Cell(15, 6, 'P2', 1, 0, 'C', true);
				$pdf->Cell(15, 6, 'P3', 1, 0, 'C', true);
				$pdf->Ln();
			}

			// Agregar el encabezado de la tabla en la primera página
			addTableHeader($pdf);

			// Imprimir los datos de los alumnos
			$pdf->SetFont('helvetica', '', 9);
			$numero = 1;
			$fill = false; // Variable para alternar colores de fondo
			$numRowsPerPage = 35; // Número de filas por página

			foreach ($datos as $index => $row) {
				// Añadir una nueva página si se excede el límite de filas por página
				if ($index > 0 && $index % $numRowsPerPage == 0) {
					$pdf->AddPage();
					// Agregar encabezado en la nueva página
					addTableHeader($pdf);
				}

				$nombre_completo = $row['NombreCompleto'];

				// Formatear calificación a dos decimales o dejar espacio en blanco si es null
				$calificacion_formateada1 = is_null($row['calificacion_parcial_1']) ? '' : number_format($row['calificacion_parcial_1'], 2);
				$calificacion_formateada2 = is_null($row['calificacion_parcial_2']) ? '' : number_format($row['calificacion_parcial_2'], 2);
				$calificacion_formateada3 = is_null($row['calificacion_parcial_3']) ? '' : number_format($row['calificacion_parcial_3'], 2);

				// Alternar color de fondo
				if ($fill) {
					$pdf->SetFillColor(226, 239, 218); // Color verde claro
				} else {
					$pdf->SetFillColor(169, 208, 142); // Color verde más oscuro
				}

				$pdf->Cell(10, 6, $numero, 1, 0, 'C', true);
				$pdf->Cell(30, 6, $row['vchMatricula'], 1, 0, '', true);
				$pdf->Cell(80, 6, $nombre_completo, 1, 0, '', true);
				$pdf->Cell(20, 6, $row['chvGrupo'], 1, 0, 'C', true);
				$pdf->Cell(15, 6, $calificacion_formateada1, 1, 0, 'C', true);
				$pdf->Cell(15, 6, $calificacion_formateada2, 1, 0, 'C', true);
				$pdf->Cell(15, 6, $calificacion_formateada3, 1, 0, 'C', true);
				$pdf->Ln();

				$fill = !$fill; // Alternar el valor de $fill
				$numero++;
			}
		} else {
			$pdf->Cell(0, 6, 'No se encontraron resultados', 1);
			$filename = 'No se encontraron resultados';
		}
		// Salida del PDF
		$pdf->Output($filename . '.pdf', 'D');
		exit;
	}


	private function crearExcel($datos, $datosTodosDocentes)
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		// Configurar estilos
		$TitulosStyle = [
			'font' => [
				'bold' => true,
				'size' => 12,
				'color' => ['rgb' => '000000'],
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER,
				'vertical' => Alignment::VERTICAL_CENTER,
			],
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
			],
			'borders' => [
				'allBorders' => [
					'borderStyle' => Border::BORDER_THIN,
					'color' => ['rgb' => '000000'],
				],
			],
		];

		$DatosTitulo = [
			'font' => [
				'bold' => true,
				'size' => 12,
				'color' => ['rgb' => '000000'],
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER,
				'vertical' => Alignment::VERTICAL_CENTER,
			],
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
			],
			'borders' => [
				'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
			],
		];

		$Encabezado = [
			'font' => [
				'bold' => true,
				'size' => 12,
				'color' => ['rgb' => '000000'],
			],
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
				'startColor' => ['rgb' => '009844'],
			],
			'borders' => [
				'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
			],
		];

		$dataRowStyle1 = [
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
				'startColor' => ['rgb' => 'A9D08E'],
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER,
				'vertical' => Alignment::VERTICAL_CENTER,
			],
			'borders' => [
				'allBorders' => [
					'borderStyle' => Border::BORDER_THIN,
					'color' => ['rgb' => '000000'],
				],
			],
		];

		$dataRowStyle2 = [
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
				'startColor' => ['rgb' => 'E2EFDA'],
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER,
				'vertical' => Alignment::VERTICAL_CENTER,
			],
			'borders' => [
				'allBorders' => [
					'borderStyle' => Border::BORDER_THIN,
					'color' => ['rgb' => '000000'],
				],
			],
		];

		// Añadir logotipo
		$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
		$drawing->setName('Logo');
		$drawing->setDescription('Logo');
		$drawing->setPath('./assets/img/logo_uthh_completo.png'); // Ruta del logotipo
		$drawing->setHeight(56);
		$drawing->setCoordinates('C1');
		$drawing->setWorksheet($sheet);

		if (!empty($datos) || $datos !== NULL) {

			// Añadir título estático "Materia:" en negritas
			$sheet->setCellValue('A4', 'Materia:');
			$sheet->getStyle('A4')->applyFromArray($TitulosStyle);
			$sheet->setCellValue('B4', $datos[0]['vchNomMateria']);
			$sheet->mergeCells('B4:E4');
			$sheet->getStyle('B4:E4')->applyFromArray($DatosTitulo);

			$rowNumDocentes = 5;

			foreach ($datosTodosDocentes as $docente) {

				$sheet->setCellValue("A$rowNumDocentes", 'Docente:');
				$sheet->getStyle("A$rowNumDocentes")->applyFromArray($TitulosStyle);
				$sheet->setCellValue("B$rowNumDocentes", $docente['NombreCompletoDocente']);
				$sheet->mergeCells("B$rowNumDocentes:E$rowNumDocentes");
				$sheet->getStyle("B$rowNumDocentes:E$rowNumDocentes")->applyFromArray($DatosTitulo);
				$rowNumDocentes++;
			}


			$sheet->setCellValue("A$rowNumDocentes", 'Periodo:');
			$sheet->getStyle("A$rowNumDocentes")->applyFromArray($TitulosStyle);
			$sheet->setCellValue("B$rowNumDocentes", $datos[0]['periodo']);
			$sheet->mergeCells("B$rowNumDocentes:C$rowNumDocentes");
			$sheet->getStyle("B$rowNumDocentes:C$rowNumDocentes")->applyFromArray($DatosTitulo);
			$rowNumDocentes++;
			$rowNumDocentes++;


			// Añadir encabezados
			$sheet->setCellValue("A$rowNumDocentes", 'N.O');
			$sheet->setCellValue("B$rowNumDocentes", 'MATRICULA');
			$sheet->setCellValue("C$rowNumDocentes", 'NOMBRE DEL ALUMNO');
			$sheet->setCellValue("D$rowNumDocentes", 'GRUPO');
			$sheet->setCellValue("E$rowNumDocentes", 'PARCIAL 1');
			$sheet->setCellValue("F$rowNumDocentes", 'PARCIAL 2');
			$sheet->setCellValue("G$rowNumDocentes", 'PARCIAL 3');
			$sheet->getStyle("A$rowNumDocentes:G$rowNumDocentes")->applyFromArray($Encabezado);
			$rowNumDocentes++;
		

			// Añadir datos
			$rowNum = $rowNumDocentes;
			$numRowsPerPage = 35;
			$numero = 1;

			foreach ($datos as $index => $row) {
				if ($index > 0 && $index % $numRowsPerPage == 0) {
					// Añadir encabezados en la nueva página
					$rowNum += 2; // Añadir espacio entre páginas
					$sheet->setCellValue("A$rowNum", 'N.O');
					$sheet->setCellValue("B$rowNum", 'MATRICULA');
					$sheet->setCellValue("C$rowNum", 'NOMBRE DEL ALUMNO');
					$sheet->setCellValue("D$rowNum", 'GRUPO');
					$sheet->setCellValue("E$rowNum", 'PARCIAL 1');
					$sheet->setCellValue("F$rowNum", 'PARCIAL 2');
					$sheet->setCellValue("G$rowNum", 'PARCIAL 3');
					$sheet->getStyle("A$rowNum:G$rowNum")->applyFromArray($Encabezado);
					$rowNum++;
				}

				$nombre_completo = $row['NombreCompleto'] ;

				$calificacion_formateada1 = is_null($row['calificacion_parcial_1']) ? '' : number_format($row['calificacion_parcial_1'], 2);
				$calificacion_formateada2 = is_null($row['calificacion_parcial_2']) ? '' : number_format($row['calificacion_parcial_2'], 2);
				$calificacion_formateada3 = is_null($row['calificacion_parcial_3']) ? '' : number_format($row['calificacion_parcial_3'], 2);

				// Alternar color de fondo
				$rowStyle = ($index % 2 == 0) ? $dataRowStyle1 : $dataRowStyle2;

				$sheet->setCellValue("A$rowNum", $numero);
				$sheet->setCellValue("B$rowNum", $row['vchMatricula']);
				$sheet->setCellValue("C$rowNum", $nombre_completo);
				$sheet->setCellValue("D$rowNum", $row['chvGrupo']);
				$sheet->setCellValue("E$rowNum", $calificacion_formateada1);
				$sheet->setCellValue("F$rowNum", $calificacion_formateada2);
				$sheet->setCellValue("G$rowNum", $calificacion_formateada3);

				// Aplicar estilo de fila
				$sheet->getStyle("A$rowNum:G$rowNum")->applyFromArray($rowStyle);

				$rowNum++;
				$numero++;
			}

			// Establecer tamaños de columna
			foreach (range('A', 'G') as $col) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}

			// Salida del Excel
			$filename = $datos[0]['vchNomMateria'] .'_' .$datos[0]['periodo'];
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
			header('Cache-Control: max-age=0');
		} else {

			$sheet->setCellValue('A4', 'NO SE ENCONTRARON DATOS');
			$sheet->getStyle('A4')->applyFromArray($TitulosStyle);

			// Salida del Excel
			$filename = "DATOS_NO_ENCONTRADOS";
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
			header('Cache-Control: max-age=0');
		}


		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
		exit;
	}

	private function crearPDFGrupo($datos, $datosTodosDocentes)
	{

		$this->load->library('cPDF');

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LETTER', true, 'UTF-8', false);
		$pdf->AddPage();

		// Obtener el ancho de la página
		$pageWidth = $pdf->getPageWidth();
		$imageWidth = 150; // nuevo ancho de la imagen
		$imageHeight = 18; // nuevo alto de la imagen
		$x = ($pageWidth - $imageWidth) / 2;
		$y = ''; // La posición y permanece la misma
		$pdf->Image(base_url() . '/assets/img/logo_uthh_c.jpg', $x, $y, $imageWidth, $imageHeight, '', '', '', false, 300, '', false, false, 0, 'CM', false, false);

		if (!empty($datos) || $datos !== NULL) {
			$primer_elemento = $datos[0];

			$filename = $primer_elemento['vchNomMateria'] .'_'. $primer_elemento['periodo']. '_'.$primer_elemento['chvGrupo'];

			// Añadir títulos e información subrayada
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Ln(20);

			// Materia
			$pdf->Cell(20, 7, 'Materia: ', 1);
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Cell(40, 7, $primer_elemento['vchNomMateria'], 'B');
			$pdf->Cell(40, 7, ' ', 'B');
			$pdf->Cell(40, 7, ' ', 'B');
			$pdf->Ln();

			foreach ($datosTodosDocentes as $docente) {
				$pdf->Cell(20, 7, 'Docente: ', 1);
				$pdf->SetFont('helvetica', 'B', 12);
				$pdf->Cell(40, 7, $docente['NombreCompletoDocente'], 'B');
				$pdf->Cell(40, 7, ' ', 'B');
				$pdf->Cell(40, 7, ' ', 'B');
				$pdf->Ln();
			}

			// Periodo
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Cell(20, 7, 'Periodo: ', 1);
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Cell(40, 7, $primer_elemento['periodo'], 'B');

			// Parcial
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Cell(25, 7, 'Parciales: ', 1);
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Cell(40, 7, '1,2,3', 'B');
			$pdf->Cell(20, 7, ' ', 'B');
			$pdf->Ln();
			$pdf->Ln();

			// Función para agregar el encabezado de la tabla
			function addTableHeader($pdf)
			{
				$pdf->SetFillColor(0, 152, 68); // Color de fondo verde oscuro
				$pdf->SetTextColor(0, 0, 0); // Color de texto blanco
				$pdf->SetFont('helvetica', 'B', 10);
				$pdf->Cell(10, 6, 'N.O', 1, 0, 'C', true);
				$pdf->Cell(30, 6, 'MATRICULA', 1, 0, 'C', true);
				$pdf->Cell(80, 6, 'NOMBRE DEL ALUMNO', 1, 0, 'C', true);
				$pdf->Cell(20, 6, 'GRUPO', 1, 0, 'C', true);
				$pdf->Cell(15, 6, 'P1', 1, 0, 'C', true);
				$pdf->Cell(15, 6, 'P2', 1, 0, 'C', true);
				$pdf->Cell(15, 6, 'P3', 1, 0, 'C', true);
				$pdf->Ln();
			}

			// Agregar el encabezado de la tabla en la primera página
			addTableHeader($pdf);

			// Imprimir los datos de los alumnos
			$pdf->SetFont('helvetica', '', 9);
			$numero = 1;
			$fill = false; // Variable para alternar colores de fondo
			$numRowsPerPage = 35; // Número de filas por página

			foreach ($datos as $index => $row) {
				// Añadir una nueva página si se excede el límite de filas por página
				if ($index > 0 && $index % $numRowsPerPage == 0) {
					$pdf->AddPage();
					// Agregar encabezado en la nueva página
					addTableHeader($pdf);
				}

				$nombre_completo = $row['NombreCompleto'];

				// Formatear calificación a dos decimales o dejar espacio en blanco si es null
				$calificacion_formateada1 = is_null($row['calificacion_parcial_1']) ? '' : number_format($row['calificacion_parcial_1'], 2);
				$calificacion_formateada2 = is_null($row['calificacion_parcial_2']) ? '' : number_format($row['calificacion_parcial_2'], 2);
				$calificacion_formateada3 = is_null($row['calificacion_parcial_3']) ? '' : number_format($row['calificacion_parcial_3'], 2);

				// Alternar color de fondo
				if ($fill) {
					$pdf->SetFillColor(226, 239, 218); // Color verde claro
				} else {
					$pdf->SetFillColor(169, 208, 142); // Color verde más oscuro
				}

				$pdf->Cell(10, 6, $numero, 1, 0, 'C', true);
				$pdf->Cell(30, 6, $row['vchMatricula'], 1, 0, '', true);
				$pdf->Cell(80, 6, $nombre_completo, 1, 0, '', true);
				$pdf->Cell(20, 6, $row['chvGrupo'], 1, 0, 'C', true);
				$pdf->Cell(15, 6, $calificacion_formateada1, 1, 0, 'C', true);
				$pdf->Cell(15, 6, $calificacion_formateada2, 1, 0, 'C', true);
				$pdf->Cell(15, 6, $calificacion_formateada3, 1, 0, 'C', true);
				$pdf->Ln();

				$fill = !$fill; // Alternar el valor de $fill
				$numero++;
			}
		} else {
			$pdf->Cell(0, 6, 'No se encontraron resultados', 1);
			$filename = 'No se encontraron resultados';
		}
		// Salida del PDF
		$pdf->Output($filename . '.pdf', 'D');
		exit;
	}
	private function crearExcelGrupo($datos, $datosTodosDocentes)
	{
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		// Configurar estilos
		$TitulosStyle = [
			'font' => [
				'bold' => true,
				'size' => 12,
				'color' => ['rgb' => '000000'],
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER,
				'vertical' => Alignment::VERTICAL_CENTER,
			],
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
			],
			'borders' => [
				'allBorders' => [
					'borderStyle' => Border::BORDER_THIN,
					'color' => ['rgb' => '000000'],
				],
			],
		];

		$DatosTitulo = [
			'font' => [
				'bold' => true,
				'size' => 12,
				'color' => ['rgb' => '000000'],
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER,
				'vertical' => Alignment::VERTICAL_CENTER,
			],
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
			],
			'borders' => [
				'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
			],
		];

		$Encabezado = [
			'font' => [
				'bold' => true,
				'size' => 12,
				'color' => ['rgb' => '000000'],
			],
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
				'startColor' => ['rgb' => '009844'],
			],
			'borders' => [
				'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
			],
		];

		$dataRowStyle1 = [
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
				'startColor' => ['rgb' => 'A9D08E'],
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER,
				'vertical' => Alignment::VERTICAL_CENTER,
			],
			'borders' => [
				'allBorders' => [
					'borderStyle' => Border::BORDER_THIN,
					'color' => ['rgb' => '000000'],
				],
			],
		];

		$dataRowStyle2 = [
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
				'startColor' => ['rgb' => 'E2EFDA'],
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER,
				'vertical' => Alignment::VERTICAL_CENTER,
			],
			'borders' => [
				'allBorders' => [
					'borderStyle' => Border::BORDER_THIN,
					'color' => ['rgb' => '000000'],
				],
			],
		];

		// Añadir logotipo
		$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
		$drawing->setName('Logo');
		$drawing->setDescription('Logo');
		$drawing->setPath('./assets/img/logo_uthh_completo.png'); // Ruta del logotipo
		$drawing->setHeight(56);
		$drawing->setCoordinates('C1');
		$drawing->setWorksheet($sheet);

		if (!empty($datos) || $datos !== NULL) {

			// Añadir título estático "Materia:" en negritas
			$sheet->setCellValue('A4', 'Materia:');
			$sheet->getStyle('A4')->applyFromArray($TitulosStyle);
			$sheet->setCellValue('B4', $datos[0]['vchNomMateria']);
			$sheet->mergeCells('B4:E4');
			$sheet->getStyle('B4:E4')->applyFromArray($DatosTitulo);

			$rowNumDocentes = 5;

			foreach ($datosTodosDocentes as $docente) {

				$sheet->setCellValue("A$rowNumDocentes", 'Docente:');
				$sheet->getStyle("A$rowNumDocentes")->applyFromArray($TitulosStyle);
				$sheet->setCellValue("B$rowNumDocentes", $docente['NombreCompletoDocente']);
				$sheet->mergeCells("B$rowNumDocentes:E$rowNumDocentes");
				$sheet->getStyle("B$rowNumDocentes:E$rowNumDocentes")->applyFromArray($DatosTitulo);
				$rowNumDocentes++;
			}


			$sheet->setCellValue("A$rowNumDocentes", 'Periodo:');
			$sheet->getStyle("A$rowNumDocentes")->applyFromArray($TitulosStyle);
			$sheet->setCellValue("B$rowNumDocentes", $datos[0]['periodo']);
			$sheet->mergeCells("B$rowNumDocentes:C$rowNumDocentes");
			$sheet->getStyle("B$rowNumDocentes:C$rowNumDocentes")->applyFromArray($DatosTitulo);
			$rowNumDocentes++;
			$rowNumDocentes++;


			// Añadir encabezados
			$sheet->setCellValue("A$rowNumDocentes", 'N.O');
			$sheet->setCellValue("B$rowNumDocentes", 'MATRICULA');
			$sheet->setCellValue("C$rowNumDocentes", 'NOMBRE DEL ALUMNO');
			$sheet->setCellValue("D$rowNumDocentes", 'GRUPO');
			$sheet->setCellValue("E$rowNumDocentes", 'PARCIAL 1');
			$sheet->setCellValue("F$rowNumDocentes", 'PARCIAL 2');
			$sheet->setCellValue("G$rowNumDocentes", 'PARCIAL 3');
			$sheet->getStyle("A$rowNumDocentes:G$rowNumDocentes")->applyFromArray($Encabezado);
			$rowNumDocentes++;
		

			// Añadir datos
			$rowNum = $rowNumDocentes;
			$numRowsPerPage = 35;
			$numero = 1;

			foreach ($datos as $index => $row) {
				if ($index > 0 && $index % $numRowsPerPage == 0) {
					// Añadir encabezados en la nueva página
					$rowNum += 2; // Añadir espacio entre páginas
					$sheet->setCellValue("A$rowNum", 'N.O');
					$sheet->setCellValue("B$rowNum", 'MATRICULA');
					$sheet->setCellValue("C$rowNum", 'NOMBRE DEL ALUMNO');
					$sheet->setCellValue("D$rowNum", 'GRUPO');
					$sheet->setCellValue("E$rowNum", 'PARCIAL 1');
					$sheet->setCellValue("F$rowNum", 'PARCIAL 2');
					$sheet->setCellValue("G$rowNum", 'PARCIAL 3');
					$sheet->getStyle("A$rowNum:G$rowNum")->applyFromArray($Encabezado);
					$rowNum++;
				}

				$nombre_completo = $row['NombreCompleto'] ;

				$calificacion_formateada1 = is_null($row['calificacion_parcial_1']) ? '' : number_format($row['calificacion_parcial_1'], 2);
				$calificacion_formateada2 = is_null($row['calificacion_parcial_2']) ? '' : number_format($row['calificacion_parcial_2'], 2);
				$calificacion_formateada3 = is_null($row['calificacion_parcial_3']) ? '' : number_format($row['calificacion_parcial_3'], 2);

				// Alternar color de fondo
				$rowStyle = ($index % 2 == 0) ? $dataRowStyle1 : $dataRowStyle2;

				$sheet->setCellValue("A$rowNum", $numero);
				$sheet->setCellValue("B$rowNum", $row['vchMatricula']);
				$sheet->setCellValue("C$rowNum", $nombre_completo);
				$sheet->setCellValue("D$rowNum", $row['chvGrupo']);
				$sheet->setCellValue("E$rowNum", $calificacion_formateada1);
				$sheet->setCellValue("F$rowNum", $calificacion_formateada2);
				$sheet->setCellValue("G$rowNum", $calificacion_formateada3);

				// Aplicar estilo de fila
				$sheet->getStyle("A$rowNum:G$rowNum")->applyFromArray($rowStyle);

				$rowNum++;
				$numero++;
			}

			// Establecer tamaños de columna
			foreach (range('A', 'G') as $col) {
				$sheet->getColumnDimension($col)->setAutoSize(true);
			}

			// Salida del Excel
			$filename = $datos[0]['vchNomMateria'] .'_' .$datos[0]['periodo'].'_' .$datos[0]['chvGrupo'];
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
			header('Cache-Control: max-age=0');
		} else {

			$sheet->setCellValue('A4', 'NO SE ENCONTRARON DATOS');
			$sheet->getStyle('A4')->applyFromArray($TitulosStyle);

			// Salida del Excel
			$filename = "DATOS_NO_ENCONTRADOS";
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
			header('Cache-Control: max-age=0');
		}


		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
		exit;
	}

}
