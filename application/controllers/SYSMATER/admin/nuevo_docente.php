<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Nuevo_docente extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('/SYSMATER/shared/docente_model');		
	}

	public function index()
	{
		if ($this->session->id_tipo == 1) {
			$this->validation();

			if ($this->form_validation->run() == FALSE) {

				$this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
				$this->load->view("templates/views_sysmater/admin_views/nuevo_docente_view");
				$this->load->view("templates/shared/footer_sysmater_view");

			} else {
				$datos_filtro = array(
					'chrClvTrabajador' => $this->input->post('clave'),
				);
				
				if ($this->docente_model->existe_docente($datos_filtro) == false) {
				
					$this->guardar_docente();

				} else {
					// este es el mensaje personalozado
					$data['msg'] = "El docente ya existe en la base de datos";

					$this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
					$this->load->view("templates/views_sysmater/admin_views/nuevo_docente_view", $data);
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
		} 
	}

	private function guardar_docente()
	{
		$data = array(
			'vchClvTrabajador' => $this->input->post('clave'),
			'vchNombre' => $this->input->post('nombre'),
			'vchAPaterno' => $this->input->post('apellido_paterno'),
			'vchAMaterno' => $this->input->post('apellido_materno'),
			'vchContrasenia' =>$this->input->post('clave') ,
			'intActivo' => 1,
			'id_Tipo_Usuario' => 18,
		);

		$this->docente_model->nuevo_docente($data);
		redirect("/sysmater/admin/admin/lista_docente/");
	}

	private function validation()
	{
		$config = array(
			array(
				'field' => 'nombre',
				'label' => 'Nombre del Docente',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'apellido_paterno',
				'label' => 'Apellido Paterno',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'apellido_materno',
				'label' => 'Apellido Materno',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'clave',
				'label' => 'Clave del Trabajador',
				'rules' => 'trim|required|max_length[4]'
			)
		);
		$this->form_validation->set_rules($config);
	} 
}
