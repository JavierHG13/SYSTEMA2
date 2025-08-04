<?php
defined('BASEPATH') or exit('No direct script access allowed');

class nueva_actividad extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('/SYSMATER/shared/actividades_model');
        $this->load->model('/SYSMATER/shared/instrumento_model');
    }
    public function index()
    {
        if ($this->session->id_tipo == 4) {

            $vchClvTrabajador = $this->session->Matricula;
            $periodo = $this->determinarPeriodo();

            $data['cuatrimestres'] = $this->instrumento_model->obtener_cuatrimestres_docente($vchClvTrabajador, $periodo);
            $data['modalidades'] = $this->actividades_model->lista_modalidades();
            //$data['componentes'] = $this->actividades_model->obtener_componente();

            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/nueva_actividad_view", $data);
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



    public function listar_grupos()
    {
        if ($this->session->id_tipo == 4) {
            $vchClvTrabajador = $this->session->Matricula;
            $vchClvMateria  = $this->input->post('vchClvMateria');

            if (!$vchClvMateria) {
                echo json_encode([]);
                return;
            }

            $materias = $this->actividades_model->listar_grupos($vchClvTrabajador, $vchClvMateria);

            echo json_encode($materias);
        } else {
            echo json_encode([]);
        }
    }

    public function listar_instrumentos()
    {
        if ($this->session->id_tipo == 4) {

            $vchClvMateria  = $this->input->post('vchClvMateria');
            $parcial  = $this->input->post('parcial');
    
            if (!$vchClvMateria) {
                echo json_encode([]);
                return;
            }

            $resultado = $this->actividades_model->listar_instrumentos($vchClvMateria, $parcial);

            echo json_encode($resultado);
        } else {
            echo json_encode([]);
        }
    }


    //** controlador para mostrar instrumento al asignar activdad */
    public function obtener_detalles_instrumento()
    {
        $id_instrumento = $this->input->post('id_instrumento');

        // Obtener detalles del instrumento
        $instrumento = $this->instrumento_model->get_instrumento_detalles($id_instrumento);

        // Obtener criterios asociados
        $criterios = $this->instrumento_model->get_criterios_by_instrumento($id_instrumento);

        if ($instrumento) {
            $response = [
                'instrumento' => $instrumento,
                'criterios' => $criterios
            ];
            echo json_encode($response);
        } else {
            echo json_encode(null);
        }
    }

    public function guardar_actividad()
    {

        if ($this->session->id_tipo == 4) {
            $data = json_decode(file_get_contents('php://input'));

            if (!$data) {
                show_error("Datos inválidos", 400);
            }

            // Recibir los datos enviados desde el frontend
            $vchPeriodo = $this->determinarPeriodo();

            $titulo = $data->titulo;
            $descripcion = $data->descripcion;
            $vchClvMateria = $data->vchClvMateria;
            $id_instrumento = $data->id_instrumento;
            $fechas_por_grupo = $data->fechas_por_grupo;
            $id_modalidad = $data->id_modalidad;
            $id_valor_componente = $data->id_valor_componente;
            $grupos = $data->grupos;

            $equiposNuevos = $data->equiposNuevos;
            $equiposExistentes = $data->equiposExistentes;
            $vchClvTrabajador = $this->session->Matricula;

            if (!empty($equiposNuevos)) {
                $equipos = $equiposNuevos; 
            } else {
                $equipos = $equiposExistentes;
            }

            $totalActividades = $this->actividades_model->contar_actividades($vchPeriodo, $vchClvMateria, $vchClvTrabajador);



            $numero_actividad = ($totalActividades && isset($totalActividades->TotalActividades))
                ? $totalActividades->TotalActividades + 1
                : 1;


            // Preparar arreglo de datos para insertar la actividad
            $actividadData = [
                'titulo' => $titulo,
                'descripcion' => $descripcion,
                'fecha_creacion' => date('Y-m-d'),
                'vchClvTrabajador' => $vchClvTrabajador,
                'id_instrumento' => $id_instrumento,
                'numero_actividad' =>  $numero_actividad,
                'id_modalidad' => $id_modalidad,
                'id_valor_componente' => $id_valor_componente // <- AÑADIDO
            ];


            // Insertar en BD
            $respuesta = $this->actividades_model->insertar_actividad(
                $actividadData,
                $vchClvMateria,
                $fechas_por_grupo,
                $grupos,
                $equipos,
                $vchPeriodo
            );

            // Respuesta
            if ($respuesta) {
                echo json_encode(["status" => "ok", "id_actividad" => $respuesta]);
            } else {
                echo json_encode(["status" => "error", "mensaje" => "No se pudo guardar la actividad."]);
            }
        } else {
            echo json_encode(["status" => "unauthorized"]);
        }
    }


    public function cargar_materias_del_docente()
    {
        if ($this->session->id_tipo == 4) {

            $vchClvTrabajador = $this->session->Matricula;
            $periodo = $this->determinarPeriodo();
            $vchCuatrimestre = $this->input->post('vchCuatrimestre');


            if (!$vchCuatrimestre) {
                echo json_encode([]);
                return;
            }

            $materias = $this->actividades_model->listar_materias_por_cuatrimestre($vchClvTrabajador, $vchCuatrimestre, $periodo);

            echo json_encode($materias);
        } else {
            echo json_encode([]);
        }
    }

    public function listar_equipos()
    {

        if ($this->session->id_tipo == 4) {

            $vchClvTrabajador = $this->session->Matricula;
            $idGrupo = $this->input->post('id_grupo');
            $materia = $this->input->post('materia');

            $equipos = $this->actividades_model->obtener_equipos_por_grupo($idGrupo, $materia, $vchClvTrabajador);

            echo json_encode($equipos);
        } else {
            echo json_encode([]);
        }
    }


    public function  obtener_alumnos_grupo()
    {

        if ($this->session->id_tipo == 4) {

            $vchClvTrabajador = $this->session->Matricula;
            $idGrupo = $this->input->post('id_grupo');
            $materia = $this->input->post('materia');

            $alumnos = $this->actividades_model->obtener_alumnos_grupo($idGrupo, $materia, $vchClvTrabajador);

            echo json_encode($alumnos);
        } else {
            echo json_encode([]);
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
}

/* End of file ver_examen.php */
/* Location: ./application/controllers/admin/ver_examen.php */