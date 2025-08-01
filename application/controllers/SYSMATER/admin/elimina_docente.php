<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Elimina_docente extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('/SYSMATER/shared/docente_model');
    }

    public function index()
    {
        if ($this->session->id_tipo == 1) {
            $task = $this->input->post('task');

            $id = $this->uri->segment($this->uri->total_segments());

            if ($task != 'delete') {

                $data["docente"] = $this->obtener_docente($id);

                $this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
                $this->load->view("templates/views_sysmater/admin_views/elimina_docente_view", $data);
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
        if ($this->docente_model->verifica_docente_eliminar($id) == false) {
            $this->elimina($id);
        } else {
            $data["docente"] = $this->obtener_docente($id);
            $data["error"] = true;

            $this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
            $this->load->view("templates/views_sysmater/admin_views/elimina_docente_view", $data);
            $this->load->view("templates/shared/footer_view");
        }
    }
    private function elimina($id)
    {       
        $this->docente_model->elimina_docente($id);
   
        redirect("/sysmater/admin/admin/lista_docente");
    }

    private function obtener_docente($id)
    {
        $docente = $this->docente_model->get_docente($id);
        return $docente;
    }
}
