<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Activacion_sistema extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('/sysmater/shared/activacion_systema_model');
	}

	public function index()
	{
		if ($this->session->id_tipo == 1) {
			$data['systemas'] = $this->activacion_systema_model->lista_systemas_sysmater();

			$this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
			$this->load->view("templates/views_sysmater/admin_views/systemas_action_view", $data);
			$this->load->view("templates/shared/footer_view");
		} else {
			$this->session->set_flashdata(
				'msg',
				'<div class="alert alert-danger text-center"> 
				¡Sesión no iniciada!
				Verifique que el usuario y contraseña sean correctos
			</div>'
			);
			redirect("login");
		} // end if-else
	}

	public function update_status()
	{
		if ($this->session->id_tipo == 1 && $this->input->post()) {
			$id_systema = $this->input->post('id_systema');
			$new_status = $this->input->post('new_status');

			$this->activacion_systema_model->update_systema_status($id_systema, $new_status);

			redirect('/sysmater/admin/admin/activacion_sistema');
		} else {
			redirect('login');
		}
	}
}
