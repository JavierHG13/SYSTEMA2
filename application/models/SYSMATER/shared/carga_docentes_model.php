<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Carga_docentes_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database(); // Asegúrate de cargar la base de datos
    }
    //* PENDIENTE
    public function insertarDocentes2($vchClvTrabajador, $vchAPaterno, $vchAMaterno, $vchNombre)
    {
        $data = array(
            'vchClvTrabajador' => $vchClvTrabajador,
            'vchAPaterno' => $vchAPaterno,
            'vchAMaterno' => $vchAMaterno,
            'vchNombre' => $vchNombre,
            'vchContrasenia' => $vchClvTrabajador,
            'intActivo' => 1
        );

        $this->db->insert('tbl_docentes', $data);
    }
}
