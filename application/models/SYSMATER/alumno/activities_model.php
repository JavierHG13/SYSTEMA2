
<?php
/*
 * get test that activities
 * 
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Activities_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_materias_con_actividades($matricula, $periodo, $parcial){
		$sql = "EXEC sp_GetMateriasActividadesAlumno ?, ?, ?";
		$query = $this->db->query($sql, array($matricula, $periodo, $parcial));
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
		return array();
	}

	public function get_detalle_actividad($id_actividad, $matricula){
		$result = array();

		// 1. Info básica
		$sql1 = "EXEC sp_GetInfoBasicaActividad ?, ?";
		$query1 = $this->db->query($sql1, array($id_actividad, $matricula));
		if ($query1->num_rows() > 0) {
			$result['info_basica'] = $query1->row_array();
		} else {
			return array();
		}

		// 2. Calificación
		$sql2 = "EXEC sp_GetCalificacionActividad ?, ?";
		$query2 = $this->db->query($sql2, array($id_actividad, $matricula));
		if ($query2->num_rows() > 0) {
			$result['calificacion_total'] = $query2->row_array();
		} else {
			$result['calificacion_total'] = null;
		}

		// 3. Criterios
		$sql3 = "EXEC sp_GetCriteriosActividad ?, ?";
		$query3 = $this->db->query($sql3, array($id_actividad, $matricula));
		if ($query3->num_rows() > 0) {
			$result['criterios'] = $query3->result_array();
		} else {
			$result['criterios'] = array();
		}

		// 4. Observaciones del docente
		$sql4 = "EXEC sp_GetObservacionActividad ?, ?";
		$query4 = $this->db->query($sql4, array($id_actividad, $matricula));
		if ($query4->num_rows() > 0) {
			$result['observacion'] = $query4->row_array();
		} else {
			$result['observacion'] = null;
		}

		return $result;
	}
}
