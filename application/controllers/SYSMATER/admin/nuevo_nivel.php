<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Nuevo_nivel extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('/sysmater/shared/nivel_model');
	}

	public function index()
	{
		if ($this->session->id_tipo == 1) {
			$this->validation();
			if ($this->form_validation->run() == FALSE) {
				$this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
				$this->load->view("templates/views_sysmater/admin_views/nuevo_nivel_view");
				$this->load->view("templates/shared/footer_view");
			} else {
				$this->guardar_nivel();
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
		} // end if-else
	} // end function
	private function guardar_nivel()
	{
		$data = array(
			'vch_nivel' => $this->input->post('nombreNivel')
		);
		$this->nivel_model->insertar_nivel($data);
		redirect("EGEL/admin/lista_nivel");
	}
	private function validation()
	{
		// set validation
		$config = array(
			array(
				'field' => 'nombreNivel',
				'label' => 'Nombre del nivel',
				'rules' => 'trim|required'
			)
		);
		$this->form_validation->set_rules($config);
	} // end function


}

/* End of file nuevo_nivel.php */
/* Location: ./application/controllers/admin/nuevo_nivel.php */