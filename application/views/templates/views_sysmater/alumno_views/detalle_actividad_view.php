<!-- detalle_actividad_view.php -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li><a href="<?= $url_regresar ?>">Actividades</a></li>
                <li class="active">Detalle de Actividad</li>
            </ol>

            <!-- Información Principal -->
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <span class="glyphicon glyphicon-book"></span> <?= htmlspecialchars($actividad['titulo']) ?>
                    </h3>
                </div>
                <div class="panel-body">

                    <!-- Información General y Calificación -->
                    <div class="row">
                        <div class="col-md-8">
                            <h4>Información General</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <td><strong>Materia:</strong></td>
                                    <td>
                                        <?= htmlspecialchars($actividad['vchNomMateria']) ?>
                                        <span class="label label-default"><?= htmlspecialchars($actividad['vchClvMateria']) ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Parcial:</strong></td>
                                    <td><?= htmlspecialchars($actividad['parcial']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Número de Actividad:</strong></td>
                                    <td><?= htmlspecialchars($actividad['numero_actividad']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Modalidad:</strong></td>
                                    <td><span class="label label-info"><?= $texto_modalidad ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Grupo:</strong></td>
                                    <td><span class="label label-default"><?= htmlspecialchars($grupo) ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Estado:</strong></td>
                                    <td><span class="label <?= $clase_estado ?>"><?= htmlspecialchars($actividad['nombre_estado']) ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Fecha de Entrega:</strong></td>
                                    <td>
                                        <span class="glyphicon glyphicon-calendar"></span> <?= $fecha_entrega_formateada ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Docente:</strong></td>
                                    <td>
                                        <span class="glyphicon glyphicon-user"></span> <?= htmlspecialchars($nombre_docente) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Valor Total:</strong></td>
                                    <td><strong><?= $actividad['valor_total'] ?> puntos</strong></td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-4">
                            <!-- Panel de Calificación -->
                            <div class="panel <?= $tiene_calificacion ? 'panel-success' : 'panel-warning' ?>">
                                <div class="panel-heading text-center">
                                    <h4>Calificación</h4>
                                </div>
                                <div class="panel-body text-center">
                                    <?php if ($tiene_calificacion): ?>
                                        <h1 class="<?= $clase_calificacion ?>">
                                            <strong><?= $calificacion_total['calificacion_total'] ?>/<?= $actividad['valor_total'] ?></strong>
                                        </h1>
                                        <p class="<?= $clase_calificacion ?>">
                                            <strong><?= $porcentaje_total ?>%</strong>
                                        </p>
                                    <?php else: ?>
                                        <h4 class="text-muted">Sin calificar</h4>
                                        <p class="text-muted">Actividad pendiente de evaluación</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Descripción</h4>
                            <div class="well">
                                <p><?= $actividad['descripcion'] ? nl2br(htmlspecialchars($actividad['descripcion'])) : 'Sin descripción disponible' ?></p>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($criterios_procesados)): ?>
                        <!-- Desglose por Criterios -->
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Desglose por Criterios</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr class="active">
                                                <th>Criterio</th>
                                                <th class="text-center">Valor Máximo</th>
                                                <th class="text-center">Calificación Obtenida</th>
                                                <th class="text-center">Porcentaje</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($criterios_procesados as $criterio): ?>
                                                <tr class="<?= $criterio['clase_fila'] ?>">
                                                    <td><strong><?= htmlspecialchars($criterio['nombre']) ?></strong></td>
                                                    <td class="text-center"><?= $criterio['valor_maximo'] ?></td>
                                                    <td class="text-center"><strong><?= $criterio['calificacion'] ?></strong></td>
                                                    <td class="text-center"><?= $criterio['porcentaje'] ?>%</td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Observaciones del Docente -->
                    <?php if ($tiene_observacion): ?>
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Observaciones del Docente</h4>
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <span class="glyphicon glyphicon-comment"></span> 
                                        Comentarios de <?= htmlspecialchars($nombre_docente) ?>
                                    </div>
                                    <div class="panel-body">
                                        <blockquote>
                                            <p><?= nl2br(htmlspecialchars($observacion_docente)) ?></p>
                                            <footer>
                                                <cite title="Docente"><?= htmlspecialchars($nombre_docente) ?></cite>
                                            </footer>
                                        </blockquote>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Botón de Regreso -->
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <a href="<?= $url_regresar ?>" class="btn btn-default btn-lg">
                                <span class="glyphicon glyphicon-arrow-left"></span> Regresar a Actividades
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Efectos visuales -->
<script>
    $(document).ready(function() {
        // Efecto hover en filas de criterios
        $('.table-responsive table tbody tr').hover(
            function() {
                $(this).css('background-color', 'rgba(0,0,0,0.05)');
            },
            function() {
                $(this).css('background-color', '');
            }
        );

        // Efecto fade-in para las observaciones
        <?php if ($tiene_observacion): ?>
            $('.panel-info').hide().fadeIn(1000);
        <?php endif; ?>
    });
</script>