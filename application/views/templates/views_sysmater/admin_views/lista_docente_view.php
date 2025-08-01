<link rel="stylesheet" href="<?= base_url() ?>assets/css/tablas.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/tabla.css">
<script src="<?= base_url() ?>assets/js/table.js"></script>
    <div class="cd">
        <center>
            <h2>Docentes registrados</h2>
        </center> <br>   
        <!-- seccion de busqueda -->
        <div class="">
            <div class="row">
                <div class="col-md-4">
                    <div class="">
                        <p><a href="<?= site_url('/sysmater/admin/admin/nuevo_docente') ?>" class='btn btn-primary'><span class="glyphicon glyphicon-plus"></span> Nuevo docente</a></p>
                    </div>
                </div>

                <div class="col-md-4 text-center">
                    <div class="">
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- contenedor search-waper necesario para input de busqueda con su id [buscador] -->
                    <div class="search-wrapper">
                        <span class="glyphicon glyphicon-search"></span>
                        <input type="text" id="buscador" placeholder="Buscar docente...">
                    </div>
                </div>
            </div>
        </div>
        <!-- termina seccion busqueda -->
        <div class="table-container">
            <table id="tabla_id">
                <thead>
                    <tr>
                        <th>CLAVE TRANAJADOR</th>
                        <th>APELLIDO PATERNO</th>
                        <th>APELLIDO MATERNO</th>
                        <th>NOMBRE</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($docentes && $docentes->num_rows() > 0): ?>
                        <?php foreach ($docentes->result() as $docente): ?>
                            <tr>
                                <td><?= $docente->vchClvTrabajador; ?></td>
                                <td><?= $docente->vchAPaterno; ?></td>
                                <td><?= $docente->vchAMaterno; ?></td>
                                <td><?= $docente->vchNombre; ?></td>
                                <td >
                                     <div class="btn-group btn-group-xs" role="group" aria-label="...">
                                    
                                            <a href="<?= site_url('/sysmater/admin/admin/elimina_docente/'.$docente->vchClvTrabajador) ?>" class='btns btn btn-danger' alt="Eliminar materias">
                                                <span class="glyphicon glyphicon-trash"></span> Eliminar
                                            </a>
                                            <a href="<?= site_url('/sysmater/admin/admin/asignar_docente/'.$docente->vchClvTrabajador) ?>" class='btns btn btn-primary' alt="Asignar materias">
                                                <span class="glyphicon glyphicon-list"></span> Asignar materias
                                            </a>
                                            <a href="<?= site_url('/sysmater/admin/admin/ver_docente_detalles/'.$docente->vchClvTrabajador) ?>" class='btns btn btn-warning' alt="Ver Docente">
                                                <span class="glyphicon glyphicon-eye-open"></span> Ver Docente
                                            </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">
                                <center>No existen registros</center>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div id="pagination" style="margin-top: 10px; text-align: center;"></div>
            <div class="pagination-info text-primary" id="paginationInfo" style="margin-top: 10px;margin-bottom: 10px; text-align: center;"></div>
        </div>
    </div>
