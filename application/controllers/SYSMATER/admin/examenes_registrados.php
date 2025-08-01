<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Examenes_registrados extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('/sysmater/shared/examen_model');
	}

	public function index()
	{
		if ($this->session->id_tipo == 1) {
			$data['examenes'] = $this->examen_model->lista_examenes_todos();
			
			$this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
			$this->load->view("templates/views_sysmater/admin_views/examenes_registrados_view", $data);
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
		} // end if-else
	}
}

/* End of file examenes_registrados.php */
/* Location: ./application/controllers/admin/examenes_registrados.php */