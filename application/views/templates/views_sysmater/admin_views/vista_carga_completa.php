<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carga de Preguntas</title>
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/carga_reactivos.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/forms.css">
    <script>
        function redirigir() {
            window.location.href = '<?= site_url('/sysmater/admin/admin/carga_reactivos_examen') ?>';
        }
    </script>
</head>
<body>
    <div id="box">
        <center>
            <h2>CARGA COMPLETA</h2><br><br>
            <img src=" <?= base_url() ?>assets/animationS/CargaCompleta.png" alt="Imagen" style="width: 140px; height: 140px; margin: 0 auto 20px; display: block;"><br><br>
            <button type="button" onclick="redirigir()">Continuar</button>
        </center>
    </div>
</body>
</html>
