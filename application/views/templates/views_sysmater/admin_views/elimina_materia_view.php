<link rel="stylesheet" href="<?= base_url() ?>assets/css/forms.css">
<div id="box">
    <center>
        <h2>¿Desea eliminar esta materia?</h2>
    </center>
    <?php 
    $attributes = array(
        "class" => "form-horizontal",
        "id" => "form_elimina_materia",
        "name" => "form_elimina_materia",
        "method" => "POST"
    );
    ?>
    <?php 
				$row = $materia->row();
	?>
    <?= form_open("/sysmater/admin/elimina_materia/index/" . $row->vchClvMateria, $attributes); ?>
    
    		<div style="display: flex; justify-content: center;">
  <table style="border-collapse: collapse; border: none;">
    <tr>
      <th style="text-align: left; padding: 4px 8px; font-weight: normal;">
        <b>Clave:</b>
      </th>
      <td style="text-align: left; padding: 4px 8px;">
        <?= $row->vchClvMateria ?>
      </td>
    </tr>
    <tr>
      <th style="text-align: left; padding: 4px 8px; font-weight: normal;">
        <b>Nombre:</b>
      </th>
      <td style="text-align: left; padding: 4px 8px;">
        <?= $row->vchNomMateria ?>
      </td>
    </tr>
    <tr>
      <th style="text-align: left; padding: 4px 8px; font-weight: normal;">
        <b>Periodo:</b>
      </th>
      <td style="text-align: left; padding: 4px 8px;">
        <?= $row->idPeriodo ?>
      </td>
    </tr>
    <tr>
      <th style="text-align: left; padding: 4px 8px; font-weight: normal;">
        <b>Cuatrimestre:</b>
      </th>
      <td style="text-align: left; padding: 4px 8px;">
        <?= $row->vchCuatrimestre ?>
      </td>
    </tr>
  </table>
</div> <br>

    
<center>

	<div class="form-group row">
		<div class="">
            <input type="hidden" id="task" name="task" value="delete">
            <input type="hidden" id="id_materia" name="id_materia" value="<?= $materia->vchClvMateria ?>">
            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span>  Eliminar</button>
            <a href="<?= site_url('/sysmater/admin/admin/lista_materias') ?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Cancelar</a>
        </div>
    </div>
<?php if (isset($error) && $error): ?>
    <div class="alert alert-danger text-center"> 
        ¡Lo sentimos! No se puede eliminar esta materia.
	</div>
<?php endif; ?>
<script>
    setTimeout(function() {
        const alert = document.querySelector('.alert');
        if (alert) setTimeout(() => alert.remove(), 500);
    }, 4000);
</script>




</div>

