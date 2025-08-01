<link rel="stylesheet" href="<?= base_url() ?>assets/css/tablas.css">
<center>
    <h3>Exámenes Pendientes de Programar</h3>
</center>

<p>
    <a href="<?= site_url('/sysmater/docente/docente/examenes_programados/') ?>" class='btn btn-danger'><span class="glyphicon glyphicon-calendar"></span> Exámenes programados</a>
</p>
<div class="table-container" style="margin-bottom: 20px;">

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>MATERIA</th>
                <th>TÍTULO DE EXÁMEN</th>
                <th>GRUPOS DISPONIBLES PARA APLICACIÓN</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($examenes && $examenes->num_rows() > 0) : ?>
                <?php foreach ($examenes->result() as $examen) : ?>
                    <tr>
                        <td><?= htmlspecialchars($examen->id_examen); ?></td>
                        <td><?= htmlspecialchars($examen->nombre_materia); ?></td>
                        <td><?= htmlspecialchars($examen->nvch_Titulo); ?></td>
                        <td>
                            <?php if (!empty($examen->grupos_pendientes)): ?>
                                <div class="btn-group btn-group-xs" role="group" style="display: flex; flex-wrap: wrap;">
                                    <?php 
                                    $grupos = explode(',', $examen->grupos_pendientes);
                                    $colors = ['primary', 'success', 'danger', 'warning', 'default'];
                                    foreach ($grupos as $index => $grupo): 
                                        $grupo = trim($grupo);
                                        if (!empty($grupo)):
                                            $color = $colors[$index % count($colors)];
                                    ?>
                                    <a href="<?= site_url("/sysmater/docente/docente/programar_examen_grupo/{$examen->id_examen}/".urlencode($grupo)) ?>" 
                                       class="btns btn btn-<?= $color ?> btn-xs" 
                                       >
                                        <span class="glyphicon glyphicon-education"></span> <?= htmlspecialchars($grupo) ?>
                                    </a>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                </div>
                            <?php else: ?>
                                <span class="text-muted">Todos los grupos ya están programados</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                   <td colspan="9" class="no-records">
                        No hay exámenes pendientes de programar
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>