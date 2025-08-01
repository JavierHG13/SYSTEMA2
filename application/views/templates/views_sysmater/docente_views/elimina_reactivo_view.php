<!-- AGREGADO POR ISRAEL REYES AQUINO -->
<link rel="stylesheet" href="<?= base_url() ?>assets/css/reactivos.css">
		<center>
			<h3>¿Desea eliminar el planteamiento y sus reactivos ?</h3>
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
			<?php 
				$row = $reactivo_main->row();
			?>
			<?=form_open("/docente/elimina_reactivo/index/".$row->id_reactivo_main, $attributes);  ?>
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
									<label for="" class="control-label col-sm-2">Base del Reactivo</label>
								    <div class="col-sm-10">
								    	<figure class="highlight">
								    		<?php echo $reactivo->txt_base; ?>
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
			<div class="form-group">
				<div class="col col-sm-12">
					<center>
						<input type="hidden" id='task' name='task' value="delete">
						<input type="hidden" id='idreactivo' name='idreactivo' value="">
						<button type="submit" class="btn btn-success">Si</button>
			 			<a href="<?= site_url('/docente/docente/lista_reactivos') ?>" class="btn btn-danger"> No </a>
					</center>
				</div>
			</div>
			<?=form_close(); ?>
		</div>