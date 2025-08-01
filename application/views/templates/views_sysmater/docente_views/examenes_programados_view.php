<link rel="stylesheet" href="<?= base_url() ?>assets/css/tablas.css">

    <div class="cd">

        <center>
            <h3>Exámenes programados para aplicación</h3>
        </center>
        
        <p><a href="<?= site_url('/sysmater/docente/docente/examenes_pendientes/') ?>" class='btn btn-primary'>
            <span class="glyphicon glyphicon-calendar"></span> Programar Exámen</a></p>
<div class="table-container" style="margin-bottom: 20px;">

    <table id="tablaExamenes">
        <thead>
            <tr>
                <th>ID</th>
                <th>MATERIA</th>
                <th>TÍTULO DE EXÁMEN</th>
                <th>REACTIVOS</th>
                <th>PROGRAMACIÓN</th>
                <!-- <th>ACCIONES</th> -->
            </tr>
        </thead>
        <tbody>
            <?php if ($examenes) : ?>
    
                <?php
                foreach ($examenes->result() as $examen) {
                ?>
                    <tr>
                        <td><?= $examen->id_examen; ?></td>
                        <td><?= $examen->nombre_materia; ?></td>
                        <td><?= $examen->nvch_Titulo; ?></td>
                        <td><?= $examen->nReactivos; ?></td>
                        <td>
                            <div class="btn-group btn-group-xs" role="group" aria-label="...">
                                <button class="btns btn btn-danger" data-toggle="modal" data-target="#modalAgendaGrupos" 
                                        data-examen-id="<?= $examen->id_examen ?>">
                                    <span class="glyphicon glyphicon-calendar"></span> Ver programación
                                </button>
                            </div>
                        </td>
                        <!-- <td>
                            <div class="btn-group btn-group-xs" role="group" aria-label="...">  
                                <button class="btns btn btn-danger" data-toggle="modal" data-target="#modalEliminaGrupos" 
                                        data-examen-id="<?= $examen->id_examen ?>">
                                    <span class="glyphicon glyphicon-remove"></span> Eliminar
                                </button>
                                <button class="btns btn btn-primary" data-toggle="modal" data-target="#modalEditaGrupos" 
                                        data-examen-id="<?= $examen->id_examen ?>">
                                    <span class="glyphicon glyphicon-time"></span> Editar
                                </button>
                                <button class="btns btn btn-primary" data-toggle="modal" data-target="#modalGrupos" 
                                        data-examen-id="<?= $examen->id_examen ?>">
                                    <span class="glyphicon glyphicon-stats"></span> Grupos
                                </button>
                            </div>
                        </td> -->
                    </tr>
                <?php } ?>
            <?php else : ?>
                <tr>
                    <td colspan="9" class="no-records">
                        <center>No hay aplicaciones programadas</center>
                    </td>
                </tr>
            <?php endif ?>
        </tbody>
    </table>
</div>
    </div>

<!-- Modal -->
<div class="modal fade" id="modalGrupos" tabindex="-1" role="dialog" aria-labelledby="modalGruposLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalGruposLabel">Seleccione un grupo que DESEE monitorear</h4>
            </div>
            <div class="modal-body" id="grupos-container">
                <div class="text-center">
                    <i class="fa fa-spinner fa-spin fa-3x"></i>
                    <p>Cargando grupos...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- modal para editar grupos -->
<div class="modal fade" id="modalEditaGrupos" tabindex="-1" role="dialog" aria-labelledby="modalGruposLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalGruposLabel">Seleccione un grupo para EDITAR su programación</h4>
            </div>
            <div class="modal-body" id="grupos-container-editar">
                <div class="text-center">
                    <i class="fa fa-spinner fa-spin fa-3x"></i>
                    <p>Cargando grupos...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- modal para eliminas programacion grupos -->
<div class="modal fade" id="modalEliminaGrupos" tabindex="-1" role="dialog" aria-labelledby="modalGruposLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalGruposLabel">Seleccione un grupo para ELIMINAR su programación</h4>
            </div>
            <div class="modal-body" id="grupos-container-eliminar">
                <div class="text-center">
                    <i class="fa fa-spinner fa-spin fa-3x"></i>
                    <p>Cargando grupos...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- modal para ver programacion grupos TABLA -->
<div class="modal fade" id="modalAgendaGrupos" tabindex="-6" role="dialog" aria-labelledby="modalGruposLabel">
    <div class="modal-dialog modal-lg-custom" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalGruposLabel">Programación de grupos</h4>
            </div>
            <div class="modal-body" id="grupos-container-ver">
                <div class="text-center">
                    <i class="fa fa-spinner fa-spin fa-3x"></i>
                    <p>Cargando grupos...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#modalGrupos').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var examenId = button.data('examen-id');
        var modal = $(this);
        
        $('#grupos-container').html(`
            <div class="text-center">
                <i class="fa fa-spinner fa-spin fa-3x"></i>
                <p>Cargando grupos...</p>
            </div>
        `);
        
        var ajaxTimeout = setTimeout(function() {
            $('#grupos-container').html(`
                <div class="alert alert-danger">
                    La solicitud está tardando demasiado. Por favor, intente nuevamente.
                </div>
            `);
        }, 10000);
        
        $.ajax({
            url: '<?= site_url("sysmater/docente/examen_grupos/get_grupos_ajax/") ?>' + examenId,
            method: 'GET',
            dataType: 'json',
            timeout: 8000, 
            success: function(response) {
                clearTimeout(ajaxTimeout);
                
                if(response.success) {
                    var htmlContent = '<div class="list-group">';
                    
                    if(response.grupos && response.grupos.length > 0) {
                        response.grupos.forEach(function(grupo) {
                            htmlContent += `
                                <a href="<?= site_url('sysmater/docente/docente/progreso_examen_grupo/') ?>${examenId}/${encodeURIComponent(grupo)}" 
                                   class="btn btn-primary btn-block" style="margin-bottom:5px;">
                                    <span class="glyphicon glyphicon-education"></span>  ${grupo}
                                </a>
                            `;
                        });
                    } else {
                        htmlContent += '<div class="alert alert-warning">No hay grupos asignados a este examen</div>';
                    }
                    
                    htmlContent += '</div>';
                    $('#grupos-container').html(htmlContent);
                } else {
                    $('#grupos-container').html(`
                        <div class="alert alert-danger">
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
                    <div class="alert alert-danger">
                        ${errorMsg}
                    </div>
                `);
            }
        });
    });
});
// modal para editar grupos
$(document).ready(function() {
    $('#modalEditaGrupos').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var examenId = button.data('examen-id');
        var modal = $(this);
        
        $('#grupos-container-editar').html(`
            <div class="text-center">
                <i class="fa fa-spinner fa-spin fa-3x"></i>
                <p>Cargando grupos...</p>
            </div>
        `);
        
        var ajaxTimeout = setTimeout(function() {
            $('#grupos-container-editar').html(`
                <div class="alert alert-danger">
                    La solicitud está tardando demasiado. Por favor, intente nuevamente.
                </div>
            `);
        }, 10000);
        
        $.ajax({
            url: '<?= site_url("sysmater/docente/examen_grupos/get_grupos_ajax/") ?>' + examenId,
            method: 'GET',
            dataType: 'json',
            timeout: 8000, 
            success: function(response) {
                clearTimeout(ajaxTimeout);
                
                if(response.success) {
                    var htmlContent = '<div class="list-group">';
                    
                    if(response.grupos && response.grupos.length > 0) {
                        response.grupos.forEach(function(grupo) {
                            htmlContent += `
                                <a href="<?= site_url('sysmater/docente/docente/edita_programacion/') ?>${examenId}/${encodeURIComponent(grupo)}" 
                                   class="btn btn-success btn-block" style="margin-bottom:5px;">
                                    <span class="glyphicon glyphicon-education"></span>  ${grupo}
                                </a>
                            `;
                        });
                    } else {
                        htmlContent += '<div class="alert alert-warning">No hay grupos asignados a este examen</div>';
                    }
                    
                    htmlContent += '</div>';
                    $('#grupos-container-editar').html(htmlContent);
                } else {
                    $('#grupos-container-editar').html(`
                        <div class="alert alert-danger">
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
                
                $('#grupos-container-editar').html(`
                    <div class="alert alert-danger">
                        ${errorMsg}
                    </div>
                `);
            }
        });
    });
});
// modal para elimina programacion grupos
$(document).ready(function() {
    $('#modalEliminaGrupos').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var examenId = button.data('examen-id');
        var modal = $(this);
        
        $('#grupos-container-eliminar').html(`
            <div class="text-center">
                <i class="fa fa-spinner fa-spin fa-3x"></i>
                <p>Cargando grupos...</p>
            </div>
        `);
        
        var ajaxTimeout = setTimeout(function() {
            $('#grupos-container-eliminar').html(`
                <div class="alert alert-danger">
                    La solicitud está tardando demasiado. Por favor, intente nuevamente.
                </div>
            `);
        }, 10000);
        
        $.ajax({
            url: '<?= site_url("sysmater/docente/examen_grupos/get_grupos_ajax/") ?>' + examenId,
            method: 'GET',
            dataType: 'json',
            timeout: 8000, 
            success: function(response) {
                clearTimeout(ajaxTimeout);
                
                if(response.success) {
                    var htmlContent = '<div class="list-group">';
                    
                    if(response.grupos && response.grupos.length > 0) {
                        response.grupos.forEach(function(grupo) {
                            htmlContent += `
                                <a href="<?= site_url('sysmater/docente/docente/elimina_programacion/') ?>${examenId}/${encodeURIComponent(grupo)}" 
                                   class="btn btn-danger btn-block" style="margin-bottom:5px;">
                                   <span class="glyphicon glyphicon-education"></span>  
                                    ${grupo}
                                </a>
                            `;
                        });
                    } else {
                        htmlContent += '<div class="alert alert-warning">No hay grupos asignados a este examen</div>';
                    }
                    
                    htmlContent += '</div>';
                    $('#grupos-container-eliminar').html(htmlContent);
                } else {
                    $('#grupos-container-eliminar').html(`
                        <div class="alert alert-danger">
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
                
                $('#grupos-container-eliminar').html(`
                    <div class="alert alert-danger">
                        ${errorMsg}
                    </div>
                `);
            }
        });
    });
});

// modal para ver programacion grupos
$(document).ready(function() {
    $('#modalAgendaGrupos').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var examenId = button.data('examen-id');
        var modal = $(this);

        $('#grupos-container-ver').html(`
            <div class="text-center">
                <i class="fa fa-spinner fa-spin fa-3x"></i>
                <p>Cargando programación...</p>
            </div>
        `);

        var ajaxTimeout = setTimeout(function() {
            $('#grupos-container-ver').html(`
                <div class="alert alert-danger">
                    La solicitud está tardando demasiado. Por favor, intente nuevamente.
                </div>
            `);
        }, 10000);

        $.ajax({
            url: '<?= site_url("sysmater/docente/examen_grupos/get_data_examen_ajax/") ?>' + examenId,
            method: 'GET',
            dataType: 'json',
            timeout: 8000,
            success: function(response) {
                clearTimeout(ajaxTimeout);
                //console.log(response);

                if(response.success) {
                    var htmlContent = '';

                    function formatDate(fechaStr) {
                        const d = new Date(fechaStr);
                        return d.toLocaleDateString('es-MX');
                    }

                    function formatTime(horaStr) {
                        const d = new Date('1970-01-01T' + horaStr);
                        return d.toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' });
                    }
                    function formatHour(horaStr) {
                        if (!horaStr) return 'N/A';
                        return horaStr.substring(0, 5);
                    }

                    if (response.programacion && response.programacion.length > 0) {
                        const titulo = response.programacion[0].nvch_Titulo || 'Título no disponible';
                        htmlContent += `
                        <h3 class="text-center"><strong>${titulo}</strong></h3>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>GRUPO</th>
                                        <th>FECHA INICIO</th>
                                        <th>FECHA FIN</th>
                                        <th>HORA INICIO</th>
                                        <th>HORA FIN</th>
                                        <th>DURACIÓN</th>
                                        <th>ACCIÓNES</th>
                                    </tr>
                                </thead>
                                <tbody>
                        `;

                        response.programacion.forEach(function(item) {
                            htmlContent += `
                                <tr>
                                    <td>${item.vchGrupo}</td>
                                    <td>${formatDate(item.fch_inicia)}</td>
                                    <td>${formatDate(item.fch_termina)}</td>
                                    <td>${formatTime(item.tm_hora_inicio)}</td>
                                    <td>${formatTime(item.tm_hora_final)}</td>
                                    <td>${formatHour(item.tm_duracion)}</td>
                                    <td>
                                        <div class="btn-group btn-group-xs" role="group" aria-label="...">  
                                            <a href="<?= site_url('sysmater/docente/docente/elimina_programacion/') ?>${examenId}/${encodeURIComponent(item.vchGrupo)}" 
                                                class="btns btn btn-danger" style="margin-bottom:5px;">
                                                <span class="glyphicon glyphicon-trash"></span> Eliminar
                                            </a>
                                            <a href="<?= site_url('sysmater/docente/docente/edita_programacion/') ?>${examenId}/${encodeURIComponent(item.vchGrupo)}" 
                                                class="btns btn btn-primary" style="margin-bottom:5px;">
                                                <span class="glyphicon glyphicon-time"></span> Editar
                                            </a>
                                            <a href="<?= site_url('sysmater/docente/docente/progreso_examen_grupo/') ?>${examenId}/${encodeURIComponent(item.vchGrupo)}" 
                                                class="btns btn btn-warning" style="margin-bottom:5px;">
                                                <span class="glyphicon glyphicon-eye-open"></span> Progreso
                                            </a>
                                        </div>
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
                        htmlContent += '<div class="alert alert-warning">No hay programación para este examen.</div>';
                    }

                    $('#grupos-container-ver').html(htmlContent);

                } else {
                    $('#grupos-container-ver').html(`
                        <div class="alert alert-danger">
                            ${response.error || 'Error al cargar la programación'}
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

                $('#grupos-container-ver').html(`
                    <div class="alert alert-danger">
                        ${errorMsg}
                    </div>
                `);
            }
        });
    });
});


</script>