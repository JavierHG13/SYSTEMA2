<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lista_nivel extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('/sysmater/shared/nivel_model');
	}

	public function index()
	{
		if ($this->session->id_tipo == 1) {
			$this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
			$data['niveles'] = $this->nivel_model->lista_niveles();
			$this->load->view("templates/views_sysmater/admin_views/lista_nivel_view", $data);
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

/* End of file lista_nivel.php */
/* Location: ./application/controllers/director/lista_nivel.php */