<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Asignar_docente extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('/SYSMATER/shared/materia_model');
        $this->load->model('/SYSMATER/shared/docente_model');
    }

    public function index()
    {
        if ($this->session->id_tipo == 1) {
            $this->validation();

            if ($this->form_validation->run() == FALSE) {
                $id = $this->uri->segment($this->uri->total_segments());
                
                // Obtener la carrera del docente
                $docente = $this->docente_model->get_docente($id);
                $carrera = $docente ? $docente->row()->chrClvCarrera : null;

                // Determinar periodo actual
                $periodoActual = $this->determinarPeriodo();
                $periodoNumero = substr($periodoActual, -1); // Extraer solo el número del periodo (1-4)
                $docenteData = $docente->row(); // Objeto del docente
                $data = [
                    'id' => $id,
                    'carrera_docente' => $carrera,
                    'periodo_actual' => $periodoNumero, // Pasar el número del periodo a la vista
                    'nombre' => $docenteData->vchNombre,
                    'apaterno' => $docenteData->vchAPaterno,
                    'amaterno' => $docenteData->vchAMaterno
                ];

                $this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
                $this->load->view("templates/views_sysmater/admin_views/asignar_materias_view", $data);
                $this->load->view("templates/shared/footer_view");
            } else {
                $this->guardar_datos();
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

    /**
     * Determina el periodo actual basado en la fecha del sistema
     * @return string Periodo en formato YYYYP (P=1-4)
     */
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

    public function get_materias()
    {
        $periodo = $this->input->post('Periodo');
        $cuatrimestre = $this->input->post('cuatrimestre');

        // Si no se recibe periodo, usar el actual
        if(empty($periodo)) {
            $periodoActual = $this->determinarPeriodo();
            $periodo = substr($periodoActual, -1); // Solo el número del periodo
        }

        if ($periodo && $cuatrimestre) {
            $query = $this->materia_model->lista_materias_periodo_cuatrimestre($periodo, $cuatrimestre);
        } elseif ($periodo) {
            $query = $this->materia_model->lista_materias_periodo($periodo);
        } else {
            $query = NULL;
        }

        if ($query !== NULL) {
            echo json_encode($query->result());
        } else {
            echo json_encode([]);
        }
    }

    public function get_cuatrimestres()
    {
        $periodo = $this->input->post('Periodo');
        
        // Si no se recibe periodo, usar el actual
        if(empty($periodo)) {
            $periodoActual = $this->determinarPeriodo();
            $periodo = substr($periodoActual, -1); // Solo el número del periodo
        }

        $cuatrimestres = $this->materia_model->get_cuatrimestres_por_periodo($periodo);//3ref
        echo json_encode($cuatrimestres);
    }

    public function guardar_datos()
    {
        $vchClvTrabajador = $this->input->post('vchClvTrabajador');
        $numeroMaterias = $this->input->post('numeroMaterias');
        $Periodo = $this->determinarPeriodo();

        $this->db->trans_start();

        for ($i = 1; $i <= $numeroMaterias; $i++) {
            $cuatrimestre = $this->input->post('cuatrimestre' . $i);
            $materia = $this->input->post('materia' . $i);
            $grupos = $this->input->post('grupo' . $i);

            if (empty($materia)) continue;

            if (is_array($grupos) && !empty($grupos)) {
                foreach ($grupos as $id_grupo) {
                    // Verificar si el grupo ya está asignado
                    // Reemplazar la consulta original con:
                    $existe = $this->materia_model->verificar_grupo_asignado($materia, $Periodo, $cuatrimestre, $id_grupo);

                    if ($existe) {
                        $this->db->trans_rollback();
                        $this->session->set_flashdata('error', 'El grupo seleccionado ya está asignado a otro docente');
                        redirect("/sysmater/admin/asignar_docente/index/$vchClvTrabajador");
                    }

                    $data = array(
                        'vchClvTrabajador' => $vchClvTrabajador,
                        'vchClvMateria' => $materia,
                        'Periodo' => $Periodo,
                        'vchGrupo' => $id_grupo,
                        'vchCuatrimestre' => $cuatrimestre,
                    );
                    
                    if (!$this->materia_model->insertar_docente_materia($data)) {
                        $this->db->trans_rollback();
                        $this->session->set_flashdata('error', 'Error al asignar materias');
                        redirect("/sysmater/admin/asignar_docente/index/$vchClvTrabajador");
                    }
                }
            }
        }

        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Error al guardar las asignaciones');
        } else {
            $this->session->set_flashdata('success', 'Asignaciones guardadas correctamente');
        }
        
        redirect("/sysmater/admin/admin/lista_docente/");
    }

    public function get_grupos_por_cuatri() {
        $cuatrimestre = $this->input->post('cuatrimestre');
        $materia = $this->input->post('materia');
        $periodo = $this->determinarPeriodo();

        $this->load->model('docente_model');
        $resultado = $this->docente_model->obtener_grupos_disponibles($materia, $cuatrimestre, $periodo);

        header('Content-Type: application/json');

        // Verifica si $resultado es un arreglo con 'status'
        if (is_array($resultado) && isset($resultado['status'])) {
            echo json_encode(['error' => $resultado['mensaje']]);
        } else {
            echo json_encode($resultado);
        }
    }



    private function validation()
    {
        $config = array(
            array(
                'field' => 'Periodo',
                'label' => 'Periodo',
                'rules' => 'trim|required|in_list[1,2,3,4]'
            ),
            array(
                'field' => 'numeroMaterias',
                'label' => 'Número de Materias',
                'rules' => 'trim|required|integer|greater_than_equal_to[1]|less_than_equal_to[10]'
            ),
        );
        
        // Validar campos dinámicos
        $numMaterias = $this->input->post('numeroMaterias');
        for ($i = 1; $i <= $numMaterias; $i++) {
            $config[] = array(
                'field' => 'cuatrimestre' . $i,
                'label' => 'Cuatrimestre ' . $i,
                'rules' => 'trim|required'
            );
            
            $config[] = array(
                'field' => 'materia' . $i,
                'label' => 'Materia ' . $i,
                'rules' => 'trim|required'
            );
            
            $config[] = array(
                'field' => 'grupo' . $i . '[]',
                'label' => 'Grupos para materia ' . $i,
                'rules' => 'required'
            );
        }

        $this->form_validation->set_rules($config);
        $this->form_validation->set_message('required', 'El campo {field} es obligatorio');
        $this->form_validation->set_message('in_list', 'Seleccione una opción válida para {field}');
    }
}
