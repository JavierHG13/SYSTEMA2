<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reactivos_examen extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('/SYSMATER/shared/examen_model');
		$this->load->model('/SYSMATER/shared/carrera_model');
	}
	public function index()
	{
		if ($this->session->id_tipo == 4) {

			$id_examen = $this->uri->segment($this->uri->total_segments());
			

			$this->validation();

			$admin = $this->session->Matricula;
			
			$data["examen"] = $this->get_data_examen($id_examen);
			$data["rSelec"] = $this->examen_model->get_cantidad_reactivos($id_examen);
			
			if ($this->form_validation->run() == FALSE) {
				
				$this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
				$this->load->view("templates/views_sysmater/docente_views/reactivos_examen_view", $data);
				$this->load->view("templates/shared/footer_view");
				
			} else {

				if ($this->input->post("task") == "add") {
					$this->selecciona_reactivos($id_examen);
				} elseif ($this->input->post("task") == "remove") {
					$this->deseleccionar_reactivos($id_examen);
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
		// set validation
		$config = array(
			array(
				'field' => 'task',
				'label' => 'acccion requerida',
				'rules' => 'trim|required'
			)
		);
		$this->form_validation->set_rules($config);
	}

	private function get_data_examen($id)
	{
		$examen = $this->examen_model->get_examen($id);
		return $examen;
	} // end function

}

