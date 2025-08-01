<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Examenes_programados extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('/SYSMATER/shared/examen_model');
	}

	public function index()
	{
		if ($this->session->id_tipo == 4) {

			$vchClvTrabajador = $this->session->Matricula;

			$data['examenes'] = $this->examen_model->lista_examenes_programados_docente($vchClvTrabajador);
			$this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
			$this->load->view("templates/views_sysmater/docente_views/examenes_programados_view", $data);
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
