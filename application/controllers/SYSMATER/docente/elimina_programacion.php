<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Elimina_programacion extends CI_Controller
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
			$this->session->set_flashdata(
				'msg', '<div class="alert alert-danger text-center">¡Sesión no iniciada!</div>'
			);
			redirect("login");
			return;
		}
		//valido que sea solo sus examenes asignados
		if($this->examen_model->verifica_examen_asignado($this->session->Matricula,$id_examen)){
			$data['msg'] = "Error: Usted no puede eliminar la programación de un EXÁMEN no asignado.";
            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/validacion", $data);
            $this->load->view("templates/shared/footer_view");
            return; 
		}
		//valido que sean solo sus grupos asignados
		$data['examen'] = $this->examen_model->get_examen($id_examen);
		foreach($data['examen']->result() as $cosas){
			$claveMateria=$cosas->vchClvMateria;
		}
		if($this->materia_model->verifica_grupo_asignado($claveMateria,$this->session->Matricula,$id_grupo, $this->determinarPeriodo())){
			$data['msg'] = "Error: Usted no puede eliminar la programación de un GRUPO no asignado.";
            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/validacion", $data);
            $this->load->view("templates/shared/footer_view");
            return; 
		}

		$admin = $this->session->Matricula;
		$data["aplicacion"] = $this->get_data_progamacion($id_examen, $id_grupo);
		$data['examen'] = $this->examen_model->get_examen($id_examen);
		$data['grupo'] = $id_grupo;
		$this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
		$this->load->view("templates/views_sysmater/docente_views/elimina_programacion_view", $data);
		$this->load->view("templates/shared/footer_view");

	}
	// get data examen from database
	private function get_data_progamacion($id_examen, $id_grupo)
	{
		$examen = $this->examen_model->get_programacion_examen($id_examen, $id_grupo);
		return $examen;
	} 

	public function elimina_programacion_grupo($id_examen, $id_grupo)
	{
		$this->examen_model->elimina_programacion_examen($id_examen, $id_grupo);
		redirect("/sysmater/docente/docente/examenes_programados/");
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
