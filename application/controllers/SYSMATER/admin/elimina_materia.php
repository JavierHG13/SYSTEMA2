<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Elimina_materia extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('/SYSMATER/shared/materia_model');
    }

    public function index()
    {
        if ($this->session->id_tipo == 1) {
            $task = $task = $this->input->post('task');

            $id = $this->uri->segment($this->uri->total_segments());

            if ($task != 'delete') {

                $data["materia"] = $this->obtener_materia($id);
                
                $this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
                $this->load->view("templates/views_sysmater/admin_views/elimina_materia_view", $data);
                $this->load->view("templates/shared/footer_view");
            } else {

                $this->verifica($id);
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
        } // end if-else
    } // end function

    private function verifica($id) {
    if ($this->materia_model->verifica_materia_eliminar($id) == false) {
        $this->elimina($id);
    } else {
        $data["materia"] = $this->obtener_materia($id);
        $data["error"] = true;

        $this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
        $this->load->view("templates/views_sysmater/admin_views/elimina_materia_view", $data);
        $this->load->view("templates/shared/footer_view");
    }
}

    private function elimina($id)
    {       
        $this->materia_model->elimina_materia($id);
   
        redirect("/sysmater/admin/admin/lista_materias");
    }

    private function obtener_materia($id)
    {
        $materia = $this->materia_model->get_materia($id);
        return $materia;
    }
}
