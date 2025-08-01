<!-- examenes_view.php - Lista de exámenes por parcial -->
<div class="container-fluid">
    <!-- Header -->
    <div class="text-center">
        <h2>Mis Resultados de Exámenes</h2>
        <p>Consulta las calificaciones y resultados de todos tus exámenes</p>
    </div>

    <!-- Panel de Usuario -->
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"> <span class="glyphicon glyphicon-education"></span> Información del Estudiante</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <p><b>ALUMNO(a):</b> <?= htmlspecialchars($this->session->Usuario) ?></p>
                    <p><b>MATRÍCULA:</b> <?= htmlspecialchars($this->session->Matricula) ?></p>
                    <p><b>CUATRIMESTRE:</b> <?= htmlspecialchars($this->session->Cuatri) ?></p>
                </div>
                <div class="col-md-6">
                    <p><b>CARRERA:</b> <?= htmlspecialchars($this->session->NomCarrera) ?></p>
                    <p><b>PERIODO:</b> <?= htmlspecialchars($this->session->Periodo) ?></p>
                    <p><b>GRUPO:</b> <?= htmlspecialchars($this->session->GrupoNom) ?></p>
                </div>
            </div>
        </div>
    </div>

    <?php if ($hay_examenes): ?>
            
            <!-- Acordeón de Materias -->
            <div class="panel-group" id="acordeonMaterias">
                <?php foreach ($materias_agrupadas as $index => $materia): ?>
                    <div class="panel panel-">
                        <div class="panel-heading" role="tab" id="panel<?= $index ?>">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#acordeonMaterias" 
                                   href="#collapse<?= $index ?>" aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>" 
                                   aria-controls="collapse<?= $index ?>">
                                    <span class="glyphicon glyphicon-chevron-<?= $index === 0 ? 'up' : 'down' ?>"></span>
                                    <?= htmlspecialchars($materia['nombre']) ?> 
                                    <span class="label label-default"><?= htmlspecialchars($materia['clave']) ?></span>
                                    <span class="badge pull-right"><?= count($materia['examenes']) ?></span>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse<?= $index ?>" class="panel-collapse collapse <?= $index === 0 ? 'in' : '' ?>" 
                             role="tabpanel" aria-labelledby="panel<?= $index ?>">
                            <div class="panel-body">
                                <div class="list-group">
                                    <?php foreach ($materia['examenes'] as $examen): ?>
                                        <a href="<?= $examen['url_detalle'] ?>" class="list-group-item examen-item">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <h4 style="margin: 0; font-weight: bold;">
                                                        <?= htmlspecialchars($examen['titulo']) ?>
                                                    </h4>
                                                    <span class="text-muted">Parcial <?= $examen['parcial'] ?></span>
                                                </div>
                                                <div class="col-md-3 text-center">
                                                    <span style="font-size: 14px;">
                                                        <?= $examen['aciertos'] ?> de <?= $examen['total_reactivos'] ?> preguntas
                                                    </span>
                                                </div>
                                                <div class="col-md-2 text-center">
                                                    <?php if ($examen['calificacion']): ?>
                                                        <span class="label label-primary" style="font-size: 13px; padding: 4px 8px;">
                                                            <?= number_format($examen['calificacion'], 1) ?>/100
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="label label-warning">Sin Aplicar</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-3 text-right">
                                                    <span class="text-primary" style="font-size: 14px;">
                                                        <span class="glyphicon glyphicon-chevron-right"></span> Ver detalles
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <!-- Sin exámenes en el periodo -->
        <!-- <div class="alert alert-warning text-center">
            <h4>No hay exámenes registrados para este periodo</h4>
            <p>Los resultados aparecerán aquí una vez que se publiquen las calificaciones.</p>
        </div> -->
    <?php else: ?>
        <!-- Sin exámenes - mensaje alternativo -->
        <div class="alert alert-info text-center">
            <h4>Bienvenido al módulo de resultados de exámenes</h4>
            <p>Aquí podrás consultar las calificaciones de todos tus exámenes una vez que estén disponibles.</p>
        </div>
    <?php endif; ?>
    
</div>

<!-- JavaScript solo para efectos visuales -->
<script>
$(document).ready(function() {
    // Efecto hover en items de exámenes
    $('.examen-item').hover(
        function() {
            $(this).css('background-color', '#f5f5f5');
        },
        function() {
            $(this).css('background-color', '');
        }
    );
    
    // Cambiar chevron al expandir/colapsar acordeón
    $('#acordeonMaterias').on('show.bs.collapse', function (e) {
        $(e.target).prev().find('.glyphicon')
            .removeClass('glyphicon-chevron-down')
            .addClass('glyphicon-chevron-up');
    });
    
    $('#acordeonMaterias').on('hide.bs.collapse', function (e) {
        $(e.target).prev().find('.glyphicon')
            .removeClass('glyphicon-chevron-up')
            .addClass('glyphicon-chevron-down');
    });
});
</script>