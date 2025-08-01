<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lista_reactivos extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('/EGEL/shared/reactivo_model');
	}

	public function index()
	{
		if ($this->session->id_tipo == 4) {
			$this->load->view("templates/views_egel/docente_views/docente_navbar_view");
			$id_docente = $this->session->Matricula;
			$data['reactivos'] = $this->reactivo_model->lista_reactivos($id_docente);
			$this->load->view("templates/views_egel/docente_views/lista_reactivo_view", $data);
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

/* End of file lista_reactivos.php */
/* Location: ./application/controllers/docente/lista_reactivos.php */