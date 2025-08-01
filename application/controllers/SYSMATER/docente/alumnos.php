<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Alumnos extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('/SYSMATER/shared/alumno_model');
		$this->load->model('/SYSMATER/shared/carrera_model');
	}

	public function index()
	{
		$data['alumnos'] = $this->alumno_model->list();
		$data['carreras'] = $this->carrera_model->lista_carreras();

		if ($this->session->id_tipo == 4) {
			$this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
			$this->load->view("templates/views_sysmater/docente_views/alumno_list_view", $data);
			$this->load->view("templates/shared/footer_view");
		} else {
			$this->GotoLogin();
		} 
	}


	public function filtrar()
{

    $cuatrimestre = $this->input->post('cuatrimestre');
    $grupo = $this->input->post('grupo');

 	if($carrera  == ''){
		$carrera = 0;
	}
	if($cuatrimestre  == ''){
		$cuatrimestre = 0;
	}
	if($grupo  == ''){
		$grupo = 0;
	}

	$data['alumnos'] = $this->alumno_model->filtrar_Alumnos($cuatrimestre , $grupo);
	$data['carreras'] = $this->carrera_model->lista_carreras();

	if ($this->session->id_tipo == 4) {

		$this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
		$this->load->view("templates/views_sysmater/docente_views/alumno_list_view", $data);
		$this->load->view("templates/shared/footer_view");
	} else {
		$this->GotoLogin();
	} 
}



	public function pendientes()
	{
		if ($this->session->id_tipo == 4) {
			$this->load->view("templates/views_sysmater/docente_views/admin_navbar_view");
			$data['alumnos'] = $this->alumno_model->list_pendientes();
			$this->load->view("templates/views_sysmater/docente_views/alumno_pendientes_view", $data);
			$this->load->view("templates/shared/footer_view");
		} else {
			$this->GotoLogin();
		} // end if-else
	}

	public function excel()
	{
		$table = $this->input->post('tbl');
		$this->load->library('excel');
		$data['table'] = $table;
		// Load the table view into a variable
		$html = $this->load->view('templates/views_sysmater/docente_views/tblpendientes_view', $data);
	}
	public function ver($maricula)
	{
		if ($this->session->id_tipo == 4) {
			$this->load->view("templates/views_sysmater/docente_views/admin_navbar_view");
			$data['alumno'] = $this->alumno_model->get($maricula);
			$data['maquina'] = $this->alumno_model->getmaquina($maricula);
			$this->load->view("templates/views_sysmater/docente_views/alumno_ver_view", $data);
			$this->load->view("templates/shared/footer_view");
		} else {
		} // end if-else
	}

	public function liberar()
	{

		if ($this->session->id_tipo == 4) {

			$maricula = $this->input->post("matricula");
			$fch_termina = $this->input->post("fch_termina");
			$task = $this->input->post("taskform");
			if ($task == "liberar") {
				$this->alumno_model->liberar($maricula, $fch_termina);
			} elseif ($task == "borrar") {
				$this->alumno_model->borrar($maricula);
			}
			redirect("/EGEL/admin/alumnos/");
		} else {
			$this->GotoLogin();
		} // end if-else
	}


	public function GotoLogin()
	{
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

/* End of file lista_area.php */
/* Location: ./application/controllers/admin/lista_area.php */