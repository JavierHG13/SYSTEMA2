<?php 
	$row = $examen->row();
?>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/reactivos.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/tablas.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/forms.css">
<style>
	#contador-seleccionados {
		font-weight: bold;
		color: #337ab7;
	}

	#mensaje-maximo {
		margin-top: 5px;
		color: black;
		display: none;
	}
	.reactivo-card.selected {
		background-color: #80ff9e2f !important;
		border-left: 8px solid #28a745;
	}

	.reactivo-checkbox:disabled {
		cursor: not-allowed;
		opacity: 0.6;
	}
	.img-tooltip-trigger {
	display: inline-block;
	cursor: pointer;
	position: relative;
	}

	.img-tooltip-trigger:hover .img-tooltip-content {
		display: block;
	}

	.img-tooltip-content {
		display: none;
		position: absolute;
		top: 100%;
		left: 0;
		z-index: 9999;
		background: white;
		padding: 5px;
		border: 1px solid #ccc;
		box-shadow: 0 2px 8px rgba(0,0,0,0.2);
		width: 200px;
		max-height: 200px;
		overflow: hidden;
	}

	.img-tooltip-content img {
		max-width: 100%;
		max-height: 100%;
	}


	.maximo-alcanzado {
		border-left: 4px solid #d9534f;
	}

	.scrolldiv {
		overflow-x:hidden;
		overflow-y:visible;
		height:500px;
		padding: 10px;
	}

	.reactivo-card {
		margin-bottom: 20px;
		border-radius: 12px;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.16);
		transition: transform 0.2s ease, box-shadow 0.2s ease;
		border: 1px solid #e0e0e0;
		border-left: 8px solid #28a7463a;
	}

	.reactivo-card:hover {
		transform: translateY(-1px);
		box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
	}

	.reactivo-card .panel-heading {
		padding: 16px 20px;
		color:black;
		border-radius: 12px;
	}

	.reactivo-card .panel-title {
		display: flex;
		align-items: center;
		flex-grow: 1;
		font-size: 15px;
		color: #3c903e;
		margin: 0;
	}
	.panel-body p,
	.panel-body .opcion,
	.panel-body .metadata {
		margin-bottom: 10px;
	}

	.panel-body h5 {
		margin-top: 15px;
		color: #28a745;
		border-bottom: 1px solid #ccc;
		padding-bottom: 5px;
	}


	.reactivo-card .panel-title a {
		flex-grow: 1;
		margin-left: 10px;
		text-decoration: none;
		transition: color 0.1s ease;
	}

	.reactivo-card .panel-body {
		padding: 20px;
		font-size: 15px;
		color: #333;
	}


	.opcion {
		margin-bottom: 15px;
		padding: 10px;
		background-color: #f9f9f9;
		border-radius: 4px;
	}

	.argumento {
		margin-top: 5px;
		padding-left: 10px;
		font-size: 0.9em;
		color: #666;
	}

	.imagen-opcion, .imagen-base {
		margin-top: 10px;
		padding: 5px;
		background: white;
		border: 1px solid #ddd;
		border-radius: 4px;
		text-align: center;
	}

	.metadata {
		background: #f5f5f5;
		padding: 15px;
		border-radius: 4px;
		font-size: 0.9em;
	}

	.badge {
		margin-left: 10px;
		background-color: #5bc0de;
	}
	.reactivo-checkbox {
		accent-color: #28a745;
		width: 18px;
		height: 18px;
		margin-right: 12px;
		cursor: pointer;
		transition: all 0.2s ease;
	}

	.reactivo-checkbox:checked {
		box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.2);
	}

	.opcion-correcta {
		background-color: #d4edda !important;
		border-left: 5px solid #28a745;
	}

</style>

<div id="box">
	<center>
		<h3>Seleccionar reactivos para examen</h3>
	</center>
	<div class="form-group">
		<label class="control-label col-sm-2">Materia</label>
		<div class="col-sm-4">
			<figure class="highlight">
				<?=$row->vchNomMateria; ?> - <?=$row->vchClvMateria; ?>
			</figure>
		</div>
		<input type="hidden" id="materia" name="materia" value="<?=$row->vchClvMateria; ?>">
		<input type="hidden" id="id_examen" name="id_examen" value="<?=$row->id_examen; ?>">
	</div>
	<div class="form-group">
		<label for="titulo" class="control-label col-sm-2">Título del examen</label>
		<div class="col-sm-4">
			<figure class="highlight">
				<?=$row->nvch_Titulo; ?>
			</figure>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-2">Reactivos Requeridos</label>
		<div class="col-sm-4">
			<figure class="highlight">
				<?=$row->int_reactivos_requeridos; ?>
			</figure>
		</div>
		<!-- <p>Total de reactivos seleccionados: <?= $rSelec->rSeleccionados?></p> -->

		<label class="control-label col-sm-2">Reactivos Seleccionados</label>
		<div class="col-sm-4">
			<figure class="highlight">
				<span id="contador-seleccionados">0</span> / 
				<span id="reactivos-requeridos"><?=$row->int_reactivos_requeridos; ?></span>
			</figure>
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-12">
			<div id="mensaje-maximo" class="alert alert-info text-center" style="display: none;">
				<strong>
					Muy bien ¡Ya has seleccionado todos los reactivos requeridos para este exámen! puedes continuar: <br> <br>
					<div class="btn-group btn-group-xs" role="group" aria-label="..."> 
					<a href="<?= site_url('sysmater/docente/docente/examenes_pendientes/') ?>" 
						class="btns btn btn-success" style="margin-bottom:5px;">
						<span class="glyphicon glyphicon-time"></span> PROGRAMAR EXÁMEN
					</a>
					<a href="<?= site_url('/sysmater/docente/docente/ver_examen/index/'.$row->id_examen) ?>" class='btns btn btn-default' title="Vista previa">
						<span class="glyphicon glyphicon-eye-open"></span> VER EXÁMEN
					</a>
					<a href="<?= site_url('sysmater/docente/docente/examenes_registrados/') ?>" 
						class="btns btn btn-info" style="margin-bottom:5px;">
						<span class="glyphicon glyphicon-th-list"></span> VER EXÁMENES
					</a>
					</div>
				</strong>
			</div>
			<center><h3>Reactivos disponibles de la materia <?=$row->vchNomMateria; ?></h3></center>
		</div>
	</div>
	
	<?php 
		$attributes = array(
			"class" => "form-horizontal",
			"id" => "form_examen",
			"name" => "form_examen",
			"method" => "POST"
		);
		?>
	<?=form_open("/sysmater/admin/reactivos_examen/index/".$row->id_examen, $attributes);  ?>

		<center>

			<div class="btn-group btn-group-xs" role="group" aria-label="...">
				<button type="button" id="cmd_all" class="btns btn btn-xs btn-primary">
					<span class="glyphicon glyphicon-check"></span> SELECCIONAR TODO
				</button>
				<button type="button" id="cmd_none" class="btns btn btn-xs btn-danger">
					<span class="glyphicon glyphicon-unchecked"></span> DESELECCIONAR TODO
				</button>
				<a href="<?= site_url('sysmater/docente/docente/examenes_registrados/') ?>" 
						class="btns btn btn-warning" style="margin-bottom:5px;">
						<span class="glyphicon glyphicon-remove"></span> CANCELAR
					</a>
			</div>
		</center>


	<div id="tbl_examen" class="scrolldiv"></div>

	<?=form_close(); ?>
</div>

<script>
let seleccionados = <?= $rSelec->rSeleccionados ?>;
let reactivosRequeridos = 0;

$(document).ready(function(){
	reactivosRequeridos = parseInt($('#reactivos-requeridos').text());
	actualizarContador();

	$('#cmd_all').click(function(){
		let disponibles = $('.reactivo-checkbox:not(:checked)').length;
		let aSeleccionar = Math.min(reactivosRequeridos - seleccionados, disponibles);
		let cont = 0;

		$('.reactivo-checkbox:not(:checked)').each(function(){
			if (cont < aSeleccionar) {
				$(this).prop('checked', true).trigger('change');
				cont++;
			}
		});
	});

	$('#cmd_none').click(function(){
		$('.reactivo-checkbox:checked').each(function(){
			$(this).prop('checked', false).trigger('change');
		});
	});

	cargar_reactivos_examen();
});

function actualizarContador() {
	$('#contador-seleccionados').text(seleccionados);
	if (seleccionados >= reactivosRequeridos) {
		$('#mensaje-maximo').show();
		$('.reactivo-checkbox:not(:checked)').prop('disabled', true);
	} else {
		$('#mensaje-maximo').hide();
		$('.reactivo-checkbox').prop('disabled', false);
	}
}

$(document).on('change', '.reactivo-checkbox', function() {
	let idReactivo = $(this).data('id');
	let idExamen = $('#id_examen').val();

	if ($(this).is(':checked')) {
		if (seleccionados < reactivosRequeridos) {
			seleccionados++;
			$(this).closest('.reactivo-card').addClass('selected');

			// AJAX para agregar relación
			$.ajax({
				url: base_url + 'sysmater/admin/admin/asignar_reactivo_examen',
				type: 'POST',
				data: {
					id_examen: idExamen,
					id_reactivo_main: idReactivo
				},
				success: function(response) {
					console.log('Asignado correctamente');
				},
				error: function() {
					alert('Error al asignar reactivo.');
				}
			});
		} else {
			$(this).prop('checked', false);
			alert('No puedes seleccionar más reactivos. El máximo es ' + reactivosRequeridos);
		}
	} else {
		seleccionados--;
		$(this).closest('.reactivo-card').removeClass('selected');

		// AJAX para eliminar relación
		$.ajax({
			url: base_url + 'sysmater/admin/admin/eliminar_reactivo_examen',
			type: 'POST',
			data: {
				id_examen: idExamen,
				id_reactivo_main: idReactivo
			},
			success: function(response) {
				console.log('Eliminado correctamente');
			},
			error: function() {
				alert('Error al eliminar reactivo.');
			}
		});
	}
	actualizarContador();
});
function cargar_reactivos_examen(){
	var materia = $('#materia').val();
	var id_examen = $('#id_examen').val();
	$.ajax({
		url: base_url + 'sysmater/admin/admin/ajax_reactivos_disponibles_materia',
		type: 'POST',
		dataType: 'json',
		data: { materia: materia, id_examen: id_examen },
		success: function(data) {
			$('#tbl_examen').empty();
			if(data && data.length > 0) {
				$.each(data, function(index, reactivo) {
					let mostrarImagen = (path, alt) => {
						if(path && path.trim() !== '') {
							return `
								<div class="img-tooltip-trigger">
									<span class="glyphicon glyphicon-picture"></span> Ver imagen
									<div class="img-tooltip-content">
										<img src="${base_url}uploads/${path}" alt="${alt}">
									</div>
								</div>
							`;
						}
						return '';
					};

					let construirOpcion = (letra, opcion, argumento, imagen, correcta) => {
						let claseCorrecta = (letra === correcta) ? 'opcion-correcta' : '';
						return `
							<div class="opcion ${claseCorrecta}">
								<strong>Opción ${letra}:</strong> ${opcion || 'No especificado'}
								${argumento ? `<div class="argumento"><em>Argumento:</em> ${argumento}</div>` : ''}
								${imagen ? `<div class="imagen-opcion">${mostrarImagen(imagen, `Imagen ${letra}`)}</div>` : ''}
							</div>
						`;
					};

					let card = `
						<div class="panel reactivo-card">
							<div class="panel-heading" role="tab" id="heading${reactivo.id}">
								<h4 class="panel-title">
									<input type="checkbox" name="reactivos[]" value="${reactivo.id}" class="reactivo-checkbox" data-id="${reactivo.id}">
									<a role="button" data-toggle="collapse" data-parent="#tbl_examen" href="#collapse${reactivo.id}" aria-expanded="true">
										<span class="glyphicon glyphicon-question-sign"></span> 
										<strong>Reactivo ${index + 1} - ${(reactivo.base || 'Sin texto base').substring(0, 50)}${(reactivo.base && reactivo.base.length > 50 ? '...' : '')}</strong>
									</a>
								</h4>
							</div>
							<div id="collapse${reactivo.id}" class="panel-collapse collapse">
								<div class="panel-body">
									<div class="row">
										<div class="col-md-12">
											${reactivo.base ? `<p><strong>Texto base:</strong> ${reactivo.base}</p>` : ''}
											${reactivo.imagen_base ? `<div class="imagen-base">${mostrarImagen(reactivo.imagen_base, 'Imagen base')}</div>` : ''}
											<h5>Opciones:</h5>
											<div class="row">
												<div class="col-md-6">
													${construirOpcion('A', reactivo.opcionA, reactivo.argumentoA, reactivo.imagenA, reactivo.correcta)}
													${construirOpcion('B', reactivo.opcionB, reactivo.argumentoB, reactivo.imagenB, reactivo.correcta)}
												</div>
												<div class="col-md-6">
													${construirOpcion('C', reactivo.opcionC, reactivo.argumentoC, reactivo.imagenC, reactivo.correcta)}
													${construirOpcion('D', reactivo.opcionD, reactivo.argumentoD, reactivo.imagenD, reactivo.correcta)}
												</div>
											</div>
											<div class="row">
												<div class="col-md-4 opcion">
													<p><strong>Respuesta correcta:</strong> Opción ${reactivo.correcta || 'ND'}</p>
												</div>
												<div class="col-md-4 opcion">
													<p><strong>Bibliografía:</strong> ${reactivo.bibliografia || 'No especificada'}</p>
												</div>
												<div class="col-md-4 opcion">
													<p><strong>Registrado por:</strong> ${reactivo.trabajador || 'Desconocido'}</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					`;
					$('#tbl_examen').append(card);
				});
			} else {
				$('#tbl_examen').html('<div class="alert alert-info">No hay reactivos disponibles para esta materia.</div>');
			}
		},
		error: function(xhr, status, error) {
			console.error("Error en AJAX:", status, error);
			$('#tbl_examen').html(`<div class="alert alert-danger">Error al cargar reactivos: ${error}<br>Detalles: ${xhr.responseText}</div>`);
		}
	});
}

</script>
