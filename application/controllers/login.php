<?php

/**
 * login process.
 * 03/06/2016
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{
	public function __construct()
	{
		// Call the Controller constructor.
		parent::__construct();
		$this->load->model('/SYSMATER/shared/login_model');
	}

	public function index()
	{
		// get the posted values.
		$system = "SYSMATER";
		$type = $this->input->post('type_of_user');
		$username = $this->input->post("username");
		$password = $this->input->post("password");

		$this->validation();

		if ($this->form_validation->run() == FALSE) {
			$data['systemas'] = $this->login_model->Systemas_activos();
			$this->load->view('templates/login_view');
		} else {
			// validation succeeds
			if ($this->input->post('btn_login') == "Ingresar") {


				if ($type == 'Alumno(a)') {
					$this->login_alumno($username, $password, $system);
				} elseif ($type == 'Docente') {
					$this->login_docente($username, $password, $system);
				} elseif ($type == 'Revisor') {
					$this->login_revisor($username, $password, $system);
				} elseif ($type == 'Director') {
					$this->login_director($username, $password, $system);
				} elseif ($type == 'Admin') {
					$this->login_admin($username, $password, $system);
				} else {
					$this->logout();
				}
			} else {
				redirect(base_url());
			} // end if-else
		} // end if-else
	} // end function

	public function Cliente()
	{
		//	echo $this->generate_cookie();
		foreach ($_SERVER as $key => $value) {
			echo '$_SERVER["' . $key . '"] = ' . $value . "<br />";
		}
	}

	public function get_usuarios()
	{
		$systema = $this->input->post('systema');

		if ($systema) {
			$query = $this->login_model->Systemas_activos_Usuarios($systema);
		} else {
			$query = NULL;
		}

		if ($query !== NULL) {
			echo json_encode($query->result());
		} else {
			echo json_encode([]);
		}
	}

	public function generate_cookie()
	{
		$cookie_name = "clientId";

		if (!isset($_COOKIE[$cookie_name])) {
			$cookie_value = uniqid();
			setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
		} else {

			$cookie_value = $_COOKIE[$cookie_name];
		}
		return $cookie_value;
	}

	public function cambia_password()
	{
		// get the posted values.
		$type = $this->input->post('type_of_user');
		$username = $this->input->post("username");
		$old_password = $this->input->post("old_password");
		$password = $this->input->post("password");

		$this->validation();

		if ($this->form_validation->run() == FALSE) {
			// validation fails.
			redirect(base_url());
		} else {
			// validation succeeds
			if ($this->input->post('task') == "save") {
				if ($type == 5) {
					$this->valida_alumno($username, $old_password, $password);
				} elseif ($type == 4) {
					$this->valida_docente($username, $old_password, $password);
				} elseif ($type == 3) {
					$this->valida_revisor($username, $old_password, $password);
				} elseif ($type == 2) {
					$this->valida_director($username, $old_password, $password);
				} elseif ($type == 1) {
					$this->valida_admin($username, $old_password, $password);
				}
				redirect(base_url());
			} else {
				redirect(base_url());
			} // end if-else
		} // end if-else
	}
	private function validation()
	{
		// set validations
		$config = array(
			array(
				'field' => 'username',
				'label' => 'Username',
				'rules' => 'trim|required|numeric|min_length[4]|max_length[8]'
			),
			array(
				'field' => 'password',
				'label' => 'Password',
				'rules' => 'trim|required|min_length[4]|max_length[20]'
			)
		);
		$this->form_validation->set_rules($config);
	} // end function

	private function login_alumno($id, $password, $system)
	{
		echo 'ad5';
		// check if username and password are correct
		$alumno_result = $this->login_model->login_alumno($id, $password, $system);

		if ($alumno_result > 0) {

			$this->sesion_alumno($alumno_result, $system);
		} else {
			$this->not_found();
		} // end if-else
	} // end function

	// alumno log
	private function sesion_alumno($result, $system)
	{
		echo 'ad3';
		if ($system == 'SYSMATER') {
			$nombre_completo = trim($result->vchNombre . ' ' . $result->vchAPaterno . ' ' . $result->vchAMaterno);
			$data = array(
				'Tipo' => 'Alumno(a)',
				'Usuario' => $nombre_completo,
				'Matricula' => $result->vchMatricula,
				'NomCarrera' => $result->vchNomCarrera,
				'clvCarrera' => $result->chrClvCarrera,
				'Especialidad' => 00,
				'clvEspecial' => 00,
				'Cuatri' => $result->vchNomCuatri,
				'clvCuatri' => $result->vchClvCuatri,
				'Periodo' => $this->determinarPeriodo(),
				'Grupo' => $result->chvGrupo,
				'GrupoNom' => $result->vchGrupo,
				'Password' => $result->vchContrasenia,
				'id_tipo' => 5
			);
		} else {

			$data = array(
				'Tipo' => 'Alumno(a)',
				'Usuario' => $result->Nombre,
				'Matricula' => $result->Matricula,
				'NomCarrera' => $result->Carrera,
				'clvCarrera' => $result->clvCarrera,
				'Especialidad' => $result->Especialidad,
				'clvEspecial' => $result->clvEspecialidad,
				'Cuatri' => $result->Cuatrimestre,
				'clvCuatri' => $result->clvCuatri,
				'Periodo' =>  $this->determinarPeriodo(),
				'Grupo' => $result->chvGrupo,
				'GrupoNom' => $result->vchGrupo,
				'Password' => $result->Password,
				'id_tipo' => 5
			);
		}

		$this->session->set_userdata($data);
		redirect($system . "/alumno/examenes");
	} // end function



	private function login_docente($id, $password, $system)
	{
		// check if username and password are correct
		$admin_result = $this->login_model->login_docente($id, $password);
		if ($admin_result > 0) {
			$this->sesion_docente($admin_result, $system);
		} else {
			$this->not_found();
		} // end if-else
	} // end function

	// admin log
	private function sesion_docente($resp, $system)
	{
		$Nom = $resp->Docente; //se hizo este cambio
		$data = array(
			'Tipo' => 'Docente',
			'Usuario' => $Nom,
			'Matricula' => $resp->vchClvTrabajador,
			'id_tipo' => 4
		);

		$this->session->set_userdata($data);
		redirect($system . "/docente/docente");
	} // end function

	private function login_revisor($id, $password, $system)
	{
		// check if username and password are correct
		$admin_result = $this->login_model->login_revisor($id, $password);
		if ($admin_result > 0) {
			$this->sesion_revisor($admin_result, $system);
		} else {
			$this->not_found();
		} // end if-else
	} // end function

	// admin log
	private function sesion_revisor($resp, $system)
	{
		$Nom = $resp->Docente;
		$data = array(
			'Tipo' => 'Docente',
			'Usuario' => $Nom,
			'Matricula' => $resp->vchClvTrabajador,
			'id_tipo' => 3
		);
		$this->session->set_userdata($data);
		redirect($system . "/revisor/revisor");
	} // end function

	private function login_director($id, $password, $system)
	{
		// check if username and password are correct
		$admin_result = $this->login_model->login_director($id, $password);
		if ($admin_result > 0) {
			$this->sesion_director($admin_result, $system);
		} else {
			$this->not_found();
		} // end if-else
	} // end function

	// admin log
	private function sesion_director($resp, $system)
	{
		$Nom = $resp->Director;
		$data = array(
			'Tipo' => 'Director',
			'Usuario' => $Nom,
			'Matricula' => $resp->vchClvTrabajador,
			'id_tipo' => 2
		);
		$this->session->set_userdata($data);
		redirect($system . "/director/director");
	} // end function

	private function login_admin($id, $password, $system)
	{
		// check if username and password are correct
		$admin_result = $this->login_model->login_admin($id, $password);
		if ($admin_result > 0) {
			$this->sesion_admin($admin_result, $system);
		} else {
			$this->not_found();
		} // end if-else
	} // end function

	// admin log
	private function sesion_admin($resp, $system)
	{
		$Nom = $resp->Trabajador;
		$data = array(
			'Tipo' => 'Administrador(a)',
			'Usuario' => $Nom,
			'Matricula' => $resp->vchClvTrabajador,
			'id_tipo' => 1
		);
		$this->session->set_userdata($data);

		redirect($system . "/admin/admin");
	} // end function

	// not found username
	private function not_found()
	{
		$this->session->set_flashdata(
			'msg',
			'<div class="alert alert-danger text-center">
				Invalid username or password!
			</div>'
		);
		redirect(base_url());
	}

	// Sign off and exit
	public function logout()
	{
		$this->destroy_sessions();
		redirect(base_url());
	}

	private function destroy_sessions()
	{
		// empty the sessions to then destroy them correctly
		if ($_SESSION['id_tipo'] != 5) {
			$data = array(
				'Tipo' => '',
				'Usuario' => '',
				'Matricula' => '',
				'id_tipo' => ''
			);
		} else {
			$data = array(
				'Tipo' => '',
				'Usuario' => '',
				'Matricula' => '',
				'NomCarrera' => '',
				'clvCarrera' => '',
				'Especialidad' => '',
				'Cuatri' => '',
				'clvCuatri' => '',
				'Grupo' => '',
				'Password' => '',
				'id_tipo' => ''
			);
		}
		$this->session->unset_userdata($data);
		$this->session->sess_destroy();
	} // end function

	private function valida_alumno($id, $old_password, $password)
	{
		// check if username and password are correct
		$alumno_result = $this->login_model->login_alumno($id, $old_password);
		if ($alumno_result > 0) {
			$this->login_model->update_alumno($id, $password);
		} else {
			$this->not_found();
		} // end if-else
	}

	private function valida_docente($id, $old_password, $password)
	{
		// check if username and password are correct
		$docente_result = $this->login_model->login_docente($id, $old_password);
		if ($docente_result > 0) {
			$this->login_model->update_docente($id, $password);
		} else {
			$this->not_found();
		} // end if-else
	}
	private function valida_revisor($id, $old_password, $password)
	{
		// check if username and password are correct
		$revisor_result = $this->login_model->login_revisor($id, $old_password);
		if ($revisor_result > 0) {
			$this->login_model->update_revisor($id, $password);
		} else {
			$this->not_found();
		} // end if-else
	}
	private function valida_director($id, $old_password, $password)
	{
		// check if username and password are correct

		$director_result = $this->login_model->login_director($id, $old_password);
		if ($director_result <> null) {
			$this->login_model->update_director($id, $password);
		} else {
			$this->not_found();
		} // end if-else

	}

	private function valida_admin($id, $old_password, $password)
	{
		// check if username and password are correct
		$admin_result = $this->login_model->login_admin($id, $old_password);
		if ($admin_result > 0) {
			$this->login_model->update_admin($id, $password);
		} else {
			$this->not_found();
		} // end if-else
	}
	private function determinarPeriodo()
	{
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
}// end class
