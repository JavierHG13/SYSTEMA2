<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Carga_materias_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    //* PENDIENTE
    public function insertarMaterias($vchClvMateria, $vchNomMateria, $idPeriodo, $vchCuatrimestre)
    {
        if($vchCuatrimestre<10){
            $Cuatrimestre = '0' . $vchCuatrimestre;
        }

        $data = array(
            'vchClvMateria' => $vchClvMateria,
            'vchNomMateria' => $vchNomMateria,
            'idPeriodo' => $idPeriodo,
            'vchCuatrimestre' => $Cuatrimestre
        );

        $this->db->insert('tbl_materias', $data);
    }


}
