<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Resultados_examenes extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('/SYSMATER/alumno/examenes_model');
        $this->load->helper(['url']);

        if ($this->session->id_tipo != 5) {
            show_error("Verifique que el usuario y contraseña sean correctos", 401, 'Sesión no iniciada');
        }
    }

    private function _cargar_layout($vista, $data = array())
    {
        $this->load->view("templates/views_sysmater/alumno_views/alumno_navbar_view");
        $this->load->view("templates/views_sysmater/alumno_views/{$vista}", $data);
        $this->load->view("templates/shared/footer_view");
    }

    public function index()
    {
        try {
            $examenes = $this->examenes_model->get_examenes_alumno(
                $this->session->Matricula,
                $this->session->Periodo,
                $this->session->clvCuatri,
                $this->session->Grupo
            );

            $data = [
                'materias_agrupadas' => $this->_agrupar_por_materia($examenes),
                'hay_examenes' => !empty($examenes)
            ];

            $this->_cargar_layout('examenes_view', $data);
        } catch (Exception $e) {
            log_message('error', 'Error al cargar exámenes: ' . $e->getMessage());
            show_error('Error interno del servidor. Contacte al administrador.', 500);
        }
    }

    public function detalle($id_examen = null)
    {
        if (!$id_examen || !is_numeric($id_examen)) {
            show_404();
            return;
        }

        try {
            $info_basica = $this->examenes_model->get_info_basica_examen($id_examen, $this->session->Matricula);

            if (empty($info_basica)) {
                show_error('El examen solicitado no existe o no tienes acceso a él', 404);
                return;
            }

            $respuestas = $this->examenes_model->get_respuestas_examen($id_examen, $this->session->Matricula);

            $data = [
                'examen' => $info_basica,
                'respuestas' => $this->_procesar_respuestas($respuestas),
                'url_regresar' => site_url('sysmater/alumno/resultados_examenes'),
            ];

            $this->_cargar_layout('detalle_examen_view', $data);
        } catch (Exception $e) {
            log_message('error', 'Error al cargar detalle de examen: ' . $e->getMessage());
            show_error('Error interno del servidor. Contacte al administrador.', 500);
        }
    }

    private function _agrupar_por_materia($examenes)
    {
        $agrupados = [];

        foreach ($examenes as $examen) {
            $clave = $examen['vchClvMateria'];

            if (!isset($agrupados[$clave])) {
                $agrupados[$clave] = [
                    'clave' => $clave,
                    'nombre' => $examen['vchNomMateria'],
                    'examenes' => []
                ];
            }

            $agrupados[$clave]['examenes'][] = [
                'id_examen' => $examen['id_examen'],
                'titulo' => $examen['titulo'],
                'parcial' => $examen['parcial'],
                'total_reactivos' => $examen['total_reactivos'],
                'aciertos' => $examen['aciertos'],
                'calificacion' => $examen['calificacion'],
                'url_detalle' => site_url('sysmater/alumno/resultados_examenes/detalle/' . $examen['id_examen'])
            ];
        }

        return array_values($agrupados);
    }

    private function _procesar_respuestas($respuestas)
    {
        $procesadas = [];

        foreach ($respuestas as $r) {
            $procesadas[] = [
                'numero_pregunta' => $r['numero_pregunta'],
                'pregunta' => $r['pregunta'],
                'path_imagen_base' => $r['path_imagen_base'],
                'opciones' => [
                    'A' => $r['nvch_opcionA'],
                    'B' => $r['nvch_opcionB'],
                    'C' => $r['nvch_opcionC'],
                    'D' => $r['nvch_opcionD']
                ],
                'opciones_imagenes' => [
                    'A' => $r['path_imagenA'],
                    'B' => $r['path_imagenB'],
                    'C' => $r['path_imagenC'],
                    'D' => $r['path_imagenD']
                ],
                'respuesta_alumno' => $r['respuesta_alumno'],
                'respuesta_correcta' => $r['respuesta_correcta'],
                'es_correcta' => $r['es_correcta']
            ];
        }

        return $procesadas;
    }
}