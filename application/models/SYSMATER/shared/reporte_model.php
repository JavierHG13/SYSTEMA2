<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reporte_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	//? TERMINADO
	//! ok sp yazir
	public function obtener_resultados_examen_docente($materia, $periodo, $vchClvTrabajador, $id_examen){
		$query = $this->db->query("EXEC sp_obtener_resultados_examen_docente '$materia', '$periodo', '$vchClvTrabajador', $id_examen");

		if ($query->num_rows() == 0) { return NULL; } 
		else { return $query->result_array(); }
	}
	//! ok sp yazir
	public function obtener_resultados_examen_docente_grupo($materia, $periodo, $vchClvTrabajador, $id_examen,$grupo){
		$grupo = $this->obtener_id_grupo($grupo);
		$query = $this->db->query("EXEC sp_obtener_resultados_examen_docente_grupo '$materia', '$periodo', '$vchClvTrabajador', $id_examen, $grupo");

		if ($query->num_rows() == 0) { return NULL; } 
		else { return $query->result_array(); }
	}
	//! ok sp yazir
	public function obtener_id_grupo($nombre_grupo) {       
        $query = $this->db->query("EXEC sp_obtener_id_grupo '$nombre_grupo'");
        return ($query->num_rows() > 0) ? $query->row()->id_grupo : null;
    }
	//! ok sp yazir
	public function obtener_resultados_examen_admin($materia, $id_examen){
		$query = $this->db->query("EXEC sp_obtener_resultados_examen_admin '$materia', $id_examen");

		if ($query->num_rows() == 0) { return NULL; } 
		else { return $query; }
	}
	//! ok sp yazir
	public function obtener_resultados_examen_parciales_admin($materia, $periodo) {
		$query = $this->db->query("EXEC sp_obtener_resultados_examen_parciales_admin '$materia', '$periodo'");

		if ($query->num_rows() == 0) { return NULL; } 
		else { return $query->result_array(); }
	}
	//! ok sp yazir
	public function obtener_resultados_examen_parciales_docente_admin($materia, $periodo){
		$query = $this->db->query("EXEC sp_obtener_resultados_examen_parciales_docente_admin '$materia', '$periodo'");

		if ($query->num_rows() == 0) { return NULL; } 
		else { return $query->result_array(); }
	}
	//! ok sp yazir
	public function obtener_resultados_parciales_materia_docente($materia, $periodo, $vchClvTrabajador){
		$query = $this->db->query("EXEC sp_obtener_resultados_parciales_materia_docente '$materia', '$periodo', '$vchClvTrabajador'");

		if ($query->num_rows() == 0) { return NULL; } 
		else { return $query->result_array(); }
	}
	//! ok sp yazir
	public function obtener_resultados_parciales_materia_docente_grupo($materia, $periodo, $vchClvTrabajador,$grupo){
		$query = $this->db->query("EXEC sp_obtener_resultados_parciales_materia_docente_grupo '$materia', '$periodo', '$vchClvTrabajador','$grupo'");

		if ($query->num_rows() == 0) { return NULL; } 
		else { return $query->result_array(); }
	}
	//! ok sp yazir
	public function obtener_resultados_materia_docente($materia, $periodo,$vchClvTrabajador){
		$query = $this->db->query("EXEC sp_obtener_resultados_materia_docente '$materia', '$periodo', '$vchClvTrabajador'");

		if ($query->num_rows() == 0) { return NULL; } 
		else { return $query->result_array(); }
	}
}