<center>
    <h3>Alumnos</h3>
</center>
<p>
    <a href="<?= site_url('/sysmater/docente/docente/nuevo_alumno') ?>" class='btn btn-success'>Nuevo alumno</a>
    <a href="<?= site_url('/sysmater/docente/docente/nuevo_alumno_archivo') ?>" class='btn btn-success'>Nuevo alumno Archivo Excel</a>
</p>

<div class="card">
    <div class="card-body">
        <form method="post" action="<?= site_url('/sysmater/docente/alumnos/filtrar') ?>">
            <div class="form-group row">
                <label for="cuatrimestre" class="col-sm-1 col-form-label">Cuatrimestre:</label>
                <div class="col-sm-1">
                    <select class="form-control" id="cuatrimestre" name="cuatrimestre">
                        <option value="">Selecciona un cuatrimestre</option>
                        <!-- Opciones de cuatrimestre del 01 al 11 -->
                        <?php for ($i = 1; $i <= 11; $i++) : ?>
                            <option value="<?= sprintf('%02d', $i); ?>"><?= sprintf('%02d', $i); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <label for="grupo" class="col-sm-1 col-form-label">Grupo:</label>
                <div class="col-sm-1">
                    <select class="form-control" id="grupo" name="grupo">
                        <option value="">Selecciona un grupo</option>
                        <!-- Grupos de A a F -->
                        <?php for ($char = 'A'; $char <= 'F'; $char++) : ?>
                            <option value="<?= $char; ?>"><?= $char; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-sm-2">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered table-condensed" id="tbl_alumnos">
        <thead>
            <tr>
                <th>MATRICULA</th>
                <th>CARRERA</th>
                <th>ALUMNO</th>
                <th>CUATRIMESTRE</th>
                <th>GRUPO</th>
  
            </tr>
        </thead>
        <tbody>
            <?php if ($alumnos) : ?>
                <?php foreach ($alumnos->result() as $alumno) : ?>
                    <tr>
                        <td><?= $alumno->vchMatricula; ?></td>
                        <td><?= $alumno->vchNomCarrera; ?></td>
                        <td><?= $alumno->Alumno; ?></td>
                        <td><?= $alumno->vchClvCuatri; ?></td>
                        <td><?= $alumno->chvGrupo; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="8">No existen registros</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tbl_alumnos').DataTable();
    });
</script>
