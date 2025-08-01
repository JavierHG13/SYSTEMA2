<!-- lista reactivoe -->
<!-- AGREGADO POR ISRAEL REYES AQUINO -->
		<link rel="stylesheet" href="<?= base_url() ?>assets/css/reactivos.css">

		<center>
			<h3>Lote de reactivos</h3>
		</center>
			
		<div id="box">
			<?php 
				$attributes = array(
							"class" => "form-horizontal",
							"id" => "form_lote",
							"name" => "form_lote",
							"method" => "POST"
							);
			?>
			<?=form_open_multipart("docente/subir_lote", $attributes);  ?>
			            
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
						<label for="lote_file" class="control-label col-sm-2">Lote de reactivos</label>
						<div class="col-sm-5">
							<div class="control">
				    			<input type="file" id="<lote_file></lote_file>" name="lote_file" class="form-control  ignore" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
							</div>
					    </div>
					</div>
				</div>
			</div>

		

			<div class="campos_ocultos">
				<input type="hidden" value="" name="task" id="task">
			</div>
			<br>
			<div class="form-group">
				<div class="col col-sm-10 col-sm-offset-2">
			 		<button type="button" class="btn btn-success" id="btn_guardar">Subir Archivo</button>
			 		<a href="<?= site_url('/docente/docente/lista_reactivos/') ?>" class="btn btn-danger"> Cancelar</a>
				</div>
			</div>

			<?=form_close(); ?>
		</div>
		<script src="<?= base_url() ?>assets/js/docente/fn_lote_reactivo.js"></script>
		
