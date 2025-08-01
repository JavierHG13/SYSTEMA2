<?php
class Activacion_systema_model extends CI_Model
{
	//? TERMINADO
	//! ok sp yazir
	public function lista_systemas_sysmater(){
		$query=$this->db->query('EXEC sp_lista_systemas_sysmater');
		return $query->result();
	}
	//! ok sp
	public function update_systema_status($id_systema, $new_status){
		$this->db->query('EXEC sp_update_systema_status ?, ?', 
			array($id_systema,'Activo' => $new_status));
	}
}
