<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cargar_reactivos_examen extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('/SYSMATER/shared/examen_model');
        $this->load->model('/SYSMATER/shared/carga_reactivos_model');
        $this->load->library('upload');
    }

    public function index()
    {
        if ($this->session->id_tipo == 4) {
            $id_examen = $this->uri->segment($this->uri->total_segments());
            $data['examen'] = $this->examen_model->get_detalles_examen($id_examen);
            $data["rSelec"] = $this->examen_model->get_cantidad_reactivos($id_examen);

            if ($data['examen'] === NULL) {
                $data['msg'] = "No se encontraron datos del examen";
            }

            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/cargar_reactivos_examen_view", $data);
            $this->load->view("templates/shared/footer_view");
        } else {
            $this->session->set_flashdata(
                'msg',
                '<div class="alert alert-danger text-center"> 
                ¡Sesión no iniciada!
                Verifique que el usuario y contraseña sean correctos
                </div>'
            );
            redirect("login");
        }
    }

    public function guardar_reactivos()
    {
        if ($this->session->id_tipo != 4) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">¡No tiene permisos para esta acción!</div>');
            redirect("login");
        }

        $vchClvCuatri = $this->input->post('cuatri');
        $vchClvMateria = $this->input->post('materia');
        $vchClvTrabajador = $this->input->post('vchClvTrabajador');
        $vchPeriodo = $this->input->post('periodo');
        $numeroSeleccionados = $this->input->post('numeroSeleccionados');
        $numeroReactivos = $this->input->post('numeroReactivos');
        $id_examen = $this->input->post('id_examen');
        $reactivos = $this->input->post('reactivos');
        $parcial = $this->input->post('parcial');

        //TOTAL DE REACTIVOS A CARGAR
        $totalReactivos = $numeroReactivos - $numeroSeleccionados;

        $uploadPath = FCPATH . 'uploads/';
        if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

        for ($i = 1; $i <= $totalReactivos; $i++) {
            $id_reactivo_main = $this->carga_reactivos_model->insertarPreguntaTipo(
                '', 68, $vchClvCuatri, $vchClvMateria, '', $vchClvTrabajador, 1, $vchPeriodo
            );

            if (!$id_reactivo_main) continue;

            $reactivoData = $reactivos[$i];

            // Subir imagen base
            $imagen_base = '';
            if (isset($_FILES['reactivos']['name'][$i]['imagen_base']) && $_FILES['reactivos']['error'][$i]['imagen_base'] == 0) {
                $fileBase = [
                    'name' => $_FILES['reactivos']['name'][$i]['imagen_base'],
                    'type' => $_FILES['reactivos']['type'][$i]['imagen_base'],
                    'tmp_name' => $_FILES['reactivos']['tmp_name'][$i]['imagen_base'],
                    'error' => $_FILES['reactivos']['error'][$i]['imagen_base'],
                    'size' => $_FILES['reactivos']['size'][$i]['imagen_base']
                ];
                $imagen_base = $this->subirArchivoDirecto(
                    $fileBase, $vchClvTrabajador, $vchClvMateria, $i, 'BASE', $parcial, $vchPeriodo
                );
            }

            $data = [
                'id_reactivos_main_sysmater' => $id_reactivo_main,
                'int_horas' => $reactivoData['int_horas'] ?? 0,
                'int_minutos' => $reactivoData['int_minutos'] ?? 5,
                'id_nivel' => $reactivoData['id_nivel'] ?? 1,
                'txt_base' => $reactivoData['txt_base'],
                'chr_correcto' => $reactivoData['correcta'],
                'vch_bibliografia' => $reactivoData['vch_bibliografia'] ?? '',
                'path_imagen_base' => $imagen_base,
                'vchClvTrabajador' => $vchClvTrabajador,
                'id_estado' => 1
            ];

            // Sube imagenes opciones A-D
            foreach (['A', 'B', 'C', 'D'] as $letra) {
                $img = '';
                if (
                    isset($_FILES['reactivos']['name'][$i]['opciones'][$letra]['imagen']) &&
                    $_FILES['reactivos']['error'][$i]['opciones'][$letra]['imagen'] == 0
                ) {
                    $fileOpcion = [
                        'name' => $_FILES['reactivos']['name'][$i]['opciones'][$letra]['imagen'],
                        'type' => $_FILES['reactivos']['type'][$i]['opciones'][$letra]['imagen'],
                        'tmp_name' => $_FILES['reactivos']['tmp_name'][$i]['opciones'][$letra]['imagen'],
                        'error' => $_FILES['reactivos']['error'][$i]['opciones'][$letra]['imagen'],
                        'size' => $_FILES['reactivos']['size'][$i]['opciones'][$letra]['imagen']
                    ];
                    $img = $this->subirArchivoDirecto(
                        $fileOpcion, $vchClvTrabajador, $vchClvMateria, $i, 'op' . $letra, $parcial, $vchPeriodo
                    );
                }

                $op = $reactivoData['opciones'][$letra];
                $data["nvch_opcion{$letra}"] = $op['texto'];
                $data["nvch_argumenta{$letra}"] = $op['argumentacion'] ?? '';
                $data["path_imagen{$letra}"] = $img;
            }

            $this->carga_reactivos_model->insertarReactivosDetallados(
                $data['id_reactivos_main_sysmater'],
                $data['int_horas'],
                $data['int_minutos'],
                $data['id_nivel'],
                $data['txt_base'],
                $data['nvch_opcionA'], $data['nvch_argumentaA'], $data['path_imagenA'],
                $data['nvch_opcionB'], $data['nvch_argumentaB'], $data['path_imagenB'],
                $data['nvch_opcionC'], $data['nvch_argumentaC'], $data['path_imagenC'],
                $data['nvch_opcionD'], $data['nvch_argumentaD'], $data['path_imagenD'],
                $data['chr_correcto'],
                $data['vch_bibliografia'],
                $data['path_imagen_base'],
                $data['vchClvTrabajador'],
                $data['id_estado']
            );

            $this->carga_reactivos_model->insertarReactivosExamen($id_examen, $data['id_reactivos_main_sysmater']);
        }

        redirect("/sysmater/docente/examenes_registrados");
    }

    private function subirArchivoDirecto($file, $vchClvTrabajador, $vchClvMateria, $numReactivo, $tipo, $parcial, $periodo)
    {
        $uploadPath = FCPATH . 'uploads/';
        if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

        $allowedExt = ['gif', 'jpg', 'jpeg', 'png'];

        $nombreOriginal = $file['name'];
        $tmpName = $file['tmp_name']; 
        $error = $file['error'];
        $size = $file['size'];

        if ($error !== UPLOAD_ERR_OK) {
            log_message('error', 'Error en archivo: código ' . $error . ' para archivo ' . $nombreOriginal);
            return '';
        }

        $ext = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt)) {
            log_message('error', 'Extensión no permitida para archivo ' . $nombreOriginal);
            return '';
        }

        // Nomenclatura
        $nuevoNombre = "{$vchClvTrabajador}-{$vchClvMateria}-{$numReactivo}-{$tipo}-{$parcial}-{$periodo}.{$ext}";
        $nuevoNombre = preg_replace('/\s+/', '_', $nuevoNombre); // Limpia espacios

        $rutaDestino = $uploadPath . $nuevoNombre;

        if (move_uploaded_file($tmpName, $rutaDestino)) {
            return $nuevoNombre;
        } else {
            log_message('error', 'Error al mover archivo ' . $nombreOriginal);
            return '';
        }
    }

}
