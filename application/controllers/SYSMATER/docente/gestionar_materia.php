<?php
defined('BASEPATH') or exit('No direct script access allowed');

class gestionar_materia extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('/SYSMATER/shared/materia_model');
    }

    public function index()
    {
        if ($this->session->id_tipo == 4) {

            $vchClvMateria = $this->uri->segment($this->uri->total_segments());
            $vchClvTrabajador = $this->session->Matricula; //Obtenemos el clave del trabajador
            $periodo = $this->determinarPeriodo();


            $data['grupos'] = $this->materia_model->mostrar_grupos_docente_por_materia($vchClvTrabajador, $vchClvMateria, $periodo);
            $data['materia'] = $this->materia_model->datos_materia($vchClvMateria)->row();
            $data['periodo'] = $periodo;
            $data['vchClvMateria'] = $vchClvMateria;


            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/gestionar_materia_view", $data);
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

    function obtener_id_periodo()
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

        return $periodo;
    }

    function esquema()
    {
        if ($this->session->id_tipo == 4) {

            $vchClvMateria = $this->uri->segment($this->uri->total_segments());
            $vchClvTrabajador = $this->session->Matricula; //Obtenemos el clave del trabajador
            $periodo = $this->determinarPeriodo();

            $data['componentes'] = $this->materia_model->obtener_componentes($vchClvTrabajador, $vchClvMateria, $periodo);


            $data['vchClvMateria'] = $vchClvMateria;


            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/gestionar_componente_view", $data);
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

    public function guardar_componente()
    {
        if ($this->session->id_tipo == 4) {
            $vchClvTrabajador = $this->session->Matricula;
            $vchPeriodo = $this->determinarPeriodo();
            $idPeriodo = $this->obtener_id_periodo();

            // Recibir datos del formulario
            $vchClvMateria = $this->input->post('vchClvMateria');
            $parcial = $this->input->post('parcial');
            $componentes = $this->input->post('componentes'); // Array de componentes

            // Validaciones básicas
            if (!$this->materia_model->existe_materia($vchClvMateria)) {
                $this->session->set_flashdata('msg', '
            <div class="alert alert-danger text-center">
                La materia con clave <strong>' . $vchClvMateria . '</strong> no existe.
            </div>');
                redirect("sysmater/docente/gestionar_materia/componente/$vchClvMateria");
                return;
            }

            // Validar que se enviaron componentes
            if (empty($componentes) || !is_array($componentes)) {
                $this->session->set_flashdata('msg', '
            <div class="alert alert-danger text-center">
                Error: No se recibieron componentes válidos.
            </div>');
                redirect("sysmater/docente/gestionar_materia/componente/$vchClvMateria");
                return;
            }

            // Validar estructura de componentes y calcular total
            $total_puntos = 0;
            $componentes_validados = [];

            foreach ($componentes as $index => $comp) {
                // Validar que existe nombre y valor
                if (empty($comp['nombre']) || !isset($comp['valor']) || $comp['valor'] <= 0) {
                    $this->session->set_flashdata('msg', '
                <div class="alert alert-danger text-center">
                    Error: Todos los componentes deben tener nombre y valor válidos.
                </div>');
                    redirect("sysmater/docente/gestionar_materia/componente/$vchClvMateria");
                    return;
                }

                $valor = floatval($comp['valor']);
                $total_puntos += $valor;

                // Preparar datos para inserción
                $componentes_validados[] = [
                    'vchClvMateria'      => $vchClvMateria,
                    'idPeriodo'          => $idPeriodo,
                    'parcial'            => $parcial,
                    'vchClvTrabajador'   => $vchClvTrabajador,
                    'vchPeriodo'         => $vchPeriodo,
                    'componente'         => trim($comp['nombre']),
                    'valor_componente'   => $valor
                ];
            }

            // Validar que el total no exceda los puntos disponibles
            $total_actual = $this->materia_model->obtener_total_parcial($vchClvMateria, $idPeriodo, $parcial, $vchClvTrabajador);
            $disponible = 10 - $total_actual;

            if ($total_puntos > $disponible) {
                $this->session->set_flashdata('msg', '
            <div class="alert alert-danger text-center">
                Error: El total de puntos (' . number_format($total_puntos, 1) . ') excede los disponibles (' . number_format($disponible, 1) . ').
            </div>');
                redirect("sysmater/docente/gestionar_materia/componente/$vchClvMateria");
                return;
            }

            // Usar transacción para garantizar consistencia
            $this->db->trans_start();

            try {
                // Guardar todos los componentes
                $resultado = $this->materia_model->guardar_componentes_batch($componentes_validados);

                if ($resultado) {
                    $this->db->trans_complete();

                    if ($this->db->trans_status() === FALSE) {
                        throw new Exception('Error en la transacción de base de datos');
                    }

                    $mensaje_exito = count($componentes_validados) === 1
                        ? '¡Componente guardado correctamente!'
                        : '¡' . count($componentes_validados) . ' componentes guardados correctamente!';

                    $this->session->set_flashdata('msg', '
                <div class="alert alert-success text-center">
                    ' . $mensaje_exito . '
                </div>');
                } else {
                    throw new Exception('Error al insertar componentes en la base de datos');
                }
            } catch (Exception $e) {
                $this->db->trans_rollback();

                $this->session->set_flashdata('msg', '
            <div class="alert alert-danger text-center">
                Error al guardar los componentes: ' . $e->getMessage() . '
            </div>');
            }

            redirect("sysmater/docente/gestionar_materia/componente/$vchClvMateria");
        } else {
            $this->session->set_flashdata(
                'msg',
                '<div class="alert alert-danger text-center">
            ¡Sesión no iniciada! Verifica tus credenciales.
        </div>'
            );
            redirect("login");
        }
    }
    public function eliminar_componente()
    {
        if ($this->input->is_ajax_request() && $this->session->id_tipo == 4) {

            $id = $this->input->post('id_valor_componente');

            $eliminado = $this->materia_model->eliminar_componente($id);

            if ($eliminado) {
                echo 'ok';
            } else {
                echo 'en_uso';
            }
        } else {
            show_404(); // Petición inválida
        }
    }
}

/* End of file ver_examen.php */
/* Location: ./application/controllers/admin/ver_examen.php */