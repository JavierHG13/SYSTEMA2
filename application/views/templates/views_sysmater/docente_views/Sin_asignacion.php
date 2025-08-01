<style>
      #box {
        background-color: #fff;
        border-radius: 8px;
        padding: 20px;
        margin: 20px auto;
        max-width: 1100px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    p{
        max-width: 800px;
    }
</style>

    <div id="box">
        <div class="form-group row">
                <h3 class="text-center">Sin materias asignadas</h3>
                <center>
                    <p class="alert alert-info ">No tiene materias asignadas para programar exámenes en el periodo actual. <br>
                     Pongase en contacto con su administrador. </p>
                </center>
                <div class="text-center">
                    <a href="<?= site_url('/sysmater/docente/docente/examenes_registrados/') ?>" class="btn btn-danger">
                    <span class="glyphicon glyphicon-chevron-left"></span>  Regresar
                </a>
                </div>
        </div>
        
    </div>
