<?php
defined('BASEPATH') or exit('No direct script access allowed');

class crear_instrumento extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('SYSMATER/shared/instrumento_model');
        $this->load->model('SYSMATER/shared/actividades_model');
    }

    public function index()
    {
        if ($this->session->id_tipo == 4) {

            $vchClvTrabajador = $this->session->Matricula;
            $periodo = $this->determinarPeriodo();

            $data['cuatrimestres'] = $this->instrumento_model->obtener_cuatrimestres_docente($vchClvTrabajador, $periodo);
            $data['tipo_de_instrumento'] = $this->instrumento_model->lista_tipos_de_instrumentos();

            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/crear_rubrica_view", $data);
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

    public function guardar_instrumento()
    {

        //log_message('debug', 'Periodo calculado: ' . var_export($vchPeriodo, true));


        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            echo json_encode(['success' => false, 'message' => 'Datos JSON inválidos']);
            return;
        }

        if (!isset($this->session->Matricula)) {
            echo json_encode(['success' => false, 'message' => 'Sesión no iniciada']);
            return;
        }


        // Primero obtienes los valores del input y otras fuentes
        $nombre              = $input['nombre'];
        $parcial             = $input['parcial'];
        $valor_total         = $input['valor_total'];
        $vchClvMateria       = $input['vchClvMateria'];
        $id_tipo_instrumento = $input['idTipoInstrumento'];
        $vchClvTrabajador    = $this->session->Matricula;
        $idPeriodo           = $this->determinarIdPeriodo();
        $vchPeriodo          = $this->determinarPeriodo();

        if ($this->instrumento_model->existeInstrumento($nombre, $vchClvMateria, $idPeriodo)) {
            echo json_encode([
                'success' => false,
                'message' => "Ya existe un instrumento con el nombre \"$nombre\" para la materia y el periodo actual."
            ]);
            return;
        }

        $idInstrumento = $this->instrumento_model->insertarInstrumento(
            $nombre,
            $parcial,
            $valor_total,
            $vchClvMateria,
            $idPeriodo,
            $id_tipo_instrumento,
            $vchClvTrabajador,
            $vchPeriodo
        );

        if (!$idInstrumento) {
            echo json_encode(['success' => false, 'message' => 'Error al guardar instrumento']);
            return;
        }

        $criterios = [];
        foreach ($input['criterios'] as $c) {
            $criterios[] = [
                'nombre'         => $c['nombre'],
                'descripcion'    => $c['descripcion'],
                'valor_maximo'   => $c['valor_maximo'],
                'id_instrumento' => $idInstrumento
            ];
        }

        $resultado = $this->instrumento_model->insertarCriterios($criterios);

        if ($resultado) {
            echo json_encode([
                'success' => true,
                'message' => 'Instrumento creado correctamente.',
                'id_instrumento' => $idInstrumento
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al guardar el instrumento.'
            ]);
        }
    }

    public function determinarPeriodo()
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

    public function determinarIdPeriodo()
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

        return $periodo;
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
}
