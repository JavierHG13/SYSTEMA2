<div id="box">
	<div class="alert alert-danger" role="alert">
		<strong><?php echo $error; ?> </strong> 
		<p><?php echo $msg ?></p>
	</div>

	<div class="form-group row">
		<div class="col col-sm-12">
	 		<a href="<?= site_url('/docente/docente/subir_lote/') ?>" class="btn btn-danger"> Regresar</a>
		</div>
	</div>
</div>