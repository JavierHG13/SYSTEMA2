<link rel="stylesheet" href="<?= base_url() ?>assets/css/tablas.css">
<div class="cd">
<center>
	<h2>Exámenes programados para aplicación</h2>
</center>

<div class="table-container" style="margin-bottom: 20px;">
	<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>TITULO</th>
			<th>CLAVE</th>
			<th>REACTIVOS</th>
			<th>DESDE</th>
			<th>HASTA</th>
			<th>INICIO</th>
			<th>TERMINO</th>
			<th>DURACIÓN</th>
		</tr>
	</thead>
	<tbody>
		<?php if ($examenes) : ?>

			<?php
			foreach ($examenes->result() as $examen) {
			?>
				<tr>
					<td><?= $examen->id_examen; ?></td>
					<td><?= $examen->nvch_Titulo; ?></td>
					<td><?= $examen->nvch_clave; ?></td>
					<td><?= $examen->nReactivos; ?></td>
					<?php $date = date_create($examen->fch_inicia) ?>
					<td><?= date_format($date, 'd/m/Y'); ?></td>
					<?php $date = date_create($examen->fch_termina) ?>
					<td><?= date_format($date, 'd/m/Y'); ?></td>
					<?php $date = date_create($examen->tm_hora_inicio) ?>
					<td><?= date_format($date, 'H:i'); ?></td>
					<?php $date = date_create($examen->tm_hora_final) ?>
					<td><?= date_format($date, 'H:i'); ?></td>
					<?php $date = date_create($examen->tm_duracion) ?>
					<td><?= date_format($date, 'H:i'); ?></td>
				</tr>
			<?php } ?>
		<?php else : ?>
			<tr>
				<td colspan="7">
					<center>No existen registros</center>
				</td>
			</tr>
		<?php endif ?>
	</tbody>
</table>
    </div>
</div>