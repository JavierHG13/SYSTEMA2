<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Editar_reactivos_examen extends CI_Controller
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
            
            // Obtener datos del examen
            $data['examen'] = $this->examen_model->get_detalles_examen($id_examen);
            
            // Obtener reactivos asignados a este examen
            $data['reactivos'] = $this->carga_reactivos_model->obtener_reactivos_examen($id_examen);

            if ($data['examen'] === NULL) {
                $data['msg'] = "No se encontraron datos del examen";
            }

            $this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
            $this->load->view("templates/views_sysmater/docente_views/editar_reactivos_examen_view", $data);
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

    public function actualizar_reactivos()
    {
        if ($this->session->id_tipo != 4) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">¡No tiene permisos para esta acción!</div>');
            redirect("login");
        }

        $vchClvCuatri = $this->input->post('cuatri');
        $vchClvMateria = $this->input->post('materia');
        $vchClvTrabajador = $this->input->post('vchClvTrabajador');
        $vchPeriodo = $this->input->post('periodo');
        $numeroReactivos = $this->input->post('numeroReactivos');
        $id_examen = $this->input->post('id_examen');
        $reactivos = $this->input->post('reactivos');
        $parcial = $this->input->post('parcial');

        $uploadPath = FCPATH . 'uploads/';
        if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

        foreach ($reactivos as $i => $reactivoData) {
            $id_reactivo_main = $reactivoData['id_reactivo_main'];
            $id_reactivo_detail = $reactivoData['id_reactivo_detail'];

            // Subir imagen base si se proporcionó una nueva
            $imagen_base = $reactivoData['imagen_base_actual'] ?? '';
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

            // Procesar opciones A-D
            foreach (['A', 'B', 'C', 'D'] as $letra) {
            $opcion = $reactivoData['opciones'][$letra];
            
            // Determinar si la opción es de tipo texto o imagen
            $isImageOption = isset($_FILES['reactivos']['name'][$i]['opciones'][$letra]['imagen']) && 
                            $_FILES['reactivos']['error'][$i]['opciones'][$letra]['imagen'] == 0;
            
            $img = '';
            if ($isImageOption) {
                // Procesar imagen subida
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
                
                // Si se subió nueva imagen, limpiar el texto de la opción
                $opcion['texto'] = '';
            } elseif (isset($opcion['imagen_actual']) && !empty($opcion['imagen_actual'])) {
                // Mantener imagen existente y limpiar texto
                $img = $opcion['imagen_actual'];
                $opcion['texto'] = '';
            } else {
                // Es opción de texto, limpiar imagen
                $img = '';
            }
            
            $data["nvch_opcion{$letra}"] = $opcion['texto'] ?? '';
            $data["nvch_argumenta{$letra}"] = $opcion['argumentacion'] ?? '';
            $data["path_imagen{$letra}"] = $img;
        }

            // Actualizar el reactivo en la base de datos
            $this->carga_reactivos_model->actualizar_reactivo_detallado($id_reactivo_detail, $data);
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
