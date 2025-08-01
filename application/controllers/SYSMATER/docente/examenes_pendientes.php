<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Examenes_pendientes extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('/SYSMATER/shared/examen_model');
	}
	public function index()
	{
		if ($this->session->id_tipo == 4) {

			$this->validation();

			$id = $this->session->Matricula;

			$data['examenes'] = $this->examen_model->lista_examenes_sin_programar_docente($id);

			if ($this->form_validation->run() == FALSE) {

				$this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
				$this->load->view("templates/views_sysmater/docente_views/examenes_pendientes_view", $data);
				$this->load->view("templates/shared/footer_view");

			} else {

				$datos_filtro = array(
					'id_examen' => $this->input->post('examen'),
					'fch_inicia' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('fecha1')))),
					'fch_termina' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('fecha2'))))
				);
				
				if ($this->examen_model->existe_programa_examen($datos_filtro) == 'false') {

					$this->guardar_programacion_examen();

				} else {
					$data['msg'] = " La conbinación de Examen, Fecha  ya existe en la base de datos";

					$this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
					$this->load->view("templates/views_sysmater/docente_views/examenes_pendientes_view", $data);
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

	private function guardar_programacion_examen()
	{
		$fecha1 = str_replace('/', '-', $this->input->post('fecha1'));
		$fecha2 = str_replace('/', '-', $this->input->post('fecha2'));

		$duracion_str = $this->input->post('duracion'); 
		$duracion_parts = explode(":", $duracion_str);

		$horas   = isset($duracion_parts[0]) ? (int)$duracion_parts[0] : 0;
		$minutos = isset($duracion_parts[1]) ? (int)$duracion_parts[1] : 0;

		$duracion_time = sprintf('%02d:%02d:00', $horas, $minutos); 

		$data = array(
			'id_examen'      => $this->input->post('examen'),
			'fch_inicia'     => date('Y-d-m', strtotime($fecha1)),
			'fch_termina'    => date('Y-d-m', strtotime($fecha2)),
			'tm_hora_inicio' => date("H:i:s", strtotime($this->input->post('inicio'))),
			'tm_hora_final'  => date("H:i:s", strtotime($this->input->post('termino'))),
			'tm_duracion'    => $duracion_time 
		);

		$this->examen_model->guardar_programa_examen($data);
		redirect("/sysmater/docente/docente/examenes_programados");
	}

	public function obtener_grupos_por_examen()
	{
		$id_examen = $this->input->post('id_examen');
		$resultado = $this->examen_model->get_grupos_por_examen($id_examen);

		if ($resultado && isset($resultado->grupos)) {
			$grupos_array = explode(', ', $resultado->grupos);
			$response = [];

			foreach ($grupos_array as $grupo_nombre) {
				$response[] = [
					'id_grupo' => $grupo_nombre, // o un hash si no tienes el id
					'vchGrupo' => $grupo_nombre
				];
			}

			echo json_encode($response);
		} else {
			echo json_encode([]);
		}
	}



	private function validation()
	{
		// set validation
		$config = array(
			array(
				'field' => 'fecha1',
				'label' => 'fecha de inicio del examen',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'fecha2',
				'label' => 'fecha de termino del examen',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'inicio',
				'label' => 'inicio del examen',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'termino',
				'label' => 'termino del examen',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'duracion',
				'label' => 'duracion del examen',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'grupo',
				'label' => 'grupo del examen',
				'rules' => 'trim|required'
			)
		);
		$this->form_validation->set_rules($config);
	} // end function

}
