<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	//? TERMINADO
	//! ok sp yazir
	public function Systemas_activos(){
		$query = $this->db->query("EXEC sp_sistemas_activos");

		if ($query->num_rows() == 0) { return null; } 
		else { return $query; } 
	}
	//! ok sp yazir
	public function Systemas_activos_Usuarios($id_systema){
		$query = $this->db->query("EXEC sp_sistemas_activos_usuarios '$id_systema'");

		if ($query->num_rows() == 0) return null;
		else return $query;
	}
	//! ok sp
	public function login_alumno($usr, $pdw){
		$sql = "EXEC spLoginAlumno '$usr','$pdw'";
		$query = $this->db->query($sql);		

		if ($query->num_rows() == 0)
			return null;
		else
			return $query->row();  
	} 
	//! ok sp
	public function login_docente($usr, $pdw){
		$sql = "EXEC sp_login_docente '$usr','$pdw'";
		$query = $this->db->query($sql);
		
		if ($query->num_rows() == 0)
			return null;
		else
			return $query->row();
	} 
	//! ok sp
	public function login_revisor($usr, $pdw){
		$sql = "EXEC sp_login_revisor '$usr','$pdw'";
		$query = $this->db->query($sql);

		if ($query->num_rows() == 0)
			return null;
		else
			return $query->row();
	} 
	//! ok sp
	public function login_director($usr, $pdw){
		$sql = "EXEC sp_login_director '$usr','$pdw'";
		$query = $this->db->query($sql);
		
		if ($query->num_rows() == 0)
			return null;
		else
			return $query->row();
	} 
	//! ok sp
	public function login_admin($usr, $pdw){
		$sql = "EXEC sp_login_admin '$usr','$pdw'";
		$query = $this->db->query($sql);

		if ($query->num_rows() == 0) 
			return null;
		else
			return $query->row();
	} 
	//! ok sp
	public function update_alumno($usr, $pdw){
		$sql = "EXEC spCambioContraseniaAlumno '$usr','$pdw'";
		$query = $this->db->query($sql);		
	} 
	//! ok sp
	public function update_admin($usr, $pdw)
	{
		$sql = "EXEC spCambioContraseniaAdmin '$usr','$pdw'";
		$query = $this->db->query($sql);
	} 
	//! ok sp
	public function update_director($usr, $pdw)	{
		$sql = "EXEC spCambioContraseniaTrabajador '$usr','$pdw'";
		$query = $this->db->query($sql);
	} 
	//! ok sp
	public function update_docente($usr, $pdw){
		$sql = "EXEC spCambioContraseniaDocente '$usr','$pdw'";
		$query = $this->db->query($sql);
	} 
	//! ok sp
	public function update_revisor($usr, $pdw){
		$sql = "EXEC spCambioContraseniaTrabajador '$usr','$pdw'";
		$query = $this->db->query($sql); 
	} 
}
