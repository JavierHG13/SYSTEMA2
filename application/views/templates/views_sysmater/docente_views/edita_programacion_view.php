<!-- ALEXIS YAZIR -->
<link rel="stylesheet" href="<?= base_url() ?>assets/css/forms.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-material-datetimepicker.css" />
<link href='http://fonts.googleapis.com/css?family=Roboto:400,500' rel='stylesheet' type='text/css'>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script type="text/javascript" src="<?= base_url() ?>assets/js/moment-with-locales.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/bootstrap-material-datetimepicker.js"></script>

<?php
$row = $aplicacion->row();
?>
<?php if (isset($examen) && !empty($examen)) : ?>
    <?php foreach ($examen->result() as $datos) : ?>
        <?php $titulo = $datos->nvch_Titulo; ?>
        <?php $id_examen = $datos->id_examen; ?>
    <?php endforeach; ?>
<?php endif; ?>
<?php if (isset($gruposs) && !empty($gruposs)) : ?>
    <?php foreach ($gruposs->result() as $datos_g) : ?>
        <?php $grupo = $datos_g; ?>
    <?php endforeach; ?>
<?php endif; ?>

    <div id="box">
		
		<div class="header-section mb-4 mt-4 p-3" style=" border-radius: 5px; padding-top:1px; padding-bottom:1px; margin-bottom:20px;">
			<h2 class="text-center">Edita Programación de Examen</h2>
            <h4 class="text-center" style="color: #4A5C42;"><?php echo $titulo; ?> para Grupo - <?php echo $grupo; ?></h4>
        </div>

        <?php
        $attributes = array(
            "class" => "form-horizontal",
            "id" => "form_examen",
            "name" => "form_examen",
            "method" => "POST"
        );
        ?>
        <?= form_open("/sysmater/docente/guardar_edicion_programacion/index/{$id_examen}/{$grupo}", $attributes);  ?>
<div  style="padding-right: 100px;">
    <div class="form-group row">
        <label for="fecha1" class="control-label col-sm-2 col-form-label">Desde</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" id="fecha1" name="fecha1" placeholder="dd/mm/aaaa" maxlength="10" size="10" value="<?php
                if (set_value('fecha1')) {
                echo set_value('fecha1');
                } else {
                $date = date_create($row->fch_inicia);
                echo  date_format($date, 'd/m/Y');
                }
                ?>">
            <span class="text-danger"><?php echo form_error('fecha1'); ?></span>
        </div>
        <label for="fecha2" class="control-label col-sm-2 col-form-label">Hasta</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" id="fecha2" name="fecha2" placeholder="dd/MM/YYYY" maxlength="10" size="10" value="<?php
                if (set_value('fecha2')) {
                    echo set_value('fecha2');
                } else {
                    $date = date_create($row->fch_termina);
                    echo  date_format($date, 'd/m/Y');
                }
                ?>">
            <span class="text-danger"><?php echo form_error('fecha2'); ?></span>
        </div>
    </div>
    
    <div class="form-group row">
        <label for="inicio" class="control-label col-sm-2 col-form-label">Inicio</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" id="inicio" name="inicio" placeholder="HH:mm" maxlength="5" size="5" value="<?php
                if (set_value('inicio')) {
                    echo set_value('inicio');
                } else {
                    $date = date_create($row->tm_hora_inicio);
                    echo  date_format($date, 'H:i');
                }
                ?>">
            <span class="text-danger"><?php echo form_error('inicio'); ?></span>
        </div>
        <label for="termino" class="control-label col-sm-2 col-form-label">Termino</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" id="termino" name="termino" placeholder="HH:mm" maxlength="5" size="5" value="<?php
                if (set_value('termino')) {
                    echo set_value('termino');
                } else {
                    $date = date_create($row->tm_hora_final);
                    echo  date_format($date, 'H:i');
                }
                ?>">
            <span class="text-danger"><?php echo form_error('termino'); ?></span>
        </div>
    </div>

    <div class="form-group row">
        <label for="duracion" class="control-label col-sm-2 col-form-label">Duración</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" id="duracion" name="duracion" placeholder="HH:mm" maxlength="5" size="5" value="<?php
                if (set_value('duracion')) {
                echo set_value('duracion');
                } else {
                $date = date_create($row->tm_duracion);
                echo  date_format($date, 'H:i');
                }
                ?>">
            <span class="text-danger"><?php echo form_error('duracion'); ?></span>
        </div>
    </div>
</div>

       
<center>

	<div class="form-group row">
		<div class="">
			<button type="submit" class="btn btn-success mr-2">
				<span class="glyphicon glyphicon-floppy-saved"></span>  Guardar
			</button>
			<a href="<?= site_url('/sysmater/docente/docente/examenes_programados/') ?>" class="btn btn-danger">
				<span class="glyphicon glyphicon-remove"></span> Cancelar
			</a>
		</div>
	</div>
</center>
  <?php if (isset($msg)): ?>
            <div class="alert alert-info text-center">
                <?= $msg ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <?= form_close(); ?>
    </div>

<script>
    $(document).ready(function() {
        $('#fecha1').bootstrapMaterialDatePicker({
            lang: 'sp',
            weekStart: 0,
            time: false,
            format: 'DD/MM/YYYY'
        });
        $('#fecha2').bootstrapMaterialDatePicker({
            lang: 'sp',
            weekStart: 0,
            time: false,
            format: 'DD/MM/YYYY'
        });
        $('#inicio').bootstrapMaterialDatePicker({
            date: false,
            shortTime: false,
            format: 'HH:mm'
        });
        $('#termino').bootstrapMaterialDatePicker({
            date: false,
            shortTime: false,
            format: 'HH:mm'
        });
        $('#duracion').bootstrapMaterialDatePicker({
            date: false,
            shortTime: false,
            format: 'HH:mm'
        });
    });
</script>