<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Progreso_examen extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('/SYSMATER/shared/examen_model');
        $this->load->model('/SYSMATER/shared/docente_model');
        $this->load->model('/SYSMATER/shared/alumno_model');
    }
    public function index()
    {
        if ($this->session->id_tipo == 4) {

            $id_examen = $this->uri->segment($this->uri->total_segments());

            $lista_pendientes = $this->alumno_model->lista_pendientes($id_examen);
            $lista_progreso = $this->alumno_model->lista_progreso($id_examen);
            $lista_fin_examen = $this->alumno_model->lista_fin_examen($id_examen);

            if ($lista_pendientes !== NULL) {
                $data['alumnos_faltantes'] = $this->alumno_model->lista_pendientes($id_examen);
            }

            if ($lista_progreso !== NULL) {
                $data['alumnos_en_progreso'] = $this->alumno_model->lista_progreso($id_examen);
            }

            if ($lista_fin_examen !== NULL) {
                $data['alumnos_fin_examen'] = $this->alumno_model->lista_fin_examen($id_examen);
            }

            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/progreso_examen_view", $data);
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
        } // end if-else

    }
}
