<?php

/**
 * Page director controller.
 * 06/06/2016
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Director extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{

		if ($this->session->id_tipo == 2) {
			$this->load->view("templates/views_sysmater/director_views/director_navbar_view");
			$this->load->view("templates/shared/home_sysmater_view");
			$this->load->view("templates/shared/footer_sysmater_view");
		} else {
			$message = "Verifique que el usuario y contraseña sean correctos ";
			$status_code = 401;
			show_error($message, $status_code, $heading = 'Sesión no iniciada');
		}
	} 	

	//materias
	public function detalles_alumno()
	{
		$id = $this->uri->segment($this->uri->total_segments());
		redirect("/sysmater/director/detalles_alumno/index/" . $id);
	}

	// Lista de Docentes - Redirecciona al controlador lista_docente
	public function lista_docente()
	{
		redirect("/sysmater/director/lista_docente");
	}

	public function detalles_docente()
	{
		$id = $this->uri->segment($this->uri->total_segments());
		redirect("/sysmater/director/detalles_docente/index/" . $id);
	}

	public function examenes_registrados()
	{
		redirect("/sysmater/director/examenes_registrados");
	}


}// end class
