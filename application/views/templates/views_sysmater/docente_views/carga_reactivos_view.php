
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/carga_reactivos.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.4/lottie.min.js"></script>
        
<div id="contenedor_carga_reactivos">
        <h2 id="encabezado">Cargar reactivos para examen</h2>
        <div id="animationContainer"></div>
        <form id="uploadForm" action="<?= site_url('/sysmater/docente/docente/carga_reactivos_examen') ?>" method="POST" enctype="multipart/form-data">
            <label for="file">Archivo Excel</label>
            <input type="file" name="archivo" accept=".xlsx, .xls" required>
            <input type="hidden" name="nombresImagenes" value='<?= htmlspecialchars(json_encode($nombresImagenes), ENT_QUOTES, 'UTF-8') ?>'>
            <button type="submit" name="submit">Cargar</button>
        </form>

    </div>

    <div id="overlay">
        <div id="focusedAnimationContainer"></div>
    </div>
    <script>
        var baseUrl = '<?= base_url() ?>';
    </script>

    
    <script src="<?= base_url() ?>assets/js/animacionCargaReactivos.js"></script>

