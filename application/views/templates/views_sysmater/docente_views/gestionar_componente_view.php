<style>
    /* Mensajes mejorados */
    #msgFlash {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        max-width: 400px;
    }

    .alert {
        padding: 15px 20px;
        border-radius: 10px;
        font-weight: 500;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        border: none;
        animation: slideIn 0.3s ease-out;
    }

    .alert-success {
        background: linear-gradient(45deg, #d4edda, #c3e6cb);
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .alert-error {
        background: linear-gradient(45deg, #f8d7da, #f5c6cb);
        color: #721c24;
        border-left: 4px solid #dc3545;
    }



    /* Estilos para esquemas dinámicos */
    :root {
        --sys-primary: #007bff;
        --sys-success: #28a745;
        --sys-warning: #ffc107;
        --sys-danger: #dc3545;
        --sys-neutral: #f8f9fa;
        --sys-neutral-border: #dee2e6;
    }

    .cantidad-selector {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .cantidad-btn {
        background: var(--sys-primary);
        color: white;
        border: none;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 18px;
        transition: all 0.3s ease;
    }

    .cantidad-btn:hover {
        background: #0056b3;
    }

    .cantidad-btn:disabled {
        background: #6c757d;
        cursor: not-allowed;
    }

    .cantidad-input {
        width: 60px;
        text-align: center;
        font-weight: bold;
        font-size: 18px;
    }

    .esquema-item {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        position: relative;
        transition: all 0.3s ease;
    }

    .esquema-item.valid {
        border-color: var(--sys-success) !important;
        background: #d4edda !important;
    }

    .esquema-item.invalid {
        border-color: var(--sys-danger) !important;
        background: #f8d7da !important;
    }

    .remove-esquema {
        position: absolute;
        top: -10px;
        right: -10px;
        background: var(--sys-danger);
        color: white;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 12px;
        z-index: 10;
        transition: all 0.3s ease;
    }

    .remove-esquema:hover {
        background: #c82333;
        transform: scale(1.1);
    }

    #totalDisplay.alert-success {
        background: #d4edda !important;
        border-color: var(--sys-success) !important;
        color: #155724 !important;
    }

    #totalDisplay.alert-warning {
        background: #fff3cd !important;
        border-color: var(--sys-warning) !important;
        color: #856404 !important;
    }

    #totalDisplay.alert-danger {
        background: #f8d7da !important;
        border-color: var(--sys-danger) !important;
        color: #721c24 !important;
    }

    .sys-esquema-form-control {
        border: 2px solid #e9ecef;
        border-radius: 6px;
        padding: 10px 12px;
        transition: all 0.3s ease;
    }

    .sys-esquema-form-control:focus {
        border-color: var(--sys-primary);
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .modal-lg {
        width: 900px;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .fade-out {
        animation: slideOut 0.5s ease-in forwards;
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }

        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
</style>


<link rel="stylesheet" href="<?= base_url() ?>assets/css/sys-styles.css">

<div class="sys-esquema-container">
    <!-- Breadcrumbs -->
    <ol class="breadcrumb sys-esquema-breadcrumb">
        <li><a href="javascript:history.back()"><span class="glyphicon glyphicon-book"></span> Regresar</a></li>
        <li class="active"><span class="glyphicon glyphicon-cog"></span> Esquema de Evaluación</li>
    </ol>

    <!-- Header -->
    <div class="text-center" style="margin-bottom: 30px;">
        <h2 style="margin-bottom: 10px;">Esquema de Evaluación</h2>
        <h4>
            Materia: <strong><?= isset($nombre_materia) ? $nombre_materia : $vchClvMateria ?></strong>
            <?php
            $primer_componente = !empty($componentes) ? $componentes[0] : null;
            $periodo = $primer_componente ? $primer_componente->vchPeriodo : '';
            ?>
            <?php if ($periodo): ?>
                | Periodo: <strong><?= $periodo ?></strong>
            <?php endif; ?>
        </h4>
    </div>

    <!-- Botón -->
    <div style="margin-bottom: 25px;">
        <button class="btn sys-esquema-btn sys-rounded" data-toggle="modal" data-target="#modal">
            <span class="glyphicon glyphicon-plus"></span> Agregar Esquema de Evaluación
        </button>
    </div>

    <?php
    $componentes_por_parcial = [];
    $totales_por_parcial = [];
    $parciales_disponibles = 0;

    if (!empty($componentes)) {
        foreach ($componentes as $comp) {
            $componentes_por_parcial[$comp->parcial][] = $comp;
            $totales_por_parcial[$comp->parcial] = ($totales_por_parcial[$comp->parcial] ?? 0) + floatval($comp->valor_componente);
        }
    }

    for ($p = 1; $p <= 3; $p++) {
        if (($totales_por_parcial[$p] ?? 0) < 10) $parciales_disponibles++;
    }
    ?>

    <?php if (empty($componentes_por_parcial)): ?>
        <div class="alert alert-warning text-center sys-rounded" style="background: var(--sys-success); border: none; color: #856404;">
            <span class="glyphicon glyphicon-exclamation-sign" style="font-size: 48px; display: block; margin-bottom: 15px;"></span>
            <h4>No hay esquemas configurados</h4>
            <p>Agrega el primer elemento del esquema de evaluación para comenzar.</p>
        </div>
    <?php else: ?>
        <div class="panel-group" id="accordion">
            <?php for ($parcial = 1; $parcial <= 3; $parcial++) : ?>
                <?php if (isset($componentes_por_parcial[$parcial])) : ?>
                    <?php
                    $componentes_parcial = $componentes_por_parcial[$parcial];
                    $total_parcial = $totales_por_parcial[$parcial] ?? 0;
                    $porcentaje = min(($total_parcial / 10) * 100, 100);
                    ?>
                    <div class="panel sys-esquema-panel sys-rounded" style="margin-bottom: 20px;">
                        <div class="panel-heading" id="h<?= $parcial; ?>" style="padding: 20px;">
                            <h4 style="margin: 0;">
                                <a data-toggle="collapse" data-parent="#accordion" href="#p<?= $parcial; ?>" class="sys-esquema-accordion-toggle" style="text-decoration: none !important; color: var(--sys-primary) !important; font-weight: 600;">
                                    <span class="glyphicon glyphicon-education"></span> <?= $parcial; ?>° Parcial
                                    <span class="badge sys-esquema-badge" style="margin-left: 10px;"><?= count($componentes_parcial) ?> elementos</span>
                                    <span class="badge sys-esquema-badge-<?= $total_parcial >= 10 ? 'complete' : 'progress' ?>" style="margin-left: 5px;">
                                        <?= number_format($total_parcial, 1) ?>/10 pts
                                    </span>
                                    <span class="glyphicon glyphicon-chevron-down pull-right" style="color: var(--sys-primary);"></span>
                                </a>
                            </h4>
                        </div>
                        <div id="p<?= $parcial; ?>" class="panel-collapse collapse">
                            <div class="panel-body" style="padding: 25px;">
                                <div class="table-responsive sys-rounded" style="box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                    <table class="table table-striped table-hover table-bordered sys-esquema-table">
                                        <thead style="background: var(--sys-primary) !important; color: white !important;">
                                            <tr>
                                                <th>Esquema de Evaluación</th>
                                                <th class="text-center">Puntos</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($componentes_parcial as $comp): ?>
                                                <tr>
                                                    <td><strong><?= $comp->componente ?></strong></td>
                                                    <td class="text-center">
                                                        <span class="badge sys-esquema-badge"><?= number_format($comp->valor_componente, 1) ?> pts</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <button class="btn sys-btn-danger btn-xs eliminar" data-id="<?= $comp->id_valor_componente ?>">
                                                            <span class="glyphicon glyphicon-trash"></span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center" style="margin-top: 15px;">
                                    <span class="glyphicon glyphicon-chevron-up sys-esquema-close" data-target="#p<?= $parcial; ?>"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endfor; ?>
        </div>

        <div class="text-center sys-text-muted" style="margin-top: 20px; padding: 15px; border-top: 1px solid var(--sys-neutral-border); background: white;" class="sys-rounded">
            <span class="glyphicon glyphicon-info-sign"></span> Total de elementos: <strong style="color: var(--sys-primary);"><?= count($componentes) ?></strong>
        </div>
    <?php endif; ?>
</div>


<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('sysmater/docente/gestionar_materia/guardar_componente') ?>" method="post">
            <div class="modal-content sys-rounded">
                <div class="modal-header sys-esquema-modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">
                        <span class="glyphicon glyphicon-plus"></span> Agregar Elemento al Esquema
                    </h4>
                </div>
                <div class="modal-body">
                    <?php if ($parciales_disponibles == 0): ?>
                        <div class="alert alert-info text-center sys-rounded" style="background: var(--sys-success); border: none;">
                            <span class="glyphicon glyphicon-check-circle" style="font-size: 48px; display: block; margin-bottom: 15px; color: var(--sys-primary);"></span>
                            <h4><strong>¡Esquema Completo!</strong></h4>
                            <p>Todos los parciales están completos (10/10 puntos).<br>
                                No se pueden agregar más componentes al esquema de evaluación.</p>
                        </div>
                    <?php else: ?>
                        <div class="form-group">
                            <label for="parcial"><strong>Parcial *</strong></label>
                            <select name="parcial" id="parcial" class="form-control sys-esquema-form-control sys-rounded" required title="Seleccione un parcial">
                                <option value="">Selecciona una opción</option>
                                <?php for ($p = 1; $p <= 3; $p++): ?>
                                    <?php
                                    $total_actual = $totales_por_parcial[$p] ?? 0;
                                    $disponible = 10 - $total_actual;
                                    $disabled = $disponible <= 0 ? 'disabled' : '';
                                    ?>
                                    <option value="<?= $p ?>" <?= $disabled ?>>
                                        <?= $p ?>° Parcial
                                        <?php if ($disponible > 0): ?>
                                            (<?= number_format($disponible, 1) ?> pts disponibles)
                                        <?php else: ?>
                                            (COMPLETO - 10/10 pts)
                                        <?php endif; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                            <small class="help-block sys-text-muted">
                                Cada parcial puede tener máximo 10.0 puntos en total
                            </small>
                        </div>


                        <!-- Selector de Cantidad -->
                        <div class="form-group">
                            <label><strong>¿Cuántos esquemas deseas crear?</strong></label>
                            <div class="cantidad-selector">
                                <button type="button" class="cantidad-btn" id="decrementarCantidad">-</button>
                                <input type="number" id="cantidadEsquemas" class="form-control cantidad-input sys-esquema-form-control"
                                    value="2" min="2" max="10" readonly>
                                <button type="button" class="cantidad-btn" id="incrementarCantidad">+</button>
                                <span style="margin-left: 10px; color: #6c757d;">
                                    <small>Mínimo 2, máximo 10 elementos</small>
                                </span>
                            </div>
                        </div>

                        <!-- Contenedor de Esquemas Dinámicos -->
                        <div id="esquemasContainer">
                            <!-- Los esquemas se generarán aquí dinámicamente -->
                        </div>


                        <div id="totalDisplay" class="alert sys-rounded" style="display: none; margin-top: 15px;">
                            <div class="text-center">
                                <span class="glyphicon glyphicon-calculator"></span>
                                <strong>Total: <span id="totalPuntos">0.0</span> / 10.0 puntos</strong>
                                <div id="totalEstado" style="margin-top: 5px; font-size: 14px;"></div>
                            </div>
                        </div>

                        <input type="hidden" name="vchClvMateria" id="vchClvMateria" value="<?= $vchClvMateria ?>">

                    <?php endif; ?>
                </div>
                <div class="modal-footer" style="background: var(--sys-neutral);">
                    <button type="button" class="btn btn-danger sys-rounded" data-dismiss="modal">
                        <span class="glyphicon glyphicon-remove"></span> Cancelar
                    </button>
                    <?php if ($parciales_disponibles > 0): ?>
                        <button type="submit" class="btn sys-esquema-btn sys-rounded" id="btn-guardar">
                            <span class="glyphicon glyphicon-floppy-disk"></span> Guardar
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Modal Confirmar Eliminación -->
<div class="modal fade" id="modalConfirmarEliminacion" tabindex="-1" role="dialog" aria-labelledby="confirmarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="confirmarLabel">Confirmar eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Desea eliminar este esquema?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
            </div>
        </div>
    </div>
</div>




<!-- Contenedor de mensajes -->
<div id="msgFlash"></div>

<script>
    $(function() {
        var totales = <?= json_encode($totales_por_parcial) ?>;
        var disponibles = <?= $parciales_disponibles ?>;
        let esquemaCounter = 0;


        // Accordion con scroll
        $('#accordion').on('show.bs.collapse', function(e) {
            setTimeout(() => $('html, body').animate({
                scrollTop: $('#h' + e.target.id.slice(1)).offset().top - 20
            }, 500), 100);
        });

        // Generar esquemas dinámicamente
        function generarEsquemas(cantidad) {
            const container = $('#esquemasContainer');
            const parcialSeleccionado = parseInt($('#parcial').val());

            if (!parcialSeleccionado) {
                container.empty();
                $('#totalDisplay').hide();
                // Generar esquemas vacíos para mostrar la estructura
                for (let i = 0; i < cantidad; i++) {
                    const esquemaHTML = `
        <div class="esquema-item" data-index="${i}">
            <button type="button" class="remove-esquema" style="display: ${cantidad > 2 ? 'flex' : 'none'}">×</button>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label><strong>Esquema ${i + 1} *</strong></label>
                        <input type="text" name="componentes[${i}][nombre]" class="form-control sys-esquema-form-control sys-rounded esquema-nombre" 
                               placeholder="Ej: Examen, Proyecto, Participación..." required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><strong>Puntos *</strong></label>
                        <input type="number" name="componentes[${i}][valor]" class="form-control sys-esquema-form-control sys-rounded esquema-puntos" 
                               min="0.1" max="10" step="0.1" placeholder="0.0" required disabled>
                    </div>
                </div>
            </div>
        </div>
        `;
                    container.append(esquemaHTML);
                }
                return;
            }

            const disponible = 10 - (totales[parcialSeleccionado] || 0);

            container.empty();
            esquemaCounter = 0;

            for (let i = 0; i < cantidad; i++) {
                const esquemaHTML = `
                <div class="esquema-item" data-index="${i}">
                    <button type="button" class="remove-esquema" style="display: ${cantidad > 2 ? 'flex' : 'none'}">×</button>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label><strong>Esquema ${i + 1} *</strong></label>
                                <input type="text" name="componentes[${i}][nombre]" class="form-control sys-esquema-form-control sys-rounded esquema-nombre" 
                                       placeholder="Ej: Examen, Proyecto, Participación..." required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><strong>Puntos *</strong></label>
                                <input type="number" name="componentes[${i}][valor]" class="form-control sys-esquema-form-control sys-rounded esquema-puntos" 
                                       min="0.1" max="${disponible}" step="0.1" placeholder="0.0" required>
                            </div>
                        </div>
                    </div>
                </div>
            `;
                container.append(esquemaHTML);
            }

            // Distribución automática inteligente
            if (disponible > 0) {
                const puntosBase = Math.floor((disponible / cantidad) * 10) / 10;
                const resto = Math.round((disponible - (puntosBase * cantidad)) * 10) / 10;

                container.find('.esquema-puntos').each(function(index) {
                    let valor = puntosBase;
                    if (index < Math.abs(resto * 10)) {
                        valor += resto > 0 ? 0.1 : -0.1;
                    }
                    $(this).val(Math.max(0.1, valor).toFixed(1));
                });
            }

            calcularTotal();
        }

        // Calcular total y validar
        function calcularTotal() {
            const parcialSeleccionado = parseInt($('#parcial').val());
            if (!parcialSeleccionado) return;

            const disponible = 10 - (totales[parcialSeleccionado] || 0);
            let total = 0;
            let todosCompletos = true;

            $('.esquema-puntos').each(function() {
                const valor = parseFloat($(this).val()) || 0;
                total += valor;

                const $item = $(this).closest('.esquema-item');
                const nombre = $item.find('.esquema-nombre').val().trim();

                if (!nombre || valor <= 0) {
                    todosCompletos = false;
                    $item.removeClass('valid').addClass('invalid');
                } else {
                    $item.removeClass('invalid').addClass('valid');
                }
            });

            total = Math.round(total * 10) / 10;

            $('#totalPuntos').text(total.toFixed(1));
            $('#totalDisplay').show();

            const $totalDisplay = $('#totalDisplay');
            const $totalEstado = $('#totalEstado');
            const $btnGuardar = $('#btn-guardar');

            // Limpiar clases previas
            $totalDisplay.removeClass('alert-success alert-warning alert-danger');

            if (total === disponible && todosCompletos && disponible > 0) {
                $totalDisplay.addClass('alert-success');
                $totalEstado.html('<span class="glyphicon glyphicon-ok"></span> ¡Perfecto! Los esquemas completan exactamente los puntos disponibles');
                $btnGuardar.prop('disabled', false);
            } else if (total < disponible) {
                $totalDisplay.addClass('alert-warning');
                $totalEstado.html(`<span class="glyphicon glyphicon-warning-sign"></span> Faltan ${(disponible - total).toFixed(1)} puntos para completar`);
                $btnGuardar.prop('disabled', true);
            } else if (total > disponible) {
                $totalDisplay.addClass('alert-danger');
                $totalEstado.html(`<span class="glyphicon glyphicon-exclamation-sign"></span> Excede por ${(total - disponible).toFixed(1)} puntos. Máximo disponible: ${disponible.toFixed(1)}`);
                $btnGuardar.prop('disabled', true);
            } else {
                $totalDisplay.addClass('alert-warning');
                $totalEstado.html('<span class="glyphicon glyphicon-warning-sign"></span> Complete todos los campos correctamente');
                $btnGuardar.prop('disabled', true);
            }
        }

        // Eventos de cantidad
        $('#incrementarCantidad').click(function() {
            const $input = $('#cantidadEsquemas');
            const valor = parseInt($input.val());
            if (valor < 10) {
                $input.val(valor + 1);
                generarEsquemas(valor + 1);
            }
        });

        $('#decrementarCantidad').click(function() {
            const $input = $('#cantidadEsquemas');
            const valor = parseInt($input.val());
            if (valor > 2) {
                $input.val(valor - 1);
                generarEsquemas(valor - 1);
            }
        });

        // Cambio de parcial
        $('#parcial').change(function() {
            const cantidad = parseInt($('#cantidadEsquemas').val());
            generarEsquemas(cantidad);
        });

        // Eliminar esquema individual
        $(document).on('click', '.remove-esquema', function() {
            const cantidad = $('.esquema-item').length;
            if (cantidad > 2) {
                $(this).closest('.esquema-item').remove();

                // Reindexar
                $('.esquema-item').each(function(index) {
                    $(this).attr('data-index', index);
                    $(this).find('label strong').text(`Esquema ${index + 1} *`);
                    $(this).find('[name^="componentes"]').each(function() {
                        const name = $(this).attr('name');
                        const newName = name.replace(/componentes\[\d+\]/, `componentes[${index}]`);
                        $(this).attr('name', newName);
                    });
                });

                calcularTotal();
            }
        });

        // Eventos de cambio en inputs
        $(document).on('input change', '.esquema-nombre, .esquema-puntos', function() {
            calcularTotal();
        });






        // Cerrar accordion
        $('.sys-esquema-close').click(function(e) {
            e.preventDefault();
            var $target = $($(this).data('target'));
            if ($target.hasClass('in')) {
                $target.collapse('hide');
                setTimeout(() => $('html, body').animate({
                    scrollTop: $('#h' + $target.attr('id').slice(1)).offset().top - 20
                }, 500), 100);
            }
        });

        // Validación en tiempo real
        if (disponibles > 0) {
            $('#parcial, #valor_componente').on('change input', function() {
                var parcial = parseInt($('#parcial').val()),
                    valor = parseFloat($('#valor_componente').val()) || 0;
                var $info = $('#validacion-info'),
                    $btn = $('#btn-guardar');

                if (!parcial || valor <= 0) {
                    $info.hide();
                    $btn.prop('disabled', false);
                    return;
                }

                var actual = totales[parcial] || 0,
                    nuevo = actual + valor;

                if (nuevo > 10) {
                    $info.removeClass('alert-info alert-success').addClass('alert-danger');
                    $('#validacion-icon').removeClass().addClass('glyphicon glyphicon-exclamation-sign');
                    $('#validacion-texto').html('<strong>¡Error!</strong> El parcial ' + parcial + '° ya tiene ' + actual.toFixed(1) + ' puntos. Solo quedan ' + (10 - actual).toFixed(1) + ' puntos disponibles.');
                    $info.show();
                    $btn.prop('disabled', true);
                } else if (nuevo === 10) {
                    $info.removeClass('alert-danger alert-info').addClass('alert-success');
                    $('#validacion-icon').removeClass().addClass('glyphicon glyphicon-ok');
                    $('#validacion-texto').html('<strong>¡Perfecto!</strong> El parcial ' + parcial + '° completará exactamente 10.0 puntos.');
                    $info.show();
                    $btn.prop('disabled', false);
                } else {
                    $info.removeClass('alert-danger alert-success').addClass('alert-info');
                    $('#validacion-icon').removeClass().addClass('glyphicon glyphicon-info-sign');
                    $('#validacion-texto').html('El parcial ' + parcial + '° tendrá ' + nuevo.toFixed(1) + ' puntos. Quedarán ' + (10 - nuevo).toFixed(1) + ' puntos disponibles.');
                    $info.show();
                    $btn.prop('disabled', false);
                }
            });

            // Validación al enviar
            $('form').submit(function(e) {
                var parcial = parseInt($('#parcial').val()),
                    valor = parseFloat($('#valor_componente').val());
                if (!parcial || valor <= 0 || valor > 10 || (totales[parcial] || 0) + valor > 10) {
                    e.preventDefault();
                    mostrarMensaje('Por favor, complete todos los campos correctamente.', 'error');
                    return false;
                }
            });
        }

        let idAEliminar = null;

        function confirmarEliminacion(id) {
            idAEliminar = id;
            $('#modalConfirmarEliminacion').modal('show');
        }

        // Eliminar
        $(document).on('click', '.eliminar', function() {
            var id = $(this).data('id');
            confirmarEliminacion(id); // Llama al modal de confirmación
        });

        $('#btnConfirmarEliminar').click(function() {
            if (!idAEliminar) return;

            $.post("<?= base_url('sysmater/docente/gestionar_materia/eliminar_componente') ?>", {
                id_valor_componente: idAEliminar
            }, function(response) {
                $('#modalConfirmarEliminacion').modal('hide');

                if (response === 'ok') {

                    mostrarMensaje("Esquema de evaluacion eliminado correctamente.", "success");
                    setTimeout(() => location.reload(), 1500);
                } else if (response === 'en_uso') {

                    mostrarMensaje("Este componente está siendo utilizado en una o más actividades y no puede ser eliminado.", "error");

                } else {
                    mostrarMensaje('Error al eliminar el componente.', 'error');
                }

                idAEliminar = null;
            });
        });

        // Generar esquemas al abrir el modal
        $('#modal').on('shown.bs.modal', function() {
            if (disponibles > 0) {
                const cantidad = parseInt($('#cantidadEsquemas').val());
                generarEsquemas(cantidad);
            }
        });


        // Limpiar modal
        $('#modal').on('hidden.bs.modal', function() {
            if (disponibles > 0) {
                $(this).find('form')[0].reset();
                $('#validacion-info').hide();
                $('#btn-guardar').prop('disabled', false);
            }
        });
    });


    // Función para mostrar mensajes
    function mostrarMensaje(mensaje, tipo = "info") {
        const msgFlash = document.getElementById('msgFlash');

        const alertClass = tipo === "success" ? "alert-success" : "alert-error";

        msgFlash.innerHTML = `
                <div class="alert ${alertClass}" role="alert">
                    <i class="fas ${tipo === "success" ? "fa-check-circle" : "fa-exclamation-triangle"}"></i>
                    ${mensaje}
                </div>
            `;

        // Auto-ocultar después de 5 segundos
        setTimeout(() => {
            const alertDiv = msgFlash.querySelector('.alert');
            if (alertDiv) {
                alertDiv.classList.add('fade-out');
                setTimeout(() => {
                    msgFlash.innerHTML = '';
                }, 500);
            }
        }, 5000);
    }
</script>