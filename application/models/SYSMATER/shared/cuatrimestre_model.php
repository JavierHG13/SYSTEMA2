<?php
/**
 * Model used to fill cuatri selection.
 * 22/06/2016
 */
if ( ! defined('BASEPATH')) exit('No direct script accessallowed');

class Cuatrimestre_model extends CI_Model
{
	// call the model constructor.
	public function __construct()
	{
		parent::__construct();
	}

	// get carrera data from tblCarreras
	public function lista_cuatrimestres()
	{
		$query = $this->db->query("SELECT vchClvCuatri, vchNomCuatri FROM dbo.tblCuatrimestres");
		if ($query->num_rows() == 0)
		{
			return NULL;
		}
		else
		{
			return $query;
		}// end if-else
	}// end function
	public function lista_cuatrimestres_docente($carrera,$docente,$periodo){
		$query=$this->db->query("SELECT DISTINCT CU.vchClvCuatri, CU.vchNomCuatri FROM dbo.tbl_carga_docentes AS C INNER JOIN dbo.tblCuatrimestres AS CU ON C.vchClvCuatri = CU.vchClvCuatri WHERE (C.vchClvTrabajador = '$docente') AND (C.vchPeriodo = '$periodo') AND (C.chrClvCarrera = '$carrera')");
		if ($query->num_rows() == 0)
		{
			return NULL;
		}
		else
		{
			return $query;
		}// end if-else
	}
	public function get_data_cuatrimestre($cuatrimestre)
	{
		$query = $this->db->query("SELECT vchClvCuatri, vchNomCuatri FROM dbo.tblCuatrimestres WHERE vchClvCuatri='$cuatrimestre'");
		if ($query->num_rows() == 0)
		{
			return NULL;
		}
		else
		{
			return $query;
		}// end if-else
	}// end function
}// end class
