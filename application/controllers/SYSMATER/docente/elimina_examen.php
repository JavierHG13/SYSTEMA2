<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Elimina_examen extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('/sysmater/shared/examen_model');
	}
	public function index()
	{
		if ($this->session->id_tipo == 4) {
			$task = $this->input->post('task');
			$id_examen = $this->input->post('id_examen');
			
			$id = $this->uri->segment($this->uri->total_segments());	
			$data["examen"] = $this->get_data_examen($id);

			if ($task != 'delete') {
				$this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
				$this->load->view("templates/views_sysmater/docente_views/elimina_examen_view", $data);
				$this->load->view("templates/shared/footer_view");
			} else {
				$this->elimina_examen($id_examen);			
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
	private function elimina_examen($id)
	{	
		if($this->examen_model->eliminar_exame($id)) {
			$data["examen"] = $this->get_data_examen($id);
            $data["error"] = true;

            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
			$this->load->view("templates/views_sysmater/docente_views/elimina_examen_view", $data);
			$this->load->view("templates/shared/footer_view");
		} else {
			redirect("/sysmater/docente/docente/examenes_registrados/");
		}
	}

	private function get_data_examen($id)
	{
		$examen = $this->examen_model->get_examen($id);
		return $examen;
	}
}