<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subir_lote extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('/EGEL/shared/carrera_model');
		$this->load->model('/EGEL/shared/reactivo_model');
		$this->load->model('/EGEL/shared/cuatrimestre_model');
		$this->load->model('/EGEL/shared/materia_model');
		$this->load->model('/EGEL/shared/nivel_model');
	}
	public function index()
	{
		if ($this->session->id_tipo == 4) {
			$this->validation();
			if ($this->form_validation->run() == FALSE) {
				$docente = $this->session->Matricula;
				$periodo = get_periodo();
				$data['carreras'] = $this->carrera_model->lista_carreras_docente($docente, $periodo);
				$res = $data['carreras']->result();
				$row = $res[0];
				$carrera = $row->chrClvCarrera;
				$data['cuatrimestres'] = $this->cuatrimestre_model->lista_cuatrimestres_docente($carrera, $docente, $periodo);
				$res = $data['cuatrimestres']->result();
				$row = $res[0];
				$cuatrimestre = $row->vchClvCuatri;
				$data['materias'] = $this->materia_model->lista_materias_docente($cuatrimestre, $carrera, $docente, $periodo);

				$this->load->view("templates/views_egel/docente_views/docente_navbar_view");
				$this->load->view("templates/views_egel/docente_views/subir_lote_view", $data);
				$this->load->view("templates/shared/footer_view");
			} else {
				$this->read_file();
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
		} // end if-else
	}

	private function validation()
	{
		// set validation
		$config = array(
			array(
				'field' => 'task',
				'label' => 'task',
				'rules' => 'trim|required'
			)
		);
		$this->form_validation->set_rules($config);
	} // end function

	// read the excel file
	public function read_file()
	{
		$msg = "";
		// validation succeeds
		if ($this->input->post('task') == "save") {
			if ($_FILES['lote_file']['tmp_name'] != "") {
				$tname = $_FILES['lote_file']['tmp_name'];
				$name = $this->get_name_file();

				// load the excel library
				$this->load->library('excel');

				// Cargando la hoja de cálculo
				$objReader = new PHPExcel_Reader_Excel2007();
				$objPHPExcel = $objReader->load($tname);
				// Asignar hoja de excel activa
				$objPHPExcel->setActiveSheetIndex(0);
				$CurrentSheet = $objPHPExcel->setActiveSheetIndex(0);
				$Formato_correcto = 0;

				$i = 1;
				$txt_planeamiento = $CurrentSheet->getCell('A' . $i)->getCalculatedValue();
				$int_horas = $CurrentSheet->getCell('B' . $i)->getCalculatedValue();
				$int_minutos = $CurrentSheet->getCell('C' . $i)->getCalculatedValue();
				$id_nivel = $CurrentSheet->getCell('D' . $i)->getCalculatedValue();
				$txt_base = $CurrentSheet->getCell('E' . $i)->getCalculatedValue();
				$nvch_opcionA = $CurrentSheet->getCell('F' . $i)->getCalculatedValue();
				$nvch_argumentaA = $CurrentSheet->getCell('G' . $i)->getCalculatedValue();
				$nvch_opcionB = $CurrentSheet->getCell('H' . $i)->getCalculatedValue();
				$nvch_argumentaB = $CurrentSheet->getCell('I' . $i)->getCalculatedValue();
				$nvch_opcionC = $CurrentSheet->getCell('J' . $i)->getCalculatedValue();
				$nvch_argumentaC = $CurrentSheet->getCell('K' . $i)->getCalculatedValue();
				$nvch_opcionD = $CurrentSheet->getCell('L' . $i)->getCalculatedValue();
				$nvch_argumentaD = $CurrentSheet->getCell('M' . $i)->getCalculatedValue();
				$chr_correcto = $CurrentSheet->getCell('N' . $i)->getCalculatedValue();
				$vch_bibliografia = $CurrentSheet->getCell('O' . $i)->getCalculatedValue();

				if (($txt_planeamiento == "txt_planeamiento") &&
					($int_horas == "int_horas") &&
					($int_minutos == "int_minutos") &&
					($id_nivel == "id_nivel") &&
					($txt_base == "txt_base") &&
					($nvch_opcionA == "nvch_opcionA") &&
					($nvch_argumentaA == "nvch_argumentaA") &&
					($nvch_opcionB == "nvch_opcionB") &&
					($nvch_argumentaB == "nvch_argumentaB") &&
					($nvch_opcionC == "nvch_opcionC") &&
					($nvch_argumentaC == "nvch_argumentaC") &&
					($nvch_opcionD == "nvch_opcionD") &&
					($nvch_argumentaD == "nvch_argumentaD") &&
					($chr_correcto == "chr_correcto") &&
					($vch_bibliografia == "vch_bibliografia")
				) {
					$Formato_correcto = 1;
				} else {
					$Formato_correcto = 0;
					$error = true;
					$msg .= "el archivo tiene el siguiente formato" . "<br>" .
						$txt_planeamiento . "|" .
						$int_horas . "|" .
						$int_minutos . "|" .
						$id_nivel . "|" .
						$txt_base . "|" .
						$nvch_opcionA . "|" .
						$nvch_argumentaA . "|" .
						$nvch_opcionB . "|" .
						$nvch_argumentaB . "|" .
						$nvch_opcionC . "|" .
						$nvch_argumentaC . "|" .
						$nvch_opcionD . "|" .
						$nvch_argumentaD . "|" .
						$chr_correcto . "|" .
						$vch_bibliografia . "|";
				}

				if ($Formato_correcto == 1) {


					$totalrows = $CurrentSheet->getHighestRow();
					// Asignar hoja de excel activa

					$indice = 0;
					for ($i = 2; $i < $totalrows; $i++) {

						$txt_planeamiento = $CurrentSheet->getCell('A' . $i)->getCalculatedValue();
						$int_horas = $CurrentSheet->getCell('B' . $i)->getCalculatedValue();
						$int_minutos = $CurrentSheet->getCell('C' . $i)->getCalculatedValue();
						$id_nivel = $CurrentSheet->getCell('D' . $i)->getCalculatedValue();
						$txt_base = $CurrentSheet->getCell('E' . $i)->getCalculatedValue();
						$nvch_opcionA = $CurrentSheet->getCell('F' . $i)->getCalculatedValue();
						$nvch_argumentaA = $CurrentSheet->getCell('G' . $i)->getCalculatedValue();
						$nvch_opcionB = $CurrentSheet->getCell('H' . $i)->getCalculatedValue();
						$nvch_argumentaB = $CurrentSheet->getCell('I' . $i)->getCalculatedValue();
						$nvch_opcionC = $CurrentSheet->getCell('J' . $i)->getCalculatedValue();
						$nvch_argumentaC = $CurrentSheet->getCell('K' . $i)->getCalculatedValue();
						$nvch_opcionD = $CurrentSheet->getCell('L' . $i)->getCalculatedValue();
						$nvch_argumentaD = $CurrentSheet->getCell('M' . $i)->getCalculatedValue();
						$chr_correcto = $CurrentSheet->getCell('N' . $i)->getCalculatedValue();
						$vch_bibliografia = $CurrentSheet->getCell('O' . $i)->getCalculatedValue();

						if ($txt_base != "" && $nvch_opcionA != "" && $nvch_opcionB != "" && $nvch_opcionC != "" && $nvch_opcionD != "") {

							$xlm[0][$indice] = $txt_planeamiento; //PLANTEAMIENTO
							$xlm[1][$indice] = $int_horas; //HORAS
							$xlm[2][$indice] = $int_minutos; //MINUTOS
							$xlm[3][$indice] = $id_nivel; //NIVEL
							$xlm[4][$indice] = $txt_base; //BASE
							$xlm[5][$indice] = $nvch_opcionA; //OPCION A
							$xlm[6][$indice] = $nvch_argumentaA; //ARGUMENTO A
							$xlm[7][$indice] = $nvch_opcionB; //OPCION B
							$xlm[8][$indice] = $nvch_argumentaB; //ARGUMENTO B
							$xlm[9][$indice] = $nvch_opcionC; //OPCION C
							$xlm[10][$indice] = $nvch_argumentaC; //ARGUMENTO C
							$xlm[11][$indice] = $nvch_opcionD; //OPCION D
							$xlm[12][$indice] = $nvch_argumentaD; //ARGUMENTO D
							$xlm[13][$indice] = $chr_correcto; //CORRECTO
							$xlm[14][$indice] = $vch_bibliografia; //BIBLIOGRAFIA

							$indice++;
						}
					}
					$data['registros'] = $indice - 1;
					$data['name'] = $name;
					$data['xml'] = $xlm; //$this->get_data_from_excel($tname);


					$carrera = $this->input->post('carrera');
					$data['carreras'] = $this->carrera_model->get_data_carrera($carrera);
					$cuatrimestre = $this->input->post('cuatrimestre');
					$data['cuatrimestres'] = $this->cuatrimestre_model->get_data_cuatrimestre($cuatrimestre);
					$materia = $this->input->post('materia');
					$data['materias'] = $this->materia_model->get_data_materia($materia);
					$data['niveles'] = $this->nivel_model->lista_niveles();
					$this->load->view("templates/views_egel/docente_views/docente_navbar_view");
					$this->load->view("templates/views_egel/docente_views/subir_lote_preview_view", $data);
					$this->load->view("templates/shared/footer_view");
				} else {
					$this->load->view("templates/views_egel/docente_views/docente_navbar_view");
					$data['error'] = "El formato del archivo no es correcto<br>";
					$data['msg'] = $msg;
					$this->load->view("templates/views_egel/docente_views/subir_lote_error_view", $data);
					$this->load->view("templates/shared/footer_view");
				}
			} else {
				$this->session->set_flashdata(
					'msg',
					'<div class="alert alert-danger text-center">
					Seleccione archivo por favor!
				</div>'
				);
				redirect("/egel/docente/subir_lote");
			} // end if-else
		} else {
			redirect("/egel/docente/docente/subir_lote");
		} // end if-else
	} // end function

	// get name file without extension
	private function get_name_file()
	{
		$name_file = $_FILES['lote_file']['name'];
		$index_point = strpos($name_file, '.');
		$name = substr($name_file, 0, $index_point);
		return $name_file;
	} // end function

	private function get_data_from_excel($tname)
	{
		// read file from path
		$obj_xls = PHPExcel_IOFactory::load($tname);

		// get only cell collection
		$cellset = $obj_xls->getActiveSheet()->getCellCollection();

		// extract to a PHP readable array format
		foreach ($cellset as $cell) {
			$column = $obj_xls->getActiveSheet()->getCell($cell)->getColumn();
			$row = $obj_xls->getActiveSheet()->getCell($cell)->getRow();
			$data = $obj_xls->getActiveSheet()->getCell($cell)->getValue();

			// the header in the first row
			if ($row == 1) {
				$header[$row][$column] = $data;
			} else {
				$arr_data[$row][$column] = $data;
			} // end if-else
		} // end foreach

		// send the data in an array format
		$data_set['header'] = $header;
		$data_set['values'] = $arr_data;
		return $data_set;
	} // end function
}

/* End of file subir_lote.php */
/* Location: ./application/controllers/docente/subir_lote.php */