<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Detalles_alumno extends CI_Controller
{

    
    // Estados de actividades
    const ESTADOS_CLASE = [
        'asignada' => 'label-info',
        'entregada' => 'label-primary',
        'pendiente' => 'label-warning',
        'revisada' => 'label-success',
        'incompleta' => 'label-danger'
    ];

    // Modalidades
    const MODALIDADES = [
        1 => 'Individual',
        2 => 'Equipo'
    ];
    public function __construct()
    {
        parent::__construct();
        $this->load->model('/SYSMATER/shared/alumno_model');
        $this->load->model('/SYSMATER/alumno/activities_model');
        $this->load->model('/SYSMATER/alumno/examenes_model');
        $this->load->helper(['url', 'security']);
    }

    public function index()
    {
        if ($this->session->id_tipo == 2) {

            $id = $this->uri->segment($this->uri->total_segments());
			$data['alumno'] = $this->alumno_model->get_detalles_alumno($id);
			if ($data['alumno'] === NULL) {
				$data['msg'] = "No se encontraron datos del alumno";
                $this->load->view("templates/views_sysmater/director_views/director_navbar_view");
                $this->load->view("templates/views_sysmater/director_views/ver_alumno_detalles_view", $data);
                $this->load->view("templates/shared/footer_view");
			} else {
				$this->load->view("templates/views_sysmater/director_views/director_navbar_view");
                $this->load->view("templates/views_sysmater/director_views/ver_alumno_detalles_view", $data);
                $this->load->view("templates/shared/footer_view");
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
        }
    }
    
    public function cargar_actividades($matricula, $parcial,$periodo)
    {
        header('Content-Type: application/json');

        // $periodo = '20251'; // o usar $this->determinarPeriodo();
        $actividades = $this->activities_model->get_materias_con_actividades($matricula, $periodo, $parcial);

        if (!empty($actividades)) {
            $materias_agrupadas = $this->_agrupar_actividades_por_materia($actividades);
            echo json_encode([
                'hay_actividades' => true,
                'materias_agrupadas' => $materias_agrupadas
            ]);
        } else {
            echo json_encode([
                'hay_actividades' => false
            ]);
        }
    }
    public function cargar_examenes($matricula, $periodo,$cuatri,$grupo)
    {
        header('Content-Type: application/json');

        $examenes = $this->examenes_model->get_examenes_alumno($matricula, $periodo, $cuatri,$grupo);

        if (!empty($examenes)) {
            echo json_encode([
                'hay_examenes' => true,
                'examenes' => $examenes
            ]);
        } else {
            echo json_encode([
                'hay_examenes' => false
            ]);
        }
    }

    private function _agrupar_actividades_por_materia($actividades)
    {
        $materias_agrupadas = [];

        foreach ($actividades as $actividad) {
            $clave_materia = $actividad['vchClvMateria'];

            if (!isset($materias_agrupadas[$clave_materia])) {
                $materias_agrupadas[$clave_materia] = [
                    'clave' => $clave_materia,
                    'nombre' => $actividad['vchNomMateria'],
                    'actividades' => []
                ];
            }

            $materias_agrupadas[$clave_materia]['actividades'][] = $this->_procesar_actividad($actividad);
        }

        return array_values($materias_agrupadas);
    }

    private function _procesar_actividad($actividad)
    {
        return [
            'id_actividad' => $actividad['id_actividad'],
            'titulo' => $actividad['titulo'],
            'fecha_entrega_formateada' => $this->_formatear_fecha_corta($actividad['fecha_entrega']),
            'nombre_estado' => $actividad['nombre_estado'],
            'clase_estado' => $this->_obtener_clase_estado($actividad['nombre_estado']),
            'texto_modalidad' => $this->_obtener_texto_modalidad($actividad['id_modalidad']),
            'url_detalle' => site_url("sysmater/alumno/ActividadController/detalle/{$actividad['id_actividad']}"),
            'calificacion' => $actividad['calificacion'] ?? null
        ];
    }
    private function _obtener_clase_estado($estado)
    {
        $estado_lower = strtolower(trim($estado));
        return self::ESTADOS_CLASE[$estado_lower] ?? 'label-default';
    }

    private function _obtener_texto_modalidad($id_modalidad)
    {
        return self::MODALIDADES[(int)$id_modalidad] ?? 'Sin definir';
    }

    private function _formatear_fecha_corta($fecha)
    {
        if (!$fecha) return 'Sin fecha';

        $timestamp = strtotime($fecha);
        return $timestamp ? date('d/m/Y', $timestamp) : 'Fecha inválida';
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
