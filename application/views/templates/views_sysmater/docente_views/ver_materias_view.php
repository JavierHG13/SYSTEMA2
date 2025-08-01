<link rel="stylesheet" href="<?= base_url() ?>assets/css/sys-styles.css">

<div class="container-fluid">
    <!-- Header -->
    <div class="text-center" style="margin-bottom: 30px;">
        <h2>Mis Materias</h2>
        <p>Gestiona las materias asignadas y sus grupos de estudiantes</p>
    </div>
    <!-- Panel de Usuario -->
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">Información del Docente</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <h4><span class="glyphicon glyphicon-user"></span> Docente: <?= htmlspecialchars($this->session->Usuario) ?></h4>
                    <p><b>Clave de Trabajador:</b> <?= htmlspecialchars($this->session->Matricula) ?></p>
                </div>
            </div>
        </div>
    </div>
    <!-- Panel de Materias -->
    <div class="panel panel-success sys-panel-success sys-panel-rounded">
        <div class="panel-heading">
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-book"></span> Lista de Materias Asignadas
            </h3>
        </div>

        <div class="panel-body" style="padding: 0;">
            <div class="table-responsive sys-rounded">
                <table class="table table-striped table-hover" style="margin-bottom: 0;">
                    <thead class="bg-success">
                        <tr>
                            <th>Clave</th>
                            <th>Materia</th>
                            <th>Cuatrimestre</th>
                            <th class="text-center">Grupos</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($materias) : ?>
                            <?php foreach ($materias->result() as $materia) : ?>
                                <?php if ($materia->totalGrupos > 0) : ?>
                                    <tr>
                                        <td><strong><?= $materia->vchClvMateria; ?></strong></td>
                                        <td><?= $materia->vchNomMateria; ?></td>
                                        <td><?= $materia->vchCuatrimestre; ?></td>
                                        <td class="text-center">
                                            <span class="badge sys-badge"><?= $materia->totalGrupos; ?></span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-outline-primary sys-btn-outline-primary"
                                                data-toggle="modal"
                                                data-target="#modalGestionMateria"
                                                data-materia-id="<?= $materia->vchClvMateria ?>"
                                                data-materia-nombre="<?= $materia->vchNomMateria ?>"
                                                title="Gestionar materia">
                                                <span class="glyphicon glyphicon-cog"></span> Gestionar
                                            </button>
                              
                                            <a href="<?= site_url('sysmater/docente/docente/gestionar_esquema/' . $materia->vchClvMateria) ?>"
                                                class="btn btn-info">
                                                <span class="glyphicon glyphicon-cog"></span> Esquema
                                            </a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>

                            <?php
                            // Verificar si todas las materias no tienen grupos
                            $hasMateriasWithGroups = false;
                            foreach ($materias->result() as $m) {
                                if ($m->totalGrupos > 0) {
                                    $hasMateriasWithGroups = true;
                                    break;
                                }
                            }
                            ?>

                            <?php if (!$hasMateriasWithGroups) : ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted" style="padding: 40px;">
                                        <span class="glyphicon glyphicon-info-sign" style="font-size: 24px; margin-bottom: 10px; display: block;"></span>
                                        No tienes materias con grupos asignados.
                                    </td>
                                </tr>
                            <?php endif; ?>

                        <?php else : ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted" style="padding: 40px;">
                                    <span class="glyphicon glyphicon-info-sign" style="font-size: 24px; margin-bottom: 10px; display: block;"></span>
                                    No se encontraron materias asignadas.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if ($materias && $hasMateriasWithGroups) : ?>
            <div class="panel-footer bg-light">
                <span class="glyphicon glyphicon-info-sign"></span>
                Mostrando <?= $materias->num_rows() ?> materias asignadas
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal para Gestión de Materia -->
<div class="modal fade" id="modalGestionMateria" tabindex="-1" role="dialog" aria-labelledby="modalGestionMateriaLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content sys-rounded">
            <div class="modal-header sys-modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="tituloMateria">
                    <span class="glyphicon glyphicon-cog"></span> Gestionar Materia
                </h4>
            </div>
            <div class="modal-body" id="grupos-container">
                <div class="text-center" style="padding: 40px 20px;">
                    <span class="glyphicon glyphicon-refresh glyphicon-spin sys-glyphicon-spin" style="font-size: 24px;"></span>
                    <p class="text-muted" style="margin-top: 15px;">Cargando grupos...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('#modalGestionMateria').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var materiaId = button.data('materia-id');
            var materiaNombre = button.data('materia-nombre');


            // Cambiar título del modal
            $('#tituloMateria').html(`
            <span class="glyphicon glyphicon-cog"></span> Gestionar: ${materiaNombre}
        `);

            // Mostrar loading
            $('#grupos-container').html(`
            <div class="text-center" style="padding: 40px 20px;">
                <span class="glyphicon glyphicon-refresh glyphicon-spin sys-glyphicon-spin" style="font-size: 24px;"></span>
                <p class="text-muted" style="margin-top: 15px;">Cargando grupos de ${materiaNombre}...</p>
            </div>
        `);

            // Timeout de seguridad
            var ajaxTimeout = setTimeout(function() {
                $('#grupos-container').html(`
                <div class="alert alert-danger text-center">
                    <span class="glyphicon glyphicon-exclamation-sign"></span>
                    La solicitud está tardando demasiado. Por favor, intente nuevamente.
                </div>
            `);
            }, 10000);

            // Llamada AJAX para obtener grupos
            $.ajax({
                url: '<?= base_url() ?>sysmater/docente/ver_materias/get_grupos_materia/' + materiaId,
                method: 'GET',
                dataType: 'json',
                timeout: 8000,
                success: function(response) {
                    clearTimeout(ajaxTimeout);
                    console.log('📡 Respuesta del servidor:', response);

                    if (response.success) {
                        var htmlContent = '';

                        if (response.grupos && response.grupos.length > 0) {
                            htmlContent = `
                            <div class="alert alert-success">
                                <span class="glyphicon glyphicon-ok-circle"></span> 
                                Se encontraron <strong>${response.grupos.length} grupos</strong> para esta materia
                            </div>
                            <div class="table-responsive sys-rounded">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead class="bg-success sys-bg-success">
                                        <tr>
                                            <th class="text-center">ID Grupo</th>
                                            <th class="text-center">Grupo</th>
                                            <th class="text-center">Alumnos</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        `;

                            response.grupos.forEach(function(grupo) {
                                htmlContent += `
                                <tr>
                                    <td class="text-center"><strong>${grupo.idGrupo}</strong></td>
                                    <td class="text-center">${grupo.vchGrupo}</td>
                                    <td class="text-center">${grupo.estudiantes}</td>
                                    <td class="text-center">
        
                                            <a href="<?= site_url('sysmater/docente/docente/ver_actividades/') ?>${grupo.idGrupo}/${materiaId}" 
                                               class="btn btn-success sys-btn-success">
                                                <span class="glyphicon glyphicon-list-alt"></span> Actividades
                                            </a>
                                            <a href="<?= site_url('sysmater/docente/docente/gestionar_equipos/') ?>${grupo.idGrupo}/${materiaId}" 
                                               class="btn btn-primary">
                                                <span class="glyphicon glyphicon-user"></span> Equipos
                                            </a>
                                    </td>
                                </tr>
                            `;
                            });

                            htmlContent += `
                                    </tbody>
                                </table>
                            </div>
                        `;
                        } else {
                            htmlContent = `
                            <div class="alert alert-warning text-center">
                                <span class="glyphicon glyphicon-exclamation-sign" style="font-size: 48px; display: block; margin-bottom: 15px;"></span>
                                <h4>No hay grupos asignados</h4>
                                <p>Esta materia no tiene grupos asignados actualmente.</p>
                            </div>
                        `;
                        }

                        $('#grupos-container').html(htmlContent);
                    } else {
                        $('#grupos-container').html(`
                        <div class="alert alert-danger text-center">
                            <span class="glyphicon glyphicon-remove-circle"></span>
                            ${response.error || 'Error al cargar los grupos'}
                        </div>
                    `);
                    }
                },
                error: function(xhr, status, error) {
                    clearTimeout(ajaxTimeout);

                    var errorMsg = 'Error en la conexión';
                    if (status === 'timeout') {
                        errorMsg = 'La solicitud tardó demasiado';
                    } else if (error) {
                        errorMsg = error;
                    }

                    $('#grupos-container').html(`
                    <div class="alert alert-danger text-center">
                        <span class="glyphicon glyphicon-exclamation-sign"></span>
                        ${errorMsg}
                    </div>
                `);

                    console.error('❌ Error en AJAX:', {
                        xhr,
                        status,
                        error
                    });
                }
            });
        });

        // Log cuando se cierra el modal
        $('#modalGestionMateria').on('hidden.bs.modal', function() {});
    });
</script>