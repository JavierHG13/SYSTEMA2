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

<!-- Solo sys-styles.css -->
<link rel="stylesheet" href="<?= base_url() ?>assets/css/sys-styles.css">

<div class="sys-esquema-container">
    <!-- Breadcrumbs -->
    <ol class="breadcrumb sys-esquema-breadcrumb sys-rounded">
        <!-- <li><a href="<?= site_url('/sysmater/docente/docente/ver_materias') ?>"><span class="glyphicon glyphicon-book"></span> Mis Materias</a></li>
        <li><a href="<?= site_url('/sysmater/docente/docente/ver_actividades/' . $id_grupo . '/' . $vchClvMateria) ?>"><span class="glyphicon glyphicon-tasks"></span> Actividades</a></li> -->
        <li><a href="<?= site_url('/sysmater/docente/docente/ver_alumnos/' . $id_actividad) ?>"><span class="glyphicon glyphicon-education"></span> Alumnos</a></li>
        <li class="active" aria-current="page"><span class="glyphicon glyphicon-pencil"></span> Calificar</li>
    </ol>

    <!-- Título -->
    <div class="text-center" style="margin-bottom: 30px;">
        <h2 class="hidden-xs" style="margin-bottom: 10px;"><?= htmlspecialchars($actividad->titulo) ?></h2>
        <h3 class="visible-xs" style="margin-bottom: 10px;"><?= htmlspecialchars($actividad->titulo) ?></h3>
        <p class="sys-text-muted">Evaluación individual por criterios</p>
    </div>

    <!-- Panel del estudiante -->
    <div class="panel panel-success sys-rounded">
        <div class="panel-heading">
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-user"></span> Información del Estudiante
            </h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <h4 style="color: var(--sys-primary); margin-bottom: 15px;">
                        <?= htmlspecialchars($alumno->vchNombre . ' ' . $alumno->vchAPaterno . ' ' . $alumno->vchAMaterno) ?>
                    </h4>
                    <p><strong>Matrícula:</strong> <?= htmlspecialchars($alumno->vchMatricula) ?></p>
                    <p><strong>Carrera:</strong> <?= htmlspecialchars($alumno->vchNomCarrera) ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Cuatrimestre:</strong> <?= htmlspecialchars($alumno->vchNomCuatri) ?></p>
                    <p><strong>Periodo:</strong> <?= htmlspecialchars($alumno->vchPeriodo) ?></p>
                    <p><strong>Grupo:</strong> <?= htmlspecialchars($alumno->vchGrupo) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerta de resultado (inicialmente oculta) -->
    <div id="alertaResultado" class="alert alert-dismissible sys-rounded" style="display: none;">
        <button type="button" class="close" onclick="ocultarAlerta()">
            <span aria-hidden="true">&times;</span>
        </button>
        <span class="glyphicon" id="alertaIcon"></span>
        <strong id="alertaTitulo"></strong>
        <span id="alertaMensaje"></span>
    </div>

    <!-- Tabla de criterios -->
    <div class="panel sys-esquema-panel sys-rounded">
        <div class="panel-heading">
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-list-alt"></span> Criterios de Evaluación
            </h3>
        </div>
        <div class="panel-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered sys-esquema-table" id="tbl_evaluacion">
                    <thead style="background: var(--sys-primary) !important; color: white !important;">
                        <tr>
                            <th style="width: 25%;">Criterio</th>
                            <th style="width: 45%;">Descripción</th>
                            <th style="width: 15%;" class="text-center">Valor Máximo</th>
                            <th style="width: 15%;" class="text-center">Cumple</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($criterios as $criterio): ?>
                            <tr>
                                <td><strong><?= $criterio->nombre ?></strong></td>
                                <td><?= $criterio->descripcion ?></td>
                                <td class="text-center">
                                    <span class="badge sys-esquema-badge">
                                        <?= $criterio->valor_maximo ?> pts
                                    </span>
                                </td>
                                <td class="text-center">
                                    <label class="checkbox-inline" style="margin: 0;">
                                        <input type="checkbox"
                                            class="criterio"
                                            data-valor="<?= $criterio->valor_maximo ?>"
                                            data-id="<?= $criterio->id_criterio ?>"
                                            checked
                                            style="transform: scale(1.3); margin-right: 5px;">
                                    </label>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot style="background: var(--sys-neutral) !important;">
                        <tr>
                            <td colspan="2" class="text-right"><strong>Calificación Total:</strong></td>
                            <td colspan="2" class="text-center">
                                <span class="badge sys-esquema-badge-complete" style="font-size: 16px; padding: 8px 12px;" id="totalBadge">
                                    <span id="totalPuntos">0</span> / 10 pts
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Observaciones -->
    <div class="panel sys-esquema-panel sys-rounded">
        <div class="panel-heading">
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-comment"></span> Observaciones (Opcional)
            </h3>
        </div>
        <div class="panel-body">
            <textarea id="observacion"
                class="form-control sys-esquema-form-control sys-rounded"
                rows="4"
                placeholder="Escribe observaciones adicionales sobre la evaluación del estudiante..."></textarea>
        </div>
    </div>

    <!-- Botones de acción -->
    <div class="text-center" style="margin: 30px 0;">
        <button class="btn sys-esquema-btn sys-rounded" onclick="guardarEvaluacion()" id="btnGuardar">
            <span class="glyphicon glyphicon-floppy-disk"></span> Guardar Evaluación
        </button>
        <button class="btn sys-btn-danger sys-rounded" onclick="cancelarEvaluacion()" style="margin-left: 10px;">
            <span class="glyphicon glyphicon-remove"></span> Cancelar
        </button>
    </div>
</div>


<!-- Contenedor de mensajes -->
<div id="msgFlash"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.criterio');
        const totalDisplay = document.getElementById('totalPuntos');
        const totalBadge = document.getElementById('totalBadge');
        let evaluacionGuardada = false;

        function calcularTotal() {
            let total = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    total += parseInt(cb.getAttribute('data-valor'));
                }
            });
            totalDisplay.textContent = total;
            updateTotalBadge(total);
        }

        function updateTotalBadge(total) {
            if (total >= 8) {
                totalBadge.className = 'badge sys-esquema-badge-complete';
                totalBadge.style.fontSize = '16px';
                totalBadge.style.padding = '8px 12px';
            } else if (total >= 6) {
                totalBadge.className = 'badge sys-esquema-badge-progress';
                totalBadge.style.fontSize = '16px';
                totalBadge.style.padding = '8px 12px';
            } else {
                totalBadge.className = 'badge sys-esquema-badge';
                totalBadge.style.fontSize = '16px';
                totalBadge.style.padding = '8px 12px';
            }
        }

        function mostrarAlerta(tipo, titulo, mensaje, autoOcultar = false) {
            const alerta = document.getElementById('alertaResultado');
            const icon = document.getElementById('alertaIcon');
            const tituloEl = document.getElementById('alertaTitulo');
            const mensajeEl = document.getElementById('alertaMensaje');

            // Configurar estilos según tipo
            alerta.className = `alert alert-dismissible sys-rounded alert-${tipo}`;

            if (tipo === 'success') {
                icon.className = 'glyphicon glyphicon-ok-circle';
                alerta.style.background = 'var(--sys-success)';
                alerta.style.borderColor = 'var(--sys-primary)';
                alerta.style.color = '#856404';
            } else if (tipo === 'danger') {
                icon.className = 'glyphicon glyphicon-exclamation-sign';
                alerta.style.background = '#f8d7da';
                alerta.style.borderColor = '#d9534f';
                alerta.style.color = '#721c24';
            }

            tituloEl.textContent = titulo;
            mensajeEl.textContent = mensaje;
            alerta.style.display = 'block';

            // Scroll suave hacia la alerta
            alerta.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });

            if (autoOcultar) {
                setTimeout(() => {
                    alerta.style.display = 'none';
                    if (tipo === 'success') {
                        // Regresar a la lista después del éxito
                        const returnUrl = document.referrer || '<?= site_url("/sysmater/docente/docente/ver_alumnos/" . $id_actividad) ?>';
                        window.location.href = returnUrl + '?refresh=' + Date.now();
                    }
                }, 2000);
            }
        }

        window.ocultarAlerta = function() {
            document.getElementById('alertaResultado').style.display = 'none';
        }

        window.guardarEvaluacion = function() {
            const btnGuardar = document.getElementById('btnGuardar');
            const observacion = document.getElementById('observacion').value;

            // Deshabilitar botón y mostrar loading
            btnGuardar.disabled = true;
            btnGuardar.innerHTML = '<span class="glyphicon glyphicon-refresh glyphicon-spin"></span> Guardando...';

            const criterios = [];
            checkboxes.forEach(cb => {
                criterios.push({
                    id_criterio: parseInt(cb.getAttribute('data-id')),
                    calificacion: cb.checked ? parseInt(cb.getAttribute('data-valor')) : 0
                });
            });

            const data = {
                id_actividad: <?= json_encode($id_actividad) ?>,
                matricula: <?= json_encode($matricula) ?>,
                observacion: observacion,
                criterios: criterios,
            };

            fetch("<?= site_url('sysmater/docente/calificar_actividad/guardar_evaluacion_individual') ?>", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(data)
                })
                .then(async res => {
                    const text = await res.text();
                    console.log("Respuesta del servidor:", text);
                    const json = JSON.parse(text);

                    if (json.success) {
                        evaluacionGuardada = true;
                        mostrarAlerta('success', '¡Evaluación guardada!',
                            `Calificación: ${totalDisplay.textContent}/10 pts. Redirigiendo...`, true);
                    } else {
                        mostrarAlerta('danger', 'Error al guardar', json.message || 'Error desconocido');
                    }
                })
                .catch(err => {
                    console.error(err);
                    mostrarAlerta('danger', 'Error de conexión', 'No se pudo conectar con el servidor. Inténtalo de nuevo.');
                })
                .finally(() => {
                    // Restaurar botón
                    btnGuardar.disabled = false;
                    btnGuardar.innerHTML = '<span class="glyphicon glyphicon-floppy-disk"></span> Guardar Evaluación';
                });
        }

        // OPCIÓN A: Siempre preguntar al cancelar
        window.cancelarEvaluacion = function() {
            if (confirm('¿Estás seguro de cancelar? Se perderán todos los cambios no guardados.')) {
                window.location.href = document.referrer || '<?= site_url("/sysmater/docente/docente/ver_alumnos/" . $id_actividad) ?>';
            }
        }

        // Event listeners
        checkboxes.forEach(cb => cb.addEventListener('change', calcularTotal));

        // Inicializar
        calcularTotal();

        console.log('🎯 Sistema de calificación individual inicializado');
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