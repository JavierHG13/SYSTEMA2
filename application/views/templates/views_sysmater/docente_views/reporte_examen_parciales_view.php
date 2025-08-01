<link rel="stylesheet" href="<?= base_url() ?>assets/css/forms.css">
<div id="box">

	<center>
		<h3>Reporte Por Parciales</h3> <br>
	</center>

	<?php
	$attributes = array(
		"class" => "form-horizontal",
		"id" => "reporte_examen",
		"name" => "reporte_examen",
		"method" => "POST"
	);
	?>

	<?= form_open("/sysmater/docente/reporte_examen_parciales", $attributes); ?>

	<?php $row = $materias->row(); ?>

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
						<option value="<?= $clave; ?>"<?= set_value('materia') == $clave ? ' selected' : '' ?>>
							<?= $nombre; ?>
						</option>
					<?php endforeach ?>
				<?php endif ?>

			</select>
			<span class="text-danger"><?php echo form_error('materia'); ?></span>
		</div>
	</div>

	<div class="form-group" id="grupo-container">
		<label for="grupo" class="control-label col-sm-2">Grupo:</label>
		<div class="col-sm-10">
			<select name="grupo" id="grupo" class="form-control" disabled>
				<option value="00">Ninguno</option>
			</select>
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
			<button type="button" class="btn btn-success" onclick="exportar()"><span class="glyphicon glyphicon-ok"></span> Generar Reporte</button>
		</div>
	</div><br><br>

	<?= form_close(); ?>
	<?= $this->session->flashdata('msg'); ?>
</div>

<script>
function exportar() {
	var materia = $("#materia").find(":selected").text();
	var tipo = $("#tipo").find(":selected").text();

	if (materia && materia !== 'Ninguno') {
		var string = "EXPORTAR A " + tipo.toUpperCase() + " " + materia + "?";
		if (confirm(string)) {
			$("#reporte_examen").submit();
		}
	} else {
		alert("Ningún tipo de reporte seleccionado.");
	}
}

// JSON generado desde PHP con los grupos por materia
const gruposPorMateria = <?= json_encode(array_reduce($materias->result(), function($result, $m) {
	$grupos = array_map('trim', explode(',', $m->GrupoAlumnos));
	$result[$m->ClaveMateria] = $grupos;
	return $result;
}, [])); ?>;

$(document).ready(function () {
	$('#materia').change(function () {
		const materiaSeleccionada = $(this).val();
		const grupoSelect = $('#grupo');
		grupoSelect.empty();

		if (materiaSeleccionada !== "00" && gruposPorMateria[materiaSeleccionada]) {
			const grupos = gruposPorMateria[materiaSeleccionada];

			grupoSelect.append(`<option value="TODOS">Todos</option>`);
			grupos.forEach(grupo => {
				grupoSelect.append(`<option value="${grupo}">${grupo}</option>`);
			});

			grupoSelect.prop('disabled', false);
		} else {
			grupoSelect.prop('disabled', true).empty().append('<option value="0">Todos</option>');
		}
	});
});
</script>
