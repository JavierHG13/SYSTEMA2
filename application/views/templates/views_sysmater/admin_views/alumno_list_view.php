<link rel="stylesheet" href="<?= base_url() ?>assets/css/tablas.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/tabla.css">

<div class="cd">
  <center>
    <h2>Alumnos registrados</h2>
  </center>  <br>
     <!-- seccion de busqueda -->
    <div class="row" style="margin-bottom: 15px; display: flex; align-items: center; flex-wrap: wrap;">
        <!-- nuevo Alumno -->
        <div class="col-md-3" style="margin-bottom: 10px;">
            <a href="<?= site_url('/sysmater/admin/nuevo_alumno') ?>" class="btn btn-primary">
            <span class="glyphicon glyphicon-plus"></span> Nuevo Alumno
            </a>
        </div>

        <!-- Formulario de filtros -->
        <div class="col-md-6" style="margin-bottom: 10px;">
            <form method="post" action="<?= site_url('/sysmater/admin/alumnos/filtrar') ?>" class="form-inline" id="filtroForm">

            <div class="form-group">
                <label for="periodo">Periodo:</label>
                <select class="form-control input-sm" name="periodo" id="periodo">
                <option value="<?= $periodo_actual ?>" selected> <?= $periodo_actual ?></option>
                <?php foreach ($periodos_disponibles as $p): ?>
                    <?php if ($p->vchPeriodo != $periodo_actual): ?>
                    <option value="<?= $p->vchPeriodo ?>" <?= ($filtro_actual['periodo'] == $p->vchPeriodo) ? 'selected' : '' ?>>
                        <?= $p->vchPeriodo ?>
                    </option>
                    <?php endif; ?>
                <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin-left: 5px;">
                <label for="cuatrimestre">Cuatrimestre:</label>
                <select class="form-control input-sm" name="cuatrimestre" id="cuatrimestre" <?= empty($cuatrimestres) ? 'disabled' : '' ?>>
                <option value="0">Todos</option>
                <?php foreach ($cuatrimestres as $c): ?>
                    <option value="<?= $c->vchClvCuatri ?>" <?= $filtro_actual['cuatrimestre'] == $c->vchClvCuatri ? 'selected' : '' ?>>
                    <?= $c->vchClvCuatri ?>
                    </option>
                <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin-left: 5px;">
                <label for="grupo">Grupo:</label>
                <select class="form-control input-sm" name="grupo" id="grupo" <?= ($filtro_actual['cuatrimestre'] == 0 || empty($grupos_filtrados)) ? 'disabled' : '' ?>>
                <option value="0">Todos</option>
                <?php foreach ($grupos_filtrados as $g): ?>
                    <option value="<?= $g->id_grupo ?>" <?= $filtro_actual['grupo'] == $g->id_grupo ? 'selected' : '' ?>>
                    <?= $g->vchGrupo ?>
                    </option>
                <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-warning btn-sm" style="margin-left: 5px;">
                <span class="glyphicon glyphicon-filter"></span> Filtrar
            </button>

            <?php if ($filtro_actual['periodo'] != $periodo_actual || $filtro_actual['cuatrimestre'] != 0 || $filtro_actual['grupo'] != 0): ?>
                <a href="<?= site_url('/sysmater/admin/alumnos') ?>" class="btn btn-danger btn-sm" style="margin-left: 5px;">
                <span class="glyphicon glyphicon-remove"></span> Limpiar
                </a>
            <?php endif; ?>
            </form>
        </div>

        <!-- Buscador -->
        <div class="col-md-3">
            <div class="search-wrapper" >
            <span class="glyphicon glyphicon-search" ></span>
            <input type="text" id="buscador" placeholder="Buscar alumno...">
            </div>
        </div>

        </div>
    <!-- termina seccion busqueda -->

  <div class="table-container" id="tablaPaginada">
    <?php if ($alumnos && $alumnos->num_rows() > 0): ?>
        <table id="tbl_alumnos">
            <thead class="thead-dark">
                <tr>
                    <th>MATRICULA</th>
                    <th>CARRERA</th>
                    <th>ALUMNO</th>
                    <th>PERIODO</th>
                    <th>CUATRI</th>
                    <th>GRUPO</th>
                </tr>
            </thead>
            <tbody id="tablaCuerpo">
                <?php foreach ($alumnos->result() as $alumno): ?>
                    <tr>
                        <td><?= htmlspecialchars($alumno->vchMatricula) ?></td>
                        <td><?= htmlspecialchars($alumno->vchNomCarrera) ?></td>
                        <td><?= htmlspecialchars($alumno->Alumno) ?></td>
                        <td><?= htmlspecialchars($alumno->vchPeriodo) ?></td>
                        <td><?= htmlspecialchars($alumno->vchClvCuatri) ?></td>
                        <td><?= htmlspecialchars($alumno->chvGrupo) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <div id="pagination" style="margin-top: 10px; text-align: center;"></div>
        <div class="pagination-info text-primary" id="paginationInfo" style="margin-top: 10px;margin-bottom: 10px; text-align: center;"></div>

    <?php else: ?>
        <div class="alert alert-info text-center" style="font-weight: bold; margin-top: 20px; margin-left:20px; margin-right:20px;">
            <?= $msg ?: 'No hay registros disponibles.' ?>
        </div>
    <?php endif; ?>
</div>

</div>

<script>
$(document).ready(function() {    
    $('#periodo').change(function() {
        var periodo = $(this).val();
        var cuatrimestreSelect = $('#cuatrimestre');
        var grupoSelect = $('#grupo');
        
        // Resetear selects dependientes
        cuatrimestreSelect.html('<option value="0">Todos</option>').prop('disabled', true);
        grupoSelect.html('<option value="0">Todos</option>').prop('disabled', true);
        
        // Cargar cuatrimestres para el periodo seleccionado
        cuatrimestreSelect.prop('disabled', true).html('<option value="0">Cargando cuatrimestres...</option>');
        
        $.ajax({
            url: '<?= site_url("/sysmater/admin/alumnos/get_cuatrimestres_ajax") ?>',
            type: 'POST',
            dataType: 'json',
            data: { 
                periodo: periodo,
                <?= $this->security->get_csrf_token_name() ?>: '<?= $this->security->get_csrf_hash() ?>'
            },
            success: function(response) {
                var options = '<option value="0">Todos</option>';
                
                if (response.error) {
                    options = '<option value="">' + response.error + '</option>';
                } 
                else if (response && response.length > 0) {
                    $.each(response, function(index, cuatri) {
                        options += '<option value="' + cuatri.vchClvCuatri + '">' + cuatri.vchClvCuatri + '</option>';
                    });
                } else {
                    options = '<option value="">No hay cuatrimestres disponibles</option>';
                }
                
                cuatrimestreSelect.html(options).prop('disabled', false);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                cuatrimestreSelect.html('<option value="">Error al cargar cuatrimestres</option>').prop('disabled', false);
            }
        });
    });
    
    // Manejar cambio de cuatrimestre
    $('#cuatrimestre').change(function() {
        var cuatrimestre = $(this).val();
        var periodo = $('#periodo').val() || '<?= $periodo_actual ?>';
        var grupoSelect = $('#grupo');
        
        if (cuatrimestre == 0) {
            grupoSelect.html('<option value="0">Todos</option>').prop('disabled', true);
            return;
        }
        
        grupoSelect.prop('disabled', true).html('<option value="0">Cargando grupos...</option>');
        
        $.ajax({
            url: '<?= site_url("/sysmater/admin/alumnos/get_grupos_ajax") ?>',
            type: 'POST',
            dataType: 'json',
            data: { 
                cuatrimestre: cuatrimestre,
                periodo: periodo,
                <?= $this->security->get_csrf_token_name() ?>: '<?= $this->security->get_csrf_hash() ?>'
            },
            success: function(response) {
                var options = '<option value="0">Todos</option>';
                
                if (response.error) {
                    options = '<option value="">' + response.error + '</option>';
                } 
                else if (response.info) {
                    options = '<option value="">' + response.info + '</option>';
                }
                else if (response && response.length > 0) {
                    $.each(response, function(index, grupo) {
                        options += '<option value="' + grupo.id_grupo + '">' + grupo.vchGrupo + '</option>';
                    });
                } else {
                    options = '<option value="">No hay grupos disponibles</option>';
                }
                
                grupoSelect.html(options).prop('disabled', false);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                grupoSelect.html('<option value="">Error al cargar grupos</option>').prop('disabled', false);
            }
        });
    });
});

// Sistema de paginación y búsqueda mejorado
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('buscador');
    const table = document.getElementById('tbl_alumnos');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    const rowsPerPage = 10;
    const maxVisiblePages = 10;
    let currentPage = 1;
    let filteredRows = rows;

    function filterTable() {
        const search = searchInput.value.toLowerCase();
        filteredRows = rows.filter(row => row.textContent.toLowerCase().includes(search));
        currentPage = 1;
        renderTable();
    }

    function renderTable() {
        tbody.innerHTML = "";
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const paginatedRows = filteredRows.slice(start, end);

        paginatedRows.forEach(row => tbody.appendChild(row));
        renderPagination();
        updatePaginationInfo();
    }

    function renderPagination() {
        let pagination = document.getElementById('pagination');
        pagination.innerHTML = "";

        const pageCount = Math.ceil(filteredRows.length / rowsPerPage);

        if (pageCount <= 1) return;

        function createButton(label, targetPage, disabled = false, isActive = false, tipo = 'pagina') {
            const btn = document.createElement('button');
            btn.textContent = label;
            btn.disabled = disabled;
            btn.classList.add('pagination-button');

            // Asignar clase según tipo
            if (tipo === 'inicio') btn.classList.add('inicio');
            else if (tipo === 'fin') btn.classList.add('fin');
            else if (tipo === 'anterior') btn.classList.add('anterior');
            else if (tipo === 'siguiente') btn.classList.add('siguiente');
            else btn.classList.add('pagina');

            if (isActive) btn.classList.add('active');

            btn.onclick = () => {
                currentPage = targetPage;
                renderTable();
            };

            pagination.appendChild(btn);
        }

        // Botones Inicio y Anterior
        createButton('⏮ INICIO', 1, currentPage === 1, false, 'inicio');
        createButton('◀ ANT', currentPage - 1, currentPage === 1, false, 'anterior');

        // Botones de páginas
        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(pageCount, startPage + maxVisiblePages - 1);
        if (endPage - startPage < maxVisiblePages - 1) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }

        for (let i = startPage; i <= endPage; i++) {
            createButton(i, i, false, i === currentPage, 'pagina');
        }

        createButton('SIG ▶', currentPage + 1, currentPage === pageCount || pageCount === 0, false, 'siguiente');
        createButton('FIN ⏭', pageCount, currentPage === pageCount || pageCount === 0, false, 'fin');
    }

    function updatePaginationInfo() {
        const paginationInfo = document.getElementById('paginationInfo');
        const totalRecords = filteredRows.length;
        const start = totalRecords === 0 ? 0 : (currentPage - 1) * rowsPerPage + 1;
        const end = Math.min(currentPage * rowsPerPage, totalRecords);
        
        paginationInfo.textContent = `Mostrando ${start} a ${end} de ${totalRecords} registros`;
    }

    searchInput.addEventListener('input', filterTable);

    renderTable();
});
</script>