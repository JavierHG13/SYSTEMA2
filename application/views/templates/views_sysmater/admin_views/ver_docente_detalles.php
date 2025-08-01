<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Docente</title>
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/tablas.css">
</head>
<body>
    <p><a href="<?= site_url('/sysmater/admin/lista_docente/') ?>" class='btn btn-danger'><span class="glyphicon glyphicon-chevron-left"></span> Regresar</a></p>
    <div class="panel panel-info">
        <div class="panel-heading">
                <h3 class="panel-title">Detalles del Docente</h3>
            </div>
            <div class="panel-body">
                <?php if ($clave_trabajador !== null): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <p><b>CLAVE DE TRABAJADOR:</b> <?= $clave_trabajador ?></p>
                            <p><b>NOMBRE: </b><?= $nombre_completo ?></h4>
                        </div>
                        <div class="col-md-6">
                            <p><b>ACTIVO: </b><?= $activo ? 'Sí' : 'No' ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">No se encontraron detalles del docente.</div>
                <?php endif; ?>
          </div>
        </div>

                    <div>
                        <h3 class="text-center">Materias asignadas</h3>
                    </div>
                    <div class="cd">
                        <div class="table-container"  style="margin-bottom: 20px;">

                            <?php if (!empty($materias_asignadas) && $materias_asignadas->num_rows() > 0): ?>
                                    <table>
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col">CLAVE</th>
                                                <th scope="col">MATERIA</th>
                                                <th scope="col">FECHA DE ASIGNACIÓN</th>
                                                <th scope="col">CUATRIMESTRE</th>
                                                <th scope="col">GRUPOS</th>
                                                <th scope="col">PERIODO</th>
                                                <th scope="col">ACCIÓN</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($materias_asignadas->result_array() as $materia): ?>
                                                <?php if (!empty($materia['ClaveMateria']) && !empty($materia['NombreMateria'])): ?>
                                                    <tr>
                                                        <td><?= $materia['ClaveMateria'] ?></td>
                                                        <td><?= $materia['NombreMateria'] ?></td>
                                                        <td><?= $materia['FechaAsignacion'] ?></td>
                                                        <td><?= $materia['Cuatrimestre'] ?></td>
                                                        <td><?= $materia['GrupoAlumnos'] ?></td>
                                                        <td><?= $materia['Periodo'] ?></td>
                                                        <td>
                                                            <div class="btn-group btn-group-xs" role="group" aria-label="...">
                                                                <form action="<?= site_url('/sysmater/admin/ver_docente_detalles/eliminarMateria') ?>" method="post">
                                                                    <input type="hidden" name="id_materia" value="<?= $materia['ClaveMateria'] ?>">
                                                                    <input type="hidden" name="id_grupo" value="<?= $materia['id_grupo'] ?>">
                                                                    <input type="hidden" name="id_docenteMateria" value="<?= $materia['idDocenteMateria'] ?>">
                                                                    <input type="hidden" name="clave_trabajador" value="<?= $clave_trabajador ?>">
                                                                    <button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> Eliminar</button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="7">No hay detalles de la materia disponibles.</td>
                                                    </tr>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                            <?php else: ?>
                                <div class="alert alert-info">No hay materias asignadas.</div>
                            <?php endif; ?>
                        </div>

                    </div>
</body>
</html>
