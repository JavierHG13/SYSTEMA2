<link rel="stylesheet" href="<?= base_url() ?>assets/css/tablas.css">

<!-- css y js necesario para funcionar el buscador y paginacion -->
<link rel="stylesheet" href="<?= base_url() ?>assets/css/tabla.css">
<script src="<?= base_url() ?>assets/js/table.js"></script>
<div class="cd">
    <center>
        <h2>Docentes registrados</h2> <br>
    </center>    
<div class="">
  <div class="row">
    <div class="col-md-4">
      <div class="izquierda">
      </div>
    </div>

    <div class="col-md-4 text-center">
      <div class="centro">
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
     <div class="table-container" style="margin-bottom: 20px;">
        <!-- id [table_id] necesario para usar paginacion y busqueda -->
        <table id="tabla_id">

            <thead>
                <tr>
                    <th>Clave Trabajador</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Nombre</th>
                    <th>Acción</th>
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
                            <td style="padding: 0;">
                                    <div class="btn-group btn-group-xs" role="group" aria-label="Acciones">
                                        <a href="<?= site_url('/sysmater/director/director/detalles_docente/'.$docente->vchClvTrabajador) ?>" class='btns btn btn-primary' alt="Ver Docente" style="margin-left: 10px;">
                                            <span class="glyphicon glyphicon-check"></span> Ver Docente
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
        <!-- div necesario para mostrar paginacion  con id [pagination]-->
        <div id="pagination" style="margin-top: 10px; text-align: center;"></div>
    </div>
</div>
<!-- IMPLEMENTACION DE PAGINACION Y BUSQUEDA MAYO-JULIO 2025 -->