<p>
    <a href="<?= site_url('/sysmater/docente/docente/examenes_programados') ?>" class='btn btn-danger'>Regresar</a>
</p>

<div id="box">
    <div class="row">
        <div class="col-md-12">
            <?php if (isset($alumnos_faltantes) && !empty($alumnos_faltantes)) : ?>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Alumnos Pendientes</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Matrícula</th>
                                        <th>Nombre</th>
                                        <th>Cuatrimestre</th>
                                        <th>Grupo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($alumnos_faltantes->result() as $alumno) : ?>
                                        <tr>
                                            <td><?php echo $alumno->Matricula; ?></td>
                                            <td><?php echo $alumno->Nombre; ?></td>
                                            <td><?php echo $alumno->vchCuatrimestre; ?></td>
                                            <td><?php echo $alumno->Grupo; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($alumnos_en_progreso) && !empty($alumnos_en_progreso)) : ?>
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Alumnos en Progreso</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Matrícula</th>
                                        <th>Nombre</th>
                                        <th>Grupo</th>
                                        <th>Cuatrimestre</th>
                                        <th>Total de Reactivos</th>
                                        <th>Aciertos</th>
                                        <th>Calificación</th>
                                        <th>Reactivos Contestados</th>
                                        <th>Reactivos Faltantes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($alumnos_en_progreso->result() as $alumno) : ?>
                                        <tr>
                                            <td><?php echo $alumno->vchMatricula; ?></td>
                                            <td><?php echo $alumno->Nombre; ?></td>
                                            <td><?php echo $alumno->vchGrupo; ?></td>
                                            <td><?php echo $alumno->vchCuatrimestre; ?></td>
                                            <td><?php echo $alumno->total_reactivos; ?></td>
                                            <td><?php echo $alumno->aciertos; ?></td>
                                            <td><?php echo number_format($alumno->calificacion, 2); ?></td>
                                            <td><?php echo $alumno->reactivos_contestados; ?></td>
                                            <td><?php echo $alumno->reactivos_faltantes; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($alumnos_fin_examen) && !empty($alumnos_fin_examen)) : ?>
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Alumnos que han Finalizado el Examen</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Matrícula</th>
                                        <th>Nombre Completo</th>
                                        <th>Grupo</th>
                                        <th>Cuatrimestre</th>
                                        <th>Total de Reactivos</th>
                                        <th>Aciertos</th>
                                        <th>Calificación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($alumnos_fin_examen->result() as $alumno) : ?>
                                        <tr>
                                            <td><?php echo $alumno->vchMatricula; ?></td>
                                            <td><?php echo $alumno->NombreCompleto; ?></td>
                                            <td><?php echo $alumno->vchGrupo; ?></td>
                                            <td><?php echo $alumno->vchCuatrimestre; ?></td>
                                            <td><?php echo $alumno->total_reactivos; ?></td>
                                            <td><?php echo $alumno->aciertos; ?></td>
                                            <td><?php echo number_format($alumno->calificacion, 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>
