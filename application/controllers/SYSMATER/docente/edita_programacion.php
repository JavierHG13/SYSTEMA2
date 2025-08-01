<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Edita_programacion extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('/SYSMATER/shared/examen_model');
		$this->load->model('/SYSMATER/shared/materia_model');
	}
	public function index($id_examen,$id_grupo)
	{
		 if (!$this->session->id_tipo == 4) {
			$this->session->set_flashdata( 'msg', '<div class="alert alert-danger text-center">¡Sesión no iniciada!</div>' );
			redirect("login");
			return;
		}
		//recupero clave de materia
		$data['examen'] = $this->examen_model->get_examen($id_examen);
		 foreach($data['examen']->result() as $cosas){
			$claveMateria=$cosas->vchClvMateria;
		}				
		
        //? ANTES DE TODO VERIFICO QUE EL GRUPO SI SEA DEL DOCENTE
		if($this->materia_model->verifica_grupo_asignado($claveMateria,$this->session->Matricula,$id_grupo, $this->determinarPeriodo())){
			//cargo mi vista de validaciones que solo me muestra los mensajes de error
			$data['msg'] = "Error: Usted no puede editar la programación de exámen para un grupo no asignado.";
            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/validacion", $data);
            $this->load->view("templates/shared/footer_view");
            return; 
		}
		$data['grupo'] = $id_grupo;	
		$data["aplicacion"] = $this->get_data_progamacion($id_examen, $id_grupo);
		$this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
		$this->load->view("templates/views_sysmater/docente_views/edita_programacion_view", $data);
		$this->load->view("templates/shared/footer_view");
			
	}
	private function actualiza_programa_examen($id)
	{
		$data = array(
			'id_examen' => $this->input->post('examen'),
			'fch_inicia' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('fecha1')))),
			'fch_termina' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('fecha2')))),
			'tm_hora_inicio' => $this->input->post('inicio'),
			'tm_hora_final' => $this->input->post('termino'),
			'tm_duracion' => $this->input->post('duracion'),
		);

		$this->examen_model->actualiza_programa_examen($data, $id);
		redirect("/sysmater/docente/docente/examenes_programados/");
	}
	private function get_data_progamacion($id_examen, $id_grupo)
	{
		$examen = $this->examen_model->get_programacion_examen($id_examen, $id_grupo);
		return $examen;
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
