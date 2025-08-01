<link rel="stylesheet" href="<?= base_url() ?>assets/css/forms.css">
<div id="box">
			<center>
				<h3>Reporte por examen</h3>
			</center>

	<?php
	$attributes = array(
		"class" => "form-horizontal",
		"id" => "reporte_examen",
		"name" => "reporte_examen",
		"method" => "POST"
	); ?>

	<?= form_open("/sysmater/admin/reporte_examen", $attributes); ?>

	<?php
		$row = $materias->row();
	?>

	<div class="form-group">
		<label for="materia" class="control-label col-sm-2">Nombre de la materia:</label>
		<div class="col-sm-10">
			<select name="materia" id="materia" class="form-control">
				<option value="00">Ninguno</option>
				<?php if ($materias): ?>
					<?php foreach ($materias->result() as $materia): ?>
						<option value="<?= $materia->vchClvMateria; ?>"<?php if(set_value('materia') == $materia->vchClvMateria) { echo 'selected'; } ?>><?= $materia->vchNomMateria; ?></option>
					<?php endforeach ?>
				<?php endif ?>
			</select>
			<span class="text-danger"><?php echo form_error('materia'); ?></span>
		</div>
	</div><br>

	<div class="form-group">
		<label for="examenes" class="control-label col-sm-2">Examen:</label>
		<div class="col-sm-10">
			<select name="examenes" id="examenes" class="form-control">
				<option value="00">Ninguno</option>
			</select>
			<span class="text-danger"><?php echo form_error('examenes'); ?></span>
		</div>
	</div><br>

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
        <div class="form-group row">
            <div class="col-sm-12 text-center">
			<button type="button" class="btn btn-success" onclick="exportar()" ><span class="glyphicon glyphicon-ok"></span> Generar Reporte</button>
		</div>
	</div>

	<?php echo form_close(); ?>
	<?php echo $this->session->flashdata('msg'); ?>
</div>

<script>
$(document).ready(function() {
	if ($("#materia").val() != '00') {
		get_examenes();
	}
	$("#materia").change(function() {
		get_examenes();
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
    var materia = $('#materia').val();
    var periodo = $('#periodo').val(); 

    $.post(
        base_url + "/sysmater/admin/reporte_examen/get_data_examenes",
        {
            materia: materia,
            periodo: periodo
        },
        function(data) {
            var examenes = $('#examenes');
            examenes.empty(); // Clear current options
            var response = JSON.parse(data);
            examenes.append('<option value="00">Ninguno</option>');
            $.each(response, function(index, examen) {
                examenes.append('<option value="' + examen.id_examen + '">' + examen.nvch_Titulo + '</option>');
            });
        }
    );
}
</script>
