<title>Seleccione un grupo para ver su Progreso</title>
<div id="container" class="container">
    <div class="card">
        <h3 class="text-center">Seleccione un grupo para ver su progreso de examen</h3>
        <div class="card-body">
                <?php if ($examenes) : ?>
                <?php foreach ($examenes->result() as $examen): ?>
                    <?php
                    $grupos = explode(', ', $examen->vchGrupo);
                    foreach ($grupos as $grupo):
                    ?>
                        <form method="get" action="<?= site_url('sysmater/docente/docente/progreso_examen_grupo/'.$examen->id_examen.'/'.urlencode($grupo)) ?>">
                            <button type="submit" class="btn btn-primary"><?= htmlspecialchars($grupo) ?></button>
                        </form>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php else : ?>
                <p><center>No existen registros</center></p>
            <?php endif ?>
        </div>
    </div>
</div>