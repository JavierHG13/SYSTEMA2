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
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


<div class="sys-esquema-container">
    <!-- Breadcrumbs -->
    <ol class="breadcrumb sys-esquema-breadcrumb sys-rounded">
        <li><a href="<?= site_url('/sysmater/docente/docente/ver_materias') ?>"><span class="glyphicon glyphicon-book"></span> Regresar</a></li>
        <li class="active"><span class="glyphicon glyphicon-list-alt"></span> Actividades</li>
    </ol>

    <!-- Header -->
    <div class="text-center" style="margin-bottom: 30px;">
        <h2 style="margin-bottom: 10px;">Actividades</h2>
        <p>Gestiona las actividades asignadas para este grupo</p>
    </div>


    <?php
    // Verificar actividades
    $tiene_actividades = false;
    foreach ($parciales as $actividades) {
        $individual = array_filter($actividades, fn($a) => $a->id_modalidad == 1);
        $equipo = array_filter($actividades, fn($a) => $a->id_modalidad == 2);
        if (!empty($individual) || !empty($equipo)) {
            $tiene_actividades = true;
            break;
        }
    }
    ?>

    <?php if (!$tiene_actividades): ?>
        <div class="alert alert-warning text-center sys-rounded" style="background: var(--sys-success); border: none; color: #856404;">
            <span class="glyphicon glyphicon-exclamation-sign" style="font-size: 48px; display: block; margin-bottom: 15px;"></span>
            <h4>No hay actividades disponibles</h4>
            <p>No hay actividades asignadas para este equipo.</p>
        </div>
    <?php else: ?>
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <?php foreach ($parciales as $parcial => $actividades) : ?>

                <?php
                // Ordenar por fecha de entrega ascendente
                usort($actividades, function ($a, $b) {
                    return strtotime($a->fecha_entrega) - strtotime($b->fecha_entrega);
                });

                $individual = array_filter($actividades, fn($a) => $a->id_modalidad == 1);
                $equipo = array_filter($actividades, fn($a) => $a->id_modalidad == 2);
                if (empty($individual) && empty($equipo)) continue;
                ?>


                <div class="panel sys-esquema-panel sys-rounded" style="margin-bottom: 20px;">
                    <div class="panel-heading" role="tab" id="headingParcial<?= $parcial; ?>" style="padding: 20px;">
                        <h4 style="margin: 0;">
                            <a role="button" data-toggle="collapse" data-parent="#accordion"
                                href="#collapseParcial<?= $parcial; ?>" aria-expanded="false"
                                aria-controls="collapseParcial<?= $parcial; ?>"
                                style="text-decoration: none !important; color: var(--sys-primary) !important; font-weight: 600; display: block;">
                                <span class="glyphicon glyphicon-education"></span> <?= $parcial; ?>° Parcial
                                <span class="badge sys-esquema-badge" style="margin-left: 10px;">
                                    <?= count($individual) + count($equipo) ?> actividades
                                </span>
                                <span class="glyphicon glyphicon-chevron-down pull-right" style="color: var(--sys-primary);"></span>
                            </a>
                        </h4>
                    </div>
                    <div id="collapseParcial<?= $parcial; ?>" class="panel-collapse collapse" role="tabpanel"
                        aria-labelledby="headingParcial<?= $parcial; ?>">
                        <div class="panel-body" style="padding: 25px;">
                            <!-- Actividades Individuales -->
                            <?php if (!empty($individual)): ?>
                                <div style="margin-bottom: 25px;">
                                    <h5 style="color: var(--sys-primary); margin-bottom: 15px;">
                                        <span class="glyphicon glyphicon-user"></span> <strong>Modalidad: Individual</strong>
                                    </h5>
                                    <div class="table-responsive sys-rounded" style="box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                        <table class="table table-striped table-hover table-bordered sys-esquema-table">
                                            <thead style="background: var(--sys-primary) !important; color: white !important;">
                                                <tr>
                                                    <th style="width: 300px;">Actividad</th>
                                                    <th>Fecha de entrega</th>
                                                    <th>Hora de entrega</th>
                                                    <th>Calificados</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($individual as $actividad): ?>
                                                    <tr>
                                                        <td><?= $actividad->titulo; ?></td>
                                                        <td><?= date('Y/m/d', strtotime($actividad->fecha_entrega)); ?></td>
                                                        <td><?= date('H:i', strtotime($actividad->fecha_entrega)); ?> horas</td>
                                                        <td><?= $actividad->calificados_individual; ?> / <?= $actividad->numero_alumnos; ?></td>

                                                        <td>
                                                            <a href="<?= site_url('/sysmater/docente/docente/ver_alumnos/' . $actividad->id_actividad . '/' . $id_grupo) ?>"
                                                                class="btn sys-esquema-btn sys-rounded">
                                                                <span class="glyphicon glyphicon-eye-open"></span> Revisar
                                                            </a>
                                                            <button class="btn sys-btn-outline-primary sys-rounded"
                                                                data-toggle="modal"
                                                                data-target="#modalGestionMateria"
                                                                data-materia-id="<?= $actividad->id_actividad ?>"
                                                                data-materia-nombre="<?= $id_grupo ?>"
                                                                data-fecha-entrega="<?= date('Y-m-d', strtotime($actividad->fecha_entrega)) ?>"
                                                                data-hora-entrega="<?= date('H:i', strtotime($actividad->fecha_entrega)) ?>"
                                                                title="Editar actividad">
                                                                <span class="glyphicon glyphicon-edit"></span> Editar
                                                            </button>


                                                            <button class="btn btn-danger btn-sm btn-eliminar-actividad"
                                                                data-id="<?= $actividad->id_actividad ?>"
                                                                data-titulo="<?= htmlspecialchars($actividad->titulo) ?>">
                                                                <span class="glyphicon glyphicon-trash"></span> Eliminar
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Actividades por Equipo -->
                            <?php if (!empty($equipo)): ?>
                                <div style="margin-bottom: 15px;">
                                    <h5 style="color: var(--sys-primary); margin-bottom: 15px;">
                                        <span class="glyphicon glyphicon-users"></span> <strong>Modalidad: Por equipo</strong>
                                    </h5>
                                    <div class="table-responsive sys-rounded" style="box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                        <table class="table table-striped table-hover table-bordered sys-esquema-table">
                                            <thead style="background: var(--sys-primary) !important; color: white !important;">
                                                <tr>
                                                    <th style="width: 300px;">Actividad</th>
                                                    <th>Fecha de entrega</th>
                                                    <th>Hora de entrega</th>
                                                    <th>Calificados</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($equipo as $actividad): ?>
                                                    <tr>
                                                        <td><?= $actividad->titulo; ?></td>
                                                        <td><?= date('Y/m/d', strtotime($actividad->fecha_entrega)); ?></td>
                                                        <td><?= date('H:i', strtotime($actividad->fecha_entrega)); ?></td>
                                                        <td><?= $actividad->equipos_calificados; ?> / <?= $actividad->numero_equipos; ?></td>

                                                        <td>

                                                            <a href="<?= site_url('/sysmater/docente/docente/ver_equipo/' . $id_grupo . '/' . $actividad->id_actividad) ?>"
                                                                class="btn sys-esquema-btn sys-rounded">
                                                                <span class="glyphicon glyphicon-eye-open"></span> Revisar
                                                            </a>
                                                            <button class="btn sys-btn-outline-primary sys-rounded"
                                                                data-toggle="modal"
                                                                data-target="#modalGestionMateria"
                                                                data-materia-id="<?= $actividad->id_actividad ?>"
                                                                data-materia-nombre="<?= $id_grupo ?>"
                                                                data-fecha-entrega="<?= date('Y-m-d', strtotime($actividad->fecha_entrega)) ?>"
                                                                data-hora-entrega="<?= date('H:i', strtotime($actividad->fecha_entrega)) ?>"
                                                                title="Editar actividad">
                                                                <span class="glyphicon glyphicon-edit"></span> Editar
                                                            </button>
                                                            <button class="btn btn-danger btn-sm btn-eliminar-actividad"
                                                                data-id="<?= $actividad->id_actividad ?>"
                                                                data-titulo="<?= htmlspecialchars($actividad->titulo) ?>">
                                                                <span class="glyphicon glyphicon-trash"></span> Eliminar
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Cerrar -->
                            <div class="text-center" style="margin-top: 15px;">
                                <span class="glyphicon glyphicon-chevron-up sys-esquema-close" data-target="#collapseParcial<?= $parcial; ?>"></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>


    <!-- Modal para editar actividad -->
    <div class="modal fade" id="modalGestionMateria" tabindex="-1" role="dialog" aria-labelledby="modalGestionMateriaLabel">
        <div class="modal-dialog" role="document">

            <form method="post" id="formEditarActividad">

                <div class="modal-content">
                    <div class="modal-header" style="background-color: var(--primary-color); color: white;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">
                            <span class="glyphicon glyphicon-edit"></span> Editar Actividad
                        </h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_actividad" id="inputIdActividad">

                        <div class="form-group">
                            <label for="inputFechaEntrega">Fecha de entrega</label>
                            <input type="date" class="form-control" name="fecha_entrega" id="inputFechaEntrega" required>
                        </div>

                        <div class="form-group">
                            <label for="inputHoraEntrega">Hora de entrega</label>
                            <input type="time" class="form-control" name="hora_entrega" id="inputHoraEntrega" required>
                        </div>
                    </div>

                    <input type="hidden" class="form-control" name="id_grupo" id="id_grupo" value="<?= $id_grupo ?>" required>


                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            <span class="glyphicon glyphicon-floppy-disk"></span> Guardar cambios
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Cancelar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Modal de Confirmación de Eliminación -->
    <div class="modal fade" id="modalEliminarActividad" tabindex="-1" role="dialog" aria-labelledby="modalEliminarLabel">
        <div class="modal-dialog" role="document">

            <form method="post" id="formEliminarActividad">

                <div class="modal-content">
                    <div class="modal-header" style="background-color: #d9534f; color: white;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="modalEliminarLabel">
                            <span class="glyphicon glyphicon-warning-sign"></span> Confirmar Eliminación
                        </h4>
                    </div>
                    <div class="modal-body">
                        <!-- Mensaje personalizado -->
                        <div id="mensajeEliminacion" class="alert alert-info text-center" style="display: none; margin-top: 20px;"></div>

                        <p>¿Estás seguro de que deseas eliminar la actividad <strong id="tituloActividadEliminar"></strong>?</p>
                        <input type="hidden" id="idActividadEliminar">
                    </div>


                    <input type="hidden" class="form-control" name="id_grupo" id="id_grupo" value="<?= $id_grupo ?>" required>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

</div>

<!-- Contenedor de mensajes -->
<div id="msgFlash"></div>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Idioma español -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

<script>
    flatpickr("#inputFechaEntrega", {
        dateFormat: "Y-m-d",
        minDate: "today", // desde hoy
        maxDate: new Date().fp_incr(30), // hasta 30 días después
        disableMobile: true,
        locale: "es" // <-- Aquí defines el idioma
    });


    $(function() {
        console.log('✅ Sistema de gestión de actividades inicializado');

        // Funcionalidad del accordion con scroll automático
        $('#accordion').on('show.bs.collapse', function(e) {
            var $target = $(e.target);
            var parcialId = $target.attr('id');
            console.log('🔄 Abriendo parcial:', parcialId);

            setTimeout(function() {
                var headerElement = $('#heading' + parcialId.replace('collapse', ''));
                if (headerElement.length) {
                    $('html, body').animate({
                        scrollTop: headerElement.offset().top - 20
                    }, 500);
                }
            }, 100);
        });

        // Cerrar accordion desde ícono inferior
        $('.sys-esquema-close').on('click', function(e) {
            e.preventDefault();
            var targetId = $(this).data('target');
            var $target = $(targetId);

            if ($target.hasClass('in')) {
                $target.collapse('hide');
                console.log('📤 Cerrando parcial desde ícono inferior:', targetId);

                setTimeout(function() {
                    var headerId = targetId.replace('#collapse', '#heading');
                    var headerElement = $(headerId);
                    if (headerElement.length) {
                        $('html, body').animate({
                            scrollTop: headerElement.offset().top - 20
                        }, 500);
                    }
                }, 100);
            }
        });

        // Actualizar iconos de flechas
        $('#accordion').on('shown.bs.collapse', function(e) {
            var $toggle = $(e.target).prev().find('a[data-toggle="collapse"]');
            $toggle.attr('aria-expanded', 'true');
        });

        $('#accordion').on('hidden.bs.collapse', function(e) {
            var $toggle = $(e.target).prev().find('a[data-toggle="collapse"]');
            $toggle.attr('aria-expanded', 'false');
        });

        // Funcionalidad del modal
        $('#modalGestionMateria').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var actividadId = button.data('materia-id');
            var grupoNombre = button.data('materia-nombre');
            var fechaEntrega = button.data('fecha-entrega');
            var horaEntrega = button.data('hora-entrega');

            $('#inputIdActividad').val(actividadId);
            $('#inputFechaEntrega').val(fechaEntrega);
            $('#inputHoraEntrega').val(horaEntrega);
        });
    });


    $(document).ready(function() {
        // Abrir el modal con datos
        $('.btn-eliminar-actividad').on('click', function() {
            const id_actividad = $(this).data('id');
            const titulo = $(this).data('titulo');

            $('#idActividadEliminar').val(id_actividad);
            $('#tituloActividadEliminar').text(titulo);
            $('#modalEliminarActividad').modal('show');
        });
    });



    $(document).ready(function() {
        $('#formEliminarActividad').on('submit', function(e) {
            e.preventDefault();

            const idActividad = $('#idActividadEliminar').val();
            const idGrupo = $('#id_grupo').val(); // viene del input oculto

            $.ajax({
                url: '<?= base_url("sysmater/docente/actividades/eliminar_actividad") ?>',
                type: 'POST',
                data: {
                    id_actividad: idActividad,
                    id_grupo: idGrupo
                },
                dataType: 'json',
                success: function(response) {
                    const mensajeDiv = $('#mensajeEliminacion');

                    if (response.status === 'ok') {
                        mensajeDiv
                            .removeClass('alert-danger')
                            .addClass('alert-success')
                            .text('Actividad eliminada correctamente.')
                            .fadeIn();

                        setTimeout(function() {
                            mensajeDiv.fadeOut();
                            $('#modalEliminarActividad').modal('hide');
                            location.reload(); // recarga para reflejar los cambios
                        }, 2500);
                    } else {
                        mensajeDiv
                            .removeClass('alert-success')
                            .addClass('alert-danger')
                            .text(response.error || 'Error al eliminar la actividad.')
                            .fadeIn();

                        setTimeout(function() {
                            mensajeDiv.fadeOut();
                        }, 4000);
                    }
                },
                error: function() {
                    $('#mensajeEliminacion')
                        .removeClass('alert-success')
                        .addClass('alert-danger')
                        .text('Ocurrió un error inesperado.')
                        .fadeIn();

                    setTimeout(function() {
                        $('#mensajeEliminacion').fadeOut();
                    }, 4000);
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


    $('#formEditarActividad').on('submit', function(e) {
        e.preventDefault(); // Prevenir envío normal del formulario

        console.log('📝 Enviando formulario de edición...');

        // Obtener datos del formulario
        const formData = {
            id_actividad: $('#inputIdActividad').val(),
            id_grupo: $('#id_grupo').val(),
            fecha_entrega: $('#inputFechaEntrega').val(),
            hora_entrega: $('#inputHoraEntrega').val()
        };

        console.log('Datos a enviar:', formData);

        // Validar que todos los campos estén llenos
        if (!formData.id_actividad || !formData.fecha_entrega || !formData.hora_entrega) {
            mostrarMensaje('Por favor completa todos los campos', 'error');
            return;
        }

        // Deshabilitar botón de envío
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Guardando...');

        // Envío AJAX
        $.ajax({
            url: '<?= base_url("sysmater/docente/actividades/actualizar_fecha_actividad") ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log('✅ Respuesta del servidor:', response);

                if (response.success) {

                    $('#modalGestionMateria').modal('hide');
                    mostrarMensaje(response.mensaje || 'Actividad actualizada correctamente', 'success');

                    // Cerrar modal después de un breve delay
                    setTimeout(function() {
                        location.reload(); // Recargar para mostrar cambios
                    }, 1500);
                } else {
                    mostrarMensaje(response.mensaje || 'Error al actualizar la actividad', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ Error AJAX:', error);
                console.error('Respuesta completa:', xhr.responseText);
                mostrarMensaje('Error de conexión al actualizar la actividad', 'error');
            },
            complete: function() {
                // Rehabilitar botón
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
</script>