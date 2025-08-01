<!-- cargar estilos de sys-datatables.css -->
<link rel="stylesheet" href="<?= base_url() ?>assets/css/sys-datatables.css">

<div class="sys-esquema-container">
    <!-- Header simple -->
    <div class="text-center" style="margin-bottom: 30px;">
        <h2><span class="glyphicon glyphicon-list-alt"></span> Instrumentos de Evaluación</h2>
        <p>Gestiona y organiza todos tus instrumentos de evaluación</p>
    </div>

    <!-- Controles en una fila -->
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-3">
            <a href="<?= site_url('/sysmater/docente/docente/crear_rubrica') ?>" class="btn btn-success btn-lg" style="font-size: 14px; padding: 10px 16px;">
                <span class="glyphicon glyphicon-plus"></span> Nuevo instrumento
            </a>
        </div>
        <div class="col-md-3">
            <div class="sys-search-wrapper">
                <span class="glyphicon glyphicon-search"></span>
                <input type="text" id="searchInput" placeholder="Buscar instrumento..." style="font-size: 14px; height: 42px;">
            </div>
        </div>
        <div class="col-md-2">
            <select class="form-control" id="filterMateria" style="font-size: 14px; height: 42px;">
                <option value="">Todas las materias</option>
                <?php
                if ($instrumentos) {
                    $materias = array();
                    foreach ($instrumentos->result() as $item) {
                        if (!in_array($item->vchNomMateria, $materias)) {
                            $materias[] = $item->vchNomMateria;
                        }
                    }
                    sort($materias);
                    foreach ($materias as $materia) {
                        echo '<option value="' . strtolower($materia) . '">' . $materia . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-control" id="filterParcial" style="font-size: 14px; height: 42px;">
                <option value="">Todos los parciales</option>
                <option value="1">Parcial 1</option>
                <option value="2">Parcial 2</option>
                <option value="3">Parcial 3</option>
            </select>
        </div>
        <div class="col-md-2 text-right">
            <div class="alert alert-info" style="margin: 0; padding: 12px; font-size: 14px; height: 42px; display: flex; align-items: center; justify-content: center;">
                <strong>Total:</strong> <span id="totalInstrumentos"><?= $instrumentos ? $instrumentos->num_rows() : 0 ?></span>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="sys-table-container">
        <table class="sys-custom-table" id="tablaInstrumentos">
            <thead>
                <tr>
            
                    <th data-sort="nombre">Nombre del Instrumento</th>
                    <th data-sort="vchNomMateria">Materia</th>
                    <th data-sort="nombre_tipo" class="text-center">Tipo</th>
                    <th data-sort="parcial" class="text-center">Parcial</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($instrumentos) : ?>
                    <?php foreach ($instrumentos->result() as $materia) : ?>
                        <tr>
                         
                            <td><?= $materia->nombre; ?></td>
                            <td><?= $materia->vchNomMateria; ?></td>
                            <td class="text-center">
                                <span class="label label-primary"><?= $materia->nombre_tipo; ?></span>
                            </td>
                            <td class="text-center">
                                <span class="label label-success">Parcial <?= $materia->parcial; ?></span>
                            </td>
                            <td class="text-center">
                                <a href="<?= site_url('/sysmater/docente/ver_instrumentos/detalles_instrumento/' . $materia->id_instrumento) ?>"
                                    class="btn btn-success btn-sm" style="margin-right: 5px;">
                                    <span class="glyphicon glyphicon-eye-open"></span> Ver
                                </a>
                                <button class="btn btn-danger btn-sm btnEliminarInstrumento"
                                    data-id="<?= $materia->id_instrumento; ?>"
                                    data-nombre="<?= $materia->nombre; ?>"
                                    data-toggle="modal"
                                    data-target="#modalEliminarInstrumento">
                                    <i class="glyphicon glyphicon-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="text-center" style="padding: 60px;">
                            <span class="glyphicon glyphicon-info-sign" style="font-size: 48px; color: #ddd; display: block; margin-bottom: 20px;"></span>
                            <h4>No hay instrumentos disponibles</h4>
                            <p>Comienza creando tu primer instrumento de evaluación</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Espacio antes del footer -->
    <div style="margin-bottom: 60px;"></div>
</div>





<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="modalEliminarInstrumento" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">

                <h5 class="modal-title" id="modalEliminarLabel">Eliminar instrumento</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Estás seguro que deseas eliminar el instrumento <strong id="nombreInstrumento"></strong>?

                <!-- Mensaje personalizado -->
                <div id="mensajeEliminacion" class="alert alert-info text-center" style="display: none; margin-top: 20px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button id="confirmarEliminar" class="btn btn-danger">Eliminar</button>
            </div>
        </div>
    </div>
</div>


<script>
    let idInstrumentoAEliminar = null;

    $(document).on('click', '.btnEliminarInstrumento', function() {
        idInstrumentoAEliminar = $(this).data('id');
        const nombre = $(this).data('nombre');
        $('#nombreInstrumento').text(nombre);
    });

    $('#confirmarEliminar').on('click', function() {
        if (!idInstrumentoAEliminar) return;

        $.ajax({
            url: "<?= site_url('/sysmater/docente/ver_instrumentos/eliminar_instrumento') ?>",
            method: "POST",
            data: {
                id_instrumento: idInstrumentoAEliminar
            },
            dataType: "json",
            success: function(respuesta) {
                const $mensaje = $('#mensajeEliminacion');

                if (respuesta.exito == 1) {
                    $('#modalEliminarInstrumento').modal('hide');

                    // Eliminar la fila de la tabla
                    $(`button[data-id="${idInstrumentoAEliminar}"]`).closest('tr').remove();

                    // Mostrar mensaje exitoso fuera del modal si lo deseas
                    //mostrarMensajeGlobal('Instrumento eliminado correctamente.', 'success');

                } else {
                    // Mostrar mensaje de error en el modal
                    $mensaje.removeClass('alert-info alert-success').addClass('alert-danger');
                    $mensaje.text(respuesta.mensaje || 'Error al eliminar.');
                    $mensaje.fadeIn();

                    setTimeout(() => {
                        $mensaje.fadeOut();
                    }, 3000); // Desaparece después de 3 segundos
                }
            },
            error: function() {
                const $mensaje = $('#mensajeEliminacion');
                $mensaje.removeClass('alert-info alert-success').addClass('alert-danger');
                $mensaje.text('Error en la solicitud AJAX.');
                $mensaje.fadeIn();

                setTimeout(() => {
                    $mensaje.fadeOut();
                }, 3000);
            }
        });
    });


    document.addEventListener('DOMContentLoaded', function() {
        const filterMateria = document.getElementById('filterMateria');
        const filterParcial = document.getElementById('filterParcial');
        const searchInput = document.getElementById('searchInput');
        const tbody = document.querySelector('#tablaInstrumentos tbody');
        let sortDirection = {};
        let rows = Array.from(tbody.querySelectorAll('tr'));

        [filterMateria, filterParcial, searchInput].forEach(element => {
            element.addEventListener('input', applyFilters);
        });

        document.querySelectorAll('.sys-custom-table th[data-sort]').forEach(th => {
            th.addEventListener('click', () => {
                const key = th.dataset.sort;
                sortDirection[key] = sortDirection[key] === 'asc' ? 'desc' : 'asc';
                sortTable(key, sortDirection[key]);
                applyFilters();
            });
        });

        function sortTable(key, direction) {
            const index = Array.from(document.querySelectorAll('.sys-custom-table th[data-sort]')).findIndex(th => th.dataset.sort === key);
            rows.sort((a, b) => {
                if (a.cells.length <= 1) return 0;
                const aValue = a.cells[index].textContent.trim();
                const bValue = b.cells[index].textContent.trim();
                return direction === 'asc' ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
            });
            rows.forEach(row => tbody.appendChild(row));
            document.querySelectorAll('.sys-custom-table th[data-sort]').forEach(th => th.classList.remove('sort-asc', 'sort-desc'));
            document.querySelector(`.sys-custom-table th[data-sort="${key}"]`).classList.add(`sort-${direction}`);
        }

        function applyFilters() {
            const materia = filterMateria.value.toLowerCase();
            const parcial = filterParcial.value.toLowerCase();
            const searchTerm = searchInput.value.toLowerCase();

            rows.forEach(row => {
                if (row.cells.length > 1) {
                    const materiaText = row.cells[2].textContent.toLowerCase();
                    const parcialText = row.cells[4].textContent.toLowerCase();
                    const rowText = Array.from(row.cells).map(cell => cell.textContent.toLowerCase()).join(' ');
                    const matches = (!materia || materiaText.includes(materia)) &&
                        (!parcial || parcialText.includes(parcial)) &&
                        (!searchTerm || rowText.includes(searchTerm));
                    row.style.display = matches ? '' : 'none';
                }
            });

            document.getElementById('totalInstrumentos').textContent =
                rows.filter(row => row.style.display !== 'none' && row.cells.length > 1).length;
        }
    });
</script>