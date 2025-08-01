<style>
:root {
    --primary-color: #4CAF50;
    --secondary-color: #F0FAF2;
    --hover-color: #3e8e41;
    --border-color: #dcdcdc;
    --text-color: #333;
    --muted-color: #777;
}
.exam-card.error {
    border-left: 6px solid #d32f2f !important;
    box-shadow: 0 3px 8px rgba(211, 47, 47, 0.5) !important;
}


.container-form {
    max-width: 1200px;
    margin: 30px auto;
    background: white;
    border-radius: 10px;
    padding: 40px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
}

.header {
    text-align: center;
    margin-bottom: 30px;
}


.exam-card {
    background: #fff;
    border-left: 6px solid var(--primary-color);
    border-radius: 10px;
    padding: 15px 20px;
    margin-bottom: 15px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
    overflow: hidden;
}

.exam-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    padding: 10px 0;
}

.exam-card-header h4 {
    margin: 0;
    font-size: 1.3rem;
    color: var(--primary-color);
}

.exam-card-content {
    max-height: 0;
    opacity: 0;
    overflow: hidden;
    transition: all 0.3s ease;
}

.exam-card.active .exam-card-content {
    max-height: 5000px;
    opacity: 1;
    padding-top: 15px;
    border-top: 1px solid #eee;
}

.toggle-icon {
    transition: transform 0.3s ease;
}

.exam-card.active .toggle-icon {
    transform: rotate(180deg);
}

.options-panel {
    margin-top: 20px;
    background-color: var(--secondary-color);
    border-radius: 8px;
    padding: 20px;
}

.options-panel .panel-heading {
    background-color: var(--primary-color);
    color: white;
    font-weight: bold;
    padding: 10px 15px;
    border-radius: 6px 6px 0 0;
    margin: -20px -20px 20px -20px;
}

.option-row {
    display: flex;
    flex-wrap: wrap;
    background: #fff;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

.option-label {
    flex: 1 1 120px;
    color: var(--primary-color);
    font-weight: 600;
}

.option-content {
    flex: 3;
    padding-left: 20px;
}

.option-check {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
}

.image-preview {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border: 1px solid var(--border-color);
    transition: transform 0.5s ease;
    cursor: pointer;
    position: relative;
    z-index: 1;
    background-color:white;
}

.image-preview:hover {
    object-fit: contain; 
    transform: scale(4) translateX(-40%);
    z-index: 1000;
    box-shadow: 0 6px 20px rgba(0,0,0,0.3);
}
.form-control {
        border: 1px solid rgb(184, 184, 184);
        border-radius: 4px;
    }
    
    .form-control:focus {
        box-shadow: 0 0 0 0.3rem rgba(69, 151, 213, 0.25);
    }

.option-type-selector {
    margin-top: 8px;
    display: flex;
    gap: 8px;
}

.option-type-btn {
    background-color: white;
    border: 1px solid #ccc;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 0.9rem;
    cursor: pointer;
}

.option-type-btn.active {
    background-color: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.time-inputs {
    display: flex;
    gap: 15px;
    margin-top: 15px;
}

.time-inputs .form-group {
    flex: 1;
}

.help-block {
    color: #d32f2f;
    font-size: 0.9rem;
    margin-top: 5px;
}

.has-error .form-control {
    border-color: #d32f2f;
}

.mt-4 { margin-top: 1.5rem; }
.mt-3 { margin-top: 1rem; }
.mt-2 { margin-top: 0.5rem; }
.mb-3 { margin-bottom: 1rem; }

.flex {
    display: flex;
}
.justify-content-between {
    justify-content: space-between;
}
.align-items-center {
    align-items: center;
}
.gap-3 {
    gap: 1rem;
}
.text-muted {
    color: var(--muted-color);
}
.hidden {
    display: none;
}

/* Responsivo */
@media (max-width: 768px) {
    .option-row {
        flex-direction: column;
    }
    .option-content {
        padding-left: 0;
    }
    .option-check {
        justify-content: flex-start;
        margin-top: 10px;
    }
    .time-inputs {
        flex-direction: column;
    }
}

</style>

<div class="container-form">
    <div class="header">
        <h2><?php echo $examen->row()->nvch_Titulo; ?></h2>
        <h3 class="text-muted">Crea y asigna reactivos para este exámen</h3>
    </div>

    <?php if (isset($msg)): ?>
        <div class="alert alert-warning alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Advertencia</strong> <?= $msg; ?>
        </div>
    <?php endif ?>

    <?php 
        $attributes = [
            "id" => "form_examen",
            "name" => "form_examen",
            "method" => "POST",
            "enctype" => "multipart/form-data"
        ];
        echo form_open("/sysmater/docente/cargar_reactivos_examen/guardar_reactivos/".$examen->row()->id_examen, $attributes);
    ?>
    
    <input type="hidden" name="numeroReactivos" value="<?php echo $examen->row()->int_reactivos_requeridos; ?>">
    <input type="hidden" name="numeroSeleccionados" value=" <?= $rSelec->rSeleccionados?>">
    <input type="hidden" name="vchClvTrabajador" value="<?= $this->session->userdata('Matricula'); ?>">
    <input type="hidden" name="id_examen" value="<?= $examen->row()->id_examen; ?>">
    <input type="hidden" name="materia" value="<?= $examen->row()->vchClvMateria; ?>">
    <input type="hidden" name="cuatri" value="<?= $examen->row()->vchCuatrimestre; ?>">
    <input type="hidden" name="periodo" value="<?= $examen->row()->periodo; ?>">
    <input type="hidden" name="parcial" value="<?= $examen->row()->parcial; ?>">

    <div id="reactivosContainer"></div>

    <div class="form-group row">
        <div class="col-sm-12 text-center"> 
            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-floppy-saved"></span> Guardar Reactivos</button>
            <a href="<?= site_url('/sysmater/docente/examenes_registrados'); ?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"> </span> Cancelar</a>
    </div>
    </div>

    <?= form_close(); ?>
</div>

<script>
$(document).ready(function() {
    // Solución para el navbar colapsable
    $('.navbar-toggle').click(function() {
        $('.navbar-collapse').toggleClass('in');
    });

    const numeroReactivos = $('input[name="numeroReactivos"]').val();
    const numeroSeleccionados = $('input[name="numeroSeleccionados"]').val();
    const numeroTotal = parseInt(numeroReactivos) - parseInt(numeroSeleccionados);
    const reactivosContainer = $('#reactivosContainer');
    
    renderizarReactivos(numeroTotal);
    setupFormValidation();

    function renderizarReactivos(numero) {
        reactivosContainer.empty();
        
        for (let i = 1; i <= numero; i++) {
            const card = $('<div>').addClass('exam-card').attr('id', `reactivo-${i}`).html(`
                <div class="exam-card-header">
                    <h4 style="font-size:15px;"><b><span class="glyphicon glyphicon-question-sign"></span> Reactivo ${i}</b></h4>
                    <svg class="toggle-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>
                
                <div class="exam-card-content">
                    <div class="mb-3">
                        <label for="txt_base_${i}" class="form-label">Texto base</label>
                        <textarea name="reactivos[${i}][txt_base]" id="txt_base_${i}" class="form-control" rows="3" placeholder="Escribe el planteamiento del reactivo" required></textarea>
                        <span class="help-block hidden">Por favor ingresa el texto base para este reactivo.</span>
                    </div>
                    
                    <div class="mb-3">
                        <label for="imagen_base_${i}" class="form-label">
                            Imagen base (Opcional)
                        </label>
                        <div class="flex align-items-center gap-3">
                            <button type="button" class="btn btn-warning btn-sm" onclick="limpiarImagen(${i})"><span class="glyphicon glyphicon-trash"></span> Limpiar</button>
                            <input type="file" name="reactivos[${i}][imagen_base]" class="form-control imagen-input" id="imagen_base_${i}" data-preview="preview_base_${i}" accept="image/*">
                            <div class="preview-imagen" id="preview_base_${i}"></div>
                        </div>
                    </div>
                    
                   <div class="row">
                    <div class="form-group col-md-4">
                        <label class="control-label">Tiempo (horas):</label>
                        <input type="number" name="reactivos[${i}][int_horas]" class="form-control" min="0" value="0" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">Tiempo (minutos):</label>
                        <input type="number" name="reactivos[${i}][int_minutos]" class="form-control" min="0" max="59" value="5" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">Nivel taxonómico:</label>
                        <select name="reactivos[${i}][id_nivel]" class="form-control" required>
                            <option value="1">1) RECORDAR</option>
                            <option value="2">2) COMPRENDER</option>
                            <option value="3">3) APLICAR</option>
                            <option value="4">4) ANALIZAR</option>
                            <option value="5">5) EVALUAR</option>
                            <option value="6">6) CREAR</option>       
                        </select>
                    </div>
                </div>
                    
                    <div class="options-panel">
                        <div class="panel-heading"><span class="glyphicon glyphicon-star"></span> Opciones de respuesta</div>
                        <p class="alert alert-info "><strong><span class="glyphicon glyphicon-info-sign"></span> Para cada opción, elige si será texto o imagen. Y marca cuál sera la respuesta correcta</strong> </p>
                        
                        ${['A', 'B', 'C', 'D'].map(letra => `
                            <div class="option-row" data-reactivo="${i}" data-option="${letra}">
                                <div class="option-label">
                                    <div>Opción ${letra}</div>
                                    <div class="option-type-selector mt-2">
                                        <button type="button" class="option-type-btn active" data-type="text" data-reactivo="${i}" data-option="${letra}">Texto</button>
                                        <button type="button" class="option-type-btn" data-type="image" data-reactivo="${i}" data-option="${letra}">Imagen</button>
                                    </div>  
                                </div>
                                <div class="option-content">
                                    <div class="option-content-wrapper">
                                        <div class="mb-2 text-option">
                                            <textarea name="reactivos[${i}][opciones][${letra}][texto]" class="form-control" rows="2" placeholder="Texto de la opción"></textarea>
                                            <span class="help-block hidden">El texto de la opción es requerido.</span>
                                        </div>
                                        <div class="mb-2 image-option hidden">
                                            <div class="flex align-items-center gap-3">
                                                <input type="file" name="reactivos[${i}][opciones][${letra}][imagen]" class="form-control imagen-input" id="imagen_${i}_${letra}" data-preview="preview_${i}_${letra}" accept="image/*">
                                                <div class="preview-imagen" id="preview_${i}_${letra}"></div>
                                            </div>
                                            <span class="help-block hidden">La imagen de la opción es requerida.</span>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <textarea name="reactivos[${i}][opciones][${letra}][argumentacion]" class="form-control" rows="2" placeholder="Escribe una argumentación. Puede ser NA"></textarea>
                                        <span class="help-block hidden">La argumentación es requerida.</span>
                                    </div>
                                </div>
                                <div class="option-check">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="reactivos[${i}][correcta]" id="correcta_${i}_${letra}" value="${letra}">
                                        <label class="form-check-label" for="correcta_${i}_${letra}">
                                            Correcta
                                        </label>
                                        <span class="help-block hidden radio-error-message">Debes seleccionar una opción correcta.</span>
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                    
                    <div class="mt-3">
                        <label for="bibliografia_${i}" class="form-label">Bibliografía</label>
                        <input type="text" name="reactivos[${i}][vch_bibliografia]" id="bibliografia_${i}" class="form-control" placeholder="Escribe bibliografia">
                        <span class="help-block hidden">Por favor ingresa la bibliografía.</span>
                    </div> </br>
                    <div class="text-center cerrar-reactivo" style="cursor:pointer;">
                        <svg  style="color:blue;" class="toggle-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                        <p style="color:blue;">Cerrar reactivo ${i}</p>
                    </div>

                </div>
            `);
            
            reactivosContainer.append(card);
            
            // Configurar vista previa de imágenes
            card.find('.imagen-input').on('change', function(e) {
                const previewId = $(this).data('preview');
                const previewDiv = card.find(`#${previewId}`);
                previewDiv.empty();

                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(evt) {
                        const img = $('<img>').attr('src', evt.target.result)
                            .addClass('image-preview');
                        previewDiv.append(img);
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
            
            // Configurar selectores de tipo de opción para cada opción individual
            card.find('.option-type-btn').on('click', function() {
                const reactivoId = $(this).data('reactivo');
                const optionId = $(this).data('option');
                const type = $(this).data('type');
                
                // Actualizar botones activos para esta opción específica
                const optionRow = card.find(`.option-row[data-reactivo="${reactivoId}"][data-option="${optionId}"]`);
                optionRow.find('.option-type-btn').removeClass('active');
                $(this).addClass('active');
                
                // Mostrar/ocultar campos según el tipo seleccionado
                if (type === 'text') {
                    optionRow.find('.text-option').removeClass('hidden');
                    optionRow.find('.image-option').addClass('hidden');
                } else {
                    optionRow.find('.text-option').addClass('hidden');
                    optionRow.find('.image-option').removeClass('hidden');
                }
            });
        }

        // Funcionalidad para expandir/colapsar reactivos (solo uno abierto a la vez)
        $('.exam-card-header').on('click', function() {
            const clickedCard = $(this).closest('.exam-card');

            // Cierra cualquier otra tarjeta que esté abierta
            $('.exam-card').not(clickedCard).removeClass('active');

            // Alterna (abre/cierra) la tarjeta a la que se le hizo clic
            clickedCard.toggleClass('active');
        });        
    }
    
    function setupFormValidation() {
        const form = $('#form_examen');
        
        form.on('submit', function(event) {
            let isValid = true;
            
            // Validar cada campo requerido
           form.find('[required]').each(function () {
            const field = $(this);
            const formGroup = field.closest('.form-group');
            const helpBlock = field.next('.help-block');

            if (!field.val()) {
                formGroup.addClass('has-error');
                helpBlock.removeClass('hidden');
                isValid = false;

                const card = field.closest('.exam-card');
                card.addClass('error') // Marca en rojo y abre
            } else {
                formGroup.removeClass('has-error');
                helpBlock.addClass('hidden');
            }
        });

            
            // Validar radios (opción correcta)
            $('[name^="reactivos["][name$="[correcta]"]').each(function () {
    const radioName = $(this).attr('name');
    const isChecked = $(`[name="${radioName}"]:checked`).length > 0;
    const formGroup = $(this).closest('.form-check');
    const helpBlock = formGroup.find('.radio-error-message');

    if (!isChecked) {
    formGroup.addClass('has-error');
    helpBlock.removeClass('hidden');
    isValid = false;

    const card = $(this).closest('.exam-card');
    card.addClass('error');
} else {
    formGroup.removeClass('has-error');
    helpBlock.addClass('hidden');
}

});

            
            // Validar opciones de texto/imagen según lo seleccionado
        $('.option-row').each(function () {
            const row = $(this);
            const isTextOption = row.find('.option-type-btn.active').data('type') === 'text';
            const card = row.closest('.exam-card');

            if (isTextOption) {
            const textarea = row.find('.text-option textarea');
            if (!textarea.val()) {
                row.find('.text-option .help-block').removeClass('hidden');
                card.addClass('error');
                isValid = false;
            }
        } else {
            const fileInput = row.find('.image-option input[type="file"]');
            if (!fileInput.val()) {
                row.find('.image-option .help-block').removeClass('hidden');
                card.addClass('error');
                isValid = false;
            }
        }

        });

            
            if (!isValid) {
                event.preventDefault();
                event.stopPropagation();
                return false;
            }
            
            return true;
        });
        
        // Validación en tiempo real
    form.on('input', '[required]', function() {
        const field = $(this);
        const formGroup = field.closest('.form-group');
        const helpBlock = field.next('.help-block');
        
        if (field.val()) {
            formGroup.removeClass('has-error');
            helpBlock.addClass('hidden');
            field.closest('.exam-card').removeClass('error');
        }
    });

        
        // Validar radios en tiempo real
        form.on('change', '[name^="reactivos["][name$="[correcta]"]', function() {
    const radioName = $(this).attr('name');
    const isChecked = $(`[name="${radioName}"]:checked`).length > 0;
    const formGroup = $(this).closest('.form-check');
    const helpBlock = formGroup.find('.radio-error-message');
    
    if (isChecked) {
        formGroup.removeClass('has-error');
        helpBlock.addClass('hidden');
        $(this).closest('.exam-card').removeClass('error');
    }
});

    }
});

function limpiarImagen(i) {
    const input = document.getElementById(`imagen_base_${i}`);
    const preview = document.getElementById(`preview_base_${i}`);

    if (input) input.value = '';
    if (preview) preview.innerHTML = '';
}

</script>
