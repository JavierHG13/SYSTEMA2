<link rel="stylesheet" href="<?= base_url() ?>assets/css/forms.css">


<div id="box">
    <center>
        <h3>Nuevo Docente</h3>
    </center>
    <?php echo form_open('/sysmater/admin/nuevo_docente'); ?>

    <div class="form-group pad" style="padding-bottom: 1px;">
        <label for="nombre" class="control-label col-sm-2">Nombre:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del Docente" value="<?php echo set_value('nombre'); ?>" oninput="this.value = this.value.slice(0, 25)">
            <span class="text-danger"><?php echo form_error('nombre'); ?></span>
        </div>
    </div><br><br>

    <div class="form-group">
        <label for="apellido_paterno" class="control-label col-sm-2">Apellido Paterno:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" placeholder="Apellido Paterno" value="<?php echo set_value('apellido_paterno'); ?>" oninput="this.value = this.value.slice(0, 25)">
            <span class="text-danger"><?php echo form_error('apellido_paterno'); ?></span>
        </div>
    </div><br><br>

    <div class="form-group">
        <label for="apellido_materno" class="control-label col-sm-2">Apellido Materno:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" placeholder="Apellido Materno" value="<?php echo set_value('apellido_materno'); ?>" oninput="this.value = this.value.slice(0, 25)">
            <span class="text-danger"><?php echo form_error('apellido_materno'); ?></span>
        </div>
    </div><br><br>

    <div class="form-group">
        <label for="clave" class="control-label col-sm-2">Clave Del Trabajador:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="clave" name="clave" placeholder="Clave Del Trabajador" maxlength="4" value="<?php echo set_value('clave'); ?>">
            <span class="text-danger"><?php echo form_error('clave'); ?></span>
        </div>
    </div><br><br>


    <div class="form-group">
        <div class="col-sm-12 text-center">
            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-floppy-saved"></span> Guardar</button>
            <a href="<?= site_url('/sysmater/admin/admin/lista_docente/') ?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span>  Cancelar</a>
        </div>
    </div><br><br>

    <?php echo form_close(); ?>

    
    <?php if (isset($msg)) : ?>
    <div class="alert alert-danger text-center">
        <?php echo $msg; ?>
    </div>
    <?php endif; ?>
</div>





<script>
    $(document).ready(function() {
        $("#clave").keydown(function(e) {
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 || (e.keyCode == 65 && e.ctrlKey === true) || (e.keyCode >= 35 && e.keyCode <= 39)) {
                return;
            }
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    });
    mayuscula("input#nombre");
    mayuscula("input#apellido_paterno");
    mayuscula("input#apellido_materno");
</script>