<link rel="stylesheet" href="<?= base_url() ?>assets/css/tablas.css">
<?php if (isset($examen) && !empty($examen)) : ?>
    <?php foreach ($examen->result() as $datos) : ?>
        <?php $titulo = $datos->nvch_Titulo; ?>
        <?php endforeach; ?>
        <?php endif; ?>
        
        <div id="box">
            <h3 class="card-title text-center"><?php echo $titulo; ?>  Grupo - <?php echo $gruposs; ?></h3>
        </div>
        <p>
            <a href="<?= site_url('/sysmater/docente/docente/examenes_programados') ?>" class='btn btn-danger'><span class="glyphicon glyphicon-chevron-left"></span> Regresar</a>
        </p>

<div id="box">
    <div class="row">
        <div class="col-md-12">

            <!-- Alumnos Pendientes -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <?php
                        if (isset($alumnos_faltantes) && $alumnos_faltantes->num_rows() > 0) {
                            echo $alumnos_faltantes->num_rows() . ' alumno(s) pendiente(s)';
                        } else {
                            echo 'Ningún alumno pendiente';
                        }
                        ?>
                    </h3>
                </div>
                <div class="">
                    <?php if (isset($alumnos_faltantes) && $alumnos_faltantes->num_rows() > 0) : ?>
                        <div class="table-container" style="margin-bottom: 20px;">
                            <table >
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
                    <?php else : ?>
                        <div class="alert alert-info text-center">No hay alumnos pendientes.</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Alumnos en Progreso -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <?php
                        if (isset($alumnos_en_progreso) && $alumnos_en_progreso->num_rows() > 0) {
                            echo $alumnos_en_progreso->num_rows() . ' alumno(s) en progreso';
                        } else {
                            echo 'Ningún alumno en progreso';
                        }
                        ?>
                    </h3>
                </div>
                <div class="">
                    <?php if (isset($alumnos_en_progreso) && $alumnos_en_progreso->num_rows() > 0) : ?>
                        <div class="table-container" style="margin-bottom: 20px;">
                            <table >
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
                    <?php else : ?>
                        <div class="alert alert-info text-center">No hay alumnos en progreso.</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Alumnos Finalizados -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <?php
                        if (isset($alumnos_fin_examen) && $alumnos_fin_examen->num_rows() > 0) {
                            echo $alumnos_fin_examen->num_rows() . ' alumno(s) que han finalizado el examen';
                        } else {
                            echo 'Ningún alumno ha finalizado el examen';
                        }
                        ?>
                    </h3>
                </div>
                <div class="">
                    <?php if (isset($alumnos_fin_examen) && $alumnos_fin_examen->num_rows() > 0) : ?>
                        <div class="table-container" style="margin-bottom: 20px;">
                            <table >
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
                    <?php else : ?>
                        <div class="alert alert-info text-center">No hay alumnos que hayan finalizado el examen.</div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>
