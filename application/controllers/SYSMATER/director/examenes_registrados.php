<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Examenes_registrados extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('/sysmater/shared/examen_model');
	}

	public function index($vchClvTrabajador = null)
	{
		if ($this->session->id_tipo == 2) {
			if (empty($vchClvTrabajador)) {
				$vchClvTrabajador = $this->session->vchClvTrabajador ?? $this->input->post('vchClvTrabajador');
			}

			$data['examenes'] = $this->examen_model->lista_examenes_docente($vchClvTrabajador);

			$this->load->view("templates/views_sysmater/director_views/director_navbar_view");
			$this->load->view("templates/views_sysmater/director_views/lista_examenes_view", $data);
			$this->load->view("templates/shared/footer_view");
		} else {
			$this->session->set_flashdata('msg', '
				<div class="alert alert-danger text-center"> 
					¡Sesión no iniciada! Verifique que el usuario y contraseña sean correctos.
				</div>'
			);
			redirect("login");
		}
	}

	public function filtro_materia_grupo()
{
    if ($this->input->is_ajax_request()) {
        $materia = $this->input->post('clave_materia');
        $grupo = $this->input->post('grupo');
        $periodo = (string) $this->input->post('periodo');
        $docente = $this->input->post('vchClvTrabajador');

        $examenes = $this->examen_model->lista_examenes_materia_director($materia, $periodo, $docente, $grupo);

        if ($examenes && $examenes->num_rows() > 0) {
            $output = '<div class="panel panel-default">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Materia</th>
                                            <th>Título</th>
                                            <th>Periodo</th>
                                            <th>Parcial</th>
                                            <th>Grupo</th>
                                            <th>Reactivos</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

            foreach ($examenes->result() as $e) {
                $output .= "<tr>
                    <td>{$e->id_examen}</td>
                    <td>{$e->vchClvMateria}</td>
                    <td>{$e->nvch_Titulo}</td>
                    <td>{$e->periodo}</td>
                    <td>{$e->parcial}</td>
                    <td>{$e->vchGrupo}</td>
                    <td>{$e->int_reactivos_requeridos}</td>
                </tr>";
            }
            
            $output .= '</tbody></table></div></div>';
            echo $output;
        } else {
            echo '<div class="alert alert-warning"><i class="glyphicon glyphicon-info-sign"></i> No hay exámenes registrados para esta materia y grupo.</div>';
        }
    } else {
        show_404();
    }
}

public function filtro_actividades_materia_grupo()
{
    if ($this->input->is_ajax_request()) {
        $materia = $this->input->post('clave_materia');
        $docente = $this->input->post('vchClvTrabajador');
        $grupo = $this->input->post('grupo');
        $periodo = (string) $this->input->post('periodo');

        $actividades = $this->examen_model->lista_actividades_materia($docente, $grupo, $periodo, $materia);

        if ($actividades && $actividades->num_rows() > 0) {
            $output = '<div class="panel panel-default">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Título</th>
                                            <th>Grupo</th>
                                            <th>Asignación</th>
                                            <th>Entrega</th>
                                            <th>Modalidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

            foreach ($actividades->result() as $a) {
                $output .= "<tr>
                    <td>{$a->id_actividad}</td>
                    <td>{$a->titulo}</td>
                    <td>{$a->vchGrupo}</td>
                    <td>{$a->fecha_asignacion}</td>
                    <td>{$a->fecha_entrega}</td>
                    <td>{$a->modalidad}</td>
                </tr>";
            }
            
            $output .= '</tbody></table></div></div>';
            echo $output;
        } else {
            echo '<div class="alert alert-info"><i class="glyphicon glyphicon-info-sign"></i> No hay actividades registradas para esta materia y grupo.</div>';
        }
    } else {
        show_404();
    }
}
}
