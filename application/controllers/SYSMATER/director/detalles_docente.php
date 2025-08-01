<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Detalles_docente extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('SYSMATER/shared/docente_model');
        $this->load->library('session');
    }

    public function index()
    {
        if ($this->session->userdata('id_tipo') == 2) {
            $clave_trabajador = $this->uri->segment($this->uri->total_segments());
            
            $docente_data = $this->docente_model->get_docente($clave_trabajador);

            if ($docente_data) {
                $data['clave_trabajador'] = $docente_data->row()->vchClvTrabajador;
                $data['nombre_completo'] = $docente_data->row()->vchAPaterno . ' ' . $docente_data->row()->vchAMaterno . ' ' . $docente_data->row()->vchNombre;
                $data['activo'] = $docente_data->row()->intActivo == 1 ? true : false;

                // Obtener el período actual
                $periodo_actual = $this->determinarPeriodo();
                $data['periodo_actual'] = $periodo_actual;                
                $data['periodos'] = $this->docente_model->get_periodos_docente($clave_trabajador);
                
                // Obtener materias del período actual (usando LEFT JOIN)
                $materias_actuales = $this->docente_model->lista_docentes_asignacion_filtrada($clave_trabajador);
                $data['materias_asignadas'] = $materias_actuales;

                $this->load->view("templates/views_sysmater/director_views/director_navbar_view");
                $this->load->view("templates/views_sysmater/director_views/ver_docente_detalles_view", $data);
                $this->load->view("templates/shared/footer_view");
            } else {
                show_error('Docente no encontrado.');
            }
        } else {
            $this->session->set_flashdata(
                'msg',
                '<div class="alert alert-danger text-center">¡Sesión no iniciada!</div>'
            );
            redirect("login");
        }
    }

    public function filtrar()
    {
        if ($this->session->userdata('id_tipo') == 2) {
            $clave_trabajador = $this->input->post('clave_trabajador') ?? $this->uri->segment($this->uri->total_segments());
            
            $periodo = $this->input->post('periodo');
            $cuatrimestre = $this->input->post('cuatrimestre');
            $grupo = $this->input->post('grupo');

            $docente_data = $this->docente_model->get_docente($clave_trabajador);

            if ($docente_data) {
                $data['clave_trabajador'] = $docente_data->row()->vchClvTrabajador;
                $data['nombre_completo'] = $docente_data->row()->vchAPaterno . ' ' . $docente_data->row()->vchAMaterno . ' ' . $docente_data->row()->vchNombre;
                $data['activo'] = $docente_data->row()->intActivo == 1 ? true : false;

                // Obtener todos los períodos disponibles para el docente
                $data['periodos'] = $this->docente_model->get_periodos_docente($clave_trabajador);
                
                // Obtener materias filtradas
                $data['materias_asignadas'] = $this->docente_model->lista_docentes_asignacion_filtrada(
                    $clave_trabajador, 
                    $periodo,
                    $cuatrimestre,
                    $grupo
                );

                // Pasar el período actual a la vista
                $data['periodo_actual'] = $periodo;

                $this->load->view("templates/views_sysmater/director_views/director_navbar_view");
                $this->load->view("templates/views_sysmater/director_views/ver_docente_detalles_view", $data);
                $this->load->view("templates/shared/footer_view");
            } else {
                show_error('Docente no encontrado.');
            }
        } else {
            $this->session->set_flashdata(
                'msg',
                '<div class="alert alert-danger text-center">¡Sesión no iniciada!</div>'
            );
            redirect("login");
        }
    }

    public function eliminarMateria()
    {
        if ($this->session->userdata('id_tipo') == 2) { // Check if user is admin
            $id_materia = $this->input->post('id_materia');
            $clave_trabajador = $this->input->post('clave_trabajador');
            $id_grupo = $this->input->post('id_grupo');
            $idDocMat = $this->input->post('id_docenteMateria');
          
            if ($id_materia && $clave_trabajador && $id_grupo && $idDocMat) {
                $this->docente_model->eliminar_materia($id_materia, $clave_trabajador, $id_grupo, $idDocMat);
            } else {
                $si=1;
            }

            redirect('/sysmater/director/ver_docente_detalles/index/' . $clave_trabajador);
        } else {
            $this->session->set_flashdata(
                'msg',
                '<div class="alert alert-danger text-center">¡Sesión no iniciada! Verifique que el usuario y contraseña sean correctos.</div>'
            );
            redirect("login");
        }
    }

     public function determinarPeriodo()
    {
        $fechaActual = new DateTime();
        $mes = (int) $fechaActual->format('m');
        $año = $fechaActual->format('Y');
        
        if ($mes >= 1 && $mes <= 4) {
            return $año . '1'; // Enero - Abril
        } elseif ($mes >= 5 && $mes <= 8) {
            return $año . '2'; // Mayo - Agosto
        } else {
            return $año . '3'; // Septiembre - Diciembre
        }
    }
}
