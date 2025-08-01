<?php
/**
 * Model used to fill especialidad selection.
 * 14/06/2016
 */
if ( ! defined('BASEPATH')) exit('No direct script accessallowed');

class Especialidad_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	//? TERMINADO
	//! ok sp yazir
	public function get_especialidad($id_carrera) {
		$query = $this->db->query("EXEC sp_get_especialidad ?", array($id_carrera));

		if ($query->num_rows() == 0) { return null; }
		else { return $query->result_array(); }
	}
}
