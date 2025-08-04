<?php
defined('BASEPATH') or exit('No direct script access allowed');

class actividades_model extends CI_Model
{
    // call the model constructor.
    public function __construct()
    {
        parent::__construct();
    }

    public function get_componentes($vchClvMateria, $idPeriodo, $parcial, $vchClvTrabajador)
    {
        $sql = "EXEC sp_GetComponentes ?, ?, ?, ?";
        $query = $this->db->query($sql, [$vchClvMateria, $idPeriodo, $parcial, $vchClvTrabajador]);

        return $query->result();
    }

    public function contar_actividades($periodo, $claveMateria, $claveTrabajador)
    {
        $sql = "EXEC sp_ContarActividades ?, ?, ?";
        $query = $this->db->query($sql, array($periodo, $claveMateria, $claveTrabajador));
        return $query->row(); // Devuelve un objeto con TotalActividades
    }


    public function insertar_actividad($actividad_data, $vchClvMateria, $fechas_por_grupo, $grupos, $equipos, $vchPeriodo)
    {
        // Iniciar transacción
        $this->db->trans_start();

        // 1. Insertar actividad principal
        $this->db->insert('tbl_actividades', $actividad_data);
        $id_actividad = $this->db->insert_id();

        log_message('info', "✅ Actividad insertada: ID = $id_actividad, Materia = $vchClvMateria");

        // Obtener modalidad (1 = individual, 2 = equipo)
        $modalidad = $actividad_data['id_modalidad'];
        $id_grupo_asignado = isset($grupos[0]) ? $grupos[0] : null;


        // 2. Insertar relación con grupos
        foreach ($grupos as $id_grupo) {

            $fecha_entrega_raw = isset($fechas_por_grupo->$id_grupo) ? $fechas_por_grupo->$id_grupo : null;

            // Convertir al formato correcto solo si existe la fecha
            $fecha_entrega = $fecha_entrega_raw ? date('Y-m-d H:i:s', strtotime($fecha_entrega_raw)) : null;

            $this->db->insert('tbl_actividad_grupo', [
                'id_actividad' => $id_actividad,
                'id_grupo' => $id_grupo,
                'fecha_asignacion' => date('Y-m-d H:i:s'),
                'fecha_entrega' => $fecha_entrega
            ]);

            // Si la actividad es INDIVIDUAL, insertamos todos los alumnos del grupo
            if ($modalidad == 1) {
                /*$alumnos = $this->db
                    ->select('vchMatricula')
                    ->where('chvGrupo', $id_grupo)
                    ->where('vchPeriodo', $vchPeriodo)
                    ->get('tblAlumnos')
                    ->result();

                foreach ($alumnos as $alumno) {
                    $this->db->insert('tbl_actividad_alumno', [
                        'id_actividad' => $id_actividad,
                        'vchMatricula' => $alumno->vchMatricula,
                        'id_estado'  => 1

                    ]);

                    // Mostrar log en archivo log de CI
                    log_message('info', 'Insertado alumno: ' . $alumno->vchMatricula . ' en actividad ID: ' . $id_actividad);
                }*/
            }
        }

        // Si es modalidad en equipo
        if ($modalidad == 2 && !empty($equipos)) {
            foreach ($equipos as $equipo) {
                // Crear equipo
                $this->db->insert('tbl_equipos', [
                    'id_grupo' => $id_grupo_asignado,
                    'nombre_equipo' => $equipo->nombre
                ]);
                $id_equipo = $this->db->insert_id();

                // Relacionar equipo con actividad
                $this->db->insert('tbl_actividad_equipo', [
                    'id_actividad' => $id_actividad,
                    'id_equipo' => $id_equipo,
                    'id_estado' => 1
                ]);

                // Asignar integrantes
                foreach ($equipo->integrantes as $integrante) {
                    $this->db->insert('tbl_equipo_alumno', [
                        'id_equipo' => $id_equipo,
                        'vchMatricula' => $integrante->vchMatricula
                    ]);

                    $this->db->insert('tbl_actividad_alumno', [
                        'id_actividad' => $id_actividad,
                        'vchMatricula' => $integrante->vchMatricula,
                        'id_estado'  => 1
                    ]);
                }
            }
        }

        // Finalizar transacción
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            log_message('info', "✅ Transacción finalizada correctamente para actividad $id_actividad");
            return $id_actividad;
        } else {
            log_message('error', "❌ Error al insertar actividad. Transacción revertida.");
            return false;
        }
    }


    public function lista_periodos()
    {
        $query = $this->db->query("SELECT * FROM dbo.tbl_periodos");

        if ($query->num_rows() == 0) {
            return NULL;
        } else {
            return $query;
        } // end if-else
    } // end function


    public function lista_modalidades()
    {
        $query = $this->db->query("EXEC sp_ListaModalidades");

        if ($query->num_rows() == 0) {
            return NULL;
        } else {
            return $query;
        } // end if-else
    } // end function



    public function listar_materias($vchClvTrabajador, $vchCuatrimestre)
    {

        $sql = "EXEC sp_ListarMaterias ?, ?";
        $query = $this->db->query($sql, [$vchClvTrabajador, $vchCuatrimestre]);


        return $query->num_rows() > 0 ? $query->result() : []; //Resultado listo para JSON
    }


    public function listar_grupos($vchClvTrabajador, $vchClvMateria)
    {
        $sql = "EXEC sp_ListarGrupos ?, ?";
        $query = $this->db->query($sql, [$vchClvTrabajador, $vchClvMateria]);


        return $query->num_rows() > 0 ? $query->result() : []; //Resultado listo para JSON
    }


    public function obtener_actividades($vchClvMateria, $id_grupo)
    {
        $sql = "SELECT id_actividad_grupo, tbl_actividad_grupo.id_actividad, fecha_entrega, tbl_actividades.titulo, tbl_actividades.vchClvTrabajador,
        tbl_instrumento.parcial, vchClvMateria,  tbl_actividad_grupo.id_grupo
        FROM tbl_actividad_grupo INNER JOIN tbl_actividades ON tbl_actividades.id_actividad = tbl_actividad_grupo.id_actividad
        INNER JOIN tbl_instrumento ON tbl_instrumento.id_instrumento =  tbl_actividades.id_instrumento WHERE  tbl_actividades.vchClvTrabajador = '0432'
        AND tbl_instrumento.vchClvMateria = ? AND tbl_actividad_grupo.id_grupo = ?";

        $query = $this->db->query($sql, array($vchClvMateria, $id_grupo));

        return $query->num_rows() > 0 ? $query->result() : null;
    }

    public function obtener_alumnos_de_la_actividad($id_actividad, $id_grupo)
    {

        $this->db->select("
        a.vchMatricula,
         a.vchAPaterno + ' ' + a.vchAMaterno + ' ' + a.vchNombre AS nombreCompleto,
        aa.id_actividad,
        aa.id_actividad_alumno,
        nombre_estado,
        (
            SELECT COALESCE(SUM(e.calificacion), 0)
            FROM tbl_evaluacion_criterioActividad e
            WHERE e.id_actividad_alumno = aa.id_actividad_alumno
        ) AS calificacion_total
    ");

        $this->db->from('tbl_actividad_alumno aa');
        $this->db->join('tblAlumnos a', 'a.vchMatricula = aa.vchMatricula');
        $this->db->join('tbl_estado_actividad e', 'aa.id_estado = e.id_estado_actividad', 'left');
        $this->db->where('aa.id_actividad', $id_actividad);
        $this->db->where('a.chvGrupo', $id_grupo);
        $this->db->order_by('a.vchAPaterno', 'ASC');

        return $this->db->get()->result();
    }



    public function obtener_hoja_de_evaluacion($id_actividad)
    {
        echo $id_actividad;

        $sql = "SELECT id_actividad, fecha_creacion, tbl_actividades.id_instrumento, numero_actividad, id_criterio, tbl_criterios.nombre, tbl_criterios.descripcion, valor_maximo FROM tbl_actividades INNER JOIN tbl_instrumento ON tbl_actividades.id_instrumento = tbl_instrumento.id_instrumento
        INNER JOIN tbl_criterios ON tbl_criterios.id_instrumento = tbl_instrumento.id_instrumento WHERE tbl_actividades.id_actividad = ?";

        $query = $this->db->query($sql, array($id_actividad));

        return $query->num_rows() > 0 ? $query->result() : null;
    }



    public function obtener_datos_alumno($vchMatricula)
    {

        $sql = "SELECT a.vchMatricula, a.vchAPaterno, a.vchAMaterno, a.vchNombre,a.chrClvEspecialidad,a.chrClvCarrera, c.vchNomCarrera, ct.vchNomCuatri,a.vchClvCuatri, a.chvGrupo, g.vchGrupo, vchPeriodo
                FROM tblAlumnos a
                LEFT JOIN tblCarreras c on a.chrClvCarrera = c.chrClvCarrera
                LEFT JOIN tblCuatrimestres ct on a.vchClvCuatri = ct.vchClvCuatri
                LEFT JOIN tbl_grupos g on a.chvGrupo = g.id_grupo
                where vchMatricula = ?";

        $query = $this->db->query($sql, array($vchMatricula));

        return $query->num_rows() > 0 ? $query->result() : null;
    }

    public function obtener_nombre_actividad($id_actividad)
    {
        $sql = "EXEC sp_ObtenerNombreActividad ?";
        $query = $this->db->query($sql, [$id_actividad]);

        return $query->num_rows() > 0 ? $query->result() : null;
    }

    public function listar_materias_por_cuatrimestre($vchClvTrabajador, $vchCuatrimestre, $periodo)
    {
        $sql = "SELECT DISTINCT tbl_docente_materia.vchClvMateria, [tbl_materias ].vchNomMateria FROM tbl_docente_materia 
        INNER JOIN [tbl_materias ] ON tbl_docente_materia.vchClvMateria = [tbl_materias ].vchClvMateria 
        WHERE vchClvTrabajador = ? AND tbl_docente_materia.vchCuatrimestre = ? AND Periodo = ?";

        $query = $this->db->query($sql, array($vchClvTrabajador, $vchCuatrimestre, $periodo));

        return $query->num_rows() > 0 ? $query->result() : []; //Resultado listo para JSON
    }


    public function listar_instrumentos($vchClvMateria, $parcial)
    {
        $sql = "SELECT * FROM tbl_instrumento WHERE vchClvMateria = ? AND parcial = ?";

        $query = $this->db->query($sql, array($vchClvMateria, $parcial));

        return $query->num_rows() > 0 ? $query->result() : []; //Resultado listo para JSON
    }



    public function obtener_alumnos_grupo($idGrupo, $materia, $vchClvTrabajador)
    {
        $query = $this->db->query(
            "
        EXEC sp_obtenerAlumnosPorGrupo 
            @claveDocente = ?, 
            @claveMateria = ?, 
            @idGrupo = ?",
            [$vchClvTrabajador, $materia, $idGrupo]
        );

        return $query->result_array();
    }



    public function obtener_equipos_por_grupo($idGrupo, $materia, $vchClvTrabajador)
    {
        // Obtener actividades anteriores
        $query = $this->db->query(
            "
        EXEC sp_obtenerActividadesConEquiposPorGrupo 
            @claveDocente = ?, 
            @claveMateria = ?, 
            @idGrupo = ?",
            [$vchClvTrabajador, $materia, $idGrupo]
        );

        $actividades = $query->result_array();

        if (empty($actividades)) {
            return [];
        }

        $maxActividad = max(array_column($actividades, 'id_actividad'));

   
        $queryEquipos = $this->db->query(
            "
        EXEC sp_equiposAnteriores 
            @idActividadAnterior = ?, 
            @idGrupo = ?",
            [$maxActividad, $idGrupo]
        );

        $equiposIds = array_column($queryEquipos->result_array(), 'id_equipo');
        if (empty($equiposIds)) {
            return [];
        }

        $this->db->where_in('id_equipo', $equiposIds);
        $equipos = $this->db->get('tbl_equipos')->result_array();

        foreach ($equipos as &$equipo) {
            $idEquipo = $equipo['id_equipo'];

            $integrantesQuery = $this->db->query(
                "SELECT 
                tbl_equipo_alumno.vchMatricula, 
                vchNombre, 
                vchAPaterno, 
                vchAMaterno 
            FROM tbl_equipo_alumno
            INNER JOIN tblAlumnos 
                ON tbl_equipo_alumno.vchMatricula = tblAlumnos.vchMatricula
            WHERE id_equipo = ?",
                [$idEquipo]
            );

            $equipo['integrantes'] = $integrantesQuery->result_array();
        }

        return $equipos;
    }



    public function obtener_integrantes_actividad($id_grupo, $id_actividad)
    {
        $sql = "
        SELECT e.id_equipo,
               e.nombre_equipo,
               COUNT(DISTINCT ea.vchMatricula) AS total_integrantes
        FROM tbl_equipos e
        INNER JOIN tbl_equipo_alumno ea ON e.id_equipo = ea.id_equipo
        INNER JOIN tbl_actividad_alumno aa ON ea.vchMatricula = aa.vchMatricula AND aa.id_actividad = ?
        WHERE e.id_grupo = ?
        GROUP BY e.id_equipo, e.nombre_equipo
        ORDER BY e.nombre_equipo
    ";

        $query = $this->db->query($sql, array($id_actividad, $id_grupo));
        return $query->result();
    }


    public function obtener_integrantes_equipo($id_grupo,  $id_equipo)
    {
        $sql = " SELECT e.id_equipo,
          a.vchMatricula,
		  a.vchNombre,
		  a.vchAMaterno,
		  a.vchAPaterno,
		  a.chvGrupo
        FROM tbl_equipos e
        LEFT JOIN tbl_equipo_alumno ea ON e.id_equipo = ea.id_equipo
        LEFT JOIN tblAlumnos a ON ea.vchMatricula = a.vchMatricula
        WHERE e.id_grupo = ? AND ea.id_equipo = ?
        ORDER BY a.vchNombre";

        $query = $this->db->query($sql, array($id_grupo, $id_equipo));
        return $query->result();
    }

    public function obtener_integrantes_con_calificacion($id_actividad_equipo, $periodo)
    {

        // Primero, obtenemos los datos de la actividad y el equipo vinculados
        $datos = $this->db->select('id_actividad, id_equipo')
            ->get_where('tbl_actividad_equipo', ['id_actividad_equipo' => $id_actividad_equipo])
            ->row();

        if (!$datos) {
            return []; // No se encontró la actividad-equipo
        }

        $id_actividad = $datos->id_actividad;
        $id_equipo = $datos->id_equipo;

        $this->db->select('alumno.vchMatricula, alumno.vchNombre, alumno.vchAPaterno, alumno.vchAMaterno, 
                       SUM(evaluacion.calificacion) AS calificacion');
        $this->db->from('tbl_equipo_alumno equipo_alumno');
        $this->db->join('tblAlumnos alumno', 'equipo_alumno.vchMatricula = alumno.vchMatricula');
        $this->db->join('tbl_actividad_alumno actividad_alumno', 'actividad_alumno.vchMatricula = alumno.vchMatricula AND actividad_alumno.id_actividad = ' . (int)$id_actividad, 'left');
        $this->db->join('tbl_evaluacion_criterioActividad evaluacion', 'evaluacion.id_actividad_alumno = actividad_alumno.id_actividad_alumno', 'left');
        $this->db->where('equipo_alumno.id_equipo', $id_equipo);
        $this->db->where('alumno.vchPeriodo', $periodo);
        $this->db->group_by('alumno.vchMatricula, alumno.vchNombre, alumno.vchAPaterno, alumno.vchAMaterno');



        return $this->db->get()->result_array();
    }



    public function obtener_id_actividad_alumno($id_actividad, $matricula)
    {
        $this->db->where('id_actividad', $id_actividad);
        $this->db->where('vchMatricula', $matricula);
        $query = $this->db->get('tbl_actividad_alumno');

        return $query->num_rows() > 0 ? $query->row()->id_actividad_alumno : null;
    }

    public function guardar_evaluacion_criterios($id_actividad_alumno, $criterios)
    {
        foreach ($criterios as $criterio) {
            // Verificar si ya existe una calificación para ese criterio
            $this->db->where([
                'id_actividad_alumno' => $id_actividad_alumno,
                'id_criterio' => $criterio['id_criterio']
            ]);
            $existe = $this->db->get('tbl_evaluacion_criterioActividad')->row();

            if ($existe) {
                // Si ya existe, actualiza
                $this->db->where([
                    'id_actividad_alumno' => $id_actividad_alumno,
                    'id_criterio' => $criterio['id_criterio']
                ])->update('tbl_evaluacion_criterioActividad', [
                    'calificacion' => $criterio['calificacion']
                ]);
            } else {
                // Si no existe, inserta
                $this->db->insert('tbl_evaluacion_criterioActividad', [
                    'id_actividad_alumno' => $id_actividad_alumno,
                    'id_criterio' => $criterio['id_criterio'],
                    'calificacion' => $criterio['calificacion']
                ]);
            }
        }
    }

    //
    public function actualizar_observacion_alumno($id_actividad_alumno, $observacion)
    {
        $this->db->where('id_actividad_alumno', $id_actividad_alumno);
        $this->db->update('tbl_actividad_alumno', ['observacion' => $observacion]);


        //echo $this->db->last_query();
    }



    public function obtener_componente()
    {

        $sql = "SELECT * FROM tbl_componentes";


        $query = $this->db->query($sql);

        return $query->num_rows() > 0 ? $query->result() : null;
    }


    public function obtener_actividades_por_grupo($vchClvTrabajador, $vchClvMateria, $id_grupo, $parcial = null, $modalidad = null)
    {
        $sql = "
        SELECT 
            a.id_actividad,  
            ag.id_actividad_grupo, 
            a.titulo, 
            a.descripcion, 
            a.fecha_creacion, 
            a.numero_actividad, 
            ISNULL(a.id_modalidad, 1) AS id_modalidad,
            ag.fecha_asignacion, 
            ag.fecha_entrega, 
            i.parcial, 
            ISNULL(i.nombre, 'Sin nombre') AS nombre_instrumento, 
            g.vchGrupo,

            -- Total alumnos asignados a la actividad
            (
                SELECT COUNT(*)
                FROM tbl_actividad_alumno aa
                INNER JOIN tblAlumnos al ON aa.vchMatricula = al.vchMatricula
                WHERE al.chvGrupo = ag.id_grupo 
                  AND al.vchPeriodo = i.vchPeriodo
                  AND al.intActivo = 1
                  AND aa.id_actividad = a.id_actividad
            ) AS numero_alumnos,

            -- Alumnos con evaluación individual
            (
                SELECT COUNT(DISTINCT al.vchMatricula)
                FROM tblAlumnos al
                JOIN tbl_actividad_alumno aa ON al.vchMatricula = aa.vchMatricula
                JOIN tbl_evaluacion_criterioActividad ec ON ec.id_actividad_alumno = aa.id_actividad_alumno
                WHERE al.chvGrupo = ag.id_grupo AND aa.id_actividad = a.id_actividad
            ) AS calificados_individual,

            -- Total de equipos en la actividad
            (
                SELECT COUNT(*)
                FROM tbl_actividad_equipo ae
                INNER JOIN tbl_equipos e ON ae.id_equipo = e.id_equipo
                WHERE ae.id_actividad = a.id_actividad AND e.id_grupo = ag.id_grupo
            ) AS numero_equipos,

            -- Equipos calificados (evaluados)
            (
                SELECT COUNT(DISTINCT ae.id_equipo)
                FROM tbl_actividad_equipo ae
                INNER JOIN tbl_equipos e ON ae.id_equipo = e.id_equipo
                INNER JOIN tbl_evaluacion_criterioActividadEquipo ec 
                    ON ec.id_actividad_equipo = ae.id_actividad_equipo
                WHERE ae.id_actividad = a.id_actividad AND e.id_grupo = ag.id_grupo
            ) AS equipos_calificados

        FROM tbl_actividades a
        JOIN tbl_instrumento i ON a.id_instrumento = i.id_instrumento
        JOIN tbl_actividad_grupo ag ON a.id_actividad = ag.id_actividad
        JOIN tbl_grupos g ON ag.id_grupo = g.id_grupo
        WHERE i.vchClvTrabajador = ?
          AND i.vchClvMateria = ?
          AND ag.id_grupo = ?
        ORDER BY i.parcial, a.numero_actividad DESC, a.fecha_creacion DESC;
    ";

        $query = $this->db->query($sql, array($vchClvTrabajador, $vchClvMateria, $id_grupo));

        return $query->num_rows() > 0 ? $query->result() : null;
    }






    //Obtenemos a todos los equipos 
    public function obtenerEquiposParaCalificar($idActividad)
    {
        $this->load->database();

        // Obtener valores del instrumento (ajustado si lo necesitas)
        $this->db->select('i.valor_total, SUM(c.valor_maximo) as suma_maxima_criterios');
        $this->db->from('tbl_actividades a');
        $this->db->join('tbl_instrumento i', 'a.id_instrumento = i.id_instrumento');
        $this->db->join('tbl_criterios c', 'i.id_instrumento = c.id_instrumento');
        $this->db->where('a.id_actividad', $idActividad);
        $this->db->group_by('i.valor_total');
        $instrumento = $this->db->get()->row();

        $valorTotal = $instrumento->valor_total ?? 10;
        $sumaMaximaCriterios = $instrumento->suma_maxima_criterios ?? 10;

        // Consulta corregida
        $sql = "
        SELECT 
            ae.id_actividad_equipo,
            e.id_equipo,
            e.nombre_equipo,
            ae.observacion,

            -- Lista de integrantes
            (
              SELECT STRING_AGG(al2.vchNombre + ' ' + ISNULL(al2.vchAPaterno, ''), ', ')
              FROM tbl_equipo_alumno ea2
              INNER JOIN tblAlumnos al2 ON ea2.vchMatricula = al2.vchMatricula
              WHERE ea2.id_equipo = e.id_equipo
            ) AS integrantes,

            -- Total de integrantes
            (
              SELECT COUNT(*) 
              FROM tbl_equipo_alumno ea3 
              WHERE ea3.id_equipo = e.id_equipo
            ) AS totalIntegrantes,

            -- Si ya fue calificado
            CASE 
              WHEN EXISTS (
                SELECT 1 
                FROM tbl_evaluacion_criterioActividadEquipo ece 
                WHERE ece.id_actividad_equipo = ae.id_actividad_equipo
              ) THEN 1 
              ELSE 0 
            END AS yaCalificado,

            -- Calificación total
            (
              SELECT ROUND(SUM(ece2.calificacion) * 1.0 / {$sumaMaximaCriterios} * {$valorTotal}, 1)
              FROM tbl_evaluacion_criterioActividadEquipo ece2
              WHERE ece2.id_actividad_equipo = ae.id_actividad_equipo
            ) AS calificacionTotal

        FROM tbl_actividad_equipo ae
        INNER JOIN tbl_equipos e ON ae.id_equipo = e.id_equipo
        WHERE ae.id_actividad = ?
        ORDER BY e.nombre_equipo
    ";

        $query = $this->db->query($sql, [$idActividad]);
        return $query->result_array();
    }

    public function obtener_integrantes_por_actividad_equipo($id_actividad_equipo)
    {

        return $this->db->query("
        SELECT a.vchMatricula, a.vchNombre, a.vchAPaterno, a.vchAMaterno
        FROM tbl_actividad_equipo ae
        JOIN tbl_equipo_alumno ea ON ae.id_equipo = ea.id_equipo
        JOIN tblAlumnos a ON ea.vchMatricula = a.vchMatricula
        WHERE ae.id_actividad_equipo = ?
    ", [$id_actividad_equipo])->result_array();
    }

    public function obtener_criterios_por_actividad_equipo($id_actividad_equipo)
    {
        return $this->db->query("
        SELECT c.*
        FROM tbl_actividad_equipo ae
        JOIN tbl_actividades a ON ae.id_actividad = a.id_actividad
        JOIN tbl_instrumento i ON a.id_instrumento = i.id_instrumento
        JOIN tbl_criterios c ON i.id_instrumento = c.id_instrumento
        WHERE ae.id_actividad_equipo = ?
    ", [$id_actividad_equipo])->result();
    }

    public function obtener_reporte_detallado($params, $periodo)
    {
        // Obtener el nombre de la materia a partir de la clave
        $materia = $this->db->select('vchNomMateria')
            ->from('tbl_materias')
            ->where('vchClvMateria', $params['materia'])
            ->get()
            ->row();

        // Validar si se encontró la materia
        if (!$materia) {
            return []; // O puedes lanzar un error personalizado
        }

        // Ejecutar el procedimiento almacenado
        $query = $this->db->query("
        EXEC sp_ReporteCalificaciones
            @Parcial = ?, 
            @Grupo = ?, 
            @Periodo = ?, 
            @Cuatrimestre = ?, 
            @Materia = ?", [
            $params['parcial'],
            $params['grupo'],
            $periodo,
            $params['cuatrimestre'],
            $materia->vchNomMateria
        ]);

        return $query->result_array();
    }

    public function obtener_concentrado_final($params, $periodo)
    {
        try {
            // Obtener el nombre de la materia a partir de la clave
            $materia = $this->db->select('vchNomMateria')
                ->from('tbl_materias')
                ->where('vchClvMateria', $params['materia'])
                ->get()
                ->row();

            // Validar si se encontró la materia
            if (!$materia) {
                return []; // O puedes lanzar un error personalizado
            }

            // Ejecutar el procedimiento almacenado
            $query = $this->db->query("
        EXEC sp_FiltrarConcentradoFinal 
            @Parcial = ?, 
            @Grupo = ?, 
            @Periodo = ?, 
            @Cuatrimestre = ?, 
            @Materia = ?", [
                $params['parcial'],
                $params['grupo'],
                $periodo,
                $params['cuatrimestre'],
                $materia->vchNomMateria
            ]);

            return $query->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error en obtener_reporte_detallado: ' . $e->getMessage());
            return false; // o []
        }
    }

    public function informacion_de_la_actividad($id_actividad)
    {
        $this->db->where('id_actividad', $id_actividad);

        $query = $this->db->get('tbl_actividades');

        return $query->row();
    }

    public function obtener_detalles_actividad_equipo($id_actividad_equipo)
    {

        /*SELECT * FROM tbl_actividades
        INNER JOIN  tbl_instrumento ON tbl_actividades.id_instrumento = tbl_instrumento.id_instrumento WHERE id_actividad = */

        $this->db->select('tbl_actividades.titulo, tbl_actividades.descripcion, tbl_actividad_equipo.id_actividad');
        $this->db->from('tbl_actividades');
        $this->db->join('tbl_actividad_equipo', 'tbl_actividad_equipo.id_actividad = tbl_actividades.id_actividad');
        $this->db->where('tbl_actividad_equipo.id_actividad_equipo', $id_actividad_equipo);

        $query = $this->db->get();
        return $query->row(); // Devuelve un solo registro
    }


    public function  informacion_de_la_materia($vchClvMateria)
    {
        $this->db->where('vchClvMateria', $vchClvMateria);
        $query = $this->db->get('tbl_materias');

        return $query->row();
    }

    public function guardar_evaluacion_equipo($id_actividad_equipo, $id_equipo, $criterios, $observacion)
    {
        $this->db->trans_start(); // 🔁 Inicia transacción

        log_message('debug', 'Iniciando guardado de evaluación de equipo. ID actividad equipo: ' . $id_actividad_equipo);

        // 1. Obtener id_actividad desde tbl_actividad_equipo
        $actividad = $this->db->get_where('tbl_actividad_equipo', [
            'id_actividad_equipo' => $id_actividad_equipo
        ])->row();

        if (!$actividad) {
            log_message('error', 'No se encontró la actividad con ID: ' . $id_actividad_equipo);
            return [
                'success' => false,
                'message' => 'No se encontró la actividad para el ID proporcionado.'
            ];
        }

        $id_actividad = $actividad->id_actividad;
        log_message('debug', 'ID actividad recuperado: ' . $id_actividad);

        // 2. Verificar si ya existe evaluación del equipo
        $existe = $this->db->get_where('tbl_actividad_equipo', [
            'id_actividad' => $id_actividad,
            'id_equipo' => $id_equipo
        ])->row();

        if ($existe) {
            log_message('debug', 'Evaluación ya existente. Se actualiza observación.');
            // Actualiza la observación
            $this->db->where([
                'id_actividad' => $id_actividad,
                'id_equipo' => $id_equipo
            ])->update('tbl_actividad_equipo', [
                'observacion' => $observacion
            ]);
            $id_actividad_equipo = $existe->id_actividad_equipo;
        } else {
            log_message('debug', 'No existe evaluación previa. Se inserta nueva.');
            // Inserta nuevo registro
            $this->db->insert('tbl_actividad_equipo', [
                'id_actividad' => $id_actividad,
                'id_equipo' => $id_equipo,
                'observacion' => $observacion
            ]);
            $id_actividad_equipo = $this->db->insert_id();
            log_message('debug', 'Nuevo ID actividad_equipo generado: ' . $id_actividad_equipo);
        }

        // 3. Eliminar criterios anteriores
        $this->db->where('id_actividad_equipo', $id_actividad_equipo)->delete('tbl_evaluacion_criterioActividadEquipo');
        log_message('debug', 'Criterios anteriores eliminados para ID actividad_equipo: ' . $id_actividad_equipo);

        // 4. Insertar criterios nuevos
        foreach ($criterios as $criterio) {
            $this->db->insert('tbl_evaluacion_criterioActividadEquipo', [
                'id_equipo' => $id_equipo,
                'id_actividad_equipo' => $id_actividad_equipo,
                'id_criterio' => $criterio['id_criterio'],
                'calificacion' => $criterio['calificacion']
            ]);
            log_message('debug', 'Criterio insertado: ' . json_encode($criterio));
        }

        // 5. Obtener integrantes del equipo
        $integrantes = $this->db
            ->select('vchMatricula')
            ->where('id_equipo', $id_equipo)
            ->get('tbl_equipo_alumno')
            ->result();

        log_message('debug', 'Cantidad de integrantes: ' . count($integrantes));

        // 6. Procesar evaluación individual por alumno
        foreach ($integrantes as $alumno) {
            $matricula = $alumno->vchMatricula;

            $actividad_alumno = $this->db->get_where('tbl_actividad_alumno', [
                'id_actividad' => $id_actividad,
                'vchMatricula' => $matricula
            ])->row();

            if (!$actividad_alumno) {
                // 🔹 Crear el registro con observación e id_estado = 2
                $this->db->insert('tbl_actividad_alumno', [
                    'id_actividad' => $id_actividad,
                    'vchMatricula' => $matricula,
                    'observacion' => $observacion,
                    'id_estado'   => 2
                ]);
                $id_actividad_alumno = $this->db->insert_id();
            } else {
                $id_actividad_alumno = $actividad_alumno->id_actividad_alumno;

                // 🔹 Si ya existe, actualizamos observación e id_estado = 2
                $this->db->where('id_actividad_alumno', $id_actividad_alumno)
                    ->update('tbl_actividad_alumno', [
                        'observacion' => $observacion,
                        'id_estado'   => 2
                    ]);
            }


            // Eliminar evaluaciones anteriores
            $this->db->where('id_actividad_alumno', $id_actividad_alumno)->delete('tbl_evaluacion_criterioActividad');
            log_message('debug', 'Evaluaciones anteriores eliminadas para ' . $matricula);

            // Insertar nuevas calificaciones individuales
            foreach ($criterios as $criterio) {
                $this->db->insert('tbl_evaluacion_criterioActividad', [
                    'id_actividad_alumno' => $id_actividad_alumno,
                    'id_criterio' => $criterio['id_criterio'],
                    'calificacion' => $criterio['calificacion']
                ]);
                log_message('debug', 'Criterio individual insertado para ' . $matricula . ': ' . json_encode($criterio));
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            log_message('error', 'Transacción fallida al guardar evaluación de equipo.');
            return [
                'success' => false,
                'message' => 'Error al guardar la evaluación. Transacción fallida.'
            ];
        }

        log_message('debug', 'Evaluación de equipo guardada exitosamente.');
        return [
            'success' => true,
            'message' => 'Evaluación guardada correctamente.'
        ];
    }






    public function obtener_equipo_por_actividad($id_actividad_equipo)
    {
        $this->db->select('id_equipo');
        $this->db->from('tbl_actividad_equipo');
        $this->db->where('id_actividad_equipo', $id_actividad_equipo);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row()->id_equipo;
        }

        return null;
    }

    public function obtenerEquipoPorActividadEquipo($id_actividad_equipo)
    {
        $this->db->select('tbl_equipos.id_equipo, tbl_equipos.nombre_equipo');
        $this->db->from('tbl_equipos');
        $this->db->join('tbl_actividad_equipo', 'tbl_equipos.id_equipo = tbl_actividad_equipo.id_equipo');
        $this->db->where('tbl_actividad_equipo.id_actividad_equipo', $id_actividad_equipo);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row(); // Devuelve un solo equipo
        } else {
            return null;
        }
    }


    //Funciones de actualizar
    public function crear_actividad_alumno($id_actividad, $matricula, $id_equipo)
    {
        $this->db->insert('tbl_actividad_alumno', [
            'id_actividad' => $id_actividad,
            'vchMatricula' => $matricula,
            'id_equipo' => $id_equipo
        ]);
        return $this->db->insert_id();
    }

    public function eliminar_criterios_individuales($id_actividad_alumno)
    {
        $this->db
            ->where('id_actividad_alumno', $id_actividad_alumno)
            ->delete('tbl_evaluacion_criterioActividad');
    }

    public function insertar_criterio_individual($id_actividad_alumno, $id_criterio, $calificacion)
    {
        $this->db->insert('tbl_evaluacion_criterioActividad', [
            'id_actividad_alumno' => $id_actividad_alumno,
            'id_criterio' => $id_criterio,
            'calificacion' => $calificacion
        ]);
    }


    public function actualizar($id_actividad, $id_grupo, $fecha_entrega_completa)
    {
        $this->db->where('id_actividad', $id_actividad);
        $this->db->where('id_grupo', $id_grupo);
        $this->db->update('tbl_actividad_grupo', [
            'fecha_entrega' => $fecha_entrega_completa
        ]);

        return $this->db->affected_rows() > 0;
    }


    public function obtener_detalles_de_la_actividad($id_actividad)
    {
        $this->db->select('
        a.titulo,
        a.descripcion,
        a.fecha_creacion,
        i.vchClvMateria,
        m.vchNomMateria,
        i.parcial,
        i.vchPeriodo
    ');
        $this->db->from('tbl_actividades a');
        $this->db->join('tbl_instrumento i', 'a.id_instrumento = i.id_instrumento');
        $this->db->join('tbl_materias m', 'i.vchClvMateria = m.vchClvMateria');
        $this->db->where('a.id_actividad', $id_actividad);

        return $this->db->get()->row(); // Devuelve solo una fila
    }


    public function eliminar_actividad($id_actividad, $id_grupo)
    {
        // Ejecutar el procedimiento almacenado
        $sql = "EXEC sp_validarYEliminarActividadGrupo ?, ?";
        $query = $this->db->query($sql, [$id_actividad, $id_grupo]);

        // Obtener el mensaje devuelto
        $resultado = $query->row();

        if (isset($resultado->mensaje)) {
            // Analizar el contenido del mensaje
            if (strpos($resultado->mensaje, 'eliminada correctamente') !== false) {
                return ['success' => true, 'mensaje' => $resultado->mensaje];
            } else {
                return ['success' => false, 'error' => $resultado->mensaje];
            }
        } else {
            return ['success' => false, 'error' => 'Error inesperado en la eliminación.'];
        }
    }



    public function obtener_concentrado_completo($params, $periodo)
    {
        try {
            // Obtener el nombre de la materia a partir de la clave
            $materia = $this->db->select('vchNomMateria')
                ->from('tbl_materias')
                ->where('vchClvMateria', $params['materia'])
                ->get()
                ->row();

            // Validar si se encontró la materia
            if (!$materia) {
                return []; // O puedes lanzar un error personalizado
            }

            // Ejecutar el procedimiento almacenado
            $query = $this->db->query("
       EXEC sp_ConcentradoCompleto
            @Parcial = ?, 
            @Grupo = ?, 
            @Periodo = ?, 
            @Cuatrimestre = ?, 
            @Materia = ?", [
                $params['parcial'],
                $params['grupo'],
                $periodo,
                $params['cuatrimestre'],
                $materia->vchNomMateria
            ]);

            return $query->result_array();
        } catch (Exception $e) {
            log_message('error', 'Error en obtener_reporte_detallado: ' . $e->getMessage());
            return false; // o []
        }
    }
}
