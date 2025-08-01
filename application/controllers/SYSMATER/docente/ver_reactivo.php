<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ver_reactivo extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('/EGEL/shared/reactivo_model');
	}

	public function index()
	{
		if ($this->session->id_tipo == 4) {

			$id = $this->uri->segment($this->uri->total_segments());

			$data["reactivo_main"] = $this->get_reactivo_main_preview($id);
			$data["reactivo_detail"] = $this->get_reactivo_detail_preview($id);

			$this->load->view("templates/views_egel/docente_views/docente_navbar_view");
			$this->load->view("templates/views_egel/docente_views/ver_reactivo_view", $data);
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
	} // end function
	// get data planteamiento from database
	private function get_reactivo_main_preview($id)
	{
		$reactivo = $this->reactivo_model->get_reactivo_main_preview($id);
		return $reactivo;
	} // end function
	private function get_reactivo_detail_preview($id)
	{
		$reactivo = $this->reactivo_model->get_reactivo_detail_preview($id);
		return $reactivo;
	} // end function
}
/* End of file ver_reactivo.php */
/* Location: ./application/controllers/docente/ver_reactivo.php */