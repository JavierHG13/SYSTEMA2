<?php
defined('BASEPATH') or exit('No direct script access allowed');

class instrumento_model extends CI_Model
{
    // call the model constructor.
    public function __construct()
    {
        parent::__construct();
    }

    public function insertarInstrumento(
        $nombre,
        $parcial,
        $valor_total,
        $vchClvMateria,
        $idPeriodo,
        $id_tipo_instrumento,
        $vchClvTrabajador,
        $vchPeriodo
    ) {

        $sql = "INSERT INTO tbl_instrumento 
        (nombre, parcial, valor_total, vchClvMateria, idPeriodo, id_tipo_instrumento, vchClvTrabajador, vchPeriodo)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $this->db->query($sql, array(
            $nombre,
            $parcial,
            $valor_total,
            $vchClvMateria,
            $idPeriodo,
            $id_tipo_instrumento,
            $vchClvTrabajador,
            $vchPeriodo
        ));
        // Imprimir la consulta final ejecutada
        log_message('debug', 'Última consulta ejecutada: ' . $this->db->last_query());


        return $this->db->insert_id();
    }



    public function insertarCriterios($criterios)
    {
        return $this->db->insert_batch('tbl_criterios', $criterios); // inserción masiva
    }


    //Actualizacion y demas funciones para los criterios
    public function obtener_criterio($id_criterio)
    {
        return $this->db->get_where('tbl_criterios', ['id_criterio' => $id_criterio])->row();
    }

    public function sumar_valores_criterios($id_instrumento)
    {
        $this->db->select_sum('valor_maximo');
        $this->db->where('id_instrumento', $id_instrumento);
        $query = $this->db->get('tbl_criterios');
        return floatval($query->row()->valor_maximo);
    }

    public function existeInstrumento($nombre, $vchClvMateria, $idPeriodo)
    {
        $this->db->where('nombre', $nombre);
        $this->db->where('vchClvMateria', $vchClvMateria);
        $this->db->where('idPeriodo', $idPeriodo);
        $query = $this->db->get('tbl_instrumento');

        return $query->num_rows() > 0;
    }



    public function lista_instrumentos_docente($vchClvTrabajador,  $vchPeriodo)
    {
        $sql = "EXEC sp_lista_instrumentos_docente ?, ?";
        $query = $this->db->query($sql, [$vchClvTrabajador, $vchPeriodo]);
        return $query->num_rows() > 0 ? $query : NULL;
    }

    public function lista_tipos_de_instrumentos()
    {
        $sql = "EXEC sp_lista_tipos_de_instrumentos";
        $query = $this->db->query($sql);
        return $query->num_rows() > 0 ? $query : NULL;
    }



    public function obtener_cuatrimestres_docente($vchClvTrabajador, $periodo)
    {
        $sql = "EXEC sp_obtener_cuatrimestres_docente ?, ?";
        $query = $this->db->query($sql, [$vchClvTrabajador, $periodo]);
        return $query->num_rows() > 0 ? $query : NULL;
    }




    public function lista_de_criterios($id_instrumento)
    {
        $sql = "SELECT * FROM tbl_criterios WHERE id_instrumento =  ?";

        $query = $this->db->query($sql, array($id_instrumento));

        return $query->num_rows() > 0 ? $query : NULL;
    }


    public function detalles_instrumento($id_instrumento)
    {
        $sql = "SELECT id_instrumento, nombre, parcial, vchNomMateria, vchPeriodo 
            FROM tbl_instrumento 
            INNER JOIN tbl_materias 
            ON tbl_instrumento.vchClvMateria = tbl_materias.vchClvMateria
            WHERE id_instrumento = ?";

        $query = $this->db->query($sql, array($id_instrumento));

        return $query->num_rows() > 0 ? $query->row() : NULL;
    }


    //** funciones para mostrar instrumento en asignar actividad */
    public function get_instrumento_detalles($id_instrumento)
    {
        $this->db->select('i.id_instrumento, i.nombre, i.parcial, i.valor_total, t.nombre_tipo');
        $this->db->from('tbl_instrumento i');
        $this->db->join('tbl_tipo_instrumento t', 'i.id_tipo_instrumento = t.id_tipo_instrumento', 'left');
        $this->db->where('i.id_instrumento', $id_instrumento);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_criterios_by_instrumento($id_instrumento)
    {
        $this->db->select('id_criterio, nombre, descripcion, valor_maximo');
        $this->db->from('tbl_criterios');
        $this->db->where('id_instrumento', $id_instrumento);
        $query = $this->db->get();
        return $query->result_array();
    }



    /**
     * tualizar todos los criterios
     */
    public function actualizar_todos_criterios($criterios, $id_instrumento)
    {
        $this->db->trans_start();
        $tabla = 'tbl_criterios';

        try {
            foreach ($criterios as $criterio) {
                $datos = [
                    'nombre' => trim($criterio['nombre']),
                    'descripcion' => trim($criterio['descripcion']),
                    'valor_maximo' => floatval($criterio['valor_maximo']),
                    'id_instrumento' => $id_instrumento
                ];

                if (!empty($criterio['id'])) {
                    // 🔄 Si existe ID, actualiza
                    $this->db->where('id_criterio', $criterio['id']);
                    $this->db->update($tabla, $datos);
                } else {
                    // ➕ Si no tiene ID, es un nuevo criterio → insertar
                    $this->db->insert($tabla, $datos);
                }
            }

            $this->db->trans_complete();
            return $this->db->trans_status();
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return false;
        }
    }

    public function validar_suma_total($criterios)
    {
        $suma = 0;
        foreach ($criterios as $criterio) {
            $suma += floatval($criterio['valor_maximo']);
        }
        return abs($suma - 10.0) < 0.01; // Maneja decimales
    }


    public function verificar_nombres_duplicados($criterios)
    {
        $nombres = array();
        foreach ($criterios as $criterio) {
            $nombre = strtolower(trim($criterio['nombre']));
            if (in_array($nombre, $nombres)) {
                return true; // Hay duplicados
            }
            $nombres[] = $nombre;
        }
        return false; // No hay duplicados
    }
}
