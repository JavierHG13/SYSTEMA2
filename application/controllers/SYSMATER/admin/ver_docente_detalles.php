<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ver_docente_detalles extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('SYSMATER/shared/docente_model'); // Adjust path as per your actual folder structure
        $this->load->library('session');
    }

    public function index()
    {
        if ($this->session->userdata('id_tipo') == 1) { // Check if user is admin (assuming 'id_tipo' is set in session)

            $clave_trabajador = $this->uri->segment($this->uri->total_segments());
            
            $docente_data = $this->docente_model->get_docente($clave_trabajador);

            if ($docente_data) {
                $data['clave_trabajador'] = $docente_data->row()->vchClvTrabajador;
                $data['nombre_completo'] = $docente_data->row()->vchAPaterno . ' ' . $docente_data->row()->vchAMaterno . ' ' . $docente_data->row()->vchNombre;
                $data['activo'] = $docente_data->row()->intActivo == 1 ? true : false;

                // Assuming this function retrieves assigned subjects based on the docente's clave
                $data['materias_asignadas'] = $this->docente_model->lista_docentes_asignacion_Admin($clave_trabajador);

                // Load views with data
                $this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
                $this->load->view("templates/views_sysmater/admin_views/ver_docente_detalles", $data);
                $this->load->view("templates/shared/footer_view");
            } else {
                // Handle case where docente data is not found
                show_error('Docente no encontrado.');
            }

        } else {
            // Redirect to login if not admin
            $this->session->set_flashdata(
                'msg',
                '<div class="alert alert-danger text-center">¡Sesión no iniciada! Verifique que el usuario y contraseña sean correctos.</div>'
            );
            redirect("login");
        }
    }

    public function eliminarMateria()
    {
        if ($this->session->userdata('id_tipo') == 1) { // Check if user is admin
            $id_materia = $this->input->post('id_materia');
            $clave_trabajador = $this->input->post('clave_trabajador');
            $id_grupo = $this->input->post('id_grupo');
            $idDocMat = $this->input->post('id_docenteMateria');
          
            if ($id_materia && $clave_trabajador && $id_grupo && $idDocMat) {
                $this->docente_model->eliminar_materia_docente($idDocMat);
            }

            redirect('/sysmater/admin/ver_docente_detalles/index/' . $clave_trabajador);
        } else {
            $this->session->set_flashdata(
                'msg',
                '<div class="alert alert-danger text-center">¡Sesión no iniciada! Verifique que el usuario y contraseña sean correctos.</div>'
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
}
