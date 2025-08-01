<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Nuevo_examen extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('/SYSMATER/shared/examen_model');
		$this->load->model('/SYSMATER/shared/carrera_model');
	}
	public function index()
	{
		if ($this->session->id_tipo == 1) {
			$this->validation();

			$admin = $this->session->Matricula;
			
			$data['carreras'] = $this->carrera_model->lista_carreras();
			if ($this->form_validation->run() == FALSE) {
				$this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
				$this->load->view("templates/views_sysmater/admin_views/nuevo_examen_view", $data);
				$this->load->view("templates/shared/footer_view");
			} else {
				$datos_filtro = array(
					'chrClvCarrera' => $this->input->post('carrera'),
					'nvch_Titulo' => $this->input->post('titulo')
				);

				if ($this->examen_model->existe_examen($datos_filtro) == false) {
					$this->guardar_examen();
				} else {
					$data['msg'] = " La conbinación de Carrera, Titulo  ya existe en la base de datos";

					$this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
					$this->load->view("templates/views_sysmater/admin_views/nuevo_examen_view", $data);
					$this->load->view("templates/shared/footer_view");
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

	private function guardar_examen()
	{

		$data = array(
			'chrClvCarrera' => $this->input->post('carrera'),
			'nvch_Titulo' => $this->input->post('titulo'),
			'int_reactivos_requeridos' => $this->input->post('nreactivos'),
			'nvch_clave' => $this->input->post('clave'),
			'system' => 'EGEL',
			'vchCuatrimestre' => '11'

		);
		
		$this->examen_model->insertar_examen($data);
		redirect("/EGEL/admin/admin/examenes_registrados/");
	}
	private function validation()
	{
		// set validation
		$config = array(
			array(
				'field' => 'titulo',
				'label' => 'Titulo del examen',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'clave',
				'label' => 'Clave de acceso',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'nreactivos',
				'label' => 'Reactivos requeridos',
				'rules' => 'trim|required'
			)
		);
		$this->form_validation->set_rules($config);
	} // end function

}

/* End of file nuevo_examen.php */
/* Location: ./application/controllers/admin/nuevo_examen.php */