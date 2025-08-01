<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Alumno_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	//? TERMINADO
	//! ok sp
	public function filtrar_Alumnos($cuatrimestre, $grupo_id, $periodo)
	{
		$query = $this->db->query("EXEC sp_filtrar_alumnos ?, ?, ?", 
			array($cuatrimestre, $grupo_id, $periodo));
		
		return $query->num_rows() > 0 ? $query : NULL;
	}
	//! ok sp
	public function traer_carreas(){
		$query = $this->db->query("EXEC sp_traer_carreras");

		if ($query->num_rows() > 0) {return $query;} else {return null;} 
	}
	//! ok sp
	public function traer_materias(){
		$query = $this->db->query("EXEC sp_traer_grupos");

		if ($query->num_rows() > 0) {return $query;} else {return null;} 
	}
	//! ok sp
	public function existe_matricula($data){
		$query = $this->db->query("EXEC sp_existe_matricula ?", array($data['vchMatricula']));

		if ($query->num_rows() >= 1){return true;} else {return false;}
	}
	//! ok sp
	public function inserta_alumno($data){
		$query = $this->db->query("EXEC sp_inserta_alumno ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?", array(
			$data['vchMatricula'],
			$data['vchAPaterno'],
			$data['vchAMaterno'],
			$data['vchNombre'],
			$data['vchContrasenia'],
			$data['intActivo'],
			$data['chrClvCarrera'],
			$data['chrClvEspecialidad'],
			$data['vchClvCuatri'],	
			$data['chvGrupo'],
			$data['dtmFchInsc'],
			$data['vchPeriodo'],
			(int)$data['id_Tipo_Usuario']
		));

		if($this->db->affected_rows() > 0){ return true; }
		else { return false; }
	}

	//! sp 
	public function get_detalles_alumno($id){
		$query = $this->db->query("EXEC sp_get_detalles_alumno ?", array($id));

		if ($query->num_rows() > 0) {
			return $query;
		} else {
			return false;
		}
	}
	//! ok sp
	public function lista_pendientes_grupo($id_examen, $grupo)
	{
		$query = $this->db->query("EXEC sp_lista_pendientes_grupo $id_examen, '$grupo'");

		if ($query->num_rows() == 0) { return NULL; } 
		else { return $query; }
	}
	//! ok sp
	public function lista_progreso_grupo($id_examen, $grupo) 
	{
		$query = $this->db->query("EXEC sp_lista_progreso_grupo $id_examen, '$grupo'");

		if ($query->num_rows() == 0) { return NULL; } 
		else { return $query; }
	}
	//! ok sp
	public function lista_fin_examen_grupo($id_examen, $grupo)
	{
		$query = $this->db->query("EXEC sp_lista_fin_examen_grupo $id_examen, '$grupo'");

		if ($query->num_rows() == 0) { return NULL; } 
		else { return $query; }
	}
	//! sp ok
	public function get_periodos_disponibles()
	{
		$query = $this->db->query("EXEC sp_get_periodos_disponibles");
		return $query->result();
	}
	//! sp ok
	public function get_cuatrimestres_por_periodo($periodo)
	{
		$query = $this->db->query("EXEC sp_get_cuatrimestres_por_periodo_filtro_alumnos ?", array($periodo));
		return $query->num_rows() > 0 ? $query->result() : [];
	}
	//! sp ok
	public function get_grupos_por_cuatrimestre_periodo($cuatrimestre, $periodo)
	{
		$query = $this->db->query("EXEC sp_get_grupos_por_cuatrimestre_periodo ?, ?", 
			array($cuatrimestre, $periodo));
		return $query->num_rows() > 0 ? $query->result() : [];
	}
	//SP OK
	public function get($matricula)
	{
		$query = $this->db->query("EXEC sp_get_alumno_respuestas ?", array($matricula));

		if ($query->num_rows() == 0) {
			return NULL;
		} else {
			return $query;
		}
	}

	public function getmaquina($matricula)
	{
		$query = $this->db->query("EXEC sp_get_maquina_alumno ?", array($matricula));
		
		return $query->num_rows() > 0 ? $query : NULL;
	}

	public function liberar($matricula, $fch_termina)
	{
		$query = $this->db->query("EXEC sp_liberar_maquina ?, ?", array(
			$matricula,
			$fch_termina->format('Y-m-d H:i:s')
		));
		
		$result = $query->row();
		return $result->filas_afectadas > 0;
	}

	public function borrar($matricula)
	{
		$query = $this->db->query("EXEC sp_borrar_maquina ?", array($matricula));
		
		$result = $query->row();
		return $result->filas_afectadas > 0;
	}
	
}
