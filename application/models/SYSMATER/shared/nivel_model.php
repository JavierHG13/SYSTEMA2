<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nivel_model extends CI_Model {
	// call the model constructor.
	public function __construct()
	{
		parent::__construct();
	}
	//? TERMINADO
	//! ok sp yazir
	public function lista_niveles(){
		$query = $this->db->query("EXEC sp_lista_niveles");
		if ($query->num_rows() == 0){ return NULL; }
		else { return $query; }
	}
	//! ok sp yazir
	public function insertar_nivel($data) {
		$params = array($data['vch_nivel']);
		$this->db->query('EXEC sp_insertar_nivel_taxonomico ?', $params);
	}
	//! ok sp yazir
	public function elimina_nivel($id) {
		$params = array($id); 
		$this->db->query('EXEC sp_eliminar_nivel_taxonomico ?', $params);
	}
	//! ok sp yazir
	public function actualiza_nivel($data) {
		$params = array(
			$data['id_nivel'],
			$data['vch_nivel']
		);
		$this->db->query("EXEC sp_actualiza_nivel_taxonomico ?, ?", $params);
	}
	//! ok sp yazir
	public function get_nivel($id){
		$query = $this->db->query("EXEC sp_get_nivel_taxonomico ?", array($id));
		if ($query->num_rows() == 0) { return NULL; }
		else { return $query; }
	}
}
