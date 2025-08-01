<link rel="stylesheet" href="<?= base_url() ?>assets/css/forms.css">

<?php if (isset($aplicacion) && !empty($aplicacion)) : ?>
    <?php foreach ($aplicacion->result() as $datos) : ?>
        <?php $titulo = $datos->nvch_Titulo; ?>
        <?php $id_examen = $datos->id_examen; ?>
        <?php $fch_inicia = $datos->fch_inicia; ?>
        <?php $fch_final = $datos->fch_final; ?>
        <?php $tm_hora_inicio = $datos->tm_hora_inicio; ?>
        <?php $tm_hora_final = $datos->tm_hora_final; ?>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (isset($gruposs) && !empty($gruposs)) : ?>
    <?php foreach ($gruposs->result() as $datos_g) : ?>
        <?php $grupo = $datos_g; ?>
    <?php endforeach; ?>
<?php endif; ?>

<div id="box">
    <center>
        <h2>¿Desea eliminar la programación del examen?</h2>
        <h4 style="color: #4A5C42;"><?= $titulo ?> para Grupo - <?= $grupo ?></h4>
    </center>
    
    <?php 
    $attributes = array(
        "class" => "form-horizontal",
        "id" => "form_examen",
        "name" => "form_examen",
        "method" => "POST"
    );
    ?>
    
    <?= form_open("/sysmater/docente/elimina_programacion/elimina_programacion_grupo/{$id_examen}/{$grupo}", $attributes); ?>
    
    <div style="display: flex; justify-content: center;">
        <table style="border-collapse: collapse; border: none;">
            <!-- Fecha de inicio -->
            <tr>
                <th style="text-align: left; padding: 4px 8px; font-weight: normal;">
                    <b>Fecha inicio:</b>
                </th>
                <td style="text-align: left; padding: 4px 8px;">
                    <?php $date = date_create($fch_inicia) ?>
                    <?= date_format($date, 'd/m/Y'); ?>
                </td>
            </tr>
            
            <!-- Fecha final -->
            <tr>
                <th style="text-align: left; padding: 4px 8px; font-weight: normal;">
                    <b>Fecha final:</b>
                </th>
                <td style="text-align: left; padding: 4px 8px;">
                    <?php $date = date_create($fch_final) ?>
                    <?= date_format($date, 'd/m/Y'); ?>
                </td>
            </tr>
            
            <!-- Hora de inicio -->
            <tr>
                <th style="text-align: left; padding: 4px 8px; font-weight: normal;">
                    <b>Hora inicio:</b>
                </th>
                <td style="text-align: left; padding: 4px 8px;">
                    <?php $date = date_create($tm_hora_inicio) ?>
                    <?= date_format($date, 'H:i'); ?>
                </td>
            </tr>
            
            <!-- Hora final -->
            <tr>
                <th style="text-align: left; padding: 4px 8px; font-weight: normal;">
                    <b>Hora final:</b>
                </th>
                <td style="text-align: left; padding: 4px 8px;">
                    <?php $date = date_create($tm_hora_final) ?>
                    <?= date_format($date, 'H:i'); ?>
                </td>
            </tr>
        </table>
    </div>
    
    <br>
    
    <center>
        <div class="form-group row">
            <div class="">
                <button type="submit" class="btn btn-success">
                    <span class="glyphicon glyphicon-ok"></span> Eliminar
                </button>
                <a href="<?= site_url('/sysmater/docente/docente/examenes_programados/') ?>" class="btn btn-danger">
                    <span class="glyphicon glyphicon-remove"></span> Cancelar
                </a>
            </div>
        </div>
    </center>
    
    <?= form_close(); ?>
</div>