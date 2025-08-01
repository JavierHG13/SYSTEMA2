<?php

if ( ! defined('BASEPATH')) exit('No direct script accessallowed');

class Valid_test_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	//? TERMINADO
	//! ok sp yazir
	public function get_valid_test(){
		$cuat = $this->session->clvCuatri;
		$grup = $this->session->Grupo;
		$periodo = $this->determinarPeriodo();
		
		$query = $this->db->query("EXEC sp_get_examenes_alumno ?, ?, ?", array($cuat,$periodo, $grup));
	
		if ($query->num_rows() == 0) { return NULL; }
		else { return $query->result_array(); }
	}
	//! ok sp yazir
	public function get_test_selec($id_examen){		
		$query = $this->db->query("EXEC sp_get_examen_seleccionado ?", array($id_examen));
	
		if ($query->num_rows() == 0) { return NULL; }
		else { return $query->result_array(); }
	}
	private function determinarPeriodo() {
		$fechaActual = new DateTime();
		$mes = (int) $fechaActual->format('m');
		$año = $fechaActual->format('Y');
		$periodo = 0;

		if ($mes >= 1 && $mes <= 4) {
			$periodo = 1;
		} elseif ($mes >= 5 && $mes <= 8) {
			$periodo = 2;
		} elseif ($mes >= 9 && $mes <= 12) {
			$periodo = 3;
		} else {
			$periodo = 4;
		}

		return $año . $periodo;
	}
}
