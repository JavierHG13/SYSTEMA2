<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Progreso_examen_grupo extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('/SYSMATER/shared/examen_model');
        $this->load->model('/SYSMATER/shared/docente_model');
        $this->load->model('/SYSMATER/shared/alumno_model');
        $this->load->model('/SYSMATER/shared/materia_model');
    }
    public function index($id_examen, $grupo)
    {
        if ($this->session->id_tipo == 4) {

        //? ANTES DE TODO VERIFICO QUE EL GRUPO SI SEA DEL DOCENTE
        $data['examen'] = $this->examen_model->get_examen($id_examen);
		foreach($data['examen']->result() as $cosas){
			$claveMateria=$cosas->vchClvMateria;
		}	
		if($this->materia_model->verifica_grupo_asignado($claveMateria,$this->session->Matricula,$grupo, $this->determinarPeriodo())){
			$data['msg'] = "Error: Usted no puede monitorear grupos no asignados.";
            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/validacion", $data);
            $this->load->view("templates/shared/footer_view");
            return; 
		}

        //PENDIENTES
        $lista_pendientes = $this->alumno_model->lista_pendientes_grupo($id_examen, $grupo);
        if ($lista_pendientes !== NULL) {
            $data['alumnos_faltantes'] = $lista_pendientes;
        }
        //PROGRESO
        $lista_progreso = $this->alumno_model->lista_progreso_grupo($id_examen, $grupo);
        if ($lista_progreso !== NULL) {
                $data['alumnos_en_progreso'] =  $lista_progreso;
            }
        //FINALIZADOS
        $lista_fin_examen = $this->alumno_model->lista_fin_examen_grupo($id_examen, $grupo);
        if ($lista_fin_examen !== NULL) {
            $data['alumnos_fin_examen'] = $lista_fin_examen;
        }
        //EXAMEN
        $data['examen'] = $this->examen_model->get_examen($id_examen);
        $data['gruposs'] = $grupo;

        $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
        $this->load->view("templates/views_sysmater/docente_views/progreso_examen_grupo_view", $data);
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
}
