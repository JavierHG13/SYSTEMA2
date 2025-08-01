<!-- test template. -->
<!-- 03/06/2016 -->
<!DOCTYPE html>
<html lang="en">

<head>
	<script type="text/javascript">
		base_url = "<?php echo base_url(); ?>";
	</script>
	<meta charset="UTF-8">
	<link rel="shortcut icon" type="image/x-icon" href="<?= base_url() ?>assets/img/logo_2_SysGED.ico" />
	<title>EGEL - Examen General de Egreso de Licenciatura</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap.min.css">
	<script src="<?= base_url() ?>assets/js/jquery-3.1.1.min.js"></script>
	<script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
	<script src="<?= base_url() ?>assets/js/alumno/get_examen.js"></script>
	<style>
		body {
			background-color: #f8f9fa;
			color: #333;
			padding: 20px;
			padding-top:10px;
		}

		header, nav, footer {
			margin-bottom: 2px;
		}

		header .row, nav .row {
			background-color: #3e9c3fff; /* verde */
			color: white;
			padding: 10px 15px;
			border-radius: 10px;
			box-shadow: 0 2px 5px rgba(0,0,0,0.15);
			font-weight: 600;
		}

		.nav-tabs {
			border-bottom: none;
			margin-bottom: 10px;
		}

		.nav-tabs > li > a {
			background-color: #f1f1f1;
			border-radius: 8px 8px 0 0;
			color: #000;
			font-weight: 500;
			border: none;
		}

		.nav-tabs > li.active > a,
		.nav-tabs > li.active > a:focus,
		.nav-tabs > li.active > a:hover {
			background-color: #287bbeff; /* azul */
			color: white;
		}

		.tab-content {
			background: #fff;
			padding: 20px;
			border-radius: 12px;
			box-shadow: 0 0 10px rgba(0,0,0,0.08);
		}

		/* ======== PREGUNTA Y OPCIONES ======== */
		h3 #Pregunta {
			font-size: 25px;
			font-weight: 600;
		}

		#opciones .list-group-item {
			border: 1px solid #ddd;
			border-radius: 10px;
			margin-bottom: 10px;
			transition: all 0.3s ease;
		}

		#opciones .list-group-item:hover {
			background-color: #f0f8ff;
			border-color: #326d9eff;
			box-shadow: 0 2px 8px rgba(0,0,0,0.1);
		}

		#opciones input[type="radio"] {
			transform: scale(1.3);
			margin-right: 10px;
		}

		#opciones label {
			font-weight: bold;
			font-size: 1.1em;
		}

		#opciones span {
			font-size: 1em;
		}

		#Botones .btn {
			border-radius: 8px;
			font-weight: bold;
			border: 1px solid #ccc;
			margin: 10px;
			transition: all 0.3s ease;
		}
		.btn-group{
			padding-top:2px;
		}


		.img-thumbnail {
			border-radius: 12px;
			max-width: 100%;
		}

		.hover_img i {
			color: #4682b4;
			font-size: 1.4em;
		}

		@media (max-width: 768px) {
			#opciones .col-sm-1,
			#opciones .col-sm-11 {
				width: 100%;
			}

			#img {
				margin-top: 20px;
			}

			header .row,
			nav .row {
				text-align: center;
			}
		}
		.hover_img {
			position: relative;
		}

		.hover_img span {
			display: none;
			position: fixed;
			top: 50%;
			left: 28%;
			transform: translate(-50%, -50%);
			z-index: 9999;
			background: rgba(255, 255, 255, 0.95);
			padding: 10px;
			border: 1px solid #ccc;
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
		}

		.hover_img:hover span {
			display: block;
		}
	</style>
</head>

<body id="body" onload="upload_questions(<?php echo $this->session->ID_exam ?>)">
	<header>
		<div class="row">
			<div class="col col-sm-5" for="nom_exa">
				EXAMEN:<?php echo $this->session->Nom_exam; ?>
			</div>
			<div class="col col-sm-4" id="nombre">
				ALUMNO(A):<span id="matricula"><?php echo $this->session->Matricula ?></span>
				<?php echo $this->session->Usuario ?>
				<input type="hidden" id="especialidad" value="<?php echo $this->session->clvEspecial ?>" name="especialidad">
			</div>
			<div class="col col-sm-3">TIEMPO RESTANTE <span id="Cronometro">00:00:00</span></div>
		</div>
	</header>
	<nav>
		<div class="row">
			<div class="col col-sm-12" id="numeros"></div>
		</div>
	</nav>
	<ul class="nav nav-tabs">
		<li role="presentation" class="active" id="link-pregunta">
			<a data-toggle="tab" href="#tab-reactivo"> Pregunta <span id="numPre">0</span>/ <span id="tot"> <?php echo $this->session->Cant_preguntas ?>
				</span><br />
			</a>
		</li>
		<li role="presentation" id="link-planteamiento"><a data-toggle="tab" href="#tab-planteamiento">Planteamiento</a></li>
	</ul>
	<div class="tab-content">
		<div id="tab-reactivo" class="tab-pane fade in active">
			<div id='main'>
				<article>
					<h3>
						<div id="Pregunta">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</div>
					</h3>
					<div class="row">
						<div class="col col-sm-7" id="opciones">
							<label for="">Opciones: </label>
							<ul class="list-group">
								<li class="list-group-item">
									<div class="row">
										<div class="col-sm-1">
											<input type="radio" name="radio" id="A" value="A">
											<label for=""> A) </label>
										</div>
										<div class="col-sm-11">
											<span id="textA">
												Lorem ipsum dolor sit amet, consectetur adipisicing elit.
											</span>
											<span class="hover_img" id="cont_img-a">
												<i class="glyphicon glyphicon-picture" aria-hidden="true"></i>
												<span class="img-container">
													<img src="https://via.placeholder.com/400x200.png?text=Imagen+A" id="img-a" width="400" alt="image" />
												</span>
											</span>

										</div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-sm-1">
											<input type="radio" name="radio" id="B" value="B">
											<label for=""> B) </label>
										</div>
										<div class="col-sm-11">
											<span id="textB">
												Lorem ipsum dolor sit amet, consectetur adipisicing elit.
											</span>
											<span class="hover_img" id="cont_img-b">
												<i class="glyphicon glyphicon-picture" aria-hidden="true"></i>
												<span class="img-container">
													<img src="https://via.placeholder.com/400x200.png?text=Imagen+A" id="img-b" width="400" alt="image" />
												</span>
											</span>

										</div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-sm-1">
											<input type="radio" name="radio" id="C" value="C">
											<label for=""> C) </label>
										</div>
										<div class="col-sm-11">
											<span id="textC">
												Lorem ipsum dolor sit amet, consectetur adipisicing elit.
											</span>
											<span class="hover_img" id="cont_img-c">
												<i class="glyphicon glyphicon-picture" aria-hidden="true"></i>
												<span class="img-container">
													<img src="https://via.placeholder.com/400x200.png?text=Imagen+A" id="img-c" width="400" alt="image" />
												</span>
											</span>

										</div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-sm-1">
											<input type="radio" name="radio" id="D" value="D">
											<label for=""> D) </label>
										</div>
										<div class="col-sm-11">
											<span id="textD">
												Lorem ipsum dolor sit amet, consectetur adipisicing elit.
											</span>
											<span class="hover_img" id="cont_img-d">
												<i class="glyphicon glyphicon-picture" aria-hidden="true"></i>
												<span class="img-container">
													<img src="https://via.placeholder.com/400x200.png?text=Imagen+A" id="img-d" width="400" alt="image" />
												</span>
											</span>

										</div>
									</div>
								</li>
							</ul>
						</div>
						<div class="col col-sm-5" id="img">
							<br>
							<img class="img-thumbnail" width="450" id="img-base" src="" />
						</div>
					</div>
				</article>
			</div>
			<footer>
				<div class="btn-group" role="group" id="Botones">
					<button type="button" class="btn btn-success" id="In" onClick="start()">
						<span class="glyphicon glyphicon-step-backward"></span> <u>I</u>nicio
					</button>
					<button type="button" class="btn btn-primary" id="An" onClick="previous()">
						<span class="glyphicon glyphicon-backward"></span> A<u>n</u>terior
					</button>
					<button type="button" class="btn btn-primary" id="Si" onClick="next()"> <u>S</u>iguiente
						<span class="glyphicon glyphicon-forward"></span>
					</button>
					<button type="button" class="btn btn-danger" id="finalizar" type="button" onClick="finish_test()">
						<span class="glyphicon glyphicon-log-out"></span> C<u>e</u>rrar Examen
					</button>
				</div>
			</footer>
		</div>
		<div id="tab-planteamiento" class="tab-pane fade">
			<div id='main'>
				<article>
					<div class="row">
						<div class="col col-sm-10 col-sm-offset-1">
							<span id="planteamiento"></span>
						</div>
						<div class="col col-sm-10 col-sm-offset-1" id="cont_img-plan" align="center">
							<img src="" class="img-thumbnail" id="img-plantea">
						</div>
					</div>
				</article>
			</div>
		</div>
	</div>

</body>

</html>