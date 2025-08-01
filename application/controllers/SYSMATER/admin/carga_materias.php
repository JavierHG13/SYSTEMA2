<?php
defined('BASEPATH') or exit('No direct script access allowed');

class carga_materias extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('/SYSMATER/shared/carga_reactivos_model');
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
        $this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
        $this->load->view("templates/views_sysmater/admin_views/carga_materias_view.php");
        $this->load->view("templates/shared/footer_view");
    }
}
