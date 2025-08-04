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
        if ($this->session->id_tipo != 4) {
            // Si no hay sesión válida
            if ($this->input->is_ajax_request()) {
                echo 'error_sesion';
                return;
            }
            $this->session->set_flashdata(
                'msg',
                '<div class="alert alert-danger text-center">
                ¡Sesión no iniciada! Verifica tus credenciales.
            </div>'
            );
            redirect("login");
            return;
        }

        $vchClvTrabajador = $this->session->Matricula;
        $vchPeriodo = $this->determinarPeriodo();
        $idPeriodo = $this->obtener_id_periodo();

        // Recibir datos
        $vchClvMateria = $this->input->post('vchClvMateria');
        $parcial = $this->input->post('parcial');
        $componentes = $this->input->post('componentes');

        // Validar materia
        if (!$this->materia_model->existe_materia($vchClvMateria)) {
            if ($this->input->is_ajax_request()) {
                echo 'materia_invalida';
                return;
            }
            $this->session->set_flashdata(
                'msg',
                '<div class="alert alert-danger text-center">
                La materia con clave <strong>' . $vchClvMateria . '</strong> no existe.
            </div>'
            );
            redirect("sysmater/docente/gestionar_materia/componente/$vchClvMateria");
            return;
        }

        // Validar componentes
        if (empty($componentes) || !is_array($componentes)) {
            if ($this->input->is_ajax_request()) {
                echo 'componentes_invalidos';
                return;
            }
            $this->session->set_flashdata(
                'msg',
                '<div class="alert alert-danger text-center">
                Error: No se recibieron componentes válidos.
            </div>'
            );
            redirect("sysmater/docente/gestionar_materia/componente/$vchClvMateria");
            return;
        }

        // Validar estructura y sumar valores
        $total_puntos = 0;
        $componentes_validados = [];

        foreach ($componentes as $comp) {
            if (empty($comp['nombre']) || !isset($comp['valor']) || $comp['valor'] <= 0) {
                if ($this->input->is_ajax_request()) {
                    echo 'valores_invalidos';
                    return;
                }
                $this->session->set_flashdata(
                    'msg',
                    '<div class="alert alert-danger text-center">
                    Error: Todos los componentes deben tener nombre y valor válidos.
                </div>'
                );
                redirect("sysmater/docente/gestionar_materia/componente/$vchClvMateria");
                return;
            }

            $valor = floatval($comp['valor']);
            $total_puntos += $valor;

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

        // Validar puntos disponibles
        $total_actual = $this->materia_model->obtener_total_parcial($vchClvMateria, $idPeriodo, $parcial, $vchClvTrabajador);
        $disponible = 10 - $total_actual;

        if ($total_puntos > $disponible) {
            if ($this->input->is_ajax_request()) {
                echo 'puntos_excedidos';
                return;
            }
            $this->session->set_flashdata(
                'msg',
                '<div class="alert alert-danger text-center">
                Error: El total de puntos (' . number_format($total_puntos, 1) . ') excede los disponibles (' . number_format($disponible, 1) . ').
            </div>'
            );
            redirect("sysmater/docente/gestionar_materia/componente/$vchClvMateria");
            return;
        }

        // Guardar con transacción
        $this->db->trans_start();
        $resultado = $this->materia_model->guardar_componentes_batch($componentes_validados);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE || !$resultado) {
            if ($this->input->is_ajax_request()) {
                echo 'error_db';
                return;
            }
            $this->session->set_flashdata(
                'msg',
                '<div class="alert alert-danger text-center">
                Error al guardar los componentes.
            </div>'
            );
            redirect("sysmater/docente/gestionar_materia/componente/$vchClvMateria");
            return;
        }

        // ✅ Si es AJAX devolver solo "ok"
        if ($this->input->is_ajax_request()) {
            echo 'ok';
            return;
        }


        $mensaje_exito = count($componentes_validados) === 1
            ? '¡Componente guardado correctamente!'
            : '¡' . count($componentes_validados) . ' componentes guardados correctamente!';

        $this->session->set_flashdata(
            'msg',
            '<div class="alert alert-success text-center">' . $mensaje_exito . '</div>'
        );
        redirect("sysmater/docente/gestionar_materia/componente/$vchClvMateria");
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



    public function obtener_componentes_parcial()
    {
        if ($this->input->is_ajax_request() && $this->session->id_tipo == 4) {

            $parcial = $this->input->post('parcial');
            $vchClvMateria = $this->input->post('vchClvMateria');
            $vchClvTrabajador = $this->session->Matricula;
            $vchPeriodo = $this->determinarPeriodo();

            $resultado = $this->materia_model->obtener_componentes_parcial(
                $vchClvMateria,
                $vchPeriodo,
                $parcial,
                $vchClvTrabajador
            );

            foreach ($resultado as $comp) {
                $componentesFormateados[] = [
                    'id_valor_componente' => $comp->id_valor_componente,
                    'componente' => $comp->componente,
                    'valor_componente' => floatval($comp->valor_componente),
                    'parcial' => $comp->parcial
                ];
            }

            echo json_encode([
                'success' => true,
                'componentes' => $componentesFormateados,
                'total_componentes' => count($componentesFormateados),
                'parcial' => $parcial
            ]);
        } else {
            show_error('Acceso no autorizado', 403);
        }
    }


    public function actualizar_parcial_completo()
    {
        if (!$this->input->is_ajax_request() || $this->session->id_tipo != 4) {
            show_error('Acceso no autorizado', 403);
            return;
        }

        $parcial = $this->input->post('parcial_editar');
        $vchClvMateria = $this->input->post('vchClvMateria');
        $vchClvTrabajador = $this->session->Matricula;
        $vchPeriodo = $this->determinarPeriodo();
        $idPeriodo = $this->obtener_id_periodo();
        $esquemas = $this->input->post('esquemas');

        $total = 0;
        foreach ($esquemas as $esquema) {
            $total += floatval($esquema['puntos']);
        }
        if (abs($total - 10) > 0.01) {
            echo 'puntos_invalidos';
            return;
        }

        $this->db->trans_start();

        $ids_actuales = $this->materia_model->obtener_ids_componentes($vchClvMateria, $vchPeriodo, $parcial, $vchClvTrabajador);


        $ids_recibidos = [];
        foreach ($esquemas as $esquema) {
            if (!empty($esquema['id'])) {
                $ids_recibidos[] = $esquema['id'];
            }
        }

        $ids_a_eliminar = array_diff($ids_actuales, $ids_recibidos);

        //Verificar si alguno está en uso
        if (!empty($ids_a_eliminar)) {
            foreach ($ids_a_eliminar as $id_eliminar) {
                if ($this->materia_model->componente_en_uso($id_eliminar)) {
                    echo 'en_uso';
                    $this->db->trans_rollback();
                    return;
                }
            }

            // Si ninguno está en uso, eliminar
            foreach ($ids_a_eliminar as $id_eliminar) {
                $this->materia_model->eliminar_componente($id_eliminar);
            }
        }

        // 🔹 5️⃣ Separar actualizaciones e inserciones
        $componentes_actualizar = [];
        $componentes_nuevos = [];

        foreach ($esquemas as $esquema) {
            $id = trim($esquema['id']);
            $nombre = trim($esquema['nombre']);
            $puntos = floatval($esquema['puntos']);

            if ($id) {
                $componentes_actualizar[] = [
                    'id_valor_componente' => $id,
                    'componente'          => $nombre,
                    'valor_componente'    => $puntos
                ];
            } else {
                $componentes_nuevos[] = [
                    'vchClvMateria'    => $vchClvMateria,
                    'idPeriodo'        => $idPeriodo,
                    'parcial'          => $parcial,
                    'vchClvTrabajador' => $vchClvTrabajador,
                    'vchPeriodo'       => $vchPeriodo,
                    'componente'       => $nombre,
                    'valor_componente' => $puntos
                ];
            }
        }

        if (!empty($componentes_actualizar)) {
            $this->materia_model->actualizar_componentes_parcial($componentes_actualizar);
        }
        if (!empty($componentes_nuevos)) {
            $this->materia_model->insertar_componentes_parcial($componentes_nuevos);
        }

        $this->db->trans_complete();

        echo $this->db->trans_status() ? 'ok' : 'error';
    }


    public function verificar_componente_en_uso()
    {
        if (!$this->input->is_ajax_request() || $this->session->id_tipo != 4) {
            show_error('Acceso no autorizado', 403);
            return;
        }

        $id_valor_componente = $this->input->post('id_valor_componente');
        $en_uso = $this->materia_model->componente_en_uso($id_valor_componente);

        echo json_encode([
            'success' => true,
            'en_uso' => $en_uso
        ]);
    }
}

/* End of file ver_examen.php */
/* Location: ./application/controllers/admin/ver_examen.php */