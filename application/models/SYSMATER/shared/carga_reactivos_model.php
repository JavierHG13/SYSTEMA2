<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Carga_reactivos_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database(); // Asegúrate de cargar la base de datos
    }
    //? TERMINADO
    //! sp ok yazir
    public function insertarPreguntaTipo($txt_planeamiento, $chrClvCarrera, $vchClvCuatri, $vchClvMateria, $path_imagen, $vchClvTrabajador, $id_estado, $vchPeriodo){
        $query = $this->db->query("EXEC sp_insertarPreguntaTipo ?, ?, ?, ?, ?, ?, ?, ?", [
            $txt_planeamiento, $chrClvCarrera, $vchClvCuatri, $vchClvMateria, 
            $path_imagen, $vchClvTrabajador, $id_estado, $vchPeriodo
        ]);

        $id_generado =$this->db->query("EXEC sp_get_ultimo_id_reactivo_main")->row()->ultimo_id;

        return $id_generado;
    }
    //! ok sp yazir
    public function insertarReactivosDetallados($id_reactivos_main_sysmater, $int_horas, $int_minutos, $id_nivel, $txt_base, $nvch_opcionA, $nvch_argumentaA, $path_imagenA, $nvch_opcionB, $nvch_argumentaB, $path_imagenB, $nvch_opcionC, $nvch_argumentaC, $path_imagenC, $nvch_opcionD, $nvch_argumentaD, $path_imagenD, $chr_correcto, $vch_bibliografia, $path_imagen_base, $vchClvTrabajador, $id_estado){
        $id = $this->db->query("EXEC sp_get_ultimo_id_reactivo_detail");
        $row = $id->row();
        $next_id = $row ? $row->max_id + 1 : 1;

        $data = array(
            'id_reactivo_detail_sysmater' => $next_id,
            'id_reactivos_main_sysmater' => $id_reactivos_main_sysmater,
            'int_horas' => $int_horas,
            'int_minutos' => $int_minutos,
            'id_nivel' => $id_nivel,
            'txt_base' => $txt_base,
            'nvch_opcionA' => $nvch_opcionA,
            'nvch_argumentaA' => $nvch_argumentaA,
            'path_imagenA' => $path_imagenA,
            'nvch_opcionB' => $nvch_opcionB,
            'nvch_argumentaB' => $nvch_argumentaB,
            'path_imagenB' => $path_imagenB,
            'nvch_opcionC' => $nvch_opcionC,
            'nvch_argumentaC' => $nvch_argumentaC,
            'path_imagenC' => $path_imagenC,
            'nvch_opcionD' => $nvch_opcionD,
            'nvch_argumentaD' => $nvch_argumentaD,
            'path_imagenD' => $path_imagenD,
            'chr_correcto' => $chr_correcto,
            'vch_bibliografia' => $vch_bibliografia,
            'path_imagen_base' => $path_imagen_base,
            'vchClvTrabajador' => $vchClvTrabajador,
            'id_estado' => $id_estado
        );

        $insert = $this->db->query("EXEC sp_insertarReactivosDetallados ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?", $data);

        $id = $this->db->query("EXEC sp_get_ultimo_id_reactivo_detail")->row()->max_id;
        return $id;
    }
    //! SP OK ANGELO.O
    public function insertarReactivosExamen($id_examen, $id_reactivo) {
        $query = $this->db->query("EXEC sp_insertar_reactivo_examen ?, ?", array($id_examen, $id_reactivo));

        $result = $query->row();
        return $result->resultado == 1;
    }
    //! SP OK ANGELO.O
    public function obtener_reactivos_examen($id_examen){
        $query = $this->db->query(" EXEC sp_obtener_reactivos_examen ?", array($id_examen));

        return $query->result();
    }
    //! SP OK ANGELO.O
    public function actualizar_reactivo_detallado($id_reactivo_detail, $data){
        $sql = ('EXEC sp_actualizar_reactivo_detallado ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ,?, ?, ?, ?');
        
		$query = $this->db->query($sql, array(
			$id_reactivo_detail, $data['int_horas'], $data['int_minutos'], $data['id_nivel'],$data['txt_base'],$data['nvch_opcionA'],$data['nvch_argumentaA'],
            $data['path_imagenA'],$data['nvch_opcionB'],$data['nvch_argumentaB'],$data['path_imagenB'],$data['nvch_opcionC'],$data['nvch_argumentaC'],
            $data['path_imagenC'],$data['nvch_opcionD'],$data['nvch_argumentaD'],$data['path_imagenD'],$data['chr_correcto'],$data['vch_bibliografia'],
            $data['path_imagen_base'],$data['vchClvTrabajador'],$data['id_estado']
		));
		return true;
    }
    //! ok sp yazir
    public function get_reactivos_por_materia_json($clvClvMateria,$id_examen){
        $query = $this->db->query("EXEC sp_get_reactivos_por_materia_json '$clvClvMateria', $id_examen");
        
        if ($query->num_rows() == 0) { return NULL; } 
        else { return $query; }
    }
}
