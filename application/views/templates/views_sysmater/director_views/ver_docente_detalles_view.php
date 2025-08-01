<link rel="stylesheet" href="<?= base_url() ?>assets/css/tablas.css">
<style>
    :root {
        --primary-color: #4a9f4c;
        --primary-dark: #35672dff;
        --accent-color: #e9f5e9;
    }

    .container-main {
        max-width: 1200px;
        margin: 30px auto;
    }

    .info-card {
        border-radius: 10px;
    }
    .info-row {
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: bold;
        color: var(--primary-dark);
    }

    .panel-default {
        border-color: #ddd;
    }
    
    .panel-default > .panel-heading {
        background-color: var(--primary-color);
        color: white;
        border-color: #ddd;
    }
    
    .panel-title {
        font-size: 16px;
    }
    
    .panel-body .table {
        margin-bottom: 0;
    }
    
    .table-striped > tbody > tr:nth-child(odd) > td,
    .table-striped > tbody > tr:nth-child(odd) > th {
        background-color: #f9f9f9;
    }
    
    .table-striped > tbody > tr:hover > td,
    .table-striped > tbody > tr:hover > th {
        background-color: var(--accent-color);
    }
    
    .table > thead > tr > th {
        background-color: var(--primary-color);
        color: white;
        border-bottom: 1px solid var(--primary-dark);
    }

    .dataTable thead th {
        background-color: var(--primary-color) !important;
        color: white !important;
    }

    @media (max-width: 768px) {
        .info-row {
            display: block;
        }
        
        .info-label {
            display: block;
            margin-bottom: 5px;
        }
        
        .panel-body {
            padding: 10px;
        }
    }
</style>
<div class="container-main">
    <?php if ($clave_trabajador !== null): ?>
    <div class="info-card panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><span class="glyphicon glyphicon-info-sign"></span>  DETALLES DEL DOCENTE</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="text-center" style="border-right: 1px solid #eee; padding: 15px;">
                        <p><strong>CLAVE TRABAJADOR</strong></p>
                        <h4><?= $clave_trabajador ?></h4>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="text-center" style="border-right: 1px solid #eee; padding: 15px;">
                        <p><strong>NOMBRE COMPLETO</strong></p>
                        <h4><?= $nombre_completo ?></h4>
                    </div>
                </div>        
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="text-center" style="padding: 15px;">
                        <p><strong>ESTADO</strong></p>
                        <div style="margin-top: 8px;">
                            <?php if ($activo): ?>
                                <h4 class="label label-success">
                                    <i class="glyphicon glyphicon-ok"></i> Activo
                                </h4>
                            <?php else: ?>
                                <span class="label label-danger">
                                    <i class="glyphicon glyphicon-remove"></i> Inactivo
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <?php else: ?>
            <div class="alert alert-warning">No se encontraron detalles del docente.</div>
        <?php endif; ?>
            <!-- termina detalles docente -->

    <?php
    $materiasAgrupadas = [];
    foreach ($materias_asignadas->result_array() as $materia) {
        $clave = $materia['ClaveMateria'];
        if (!isset($materiasAgrupadas[$clave])) {
            $materiasAgrupadas[$clave] = [
                'NombreMateria' => $materia['NombreMateria'],
                'FechaAsignacion' => $materia['FechaAsignacion'],
                'Cuatrimestre' => $materia['Cuatrimestre'],
                'Periodo' => $materia['Periodo'],
                'Grupos' => []
            ];
        }
        $materiasAgrupadas[$clave]['Grupos'][] = [
            'GrupoAlumnos' => $materia['GrupoAlumnos']
        ];
    }
    ?>

    <?php if (!empty($materiasAgrupadas)): ?>
    <div class="table-container panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="glyphicon glyphicon-list"></i> MATERIAS ASIGNADAS</h3>
        </div>
        <div class="panel-body">
            <div class="form-group row">
                <label for="periodo" class="control-label col-sm-2 col-form-label" id="titulo-periodo">Filtro por período:</label>
                <select class="form-control d-inline-block w-auto ms-2" id="filtroPeriodo" name="periodo" required style="margin: 0 10px">
                    <option value="todos">Todos</option>
                    
                    <?php if(isset($periodo_actual) && !empty($periodo_actual)): ?>
                        <option value="<?= $periodo_actual ?>" selected>
                            <?= $periodo_actual ?>
                        </option>
                    <?php endif; ?>
                    
                    <?php if(isset($periodos) && !empty($periodos)): ?>
                        <?php foreach($periodos as $periodo): ?>
                            <?php if($periodo->vchPeriodo != $periodo_actual): ?>
                                <option value="<?= $periodo->vchPeriodo ?>">
                                    <?= $periodo->vchPeriodo ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
               
            </div>

          <div class="panel" >
                    <div class="panel-body">
                    <div id="tbl_materias_wrapper">
                        <div class="table-responsive">
                            <div class="table-container">

                                <table id="tbl_materias" class="">
                                    <thead>
                                        <tr>
                                            <th>Clave</th>
                                            <th>Materia</th>
                                            <th>Fecha de Asignación</th>
                                            <th>Cuatrimestre</th>
                                            <th>Periodo</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($materiasAgrupadas as $clave => $materia): ?>
                                            <tr  data-periodo="<?= $materia['Periodo'] ?>">
                                                <td><?= $clave ?></td>
                                                <td><?= $materia['NombreMateria'] ?></td>
                                                <td><?= $materia['FechaAsignacion'] ?></td>
                                                <td><?= $materia['Cuatrimestre'] ?></td>
                                                <td><?= $materia['Periodo'] ?></td>
                                                <td>
                                                    <div class="btn-group btn-group-xs" role="group" aria-label="...">
                                                        <button class="btns btn btn-danger btn-xs" onclick='mostrarModalGrupos("<?= $clave ?>", <?= json_encode($materia['Grupos']) ?>, "<?= $materia['Periodo'] ?>")'>
                                                            <i class="glyphicon glyphicon-user"></i> Grupos
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="contenedor-examenes" class="table-container panel panel-default" style="display:none;">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="glyphicon glyphicon-file"></i> EXÁMENES REGISTRADOS</h3>
        </div>
        <div class="panel-body">
            <div id="examenes-tabla" style="padding:20px;"></div>
        </div>
    </div>

    <div id="contenedor-actividades" class="table-container panel panel-default" style="display:none;">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="glyphicon glyphicon-tasks"></i> ACTIVIDADES REGISTRADAS</h3>
        </div>
        <div class="panel-body">
            <div id="actividades-tabla"  style="padding:20px;"></div>
        </div>
    </div>
    <?php else: ?>
    <div class="alert alert-info">
        <i class="glyphicon glyphicon-info-sign"></i> No hay materias asignadas para este docente.
    </div>
    <?php endif; ?>
</div>

<!-- Modal Grupos -->
<div id="modalGrupos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalGruposLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close btns" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="glyphicon glyphicon-user"></i> Grupos de la materia: <span id="tituloModalMateria"></span></h4>
            </div>
            <div class="modal-body" id="grupos-container">
                <div class="text-center">

                    <div id="contenidoModalGrupos"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {    
        const $select = $('#filtroPeriodo');
        const $tabla = $('#tbl_materias tbody');
        const $titulo = $('#titulo-periodo');

        if ($select.find('option[value="todos"]').length === 0) {
            $select.prepend('<option value="todos">Todos</option>');
        }
        function filtrarPorPeriodo() {
            const periodoSeleccionado = $select.val();
            const $filas = $tabla.find('tr:not(.no-result)');
            
            if (periodoSeleccionado === 'todos' || periodoSeleccionado === '') {
                $tabla.find('.no-result').remove();
                $filas.show();
                $titulo.text('Filtro por período: Todos');

            } else {
                $tabla.find('.no-result').remove();
                $filas.hide();
                
                const $filasVisibles = $filas.filter(`[data-periodo="${periodoSeleccionado}"]`);
                $filasVisibles.show();
                $titulo.text(`Filtro por período: ${periodoSeleccionado}`);
                
                if ($filasVisibles.length === 0) {
                    $tabla.find('.no-result').remove();
                    $tabla.append(
                        `<tr class="no-result">
                            <td colspan="6" class="text-center text-muted">
                                <i class="glyphicon glyphicon-info-sign"></i> 
                                No hay materias asignadas para el período "${periodoSeleccionado}"
                            </td>
                        </tr>`
                    );
                }else {
                    $tabla.find('.no-result').remove();
                }
            }
        }
        $select.on('change', function() {
            filtrarPorPeriodo();
        });
        
        filtrarPorPeriodo();
    });

    const vchClvTrabajador = '<?= $clave_trabajador ?>';

    function verExamenes(claveMateria, grupo, periodo) {
        $.ajax({
            url: "<?= site_url('/sysmater/director/examenes_registrados/filtro_materia_grupo') ?>",
            method: "POST",
            data: {
                clave_materia: claveMateria,
                grupo: grupo,
                periodo: periodo,
                vchClvTrabajador: vchClvTrabajador
            },
            success: function(response) {
                $('#examenes-tabla').html(response);
                $('#contenedor-examenes').show();
                $('html, body').animate({
                    scrollTop: $('#contenedor-examenes').offset().top - 20
                }, 500);
            },
            error: function() {
                alert('Error al cargar los exámenes.');
            }
        });
    }

    function verActividades(claveMateria, grupo, periodo) {
        $.ajax({
            url: "<?= site_url('/sysmater/director/examenes_registrados/filtro_actividades_materia_grupo') ?>",
            method: "POST",
            data: {
                clave_materia: claveMateria,
                grupo: grupo,
                periodo: periodo,
                vchClvTrabajador: vchClvTrabajador
            },
            success: function(response) {
                $('#actividades-tabla').html(response);
                $('#contenedor-actividades').show();
                $('html, body').animate({
                    scrollTop: $('#contenedor-actividades').offset().top - 20
                }, 500);
            },
            error: function() {
                alert('Error al cargar las actividades.');
            }
        });
    }

    function mostrarModalGrupos(claveMateria, grupos, periodo) {
        $('#tituloModalMateria').text(claveMateria);
        let html = '<div class="table-container"><table class=""><thead><tr><th>Grupo</th><th>Exámenes</th><th>Actividades</th></tr></thead><tbody>';
        
        grupos.forEach(grupoObj => {
            const grupo = grupoObj.GrupoAlumnos;
            html += `
                <tr>
                    <td>${grupo}</td>
                    <td>
                    <div class="btn-group btn-group-xs" role="group" aria-label="...">  
                        <button class="btns btn btn-primary btn-xs" onclick="verExamenes('${claveMateria}', '${grupo}', '${periodo}')">
                            <i class="glyphicon glyphicon-eye-open"></i> Ver
                        </button>
                    </div>
                    </td>
                    <td>
                    <div class="btn-group btn-group-xs" role="group" aria-label="...">  
                        <button class="btns btn btn-warning btn-xs" onclick="verActividades('${claveMateria}', '${grupo}', '${periodo}')">
                            <i class="glyphicon glyphicon-eye-open"></i> Ver
                        </button>
                    </div>
                    </td>
                </tr>`;
        });
        
        html += '</tbody></table></div>';
        $('#contenidoModalGrupos').html(html);
        $('#modalGrupos').modal('show');
    }

</script>