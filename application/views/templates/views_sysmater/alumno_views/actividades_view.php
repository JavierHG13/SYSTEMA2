<!-- actividades_view.php - Server-side Puro -->
<div class="container-fluid">
    <!-- Header -->
    <div class="text-center">
        <h2>Mis Actividades</h2>
        <p>Consulta y revisa el estado de todas tus actividades académicas</p>
    </div>

    <!-- Panel de Usuario -->
    <div class="panel panel-success">
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

    <!-- Selector de Parcial-->
    <div class="panel panel-default">
        <div class="panel-body">
            <form method="get" action="<?= site_url('sysmater/alumno/resultados_actividades/index') ?>" class="form-inline">
                <div class="form-group">
                    <label for="parcial">Seleccionar Parcial:</label>
                    <select class="form-control" id="parcial" name="parcial" onchange="this.form.submit()" style="margin-left: 10px;">
                        <option value="">Selecciona un Parcial</option>
                        <?php foreach ($urls_parciales as $parcial_info): ?>
                            <option value="<?= $parcial_info['numero'] ?>" 
                                    <?= $parcial_info['activo'] ? 'selected' : '' ?>>
                                <?= $parcial_info['nombre'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <?php if ($parcial_actual): ?>
        <?php if ($hay_actividades): ?>
            
            <!-- Acordeón de Materias -->
            <div class="panel-group" id="acordeonMaterias">
                <?php foreach ($materias_agrupadas as $index => $materia): ?>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="panel<?= $index ?>">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#acordeonMaterias" 
                                   href="#collapse<?= $index ?>" aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>" 
                                   aria-controls="collapse<?= $index ?>">
                                    <span class="glyphicon glyphicon-chevron-<?= $index === 0 ? 'up' : 'down' ?>"></span>
                                    <?= htmlspecialchars($materia['nombre']) ?> 
                                    <span class="label label-default"><?= htmlspecialchars($materia['clave']) ?></span>
                                    <span class="badge pull-right"><?= count($materia['actividades']) ?></span>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse<?= $index ?>" class="panel-collapse collapse <?= $index === 0 ? 'in' : '' ?>" 
                             role="tabpanel" aria-labelledby="panel<?= $index ?>">
                            <div class="panel-body">
                                <div class="row">
                                    <?php foreach ($materia['actividades'] as $actividad): ?>
                                        <div class="col-md-4 col-sm-6" style="margin-bottom: 15px;">
                                            <a href="<?= $actividad['url_detalle'] ?>" class="actividad-link" 
                                               style="text-decoration: none; color: inherit; display: block;">
                                                <div class="panel panel-default actividad-card" 
                                                     style="transition: all 0.2s; height: 100%;">
                                                    <div class="panel-body" style="padding: 10px;">
                                                        <h5 style="margin-top: 0; margin-bottom: 8px;">
                                                            <strong><?= htmlspecialchars($actividad['titulo']) ?></strong>
                                                        </h5>
                                                        <p style="margin-bottom: 5px;">
                                                            <small class="text-muted">
                                                                <span class="glyphicon glyphicon-calendar"></span> 
                                                                <?= $actividad['fecha_entrega_formateada'] ?>
                                                            </small>
                                                        </p>
                                                        <div style="margin-bottom: 8px;">
                                                            <span class="label <?= $actividad['clase_estado'] ?>">
                                                                <?= htmlspecialchars($actividad['nombre_estado']) ?>
                                                            </span>
                                                            <span class="label label-info">
                                                                <?= $actividad['texto_modalidad'] ?>
                                                            </span>
                                                            <?php if ($actividad['calificacion']): ?>
                                                                <span class="label label-default pull-right">
                                                                    <?= $actividad['calificacion'] ?>/100
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <p class="text-center" style="margin-bottom: 0;">
                                                            <small class="text-primary">
                                                                <span class="glyphicon glyphicon-eye-open"></span> Ver detalles
                                                            </small>
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <!-- Sin actividades para el parcial -->
            <div class="alert alert-warning text-center">
                <h4>No hay actividades registradas para el Parcial <?= $parcial_actual ?></h4>
                <p>Contacta a tu profesor si consideras que esto es un error.</p>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <!-- Sin parcial seleccionado -->
        <div class="alert alert-info text-center">
            <h4>Selecciona un parcial para ver las actividades</h4>
            <p>Utiliza los botones de arriba para elegir el parcial que deseas consultar.</p>
        </div>
    <?php endif; ?>
    
</div>

<!-- efectos visuales -->
<script>
$(document).ready(function() {
    // Efecto hover en cards de actividades
    $('.actividad-link').hover(
        function() {
            $(this).find('.actividad-card').removeClass('panel-default').addClass('panel-info');
        },
        function() {
            $(this).find('.actividad-card').removeClass('panel-info').addClass('panel-default');
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