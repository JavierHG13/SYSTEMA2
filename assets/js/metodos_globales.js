// confirm delete the test for delete_test.php
function confirm_exit()
{
	if (confirm("¿Realmente desea salir?"))
	{ 
		return true;
	}
	else
	{ 
		return false;
	}

}// end function
function mayuscula(campo){
    $(campo).keyup(function() {
       $(this).val($(this).val().toUpperCase());
    });
}

function validaForm(form){
    var error=0;

    //VALIDAD LOS CONTROLES PARA QUE NO ESTEN VACIOS
    $("form"+form+" textarea.form-control[required],form"+form+" input.form-control[required] ").each(function(i, elem){
        if($.trim($(elem).val()).length==0)
        {
            $(elem).next("span").remove();
            $(elem).parent().addClass("has-error has-feedback").append('<span id="icon_alerta" class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
            error++;
            $(elem).bind( "click", function(){
	            $(elem).parent().removeClass("has-error has-feedback")
	            $(elem).parent().removeClass("has-success has-feedback");
	            $(elem).next("span").remove();
	            //$(elem).parent().append('<span id="icon_alerta" class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>');
	       	});
        }
        else{
            $(elem).parent().removeClass("has-error has-feedback").addClass("has-success has-feedback");
            $(elem).next("span").remove();
            $(elem).parent().append('<span id="icon_alerta" class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>');

            $(elem).unbind( "click" );
        }
    });
     //VALIDAD LOS CONTROLES PARA QUE NO ESTEN VACIOS

    
    return error;
}

function guardarModal(form,validar)
{
    //event.preventDefault();
    var error=0;
    if(validar == undefined)
      {
        // si no se especifica nombre
        validar = 1;
      }
      
    if (validar == 1)
        error=validaForm(form);

    //SI NO EXISTEN ERRORES
    if (error==0){
 		$(form+' #task').val('save');
        $(form).submit();
    }
    else{
    	$(form+' #task').val('save');
    }
}


///FORMULARIO DE CAMBIO DE CONTRASEÑA
$(document).ready(function(){
    $("#form_passwd #cmd_guardar").click(function(){
        guardarModal("#form_passwd",1);
    });
});