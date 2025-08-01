<link rel="stylesheet" href="<?= base_url() ?>assets/css/forms.css">

<div id="box">
	<center>
		<h3>Reporte por exámen </h3><br>
	</center>

	<?php
	$attributes = array(
		"class" => "form-horizontal",
		"id" => "reporte_examen",
		"name" => "reporte_examen",
		"method" => "POST"
	); ?>

	<?= form_open("/sysmater/docente/reporte_examen", $attributes); ?>

	<?php
		$row = $materias->row();
	?>

	<div class="campos_ocultos">
		<input type="hidden" name="periodo" id="periodo" value="<?= $row->Periodo; ?>">
	</div>

	<div class="form-group">
		<label for="materia" class="control-label col-sm-2">Nombre de la materia:</label>
		<div class="col-sm-10">
			<select name="materia" id="materia" class="form-control">
				<option value="00">Ninguno</option>
				<?php if ($materias): ?>
                    <?php
                        $materiasUnicas = [];
                        foreach ($materias->result() as $materia) {
                            if (!isset($materiasUnicas[$materia->ClaveMateria])) {
                                $materiasUnicas[$materia->ClaveMateria] = $materia->NombreMateria;
                            }
                        }
                    ?>
                    <?php foreach ($materiasUnicas as $clave => $nombre): ?>
                        <option value="<?= $clave; ?>"<?= (set_value('materia') == $clave) ? ' selected' : '' ?>>
                            <?= $nombre; ?>
                        </option>
                    <?php endforeach ?>
                <?php endif ?>

			</select>
			<span class="text-danger"><?php echo form_error('materia'); ?></span>
		</div>
	</div>

	<div class="form-group">
		<label for="examenes" class="control-label col-sm-2">Examen:</label>
		<div class="col-sm-10">
			<select name="examenes" id="examenes" class="form-control">
				<option value="00">Ninguno</option>
			</select>
			<span class="text-danger"><?php echo form_error('examenes'); ?></span>
		</div>
	</div>
    <div class="form-group">
        <label for="grupo" class="control-label col-sm-2">Grupo:</label>
        <div class="col-sm-10">
            <select name="grupo" id="grupo" class="form-control" disabled>
                <option value="00">Ninguno</option>
            </select>
            <span class="text-danger"><?php echo form_error('grupo'); ?></span>
        </div>
    </div>

	<div class="form-group">
		<label for="tipo" class="control-label col-sm-2">Exportar En Archivo:</label>
		<div class="col-sm-10">
			<select name="tipo" id="tipo" class="form-control">
				<option value="pdf">PDF</option>
				<option value="excel">EXCEL</option>
			</select>
			<span class="text-danger"><?php echo form_error('tipo'); ?></span>
		</div>
	</div><br>

	<div class="form-group">
		<div class="col-sm-12 text-center">
			<button type="button" class="btn btn-success" onclick="exportar()" ><span class="glyphicon glyphicon-ok"></span> Generar Reporte</button>
		</div>
	</div><br><br>

	<?php echo form_close(); ?>
	<?php echo $this->session->flashdata('msg'); ?>
</div>

<script>
    $(document).ready(function () {

        if ($("#materia").val() !== '00') {
            get_examenes(); 
        }

        $("#materia").change(function () {
            get_examenes();
        });

        $("#examenes").change(function () {
            cargarGrupos();
        });
    });
    
    function exportar() {
        var examenes = $("#examenes").find(":selected").text();
        var tipo = $("#tipo").find(":selected").text();

        if (examenes && examenes !== 'Ninguno') {
            var string = "EXPORTAR A " + tipo.toUpperCase() + " " + examenes + "?";
            if (confirm(string)) {
                $("#reporte_examen").submit();
            }
        } else {
            alert("Ningún tipo de reporte seleccionado.");
        }
    }

    function get_examenes() {
        const materia = $('#materia').val();
        const periodo = $('#periodo').val();

        $.post(
            base_url + "/sysmater/docente/reporte_examen/get_data_examenes",
            { materia, periodo },
            function (data) {

                const selExamenes = $('#examenes');
                const selGrupos   = $('#grupo');
                selExamenes.empty().append('<option value="00">Ninguno</option>');
                selGrupos.empty().append('<option value="00">Ninguno</option>').prop('disabled', true);

                const examenesArr = JSON.parse(data);
                selExamenes.data('info-examenes', examenesArr);

                $.each(examenesArr, function (_, examen) {
                    selExamenes.append(`<option value="${examen.id_examen}">${examen.nvch_Titulo}</option>`);
                });
            }
        );
    }

    function cargarGrupos() {
        const selExamenes = $('#examenes');
        const selGrupos   = $('#grupo');
        const examenId    = selExamenes.val();

        if (examenId === '00') {
            selGrupos.empty().append('<option value="00">Ninguno</option>').prop('disabled', true);
            return;
        }

        const examenesArr = selExamenes.data('info-examenes') || [];
        const seleccionado = examenesArr.find(e => e.id_examen == examenId);

        if (seleccionado && seleccionado.Grupos) {
            const gruposSplit = seleccionado.Grupos.split(',').map(g => g.trim());

            selGrupos.empty().append('<option value="TODOS">Todos</option>');

            gruposSplit.forEach(g => {
                selGrupos.append(`<option value="${g}">${g}</option>`);
            });

            selGrupos.prop('disabled', false);
        } else {
            selGrupos.empty().append('<option value="00">Ninguno</option>').prop('disabled', true);
        }
    }


</script>
