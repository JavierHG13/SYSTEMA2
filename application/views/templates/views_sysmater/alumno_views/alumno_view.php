<!-- Alumno template.-->
<!--03/06/2016-->

<!-- anterior -->
<div class="panel color3">
	<div class="panel-heading color1">
		<h5 class="panel-title" id="pregunta">
			<h2>Bienvenido(a): <?php echo $this->session->Usuario ?></h2>
			<h4><b>Matricula: </b><?php echo $this->session->Matricula ?></h4>
			<h4><b>Carrera: </b><?php echo $this->session->NomCarrera ?></h4>
			<h4><b>Especialidad: </b><?php echo $this->session->Especialidad ?></h4>
			<h4><b>Cuatrimestre: </b><?php echo $this->session->Cuatri ?></h4>
			<h4><b>Grupo: </b><?php echo $this->session->GrupoNom ?></h4>
		</h5>
	</div>
	<div class="panel-body">
		<div class=" col-md-7">
			<h3>Instrucciones:</h3>
			<OL TYPE="1">
				<LI>Leer atentamente las preguntas.</LI>
				<LI>Seleccionar la respuesta correcta con el mouse <br>
					o bien preciona las tecla
					<span class="label label-default">A</span>
					<span class="label label-default">B</span>
					<span class="label label-default">C</span>
					<span class="label label-default">D</span>
				</LI>
				<LI>Dar click en el bot&oacute;n siguiente para pasar a otra pregunta.</LI>
				<LI>Al concluir el examen has clic en el botón <b>Cerrar Examen</b>.</LI>
			</OL>
			<h4>Notas:</h4>
			<OL>
				<li>
					Puedes utilizar la teclas de las letras subrayadas en los botones si asi lo deseas.
				</li>
				<li>
					En caso de que suceda algun error, por favor dirigirse con el aplicador del examen.
				</li>
			</OL>

		</div>
		<div class="col-md-5 div-alumno">
			<?php
			$attributes = array(
				"class" => "form-horizontal",
				"id" => "alumno",
				"name" => "alumno",
				"method" => "POST"
			);
			echo form_open("/sysmater/alumno/examenes", $attributes); ?>
			<div class="form-group">
				<label for="" class="control-label col-sm-3">
					Examen:
				</label>
				<div class="col-sm-9">
					<select name="valid_test" id="valid_test" class="form-control">
						<option value="00">Ninguno</option>
						<?php
						if (is_array($valid_test)) {
							$num = count($valid_test);
							for ($i = 0; $i < $num; $i++) {
						?>
								<option value="<?php echo $valid_test[$i]['id_examen'] ?>">
									<?php echo $valid_test[$i]['nvch_Titulo'] ?>
								</option>
						<?php
							} //end for
						} // end if
						?>
					</select>
					<span class="text-danger"><?php echo form_error('valid_test'); ?>
					</span>
				</div>
			</div>
			<div class="form-group">
				<label for="" class="control-label col-sm-3">
					Contrase&ntilde;a:
				</label>
				<div class="col-sm-9">
					<input class="form-control" id="password" name="password" placeholder="Password" type="password" value="<?php echo set_value('password'); ?>" />
					<span class="text-danger"><?php echo form_error('password'); ?>
					</span>
				</div>
			</div>
			<div class="form-group">
				<div class="col col-sm-12">
					<input id="start" name="start" onclick="if(confirm_start() == false) return false" class="btn btn-success btn-block" type="submit" value="Iniciar Examen">
				</div>
				<!-- <div class="col col-sm-6">
					<input id="exit" name="exit" onclick="if(confirm_exit() == false) return false" class="btn btn-danger btn-block" type="submit" value="Salir">
				</div> -->
			</div>
			<div class="clearfix visible-sm-block"></div>
			<?php echo form_close(); ?>
			<?php echo $this->session->flashdata('msg'); ?>
		</div>
	</div>
</div>

<!-- Estilos CSS específicos para esta vista -->
<style>
	.panel-body {
		background: #FFFFEF;
	}
</style>