<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Alumnos extends CI_Controller
{
   // En Alumnos.php

public function __construct()
{
    parent::__construct();
    $this->load->model('/SYSMATER/shared/alumno_model');
    $this->load->model('/SYSMATER/shared/carrera_model');
    $this->load->library('session');
    $this->load->helper('url');

    $this->periodo_actual = $this->determinarPeriodo();
    $this->periodos_disponibles = $this->alumno_model->get_periodos_disponibles();
}

public function index()
{
    $this->load_filtros($this->periodo_actual, 0, 0);
}

public function filtrar()
{
    $periodo = $this->input->post('periodo') ?: $this->periodo_actual;
    $cuatrimestre = $this->input->post('cuatrimestre') ?: 0;
    $grupo_id = $this->input->post('grupo') ?: 0;

    $this->session->set_userdata([
        'filtro_periodo' => $periodo,
        'filtro_cuatrimestre' => $cuatrimestre,
        'filtro_grupo' => $grupo_id
    ]);

    redirect('/sysmater/admin/alumnos/resultados');
}

public function resultados()
{
    $periodo = $this->session->userdata('filtro_periodo') ?: $this->periodo_actual;
    $cuatrimestre = $this->session->userdata('filtro_cuatrimestre') ?: 0;
    $grupo_id = $this->session->userdata('filtro_grupo') ?: 0;

    $this->load_filtros($periodo, $cuatrimestre, $grupo_id);
}

public function load_filtros($periodo, $cuatrimestre, $grupo_id)
{
    if ($this->session->id_tipo != 1) {
        $this->GotoLogin();
        return;
    }

    // Obtener cuatrimestres para el periodo seleccionado
    $cuatrimestres = $this->alumno_model->get_cuatrimestres_por_periodo($periodo); //1ref
    
    // Obtener grupos si hay cuatrimestre seleccionado
    $grupos_filtrados = ($cuatrimestre != 0) ? 
        $this->alumno_model->get_grupos_por_cuatrimestre_periodo($cuatrimestre, $periodo) : [];
    
    // Obtener alumnos filtrados
    $alumnos = $this->alumno_model->filtrar_Alumnos($cuatrimestre, $grupo_id, $periodo);
    
    // Mensajes condicionales
    $msg = '';
    if (empty($cuatrimestres)) {
        $msg = "No hay cuatrimestres con alumnos en el periodo seleccionado ($periodo)";
    } 
    elseif ($cuatrimestre != 0 && (empty($alumnos) || $alumnos->num_rows() == 0)) {
        if ($grupo_id != 0) {
            $msg = "No hay alumnos en el grupo seleccionado para el periodo $periodo";
        } else {
            $msg = "No hay alumnos en el cuatrimestre seleccionado para el periodo $periodo";
        }
    } 
    elseif (empty($alumnos) || $alumnos->num_rows() == 0) {
        $msg = "No hay alumnos registrados en el periodo $periodo";
    }

    $data = [
        'alumnos' => $alumnos,
        'periodos_disponibles' => $this->periodos_disponibles,
        'periodo_seleccionado' => $periodo,
        'cuatrimestres' => $cuatrimestres,
        'grupos_filtrados' => $grupos_filtrados,
        'filtro_actual' => [
            'periodo' => $periodo,
            'cuatrimestre' => $cuatrimestre,
            'grupo' => $grupo_id
        ],
        'periodo_actual' => $this->periodo_actual,
        'msg' => $msg
    ];

    $this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
    $this->load->view("templates/views_sysmater/admin_views/alumno_list_view", $data);
    $this->load->view("templates/shared/footer_view");
}

// Métodos AJAX
public function get_cuatrimestres_ajax()
{
    if (!$this->input->is_ajax_request()) {
        show_404();
    }

    $periodo = $this->input->post('periodo') ?: $this->periodo_actual;
    $cuatrimestres = $this->alumno_model->get_cuatrimestres_por_periodo($periodo);//2ref
    
    if (empty($cuatrimestres)) {
        echo json_encode(['error' => 'No hay cuatrimestres disponibles para este periodo']);
    } else {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($cuatrimestres));
    }
}

public function get_grupos_ajax()
{
    if (!$this->input->is_ajax_request()) {
        show_404();
    }

    $cuatrimestre = $this->input->post('cuatrimestre');
    $periodo = $this->input->post('periodo') ?: $this->periodo_actual;
    
    if (empty($cuatrimestre)) {
        echo json_encode(['error' => 'Seleccione un cuatrimestre primero']);
        return;
    }

    $grupos = $this->alumno_model->get_grupos_por_cuatrimestre_periodo($cuatrimestre, $periodo);
    
    if (empty($grupos)) {
        echo json_encode(['info' => 'No hay grupos disponibles']);
    } else {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($grupos));
    }
}

    private function GotoLogin()
    {
        $this->session->set_flashdata(
            'msg',
            '<div class="alert alert-danger text-center"> 
                ¡Sesión no iniciada!
                Verifique que el usuario y contraseña sean correctos
            </div>'
        );
        redirect("login");
    }
    
    private function determinarPeriodo() 
    {
        $fechaActual = new DateTime();
        $mes = (int) $fechaActual->format('m');
        $año = $fechaActual->format('Y');
        
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
