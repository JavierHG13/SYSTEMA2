<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ver_instrumentos extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('/SYSMATER/shared/instrumento_model');
    }

    public function index()
    {
        if ($this->session->id_tipo == 4) {

            $vchClvTrabajador = $this->session->Matricula; //Obtenemos el clave del trabajador
            $vchPeriodo = $this->determinarPeriodo();

            $data['instrumentos'] = $this->instrumento_model->lista_instrumentos_docente($vchClvTrabajador,  $vchPeriodo);

            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/ver_instrumentos_view", $data);
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
    } // end function



    public function eliminar()
    {
        if ($this->session->id_tipo == 4) {
            $id_instrumento = $this->uri->segment($this->uri->total_segments());

            $data['detalles'] = $this->instrumento_model->detalles_instrumento($id_instrumento);

            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/eliminar_instrumento_view", $data);
            $this->load->view("templates/shared/footer_view");
        } else {
            $this->session->set_flashdata(
                'msg',
                '<div class="alert alert-danger text-center"6> 
				!Sesión no iniciada!
				Verifique que el usuario y contraseña sean correctos
			</div>'
            );
            redirect("login");
        } // end if-else
    } // end function



    public function detalles_instrumento()
    {
        if ($this->session->id_tipo == 4) {

            $id_instrumento = $this->uri->segment($this->uri->total_segments());

            $data['criterios'] = $this->instrumento_model->lista_de_criterios($id_instrumento);
            $data['id_instrumento'] = $id_instrumento;

            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/detalles_instrumento_view", $data);
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
    } // end function

    //Actualizar instrumento
    public function actualizar()
    {
        $data = json_decode($this->input->raw_input_stream, true);

        if (!isset($data['id']) || !isset($data['nombre']) || !isset($data['descripcion']) || !isset($data['valor_maximo'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Datos incompletos']));
        }

        $id = $data['id'];
        $valor_maximo = floatval($data['valor_maximo']);

        if ($valor_maximo < 0 || $valor_maximo > 10) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Valor máximo inválido']));
        }

        // Obtener id_instrumento para validar el total acumulado
        $criterio = $this->instrumento_model->obtener_criterio($id);
        if (!$criterio) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Criterio no encontrado']));
        }

        $id_instrumento = $criterio->id_instrumento; //Obtenemos el id del instrumento
        $total_actual = $this->instrumento_model->sumar_valores_criterios($id_instrumento);
        $total_sin_actual = $total_actual - $criterio->valor_maximo;

        if (($total_sin_actual + $valor_maximo) > 10) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'El total no puede exceder 10']));
        }

        $actualizado = $this->instrumento_model->actualizar_criterio($id, $data['nombre'], $data['descripcion'], $valor_maximo);

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['success' => $actualizado]));
    }



    public function actualizar_todos()
    {
        // Obtener datos del formulario
        $criterios = $this->input->post('criterios');
        $id_instrumento = $this->input->post('id_instrumento');

        // VALIDACIÓN 1: Verificar que se recibieron datos
        if (empty($criterios) || empty($id_instrumento)) {
            $this->session->set_flashdata('error', 'Datos incompletos');
            redirect($_SERVER['HTTP_REFERER']);
            return;
        }

        // VALIDACIÓN 2: Verificar campos requeridos
        foreach ($criterios as $criterio) {
            if (empty(trim($criterio['nombre'])) || strlen(trim($criterio['nombre'])) < 3) {
                $this->session->set_flashdata('error', 'Todos los nombres deben tener al menos 3 caracteres');
                redirect($_SERVER['HTTP_REFERER']);
                return;
            }

            if (empty(trim($criterio['descripcion'])) || strlen(trim($criterio['descripcion'])) < 3) {
                $this->session->set_flashdata('error', 'Todas las descripciones deben tener al menos 3 caracteres');
                redirect($_SERVER['HTTP_REFERER']);
                return;
            }

            if (!is_numeric($criterio['valor_maximo']) || $criterio['valor_maximo'] <= 0 || $criterio['valor_maximo'] > 10) {
                $this->session->set_flashdata('error', 'Todos los valores deben estar entre 0.1 y 10');
                redirect($_SERVER['HTTP_REFERER']);
                return;
            }
        }

        // VALIDACIÓN 3: Verificar suma total = 10
        if (!$this->instrumento_model->validar_suma_total($criterios)) {
            $this->session->set_flashdata('error', 'La suma total debe ser exactamente 10 puntos');
            redirect($_SERVER['HTTP_REFERER']);
            return;
        }

        // VALIDACIÓN 4: Verificar nombres duplicados
        if ($this->instrumento_model->verificar_nombres_duplicados($criterios)) {
            $this->session->set_flashdata('error', 'No puede haber criterios con nombres duplicados');
            redirect($_SERVER['HTTP_REFERER']);
            return;
        }

        // ACTUALIZAR EN BASE DE DATOS
        if ($this->instrumento_model->actualizar_todos_criterios($criterios, $id_instrumento)) {
            $this->session->set_flashdata('success', 'Criterios actualizados correctamente');
        } else {
            $this->session->set_flashdata('error', 'Error al actualizar los criterios. Intente nuevamente');
        }

        redirect($_SERVER['HTTP_REFERER']);
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
/* End of file ver_reactivo.php */
/* Location: ./application/controllers/docente/ver_reactivo.php */