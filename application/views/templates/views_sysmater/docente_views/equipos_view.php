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

<link rel="stylesheet" href="<?= base_url() ?>assets/css/sys-styles.css">

<div class="sys-esquema-container">
    <!-- Breadcrumbs -->
    <ol class="breadcrumb sys-esquema-breadcrumb">
        <li><a href="<?= site_url('/sysmater/docente/docente/actividades/' . $id_grupo . '/' . $detalles->vchClvMateria) ?>"><span class="glyphicon glyphicon-list-alt"></span> Actividades</a></li>
        <li><a href="javascript:history.back()"><span class="glyphicon glyphicon-book"></span> Regresar</a></li>
        <li class="active"><span class="glyphicon glyphicon-cog"></span> Gestionar Equipos</li>
    </ol>

    <!-- Header -->
    <div class="text-center" style="margin-bottom: 30px;">
        <h2 style="margin-bottom: 10px;">Equipos del Grupo</h2>
    </div>

    <!-- Botón principal -->
    <div style="margin-bottom: 25px;">
        <button class="btn sys-esquema-btn sys-rounded" onclick="mostrarFormularioCrearEquipo()">
            <span class="glyphicon glyphicon-plus"></span> Crear Nuevo Equipo
        </button>
    </div>

    <!-- Lista de Equipos -->
    <?php if (empty($equipos)): ?>
        <div style="background: white; padding: 40px; border-radius: 8px; text-align: center; border: 2px dashed var(--sys-neutral-border);">
            <span class="glyphicon glyphicon-users" style="font-size: 64px; color: var(--sys-neutral-border); margin-bottom: 20px; display: block;"></span>
            <h4 style="color: #6c757d; margin-bottom: 15px;">No hay equipos registrados</h4>
            <p class="sys-text-muted" style="margin-bottom: 25px;">No hay equipos registrados para este grupo</p>
            <button class="btn sys-esquema-btn sys-rounded" onclick="mostrarFormularioCrearEquipo()">
                <span class="glyphicon glyphicon-plus"></span> Crear Primer Equipo
            </button>
        </div>
    <?php else: ?>
        <div class="panel-group" id="accordionEquipos" role="tablist" aria-multiselectable="true">
            <?php foreach ($equipos as $equipo): ?>
                <div class="panel sys-esquema-panel sys-rounded" style="margin-bottom: 20px;">
                    <div class="panel-heading" role="tab" id="heading<?= $equipo->id_equipo ?>" style="padding: 20px;">
                        <h4 style="margin: 0;">
                            <a role="button" data-toggle="collapse" data-parent="#accordionEquipos"
                                href="#collapseEquipo<?= $equipo->id_equipo ?>" aria-expanded="false"
                                aria-controls="collapseEquipo<?= $equipo->id_equipo ?>"
                                style="text-decoration: none !important; color: var(--sys-primary) !important; font-weight: 600; display: block;">
                                <span class="glyphicon glyphicon-users"></span> Equipo <?= $equipo->nombre_equipo ?>
                                <span class="badge sys-esquema-badge" style="margin-left: 10px;"><?= count($equipo->integrantes) ?> integrantes</span>
                                <div class="pull-right" style="margin-left: 15px;">
                                    <button class="btn btn-info sys-rounded" onclick="abrirModalIntegrantes(<?= $equipo->id_equipo ?>, '<?= $equipo->nombre_equipo ?>')" title="Agregar integrantes">
                                        <span class="glyphicon glyphicon-plus"></span> Agregar
                                    </button>
                                </div>
                                <span class="glyphicon glyphicon-chevron-down pull-right" style="color: var(--sys-primary);"></span>
                            </a>
                        </h4>
                    </div>
                    <div id="collapseEquipo<?= $equipo->id_equipo ?>" class="panel-collapse collapse" role="tabpanel"
                        aria-labelledby="heading<?= $equipo->id_equipo ?>">
                        <div class="panel-body" style="padding: 25px;">
                            <div class="table-responsive sys-rounded" style="box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                <table class="table table-striped table-hover table-bordered sys-esquema-table">
                                    <thead style="background: var(--sys-primary) !important; color: white !important;">
                                        <tr>
                                            <th>Matrícula</th>
                                            <th>Nombre Completo</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($equipo->integrantes)): ?>
                                            <?php foreach ($equipo->integrantes as $alumno): ?>
                                                <tr>
                                                    <td><strong><?= $alumno->vchMatricula ?></strong></td>
                                                    <td><?= $alumno->vchNombre ?> <?= $alumno->vchAPaterno ?> <?= $alumno->vchAMaterno ?></td>
                                                    <td class="text-center">
                                                        <button class="btn sys-btn-danger" onclick="eliminarIntegrante('<?= $alumno->vchMatricula ?>', <?= $equipo->id_equipo ?>)" title="Eliminar del equipo">
                                                            <span class="glyphicon glyphicon-trash"></span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center sys-text-muted">
                                                    <span class="glyphicon glyphicon-info-sign" style="font-size: 24px; display: block; margin-bottom: 10px;"></span>
                                                    Este equipo no tiene integrantes asignados
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Ícono para cerrar -->
                            <div class="text-center" style="margin-top: 20px;">
                                <span class="glyphicon glyphicon-chevron-up sys-esquema-close"
                                    data-target="#collapseEquipo<?= $equipo->id_equipo ?>"
                                    style="font-size: 18px; color: var(--sys-primary); cursor: pointer; padding: 8px; border-radius: 50%; background: #f8f9fa;"
                                    title="Cerrar equipo"></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Footer informativo -->
        <div class="text-center sys-text-muted" style="margin-top: 20px; padding: 15px; border-top: 1px solid var(--sys-neutral-border); background: white;" class="sys-rounded">
            <span class="glyphicon glyphicon-info-sign"></span> Total de equipos: <strong style="color: var(--sys-primary);"><?= count($equipos) ?></strong>
        </div>
    <?php endif; ?>
</div>

<!-- Modal para agregar integrantes -->
<div class="modal fade" id="modalAgregarIntegrantes" tabindex="-1" role="dialog" aria-labelledby="tituloModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="formAgregarIntegrantes" onsubmit="agregarNuevoIntegrante(event)">
            <div class="modal-content">
                <div class="modal-header sys-esquema-modal-header">
                    <button type="button" class="close sys-esquema-modal-header .close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="tituloModal">
                        <span class="glyphicon glyphicon-plus"></span> Agregar integrantes al equipo
                    </h4>
                </div>

                <div class="modal-body" style="padding: 25px;">
                    <input type="hidden" id="modal_id_equipo" name="id_equipo">
                    <p style="margin-bottom: 20px;"><strong>Equipo:</strong> <span id="modal_nombre_equipo" style="color: var(--sys-primary);"></span></p>

                    <?php if (!empty($alumnos_sin_equipo)): ?>
                        <div class="row">
                            <?php foreach ($alumnos_sin_equipo as $alumno): ?>
                                <div class="col-md-4" style="margin-bottom: 10px;">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="matriculas" value="<?= $alumno->vchMatricula ?>" id="alumno_<?= $alumno->vchMatricula ?>">
                                            <?= $alumno->vchMatricula ?> - <?= $alumno->nombre_completo ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div style="text-align: center; padding: 20px; background: #d1ecf1; border-radius: 8px; color: #0c5460;">
                            <span class="glyphicon glyphicon-info-sign"></span> No hay alumnos sin equipo disponibles.
                        </div>
                    <?php endif; ?>
                </div>

                <div class="modal-footer" style="background: var(--sys-neutral);">
                    <button type="button" class="btn btn-danger sys-rounded" data-dismiss="modal">
                        <span class="glyphicon glyphicon-remove"></span> Cancelar
                    </button>
                    <button type="submit" class="btn sys-esquema-btn sys-rounded">
                        <span class="glyphicon glyphicon-floppy-disk"></span> Guardar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal para crear nuevo equipo -->
<div class="modal fade" id="modalCrearEquipo" tabindex="-1" role="dialog" aria-labelledby="tituloCrearEquipo" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="formCrearEquipo" onsubmit="guardarNuevoEquipo(event)">
            <div class="modal-content">
                <div class="modal-header sys-esquema-modal-header">
                    <button type="button" class="close sys-esquema-modal-header .close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="tituloCrearEquipo">
                        <span class="glyphicon glyphicon-plus"></span> Crear nuevo equipo
                    </h4>
                </div>

                <div class="modal-body" style="padding: 25px;">
                    <div class="form-group">
                        <label for="nuevo_nombre_equipo"><strong>Nombre del equipo:</strong></label>
                        <input type="text" class="form-control sys-esquema-form-control" id="nuevo_nombre_equipo" name="nombre_equipo" required>
                    </div>

                    <?php if (!empty($alumnos_sin_equipo)): ?>
                        <hr>
                        <p><strong>Selecciona integrantes disponibles:</strong></p>
                        <div class="row">
                            <?php foreach ($alumnos_sin_equipo as $alumno): ?>
                                <div class="col-md-4" style="margin-bottom: 10px;">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="alumnos[]" value="<?= $alumno->vchMatricula ?>" id="nuevo_alumno_<?= $alumno->vchMatricula ?>">
                                            <?= $alumno->vchMatricula ?> - <?= $alumno->nombre_completo ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div style="text-align: center; padding: 20px; background: #d1ecf1; border-radius: 8px; color: #0c5460;">
                            <span class="glyphicon glyphicon-info-sign"></span> No hay alumnos sin equipo disponibles.
                        </div>
                    <?php endif; ?>
                </div>

                <div class="modal-footer" style="background: var(--sys-neutral);">
                    <button type="button" class="btn btn-danger sys-rounded" data-dismiss="modal">
                        <span class="glyphicon glyphicon-remove"></span> Cancelar
                    </button>
                    <button type="submit" class="btn sys-esquema-btn sys-rounded">
                        <span class="glyphicon glyphicon-plus"></span> Crear equipo
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Confirmar Eliminación -->
<div class="modal fade" id="modalConfirmarEliminar" tabindex="-1" role="dialog" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #d9534f; color: white;">
                <h4 class="modal-title" id="modalEliminarLabel">Confirmar eliminación</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 25px;">
                <div id="mensajeErrorEliminar" class="alert alert-danger" style="display: none;"></div>
                ¿Estás seguro de que deseas eliminar al alumno <strong id="matriculaEliminar"></strong> de este equipo?
            </div>
            <div class="modal-footer" style="background: var(--sys-neutral);">
                <button type="button" class="btn btn-danger sys-rounded" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn sys-btn-danger sys-rounded" id="btnEliminarConfirmado">Eliminar</button>
            </div>
        </div>
    </div>
</div>


<div id="msgFlash"></div>

<script>
   
    
    $(function() {
        // Funcionalidad del accordion con scroll automático
        $('#accordionEquipos').on('show.bs.collapse', function(e) {
            var $target = $(e.target);
            var equipoId = $target.attr('id');

            setTimeout(function() {
                var headerElement = $('#heading' + equipoId.replace('collapseEquipo', ''));
                if (headerElement.length) {
                    $('html, body').animate({
                        scrollTop: headerElement.offset().top - 20
                    }, 500);
                }
            }, 100);
        });

        // Cerrar desde ícono inferior
        $('.sys-esquema-close').on('click', function(e) {
            e.preventDefault();
            var targetId = $(this).data('target');
            var $target = $(targetId);

            if ($target.hasClass('in')) {
                $target.collapse('hide');

                setTimeout(function() {
                    var headerId = targetId.replace('#collapseEquipo', '#heading');
                    var headerElement = $(headerId);
                    if (headerElement.length) {
                        $('html, body').animate({
                            scrollTop: headerElement.offset().top - 20
                        }, 500);
                    }
                }, 100);
            }
        });
    });

    // TODAS LAS FUNCIONES ORIGINALES MANTENIDAS INTACTAS
    function mostrarFormularioCrearEquipo() {
        document.getElementById('formCrearEquipo').reset();
        $('#modalCrearEquipo').modal('show');
    }

    function abrirModalIntegrantes(id_equipo, nombre_equipo) {
        document.getElementById('modal_id_equipo').value = id_equipo;
        document.getElementById('modal_nombre_equipo').textContent = nombre_equipo;
        document.querySelectorAll('#modalAgregarIntegrantes input[name="matriculas[]"]').forEach(cb => cb.checked = false);
        $('#modalAgregarIntegrantes').modal('show');
    }

    function agregarNuevoIntegrante(event) {
        event.preventDefault();
        const form = document.getElementById('formAgregarIntegrantes');
        const formData = new FormData(form);

        const seleccionados = [...formData.getAll('matriculas')];

        if (seleccionados.length === 0) {
            alert('Selecciona al menos un alumno para agregar.');
            return;
        }

        fetch("<?= site_url('sysmater/docente/equipos/actualizar_equipo') ?>", {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {


                    $('#modalAgregarIntegrantes').modal('hide');

                    mostrarMensaje(`${data.message || 'Alumno(s) agregado(s) correctamente.'}`, 'success')

                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);
                } else {
                    mostrarMensaje(`${data.error || 'No se pudo agregar integrantes.'}`, 'error')
                }
            })
    }

    function guardarNuevoEquipo(event) {
        event.preventDefault();
        const form = document.getElementById('formCrearEquipo');
        const id_grupo = <?= json_encode($id_grupo) ?>;
        const vchClvMateria = <?= json_encode($vchClvMateria) ?>;

        const formData = new FormData(form);
        formData.append('id_grupo', id_grupo);
        formData.append('vchClvMateria', vchClvMateria);

        fetch("<?= site_url('sysmater/docente/equipos/crear_equipo') ?>", {
                method: 'POST',
                body: new URLSearchParams(formData)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {

                    $('#modalCrearEquipo').modal('hide');


                    mostrarMensaje(`${data.message || 'Equipo creado correctamente.'}`, 'success')

                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);
                } else {
                    mostrarMensaje(`${data.error || 'No se pudo crear el equipo.'}`, 'error')
                }
            })
            .catch(() => alert('Error de conexión.'));
    }

    // Variables y funciones para eliminación - ORIGINAL
    let matriculaAEliminar = null;
    let equipoAEliminar = null;

    function eliminarIntegrante(matricula, id_equipo) {
        matriculaAEliminar = matricula;
        equipoAEliminar = id_equipo;
        document.getElementById('matriculaEliminar').textContent = matricula;
        $('#modalConfirmarEliminar').modal('show');
    }

    document.getElementById('btnEliminarConfirmado').addEventListener('click', function() {
        const formData = new FormData();
        formData.append('matricula', matriculaAEliminar);
        formData.append('id_equipo', equipoAEliminar);

        fetch("<?= site_url('sysmater/docente/equipos/eliminar_integrante') ?>", {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'ok') {

                    $('#modalConfirmarEliminar').modal('hide');

                    mostrarMensaje('Alumno eliminado correctamente', 'success')

                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);
                } else {

                    console.log(data)
                    const mensaje = document.getElementById('mensajeErrorEliminar');
                    mensaje.textContent = data.error || 'No se pudo eliminar';

                    mensaje.style.display = 'block';

                    // Ocultar automáticamente después de 4 segundos
                    setTimeout(() => {
                        mensaje.style.display = 'none';
                    }, 4000);
                }
            })
            .catch(() => mostrarMensaje('Errror de conexion', 'error'));
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