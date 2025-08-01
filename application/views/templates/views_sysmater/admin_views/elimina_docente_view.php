<link rel="stylesheet" href="<?= base_url() ?>assets/css/forms.css">
<div id="box">
    <center>
        <h2>¿Desea eliminar al docente?</h2>
        <h4 style="color: #4A5C42;"><?= $row->vchNombre.' '.$row->vchAPaterno.' '.$row->vchAMaterno ?></h4>
    </center>
    
    <?php 
    $attributes = array(
        "class" => "form-horizontal",
        "id" => "form_elimina_docente",
        "name" => "form_elimina_docente",
        "method" => "POST"
    );
    ?>
    <?php $row = $docente->row(); ?>
    
    <?= form_open("/sysmater/admin/elimina_docente/index/" . $row->vchClvTrabajador, $attributes); ?>
    
    <div style="display: flex; justify-content: center;">
        <table style="border-collapse: collapse; border: none;">
            <tr>
                <th style="text-align: left; padding: 4px 8px; font-weight: normal;">
                    <b>Nombre:</b>
                </th>
                <td style="text-align: left; padding: 4px 8px;">
                    <?= $row->vchNombre ?>
                </td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 4px 8px; font-weight: normal;">
                    <b>Apellido Paterno:</b>
                </th>
                <td style="text-align: left; padding: 4px 8px;">
                    <?= $row->vchAPaterno ?>
                </td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 4px 8px; font-weight: normal;">
                    <b>Apellido Materno:</b>
                </th>
                <td style="text-align: left; padding: 4px 8px;">
                    <?= $row->vchAMaterno ?>
                </td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 4px 8px; font-weight: normal;">
                    <b>Clave del Trabajador:</b>
                </th>
                <td style="text-align: left; padding: 4px 8px;">
                    <?= $row->vchClvTrabajador ?>
                </td>
            </tr>
        </table>
    </div>
    
    <br>
    
    <center>
        <div class="form-group row">
            <div class="">
                <input type="hidden" id="task" name="task" value="delete">
                <input type="hidden" id="id_docente" name="id_docente" value="<?= $docente->vchClvTrabajador ?>">
                <button type="submit" class="btn btn-success">
                    <span class="glyphicon glyphicon-ok"></span> Eliminar
                </button>
                <a href="<?= site_url('/sysmater/admin/admin/lista_docente') ?>" class="btn btn-danger">
                    <span class="glyphicon glyphicon-remove"></span> Cancelar
                </a>
            </div>
        </div>
    </center>
    
    <?= form_close(); ?>
    <?php if (isset($error) && $error): ?>
        <div class="alert alert-danger text-center"> 
            ¡Lo sentimos! No se puede eliminar este profesor.
        </div>
    <?php endif; ?>
</div>


<script>
    setTimeout(function() {
        const alert = document.querySelector('.alert');
        if (alert) setTimeout(() => alert.remove(), 500);
    }, 5000);
</script>