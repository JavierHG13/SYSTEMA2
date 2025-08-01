<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
<!-- CSS extraído -->
<link rel="stylesheet" href="<?= base_url() ?>assets/css/sys-forms.css">
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


<div class="container-fluid">
    <!-- Header -->
    <div class="text-center">
        <h2>Asignar Nueva Actividad</h2>
        <p>Configura y asigna actividades de evaluación a tus grupos</p>
    </div>

    <!-- Información Básica y Configuración Académica -->
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><span class="glyphicon glyphicon-edit"></span> Información Básica y Configuración Académica</h3>
        </div>
        <div class="panel-body">
            <form id="formAsignarActividad" onsubmit="asignarActividad(event)">
                <!-- Información Básica -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre_actividad">Nombre de la Actividad:</label>
                            <input type="text" id="nombre_actividad" name="nombre_actividad" class="form-control" placeholder="Ej: Examen Parcial de Matemáticas" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cuatrimestre">Cuatrimestre:</label>
                            <select id="cuatrimestre" name="cuatrimestre" class="form-control" required>
                                <option value="">Seleccione</option>
                                <?php if (isset($cuatrimestres) && $cuatrimestres->num_rows() > 0): ?>
                                    <?php foreach ($cuatrimestres->result() as $cuatri): ?>
                                        <option value="<?= $cuatri->vchCuatrimestre ?>"><?= $cuatri->vchNomCuatri ?></option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="">No hay cuatrimestres disponibles</option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" class="form-control" placeholder="Describe las instrucciones y objetivos de la actividad..." rows="3" required></textarea>
                </div>

                <!-- Configuración Académica -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="materias">Materia:</label>
                            <select id="materias" name="materias" class="form-control" required>
                                <option value="">Seleccione primero el cuatrimestre</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="parcial">Parcial:</label>
                            <select id="parcial" name="parcial" class="form-control" required>
                                <option value="">Seleccione</option>
                                <option value="1">1er Parcial</option>
                                <option value="2">2do Parcial</option>
                                <option value="3">3er Parcial</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="modalidad">Modalidad:</label>
                    <select id="modalidad" name="modalidad" class="form-control" required>
                        <option value="">Seleccione</option>
                        <?php foreach ($modalidades->result() as $modalidad): ?>
                            <option value="<?= $modalidad->id_modalidad; ?>">
                                <?= $modalidad->nombre_modalidad; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
        </div>
    </div>

    <!-- Asignación de Grupos -->
    <div class="panel panel-warning">
        <div class="panel-heading">
            <h3 class="panel-title"><span class="glyphicon glyphicon-th-list"></span> Asignación de Grupos</h3>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label>Grupos Asignados:</label>
                <div id="gruposContainer" class="selection-container">
                    <p class="text-muted">Seleccione una materia para ver los grupos disponibles</p>
                </div>
            </div>

            <div class="form-group" id="equiposContainer" style="display: none;">
                <label>Equipos del Grupo:</label>
                <div id="equiposCheckboxes" class="selection-container">
                    <p class="text-muted">Seleccione un grupo para ver los equipos disponibles</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Configuración de Evaluación -->
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title"><span class="glyphicon glyphicon-star"></span> Configuración de Evaluación</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="componente">Componente:</label>
                        <select id="componente" name="componente" class="form-control" required>
                            <option value="">Seleccione un componente</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="instrumentos">Instrumento:</label>
                        <div class="input-group">
                            <select id="instrumentos" name="instrumentos" class="form-control" required>
                                <option value="">Seleccione</option>
                            </select>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info" id="verInstrumento" data-toggle="modal" data-target="#instrumentoModal" disabled style="background-color: var(--primary-color); border-color: var(--primary-color); color: white; border-radius: 0 4px 4px 0; padding: 8px 12px; font-size: 15px;">
                                    <span class="glyphicon glyphicon-eye-open"></span> Ver Detalles
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para mostrar detalles del instrumento -->
            <div class="modal fade" id="instrumentoModal" tabindex="-1" role="dialog" aria-labelledby="instrumentoModalLabel">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content" style="border-radius: 8px; border: 1px solid #ddd; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);">
                        <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%); border-color: var(--primary-color); border-radius: 7px 7px 0 0; padding: 15px 20px;">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title" id="instrumentoModalLabel" style="color: white; font-weight: 600; font-size: 18px;">Detalles del Instrumento</h4>
                        </div>
                        <div class="modal-body" style="padding: 20px; max-height: 500px; overflow-y: auto; background-color: #f9f9f9;">
                            <div id="instrumentoDetalles" style="font-size: 15px; color: #333;">
                                <p class="text-muted">Seleccione un instrumento para ver los detalles.</p>
                            </div>
                            <div id="criteriosDetalles" style="font-size: 15px; color: #333;">
                                <h5 style="font-weight: 600; margin-bottom: 10px;">Criterios Asociados</h5>
                                <p class="text-muted">No hay criterios disponibles.</p>
                            </div>
                        </div>
                        <div class="modal-footer" style="border-top: 1px solid #ddd; padding: 15px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal" style="background-color: #f9f9f9; border-color: #ddd; color: #333; font-size: 15px; padding: 8px 16px; border-radius: 4px;">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin Modal -->

            <!-- Botones -->
            <div class="text-center" style="margin-top: 20px;">
                <button type="submit" class="btn btn-success btn-lg">
                    <span class="glyphicon glyphicon-ok"></span> Asignar Actividad
                </button>
                <a href="<?= site_url('/sysmater/admin/admin/lista_actividades') ?>" class="btn btn-danger btn-lg" style="margin-left: 15px;">
                    <span class="glyphicon glyphicon-remove"></span> Cancelar
                </a>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Contenedor de mensajes -->
<div id="msgFlash"></div>

<!-- Flatpickr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>

<script>
    const base_url = "<?= base_url(); ?>";

    document.addEventListener("DOMContentLoaded", function() {
        // Inicializar Flatpickr
        flatpickr("#fechaAplicacion", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            minDate: "today",
            locale: {
                firstDayOfWeek: 1,
                weekdays: {
                    shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                    longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado']
                },
                months: {
                    shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    longhand: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']
                }
            }
        });

        document.getElementById("cuatrimestre").addEventListener("change", get_materias);
        document.getElementById("materias").addEventListener("change", get_grupos);
        document.getElementById("parcial").addEventListener("change", () => {
            get_instrumentos();
        });
    });

    function get_materias() {
        const cuatrimestre = document.getElementById("cuatrimestre").value;
        $.post(base_url + "sysmater/docente/asignar_actividades/cargar_materias_del_docente", {
            vchCuatrimestre: cuatrimestre
        }, function(data) {
            const materias = $('#materias');
            materias.empty().append('<option value="">Seleccione una materia</option>');
            const response = JSON.parse(data);
            response.forEach(m => materias.append(`<option value="${m.vchClvMateria}">${m.vchNomMateria}</option>`));
        });
    }

    function get_grupos(esEquipo = false) {
        const materias = document.getElementById("materias").value;
        if (!materias) return;

        $.post(base_url + "sysmater/docente/asignar_actividades/listar_grupos", {
            vchClvMateria: materias
        }, function(data) {
            const response = JSON.parse(data);
            const container = document.getElementById("gruposContainer");
            container.innerHTML = "";

            if (response.length === 0) {
                container.innerHTML = '<div class="alert alert-warning">⚠ No hay grupos asignados para esta materia.</div>';
                return;
            }

            if (esEquipo) {
                response.forEach(grupo => {
                    container.innerHTML += `
                        <div class="form-check form-check-inline me-3">
                            <input class="form-check-input" type="radio" name="grupo_unico" value="${grupo.id_grupo}" id="grupo-${grupo.vchGrupo}">
                            <label class="form-check-label" for="grupo-${grupo.vchGrupo}">${grupo.vchGrupo}</label>
                        </div>
                        <div class="form-group mt-1">
                            <label for="fecha-grupo-${grupo.id_grupo}">Fecha y Hora de Evaluación para grupo ${grupo.vchGrupo}:</label>
                            <input type="text" class="form-control fecha-grupo" id="fecha-grupo-${grupo.id_grupo}" data-idgrupo="${grupo.id_grupo}" placeholder="Seleccionar fecha" readonly>
                        </div>
                    `;
                    document.querySelectorAll('.fecha-grupo').forEach(input => {
                        flatpickr(input, {
                            enableTime: true,
                            dateFormat: "Y-m-d H:i",
                            time_24hr: true,
                            minDate: "today",
                            locale: {
                                firstDayOfWeek: 1,
                                weekdays: {
                                    shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                                    longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado']
                                },
                                months: {
                                    shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                                    longhand: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']
                                }
                            }
                        });
                    });
                });
                document.querySelectorAll('input[name="grupo_unico"]').forEach(rb =>
                    rb.addEventListener("change", function() {
                        cargarEquiposPorGrupo(this.value);
                    })
                );
            } else {
                response.forEach(grupo => {
                    container.innerHTML += `
                        <div class="grupo-fecha-container mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="grupos" value="${grupo.id_grupo}" id="grupo-${grupo.vchGrupo}">
                                <label class="form-check-label me-3" for="grupo-${grupo.vchGrupo}">${grupo.vchGrupo}</label>
                            </div>
                            <div class="form-group mt-1">
                                <label for="fecha-grupo-${grupo.id_grupo}">Fecha y Hora de Evaluación para grupo  ${grupo.vchGrupo}:</label>
                                <input type="text" class="form-control fecha-grupo" id="fecha-grupo-${grupo.id_grupo}" data-idgrupo="${grupo.id_grupo}" placeholder="Seleccionar fecha" readonly>
                            </div>
                        </div>
                    `;
                });

                // Aplicar Flatpickr a los nuevos elementos
                document.querySelectorAll('.fecha-grupo').forEach(input => {
                    flatpickr(input, {
                        enableTime: true,
                        dateFormat: "Y-m-d H:i",
                        time_24hr: true,
                        minDate: "today",
                        locale: {
                            firstDayOfWeek: 1,
                            weekdays: {
                                shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                                longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado']
                            },
                            months: {
                                shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                                longhand: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']
                            }
                        }
                    });
                });
            }
        });
    }

    function get_instrumentos() {
        const vchClvMateria = document.getElementById("materias").value;
        const parcial = document.getElementById("parcial").value;
        $.post(base_url + "sysmater/docente/asignar_actividades/listar_instrumentos", {
            vchClvMateria,
            parcial
        }, function(data) {
            const instrumentos = $('#instrumentos');
            instrumentos.empty().append('<option value="">Seleccione una hoja</option>');
            const response = JSON.parse(data);
            if (response.length === 0) {
                instrumentos.append('<option value="">⚠ No hay instrumentos disponibles</option>');
            } else {
                response.forEach(inst => instrumentos.append(`<option value="${inst.id_instrumento}">${inst.nombre}</option>`));
            }
        });
    }



    function asignarActividad(event) {
        event.preventDefault();
        const titulo = document.getElementById("nombre_actividad").value.trim();
        const descripcion = document.getElementById("descripcion").value.trim();
        const vchClvMateria = document.getElementById("materias").value;
        const id_instrumento = document.getElementById("instrumentos").value;
        const id_valor_componente = document.getElementById("componente").value;
        const id_modalidad = document.getElementById("modalidad").value;

        if (!titulo || !descripcion || !vchClvMateria || !id_instrumento || !id_modalidad) {
            alert("Por favor, complete todos los campos obligatorios.");
            return;
        }

        const fechas_por_grupo = {};
        document.querySelectorAll('.fecha-grupo').forEach(input => {
            fechas_por_grupo[input.dataset.idgrupo] = input.value;
        });

        let grupos = [];
        if (id_modalidad == 2) {
            let grupoSeleccionado = document.querySelector('input[name="grupo_unico"]:checked');
            if (grupoSeleccionado) grupos = [grupoSeleccionado.value];
        } else {
            grupos = Array.from(document.querySelectorAll('input[name="grupos"]:checked')).map(cb => cb.value);
        }

        // Validar que se hayan seleccionado grupos
        if (grupos.length === 0) {
            mostrarMensaje("Debe seleccionar al menos un grupo.", "error");
            return;
        }

        // NUEVA VALIDACIÓN: Verificar fechas y horas
        if (!validarFechasYHoras(grupos, fechas_por_grupo, id_modalidad)) {
            return; // Detener el proceso si las validaciones fallan
        }

        // Validar equipos para modalidad 2
        const equipos = Array.from(document.querySelectorAll('input[name="equipos"]:checked')).map(cb => cb.value);
        if (id_modalidad == 2 && equipos.length === 0) {
            mostrarMensaje("Debe seleccionar al menos un equipo.", "error");
            return;
        }

        // Validación adicional: verificar que todos los grupos seleccionados tengan fecha
        const gruposSinFecha = grupos.filter(grupo => !fechas_por_grupo[grupo] || fechas_por_grupo[grupo].trim() === '');
        if (gruposSinFecha.length > 0) {
            const nombresGrupos = gruposSinFecha.map(id => obtenerNombreGrupo(id)).join(', ');
            mostrarMensaje(`Los siguientes grupos no tienen fecha asignada: ${nombresGrupos}`, 'error');
            return;
        }

        // Validación adicional: verificar duplicados de fecha (opcional)
        const fechasUsadas = Object.values(fechas_por_grupo);
        const fechasDuplicadas = fechasUsadas.filter((fecha, index) => fechasUsadas.indexOf(fecha) !== index);
        if (fechasDuplicadas.length > 0) {
            const confirmacion = confirm("Hay grupos con la misma fecha y hora programada. ¿Desea continuar?");
            if (!confirmacion) {
                return;
            }
        }


        const datos = {
            titulo,
            descripcion,
            vchClvMateria,
            id_instrumento,
            id_modalidad,
            id_valor_componente,
            grupos,
            equipos,
            fechas_por_grupo
        };

        $.ajax({
            url: base_url + "sysmater/docente/asignar_actividades/guardar_actividad",
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify(datos),
            success: function(response) {
                mostrarMensaje("Actividad asignada correctamente", 'success');
                setTimeout(() => window.location.reload(), 3000);
            },
            error: function(xhr) {
                mostrarMensaje("Error al asignar actividad", 'error');
            }
        });
    }

    document.getElementById("modalidad").addEventListener("change", function() {
        const modalidad = this.value;
        const equiposContainer = document.getElementById("equiposContainer");
        equiposContainer.style.display = modalidad == 2 ? "flex" : "none";
        get_grupos(modalidad == 2);
    });

    function cargarEquiposPorGrupo(id_grupo) {
        $.post(base_url + "sysmater/docente/asignar_actividades/listar_equipos", {
            id_grupo
        }, function(data) {
            const contenedor = document.getElementById("equiposCheckboxes");
            const materia = document.getElementById('materias').value;

            contenedor.innerHTML = "";
            const response = JSON.parse(data);

            if (response.length === 0) {
                contenedor.innerHTML = `
                    <div class="alert alert-warning">
                        ⚠ No hay equipos registrados para este grupo.
                        <a href="${base_url}sysmater/docente/equipos/index/${id_grupo}/${materia}" class="alert-link" target="_blank">Crear equipos</a>
                    </div>`;
                return;
            }

            contenedor.innerHTML += `
                <div class="form-check mb-2">
                    <input type="checkbox" class="form-check-input" id="seleccionarTodosEquipos">
                    <label class="form-check-label" for="seleccionarTodosEquipos">Seleccionar todos</label>
                </div>`;
            response.forEach(equipo => {
                contenedor.innerHTML += `
                    <div class="form-check">
                        <input class="form-check-input equipo-check" type="checkbox" name="equipos" value="${equipo.id_equipo}" id="equipo-${equipo.id_equipo}">
                        <label class="form-check-label" for="equipo-${equipo.id_equipo}">${equipo.nombre_equipo}</label>
                    </div>`;
            });

            document.getElementById("seleccionarTodosEquipos").addEventListener("change", function() {
                document.querySelectorAll(".equipo-check").forEach(cb => cb.checked = this.checked);
            });
        });
    }

    $(document).ready(function() {
        $('#materias, #parcial, #cuatrimestre').on('change', function() {
            let id_materia = $('#materias').val();
            let parcial = $('#parcial').val();
            let periodo = $('#cuatrimestre').val();
            if (id_materia && parcial && periodo) {
                $.ajax({
                    url: '<?= base_url("sysmater/docente/actividades/obtener_componentes") ?>',
                    type: 'POST',
                    data: {
                        id_materia,
                        parcial,
                        periodo
                    },
                    dataType: 'json',
                    success: function(response) {
                        let select = $('#componente');
                        select.empty();
                        if (response.length > 0) {
                            select.append('<option value="">Seleccione un componente</option>');
                            response.forEach(comp => select.append(`<option value="${comp.id_valor_componente}">${comp.componente} (${comp.valor_componente} puntos)</option>`));
                        } else {
                            select.append('<option value="">Sin componentes disponibles</option>');
                        }
                    },
                    error: function() {
                        alert('Error al cargar los componentes.');
                    }
                });
            }
        });

        // Mostrar detalles del instrumento
        document.getElementById("instrumentos").addEventListener("change", function() {
            const idInstrumento = this.value;
            const verInstrumentoBtn = document.getElementById("verInstrumento");
            const instrumentoDetalles = document.getElementById("instrumentoDetalles");
            const criteriosDetalles = document.getElementById("criteriosDetalles");

            const defaultContent = {
                instrumento: '<p class="text-muted">Seleccione un instrumento para ver los detalles.</p>',
                criterios: '<h5 style="font-weight: 600; margin-bottom: 10px;">Criterios Asociados</h5><p class="text-muted">No hay criterios disponibles.</p>'
            };

            if (!idInstrumento) {
                verInstrumentoBtn.disabled = true;
                instrumentoDetalles.innerHTML = defaultContent.instrumento;
                criteriosDetalles.innerHTML = defaultContent.criterios;
                return;
            }

            verInstrumentoBtn.disabled = false;
            instrumentoDetalles.innerHTML = '<p><span class="glyphicon glyphicon-refresh glyphicon-spin"></span> Cargando...</p>';
            criteriosDetalles.innerHTML = '<h5 style="font-weight: 600; margin-bottom: 10px;">Criterios Asociados</h5><p><span class="glyphicon glyphicon-refresh glyphicon-spin"></span> Cargando...</p>';

            $.post(base_url + "sysmater/docente/asignar_actividades/obtener_detalles_instrumento", {
                id_instrumento: idInstrumento
            }, function(data) {
                const response = JSON.parse(data);
                if (response?.instrumento) {
                    instrumentoDetalles.innerHTML = `
                        <h5 style="font-weight: 600; margin-bottom: 10px;">${response.instrumento.nombre}</h5>
                        <p><strong>Tipo:</strong> ${response.instrumento.nombre_tipo}</p>
                        <p><strong>Parcial:</strong> ${response.instrumento.parcial}° Parcial</p>
                        <p><strong>Valor Total:</strong> ${response.instrumento.valor_total} puntos</p>
                    `;
                    criteriosDetalles.innerHTML = response.criterios?.length ? `
                        <h5 style="font-weight: 600; margin-bottom: 10px;">Criterios Asociados</h5>
                        <table class="table table-bordered" style="background-color: white; border: 1px solid #ddd;">
                            <thead style="background-color: rgba(39, 129, 4, 0.1);"><tr><th style="font-weight: 600; font-size: 14px; padding: 10px;">Nombre</th><th style="font-weight: 600; font-size: 14px; padding: 10px;">Descripción</th><th style="font-weight: 600; font-size: 14px; padding: 10px;">Valor Máximo</th></tr></thead>
                            <tbody>${response.criterios.map(c => `
                                <tr>
                                    <td style="font-size: 14px; padding: 10px;">${c.nombre}</td>
                                    <td style="font-size: 14px; padding: 10px;">${c.descripcion || 'Sin descripción'}</td>
                                    <td style="font-size: 14px; padding: 10px;">${c.valor_maximo} puntos</td>
                                </tr>`).join('')}
                            </tbody>
                        </table>
                    ` : defaultContent.criterios;
                } else {
                    instrumentoDetalles.innerHTML = '<p class="text-danger" style="font-size: 15px;">No se encontraron detalles para este instrumento.</p>';
                    criteriosDetalles.innerHTML = defaultContent.criterios;
                }
            }).fail(function(xhr, status, error) {
                instrumentoDetalles.innerHTML = `<p class="text-danger" style="font-size: 15px;">Error al cargar los detalles: ${error}</p>`;
                criteriosDetalles.innerHTML = '<h5 style="font-weight: 600; margin-bottom: 10px;">Criterios Asociados</h5><p class="text-danger" style="font-size: 15px;">Error al cargar los criterios.</p>';
            });
        });
    });


    // Validación en tiempo real cuando se selecciona una fecha
    document.addEventListener("DOMContentLoaded", function() {
        // Agregar validación a los inputs de fecha cuando pierden el foco
        document.addEventListener('focusout', function(e) {
            if (e.target.classList.contains('fecha-grupo')) {
                const fecha = e.target.value;
                if (fecha && !validarFormatoFecha(fecha)) {
                    mostrarMensaje("Formato de fecha inválido. Use: YYYY-MM-DD HH:MM", "error");
                    e.target.focus();
                    return;
                }

                if (fecha) {
                    const fechaSeleccionada = new Date(fecha);
                    const fechaActual = new Date();

                    if (fechaSeleccionada <= fechaActual) {
                        mostrarMensaje("La fecha debe ser posterior a la fecha actual.", "error");
                        e.target.focus();
                        return;
                    }
                }
            }
        });
    });

    // Función adicional para validar antes de enviar el formulario
    function validarFormularioCompleto() {
        // Obtener todos los campos de fecha
        const camposFecha = document.querySelectorAll('.fecha-grupo');
        let hayErrores = false;

        camposFecha.forEach(campo => {
            const grupoCheckbox = document.querySelector(`input[data-idgrupo="${campo.dataset.idgrupo}"]:checked`) ||
                document.querySelector(`input[value="${campo.dataset.idgrupo}"]:checked`);

            // Si el grupo está seleccionado, la fecha es obligatoria
            if (grupoCheckbox && (!campo.value || campo.value.trim() === '')) {
                campo.classList.add('error');
                campo.style.borderColor = '#dc3545';
                hayErrores = true;
            } else {
                campo.classList.remove('error');
                campo.style.borderColor = '';
            }
        });

        return !hayErrores;
    }

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


    // Función para validar fechas y horas

    function validarFechasYHoras(grupos, fechas_por_grupo, id_modalidad) {
        // Validar que todas las fechas estén presentes
        for (let id_grupo of grupos) {
            const fecha = fechas_por_grupo[id_grupo];

            // Verificar que la fecha no esté vacía
            if (!fecha || fecha.trim() === '') {
                mostrarMensaje(`Por favor, seleccione una fecha y hora para el grupo ${obtenerNombreGrupo(id_grupo)}.`, "error");
                return false;
            }

            // Validar formato de fecha
            if (!validarFormatoFecha(fecha)) {
                mostrarMensaje(`La fecha "${fecha}" no tiene un formato válido. Use el formato: YYYY-MM-DD HH:MM`, "error");
                return false;
            }

            // Validar que la fecha sea futura
            const fechaSeleccionada = new Date(fecha);
            const fechaActual = new Date();

            if (fechaSeleccionada <= fechaActual) {
                mostrarMensaje(`La fecha para el grupo ${obtenerNombreGrupo(id_grupo)} debe ser posterior a la fecha actual.`, "error");
                return false;
            }

            // Validar que no sea un día pasado
            if (fechaSeleccionada.getTime() < fechaActual.getTime()) {
                mostrarMensaje(`No se puede programar una actividad en una fecha pasada para el grupo ${obtenerNombreGrupo(id_grupo)}.`, "error");
                return false;
            }
 
            // Validar horario académico (opcional - ajusta según tus necesidades)
            const hora = fechaSeleccionada.getHours();
            if (hora < 7 || hora > 22) {
                mostrarMensaje(`La hora seleccionada para el grupo ${obtenerNombreGrupo(id_grupo)} debe estar en un horario valido.`, "error");
                return false;
            }
        }

        return true;
    }

    // Función auxiliar para validar formato de fecha
    function validarFormatoFecha(fecha) {
        // Regex para formato YYYY-MM-DD HH:MM
        const formatoFecha = /^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/;

        if (!formatoFecha.test(fecha)) {
            return false;
        }

        // Crear objeto Date y verificar que sea válido
        const fechaObj = new Date(fecha);
        if (isNaN(fechaObj.getTime())) {
            return false;
        }

        // Verificar que los componentes de fecha sean válidos
        const partes = fecha.split(' ');
        const [año, mes, dia] = partes[0].split('-');
        const [hora, minuto] = partes[1].split(':');

        const añoNum = parseInt(año);
        const mesNum = parseInt(mes);
        const diaNum = parseInt(dia);
        const horaNum = parseInt(hora);
        const minutoNum = parseInt(minuto);

        // Validaciones específicas
        if (añoNum < 2024 || añoNum > 2030) return false;
        if (mesNum < 1 || mesNum > 12) return false;
        if (diaNum < 1 || diaNum > 31) return false;
        if (horaNum < 0 || horaNum > 23) return false;
        if (minutoNum < 0 || minutoNum > 59) return false;

        return true;
    }

    // Función auxiliar para obtener el nombre del grupo
    function obtenerNombreGrupo(id_grupo) {
        const grupoElement = document.querySelector(`input[value="${id_grupo}"]`);
        if (grupoElement) {
            const label = document.querySelector(`label[for="${grupoElement.id}"]`);
            return label ? label.textContent : `Grupo ${id_grupo}`;
        }
        return `Grupo ${id_grupo}`;
    }
</script>