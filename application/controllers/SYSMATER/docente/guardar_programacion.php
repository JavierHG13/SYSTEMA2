<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Guardar_programacion extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('/SYSMATER/shared/examen_model');	
		$this->load->model('/SYSMATER/shared/materia_model');
	}
	public function index($id_examen, $grupo)
	{
		if (!$this->session->id_tipo == 4) {
			$this->session->set_flashdata(
				'msg', '<div class="alert alert-danger text-center">¡Sesión no iniciada!</div>'
			);
			redirect("login");
			return;
		}

		$data['examen'] = $this->examen_model->get_examen($id_examen);
		foreach($data['examen']->result() as $cosas){
			$claveMateria=$cosas->vchClvMateria;
		}				
		$data['grupo'] = $grupo;		
		//? ANTES DE TODO VERIFICO QUE EL GRUPO SI SEA DEL DOCENTE
		if($this->materia_model->verifica_grupo_asignado($claveMateria,$this->session->Matricula,$grupo, $this->determinarPeriodo())){
			$data['msg'] = "Error: Usted no puede programar exámenes para otros grupos no asignados.";
            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/programar_examen_view", $data);
            $this->load->view("templates/shared/footer_view");
            return; 
		}

		//?  1 - VALIDO FECHAS
		$fecha1_str = $this->input->post('fecha1');
        $fecha2_str = $this->input->post('fecha2');
		//conversion
		$fecha1_formato = str_replace('/', '-', $fecha1_str);
        $fecha2_formato = str_replace('/', '-', $fecha2_str);

        $fecha_inicio_dt = new DateTime($fecha1_formato);
        $fecha_fin_dt = new DateTime($fecha2_formato);

        if ($fecha_inicio_dt > $fecha_fin_dt) {
            $data['msg'] = "Error: La fecha de inicio no puede ser mayor a la fecha de término.";
            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/programar_examen_view", $data);
            $this->load->view("templates/shared/footer_view");
            return; 
        }
		//?  2 - VALIDO HORAS
		$inicio_str = $this->input->post('inicio');
        $fin_str = $this->input->post('termino');
		$hora_inicio_dt = new DateTime($inicio_str);
        $hora_fin_dt = new DateTime($fin_str);

        if ($hora_fin_dt < $hora_inicio_dt) {
            $data['msg'] = "Error: La hora de fin no puede ser menor a la hora de inicio.";
            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/programar_examen_view", $data);
            $this->load->view("templates/shared/footer_view");
            return; 
        }

		//? 3 - VALIDO TIEMPO ENTRE INICIO-FIN
		$duracion_str = $this->input->post('duracion'); 

		// Convertir la duración ingresada a segundos para fácil comparación
		list($horas_duracion, $minutos_duracion) = explode(":", $duracion_str);
		$total_segundos_duracion_ingresada = ($horas_duracion * 3600) + ($minutos_duracion * 60);

		if ($hora_fin_dt < $hora_inicio_dt) {
			$data['msg'] = "Error: La hora de fin no puede ser anterior a la hora de inicio.";
			$this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
			$this->load->view("templates/views_sysmater/docente_views/programar_examen_view", $data);
			$this->load->view("templates/shared/footer_view");
			return;
		}
		// Calcular la diferencia entre las dos horas
		$intervalo = $hora_inicio_dt->diff($hora_fin_dt);
		// Obtener la duración disponible en segundos
		$total_segundos_disponibles = ($intervalo->h * 3600) + ($intervalo->i * 60) + $intervalo->s;

		if ($total_segundos_duracion_ingresada > $total_segundos_disponibles) {
			$data['msg'] = "Error: La duración del examen ({$duracion_str}) es mayor que el tiempo disponible en el horario ({$inicio_str} - {$fin_str}).";
			$this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
			$this->load->view("templates/views_sysmater/docente_views/programar_examen_view", $data);
			$this->load->view("templates/shared/footer_view");
			return;
		}

		//? 4 - ULTIMA VALIDACION DE HORARIOS
		$datos_filtro = array(
			'periodo' => $this->determinarPeriodo(),
			'materia' => $claveMateria,
			'iddocente' => $this->session->Matricula,
			'grupo' => $grupo,
			'hora_inicio' => date("H:i:s", strtotime($this->input->post('inicio'))),
			'hora_final'  => date("H:i:s", strtotime($this->input->post('termino')))
		);

		if ($this->examen_model->existe_conflicto_horario($datos_filtro)) {
			$data['msg'] = "Error: El rango de horario seleccionado se encuentra dentro del rango ya programado por usted en otro grupo para esta materia($claveMateria).";
			$this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
			$this->load->view("templates/views_sysmater/docente_views/programar_examen_view", $data);
			$this->load->view("templates/shared/footer_view");
		} else {
			$this->guardar_programacion_examen($id_examen, $grupo);
		}
	}


	//! finalmente guardo
	private function guardar_programacion_examen($id_examen, $grupo){
		// Procesamiento de fechas
		$fecha1 = str_replace('/', '-', $this->input->post('fecha1'));
		$fecha2 = str_replace('/', '-', $this->input->post('fecha2'));
		
		// Procesamiento de duración
		$duracion_str = $this->input->post('duracion'); 
		$duracion_parts = explode(":", $duracion_str);
		$horas   = isset($duracion_parts[0]) ? (int)$duracion_parts[0] : 0;
		$minutos = isset($duracion_parts[1]) ? (int)$duracion_parts[1] : 0;
		$duracion_time = sprintf('%02d:%02d:00', $horas, $minutos); 

		// Obtener ID del grupo
		$id_grupo = $this->examen_model->obtener_id_grupo($grupo);
		if (!$id_grupo) {
			$this->session->set_flashdata('error', 'Grupo no encontrado');
			redirect("/sysmater/docente/docente/programar_examen_grupo/{$id_examen}/{$grupo}");
			return;
		}

		// Preparar datos para inserción
		$data = array(
			'id_examen'      => $id_examen,
			'fch_inicia'     => date('Y-m-d H:i:s', strtotime($fecha1)),
			'fch_termina'    => date('Y-m-d H:i:s', strtotime($fecha2)),
			'tm_hora_inicio' => date("H:i:s", strtotime($this->input->post('inicio'))),
			'tm_hora_final'  => date("H:i:s", strtotime($this->input->post('termino'))),
			'tm_duracion'    => $duracion_time,
			'id_grupo'       => $id_grupo
		);

		if ($this->examen_model->guardar_programa_examen($data)) {
			redirect("/sysmater/docente/docente/examenes_pendientes/");
		} else {
			redirect("/sysmater/docente/docente/programar_examen_grupo/{$id_examen}/{$grupo}");
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
}
