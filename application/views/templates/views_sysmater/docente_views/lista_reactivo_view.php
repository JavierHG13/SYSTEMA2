<!-- lista reactivoe -->
<!-- AGREGADO POR ISRAEL REYES AQUINO -->
		<center>
			<h3>Reactivos registrados</h3>
		</center>
		<p><a href="<?= site_url('/docente/docente/nuevo_reactivo') ?>" class='btn btn-success'>Nuevo reactivo</a></p>
		<table class="table table-striped table-bordered table-condensed" >
			<thead>
				<tr>
					<th>#</th>
					<th>ID</th>
					<th>CARRERA</th>
					<th>CUAT.</th>
					<th>MATERIA</th>
					<th>NO. REACTIVOS</th>
					<th>ESTADO</th>
					<th>ACCIÓN</th>
				</tr>
			</thead>
			<tbody>
				<?php if ($reactivos): ?>
				<?php $pos=1; ?>
				<?php 
					foreach ($reactivos->result() as $reactivo) {
				 ?>
				<tr>
					<td><?= $pos; ?></td>
					<td><?= $reactivo->id_reactivo_main; ?></td>
					<td><?= $reactivo->vchNomCarrera; ?></td>
					<td><?= $reactivo->vchClvCuatri; ?></td>
					<td><?= $reactivo->vchNomMateria; ?></td>
					<td><?= $reactivo->nReactivos; ?></td>
					<td><?= $reactivo->vch_estado; ?></td>
					<td class="col-sm-1">
						<div class="btn-group btn-group-xs" role="group" aria-label="...">
							<a href="<?= site_url('/docente/docente/elimina_reactivo/'.$reactivo->id_reactivo_main) ?>" class='btn btn-danger'><span class="glyphicon glyphicon-remove"></span></a>
							<a href="<?= site_url('/docente/docente/edita_reactivo/'.$reactivo->id_reactivo_main) ?>" class='btn btn-success'><span class="glyphicon glyphicon-pencil"></span></a>
							<a href="<?= site_url('/docente/docente/ver_reactivo/'.$reactivo->id_reactivo_main) ?>" class='btn btn-default'><span class="glyphicon glyphicon-eye-open"></span></a>
						</div>
					</td>
				</tr>
					<?php $pos++; ?>
				<?php } ?>
				<?php else: ?>
					<tr>
						<td colspan="7"> <center>No existen registros</center></td>
					</tr>
				<?php endif ?>
			</tbody>
		</table>