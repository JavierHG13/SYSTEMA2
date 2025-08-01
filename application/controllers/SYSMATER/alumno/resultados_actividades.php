<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Resultados_actividades extends CI_Controller
{
    // Constantes para mejorar mantenibilidad
    const TIPO_USUARIO_ALUMNO = 5;
    const MIN_PARCIAL = 1;
    const MAX_PARCIAL = 3;

    // Estados de actividades
    const ESTADOS_CLASE = [
        'asignada' => 'label-info',
        'entregada' => 'label-primary',
        'pendiente' => 'label-warning',
        'revisada' => 'label-success',
        'incompleta' => 'label-danger'
    ];

    // Modalidades
    const MODALIDADES = [
        1 => 'Individual',
        2 => 'Equipo'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('/SYSMATER/alumno/activities_model');
        $this->load->helper(['url', 'security']);

        $this->_validar_sesion();
    }

    /**
     * Validación robusta de sesión
     */
    private function _validar_sesion()
    {
        // Verificar que existe sesión
        if (!$this->session->userdata()) {
            $this->_mostrar_error_sesion('No hay sesión activa');
        }

        // Verificar tipo de usuario
        if (!$this->session->id_tipo || $this->session->id_tipo != self::TIPO_USUARIO_ALUMNO) {
            $this->_mostrar_error_sesion('Acceso no autorizado para este tipo de usuario');
        }

        // Verificar datos críticos de sesión
        if (!$this->session->Matricula || !$this->session->Periodo) {
            $this->_mostrar_error_sesion('Datos de sesión incompletos');
        }

        // Sanitizar datos de sesión
        $this->session->set_userdata([
            'Matricula' => $this->security->xss_clean($this->session->Matricula),
            'Periodo' => $this->security->xss_clean($this->session->Periodo)
        ]);
    }

    private function _mostrar_error_sesion($mensaje)
    {
        show_error($mensaje, 401, 'Error de Autenticación');
    }

    private function _cargar_layout($vista, $data = [])
    {
        $this->load->view("templates/views_sysmater/alumno_views/alumno_navbar_view");
        $this->load->view("templates/views_sysmater/alumno_views/{$vista}", $data);
        $this->load->view("templates/shared/footer_view");
    }

    /**
     * Página principal - Lista de actividades por parcial
     */
    public function index($parcial = null)
    {
        try {
            // Obtener y validar parcial
            $parcial = $this->_obtener_parcial_valido($parcial);

            // Preparar datos base
            $data = [
                'parcial_actual' => $parcial,
                'urls_parciales' => $this->_generar_urls_parciales($parcial),
                'materias_agrupadas' => [],
                'total_actividades' => 0,
                'hay_actividades' => false
            ];

            // Cargar actividades si hay parcial válido
            if ($parcial) {
                $this->_cargar_actividades_parcial($data, $parcial);
            }

            $this->_cargar_layout('actividades_view', $data);
        } catch (DatabaseException $e) {
            $this->_manejar_error_bd($e);
        } catch (Exception $e) {
            $this->_manejar_error_general($e, 'Error al cargar actividades');
        }
    }

    /**
     * Vista de detalle de actividad con validación de ownership
     */
    public function detalle($id_actividad = null)
    {
        // Validar parámetro
        if (!$this->_es_id_valido($id_actividad)) {
            show_404();
            return;
        }

        try {
            // Obtener datos con validación de ownership
            $datos_actividad = $this->activities_model->get_detalle_actividad(
                (int)$id_actividad,
                $this->session->Matricula
            );

            if (!$this->_validar_datos_actividad($datos_actividad)) {
                show_error('La actividad no existe o no tienes acceso a ella', 404, 'Actividad no encontrada');
                return;
            }

            $data = $this->_preparar_datos_detalle($datos_actividad);
            $this->_cargar_layout('detalle_actividad_view', $data);
        } catch (DatabaseException $e) {
            $this->_manejar_error_bd($e);
        } catch (Exception $e) {
            $this->_manejar_error_general($e, 'Error al cargar detalle de actividad');
        }
    }

    // =================== MÉTODOS PRIVADOS DE VALIDACIÓN ===================

    private function _obtener_parcial_valido($parcial)
    {
        // Priorizar GET sobre URL
        if (!$parcial) {
            $parcial = $this->input->get('parcial');
        }

        // Validar y sanitizar
        if (!$parcial || !is_numeric($parcial)) {
            return null;
        }

        $parcial = (int)$parcial;
        return ($parcial >= self::MIN_PARCIAL && $parcial <= self::MAX_PARCIAL) ? $parcial : null;
    }

    private function _es_id_valido($id)
    {
        return $id && is_numeric($id) && $id > 0;
    }

    private function _validar_datos_actividad($datos)
    {
        return !empty($datos) && isset($datos['info_basica']) && !empty($datos['info_basica']);
    }

    // =================== MÉTODOS DE CARGA DE DATOS ===================

    private function _cargar_actividades_parcial(&$data, $parcial)
    {
        $actividades = $this->activities_model->get_materias_con_actividades(
            $this->session->Matricula,
            $this->session->Periodo,
            $parcial
        );

        if (!empty($actividades)) {
            $data['materias_agrupadas'] = $this->_agrupar_actividades_por_materia($actividades);
            $data['total_actividades'] = count($actividades);
            $data['hay_actividades'] = true;
        }
    }

    private function _preparar_datos_detalle($datos_actividad)
    {
        $info_basica = $datos_actividad['info_basica'];
        $calificacion_total = $datos_actividad['calificacion_total'] ?? null;
        $criterios = $datos_actividad['criterios'] ?? [];
        $observacion = $datos_actividad['observacion'] ?? null; // NUEVO

        return [
            'actividad' => $info_basica,
            'calificacion_total' => $calificacion_total,
            'criterios' => $criterios,

            // Datos formateados
            'fecha_entrega_formateada' => $this->_formatear_fecha_completa($info_basica['fecha_entrega']),
            'clase_estado' => $this->_obtener_clase_estado($info_basica['nombre_estado']),
            'texto_modalidad' => $this->_obtener_texto_modalidad($info_basica['id_modalidad']),

            // NUEVOS: Info del docente y observaciones
            'nombre_docente' => $this->_limpiar_nombre_docente($info_basica['nombre_docente'] ?? ''),
            'observacion_docente' => $observacion['observacion_docente'] ?? null,
            'tiene_observacion' => !empty(trim($observacion['observacion_docente'] ?? '')),

            // NUEVO: Info del grupo
            'grupo' => $info_basica['vchGrupo'] ?? 'Sin grupo',

            // Cálculos (sin cambios)
            'tiene_calificacion' => !is_null($calificacion_total),
            'porcentaje_total' => $this->_calcular_porcentaje($calificacion_total, $info_basica['valor_total']),
            'clase_calificacion' => $this->_obtener_clase_calificacion($calificacion_total, $info_basica['valor_total']),

            // URLs
            'url_regresar' => site_url('sysmater/alumno/resultados_actividades'),
            'criterios_procesados' => $this->_procesar_criterios($criterios)
        ];
    }

    // =================== MÉTODOS DE PROCESAMIENTO ===================

    private function _generar_urls_parciales($parcial_actual)
    {
        $parciales = [
            1 => '01 - Primer Parcial',
            2 => '02 - Segundo Parcial',
            3 => '03 - Tercer Parcial'
        ];

        $urls = [];
        foreach ($parciales as $num => $nombre) {
            $urls[] = [
                'numero' => $num,
                'nombre' => $nombre,
                'url' => site_url("sysmater/alumno/resultados_actividades/index/{$num}"),
                'activo' => ($parcial_actual == $num)
            ];
        }

        return $urls;
    }

    private function _agrupar_actividades_por_materia($actividades)
    {
        $materias_agrupadas = [];

        foreach ($actividades as $actividad) {
            $clave_materia = $actividad['vchClvMateria'];

            if (!isset($materias_agrupadas[$clave_materia])) {
                $materias_agrupadas[$clave_materia] = [
                    'clave' => $clave_materia,
                    'nombre' => $actividad['vchNomMateria'],
                    'actividades' => []
                ];
            }

            $materias_agrupadas[$clave_materia]['actividades'][] = $this->_procesar_actividad($actividad);
        }

        return array_values($materias_agrupadas);
    }

    private function _procesar_actividad($actividad)
    {
        return [
            'id_actividad' => $actividad['id_actividad'],
            'titulo' => $actividad['titulo'],
            'fecha_entrega_formateada' => $this->_formatear_fecha_corta($actividad['fecha_entrega']),
            'nombre_estado' => $actividad['nombre_estado'],
            'clase_estado' => $this->_obtener_clase_estado($actividad['nombre_estado']),
            'texto_modalidad' => $this->_obtener_texto_modalidad($actividad['id_modalidad']),
            'url_detalle' => site_url("sysmater/alumno/resultados_actividades/detalle/{$actividad['id_actividad']}"),
            'calificacion' => $actividad['calificacion'] ?? null
        ];
    }

    private function _procesar_criterios($criterios)
    {
        $criterios_procesados = [];

        foreach ($criterios as $criterio) {
            if (!$this->_es_criterio_valido($criterio)) {
                continue;
            }

            $porcentaje = $this->_calcular_porcentaje_criterio($criterio);

            $criterios_procesados[] = [
                'nombre' => $criterio['nombre'],
                'valor_maximo' => $criterio['valor_maximo'],
                'calificacion' => $criterio['calificacion'],
                'porcentaje' => round($porcentaje, 1),
                'clase_fila' => $this->_obtener_clase_por_porcentaje($porcentaje)
            ];
        }

        return $criterios_procesados;
    }

    // =================== MÉTODOS HELPER ===================

    private function _obtener_clase_estado($estado)
    {
        $estado_lower = strtolower(trim($estado));
        return self::ESTADOS_CLASE[$estado_lower] ?? 'label-default';
    }

    private function _obtener_texto_modalidad($id_modalidad)
    {
        return self::MODALIDADES[(int)$id_modalidad] ?? 'Sin definir';
    }

    private function _formatear_fecha_corta($fecha)
    {
        if (!$fecha) return 'Sin fecha';

        $timestamp = strtotime($fecha);
        return $timestamp ? date('d/m/Y', $timestamp) : 'Fecha inválida';
    }

    private function _formatear_fecha_completa($fecha)
    {
        if (!$fecha) return 'Sin fecha establecida';

        $timestamp = strtotime($fecha);
        if (!$timestamp) return 'Fecha inválida';

        // Usar locale español si está disponible
        if (setlocale(LC_TIME, 'es_ES.utf8', 'es_ES', 'spanish')) {
            return strftime('%d de %B de %Y a las %H:%M', $timestamp);
        }

        // Fallback manual
        $meses = [
            1 => 'enero',
            2 => 'febrero',
            3 => 'marzo',
            4 => 'abril',
            5 => 'mayo',
            6 => 'junio',
            7 => 'julio',
            8 => 'agosto',
            9 => 'septiembre',
            10 => 'octubre',
            11 => 'noviembre',
            12 => 'diciembre'
        ];

        $dia = date('j', $timestamp);
        $mes = $meses[(int)date('n', $timestamp)];
        $año = date('Y', $timestamp);
        $hora = date('H:i', $timestamp);

        return "{$dia} de {$mes} de {$año} a las {$hora}";
    }

    private function _calcular_porcentaje($calificacion_total, $valor_total)
    {
        if (!$calificacion_total || !isset($calificacion_total['calificacion_total']) || $valor_total == 0) {
            return 0;
        }

        return round(($calificacion_total['calificacion_total'] / $valor_total) * 100, 1);
    }

    private function _calcular_porcentaje_criterio($criterio)
    {
        if ($criterio['valor_maximo'] == 0) return 0;
        return ($criterio['calificacion'] / $criterio['valor_maximo']) * 100;
    }

    private function _obtener_clase_calificacion($calificacion_total, $valor_total)
    {
        if (!$calificacion_total || !isset($calificacion_total['calificacion_total'])) {
            return 'text-muted';
        }

        $porcentaje = $this->_calcular_porcentaje($calificacion_total, $valor_total);
        return $this->_obtener_clase_por_porcentaje($porcentaje);
    }

    private function _obtener_clase_por_porcentaje($porcentaje)
    {
        if ($porcentaje >= 80) return 'success';
        if ($porcentaje >= 60) return 'warning';
        return 'danger';
    }

    private function _es_criterio_valido($criterio)
    {
        return isset($criterio['calificacion']) &&
            isset($criterio['valor_maximo']) &&
            $criterio['valor_maximo'] > 0;
    }

    /**
     * Limpia y formatea el nombre del docente
     */
    private function _limpiar_nombre_docente($nombre)
    {
        $nombre_limpio = trim($nombre);
        return !empty($nombre_limpio) ? $nombre_limpio : 'Sin asignar';
    }

    // =================== MANEJO DE ERRORES ===================

    private function _manejar_error_bd($e)
    {
        log_message('error', 'Error de base de datos: ' . $e->getMessage());
        show_error('Error de conexión con la base de datos. Intente más tarde.', 500, 'Error de Base de Datos');
    }

    private function _manejar_error_general($e, $contexto)
    {
        log_message('error', "{$contexto}: " . $e->getMessage());
        show_error('Error interno del servidor. Contacte al administrador.', 500, 'Error del Sistema');
    }
}
