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
</style>

<!-- Incluir el CSS del sistema -->
<link rel="stylesheet" href="<?= base_url() ?>assets/css/sys-styles.css">

<div class="container-fluid">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb sys-esquema-breadcrumb sys-rounded">
            <li class="breadcrumb-item">
                <a href="javascript:history.back()">
                    <span class="glyphicon glyphicon-list-alt"></span> Regresar a Instrumentos
                </a>
            <li class="breadcrumb-item active" aria-current="page">
                <span class="glyphicon glyphicon-cog"></span> Detalles del Instrumento
            </li>
        </ol>
    </nav>
    <!-- Header -->
    <div class="text-center" style="margin-bottom: 30px;">
        <h2>Criterios de Evaluación</h2>
        <p>Gestiona los criterios del instrumento de evaluación</p>
    </div>

    <!-- Panel de Información -->
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-info-sign"></span> Información del Instrumento
            </h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <h4><span class="glyphicon glyphicon-list"></span> Total de Criterios: <span id="totalCriterios"><?= $criterios->num_rows() ?></span></h4>
                    <p><b>Valor Total Asignado:</b> <span id="valorTotal" class="badge sys-badge">0</span> / 10 puntos</p>
                </div>
                <div class="col-md-6">
                    <div id="mensajeError" class="alert alert-danger" style="display: none;">
                        <span class="glyphicon glyphicon-exclamation-sign"></span> El valor total no puede exceder 10 puntos.
                    </div>
                    <div id="mensajeCompleto" class="alert alert-success" style="display: none;">
                        <span class="glyphicon glyphicon-ok-circle"></span> Distribución de puntos completa (10/10)
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario Principal -->
    <form id="formCriterios" action="<?= base_url('sysmater/docente/ver_instrumentos/actualizar_todos') ?>" method="post">
        <div class="panel panel-success sys-panel-success sys-panel-rounded">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <span class="glyphicon glyphicon-list"></span> Lista de Criterios de Evaluación
                </h3>
            </div>

            <div class="panel-body" style="padding: 0;">
                <div class="table-responsive sys-rounded">
                    <table class="table table-striped table-hover" style="margin-bottom: 0;">
                        <thead class="bg-success">
                            <tr>
                                <th>Nombre del Criterio</th>
                                <th>Descripción</th>
                                <th class="text-center">Valor Máximo</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaCriterios">
                            <?php if ($criterios->num_rows() > 0): ?>
                                <?php $index = 0; ?>
                                <?php foreach ($criterios->result() as $criterio): ?>
                                    <tr data-id="<?= $criterio->id_criterio ?>">
                                        <td>
                                            <input type="hidden" name="criterios[<?= $index ?>][id]" value="<?= $criterio->id_criterio ?>">
                                            <input type="text" name="criterios[<?= $index ?>][nombre]" class="form-control nombre" value="<?= htmlspecialchars($criterio->nombre) ?>" required>
                                        </td>
                                        <td>
                                            <textarea name="criterios[<?= $index ?>][descripcion]" class="form-control descripcion" required><?= htmlspecialchars($criterio->descripcion) ?></textarea>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge sys-badge"><?= $criterio->valor_maximo ?> pts</span>
                                            <input type="number" name="criterios[<?= $index ?>][valor_maximo]" class="form-control valor_maximo text-center" value="<?= $criterio->valor_maximo ?>"
                                                min="0" max="10" step="0.1" style="width: 80px; display: inline-block; margin-left: 10px;" required>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-primary sys-btn-outline-primary btnEliminar"
                                                style="color: #d9534f; border-color: #d9534f;">
                                                <span class="glyphicon glyphicon-trash"></span>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php $index++; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr id="noDataRow">
                                    <td colspan="4" class="text-center text-muted" style="padding: 40px;">
                                        <span class="glyphicon glyphicon-info-sign" style="font-size: 24px; margin-bottom: 10px; display: block;"></span>
                                        No hay criterios definidos para este instrumento.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel-footer bg-light">
                <div class="row">
                    <div class="col-md-4">
                        <button type="button" class="btn btn-outline-primary sys-btn-outline-primary" id="btnAgregarFila">
                            <span class="glyphicon glyphicon-plus"></span> Agregar Criterio
                        </button>
                    </div>

                    <input type="hidden" name="id_instrumento" id="" value="<?= $id_instrumento ?>">
                    <div class="col-md-4 text-center">
                        <button type="submit" class="btn btn-success" id="btnGuardarTodo" disabled>
                            <span class="glyphicon glyphicon-floppy-disk"></span> Guardar Todos los Criterios
                        </button>
                    </div>
                    <div class="col-md-4 text-right">
                        <span class="glyphicon glyphicon-info-sign"></span>
                        Los valores deben sumar exactamente <strong>10 puntos</strong>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Contenedor de mensajes -->
<div id="msgFlash"></div>

<script>
    $(document).ready(function() {
        const MAX_TOTAL = 10;
        let contadorIndex = <?= isset($index) ? $index : 0 ?>;

        // Función unificada para recalcular y actualizar UI
        function actualizarEstado() {
            let total = 0;
            let count = 0;

            $('.valor_maximo').each(function() {
                const valor = parseFloat($(this).val()) || 0;
                total += valor;
                count++;
            });

            $('#valorTotal').text(total.toFixed(1));
            $('#totalCriterios').text(count);

            const $error = $('#mensajeError');
            const $completo = $('#mensajeCompleto');
            const $total = $('#valorTotal');
            const $btnGuardar = $('#btnGuardarTodo');

            $error.hide();
            $completo.hide();

            if (total > MAX_TOTAL) {
                $error.show();
                $total.attr('class', 'badge').css({
                    background: '#f8d7da',
                    color: '#721c24'
                });
                $btnGuardar.prop('disabled', true);
            } else if (total === MAX_TOTAL && count > 0) {
                $completo.show();
                $total.attr('class', 'badge').css({
                    background: '#d4edda',
                    color: '#155724'
                });
                $btnGuardar.prop('disabled', false);
            } else {
                $total.attr('class', 'badge sys-badge');
                $btnGuardar.prop('disabled', true);
            }

            $('#noDataRow').toggle(count === 0);

            // Actualizar badges individuales
            $('.valor_maximo').each(function() {
                const valor = parseFloat($(this).val()) || 0;
                $(this).closest('tr').find('.badge').text(valor.toFixed(1) + ' pts');
            });
        }

        // Event listeners
        $(document).on('input', '.valor_maximo', function() {
            // Validar que no exceda 10
            const valor = parseFloat($(this).val());
            if (valor > 10) {
                $(this).val(10);
                mostrarMensaje('El valor máximo por criterio es 10 puntos', 'error');
            }
            actualizarEstado();
        });

        // Agregar nueva fila
        $('#btnAgregarFila').click(function() {
            const newRow = `
                <tr data-id="nuevo_${contadorIndex}">
                    <td>
                        <input type="hidden" name="criterios[${contadorIndex}][id]" value="">
                        <input type="text" name="criterios[${contadorIndex}][nombre]" class="form-control nombre" placeholder="Nombre del criterio" required>
                    </td>
                    <td>
                        <textarea name="criterios[${contadorIndex}][descripcion]" class="form-control descripcion" placeholder="Descripción del criterio" required></textarea>
                    </td>
                    <td class="text-center">
                        <span class="badge sys-badge">0.0 pts</span>
                        <input type="number" name="criterios[${contadorIndex}][valor_maximo]" class="form-control valor_maximo text-center" 
                               min="0" max="10" step="0.1" style="width: 80px; display: inline-block; margin-left: 10px;" 
                               placeholder="0.0" required>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-outline-primary sys-btn-outline-primary btnEliminar" 
                                style="color: #d9534f; border-color: #d9534f;">
                            <span class="glyphicon glyphicon-trash"></span>
                        </button>
                    </td>
                </tr>`;

            $('#tablaCriterios').append(newRow);
            contadorIndex++;
            actualizarEstado();
        });

        // Eliminar fila
        $(document).on('click', '.btnEliminar', function() {
            if (confirm('¿Está seguro de que desea eliminar este criterio?')) {
                $(this).closest('tr').remove();
                actualizarEstado();
                mostrarMensaje('Criterio eliminado', 'success');
            }
        });

        // Validación del formulario antes de enviar
        $('#formCriterios').on('submit', function(e) {
            let total = 0;
            let hasErrors = false;

            // Validar campos requeridos
            $('.nombre, .descripcion, .valor_maximo').each(function() {
                if (!$(this).val() || $(this).val().trim() === '') {
                    hasErrors = true;
                    $(this).addClass('error');
                } else {
                    $(this).removeClass('error');
                }
            });

            // Validar nombres únicos
            const nombres = [];
            $('.nombre').each(function() {
                const nombre = $(this).val().trim().toLowerCase();
                if (nombres.includes(nombre)) {
                    hasErrors = true;
                    $(this).addClass('error');
                    mostrarMensaje('No puede haber criterios con nombres duplicados', 'error');
                } else {
                    nombres.push(nombre);
                }
            });

            // Calcular total
            $('.valor_maximo').each(function() {
                total += parseFloat($(this).val()) || 0;
            });

            if (hasErrors) {
                e.preventDefault();
                mostrarMensaje('Por favor, complete todos los campos correctamente', 'error');
                return false;
            }

            if (total !== MAX_TOTAL) {
                e.preventDefault();
                mostrarMensaje('La suma total debe ser exactamente 10 puntos', 'error');
                return false;
            }

            // Validar que hay al menos un criterio
            if ($('.valor_maximo').length === 0) {
                e.preventDefault();
                mostrarMensaje('Debe agregar al menos un criterio', 'error');
                return false;
            }

            mostrarMensaje('Guardando criterios...', 'info');
        });

        // Inicializar
        actualizarEstado();
    });

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

<style>
    .error {
        border-color: #d9534f !important;
        box-shadow: 0 0 0 0.2rem rgba(217, 83, 79, 0.25) !important;
    }

    .fade-out {
        opacity: 0;
        transition: opacity 0.5s ease-out;
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
</style>