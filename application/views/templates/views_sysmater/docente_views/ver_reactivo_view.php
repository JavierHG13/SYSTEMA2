<!-- lista reactivoe -->
<!-- AGREGADO POR ISRAEL REYES AQUINO -->
		<?php 
			$row = $reactivo_main->row();
		?>
		<?php 
			$path_images='uploads/';
		?>
		<link rel="stylesheet" href="<?= base_url() ?>assets/css/reactivos.css">
		<center>
			<h3>Ver reactivo</h3>
		</center>
		<div id="box">
			<div class="panel panel-success">
				<div class="panel-body">
					<div class="row">
						<label for="" class="control-label col-sm-2">Carrera</label>
						<div class="col-sm-10 ">
							<figure class="highlight">
								<?php echo $row->vchNomCarrera; ?>
							</figure>
						</div>
					</div>
					<div class="row">
						<label for="" class="control-label col-sm-2">Cuatrimestre</label>
						<div class="col-sm-3">
							<figure class="highlight">
								<?php echo $row->vchNomCuatri; ?>
							</figure>
						</div>
						<label for="" class="control-label col-sm-1">Materia</label>
						<div class="col-sm-6">
							<figure class="highlight">
								<?php echo $row->vchNomMateria; ?>
							</figure>
						</div>
					</div>
					<div class="row">
						<label for="planteamiento" class="control-label col-sm-2">Planteamiento</label>
					    <div class="col-sm-10">
					    	<figure class="highlight">
					    		<?php echo $row->txt_planeamiento; ?>
					    	</figure>
					    </div>
					</div>
					<div class="row">
						<label for="" class="control-label col-sm-2">Imagen multi-reactivo:</label>
						<div class="col-sm-5 hover_img">
							<?php if ($row->path_imagen): ?>
							<a href="#"><span><img src="<?php echo base_url().$path_images.$row->path_imagen; ?>" class="img-rounded" alt="image"/></span><i class="glyphicon glyphicon-picture" aria-hidden="true"></i></a>
							<?php endif ?>
					    </div>
					</div>
				</div>
			</div>
			<?php $i=1; ?>
			<ul class="nav nav-tabs" id="tabs">
			<?php if ($reactivo_detail): ?>
				<?php foreach ($reactivo_detail->result() as $reactivo): ?>
						<?php if ($i==1): ?>
							<?php $active='active' ?>
						<?php else: ?>
							<?php $active='' ?>
						<?php endif ?>
					 	<li  id="refTab<?php echo $i; ?>" class="<?=$active; ?>"><a data-toggle="tab" href="#tab<?php echo $i; ?>">Reactivo <?php echo $i; ?></a></li>
					 	<?php $i++; ?>
				<?php endforeach ?>	
			<?php endif ?>
			</ul>
			
			<div class="tab-content">
				<br>
				<?php $i=1; ?>
				<?php if ($reactivo_detail): ?>
					<?php foreach ($reactivo_detail->result() as $reactivo): ?>
						<?php if ($i==1): ?>
							<?php $active='active' ?>
						<?php else: ?>
							<?php $active='' ?>
						<?php endif ?>
			  	<div id="tab<?php echo $i; ?>" class="tab-pane fade in <?=$active; ?>">
			  		<div id="ctrl_reactivo">
			  			<div class="row">
							<label for="" class="control-label col-sm-2">Duración</label>
							<label for="" class="control-label col-sm-1">Horas</label>
						    <div class="col-sm-2">
						    	<figure class="highlight">
					    			<?php echo $reactivo->int_horas; ?>
						    	</figure>
						    </div>
						    <label for="" class="control-label col-sm-1">Minutos</label>
						    <div class="col-sm-2">
						    	<figure class="highlight">
						    		<?php echo $reactivo->int_minutos; ?>
						    	</figure>
						    </div>
						</div>
						<div class="row">
							<label for="" class="control-label col-sm-2">Base del Reactivo</label>
						    <div class="col-sm-10">
						    	<figure class="highlight">
						    		<?php echo $reactivo->txt_base; ?>
						    	</figure>
						    </div>
						</div>
						<div class="row">
							<label for="" class="control-label col-sm-2">Imagen base:</label>
							<div class="col-sm-5 hover_img">
								<?php if ($reactivo->path_imagen_base): ?>
									<a href="#"><span><img src="<?php echo base_url().$path_images.$reactivo->path_imagen_base; ?>" class="img-rounded" alt="image"/></span><i class="glyphicon glyphicon-picture" aria-hidden="true"></i></a>
								<?php endif ?>
						    </div>
						</div>
						<div class="row">
							<div class="col col-sm-2 text-right">
							</div>
							<div class="col col-sm-5">
								<b>Respuesta</b>
							</div>
							<div class="col col-sm-5">
								<b>Argumentación</b>
							</div>
						</div>

						<div class="row">
							<div class="col col-sm-2 text-right">
								<b>Opción A</b>
							</div>
							<div class="col col-sm-5">
								<figure class="highlight">
									<?php echo $reactivo->nvch_opcionA; ?>
								</figure>
							</div>
							<div class="col col-sm-5">
								<figure class="highlight">
									<?php echo $reactivo->nvch_argumentaA; ?>
								</figure>
							</div>
						</div>
						<div class="row">
							<label for="" class="control-label col-sm-2">Imagen Opción A:</label>
							<div class="col-sm-5 hover_img">
						    	<?php if ($reactivo->path_imagenA): ?>
									<a href="#"><span><img src="<?php echo base_url().$path_images.$reactivo->path_imagenA; ?>" class="img-rounded" alt="image"/></span><i class="glyphicon glyphicon-picture" aria-hidden="true"></i></a>
								<?php endif ?>
						    </div>
						</div>

						<div class="row">
							<div class="col col-sm-2 text-right">
								<b>Opción B</b>
							</div>
							<div class="col col-sm-5">
								<figure class="highlight">
									<?php echo $reactivo->nvch_opcionB; ?>
								</figure>
							</div>
							<div class="col col-sm-5">
								<figure class="highlight">
									<?php echo $reactivo->nvch_argumentaB; ?>
								</figure>
							</div>
						</div>
						<div class="row">
							<label for="" class="control-label col-sm-2">Imagen Opción B:</label>
							<div class="col-sm-5 hover_img">
						    	<?php if ($reactivo->path_imagenB): ?>
									<a href="#"><span><img src="<?php echo base_url().$path_images.$reactivo->path_imagenB; ?>" class="img-rounded" alt="image"/></span><i class="glyphicon glyphicon-picture" aria-hidden="true"></i></a>
								<?php endif ?>
						    </div>
						</div>

						<div class="row">
							<div class="col col-sm-2 text-right">
								<b>Opción C</b>
							</div>
							<div class="col col-sm-5">
								<figure class="highlight">
									<?php echo $reactivo->nvch_opcionC; ?>
								</figure>
							</div>
							<div class="col col-sm-5">
								<figure class="highlight">
									<?php echo $reactivo->nvch_argumentaC; ?>
								</figure>
							</div>
						</div>
						<div class="row">
							<label for="" class="control-label col-sm-2">Imagen Opción C:</label>
							<div class="col-sm-5 hover_img">
						    	<?php if ($reactivo->path_imagenC): ?>
									<a href="#"><span><img src="<?php echo base_url().$path_images.$reactivo->path_imagenC; ?>" class="img-rounded" alt="image"/></span><i class="glyphicon glyphicon-picture" aria-hidden="true"></i></a>
								<?php endif ?>
						    </div>
						</div>

						<div class="row">
							<div class="col col-sm-2 text-right">
								<b>Opción D</b>
							</div>
							<div class="col col-sm-5">
								<figure class="highlight">
									<?php echo $reactivo->nvch_opcionD; ?>
								</figure>
							</div>
							<div class="col col-sm-5">
								<figure class="highlight">
									<?php echo $reactivo->nvch_argumentaD; ?>
								</figure>
							</div>
						</div>
						<div class="row">
							<label for="" class="control-label col-sm-2">Imagen Opción D:</label>
							<div class="col-sm-5 hover_img">
						    	<?php if ($reactivo->path_imagenD): ?>
									<a href="#"><span><img src="<?php echo base_url().$path_images.$reactivo->path_imagenD; ?>" class="img-rounded" alt="image"/></span><i class="glyphicon glyphicon-picture" aria-hidden="true"></i></a>
								<?php endif ?>
						    </div>
						</div>

						<div class="row">
							<label for="" class="control-label col-sm-2">Respuesta Correcta:</label>
							<div class="col-sm-4">
								<figure class="highlight">
									<?php echo $reactivo->chr_correcto; ?>
								</figure>
							</div>
							 <label for="" class="control-label col-sm-1">Nivel</label>
						    <div class="col-sm-3">
						    	<figure class="highlight">
									<?php echo $reactivo->vch_nivel; ?>
						    	</figure>
							</div>
						</div>

						<div class="row">
							<label for="" class="control-label col-sm-2">Bibliografia</label>
						    <div class="col-sm-10">
						    	<figure class="highlight">
						    		<?php echo $reactivo->vch_bibliografia; ?>
						    	</figure>
						    </div>
						</div>
						<br>
			  		</div>
			  	</div><!--TERMINA TAB-->
				  	<?php $i++; ?>
					<?php endforeach ?>	
				<?php endif ?>

			</div><!--TERMINA TAB-CONTENT -->

			<div class="row">
				<div class="col col-sm-10 col-sm-offset-2">
			 		<a href="<?= site_url('/docente/docente/lista_reactivos/') ?>" class="btn btn-danger"> Regresar</a>
				</div>
			</div>
		</div>
		