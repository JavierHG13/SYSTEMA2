
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
    <h2 class="text-center">Cargar Alumnos Para Sysmater</h2>
    <div id="animationContainer"></div>
    <form id="uploadForm" action="<?= site_url('/sysmater/admin/admin/carga_alumnos') ?>" method="POST" enctype="multipart/form-data">
        <label for="archivo" class="file-label">
            <span id="file-name">Seleccionar archivo...</span>
        </label>
        <input type="file" name="archivo" id="archivo" accept=".xlsx, .xls" required>
        <button type="submit" name="submit" class="btn btn-primary"><span class="glyphicon glyphicon-ok"></span>  Cargar alumnos</button>
    </form>
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
    const nombre = this.files[0] ? this.files[0].name : 'Seleccionar archivo...';
    document.getElementById('file-name').textContent = nombre;
  });
</script>
