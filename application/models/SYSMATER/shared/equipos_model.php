<?php
defined('BASEPATH') or exit('No direct script access allowed');

class equipos_model extends CI_Model
{

    // call the model constructor.
    public function __construct()
    {
        parent::__construct();
    }

    public function obtenerMaterias($claveDocente)
    {
        return $this->db
            ->where('vchClvTrabajador', $claveDocente)
            ->get('tbl_materias_asignadas');
    }

    public function obtenerEquiposPorGrupo($idGrupo, $periodo, $materia)
    {

        $cuatriQuery = $this->db->query("
        SELECT vchCuatrimestre 
        FROM tbl_materias 
        WHERE vchClvMateria = ?", [$materia]);

        $cuatrimestre = $cuatriQuery->row()->vchCuatrimestre ?? null;

        if ($cuatrimestre === null) {
            return [];
        }

        $equipos = $this->db
            ->select('id_equipo, nombre_equipo')
            ->from('tbl_equipos')
            ->where('id_grupo', $idGrupo)
            ->where('Periodo', $periodo)
            ->where('vchClvMateria', $materia)   // 🔹 Filtro de materia
            ->order_by('nombre_equipo')
            ->get()
            ->result();

        $sql = "
        SELECT 
            ea.id_equipo,
            a.vchMatricula,
            a.vchNombre,
            a.vchAPaterno,
            a.vchAMaterno
        FROM tbl_equipo_alumno ea
        JOIN tblAlumnos a ON a.vchMatricula = ea.vchMatricula
        WHERE ea.id_equipo IN (
            SELECT id_equipo 
            FROM tbl_equipos 
            WHERE id_grupo = ? AND Periodo = ? AND vchClvMateria = ?
        )
          AND a.vchPeriodo = ?
          AND a.vchClvCuatri = ?";

        $query = $this->db->query($sql, [$idGrupo, $periodo, $materia, $periodo, $cuatrimestre]);

        $integrantes = $query->result();

        $equipos_con_integrantes = [];
        foreach ($equipos as $equipo) {
            $equipos_con_integrantes[$equipo->id_equipo] = (object)[
                'id_equipo' => $equipo->id_equipo,
                'nombre_equipo' => $equipo->nombre_equipo,
                'integrantes' => [],
            ];
        }

        foreach ($integrantes as $alumno) {
            $equipos_con_integrantes[$alumno->id_equipo]->integrantes[] = $alumno;
        }

        return array_values($equipos_con_integrantes);
    }



    public function alumnosSinEquipo($idGrupo, $periodo, $materia)
    {
        $sql = "
    SELECT a.vchMatricula, 
           CONCAT(a.vchAPaterno, ' ', a.vchAMaterno, ' ', a.vchNombre) AS nombre_completo
    FROM tblAlumnos a
    INNER JOIN tbl_grupos g ON g.id_grupo = a.chvGrupo
    INNER JOIN tbl_docente_materia_grupo dmg ON dmg.id_grupo = g.id_grupo
    INNER JOIN tbl_docente_materia dm ON dm.idDocenteMateria = dmg.id_DocenteMateria
    INNER JOIN tbl_materias m ON m.vchClvMateria = dm.vchClvMateria
    WHERE g.id_grupo = ? 
      AND a.vchPeriodo = ?
      AND a.vchClvCuatri = m.vchCuatrimestre   -- 🔹 Cuatrimestre correcto
      AND dm.vchClvMateria = ?                 -- 🔹 Solo la materia actual
      AND NOT EXISTS (
          SELECT 1 
          FROM tbl_equipo_alumno ea
          INNER JOIN tbl_equipos e ON e.id_equipo = ea.id_equipo
          WHERE ea.vchMatricula = a.vchMatricula
            AND e.vchClvMateria = ?            -- 🔹 Solo excluir si ya tiene equipo en esta materia
            AND e.Periodo = ?                  -- 🔹 Y en el mismo periodo
      )
    ORDER BY a.vchAPaterno ASC";

        $query = $this->db->query($sql, [$idGrupo, $periodo, $materia, $materia, $periodo]);

        return $query->result();
    }




    // Inserta un equipo y devuelve su id
    public function insertar_equipo($id_grupo, $nombre_equipo, $periodo, $vchClvMateria)
    {
        $data = array(
            'id_grupo' => $id_grupo,
            'nombre_equipo' => $nombre_equipo,
            'Periodo' => $periodo,
            'vchClvMateria' => $vchClvMateria,

        );
        $this->db->insert('tbl_equipos', $data);

        return $this->db->insert_id();
    }

    // Inserta integrantes a un equipo
    public function insertar_integrantes($id_equipo, $alumnos = [])
    {
        $tbl_equipo_alumno = 'tbl_equipo_alumno';

        if (empty($alumnos)) return false;

        $data = [];
        foreach ($alumnos as $matricula) {
            $data[] = [
                'id_equipo' => $id_equipo,
                'vchMatricula' => $matricula
            ];
        }

        return $this->db->insert_batch($tbl_equipo_alumno, $data);  // sin $this->
    }



    public function actualizarEquipo($datos)
    {
        $idEquipo = $datos['id_equipo'];
        $matriculas = $datos['matriculas'];

        if (!is_array($matriculas)) {
            $matriculas = [$matriculas];
        }

        foreach ($matriculas as $matricula) {
            if (!empty($matricula)) {
                $this->db->insert('tbl_equipo_alumno', [
                    'id_equipo' => $idEquipo,
                    'vchMatricula' => $matricula
                ]);
            }
        }
    }


    public function eliminar_integrante($id_equipo, $matricula)
    {
        // Validar si el equipo tiene actividades asignadas
        $asignado = $this->db
            ->where('id_equipo', $id_equipo)
            ->get('tbl_actividad_equipo')
            ->num_rows();

        if ($asignado > 0) {
            return [
                'success' => false,
                'mensaje' => 'No se puede eliminar porque el alumno está asignado a una actividad.'
            ];
        }

        // Eliminar si no está en actividad
        $this->db->where('id_equipo', $id_equipo);
        $this->db->where('vchMatricula', $matricula);
        $this->db->delete('tbl_equipo_alumno');

        return ['success' => true];
    }



    public function eliminarEquipo($idEquipo)
    {
        $this->db->where('id_equipo', $idEquipo)->delete('tbl_equipo_alumno');
        $this->db->where('id_equipo', $idEquipo)->delete('tbl_equipos');
    }
}
