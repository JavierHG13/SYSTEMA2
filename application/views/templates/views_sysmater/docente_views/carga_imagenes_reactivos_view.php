
<link rel="stylesheet" href="<?= base_url() ?>assets/css/carga_reactivos.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/forms.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.4/lottie.min.js"></script>
    <style>
  .file-label {
    border: 2px dashed #007bff;
    padding: 8px;
    border-radius: 4px;
    text-align: center;
    cursor: pointer;
    display: inline-block;
    width: 100%;
    max-width: 600px;
    margin-bottom: 10px;
  }

  #archivo {
    display: none;
  }
</style>
<div id="box">
    <h2 class="text-center">Cargar Imagenes Para Reactivos</h2>
    <div id="animationContainer"></div>
        <form id="uploadForm" action="<?= site_url('/sysmater/docente/docente/cargarImagenesReactivos') ?>" method="POST" enctype="multipart/form-data">
             <label for="archivo" class="file-label">
                <span id="file-name">Seleccionar archivo...</span>
                </label>
            <input type="file" name="imagenes[]" id="archivo" accept=".png, .jpg, .jpeg" multiple>
            <button type="submit" class="btn btn-primary">
                 <span class="glyphicon glyphicon-ok"></span> Subir Imágenes</button>
        </form>
    </div>
</div>
    <div id="overlay">
        <div id="focusedAnimationContainer"></div>
    </div>
<script>
    var baseUrl = '<?= base_url() ?>';
</script>
<script src="<?= base_url() ?>assets/js/animacionCargaReactivos.js"></script>
<script>
 document.getElementById('archivo').addEventListener('change', function () {
  const fileList = this.files;
  const fileNames = [];

  for (let i = 0; i < fileList.length; i++) {
    fileNames.push(fileList[i].name);
  }

  document.getElementById('file-name').textContent = fileNames.length > 0 
    ? fileNames.join(', ')
    : 'Seleccionar archivo...';
});

</script>