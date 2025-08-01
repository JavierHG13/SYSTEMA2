<link rel="stylesheet" href="<?= base_url() ?>assets/css/tablas.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/tabla.css">
<!-- <script src="<?= base_url() ?>assets/js/table.js"></script> -->
    <div class="cd">
    <div class="text-center">
        <h2 id="titulo-periodo">Exámenes registrados <?= $periodo_actual; ?></h2>
    </div> <br>

    <!-- seccion de busqueda -->
    <div class="row" style="margin-bottom: 15px; display: flex; align-items: center; flex-wrap: wrap;">
        <!-- nuevo examen -->
        <div class="col-md-3" style="margin-bottom: 10px;">
            <a href="<?= site_url('/sysmater/docente/docente/nuevo_examen') ?>" class='btn btn-primary'>
                <span class="glyphicon glyphicon-plus"></span> Nuevo examen
            </a>
        </div>

        <!-- Formulario de filtros -->
        <div class="col-md-6" style="margin-bottom: 10px;">
             <label for="filtro" class="control-label col-sm-2 col-form-label">Filtrar:</label>
            <select id="filtroPeriodo" class="form-control input-sm">
                <option value="todos">Todos</option>
                <?php foreach ($periodos as $per): ?>
                    <option value="<?= $per; ?>" <?= $per == $periodo_actual ? 'selected' : ''; ?>>
                        <?= $per; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Buscador -->
        <div class="col-md-3">
            <div class="search-wrapper" >
            <span class="glyphicon glyphicon-search" ></span>
            <input type="text" id="buscador" placeholder="Buscar exámen...">
            </div>
        </div>

        </div>
    <!-- termina seccion busqueda -->

    <div class="table-container" style="margin-bottom: 20px;">
        <table id="tabla_id">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>MATERIA</th>
                    <th>TÍTULO</th>
                    <th>PERIODO</th>
                    <th>PARCIAL</th>
                    <th>GRUPOS</th>
                    <th>REACT</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($examenes && $examenes->num_rows() > 0): ?>
                    <?php foreach ($examenes->result() as $examen): ?>
                        <tr data-periodo="<?= $examen->periodo; ?>">
                            <td><?= $examen->id_examen; ?></td>
                            <td style="max-width:300px;"><?= $examen->vchNomMateria; ?></td>
                            <td><?= $examen->nvch_Titulo; ?></td>
                            <td><?= $examen->periodo; ?></td>
                            <td><?= $examen->parcial; ?></td>
                            <td><?= $examen->vchGrupo; ?></td>
                            <td style="width:80px;"><?= $examen->nReactivos; ?> / <?= $examen->int_reactivos_requeridos; ?></td>

                            <td>
                                 <div class="btn-group btn-group-xs" role="group" aria-label="Acciones">
                                    <a href="<?= site_url('/sysmater/docente/elimina_examen/index/'.$examen->id_examen) ?>" class='btns btn btn-danger' title="Eliminar examen">
                                         <span class="glyphicon glyphicon-trash"></span>
                                    </a>
                                    <a href="<?= site_url('/sysmater/docente/docente/edita_examen/index/'.$examen->id_examen) ?>" class='btns btn btn-success' title="Editar examen">
                                         <span class="glyphicon glyphicon-pencil"></span>
                                    </a>
                                    <a href="<?= site_url('/sysmater/docente/docente/ver_examen/index/'.$examen->id_examen) ?>" class='btns btn btn-default' title="Vista previa">
                                        <span class="glyphicon glyphicon-eye-open"></span>
                                    </a>
                                    <!-- pa seleccionar reactivos existentes -->
                                    <?php if ($examen->nReactivos < $examen->int_reactivos_requeridos): ?>
                                        <a href="<?= site_url('/sysmater/docente/docente/reactivos_examen/index/'.$examen->id_examen) ?>" class='btns btn btn-primary' title="Seleccionar reactivos">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </a>
                                    <?php else: ?>
                                        <button disabled class='btns btn btn-primary' title="Seleccionar reactivos">
                                            <span class="glyphicon glyphicon-ok"></span>
                                        </button>
                                    <?php endif; ?>
                                    <!-- carga eactivos -->
                                    <?php if ($examen->nReactivos < $examen->int_reactivos_requeridos): ?>
                                        <a href="<?= site_url('/sysmater/docente/docente/cargar_reactivos_examen/index/' . $examen->id_examen) ?>" class='btns btn btn-warning' title="Asignar reactivos">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= site_url('/sysmater/docente/docente/editar_reactivos_examen/index/' . $examen->id_examen) ?>" class='btns btn btn-warning' title="Editar reactivos">
                                            <span class="glyphicon glyphicon-edit"></span>
                                        </a>
                                    <?php endif; ?>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="9" class="no-records">No tiene exámenes registrados</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <!-- Paginacion -->
      <div id="pagination" style="margin-top: 10px; text-align: center;"></div>
      <div class="pagination-info text-primary" id="paginationInfo" style="margin-top: 10px;margin-bottom: 10px; text-align: center;"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('buscador');
  const periodoSelect = document.getElementById('filtroPeriodo');
  const tituloPeriodo = document.getElementById('titulo-periodo');
  const table = document.getElementById('tabla_id');
  const tbody = table.querySelector('tbody');
  const allRows = Array.from(tbody.querySelectorAll('tr'));

  const rowsPerPage = 10;
  const maxVisiblePages = 10;
  let currentPage = 1;
  let filteredRows = [];

  function applyFilters() {
    const search = searchInput.value.toLowerCase();
    const selectedPeriodo = periodoSelect.value;

    filteredRows = allRows.filter(row => {
      const matchesSearch = row.textContent.toLowerCase().includes(search);
      const matchesPeriodo = selectedPeriodo === 'todos' || row.getAttribute('data-periodo') === selectedPeriodo;
      return matchesSearch && matchesPeriodo;
    });

    // Actualizar título
    tituloPeriodo.textContent =
      selectedPeriodo === 'todos'
        ? 'Exámenes registrados (todos los períodos)'
        : `Exámenes registrados ${selectedPeriodo}`;

    currentPage = 1;
    renderTable();
  }

  function renderTable() {
    tbody.innerHTML = '';
    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const paginatedRows = filteredRows.slice(start, end);

    if (paginatedRows.length === 0) {
      const tr = document.createElement('tr');
      const td = document.createElement('td');
      td.colSpan = 9;
      td.className = 'no-records';
      td.textContent = 'No se encontraron exámenes con esos filtros';
      tr.appendChild(td);
      tbody.appendChild(tr);
    } else {
      paginatedRows.forEach(row => tbody.appendChild(row));
    }

    renderPagination();
    updatePaginationInfo();
  }

  function renderPagination() {
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';
    const pageCount = Math.ceil(filteredRows.length / rowsPerPage);

    function createButton(label, targetPage, disabled = false, isActive = false, tipo = 'pagina') {
      const btn = document.createElement('button');
      btn.textContent = label;
      btn.disabled = disabled;
      btn.classList.add('pagination-button');

      // Estilo según tipo
      btn.classList.add(tipo);
      if (isActive) btn.classList.add('active');

      btn.onclick = () => {
        currentPage = targetPage;
        renderTable();
      };

      pagination.appendChild(btn);
    }

    createButton('⏮ INICIO', 1, currentPage === 1, false, 'inicio');
    createButton('◀ ANT', currentPage - 1, currentPage === 1, false, 'anterior');

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
    const total = filteredRows.length;
    const start = total === 0 ? 0 : (currentPage - 1) * rowsPerPage + 1;
    const end = Math.min(currentPage * rowsPerPage, total);
    paginationInfo.textContent = `Mostrando ${start} a ${end} de ${total} registros`;
  }

  // Eventos
  searchInput.addEventListener('input', applyFilters);
  periodoSelect.addEventListener('change', applyFilters);

  // Inicializar
  applyFilters();
});
</script>

