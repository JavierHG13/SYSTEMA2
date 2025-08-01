<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lista_docente extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('/SYSMATER/shared/docente_model');
    }

    public function index()
    {
      
        if ($this->session->id_tipo == 1) {
            $data['docentes'] = $this->docente_model->lista_docentes_todos(); // Método del modelo para obtener la lista de docentes
            
            $this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
            $this->load->view("templates/views_sysmater/admin_views/lista_docente_view", $data); // Vista para mostrar la lista de docentes
            $this->load->view("templates/shared/footer_view");
        } else {
            $this->session->set_flashdata(
                'msg',
                '<div class="alert alert-danger text-center"> 
                ¡Sesión no iniciada! Verifique que el usuario y contraseña sean correctos.
                </div>'
            );
            redirect("login");
        }
    }
}

