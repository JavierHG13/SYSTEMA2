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


    .remove-esquema-editar {
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

    .remove-esquema-editar:hover {
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

    .btn-agregar-mas {
        background: #218838;
        color: white;

        padding: 10px 20px;
        border-radius: 6px;
        transition: all 0.3s ease;
        margin-bottom: 15px;


    }

    .btn-agregar-mas:hover {

        background: var(--sys-success);
        color: white;
        border: none;
        
    }

    .modal-header {
        background: var(--sys-primary);
        color: white;
    }

    #totalDisplay {
        padding: 15px;
        border-radius: 8px;
        margin-top: 15px;
        border: 2px solid;
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
                            <h4 style="margin: 0; display: flex; justify-content: space-between; align-items: center;">
                                <a data-toggle="collapse" data-parent="#accordion" href="#p<?= $parcial; ?>" class="sys-esquema-accordion-toggle" style="text-decoration: none !important; color: var(--sys-primary) !important; font-weight: 600;">
                                    <span class="glyphicon glyphicon-education"></span> <?= $parcial; ?>° Parcial
                                    <span class="badge sys-esquema-badge" style="margin-left: 10px;"><?= count($componentes_parcial) ?> elementos</span>
                                    <span class="badge sys-esquema-badge-<?= $total_parcial >= 10 ? 'complete' : 'progress' ?>" style="margin-left: 5px;">
                                        <?= number_format($total_parcial, 1) ?>/10 pts
                                    </span>
                                </a>
                                <button class="btn btn-warning btn-sm editar-parcial"
                                    data-parcial="<?= $parcial ?>"
                                    title="Editar parcial completo">
                                    <span class="glyphicon glyphicon-edit"></span> Editar
                                </button>

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

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($componentes_parcial as $comp): ?>
                                                <tr>
                                                    <td><strong><?= $comp->componente ?></strong></td>
                                                    <td class="text-center">
                                                        <span class="badge sys-esquema-badge"><?= number_format($comp->valor_componente, 1) ?> pts</span>
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




<!-- Modal Simplificado -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog" role="document">
        <form action="#" method="post" id="formEsquemas">
            <div class="modal-content sys-rounded">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">
                        <span class="glyphicon glyphicon-plus"></span> Agregar Elemento al Esquema
                    </h4>
                </div>
                <div class="modal-body">
                    <!-- Selección de Parcial -->
                    <div class="form-group">
                        <label for="parcial"><strong>Parcial *</strong></label>
                        <select name="parcial" id="parcial" class="form-control sys-esquema-form-control sys-rounded" required>
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
                        <small class="help-block">
                            Cada parcial puede tener máximo 10.0 puntos en total
                        </small>
                    </div>

                    <!-- Contenedor de Esquemas -->
                    <div id="esquemasContainer" style="display: none;">
                        <!-- Los esquemas se generarán aquí -->
                    </div>

                    <!-- Botón Agregar Más -->
                    <button type="button" id="btnAgregarMas" class="btn btn-agregar-mas" style="display: none;">
                        <span class="glyphicon glyphicon-plus"></span> Agregar Otro Esquema
                    </button>

                    <!-- Display del Total -->
                    <div id="totalDisplay" style="display: none;">
                        <div class="text-center">
                            <span class="glyphicon glyphicon-calculator"></span>
                            <strong>Total: <span id="totalPuntos">0.0</span> / <span id="maxPuntos">10.0</span> puntos</strong>
                            <div id="totalEstado" style="margin-top: 5px; font-size: 14px;"></div>
                        </div>
                    </div>

                    <input type="hidden" name="vchClvMateria" value="<?= $vchClvMateria ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger sys-rounded" data-dismiss="modal">
                        <span class="glyphicon glyphicon-remove"></span> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary sys-rounded" id="btn-guardar" disabled>
                        <span class="glyphicon glyphicon-floppy-disk"></span> Guardar
                    </button>
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


<!-- Modal para Editar Parcial Completo -->
<div class="modal fade" id="modalEditarParcial" tabindex="-1" role="dialog" aria-labelledby="modalEditarParcialLabel">
    <div class="modal-dialog modal-lg" role="document">
        <form action="#" method="post" id="formEditarParcial">
            <div class="modal-content sys-rounded">
                <div class="modal-header" style="color: white;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">
                        <span class="glyphicon glyphicon-edit"></span> Editar <span id="editarTituloParcial"></span>
                    </h4>
                </div>
                <div class="modal-body">
                    <!-- Información del parcial -->
                    <div class="alert alert-info sys-rounded">
                        <span class="glyphicon glyphicon-info-sign"></span>
                        <strong>Editando:</strong> <span id="editarInfoParcial"></span><br>
                        <small>Modifica los esquemas manteniendo un total de exactamente 10.0 puntos</small>
                    </div>

                    <!-- Contenedor de Esquemas Editables -->
                    <div id="editarEsquemasContainer">
                        <!-- Los esquemas se cargarán aquí dinámicamente -->
                    </div>

                    <!-- Botón Agregar Más -->
                    <button type="button" id="editarBtnAgregarMas" class="btn btn-agregar-mas" style="display: none;">
                        <span class="glyphicon glyphicon-plus"></span> Agregar Otro Esquema
                    </button>

                    <!-- Display del Total -->
                    <div id="editarTotalDisplay" class="alert sys-rounded" style="margin-top: 15px;">
                        <div class="text-center">
                            <span class="glyphicon glyphicon-calculator"></span>
                            <strong>Total: <span id="editarTotalPuntos">0.0</span> / 10.0 puntos</strong>
                            <div id="editarTotalEstado" style="margin-top: 5px; font-size: 14px;"></div>
                        </div>
                    </div>

                    <input type="hidden" name="parcial_editar" id="editarParcialNumero">
                    <input type="hidden" name="vchClvMateria" value="<?= $vchClvMateria ?>">
                </div>
                <div class="modal-footer" style="background: var(--sys-neutral);">
                    <button type="button" class="btn btn-danger sys-rounded" data-dismiss="modal">
                        <span class="glyphicon glyphicon-remove"></span> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning sys-rounded" id="btn-actualizar-parcial" disabled>
                        <span class="glyphicon glyphicon-floppy-disk"></span> Actualizar Parcial
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>



<!-- Contenedor de mensajes -->
<div id="msgFlash"></div>

<script>
    $(document).ready(function() {
        var totales = <?= json_encode($totales_por_parcial) ?>;
        var disponibles = <?= $parciales_disponibles ?>;
        let esquemaCounter = 0;
        let idAEliminar = null;

        // Datos de parciales desde PHP
        const parciales = <?= json_encode([
                                1 => ['disponible' => 10 - ($totales_por_parcial[1] ?? 0), 'total' => 10],
                                2 => ['disponible' => 10 - ($totales_por_parcial[2] ?? 0), 'total' => 10],
                                3 => ['disponible' => 10 - ($totales_por_parcial[3] ?? 0), 'total' => 10]
                            ]) ?>;

        // Accordion con scroll
        $('#accordion').on('show.bs.collapse', function(e) {
            setTimeout(() => $('html, body').animate({
                scrollTop: $('#h' + e.target.id.slice(1)).offset().top - 20
            }, 500), 100);
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

        // Cuando se selecciona un parcial
        $('#parcial').change(function() {
            const parcialSeleccionado = parseInt($(this).val());

            if (parcialSeleccionado && parciales[parcialSeleccionado].disponible > 0) {
                $('#esquemasContainer').show();
                $('#btnAgregarMas').show();
                $('#totalDisplay').show();
                $('#esquemasContainer').empty();
                esquemaCounter = 0;
                agregarEsquema();
                $('#maxPuntos').text(parciales[parcialSeleccionado].disponible.toFixed(1));
            } else {
                $('#esquemasContainer').hide();
                $('#btnAgregarMas').hide();
                $('#totalDisplay').hide();
                $('#esquemasContainer').empty();
                esquemaCounter = 0;
            }
            calcularTotal();
        });

        // Función para agregar un nuevo esquema
        function agregarEsquema() {
            const parcialSeleccionado = parseInt($('#parcial').val());
            if (!parcialSeleccionado) return;

            const disponible = parciales[parcialSeleccionado].disponible;
            const esquemaHTML = `
            <div class="esquema-item" data-index="${esquemaCounter}">
                ${esquemaCounter > 0 ? '<button type="button" class="remove-esquema">×</button>' : ''}
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label><strong>Esquema ${esquemaCounter + 1} *</strong></label>
                            <input type="text" name="componentes[${esquemaCounter}][nombre]" 
                                   class="form-control sys-esquema-form-control sys-rounded esquema-nombre" 
                                   placeholder="Ej: Examen, Proyecto, Participación..." required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><strong>Puntos *</strong></label>
                            <input type="number" name="componentes[${esquemaCounter}][valor]" 
                                   class="form-control sys-esquema-form-control sys-rounded esquema-puntos" 
                                   min="0.1" max="${disponible}" step="0.1" placeholder="0.0" required>
                        </div>
                    </div>
                </div>
            </div>
        `;
            $('#esquemasContainer').append(esquemaHTML);
            esquemaCounter++;
            if (esquemaCounter >= 10) $('#btnAgregarMas').hide();
        }

        // Eliminar esquema en modal agregar
        $(document).on('click', '.remove-esquema', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const $item = $(this).closest('.esquema-item');
            $item.remove();
            reindexarEsquemas();
            calcularTotal();

            // Mostrar botón agregar más si hay menos de 10
            if ($('.esquema-item').length < 10) {
                $('#btnAgregarMas').show();
            }
        });

        // Botón agregar más esquemas
        $('#btnAgregarMas').click(function() {
            agregarEsquema();
            calcularTotal();
        });

        // Reindexar esquemas después de eliminar
        function reindexarEsquemas() {
            $('.esquema-item').each(function(index) {
                $(this).attr('data-index', index);
                $(this).find('label strong').text(`Esquema ${index + 1} *`);
                $(this).find('[name^="componentes"]').each(function() {
                    const name = $(this).attr('name');
                    const newName = name.replace(/componentes\[\d+\]/, `componentes[${index}]`);
                    $(this).attr('name', newName);
                });
            });
            esquemaCounter = $('.esquema-item').length;
        }

        // Calcular total y validar
        function calcularTotal() {
            const parcialSeleccionado = parseInt($('#parcial').val());
            if (!parcialSeleccionado) return;

            const disponible = parciales[parcialSeleccionado].disponible;
            let total = 0;
            let todosCompletos = true;
            let hayDatos = false;

            $('.esquema-puntos').each(function() {
                const valor = parseFloat($(this).val()) || 0;
                total += valor;
                const $item = $(this).closest('.esquema-item');
                const nombre = $item.find('.esquema-nombre').val().trim();

                if (nombre || valor > 0) hayDatos = true;

                if (hayDatos && (!nombre || valor <= 0)) {
                    todosCompletos = false;
                    $item.removeClass('valid').addClass('invalid');
                } else if (hayDatos && nombre && valor > 0) {
                    $item.removeClass('invalid').addClass('valid');
                } else {
                    $item.removeClass('valid invalid');
                }
            });

            if (!hayDatos) {
                $('#btn-guardar').prop('disabled', true);
                return;
            }

            total = Math.round(total * 10) / 10;
            $('#totalPuntos').text(total.toFixed(1));

            const $totalDisplay = $('#totalDisplay');
            const $totalEstado = $('#totalEstado');
            const $btnGuardar = $('#btn-guardar');

            $totalDisplay.removeClass('alert-success alert-warning alert-danger');

            if (total === disponible && todosCompletos) {
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

        // Eventos de cambio en inputs
        $(document).on('input change', '.esquema-nombre, .esquema-puntos', function() {
            calcularTotal();
        });

        // Eliminar componente
        $(document).on('click', '.eliminar', function() {
            idAEliminar = $(this).data('id');
            $('#modalConfirmarEliminacion').modal('show');
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

        // Limpiar modal al cerrar
        $('#modal').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
            $('#esquemasContainer').hide().empty();
            $('#btnAgregarMas').hide();
            $('#totalDisplay').hide();
            $('#btn-guardar').prop('disabled', true);
            esquemaCounter = 0;
        });

        // Envío del formulario
        $('#formEsquemas').submit(function(e) {
            e.preventDefault();
            if ($('.esquema-item').length === 0) {
                alert('Debe agregar al menos un esquema');
                return;
            }


            console.log("Enviando datos")
            $.ajax({
                url: '<?= base_url('sysmater/docente/gestionar_materia/guardar_componente') ?>',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'ok') {
                        mostrarMensaje("Esquemas guardados correctamente.", "success");
                        $('#modal').modal('hide');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        mostrarMensaje('Error al guardar los esquemas.', 'error');
                    }
                },
                error: function() {
                    mostrarMensaje('Error de conexión.', 'error');
                }
            });
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
        setTimeout(() => {
            const alertDiv = msgFlash.querySelector('.alert');
            if (alertDiv) {
                alertDiv.classList.add('fade-out');
                setTimeout(() => msgFlash.innerHTML = '', 500);
            }
        }, 5000);
    }


    //Funciones para editar
    // Variables para edición de parcial
    let editarEsquemaCounter = 0;
    let esquemasParcialOriginales = [];

    // Evento click para editar parcial
    $(document).on('click', '.editar-parcial', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const parcial = $(this).data('parcial');
        console.log('Editando parcial:', parcial);

        // Obtener componentes del parcial
        $.ajax({
            url: '<?= base_url('sysmater/docente/gestionar_materia/obtener_componentes_parcial') ?>',
            method: 'POST',
            data: {
                parcial: parcial,
                vchClvMateria: '<?= $vchClvMateria ?>'
            },
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta del servidor:', response);

                if (response.success) {
                    abrirModalEditarParcial(parcial, response.componentes);
                } else {
                    mostrarMensaje('Error: ' + (response.message || 'No se pudieron cargar los componentes'), 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX:', status, error);
                console.error('Respuesta:', xhr.responseText);
                mostrarMensaje('Error de conexión al cargar los componentes del parcial.', 'error');
            }
        });
    });

    // Función para abrir modal de editar parcial completo
    function abrirModalEditarParcial(parcial, componentes) {
        console.log('Abriendo modal para parcial:', parcial, 'con componentes:', componentes);

        $('#editarParcialNumero').val(parcial);
        $('#editarTituloParcial').text(parcial + '° Parcial');
        $('#editarInfoParcial').text(parcial + '° Parcial - ' + componentes.length + ' componente(s)');

        cargarEsquemasParcial(parcial, componentes);
        $('#modalEditarParcial').modal('show');
    }

    // Cargar esquemas existentes del parcial
    function cargarEsquemasParcial(parcial, componentes) {
        console.log('Cargando esquemas para parcial:', parcial, componentes);

        // Guardar esquemas originales para comparación
        esquemasParcialOriginales = componentes.map(comp => ({
            id: comp.id_valor_componente,
            nombre: comp.componente,
            puntos: parseFloat(comp.valor_componente)
        }));

        // Limpiar contenedor
        $('#editarEsquemasContainer').empty();
        editarEsquemaCounter = 0;

        // Cargar cada esquema existente
        if (componentes && componentes.length > 0) {
            componentes.forEach(function(comp, index) {
                agregarEsquemaEdicion(comp.id_valor_componente, comp.componente, comp.valor_componente);
            });
        } else {
            // Si no hay esquemas, agregar uno vacío
            agregarEsquemaEdicion('', '', '');
        }

        // Mostrar botón agregar más si hay menos de 10
        if (editarEsquemaCounter < 10) {
            $('#editarBtnAgregarMas').show();
        } else {
            $('#editarBtnAgregarMas').hide();
        }

        // Calcular total inicial
        calcularTotalEdicion();
    }

    // Función para agregar esquema en edición
    function agregarEsquemaEdicion(id = '', nombre = '', puntos = '') {
        const esquemaHTML = `
        <div class="esquema-item" data-index="${editarEsquemaCounter}">
            ${editarEsquemaCounter > 0 || esquemasParcialOriginales.length > 1 ? '<button type="button" class="remove-esquema-editar">×</button>' : ''}
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label><strong>Esquema ${editarEsquemaCounter + 1} *</strong></label>
                        <input type="text" name="esquemas[${editarEsquemaCounter}][nombre]" 
                               class="form-control sys-esquema-form-control sys-rounded editar-esquema-nombre" 
                               placeholder="Ej: Examen, Proyecto, Participación..." 
                               value="${nombre}" required>
                        <input type="hidden" name="esquemas[${editarEsquemaCounter}][id]" value="${id}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><strong>Puntos *</strong></label>
                        <input type="number" name="esquemas[${editarEsquemaCounter}][puntos]" 
                               class="form-control sys-esquema-form-control sys-rounded editar-esquema-puntos" 
                               min="0.1" max="10" step="0.1" placeholder="0.0" 
                               value="${puntos}" required>
                    </div>
                </div>
            </div>
        </div>
    `;

        $('#editarEsquemasContainer').append(esquemaHTML);
        editarEsquemaCounter++;

        // Ocultar botón si se alcanza el máximo
        if (editarEsquemaCounter >= 10) {
            $('#editarBtnAgregarMas').hide();
        }
    }

    // Botón agregar más esquemas en edición
    $('#editarBtnAgregarMas').click(function() {
        agregarEsquemaEdicion();
        calcularTotalEdicion();
    });

    $(document).on('click', '#editarEsquemasContainer .remove-esquema-editar', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const $item = $(this).closest('.esquema-item');
        const id = $item.find('input[type="hidden"]').val();

        console.log("Id_esquema:", id, "tipo:", typeof id, "longitud:", id.length);
        console.log("Evaluación if (id):", !!id);

        if (id) {
            console.log("ENTRANDO AL IF - iniciando AJAX");

            $.ajax({
                url: '<?= base_url('sysmater/docente/gestionar_materia/verificar_componente_en_uso') ?>',
                method: 'POST',
                data: {
                    id_valor_componente: id
                },
                beforeSend: function() {
                    console.log("AJAX - beforeSend ejecutado");
                },
                success: function(response) {

                    const data = JSON.parse(response);

                    if (data.en_uso === true) {
                        mostrarMensaje('Este esquema tiene actividades ligadas y no se puede eliminar.', 'error');
                    } else {
                        console.log("AJAX - procediendo a eliminar");
                        $item.remove();
                        reindexarEsquemasEdicion();
                        calcularTotalEdicion();

                        if ($('#editarEsquemasContainer .esquema-item').length < 10) {
                            $('#editarBtnAgregarMas').show();
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.log("AJAX - error ejecutado:", status, error);
                    console.log("AJAX - respuesta completa:", xhr.responseText);
                    mostrarMensaje('Error al verificar el esquema.', 'error');
                }
            });
        } else {
            console.log("ENTRANDO AL ELSE");
            $item.remove();
            reindexarEsquemasEdicion();
            calcularTotalEdicion();

            if ($('#editarEsquemasContainer .esquema-item').length < 10) {
                $('#editarBtnAgregarMas').show();
            }
        }
    });

    // Reindexar esquemas en edición
    function reindexarEsquemasEdicion() {
        $('#editarEsquemasContainer .esquema-item').each(function(index) {
            $(this).attr('data-index', index);
            $(this).find('label strong').text(`Esquema ${index + 1} *`);
            $(this).find('[name^="esquemas"]').each(function() {

                const name = $(this).attr('name');
                const newName = name.replace(/esquemas\[\d+\]/, `esquemas[${index}]`);
                $(this).attr('name', newName);
            });
        });
        editarEsquemaCounter = $('#editarEsquemasContainer .esquema-item').length;
    }

    // Calcular total en edición
    function calcularTotalEdicion() {
        let total = 0;
        let todosCompletos = true;
        let hayDatos = false;
        let hayCambios = false;

        $('#editarEsquemasContainer .editar-esquema-puntos').each(function() {
            const valor = parseFloat($(this).val()) || 0;
            total += valor;
            const $item = $(this).closest('.esquema-item');
            const nombre = $item.find('.editar-esquema-nombre').val().trim();

            if (nombre || valor > 0) hayDatos = true;

            if (hayDatos && (!nombre || valor <= 0)) {
                todosCompletos = false;
                $item.removeClass('valid').addClass('invalid');
            } else if (hayDatos && nombre && valor > 0) {
                $item.removeClass('invalid').addClass('valid');
            } else {
                $item.removeClass('valid invalid');
            }
        });


        // Verificar si hay cambios comparando con originales
        const esquemasActuales = [];
        $('#editarEsquemasContainer .esquema-item').each(function() {
            const nombre = $(this).find('.editar-esquema-nombre').val().trim();
            const puntos = parseFloat($(this).find('.editar-esquema-puntos').val()) || 0;
            const id = $(this).find('input[type="hidden"]').val();
            if (nombre && puntos > 0) {
                esquemasActuales.push({
                    id,
                    nombre,
                    puntos
                });
            }
        });

        // Comparar con originales para detectar cambios
        if (esquemasActuales.length !== esquemasParcialOriginales.length) {
            hayCambios = true;
        } else {
            for (let i = 0; i < esquemasActuales.length; i++) {
                const actual = esquemasActuales[i];
                const original = esquemasParcialOriginales.find(orig => orig.id == actual.id);
                if (!original || actual.nombre !== original.nombre || Math.abs(actual.puntos - original.puntos) > 0.01) {
                    hayCambios = true;
                    break;
                }
            }
        }

        if (!hayDatos) {
            $('#btn-actualizar-parcial').prop('disabled', true);
            return;
        }

        total = Math.round(total * 10) / 10;
        $('#editarTotalPuntos').text(total.toFixed(1));

        const $totalDisplay = $('#editarTotalDisplay');
        const $totalEstado = $('#editarTotalEstado');
        const $btnActualizar = $('#btn-actualizar-parcial');

        // Limpiar clases previas
        $totalDisplay.removeClass('alert-success alert-warning alert-danger');

        if (total === 10.0 && todosCompletos && hayCambios) {
            $totalDisplay.addClass('alert-success');
            $totalEstado.html('<span class="glyphicon glyphicon-ok"></span> ¡Perfecto! Los esquemas suman exactamente 10.0 puntos y hay cambios para guardar');
            $btnActualizar.prop('disabled', false);
        } else if (total === 10.0 && todosCompletos && !hayCambios) {
            $totalDisplay.addClass('alert-warning');
            $totalEstado.html('<span class="glyphicon glyphicon-warning-sign"></span> No se han realizado cambios');
            $btnActualizar.prop('disabled', true);
        } else if (total < 10.0) {
            $totalDisplay.addClass('alert-warning');
            $totalEstado.html(`<span class="glyphicon glyphicon-warning-sign"></span> Faltan ${(10.0 - total).toFixed(1)} puntos para completar los 10.0 puntos`);
            $btnActualizar.prop('disabled', true);
        } else if (total > 10.0) {
            $totalDisplay.addClass('alert-danger');
            $totalEstado.html(`<span class="glyphicon glyphicon-exclamation-sign"></span> Excede por ${(total - 10.0).toFixed(1)} puntos. Debe ser exactamente 10.0 puntos`);
            $btnActualizar.prop('disabled', true);
        } else {
            $totalDisplay.addClass('alert-warning');
            $totalEstado.html('<span class="glyphicon glyphicon-warning-sign"></span> Complete todos los campos correctamente');
            $btnActualizar.prop('disabled', true);
        }
    }

    // Eventos de cambio en inputs de edición
    $(document).on('input change', '.editar-esquema-nombre, .editar-esquema-puntos', function() {
        calcularTotalEdicion();
    });

    // Limpiar modal al cerrar
    $('#modalEditarParcial').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
        $('#editarEsquemasContainer').empty();
        $('#editarBtnAgregarMas').hide();
        $('#btn-actualizar-parcial').prop('disabled', true);
        editarEsquemaCounter = 0;
        esquemasParcialOriginales = [];
    });

    // Envío del formulario de edición
    $('#formEditarParcial').submit(function(e) {
        e.preventDefault();

        if ($('#editarEsquemasContainer .esquema-item').length === 0) {
            mostrarMensaje('Debe tener al menos un esquema', 'error');
            return;
        }

        // Ver los datos que se envían
        const datos = $(this).serialize();
        console.log('Datos enviados:', datos);

        // Mostrar loading
        $('#btn-actualizar-parcial').prop('disabled', true).html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Guardando...');

        $.ajax({
            url: '<?= base_url('sysmater/docente/gestionar_materia/actualizar_parcial_completo') ?>',
            method: 'POST',
            data: $(this).serialize(),

            success: function(response) {

                console.log('Respuesta actualización:', response);

                if (response === 'ok') {
                    mostrarMensaje("Parcial actualizado correctamente.", "success");
                    $('#modalEditarParcial').modal('hide');
                    setTimeout(() => location.reload(), 1500);
                } else if (response === 'puntos_invalidos') {
                    mostrarMensaje('El total de puntos debe ser exactamente 10.0', 'error');
                } else if (response === 'actualizado_sin_borrar') {
                    mostrarMensaje('Parcial actualizado. No se eliminaron componentes porque hay actividades relacionadas.', 'warning');
                    $('#modalEditarParcial').modal('hide');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    mostrarMensaje('Error al actualizar el parcial: ' + response, 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al actualizar:', status, error);
                mostrarMensaje('Error de conexión al actualizar.', 'error');
            },
            complete: function() {
                $('#btn-actualizar-parcial').prop('disabled', false).html('<span class="glyphicon glyphicon-floppy-disk"></span> Actualizar Parcial');
            }
        });
    });
</script>