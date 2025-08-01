<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ver_materias extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('/SYSMATER/shared/materia_model');
    }

    public function index()
    {
        if ($this->session->id_tipo == 4) {

            $vchClvTrabajador = $this->session->Matricula; //Obtenemos el clave del trabajador
            $periodo = $this->determinarPeriodo();


            $data['materias'] = $this->materia_model->mostrar_materias_docente($vchClvTrabajador, $periodo);



            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/ver_materias_view", $data);
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

    //* funcion para el modal de gestionar materia
    public function get_grupos_materia($materia_id)
    {
        $periodo = $this->determinarPeriodo();
        $vchClvTrabajador = $this->session->Matricula; //Obtenemos el clave del trabajador


        // Validar que venga el parámetro
        if (empty($materia_id)) {
            echo json_encode([
                'success' => false,
                'error' => 'ID de materia requerido'
            ]);
            return;
        }


        try {
            // Llamar al modelo
            $grupos = $this->materia_model->get_grupos_por_materia($materia_id, $periodo, $vchClvTrabajador);
            

            if ($grupos) {
                $grupos_array = [];
                foreach ($grupos as $grupo) {
                    $grupos_array[] = [
                        'idGrupo' => $grupo->id_grupo,
                        'vchGrupo' => $grupo->vchGrupo,
                        'estudiantes' => $grupo->estudiantes
                    ];
                }

                echo json_encode([
                    'success' => true,
                    'grupos' => $grupos_array
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'No se encontraron grupos para esta materia'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => 'Error interno: ' . $e->getMessage()
            ]);
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