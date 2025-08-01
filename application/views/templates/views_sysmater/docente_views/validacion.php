<!-- ALEXIS YAZIR -->
<link rel="stylesheet" href="<?= base_url() ?>assets/css/forms.css">
    <div id="box">		
		<div class="header-section mb-4 mt-4 p-3" style=" border-radius: 5px; padding-top:1px; padding-bottom:1px; margin-bottom:20px;">
			<h2 class="text-center"><b>¡ Ocurrio un error  !</b></h2>
        </div>

        <?php if (isset($msg)): ?>
            <div class="alert alert-danger text-center">
               <b><span class="glyphicon glyphicon-info-sign"></span>  <?= $msg ?> </b>
            </div>
        <?php endif; ?>
        <center>
            <div class="form-group row">
                <div class="">
                    <a href="<?= site_url('/sysmater/docente/docente/examenes_programados/') ?>" class="btn btn-danger">
                        <span class="glyphicon glyphicon-th-list"></span> Ver exámenes programados
                    </a>
                </div>
            </div>
        </center>
    </div>