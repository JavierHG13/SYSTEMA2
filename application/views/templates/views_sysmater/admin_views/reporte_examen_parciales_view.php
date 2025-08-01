<link rel="stylesheet" href="<?= base_url() ?>assets/css/forms.css">
<div id="box">
	<center>
		<h3>Reporte Por Parciales</h3>
	</center>

	<?php
	$attributes = array(
		"class" => "form-horizontal",
		"id" => "reporte_examen",
		"name" => "reporte_examen",
		"method" => "POST"
	); ?>

	<?= form_open("/sysmater/admin/reporte_examen_parciales", $attributes); ?>

	<div class="form-group">
		<label for="periodo" class="control-label col-sm-2">Periodo:</label>
		<div class="col-sm-10">
			<select name="periodo" id="periodo" class="form-control">
				<option value="00">Ninguno</option>
				<?php if ($periodos): ?>
					<?php foreach ($periodos->result() as $periodo): ?>
						<option value="<?= $periodo->periodo; ?>"<?php if(set_value('periodo') == $periodo->periodo) { echo 'selected'; } ?>><?= $periodo->periodo; ?></option>
					<?php endforeach ?>
				<?php endif ?>
			</select>
			<span class="text-danger"><?php echo form_error('periodo'); ?></span>
		</div>
	</div><br>

	
	<div class="form-group">
		<label for="materias" class="control-label col-sm-2">Materias:</label>
		<div class="col-sm-10">
			<select name="materias" id="materias" class="form-control">
				<option value="00">Ninguno</option>
			</select>
			<span class="text-danger"><?php echo form_error('materias'); ?></span>
		</div>
	</div><br>

	<div class="form-group">
		<label for="tipo" class="control-label col-sm-2">Exportar En Archivo:</label>
		<div class="col-sm-10">
			<select name="tipo" id="tipo" class="form-control" required>
				<option value="pdf">PDF</option>
				<option value="excel">EXCEL</option>
			</select>
			<span class="text-danger"><?php echo form_error('tipo'); ?></span>
		</div>
	</div><br>

	<div class="form-group row">
            <div class="col-sm-12 text-center">
			<button type="button" class="btn btn-success" onclick="exportar()" ><span class="glyphicon glyphicon-ok"></span> Generar Reporte</button>
		</div>
	</div>
	<br><br>

	<?php echo form_close(); ?>
	<?php echo $this->session->flashdata('msg'); ?>
</div>

<script>
$(document).ready(function() {
	if ($("#periodo").val() != '00') {
		get_materias();
	}
	$("#periodo").change(function() {
		get_materias();
	});
});

function exportar() {
    var materias = $("#materias").find(":selected").text();
	var tipo = $("#tipo").find(":selected").text();

    if (materias && materias !== 'Ninguno') {
        var string = "EXPORTAR A " + tipo.toUpperCase() + " " + materias + "?";
        if (confirm(string)) {
			$("#reporte_examen").submit();
        }
    } else {
        alert("Ningún tipo de reporte seleccionado.");
    }
}

function get_materias() {
    var periodo = $('#periodo').val(); 

	console.log(periodo);

    $.post(
        base_url + "/sysmater/admin/reporte_examen_parciales/get_data_materias",
        {
            periodo: periodo
        },
        function(data) {
            var materias = $('#materias');
            materias.empty(); // Clear current options
            var response = JSON.parse(data);
            materias.append('<option value="00">Ninguno</option>');
            $.each(response, function(index, materia) {
                materias.append('<option value="' + materia.vchClvMateria + '">' + materia.vchNomMateria + '</option>');
            });
        }
    );
}
</script>
