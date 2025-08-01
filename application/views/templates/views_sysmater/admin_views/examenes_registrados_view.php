<link rel="stylesheet" href="<?= base_url() ?>assets/css/tablas.css">
<div class="cd">

    <center>
        <h2>Exámenes registrados</h2>
    </center>
    <div class="table-container" style="margin-bottom: 20px;">

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>MATERIA</th>
                    <th>TITULO</th>
                    <th>PERIODO</th>
                    <th>PARCIAL</th>
                    <th>GRUPOS</th>
                    <th>CUATRIMESTRE</th>
                    <th>R.SELECCIONADOS / R.REQUERIDOS </th>
                    <th>ACCIÓN</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($examenes && $examenes->num_rows() > 0): ?>
                    <?php foreach ($examenes->result() as $examen): ?>
                        <tr>
                            <td><?= $examen->id_examen; ?></td>
                            <td><?= $examen->vchNomMateria; ?></td>
                            <td><?= $examen->nvch_Titulo; ?></td>
                            <td><?= $examen->periodo; ?></td>
                            <td><?= $examen->parcial; ?></td>
                            <td><?= $examen->vchGrupo; ?></td>
                            <td><?= $examen->vchCuatrimestre; ?></td>
                            <td><?= $examen->nReactivos; ?> / <?= $examen->int_reactivos_requeridos; ?></td>
        
                            <td>
                                <div class="btn-group btn-group-xs" role="group" aria-label="Acciones">
                                    <a href="<?= site_url('/sysmater/admin/ver_examen/index/ver_examen/'.$examen->id_examen) ?>" class='btns btn btn-default' alt="Vista previa del examen">
                                        <span class="glyphicon glyphicon-eye-open"></span> Ver
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">
                            <center>No existen registros</center>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
