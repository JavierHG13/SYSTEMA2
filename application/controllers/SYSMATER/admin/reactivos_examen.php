<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reactivos_examen extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('/EGEL/shared/examen_model');
		$this->load->model('/EGEL/shared/carrera_model');
	}
	public function index()
	{
		if ($this->session->id_tipo == 1) {

			$id_examen = $this->uri->segment($this->uri->total_segments());
			

			$this->validation();

			$admin = $this->session->Matricula;
			$data["examen"] = $this->get_data_examen($id_examen);
			if ($this->form_validation->run() == FALSE) {
				$this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
				$this->load->view("templates/views_sysmater/admin_views/reactivos_examen_view", $data);
				$this->load->view("templates/shared/footer_view");
			} else {
				if ($this->input->post("task") == "add") {
					$this->selecciona_reactivos($id_examen);
				} elseif ($this->input->post("task") == "remove") {
					$this->deseleccionar_reactivos($id_examen);
				}
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
	}
	private function selecciona_reactivos($id_examen)
	{
		for ($i = 0; $i < count($this->input->post("chk_select")); $i++) {
			$id_reactivo_main = $this->input->post('chk_select[' . $i . ']');

			$data = array(
				'id_examen' => $id_examen,
				'id_reactivo_main' => $id_reactivo_main
			);
			$this->examen_model->seleccionar_reactivo($data);
		}
		redirect("/SYAMTER/admin/admin/reactivos_examen/" . $id_examen);
		//redirect("/admin/admin/examenes_registrados/");
	}
	private function deseleccionar_reactivos($id_examen)
	{
		for ($i = 0; $i < count($this->input->post("chk_eliminar")); $i++) {
			$id_reactivo_main = $this->input->post('chk_eliminar[' . $i . ']');

			$data = array(
				'id_examen' => $id_examen,
				'id_reactivo_main' => $id_reactivo_main
			);
			$this->examen_model->deseleccionar_reactivos($data);
		}
		redirect("/SYSMATER/admin/admin/reactivos_examen/" . $id_examen);
		//redirect("/admin/admin/examenes_registrados/");
	}
	private function validation()
	{
		// set validation
		$config = array(
			array(
				'field' => 'task',
				'label' => 'acccion requerida',
				'rules' => 'trim|required'
			)
		);
		$this->form_validation->set_rules($config);
	} // end function



	// get data examen from database
	private function get_data_examen($id)
	{
		$examen = $this->examen_model->get_examen_datos_reactivos($id);
		return $examen;
	} // end function

}

/* End of file reactivos_examen.php */
/* Location: ./application/controllers/admin/reactivos_examen.php */