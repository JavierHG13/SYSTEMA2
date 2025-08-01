<?php
defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Reporte_examen extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('/SYSMATER/shared/reporte_model');
		$this->load->model('/SYSMATER/shared/examen_model');
		$this->load->model('/SYSMATER/shared/docente_model');
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
					$this->load->view("templates/views_sysmater/docente_views/reporte_examen_view", $data);
					$this->load->view("templates/shared/footer_view");
				}
			} else {
				$tipo = $this->input->post('tipo');
				
				if ($tipo == "excel" || $tipo == "pdf") {
					
					$vchClvTrabajador = $this->session->Matricula;
					$materia = $this->input->post("materia");
					$periodo = $this->input->post("periodo");
					$examen = $this->input->post("examenes");			
					$grupos= $this->input->post('grupo');
					
					$this->exportar($tipo, $materia, $periodo, $vchClvTrabajador, $examen,$grupos);
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
	public function get_data_examenes()
	{
		$materia = $this->input->post('materia');
		$periodo = $this->input->post('periodo');
		$vchClvTrabajador = $this->session->Matricula;

		if ($materia && $periodo) {
			$query = $this->examen_model->lista_examenes_materia($materia, $periodo, $vchClvTrabajador);
		} else {
			$query = NULL;
		}

		if ($query !== NULL) {
			echo json_encode($query->result());
		} else {
			echo json_encode([]);
		}
	}

	private function validation()
	{
		$config = array(
			array(
				'field' => 'materia',
				'label' => 'materia',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'examenes',
				'label' => 'examenes',
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


	private function exportar($tipo, $materia, $periodo, $vchClvTrabajador, $examen,$grupos)
	{
		if ($grupos === 'TODOS' || $grupos === '00') {
			// consulta los resultados de todos los grupos
			$datos = $this->reporte_model->obtener_resultados_examen_docente($materia, $periodo, $vchClvTrabajador, $examen);
			if($datos == NULL){
				echo '<script type="text/javascript">';
				echo 'alert("No se encontraron registros");';
				echo 'window.location.href = "'.site_url('sysmater/docente/docente/reporte_examen').'";';
				echo '</script>';
				exit();
			}else{
				if ($tipo == "excel") {
					$this->crearExcel($datos);
				} elseif ($tipo == "pdf") {
					$this->crearPDF($datos);
				}
			}
		} else {
			// cconsulta por grupo esprcifico
			$datos = $this->reporte_model->obtener_resultados_examen_docente_grupo($materia, $periodo, $vchClvTrabajador, $examen, $grupos);
			if($datos == NULL){
				echo '<script type="text/javascript">';
				echo 'alert("No se encontraron registros del grupo '.$grupos.'");';
				echo 'window.location.href = "'.site_url('sysmater/docente/docente/reporte_examen').'";';
				echo '</script>';
				exit();
			}else{
				if ($tipo == "excel") {
					$this->crearExcelGrupo($datos);
				} elseif ($tipo == "pdf") {
					$this->crearPDFGrupo($datos);
				}
			}
		}		
	}

	private function crearPDF($datos)
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

		if (!empty($datos)) {
			$primer_elemento = $datos[0];

			$filename = $primer_elemento['vchNomMateria'] . '_' . $primer_elemento['nvch_Titulo'] . '_' . $primer_elemento['periodo'] . '_' . $primer_elemento['parcial'];

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

			// Examen
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Cell(20, 7, 'Examen: ', 1);
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Cell(40, 7, $primer_elemento['nvch_Titulo'], 'B');
			$pdf->Cell(40, 7, ' ', 'B');
			$pdf->Cell(40, 7, ' ', 'B');
			$pdf->Ln();

			// Docente
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Cell(20, 7, 'Docente: ', 1);
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Cell(40, 7, trim($primer_elemento['DocenteAPaterno'] . ' ' . $primer_elemento['DocenteAMaterno'] . ' ' . $primer_elemento['DocenteNombre']), 'B');
			$pdf->Cell(40, 7, ' ', 'B');
			$pdf->Cell(40, 7, ' ', 'B');
			$pdf->Ln();

			// Periodo
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Cell(20, 7, 'Periodo: ', 1);
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Cell(40, 7, $primer_elemento['periodo'], 'B');

			// Parcial
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Cell(20, 7, 'Parcial: ', 1);
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Cell(40, 7, $primer_elemento['parcial'], 'B');
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
				$pdf->Cell(20, 6, 'ACIERTOS', 1, 0, 'C', true);
				$pdf->Cell(30, 6, 'CALIFICACIÓN', 1, 0, 'C', true);
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

				$nombre_completo = trim($row['AlumnoAPaterno'] . ' ' . $row['AlumnoAMaterno'] . ' ' . $row['AlumnoNombre']);

				// Formatear calificación a dos decimales
				$calificacion_formateada = number_format($row['calificacion'], 2);

				// Alternar color de fondo
				if ($fill) {
					$pdf->SetFillColor(226, 239, 218); // Color verde claro
				} else {
					$pdf->SetFillColor(169, 208, 142); // Color verde más oscuro
				}

				$pdf->Cell(10, 6, $numero, 1, 0, 'C', true);
				$pdf->Cell(30, 6, $row['vchMatricula'], 1, 0, '', true);
				$pdf->Cell(80, 6, $nombre_completo, 1, 0, '', true);
				$pdf->Cell(20, 6, $row['AlumnoGrupo'], 1, 0, 'C', true);
				$pdf->Cell(20, 6, $row['aciertos'] . '/' . $row['total_reactivos'], 1, 0, 'C', true);
				$pdf->Cell(30, 6, $calificacion_formateada, 1, 0, 'C', true);
				$pdf->Ln();

				$fill = !$fill; // Alternar el valor de $fill
				$numero++;
			}
		} else {
			$pdf->Cell(0, 6, 'No se encontraron resultados', 1);
		}
		// Salida del PDF
		$pdf->Output($filename . '.pdf', 'D');
		exit;
	}


	private function crearExcel($datos)
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

		// Añadir título estático "Materia:" en negritas
		$sheet->setCellValue('A4', 'Materia:');
		$sheet->getStyle('A4')->applyFromArray($TitulosStyle);
		$sheet->setCellValue('B4', $datos[0]['vchNomMateria']);
		$sheet->mergeCells('B4:E4');
		$sheet->getStyle('B4:E4')->applyFromArray($DatosTitulo);

		// Añadir otros títulos y subrayados
		$sheet->setCellValue('A5', 'Examen:');
		$sheet->getStyle('A5')->applyFromArray($TitulosStyle);
		$sheet->setCellValue('B5', $datos[0]['nvch_Titulo']);
		$sheet->mergeCells('B5:E5');
		$sheet->getStyle('B5:E5')->applyFromArray($DatosTitulo);

		$sheet->setCellValue('A6', 'Docente:');
		$sheet->getStyle('A6')->applyFromArray($TitulosStyle);
		$sheet->setCellValue('B6', trim($datos[0]['DocenteAPaterno'] . ' ' . $datos[0]['DocenteAMaterno'] . ' ' . $datos[0]['DocenteNombre']));
		$sheet->mergeCells('B6:E6');
		$sheet->getStyle('B6:E6')->applyFromArray($DatosTitulo);

		$sheet->setCellValue('A7', 'Periodo:');
		$sheet->getStyle('A7')->applyFromArray($TitulosStyle);
		$sheet->setCellValue('B7', $datos[0]['periodo']);
		$sheet->mergeCells('B7:C7');
		$sheet->getStyle('B7:C7')->applyFromArray($DatosTitulo);

		$sheet->setCellValue('D7', 'Parcial:');
		$sheet->getStyle('D7')->applyFromArray($TitulosStyle);
		$sheet->setCellValue('E7', $datos[0]['parcial']);
		$sheet->getStyle('E7')->applyFromArray($DatosTitulo);


		// Añadir encabezados
		$sheet->setCellValue('A9', 'N.O');
		$sheet->setCellValue('B9', 'MATRICULA');
		$sheet->setCellValue('C9', 'NOMBRE DEL ALUMNO');
		$sheet->setCellValue('D9', 'GRUPO');
		$sheet->setCellValue('E9', 'ACIERTOS');
		$sheet->setCellValue('F9', 'CALIFICACIÓN');
		$sheet->getStyle('A9:F9')->applyFromArray($Encabezado);

		// Añadir datos
		$rowNum = 10;
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
				$sheet->setCellValue("E$rowNum", 'ACIERTOS');
				$sheet->setCellValue("F$rowNum", 'CALIFICACIÓN');
				$sheet->getStyle("A$rowNum:F$rowNum")->applyFromArray($Encabezado);
				$rowNum++;
			}

			$nombre_completo = trim($row['AlumnoAPaterno'] . ' ' . $row['AlumnoAMaterno'] . ' ' . $row['AlumnoNombre']);
			$calificacion_formateada = number_format($row['calificacion'], 2);

			// Alternar color de fondo
			$rowStyle = ($index % 2 == 0) ? $dataRowStyle1 : $dataRowStyle2;

			$sheet->setCellValue("A$rowNum", $numero);
			$sheet->setCellValue("B$rowNum", $row['vchMatricula']);
			$sheet->setCellValue("C$rowNum", $nombre_completo);
			$sheet->setCellValue("D$rowNum", $row['AlumnoGrupo']);
			$sheet->setCellValue("E$rowNum", $row['aciertos'] . '/' . $row['total_reactivos']);
			$sheet->setCellValue("F$rowNum", $calificacion_formateada);

			// Aplicar estilo de fila
			$sheet->getStyle("A$rowNum:F$rowNum")->applyFromArray($rowStyle);

			$rowNum++;
			$numero++;
		}

		// Establecer tamaños de columna
		foreach (range('A', 'F') as $col) {
			$sheet->getColumnDimension($col)->setAutoSize(true);
		}

		// Salida del Excel
		$filename = $datos[0]['vchNomMateria'] . '_' . $datos[0]['nvch_Titulo'] . '_' . $datos[0]['periodo'] . '_' . $datos[0]['parcial'];
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
		exit;
	}

	private function crearPDFGrupo($datos)
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

		if (!empty($datos)) {
			$primer_elemento = $datos[0];

			$filename = $primer_elemento['vchNomMateria'] . '_' . $primer_elemento['nvch_Titulo'] . '_' . $primer_elemento['periodo'] . '_' . $primer_elemento['parcial']. '_' . $primer_elemento['AlumnoGrupo'];

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

			// Examen
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Cell(20, 7, 'Examen: ', 1);
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Cell(40, 7, $primer_elemento['nvch_Titulo'], 'B');
			$pdf->Cell(40, 7, ' ', 'B');
			$pdf->Cell(40, 7, ' ', 'B');
			$pdf->Ln();

			// Docente
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Cell(20, 7, 'Docente: ', 1);
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Cell(40, 7, trim($primer_elemento['DocenteAPaterno'] . ' ' . $primer_elemento['DocenteAMaterno'] . ' ' . $primer_elemento['DocenteNombre']), 'B');
			$pdf->Cell(40, 7, ' ', 'B');
			$pdf->Cell(40, 7, ' ', 'B');
			$pdf->Ln();

			// Periodo
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Cell(20, 7, 'Periodo: ', 1);
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Cell(40, 7, $primer_elemento['periodo'], 'B');

			// Parcial
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Cell(20, 7, 'Parcial: ', 1);
			$pdf->SetFont('helvetica', 'B', 12);
			$pdf->Cell(40, 7, $primer_elemento['parcial'], 'B');
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
				$pdf->Cell(20, 6, 'ACIERTOS', 1, 0, 'C', true);
				$pdf->Cell(30, 6, 'CALIFICACIÓN', 1, 0, 'C', true);
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

				$nombre_completo = trim($row['AlumnoAPaterno'] . ' ' . $row['AlumnoAMaterno'] . ' ' . $row['AlumnoNombre']);

				// Formatear calificación a dos decimales
				$calificacion_formateada = number_format($row['calificacion'], 2);

				// Alternar color de fondo
				if ($fill) {
					$pdf->SetFillColor(226, 239, 218); // Color verde claro
				} else {
					$pdf->SetFillColor(169, 208, 142); // Color verde más oscuro
				}

				$pdf->Cell(10, 6, $numero, 1, 0, 'C', true);
				$pdf->Cell(30, 6, $row['vchMatricula'], 1, 0, '', true);
				$pdf->Cell(80, 6, $nombre_completo, 1, 0, '', true);
				$pdf->Cell(20, 6, $row['AlumnoGrupo'], 1, 0, 'C', true);
				$pdf->Cell(20, 6, $row['aciertos'] . '/' . $row['total_reactivos'], 1, 0, 'C', true);
				$pdf->Cell(30, 6, $calificacion_formateada, 1, 0, 'C', true);
				$pdf->Ln();

				$fill = !$fill; // Alternar el valor de $fill
				$numero++;
			}
		} else {
			$pdf->Cell(0, 6, 'No se encontraron resultados', 1);
		}
		// Salida del PDF
		$pdf->Output($filename . '.pdf', 'D');
		exit;
	}


	private function crearExcelGrupo($datos)
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

		// Añadir título estático "Materia:" en negritas
		$sheet->setCellValue('A4', 'Materia:');
		$sheet->getStyle('A4')->applyFromArray($TitulosStyle);
		$sheet->setCellValue('B4', $datos[0]['vchNomMateria']);
		$sheet->mergeCells('B4:E4');
		$sheet->getStyle('B4:E4')->applyFromArray($DatosTitulo);

		// Añadir otros títulos y subrayados
		$sheet->setCellValue('A5', 'Examen:');
		$sheet->getStyle('A5')->applyFromArray($TitulosStyle);
		$sheet->setCellValue('B5', $datos[0]['nvch_Titulo']);
		$sheet->mergeCells('B5:E5');
		$sheet->getStyle('B5:E5')->applyFromArray($DatosTitulo);

		$sheet->setCellValue('A6', 'Docente:');
		$sheet->getStyle('A6')->applyFromArray($TitulosStyle);
		$sheet->setCellValue('B6', trim($datos[0]['DocenteAPaterno'] . ' ' . $datos[0]['DocenteAMaterno'] . ' ' . $datos[0]['DocenteNombre']));
		$sheet->mergeCells('B6:E6');
		$sheet->getStyle('B6:E6')->applyFromArray($DatosTitulo);

		$sheet->setCellValue('A7', 'Periodo:');
		$sheet->getStyle('A7')->applyFromArray($TitulosStyle);
		$sheet->setCellValue('B7', $datos[0]['periodo']);
		$sheet->mergeCells('B7:C7');
		$sheet->getStyle('B7:C7')->applyFromArray($DatosTitulo);

		$sheet->setCellValue('D7', 'Parcial:');
		$sheet->getStyle('D7')->applyFromArray($TitulosStyle);
		$sheet->setCellValue('E7', $datos[0]['parcial']);
		$sheet->getStyle('E7')->applyFromArray($DatosTitulo);


		// Añadir encabezados
		$sheet->setCellValue('A9', 'N.O');
		$sheet->setCellValue('B9', 'MATRICULA');
		$sheet->setCellValue('C9', 'NOMBRE DEL ALUMNO');
		$sheet->setCellValue('D9', 'GRUPO');
		$sheet->setCellValue('E9', 'ACIERTOS');
		$sheet->setCellValue('F9', 'CALIFICACIÓN');
		$sheet->getStyle('A9:F9')->applyFromArray($Encabezado);

		// Añadir datos
		$rowNum = 10;
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
				$sheet->setCellValue("E$rowNum", 'ACIERTOS');
				$sheet->setCellValue("F$rowNum", 'CALIFICACIÓN');
				$sheet->getStyle("A$rowNum:F$rowNum")->applyFromArray($Encabezado);
				$rowNum++;
			}

			$nombre_completo = trim($row['AlumnoAPaterno'] . ' ' . $row['AlumnoAMaterno'] . ' ' . $row['AlumnoNombre']);
			$calificacion_formateada = number_format($row['calificacion'], 2);

			// Alternar color de fondo
			$rowStyle = ($index % 2 == 0) ? $dataRowStyle1 : $dataRowStyle2;

			$sheet->setCellValue("A$rowNum", $numero);
			$sheet->setCellValue("B$rowNum", $row['vchMatricula']);
			$sheet->setCellValue("C$rowNum", $nombre_completo);
			$sheet->setCellValue("D$rowNum", $row['AlumnoGrupo']);
			$sheet->setCellValue("E$rowNum", $row['aciertos'] . '/' . $row['total_reactivos']);
			$sheet->setCellValue("F$rowNum", $calificacion_formateada);

			// Aplicar estilo de fila
			$sheet->getStyle("A$rowNum:F$rowNum")->applyFromArray($rowStyle);

			$rowNum++;
			$numero++;
		}

		// Establecer tamaños de columna
		foreach (range('A', 'F') as $col) {
			$sheet->getColumnDimension($col)->setAutoSize(true);
		}

		// Salida del Excel
		$filename = $datos[0]['vchNomMateria'] . '_' . $datos[0]['nvch_Titulo'] . '_' . $datos[0]['periodo'] . '_' . $datos[0]['parcial']. '_' . $datos['AlumnoGrupo'];
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
		exit;
	}


	
}
