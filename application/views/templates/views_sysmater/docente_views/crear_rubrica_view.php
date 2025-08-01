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


<link rel="stylesheet" href="<?= base_url() ?>assets/css/sys-forms.css">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb bg-light p-2 rounded">
        <li class="breadcrumb-item">
            <a href="javascript:history.back()">
                <span class="glyphicon glyphicon-list-alt"></span> Regresar
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            <span class="glyphicon glyphicon-plus"></span> Crear instrumento
        </li>
    </ol>
</nav>

<div class="container-fluid">
    <!-- Header -->
    <div class="text-center" style="margin-bottom: 30px;">
        <h2><i class="fa fa-file-text-o me-2"></i>Nuevo instrumento de evaluación</h2>
        <p>Configure los criterios y parámetros para el instrumento de evaluación</p>
    </div>

    <!-- Información Básica -->
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-info-circle"></i> Información Básica</h3>
        </div>
        <div class="panel-body">
            <form id="formPlantillaEvaluacion" onsubmit="crearPlantilla(event)">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="nombrePlantilla">Nombre del instrumento</label>
                            <input type="text" id="nombrePlantilla" class="form-control" placeholder="Ejemplo: IE-LCPO01" required>
                            <small class="form-text text-muted">Identificador único del instrumento</small>
                        </div>
                    </div>

                    <input type="hidden" id="valorTotal" class="form-control" min="1" max="10" step="1" value="10" required>

                    <!--<div class="col-md-4">
                        <div class="form-group">
                            <label for="valorTotal">Valor total (puntos)</label>
                            <small class="form-text text-muted">Entre 1 y 10 puntos</small>
                        </div>
                    </div>-->
                </div>
        </div>
    </div>

    <!-- Configuración Académica -->
    <div class="panel panel-warning">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-graduation-cap"></i> Configuración Académica</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="cuatrimestre">Cuatrimestre</label>
                        <select id="cuatrimestre" class="form-control" required>
                            <option value="">Seleccione cuatrimestre</option>
                            <?php if (isset($cuatrimestres) && $cuatrimestres->num_rows() > 0): ?>
                                <?php foreach ($cuatrimestres->result() as $cuatrimestre): ?>
                                    <option value="<?= $cuatrimestre->vchCuatrimestre; ?>"><?= $cuatrimestre->vchNomCuatri; ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">No hay cuatrimestres disponibles</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="vchClvMateria">Materia</label>
                        <select id="vchClvMateria" class="form-control" required>
                            <option value="">Primero seleccione cuatrimestre</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="parcial">Parcial</label>
                        <select id="parcial" class="form-control" required>
                            <option value="">Seleccione parcial</option>
                            <option value="1">1er Parcial</option>
                            <option value="2">2do Parcial</option>
                            <option value="3">3er Parcial</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="idTipoInstrumento">Tipo de instrumento</label>
                        <select id="idTipoInstrumento" class="form-control" required>
                            <option value="">Seleccione tipo</option>
                            <?php foreach ($tipo_de_instrumento->result() as $tipo_instrumento): ?>
                                <option value="<?= $tipo_instrumento->id_tipo_instrumento; ?>"><?= $tipo_instrumento->nombre_tipo; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Criterios de Evaluación -->
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-list-alt"></i> Criterios de Evaluación</h3>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered table-condensed" id="tablaCriterios">
                    <thead style="background-color: #5cb85c; color: white;">
                        <tr>
                            <th class="text-center" style="width: 25%;">
                                <i class="fa fa-tag"></i> Nombre del Criterio
                            </th>
                            <th class="text-center" style="width: 40%;">
                                <i class="fa fa-align-left"></i> Descripción
                            </th>
                            <th class="text-center" style="width: 15%;">
                                <i class="fa fa-star"></i> Puntaje
                            </th>
                            <th class="text-center" style="width: 20%;">
                                <i class="fa fa-cogs"></i> Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody id="criteriosContainer">
                        <tr class="criterio-item">
                            <td>
                                <input type="text" class="form-control criterio-nombre" placeholder="Nombre del criterio" required>
                            </td>
                            <td>
                                <textarea class="form-control criterio-descripcion" placeholder="Descripción detallada del criterio de evaluación" rows="2" required></textarea>
                            </td>
                            <td>
                                <input type="number" class="form-control criterio-puntaje text-center" placeholder="0.0" min="0.1" max="10" step="0.1" required>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm" onclick="eliminarCriterio(this)">
                                    <i class="glyphicon glyphicon-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row" style="margin-top: 15px;">
                <div class="col-md-6">
                    <button type="button" class="btn btn-success" onclick="agregarCriterio()">
                        Agregar Nuevo Criterio
                    </button>
                </div>
                <div class="col-md-6">
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; height: 34px; display: flex; align-items: center; justify-content: space-between;">
                        <span style="color: #007bff; font-weight: bold;">Total Asignado:</span>
                        <span id="totalDisplay" style="color: #007bff; font-weight: bold;">0.0 puntos</span>
                    </div>
                </div>
            </div>

            <input type="hidden" id="totalAsignado" value="0">

            <!-- Botones -->
            <div class="text-center" style="margin-top: 20px;">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="glyphicon glyphicon-floppy-disk"></i> Guardar Instrumento
                </button>
                <a href="javascript:history.back()" class="btn btn-danger btn-lg" style="margin-left: 15px;">
                    <i class="glyphicon glyphicon-remove"></i> Cancelar
                </a>
            </div>

            </form>
        </div>
    </div>
</div>

<!-- Contenedor de mensajes -->
<div id="msgFlash"></div>

<script>
    function actualizarTotal() {
        let total = 0;
        $('.criterio-puntaje').each(function() {
            const val = parseFloat($(this).val());
            if (!isNaN(val)) total += val;
        });
        $('#totalAsignado').val(total.toFixed(1));
        $('#totalDisplay').text(total.toFixed(1) + ' puntos');
        return total;
    }

    $(document).on('input', '.criterio-puntaje', function() {
        const total = actualizarTotal();
        if (total > 10) {
            mostrarMensaje("La suma de los criterios no puede superar los 10 puntos.", "error");
            $(this).val('');
            actualizarTotal();
        }
    });

    function agregarCriterio() {
        const fila = document.createElement('tr');
        fila.classList.add('criterio-item');

        fila.innerHTML = `
        <td>
            <input type="text" class="form-control criterio-nombre" placeholder="Nombre del criterio" required>
        </td>
        <td>
            <textarea class="form-control criterio-descripcion" placeholder="Descripción detallada del criterio de evaluación" rows="2" required></textarea>
        </td>
        <td>
            <input type="number" class="form-control criterio-puntaje text-center" placeholder="0.0" min="0.1" max="10" step="0.1" required>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm" onclick="eliminarCriterio(this)">
                <i class="glyphicon glyphicon-trash"></i> Eliminar
            </button>
        </td>
    `;

        document.getElementById('criteriosContainer').appendChild(fila);
    }

    function eliminarCriterio(boton) {
        boton.closest('.criterio-item').remove();
        actualizarTotal();
    }

    function crearPlantilla(event) {
        event.preventDefault();

        // VALIDACIONES AGREGADAS
        if (!$('#nombrePlantilla').val().trim()) {
            mostrarMensaje("El nombre del instrumento es requerido.", "error");
            $('#nombrePlantilla').focus();
            return;
        }

        if (!$('#cuatrimestre').val()) {
            mostrarMensaje("Debe seleccionar un cuatrimestre.", "error");
            $('#cuatrimestre').focus();
            return;
        }

        if (!$('#vchClvMateria').val()) {
            mostrarMensaje("Debe seleccionar una materia.", "error");
            $('#vchClvMateria').focus();
            return;
        }

        if (!$('#parcial').val()) {
            mostrarMensaje("Debe seleccionar un parcial.", "error");
            $('#parcial').focus();
            return;
        }

        if (!$('#idTipoInstrumento').val()) {
            mostrarMensaje("Debe seleccionar un tipo de instrumento.", "error");
            $('#idTipoInstrumento').focus();
            return;
        }


        // Validar criterios
        let criteriosCompletos = true;
        $('.criterio-item').each(function() {
            const nombre = $(this).find('.criterio-nombre').val().trim();
            const descripcion = $(this).find('.criterio-descripcion').val().trim();
            const puntaje = $(this).find('.criterio-puntaje').val();

            if (!nombre || !descripcion || !puntaje) {
                mostrarMensaje("Todos los criterios deben tener nombre, descripción y puntaje.", "error");
                criteriosCompletos = false;
                return false;
            }
        });

        if (!criteriosCompletos) return;

        const total = actualizarTotal();
        if (total > 10) {
            mostrarMensaje("El total asignado a los criterios no puede ser mayor a 10.", "error");
            return;
        }

        const datos = {
            nombre: $('#nombrePlantilla').val(),
            parcial: $('#parcial').val(),
            valor_total: $('#valorTotal').val(),
            vchClvMateria: $('#vchClvMateria').val(),
            idTipoInstrumento: $('#idTipoInstrumento').val(),
            criterios: []
        };

        $('.criterio-item').each(function() {
            datos.criterios.push({
                nombre: $(this).find('.criterio-nombre').val(),
                descripcion: $(this).find('.criterio-descripcion').val(),
                valor_maximo: $(this).find('.criterio-puntaje').val()
            });
        });

        $.ajax({
            url: base_url + "sysmater/docente/crear_instrumento/guardar_instrumento",
            method: "POST",
            data: JSON.stringify(datos),
            contentType: "application/json",
            success: function(response) {

                if (response.success) {
                    mostrarMensaje(response.message, "success");
                    setTimeout(() => location.reload(), 1500);
                } else {
                    mostrarMensaje(response.message, "error");
                }
            },
            error: function(xhr) {
                //alert("Error del servidor: " + xhr.responseText);
                mostrarMensaje("Error del servidor.", "error");
            }
        });
    }

    $('#cuatrimestre').on('change', function() {
        const cuatrimestre = $(this).val();
        const url = base_url + "sysmater/docente/crear_instrumento/cargar_materias_del_docente";

        $.post(url, {
            vchCuatrimestre: cuatrimestre
        }, function(data) {
            const response = JSON.parse(data);
            const materias = $('#vchClvMateria');
            materias.empty().append('<option value="">Seleccione una materia</option>');
            $.each(response, function(_, materia) {
                materias.append(`<option value="${materia.vchClvMateria}">${materia.vchNomMateria}</option>`);
            });
        });
    });

    $(document).ready(function() {
        actualizarTotal();
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