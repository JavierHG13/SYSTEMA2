<!-- Login template -->
<!-- 07/06/2016 -->
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<link rel="shortcut icon" type="image/x-icon" href="<?= base_url() ?>assets/img/logo_2_SysGED.ico" />
	<title>SYSMATER - Examen General de Egreso de Licenciatura</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/forms.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/login.css">
	<script src="<?= base_url() ?>assets/js/jquery-3.1.1.min.js"></script>
	<script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
	<script>
        var base_url = '<?= base_url() ?>';
       // console.log("Base URL: ", base_url); // Verificar que la base URL se está definiendo correctamente
    </script>
	<style>
		#box,
		.form-signin,
		.form-group,
		.form-control {
			box-shadow: none !important;
		}
	</style>
</head>

<body id="">
	<div id="box" class="container">
		<header id="header">
			<center> <br>
				<img id="img_uthh" src="<?= base_url() ?>assets/img/logo_uthh_completo_small.png">
			</center>
		</header>
		<div class="">
			<center><br>
				<h1>Plataforma General De Exámenes</h1>
			</center>
		</div>
		<div class="">
			<?php
			$attributes = array(
				"class" => "form-signin",
				"id" => "login_form",
				"name" => "login_form"
			);
			echo form_open("login/index", $attributes); ?>
			<h3 class="form-signin-heading text-center">Iniciar sesión</h3>

			<div class="form-group">
				<div class="col-sm-12">
					<div>
						<label for="type_of_user" >Tipo de usuario:</label>
						<select name="type_of_user" id="type_of_user" class="form-control">
							<option value="">Ninguna</option>
						</select>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-12">
					<div class="">
						<label for="username" >Usuario:</label>
						<input class="form-control" id="username" name="username" placeholder="Username" type="number" value="<?php echo set_value('username'); ?>" />
						<span class="text-danger"><?php echo form_error('username'); ?></span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-12">
					<div class="">
						<label for="password" >Contraseña</label>
						<input class="form-control" id="password" name="password" placeholder="Password" type="password" value="<?php echo set_value('password'); ?>" />
						<span class="text-danger"><?php echo form_error('password'); ?></span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12">
					<div class="text-center">
						<?php echo $this->session->flashdata('msg'); ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12">
					<button id="btn_login" name="btn_login"  class="btns btn color1 btn-block" type="submit" value="Ingresar">
						<span class="glyphicon glyphicon-log-in"></span> Ingresar
					</button>
				</div>
			</div>
			<?php echo form_close(); ?>
			
		</div>
		</div>

		<footer role="contentinfo">
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<center>
							<p>
								Universidad Tecnol&oacute;gica de la Huasteca Hidalguense<br />
								Carretera Huejutla - Chalahuiyapa S/N, C.P. 43000, Huejutla de Reyes Hidalgo<br />
								<small>
									2016. &copy; Copyrigth. Derechos reservados.
								</small>
							</p>
						</center>
					</div>
				</div>
			</div>
		</footer>


		<script>
			$(document).ready(function() {
				get_usuarios();
			});
			
			function get_usuarios() {
				var systema = "SYSMATER";
				
				var url = base_url + "start/get_usuarios";
				//console.log("URL: " + url);
			
				$.post(
					url,
					{
						systema: systema,
					},
					function(data) {
						var type_of_user = $('#type_of_user');
						type_of_user.empty(); // Clear current options
						var response = JSON.parse(data);
						$.each(response, function(index, user) {
							type_of_user.append('<option value="' + user.vchClaveTipo + '">' + user.vch_TipoUsuario + '</option>');
						});
					}
				);
			}
			
		</script>	
</body>
</html>