<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ver_alumnos extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('/SYSMATER/shared/actividades_model');
    }

    public function index()
    {
        if ($this->session->id_tipo == 4) {

            $id_actividad = $this->uri->segment(5);
            $id_grupo     = $this->uri->segment(6);


            $data['alumnos'] = $this->actividades_model->obtener_alumnos_de_la_actividad($id_actividad, $id_grupo);
            $data['detalles'] = $this->actividades_model->obtener_detalles_de_la_actividad($id_actividad);
            $data['id_grupo'] = $id_grupo;


            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/ver_alumnos_view", $data);
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
}

/* End of file ver_examen.php */
/* Location: ./application/controllers/admin/ver_examen.php */