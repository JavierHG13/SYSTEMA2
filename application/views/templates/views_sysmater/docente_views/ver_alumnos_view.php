<!-- Solo necesitas estos dos CSS -->
<link rel="stylesheet" href="<?= base_url() ?>assets/css/sys-styles.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/sys-datatables.css">


<div class="sys-esquema-container">
    <!-- Breadcrumbs - aprovecha sys-styles -->
    <ol class="breadcrumb sys-esquema-breadcrumb sys-rounded">
        <li><a href="<?= site_url('/sysmater/docente/docente/ver_materias') ?>"><span class="glyphicon glyphicon-book"></span> Mis Materias</a></li>
        <li><a href="<?= site_url('/sysmater/docente/actividades/index/' . $id_grupo . '/' . $detalles->vchClvMateria) ?>"><span class="glyphicon glyphicon-list-alt"></span> Actividades</a></li>
        <li class="active" aria-current="page"><span class="glyphicon glyphicon-education"></span> Alumnos</li>
    </ol>


    <!-- Panel - aprovecha sys-styles -->
    <div class="panel panel-success sys-rounded">
        <div class="panel-heading">
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-info-sign"></span> Información de la Actividad
            </h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Materia:</strong> <?= htmlspecialchars($detalles->vchNomMateria) ?></p>
                    <p><strong>Actividad:</strong> <?= htmlspecialchars($detalles->titulo) ?></p>
                    <p><strong>Descripcion:</strong> <?= htmlspecialchars($detalles->descripcion) ?></p>
                </div>

            </div>
        </div>
    </div>

    <!-- Header responsivo - aprovecha sys-styles -->
    <div class="text-center" style="margin-bottom: 30px;">
        <h2 class="hidden-xs" style="margin-bottom: 10px;">Gestión de Alumnos</h2>
        <h3 class="visible-xs" style="margin-bottom: 10px;">Gestión de Alumnos</h3>
        <p>Califica y revisa el progreso de los estudiantes</p>
    </div>

    <!-- Búsqueda y controles -->
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-6">
            <!-- Alert - aprovecha variables sys-styles -->
            <div class="alert" style="background: var(--sys-success); border: none; color: #856404; margin: 0;">
                <span class="glyphicon glyphicon-users"></span>
                <strong>Total de estudiantes:</strong> <?= count($alumnos) ?>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Solo el wrapper es custom, el resto aprovecha Bootstrap -->
            <div class="sys-search-wrapper">
                <span class="glyphicon glyphicon-search"></span>
                <input type="text" id="sys-buscador" placeholder="Buscar estudiante...">
            </div>
        </div>
    </div>

    <!-- Tabla custom + sys-styles para badges y botones -->
    <div class="sys-table-container">
        <table class="sys-custom-table" id="sys-tabla">
            <thead>
                <tr>
                    <th>Matrícula</th>
                    <th style="width: 500px;">Nombre Completo</th>
                    <th class="text-center">Calificación</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alumnos as $alumno): ?>
                    <?php
                    $calificacion = $alumno->calificacion_total ?? 0;

                    $estado = $alumno->nombre_estado ?? 'Pendiente';

                    switch ($estado) {
                        case 'Entregado':
                            $badge_class = 'sys-esquema-badge-complete'; // Verde
                            break;
                        case 'Entregado fuera de tiempo':
                            $badge_class = 'sys-esquema-badge-warning'; // Amarillo
                            break;
                        case 'No entregado':
                            $badge_class = 'sys-esquema-badge-danger'; // Rojo
                            break;
                        case 'Pendiente':
                        default:
                            $badge_class = 'sys-esquema-badge-progress'; // Azul o gris
                            break;
                    }

                    ?>
                    <tr>
                        <td><strong><?= $alumno->vchMatricula ?></strong></td>
                        <td><?= $alumno->nombreCompleto ?></td>
                        <td class="text-center">
                            <!-- Badges aprovechan sys-styles -->
                            <span class="badge sys-esquema-badge">
                                <?= number_format($calificacion, 1) ?>/10
                            </span>
                        </td>

                        <td class="text-center">
                            <!-- Badges aprovechan sys-styles -->
                            <span class="badge <?= $badge_class ?>">
                                <?= $estado ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <!-- Botones aprovechan sys-styles -->
                            <a href="<?= site_url('/sysmater/docente/docente/calificar_actividad/' . $alumno->id_actividad . '/' . $alumno->vchMatricula) ?>"
                                class="btn sys-esquema-btn sys-rounded">
                                <span class="glyphicon glyphicon-check"></span> Calificar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Paginación custom -->
        <div id="sys-pagination" class="sys-pagination"></div>
        <div id="sys-pagination-info" class="sys-pagination-info"></div>
    </div>
</div>

<!-- JavaScript sin cambios -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const CONFIG = {
            searchInputId: 'sys-buscador',
            tableId: 'sys-tabla',
            paginationId: 'sys-pagination',
            paginationInfoId: 'sys-pagination-info',
            rowsPerPage: 10,
            maxVisiblePages: 8
        };

        const searchInput = document.getElementById(CONFIG.searchInputId);
        const table = document.getElementById(CONFIG.tableId);

        if (!searchInput || !table) return;

        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        let currentPage = 1;
        let filteredRows = rows;
        let sortColumn = null;
        let sortDirection = 'asc';

        function initializeSorting() {
            const headers = table.querySelectorAll('thead th');
            headers.forEach((header, index) => {
                if (index < headers.length - 1) {
                    header.classList.add('sortable');
                    header.addEventListener('click', () => sortTable(index));
                }
            });
        }

        function sortTable(columnIndex) {
            const headers = table.querySelectorAll('thead th');
            headers.forEach(h => h.classList.remove('sort-asc', 'sort-desc'));

            if (sortColumn === columnIndex) {
                sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                sortDirection = 'asc';
                sortColumn = columnIndex;
            }

            headers[columnIndex].classList.add(sortDirection === 'asc' ? 'sort-asc' : 'sort-desc');

            filteredRows.sort((rowA, rowB) => {
                const cellA = rowA.cells[columnIndex].textContent.trim();
                const cellB = rowB.cells[columnIndex].textContent.trim();

                let compareResult = 0;

                if (columnIndex === 0) {
                    compareResult = parseInt(cellA) - parseInt(cellB);
                } else if (columnIndex === 2) {
                    const numA = parseFloat(cellA.split('/')[0]);
                    const numB = parseFloat(cellB.split('/')[0]);
                    compareResult = numA - numB;
                } else {
                    compareResult = cellA.localeCompare(cellB, 'es', {
                        numeric: true,
                        sensitivity: 'base'
                    });
                }

                return sortDirection === 'asc' ? compareResult : -compareResult;
            });

            currentPage = 1;
            renderTable();
        }

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            filteredRows = rows.filter(row => row.textContent.toLowerCase().includes(searchTerm));
            currentPage = 1;
            renderTable();
        }

        function renderTable() {
            tbody.innerHTML = "";

            if (filteredRows.length === 0) {
                const noResultsRow = document.createElement('tr');
                const colCount = table.querySelectorAll('thead th').length;
                noResultsRow.innerHTML = `
                <td colspan="${colCount}" class="sys-no-records">
                    <span class="glyphicon glyphicon-search" style="font-size: 24px; display: block; margin-bottom: 10px; opacity: 0.5;"></span>
                    ${searchInput.value.trim() ? 'No se encontraron resultados para tu búsqueda' : 'No hay registros disponibles'}
                </td>
            `;
                tbody.appendChild(noResultsRow);
            } else {
                const start = (currentPage - 1) * CONFIG.rowsPerPage;
                const end = start + CONFIG.rowsPerPage;
                const paginatedRows = filteredRows.slice(start, end);
                paginatedRows.forEach(row => tbody.appendChild(row));
            }

            renderPagination();
            updatePaginationInfo();
        }

        function renderPagination() {
            const pagination = document.getElementById(CONFIG.paginationId);
            if (!pagination) return;

            pagination.innerHTML = "";
            const pageCount = Math.ceil(filteredRows.length / CONFIG.rowsPerPage);

            if (pageCount <= 1) {
                pagination.style.display = 'none';
                return;
            }

            pagination.style.display = 'flex';

            function createButton(label, targetPage, disabled = false, isActive = false, type = 'pagina') {
                const btn = document.createElement('button');
                btn.innerHTML = label;
                btn.disabled = disabled;
                btn.className = `pagination-button ${type}`;


                if (isActive) btn.classList.add('active');

                if (!disabled) {
                    btn.onclick = () => {
                        currentPage = targetPage;
                        renderTable();
                        table.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    };
                }

                pagination.appendChild(btn);
            }

            createButton('⏮ INICIO', 1, currentPage === 1, false, 'inicio');
            createButton('◀ ANT', currentPage - 1, currentPage === 1, false, 'anterior');

            let startPage = Math.max(1, currentPage - Math.floor(CONFIG.maxVisiblePages / 2));
            let endPage = Math.min(pageCount, startPage + CONFIG.maxVisiblePages - 1);

            if (endPage - startPage < CONFIG.maxVisiblePages - 1) {
                startPage = Math.max(1, endPage - CONFIG.maxVisiblePages + 1);
            }

            for (let i = startPage; i <= endPage; i++) {
                createButton(i, i, false, i === currentPage, 'pagina');
            }

            createButton('SIG ▶', currentPage + 1, currentPage === pageCount, false, 'siguiente');
            createButton('FIN ⏭', pageCount, currentPage === pageCount, false, 'fin');
        }

        function updatePaginationInfo() {
            const paginationInfo = document.getElementById(CONFIG.paginationInfoId);
            if (!paginationInfo) return;

            const totalRecords = filteredRows.length;

            if (totalRecords === 0) {
                paginationInfo.textContent = 'No hay registros para mostrar';
                return;
            }

            const start = (currentPage - 1) * CONFIG.rowsPerPage + 1;
            const end = Math.min(currentPage * CONFIG.rowsPerPage, totalRecords);

            paginationInfo.innerHTML = `Mostrando <strong>${start}</strong> a <strong>${end}</strong> de <strong>${totalRecords}</strong> registros`;

        }

        function initializeSearch() {
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(filterTable, 250);
            });

            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    this.value = '';
                    filterTable();
                    this.blur();
                }
            });
        }

        initializeSorting();
        initializeSearch();
        renderTable();
    });
</script>