<!-- lista reactivoe -->
<!-- AGREGADO POR ISRAEL REYES AQUINO -->
		<link rel="stylesheet" href="<?= base_url() ?>assets/css/reactivos.css">

		<center>
			<h3>Nuevo reactivo</h3>
		</center>
			
		<div id="box">
			<?php 
				$attributes = array(
							"class" => "form-horizontal",
							"id" => "form_reactivo",
							"name" => "form_reactivo",
							"method" => "POST"
							);
			?>
			<?=form_open_multipart("docente/nuevo_reactivo", $attributes);  ?>
			            
			<div class="panel panel-success">
				<div class="panel-body">
					<div class="form-group">
						<label for="" class="control-label col-sm-2">Carrera</label>
						<div class="col-sm-10">
							<div class="control">
								<select name="carrera" id="carrera" class="form-control ignore" >
									<?php if ($carreras): ?>
										<?php foreach ($carreras->result() as $carrera): ?>
											<option value="<?=$carrera->chrClvCarrera;  ?>">
												<?= $carrera->vchNomCarrera;?>
											</option>
										<?php endforeach ?>	
									<?php endif ?>
								</select>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="cuatrimestre" class="control-label col-sm-2">Cuatrimestre</label>
						<div class="col-sm-2">
							<div class="control">
								<select name="cuatrimestre" id="cuatrimestre" class="form-control ignore">
									<?php if ($cuatrimestres): ?>
										<?php foreach ($cuatrimestres->result() as $cuatrimestre): ?>
											<option value="<?=$cuatrimestre->vchClvCuatri;  ?>">
												<?= $cuatrimestre->vchNomCuatri;?>
											</option>
										<?php endforeach ?>	
									<?php endif ?>
								</select>
							</div>
						</div>
						<label for="" class="control-label col-sm-2">Materia</label>
						<div class="col-sm-6">
							<div class="control">
								<select name="materia" id="materia" class="form-control ignore">
									<?php if ($materias): ?>
										<?php foreach ($materias->result() as $materia): ?>
											<option value="<?=$materia->vchClvMateria;  ?>">
												<?= $materia->vchNomMateria;?>
											</option>
										<?php endforeach ?>	
									<?php endif ?>
								</select>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="planteamiento" class="control-label col-sm-2">Planteamiento</label>
					    <div class="col-sm-10">
					    	<div class="control">
					    		<div class="zero-clipboard"><button type="button" class="btn-clipboard" data-toggle="modal" data-backdrop="static" data-target="#myModal" data-titulo="Edita Planteamiento">Editor</button></div>
               					<textarea name="planteamiento" id="planteamiento" class="form-control  ignore" cols="30" rows="3" placeholder="Planteamiento para multi-reactivo"></textarea>
					    	</div>
					    </div>
					</div>
					<div class="form-group">
						<label for="imgPlanteamiento" class="control-label col-sm-2">Imagen multi-reactivo:</label>
						<div class="col-sm-5">
							<div class="control">
				    			<input type="file" id="imgPlanteamiento" name="imgPlanteamiento" class="form-control  ignore" accept="image/jpg,image/png">
							</div>
					    </div>
					</div>
				</div>
			</div>

			<a href="#" id="btnAdd"><i class="glyphicon glyphicon-plus-sign"></i> Agregar reactivo</a>
			<a href="#" id="btnDelete"><i class="glyphicon glyphicon-minus-sign"></i> Eliminar reactivo</a>

    		<br><br>
			<ul class="nav nav-tabs" id="tabs">
			 	<li  id="refTab1" class="active"><a data-toggle="tab" href="#tab1">Reactivo 1</a></li>
			</ul>
			<div class="tab-content">
				<br>
			  	<div id="tab1" class="tab-pane fade in active">
			  		<div id="ctrl_reactivo">

			  			<div class="form-group">
							<label for="" class="control-label col-sm-2">Duración</label>
							<label for="horas" class="control-label col-sm-1">Horas</label>
						    <div class="col-sm-2">
						    	<div class="control">
					    			<input type="text" class="form-control" id="horas" name="horas[]" placeholder="Horas del reactivo" value="" required="required">
						    	</div>
						    </div>
						    <label for="minutos" class="control-label col-sm-1">Minutos</label>
						    <div class="col-sm-2">
						    	<div class="control">
						    		<input type="text" class="form-control" id="minutos" name="minutos[]" placeholder="minutos del reactivo" value="" required="required">
						    	</div>
						    </div>
						</div>
						<div class="form-group">
							<label for="reactivo" class="control-label col-sm-2">Base del Reactivo</label>
						    <div class="col-sm-10">
						    	<div class="control">
						    		<div class="zero-clipboard"><button type="button" class="btn-clipboard" data-toggle="modal" data-backdrop="static" data-target="#myModal" data-titulo="Edita Base del reactivo">Editor</button></div>
						    		<textarea name="reactivo[]" id="reactivo" class="form-control" cols="30" rows="3" placeholder="Descripción del reactivo" required="required"></textarea>
						    	</div>
						    </div>
						</div>
						<div class="form-group">
							<label for="imgReactivo" class="control-label col-sm-2">Imagen base:</label>
							<div class="col-sm-5">
						    	<input type="file" id="imgReactivo" name="imgReactivo[]" class="form-control" accept="image/jpg,image/png">
						    </div>
						</div>
						<div class="form-group">
							<div class="col col-sm-2 text-right">
							</div>
							<div class="col col-sm-5">
								<b>Respuesta</b>
							</div>
							<div class="col col-sm-5">
								<b>Argumentación</b>
							</div>
						</div>
						<div class="form-group">
							<div class="col col-sm-2 text-right">
								<b>Opción A</b>
							</div>
							<div class="col col-sm-5">
								<div class="control">
									<div class="zero-clipboard"><button type="button" class="btn-clipboard" data-toggle="modal" data-backdrop="static" data-target="#myModal" data-titulo="Edita Opción A">Editor</button></div>
									<textarea class="form-control" name="opcionA[]" id="opcionA" cols="30" rows="2" placeholder="Descripción de la opción A" required="required"></textarea>
								</div>
							</div>
							<div class="col col-sm-5">
								<div class="control">
									<textarea class="form-control" name="argumentaA[]" id="argumentaA" cols="30" rows="2" placeholder="Argumentación de la option A" required="required"></textarea>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="imgOpcionA" class="control-label col-sm-2">Imagen Opción A:</label>
							<div class="col-sm-5">
						    	<div class="control">
						    		<input type="file" id="imgOpcionA" name="imgOpcionA[]" class="form-control" accept="image/jpg,image/png">
						    	</div>
						    </div>
						</div>
						<div class="form-group">
							<div class="col col-sm-2 text-right">
								<b>Opción B</b>
							</div>
							<div class="col col-sm-5">
								<div class="control">
									<div class="zero-clipboard"><button type="button" class="btn-clipboard" data-toggle="modal" data-backdrop="static" data-target="#myModal" data-titulo="Edita Opción B">Editor</button></div>
									<textarea class="form-control" name="opcionB[]" id="opcionB" cols="30" rows="2"  placeholder="Descripción de la opción B" required="required"></textarea>
								</div>
							</div>
							<div class="col col-sm-5">
								<div class="control">
									<textarea class="form-control" name="argumentaB[]" id="argumentaB" cols="30" rows="2"  placeholder="Argumentación de la option B" required="required"></textarea>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="imgOpcionB" class="control-label col-sm-2">Imagen Opción B:</label>
							<div class="col-sm-5">
						    	<div class="control">
						    		<input type="file" id="imgOpcionB" name="imgOpcionB[]" class="form-control" accept="image/jpg,image/png">
						    	</div>
						    </div>
						</div>
						<div class="form-group">
							<div class="col col-sm-2 text-right">
								<b>Opción C</b>
							</div>
							<div class="col col-sm-5">
								<div class="control">
									<div class="zero-clipboard"><button type="button" class="btn-clipboard" data-toggle="modal" data-backdrop="static" data-target="#myModal" data-titulo="Edita Opción C">Editor</button></div>
									<textarea class="form-control" name="opcionC[]" id="opcionC" cols="30" rows="2"  placeholder="Descripción de la opción C" required="required"></textarea>
								</div>
							</div>
							<div class="col col-sm-5">
								<div class="control">
									<textarea class="form-control" name="argumentaC[]" id="argumentaC" cols="30" rows="2"  placeholder="Argumentación de la option C" required="required"></textarea>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="imgOpcionC" class="control-label col-sm-2">Imagen Opción C:</label>
							<div class="col-sm-5">
						    	<div class="control">
						    		<input type="file" id="imgOpcionC" name="imgOpcionC[]" class="form-control" accept="image/jpg,image/png">
						    	</div>
						    </div>
						</div>
						<div class="form-group">
							<div class="col col-sm-2 text-right">
								<b>Opción D</b>
							</div>
							<div class="col col-sm-5">
								<div class="control">
									<div class="zero-clipboard"><button type="button" class="btn-clipboard" data-toggle="modal" data-backdrop="static" data-target="#myModal" data-titulo="Edita Opción D">Editor</button></div>
									<textarea class="form-control" name="opcionD[]" id="opcionD" cols="30" rows="2"  placeholder="Descripción de la opción D" required="required"></textarea>
								</div>
							</div>
							<div class="col col-sm-5">
								<div class="control">
									<textarea class="form-control" name="argumentaD[]" id="argumentaD" cols="30" rows="2"  placeholder="Argumentación de la option D" required="required"></textarea>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="imgOpcionD" class="control-label col-sm-2">Imagen Opción D:</label>
							<div class="col-sm-5">
						    	<div class="control">
						    		<input type="file" id="imgOpcionD" name="imgOpcionD[]" class="form-control" accept="image/jpg,image/png">
						    	</div>
						    </div>
						</div>
						<div class="form-group">
							<label for="correcta" class="control-label col-sm-2">Respuesta Correcta:</label>
							<div class="col-sm-4">
								<div class="control">
									<select name="correcta[]" id="correcta" class="form-control">
										<option value="A">Opción A</option>
										<option value="B">Opción B</option>
										<option value="C">Opción C</option>
										<option value="D">Opción D</option>
									</select>
								</div>
							</div>
							 <label for="nivel" class="control-label col-sm-1">Nivel</label>
						    <div class="col-sm-3">
						    	<div class="control">
									<select name="nivel[]" id="nivel" class="form-control">
									<?php if ($niveles): ?>
										<?php foreach ($niveles->result() as $nivel): ?>
											<option value="<?=$nivel->id_nivel;  ?>">
												<?= $nivel->vch_nivel;?>
											</option>
										<?php endforeach ?>	
									<?php endif ?>
									</select>
						    	</div>
							</div>
						</div>

						<div class="form-group">
							<label for="bibliografia" class="control-label col-sm-2">Bibliografia</label>
						    <div class="col-sm-10">
						    	<div class="control">
						    		<textarea name="bibliografia[]" id="bibliografia" class="form-control" cols="30" rows="3" placeholder="Descripción del bibliografia" required="required"></textarea>
						    	</div>
						    </div>
						</div>
						<br>
			  		</div>
					
			  	</div><!--TERMINA TAB-->

			</div><!--TERMINA TAB-CONTENT -->

			<div class="campos_ocultos">
				<input type="hidden" value="" name="task" id="task">
			</div>
			<br>
			<div class="form-group">
				<div class="col col-sm-10 col-sm-offset-2">
			 		<button type="button" class="btn btn-success" id="btn_guardar">Guardar</button>
			 		<a href="<?= site_url('/docente/docente/lista_reactivos/') ?>" class="btn btn-danger"> Cancelar</a>
				</div>
			</div>

			<?=form_close(); ?>
		</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Modal title</h4>
				</div>
			<div class="modal-body">
				<textarea name="editor" id="editor" cols="30" rows="10"></textarea>
			</div>
			<div class="modal-footer">
				<button class="btn btn-default" type="button" data-dismiss="modal" id="cmd_cerrar">Cerrar</button>
				<button class="btn btn-primary" type="button" id="cmd_guardar">Guardar cambios</button>
			</div>
		</div>
	</div>
</div><!-- fin Modal -->
<script src="<?= base_url() ?>assets/js/docente/fn_nuevo_reactivo.js"></script>
		
