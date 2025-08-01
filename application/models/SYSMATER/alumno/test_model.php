<?php
/*
 * get test that alumnos respond
 * 
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Test_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	//? TERMINADO
	//! ok sp 
	public function get_test($id_examen){
		$query = $this->db->query("EXEC sp_get_id_reactivo_detail $id_examen");
		$num = $query->num_rows();

		if ($num == 0) { return NULL; } 
		else { return $num; }
	} 
	//! ok sp
	public function get_questions_alumno($id_examen, $matricula){
		$query = $this->db->query("Exec get_questions_alumno_sysmater $id_examen,'$matricula'");

		$num = $query->num_rows();
		if ($num == 0) { return NULL; } 
		else { return $query->result_array(); }
	} 
	//! ok sp
	public function get_questions($id_examen){
		$query = $this->db->query("EXEC sp_get_reactivo_details $id_examen");

		$num = $query->num_rows();
		if ($num == 0) { return NULL; } 
		else { return $query->result_array(); }
	} 
	//! ok sp
	public function get_num_responses($id_examen, $matricula){
		$query = $this->db->query("EXEC sp_get_num_responses $id_examen, '$matricula'");

		$num = $query->num_rows();
		return $num;
	} 
	//! ok sp
	public function check_if_start($mac, $matricula, $id_examen, $grupo){
		$query = $this->db->query("EXEC sp_get_mac '$matricula', $id_examen");
		
		if ($query->num_rows() != 0) {
			$response = $query->result_array();			
			$mac_registered = trim($response[0]["vch_mac"]);
		
			if ($mac === $mac_registered || $mac_registered === "") {
				$this->actualiza_tiempo_restante($matricula, $id_examen, $mac);
				return FALSE;
			} else { return TRUE; }
		} else {		
			$this->insert_mac($mac, $matricula, $id_examen, $grupo);
			return FALSE;
		}
	} 
	//! ok sp
	private function actualiza_tiempo_restante($matricula, $id_examen, $mac){
		$query=$this->db->query("EXEC sp_actualiza_tiempo_restante '$matricula',$id_examen,'$mac'");
	}
	//! ok sp
	private function insert_mac($mac, $matricula, $id_examen, $grupo){
		$this->db->query("EXEC sp_insert_mac ?, ?, ?, ?", array($mac, $matricula, $id_examen, $grupo));

		return ($this->db->affected_rows() != 1) ? "false" : "true";
	}
	//! ok sp
	public function get_date_of_aplication($id_examen, $grupo){
		$query = $this->db->query("EXEC sp_get_date_of_aplication $id_examen, '$grupo'");
		if ($query->num_rows() == 0) { return NULL; } 
		else { return $query->result_array(); }
	} 
	//! ok sp
	public function validate_date($id_examen,$grupo) {
		$query = $this->db->query("EXEC validate_date_sysmater $id_examen, $grupo"); //OKEEEEY
		if ($query->num_rows() == 0) { return NULL; } 
		else { return $query->result_array(); } 
	} 
	//! ok sp
	public function validate_hour($id_examen, $matricula,$grupo){
		$query = $this->db->query("EXEC validate_hour_sysmater  $id_examen,$matricula,$grupo");
	
		if ($query->num_rows() == 0) { return NULL; } 
		else { return $query->result_array(); } 
	} 
	//! ok sp
    public function get_time($matricula, $id)	{
		$query = $this->db->query("Exec time_left_sysmater $matricula, $id;");
		$num = $query->num_rows();
		if ($num == 0) { return NULL; } 
		else { return $query->result_array(); }
	}
	//! ok sp
	public function get_data_test($id_examen){
		$query = $this->db->query("EXEC sp_get_data_test $id_examen");
		if ($query->num_rows() == 0) { return NULL; } 
		else { return $query->result_array(); } 
	} 
	//! ok sp
	public function save_resp($matricula, $id_examen, $id_reactivo_main, $id_reactivo_detail, $resp, $period, $time){
		$query = $this->db->query("Exec save_response_sysmater '" . $matricula . "','" . $id_examen . "','" . $id_reactivo_main . "','" . $id_reactivo_detail . "','" . $resp . "','" . $period . "','" . $time . "'");
		$num = $query->num_rows();
		if ($num == 0) { return NULL; } 
		else { return $query->result_array(); }
	} 
}
