<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reactivo_model extends CI_Model {
// call the model constructor.
	public function __construct()
	{
		parent::__construct();
	}
	//? ok
	public function lista_reactivos($id_docente) {
		$this->borrar_reactivos_inconpletos();
		$query = $this->db->query("EXEC sp_lista_reactivos '$id_docente'");
		
		if ($query->num_rows() == 0) { return NULL; }
		else { return $query; }
	}
	
	public function reactivos_carrera($id_carrera){
		$this->borrar_reactivos_inconpletos();
		$query=$this->db->query("EXEC sp_reactivos_carrera '$id_carrera'");

		if ($query->num_rows() == 0) { return NULL; }
		else { return $query; }
	}
	
	public function borrar_reactivos_inconpletos(){
        $this->db->query("EXEC sp_borrar_reactivos_inconpletos");
	}
	
}
