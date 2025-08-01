<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Elimina_reactivo extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('/EGEL/shared/reactivo_model');
	}

	public function index()
	{
		if ($this->session->id_tipo == 4) {

			$task = $task = $this->input->post('task');
			
			$id_reactivo_main = $this->uri->segment($this->uri->total_segments());

			if ($task != 'delete') {

				$data["reactivo_main"] = $this->get_reactivo_main_preview($id_reactivo_main);
				$data["reactivo_detail"] = $this->get_reactivo_detail_preview($id_reactivo_main);
				$this->load->view("templates/views_egel/docente_views/docente_navbar_view");
				$this->load->view("templates/views_egel/docente_views/elimina_reactivo_view", $data);
				$this->load->view("templates/shared/footer_view");
			} else {
				$this->elimina_reactivo($id_reactivo_main);
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
	private function elimina_reactivo($id_reactivo_main)
	{
		$this->reactivo_model->elimina_reactivo($id_reactivo_main);
		$borrar_archivo = $id_reactivo_main . "_Main*.*";
		$path_images = 'uploads/' . $borrar_archivo;
		foreach (glob($path_images) as $filename) {
			unlink($filename);
		}
		redirect("/egel/docente/lista_reactivos");
	}


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

/* End of file elimina_reactivo.php */
/* Location: ./application/controllers/docente/elimina_reactivo.php */