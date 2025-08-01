<link rel="stylesheet" href="<?= base_url() ?>assets/css/forms.css">

<div id="box">
    <center>
        <h3>Nueva Materia</h3>
    </center>
    <?php echo form_open('/sysmater/admin/nueva_materia'); ?>

    <div class="form-group" style="padding-bottom: 1px;">
        <label for="clave" class="control-label col-sm-2">Clave:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="clave" name="clave" placeholder="Clave de materia" value="<?php echo set_value('clave'); ?>" oninput="this.value = this.value.slice(0, 8)">
            <span class="text-danger"><?php echo form_error('clave'); ?></span>
        </div>
    </div><br><br>

    <div class="form-group">
        <label for="nombre" class="control-label col-sm-2">Nombre de materia:</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre materia" value="<?php echo set_value('nombre'); ?>" oninput="this.value = this.value.slice(0, 100)">
            <span class="text-danger"><?php echo form_error('nombre'); ?></span>
        </div>
    </div><br><br>

    <div class="form-group">
    <label for="periodo" class="control-label col-sm-2">Periodo:</label>
    <div class="col-sm-10">
        <select class="form-control" id="periodo" name="periodo">
            <option value="">Seleccione un periodo</option>
            <?php
            $periodos = array(
                '1' => 'Enero-Abril',
                '2' => 'Mayo-Agosto',
                '3' => 'Septiembre-Diciembre',
                '4' => '4'
            );
            foreach ($periodos as $value => $label) {
                $selected = (set_value('periodo') == $value) ? 'selected' : '';
                echo '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
            }
            ?>
        </select>
        <span class="text-danger"><?php echo form_error('periodo'); ?></span>
    </div>
</div><br><br>

<div class="form-group">
    <label for="cuatrimestre" class="control-label col-sm-2">Cuatrimestre:</label>
    <div class="col-sm-10">
        <select class="form-control" id="cuatrimestre" name="cuatrimestre">
            <option value="">Seleccione un cuatrimestre</option>
            <?php
            for ($i = 1; $i <= 10; $i++) {
                $selected = (set_value('cuatrimestre') == $i) ? 'selected' : '';
                echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
            }
            ?>
        </select>
        <span class="text-danger"><?php echo form_error('cuatrimestre'); ?></span>
    </div>
</div><br><br>


    <div class="form-group">
        <div class="col-sm-12 text-center">
            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-floppy-saved"></span> Guardar</button>
            <a href="<?= site_url('/sysmater/admin/admin/nueva_materia/') ?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span>  Cancelar</a>
        </div>
    </div><br><br>

    <?php echo form_close(); ?>

</div>

<?php if ($this->session->flashdata('error_msg')) : ?>
<div class="alert alert-danger alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <?php echo $this->session->flashdata('error_msg'); ?>
</div>
<?php endif; ?>

<?php if (isset($msg)) : ?>
<div class="alert alert-warning">
    <?php echo $msg; ?>
</div>
<?php endif; ?>


<script>
    $(document).ready(function() {
        $("#cuatrimestre").keydown(function(e) {
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 || (e.keyCode == 65 && e.ctrlKey === true) || (e.keyCode >= 35 && e.keyCode <= 39)) {
                return;
            }
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    });
    mayuscula("input#nombre");
    mayuscula("input#periodo"); 
    mayuscula("input#clave");
</script>