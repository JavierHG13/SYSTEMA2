<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Save_lote extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('/EGEL/shared/reactivo_model');

		$this->load->library('upload');
	}

	public function index()
	{
		if ($this->session->id_tipo == 4) {
			$this->validation();
			if ($this->form_validation->run() == TRUE) {
				$this->guardar_reactivos();
			} else {
				redirect("/egel/docente/subir_lote");
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
	private function guardar_reactivos()
	{
		/*CUENTA EL NUMERO DE REACTIVOS DETALLE*/
		if (count($this->input->post("planteamiento")) > 1) {
			$id_reactivo_main = 0;
			/*RECORRE LOS REACTIVOS*/
			for ($i = 0; $i < count($this->input->post("planteamiento")); $i++) {
				$planteamiento = $this->input->post('planteamiento[' . $i . ']');

				//	//SI ES UN REACTIVO SIMPLE O TIENE UN PLANTEAMIENTO SE AGREGA UN NUEVO REGISTRO EN TBL_REACTIVOS_MAIN
				if ((strtoupper($planteamiento) == "SIMPLE") || (strlen(trim($planteamiento)) > 0)) {
					if (strtoupper($planteamiento) == "SIMPLE") {
						$planteamiento = "";
					}
					/*OBTENE LOS POST DEL ENCABEZADO*/
					$data = array(
						'txt_planeamiento' => $planteamiento,
						'chrClvCarrera' => $this->input->post('carrera'),
						'vchClvCuatri' => $this->input->post('cuatrimestre'),
						'vchClvMateria' => $this->input->post('materia'),
						'path_imagen' => '',
						'vchClvTrabajador' => $this->session->Matricula,
						'id_estado' => 1,
						'vchPeriodo' => get_periodo()
					);
					/*OBTIENE EL ID DEL REACTIVO PRINCIPAL*/
					$id_reactivo_main = $this->reactivo_model->insertar_reactivo($data);
					//SI SELECCIONAMOS UNA IMAGEN PARA EL MULTIREACTIVO
					$this->guarda_archivo('imgPlanteamiento', $i, 0, $id_reactivo_main, 0);
				}


				//FILTRO DE BUSQUEDA
				$dataFilter = array(
					'int_horas' => $this->input->post("int_horas[" . $i . "]"),
					'int_minutos' => $this->input->post("int_minutos[" . $i . "]"),
					'id_nivel' => $this->input->post("id_nivel[" . $i . "]"),
					'nvch_opcionA' => $this->input->post("nvch_opcionA[" . $i . "]"),
					'nvch_opcionB' => $this->input->post("nvch_opcionB[" . $i . "]"),
					'nvch_opcionC' => $this->input->post("nvch_opcionC[" . $i . "]"),
					'nvch_opcionD' => $this->input->post("nvch_opcionD[" . $i . "]"),
					'chr_correcto' => $this->input->post("chr_correcto[" . $i . "]"),
					'vch_bibliografia' => $this->input->post("vch_bibliografia[" . $i . "]"),
					'vchClvTrabajador' => $this->session->Matricula
				);
				//SI NO EXISTE EL SUBREACTIVO
				if ($this->reactivo_model->existe_subreactivo($dataFilter) == FALSE) {
					//GUARDA LOS REACTIVOS
					$data = array(
						'id_reactivo_main' => $id_reactivo_main,
						'int_horas' => $this->input->post("int_horas[" . $i . "]"),
						'int_minutos' => $this->input->post("int_minutos[" . $i . "]"),
						'id_nivel' => $this->input->post("id_nivel[" . $i . "]"),
						'txt_base' => $this->input->post("txt_base[" . $i . "]"),
						'path_imagen_base' => '',
						'nvch_opcionA' => $this->input->post("nvch_opcionA[" . $i . "]"),
						'nvch_argumentaA' => $this->input->post("nvch_argumentaA[" . $i . "]"),
						'path_imagenA' => '',
						'nvch_opcionB' => $this->input->post("nvch_opcionB[" . $i . "]"),
						'nvch_argumentaB' => $this->input->post("nvch_argumentaB[" . $i . "]"),
						'path_imagenB' => '',
						'nvch_opcionC' => $this->input->post("nvch_opcionC[" . $i . "]"),
						'nvch_argumentaC' => $this->input->post("nvch_argumentaC[" . $i . "]"),
						'path_imagenC' => '',
						'nvch_opcionD' => $this->input->post("nvch_opcionD[" . $i . "]"),
						'nvch_argumentaD' => $this->input->post("nvch_argumentaD[" . $i . "]"),
						'path_imagenD' => '',
						'chr_correcto' => $this->input->post("chr_correcto[" . $i . "]"),
						'vch_bibliografia' => $this->input->post("vch_bibliografia[" . $i . "]"),
						'vchClvTrabajador' => $this->session->Matricula,
						'id_estado' => 1
					);
					//GUARDA EL SUBREACTIVO
					$id_reactivo_detail = $this->reactivo_model->insertar_subreactivo($data);

					//SI SELECCIONAMOS UNA IMAGEN PARA LA BASE
					$this->guarda_archivo('imgReactivo', $i, 1, $id_reactivo_main, $id_reactivo_detail);
					//SI SELECCIONAMOS UNA IMAGEN PARA OPCION A
					$this->guarda_archivo('imgOpcionA', $i, 2, $id_reactivo_main, $id_reactivo_detail);
					//SI SELECCIONAMOS UNA IMAGEN PARA OPCION B
					$this->guarda_archivo('imgOpcionB', $i, 3, $id_reactivo_main, $id_reactivo_detail);
					//SI SELECCIONAMOS UNA IMAGEN PARA OPCION C
					$this->guarda_archivo('imgOpcionC', $i, 4, $id_reactivo_main, $id_reactivo_detail);
					//SI SELECCIONAMOS UNA IMAGEN PARA OPCION D
					$this->guarda_archivo('imgOpcionD', $i, 5, $id_reactivo_main, $id_reactivo_detail);
				}
			}
		} else {
			//SI ES UN SOLO REGISTRO, SE TOMAN LOS VALORES DE LA POCICION CERO
			/*OBTENE LOS POST DEL ENCABEZADO*/
			$data = array(
				'txt_planeamiento' => $this->input->post('planteamiento[0]'),
				'chrClvCarrera' => $this->input->post('carrera'),
				'vchClvCuatri' => $this->input->post('cuatrimestre'),
				'vchClvMateria' => $this->input->post('materia'),
				'path_imagen' => '',
				'vchClvTrabajador' => $this->session->Matricula,
				'id_estado' => 1,
				'vchPeriodo' => get_periodo()
			);
			/*OBTIENE EL ID DEL REACTIVO PRINCIPAL*/
			$id_reactivo_main = $this->reactivo_model->insertar_reactivo($data);
			//SI SELECCIONAMOS UNA IMAGEN PARA EL MULTIREACTIVO
			$this->guarda_archivo('imgPlanteamiento', 0, 0, $id_reactivo_main, 0);
			//GUARDA LOS REACTIVOS
			$data = array(
				'id_reactivo_main' => $id_reactivo_main,
				'int_horas' => $this->input->post("int_horas[0]"),
				'int_minutos' => $this->input->post("int_minutos[0]"),
				'id_nivel' => $this->input->post("id_nivel[0]"),
				'txt_base' => $this->input->post("txt_base[0]"),
				'path_imagen_base' => '',
				'nvch_opcionA' => $this->input->post("nvch_opcionA[0]"),
				'nvch_argumentaA' => $this->input->post("nvch_argumentaA[0]"),
				'path_imagenA' => '',
				'nvch_opcionB' => $this->input->post("nvch_opcionB[0]"),
				'nvch_argumentaB' => $this->input->post("nvch_argumentaB[0]"),
				'path_imagenB' => '',
				'nvch_opcionC' => $this->input->post("nvch_opcionC[0]"),
				'nvch_argumentaC' => $this->input->post("nvch_argumentaC[0]"),
				'path_imagenC' => '',
				'nvch_opcionD' => $this->input->post("nvch_opcionD[0]"),
				'nvch_argumentaD' => $this->input->post("nvch_argumentaD[0]"),
				'path_imagenD' => '',
				'chr_correcto' => $this->input->post("chr_correcto[0]"),
				'vch_bibliografia' => $this->input->post("vch_bibliografia[0]"),
				'vchClvTrabajador' => $this->session->Matricula,
				'id_estado' => 1
			);
			//GUARDA EL SUBREACTIVO
			$id_reactivo_detail = $this->reactivo_model->insertar_subreactivo($data);

			//SI SELECCIONAMOS UNA IMAGEN PARA LA BASE
			$this->guarda_archivo('imgReactivo', 0, 1, $id_reactivo_main, $id_reactivo_detail);
			//SI SELECCIONAMOS UNA IMAGEN PARA OPCION A
			$this->guarda_archivo('imgOpcionA', 0, 2, $id_reactivo_main, $id_reactivo_detail);
			//SI SELECCIONAMOS UNA IMAGEN PARA OPCION B
			$this->guarda_archivo('imgOpcionB', 0, 3, $id_reactivo_main, $id_reactivo_detail);
			//SI SELECCIONAMOS UNA IMAGEN PARA OPCION C
			$this->guarda_archivo('imgOpcionC', 0, 4, $id_reactivo_main, $id_reactivo_detail);
			//SI SELECCIONAMOS UNA IMAGEN PARA OPCION D
			$this->guarda_archivo('imgOpcionD', 0, 5, $id_reactivo_main, $id_reactivo_detail);
		}
		redirect("/egel/docente/lista_reactivos");
	}

	private function guarda_archivo($elemento, $pos_array, $pos_bd, $id_reactivo_main, $id_reactivo_detail)
	{
		if (!empty($_FILES[$elemento]['name'][$pos_array])) {
			// obtenemos los datos del archivo 
			$archivo = $_FILES[$elemento]['name'][$pos_array];
			list($nae, $extn1) = explode(".", "$archivo");
			$extn1 = strtolower(trim($extn1));
			$sufijo = substr(md5(uniqid(rand())), 0, 2);

			switch ($pos_bd) {
				case 0:
					$archivo = $id_reactivo_main . "_Main_" . $sufijo . ".$extn1";
					$id_reactivo_detail = $id_reactivo_main;
					break;
				case 1:
					$archivo = $id_reactivo_main . "_Main_" . $id_reactivo_detail . "_Reac_Base_" . $sufijo . ".$extn1";
					break;
				case 2:
					$archivo = $id_reactivo_main . "_Main_" . $id_reactivo_detail . "_Reac_opA_" . $sufijo . ".$extn1";
					break;
				case 3:
					$archivo = $id_reactivo_main . "_Main_" . $id_reactivo_detail . "_Reac_opB_" . $sufijo . ".$extn1";
					break;
				case 4:
					$archivo = $id_reactivo_main . "_Main_" . $id_reactivo_detail . "_Reac_opC_" . $sufijo . ".$extn1";
					break;
				case 5:
					$archivo = $id_reactivo_main . "_Main_" . $id_reactivo_detail . "_Reac_opD_" . $sufijo . ".$extn1";
					break;
			}

			$_FILES['userfile']['name']     = $_FILES[$elemento]['name'][$pos_array];
			$_FILES['userfile']['type']     = $_FILES[$elemento]['type'][$pos_array];
			$_FILES['userfile']['tmp_name'] = $_FILES[$elemento]['tmp_name'][$pos_array];
			$_FILES['userfile']['error']    = $_FILES[$elemento]['error'][$pos_array];
			$_FILES['userfile']['size']     = $_FILES[$elemento]['size'][$pos_array];

			if ($this->subir_archivo("userfile", $archivo) == true) {
				$this->reactivo_model->guardar_imagen_reactivo($id_reactivo_detail, $archivo, $pos_bd);
			}
		}
	}
	private function subir_archivo($elemento, $nombre_archivo)
	{
		$result = true;

		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size'] = '5000';
		$config['max_width'] = '0';
		$config['max_height'] = '0';
		$config['file_name'] = $nombre_archivo;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if (!$this->upload->do_upload($elemento)) {
			$error = array('error' => $this->upload->display_errors());
			$result = false;
		} else {
			$data = array('upload_data' => $this->upload->data());
			$result = true;
		}
		return $result;
	}
}

/* End of file save_lote.php */
/* Location: ./application/controllers/docente/save_lote.php */