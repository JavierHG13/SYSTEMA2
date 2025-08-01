<style>
    :root {
        --primary-color: #3c903eff;
        --secondary-color: #F0FAF2;
        --hover-color: #3e8e41;
        --border-color: #e0e0e0;
        --text-color: #333;
    }

    .btns {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        border: none;
        gap: 8px;
    }

    .table-container {
        overflow-x: auto;
        margin-top: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .cd {
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    th {
        background-color: var(--primary-color);
        color: white;
        font-weight: 600;
        padding: 14px 16px;
        text-align: left;
    }

    td {
        padding: 12px 16px;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }

    tr:nth-child(even) {
        background-color: var(--secondary-color);
    }

    tr:hover {
        background-color: rgba(76, 175, 80, 0.08);
    }


    .btn-group-xs .btn {
        padding: 6px;
    }

    .no-records {
        text-align: center;
        padding: 20px;
        font-size: 15px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        body {
            padding: 15px;
        }

        th,
        td {
            padding: 10px 12px;
            font-size: 0.9rem;
        }

    }

    @media (max-width: 576px) {
        .btn-group {
            flex-direction: column;
            gap: 4px;
        }

        .btn-group-xs .btn {
            width: 100%;
            justify-content: flex-start;
        }
    }

    .form-control {
        border: 1px solid gray;
        border-radius: 4px;
        max-width: 90px;
    }

    .form-control:focus {
        box-shadow: 0 0 0 0.3rem rgba(69, 151, 213, 0.25);
    }
</style>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb bg-light p-2 rounded">
        <li class="breadcrumb-item">
            <a href="javascript:history.back()">Mis materias</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Grupos</li>
    </ol>
</nav>

<div class="cd">
    <!-- Panel de la materia -->
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">Información del la Materia</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <p><b>Materia:</b> <?= htmlspecialchars($materia->vchNomMateria) ?></p>
                    <p><b>Carrera:</b> <?= htmlspecialchars($alumno->vchNomCarrera) ?></p>
                </div>
                <div class="col-md-6">
                    <p><b>Cuatrimestre:</b> <?= htmlspecialchars($materia->vchNomCuatri) ?></p>
                    <p><b>Periodo:</b> <?= htmlspecialchars($periodo) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Título de Grupos -->
    <div class="container">
        <div class="text-center mb-4">
            <h3>
                <i class="fa fa-users"></i> Grupos asignados
            </h3>
        </div>

        <!-- Tabla de Grupos -->

        <div class="table-container">
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>ID Grupo</th>
                                <th>Nombre del Grupo</th>
                                 <th>Alumnos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($grupos && $grupos->num_rows() > 0) : ?>
                                <?php foreach ($grupos->result() as $grupo) : ?>
                                    <tr>
                                        <td class="text-center"><?= $grupo->id_grupo; ?></td>
                                        <td class="text-center"><?= $grupo->vchGrupo; ?></td>
                                        <td class="text-center"><?= $grupo->estudiantes; ?></td>
                                        <td class="text-center">
                                            <a href="<?= site_url('sysmater/docente/docente/ver_actividades/' . $grupo->id_grupo . '/' . $vchClvMateria) ?>"
                                                class="btn btn-success btn-sm mb-1" title="Ver actividades">
                                                <i class="fa fa-eye"></i> Ver actividades
                                            </a>
                                            <a href="<?= site_url('sysmater/docente/docente/gestionar_equipos/' . $grupo->id_grupo . '/' . $vchClvMateria) ?>"
                                                class="btn btn-primary btn-sm mb-1" title="Gestionar equipos">
                                                <i class="fa fa-users"></i> Gestionar equipos
                                            </a>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No se encontraron grupos asignados.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</div>