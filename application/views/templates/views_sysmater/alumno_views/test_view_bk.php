<!-- test template. -->
<!-- 03/06/2016 -->
<!DOCTYPE html>
<html lang="en">
<head>
	<script type="text/javascript">
		base_url = "<?php echo base_url();?>";
	</script>
	<meta charset="UTF-8">
	<link rel="shortcut icon" type="image/x-icon" href="<?= base_url() ?>assets/img/logo_2_SysGED.ico"/>
	<title>EXAMEN GENERAL DE EGRESO DE LICENCIATURA</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css">
	<script src="<?= base_url() ?>assets/js/jquery-3.1.1.min.js"></script>
	<script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
	<script src="<?= base_url() ?>assets/js/alumno/fn_examen.js"></script>
</head>

<body id="body" onload="upload_questions(<?php echo $this->session->ID_exam ?>)">
	<div class="container-fluid" style="height: 500px;" >

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col col-sm-9" id="informacion">
						<div class="row">
							<div class="col col-sm-6" for="nom_exa">
								EXAMEN:<?php echo $this->session->Nom_exam; ?>
							</div>
							<div class="col col-sm-6" id="nombre">
								ALUMNO(A):<span id="matricula"><?php echo $this->session->Matricula ?></span>
								<?php echo $this->session->Usuario ?>
							</div>
						</div>
					</div>
					<div class="col col-sm-3">TIEMPO RESTANTE <span id="Cronometro">00:00:00</span></div>
				</div>
			</div>
		</div>
		<ul class="nav nav-tabs">
		  	<li role="presentation" class="active" id="link-pregunta">
			  	<a data-toggle="tab" href="#tab-reactivo"> Pregunta <span id="numPre">0</span>/ <span id="tot"> <?php echo $this->session->Cant_preguntas ?>
					</span><br/>
				</a>
			</li>
		  	<li role="presentation" id="link-planteamiento" ><a data-toggle="tab" href="#tab-planteamiento">Planteamiento</a></li>
		</ul>

		<div class="tab-content">
		 	<div id="tab-reactivo" class="tab-pane fade in active">
		 		<div class="panel panel-primary">
		  			<div class="panel-heading">
		  				 <h5 class="panel-title" >
								<div id="Pregunta">
									Lorem ipsum dolor sit amet, consectetur adipisicing elit.
								</div>
		  				 </h5>
		  			</div>
		 		 	<div class="panel-body">
			 		 	<div class="col col-sm-7" id="opciones">
							<label for="">Opciones: </label>
							<ul class="list-group">
								<li class="list-group-item">
									<div class="row">
										<div class="col-sm-1">
											<center>
												<input type="radio" name="radio" id="A" value="A">
											</center>
										</div>
										<div class="col-sm-11">
											<label for=""> A) </label>
											<span id="textA">
												Lorem ipsum dolor sit amet, consectetur adipisicing elit.
											</span>
											<span class="hover_img" id="cont_img-a">
												<a href="#"><span><img src="#" id='img-a' width="400" alt="image"/></span><i class="glyphicon glyphicon-picture" aria-hidden="true"></i></a>
										    </span>
										</div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-sm-1">
											<center>
												<input type="radio" name="radio" id="B" value="B">
											</center>
										</div>
										<div class="col-sm-11">
											<label for=""> B) </label>
											<span id="textB">
												Lorem ipsum dolor sit amet, consectetur adipisicing elit.
											</span>
											<span class="hover_img" id="cont_img-b">
												<a href="#"><span><img src="#" id='img-b' width="400" alt="image"/></span><i class="glyphicon glyphicon-picture" aria-hidden="true"></i></a>
										    </span>
										</div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-sm-1">
											<center>
												<input type="radio" name="radio" id="C" value="C">
											</center>
										</div>
										<div class="col-sm-11">
											<label for=""> C) </label>
											<span id="textC">
												Lorem ipsum dolor sit amet, consectetur adipisicing elit.
											</span>
											<span class="hover_img" id="cont_img-c">
												<a href="#"><span><img src="#" id='img-c' width="400" alt="image"/></span><i class="glyphicon glyphicon-picture" aria-hidden="true"></i></a>
										    </span>
										</div>
									</div>
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-sm-1">
											<center>
												<input type="radio" name="radio" id="D" value="D">
											</center>
										</div>
										<div class="col-sm-11">
											<label for=""> D) </label>
											<span id="textD">
												Lorem ipsum dolor sit amet, consectetur adipisicing elit.
											</span>
											<span class="hover_img" id="cont_img-d">
												<a href="#"><span><img src="#" id='img-d' width="400" alt="image"/></span><i class="glyphicon glyphicon-picture" aria-hidden="true"></i></a>
										    </span>
										</div>
									</div>
								</li>
							</ul>
						</div>
						<div class="col col-sm-5" id="img">
							<img class="img-thumbnail" width="450" id="img-base" src=""/>
						</div>
					 </div>
					 <div class="panel-footer" id="Botones">
						<!--<button class="btn btn-info" id="In" onClick="start()">
							<span class="glyphicon glyphicon-fast-backward"></span> Inicio
						</button> 
						<button class="btn btn-info" id="An" onClick="previous()">
							<span class="glyphicon glyphicon-step-backward"></span> Anterior
						</button>-->
						<button class="btn btn-primary" id="Si" onClick="next()"> Siguiente
							<span class="glyphicon glyphicon-step-forward"></span> 
						</button>
						<button class="btn btn-success" id="finalizar" type="button" onClick="finish_test()">
							<span class="glyphicon glyphicon-ok"></span> Cerrar Examen
						</button>
					 </div>
				</div>
		    </div><!--reactivo-->
		  	<div id="tab-planteamiento" class="tab-pane fade">
		  		<div class="panel panel-info">
		  			<div class="panel-body">
		  				<div class="row">
		  					<div class="col col-sm-12">
		  						<span id="planteamiento"></span>
		  					</div>
		  					<div class="col col-sm-12" id="cont_img-plan" align="center">
		  						<img src="" class="img-thumbnail" id="img-plantea">
		  					</div>		  					
		  				</div>
		  			</div>
		  		</div>
		  	</div><!--planteamiento-->
		</div>
		
	</div>
	<script>
		
	</script>
</body>
</html>
