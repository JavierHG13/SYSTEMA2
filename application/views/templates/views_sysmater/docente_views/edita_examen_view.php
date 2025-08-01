<link rel="stylesheet" href="<?= base_url() ?>assets/css/forms.css">
<?php 
	$row = $examen->row();
?>
<div id="box">
	<center>
		<h3>Edita Examen</h3>
	</center>
	<?php 
		$attributes = array(
					"class" => "form-horizontal",
					"id" => "form_examen",
					"name" => "form_examen",
					"method" => "POST"
					);
	?>
	<?=form_open("/sysmater/docente/edita_examen/index/".$row->id_examen, $attributes);  ?>
	<div class="form-group">
		<label for="titulo" class="control-label col-sm-2">Titulo del examen</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="titulo" name="titulo" placeholder="Titulo del examen"  value="<?php 
				if (set_value('titulo')) {
					echo set_value('titulo');
				}else{
					echo $row->nvch_Titulo;
				}
				?>" oninput="this.value = this.value.slice(0, 60)">
			<span class="text-danger"><?php echo form_error('titulo'); ?></span>
		</div>
	</div>

	<div class="form-group">
		<label for="clave" class="control-label col-sm-2">Clave de acceso</label>
		<div class="col-sm-4">
			<input type="text" class="form-control" id="clave" name="clave" placeholder="Clave para ingresar al examen" maxlength="8" size="8" value="<?php 
				if (set_value('clave')) {
					echo set_value('clave');
				}else{
					echo $row->nvch_clave;
				}
				?>" oninput="this.value = this.value.slice(0, 8)">
			<span class="text-danger"><?php echo form_error('clave'); ?></span>
		</div>

		 <div class="form-group">
        <label class="control-label col-sm-2">Grupos de Alumnos</label>
        <div class="col-sm-4">
            <div id="grupos-container" class="checkbox grupos-horizontales">
                <?php if ($grupos_disponibles): 
                    foreach ($grupos_disponibles->result() as $grupo): 
                        $esta_seleccionado = in_array($grupo->vchGrupo, $grupos_actuales);
                ?>
                    <div class="grupo-item <?= $esta_seleccionado ? 'grupo-actual' : '' ?>">
                        <input type="checkbox" 
                            name="grupos[]" 
                            value="<?= $grupo->vchGrupo ?>" 
                            <?= $esta_seleccionado ? 'checked' : '' ?>
                            <?= $esta_seleccionado ? 'disabled' : '' ?>>
                        <label><?= $grupo->vchGrupo ?></label>
                        <?php if($esta_seleccionado): ?>
                            <input type="hidden" name="grupos[]" value="<?= $grupo->vchGrupo ?>">
                        <?php endif; ?>
                    </div>
                <?php 
                    endforeach;
                else: 
                ?>
                    <p>No hay grupos disponibles para esta materia.</p>
                <?php endif; ?>
            </div>
            <span class="text-danger"><?php echo form_error('grupos[]'); ?></span>
        </div>
    </div>
		
	</div>
		<div class="form-group">
		 <label for="nreactivos" class="control-label col-sm-2">Reactivos Requeridos</label>
    <div class="col-sm-4">
        <input type="number" class="form-control" id="nreactivos" name="nreactivos" placeholder="Reactivos Requeridos" maxlength="2" size="2" value="<?php 
            if (set_value('nreactivos')) {
                echo set_value('nreactivos');
            }else{
                echo $row->int_reactivos_requeridos;
            }
        ?>" oninput="this.value = this.value.slice(0, 2)">
        <span class="text-danger"><?php echo form_error('nreactivos'); ?></span>

        <div id="error-reactivos" class="alert alert-info text-center" style="display: none;">
			El número de reactivos requeridos no puede ser menor a los reactivos ya existentes (<?= $row->int_reactivos_requeridos ?>).
		</div>

    </div>

		<label for="parcial" class="control-label col-sm-2">Parcial</label>
		<div class="col-sm-4">
			<input type="text" class="form-control" id="parcial" name="parcial" placeholder="Parcial del exámen" maxlength="8" size="8" value="<?php 
				if (set_value('clave')) {
					echo set_value('clave');
				}else{
					echo $row->parcial;
				}
				?>" disabled>
			<span class="text-danger"><?php echo form_error('parcial'); ?></span>
		</div>
		
	</div>

	<div class="form-group">		
		<label for="tipo_examen" class="control-label col-sm-2">Tipo de Examen</label>
		<div class="col-sm-10">
			<?php if ($examen): ?>
					<?php foreach ($examen->result() as $datos): ?>
						<input type="text" value="<?= $datos->Tipo_Examen; ?>" name="tipo" id="tipo" class="form-control" disabled>
						<input type="hidden" name="tipo_examen" id="tipo_examen" value="<?= $datos->tipo_Examen; ?>">
					<?php endforeach ?>
			<?php endif ?>
			<span class="text-danger"><?php echo form_error('tipo_examen'); ?></span>
		</div>
	</div>

	<div class="form-group">
		<label for="" class="control-label col-sm-2">Materia</label>
		<div class="col-sm-10">
			<?php if ($examen): ?>
					<?php foreach ($examen->result() as $datos): ?>
						<input type="text" value="<?= $datos->vchNomMateria; ?>" name="materia" id="materia" class="form-control" disabled>
					<?php endforeach ?>
			<?php endif ?>
		</div>
	</div>

	<div>
		<input type="hidden" name="materia" id="materia" value="<?= $datos->vchClvMateria; ?>">
	</div>

	<div class="form-group row">
		<div class="col-sm-12 text-center">
			<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-floppy-saved"></span> Guardar</button>
			<a href="<?= site_url('/sysmater/docente/docente/examenes_registrados/') ?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span>  Cancelar</a>
		</div>
	</div>
		<?php if (isset($msg) && $msg): ?>
            <div class="alert alert-danger text-center mt-3">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>
	<?=form_close(); ?>
</div>
<script>
	mayuscula("input#titulo");
    // Número actual de reactivos ya existentes (desde PHP)
    const reactivosActuales = <?= $row->int_reactivos_requeridos ?>;

    document.getElementById('form_examen').addEventListener('submit', function(event) {
        const input = document.getElementById('nreactivos');
        const valor = parseInt(input.value.trim());
        const mensajeError = document.getElementById('error-reactivos');

        // Validaciones:
        if (isNaN(valor) || valor <= 1 || valor < reactivosActuales) {
            mensajeError.style.display = 'block';
            input.focus();
            event.preventDefault(); // Detiene el envío del formulario
        } else {
            mensajeError.style.display = 'none'; // Todo bien, oculta el mensaje
        }
    });
</script>
