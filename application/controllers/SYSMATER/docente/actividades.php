<?php
defined('BASEPATH') or exit('No direct script access allowed');

class actividades extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('/SYSMATER/shared/actividades_model');
    }

    public function index($id_grupo = null)
    {
        if ($this->session->id_tipo == 4) {

            $id_grupo = $this->uri->segment($this->uri->total_segments() - 1);
            $vchClvMateria = $this->uri->segment($this->uri->total_segments());

            $vchClvTrabajador = $this->session->Matricula;

            //$parcial = $this->input->get('parcial');
            //$modalidad = $this->input->get('modalidad');
            $parcial = 1;
            $modalidad = 1;

            $data['actividades'] = $this->actividades_model->obtener_actividades_por_grupo($vchClvTrabajador, $vchClvMateria, $id_grupo, $parcial, $modalidad);
            $actividades = $data['actividades'];


            $parciales = [];
            foreach ($actividades as $actividad) {
                $parciales[$actividad->parcial][] = $actividad;
            }

            // Enviamos a la vista
            $data['parciales'] = $parciales;
            $data['id_grupo'] = $id_grupo;
            $data['vchClvMateria'] = $vchClvMateria;

            // Cargar las vistas
            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/actividades_view", $data);
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


    public function ver_equipo()
    {
        if ($this->session->id_tipo == 4) {

            $id_grupo = $this->uri->segment(5);
            $id_actividad = $this->uri->segment(6);



            $data['equipos'] = $this->actividades_model->obtenerEquiposParaCalificar($id_actividad);
            //$data['actividad'] = $this->actividades_model->informacion_de_la_actividad($id_actividad);
            $data['detalles'] = $this->actividades_model->obtener_detalles_de_la_actividad($id_actividad);

            $data['id_grupo'] =   $id_grupo;
            $data['id_actividad'] =   $id_actividad;


            //var_dump($data['equipos']);
            //exit;

            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/ver_equipos_view", $data);
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

    public function integrantes_equipo()
    {
        if ($this->session->id_tipo == 4) {

            $id_grupo = $this->input->post('id_grupo');
            $id_equipo = $this->input->post('id_equipo');

            if (!$id_grupo || !$id_equipo) {
                // Error por datos incompletos
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(['error' => 'Faltan parámetros']));
                return;
            }

            $this->load->model('actividades_model');
            $integrantes = $this->actividades_model->obtener_integrantes_equipo($id_grupo, $id_equipo);

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($integrantes));
        } else {
            // Usuario no autorizado
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode(['error' => 'Sesión no válida']));
        }
    }

    public function obtener_componentes()
    {
        if ($this->session->id_tipo == 4) {

            $vchClvMateria = $this->input->post('id_materia');
            $periodo = $this->determinarPeriodo();

            $parcial = $this->input->post('parcial');
            $vchClvTrabajador = $this->session->Matricula;

            $componentes = $this->actividades_model->get_componentes($vchClvMateria, $periodo, $parcial, $vchClvTrabajador);

            echo json_encode($componentes);
        } else {
            // Usuario no autorizado
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode(['error' => 'Sesión no válida']));
        }
    }


    public function eliminar_actividad()
    {
        if ($this->session->id_tipo == 4) {

            $id_actividad = $this->input->post('id_actividad');
            $id_grupo = $this->input->post('id_grupo');
            $periodo = $this->determinarPeriodo();


            if ($id_actividad && $id_grupo) {

                $resultado = $this->actividades_model->eliminar_actividad($id_actividad, $id_grupo);

                if ($resultado['success']) {
                    echo json_encode(['status' => 'ok', 'mensaje' => $resultado['mensaje']]);
                } else {
                    echo json_encode(['status' => 'error', 'error' => $resultado['error']]);
                }
            } else {
                echo json_encode(['status' => 'error', 'error' => 'Datos incompletos']);
            }
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

    public function actualizar_fecha_actividad()
    {
        // Obtener datos del POST
        $id_actividad = $this->input->post('id_actividad');
        $id_grupo = $this->input->post('id_grupo');
        $fecha = $this->input->post('fecha_entrega');
        $hora = $this->input->post('hora_entrega');

        // Log para debugging
        log_message('debug', 'Datos recibidos: ' . json_encode([
            'id_actividad' => $id_actividad,
            'id_grupo' => $id_grupo,
            'fecha' => $fecha,
            'hora' => $hora
        ]));

        // Validar que todos los datos estén presentes
        if (empty($id_actividad) || empty($id_grupo) || empty($fecha) || empty($hora)) {
            echo json_encode([
                'success' => false,
                'mensaje' => 'Faltan datos requeridos'
            ]);
            return;
        }

        // Construir fecha completa
        $fechaCompleta = $fecha . ' ' . $hora . ':00';

        // Log de la fecha completa
        log_message('debug', 'Fecha completa formateada: ' . $fechaCompleta);

        try {
            // Intentar actualizar
            $actualizado = $this->actividades_model->actualizar($id_actividad, $id_grupo, $fechaCompleta);

            // Respuesta JSON
            echo json_encode([
                'success' => $actualizado,
                'mensaje' => $actualizado ? 'Actividad actualizada correctamente' : 'No se realizaron cambios'
            ]);
        } catch (Exception $e) {
            // Log del error
            log_message('error', 'Error al actualizar actividad: ' . $e->getMessage());

            echo json_encode([
                'success' => false,
                'mensaje' => 'Error interno del servidor'
            ]);
        }
    }
}

/* End of file ver_examen.php */
/* Location: ./application/controllers/admin/ver_examen.php */