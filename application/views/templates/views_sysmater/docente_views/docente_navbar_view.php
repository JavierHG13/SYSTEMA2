<!-- docente navbar -->
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" type="image/x-icon" href="<?= base_url() ?>assets/img/logo_2_SysGED.ico" />
	<title>EXAMEN GENERAL DE EGRESO DE LICENCIATURA</title>

	<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/jquery-ui/jquery-ui.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/navbar.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">

	<script src="<?= base_url() ?>assets/js/jquery-3.1.1.min.js"></script>
	<script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
	<script src="<?= base_url() ?>assets/jquery-ui/jquery-ui.js"></script>
	<script src="<?= base_url() ?>assets/js/tinymce/tinymce.min.js"></script>
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>

	<script src="<?= base_url() ?>assets/js/metodos_globales.js"></script>
	<script type="text/javascript">
		base_url = "<?php echo base_url(); ?>";
	</script>
	<style>
		.navbar-nav > li.dropdown .dropdown-menu {
			display: block;
			opacity: 0;
			visibility: hidden;
			transform: translateY(10px);
			transition: all 0.2s ease;
			position: absolute;
		}

		.navbar-nav > li.dropdown:hover .dropdown-menu {
			opacity: 1;
			visibility: visible;
			transform: translateY(0);
			z-index: 1000;
		}
		.color:hover {
			color: red;
			text-decoration: underline;
			background-color: #f0f0f0;
		}

	</style>
</head>

<body id="body">
	<!--VENTANAS-->
	<!--ventana_peril-->
	<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="ventana_peril" style="display: none;">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<?php
				$attributes = array(
					"id" => "form_passwd",
					"name" => "form_passwd",
					"method" => "POST"
				);
				?>
				<?= form_open("/login/cambia_password/", $attributes);  ?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
					<h4 class="modal-title" id="mySmallModalLabel">Cambio de contraseña </h4>
				</div>
				<div class="modal-body">
					<fieldset>
						<div class="form-group">
							<label class="control-label" for="username">No. Trabajador</label>
							<input class="form-control" placeholder="Usuario" name="username" id="username" type="text" value="<?php echo $this->session->Matricula; ?>" readonly required>
						</div>
						<div class="form-group">
							<label class="control-label" for="old_password">Contrase&ntilde;a anterior</label>
							<input class="form-control" placeholder="Contrase&ntilde;a" name="old_password" id="old_password" type="password" value="" required maxlength="8">
						</div>
						<div class="form-group">
							<label class="control-label" for="password">Nueva Contrase&ntilde;a</label>
							<input class="form-control" placeholder="Contrase&ntilde;a" name="password" id="password" type="password" value="" required maxlength="8">
						</div>
						<div class="campos-ocultos">
							<input type="hidden" name="task" id="task" value="" />
							<input type="hidden" id="type_of_user" name="type_of_user" value="<?php echo $this->session->id_tipo; ?>" />
						</div>
					</fieldset>
				</div>
				<div class="modal-footer">
					<button class="btn btn-default" type="button" data-dismiss="modal" id="cmd_cerrar">Cancelar</button>
					<button class="btn btn-primary" type="button" id="cmd_guardar">Aceptar</button>
				</div>
				<?= form_close(); ?>
			</div>
		</div>
	</div>
	<!-- <div class="container" id="contenedor"> -->
	<div class="" style="padding:20px;" id="contenedor">

		<header id="header">
			<center>
				<img id="img_uthh" class="img-responsive" src="<?= base_url() ?>assets/img/logo_uthh_completo_small.png">
			</center>
		</header>
		<div>
			<nav class="navbar navbar-default">
				<div class="container-fluid">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-1">
							<span class="sr-only">Menu</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="<?= site_url('sysmater/docente/docente') ?>">
							Docente
						</a>
					</div>
					<div class="collpse navbar-collapse" id="navbar-1" aria-expanded="false">
						<ul class="nav navbar-nav">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle color" data-toggle="dropdown" role="button">
									Examen <span class="caret"></span>
								</a>
								<ul class="dropdown-menu">
									<li>
										<a href="<?= site_url('/sysmater/docente/docente/nuevo_examen') ?>">
											Nuevo Exámen
										</a>
									</li>
									<li>
										<a href="<?= site_url('/sysmater/docente/docente/examenes_registrados') ?>">
											Exámenes registrados
										</a>
									</li>
									<li>
										<a href="<?= site_url('/sysmater/docente/docente/examenes_programados') ?>">
											Aplicaciones programadas
										</a>
									</li>
									<li>
										<a href="<?= site_url('/sysmater/docente/docente/cargarImagenesReactivos') ?>">
											Cargar Reactivos Examen
										</a>
									</li>
								</ul>
							</li>

							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">
									Resultados <span class="caret"></span>
								</a>
								<ul class="dropdown-menu">

									<li>
										<a href="<?= site_url('/sysmater/docente/docente/reporte_examen') ?>">
											Resultados Examen
										</a>
									</li>
									<li>
										<a href="<?= site_url('/sysmater/docente/docente/reporte_examen_parciales') ?>">
											Resultados Por Parciales
										</a>
									</li>
									<li>
										<a href="<?= site_url('/sysmater/docente/docente/reportes_actividades') ?>">
											Resultados Actividades
										</a>
									</li>

								</ul>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">
									Actividades <span class="caret"></span>
								</a>
								<ul class="dropdown-menu">

									<li>
										<a href="<?= site_url('/sysmater/docente/docente/ver_materias') ?>">
											Mis materias
										</a>
									</li>
									<li>
										<a href="<?= site_url('/sysmater/docente/docente/programar_actividad') ?>">
											Asignar actividades
										</a>
									</li>
									<li>
										<a href="<?= site_url('/sysmater/docente/docente/ver_instrumentos') ?>">
											Hojas de evaluación
										</a>
									</li>

								</ul>
							</li>


						</ul>
						<ul class="nav navbar-nav navbar-right">
							<li class="dropdown">
								<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
									<span class="glyphicon glyphicon-user"></span> <?php echo  ucwords(strtolower($this->session->Usuario)) ?><span class="caret"></span></a>

								<ul class="dropdown-menu" role="menu">
									<li><a href="#" data-toggle="modal" data-target="#ventana_peril" data-backdrop="static" data-task="update" data-user="" data-id=">" data-titulo="perfil del usuario "><span class="glyphicon glyphicon-lock"></span> Cambiar contraseña</a></li>
									<li class="divider"></li>
									<li>
										<a onclick="if(confirm_exit() == false) return false" href="<?= site_url('login/logout') ?>"> <span class="glyphicon glyphicon-log-out"></span> Cerrar Sesión</a>
									</li>
								</ul>
								<!-- /.dropdown-user -->
							</li>
						</ul>
						
						<ul class="nav navbar-nav navbar-right">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">
									Plantillas De Carga <span class="caret"></span>
								</a>
								<ul class="dropdown-menu">

									<li>
										<a href="<?= site_url('/Archivos_Plantillas/Plantilla_De_Carga_Reactivos_Examen.xlsx') ?>">
											Plantilla De Reactivos Examen
										</a>
									</li>

								</ul>
							</li>
						</ul>
					</div>
				</div>
			</nav>
		</div>