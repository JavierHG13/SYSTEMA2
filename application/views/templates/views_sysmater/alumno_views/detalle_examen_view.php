<!-- detalle_examen_view.php - Vista de detalle con soporte para imágenes -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li><a href="<?= $url_regresar ?>">Resultados de Exámenes</a></li>
                <li class="active">Detalle del Examen</li>
            </ol>

            <!-- Información Principal del Examen -->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <span class="glyphicon glyphicon-book"></span> <?= htmlspecialchars($examen['titulo']) ?>
                    </h3>
                </div>
                <div class="panel-body">
                    
                    <!-- Información General y Calificación -->
                    <div class="row">
                        <div class="col-md-8">
                            <h4>Información del Examen</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <td><strong>Materia:</strong></td>
                                    <td>
                                        <?= htmlspecialchars($examen['vchNomMateria']) ?> 
                                        <span class="label label-default"><?= htmlspecialchars($examen['vchClvMateria']) ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Parcial:</strong></td>
                                    <td><?= htmlspecialchars($examen['parcial']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Total de Preguntas:</strong></td>
                                    <td><?= $examen['total_reactivos'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Respuestas Correctas:</strong></td>
                                    <td><?= $examen['aciertos'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Respuestas Incorrectas:</strong></td>
                                    <td><?= $examen['total_reactivos'] - $examen['aciertos'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Porcentaje de Aciertos:</strong></td>
                                    <td><?= number_format(($examen['aciertos'] / $examen['total_reactivos']) * 100, 1) ?>%</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-4">
                            <!-- Panel de Calificación -->
                            <div class="panel <?= $examen['calificacion'] ? 'panel-success' : 'panel-warning' ?>">
                                <div class="panel-heading text-center">
                                    <h4>Calificación Final</h4>
                                </div>
                                <div class="panel-body text-center">
                                    <?php if ($examen['calificacion']): ?>
                                        <h1 class="text-primary">
                                            <strong><?= number_format($examen['calificacion'], 1) ?>/100</strong>
                                        </h1>
                                    <?php else: ?>
                                        <h4 class="text-muted">Sin Calificar</h4>
                                        <p class="text-muted">Examen pendiente de evaluación</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($respuestas)): ?>
                    <!-- Detalle de Preguntas y Respuestas con imágenes -->
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Revisión de Preguntas</h4>
                            
                            <?php foreach ($respuestas as $respuesta): ?>
                                <div class="panel panel-default" style="margin-bottom: 25px;">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <h5 style="margin: 0;">
                                                    <strong>Pregunta <?= $respuesta['numero_pregunta'] ?></strong>
                                                </h5>
                                            </div>
                                            <div class="col-md-2 text-right">
                                                <span class="label label-<?= $respuesta['es_correcta'] ? 'success' : 'danger' ?>">
                                                    <?= $respuesta['es_correcta'] ? 'Correcta' : 'Incorrecta' ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        
                                        <?php 
                                        // Verificar si esta pregunta tiene imagen válida
                                        $tiene_imagen_pregunta = false;
                                        if (!empty($respuesta['path_imagen_base'])) {
                                            $nombre_img = trim($respuesta['path_imagen_base']);
                                            if ($nombre_img !== '' && $nombre_img !== 'null' && $nombre_img !== '0') {
                                                $ruta_img = FCPATH . 'uploads/' . $nombre_img;
                                                if (file_exists($ruta_img) && is_file($ruta_img)) {
                                                    $tiene_imagen_pregunta = true;
                                                }
                                            }
                                        }
                                        ?>
                                        
                                        <div class="row">
                                            <!-- Pregunta y opciones -->
                                            <div class="col-md-<?= $tiene_imagen_pregunta ? '8' : '12' ?>">
                                                
                                                <!-- Pregunta -->
                                                <div style="margin-bottom: 20px;">
                                                    <h5><strong><?= nl2br(htmlspecialchars($respuesta['pregunta'])) ?></strong></h5>
                                                </div>

                                                <!-- Opciones -->
                                                <?php 
                                                $opciones_con_contenido = array_filter($respuesta['opciones']) + array_filter($respuesta['opciones_imagenes']);
                                                ?>
                                                
                                                <?php if (!empty($opciones_con_contenido)): ?>
                                                    <div style="margin-bottom: 15px;">
                                                        <h6><strong>Opciones:</strong></h6>
                                                        <?php foreach (['A', 'B', 'C', 'D'] as $letra): ?>
                                                            <?php 
                                                            // Validación estricta para texto - considerar "0" como válido
                                                            $texto_opcion = isset($respuesta['opciones'][$letra]) ? trim($respuesta['opciones'][$letra]) : '';
                                                            $tiene_texto = ($texto_opcion !== '' && $texto_opcion !== null);
                                                            
                                                            // Validación estricta para imagen
                                                            $nombre_imagen = isset($respuesta['opciones_imagenes'][$letra]) ? trim($respuesta['opciones_imagenes'][$letra]) : '';
                                                            $tiene_imagen = false;
                                                            
                                                            if (!empty($nombre_imagen) && $nombre_imagen !== 'null' && $nombre_imagen !== '0') {
                                                                $ruta_imagen = FCPATH . 'uploads/' . $nombre_imagen;
                                                                if (file_exists($ruta_imagen) && is_file($ruta_imagen)) {
                                                                    $tiene_imagen = true;
                                                                }
                                                            }
                                                            ?>
                                                            
                                                            <?php if ($tiene_texto || $tiene_imagen): ?>
                                                                <div class="well well-sm <?php 
                                                                    if ($letra == $respuesta['respuesta_correcta']) echo 'alert-success';
                                                                    elseif ($letra == $respuesta['respuesta_alumno'] && !$respuesta['es_correcta']) echo 'alert-danger';
                                                                ?>" style="margin-bottom: 10px;">
                                                                    
                                                                    <div class="row">
                                                                        <div class="col-md-8">
                                                                            <strong><?= $letra ?>)</strong>
                                                                            
                                                                            <?php if ($tiene_texto): ?>
                                                                                <?= htmlspecialchars($texto_opcion) ?>
                                                                            <?php endif; ?>
                                                                            
                                                                            <?php if ($tiene_imagen): ?>
                                                                                <div style="margin-top: 10px;">
                                                                                    <img src="<?= base_url('uploads/' . $nombre_imagen) ?>" 
                                                                                         class="img-thumbnail imagen-opcion" 
                                                                                         style="max-width: 200px; max-height: 150px;" 
                                                                                         alt="Opción <?= $letra ?>"
                                                                                         data-ruta="<?= $nombre_imagen ?>">
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                        
                                                                        <div class="col-md-4 text-right">
                                                                            <?php if ($letra == $respuesta['respuesta_correcta']): ?>
                                                                                <span class="label label-success">✓ Correcta</span>
                                                                            <?php elseif ($letra == $respuesta['respuesta_alumno']): ?>
                                                                                <span class="label label-primary">Tu respuesta</span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>

                                            </div>

                                            <!-- Imagen de la pregunta (solo si existe) -->
                                            <?php if ($tiene_imagen_pregunta): ?>
                                                <div class="col-md-4">
                                                    <div class="well text-center">
                                                        <h6><strong>Imagen de la pregunta:</strong></h6>
                                                        <img src="<?= base_url('uploads/' . trim($respuesta['path_imagen_base'])) ?>" 
                                                             class="img-thumbnail imagen-pregunta" 
                                                             style="max-width: 100%; max-height: 300px;" 
                                                             alt="Imagen de pregunta <?= $respuesta['numero_pregunta'] ?>"
                                                             data-ruta="<?= trim($respuesta['path_imagen_base']) ?>">
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Resumen de respuestas -->
                                        <div class="alert <?= $respuesta['es_correcta'] ? 'alert-success' : 'alert-danger' ?>" style="margin-top: 15px;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <strong>Tu respuesta:</strong> 
                                                    <?= ($respuesta['respuesta_alumno'] !== null && $respuesta['respuesta_alumno'] !== '') ? htmlspecialchars($respuesta['respuesta_alumno']) : 'Sin respuesta' ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Respuesta correcta:</strong> 
                                                    <?= htmlspecialchars($respuesta['respuesta_correcta']) ?>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Botón de Regreso -->
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <a href="<?= $url_regresar ?>" class="btn btn-danger">
                                <span class="glyphicon glyphicon-chevron-left"></span> Regresar a Resultados
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript para manejo de imágenes -->
<script>
$(document).ready(function() {
    // Manejar errores de carga de imágenes (opcional - puedes removarlo si no lo necesitas)
    $('.img-thumbnail').on('error', function() {
        console.log('Error cargando imagen: ' + $(this).attr('src'));
        $(this).hide(); // Solo oculta la imagen, no muestra mensaje
    });
    
    // Click en imagen para zoom (modal)
    $('.img-thumbnail').click(function() {
        var src = $(this).attr('src');
        var alt = $(this).attr('alt');
        
        var modal = `
            <div class="modal fade" id="imageModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">${alt}</h4>
                        </div>
                        <div class="modal-body text-center">
                            <img src="${src}" class="img-responsive" style="max-width: 100%;">
                        </div>
                    </div>
                </div>
            </div>`;
        
        $('body').append(modal);
        $('#imageModal').modal('show');
        
        $('#imageModal').on('hidden.bs.modal', function() {
            $(this).remove();
        });
    });
    
    // Cursor pointer en imágenes
    $('.img-thumbnail').css('cursor', 'pointer');
});
</script>