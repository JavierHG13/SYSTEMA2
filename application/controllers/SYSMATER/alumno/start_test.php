<?php
/*
 * show test that alumnos respond
 * 05/07/2016
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Start_test extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('/SYSMATER/alumno/test_model', 'test');
	}

	public function index()
	{
		show_404($page = base_url(), $log_error = TRUE);
	}

	// validate if the alumno can respond the test
	public function validate()
	{
		$id_examen = $this->uri->segment($this->uri->total_segments());
		
		$matricula = $this->session->Matricula;
		$grupo = $this->session->Grupo;

		$num_questions = $this->test->get_test($id_examen);
	
		$num_responses = $this->test->get_num_responses($id_examen, $matricula);		

		if ($num_questions != 0 && $num_questions === $num_responses) {
			$text =  "Usted ya realizo el examen! Informe a su aplicador";
			$this->_message($text);
		} else {
			/*REVISA SI EL EXAMEN TIENE FECHA DE APLICACION*/
		
			$date = $this->test->get_date_of_aplication($id_examen, $grupo);			

			if (is_array($date)) {
				/*REVISA SI LA FECHA Y HORA DEL SERVIDOR ESTA DENTRO DE RANGO PROGRAMADO*/
				$response = $this->_validation_of_date_hour($id_examen, $matricula, $grupo); ///-- RECIENTE CAMBIO PARA VERIFICAR GRUPO
				
				if ($response === "si") {

					$mac =  str_replace("-", "", $this->generate_cookie());
				
					$to_initiate = $this->test->check_if_start($mac, $matricula, $id_examen, $grupo);
				

					if ($to_initiate === TRUE) {
						$text =  "Usted ya tiene iniciado el examen en otro equipo. Informe a su aplicador";
						$this->_message($text);
						
					} else {
						$this->_start_test($id_examen, $num_questions);
						
					} // end if-else
				
				} else {
					$text = $response;

					$this->_message($text);
				} // end if-else
			} else {
				$text =  "No se ha programado fecha de aplicación para este examen.";
				$this->_message($text);
			} // end if-else
		} // end if-else
	} // end function

	// get the physical address of the computer
	function _get_mac()
	{

		$result = "";
		exec("ipconfig/all", $array, $retval);
		//$array[15];
		foreach ($array as $clave => $valor) {
			$ph = explode(":", $valor);
			if (count($ph) > 1) {
				if (strlen($ph[1]) == 18 && ($result == "")) {
					$result = trim($ph[1]);
				}
			}
		}
		return $result;
	} // end function

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
	
	// show message to alumno 
	private function _message($text)
	{
		$this->session->set_flashdata(
			'msg',
			'<div class="alert alert-danger text-center">
				' . $text . '
			</div>'
		);
		redirect("/sysmater/alumno/examenes"); //sel realizo cambio para redireccion a vista principal de examenes
	} // end function
	
	private function _start_test($id_examen, $num_questions)
	{

		$period = $this->_cuatrimestre();
		$test = $this->test->get_data_test($id_examen);
		$name_test = $test[0]["nvch_Titulo"];

		$data = array(
			'ID_exam' => $id_examen,
			'Nom_exam' => $name_test,
			'Cant_preguntas' => $num_questions,
			'Periodo' => $period
		);
		$this->session->set_userdata($data);

		if (isset($this->session->Tipo)) {
			if ($this->session->Tipo === "Administrador(a)") {
				$this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
			} else {
				$this->load->view('templates/views_sysmater/alumno_views/test_view'); //HASTA ACA INICIA EL EXAMEN	
			} // end if-else
		} else {
			$message = "Verifique que el usuario y contraseña sean correctos ";
			$status_code = 401;
			show_error($message, $status_code, $heading = 'Sesión no iniciada');
		} // end if-else
	} // end function

	private function _cuatrimestre($mouth = null)
	{ 
		$year = date('y');
		$mouth = is_null($mouth) ? date('m') : $mouth;
		$cuatri = floor(($mouth - 1) / 4) + 1;
		return "20" . $year . $cuatri;
	}

	// return data for methods_test.js($getJSON)
	public function get_questions($id_examen, $matricula)
	{
		$questions = $this->test->get_questions_alumno($id_examen, $matricula);
		if (is_array($questions)) {
			
			echo json_encode($questions);
		} else {
			return FALSE;
		} // end if-else
	} // end function


	//AQUI FALTARIA RECIBIR EL GRUPO
	public function get_time($matricula, $id)
	{		
		$time = $this->test->get_time($matricula, $id);
		if (is_array($time)) {
			header('Content-Type: application/json');
			echo json_encode($time);
			exit; // <- asegúrate de terminar correctamente
		} else {
			http_response_code(404); // devuelve un error válido
			echo json_encode(['error' => 'No se encontró el tiempo']);
			exit;
		}
	}

	private function _validation_of_date_hour($id_examen, $matricula, $grupo)
	{
		$date = $this->test->validate_date($id_examen, $grupo);
		if ($date[0]["pasa"] === "si") {
			$hour = $this->test->validate_hour($id_examen, $matricula, $grupo);

			if ($hour[0]["pasa"] === "si") {
				return 'si';
			} else {
				return 'La aplicación del examen esta programado en otro horario o  ya se termino su tiempo.';
			} // end if-else	
		} else {
			return 'La aplicación del examen esta programado con otra fecha.';
		} // end if-else
	} // end function


	public function save_response($matricula, $id_examen, $id_reactivo_main, $id_reactivo_detail, $resp, $time)
	{
		$per = $this->session->Periodo;
		$response = $this->test->save_resp($matricula, $id_examen, $id_reactivo_main, $id_reactivo_detail, $resp, $per, $time);
		if (is_array($response)) {
			echo json_encode($response);
		} else {
			return FALSE;
		} // end if-else
	} // end function

	// verify if it has responded
	public function check_responses($matricula, $id_examen)
	{
		$response = $this->test->check_resp($matricula, $id_examen);
		if (is_array($response)) {
			echo json_encode($response);
		} else {
			return FALSE;
		} // end if-else
	} // end function

}// end class
