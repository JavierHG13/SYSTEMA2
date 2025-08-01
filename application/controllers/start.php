<?php

/**
 * Initial controller.
 * 01/06/2016
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Start extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('/SYSMATER/shared/login_model');
	}

	public function index()
	{
		$data['systemas'] = $this->login_model->Systemas_activos();
		$this->load->view('templates/login_view',$data);
	}

	public function get_usuarios() {
		$systema = $this->input->post('systema');

		if ($systema) {
			$query = $this->login_model->Systemas_activos_Usuarios($systema);
		} else {
			$query = NULL;
		}

		if ($query !== NULL) { echo json_encode($query->result()); } 
		else {  echo json_encode([]); }
	}

} 
