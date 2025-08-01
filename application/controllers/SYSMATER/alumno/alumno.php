<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');

	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\IOFactory;
	use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
	use PhpOffice\PhpSpreadsheet\Style\Fill;


	class Alumno extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
		}

		public function index()
		{
			if ($this->session->id_tipo == 5) {
				$this->load->view("templates/views_sysmater/alumno_views/alumno_navbar_view");
				$this->load->view("templates/shared/home_sysmater_view");
				$this->load->view("templates/shared/footer_sysmater_view");
			} else {
				$message = "Verifique que el usuario y contraseña sean correctos ";
				$status_code = 401;
				show_error($message, $status_code, $heading = 'Sesión no iniciada');
			}
		} 
		public function examenes()
		{
			redirect("/sysmater/alumno/examenes");
		}

		public function resultados_examenes()
		{
			redirect("/sysmater/alumno/resultados_examenes");
		}

		public function resultados_actividades()
		{
			redirect("/sysmater/alumno/resultados_actividades");
		}
	}