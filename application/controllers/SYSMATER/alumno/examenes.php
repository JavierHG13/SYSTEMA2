<?php

/**
 * Page examenes controller.
 * 02/06/2016
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Examenes extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // cargar modelo para examenes
        $this->load->model('/SYSMATER/alumno/valid_test_model', 'valid');
    }

    public function index()
    {
        if ($this->session->id_tipo == 5) {
            // --> validaciones
            $this->validation();

            // obtener examenes disponibes
            $data['valid_test'] = $this->get_valid_test();

            if ($this->form_validation->run() == FALSE) {
                // Cargar las vistas con los datos
                $this->load->view("templates/views_sysmater/alumno_views/alumno_navbar_view");
                $this->load->view("templates/views_sysmater/alumno_views/alumno_view", $data);
                $this->load->view("templates/shared/footer_view");
            } else {
                // Procesar el formulario cuando se envía
                if ($this->input->post('start') == "Iniciar Examen") {
                    $name_test = $this->input->post("valid_test");
                    $data2 = $this->valid->get_test_selec($name_test);
                    $this->chech_pass($data2);
                }       
            }

            // Manejar el botón de salir
            if ($this->input->post('exit') == "Salir") {
                redirect("login/logout");
            }
        } else {
            $message = "Verifique que el usuario y contraseña sean correctos ";
            $status_code = 401;
            show_error($message, $status_code, $heading = 'Sesión no iniciada');
        }
    } // end if-else


    // funtions
    private function chech_pass($data)
    {
        $name_test = $this->input->post("valid_test");
        $password = $this->input->post("password");
        $id_test = $data[0]["id_examen"];
        $pass = $data[0]["nvch_clave"];

        if ($password == $pass) {
            redirect("/sysmater/alumno/start_test/validate/" . $id_test);
        } else {
            $this->session->set_flashdata(
                'msg',
                '<div class="alert alert-danger text-center">
                    Contraseña no válida!
                </div>'
            );
            redirect("/sysmater/alumno/examenes");
        }
    }

    private function validation()
    {
        // Configurar validaciones del formulario
        $config = array(
            array(
                'field' => 'valid_test',
                'label' => 'Valid_test',
                'rules' => 'trim|required|callback_check_default_test'
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|required|min_length[1]|max_length[20]'
            )
        );
        $this->form_validation->set_rules($config);
    }
    
    public function check_default_test()
    {
        $valid_test = $this->input->post('valid_test');
        if ($valid_test == '00') {
            $this->form_validation->set_message('check_default_test', 'Selecciona un examen');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    private function get_valid_test()
    {
        $valid_test = $this->valid->get_valid_test();
        return $valid_test;
    }

}// end class
