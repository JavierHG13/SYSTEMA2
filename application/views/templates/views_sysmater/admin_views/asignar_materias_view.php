<link rel="stylesheet" href="<?= base_url() ?>assets/css/forms.css">
<div id="box">
<center>
    <h2>Asignar Materias</h2>
</center>
<center>
    <h4>
        Docente: 
        <?= isset($id) ? $id : ''; ?> - 
        <?= isset($apaterno) ? $apaterno : ''; ?>
        <?= isset($amaterno) ? $amaterno : ''; ?>
        <?= isset($nombre) ? $nombre : ''; ?>
    </h4>

</center>

<?php if (isset($periodo_actual)): ?>
    <h4 class="text-center">Periodo Actual: <?= $periodo_actual ?></h4>
    <input type="hidden" name="Periodo" id="Periodo" value="<?= $periodo_actual ?>">
<?php endif; ?>

<?php if (isset($msg)): ?>
    <div class="alert alert-warning alert-dismissable fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Advertencia</strong> <?= $msg; ?>
    </div> <br><br>
<?php endif ?>
<div class="alert alert-info alert-dismissible fade in">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        &times;
    </button>
    <span class="glyphicon glyphicon-info-sign"></span> 
    Para asignar materias en un cuatrimestre debe haber alumnos registrados en ese cuatrimestre y periodo actual <?= $periodo_actual ?>
</div>



    <?php 
        $attributes = array(
            "class" => "form-horizontal",
            "id" => "form_examen",
            "name" => "form_examen",
            "method" => "POST"
        );
        echo form_open("/sysmater/admin/asignar_docente/guardar_datos", $attributes); 
    ?>
    
    <!-- Campos ocultos necesarios -->
    <input type="hidden" name="selectedPeriodo" id="selectedPeriodo" value="<?= isset($periodo_actual) ? $periodo_actual : '' ?>">
    
    <div class="container">
        <div class="form-group"><br>
            <label for="numeroMaterias" class="control-label col-sm-2">Número de Materias:</label>
            <div class="col-sm-9">
                <select name="numeroMaterias" id="numeroMaterias" class="form-control">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
                <span class="text-danger"></span>
            </div>
        </div>

        <div id="materiasContainer"></div>

        <div class="form-group">
            <label for="vchClvTrabajador" class="control-label col-sm-2" style="display:none;">Clave del Trabajador:</label>
            <div class="col-sm-9">
                <input type="hidden" name="vchClvTrabajador" id="vchClvTrabajador" class="form-control" required value="<?= isset($id) ? $id : '' ?>">
                <span class="text-danger"></span>
            </div>
        </div>
        
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const periodoSelect = document.getElementById('Periodo');
            const numeroMateriasSelect = document.getElementById('numeroMaterias');
            const materiasContainer = document.getElementById('materiasContainer');
            const hiddenPeriodoInput = document.getElementById('selectedPeriodo');
        
            // Establecer el periodo actual si está definido
            <?php if(isset($periodo_actual)): ?>
                periodoSelect.value = '<?= $periodo_actual ?>';
                hiddenPeriodoInput.value = '<?= $periodo_actual ?>';
            <?php endif; ?>
        
            periodoSelect.addEventListener('change', function() {
                hiddenPeriodoInput.value = periodoSelect.value;
                cargarMaterias();
            });
        
            numeroMateriasSelect.addEventListener('change', function() {
                renderizarTarjetasMaterias();
            });
        
            function cargarMaterias() {
                const periodo = periodoSelect.value;
                const numeroMaterias = parseInt(numeroMateriasSelect.value);
        
                if (periodo !== '0' && !isNaN(numeroMaterias)) {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '<?= base_url('/sysmater/admin/asignar_docente/get_materias'); ?>');
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            const materias = JSON.parse(xhr.responseText);
                            renderizarTarjetasMaterias(materias);
                        }
                    };
                    xhr.send(`Periodo=${periodo}`);
                } else {
                    materiasContainer.innerHTML = '';
                }
            }
        
            function renderizarTarjetasMaterias(materias) {
                materiasContainer.innerHTML = '';
                const numeroMaterias = parseInt(numeroMateriasSelect.value);
        
                for (let i = 1; i <= numeroMaterias; i++) {
                    const card = document.createElement('div');
                    card.className = 'card';
                    card.style.marginBottom = '20px';
                    card.style.padding = '10px';
                    card.style.width = '93%';
                    card.style.border = '1px solid #ccc';
                    card.style.borderRadius = '4px';
        
                    const divContainer = document.createElement('div');
                    divContainer.className = 'materia-group';
                    
                    // Selector de cuatrimestre
                    const formGroupCuatri = document.createElement('div');
                    formGroupCuatri.className = 'form-group';

                    const labelCuatri = document.createElement('label');
                    labelCuatri.className = 'control-label col-sm-2';
                    labelCuatri.htmlFor = 'cuatrimestre' + i;
                    labelCuatri.innerText = 'Cuatrimestre: ';

                    const colDivCuatri = document.createElement('div');
                    colDivCuatri.className = 'col-sm-9';

                    const selectCuatri = document.createElement('select');
                    selectCuatri.name = 'cuatrimestre' + i;
                    selectCuatri.id = 'cuatrimestre' + i;
                    selectCuatri.className = 'form-control';

                    colDivCuatri.appendChild(selectCuatri);
                    formGroupCuatri.appendChild(labelCuatri);
                    formGroupCuatri.appendChild(colDivCuatri);
                    divContainer.appendChild(formGroupCuatri);

                    // Cargar cuatrimestres dinámicamente
                    cargarCuatrimestres(i);

                    // Selector de materia        
                    const formGroupMateria = document.createElement('div');
                    formGroupMateria.className = 'form-group';
        
                    const labelMateria = document.createElement('label');
                    labelMateria.className = 'control-label col-sm-2';
                    labelMateria.htmlFor = 'materia' + i;
                    labelMateria.innerText = 'Materia ' + i;
        
                    const colDivMateria = document.createElement('div');
                    colDivMateria.className = 'col-sm-9';
        
                    const selectMateria = document.createElement('select');
                    selectMateria.name = 'materia' + i;
                    selectMateria.id = 'materia' + i;
                    selectMateria.className = 'form-control';
        
                    const optionDefault = document.createElement('option');
                    optionDefault.value = '';
                    optionDefault.innerText = 'Seleccione una materia';
                    selectMateria.appendChild(optionDefault);
        
                    colDivMateria.appendChild(selectMateria);
                    formGroupMateria.appendChild(labelMateria);
                    formGroupMateria.appendChild(colDivMateria);
                    divContainer.appendChild(formGroupMateria);
        
                    // Sección de grupos (ahora dinámica)
                    const formGroupGrupos = document.createElement('div');
                    formGroupGrupos.className = 'form-group';
        
                    const labelGrupos = document.createElement('label');
                    labelGrupos.className = 'control-label col-sm-2';
                    labelGrupos.innerText = 'Grupos: ';
        
                    const colDivGrupos = document.createElement('div');
                    colDivGrupos.className = 'col-sm-9 grupos-container';
                    colDivGrupos.id = `grupos-container-${i}`;
                    
                    // Mensaje temporal mientras se cargan los grupos
                    const loadingMsg = document.createElement('div');
                    loadingMsg.className = 'alert alert-info';
                    loadingMsg.textContent = 'Seleccione un cuatrimestre y materia para ver los grupos disponibles';
                    colDivGrupos.appendChild(loadingMsg);
        
                    formGroupGrupos.appendChild(labelGrupos);
                    formGroupGrupos.appendChild(colDivGrupos);
                    divContainer.appendChild(formGroupGrupos);
        
                    card.appendChild(divContainer);
                    materiasContainer.appendChild(card);
        
                    // Evento para cargar materias cuando cambia el cuatrimestre
                    selectCuatri.addEventListener('change', function() {
                        const cuatrimestre = this.value;
                        cargarMateriasPorCuatrimestre(i, cuatrimestre);
                    });
        
                    // Evento para cargar grupos cuando cambia la materia
                    selectMateria.addEventListener('change', function() {
                        const materia = this.value;
                        const cuatrimestre = document.getElementById('cuatrimestre' + i).value;
                        if (materia && cuatrimestre) {
                            cargarGrupos(i, cuatrimestre, materia);
                        }
                    });
        
                    // Cargar las materias inicialmente si hay datos
                    if (materias && materias.length > 0) {
                        cargarMateriasPorCuatrimestre(i, selectCuatri.value);
                    }
                }
            }
        
            function cargarMateriasPorCuatrimestre(index, cuatrimestre) {
                const xhr = new XMLHttpRequest();
                const url = '<?= base_url('/sysmater/admin/asignar_docente/get_materias'); ?>';
                const periodo = document.getElementById('Periodo').value;
            
                xhr.open('POST', url);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        const materias = JSON.parse(xhr.responseText);
                        const selectMateria = document.getElementById('materia' + index);
            
                        selectMateria.innerHTML = '';
            
                        const optionDefault = document.createElement('option');
                        optionDefault.value = '';
                        optionDefault.innerText = 'Seleccione una materia';
                        selectMateria.appendChild(optionDefault);
            
                        if (materias && materias.length > 0) {
                            materias.forEach(materia => {
                                const option = document.createElement('option');
                                option.value = materia.vchClvMateria;
                                option.innerText = materia.vchNomMateria;
                                selectMateria.appendChild(option);
                            });
                        } else {
                            const optionNoMaterias = document.createElement('option');
                            optionNoMaterias.value = '';
                            optionNoMaterias.innerText = 'No hay materias disponibles';
                            selectMateria.appendChild(optionNoMaterias);
                        }
                    }
                };
                xhr.send(`Periodo=${periodo}&cuatrimestre=${cuatrimestre}`);
            }

            function cargarCuatrimestres(index) {
                const xhr = new XMLHttpRequest();
                const url = '<?= base_url('/sysmater/admin/asignar_docente/get_cuatrimestres'); ?>';
                const periodo = document.getElementById('Periodo').value;

                xhr.open('POST', url);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onload = function () {
                    const selectCuatri = document.getElementById('cuatrimestre' + index);
                    if (!selectCuatri) return;

                    if (xhr.status === 200) {
                        const cuatrimestres = JSON.parse(xhr.responseText);

                        selectCuatri.innerHTML = '';

                        const optionDefault = document.createElement('option');
                        optionDefault.value = '';
                        optionDefault.innerText = 'Seleccione un cuatrimestre';
                        selectCuatri.appendChild(optionDefault);

                        cuatrimestres.forEach(cuatri => {
                            const option = document.createElement('option');
                            option.value = cuatri.vchClvCuatri;
                            option.text = parseInt(cuatri.vchClvCuatri);
                            selectCuatri.appendChild(option);
                        });
                    }
                };

                xhr.send(`Periodo=${periodo}`);
            }
            
            // Función para cargar grupos dinámicamente
            function cargarGrupos(index, cuatrimestre, materia) {
                const carrera = '<?= isset($carrera_docente) ? $carrera_docente : "68" ?>';
                const gruposContainer = document.getElementById(`grupos-container-${index}`);
                
                if (!cuatrimestre || !carrera || !materia) {
                    gruposContainer.innerHTML = '<div class="alert alert-info">Seleccione un cuatrimestre y materia</div>';
                    return;
                }
                
                gruposContainer.innerHTML = '<div class="alert alert-info">Cargando grupos disponibles...</div>';
                
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '<?= base_url('/sysmater/admin/asignar_docente/get_grupos_por_cuatri'); ?>');
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    gruposContainer.innerHTML = '';
                    
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            
                            if (response.error) {
                                gruposContainer.innerHTML = `<div class="alert alert-warning">${response.error}</div>`;
                                return;
                            }
                            
                            if (response && response.length > 0) {
                                response.forEach(grupo => {
                                    const divGroup = document.createElement('div');
                                    divGroup.className = 'checkbox-inline';
                                    
                                    const checkbox = document.createElement('input');
                                    checkbox.type = 'checkbox';
                                    checkbox.name = `grupo${index}[]`;
                                    checkbox.value = grupo.id_grupo;
                                    checkbox.id = `grupo-${index}-${grupo.id_grupo}`;
                                    
                                    const label = document.createElement('label');
                                    label.htmlFor = `grupo-${index}-${grupo.id_grupo}`;
                                    label.appendChild(document.createTextNode(' Grupo ' + grupo.vchGrupo));
                                    
                                    divGroup.appendChild(checkbox);
                                    divGroup.appendChild(label);
                                    gruposContainer.appendChild(divGroup);
                                });
                            } else {
                                gruposContainer.innerHTML = '<div class="alert alert-info">No hay grupos disponibles para esta materia</div>';
                            }
                        } catch (e) {
                            gruposContainer.innerHTML = '<div class="alert alert-danger">Error al cargar grupos</div>';
                            console.error('Error parsing JSON:', e);
                        }
                    } else {
                        gruposContainer.innerHTML = '<div class="alert alert-danger">Error al obtener grupos</div>';
                    }
                };
                xhr.onerror = function() {
                    gruposContainer.innerHTML = '<div class="alert alert-danger">Error de conexión</div>';
                };
                xhr.send(`carrera=${carrera}&cuatrimestre=${cuatrimestre}&materia=${materia}`);
            }
                    
            // Cargar inicialmente si hay periodo seleccionado
            if (periodoSelect.value !== '0') {
                cargarMaterias();
            }

            document.getElementById('form_examen').addEventListener('submit', function(e) {
                let isValid = true;
                const periodo = document.getElementById('Periodo').value;
                const numMaterias = parseInt(document.getElementById('numeroMaterias').value);
                
                // Validar periodo
                if (periodo === '0') {
                    alert('Por favor seleccione un periodo');
                    isValid = false;
                }

                // Validar cada materia
                for (let i = 1; i <= numMaterias; i++) {
                    const materia = document.getElementById('materia' + i);
                    const cuatrimestre = document.getElementById('cuatrimestre' + i);
                    const grupos = document.querySelectorAll(`input[name="grupo${i}[]"]:checked`);
                    
                    if (!materia || materia.value === '' || !cuatrimestre || cuatrimestre.value === '' || grupos.length === 0) {
                        alert(`Por favor complete todos los campos para la materia ${i}`);
                        isValid = false;
                        break;
                    }
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
    </script>

    <div class="form-group">
		<div class="col-sm-12 text-center">
            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-floppy-saved"></span> Guardar</button>
            <a href="<?= site_url('/sysmater/admin/lista_docente/') ?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
        </div>
    </div>

    <?= form_close(); ?>
</div>