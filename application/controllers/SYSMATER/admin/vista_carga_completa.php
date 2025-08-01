<?php
defined('BASEPATH') or exit('No direct script access allowed');

class vista_carga_completa extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // Verificar si el usuario tiene la sesión iniciada y es del tipo correcto

        if ($this->session->id_tipo == 1) {
            $this->cargarVistaCarga();
        } else {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">¡Sesión no iniciada! Verifique que el usuario y contraseña sean correctos</div>');
            redirect("login");
        }
    }

    private function cargarVistaCarga()
    {
        // Cargar las vistas para la carga de archivos
        $this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
        $this->load->view("templates/views_sysmater/admin_views/vista_carga_completa");
        $this->load->view("templates/shared/view_sysmater/footer_view");
    }
}
