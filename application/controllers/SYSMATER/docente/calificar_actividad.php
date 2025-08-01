<?php
defined('BASEPATH') or exit('No direct script access allowed');

class calificar_actividad extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('/SYSMATER/shared/actividades_model');
    }

    public function index()
    {
        if ($this->session->id_tipo == 4) {

            $id_actividad  = $this->uri->segment($this->uri->total_segments() - 1);
            $vchMatricula = $this->uri->segment($this->uri->total_segments());

            $data['matricula'] = $vchMatricula;
            $data['id_actividad'] = $id_actividad;
            $data['criterios'] = $this->actividades_model->obtener_hoja_de_evaluacion($id_actividad);
            $resultado = $this->actividades_model->obtener_datos_alumno($vchMatricula);
            $data['alumno'] = $resultado[0];

            $actividad = $this->actividades_model->obtener_nombre_actividad($id_actividad);
            $data['actividad'] = $actividad[0];

            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/calificar_actividad_view", $data);
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
        }
    }

    //Funcion para calificar a un alumno en especifico
    public function guardar_evaluacion_individual()
    {
        header('Content-Type: application/json');

        if ($this->session->id_tipo != 4) {
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            return;
        }

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!$data || !isset($data['id_actividad'], $data['matricula'], $data['criterios'])) {
            echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
            return;
        }

        $id_actividad = $data['id_actividad'];
        $matricula = $data['matricula'];
        $observacion = $data['observacion'];
        $criterios = $data['criterios'];


        $id_actividad_alumno = $this->actividades_model->obtener_id_actividad_alumno($id_actividad,  $matricula);

        if (!$id_actividad_alumno) {
            echo json_encode(['success' => false, 'message' => 'Alumno no encontrado']);
            return;
        }

        // Actualizar la observación
        $this->actividades_model->actualizar_observacion_alumno($id_actividad_alumno, $observacion);

        // Guardar los criterios evaluados
        $this->actividades_model->guardar_evaluacion_criterios($id_actividad_alumno, $criterios);

        echo json_encode(['success' => true]);
    }

    //Se muestra una vista para el integrante de cada equipo
    public function integrante()
    {
        if ($this->session->id_tipo == 4) {

            $id_actividad = $this->uri->segment(5); // ejemplo: actividades/calificar/ID_ACTIVIDAD
            $id_equipo = $this->uri->segment(6);    // actividades/calificar/ID_ACTIVIDAD/ID_EQUIPO
            $vchMatricula = $this->uri->segment(7); // actividades/calificar/ID_ACTIVIDAD/ID_EQUIPO/MATRICULA


            $data['matricula'] = $vchMatricula;
            $data['id_equipo'] = $id_actividad;
            $data['id_actividad'] = $id_actividad;
            $data['criterios'] = $this->actividades_model->obtener_hoja_de_evaluacion($id_actividad);


            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/calificar_integrante_view", $data);
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
        }
    }

    public function calificar_equipo()
    {
        if ($this->session->id_tipo == 4) {

            $id_actividad_equipo  = $this->uri->segment($this->uri->total_segments());
            $periodo = $this->determinarPeriodo();



            // 1. Obtener integrantes del equipo
            //$data['integrantes'] = $this->actividades_model->obtener_integrantes_por_actividad_equipo($id_actividad_equipo);
            // 2. Obtener criterios del instrumento relacionado a la actividad
            $data['criterios'] = $this->actividades_model->obtener_criterios_por_actividad_equipo($id_actividad_equipo);
            $data['id_actividad_equipo'] = $id_actividad_equipo;
            $data['actividad'] = $this->actividades_model->obtener_detalles_actividad_equipo($id_actividad_equipo);
            $data['detalles_equipo'] = $this->actividades_model->obtenerEquipoPorActividadEquipo($id_actividad_equipo, $periodo);
            $data['equipo'] = $this->actividades_model->obtener_integrantes_con_calificacion($id_actividad_equipo, $periodo);


            // Vista
            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/calificar_equipo_view", $data);
            $this->load->view("templates/shared/footer_view");
        } else {
            $this->session->set_flashdata(
                'msg',
                '<div class="alert alert-danger text-center"> 
				¡Sesión no iniciada!
				Verifique que el usuario y contraseña sean correctos
			</div>'
            );
            redirect("login");
        }
    }

    public function guardar_evaluacion_equipo()
    {
        // Verifica que sea una solicitud POST AJAX válida
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || strpos($_SERVER['CONTENT_TYPE'], 'application/json') === false) {
            show_404(); // O simplemente salta la ejecución
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'No se recibió información']));
        }

        $id_actividad_equipo = $data['id_actividad_equipo'];
        $observacion = $data['observacion'];
        $criterios = $data['criterios'];
        $id_equipo = $data['id_equipo'];

        if (!is_numeric($id_actividad_equipo)) {
            log_message('error', 'ID inválido recibido: ' . $id_actividad_equipo);
            show_error('ID de actividad inválido', 400);
            return;
        }

        //$resultado = $this->actividades_model->guardarEvaluacionEquipo($id_actividad_equipo, $observacion, $calificaciones);

        $resultado = $this->actividades_model->guardar_evaluacion_equipo($id_actividad_equipo, $id_equipo, $criterios, $observacion);


        if ($resultado) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => true]));
        } else {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Error al guardar']));
        }
    }

    public function actualizar_evaluacion_individual()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $id_alumno = $data['id_alumno'];
        $id_actividad = $data['id_actividad'];
        $id_equipo = $data['id_equipo'];
        $observacion = $data['observacion'];
        $criterios = $data['criterios'];


        $id_actividad_alumno = $this->actividades_model->obtener_id_actividad_alumno($id_actividad, $id_alumno);

        // Si no existe, lo insertamos
        if (!$id_actividad_alumno) {
            $id_actividad_alumno = $this->actividades_model->crear_actividad_alumno($id_actividad, $id_alumno, $id_equipo);
        }

        // Eliminar criterios anteriores
        $this->actividades_model->eliminar_criterios_individuales($id_actividad_alumno);

        // Insertar nuevos criterios
        $total = 0;
        foreach ($criterios as $c) {
            $this->actividades_model->insertar_criterio_individual($id_actividad_alumno, $c['id_criterio'], $c['calificacion']);
            $total += $c['calificacion'];
        }

        // Actualizar observación (si la tienes en tbl_actividad_alumno)
        $this->actividades_model->actualizar_observacion_alumno($id_actividad_alumno, $observacion);

        // Responder al frontend
        echo json_encode([
            'success' => true,
            'calificacion' => number_format($total, 1) . ' /10'
        ]);
    }

    function determinarPeriodo()
    {
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
