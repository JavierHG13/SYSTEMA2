<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Examen_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }
    //? TERMINADO
    //! ok sp
    public function lista_examenes_docente($vchClvTrabajador){
		$query = $this->db->query("EXEC sp_lista_examenes_docente '$vchClvTrabajador';");
        
		if ($query->num_rows() == 0) { return NULL; } 
        else { return $query; }
	}
    //! ok sp
    public function lista_examenes_todos(){
        $query = $this->db->query("EXEC sp_lista_examenes_todos");

        if ($query->num_rows() == 0){ return NULL; }
        else { return $query;}
    }
    //! ok sp
    public function lista_examenes_programados_todos(){
        $query = $this->db->query("EXEC sp_lista_examenes_programados_todos");
        if ($query->num_rows() == 0) { return NULL; }
        else { return $query; }
    }

    //! ok sp
    public function lista_examenes_programados_docente($id){
        $query = $this->db->query("EXEC sp_lista_examenes_programados_docente '$id'");

        if ($query->num_rows() == 0){ return NULL; }
        else{ return $query; }
    }
    //! ok sp
    public function lista_examenes_sin_programar_docente($id){
        $query = $this->db->query("EXEC sp_lista_examenes_sin_programar_docente '$id'");

        if ($query->num_rows() == 0){ return NULL; }
        else{ return $query; }
    }
    //! ok sp
    public function lista_examenes_sin_programar_todos(){        
        $query = $this->db->query("EXEC sp_lista_examenes_sin_programar_todos");

        if ($query->num_rows() == 0){ return NULL; }
        else { return $query; }
    }
    //! ok sp
    public function lista_examenes_materia($materia , $periodo, $vchClvTrabajador){
        $query = $this->db->query("EXEC sp_lista_examenes_materia '$vchClvTrabajador', '$materia', '$periodo'");

        if ($query->num_rows() == 0){ return NULL; }
        else { return $query; }        
    }
    //! ok sp
    public function lista_examenes_materia_periodo($periodo){
        $query = $this->db->query("EXEC sp_lista_examenes_materia_periodo '$periodo'");

        if ($query->num_rows() == 0) { return NULL; }
        else { return $query; }
        
    }
    //! ok sp
    public function lista_examenes_materia_todos($materia){
        $query = $this->db->query("EXEC sp_lista_examenes_materia_todos '$materia'");

        if ($query->num_rows() == 0){ return NULL; }
        else { return $query; }        
    }      
    //! ok sp
    public function obtener_id_grupo($nombre_grupo) {       
        $query = $this->db->query("EXEC sp_obtener_id_grupo '$nombre_grupo'");
        return ($query->num_rows() > 0) ? $query->row()->id_grupo : null;
    }
    //! ok sp
    public function get_programacion_examen($id_examen, $id_grupo){
        $id_grupo = $this->obtener_id_grupo($id_grupo);
		$query=$this->db->query("EXEC sp_get_programacion_examen $id_grupo,$id_examen");
		if ($query->num_rows()==0) { return NULL; }
        else { return $query; }
	}
    //! ok sp
    public function get_examen($id){
		$query = $this->db->query("EXEC sp_get_examen $id");
		if ($query->num_rows() == 0) { return NULL; } 
        else { return $query; } 
	}    
    //! ok sp
    public function get_examen_ajax($id){
        $query = $this->db->query("EXEC sp_get_examen_ajax '$id'");

        if ($query->num_rows() == 0) { return NULL; } 
        else { return $query->result(); }
    }
    //! ok sp
    public function get_cantidad_reactivos($id_examen) {
        $query = $this->db->query("EXEC sp_get_cantidad_reactivos $id_examen");
        if ($query->num_rows() == 0) { return NULL; } 
        else { return $query->row(); }
    }
    //! ok sp
    public function get_grupos_por_examen($id_examen){
        $sql = "EXEC sp_get_grupos_por_examen ?";        
        $query = $this->db->query($sql, array($id_examen));
        
        if (!$query || $query->num_rows() == 0) {
            log_message('error', 'No se encontraron grupos para el examen ID: '.$id_examen);
            return false;
        }        
        return $query->row();
    }
    //! ok sp
    public function get_grupos_por_materia($clave_materia,$id_docente){
        $query = $this->db->query("EXEC sp_get_grupos_por_materia '$clave_materia','$id_docente'");        
        return $query->num_rows() > 0 ? $query : NULL;
    }
    //! ok sp
    public function lista_tipos_examen(){
        $query = $this->db->query("EXEC sp_lista_tipos_examen");

        if ($query->num_rows() == 0){ return NULL; }
        else { return $query; }        
    }
    //! ok sp
    public function get_detalles_examen($id_examen) {
        $query = $this->db->query("EXEC sp_get_detalles_examen '$id_examen'");

        if ($query->num_rows() == 0) { return NULL; }
        else { return $query; }        
    }
    //! ok sp
    public function insertar_examen($data, $gruposSeleccionados){
        $sql = "EXEC sp_insertar_examen ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
        $params = [$data['vchClvMateria'],$data['nvch_Titulo'],$data['int_reactivos_requeridos'],$data['nvch_clave'],$data['vchCuatrimestre'],
                $data['periodo'],$data['parcial'],$data['tipo_Examen'],$data['vchClvTrabajador'],$data['id_examen_temp']];
        $result = $this->db->query($sql, $params);
        $idExamen = $this->db->query("EXEC sp_obtener_ultimo_id_examen")->row()->ultimo_id;

        if (!empty($gruposSeleccionados) && $idExamen) {
            $grupoMapping = ['A' => 1, 'B' => 2, 'C' => 3, 'D' => 4];
            foreach ($gruposSeleccionados as $letraGrupo) {
                $idGrupo = $grupoMapping[$letraGrupo] ?? 0;
                if ($idGrupo > 0) { $this->db->query('EXEC sp_insertar_examen_grupo ?, ?',[$idExamen, $idGrupo]); }
            }
        }
        return $idExamen;
    }
    //! ok sp
    public function existe_examen($data){
        $qwery=$this->db->query("EXEC sp_existe_examen '$data[vchClvMateria]', '$data[nvch_Titulo]',$data[periodo]");
        if($qwery->num_rows()>0){ return true; }
        else{ return false; }
    }
    //! ok sp
    public function eliminar_exame($id_examen){
        $validate1 = $this->db->query("EXEC sp_validate1EliminaExamen $id_examen");
        $validate2 = $this->db->query("EXEC sp_validate2EliminaExamen $id_examen");

        if ($validate2->num_rows() > 0) { return true; } 
        else if($validate1->num_rows() > 0){ return true; } 
        else {
            $this->db->query("EXEC sp_EliminaExamen $id_examen");
            return false;
        }
    }
    //! ok sp
    public function elimina_programacion_examen($id_examen, $id_grupo){
        $id_grupo = $this->obtener_id_grupo($id_grupo);        
        $query = $this->db->query("EXEC sp_elimina_programacion_examen $id_grupo, $id_examen");        
        return $this->db->affected_rows() > 0;
    }
    public function lista_Periodos_Examenes(){
        $query = $this->db->query("SELECT DISTINCT periodo FROM tbl_examen_main ORDER BY periodo;");
        
        if ($query->num_rows() == 0) { return NULL; } 
        else { return $query;
        }
    }
    //! ok sp
    public function actualiza_examen($data, $id, $gruposSeleccionados){
        $query=$this->db->query("EXEC sp_actualiza_examen_main ?, ?, ?, ?, ?, ?", 
            array($data['vchClvMateria'], $data['nvch_Titulo'], $data['int_reactivos_requeridos'], 
                $data['nvch_clave'], $data['tipo_Examen'], $id));
        
        if(!empty($gruposSeleccionados)) {
            $this->db->query("EXEC sp_elimina_grupos_examen ?", array($id));
            
            $grupoMapping = ['A' => 1, 'B' => 2, 'C' => 3, 'D' => 4];
            
            foreach($gruposSeleccionados as $letraGrupo) {
                $idGrupo = $grupoMapping[$letraGrupo] ?? 0;
                
                if ($idGrupo > 0) {
                    $id_examen = (int)$id;
                    $id_grupo = (int)$idGrupo;

                    $sql = "EXEC sp_guarda_examen_grupo ?, ?";
                    $this->db->query($sql, array($id_examen, $id_grupo));
                }
            }
        }
    }
    //! ok sp
    public function examenes_sin_programar_editar($clvTrabajador,$id_examen){
		$query = $this->db->query("EXEC sp_examenes_sin_programar_editar '$clvTrabajador', $id_examen");
        
		if ($query->num_rows() == 0) { return NULL; }
		else { return $query; }
	}
    //! ok sp
    public function actualiza_programa_examen($data) {
        $sql = "EXEC sp_actualiza_programacion_examen ?, ?, ?, ?, ?, ?, ?";

        return $this->db->query($sql, array($data['fch_inicia'], $data['fch_termina'],$data['tm_hora_inicio'], 
            $data['tm_hora_final'],$data['tm_duracion'],(int) $data['id_examen'],(int) $data['id_grupo']));
    }
    //! ok sp
    public function guardar_programa_examen($data) {
        $sql = "EXEC sp_guarda_programa_examen ?, ?, ?, ?, ?, ?, ?";
        
        try {
            $result = $this->db->query($sql, array($data['id_examen'], $data['fch_inicia'],$data['fch_termina'],   
                $data['tm_hora_inicio'],$data['tm_hora_final'],$data['tm_duracion'],$data['id_grupo']));
            return $result;        
        } catch (Exception $e) {
            log_message('error', 'Error al insertar programa de examen: ' . $e->getMessage());
            return false;
        }
    }
    //! ok sp
    public function existe_programa_examen($data) {
        $sql = "EXEC sp_existe_programa_examen ?,?,?,?,?";
    
        $query = $this->db->query($sql, array(
            (INT)$data['id_examen'], $data['fch_inicia'], $data['fch_termina'],
            $data['tm_hora_inicio'], $data['tm_hora_final']
        ));
    
        if ($query) { 
            if ($query->row()->count > 0) { return true; }
            else { return false; }
        } else { return true; }
    }  
    //! ok sp yazir
    public function existe_conflicto_horario($data)    {
        $query = $this->db->query("EXEC  sp_existe_conflicto_horario
            '$data[materia]','$data[periodo]', '$data[iddocente]', '$data[grupo]', '$data[hora_inicio]', '$data[hora_final]'");
        
        if ($query->row()->resultado == 'NO_PASA') {
            return true;
        } else {
            return false;
        }
    }

    //! ok sp
    public function seleccionar_reactivo($data) {
        $query = $this->db->query("EXEC sp_get_max_id_examen_reactivo");
        $row = $query->row();
        $next_id = $row ? $row->max_id + 1 : 1;
    
        $data['id_examen_reactivo'] = $next_id;
    
        $this->db->query("EXEC sp_seleccionar_reactivo ?, ?, ?", 
            array($data['id_examen_reactivo'], $data['id_examen'], $data['id_reactivo_main'])
        );
    }
    //! ok sp
	public function deseleccionar_reactivos($data){
		$this->db->query("EXEC sp_deseleccionar_reactivos ?, ?", array($data['id_examen'],$data['id_reactivo_main']));
	}
    //! ok sp
    public function lista_examenes_materia_director($materia, $periodo, $vchClvTrabajador, $grupo){
        $sql = "EXEC sp_lista_examenes_materia_director ?, ?, ?, ?";

        $query = $this->db->query($sql, array($materia, $grupo, $periodo, $vchClvTrabajador));

        return $query->num_rows() > 0 ? $query : null;
    }
    //! ok sp
    public function lista_actividades_materia($clave_docente, $id_grupo, $periodo, $clave_materia){
        return $query = $this->db->query("EXEC sp_lista_actividades_materia ?, ?, ?, ?", array($clave_docente, $id_grupo, $periodo, $clave_materia));
    }
    //! ok sp yazir
	public function verifica_examen_asignado($docente,$id_examen){
		$query = $this->db->query("EXEC sp_verifica_examen_asignado ?, ?", array($docente,$id_examen));

		if ($query->num_rows() == 0) {return true;} else {return false;}
    }
}