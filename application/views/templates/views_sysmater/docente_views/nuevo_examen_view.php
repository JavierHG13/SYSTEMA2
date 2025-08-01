<link rel="stylesheet" href="<?= base_url() ?>assets/css/forms.css">

    <div id="box">
        <div class="header-section text-center">
            <h2>Nuevo Examen</h2>
        </div>

        <?php
        $attributes = array(
            "class" => "form-horizontal",
            "id" => "form_examen",
            "name" => "form_examen",
            "method" => "POST"
        );
        ?>
        
        <?php $row = $materias->row(); ?>
        <?= form_open("/sysmater/docente/nuevo_examen", $attributes); ?>
        
        <?= form_hidden('periodo', $row->Periodo); ?>
        <?= form_hidden('Cuatrimestre', $row->Cuatrimestre); ?>

        <div class="form-group row">
            <label for="tipo_examen" class="control-label col-sm-2 col-form-label">Tipo de Examen</label>
            <div class="col-sm-10">
                <select name="tipo_examen" id="tipo_examen" class="form-control">
                    <option value="0">Seleccione una opción</option>
                    <?php if ($tipos_examen) : ?>
                        <?php foreach ($tipos_examen->result() as $tipo) : ?>
                            <option value="<?= $tipo->ID; ?>" <?= set_value('tipo_examen') == $tipo->ID ? 'selected' : '' ?>>
                                <?= $tipo->Tipo_Examen; ?>
                            </option>
                        <?php endforeach ?>
                    <?php endif ?>
                </select>
                <span class="text-danger"><?= form_error('tipo_examen'); ?></span>
            </div>
        </div>

        <div class="form-group row">
            <label for="materia" class="control-label col-sm-2 col-form-label">Materias</label>
            <div class="col-sm-10">
                <select name="materia" id="materia" class="form-control">
                    <?php 
                        $hayMateriaVacia = false;
                        if ($materias && $materias->num_rows() > 0) {
                            foreach ($materias->result() as $materia) {
                                if ($materia->ClaveMateria == '') { $hayMateriaVacia = true; break; }
                            }
                        } 
                        if ($hayMateriaVacia || !$materias || $materias->num_rows() == 0): ?>
                            <option value="0" selected disabled>No tiene materias asignadas</option>
                        <?php else: ?>
                            <option value="0">Seleccione una opción</option>
                            <?php foreach ($materias->result() as $materia): ?>
                                <option value="<?= $materia->ClaveMateria; ?>" <?= set_value('materia') == $materia->ClaveMateria ? 'selected' : '' ?>>
                                    <?= $materia->NombreMateria; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                </select>
                <span class="text-danger"><?= form_error('materia'); ?></span>
            </div>
        </div>

        <div class="form-group row">
            <label class="control-label col-sm-2 col-form-label">Grupos</label>
            <div class="col-sm-10">
                <div id="grupos-container" class="checkbox">
                    <p class="text-muted">Seleccione una materia para ver sus grupos.</p>
                    <span class="text-danger"><?= form_error('grupos[]'); ?></span>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label for="parcial" class="control-label col-sm-2 col-form-label">Parcial</label>
            <div class="col-sm-10">
                <select name="parcial" id="parcial" class="form-control">
                    <option value="0">Seleccione una opción</option>
                    <option value="1" <?= set_value('parcial') == '1' ? 'selected' : '' ?>>Parcial 1</option>
                    <option value="2" <?= set_value('parcial') == '2' ? 'selected' : '' ?>>Parcial 2</option>
                    <option value="3" <?= set_value('parcial') == '3' ? 'selected' : '' ?>>Parcial 3</option>
                </select>
                <span class="text-danger"><?= form_error('parcial'); ?></span>
            </div>
        </div>

        <div class="form-group row">
            <label for="titulo" class="control-label col-sm-2 col-form-label">Título del examen</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Título del examen" value="<?= set_value('titulo'); ?>" oninput="this.value = this.value.slice(0, 60)">
                <span class="text-danger"><?= form_error('titulo'); ?></span>
            </div>
        </div>

        <div class="form-group row">
            <label for="nreactivos" class="control-label col-sm-2 col-form-label">Reactivos Requeridos</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="nreactivos" name="nreactivos" placeholder="Reactivos Requeridos" maxlength="2" value="<?= set_value('nreactivos'); ?>">
                <span class="text-danger"><?= form_error('nreactivos'); ?></span>
            </div>
            <label for="clave" class="control-label col-sm-2 col-form-label">Clave de acceso</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="clave" name="clave" placeholder="Clave para ingresar al examen" maxlength="8" value="<?= set_value('clave'); ?>">
                <span class="text-danger"><?= form_error('clave'); ?></span>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-12 text-center">
                <button type="submit" class="btn btn-success mr-3">
                    <span class="glyphicon glyphicon-floppy-saved"></span> Guardar
                </button>
                <a href="<?= site_url('/sysmater/docente/docente/examenes_registrados/') ?>" class="btn btn-danger">
                    <span class="glyphicon glyphicon-remove"></span>  Cancelar
                </a>
            </div>
        </div>
        
        <?php if (isset($msg) && $msg): ?>
            <div class="alert alert-danger text-center mt-3">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>
        <?= form_close(); ?>
    </div>

<script>
    $(document).ready(function() {
        $("#nreactivos").keydown(function(e) {
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 || 
                (e.keyCode == 65 && e.ctrlKey === true) || 
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                return;
            }
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && 
                (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    });

    mayuscula("input#titulo");

    const materias = <?= json_encode($materias->result()); ?>;
    const materiaSelect = document.getElementById('materia');
    const gruposContainer = document.getElementById('grupos-container');

    materiaSelect.addEventListener('change', function() {
        const selectedMateria = this.value;
        gruposContainer.innerHTML = '';

        if (selectedMateria) {
            const selectedMateriaData = materias.find(materia => materia.ClaveMateria === selectedMateria);

            if (selectedMateriaData) {
                // Actualizar campos ocultos
                document.querySelector('input[name="periodo"]').value = selectedMateriaData.Periodo;
                document.querySelector('input[name="Cuatrimestre"]').value = selectedMateriaData.Cuatrimestre;

                if (selectedMateriaData.Grupos) { 
                    const grupos = selectedMateriaData.Grupos.split(',').map(g => g.trim());

                    if (grupos.length > 0 && grupos[0] !== '') {
                        // Contenedor principal
                        const divContainer = document.createElement('div');
                        divContainer.className = 'grupos-horizontales';
                        
                        grupos.forEach(grupo => {
                            const divGroup = document.createElement('div');
                            divGroup.className = 'grupo-item';
                            
                            const input = document.createElement('input');
                            input.type = 'checkbox';
                            input.className = 'form-check-input';
                            input.id = `grupo-${grupo}`;
                            input.name = 'grupos[]';
                            input.value = grupo;

                            const label = document.createElement('label');
                            label.htmlFor = `grupo-${grupo}`;
                            label.textContent = grupo;

                            divGroup.appendChild(input);
                            divGroup.appendChild(label);
                            divContainer.appendChild(divGroup);
                        });
                        
                        gruposContainer.appendChild(divContainer);
                    } else {
                        gruposContainer.innerHTML = '<div class="alert alert-info">No hay grupos disponibles.</div>';
                    }
                } else {
                    gruposContainer.innerHTML = '<div class="alert alert-info">No hay grupos disponibles.</div>';
                }	
            } else {
                gruposContainer.innerHTML = '<div class="alert alert-warning">Datos no encontrados.</div>';
            }
        } else {
            gruposContainer.innerHTML = '<div class="alert alert-secondary">Seleccione una materia.</div>';
        }
    });
</script>