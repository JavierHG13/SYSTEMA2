<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Nueva_materia extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('/SYSMATER/shared/materia_model');
		
	}

	public function index()
	{
		if ($this->session->id_tipo == 1) {
			$this->validation();

			if ($this->form_validation->run() == FALSE) {

				$this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
				$this->load->view("templates/views_sysmater/admin_views/nueva_materia_view");
				$this->load->view("templates/shared/footer_sysmater_view");

			} else {
				$datos_filtro = array(
					'vchClvMateria' => $this->input->post('clave'),
                    'vchNomMateria' => $this->input->post('nombre'),
				);
				
				if ($this->materia_model->existe_materia_admin($datos_filtro) == false) {
				
					$this->guarda_nueva_materia();

				} else {
					$data['msg'] = "La materia o clave ya existe en la base de datos";

					$this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
					$this->load->view("templates/views_sysmater/admin_views/nueva_materia_view", $data);
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

	private function guarda_nueva_materia()
	{		
		$cuatri = $this->input->post('cuatrimestre');
		if($cuatri <10){
			$cuatri = '0' . $cuatri;
		}else {
			$cuatri = $cuatri;
		}
        $data = array(
            'vchClvMateria' => $this->input->post('clave'),
            'vchNomMateria' => $this->input->post('nombre'),
            'idPeriodo' => $this->input->post('periodo'),
            'vchCuatrimestre' => $cuatri
        );

        if ($this->materia_model->inserta_materia($data)) {
            redirect("/sysmater/admin/admin/lista_materias/");
        } else {
            redirect("/sysmater/admin/admin/nueva_materia/");
        }
	}


	public function validation()
	{
		$config = array(
			array(
				'field' => 'clave',
				'label' => 'Clave de Materia',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'nombre',
				'label' => 'Nombre de Materia',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'periodo',
				'label' => 'Perdiodo',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'cuatrimestre',
				'label' => 'Cuatrimestre',
				'rules' => 'trim|required|max_length[4]'
			)
		);
		$this->form_validation->set_rules($config);
	} // end function

}

/* End of file nuevo_docente.php */
/* Location: ./application/controllers/admin/nuevo_docente.php */
