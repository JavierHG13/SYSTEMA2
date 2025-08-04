<style>
    .page-title {
        text-align: center;
        margin-bottom: 40px;
        color: black;
        font-size: 2.2rem;
        font-weight: 700;
        position: relative;
    }

    .page-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;

        border-radius: 2px;
    }

    .form-section {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;

    }

    .form-group {
        margin-bottom: 25px;
    }

    /*..form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #495057;
        font-size: 0.95rem;
    }

    form-control {
        width: 100%;
        padding: 12px 15px;
        font-size: 1rem;
       
        padding: 12px 15px;
        border: 2px solid #e9ecef;
        border-radius: 10px;
     
        transition: all 0.3s ease;
        background: white;
    }*/

    .form-control:focus {
        outline: none;
        border-color: #0c6606ff;
        box-shadow: 0 0 0 3px rgba(110, 253, 146, 0.1);
        transform: translateY(-1px);
    }

    .form-control:hover {
        border-color: #b8c5f2;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .btn-container {
        text-align: center;
        margin-top: 30px;
    }

    .btn-generar {
        background: linear-gradient(45deg, #179f20ff, #12701fff);
        color: white;
        border: none;
        padding: 15px 40px;
        border-radius: 10px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }

    .btn-generar:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .btn-generar:active {
        transform: translateY(-1px);
    }

    .btn-generar:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    .btn-generar i {
        margin-right: 8px;
    }

    /* Loader simplificado y moderno */
    .loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        backdrop-filter: blur(5px);
    }

    .loader-content {
        background: white;
        padding: 40px;
        border-radius: 20px;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        max-width: 350px;
        width: 90%;
    }

    .loader-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 20px;
        position: relative;
    }

    .spinner {
        width: 100%;
        height: 100%;
        border: 4px solid #f0f0f0;
        border-top: 4px solid #667eea;
        border-radius: 50%;
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

    .loader-text {
        color: #2c3e50;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .loader-subtext {
        color: #6c757d;
        font-size: 0.9rem;
    }

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
        to {
            opacity: 0;
            transform: translateX(100%);
        }
    }

    /* Responsive design */
    @media (max-width: 768px) {
        #box {
            margin: 10px;
            padding: 20px;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .page-title {
            font-size: 1.8rem;
        }

        .btn-generar {
            padding: 12px 30px;
            font-size: 1rem;
        }
    }

    /* Efectos adicionales */
    .form-control option {
        padding: 10px;
    }

    .form-section {
        position: relative;
        overflow: hidden;
    }
</style>
<div id="box">
    <h1 class="page-title">
        <i class="fas fa-chart-line"></i>
        Reporte de Actividades
    </h1>

    <form id="formReporte">
        <div class="form-section">
            <div class="form-row">
                <div class="form-group">
                    <label for="cuatrimestre">
                        <i class="fas fa-calendar-alt"></i>
                        Cuatrimestre
                    </label>
                    <select id="cuatrimestre" name="cuatrimestre" class="form-control" required>
                        <option value="">Seleccione un cuatrimestre</option>
                        <?php if (isset($cuatrimestres) && $cuatrimestres->num_rows() > 0): ?>
                            <?php foreach ($cuatrimestres->result() as $cuatri): ?>
                                <option value="<?= $cuatri->vchCuatrimestre ?>"><?= $cuatri->vchNomCuatri ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="">No hay cuatrimestres disponibles</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="materia">
                        <i class="fas fa-book"></i>
                        Materia
                    </label>
                    <select id="materia" name="materia" class="form-control" required>
                        <option value="">Seleccione primero un cuatrimestre</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="grupo">
                        <i class="fas fa-users"></i>
                        Grupo
                    </label>
                    <select id="grupo" name="grupo" class="form-control" required>
                        <option value="">Seleccione una materia primero</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="parcial">
                        <i class="fas fa-tasks"></i>
                        Parcial
                    </label>
                    <select id="parcial" name="parcial" class="form-control" required>
                        <option value="">Seleccione un parcial</option>
                        <option value="1">1er Parcial</option>
                        <option value="2">2do Parcial</option>
                        <option value="3">3er Parcial</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="tipo">
                    <i class="fas fa-file-export"></i>
                    Formato de Exportación
                </label>
                <select name="tipo" id="tipo" class="form-control">
                    <option value="pdf">PDF</option>
                    <option value="excel">Excel</option>
                </select>
            </div>
        </div>

        <div class="btn-container">
            <button type="button" class="btn btn-success" id="btnGenerar" onclick="exportarReporte()">
                <span class="glyphicon glyphicon-ok"></span>
                Generar Reporte
            </button>

        </div>
    </form>
</div>

<!-- Loader simplificado -->
<div class="loader-overlay" id="loaderOverlay">
    <div class="loader-content">
        <div class="loader-icon">
            <div class="spinner"></div>
        </div>
        <div class="loader-text" id="loaderText">Generando reporte...</div>
        <div class="loader-subtext" id="loaderSubtext">Por favor espere</div>
    </div>
</div>

<!-- Contenedor de mensajes -->
<div id="msgFlash"></div>

<script>
    const base_url = "<?= base_url(); ?>";

    document.addEventListener("DOMContentLoaded", function() {
        // Al cambiar cuatrimestre, cargar materias
        document.getElementById("cuatrimestre").addEventListener("change", function() {
            const cuatrimestre = this.value;

            if (cuatrimestre) {
                $.post(base_url + "sysmater/docente/nueva_actividad/cargar_materias_del_docente", {
                    vchCuatrimestre: cuatrimestre
                }, function(data) {
                    const materias = $('#materia');
                    materias.empty().append('<option value="">Seleccione una materia</option>');

                    const response = JSON.parse(data);
                    response.forEach(m => materias.append(`<option value="${m.vchClvMateria}">${m.vchNomMateria}</option>`));
                });
            }

            // Resetear grupo
            $('#grupo').empty().append('<option value="">Seleccione una materia primero</option>');
        });

        // Al cambiar materia, cargar grupos
        document.getElementById("materia").addEventListener("change", function() {
            const materia = this.value;

            if (materia) {
                $.post(base_url + "sysmater/docente/nueva_actividad/listar_grupos", {
                    vchClvMateria: materia
                }, function(data) {
                    const grupos = $('#grupo');
                    grupos.empty().append('<option value="">Seleccione un grupo</option>');

                    const response = JSON.parse(data);
                    response.forEach(g => grupos.append(`<option value="${g.vchGrupo}">${g.vchGrupo}</option>`));
                });
            }
        });
    });

    // Validación de filtros
    function validarFiltros() {
        const cuatri = $("#cuatrimestre").val();
        const materia = $("#materia").val();
        const parcial = $("#parcial").val();
        const grupo = $("#grupo").val();

        if (!cuatri || !materia || !parcial || !grupo) {
            mostrarMensaje("Por favor, complete todos los filtros.", "error");
            return false;
        }
        return true;
    }

    // Función principal para exportar reporte
    function exportarReporte() {
        if (!validarFiltros()) return;

        const formData = new FormData(document.getElementById("formReporte"));
        const tipo = formData.get('tipo');

        // Mostrar loader
        mostrarLoader();

        // Deshabilitar botón
        const btnGenerar = document.getElementById('btnGenerar');
        btnGenerar.disabled = true;
        btnGenerar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando...';

        // Realizar petición AJAX
        fetch(base_url + "sysmater/docente/reporte_actividades/exportar_reporte", {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.blob();
            })
            .then(blob => {
                // Ocultar loader
                ocultarLoader();

                // Crear enlace de descarga
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;

                // Generar nombre de archivo
                const fecha = new Date().toISOString().slice(0, 19).replace(/:/g, '-');
                const extension = tipo === 'excel' ? 'xlsx' : 'pdf';
                a.download = `Reporte_${formData.get('materia')}_${formData.get('grupo')}_P${formData.get('parcial')}_${fecha}.${extension}`;

                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);

                // Mostrar mensaje de éxito
                mostrarMensaje(`¡Reporte ${tipo.toUpperCase()} generado y descargado correctamente!`, "success");
            })
            .catch(error => {
                console.error('Error:', error);
                ocultarLoader();
                mostrarMensaje("No hay datos para generar el reporte.", "error");
            })
            .finally(() => {
                // Rehabilitar botón
                btnGenerar.disabled = false;
                btnGenerar.innerHTML = '<i class="fas fa-download"></i> Generar Reporte';
            });
    }

    // Función para mostrar el loader
    function mostrarLoader() {
        const overlay = document.getElementById('loaderOverlay');
        overlay.style.display = 'flex';
    }

    // Función para ocultar el loader
    function ocultarLoader() {
        document.getElementById('loaderOverlay').style.display = 'none';
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

    // Manejar errores globales de AJAX
    $(document).ajaxError(function(event, xhr, settings, thrownError) {
        ocultarLoader();
        const btnGenerar = document.getElementById('btnGenerar');
        btnGenerar.disabled = false;
        btnGenerar.innerHTML = '<i class="fas fa-download"></i> Generar Reporte';
        mostrarMensaje("Error de conexión. Por favor, verifique su conexión a internet e inténtelo nuevamente.", "error");
    });
</script>