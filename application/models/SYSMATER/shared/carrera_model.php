<?php

/**
 * Model used to fill carrera selection.
 *
 */
if (!defined('BASEPATH')) exit('No direct script accessallowed');

class Carrera_model extends CI_Model
{
	// call the model constructor.
	public function __construct()
	{
		parent::__construct();
	}
	//* PENDIENTE

	// get carrera data from tblCarreras
	public function lista_carreras()
	{
		$query = $this->db->query("SELECT DISTINCT chrClvCarrera, vchNomCarrera
    	FROM dbo.tblCarreras
    	ORDER BY chrClvCarrera");

		if ($query->num_rows() == 0) {
			return NULL;
		} else {
			return $query->result();
		}
	}
	public function lista_carreras_docente($docente, $periodo)
	{
		$query = $this->db->query("SELECT DISTINCT CA.chrClvCarrera, CA.vchNomCarrera FROM            dbo.tbl_carga_docentes AS C INNER JOIN         dbo.tblCarreras AS CA ON C.chrClvCarrera = CA.chrClvCarrera WHERE        (C.vchClvTrabajador = '$docente') AND (C.vchPeriodo = '$periodo')");

		if ($query->num_rows() == 0) {
			return NULL;
		} else {
			return $query;
		} // end if-else
	} // end function

	public function get_data_carrera($id_carrera)
	{
		$query = $this->db->query("SELECT chrClvCarrera, vchNomCarrera FROM dbo.tblCarreras WHERE (chrClvCarrera = '$id_carrera')");
		if ($query->num_rows() == 0) {
			return NULL;
		} else {
			return $query;
		} // end if-else
	}
}// end class
