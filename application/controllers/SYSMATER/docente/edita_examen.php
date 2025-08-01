<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Edita_examen extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('/SYSMATER/shared/examen_model');
    }

    public function index()
    {
        if ($this->session->id_tipo == 4) {            
            $id = $this->uri->segment($this->uri->total_segments());            
            $this->validation();
            $admin = $this->session->Matricula;

            $data['tipos_examen'] = $this->examen_model->lista_tipos_examen();
            $data["examen"] = $this->examen_model->get_examen($id);
            
            if($data['examen'] !== NULL) {
                $row = $data['examen']->row();
                
                $data['grupos_actuales'] = explode(', ', $row->vchGrupo);
                $data['grupos_disponibles'] = $this->examen_model->get_grupos_por_materia($row->vchClvMateria,$admin);
                
                $data['parcial_actual'] = $row->parcial;
                $data['tipo_examen_selected'] = set_value('tipo_examen', $row->tipo_Examen);
                $data['titulo_actual'] = set_value('titulo', $row->nvch_Titulo);
                $data['nreactivos_actual'] = set_value('nreactivos', $row->int_reactivos_requeridos);
                $data['clave_actual'] = set_value('clave', $row->nvch_clave);
                $data['materia_nombre'] = $row->vchNomMateria;
                $data['materia_clave'] = $row->vchClvMateria;
                $data['id_examen'] = $row->id_examen;
                $titulo= $row->nvch_Titulo;
            }

            if ($this->form_validation->run() == FALSE) {
                if($data['examen'] === NULL){
                    $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
                    $this->load->view("templates/views_sysmater/docente_views/Sin_asignacion");
                    $this->load->view("templates/shared/footer_view");
                }else{
                    $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
                    $this->load->view("templates/views_sysmater/docente_views/edita_examen_view", $data);
                    $this->load->view("templates/shared/footer_view");
                }
            } else {
                $datos_filtro = array(
                    'vchClvMateria' => $this->input->post('materia'),
                    'nvch_Titulo' => $this->input->post('titulo'),
                    'periodo' => $this->determinarPeriodo()
                );
				if ($titulo == $this->input->post('titulo') ) {
					$this->actualiza_examen($id);
				} else if($this->examen_model->existe_examen($datos_filtro)){
                    $data['msg'] = "Elija otro Título. La combinación de Materia, Periodo y Título ya existe en la base de datos.";
					$this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
					$this->load->view("templates/views_sysmater/docente_views/edita_examen_view", $data);
					$this->load->view("templates/shared/footer_view");
                } else {
                    $this->actualiza_examen($id);
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

    private function actualiza_examen($id)
    {
        $gruposSeleccionados = $this->input->post('grupos');
		
        $data = array(
            'vchClvMateria' => $this->input->post('materia'),
            'nvch_Titulo' => $this->input->post('titulo'),
            'int_reactivos_requeridos' => $this->input->post('nreactivos'),
            'nvch_clave' => $this->input->post('clave'),
            'tipo_Examen' => $this->input->post('tipo_examen')
        );

        $this->examen_model->actualiza_examen($data, $id, $gruposSeleccionados);
        redirect("/sysmater/docente/docente/examenes_registrados/");
    }

    private function validation()
    {
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
            ),
            array(
                'field' => 'grupos[]',
                'label' => 'Grupos',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'Debe seleccionar al menos un grupo.'
                )
            )
        );
        $this->form_validation->set_rules($config);
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
}

