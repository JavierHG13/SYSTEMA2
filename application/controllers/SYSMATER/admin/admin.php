<?php

/**
 * Page administrator controller.
 * 06/06/2016
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Admin extends CI_Controller
{
	private $MarcarVacias = [];

	private $nombresColumnas = [
		1 => 'Matricula',
		2 => 'Apellido Paterno',
		3 => 'Apellido Materno',
		4 => 'Nombre',
		5 => 'Clave de Carrera',
		6 => 'Cuatrimestre',
		7 => 'Grupo',
		8 => 'Periodo',
	];

	private $nombresColumnasDocentes = [
		1 => 'Clave Del Trabajador',
		2 => 'Apellido Paterno',
		3 => 'Apellido Materno',
		4 => 'Nombre'
	];

	private $nombresColumnasMaterias = [
		1 => 'CLAVE',
		2 => 'NOMBRE DE LA MATERIA',
		3 => 'PERIODO',
		4 => 'CUATRIMESTRE'
	];

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{

		if ($this->session->id_tipo == 1) {
			$this->load->view("templates/views_sysmater/admin_views/admin_navbar_view");
			$this->load->view("templates/shared/home_sysmater_view");
			$this->load->view("templates/shared/footer_sysmater_view");
		} else {
			$message = "Verifique que el usuario y contraseña sean correctos ";
			$status_code = 401;
			show_error($message, $status_code, $heading = 'Sesión no iniciada');
		}
	} // end function


	public function search_alumno()
	{
		redirect('/sysmater/admin/search_alumno');
	}

	public function change_pc()
	{
		redirect('/sysmater/admin/change_pc');
	}

	/*<!--CODIGO AGREGADO POR ISRAEL REYES AQUINO-->*/
	//////////////// MODULOS    ////////////////
	public function lista_modulo()
	{
		redirect('/sysmater/admin/lista_modulo');
	}
	public function nuevo_modulo()
	{
		redirect('/sysmater/admin/nuevo_modulo');
	}
	public function nueva_materia()
	{
		redirect('/sysmater/admin/nueva_materia');
	}
	public function elimina_modulo()
	{
		$id = $this->uri->segment($this->uri->total_segments());
		redirect('/sysmater/admin/elimina_modulo/index/' . $id);
	}
	public function edita_modulo()
	{
		$id = $this->uri->segment($this->uri->total_segments());
		redirect('/sysmater/admin/edita_modulo/index/' . $id);
	}

	//////////////// AREAS    ////////////////
	public function lista_area()
	{
		redirect('/sysmater/admin/lista_area');
	}
	public function nuevo_area()
	{
		redirect('/sysmater/admin/nuevo_area');
	}
	public function elimina_area()
	{
		$id = $this->uri->segment($this->uri->total_segments());
		redirect('/sysmater/admin/elimina_area/index/' . $id);
	}
	public function edita_area()
	{
		$id = $this->uri->segment($this->uri->total_segments());
		redirect('/sysmater/admin/edita_area/index/' . $id);
	}
	//////////////// NIVELES    ////////////////
	public function lista_nivel()
	{
		redirect('/sysmater/admin/lista_nivel');
	}
	public function nuevo_nivel()
	{
		redirect('/sysmater/admin/nuevo_nivel');
	}
	public function elimina_nivel()
	{
		$id = $this->uri->segment($this->uri->total_segments());
		redirect('/sysmater/admin/elimina_nivel/index/' . $id);
	}
	public function edita_nivel()
	{
		$id = $this->uri->segment($this->uri->total_segments());
		redirect('/sysmater/admin/edita_nivel/index/' . $id);
	}
	//////////////// REPORTES    ////////////////
	public function reportes_individuales()
	{
		redirect('/sysmater/admin/reportes_individuales');
	}
	public function reporte_examen()
	{
		redirect('/sysmater/admin/reporte_examen');
	}
	public function reporte_examen_parciales()
	{
		redirect('/sysmater/admin/reporte_examen_parciales');
	}
	public function reporte_examen_escalas()
	{
		redirect('/sysmater/admin/reporte_examen_escalas');
	}
	public function reporte_examen_reactivos()
	{

		redirect('/sysmater/admin/reporte_examen_reactivos');
	}
	/*EXAMENES*/
	public function examenes_registrados()
	{
		redirect("/sysmater/admin/examenes_registrados");
	}
	public function new_examen()
	{
		redirect("/sysmater/admin/new_examen");
	}
	public function nuevo_examen()
	{
		redirect("/sysmater/admin/nuevo_examen");
	}
	public function elimina_examen()
	{
		$id = $this->uri->segment($this->uri->total_segments());
		redirect("/sysmater/admin/elimina_examen/index/" . $id);
	}
	public function edita_examen()
	{
		$id = $this->uri->segment($this->uri->total_segments());
		redirect("/sysmater/admin/edita_examen/index/" . $id);
	}
	public function reactivos_examen()
	{
		$id = $this->uri->segment($this->uri->total_segments());
		redirect("/sysmater/admin/reactivos_examen/index/" . $id);
	}
	public function ver_examen()
	{
		$id = $this->uri->segment($this->uri->total_segments());
		redirect("/sysmater/admin/ver_examen/index/" . $id);
	}
	public function examenes_programados()
	{
		redirect("/sysmater/admin/examenes_programados");
	}

	public function programar_examen()
	{
		redirect('/sysmater/admin/programar_examen');
	}
	public function elimina_programacion()
	{
		$id = $this->uri->segment($this->uri->total_segments());
		redirect("/sysmater/admin/elimina_programacion/index/" . $id);
	}
	public function edita_programacion()
	{
		$id = $this->uri->segment($this->uri->total_segments());
		redirect("/sysmater/admin/edita_programacion/index/" . $id);
	}

	public function lista_materias()
	{
		redirect('/sysmater/admin/lista_materias');
	}

	/////////////////////////////////////////////// Docentes //////////////////////////////////////////

	public function nuevo_docente()
	{
		redirect("/sysmater/admin/nuevo_docente");
	}

	public function lista_docente()
	{
		redirect("/sysmater/admin/lista_docente");
	}

	public function elimina_docente()
	{
		$id = $this->uri->segment($this->uri->total_segments());
		redirect("/sysmater/admin/elimina_docente/index/" . $id);
	}
	public function asignar_docente()
	{
		$id = $this->uri->segment($this->uri->total_segments());
		redirect("/sysmater/admin/asignar_docente/index/" . $id);
	}

	public function ver_docente_detalles()
	{
		$id = $this->uri->segment($this->uri->total_segments());
		redirect("/sysmater/admin/ver_docente_detalles/index/" . $id);
	}
	public function activacion_sistema()
	{
		redirect("/sysmater/admin/activacion_sistema");
	}

	//materias
	public function elimina_materia()
	{
		$id = $this->uri->segment($this->uri->total_segments());
		redirect("/sysmater/admin/elimina_materia/index/" . $id);
	}



	/////////////////////////////////////////////// carga de reactivos examen //////////////////////////////////////////
	//! ok
	public function ajax_reactivos_disponibles_materia() {
		$materia = $this->input->post('materia');
		$id_examen = $this->input->post('id_examen');
		$this->load->model('/SYSMATER/shared/carga_reactivos_model');
		$reactivos = $this->carga_reactivos_model->get_reactivos_por_materia_json($materia,$id_examen);
		
		$data = array();
		if ($reactivos != NULL) {
			foreach ($reactivos->result() as $row) {
				$data[] = array(
					'id' => $row->id_reactivos_main_sysmater,
					'horas' => $row->int_horas,
					'minutos' => $row->int_minutos,
					'nivel' => $row->id_nivel,
					'base' => $row->txt_base,
					'opcionA' => $row->nvch_opcionA,
					'argumentoA' => $row->nvch_argumentaA,
					'imagenA' => $row->path_imagenA,
					'opcionB' => $row->nvch_opcionB,
					'argumentoB' => $row->nvch_argumentaB,
					'imagenB' => $row->path_imagenB,
					'opcionC' => $row->nvch_opcionC,
					'argumentoC' => $row->nvch_argumentaC,
					'imagenC' => $row->path_imagenC,
					'opcionD' => $row->nvch_opcionD,
					'argumentoD' => $row->nvch_argumentaD,
					'imagenD' => $row->path_imagenD,
					'correcta' => $row->chr_correcto,
					'bibliografia' => $row->vch_bibliografia,
					'imagen_base' => $row->path_imagen_base,
					'trabajador' => $row->vchClvTrabajador
				);
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	//! ok
	public function asignar_reactivo_examen()
	{
		$id_examen = $this->input->post('id_examen');
		$id_reactivo_main = $this->input->post('id_reactivo_main');

		if ($id_examen && $id_reactivo_main) {
			$data = [
				'id_examen' => $id_examen,
				'id_reactivo_main' => $id_reactivo_main
			];
			$this->load->model('/SYSMATER/shared/examen_model');
			$res=$this->examen_model->seleccionar_reactivo($data);
			echo json_encode(['success' => true]);
		} else {
			http_response_code(400);
			echo json_encode(['error' => 'Datos incompletos']);
		}
	}
	//! ok
	public function eliminar_reactivo_examen()
	{
		$id_examen = $this->input->post('id_examen');
		$id_reactivo_main = $this->input->post('id_reactivo_main');

		if ($id_examen && $id_reactivo_main) {
			$data = [
				'id_examen' => $id_examen,
				'id_reactivo_main' => $id_reactivo_main
			];
			$this->load->model('/SYSMATER/shared/examen_model');
			$this->examen_model->deseleccionar_reactivos($data);
			echo json_encode(['success' => true]);
		} else {
			http_response_code(400);
			echo json_encode(['error' => 'Datos incompletos']);
		}
	}
	// ! hasta aca ok para recuperar reactivos disponibles de una materia


	/*AJAX*/
	public function ajax_reactivos_examen() // no se usa
	{
		$path_images = 'uploads/';
		$this->load->model('/SYSMATER/shared/reactivo_model');
		if ($this->input->post('examen')) {
			$examen = $this->input->post('examen');
			$reactivos = $this->reactivo_model->reactivos_examen_json($examen);
			if ($reactivos != NULL) {
				$i = 1;
				echo "	<div class='panel-group'>";
				foreach ($reactivos->result() as $fila) {
					$id_main = $fila->id_reactivo_main;
					if ($fila->int_compartidos == 1) {
						echo "	<div class='panel panel-info'>";
					} else {
						echo "	<div class='panel panel-success'>";
					}
					echo "		<div class='panel-heading'>";
					echo "			<input type='checkbox' id='chk_eliminar' name='chk_eliminar[]' value='" . $id_main . "'>";
					echo "			#" . $i . "- " . $fila->vchClvCuatri . "-" . $fila->vchNomMateria;
					echo "		</div>";
					echo "		<div class='panel-body'>";
					if ($fila->nReactivos > 1) {
						echo "		<b>PLANTEAMIENTO: </b> </br>" . $fila->txt_planeamiento . "<br>";
					}
					$reactivo_detail = $this->reactivo_model->get_reactivo_detail_preview($id_main);

					$t = 1;
					echo "			<ul class='nav nav-tabs sinpadding' id='tabs'>";
					if ($reactivo_detail) {
						foreach ($reactivo_detail->result() as $reactivo) {
							if ($t == 1) {
								$active = "active";
							} else {
								$active = "";
							}
							echo "	<li  id='refTab" . $id_main . $t . "' class='" . $active . " sinpadding'>";
							echo		"<a data-toggle='tab' href='#tab" . $id_main . $t . "' class='sinpadding'>Reactivo " . $t . "</a>";
							echo "	</li>";
							$t++;
						}
					}
					echo "			</ul>";

					echo "			<div class='tab-content'>";
					$t = 1;
					if ($reactivo_detail) {
						foreach ($reactivo_detail->result() as $reactivo) {
							if ($t == 1) {
								$active = 'in active';
							} else {
								$active = '';
							}

							// echo "				<div id='tab" . $id_main . $t . "' class='tab-pane fade " . $active . "'>";
							echo "	    			<div class='col-sm-12 sinpadding'>";
							echo $reactivo->txt_base;
							if ($reactivo->path_imagen_base) {
								echo "							<div class='hover_img'>";
								echo "								<a href='#'><span><img src='" . base_url() . $path_images . $reactivo->path_imagen_base . "' class='img-rounded' alt='image'/></span><i class='glyphicon glyphicon-picture' aria-hidden ='true'></i></a>";
								echo "	    					</div>";
							}
							echo "	    			</div>";
							echo "  			</div>";
							$t++;
						}
					}
					echo "			</div>";
					echo "		</div>";
					echo "	</div>";
					$i++;
				}
				echo "	</div>";
			}
		}
	}
	public function ajax_reactivos_carrera() // no se usa
	{
		$path_images = 'uploads/';
		$this->load->model('/SYSMATER/shared/reactivo_model');
		if ($this->input->post('examen')) {
			$examen = $this->input->post('examen');
			$carrera = $this->input->post('carrera');
			$reactivos = $this->reactivo_model->reactivos_carrera_json($carrera, $examen);
			if ($reactivos != NULL) {
				$i = 1;
				echo "	<div class='panel-group'>";
				foreach ($reactivos->result() as $fila) {
					$id_main = $fila->id_reactivo_main;
					echo "	<div class='panel panel-success'>";
					echo "		<div class='panel-heading sinpadding'>";
					echo "			<input type='checkbox' id='chk_select' name='chk_select[]' value='" . $id_main . "'>";
					echo "			<input type='hidden' id='nSubreactivos' name='nSubreactivos[]' value='" . $fila->nReactivos . "'>";
					echo "			#" . $i . "- " . $fila->vchClvCuatri . "-" . $fila->vchNomMateria;
					echo "		</div>";
					echo "		<div class='panel-body'>";
					if ($fila->nReactivos > 1) {
						echo "		<b>PLANTEAMIENTO: </b> </br>" . $fila->txt_planeamiento . "<br>";
					}
					$reactivo_detail = $this->reactivo_model->get_reactivo_detail_preview($id_main);

					$t = 1;
					echo "			<ul class='nav nav-tabs sinpadding' id='tabs'>";
					if ($reactivo_detail) {
						foreach ($reactivo_detail->result() as $reactivo) {
							if ($t == 1) {
								$active = "active";
							} else {
								$active = "";
							}
							echo "	<li  id='refTab" . $id_main . $t . "' class='" . $active . " sinpadding'>";
							echo		"<a data-toggle='tab' href='#tab" . $id_main . $t . "' class='sinpadding'>Reactivo " . $t . "</a>";
							echo "	</li>";
							$t++;
						}
					}
					echo "			</ul>";

					echo "			<div class='tab-content'>";
					$t = 1;
					if ($reactivo_detail) {
						foreach ($reactivo_detail->result() as $reactivo) {
							if ($t == 1) {
								$active = 'in active';
							} else {
								$active = '';
							}

							echo "				<div id='tab" . $id_main . $t . "' class='tab-pane fade " . $active . "'>";
							echo "	    			<div class='col-sm-12 sinpadding'>";
							echo $reactivo->txt_base;
							if ($reactivo->path_imagen_base) {
								echo "							<div class='hover_img'>";
								echo "								<a href='#'><span><img src='" . base_url() . $path_images . $reactivo->path_imagen_base . "' class='img-rounded' alt='image'/></span><i class='glyphicon glyphicon-picture' aria-hidden ='true'></i></a>";
								echo "	    					</div>";
							}
							echo "	    			</div>";
							echo "  			</div>";
							$t++;
						}
					}
					echo "			</div>";
					echo "		</div>";
					echo "	</div>";
					$i++;
				}
				echo "	</div>";
			}
		}
	}
	public function ajax_reactivos_compartidos() // no se usa
	{
		$path_images = 'uploads/';
		$this->load->model('/SYSMATER/shared/reactivo_model');
		if ($this->input->post('examen')) {
			$examen = $this->input->post('examen');
			$reactivos = $this->reactivo_model->reactivos_compartidos_json($examen);
			if ($reactivos != NULL) {
				$i = 1;
				echo "	<div class='panel-group'>";
				foreach ($reactivos->result() as $fila) {
					$id_main = $fila->id_reactivo_main;
					echo "	<div class='panel panel-info'>";
					echo "		<div class='panel-heading sinpadding'>";
					echo "			<input type='checkbox' id='chk_select' name='chk_select[]' value='" . $id_main . "'>";
					echo "			<input type='hidden' id='nSubreactivos' name='nSubreactivos[]' value='" . $fila->nReactivos . "'>";
					echo "			#" . $i . "- " . $fila->vchClvCuatri . "-" . $fila->vchNomMateria;
					echo "		</div>";
					echo "		<div class='panel-body'>";
					if ($fila->nReactivos > 1) {
						echo "		<b>PLANTEAMIENTO: </b> </br>" . $fila->txt_planeamiento . "<br>";
					}
					$reactivo_detail = $this->reactivo_model->get_reactivo_detail_preview($id_main);

					$t = 1;
					echo "			<ul class='nav nav-tabs sinpadding' id='tabs'>";
					if ($reactivo_detail) {
						foreach ($reactivo_detail->result() as $reactivo) {
							if ($t == 1) {
								$active = "active";
							} else {
								$active = "";
							}
							echo "	<li  id='refTab" . $id_main . $t . "' class='" . $active . " sinpadding'>";
							echo		"<a data-toggle='tab' href='#tab" . $id_main . $t . "' class='sinpadding'>Reactivo " . $t . "</a>";
							echo "	</li>";
							$t++;
						}
					}
					echo "			</ul>";

					echo "			<div class='tab-content'>";
					$t = 1;
					if ($reactivo_detail) {
						foreach ($reactivo_detail->result() as $reactivo) {
							if ($t == 1) {
								$active = 'in active';
							} else {
								$active = '';
							}

							echo "				<div id='tab" . $id_main . $t . "' class='tab-pane fade " . $active . "'>";
							echo "	    			<div class='col-sm-12 sinpadding'>";
							echo $reactivo->txt_base;
							if ($reactivo->path_imagen_base) {
								echo "							<div class='hover_img'>";
								echo "								<a href='#'><span><img src='" . base_url() . $path_images . $reactivo->path_imagen_base . "' class='img-rounded' alt='image'/></span><i class='glyphicon glyphicon-picture' aria-hidden ='true'></i></a>";
								echo "	    					</div>";
							}
							echo "	    			</div>";
							echo "  			</div>";
							$t++;
						}
					}
					echo "			</div>";
					echo "		</div>";
					echo "	</div>";
					$i++;
				}
				echo "	</div>";
			}
		}
	}
	/*<!--TERMINA CODIGO POR ISRAEL REYES AQUINO-->*/


	//! todas las funciones de abajo si se usan :]
	private function formatearMensaje($celdasVacias, $archivoModificado, $archivoNombre)
	{
		$filePath = $archivoModificado; // Ruta al archivo original

		// HTML para la imagen
		$imagen = '<img src="' . base_url('assets/animationS/advertencia.png') . '" alt="Imagen" style="width: 140px; height: 140px; margin: 0 auto 20px; display: block;">';

		$mensaje = '<div style="font-family: \'Nunito\', sans-serif; color: #f1fcf9; padding: 20px; display: flex; justify-content: center;">';
		$mensaje .= '<div style="max-width: 400px; width: 100%; background: #1bb190; padding: 40px; border-radius: 8px; box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);">';
		$mensaje .= '<h2 style="font-size: 1.5em; font-weight: bold; text-align: center; margin-bottom: 20px;">Las siguientes celdas tienen problemas:</h2>';
		$mensaje .= '<ul style="list-style-type: none; padding-left: 0; margin: 0;">';
		$mensaje .= $imagen;

		foreach ($celdasVacias as $celda) {
			$mensaje .= '<li style="background: #c0f2d4; margin: 10px 0; padding: 15px; border: 1px solid #ddd; border-radius: 18px; text-align: center; color: black; transition: transform 0.2s ease-in-out;" onmouseover="this.style.transform=\'scale(1.05)\';" onmouseout="this.style.transform=\'scale(1)\';">' . htmlspecialchars($celda) . '</li>';
		}

		$mensaje .= '</ul>';
		$mensaje .= '<div style="text-align: center; margin-top: 20px;">';
		$mensaje .= '<a href="' . $filePath . '" download="' . $archivoNombre . '">';
		$mensaje .= '<button style="padding: 10px 20px; background-color: #0e776a; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">Descargar Excel Modificado</button>';
		$mensaje .= '</a>';
		$mensaje .= '</div>';

		// Botón de regresar
		$mensaje .= '<div style="text-align: center; margin-top: 20px;">';
		$mensaje .= '<button onclick="history.back()" style="padding: 10px 20px; background-color: #0e776a; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">Regresar</button>';
		$mensaje .= '</div>';

		$mensaje .= '</div>';
		$mensaje .= '</div>';

		return $mensaje;
	}

	public function vista_carga_completa()
	{
		redirect('/sysmater/admin/admin/vista_carga_completa');
	}

	public function marcarCeldasExcelVacias($filePath, $celdasVacias, $archivoNombre)
	{
		$spreadsheet = IOFactory::load($filePath);
		$sheet = $spreadsheet->getActiveSheet();

		// Marca las celdas vacías con color amarillo
		foreach ($celdasVacias as $celda) {
			$sheet->getStyle($celda)->getFill()
				->setFillType(Fill::FILL_SOLID)
				->getStartColor()->setARGB('FFFFFF00'); // Color amarillo
		}

		// Crear el writer para guardar el archivo
		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

		// Definir la nueva ruta de guardado dentro del directorio de la aplicación
		$folderName = 'Archivos_Errores'; // Nombre de la carpeta especial
		$newFolderPath = FCPATH . $folderName . DIRECTORY_SEPARATOR;

		// Crear la carpeta especial si no existe
		if (!file_exists($newFolderPath)) {
			mkdir($newFolderPath, 0777, true);
		}

		// Definir la nueva ruta de guardado para el archivo dentro de la carpeta especial
		$newFilePath = $newFolderPath . $archivoNombre;

		try {
			// Guardar el archivo modificado en la nueva ubicación
			$writer->save($newFilePath);

			// Construir la URL de descarga
			$baseUrl = base_url(); // Obtener la URL base de la aplicación
			$downloadUrl = $baseUrl . $folderName . '/' . $archivoNombre;

			// Si el archivo se guarda correctamente, se devolverá la URL del archivo
			return $downloadUrl;
		} catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
			// Captura cualquier excepción que pueda ocurrir durante el proceso de guardado
			// Maneja el error aquí, por ejemplo, registrando el error o mostrando un mensaje al usuario
			error_log('Error al guardar el archivo: ' . $e->getMessage());
			// Puedes devolver un valor nulo o falso para indicar que ocurrió un error
			return null;
		}
	}

	public function carga_materias()
	{

		if ($this->session->id_tipo == 1) {

			if ($this->input->server('REQUEST_METHOD') === 'POST') {

				if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
					$archivoTmpPath = $_FILES['archivo']['tmp_name'];
					$archivoNombre = $_FILES['archivo']['name'];
					$archivoExtension = pathinfo($archivoNombre, PATHINFO_EXTENSION);

					// Verificar si el archivo es un archivo Excel
					if (in_array($archivoExtension, ['xlsx', 'xls'])) {
						// Llamar a la función para comprobar el archivo
						// Cargar la biblioteca en el método index

						$mensajeError = $this->comprovarArchivo_materias($archivoTmpPath, $archivoNombre);

						if (empty($mensajeError)) {
							// Si no hay errores, cargar el archivo
							$this->cargarArchivo_materias($archivoTmpPath);

							// Eliminar el archivo temporal
							if (file_exists($archivoTmpPath)) {
								unlink($archivoTmpPath);
							}

							// Redirigir a otra página después de ejecutar
							redirect('/sysmater/admin/vista_carga_completa_materias');
						} else {
							// Mostrar mensaje de error si hay celdas vacías
							echo nl2br($mensajeError);
						}
					} else {
						// Mensaje de error si el archivo no es Excel
						$this->session->set_flashdata('error', 'Por favor, sube un archivo Excel (.xlsx, .xls)');
					}
				} else {
					// Mensaje de error si no se ha subido un archivo correctamente
					$this->session->set_flashdata('error', 'Error al subir el archivo. Por favor, intenta nuevamente.');
				}
			} else {
				redirect('/sysmater/admin/carga_materias');
			}
		}
	}

	public function comprovarArchivo_materias($nombreDelArchivo, $archivoNombre)
	{
		$documento = IOFactory::load($nombreDelArchivo);
		$hojaActual = $documento->getActiveSheet();
		$totalFilas = $hojaActual->getHighestDataRow();

		// Inicializa un arreglo para almacenar las celdas vacías
		$celdasVacias = [];

		for ($indiceFila = 2; $indiceFila <= $totalFilas; $indiceFila++) {
			// Verifica si la columna 1 está llena
			$valorCeldaColumna1 = $hojaActual->getCell(Coordinate::stringFromColumnIndex(1) . $indiceFila)->getValue();
			if (trim($valorCeldaColumna1) === '') {
				continue; // Si la columna 1 está vacía, se salta esta fila
			}

			// Recorre todas las columnas que necesitas
			for ($indiceColumna = 1; $indiceColumna <= 4; $indiceColumna++) {
				$NColumna = Coordinate::stringFromColumnIndex($indiceColumna);
				// Almacena cada valor de celda en el arreglo
				$valorCelda = $hojaActual->getCell(Coordinate::stringFromColumnIndex($indiceColumna) . $indiceFila)->getValue();

				if ($valorCelda === Null || $valorCelda === '') {
					$nombresColumnasMaterias = $this->nombresColumnasMaterias[$indiceColumna];
					$celdasVacias[] = "Fila $indiceFila, Columna $nombresColumnasMaterias debe estar llena.";
					$MarcarVacias = $NColumna . $indiceFila;
				} else {

					if ($indiceColumna == 1 && strlen(trim($valorCelda)) == 6) {
						$valorCelda = trim($valorCelda);
						$primerosTres = substr($valorCelda, 0, 3);
						$ultimosTres = substr($valorCelda, 3, 3);

						if (!ctype_upper($primerosTres) || !ctype_digit($ultimosTres)) {
							$nombresColumnasMaterias = $this->nombresColumnasMaterias[$indiceColumna];
							$celdasVacias[] = "Fila $indiceFila, Columna $nombresColumnasMaterias debe tener 3 letras MAYÚSCULAS seguidas de 3 números.";
							$MarcarVacias[] = $NColumna . $indiceFila;
						}
					}

					if ($indiceColumna == 1 && strlen(trim($valorCelda)) !== 6) {
						$nombresColumnasMaterias = $this->nombresColumnasMaterias[$indiceColumna];
						$celdasVacias[] = "Fila $indiceFila, Columna $nombresColumnasMaterias debe tener 6 caracteres.";
						$MarcarVacias[] = $NColumna . $indiceFila;
					}

					if ($indiceColumna == 2 && trim($valorCelda) !== strtoupper(trim($valorCelda))) {
						$nombresColumnasMaterias = $this->nombresColumnasMaterias[$indiceColumna];
						$celdasVacias[] = "Fila $indiceFila, Columna $nombresColumnasMaterias debe estar en MAYÚSCULAS.";
						$MarcarVacias[] = $NColumna . $indiceFila;
					}

					if ($indiceColumna == 3 && strlen(trim($valorCelda)) !== 1) {
						$nombresColumnasMaterias = $this->nombresColumnasMaterias[$indiceColumna];
						$celdasVacias[] = "Fila $indiceFila, Columna $nombresColumnasMaterias debe tener 1 caracter como 1,2 o 3.";
						$MarcarVacias[] = $NColumna . $indiceFila;
					}

					if ($indiceColumna == 4) {
						$valorCelda = trim($valorCelda);
						if (strlen($valorCelda) !== 1 && strlen($valorCelda) !== 2) {
							$celdasVacias[] = "Fila $indiceFila, Columna $nombresColumnasMaterias debe tener 1 o 2 caracteres.";
							$MarcarVacias[] = $NColumna . $indiceFila;
						} else if (!ctype_digit($valorCelda)) {
							$celdasVacias[] = "Fila $indiceFila, Columna $nombresColumnasMaterias debe ser un número.";
							$MarcarVacias[] = $NColumna . $indiceFila;
						} else {
							$numero = (int)$valorCelda;
							if ((strlen($valorCelda) == 1 && ($numero < 1 || $numero > 9)) ||
								(strlen($valorCelda) == 2 && ($numero < 10 || $numero > 11))
							) {
								$celdasVacias[] = "Fila $indiceFila, Columna $nombresColumnasMaterias debe ser un número entero En cuatrimestre.";
								$MarcarVacias[] = $NColumna . $indiceFila;
							}
						}
					}


				}
			}
		}

		if (!empty($celdasVacias)) {

			// Marcar celdas vacías en el archivo Excel antes de formatear el mensaje
			$archivoModificado = $this->marcarCeldasExcelVacias($nombreDelArchivo, $MarcarVacias, $archivoNombre);
			// Formatear el mensaje con el archivo modificado
			$mensaje = $this->formatearMensaje($celdasVacias, $archivoModificado, $archivoNombre);

			$MarcarVacias = [];

			return $mensaje;
		}

		return '';
	}

	public function cargarArchivo_materias($nombreDelArchivo)
	{

		$this->load->model('/SYSMATER/shared/carga_materias_model');
		$materiasModel = new carga_materias_model;

		$documento = IOFactory::load($nombreDelArchivo);
		$hojaActual = $documento->getActiveSheet();

		$columnas = []; // Arreglo para almacenar los valores de las columnas
		$totalFilas = $hojaActual->getHighestDataRow();

		for ($indiceFila = 2; $indiceFila <= $totalFilas; $indiceFila++) {

			$valorCeldaColumna1 = $hojaActual->getCell(Coordinate::stringFromColumnIndex(1) . $indiceFila)->getValue();
			if (trim($valorCeldaColumna1) === '') {
				continue; // Si la columna 1 está vacía, se salta esta fila
			}

			for ($indiceColumna = 1; $indiceColumna <= 4; $indiceColumna++) {
				// Almacena cada valor de celda en el arreglo
				$valorCelda = $hojaActual->getCell(Coordinate::stringFromColumnIndex($indiceColumna) . $indiceFila)->getValue();

				$columnas[$indiceColumna] = trim($valorCelda);
			}

			$materiasModel->insertarMaterias($columnas[1], $columnas[2], $columnas[3], $columnas[4]);
		}
	}

	public function carga_alumnos()
	{

		if ($this->session->id_tipo == 1) {

			if ($this->input->server('REQUEST_METHOD') === 'POST') {

				if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
					$archivoTmpPath = $_FILES['archivo']['tmp_name'];
					$archivoNombre = $_FILES['archivo']['name'];
					$archivoExtension = pathinfo($archivoNombre, PATHINFO_EXTENSION);

					// Verificar si el archivo es un archivo Excel
					if (in_array($archivoExtension, ['xlsx', 'xls'])) {
						// Llamar a la función para comprobar el archivo
						// Cargar la biblioteca en el método index

						$mensajeError = $this->comprovarArchivo($archivoTmpPath, $archivoNombre);

						if (empty($mensajeError)) {
							// Si no hay errores, cargar el archivo
							$this->cargarArchivo($archivoTmpPath);

							// Eliminar el archivo temporal
							if (file_exists($archivoTmpPath)) {
								unlink($archivoTmpPath);
							}

							// Redirigir a otra página después de ejecutar
							redirect('/sysmater/admin/vista_carga_completa_alumnos');
						} else {
							// Mostrar mensaje de error si hay celdas vacías
							echo nl2br($mensajeError);
						}
					} else {
						// Mensaje de error si el archivo no es Excel
						$this->session->set_flashdata('error', 'Por favor, sube un archivo Excel (.xlsx, .xls)');
					}
				} else {
					// Mensaje de error si no se ha subido un archivo correctamente
					$this->session->set_flashdata('error', 'Error al subir el archivo. Por favor, intenta nuevamente.');
				}
			} else {
				redirect('/sysmater/admin/carga_alumnos');
			}
		}
	}

	// Aquí va la lógica para comprobar y cargar el archivo
	public function comprovarArchivo($nombreDelArchivo, $archivoNombre)
	{
		$documento = IOFactory::load($nombreDelArchivo);
		$hojaActual = $documento->getActiveSheet();
		$totalFilas = $hojaActual->getHighestDataRow();

		// Inicializa un arreglo para almacenar las celdas vacías
		$celdasVacias = [];

		for ($indiceFila = 2; $indiceFila <= $totalFilas; $indiceFila++) {
			// Verifica si la columna 1 está llena
			$valorCeldaColumna1 = $hojaActual->getCell(Coordinate::stringFromColumnIndex(1) . $indiceFila)->getValue();
			if (trim($valorCeldaColumna1) === '') {
				continue; // Si la columna 1 está vacía, se salta esta fila
			}

			// Recorre todas las columnas que necesitas
			for ($indiceColumna = 1; $indiceColumna <= 8; $indiceColumna++) {
				$NColumna = Coordinate::stringFromColumnIndex($indiceColumna);
				// Almacena cada valor de celda en el arreglo
				$valorCelda = $hojaActual->getCell(Coordinate::stringFromColumnIndex($indiceColumna) . $indiceFila)->getValue();

				if ($valorCelda === Null || $valorCelda === '') {
					$nombreColumna = $this->nombresColumnas[$indiceColumna];
					$celdasVacias[] = "Fila $indiceFila, Columna $nombreColumna debe estar llena.";
					$MarcarVacias[] = $NColumna . $indiceFila;
				} else {

					if ($indiceColumna == 1 && strlen(trim($valorCelda)) !== 8) {
						$nombreColumna = $this->nombresColumnas[$indiceColumna];
						$celdasVacias[] = "Fila $indiceFila, Columna $nombreColumna debe tener 8 caracteres.";
						$MarcarVacias[] = $NColumna . $indiceFila;
					}

					if ($indiceColumna == 5 && strlen(trim($valorCelda)) !== 2) {
						$nombreColumna = $this->nombresColumnas[$indiceColumna];
						$celdasVacias[] = "Fila $indiceFila, Columna $nombreColumna debe tener 2 caracteres.";
						$MarcarVacias[] = $NColumna . $indiceFila;
					}

					if ($indiceColumna == 6 && (strlen(trim($valorCelda)) !== 1 && strlen(trim($valorCelda)) !== 2)) {
						$nombreColumna = $this->nombresColumnas[$indiceColumna];
						$celdasVacias[] = "Fila $indiceFila, Columna $nombreColumna debe tener 2 caracteres como 01,02.";
						$MarcarVacias[] = $NColumna . $indiceFila;
					}

					if ($indiceColumna == 7  && (strlen(trim($valorCelda)) !== 1 && strlen(trim($valorCelda)) !== 2)) {
						$nombreColumna = $this->nombresColumnas[$indiceColumna];
						$celdasVacias[] = "Fila $indiceFila, Columna $nombreColumna debe contener solo numero.";
						$MarcarVacias[] = $NColumna . $indiceFila;
					}

					if ($indiceColumna == 8 && strlen(trim($valorCelda)) !== 5) {
						$nombreColumna = $this->nombresColumnas[$indiceColumna];
						$celdasVacias[] = "Fila $indiceFila, Columna $nombreColumna debe contener un periodo de 5 dígitos, como 20242.";
						$MarcarVacias[] = $NColumna . $indiceFila;
					}
				}
			}
		}

		if (!empty($celdasVacias)) {
			// Marcar celdas vacías en el archivo Excel antes de formatear el mensaje
			$archivoModificado = $this->marcarCeldasExcelVacias($nombreDelArchivo, $MarcarVacias, $archivoNombre);
			// Formatear el mensaje con el archivo modificado
			$mensaje = $this->formatearMensaje($celdasVacias, $archivoModificado, $archivoNombre);

			$MarcarVacias = [];

			return $mensaje;
		}

		return '';
	}

	public function cargarArchivo($nombreDelArchivo)
	{

		$this->load->model('/SYSMATER/shared/carga_alumnos_model');
		$alumnosModel = new carga_alumnos_model;

		$documento = IOFactory::load($nombreDelArchivo);
		$hojaActual = $documento->getActiveSheet();

		$columnas = []; // Arreglo para almacenar los valores de las columnas
		$totalFilas = $hojaActual->getHighestDataRow();



		for ($indiceFila = 2; $indiceFila <= $totalFilas; $indiceFila++) {

			$valorCeldaColumna1 = $hojaActual->getCell(Coordinate::stringFromColumnIndex(1) . $indiceFila)->getValue();
			if (trim($valorCeldaColumna1) === '') {
				continue; // Si la columna 1 está vacía, se salta esta fila
			}

			for ($indiceColumna = 1; $indiceColumna <= 8; $indiceColumna++) {
				// Almacena cada valor de celda en el arreglo
				$valorCelda = $hojaActual->getCell(Coordinate::stringFromColumnIndex($indiceColumna) . $indiceFila)->getValue();

				if ($indiceColumna === 6) {
					if (is_numeric($valorCelda)) {
						// Asegurarse de que el número tiene 2 dígitos, rellenando con ceros si es necesario
						$columnas[$indiceColumna] = str_pad($valorCelda, 2, '0', STR_PAD_LEFT);
					} else {
						// Asegurar que el texto tiene 2 caracteres, rellenando con espacios o recortando si es necesario
						$columnas[$indiceColumna] = strlen($valorCelda) > 2 ? substr($valorCelda, 0, 2) : str_pad($valorCelda, 2);
					}
				} else {
					// Para otras columnas, simplemente almacenar el valor trimmado
					$columnas[$indiceColumna] = trim($valorCelda);
				}
			}

			$alumnosModel->insertarAlumno($columnas[1], $columnas[2], $columnas[3], $columnas[4], $columnas[1], 1, $columnas[5], '00',$columnas[6], $columnas[7], $columnas[8],'21');
		}
	}


	public function carga_docentes()
	{

		if ($this->session->id_tipo == 1) {

			if ($this->input->server('REQUEST_METHOD') === 'POST') {

				if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
					$archivoTmpPath = $_FILES['archivo']['tmp_name'];
					$archivoNombre = $_FILES['archivo']['name'];
					$archivoExtension = pathinfo($archivoNombre, PATHINFO_EXTENSION);

					// Verificar si el archivo es un archivo Excel
					if (in_array($archivoExtension, ['xlsx', 'xls'])) {
						// Llamar a la función para comprobar el archivo
						// Cargar la biblioteca en el método index

						$mensajeError = $this->comprovarArchivo_docentes($archivoTmpPath, $archivoNombre);

						if (empty($mensajeError)) {
							// Si no hay errores, cargar el archivo
							$this->cargarArchivo_docentes($archivoTmpPath);

							// Eliminar el archivo temporal
							if (file_exists($archivoTmpPath)) {
								unlink($archivoTmpPath);
							}

							// Redirigir a otra página después de ejecutar
							redirect('/sysmater/admin/vista_carga_completa_docentes');
						} else {
							// Mostrar mensaje de error si hay celdas vacías
							echo nl2br($mensajeError);
						}
					} else {
						// Mensaje de error si el archivo no es Excel
						$this->session->set_flashdata('error', 'Por favor, sube un archivo Excel (.xlsx, .xls)');
					}
				} else {
					// Mensaje de error si no se ha subido un archivo correctamente
					$this->session->set_flashdata('error', 'Error al subir el archivo. Por favor, intenta nuevamente.');
				}
			} else {
				redirect('/sysmater/admin/carga_docentes');
			}
		}
	}

	public function comprovarArchivo_docentes($nombreDelArchivo, $archivoNombre)
	{
		$documento = IOFactory::load($nombreDelArchivo);
		$hojaActual = $documento->getActiveSheet();
		$totalFilas = $hojaActual->getHighestDataRow();

		// Inicializa un arreglo para almacenar las celdas vacías
		$celdasVacias = [];

		for ($indiceFila = 2; $indiceFila <= $totalFilas; $indiceFila++) {
			// Verifica si la columna 1 está llena
			$valorCeldaColumna1 = $hojaActual->getCell(Coordinate::stringFromColumnIndex(1) . $indiceFila)->getValue();
			if (trim($valorCeldaColumna1) === '') {
				continue; // Si la columna 1 está vacía, se salta esta fila
			}

			// Recorre todas las columnas que necesitas
			for ($indiceColumna = 1; $indiceColumna <= 4; $indiceColumna++) {
				$NColumna = Coordinate::stringFromColumnIndex($indiceColumna);
				// Almacena cada valor de celda en el arreglo
				$valorCelda = $hojaActual->getCell(Coordinate::stringFromColumnIndex($indiceColumna) . $indiceFila)->getValue();

				if ($valorCelda === Null || $valorCelda === '') {
					$nombresColumnasDocentes = $this->nombresColumnasDocentes[$indiceColumna];
					$celdasVacias[] = "Fila $indiceFila, Columna $nombresColumnasDocentes debe estar llena.";
					$MarcarVacias = $NColumna . $indiceFila;
				} else {

					if ($indiceColumna == 1) {
						$valorCelda = intval($valorCelda);
						if ( strlen($valorCelda) > 4) {
							$nombresColumnasDocentes = $this->nombresColumnasDocentes[$indiceColumna];
							$celdasVacias[] = "Fila $indiceFila, Columna $nombresColumnasDocentes debe tener hasta 4 dígitos.";
							$MarcarVacias[] = $NColumna . $indiceFila;
						}
					} else {

						if (!ctype_upper($valorCelda)) {
							$nombresColumnasDocentes = $this->nombresColumnasDocentes[$indiceColumna];
							$celdasVacias[] = "Fila $indiceFila, Columna $nombresColumnasDocentes debe tener solo letras mayúsculas.";
							$MarcarVacias[] = $NColumna . $indiceFila;
						}

					}
				}
			}
		}

		if (!empty($celdasVacias)) {

			// Marcar celdas vacías en el archivo Excel antes de formatear el mensaje
			$archivoModificado = $this->marcarCeldasExcelVacias($nombreDelArchivo, $MarcarVacias, $archivoNombre);
			// Formatear el mensaje con el archivo modificado
			$mensaje = $this->formatearMensaje($celdasVacias, $archivoModificado, $archivoNombre);

			$MarcarVacias = [];

			return $mensaje;
		}

		return '';
	}

	public function cargarArchivo_docentes($nombreDelArchivo)
	{

		$this->load->model('/SYSMATER/shared/carga_docentes_model');
		$docentesModel = new carga_docentes_model;

		$documento = IOFactory::load($nombreDelArchivo);
		$hojaActual = $documento->getActiveSheet();

		$columnas = []; // Arreglo para almacenar los valores de las columnas
		$totalFilas = $hojaActual->getHighestDataRow();

		for ($indiceFila = 2; $indiceFila <= $totalFilas; $indiceFila++) {

			$valorCeldaColumna1 = $hojaActual->getCell(Coordinate::stringFromColumnIndex(1) . $indiceFila)->getValue();
			if (trim($valorCeldaColumna1) === '') {
				continue; // Si la columna 1 está vacía, se salta esta fila
			}

			for ($indiceColumna = 1; $indiceColumna <= 4; $indiceColumna++) {
				// Almacena cada valor de celda en el arreglo
				$valorCelda = $hojaActual->getCell(Coordinate::stringFromColumnIndex($indiceColumna) . $indiceFila)->getValue();

				if ($indiceColumna == 1) {

					if (is_numeric($valorCelda)) {
						// Asegurarse de que el número tiene 4 dígitos, rellenando con ceros si es necesario
						$valorCelda = str_pad($valorCelda, 4, '0', STR_PAD_LEFT);
					} else {
						// Asegurar que el texto tiene 4 caracteres, rellenando con espacios ceros si es necesario
						$valorCelda = strlen($valorCelda) > 4 ? substr($valorCelda, 0, 4) : str_pad($valorCelda, 4);
					}
				}
			
				$columnas[$indiceColumna] = trim($valorCelda);
			}

			$docentesModel->insertarDocentes2($columnas[1], $columnas[2], $columnas[3], $columnas[4]);
		}
	}



}// end class
