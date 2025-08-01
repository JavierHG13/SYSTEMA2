<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Carga_alumnos_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    //? TERMINADO
    //! ok sp
    public function insertarAlumno($vchMatricula, $vchAPaterno, $vchAMaterno, $vchNombre, $vchContrasenia, $intActivo, $chrClvCarrera, $chrClvEspecialidad, $vchClvCuatri, $chvGrupo, $vchPeriodo,$tipo){
        $sql = "EXEC sp_insertar_alumno_excel ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
        $params = array($vchMatricula,$vchAPaterno,$vchAMaterno,$vchNombre,$vchContrasenia,$intActivo,
                        $chrClvCarrera,$chrClvEspecialidad,$vchClvCuatri,$chvGrupo,$vchPeriodo,$tipo);

        $result = $this->db->query($sql, $params);

        if ($result) {
            echo "Inserción exitosa.";
        } else {
            $error = $this->db->error();
            echo "Error en la inserción: " . $error['message'];
        }

        var_dump($params);
    }

}
