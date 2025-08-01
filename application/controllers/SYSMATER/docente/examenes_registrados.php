<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Examenes_registrados extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('/sysmater/shared/examen_model');
	}

	public function index()
	{
		if ($this->session->id_tipo == 4) {

			$vchClvTrabajador = $this->session->Matricula;

			$examenes = $this->examen_model->lista_examenes_docente($vchClvTrabajador);
			$data['examenes'] = $examenes;

			// Período actual
			$data['periodo_actual'] = $this->determinarPeriodo();

			$periodos = [];
			if ($examenes && $examenes->num_rows() > 0) {
				foreach ($examenes->result() as $ex) {
					$periodos[$ex->periodo] = $ex->periodo; 
				}
			}
			krsort($periodos);                 // opcional: de más reciente a más antiguo
			$data['periodos'] = $periodos; 

			// Cargar vistas
			$this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
			$this->load->view("templates/views_sysmater/docente_views/examenes_registrados_view", $data);
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
