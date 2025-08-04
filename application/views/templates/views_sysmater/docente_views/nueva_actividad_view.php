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

    /* Estilos para gestión de equipos */
    .equipos-preview {
        max-height: 400px;
        overflow-y: auto;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        background-color: #f9f9f9;
    }

    .equipo-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 10px;
        background-color: white;
    }

    .equipo-header {
        font-weight: bold;
        color: #007bff;
        margin-bottom: 8px;
    }

    .integrante-item {
        padding: 3px 8px;
        background-color: #e9ecef;
        border-radius: 4px;
        margin: 2px;
        display: inline-block;
        font-size: 12px;
    }

    .control-equipos {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 2px solid #f3f3f3;
        border-radius: 50%;
        border-top: 2px solid #007bff;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .btn-equipos {
        margin: 5px;
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
        animation: fadeOut 0.5s ease-out forwards;
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }

        to {
            opacity: 0;
            transform: translateX(100%);
        }
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="text-center">
        <h2>Nueva Actividad</h2>
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

            <!-- Sección de Equipos (solo para modalidad por equipos) -->
            <div class="form-group" id="equiposContainer" style="display: none;">
                <label>Gestión de Equipos:</label>

                <!-- Controles para formar equipos -->
                <div class="control-equipos" id="controlesEquipos" style="display: none;">
                    
                    <div class="form-group" style="margin: 0;">
                        <label for="cantidadEquipos">Cantidad de Equipos:</label>
                        <select id="cantidadEquipos" class="form-control" style="width: 100px; display: inline-block;">
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </div>

                    <button type="button" class="btn btn-info btn-equipos" onclick="simularEquiposAleatorios()">
                        🎲 Simular Aleatorio
                    </button>
                    <button type="button" class="btn btn-warning btn-equipos" onclick="mostrarFormacionManual()">
                        👥 Formar Manual
                    </button>
                    <button type="button" class="btn btn-success btn-equipos" onclick="cargarEquiposExistentesBtn()" id="btnEquiposExistentes" style="display: none;">
                        📋 Usar Existentes
                    </button>
                </div>

                <!-- Preview de equipos -->
                <div id="equiposPreview" style="display: none;">
                    <h5>Vista Previa de Equipos:</h5>
                    <div id="equiposContent" class="equipos-preview">
                        <!-- Aquí se mostrarán los equipos -->
                    </div>
                    <div class="text-center" style="margin-top: 10px;">
                        <button type="button" class="btn btn-success" onclick="confirmarEquipos()">
                            ✅ Confirmar Equipos
                        </button>
                        <button type="button" class="btn btn-danger" onclick="cancelarFormacionEquipos()">
                            ❌ Cancelar
                        </button>
                    </div>
                </div>

                <!-- Equipos confirmados -->
                <div id="equiposConfirmados" style="display: none;">
                    <div class="alert alert-success">
                        <strong>✅ Equipos Confirmados:</strong> Los equipos han sido formados correctamente.
                        <button type="button" class="btn btn-sm btn-warning" onclick="reconfigurarEquipos()" style="margin-left: 10px;">
                            🔄 Reconfigurar
                        </button>
                    </div>
                    <div id="equiposConfirmadosContent" class="equipos-preview">
                        <!-- Equipos confirmados se muestran aquí -->
                    </div>
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

<!-- Modal para formación manual de equipos -->
<div class="modal fade" id="modalFormacionManual" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Formación Manual de Equipos</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Alumnos Disponibles:</h5>
                        <div id="alumnosDisponibles" class="list-group" style="max-height: 300px; overflow-y: auto;">
                            <!-- Alumnos disponibles se cargan aquí -->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5>Equipos en Formación:</h5>
                        <div id="equiposEnFormacion" style="max-height: 300px; overflow-y: auto;">
                            <!-- Equipos en formación se muestran aquí -->
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" onclick="agregarNuevoEquipo()">
                            + Agregar Equipo
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="confirmarFormacionManual()">Confirmar Equipos</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>


            </div>
        </div>
    </div>
</div>

<!-- Contenedor de mensajes -->
<div id="msgFlash"></div>

<!-- Flatpickr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>

<script>
    const base_url = "<?= base_url(); ?>";

    // Variables globales
    let alumnosDelGrupo = [];
    let equiposFormados = [];
    let equiposConfirmadosFlag = false;
    let grupoSeleccionadoActual = null;
    let equiposExistentesData = [];

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

        // Event listeners
        document.getElementById("cuatrimestre").addEventListener("change", get_materias);
        document.getElementById("materias").addEventListener("change", function() {
            const modalidad = document.getElementById("modalidad").value;
            get_grupos(modalidad == "2");

            get_instrumentos();
        });
        document.getElementById("parcial").addEventListener("change", get_instrumentos);
        document.getElementById("modalidad").addEventListener("change", manejarCambioModalidad);
    });

    function get_materias() {
        const cuatrimestre = document.getElementById("cuatrimestre").value;
        $.post(base_url + "sysmater/docente/nueva_actividad/cargar_materias_del_docente", {
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
        if (!materias) {
            document.getElementById("gruposContainer").innerHTML = '<p class="text-muted">Seleccione una materia para ver los grupos disponibles</p>';
            return;
        }

        $.post(base_url + "sysmater/docente/nueva_actividad/listar_grupos", {
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
                // Modo radio buttons para equipos
                response.forEach(grupo => {
                    container.innerHTML += `
                        <div class="grupo-fecha-container mb-3">
                            <div class="form-check form-check-inline me-3">
                                <input class="form-check-input" type="radio" name="grupo_unico" value="${grupo.id_grupo}" id="grupo-${grupo.vchGrupo}" onchange="cargarEquiposPorGrupo(this.value, '${grupo.vchGrupo}')">
                                <label class="form-check-label" for="grupo-${grupo.vchGrupo}">${grupo.vchGrupo}</label>
                            </div>
                            <div class="form-group mt-1">
                                <label for="fecha-grupo-${grupo.id_grupo}">Fecha y Hora de Evaluación para grupo ${grupo.vchGrupo}:</label>
                                <input type="text" class="form-control fecha-grupo" id="fecha-grupo-${grupo.id_grupo}" data-idgrupo="${grupo.id_grupo}" placeholder="Seleccionar fecha" readonly>
                            </div>
                        </div>
                    `;
                });
            } else {
                // Modo checkboxes para individual
                response.forEach(grupo => {
                    container.innerHTML += `
                        <div class="grupo-fecha-container mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="grupos" value="${grupo.id_grupo}" id="grupo-${grupo.vchGrupo}">
                                <label class="form-check-label me-3" for="grupo-${grupo.vchGrupo}">${grupo.vchGrupo}</label>
                            </div>
                            <div class="form-group mt-1">
                                <label for="fecha-grupo-${grupo.id_grupo}">Fecha y Hora de Evaluación para grupo ${grupo.vchGrupo}:</label>
                                <input type="text" class="form-control fecha-grupo" id="fecha-grupo-${grupo.id_grupo}" data-idgrupo="${grupo.id_grupo}" placeholder="Seleccionar fecha" readonly>
                            </div>
                        </div>
                    `;
                });
            }

            // Aplicar Flatpickr a los nuevos campos de fecha
            inicializarFlatpickrParaFechas();
        });
    }

    function inicializarFlatpickrParaFechas() {
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

    // FUNCIÓN FALTANTE: cargarEquiposPorGrupo
    function cargarEquiposPorGrupo(idGrupo, nombreGrupo) {
        grupoSeleccionadoActual = idGrupo;
       
        // Cargar alumnos del grupo
        cargarAlumnosDelGrupo(idGrupo);

        // Verificar si hay equipos existentes
        verificarEquiposExistentes(idGrupo);
    }

    function get_instrumentos() {
        const vchClvMateria = document.getElementById("materias").value;
        const parcial = document.getElementById("parcial").value;

        const instrumentos = $('#instrumentos');
        instrumentos.empty().append('<option value="">Cargando...</option>');

        if (!vchClvMateria || !parcial) {
            instrumentos.empty().append('<option value="">Seleccione una hoja</option>');
            return;
        }

        $.post(base_url + "sysmater/docente/nueva_actividad/listar_instrumentos", {
            vchClvMateria,
            parcial
        }, function(data) {
            instrumentos.empty().append('<option value="">Seleccione una hoja</option>');
            const response = JSON.parse(data);

            console.log(response)
            if (response.length === 0) {
                instrumentos.append('<option value="">⚠ No hay instrumentos disponibles</option>');
            } else {
                response.forEach(inst =>
                    instrumentos.append(`<option value="${inst.id_instrumento}">${inst.nombre}</option>`)
                );
            }
        }).fail(function() {
            instrumentos.empty().append('<option value="">⚠ Error al cargar instrumentos</option>');
        });
    }


    function manejarCambioModalidad() {
        const modalidad = document.getElementById("modalidad").value;
        const equiposContainer = document.getElementById("equiposContainer");

        if (modalidad == "2") { // Por equipos
            equiposContainer.style.display = "block";
            resetearEstadoEquipos();
            // Recargar grupos para mostrar radio buttons
            get_grupos(true);
        } else {
            equiposContainer.style.display = "none";
            equiposFormados = [];
            equiposConfirmadosFlag = false;
            grupoSeleccionadoActual = null;
            // Recargar grupos para mostrar checkboxes
            get_grupos(false);
        }
    }

    // **GESTIÓN DE EQUIPOS**
    function cargarAlumnosDelGrupo(idGrupo) {
        const claveMateria = document.getElementById("materias").value;

        $.post(base_url + "sysmater/docente/nueva_actividad/obtener_alumnos_grupo", {
            id_grupo: idGrupo,
            materia: claveMateria
        }, function(data) {
            try {
                const response = JSON.parse(data);

                console.log("Alumnos", response)

                alumnosDelGrupo = response;

                document.getElementById("controlesEquipos").style.display = "flex";
             
            } catch (error) {
                mostrarMensaje("Error al cargar los alumnos del grupo", "error");
            }
        }).fail(function() {
            mostrarMensaje("Error al cargar los alumnos del grupo", "error");
        });
    }

    function verificarEquiposExistentes(idGrupo) {
        const materia = document.getElementById('materias').value;

        $.post(base_url + "sysmater/docente/nueva_actividad/listar_equipos", {
            id_grupo: idGrupo,
            materia: materia
        }, function(data) {
            const response = JSON.parse(data);

            console.log("Equipos existentes", response)
            const btnExistentes = document.getElementById("btnEquiposExistentes");

            if (response.length > 0) {
                equiposExistentesData = response;


                btnExistentes.style.display = "inline-block";
            } else {
                btnExistentes.style.display = "none";
            }
        });
    }

    function simularEquiposAleatorios() {
        if (!grupoSeleccionadoActual || alumnosDelGrupo.length === 0) {
            mostrarMensaje("Primero seleccione un grupo y asegúrese de que tenga alumnos", "error");
            return;
        }

        const cantidadEquipos = parseInt(document.getElementById("cantidadEquipos").value);
        if (cantidadEquipos < 2 || cantidadEquipos > alumnosDelGrupo.length) {
            mostrarMensaje("Cantidad de equipos inválida", "error");
            return;
        }

        //mostrarMensaje("Simulando equipos aleatorios...", "info");

        // Aleatorizar alumnos
        const alumnosAleatorios = [...alumnosDelGrupo].sort(() => Math.random() - 0.5);

        // Calcular distribución
        const alumnosPorEquipo = Math.floor(alumnosAleatorios.length / cantidadEquipos);
        const alumnosSobrantes = alumnosAleatorios.length % cantidadEquipos;

        // Formar equipos
        equiposFormados = [];
        let indiceAlumno = 0;

        for (let i = 1; i <= cantidadEquipos; i++) {
            const integrantesEnEsteEquipo = alumnosPorEquipo + (i <= alumnosSobrantes ? 1 : 0);
            const integrantes = [];

            for (let j = 0; j < integrantesEnEsteEquipo; j++) {
                integrantes.push(alumnosAleatorios[indiceAlumno]);
                indiceAlumno++;
            }

            equiposFormados.push({
                nombre: `Equipo ${i}`,
                integrantes,
                esNuevo: true
            });
        }

        mostrarPreviewEquipos();
        //mostrarMensaje("Equipos simulados correctamente", "success");
    }

    function mostrarFormacionManual() {
        if (!grupoSeleccionadoActual || alumnosDelGrupo.length === 0) {
            mostrarMensaje("Primero seleccione un grupo", "error");
            return;
        }

        cargarModalFormacionManual();
        $('#modalFormacionManual').modal('show');
    }

    function cargarEquiposExistentesBtn() {
        if (equiposExistentesData.length > 0) {
            cargarEquiposExistentes(equiposExistentesData);
        } else {
            mostrarMensaje("No hay equipos existentes disponibles", "error");
        }
    }

    function cargarEquiposExistentes(equiposExistentes) {
        equiposFormados = equiposExistentes.map(equipo => ({
            nombre: equipo.nombre_equipo || `Equipo ${equipo.id_equipo}`,
            integrantes: equipo.integrantes || [], // Asegúrate que el backend devuelva este array
            esNuevo: false,
            //id: equipo.id_equipo
        }));

        console.log("Formando equipos anteriores", equiposFormados)

        mostrarPreviewEquipos();
        //mostrarMensaje("Equipos existentes cargados", "success");
    }


    function mostrarPreviewEquipos() {
        const preview = document.getElementById("equiposPreview");
        const content = document.getElementById("equiposContent");

        content.innerHTML = '';
        equiposFormados.forEach((equipo, index) => {
            const integrantesHtml = equipo.integrantes.map(integrante => {
                const nombre = integrante.vchNombre || integrante.nombre || '';
                const matricula = integrante.vchMatricula || integrante.matricula || '';
                return `<span class="integrante-item">${matricula} - ${nombre}</span>`;
            }).join('');

            content.innerHTML += `
                <div class="equipo-card">
                    <div class="equipo-header">
                        ${equipo.nombre} 
                        <span class="badge">${equipo.integrantes.length} integrantes</span>
                        ${equipo.esNuevo ? '<span class="label label-success">Nuevo</span>' : '<span class="label label-info">Existente</span>'}
                    </div>
                    <div class="integrantes-container">
                        ${integrantesHtml}
                    </div>
                </div>
            `;
        });

        preview.style.display = "block";
        document.getElementById("controlesEquipos").style.display = "none";
    }

    function confirmarEquipos() {
        if (equiposFormados.length === 0) {
            mostrarMensaje("No hay equipos para confirmar", "error");
            return;
        }

        equiposConfirmadosFlag = true;

        // Mover a equipos confirmados
        const confirmados = document.getElementById("equiposConfirmados");
        const confirmadosContent = document.getElementById("equiposConfirmadosContent");

        confirmadosContent.innerHTML = document.getElementById("equiposContent").innerHTML;
        confirmados.style.display = "block";

        // Ocultar preview
        document.getElementById("equiposPreview").style.display = "none";

        //mostrarMensaje("Equipos confirmados correctamente", "success");
    }

    function cancelarFormacionEquipos() {
        equiposFormados = [];
        resetearEstadoEquipos();
        reconfigurarEquipos();
        //mostrarMensaje("Formación de equipos cancelada", "info");
    }

    function reconfigurarEquipos() {
        equiposConfirmadosFlag = false;
        document.getElementById("equiposConfirmados").style.display = "none";
        document.getElementById("controlesEquipos").style.display = "flex";
        equiposFormados = [];
    }

    function resetearEstadoEquipos() {
        document.getElementById("equiposPreview").style.display = "none";
        document.getElementById("equiposConfirmados").style.display = "none";
        document.getElementById("controlesEquipos").style.display = "none";
        equiposFormados = [];
        equiposConfirmadosFlag = false;
    }

    // **FORMACIÓN MANUAL DE EQUIPOS**
    let equiposEnFormacionManual = [];
    let equipoActualManual = 1;

    function cargarModalFormacionManual() {
        const alumnosContainer = document.getElementById("alumnosDisponibles");
        const equiposContainer = document.getElementById("equiposEnFormacion");

        // Reiniciar variables
        equiposEnFormacionManual = [];
        equipoActualManual = 1;

        // Cargar alumnos disponibles
        alumnosContainer.innerHTML = '';
        alumnosDelGrupo.forEach(alumno => {
            const nombreCompleto = `${alumno.vchNombre || ''} ${alumno.vchAPaterno || ''}`.trim();
            alumnosContainer.innerHTML += `
                <div class="list-group-item alumno-disponible" data-matricula="${alumno.vchMatricula}">
                    <strong>${alumno.vchMatricula}</strong> - ${nombreCompleto}
                    <button class="btn btn-xs btn-primary pull-right" onclick="asignarAlumnoAEquipo('${alumno.vchMatricula}')">
                        Asignar
                    </button>
                </div>
            `;
        });

        // Inicializar con un equipo vacío
        equiposContainer.innerHTML = `
            <div class="equipo-en-formacion" data-equipo="1">
                <h6>Equipo 1 <span class="badge badge-integrantes">0 integrantes</span></h6>
                <div class="equipo-integrantes" data-equipo-id="1">
                    <p class="text-muted">Seleccione alumnos para este equipo</p>
                </div>
            </div>
        `;

        equiposEnFormacionManual.push({
            id: 1,
            nombre: "Equipo 1",
            integrantes: []
        });
    }

    function asignarAlumnoAEquipo(matricula) {
        // Encontrar el alumno
        const alumno = alumnosDelGrupo.find(a => a.vchMatricula === matricula);
        if (!alumno) return;

        // Verificar si ya está asignado a algún equipo
        const yaAsignado = equiposEnFormacionManual.some(equipo =>
            equipo.integrantes.some(integrante => integrante.vchMatricula === matricula)
        );

        if (yaAsignado) {
            mostrarMensaje("El alumno ya está asignado a un equipo", "error");
            return;
        }

        // Asignar al último equipo (o crear uno nuevo si el último está lleno)
        let equipoDestino = equiposEnFormacionManual[equiposEnFormacionManual.length - 1];

        // Si no hay equipos o el último tiene más de 6 integrantes, crear uno nuevo
        if (!equipoDestino || equipoDestino.integrantes.length >= 6) {
            equipoActualManual++;
            equipoDestino = {
                id: equipoActualManual,
                nombre: `Equipo ${equipoActualManual}`,
                integrantes: []
            };
            equiposEnFormacionManual.push(equipoDestino);

            // Agregar el nuevo equipo al DOM
            const equiposContainer = document.getElementById("equiposEnFormacion");
            equiposContainer.innerHTML += `
                <div class="equipo-en-formacion" data-equipo="${equipoActualManual}">
                    <h6>Equipo ${equipoActualManual} <span class="badge badge-integrantes">0 integrantes</span></h6>
                    <div class="equipo-integrantes" data-equipo-id="${equipoActualManual}">
                        <p class="text-muted">Seleccione alumnos para este equipo</p>
                    </div>
                </div>
            `;
        }

        // Agregar al equipo
        equipoDestino.integrantes.push(alumno);

        // Actualizar la interfaz
        actualizarInterfazEquipoManual(equipoDestino.id);

        // Ocultar alumno de la lista disponible
        const alumnoElement = document.querySelector(`[data-matricula="${matricula}"]`);
        if (alumnoElement) {
            alumnoElement.style.display = 'none';
        }

        mostrarMensaje(`${alumno.vchMatricula} asignado al ${equipoDestino.nombre}`, "success");
    }

    function actualizarInterfazEquipoManual(equipoId) {
        const equipo = equiposEnFormacionManual.find(e => e.id === equipoId);
        if (!equipo) return;

        const container = document.querySelector(`[data-equipo-id="${equipoId}"]`);
        const badge = document.querySelector(`[data-equipo="${equipoId}"] .badge-integrantes`);

        if (container && badge) {
            badge.textContent = `${equipo.integrantes.length} integrantes`;

            if (equipo.integrantes.length > 0) {
                const integrantesHtml = equipo.integrantes.map(integrante => {
                    const nombre = `${integrante.vchNombre || ''} ${integrante.vchAPaterno || ''}`.trim();
                    return `
                        <div class="integrante-manual" data-matricula="${integrante.vchMatricula}">
                            <span>${integrante.vchMatricula} - ${nombre}</span>
                            <button class="btn btn-xs btn-danger" onclick="removerIntegranteManual('${integrante.vchMatricula}', ${equipoId})">
                                ×
                            </button>
                        </div>
                    `;
                }).join('');
                container.innerHTML = integrantesHtml;
            } else {
                container.innerHTML = '<p class="text-muted">Seleccione alumnos para este equipo</p>';
            }
        }
    }

    function removerIntegranteManual(matricula, equipoId) {
        const equipo = equiposEnFormacionManual.find(e => e.id === equipoId);
        if (!equipo) return;

        // Remover del equipo
        equipo.integrantes = equipo.integrantes.filter(i => i.vchMatricula !== matricula);

        // Actualizar interfaz del equipo
        actualizarInterfazEquipoManual(equipoId);

        // Mostrar nuevamente en la lista de disponibles
        const alumnoElement = document.querySelector(`[data-matricula="${matricula}"]`);
        if (alumnoElement) {
            alumnoElement.style.display = 'block';
        }

        mostrarMensaje("Integrante removido del equipo", "info");
    }

    function agregarNuevoEquipo() {
        equipoActualManual++;
        const nuevoEquipo = {
            id: equipoActualManual,
            nombre: `Equipo ${equipoActualManual}`,
            integrantes: []
        };

        equiposEnFormacionManual.push(nuevoEquipo);

        const equiposContainer = document.getElementById("equiposEnFormacion");
        equiposContainer.innerHTML += `
            <div class="equipo-en-formacion" data-equipo="${equipoActualManual}">
                <h6>Equipo ${equipoActualManual} <span class="badge badge-integrantes">0 integrantes</span></h6>
                <div class="equipo-integrantes" data-equipo-id="${equipoActualManual}">
                    <p class="text-muted">Seleccione alumnos para este equipo</p>
                </div>
            </div>
        `;
    }

    function confirmarFormacionManual() {
        // Validar que todos los equipos tengan al menos un integrante
        const equiposValidos = equiposEnFormacionManual.filter(e => e.integrantes.length > 0);

        if (equiposValidos.length === 0) {
            mostrarMensaje("Debe formar al menos un equipo con integrantes", "error");
            return;
        }

        // Verificar equipos con solo un integrante
        const equiposConUnSoloIntegrante = equiposValidos.filter(e => e.integrantes.length === 1);
        if (equiposConUnSoloIntegrante.length > 0) {
            const confirmacion = confirm(`Hay ${equiposConUnSoloIntegrante.length} equipo(s) con solo un integrante. ¿Desea continuar?`);
            if (!confirmacion) return;
        }

        // Convertir a formato de equiposFormados
        equiposFormados = equiposValidos.map(equipo => ({
            nombre: equipo.nombre,
            integrantes: equipo.integrantes,
            esNuevo: true
        }));

        $('#modalFormacionManual').modal('hide');
        mostrarPreviewEquipos();
        mostrarMensaje(`${equiposFormados.length} equipos formados manualmente`, "success");
    }

    // **FUNCIÓN PRINCIPAL DE ASIGNACIÓN**
    function asignarActividad(event) {
        event.preventDefault();

        // Validar campos básicos
        const titulo = document.getElementById("nombre_actividad").value.trim();
        const descripcion = document.getElementById("descripcion").value.trim();
        const vchClvMateria = document.getElementById("materias").value;
        const id_instrumento = document.getElementById("instrumentos").value;
        const id_valor_componente = document.getElementById("componente").value;
        const id_modalidad = document.getElementById("modalidad").value;

        if (!titulo || !descripcion || !vchClvMateria || !id_instrumento || !id_modalidad) {
            mostrarMensaje("Por favor, complete todos los campos obligatorios.", "error");
            return;
        }

        // Obtener fechas y grupos
        const fechas_por_grupo = {};
        document.querySelectorAll('.fecha-grupo').forEach(input => {
            fechas_por_grupo[input.dataset.idgrupo] = input.value;
        });

        let grupos = [];
        if (id_modalidad == "2") {
            // Modalidad por equipos - un solo grupo
            let grupoSeleccionado = document.querySelector('input[name="grupo_unico"]:checked');
            if (grupoSeleccionado) {
                grupos = [grupoSeleccionado.value];
            }

            // Validar que se hayan confirmado equipos
            if (!equiposConfirmadosFlag || equiposFormados.length === 0) {
                mostrarMensaje("Debe formar y confirmar los equipos antes de continuar.", "error");
                return;
            }
        } else {
            // Modalidad individual - múltiples grupos
            grupos = Array.from(document.querySelectorAll('input[name="grupos"]:checked')).map(cb => cb.value);
        }

        // Validar que se hayan seleccionado grupos
        if (grupos.length === 0) {
            mostrarMensaje("Debe seleccionar al menos un grupo.", "error");
            return;
        }

        // Validar fechas y horas
        if (!validarFechasYHoras(grupos, fechas_por_grupo, id_modalidad)) {
            return;
        }

        // Preparar datos para envío
        const datos = {
            titulo,
            descripcion,
            vchClvMateria,
            id_instrumento,
            id_modalidad,
            id_valor_componente,
            grupos,
            equiposNuevos: equiposFormados.filter(e => e.esNuevo),
            equiposExistentes: equiposFormados.filter(e => !e.esNuevo),
            fechas_por_grupo
        };

        console.log("Enviando datos:", datos)

        // Enviar datos
        $.ajax({
            url: base_url + "sysmater/docente/nueva_actividad/guardar_actividad",
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify(datos),
            success: function(response) {
                mostrarMensaje("Actividad asignada correctamente", 'success');
                setTimeout(() => window.location.reload(), 3000);
            },
            error: function(xhr) {
                mostrarMensaje("Error al asignar actividad", 'error');
                console.error(xhr.responseText);
            }
        });
    }

    // **COMPONENTES E INSTRUMENTOS**
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
                        mostrarMensaje('Error al cargar los componentes.', 'error');
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

            $.post(base_url + "sysmater/docente/nueva_actividad/obtener_detalles_instrumento", {
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
            })
        });
    });

    // **VALIDACIONES**
    function validarFechasYHoras(grupos, fechas_por_grupo, id_modalidad) {
        for (let id_grupo of grupos) {
            const fecha = fechas_por_grupo[id_grupo];

            if (!fecha || fecha.trim() === '') {
                mostrarMensaje(`Por favor, seleccione una fecha y hora para el grupo ${obtenerNombreGrupo(id_grupo)}.`, "error");
                return false;
            }

            if (!validarFormatoFecha(fecha)) {
                mostrarMensaje(`La fecha "${fecha}" no tiene un formato válido. Use el formato: YYYY-MM-DD HH:MM`, "error");
                return false;
            }

            const fechaSeleccionada = new Date(fecha);
            const fechaActual = new Date();

            if (fechaSeleccionada <= fechaActual) {
                mostrarMensaje(`La fecha para el grupo ${obtenerNombreGrupo(id_grupo)} debe ser posterior a la fecha actual.`, "error");
                return false;
            }

            const hora = fechaSeleccionada.getHours();
            if (hora < 7 || hora > 22) {
                mostrarMensaje(`La hora seleccionada para el grupo ${obtenerNombreGrupo(id_grupo)} debe estar en un horario válido (7:00 - 22:00).`, "error");
                return false;
            }
        }
        return true;
    }

    function validarFormatoFecha(fecha) {
        const formatoFecha = /^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/;
        if (!formatoFecha.test(fecha)) return false;

        const fechaObj = new Date(fecha);
        if (isNaN(fechaObj.getTime())) return false;

        const partes = fecha.split(' ');
        const [año, mes, dia] = partes[0].split('-');
        const [hora, minuto] = partes[1].split(':');

        const añoNum = parseInt(año);
        const mesNum = parseInt(mes);
        const diaNum = parseInt(dia);
        const horaNum = parseInt(hora);
        const minutoNum = parseInt(minuto);

        if (añoNum < 2024 || añoNum > 2030) return false;
        if (mesNum < 1 || mesNum > 12) return false;
        if (diaNum < 1 || diaNum > 31) return false;
        if (horaNum < 0 || horaNum > 23) return false;
        if (minutoNum < 0 || minutoNum > 59) return false;

        return true;
    }

    function obtenerNombreGrupo(id_grupo) {
        const grupoElement = document.querySelector(`input[value="${id_grupo}"]`);
        if (grupoElement) {
            const label = document.querySelector(`label[for="${grupoElement.id}"]`);
            return label ? label.textContent : `Grupo ${id_grupo}`;
        }
        return `Grupo ${id_grupo}`;
    }

    // **FUNCIÓN DE MENSAJES**
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
</script>