<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            background-color: #ffffff;
            border: 1px solid #ddd;
            padding: 20px;
            margin: 16px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
        }
        .card h2 {
            margin-top: 0;
            font-size: 24px;
            color: #333;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #eee;
        }
        th, td {
            padding: 12px;
            text-align: left;
            font-size: 16px;
        }
        th {
            background-color: #f1f1f1;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .action-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }
        .action-btn i {
            margin-right: 8px;
        }
        .activate {
            background-color: #28a745;
            color: white;
        }
        .activate:hover {
            background-color: #218838;
        }
        .deactivate {
            background-color: #dc3545;
            color: white;
        }
        .deactivate:hover {
            background-color: #c82333;
        }
        .status {
            display: flex;
            align-items: center;
        }
        .status i {
            margin-right: 8px;
        }
        .online {
            color: green;
        }
        .offline {
            color: red;
        }
        .no-systems {
            text-align: center;
            font-size: 18px;
            color: #888;
            padding: 20px 0;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Sistemas Activos</title>
</head>
<center>
    <div class="card">
        <h2>Sistemas Activos</h2>
        <table>
            <thead>
                <tr>
                    <th>Nombre del Sistema</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($systemas)) : ?>
                    <?php foreach ($systemas as $systema) : ?>
                        <?php 
                            $buttonText = $systema->Activo ? "Desactivar" : "Activar";
                            $buttonClass = $systema->Activo ? "deactivate" : "activate";
                            $iconClass = $systema->Activo ? "fa fa-times" : "fa fa-check";
                            $statusText = $systema->Activo ? "Online" : "Offline";
                            $statusClass = $systema->Activo ? "online" : "offline";
                            $statusIcon = $systema->Activo ? "fa fa-circle" : "fa fa-circle";
                        ?>
                        <tr>
                            <td><?= $systema->vch_NomSystema; ?></td>
                            <td class="status <?= $statusClass; ?>">
                                <i class="<?= $statusIcon; ?>"></i><?= $statusText; ?>
                            </td>
                            <td>
                                <form method="post" action="<?= site_url('/sysmater/admin/activacion_sistema/update_status'); ?>">
                                    <input type="hidden" name="id_systema" value="<?= $systema->id_systema; ?>">
                                    <input type="hidden" name="new_status" value="<?= $systema->Activo ? 0 : 1; ?>">
                                    <button type="submit" class="action-btn <?= $buttonClass; ?>">
                                        <i class="<?= $iconClass; ?>"></i><?= $buttonText; ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3" class="no-systems">No se encontraron sistemas</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</center>
