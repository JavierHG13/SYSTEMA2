<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Nuevo_examen extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('/SYSMATER/shared/examen_model');
		$this->load->model('/SYSMATER/shared/docente_model');
	}

	public function index()
    {
        if ($this->session->id_tipo != 4) {
            $this->session->set_flashdata(
                'msg',
                '<div class="alert alert-danger text-center"> 
                ¡Sesión no iniciada! Verifique que el usuario y contraseña sean correctos.
                </div>'
            );
            redirect("login");
            return;
        }

        $docente = $this->session->Matricula;
        $periodo = $this->determinarPeriodo();

        $data['materias'] = $this->docente_model->lista_materias_asignadas_docente($docente);

        if ($data['materias'] === NULL || $data['materias']->num_rows() === 0) {
            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/Sin_asignacion");
            $this->load->view("templates/shared/footer_view");
            return;
        }

        $data['tipos_examen'] = $this->examen_model->lista_tipos_examen();

        $this->validation();
        if ($this->form_validation->run() == FALSE) {
            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/nuevo_examen_view", $data);
            $this->load->view("templates/shared/footer_view");
        } else {
            $datos_filtro = array(
                'vchClvMateria' => $this->input->post('materia'),
                'nvch_Titulo' => $this->input->post('titulo'),
                'periodo' => $this->input->post('periodo'),
            );

            if (!$this->examen_model->existe_examen($datos_filtro)) {
                $this->guardar_examen();
            } else {
                $data['msg'] = "La combinación de Materia y Título ya existe en la base de datos.";
                $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
                $this->load->view("templates/views_sysmater/docente_views/nuevo_examen_view", $data);
                $this->load->view("templates/shared/footer_view");
            }
        }
    }

	private function guardar_examen()
	{
		$vchClvTrabajador = $this->session->userdata('Matricula');
		$grupos = $this->input->post('grupos');
		$cuatri = $this->input->post('Cuatrimestre');
		$reactivos=$this->input->post('nreactivos');


		 $data = array(
			'vchClvMateria' => $this->input->post('materia'),
			'nvch_Titulo' => $this->input->post('titulo'),
			'int_reactivos_requeridos' => $reactivos,
			'nvch_clave' => $this->input->post('clave'),
			'vchCuatrimestre' => $cuatri,
			'periodo' => $this->input->post('periodo'),
			'parcial' => $this->input->post('parcial'),
			'tipo_Examen' => $this->input->post('tipo_examen'),
			'vchClvTrabajador' => $vchClvTrabajador,
			'id_examen_temp' => 1028
		);

		if(!$this->examen_model->insertar_examen($data,$grupos)){
			redirect(site_url("/sysmater/docente/docente/examenes_pendientes/"));
		} else{
			redirect(site_url("/sysmater/docente/docente/examenes_registrados/"));
		}
	}


	private function determinarPeriodo() {
		$fechaActual = new DateTime();
		$mes = (int) $fechaActual->format('m');
		$año = $fechaActual->format('Y');
		$periodo = 0;

		if ($mes >= 1 && $mes <= 4) {
			$periodo = 1;
		} elseif ($mes >= 5 && $mes <= 8) {
			$periodo = 2;
		} elseif ($mes >= 9 && $mes <= 12) {
			$periodo = 3;
		} else {
			$periodo = 4;
		}

		return $año . $periodo;
	}

	private function validation()
{
	$config = array(
		array(
			'field' => 'tipo_examen',
			'label' => 'Tipo de examen',
			'rules' => 'required|greater_than[0]',
			'errors' => array(
				'required' => 'Debe seleccionar un %s.',
				'greater_than' => 'Debe seleccionar un %s válido.'
			)
		),
		array(
			'field' => 'materia',
			'label' => 'Materia',
			'rules' => 'required'
		),
		array(
			'field' => 'parcial',
			'label' => 'Parcial',
			'rules' => 'required|greater_than[0]',
			'errors' => array(
				'required' => 'Debe seleccionar un %s.',
				'greater_than' => 'Debe seleccionar un %s válido.'
			)
		),
		array(
			'field' => 'titulo',
			'label' => 'Título del examen',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'grupos[]',
			'label' => 'Grupo',
			'rules' => 'required',
			'errors' => array(
				'required' => 'Debe seleccionar al menos un %s.'
			)
		),

		array(
			'field' => 'clave',
			'label' => 'Clave de acceso',
			'rules' => 'trim|required|max_length[8]'
		),
		array(
			'field' => 'nreactivos',
			'label' => 'Reactivos requeridos',
			'rules' => 'trim|required|numeric|max_length[3]'
		)
	);

	$this->form_validation->set_rules($config);
}

}
