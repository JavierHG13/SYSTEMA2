<link rel="stylesheet" href="<?= base_url() ?>assets/css/forms.css">

<div id="box">
    <center>
        <h2>¿Desea eliminar el examen?</h2>
        <?php if ($examen && $examen->num_rows() > 0): ?>
            <h4 style="color: #4A5C42;"><?= $examen->row()->nvch_Titulo ?></h4>
        <?php endif; ?>
    </center>
    
    <?php 
    $attributes = array(
        "class" => "form-horizontal",
        "id" => "form_examen",
        "name" => "form_examen",
        "method" => "POST"
    );
    
    if ($examen && $examen->num_rows() > 0) {
        $row = $examen->row();
    ?>
    
    <?= form_open("/sysmater/docente/elimina_examen/index/" . $row->id_examen, $attributes); ?>
    
    <div style="display: flex; justify-content: center;">
        <table style="border-collapse: collapse; border: none;">
            <tr>
                <th style="text-align: left; padding: 4px 8px; font-weight: normal;">
                    <b>ID Examen:</b>
                </th>
                <td style="text-align: left; padding: 4px 8px;">
                    <?= $row->id_examen ?>
                </td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 4px 8px; font-weight: normal;">
                    <b>Materia:</b>
                </th>
                <td style="text-align: left; padding: 4px 8px;">
                    <?= $row->vchNomMateria ?>
                </td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 4px 8px; font-weight: normal;">
                    <b>Título:</b>
                </th>
                <td style="text-align: left; padding: 4px 8px;">
                    <?= $row->nvch_Titulo ?>
                </td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 4px 8px; font-weight: normal;">
                    <b>Reactivos requeridos:</b>
                </th>
                <td style="text-align: left; padding: 4px 8px;">
                    <?= $row->int_reactivos_requeridos ?>
                </td>
            </tr>
        </table>
    </div>
    
    <br>
    
    <center>
        <div class="form-group row">
            <div class="">
                <input type="hidden" id="task" name="task" value="delete">
                <input type="hidden" id="id_examen" name="id_examen" value="<?= $row->id_examen ?>">
                <button type="submit" class="btn btn-success">
                    <span class="glyphicon glyphicon-ok"></span> Eliminar
                </button>
                <a href="<?= site_url('/sysmater/docente/docente/examenes_registrados') ?>" class="btn btn-danger">
                    <span class="glyphicon glyphicon-remove"></span> Cancelar
                </a>
            </div>
        </div>
    </center>
    
    <?= form_close(); ?>
    
    <?php 
    } else {
        echo "<div class='alert alert-danger text-center'><b>Error:</b> No se encontró el examen con el ID recibido.</div>";
    }
    ?>
    <?php if (isset($error) && $error): ?>
    <div class="alert alert-danger text-center"> 
        ¡Lo sentimos! No se puede eliminar este examen.
    </div>
<?php endif; ?>
</div>



<script>
    setTimeout(function() {
        const alert = document.querySelector('.alert');
        if (alert) setTimeout(() => alert.remove(), 500);
    }, 5000);
</script>