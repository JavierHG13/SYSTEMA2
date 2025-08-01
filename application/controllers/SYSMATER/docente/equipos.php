<?php
defined('BASEPATH') or exit('No direct script access allowed');

class equipos extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('/SYSMATER/shared/equipos_model');
    }

    // Ver materias del docente
    public function index()
    {
        $vchClvTrabajador = $this->session->Matricula;
        $periodo = $this->determinarPeriodo();
        $id_grupo  = $this->uri->segment($this->uri->total_segments() - 1);
        $vchClvMateria = $this->uri->segment($this->uri->total_segments());


        $data['equipos'] = $this->equipos_model->obtenerEquiposPorGrupo($id_grupo, $periodo, $vchClvMateria);
        $data['alumnos_sin_equipo'] = $this->equipos_model->alumnosSinEquipo($id_grupo, $periodo,  $vchClvMateria);

        $data['id_grupo'] = $id_grupo;
        $data['vchClvMateria'] = $vchClvMateria;

        $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
        $this->load->view("templates/views_sysmater/docente_views/equipos_view", $data);
        $this->load->view("templates/shared/footer_view");
    }

    // Ver equipos de un grupo
    public function ver_equipos($idGrupo)
    {

        $data['equipos'] = $this->equipos_model->obtenerEquiposPorGrupo($idGrupo);
        $data['grupo'] = $idGrupo;
        $this->load->view('docente/equipos/ver_equipos', $data);
    }

    // Alumnos sin equipo
    public function alumnos_disponibles($idGrupo)
    {
        $periodo = $this->determinarPeriodo();

        $alumnos = $this->equipos_model->alumnosSinEquipo($idGrupo);
        echo json_encode($alumnos);
    }

    // Guardar nuevo equipo
    public function crear_equipo()
    {
        if ($this->input->method() !== 'post') {
            show_error('Método no permitido', 405);
        }

        $id_grupo = $this->input->post('id_grupo');
        $nombre_equipo = $this->input->post('nombre_equipo');
        $periodo = $this->determinarPeriodo();
        $alumnos = $this->input->post('alumnos');
        $vchClvMateria = $this->input->post('vchClvMateria');


        if (empty($id_grupo) || empty($nombre_equipo) || empty($vchClvMateria)) {
            echo json_encode(['success' => false, 'msg' => 'Datos incompletos (materia requerida)']);
            return;
        }

        log_message('debug', 'Crear equipo -> Materia recibida: ' . $vchClvMateria);




        $this->db->trans_start();

        $id_equipo = $this->equipos_model->insertar_equipo($id_grupo, $nombre_equipo, $periodo, $vchClvMateria);

        if (!$id_equipo) {
            $this->db->trans_rollback();
            echo json_encode(['success' => false, 'msg' => 'Error al crear equipo']);
            return;
        }

        if (!empty($alumnos) && is_array($alumnos)) {
            $this->equipos_model->insertar_integrantes($id_equipo, $alumnos);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['success' => false, 'msg' => 'Error en la transacción']);
        } else {
            echo json_encode(['success' => true]);
        }
    }

    // Actualizar equipo
    public function actualizar_equipo()
    {
        $datos = $this->input->post();

        if (empty($datos['id_equipo'])) {
            echo json_encode(['success' => false, 'error' => 'ID de equipo no proporcionado']);
            return;
        }

        if (empty($datos['matriculas'])) {
            echo json_encode(['success' => false, 'error' => 'No se recibieron matrículas']);
            return;
        }

        $this->equipos_model->actualizarEquipo($datos);

        echo json_encode([
            'success' => true,
            'message' => 'Integrantes actualizados correctamente'
        ]);
    }



    public function eliminar_integrante()
    {
        $matricula = $this->input->post('matricula');
        $id_equipo = $this->input->post('id_equipo');

        if ($matricula && $id_equipo) {

            $resultado = $this->equipos_model->eliminar_integrante($id_equipo, $matricula);

            if ($resultado['success']) {
                echo json_encode(['status' => 'ok']);
            } else {
                echo json_encode(['status' => 'error', 'error' => $resultado['mensaje']]);
            }
        } else {
            echo json_encode(['status' => 'error', 'error' => 'Datos incompletos']);
        }
    }



    // Eliminar equipo
    public function eliminar_equipo($idEquipo)
    {
        $this->equipos_model->eliminarEquipo($idEquipo);
        echo json_encode(['status' => 'ok']);
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
