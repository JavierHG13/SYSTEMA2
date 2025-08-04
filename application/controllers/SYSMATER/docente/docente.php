	<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');

	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\IOFactory;
	use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
	use PhpOffice\PhpSpreadsheet\Style\Fill;


	class Docente extends CI_Controller
	{

		private $MarcarVacias = [];

		private $nombresColumnas = [
			1 => 'chrClvCarrera',
			2 => 'vchClvCuatri',
			3 => 'vchClvMateria',
			4 => 'vchClvTrabajador',
			5 => 'vchPeriodo',
			6 => 'txt_planeamiento',
			7 => 'path_imagen',
			8 => 'int_horas',
			9 => 'int_minutos',
			10 => 'id_nivel',
			11 => 'txt_base',
			12 => 'path_imagen_base',
			13 => 'nvch_opcionA',
			14 => 'path_imagenA',
			15 => 'nvch_argumentaA',
			16 => 'nvch_opcionB',
			17 => 'path_imagenB',
			18 => 'nvch_argumentaB',
			19 => 'nvch_opcionC',
			20 => 'path_imagenC',
			21 => 'nvch_argumentaC',
			22 => 'nvch_opcionD',
			23 => 'path_imagenD',
			24 => 'nvch_argumentaD',
			25 => 'chr_correcto',
			26 => 'vch_bibliografia'
		];

		public function __construct()
		{
			parent::__construct();
		}

		public function index()
		{
			if ($this->session->id_tipo == 4) {
				$this->load->view("templates/views_sysmater/docente_views/docente_navbar_view");
				$this->load->view("templates/shared/home_sysmater_view");
				$this->load->view("templates/shared/footer_sysmater_view");
			} else {
				$message = "Verifique que el usuario y contraseña sean correctos ";
				$status_code = 401;
				show_error($message, $status_code, $heading = 'Sesión no iniciada');
			}
		} // end function
		//////////////////////////////////////////////



		public function lista_reactivos()
		{
			redirect("/sysmater/docente/lista_reactivos");
		}
		public function nuevo_reactivo()
		{
			redirect("/sysmater/docente/nuevo_reactivo");
		}
		public function elimina_reactivo()
		{
			$id = $this->uri->segment($this->uri->total_segments());
			redirect("/sysmater/docente/elimina_reactivo/index/" . $id);
		}
		public function edita_reactivo()
		{
			$id = $this->uri->segment($this->uri->total_segments());
			redirect("/sysmater/docente/edita_reactivo/index/" . $id);
		}
		public function ver_reactivo()
		{
			$id = $this->uri->segment($this->uri->total_segments());
			redirect("/sysmater/docente/ver_reactivo/index/" . $id);
		}
		public function subir_lote()
		{
			redirect("/sysmater/docente/subir_lote");
		}

		///////////////////////////////////////////////////////////////

		public function examenes_registrados()
		{
			redirect("/sysmater/docente/examenes_registrados");
		}
		public function examen_grupos()
		{
			$id = $this->uri->segment($this->uri->total_segments());
			redirect("/sysmater/docente/examen_grupos/index/" . $id);
		}
		public function progreso_examen_grupo()
		{
			$id_examen = $this->uri->segment(5);
			$grupo = $this->uri->segment(6);

			redirect("/sysmater/docente/progreso_examen_grupo/index/{$id_examen}/{$grupo}");
		}
		public function nuevo_examen()
		{
			redirect("/sysmater/docente/nuevo_examen");
		}
		public function elimina_examen()
		{
			$id = $this->uri->segment($this->uri->total_segments());
			redirect("/sysmater/docente/elimina_examen/index/" . $id);
		}
		public function edita_examen()
		{
			$id = $this->uri->segment($this->uri->total_segments());
			redirect("/sysmater/docente/edita_examen/index/" . $id);
		}
		public function editar_reactivos_examen()
		{
			$id = $this->uri->segment($this->uri->total_segments());
			redirect("/sysmater/docente/editar_reactivos_examen/index/" . $id);
		}
		public function cargar_reactivos_examen()
		{
			$id = $this->uri->segment($this->uri->total_segments());
			redirect("/sysmater/docente/cargar_reactivos_examen/index/" . $id);
		}
		public function reactivos_examen()
		{
			$id = $this->uri->segment($this->uri->total_segments());
			redirect("/sysmater/docente/reactivos_examen/index/" . $id);
		}
		public function ver_examen()
		{
			$id = $this->uri->segment($this->uri->total_segments());
			redirect("/sysmater/docente/ver_examen/index/" . $id);
		}
		public function examenes_programados()
		{
			redirect("/sysmater/docente/examenes_programados");
		}
		public function programar_examen_grupo()
		{
			$id_examen = $this->uri->segment(5);
			$grupo = $this->uri->segment(6);

			redirect("/sysmater/docente/programar_examen_grupo/index/{$id_examen}/{$grupo}");
		}
		//extraa
		public function guardar_programacion()
		{
			$id_examen = $this->uri->segment(5);
			$grupo = $this->uri->segment(6);

			redirect("/sysmater/docente/guardar_programacion/index/{$id_examen}/{$grupo}");
		}
		public function guardar_edicion_programacion()
		{
			$id_examen = $this->uri->segment(5);
			$grupo = $this->uri->segment(6);

			redirect("/sysmater/docente/guardar_edicion_programacion/index/{$id_examen}/{$grupo}");
		}
		public function programar_examen()
		{
			redirect('/sysmater/docente/programar_examen');
		}
		public function examenes_pendientes()
		{
			redirect('/sysmater/docente/examenes_pendientes');
		}
		public function elimina_programacion()
		{
			$id_examen = $this->uri->segment(5);
			$grupo = $this->uri->segment(6);

			redirect("/sysmater/docente/elimina_programacion/index/{$id_examen}/{$grupo}");
		}

		public function edita_programacion()
		{
			$id_examen = $this->uri->segment(5);
			$grupo = $this->uri->segment(6);

			redirect("/sysmater/docente/edita_programacion/index/{$id_examen}/{$grupo}");
		}

		public function progreso_examen()
		{
			$id = $this->uri->segment($this->uri->total_segments());
			redirect("/sysmater/docente/progreso_examen/index/" . $id);
		}

		public function reporte_examen()
		{
			redirect('/sysmater/docente/reporte_examen');
		}

		public function reporte_examen_parciales()
	{
		redirect('/sysmater/docente/reporte_examen_parciales');
	}


	//! Aqui van funciones relacionado a la parte de las activifdades*/
	public function ver_materias()
	{
		redirect('./sysmater/docente/ver_materias');
	}

	public function programar_actividad()
	{
		redirect('./sysmater/docente/nueva_actividad');
	}

	public function gestionar_materia()
	{
		$vchClvMateria = $this->uri->segment($this->uri->total_segments());

		redirect("sysmater/docente/gestionar_materia/index/" . $vchClvMateria);
	}

	public function ver_actividades($id_grupo = null, $vchClvMateria = null)
	{
		if ($id_grupo && $vchClvMateria) {
			redirect("sysmater/docente/actividades/index/$id_grupo/$vchClvMateria");
		} else {
			show_error("Faltan parámetros en la URL", 400);
		}
	}

	public function gestionar_equipos($id_grupo = null, $vchClvMateria = null)
	{

		if ($id_grupo && $vchClvMateria) {
			redirect("sysmater/docente/equipos/index/$id_grupo/$vchClvMateria");
		} else {
			show_error("Faltan parámetros en la URL", 400);
		}
	}



	public function ver_alumnos()
	{

		$id_actividad = $this->uri->segment(5); 
		$id_grupo     = $this->uri->segment(6);


		redirect("./sysmater/docente/ver_alumnos/index/" . $id_actividad . '/'. $id_grupo);
	}

	//Esto es para ver a los equipos de una actividad en equipo
	public function ver_equipo($id_grupo = null, $id_actividad = null)
	{
		if ($this->session->id_tipo == 4) {

			if (!$id_grupo || !$id_actividad) show_error('Faltan parametros');

			redirect("sysmater/docente/actividades/ver_equipo/" . $id_grupo . '/' . $id_actividad);
		}
	}

	public function integrantes_equipo($id_grupo = null, $id_equipo = null)
	{
		if ($this->session->id_tipo == 4) {
			if (!$id_grupo ||  !$id_equipo) show_error('ID grupo requerido');

			redirect("sysmater/docente/actividades/integrantes_equipo/" . $id_grupo . '/' . $id_equipo);
		}
	}



	//Esto es para la parte de administar los equipos
	public function ver_equipos()
	{
		$id_grupo = $this->uri->segment($this->uri->total_segments());
		redirect("./sysmater/docente/equipos/index/" . $id_grupo);
	}

	public function calificar_actividad()
	{
		$id_actividad  = $this->uri->segment($this->uri->total_segments() - 1);
		$vchMatricula = $this->uri->segment($this->uri->total_segments());

		// Puedes pasarlos por redirect, cargar vista o modelo, etc.
		redirect("./sysmater/docente/calificar_actividad/index/"  . $id_actividad . '/' . $vchMatricula);
	}

	public function calificar_actividad_equipo($id_actividad_equipo = null)
	{
		if ($id_actividad_equipo === null) {
			show_error('ID de actividad de equipo no proporcionado', 400);
			return;
		}

		redirect("sysmater/docente/calificar_actividad/calificar_equipo/" . $id_actividad_equipo);
	}

	public function gestionar_esquema()
	{
		$vchClvMateria  = $this->uri->segment($this->uri->total_segments());

		// Puedes pasarlos por redirect, cargar vista o modelo, etc.
		redirect("./sysmater/docente/gestionar_materia/esquema/"  . $vchClvMateria);
	}
	public function ver_instrumentos()
	{
		redirect('./sysmater/docente/ver_instrumentos');
	}

	public function detalles_instrumento()
	{
		$id_instrumento = $this->uri->segment($this->uri->total_segments());

		redirect('./sysmater/docente/ver_instrumentos/detalles_instrumento/' . $id_instrumento);
	}


	public function crear_rubrica()
	{
		redirect('./sysmater/docente/crear_instrumento');
	}

	public function editar_actividad($id_actividad = null)
	{
		if ($id_actividad === null) {
			show_error('ID de la actividad no proporcionado', 400);
			return;
		}


		redirect("sysmater/docente/actividades/editar/" . $id_actividad);
	}


	public function eliminar_actividad($id_actividad = null, $id_grupo = null, $vchClvMateria = null)
	{
		if ($id_actividad === null || $id_grupo === null || $vchClvMateria === null) {
			show_error('ID de la actividad no proporcionado', 400);
			return;
		}


		redirect("sysmater/docente/actividades/eliminar/" . $id_actividad . '/' . $id_grupo . '/' . $vchClvMateria);
	}


	public function eliminar_instrumento($id_instrumento = null)
	{
		if ($id_instrumento === null) {
			show_error('ID de la actividad no proporcionado', 400);
			return;
		}


		redirect("sysmater/docente/ver_instrumentos/eliminar/" . $id_instrumento);
	}


	public function reportes_actividades()
	{

		redirect('./sysmater/docente/reporte_actividades');
	}


		///////////////////////////////////////////////////////////////
		public function ajax_llena_cuatrimestres()
		{
			$this->load->model('/sysmater/shared/cuatrimestre_model');
			if ($this->input->post('carrera')) {
				$carrera = $this->input->post('carrera');
				$docente = $this->session->Matricula;
				$periodo = get_periodo();

				$cuatrimestres = $this->cuatrimestre_model->lista_cuatrimestres_docente($carrera, $docente, $periodo);
				if ($cuatrimestres != NULL) {
					foreach ($cuatrimestres->result() as $fila) {
	?>
						<option value="<?= $fila->vchClvCuatri; ?>"><?= $fila->vchNomCuatri; ?></option>
					<?php
					}
				}
			}
		}
		public function ajax_llena_materias()
		{
			$this->load->model('/sysmater/shared/materia_model');
			if ($this->input->post('carrera')) {
				$carrera = $this->input->post('carrera');
				$cuatrimestre = $this->input->post('cuatrimestre');
				$docente = $this->session->Matricula;
				$periodo = get_periodo();

				$materias = $this->materia_model->lista_materias_docente($cuatrimestre, $carrera, $docente, $periodo);
				if ($materias != NULL) {
					foreach ($materias->result() as $fila) {
					?>
						<option value="<?= $fila->vchClvMateria; ?>"><?= $fila->vchNomMateria; ?></option>
	<?php
					}
				}
			}
		}



		/*AJAX*/
		public function ajax_reactivos_examen()
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
		public function ajax_reactivos_carrera_materia()
		{
			$path_images = 'uploads/';
			$this->load->model('/SYSMATER/shared/reactivo_model');
			if ($this->input->post('examen')) {
				$examen = $this->input->post('examen');
				$materia = $this->input->post('materia');
				$reactivos = $this->reactivo_model->reactivos_carrera_materia_json($materia, $examen);
				if ($reactivos != NULL) {
					$i = 1;
					echo "	<div class='panel-group'>";
					foreach ($reactivos->result() as $fila) {
						$id_main = $fila->id_reactivo_main;
						echo "	<div class='panel panel-success'>";
						echo "		<div class='panel-heading sinpadding'>";
						echo "			<input type='checkbox' id='chk_select' name='chk_select[]' value='" . $id_main . "'>";
						echo "			<input type='hidden' id='nSubreactivos' name='nSubreactivos[]' value='" . $fila->nReactivos . "'>";
						echo "			#" . $i . "- " . $fila->vchClvCuatrimestre . "-" . $fila->vchNomMateria;
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
		public function ajax_reactivos_compartidos()
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

		public function vista_carga_completa()
		{
			redirect('/sysmater/docente/vista_carga_completa');
		}

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

		public function carga_reactivos_examen()
		{
			if ($this->session->id_tipo == 4) {

				if ($this->input->server('REQUEST_METHOD') === 'POST') {

					// Imprimir el valor del JSON recibido para revisar su formato
					if (isset($_POST['nombresImagenes'])) {
					
						// Decodificar el JSON
						$nombresImagenes = json_decode($_POST['nombresImagenes'], true);
						
					}


					if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
						$archivoTmpPath = $_FILES['archivo']['tmp_name'];
						$archivoNombre = $_FILES['archivo']['name'];
						$archivoExtension = pathinfo($archivoNombre, PATHINFO_EXTENSION);

						// Verificar si el archivo es un archivo Excel
						if (in_array($archivoExtension, ['xlsx', 'xls'])) {
							// Llamar a la función para comprobar el archivo
							// Cargar la biblioteca en el método index

							$mensajeError = $this->comprovarArchivo($archivoTmpPath, $archivoNombre, $nombresImagenes);

							if (empty($mensajeError)) {
								// Si no hay errores, cargar el archivo
								$this->cargarArchivo($archivoTmpPath);

								// Eliminar el archivo temporal
								if (file_exists($archivoTmpPath)) {
									unlink($archivoTmpPath);
								}

								// Redirigir a otra página después de ejecutar
								redirect('/sysmater/docente/vista_carga_completa');
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
					redirect('/sysmater/docente/carga_reactivos_examenD');
				}
			}
		}

		// Aquí va la lógica para comprobar y cargar el archivo
		
		public function comprovarArchivo($nombreDelArchivo, $archivoNombre, $nombresImagenes)
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
				for ($indiceColumna = 1; $indiceColumna <= 26; $indiceColumna++) {
					$NColumna = Coordinate::stringFromColumnIndex($indiceColumna);
					// Almacena cada valor de celda en el arreglo
					$valorCelda = $hojaActual->getCell(Coordinate::stringFromColumnIndex($indiceColumna) . $indiceFila)->getValue();
					$valorCeldaParSiguiente = null;
					$esParEspecial = false;

					if ($valorCelda !== null && in_array($indiceColumna, [7, 12, 14, 17, 20, 23])) {

						// Verificar si $valorCelda está en el arreglo $nombresImagenes
						$encontrado = array_filter($nombresImagenes, function($subArray) use ($valorCelda) {
							return in_array($valorCelda, $subArray);
						});
						
						if (empty($encontrado)){
							$celdasVacias[] = "La imagen '$valorCelda' no se encuentra entre las imágenes proparcionadas.";
						}
						
					}

					if ($valorCelda === null && !in_array($indiceColumna, [6, 7, 12, 14, 17, 20, 23, 26])) {

						// Verificación de pares de columnas específicas
						if (in_array($indiceColumna, [11, 13, 16, 19, 22])) {
							$valorCeldaParSiguiente = trim($hojaActual->getCell(Coordinate::stringFromColumnIndex($indiceColumna + 1) . $indiceFila)->getValue());
							$esParEspecial = true;
						}

						// Si es una columna de par especial, verificar las condiciones específicas
						if ($esParEspecial) {
							if ($valorCeldaParSiguiente === '') {
								$nombreColumna1 = $this->nombresColumnas[$indiceColumna];
								$nombreColumna2 = $this->nombresColumnas[$indiceColumna + 1];
								$celdasVacias[] = "Fila $indiceFila, Columna $nombreColumna1 y Columna $nombreColumna2 no pueden estar vacías al mismo tiempo.";
								$MarcarVacias[] = $NColumna . $indiceFila;
							} elseif ($valorCeldaParSiguiente !== '' && !preg_match('/\.(jpg|png)$/i', $valorCeldaParSiguiente)) {
								$nombreColumna2 = $this->nombresColumnas[$indiceColumna + 1];
								$celdasVacias[] = "Fila $indiceFila, Columna $nombreColumna2 debe contener una imagen válida (jpg o png).";
								$MarcarVacias[] = $NColumna . $indiceFila;
							}
						} else {
							$nombreColumna = $this->nombresColumnas[$indiceColumna];
							$celdasVacias[] = "Fila $indiceFila, Columna $nombreColumna, esta vacia.";
							$MarcarVacias[] = $NColumna . $indiceFila;
						}
					}

					if (in_array($indiceColumna, [1, 2, 4, 5, 8, 9, 10]) && !ctype_digit($valorCelda) && $valorCelda !== '0') {
						// Si el valor es numérico pero no es un entero
						if (is_numeric($valorCelda) && (float)$valorCelda != (int)$valorCelda) {
							$nombreColumna = $this->nombresColumnas[$indiceColumna];
							$celdasVacias[] = "Fila $indiceFila, Columna $nombreColumna, solo números enteros.";
							$MarcarVacias[] = $NColumna . $indiceFila;
						}
					}

					if ($indiceColumna == 3) {
						if (strlen($valorCelda) > 6) {
							$nombreColumna = $this->nombresColumnas[$indiceColumna];
							$celdasVacias[] = "Fila $indiceFila, Columna $nombreColumna debe contener 6 caracteres o menos.";
							$MarcarVacias[] = $NColumna . $indiceFila;
						}
					}

					// Verificación de pares de columnas específicas
					if (in_array($indiceColumna, [11, 13, 16, 19, 22]) && trim($valorCelda) === '') {
						$valorCeldaParSiguiente = trim($hojaActual->getCell(Coordinate::stringFromColumnIndex($indiceColumna + 1) . $indiceFila)->getValue());
						$esParEspecial = true;
					}

					// Si es una columna de par especial, verificar las condiciones específicas
					if ($esParEspecial) {
						if (trim($valorCelda) === '' && $valorCeldaParSiguiente === '') {
							$nombreColumna1 = $this->nombresColumnas[$indiceColumna];
							$nombreColumna2 = $this->nombresColumnas[$indiceColumna + 1];
							$celdasVacias[] = "Fila $indiceFila, Columna $nombreColumna1 y Columna $nombreColumna2 no pueden estar vacías al mismo tiempo.";
							$MarcarVacias[] = $NColumna . $indiceFila;
							$MarcarVacias[] = Coordinate::columnIndexFromString($NColumna) + 1  . $indiceFila;
						} elseif ($valorCeldaParSiguiente !== '' && !preg_match('/\.(jpg|png)$/i', $valorCeldaParSiguiente)) {
							$nombreColumna2 = $this->nombresColumnas[$indiceColumna + 1];
							$celdasVacias[] = "Fila $indiceFila, Columna $nombreColumna2 debe contener una imagen válida (jpg o png).";
							$MarcarVacias[] = $NColumna . $indiceFila;
						}
					}

					// Verificación de columnas que deben contener imágenes válidas
					if (in_array($indiceColumna, [12, 14, 17, 20, 23])) {
						if ($valorCelda !== null && !preg_match('/\.(jpg|png)$/i', $valorCelda)) {
							$nombreColumna = $this->nombresColumnas[$indiceColumna];
							$celdasVacias[] = "Fila $indiceFila, Columna $nombreColumna debe contener una imagen válida (jpg o png).";
							$MarcarVacias[] = $NColumna . $indiceFila;
						}
					}

					// Verificación para la columna 25 que debe estar llena y contener un carácter en mayúscula sin espacios
					if ($indiceColumna == 25) {
						if ($valorCelda === null || trim($valorCelda) === '') {
							$nombreColumna = $this->nombresColumnas[$indiceColumna];
							$celdasVacias[] = "Fila $indiceFila, Columna $nombreColumna debe estar llena.";
							$MarcarVacias[] = $NColumna . $indiceFila;
						} elseif (!preg_match('/^[A-D]$/', $valorCelda)) {
							$nombreColumna = $this->nombresColumnas[$indiceColumna];
							$celdasVacias[] = "Fila $indiceFila, Columna $nombreColumna debe contener un único carácter en mayúscula sin espacios de A - D.";
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
			$this->load->model('/SYSMATER/shared/carga_reactivos_model');
			$preguntaModel = new carga_reactivos_model;

			$documento = IOFactory::load($nombreDelArchivo);
			$hojaActual = $documento->getActiveSheet();

			$columnas = []; // Arreglo para almacenar los valores de las columnas
			$totalFilas = $hojaActual->getHighestDataRow();
			$id_compuesta = 0;
			$bandera = false;

			function formatearCeldas($valorCelda, $indiceColumna)
			{
				// Eliminar espacios en blanco al principio y al final del valor original
				$valorCelda = trim($valorCelda);

				switch ($indiceColumna) {
					case 2: // vchClvCuatri
						if (is_numeric($valorCelda)) {
							// Asegurarse de que el número tiene 2 dígitos, rellenando con ceros si es necesario
							return str_pad($valorCelda, 2, '0', STR_PAD_LEFT);
						} else {
							// Asegurar que el texto tiene 2 caracteres, rellenando con espacios o recortando si es necesario
							return strlen($valorCelda) > 2 ? substr($valorCelda, 0, 2) : str_pad($valorCelda, 2);
						}
					case 4: // vchClvTrabajador
						if (is_numeric($valorCelda)) {
							// Asegurarse de que el número tiene 4 dígitos, rellenando con ceros si es necesario
							return str_pad($valorCelda, 4, '0', STR_PAD_LEFT);
						} else {
							// Asegurar que el texto tiene 4 caracteres, rellenando con espacios o recortando si es necesario
							return strlen($valorCelda) > 4 ? substr($valorCelda, 0, 4) : str_pad($valorCelda, 4);
						}
					case 7:  // path_imagen
					case 12: // path_imagen_base
					case 14: // path_imagenA
					case 17: // path_imagenB
					case 20: // path_imagenC
					case 23: // path_imagenD
					case 25: // chr_correcto
						// Asegurarse de que no hay espacios en blanco
						return str_replace(' ', '', $valorCelda);
					default:
						// Devolver el valor tal cual para las demás columnas
						return $valorCelda;
				}
			}

			for ($indiceFila = 2; $indiceFila <=  $totalFilas; $indiceFila++) {

				for ($indiceColumna = 1; $indiceColumna <= 26; $indiceColumna++) {
					// Almacena cada valor de celda en el arreglo
					$valorCelda = $hojaActual->getCell(Coordinate::stringFromColumnIndex($indiceColumna) . $indiceFila)->getValue();

					// Formatear el valor de la celda según su columna
					$valorCeldaFormateado = formatearCeldas($valorCelda, $indiceColumna);

					// Ahora puedes asignar este valor limpio de espacios a la columna correspondiente
					$columnas[$indiceColumna] = $valorCeldaFormateado === '' ? ' ' : $valorCeldaFormateado;
				}

				if ($columnas[1] !== ' ') {
					// Ahora puedes usar $columnas[$i] donde i es el índice de la columna
					$valor = strtolower(trim($columnas[6]));

					if ($valor === 'simple' || $valor === '' && !$bandera) {
						// Si es 'simple' o un espacio en blanco, tratamos como una sola pregunta.
						$bandera = false; // Desactivamos la bandera por si acaso estaba activa

						$id_primera_insercion = $preguntaModel->insertarPreguntaTipo(' ', $columnas[1], $columnas[2], $columnas[3], $columnas[7], $columnas[4], 1, $columnas[5]);

						if ($id_primera_insercion) {
							$preguntaModel->insertarReactivosDetallados(
								$id_primera_insercion,
								$columnas[8],
								$columnas[9],
								$columnas[10],
								$columnas[11],
								$columnas[13],
								$columnas[15],
								$columnas[14],
								$columnas[16],
								$columnas[18],
								$columnas[17],
								$columnas[19],
								$columnas[21],
								$columnas[20],
								$columnas[22],
								$columnas[24],
								$columnas[23],
								$columnas[25],
								$columnas[26],
								$columnas[12],
								$columnas[4],
								1
							);
						}

						// Aquí iría el código para manejar la pregunta simple
						//echo "simple" . '<br/>';

					} elseif (!$bandera && $valor !== '') {
						// Si no está activada la bandera y no es una cadena vacía, activamos la bandera
						$bandera = true;

						// Inserta la primera pregunta tipo
						$id_primera_insercion_compuesta = $preguntaModel->insertarPreguntaTipo($columnas[6], $columnas[1], $columnas[2], $columnas[3], $columnas[7], $columnas[4], 1, $columnas[5]);

						if ($id_primera_insercion_compuesta) {
							$preguntaModel->insertarReactivosDetallados(
								$id_primera_insercion_compuesta,
								$columnas[8],
								$columnas[9],
								$columnas[10],
								$columnas[11],
								$columnas[13],
								$columnas[15],
								$columnas[14],
								$columnas[16],
								$columnas[18],
								$columnas[17],
								$columnas[19],
								$columnas[21],
								$columnas[20],
								$columnas[22],
								$columnas[24],
								$columnas[23],
								$columnas[25],
								$columnas[26],
								$columnas[12],
								$columnas[4],
								1
							);

							$id_compuesta = $id_primera_insercion_compuesta;
						}

						// Aquí iría el código para manejar el inicio de una pregunta compuesta
						//echo "compuesta ".$valor .'<br/>';

					} elseif ($bandera && $valor !== '') {
						// Si no está activada la bandera y no es una cadena vacía, desactivamos la bandera
						$bandera = false;

						// Inserta la primera pregunta tipo
						$id_primera_insercion_compuesta = $preguntaModel->insertarPreguntaTipo($columnas[6], $columnas[1], $columnas[2], $columnas[3], $columnas[7], $columnas[4], 1, $columnas[5]);

						if ($id_primera_insercion_compuesta) {
							$preguntaModel->insertarReactivosDetallados(
								$id_primera_insercion_compuesta,
								$columnas[8],
								$columnas[9],
								$columnas[10],
								$columnas[11],
								$columnas[13],
								$columnas[15],
								$columnas[14],
								$columnas[16],
								$columnas[18],
								$columnas[17],
								$columnas[19],
								$columnas[21],
								$columnas[20],
								$columnas[22],
								$columnas[24],
								$columnas[23],
								$columnas[25],
								$columnas[26],
								$columnas[12],
								$columnas[4],
								1
							);

							$id_compuesta = $id_primera_insercion_compuesta;
						}

						// Aquí iría el código para manejar el inicio de una pregunta compuesta
						//echo "compuesta ".$valor .'<br/>';

					} elseif ($bandera && $valor === '') {
						// Si la bandera está activada y encontramos una cadena vacía, continuamos con la pregunta compuesta

						if ($id_compuesta) {

							$preguntaModel->insertarReactivosDetallados(
								$id_compuesta,
								$columnas[8],
								$columnas[9],
								$columnas[10],
								$columnas[11],
								$columnas[13],
								$columnas[15],
								$columnas[14],
								$columnas[16],
								$columnas[18],
								$columnas[17],
								$columnas[19],
								$columnas[21],
								$columnas[20],
								$columnas[22],
								$columnas[24],
								$columnas[23],
								$columnas[25],
								$columnas[26],
								$columnas[12],
								$columnas[4],
								1
							);
						}
						// Aquí iría el código para manejar la continuación de una pregunta compuesta
						//echo "Continuación" . '<br/>';

					} else {
						// Si la bandera está activada y encontramos algo diferente a un espacio, terminamos la pregunta compuesta
						$bandera = false;
						// Aquí iría el código para cerrar la pregunta compuesta
						//echo "Fin" .$valor . '.<br/>';
					}
				}
			}
		}

		public function cargarImagenesReactivos()
		{
			if ($this->session->id_tipo == 4) {
				if ($_SERVER['REQUEST_METHOD'] === 'POST') {
					$nombresImagenes = [];
					$errores = [];
					$mensajes = [];
					$rutaDestino = $_SERVER['DOCUMENT_ROOT'] . '/SYSTEMA/uploads/';

					// Verifica si se han subido archivos
					if (isset($_FILES['imagenes']) && $_FILES['imagenes']['error'][0] == UPLOAD_ERR_OK) {
						// Itera sobre cada archivo subido
						foreach ($_FILES['imagenes']['name'] as $key => $nombreImagen) {
							// Extrae la extensión
							$extension = pathinfo($nombreImagen, PATHINFO_EXTENSION);

							// Valida la extensión y el nombre del archivo
							if (in_array($extension, ['png', 'jpg', 'jpeg'])) {
								// Elimina espacios en blanco del nombre
								$nombreImagenLimpio = preg_replace('/\s+/', '', $nombreImagen);

								$nombresImagenes[] = [
									'nombre_original' => $nombreImagen,
									'nombre_limpio' => $nombreImagenLimpio,
									'tmp_name' => $_FILES['imagenes']['tmp_name'][$key]
								];
								$nombresImagenes2[] = [
									$nombreImagenLimpio
								];

							} else {
								$errores[] = "La imagen '$nombreImagen' no es una imagen válida (debe ser PNG o JPG).";
							}
						}
						
						// Si no hay errores, guarda las imágenes
						if (empty($errores)) {

							foreach ($nombresImagenes as $imagen) {
								$rutaImagen = $rutaDestino . $imagen['nombre_limpio'];

								if (move_uploaded_file($imagen['tmp_name'], $rutaImagen)) {

									if ($imagen['nombre_original'] !== $imagen['nombre_limpio']) {
										$mensajes[] = "La imagen '$nombreImagen' contiene espacios en blanco se guardo con el nombre $nombreImagenLimpio";
									} else {
										$mensajes[] = "Imagen guardada: " . $imagen['nombre_limpio'];
									}
								} else {
									$mensajes[] = "Error al guardar la imagen: " . $imagen['nombre_original'];
								}
							}

							$titulo = 'Resultados De La Carga Completada De:';

							$mensajeAlerta = $this->formatearMensajeImagenes($nombresImagenes2, $mensajes, $titulo, 0);
							echo nl2br($mensajeAlerta);
						} else {

							$titulo = 'Las Siguietes Imagenes Tienen Problemas:';

							$mensajeError = $this->formatearMensajeImagenes(0, $errores, $titulo, 1);
							echo nl2br($mensajeError);
						}
					} else {
						echo "No se han subido imágenes.";
					}
				} else {
					redirect('/sysmater/docente/cargarImagenesReactivos');
				}
			}
		}
		
		private function formatearMensajeImagenes($nombresImagenes, $celdasVacias, $titulo, $opcion)
		{

			if ($opcion == 1) {
				$imagen = '<img src="' . base_url('assets/animationS/advertencia.png') . '" alt="Imagen" style="width: 140px; height: 140px; margin: 0 auto 20px; display: block;">';
			} else {
				$imagen = '<img src="' . base_url('assets/animationS/CargaCompleta.png') . '" alt="Imagen" style="width: 140px; height: 140px; margin: 0 auto 20px; display: block;">';
				$nombresImagenes = serialize($nombresImagenes);
			}

			$mensaje = '<div style="font-family: \'Nunito\', sans-serif; color: #f1fcf9; padding: 20px; display: flex; justify-content: center;">';
			$mensaje .= '<div style="max-width: 400px; width: 100%; background: #1bb190; padding: 40px; border-radius: 8px; box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);">';
			$mensaje .= '<h2 style="font-size: 1.5em; font-weight: bold; text-align: center; margin-bottom: 20px;">' . $titulo . ' ' . count($celdasVacias) . '</h2>';
			$mensaje .= '<ul style="list-style-type: none; padding-left: 0; margin: 0;">';
			$mensaje .= $imagen;

			foreach ($celdasVacias as $celda) {
				$mensaje .= '<li style="background: #c0f2d4; margin: 10px 0; padding: 15px; border: 1px solid #ddd; border-radius: 18px; text-align: center; color: black; transition: transform 0.2s ease-in-out;" onmouseover="this.style.transform=\'scale(1.05)\';" onmouseout="this.style.transform=\'scale(1)\';">' . htmlspecialchars($celda) . '</li>';
			}

			$mensaje .= '</ul>';
			// Botón de regresar
			$mensaje .= '<div style="text-align: center; margin-top: 20px;">';

			if ($opcion == 1) {
				$mensaje .= '<button onclick="history.back()" style="padding: 10px 20px; background-color: #0e776a; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">Regresar</button>';
			} else {
				$mensaje .= '<form id="redirectForm" method="post" action="' . site_url('/sysmater/docente/carga_reactivos_examenD') . '">';
				$mensaje .= '<input type="hidden" name="nombresImagenes" value="'.htmlspecialchars($nombresImagenes, ENT_QUOTES, 'UTF-8').'">';
				$mensaje .= '<button type="submit" style="padding: 10px 20px; background-color: #0e776a; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">Continuar</button>';
				$mensaje .= '</form>';
			}

			$mensaje .= '</div>';

			$mensaje .= '</div>';
			$mensaje .= '</div>';

			return $mensaje;
		}

	}// end class