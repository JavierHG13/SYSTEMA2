<link rel="stylesheet" href="<?= base_url() ?>assets/css/forms.css">
<div id="box">
    <center>
        <h3>Nuevo Alumno</h3>
    </center>
    <?php echo form_open('/sysmater/admin/nuevo_alumno'); ?>

    <div class="form-group pad" style="padding-bottom: 1px;">
        <label for="matricula" class="control-label col-sm-2">Matricula:</label>
        <div class="col-sm-10">
            <input type="number" min="0" step="1" class="form-control" id="matricula" name="matricula" placeholder="Matrícula del Alumno" oninput="this.value = this.value.slice(0, 8)" 
            value="<?php echo set_value('matricula'); ?>">
            <span class="text-danger" ><?php echo form_error('matricula'); ?></span>
        </div>
    </div><br><br>

    <div class="form-group">
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
        <label for="carrera" class="control-label col-sm-2">Carrera:</label>
        <div class="col-sm-10">
                <select name="carrera" id="carrera" class="form-control">
                    <option value="">Selecciona una carrera</option>
                </select>
             <span class="text-danger"><?php echo form_error('carrera'); ?></span>
        </div>
    </div>
    <br><br>

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
        <label for="grupo" class="control-label col-sm-2">Grupo:</label>
        <div class="col-sm-10">
                <select name="grupo" id="grupo" class="form-control">
                    <option value="">Selecciona un grupo</option>
                </select>
             <span class="text-danger"><?php echo form_error('grupo'); ?></span>
        </div>
    </div><br><br>

<div class="form-group">
        <div class="col-sm-12 text-center">
            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-floppy-saved"></span> Guardar</button>
            <a href="<?= site_url('/sysmater/admin/nuevo_alumno/') ?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span>  Cancelar</a>
        </div>
    </div><br><br>
    
    <script>
       $(document).ready(function() {
            get_carrera();
            get_materias();
        });

        function get_carrera() {
            var ok = "ok";
            var url = base_url + "sysmater/admin/nuevo_alumno/get_carreras";
            var selectedCarrera = "<?php echo set_value('carrera'); ?>";

            $.post(url, { ok: ok }, function(data) {
                var carreraSelect = $('#carrera');
                //console.log(data);
                carreraSelect.empty();

                carreraSelect.append('<option value="">Selecciona una carrera</option>');

                var response = JSON.parse(data);
                $.each(response, function(index, item) {
                    var selected = (item.chrClvCarrera === selectedCarrera) ? 'selected' : '';
                    carreraSelect.append('<option value="' + item.chrClvCarrera + '" ' + selected + '>' + item.vchNomCarrera + '</option>');
                });
            });
        }			
        function get_materias() {
            var ok = "ok";
            var url = base_url + "sysmater/admin/nuevo_alumno/get_materias_detalles";
            var selectedGrupos = "<?php echo set_value('grupo'); ?>";

            $.post(url, { ok: ok }, function(data) {
                var grupoSelect = $('#grupo');
                // console.log("datos de grupos"+data);
                grupoSelect.empty();

                grupoSelect.append('<option value="">Selecciona un grupo</option>');

                var response = JSON.parse(data);
                $.each(response, function(index, item) {
                    var selected = (item.id_grupo === grupoSelect) ? 'selected' : '';
                    grupoSelect.append('<option value="' + item.id_grupo + '" ' + selected + '>' + item.vchGrupo + '</option>');
                });
            });
        }			
	</script>

    <?php echo form_close(); ?>

</div>



<?php if (isset($msg)) : ?>
<div class="alert alert-warning">
	<?php echo $msg; ?>
</div>
<?php endif; ?>

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
    mayuscula("input#periodo"); 
    mayuscula("input#matricula"); 
</script>