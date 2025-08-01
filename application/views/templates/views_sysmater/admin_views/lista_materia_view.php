<link rel="stylesheet" href="<?= base_url() ?>assets/css/tablas.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/tabla.css">
<script src="<?= base_url() ?>assets/js/table.js"></script>

<div class="cd">
    <center>
        <h2>Materias registradas</h2>
    </center> <br>
    <!-- seccion de busqueda -->
     <div class="row" style="margin-bottom: 15px;">
        <!-- Botón de nueva materia -->
        <div class="col-md-4">
            <a href="<?= site_url('/sysmater/admin/admin/nueva_materia') ?>" class="btn btn-primary">
                <span class="glyphicon glyphicon-plus"></span> Nueva Materia
            </a>
        </div>

        <!-- Espacio central  -->
        <div class="col-md-4 text-center" style="margin-top: 10px;">
            <!-- Puedes agregar algo aquí si lo necesitas -->
        </div>

        <!-- Input de búsqueda -->
        <div class="col-md-4"  style="padding-left: -100px;">
            <div class="search-wrapper">
                <span class="glyphicon glyphicon-search" ></span>
                <input type="text" id="buscador" placeholder="Buscar materia...">
            </div>
        </div>
    </div>
    <!-- termina seccion busqueda -->

    <div class="table-container" style="margin-bottom: 20px;">
        <table id="tabla_id">
            <thead>
                <tr>
                    <th>CLAVE</th>
                    <th>MATERIA</th>
                    <th>PERIODO</th>
                    <th>CUATRIMESTRE</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($materias) : ?>
                    <?php foreach ($materias->result() as $materia) : ?>
                        <tr>
                            <td><?= $materia->vchClvMateria; ?></td>
                            <td><?= $materia->vchNomMateria; ?></td>
                            <td><?= $materia->idPeriodo; ?></td>
                            <td><?= $materia->vchCuatrimestre; ?></td>
                             <td style="padding: 0;">
                            <div class="btn-group btn-group-xs" role="group" aria-label="...">
                                <a href="<?= site_url('/sysmater/admin/admin/elimina_materia/'.$materia->vchClvMateria) ?>" class='btns btn btn-danger' alt="Eliminar materia" >
                                    <span class="glyphicon glyphicon-trash"></span> Eliminar
                                </a>

                            </div>
                        </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7"><center>No existen registros</center></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <!-- PAGINACION -->
        <div id="pagination" style="margin-top: 10px; text-align: center;"></div>
        <div class="pagination-info text-primary" id="paginationInfo" style="margin-top: 10px;margin-bottom: 10px; text-align: center;"></div>
    </div>
</div>

