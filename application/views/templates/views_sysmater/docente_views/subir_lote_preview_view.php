<!-- lista reactivoe -->
<!-- AGREGADO POR ISRAEL REYES AQUINO -->
		<link rel="stylesheet" href="<?= base_url() ?>assets/css/reactivos.css">

		<center>
			<h3>Nuevo reactivo</h3>
		</center>
			
		<div id='box'>
			<?php 
				$attributes = array(
							"class" => "form-horizontal",
							"id" => "form_save_lote",
							"name" => "form_save_lote",
							"method" => "POST"
							);
			?>
			<?=form_open_multipart("docente/save_lote", $attributes);  ?>
			            
			<div class="panel panel-success">
				<div class="panel-body">
					<div class="form-group">
						<label for="" class="control-label col-sm-2">Carrera</label>
						<div class="col-sm-10">
							<div class="control">
								<?php 
									$row = $carreras->row();
								?>
								<figure class="highlight">
									<input type="hidden" name="carrera" id="carrera" value="<?=$row->chrClvCarrera; ?>">
									<?=$row->vchNomCarrera; ?>
								</figure>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="cuatrimestre" class="control-label col-sm-2">Cuatrimestre</label>
						<div class="col-sm-3">
							<div class="control">
								<?php 
									$row = $cuatrimestres->row();
								?>
								<figure class="highlight">
									<input type="hidden" name="cuatrimestre" id="cuatrimestre" value="<?=$row->vchClvCuatri; ?>">
									<?=$row->vchNomCuatri; ?>
								</figure>
							</div>
						</div>
						<label for="" class="control-label col-sm-1">Materia</label>
						<div class="col-sm-6">
							<div class="control">
								<?php 
									$row = $materias->row();
								?>
								<figure class="highlight">
									<input type="hidden" name="materia" id="materia" value="<?=$row->vchClvMateria; ?>">
									<?=$row->vchNomMateria; ?>
								</figure>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="" class="control-label col-sm-2">Archivo</label>
						<div class="col-sm-10">
							<div class="control">
								<figure class="highlight">
									<?=$name; ?>
								</figure>
							</div>
						</div>
					</div>

				</div>
			</div>


			<div class="table-responsive">
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th></th>
							<th>Planteamiento</th>
							<th>Horas</th>
							<th>Minutos</th>
							<th>Nivel</th>
							<th>Base del reactivo</th>
							<th>Opcioón A</th>
							<th>Argumento A</th>
							<th>Opción B</th>
							<th>Argumento B</th>
							<th>Opción C</th>
							<th>Argumento C</th>
							<th>Opción D</th>
							<th>Agrumento D</th>
							<th>Correcto</th>
							<th>Bibliografia</th>
						</tr>
					</thead>

					<tbody>
						<?php
						if (is_array($xml))
						{
							$num = $registros;
							for ($i = 0; $i <= $num; $i++)
							{ 
						?>
							<tr>
								<td>
									<a role="button" class="remove_row"><span class="glyphicon glyphicon-remove-circle"></span></a>
									<p><?php echo $i+1; ?></p>
								</td>
								<td>
									<textarea name="planteamiento[]" id="planteamiento" cols="30" rows="5" class="form-control" style="width: 250px;" ><?php echo $xml[0][$i] ?></textarea>
									<input type="file" id="imgPlanteamiento" name="imgPlanteamiento[]" class="form-control  ignore" accept="image/jpg,image/png" style="width: 250px;">
								</td>
								<td>
									<input type="text" name="int_horas[]" id="int_horas" value="<?php if(is_numeric($xml[1][$i])){ echo $xml[1][$i]; }else{ echo "";} ?>" class="form-control" style="width: 68px;" required>
								</td>
								<td>
									<input type="text" name="int_minutos[]" id="int_minutos" value="<?php if(is_numeric($xml[2][$i])){ echo $xml[2][$i]; }else{ echo"";} ?>" class="form-control" style="width: 68px;" required>
								</td>
								<td>
									<select name="id_nivel[]" id="id_nivel"  class="form-control" style="width: 150px;" >
									<?php if ($niveles): ?>
										<?php foreach ($niveles->result() as $nivel): ?>
											<option value="<?=$nivel->id_nivel;  ?>"
												<?php if ($nivel->id_nivel==$xml[3][$i]): ?>
												<?php echo "selected" ?>	
												<?php endif ?>
											>
												<?= $nivel->vch_nivel;?>
											</option>
										<?php endforeach ?>	
									<?php endif ?>
									</select>
								</td>
								<td>
									<textarea name="txt_base[]" id="txt_base" cols="30" rows="5" class="form-control" style="width: 250px;" required><?php echo $xml[4][$i] ?></textarea>
									<input type="file" id="imgReactivo" name="imgReactivo[]" class="form-control  ignore" accept="image/jpg,image/png" style="width: 250px;">
								</td>
								<td>
									<textarea name="nvch_opcionA[]" id="nvch_opcionA" cols="30" rows="5" class="form-control" style="width: 250px;" required><?php echo $xml[5][$i] ?></textarea>
									<input type="file" id="imgOpcionA" name="imgOpcionA[]" class="form-control  ignore" accept="image/jpg,image/png" style="width: 250px;">
								</td>
								<td>
									<textarea name="nvch_argumentaA[]" id="nvch_argumentaA" cols="30" rows="5" class="form-control" style="width: 250px;" required><?php echo $xml[6][$i] ?></textarea>
								</td>
								<td>
									<textarea name="nvch_opcionB[]" id="nvch_opcionB" cols="30" rows="5" class="form-control" style="width: 250px;" required><?php echo $xml[7][$i] ?></textarea>
									<input type="file" id="imgOpcionB" name="imgOpcionB[]" class="form-control  ignore" accept="image/jpg,image/png" style="width: 250px;">
								</td>
								<td>
									<textarea name="nvch_argumentaB[]" id="nvch_argumentaB" cols="30" rows="5" class="form-control" style="width: 250px;" required><?php echo $xml[8][$i] ?></textarea>
								</td>
								<td>
									<textarea name="nvch_opcionC[]" id="nvch_opcionC" cols="30" rows="5" class="form-control" style="width: 250px;" required><?php echo $xml[9][$i] ?></textarea>
									<input type="file" id="imgOpcionC" name="imgOpcionC[]" class="form-control  ignore" accept="image/jpg,image/png" style="width: 250px;">
								</td>
								<td>
									<textarea name="nvch_argumentaC[]" id="nvch_argumentaC" cols="30" rows="5" class="form-control" style="width: 250px;" required><?php echo $xml[10][$i] ?></textarea>
								</td>
								<td>
									<textarea name="nvch_opcionD[]" id="nvch_opcionD" cols="30" rows="5" class="form-control" style="width: 250px;" required><?php echo $xml[11][$i] ?></textarea>
									<input type="file" id="imgOpcionD" name="imgOpcionD[]" class="form-control  ignore" accept="image/jpg,image/png" style="width: 250px;">
								</td>
								<td>
									<textarea name="nvch_argumentaD[]" id="nvch_argumentaD" cols="30" rows="5" class="form-control" style="width: 250px;" required><?php echo $xml[12][$i] ?></textarea>
								</td>
								<td>
									<select name="chr_correcto[]" id="chr_correcto" class="form-control" style="width: 110px;" >
										<option value="A" <?php if ('A'==$xml[13][$i]){ echo "selected='true'";}?> >Opción A</option>
										<option value="B" <?php if ('B'==$xml[13][$i]){ echo "selected='true'";}?> >Opción B</option>
										<option value="C" <?php if ('C'==$xml[13][$i]){ echo "selected='true'";}?> >Opción C</option>
										<option value="D" <?php if ('D'==$xml[13][$i]){ echo "selected='true'";}?> >Opción D</option>
									</select>
								</td>
								<td>
									<textarea name="vch_bibliografia[]" id="vch_bibliografia" cols="30" rows="5" class="form-control" style="width: 250px;" required ><?php echo $xml[14][$i] ?></textarea>
								</td>


							</tr>
						<?php 
							}
						} 
						?>
						
					</tbody>
				</table>
			</div>
		

			<div class="campos_ocultos">
				<input type="hidden" value="" name="task" id="task">
			</div>
			<br>
			<div class="form-group">
				<div class="col col-sm-10 col-sm-offset-2">
			 		<button type="button" class="btn btn-success" id="btn_guardar">Guardar</button>
			 		<a href="<?= site_url('/docente/docente/subir_lote/') ?>" class="btn btn-danger"> Cancelar</a>
				</div>
			</div>

			<?=form_close(); ?>
		</div>
		<script src="<?= base_url() ?>assets/js/docente/fn_lote_preview.js"></script>
		
