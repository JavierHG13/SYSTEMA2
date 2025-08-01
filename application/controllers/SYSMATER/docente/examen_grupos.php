<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Examen_grupos extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('/SYSMATER/shared/examen_model');
		$this->load->model('/SYSMATER/alumno/test_model', 'test');
	}

	public function index()
	{
		if ($this->session->id_tipo == 4) {

			$id = $this->uri->segment($this->uri->total_segments());
		
			$data["examenes"] = $this->get_data_examen($id);
			
			$this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/examen_grupos_view", $data);
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
	
    public function get_grupos_ajax($id_examen)
	{
		if (!$this->input->is_ajax_request()) {
			show_404();
		}

		try {
			// Depuración - verifica que el ID llegue correctamente
			log_message('debug', 'Solicitud AJAX recibida para examen ID: '.$id_examen);
			
			$resultado = $this->examen_model->get_grupos_por_examen($id_examen);
			
			if (!$resultado) {
				log_message('error', 'No se encontró resultado para examen ID: '.$id_examen);
				throw new Exception("No se encontraron grupos para este examen");
			}
			
			// Depuración - verifica los datos recibidos
			log_message('debug', 'Datos recibidos del modelo: '.print_r($resultado, true));
			
			$grupos = explode(', ', $resultado->grupos);
			$grupos = array_filter($grupos);
			
			if (empty($grupos)) {
				log_message('warning', 'Lista de grupos vacía para examen ID: '.$id_examen);
			}
			
			$response = [
				'success' => true,
				'grupos' => array_values($grupos)
			];
			
			log_message('debug', 'Respuesta JSON preparada: '.print_r($response, true));
			
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($response));
				
		} catch (Exception $e) {
			log_message('error', 'Error en get_grupos_ajax: '.$e->getMessage());
			
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode([
					'success' => false,
					'error' => $e->getMessage()
				]));
		}
	}
	private function get_data_examen($id)
	{
		$examen = $this->examen_model->get_examen($id);
		return $examen;
	} 

public function get_data_examen_ajax($id)
{
    $examen = $this->examen_model->get_examen_ajax($id);

    if ($examen) {
        // Preparamos la lista para enviar
        $programacion = [];

        foreach ($examen as $row) {
            $programacion[] = [
                'fch_inicia'     => $row->fch_inicia,
                'fch_termina'    => $row->fch_termina,
                'tm_hora_inicio' => $row->tm_hora_inicio,
                'tm_hora_final'  => $row->tm_hora_final,
                'tm_duracion'    => $row->tm_duracion,
                'vchGrupo'       => $row->vchGrupo,
				'nvch_Titulo'    => $row->nvch_Titulo
            ];
        }

        echo json_encode([
            'success' => true,
            'programacion' => $programacion
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'No se encontró programación para este examen.'
        ]);
    }
}




}