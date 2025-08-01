<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Examenes_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_examenes_alumno($matricula, $periodo, $cuatrimestre, $grupo){
        $sql = "EXEC sp_GetExamenesAlumno ?, ?, ?, ?";
        $query = $this->db->query($sql, array($matricula, $periodo, $cuatrimestre, $grupo));

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function get_info_basica_examen($id_examen, $matricula){
        $sql = "EXEC sp_GetInfoBasicaExamen ?, ?";
        $query = $this->db->query($sql, array($id_examen, $matricula));

        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return array();
    }

    public function get_respuestas_examen($id_examen, $matricula){
        $sql = "EXEC sp_GetRespuestasExamen ?, ?";
        $query = $this->db->query($sql, array($id_examen, $matricula));

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
}
