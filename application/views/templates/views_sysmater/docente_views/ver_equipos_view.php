<!-- Solo necesitas estos dos CSS -->
<link rel="stylesheet" href="<?= base_url() ?>assets/css/sys-styles.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/sys-datatables.css">

<div class="sys-esquema-container">
    <!-- Breadcrumbs - aprovecha sys-styles -->
    <ol class="breadcrumb sys-esquema-breadcrumb sys-rounded">
        <li><a href="<?= site_url('/sysmater/docente/docente/ver_materias') ?>"><span class="glyphicon glyphicon-book"></span> Mis Materias</a></li>
        <li><a href="<?= site_url('/sysmater/docente/actividades/index/' . $id_grupo . '/' . $detalles->vchClvMateria) ?>"><span class="glyphicon glyphicon-list-alt"></span> Actividades</a></li>
        <li class="active" aria-current="page"><span class="glyphicon glyphicon-users"></span> Equipos</li>
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
                <div class="col-md-12">
                    <p><strong>Materia:</strong> <?= htmlspecialchars($detalles->vchNomMateria) ?></p>
                    <p><strong>Actividad:</strong> <?= htmlspecialchars($detalles->titulo) ?></p>
                    <p><strong>Tipo:</strong> Evaluación por equipos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Header responsivo - aprovecha sys-styles -->
    <div class="text-center" style="margin-bottom: 30px;">
        <h2 class="hidden-xs" style="margin-bottom: 10px;">Gestión de Equipos</h2>
        <h3 class="visible-xs" style="margin-bottom: 10px;">Gestión de Equipos</h3>
        <p>Califica y revisa el progreso de los equipos de trabajo</p>
    </div>

    <!-- Búsqueda y controles -->
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-6">
            <!-- Alert - aprovecha variables sys-styles -->
            <div class="alert" style="background: var(--sys-success); border: none; color: #856404; margin: 0;">
                <span class="glyphicon glyphicon-users"></span>
                <strong>Total de equipos:</strong> <?= count($equipos) ?>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Solo el wrapper es custom, el resto aprovecha Bootstrap -->
            <div class="sys-search-wrapper">
                <span class="glyphicon glyphicon-search"></span>
                <input type="text" id="sys-buscador" placeholder="Buscar equipo o integrante...">
            </div>
        </div>
    </div>

    <!-- Tabla custom + sys-styles para badges y botones -->
    <div class="sys-table-container">
        <table class="sys-custom-table" id="sys-tabla">
            <thead>
                <tr>
                    <th>Nombre del Equipo</th>
                    <th style="width: 400px;">Integrantes</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">Calificación</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($equipos)) : ?>
                    <?php foreach ($equipos as $equipo) : ?>
                        <?php
                        $calificacion = isset($equipo['calificacionTotal'])
                            ? intval($equipo['calificacionTotal'])
                            : 0;

                        $estado = $equipo['yaCalificado'] ? 'Revisado' : 'Sin revisar';
                        $badge_class = $equipo['yaCalificado'] ? 'sys-esquema-badge-complete' : 'sys-esquema-badge-progress';

                        // Lista de integrantes separados por coma
                        $integrantes = htmlspecialchars($equipo['integrantes']);
                        ?>
                        <tr>
                            <td>
                                <strong>
                                    <span class="glyphicon glyphicon-flag" style="color: var(--sys-primary); margin-right: 5px;"></span>
                                    <?= htmlspecialchars($equipo['nombre_equipo']) ?>
                                </strong>
                            </td>
                            <td class="integrantes-cell" style="cursor: pointer;" onclick="toggleIntegrantes(this)" data-integrantes-completos="<?= htmlspecialchars($integrantes) ?>">
                                <?php
                                // Truncar inteligente para integrantes
                                $nombresArray = explode(', ', $integrantes);
                                $totalIntegrantes = count($nombresArray);

                                if ($totalIntegrantes <= 2) {
                                    // Mostrar todos si son 2 o menos
                                    $textoMostrar = $integrantes;
                                    $mostrarIcono = false;
                                } else {
                                    // Mostrar primeros 2 y agregar contador
                                    $primerosDos = array_slice($nombresArray, 0, 2);
                                    $restantes = $totalIntegrantes - 2;
                                    $textoMostrar = implode(', ', $primerosDos) . '... (+' . $restantes . ' más)';
                                    $mostrarIcono = true;
                                }
                                ?>
                                <small style="color: #666; line-height: 1.4;" class="integrantes-texto">
                                    <?= $textoMostrar ?>
                                    <?php if ($mostrarIcono): ?>
                                        <span class="expand-icon" style="color: var(--sys-primary); margin-left: 5px;">▼</span>
                                    <?php endif; ?>
                                </small>
                            </td>
                            <td class="text-center">
                                <!-- Badges aprovechan sys-styles -->
                                <span class="badge <?= $badge_class ?>" style="padding: 5px 8px;">
                                    <?= $estado ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <!-- Badges aprovechan sys-styles -->
                                <?php if ($equipo['yaCalificado']): ?>
                                    <span class="badge sys-esquema-badge-complete" style="font-size: 13px; padding: 5px 8px;">
                                        <?= $calificacion ?> / 10
                                    </span>
                                <?php else: ?>
                                    <span class="badge sys-esquema-badge" style="font-size: 13px; padding: 5px 8px;">
                                        0 / 10
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <!-- Botones aprovechan sys-styles -->
                                <a href="<?= site_url('/sysmater/docente/docente/calificar_actividad_equipo/' . $equipo['id_actividad_equipo']) ?>"
                                    class="btn sys-esquema-btn sys-rounded"
                                    style="margin-right: 5px;">
                                    <span class="glyphicon glyphicon-check"></span> Calificar
                                </a>
                                <!--<a href="<*?= site_url('docente/eliminar_equipo/' . $equipo['id_equipo']) ?>"
                                    class="btn sys-btn-danger sys-rounded"
                                    onclick="return confirmarEliminacion('<?= htmlspecialchars($equipo['nombre_equipo']) ?>');">
                                    <span class="glyphicon glyphicon-remove"></span> Eliminar
                                </a>-->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="sys-no-records">
                            <span class="glyphicon glyphicon-info-sign" style="font-size: 24px; display: block; margin-bottom: 10px; opacity: 0.5;"></span>
                            <strong>No hay equipos registrados</strong>
                            <br>
                            <small>No se han creado equipos para esta actividad.</small>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- JavaScript adaptado para equipos -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const CONFIG = {
            searchInputId: 'sys-buscador',
            tableId: 'sys-tabla'
        };

        const searchInput = document.getElementById(CONFIG.searchInputId);
        const table = document.getElementById(CONFIG.tableId);

        if (!searchInput || !table) return;

        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        let sortColumn = null;
        let sortDirection = 'asc';

        // Función mejorada para confirmar eliminación
        window.confirmarEliminacion = function(nombreEquipo) {
            return confirm(`¿Estás seguro de eliminar el equipo "${nombreEquipo}"?\n\n⚠️ Esta acción no se puede deshacer y se eliminarán:\n• Todos los integrantes del equipo\n• Las calificaciones asociadas\n• Toda la información relacionada`);
        }

        function initializeSorting() {
            const headers = table.querySelectorAll('thead th');
            headers.forEach((header, index) => {
                if (index < headers.length - 1) { // Excluir columna de acciones
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

            const sortedRows = rows.slice().sort((rowA, rowB) => {
                const cellA = rowA.cells[columnIndex].textContent.trim();
                const cellB = rowB.cells[columnIndex].textContent.trim();

                let compareResult = 0;

                if (columnIndex === 3) { // Columna de calificación
                    const numA = cellA.includes('/') ? parseFloat(cellA.split('/')[0]) : 0;
                    const numB = cellB.includes('/') ? parseFloat(cellB.split('/')[0]) : 0;
                    compareResult = numA - numB;
                } else {
                    compareResult = cellA.localeCompare(cellB, 'es', {
                        numeric: true,
                        sensitivity: 'base'
                    });
                }

                return sortDirection === 'asc' ? compareResult : -compareResult;
            });

            // Limpiar tbody y agregar filas ordenadas
            tbody.innerHTML = "";
            sortedRows.forEach(row => tbody.appendChild(row));
        }

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase().trim();

            rows.forEach(row => {
                // Obtener el texto de la fila pero excluir integrantes expandidos
                const cells = Array.from(row.cells);
                let textToSearch = '';

                cells.forEach((cell, index) => {
                    if (index === 1) { // Columna de integrantes
                        // Usar los integrantes completos para la búsqueda
                        const integrantesCompletos = cell.getAttribute('data-integrantes-completos');
                        textToSearch += (integrantesCompletos || cell.textContent) + ' ';
                    } else {
                        textToSearch += cell.textContent + ' ';
                    }
                });

                if (textToSearch.toLowerCase().includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // Verificar si hay resultados visibles
            const visibleRows = rows.filter(row => row.style.display !== 'none');

            // Mostrar mensaje si no hay resultados
            const existingNoResults = tbody.querySelector('.no-results-row');
            if (existingNoResults) {
                existingNoResults.remove();
            }

            if (visibleRows.length === 0 && searchTerm) {
                const noResultsRow = document.createElement('tr');
                noResultsRow.className = 'no-results-row';
                const colCount = table.querySelectorAll('thead th').length;
                noResultsRow.innerHTML = `
                <td colspan="${colCount}" class="sys-no-records">
                    <span class="glyphicon glyphicon-search" style="font-size: 24px; display: block; margin-bottom: 10px; opacity: 0.5;"></span>
                    <strong>No se encontraron resultados</strong>
                    <br>
                    <small>Intenta con otros términos de búsqueda</small>
                </td>
            `;
                tbody.appendChild(noResultsRow);
            }
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

        // Función para expandir/contraer integrantes
        window.toggleIntegrantes = function(cell) {
            const textoElement = cell.querySelector('.integrantes-texto');
            const iconElement = cell.querySelector('.expand-icon');
            const integrantesCompletos = cell.getAttribute('data-integrantes-completos');
            const isExpanded = cell.hasAttribute('data-expanded');

            if (!iconElement) return; // No hacer nada si no hay ícono de expandir

            if (isExpanded) {
                // Contraer - volver al texto truncado
                const nombresArray = integrantesCompletos.split(', ');
                const primerosDos = nombresArray.slice(0, 2);
                const restantes = nombresArray.length - 2;
                const textoTruncado = primerosDos.join(', ') + '... (+' + restantes + ' más)';

                textoElement.innerHTML = textoTruncado + '<span class="expand-icon" style="color: var(--sys-primary); margin-left: 5px;">▼</span>';
                cell.removeAttribute('data-expanded');
            } else {
                // Expandir - mostrar todos los integrantes
                textoElement.innerHTML = integrantesCompletos + '<span class="expand-icon" style="color: var(--sys-primary); margin-left: 5px;">▲</span>';
                cell.setAttribute('data-expanded', 'true');
            }
        }

        // Remover la función que interfiere con el tooltip
        // document.querySelectorAll('td small').forEach(...) - REMOVIDA

        initializeSorting();
        initializeSearch();

        console.log('🎯 Sistema de gestión de equipos inicializado');
    });
</script>