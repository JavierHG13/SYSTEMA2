<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ver_examen extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('/SYSMATER/shared/examen_model');
		$this->load->model('/SYSMATER/alumno/test_model', 'test');
	}

	public function index()
	{
		if ($this->session->id_tipo == 4) {

			$id = $this->uri->segment($this->uri->total_segments());
		
			$data["examen"] = $this->get_data_examen($id);
			
			$this->load->view("templates/views_sysmater/docente_views/ver_examen_view", $data);
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
	private function get_data_examen($id)
	{
		$examen = $this->examen_model->get_examen($id);
		return $examen;
	} // end function
	// return data for methods_test.js($getJSON)
	public function get_questions($id_examen)
	{
		$questions = $this->test->get_questions($id_examen);
		if (is_array($questions)) {
			echo json_encode($questions);
		} else {
			return FALSE;
		} // end if-else
	} // end function

}

/* End of file ver_examen.php */
/* Location: ./application/controllers/admin/ver_examen.php */