// fn_actividades.js - Módulo de actividades alumno

function cargarActividades(parcial) {
    const $acordeonActividades = $('#acordeonActividades');

    if (!parcial) {
        $acordeonActividades.html(`
            <div class="alert alert-info text-center">
                <h4>Selecciona un parcial para ver las actividades</h4>
            </div>`);
        return;
    }

    $acordeonActividades.html(`
        <div class="alert alert-info text-center">
            <h4>Cargando actividades...</h4>
        </div>`);

    // Validar que base_url existe antes de hacer la petición
    if (typeof base_url === 'undefined') {
        $acordeonActividades.html(`
            <div class="alert alert-danger">
                <h4>Error de configuración</h4>
                <p>Contacte al administrador del sistema</p>
            </div>`);
        return;
    }

    $.ajax({
        url: base_url + "sysmater/alumno/ActividadController/get_actividades_por_parcial",
        method: "POST",
        data: { parcial },
        dataType: "json",
        timeout: 10000,
        success: function(response) {
            const $contenedor = $('#acordeonActividades');

            if (response.error) {
                $contenedor.html(`
                    <div class="alert alert-danger">
                        <h4>Error:</h4>
                        <p>${response.error}</p>
                    </div>`);
                return;
            }

            const data = response.data;
            
            if (!data || !data.length) {
                $contenedor.html(`
                    <div class="alert alert-warning text-center">
                        <h4>No hay actividades registradas para el Parcial ${parcial}</h4>
                    </div>`);
                return;
            }

            // Agrupar por materia
            const materiasPorClave = {};
            data.forEach(item => {
                if (!materiasPorClave[item.vchClvMateria]) {
                    materiasPorClave[item.vchClvMateria] = {
                        clave: item.vchClvMateria,
                        nombre: item.vchNomMateria,
                        actividades: []
                    };
                }
                materiasPorClave[item.vchClvMateria].actividades.push(item);
            });

            // Crear acordeón
            let html = '<div class="panel-group" id="acordeonMaterias">';
            
            Object.values(materiasPorClave).forEach((materia, index) => {
                const panelId = `panel${index}`;
                const collapseId = `collapse${index}`;
                
                html += `
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="${panelId}">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#acordeonMaterias" 
                                   href="#${collapseId}" aria-expanded="${index === 0 ? 'true' : 'false'}" 
                                   aria-controls="${collapseId}">
                                    <span class="glyphicon glyphicon-chevron-down"></span>
                                    ${materia.nombre} 
                                    <span class="label label-default">${materia.clave}</span>
                                    <span class="badge pull-right">${materia.actividades.length}</span>
                                </a>
                            </h4>
                        </div>
                        <div id="${collapseId}" class="panel-collapse collapse ${index === 0 ? 'in' : ''}" 
                             role="tabpanel" aria-labelledby="${panelId}">
                            <div class="panel-body">`;

                // Cards de actividades con enlaces directos
                materia.actividades.forEach((act) => {
                    const fechaEntrega = act.fecha_entrega ? 
                        new Date(act.fecha_entrega).toLocaleDateString('es-MX') : 'Sin fecha';
                    const claseEstado = obtenerClaseEstado(act.nombre_estado || '');
                    
                    html += `
                        <div class="col-md-4 col-sm-6" style="margin-bottom: 15px;">
                            <a href="${act.url_detalle}" class="actividad-link" 
                               style="text-decoration: none; color: inherit; display: block;">
                                <div class="panel panel-default actividad-card" 
                                     style="transition: all 0.2s; height: 100%;">
                                    <div class="panel-body" style="padding: 10px;">
                                        <h5 style="margin-top: 0; margin-bottom: 8px;">
                                            <strong>${act.titulo || 'Sin título'}</strong>
                                        </h5>
                                        <p style="margin-bottom: 5px;">
                                            <small class="text-muted">
                                                <span class="glyphicon glyphicon-calendar"></span> ${fechaEntrega}
                                            </small>
                                        </p>
                                        <div style="margin-bottom: 8px;">
                                            <span class="label ${claseEstado}">${act.nombre_estado}</span>
                                            <span class="label label-info">${obtenerTextoModalidad(act.id_modalidad)}</span>
                                        </div>
                                        <p class="text-center" style="margin-bottom: 0;">
                                            <small class="text-primary">
                                                <span class="glyphicon glyphicon-eye-open"></span> Ver detalles
                                            </small>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>`;
                });

                html += `
                            </div>
                        </div>
                    </div>`;
            });
            
            html += '</div>';
            $contenedor.html(html);
            
            // Eventos para cambiar el chevron
            $('#acordeonMaterias').on('show.bs.collapse', function (e) {
                $(e.target).prev().find('.glyphicon')
                    .removeClass('glyphicon-chevron-down')
                    .addClass('glyphicon-chevron-up');
            });
            
            $('#acordeonMaterias').on('hide.bs.collapse', function (e) {
                $(e.target).prev().find('.glyphicon')
                    .removeClass('glyphicon-chevron-up')
                    .addClass('glyphicon-chevron-down');
            });

            // Efecto hover para los enlaces
            $('#acordeonMaterias').on('mouseenter', '.actividad-link', function() {
                $(this).find('.actividad-card').removeClass('panel-default').addClass('panel-info');
            }).on('mouseleave', '.actividad-link', function() {
                $(this).find('.actividad-card').removeClass('panel-info').addClass('panel-default');
            });
        },
        error: function(xhr, status, error) {
            let errorMsg = 'Error de conexión';
            if (status === 'timeout') {
                errorMsg = 'La solicitud tardó demasiado tiempo';
            } else if (status === 'parsererror') {
                errorMsg = 'Error en la respuesta del servidor';
            }
            
            $('#acordeonActividades').html(`
                <div class="alert alert-danger">
                    <h4>Error al cargar las actividades</h4>
                    <p>${errorMsg}</p>
                    <button class="btn btn-default btn-sm" onclick="cargarActividades('${parcial}')">
                        <span class="glyphicon glyphicon-refresh"></span> Reintentar
                    </button>
                </div>`);
        }
    });
}

function obtenerClaseEstado(estado) {
    switch (estado.toLowerCase()) {
        case 'asignada': return 'label-info';
        case 'entregada': return 'label-primary';
        case 'pendiente': return 'label-warning';
        case 'revisada': return 'label-success';
        case 'incompleta': return 'label-danger';
        default: return 'label-default';
    }
}

function obtenerTextoModalidad(idModalidad) {
    switch (parseInt(idModalidad)) {
        case 1: return 'Individual';
        case 2: return 'Equipo';
        default: return 'Sin definir';
    }
}