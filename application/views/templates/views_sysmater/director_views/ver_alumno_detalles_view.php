<?php $row = $alumno->row(); ?>
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

    .panel-default {
        border-color: #ddd;
        border-radius: 8px;
    }

    .panel-default > .panel-heading {
        background-color: var(--primary-color);
        color: white;
        border-color: #ddd;
        border-radius: 6px 6px 0 0;
    }

    .panel-title {
        font-size: 16px;
    }

    .info-card {
        border-radius: 10px;
    }

    .info-label {
        font-weight: bold;
        color: var(--primary-dark);
    }

    /* materias */
    .materia-card {
        background: #fff;
        border-left: 6px solid var(--primary-color);
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .materia-card:hover {
        background-color: #f5faf5;
    }

    .actividades-lista {
        margin-top: 10px;
        display: none;
        flex-wrap: wrap;
        gap: 10px;
    }

    .actividad-card {
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 10px;
        width: 100%;
        background-color: #fff;
        box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.04);
    }

    @media (min-width: 600px) {
        .actividad-card {
            width: 48%;
        }
    }

    .actividad-card span.label {
        margin-left: 5px;
    }

    .header-inline {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }

    .header-inline h4 {
        margin: 0;
        font-weight: bold;
        color: var(--primary-dark);
    }

    .form-inline label {
        margin-right: 10px;
        margin-top: 6px;
    }

    .form-inline select {
        max-width: 120px;
    }

    .glyphicon {
        margin-right: 6px;
    }
    .examen-card {
    border: 1px solid #ccc;
    border-radius: 6px;
    padding: 10px;
    background-color: #fdfdfd;
    margin-bottom: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.examen-materia {
    margin-bottom: 20px;
}

.examen-materia h4 {
    margin-bottom: 10px;
    color: var(--primary-dark);
}

</style>

<div class="container-main">  
    <!-- Info alumno -->
    <div class="info-card panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><span class="glyphicon glyphicon-user"></span> DETALLES DEL ALUMNO</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-3 col-sm-3 col-xs-12 text-center" style="border-right: 1px solid #eee; padding: 15px;">
                    <p><strong>MATRICULA</strong></p>
                    <h5><?= $row->vchMatricula; ?></h5>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12 text-center" style="border-right: 1px solid #eee; padding: 15px;">
                    <p><strong>NOMBRE COMPLETO</strong></p>
                    <h5><?= $row->vchAPaterno . ' ' . $row->vchAMaterno . ' ' . $row->vchNombre; ?></h5>                    
                </div> 
                <div class="col-md-3 col-sm-3 col-xs-12 text-center" style="border-right: 1px solid #eee; padding: 15px;">
                    <p><strong>GRADO Y GRUPO</strong></p>
                    <h5><?= $row->vchClvCuatri; ?>°<span style="margin-left: 10px;">"<?= $row->vchGrupo; ?>"</span></h5>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12 text-center" style="border-right: 1px solid #eee; padding: 15px;">
                    <p><strong>CARRERA</strong></p>
                    <h5><?= $row->vchNomCarrera; ?></h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Selector + Título -->
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="header-inline">
                <div class="form-inline">
                    <label for="parcial" class="control-label">
                        <span class="glyphicon glyphicon-check" style="font-size: 20px; color: green;"></span> Selecciona un parcial:
                    </label>
                    <select class="form-control" id="parcial" name="parcial">
                        <option value="">N/A</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>                    
                    </select> <br> <br>
                    <h4><span class="glyphicon glyphicon-tasks" style="font-size: 20px; color: green;"></span> Actividades del Alumno</h4>
                </div>
            </div>
            <!-- actividades -->
            <div id="tabla-actividades">
                <h4 class="text-center">Selecciona un parcial para ver las actividades y exámenes.</h4>
            </div>
            <!-- examenes -->
             <h4><span class="glyphicon glyphicon-tasks" style="font-size: 20px; color: green;"></span> Exámenes del Alumno</h4>
            <div class="panel-body" id="tabla-examenes"><div class="panel panel-default" id="panel-examenes" style="display:none;">
                <div class="panel-heading">
                    
                </div>
                <div class="panel-body" id="tabla-examenes">
                    <!-- Aquí se pintarán los exámenes -->
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectParcial = document.getElementById('parcial');
    const contenedor = document.getElementById('tabla-actividades');
    const matricula = '<?= $row->vchMatricula ?>';
    const periodo= '<?= $row->vchPeriodo; ?>';
    const idgrupo='<?= $row->chvGrupo; ?>';
    const clvcuatri= '<?= $row->vchClvCuatri; ?>';

    selectParcial.addEventListener('change', function () {
        const parcial = this.value;
        cargarExamenes(matricula, periodo, clvcuatri, idgrupo);

        if (!parcial) {
            contenedor.innerHTML = '<p class="text-center">Selecciona un parcial para ver las actividades.</p>';
            return;
        }

        fetch(`<?= site_url('/sysmater/director/detalles_alumno/cargar_actividades/') ?>${matricula}/${parcial}/${periodo}`)
            .then(response => response.json())
            .then(data => {
                if (data.hay_actividades) {
                    let html = '';

                    data.materias_agrupadas.forEach((materia, index) => {
                        const materiaId = `materia-${index}`;
                        html += `
                            <div class="materia-card" onclick="toggleActividades('${materiaId}')">
                                <h4><span class="glyphicon glyphicon-book"></span> ${materia.nombre}</h4>
                                <div id="${materiaId}" class="actividades-lista">
                        `;

                        materia.actividades.forEach(act => {
                            html += `
                                <div class="actividad-card">
                                    <strong>${act.titulo}</strong><br>
                                    <small><strong>Entrega:</strong> ${act.fecha_entrega_formateada}</small><br>
                                    <span class="label ${act.clase_estado}">${act.nombre_estado}</span><br>
                                    <small><strong>Modalidad:</strong> ${act.texto_modalidad}</small><br>
                                </div>
                            `;
                        });

                        html += `</div></div>`;
                    });

                    contenedor.innerHTML = html;
                } else {
                    contenedor.innerHTML =  `<div class="alert alert-info text-center" role="alert">
                        No hay actividades registrados de este alumno.
                    </div>`;
                }
            })
            .catch(error => {
                console.error('Error al cargar actividades:', error);
                contenedor.innerHTML = '<p class="text-center text-danger">Ocurrió un error al cargar las actividades.</p>';
            });
    });

    // EXAMENES FETCH
    function cargarExamenes(matricula, periodo, clvcuatri, idgrupo) {
    const url = `<?= site_url('/sysmater/director/detalles_alumno/cargar_examenes/') ?>${matricula}/${periodo}/${clvcuatri}/${idgrupo}`;

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            const contenedorExamenes = document.getElementById('tabla-examenes');
            const panelExamenes = document.getElementById('panel-examenes');

            if (data.hay_examenes && Array.isArray(data.examenes) && data.examenes.length > 0) {
                // Agrupar exámenes por materia
                const materias = {};

                data.examenes.forEach(examen => {
                    const clave = examen.vchClvMateria;
                    if (!materias[clave]) {
                        materias[clave] = {
                            nombre: examen.vchNomMateria,
                            examenes: []
                        };
                    }
                    materias[clave].examenes.push(examen);
                });

                let html = '';
                Object.keys(materias).forEach(clave => {
                    const mat = materias[clave];
                    html += `
                        <div class="examen-materia">
                            <h4><span class="glyphicon glyphicon-book"></span> ${mat.nombre}</h4>
                    `;
                    mat.examenes.forEach(ex => {
                        html += `
                            <div class="examen-card">
                                <strong>${ex.titulo}</strong><br>
                                <small><strong>Parcial:</strong> ${ex.parcial}</small><br>
                                <small><strong>Calificación:</strong> ${ex.calificacion}</small><br>
                                <small><strong>Aciertos:</strong> ${ex.aciertos} / ${ex.total_reactivos}</small>
                            </div>
                        `;
                    });
                    html += `</div>`;
                });

                contenedorExamenes.innerHTML = html;
                //panelExamenes.style.display = 'block';
            } else {
                contenedorExamenes.innerHTML = `
                    <div class="alert alert-warning text-center" role="alert">
                        No hay exámenes registrados de este alumno.
                    </div>
                `;
                //panelExamenes.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error al cargar exámenes:', error);
            document.getElementById('tabla-examenes').innerHTML = `
                <div class="alert alert-danger text-center" role="alert">
                    Ocurrió un error al cargar los exámenes. Intenta nuevamente.
                </div>
            `;
        });
}


    });

function toggleActividades(id) {
    const elem = document.getElementById(id);
    elem.style.display = (elem.style.display === 'none' || elem.style.display === '') ? 'flex' : 'none';
}
</script>
