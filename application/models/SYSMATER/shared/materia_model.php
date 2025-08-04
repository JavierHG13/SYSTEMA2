<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Materia_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	//? TERMINADO
	//! SP OK ANGELO.O
	public function lista_materias_examen_todos()
	{
		$query = $this->db->query("EXEC sp_lista_materias_examen_todos");

		if ($query->num_rows() == 0) {
			return NULL;
		} else {
			return $query;
		}
	}
	//! SP OK ANGELO.O
	public function existe_materia_admin($data)
	{
		$sql = ('EXEC sp_existe_materia ?, ? ');

		$query = $this->db->query($sql, array(
			$data['vchNomMateria'],
			$data['vchClvMateria']
		));

		if ($query->num_rows() >= 1) {
			return true;
		} else {
			return false;
		}
	}
	//! SP OK ANGELO.O
	public function inserta_materia($data)
	{
		$sql = ('EXEC sp_inserta_materia ?, ?, ?, ?');

		$query = $this->db->query($sql, array(
			$data['vchClvMateria'],
			$data['vchNomMateria'],
			$data['idPeriodo'],
			$data['vchCuatrimestre']
		));

		return true;
	}
	//! SP OK ANGELO.O
	public function get_materia($id)
	{
		$query = $this->db->query("EXEC sp_get_materia @vchClvMateria = '$id'");

		if ($query->num_rows() > 0) {
			return $query;
		} else {
			return false;
		}
	}
	//! SP OK ANGELO.O
	public function verifica_materia_eliminar($id)
	{
		$query = $this->db->query("EXEC sp_verifica_materia_eliminar '$id'");

		if ($query->num_rows() > 0) {
			return $query;
		} else {
			return false;
		}
	}
	//! SP OK ANGELO.O
	public function elimina_materia($id)
	{
		$query = $this->db->query("EXEC sp_elimina_materia @vchClvMateria = ?", array($id));

		$count = $this->db->count_all_results();

		return $count > 0;
	}
	//! SP OK ANGELO.O
	public function lista_materias_todos()
	{
		$query = $this->db->query("EXEC sp_lista_materias_todos");

		if ($query->num_rows() == 0) {
			return NULL;
		} else {
			return $query;
		}
	}
	//! SP OK ANGELO.O
	public function lista_materias_periodo($periodo)
	{
		$query = $this->db->query("EXEC sp_lista_materias_periodo ?", array($periodo));

		if ($query->num_rows() == 0) {
			return NULL;
		} else {
			return $query;
		}
	}
	//! SP OK ANGELO.O
	public function lista_materias_periodo_cuatrimestre($periodo, $Cuatrimestre)
	{
		$query = $this->db->query("EXEC sp_lista_materias_periodo_cuatrimestre ?, ?", array($periodo, $Cuatrimestre));

		if ($query->num_rows() == 0) {
			return NULL;
		} else {
			return $query;
		}
	}
	//!SP OK ANGELO.O
	public function insertar_docente_materia($data)
	{
		if (!is_numeric($data['vchGrupo'])) {
			return false;
		}

		$sql = "EXEC sp_insertar_docente_materia ?, ?, ?, ?, ?";

		$query = $this->db->query($sql, array(
			$data['vchClvTrabajador'],
			$data['vchClvMateria'],
			$data['Periodo'],
			$data['vchCuatrimestre'],
			$data['vchGrupo']
		));

		return true;
	}
	//! SP OK ANGELO.O
	public function get_cuatrimestres_por_periodo($periodo)
	{
		$query = $this->db->query("EXEC sp_get_cuatrimestres_por_periodo ?", array($periodo));

		if ($query->num_rows() == 0) {
			return NULL;
		} else {
			return $query->result();
		}
	}
	//! SP OK ANGELO.O
	public function verificar_grupo_asignado($materia, $periodo, $cuatrimestre, $id_grupo)
	{
		$query = $this->db->query(" EXEC sp_verificar_grupo_asignado 
			@Materia = '$materia', 
			@Periodo = '$periodo', 
			@Cuatrimestre = '$cuatrimestre', 
			@Grupo = '$id_grupo'");
	}
	//! ok sp yazir
	public function verifica_grupo_asignado($materia, $docente, $grupo, $periodo)
	{
		$query = $this->db->query("EXEC sp_verifica_grupo_asignado ?, ?, ?, ?", array($materia, $docente, $grupo, $periodo));

		if ($query->num_rows() == 0) {
			return true;
		} else {
			return false;
		}
	}
	public function mostrar_materias_docente($vchClvTrabajador, $periodo)
	{

		$query = $this->db->query("EXEC sp_MostrarMateriasDocente ?, ?", [$vchClvTrabajador, $periodo]);

		return $query->num_rows() > 0 ? $query : NULL;
	}
	public function mostrar_grupos_docente_por_materia($vchClvTrabajador, $vchClvMateria, $periodo)
	{

		$query = $this->db->query("EXEC sp_MostrarGruposDocentePorMateria ?", [$vchClvMateria]);

		return $query->num_rows() > 0 ? $query : NULL;
	}

	public function obtener_componentes($vchClvTrabajador, $vchClvMateria, $vchPeriodo)
	{
		return $this->db
			->from('tbl_valor_componentes_evaluacion')
			->where('vchClvMateria', $vchClvMateria)
			->where('vchClvTrabajador', $vchClvTrabajador)
			->where('vchPeriodo', $vchPeriodo)
			->order_by('id_valor_componente', 'ASC')
			->get()
			->result();
	}

	public function existe_materia($clave)
	{
		return $this->db->where('vchClvMateria', $clave)
			->get('tbl_materias')
			->num_rows() > 0;
	}


	public function datos_materia($vchClvMateria)
	{
		$query = $this->db->query("EXEC sp_DatosMateria ?", [$vchClvMateria]);

		return $query->num_rows() > 0 ? $query : NULL;
	}

	public function get_grupos_por_materia($materia_id, $periodo, $docente)
	{
		try {
			$query = $this->db->query(
				"EXEC sp_get_grupos_materia ?, ?, ?",
				array($materia_id, $periodo, $docente)
			);

			if ($query && $query->num_rows() > 0) {
				return $query->result();
			} else {
				return NULL;
			}
		} catch (Exception $e) {
			log_message('error', 'Error en get_grupos_por_materia: ' . $e->getMessage());
			return NULL;
		}
	}




	// Función original modificada para mantener compatibilidad
	public function guardar_componente_db($datos)
	{
		return $this->db->insert('tbl_valor_componentes_evaluacion', $datos);
	}

	// Nueva función para guardar múltiples componentes en lote
	public function guardar_componentes_batch($componentes_array)
	{
		if (empty($componentes_array)) {
			return false;
		}

		// Usar insert_batch para mayor eficiencia
		return $this->db->insert_batch('tbl_valor_componentes_evaluacion', $componentes_array);
	}

	// Función para obtener el total actual de un parcial
	public function obtener_total_parcial($vchClvMateria, $idPeriodo, $parcial, $vchClvTrabajador)
	{
		$this->db->select('COALESCE(SUM(valor_componente), 0) as total');
		$this->db->where('vchClvMateria', $vchClvMateria);
		$this->db->where('idPeriodo', $idPeriodo);
		$this->db->where('parcial', $parcial);
		$this->db->where('vchClvTrabajador', $vchClvTrabajador);

		$query = $this->db->get('tbl_valor_componentes_evaluacion');
		$result = $query->row();

		return $result ? floatval($result->total) : 0;
	}

	// Función auxiliar para validar disponibilidad de puntos
	public function validar_disponibilidad_puntos($vchClvMateria, $idPeriodo, $parcial, $vchClvTrabajador, $puntos_nuevos)
	{
		$total_actual = $this->obtener_total_parcial($vchClvMateria, $idPeriodo, $parcial, $vchClvTrabajador);
		$disponible = 10 - $total_actual;

		return [
			'total_actual' => $total_actual,
			'disponible' => $disponible,
			'nuevo_total' => $total_actual + $puntos_nuevos,
			'es_valido' => ($total_actual + $puntos_nuevos) <= 10
		];
	}

	// Función para obtener componentes de un parcial específico
	/*public function obtener_componentes_parcial($vchClvMateria, $idPeriodo, $parcial, $vchClvTrabajador)
	{
		$this->db->where('vchClvMateria', $vchClvMateria);
		$this->db->where('idPeriodo', $idPeriodo);
		$this->db->where('parcial', $parcial);
		$this->db->where('vchClvTrabajador', $vchClvTrabajador);
		$this->db->order_by('id_valor_componente', 'ASC');

		$query = $this->db->get('tbl_valor_componentes_evaluacion');
		return $query->result();
	}*/

	public function obtener_componentes_parcial($vchClvMateria, $vchPeriodo, $parcial, $vchClvTrabajador)
	{
		$this->db->select('id_valor_componente, vchClvMateria, idPeriodo, parcial, vchClvTrabajador, vchPeriodo, componente, valor_componente');
		$this->db->from('tbl_valor_componentes_evaluacion');
		$this->db->where('vchClvMateria', $vchClvMateria);
		$this->db->where('vchPeriodo', $vchPeriodo);
		$this->db->where('parcial', $parcial);
		$this->db->where('vchClvTrabajador', $vchClvTrabajador);

		$query = $this->db->get();

		return $query->result();
	}


	// Obtener todos los IDs actuales
	public function obtener_ids_componentes($vchClvMateria, $vchPeriodo, $parcial, $vchClvTrabajador)
	{
		$this->db->select('id_valor_componente');
		$this->db->from('tbl_valor_componentes_evaluacion');
		$this->db->where([
			'vchClvMateria' => $vchClvMateria,
			'vchPeriodo'    => $vchPeriodo,
			'parcial'       => $parcial,
			'vchClvTrabajador' => $vchClvTrabajador
		]);
		$query = $this->db->get();
		return array_column($query->result_array(), 'id_valor_componente');
	}

	// Verificar si un componente está en uso
	public function componente_en_uso($id_valor_componente)
	{
		return $this->db->where('id_valor_componente', $id_valor_componente)
			->count_all_results('tbl_actividades') > 0;
	}

	// Eliminar componente por ID
	public function eliminar_componente($id_valor_componente)
	{
		return $this->db->where('id_valor_componente', $id_valor_componente)
			->delete('tbl_valor_componentes_evaluacion');
	}

	// Actualizar múltiples componentes
	public function actualizar_componentes_parcial($componentes_actualizar)
	{
		return $this->db->update_batch('tbl_valor_componentes_evaluacion', $componentes_actualizar, 'id_valor_componente');
	}

	// Insertar múltiples componentes
	public function insertar_componentes_parcial($componentes_nuevos)
	{
		return $this->db->insert_batch('tbl_valor_componentes_evaluacion', $componentes_nuevos);
	}
}
