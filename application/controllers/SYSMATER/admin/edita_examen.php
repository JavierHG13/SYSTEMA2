<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Edita_examen extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('/SYSMATER/shared/examen_model');
		$this->load->model('/SYSMATER/shared/docente_model');

	}
	public function index()
	{
		if ($this->session->id_tipo == 1) {
			
			$id = $this->uri->segment($this->uri->total_segments());
			
			$this->validation();

			$admin = $this->session->Matricula;
			$periodo = $this->determinarPeriodo();

			$data['tipos_examen'] =$this->examen_model->lista_tipos_examen();
			$data['materias'] = $this->docente_model->lista_docentes_asignacion($admin,$periodo);
			$data["examen"] = $this->get_data_examen($id);

			if ($this->form_validation->run() == FALSE) {

				if($data['materias'] === NULL){
					$this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
					$this->load->view("templates/views_sysmater/admin_views/Sin_asignacion");
					$this->load->view("templates/shared/footer_view");
				}else{
					$this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
					$this->load->view("templates/views_sysmater/admin_views/edita_examen_view", $data);
					$this->load->view("templates/shared/footer_view");
				}
				
			} else {
				
				$datos_filtro = array(
					'vchClvMateria' => $this->input->post('materia'),
					'nvch_Titulo' => $this->input->post('titulo'),
					'periodo'=> $this->input->post('periodo'),
				);


				if ($this->examen_model->existe_examen($datos_filtro) == false) {

					$this->actualiza_examen($id);
				} else {
					$data['msg'] = " La conbinación de Carrera, Cuatrimestre, Materia, Docente ya existe en el perido seleccionado";

					$this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
					$this->load->view("templates/views_sysmater/admin_views/edita_examen_view", $data);
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
	private function actualiza_examen($id)
	{

		// Obtener grupos seleccionados y convertirlos a una cadena separada por comas
		$gruposSeleccionados = $this->input->post('grupos');
		$grupos = is_array($gruposSeleccionados) ? implode(', ', $gruposSeleccionados) : '';

		$data = array(
			'vchClvMateria' => $this->input->post('materia'),
			'nvch_Titulo' => $this->input->post('titulo'),
			'int_reactivos_requeridos' => $this->input->post('nreactivos'),
			'nvch_clave' => $this->input->post('clave'),
			'parcial'=> $this->input->post('parcial'),
			'tipo_Examen'=> $this->input->post('tipo_examen'),
			'vchGrupo' => $grupos 

		);

		$this->examen_model->actualiza_examen($data, $id);
		redirect("/sysmater/admin/admin/examenes_registrados/");
	}
	private function validation()
	{
		// set validation
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
			)
		);
		$this->form_validation->set_rules($config);
	} // end function



	// get data examen from database
	private function get_data_examen($id)
	{
		$examen = $this->examen_model->get_examen($id);
		return $examen;
	} // end function


	
	function determinarPeriodo() {
		$fechaActual = new DateTime();
		$mes = (int) $fechaActual->format('m');
		$año = $fechaActual->format('Y');
		$periodo = 0;
	
		if ($mes >= 1 && $mes <= 4) {
			$periodo = 1; // Enero - Abril
		} elseif ($mes >= 5 && $mes <= 8) {
			$periodo = 2; // Mayo - Agosto
		} elseif ($mes >= 9 && $mes <= 12) {
			$periodo = 3; // Septiembre - Diciembre
		} else {
			$periodo = 4; // Caso muy raro
		}
	
		return $año . $periodo;
	}


}
