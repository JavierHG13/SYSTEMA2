<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Docentes extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
        $this->load->model('/SYSMATER/shared/docente_model');
		$this->load->model('/SYSMATER/shared/carrera_model');
	}

	public function index()
	{

		if ($this->session->id_tipo == 2) {
			$this->load->view("templates/views_sysmater/director_views/director_navbar_view");
			$this->load->view("templates/views_sysmater/director_views/lista_docentes_view");
			$this->load->view("templates/shared/footer_sysmater_view");
		} else {
			$message = "Verifique que el usuario y contraseña sean correctos ";
			$status_code = 401;
			show_error($message, $status_code, $heading = 'Sesión no iniciada');
		}
	} 	

	//materias
	public function detalles_docentes()
	{
		$id = $this->uri->segment($this->uri->total_segments());
		redirect("/sysmater/director/detalles_docente/index/" . $id);
	}

}// end class
