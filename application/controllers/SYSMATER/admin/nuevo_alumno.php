<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Nuevo_Alumno extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('/SYSMATER/shared/alumno_model');
		
	}

	public function index()
	{
		if ($this->session->id_tipo == 1) {
			$this->validation();

			if ($this->form_validation->run() == FALSE) {

				$this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
				$this->load->view("templates/views_sysmater/admin_views/nuevo_alumno_view");
				$this->load->view("templates/shared/footer_sysmater_view");

			} else {
				$datos_filtro = array(
					'vchMatricula' => $this->input->post('matricula')
				);
				
				if ($this->alumno_model->existe_matricula($datos_filtro) == false) {
				
					$this->guarda_nuevo_alumno();

				} else {
					$data['msg'] = "La matricula ya existe en la base de datos";

					$this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
					$this->load->view("templates/views_sysmater/admin_views/nuevo_alumno_view", $data);
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
	private function guarda_nuevo_alumno()
	{	
        $cuatri= $this->input->post('cuatrimestre');
        if($cuatri<10){
            $cuatri = '0' . $cuatri; 
        }else{
            $cuatri = $cuatri;
        }
        $periodo = $this->determinarPeriodo();

        $data = array(
			'vchMatricula' => $this->input->post('matricula'),
			'vchAPaterno' => $this->input->post('apellido_paterno'),
			'vchAMaterno' => $this->input->post('apellido_materno'),
			'vchNombre' => $this->input->post('nombre'),
			'vchContrasenia' => $this->input->post('matricula'),
			'intActivo' => 1,
			'chrClvCarrera' => $this->input->post('carrera'),
			'chrClvEspecialidad' => '00',
			'vchClvCuatri' => $cuatri,
			'chvGrupo' => $this->input->post('grupo'),
            'dtmFchInsc' => date('Ymd H:i:s'),
			'vchPeriodo' => $periodo,
            'id_Tipo_Usuario' => 21,
        );

        $this->alumno_model->inserta_alumno($data);
        redirect("/sysmater/admin/alumnos/");
	}
    //funcion agregara 05/06/25
    public function get_carreras(){
        $ok = $this->input->post('ok');
        if($ok == 'ok'){
            $query = $this->alumno_model->traer_carreas();
        }else {
            $query = NULL;
        }        

        if ($query !== NULL) {
            echo json_encode($query->result()); 
        } else {
            echo json_encode([]); 
        }
    }

        public function get_materias_detalles(){
        $ok = $this->input->post('ok');
        if($ok == 'ok'){
            $query = $this->alumno_model->traer_materias();
        }else {
            $query = NULL;
        }        

        if ($query !== NULL) {
            echo json_encode($query->result()); 
        } else {
            echo json_encode([]); 
        }
    }

    private function determinarPeriodo()
    {
        $fechaActual = new DateTime();
        $mes = (int) $fechaActual->format('m');
        $año = $fechaActual->format('Y');
        
        if ($mes >= 1 && $mes <= 4) {
            return $año . '1'; // Enero - Abril
        } elseif ($mes >= 5 && $mes <= 8) {
            return $año . '2'; // Mayo - Agosto
        } else {
            return $año . '3'; // Septiembre - Diciembre
        }
    }

	public function validation()
{
    $this->load->library('form_validation');
    $config = array(
        array(
            'field' => 'matricula',
            'label' => 'Matrícula',
            'rules' => 'trim|required|min_length[8]'
        ),
        array(
            'field' => 'nombre',
            'label' => 'Nombre',
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
            'field' => 'carrera',
            'label' => 'Carrera',
            'rules' => 'required'
        ),
        array(
            'field' => 'cuatrimestre',
            'label' => 'Cuatrimestre',
            'rules' => 'required'
        ),
        array(
            'field' => 'grupo',
            'label' => 'Grupo',
            'rules' => 'required'
        )
    );

    $this->form_validation->set_rules($config);
}
 // end function

}

/* End of file nuevo_docente.php */
/* Location: ./application/controllers/admin/nuevo_docente.php */