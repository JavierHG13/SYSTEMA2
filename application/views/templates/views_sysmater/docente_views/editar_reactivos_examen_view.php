
<style>
:root {
    --primary-color: #4CAF50;
    --secondary-color: #F0FAF2;
    --hover-color: #3e8e41;
    --border-color: #dcdcdc;
    --text-color: #333;
    --muted-color: #777;
}

body {
    font-family: var(--font);
    background-color: #f5f7fa;
    color: var(--text-color);
    margin: 0;
    padding: 0;
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

.header h1 {
    color: var(--primary-color);
    font-size: 2.2rem;
}

.header h3 {
    color: var(--muted-color);
    font-weight: 400;
    margin-top: 5px;
}

.exam-card {
    background: #fff;
    border-left: 6px solid var(--primary-color);
    border-radius: 10px;
    padding: 15px 20px;
    margin-bottom: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
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
    max-height: 5000px; /* Ajusta según necesidad */
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

.form-label {
    font-weight: 600;
    color: var(--text-color);
    display: block;
    margin-bottom: 6px;
}

.form-control, .form-select {
    width: 100%;
    padding: 10px 15px;
    font-size: 1.2rem;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    transition: border-color 0.3s ease;
    margin-bottom:6px;
}

.form-control:focus, .form-select:focus {
    box-shadow: 0 0 0 0.3rem rgba(69, 151, 213, 0.25);
}

.btn-primary {
    padding: 12px 25px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-primary {
    background-color: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background-color: var(--hover-color);
}

.btn-danger {
    background-color: #f44336;
    color: white;
}

.btn-danger:hover {
    background-color: #d32f2f;
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
        <h1><?php echo $examen->row()->nvch_Titulo; ?></h1>
        <h3 class="text-muted">Editar reactivos del examen</h3>
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
        echo form_open("/sysmater/docente/editar_reactivos_examen/actualizar_reactivos/".$examen->row()->id_examen, $attributes);
    ?>
    
    <input type="hidden" name="numeroReactivos" value="<?php echo count($reactivos); ?>">
    <input type="hidden" name="vchClvTrabajador" value="<?= $this->session->userdata('Matricula'); ?>">
    <input type="hidden" name="id_examen" value="<?= $examen->row()->id_examen; ?>">
    <input type="hidden" name="materia" value="<?= $examen->row()->vchClvMateria; ?>">
    <input type="hidden" name="cuatri" value="<?= $examen->row()->vchCuatrimestre; ?>">
    <input type="hidden" name="periodo" value="<?= $examen->row()->periodo; ?>">
    <input type="hidden" name="parcial" value="<?= $examen->row()->parcial; ?>">

    <div id="reactivosContainer">
        <?php foreach ($reactivos as $i => $reactivo): ?>
            <div class="exam-card" id="reactivo-<?= $i+1 ?>">
                <div class="exam-card-header">
                    <h4 style="font-size:15px;"> <span class="glyphicon glyphicon-question-sign"></span> 
                       <b> Reactivo <?= $i+1 ?>: <?= htmlspecialchars($reactivo->txt_base) ?></b>
                    </h4>
                    <svg class="toggle-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>
                
                <div class="exam-card-content">
                    <div class="mb-3">
                        <label for="txt_base_<?= $i+1 ?>" class="form-label">Texto base</label>
                        <textarea name="reactivos[<?= $i+1 ?>][txt_base]" id="txt_base_<?= $i+1 ?>" class="form-control" rows="3" required><?= htmlspecialchars($reactivo->txt_base) ?></textarea>
                        <span class="help-block hidden">Por favor ingresa el texto base para este reactivo.</span>
                    </div>
                    
                   <div class="mb-3">
                    <label for="imagen_base_<?= $i+1 ?>" class="form-label">Imagen base <span class="optional-badge">(Opcional)</span></label>
                    <div class="flex align-items-center gap-3">
                        <input type="file" name="reactivos[<?= $i+1 ?>][imagen_base]" class="form-control imagen-input" id="imagen_base_<?= $i+1 ?>" data-preview="preview_base_<?= $i+1 ?>" accept="image/*">
                        
                        <div class="preview-imagen" id="preview_base_<?= $i+1 ?>">
                            <?php if(!empty(trim($reactivo->path_imagen_base))): // <-- Condición mejorada ?>
                                <img src="<?= base_url('uploads/'.trim($reactivo->path_imagen_base)) ?>" class="image-preview">
                                <input type="hidden" name="reactivos[<?= $i+1 ?>][imagen_base_actual]" value="<?= trim($reactivo->path_imagen_base) ?>">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                    
                     <div class="row">
                        <div class="form-group col-md-4">
                            <label class="form-label">Tiempo (horas)</label>
                            <input type="number" name="reactivos[<?= $i+1 ?>][int_horas]" class="form-control" min="0" value="<?= $reactivo->int_horas ?>" required>
                            <span class="help-block hidden">Ingresa un valor válido para horas (0 o más).</span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Tiempo (minutos)</label>
                            <input type="number" name="reactivos[<?= $i+1 ?>][int_minutos]" class="form-control" min="0" max="59" value="<?= $reactivo->int_minutos ?>" required>
                            <span class="help-block hidden">Ingresa un valor entre 0 y 59 para minutos.</span>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Nivel de dificultad</label>
                            <select name="reactivos[<?= $i+1 ?>][id_nivel]" class="form-control form-select" required>
                                <option value="1" <?= $reactivo->id_nivel == 1 ? 'selected' : '' ?>>1) RECORDAR</option>
                                <option value="2" <?= $reactivo->id_nivel == 2 ? 'selected' : '' ?>>2) COMPRENDER</option>
                                <option value="3" <?= $reactivo->id_nivel == 3 ? 'selected' : '' ?>>3) APLICAR</option>
                                <option value="4" <?= $reactivo->id_nivel == 4 ? 'selected' : '' ?>>4) ANALIZAR</option>
                                <option value="5" <?= $reactivo->id_nivel == 5 ? 'selected' : '' ?>>5) EVALUAR</option>
                                <option value="6" <?= $reactivo->id_nivel == 6 ? 'selected' : '' ?>>6) CREAR</option>       
                            </select>
                            <span class="help-block hidden">Por favor selecciona un nivel de dificultad.</span>
                        </div>
                    </div>
                    
                    <div class="options-panel">
                        <div class="panel-heading">Opciones de respuesta</div>
                        <p class="alert alert-danger "><strong><span class="glyphicon glyphicon-info-sign"></span> Para cada opción, elige si será texto o imagen. (Si cambia entre tipo imagen/texto se limpiará el campo y tendrá que volver a redactar el texto o cargar la imagen correspondiente. Si no esta seguro de los cambios, de al botón de cancelar al final del formulario.)</strong> </p>
                        
                        <?php foreach (['A', 'B', 'C', 'D'] as $letra): 
                            $opcionText = $reactivo->{"nvch_opcion$letra"};
                            $opcionImg = $reactivo->{"path_imagen$letra"};
                            $argumentacion = $reactivo->{"nvch_argumenta$letra"};
                            $isCorrect = $reactivo->chr_correcto == $letra;
                            
                            // --- CAMBIO 1: Condición más robusta para detectar la imagen ---
                            $hasImage = !empty(trim($opcionImg)); 
                        ?>
                            <div class="option-row" data-reactivo="<?= $i+1 ?>" data-option="<?= $letra ?>">
                                <div class="option-label">
                                    <div>Opción <?= $letra ?></div>
                                    <div class="option-type-selector mt-2">
                                        <button type="button" class="option-type-btn <?= !$hasImage ? 'active' : '' ?>" data-type="text" data-reactivo="<?= $i+1 ?>" data-option="<?= $letra ?>">Texto</button>
                                        <button type="button" class="option-type-btn <?= $hasImage ? 'active' : '' ?>" data-type="image" data-reactivo="<?= $i+1 ?>" data-option="<?= $letra ?>">Imagen</button>
                                    </div>
                                </div>
                                <div class="option-content">
                                    <div class="option-content-wrapper">
                                        <div class="mb-2 text-option" style="display: <?= $hasImage ? 'none' : 'block' ?>;">
                                            <textarea name="reactivos[<?= $i+1 ?>][opciones][<?= $letra ?>][texto]" class="form-control" rows="2" placeholder="Texto de la opción" <?= !$hasImage ? 'required' : '' ?>><?= htmlspecialchars($opcionText) ?></textarea>
                                            <span class="help-block hidden">El texto de la opción es requerido.</span>
                                        </div>
                                        <div class="mb-2 image-option" style="display: <?= $hasImage ? 'block' : 'none' ?>;">
                                            <div class="flex align-items-center gap-3">
                                                <input type="file" name="reactivos[<?= $i+1 ?>][opciones][<?= $letra ?>][imagen]" class="form-control imagen-input" id="imagen_<?= $i+1 ?>_<?= $letra ?>" data-preview="preview_<?= $i+1 ?>_<?= $letra ?>" accept="image/*" <?= $hasImage ? 'required' : '' ?>>
                                                <div class="preview-imagen" id="preview_<?= $i+1 ?>_<?= $letra ?>">
                                                    <?php if($opcionImg): ?>
                                                        <img src="<?= base_url('uploads/'.trim($opcionImg)) ?>" class="image-preview">
                                                        <input type="hidden" name="reactivos[<?= $i+1 ?>][opciones][<?= $letra ?>][imagen_actual]" value="<?= trim($opcionImg) ?>">
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <span class="help-block hidden">La imagen de la opción es requerida.</span>
                                        </div>
                                    </div>
                                    <div>
                                        <textarea name="reactivos[<?= $i+1 ?>][opciones][<?= $letra ?>][argumentacion]" class="form-control" rows="2" placeholder="Argumentación" required><?= htmlspecialchars($argumentacion) ?></textarea>
                                        <span class="help-block hidden">La argumentación es requerida.</span>
                                    </div>
                                </div>
                                <div class="option-check">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="reactivos[<?= $i+1 ?>][correcta]" id="correcta_<?= $i+1 ?>_<?= $letra ?>" value="<?= $letra ?>" <?= $isCorrect ? 'checked' : '' ?> required>
                                        <label class="form-check-label" for="correcta_<?= $i+1 ?>_<?= $letra ?>">
                                            Correcta
                                        </label>
                                        <span class="help-block hidden radio-error-message">Debes seleccionar una opción correcta.</span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="mt-3">
                        <label for="bibliografia_<?= $i+1 ?>" class="form-label">Bibliografía</label>
                        <input type="text" name="reactivos[<?= $i+1 ?>][vch_bibliografia]" id="bibliografia_<?= $i+1 ?>" class="form-control" value="<?= htmlspecialchars($reactivo->vch_bibliografia) ?>" required>
                        <span class="help-block hidden">Por favor ingresa la bibliografía.</span>
                    </div>
                    
                    <input type="hidden" name="reactivos[<?= $i+1 ?>][id_reactivo_detail]" value="<?= $reactivo->id_reactivo_detail_sysmater ?>">
                    <input type="hidden" name="reactivos[<?= $i+1 ?>][id_reactivo_main]" value="<?= $reactivo->id_reactivos_main_sysmater ?>">
                </div>
            </div>
        <?php endforeach; ?>
    </div>

  <div class="form-group row">
        <div class="col-sm-12 text-center">            
            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-floppy-saved"></span> Actualizar Reactivos</button>
            <a href="<?= site_url('/sysmater/docente/examenes_registrados'); ?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span>  Cancelar</a>
        </div>
    </div>

    <?= form_close(); ?>
</div>

<script>
$(document).ready(function() {
    // Configurar vista previa de imágenes para nuevos archivos
    $('.imagen-input').on('change', function(e) {
        const previewId = $(this).data('preview');
        const previewDiv = $(`#${previewId}`);
        
        if (e.target.files && e.target.files[0]) {
            previewDiv.empty();
            const reader = new FileReader();
            reader.onload = function(evt) {
                const img = $('<img>').attr('src', evt.target.result)
                    .addClass('image-preview');
                previewDiv.append(img);
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });
    
    // Reemplazar el evento click de los botones de tipo de opción con este código
    $('.option-type-btn').on('click', function() {
        const reactivoId = $(this).data('reactivo');
        const optionId = $(this).data('option');
        const type = $(this).data('type');
        const optionRow = $(`.option-row[data-reactivo="${reactivoId}"][data-option="${optionId}"]`);
        
        // Actualizar botones activos
        optionRow.find('.option-type-btn').removeClass('active');
        $(this).addClass('active');
        
        if (type === 'text') {
            // Cambiando a texto: limpiar el input de imagen y remover la vista previa
            optionRow.find('.image-option input[type="file"]').val('');
            optionRow.find('.image-option .preview-imagen').empty();
            optionRow.find('input[type="hidden"][name$="[imagen_actual]"]').remove();
            
            // Mostrar textarea y hacerlo requerido
            optionRow.find('.text-option').show().find('textarea').attr('required', true);
            optionRow.find('.image-option').hide().find('input[type="file"]').removeAttr('required');
        } else {
            // Cambiando a imagen: limpiar el textarea
            optionRow.find('.text-option textarea').val('');
            
            // Mostrar file input y hacerlo requerido (a menos que haya imagen existente)
            optionRow.find('.text-option').hide().find('textarea').removeAttr('required');
            optionRow.find('.image-option').show();
            
            // Solo requerir nueva imagen si no hay una existente
            if (optionRow.find('input[type="hidden"][name$="[imagen_actual]"]').length === 0) {
                optionRow.find('.image-option input[type="file"]').attr('required', true);
            } else {
                optionRow.find('.image-option input[type="file"]').removeAttr('required');
            }
        }
    });

    // Validación del formulario
    $('#form_examen').on('submit', function(event) {
        let isValid = true;
        $('.help-block').addClass('hidden');
        $('.has-error').removeClass('has-error');
        
        // Validar campos requeridos
        $(this).find('[required]').each(function() {
            if (!$(this).val()) {
                $(this).closest('.form-group').addClass('has-error');
                $(this).next('.help-block').removeClass('hidden');
                isValid = false;
            }
        });
        
        // Validar que cada reactivo tenga una opción correcta seleccionada
        $('[name^="reactivos["][name$="[correcta]"]').each(function() {
            const radioName = $(this).attr('name');
            if ($(`[name="${radioName}"]:checked`).length === 0) {
                $(this).closest('.form-check').addClass('has-error');
                $(this).next('.radio-error-message').removeClass('hidden');
                isValid = false;
            }
        });
        
        // Validar opciones según el tipo seleccionado
        $('.option-row').each(function() {
            const row = $(this);
            const isTextOption = row.find('.option-type-btn.active').data('type') === 'text';
            
            if (isTextOption) {
                const textarea = row.find('.text-option textarea');
                if (!textarea.val()) {
                    row.find('.text-option .help-block').removeClass('hidden');
                    isValid = false;
                }
            } else {
                const fileInput = row.find('.image-option input[type="file"]');
                const hasExistingImage = row.find('input[type="hidden"][name$="[imagen_actual]"]').length > 0;
                
                if (!fileInput.val() && !hasExistingImage) {
                    row.find('.image-option .help-block').removeClass('hidden');
                    isValid = false;
                }
            }
        });
        
        if (!isValid) {
            event.preventDefault();
            event.stopPropagation();
            
            // Mostrar el primer error para que el usuario lo vea
            $('.has-error').first().closest('.exam-card').addClass('active');
            $('html, body').animate({
                scrollTop: $('.has-error').first().offset().top - 100
            }, 500);
            
            return false;
        }
        
        return true;
    });

    // Funcionalidad para expandir/colapsar reactivos (solo uno abierto a la vez)
    $('.exam-card-header').on('click', function() {
        const clickedCard = $(this).closest('.exam-card');

        // Cierra cualquier otra tarjeta que esté abierta
        $('.exam-card').not(clickedCard).removeClass('active');

        // Alterna (abre/cierra) la tarjeta a la que se le hizo clic
        clickedCard.toggleClass('active');
    });
    // Opcional: Abrir el primer reactivo por defecto
    //$('#reactivosContainer .exam-card:first').addClass('active');
    
    // Inicializar los tipos de opción basado en los datos existentes
    $('.option-row').each(function() {
        const row = $(this);
        const isTextOption = row.find('.option-type-btn.active').data('type') === 'text';
        
        if (isTextOption) {
            row.find('.text-option textarea').attr('required', true);
            row.find('.image-option input[type="file"]').removeAttr('required');
        } else {
            row.find('.image-option input[type="file"]').attr('required', true);
            row.find('.text-option textarea').removeAttr('required');
            
            // Si ya hay una imagen existente, no es requerido subir una nueva
            if (row.find('input[type="hidden"][name$="[imagen_actual]"]').length > 0) {
                row.find('.image-option input[type="file"]').removeAttr('required');
            }
        }
    });
});
</script>
