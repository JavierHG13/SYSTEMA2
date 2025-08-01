<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Docente_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	//? TERMINADO	
	//! ok sp
	public function existe_docente($datos){
		$query = $this->db->query("EXEC sp_get_docente '$datos[chrClvTrabajador]'");

		if ($query->num_rows() > 0) { return true; } 
		else { return false; }
	}
	//! ok sp
	public function get_docente($id){
		$query = $this->db->query("EXEC sp_get_docente '$id'");

		if ($query->num_rows() > 0) { return $query; } 
		else { return false; }
	}
	//! ok sp
	public function elimina_docente($id){
		$query = $this->db->query("EXEC sp_eliminar_docente $id");
		$count = $this->db->count_all_results();
		return $count > 0;
	}
	//! ok sp
	public function verifica_docente_eliminar($id){
		$query = $this->db->query("EXEC sp_verifica_docente_eliminar '$id'");

		if ($query->num_rows() > 0) { return $query; } 
		else { return false; }
	}
	//! ok sp
	public function lista_docentes_todos(){
		$query = $this->db->query("EXEC sp_lista_docentes_todos");
		if ($query->num_rows() == 0) { return NULL; } 
		else { return $query; } 
	} 
	//! ok sp
	public function lista_docentes_asignacion($vchClvTrabajador, $periodo){
		$query = $this->db->query("EXEC sp_lista_docentes_asignacion '$vchClvTrabajador','$periodo'");
		//periodo de reprobados adfsjed
		if ($query->num_rows() == 0) {
			$fechaActual = new DateTime();

			$año = $fechaActual->format('Y');
			$periodo = $año.'4';

			$query = $this->db->query("EXEC sp_lista_docentes_asignacion '$vchClvTrabajador','$periodo'");
		} else {
			return $query;
		} 
		return $query;
	}
	//! ok sp
	public function lista_docentes_asignacion_Admin($vchClvTrabajador){
		$query = $this->db->query("EXEC sp_lista_docentes_asignacion_admin '$vchClvTrabajador'");

		if ($query->num_rows() == 0) { return NULL; } 
		else { return $query; }
	}
	//! ok sp
	public function lista_materias_asignadas_docente($vchClvTrabajador)
	{
		$periodo = $this->determinarPeriodo();
		$query = $this->db->query("EXEC sp_lista_materias_asignadas_docente '$vchClvTrabajador','$periodo';");

		if ($query->num_rows() == 0) { return NULL; }
		else { return $query; }		
	}

	//! Funcion de apoyo
	private function determinarPeriodo() {
		$fechaActual = new DateTime();
		$mes = (int) $fechaActual->format('m');
		$año = $fechaActual->format('Y');
		$periodo = 0;

		if ($mes >= 1 && $mes <= 4) {
			$periodo = 1;
		} elseif ($mes >= 5 && $mes <= 8) {
			$periodo = 2;
		} elseif ($mes >= 9 && $mes <= 12) {
			$periodo = 3;
		} else {
			$periodo = 4;
		}

		return $año . $periodo;
	}
	//! ok sp
	public function eliminar_materia_docente($idDocMat){
		return $this->db->query("EXEC sp_eliminar_docente_materia_grupo $idDocMat");
	}
	//! ok sp nuevas revisar ->revisada 19 julio 2025
	public function obtener_grupos_por_carrera_cuatri($carrera, $cuatrimestre){
		$query = $this->db->query("EXEC sp_obtener_grupos_por_carrera_cuatri ?, ?", array($carrera, $cuatrimestre));
		return $query->num_rows() > 0 ? $query : NULL;
	}
	//! ok sp
	public function nuevo_docente($data){
		$query=$this->db->insert('tblTrabajadores', $data);
		return $this->db->query("EXEC sp_nuevo_docente '$data[vchClvTrabajador]','$data[vchNombre]','$data[vchAPaterno]',
			'$data[vchAMaterno]',$data[intActivo],$data[id_Tipo_Usuario];");
	}
	//? PENDIENTE
	public function verificar_grupo($id_grupo) {
    	$this->db->where('id_grupo', $id_grupo);
    	return $this->db->count_all_results('tbl_grupos') > 0;
	}
	//! ok sp
	public function obtener_grupos_disponibles($materia, $cuatrimestre, $periodo){
		$todos_grupos = $this->db->query("EXEC sp_get_todos_grupos ?", array($cuatrimestre))->result();
		if (empty($todos_grupos)) {
			return [
				'status' => 'no_grupos',
				'mensaje' => 'No hay alumnos registrados para el cuatrimestre ' . $cuatrimestre. ' y periodo ' . $periodo
			];
		}

		$asignados = $this->db->query("EXEC sp_get_grupos_asignados ?, ?, ?", array($materia, $periodo, $cuatrimestre))->result();
		$asignados_ids = array_column($asignados, 'id_grupo');
		$disponibles = [];

		foreach($todos_grupos as $grupo) {
			if(!in_array($grupo->id_grupo, $asignados_ids)) {
				$disponibles[] = $grupo;
			}
		}

		if (empty($disponibles)) {
			return [
				'status' => 'todos_asignados',
				'mensaje' => 'Todos los grupos ya están asignados para la materia ' . $materia . ' en el cuatrimestre ' . $cuatrimestre . ' y periodo ' . $periodo
			];
		}
		return  $disponibles;
	}
	//! ok sp
	public function get_periodos_docente($clave_trabajador){
		$query = $this->db->query("EXEC sp_get_periodos_docente '$clave_trabajador'");
		
		if ($query->num_rows() > 0) { return $query->result(); } 
		else { return null; }
	}
	//! ok sp
	public function lista_docentes_asignacion_filtrada($vchClvTrabajador){
		$query = $this->db->query("EXEC sp_lista_docentes_asignacion_filtrada '$vchClvTrabajador'");

		return $query->num_rows() > 0 ? $query : NULL;
	}
}