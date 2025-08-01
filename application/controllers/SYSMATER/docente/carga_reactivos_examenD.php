<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Carga_reactivos_examenD extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Cargar el helper de URL para usar site_url()
        $this->load->helper('url');
    }

    public function index() {
        // Verificar si el usuario tiene el tipo 4
        if ($this->session->id_tipo == 4) {
            
            // Obtener los datos del POST
            $nombresImagenes = unserialize($_POST['nombresImagenes']);

            
            if ($nombresImagenes) {

        
                $data['nombresImagenes'] =  $nombresImagenes;
               
            } else {
                // Si no se recibieron datos, manejar el error
                $data['nombresImagenes'] = [];
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">No se recibieron datos.</div>');
            }
            
            // Cargar las vistas con los datos
            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/carga_reactivos_view", $data);
            $this->load->view("templates/shared/footer_view");
            
        } else {
            // Si el usuario no tiene el tipo correcto, redirigir al login
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">¡Sesión no iniciada! Verifique que el usuario y contraseña sean correctos.</div>');
            redirect("login");
        }
    }

  


}
