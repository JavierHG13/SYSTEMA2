<link rel="stylesheet" href="<?= base_url() ?>assets/css/forms.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-material-datetimepicker.css" />
<link href='http://fonts.googleapis.com/css?family=Roboto:400,500' rel='stylesheet' type='text/css'>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script type="text/javascript" src="<?= base_url() ?>assets/js/moment-with-locales.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/bootstrap-material-datetimepicker.js"></script>

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
        <div class="header-section">
            <h2 class="text-center">Programar Aplicación de Examen</h2>
            <h4 class="text-center"><?php echo $titulo; ?> para Grupo - <?php echo $grupo; ?></h4>
        </div>

        <?php
        $attributes = array(
            "class" => "form-horizontal",
            "id" => "form_examen",
            "name" => "form_examen",
            "method" => "POST"
        );
        ?>

        <?= form_open("/sysmater/docente/guardar_programacion/index/{$id_examen}/{$grupo}", $attributes);  ?>
        <div style="padding-right: 100px;">

            <div class="form-group row">
                <label for="fecha1" class="control-label col-sm-2 col-form-label">Desde</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="fecha1" name="fecha1" placeholder="dd/mm/aaaa" maxlength="10" value="<?php echo set_value('fecha1'); ?>" required>
                    <span class="text-danger"><?php echo form_error('fecha1'); ?></span>
                </div>
                <label for="fecha2" class="control-label col-sm-2 col-form-label">Hasta</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="fecha2" name="fecha2" placeholder="dd/mm/aaaa" maxlength="10" value="<?php echo set_value('fecha2'); ?>" required>
                    <span class="text-danger"><?php echo form_error('fecha2'); ?></span>
                </div>
            </div>
    
            <div class="form-group row">
                <label for="inicio" class="control-label col-sm-2 col-form-label">Inicio</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="inicio" name="inicio" placeholder="HH:mm" maxlength="5" value="<?php echo set_value('inicio'); ?>" required>
                    <span class="text-danger"><?php echo form_error('inicio'); ?></span>
                </div>
                <label for="termino" class="control-label col-sm-2 col-form-label">Termino</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="termino" name="termino" placeholder="HH:mm" maxlength="5" value="<?php echo set_value('termino'); ?>" required>
                    <span class="text-danger"><?php echo form_error('termino'); ?></span>
                </div>
            </div>
    
            <div class="form-group row">
                <label for="duracion" class="control-label col-sm-2 col-form-label">Duración</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="duracion" name="duracion" placeholder="HH:mm" maxlength="5" value="<?php echo set_value('duracion'); ?>" required>
                    <span class="text-danger"><?php echo form_error('duracion'); ?></span>
                </div>
            </div>
        </div>

        
        <div class="form-group row">
            <div class="col-sm-12 text-center">
                <button type="submit" class="btn btn-success mr-3">
                    <span class="glyphicon glyphicon-floppy-saved"></span> Guardar
                </button>
                <a href="<?= site_url('/sysmater/docente/docente/examenes_pendientes/') ?>" class="btn btn-danger">
                    <span class="glyphicon glyphicon-remove"></span> Cancelar
                </a>
            </div>
        </div>
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