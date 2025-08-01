<!-- Solo sys-styles.css -->
<link rel="stylesheet" href="<?= base_url() ?>assets/css/sys-styles.css">

<div class="sys-esquema-container">
    <!-- Breadcrumbs -->
    <ol class="breadcrumb sys-esquema-breadcrumb sys-rounded">
        <!-- <li><a href="<?= site_url('/sysmater/docente/docente/ver_materias') ?>"><span class="glyphicon glyphicon-book"></span> Mis Materias</a></li>
        <li><a href="<?= site_url('/sysmater/docente/docente/ver_actividades/' . $id_grupo . '/' . $vchClvMateria) ?>"><span class="glyphicon glyphicon-tasks"></span> Actividades</a></li> -->
        <li><a href="javascript:history.back()"><span class="glyphicon glyphicon-education"></span> Alumnos</a></li>
        <li class="active" aria-current="page"><span class="glyphicon glyphicon-pencil"></span> Calificar Equipo</li>
    </ol>

    <!-- Título -->
    <div class="text-center" style="margin-bottom: 30px;">
        <h2 class="hidden-xs" style="margin-bottom: 10px;"><?= htmlspecialchars($actividad->titulo) ?></h2>
        <h3 class="visible-xs" style="margin-bottom: 10px;"><?= htmlspecialchars($actividad->titulo) ?></h3>
        <p class="sys-text-muted">Evaluación de equipo por criterios</p>
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

    <!-- Panel del equipo -->
    <div class="panel panel-success sys-rounded">
        <div class="panel-heading">
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-users"></span> Información del Equipo
            </h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <h4 style="color: var(--sys-primary); margin-bottom: 20px;">
                        <span class="glyphicon glyphicon-flag"></span> <?= htmlspecialchars($detalles_equipo->nombre_equipo) ?>
                    </h4>
                </div>
            </div>

            <!-- Integrantes del equipo -->
            <h5><span class="glyphicon glyphicon-user"></span> Integrantes y calificaciones</h5>
            <div class="panel sys-esquema-panel sys-rounded" style="margin-bottom: 20px;">
                <div class="panel-body" style="padding: 0;">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered sys-esquema-table">
                            <thead style="background: var(--sys-primary) !important; color: white !important;">
                                <tr>
                                    <th style="width: 50%;">Nombre del integrante</th>
                                    <th style="width: 25%;" class="text-center">Calificación</th>
                                    <th style="width: 25%;" class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($equipo as $alumno): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($alumno['vchNombre'] . ' ' . $alumno['vchAPaterno'] . ' ' . $alumno['vchAMaterno']) ?></strong></td>
                                        <td class="text-center">
                                            <span class="badge sys-esquema-badge-complete" id="calificacion-alumno-<?= $alumno['vchMatricula'] ?>">
                                                <?= htmlspecialchars($alumno['calificacion'] ?? '0') ?> / 10 pts
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-primary btn-sm btn-editar-individual sys-rounded"
                                                data-toggle="modal"
                                                data-target="#modalEvaluacionAlumno"
                                                data-id="<?= $alumno['vchMatricula'] ?>"
                                                data-nombre="<?= htmlspecialchars($alumno['vchNombre'] . ' ' . $alumno['vchAPaterno'] . ' ' . $alumno['vchAMaterno']) ?>"
                                                data-calificacion="<?= htmlspecialchars($alumno['calificacion'] ?? '0') ?>">
                                                <span class="glyphicon glyphicon-edit"></span> Editar
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de criterios del equipo -->
    <div class="panel sys-esquema-panel sys-rounded">
        <div class="panel-heading">
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-list-alt"></span> Criterios de Evaluación del Equipo
            </h3>
        </div>
        <div class="panel-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered sys-esquema-table" id="tbl_evaluacion_equipo">
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
                                <td><strong><?= htmlspecialchars($criterio->nombre) ?></strong></td>
                                <td><?= htmlspecialchars($criterio->descripcion) ?></td>
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
                                <span class="badge sys-esquema-badge-complete" style="font-size: 16px; padding: 8px 12px;" id="totalBadgeEquipo">
                                    <span id="totalPuntosEquipo">0</span> / 10 pts
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Observaciones del equipo -->
    <div class="panel sys-esquema-panel sys-rounded">
        <div class="panel-heading">
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-comment"></span> Observaciones del Equipo (Opcional)
            </h3>
        </div>
        <div class="panel-body">
            <textarea id="observacion_equipo"
                class="form-control sys-esquema-form-control sys-rounded"
                rows="4"
                placeholder="Escribe observaciones adicionales sobre la evaluación del equipo..."></textarea>
        </div>
    </div>

    <input type="hidden" id="inputIdEquipo" value="<?= htmlspecialchars($detalles_equipo->id_equipo) ?>">

    <!-- Botones de acción -->
    <div class="text-center" style="margin: 30px 0;">
        <button class="btn sys-esquema-btn sys-rounded" id="btnGuardarEquipo" data-id="<?= htmlspecialchars($id_actividad_equipo) ?>">
            <span class="glyphicon glyphicon-floppy-disk"></span> Guardar Evaluación
        </button>
        <button class="btn sys-btn-danger sys-rounded" onclick="cancelarEvaluacionEquipo()" style="margin-left: 10px;">
            <span class="glyphicon glyphicon-remove"></span> Cancelar
        </button>
    </div>
</div>

<!-- Modal evaluación individual -->
<div class="modal fade" id="modalEvaluacionAlumno" tabindex="-1" role="dialog" aria-labelledby="modalEvaluacionAlumnoLabel">
    <div class="modal-dialog modal-lg" role="document">
        <form id="formEvaluacionAlumno">
            <div class="modal-content sys-rounded">
                <div class="modal-header sys-esquema-modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">
                        <span class="glyphicon glyphicon-edit"></span> Evaluar alumno: <span id="nombreAlumno"></span>
                    </h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_alumno" id="inputIdAlumno">
                    <input type="hidden" name="id_actividad" value="<?= $actividad->id_actividad ?>">

                    <!-- Alerta del modal (inicialmente oculta) -->
                    <div id="alertaModalResultado" class="alert alert-dismissible sys-rounded" style="display: none; margin-bottom: 20px;">
                        <button type="button" class="close" onclick="ocultarAlertaModal()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="glyphicon" id="alertaModalIcon"></span>
                        <strong id="alertaModalTitulo"></strong>
                        <span id="alertaModalMensaje"></span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered sys-esquema-table">
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
                                        <td><strong><?= htmlspecialchars($criterio->nombre) ?></strong></td>
                                        <td><?= htmlspecialchars($criterio->descripcion) ?></td>
                                        <td class="text-center">
                                            <span class="badge sys-esquema-badge">
                                                <?= $criterio->valor_maximo ?> pts
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <label class="checkbox-inline" style="margin: 0;">
                                                <input type="checkbox"
                                                    class="criterio-individual"
                                                    name="criterios[]"
                                                    data-id="<?= $criterio->id_criterio ?>"
                                                    data-valor="<?= $criterio->valor_maximo ?>"
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
                                        <span class="badge sys-esquema-badge-complete" style="font-size: 16px; padding: 8px 12px;" id="totalBadgeModal">
                                            <span id="totalPuntosModal">0</span> / 10 pts
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <label for="observacion_alumno"><span class="glyphicon glyphicon-comment"></span> Observación</label>
                        <textarea class="form-control sys-esquema-form-control sys-rounded" name="observacion" id="observacion_alumno" rows="3" placeholder="Observaciones sobre la evaluación individual..."></textarea>
                    </div>

                    <input type="hidden" name="id_equipo" value="<?= $detalles_equipo->id_equipo ?>">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn sys-esquema-btn sys-rounded" id="btnGuardarModal">
                        <span class="glyphicon glyphicon-floppy-disk"></span> Guardar
                    </button>
                    <button type="button" class="btn sys-btn-default sys-rounded" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Referencias a checkboxes y total para equipo
        const checkboxesEquipo = document.querySelectorAll('#tbl_evaluacion_equipo input.criterio');
        const totalDisplayEquipo = document.getElementById('totalPuntosEquipo');
        const totalBadgeEquipo = document.getElementById('totalBadgeEquipo');
        let evaluacionGuardada = false;
        let datosOriginales = null;

        // Referencias para modal individual
        const checkboxesModal = document.querySelectorAll('.criterio-individual');
        const totalDisplayModal = document.getElementById('totalPuntosModal');
        const totalBadgeModal = document.getElementById('totalBadgeModal');

        // Guardar estado original
        function guardarEstadoOriginal() {
            datosOriginales = {
                criterios: Array.from(checkboxesEquipo).map(cb => cb.checked),
                observacion: document.getElementById('observacion_equipo').value
            };
        }

        // Verificar si hay cambios
        function hayCambios() {
            if (!datosOriginales) return false;
            const criteriosActuales = Array.from(checkboxesEquipo).map(cb => cb.checked);
            const observacionActual = document.getElementById('observacion_equipo').value;
            return JSON.stringify(criteriosActuales) !== JSON.stringify(datosOriginales.criterios) ||
                observacionActual !== datosOriginales.observacion;
        }

        // Funciones para mostrar alertas
        function mostrarAlerta(tipo, titulo, mensaje, autoOcultar = false) {
            const alerta = document.getElementById('alertaResultado');
            const icon = document.getElementById('alertaIcon');
            const tituloEl = document.getElementById('alertaTitulo');
            const mensajeEl = document.getElementById('alertaMensaje');

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

            alerta.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });

            if (autoOcultar) {
                setTimeout(() => {
                    alerta.style.display = 'none';
                    if (tipo === 'success') {
                        const returnUrl = document.referrer || '<?= site_url("/sysmater/docente/docente/ver_alumnos/" . $id_actividad) ?>';
                        window.location.href = returnUrl + '?refresh=' + Date.now();
                    }
                }, 2000);
            }
        }

        function mostrarAlertaModal(tipo, titulo, mensaje, autoOcultar = false) {
            const alerta = document.getElementById('alertaModalResultado');
            const icon = document.getElementById('alertaModalIcon');
            const tituloEl = document.getElementById('alertaModalTitulo');
            const mensajeEl = document.getElementById('alertaModalMensaje');

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

            if (autoOcultar) {
                setTimeout(() => {
                    alerta.style.display = 'none';
                }, 2000);
            }
        }

        window.ocultarAlerta = function() {
            document.getElementById('alertaResultado').style.display = 'none';
        }

        window.ocultarAlertaModal = function() {
            document.getElementById('alertaModalResultado').style.display = 'none';
        }

        // Función para actualizar badge según puntuación
        function updateTotalBadge(total, badge) {
            if (total >= 8) {
                badge.className = 'badge sys-esquema-badge-complete';
                badge.style.fontSize = '16px';
                badge.style.padding = '8px 12px';
            } else if (total >= 6) {
                badge.className = 'badge sys-esquema-badge-progress';
                badge.style.fontSize = '16px';
                badge.style.padding = '8px 12px';
            } else {
                badge.className = 'badge sys-esquema-badge';
                badge.style.fontSize = '16px';
                badge.style.padding = '8px 12px';
            }
        }

        // Calcular total del equipo
        function calcularTotalEquipo() {
            let total = 0;
            checkboxesEquipo.forEach(cb => {
                if (cb.checked) {
                    total += parseInt(cb.getAttribute('data-valor'));
                }
            });
            totalDisplayEquipo.textContent = total;
            updateTotalBadge(total, totalBadgeEquipo);
        }

        // Calcular total del modal
        function calcularTotalModal() {
            let total = 0;
            checkboxesModal.forEach(cb => {
                if (cb.checked) {
                    total += parseInt(cb.getAttribute('data-valor'));
                }
            });
            totalDisplayModal.textContent = total;
            updateTotalBadge(total, totalBadgeModal);
        }

        // Event listeners para checkboxes
        checkboxesEquipo.forEach(cb => cb.addEventListener('change', calcularTotalEquipo));
        checkboxesModal.forEach(cb => cb.addEventListener('change', calcularTotalModal));

        // Guardar evaluación equipo
        document.getElementById('btnGuardarEquipo').addEventListener('click', () => {
            const id_actividad_equipo = document.getElementById('btnGuardarEquipo').getAttribute('data-id');
            guardarEvaluacionEquipo(id_actividad_equipo);
        });

        function guardarEvaluacionEquipo(id_actividad_equipo) {
            const btnGuardar = document.getElementById('btnGuardarEquipo');
            const observacion = document.getElementById('observacion_equipo').value;
            const id_equipo = document.getElementById('inputIdEquipo').value;

            // Deshabilitar botón y mostrar loading
            btnGuardar.disabled = true;
            btnGuardar.innerHTML = '<span class="glyphicon glyphicon-refresh glyphicon-spin"></span> Guardando...';

            const criterios = [];
            checkboxesEquipo.forEach(cb => {
                criterios.push({
                    id_criterio: parseInt(cb.getAttribute('data-id')),
                    calificacion: cb.checked ? parseInt(cb.getAttribute('data-valor')) : 0
                });
            });

            const data = {
                id_actividad_equipo: id_actividad_equipo,
                id_equipo: id_equipo,
                observacion: observacion,
                criterios: criterios
            };

            fetch("<?= base_url('sysmater/docente/calificar_actividad/guardar_evaluacion_equipo') ?>", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(data)
                })
                .then(async res => {
                    const text = await res.text();
                    const json = JSON.parse(text);
                    if (json.success) {
                        evaluacionGuardada = true;
                        mostrarAlerta('success', '¡Evaluación del equipo guardada!',
                            `Calificación: ${totalDisplayEquipo.textContent}/10 pts. Redirigiendo...`, true);
                    } else {
                        mostrarAlerta('danger', 'Error al guardar', json.message || 'Error desconocido');
                    }
                })
                .catch(err => {
                    mostrarAlerta('danger', 'Error de conexión', 'No se pudo conectar con el servidor. Inténtalo de nuevo.');
                })
                .finally(() => {
                    btnGuardar.disabled = false;
                    btnGuardar.innerHTML = '<span class="glyphicon glyphicon-floppy-disk"></span> Guardar Evaluación';
                });
        }

        // Función para cancelar evaluación del equipo
        window.cancelarEvaluacionEquipo = function() {
            if (confirm('¿Estás seguro de cancelar? Se perderán todos los cambios no guardados.')) {
                window.location.href = document.referrer || '<?= site_url("/sysmater/docente/docente/ver_alumnos/" . $id_actividad) ?>';
            }
        }

        // Función para cancelar modal individual
        window.cancelarModalIndividual = function() {
            if (confirm('¿Estás seguro de cancelar? Se perderán los cambios realizados en esta evaluación individual.')) {
                $('#modalEvaluacionAlumno').modal('hide');
            }
        }

        // Mostrar modal con datos del alumno y cargar calificación actual
        $('.btn-editar-individual').click(function() {
            const idAlumno = $(this).data('id');
            const nombre = $(this).data('nombre');
            const calificacionActual = parseFloat($(this).data('calificacion')) || 0;

            $('#inputIdAlumno').val(idAlumno);
            $('#nombreAlumno').text(nombre);

            // Limpiar observaciones
            $('#observacion_alumno').val('');

            // Ocultar alerta del modal
            ocultarAlertaModal();

            // Cargar checkboxes según la calificación actual
            $('.criterio-individual').each(function() {
                $(this).prop('checked', false);
            });

            // Distribuir la calificación entre los criterios (lógica simple: marcar criterios hasta llegar a la puntuación)
            let puntosRestantes = calificacionActual;
            $('.criterio-individual').each(function() {
                const valorCriterio = parseInt($(this).data('valor'));
                if (puntosRestantes >= valorCriterio) {
                    $(this).prop('checked', true);
                    puntosRestantes -= valorCriterio;
                }
            });

            // Recalcular total del modal
            calcularTotalModal();
        });

        // Guardar evaluación individual
        $('#formEvaluacionAlumno').submit(function(e) {
            e.preventDefault();

            const btnGuardarModal = document.getElementById('btnGuardarModal');
            const idAlumno = $('#inputIdAlumno').val();
            const idActividad = <?= $actividad->id_actividad ?>;
            const observacion = $('#observacion_alumno').val();
            const idEquipo = <?= $detalles_equipo->id_equipo ?>;

            // Deshabilitar botón y mostrar loading
            btnGuardarModal.disabled = true;
            btnGuardarModal.innerHTML = '<span class="glyphicon glyphicon-refresh glyphicon-spin"></span> Guardando...';

            const criterios = [];
            $('.criterio-individual').each(function() {
                const valor = parseInt($(this).data('valor'));
                const idCriterio = $(this).data('id');
                const calificacion = $(this).is(':checked') ? valor : 0;
                criterios.push({
                    id_criterio: idCriterio,
                    calificacion
                });
            });

            const data = {
                id_alumno: idAlumno,
                id_actividad: idActividad,
                id_equipo: idEquipo,
                observacion: observacion,
                criterios: criterios
            };

            fetch("<?= base_url('sysmater/docente/calificar_actividad/actualizar_evaluacion_individual') ?>", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(json => {
                    if (json.success) {
                        mostrarAlertaModal('success', '¡Evaluación individual guardada!', `Nueva calificación: ${json.calificacion}/10 pts`, true);

                        // Actualizar la calificación en la tabla principal
                        $(`#calificacion-alumno-${idAlumno}`).empty().text(`${json.calificacion}`);


                        // Actualizar el data-calificacion del botón para futuras ediciones
                        $(`.btn-editar-individual[data-id="${idAlumno}"]`).attr('data-calificacion', json.calificacion);

                        setTimeout(() => {
                            $('#modalEvaluacionAlumno').modal('hide');
                        }, 1500);
                    } else {
                        mostrarAlertaModal('danger', 'Error al guardar', json.message || 'No se pudo guardar la evaluación.');
                    }
                })
                .catch(err => {
                    console.error("Error de red o servidor", err);
                    mostrarAlertaModal('danger', 'Error de conexión', 'No se pudo conectar con el servidor.');
                })
                .finally(() => {
                    btnGuardarModal.disabled = false;
                    btnGuardarModal.innerHTML = '<span class="glyphicon glyphicon-floppy-disk"></span> Guardar';
                });
        });

        // Inicializar
        calcularTotalEquipo();
        calcularTotalModal();
        guardarEstadoOriginal();

    });
</script>