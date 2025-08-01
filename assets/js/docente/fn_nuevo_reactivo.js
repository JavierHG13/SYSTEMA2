

$('#btnDelete').click(function(){
	var tam = $('#tabs li').length;
	if (tam>1) {
		$('#tab'+tam).remove();
		$('#refTab'+tam).remove();
		// make the new tab active
  		$('#tabs a:last').tab('show');
	} else{
		$('#planteamiento').attr("required", false);
	}

});
$('#btnAdd').click(function (e) {
  	var nextTab = $('#tabs li').length+1;
	
  	// create the tab
  	$('<li  id="refTab'+nextTab+'"><a href="#tab'+nextTab+'" data-toggle="tab">Reactivo '+nextTab+'</a></li>').appendTo('#tabs');
  	
  	// create the tab content
  	$('<div class="tab-pane" id="tab'+nextTab+'">'+'</div>').appendTo('.tab-content');
  	bankTab.clone().appendTo('#tab'+nextTab);
  	$('#tab'+nextTab).find('input').val('');    
  	$('#tab'+nextTab).find('textarea').val('');    

  	$('#tabs a:last').tab('show');
  	$('#planteamiento').attr("required", true);

  	//DesactivaEditor();
  //	HabilitaEditor();
  	/*if (confirm("habilitar editor")) {
  	}*/
  	asigna_eventos();
  	// make the new tab active

});

function asigna_eventos(){
	$("input#horas").each(function(e){
		$(this).keydown(function (e) {
	        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 || (e.keyCode == 65 && e.ctrlKey === true) || (e.keyCode >= 35 && e.keyCode <= 39)) {
	                 return;
	        }
	        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
	            e.preventDefault();
	        }
	    });	
	});
   
 	$("input#minutos").each(function(){
    $(this).keydown(function (e) {
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 || (e.keyCode == 65 && e.ctrlKey === true) || (e.keyCode >= 35 && e.keyCode <= 39)) {
                 return;
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
 	 }); 
}


$('#btn_guardar').click(function(){
	guardarModal('#form_reactivo');
});



function HabilitaEditor(){
	tinymce.init({
		selector: 'textarea#editor',
		menubar: false,
		plugins: [
		    'table contextmenu paste code lists charmap visualblocks  '
		],
		toolbar: 'table | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist |',
		content_css: '//www.tinymce.com/css/codepen.min.css'
	});
}

function DesactivaEditor(){
	tinymce.remove();
}

//evento al inicio de la myModal
$('#myModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('titulo'); // Extract info from data-* attributes
  //OBTIENE Y ESTABLECE EL TITULO DE LA myModal
  var modal = $(this)
  modal.find('.modal-title').text(recipient)

  var textarea=button.parent().parent().find('textarea');

	tinyMCE.activeEditor.setContent(textarea.val());
  //evento del boton guardar
  $('#myModal #cmd_guardar').click(function(){
     //Cierra la ventana del modal
     event.preventDefault();
     textarea.val(tinyMCE.activeEditor.getContent());
     $('#myModal').modal('hide');
     
  });
});

//evento al cierre de la ventana
$('#myModal').on('hide.bs.modal',function(event){
     $('#myModal #cmd_guardar').unbind("click");
});


$(document).ready(function() {
	bankTab=$("#ctrl_reactivo").clone();
	HabilitaEditor();
  asigna_eventos();
   $("#carrera").change(function() {
  	$("#carrera option:selected").each(function() {
  		carrera = $('#carrera').val();
  		$.post(base_url+"/egel/docente/docente/ajax_llena_cuatrimestres/", { carrera : carrera }, function(data) {
  			$("#cuatrimestre").html(data);
  		})
  		.done(function() {
  		  $("#cuatrimestre").change();
  		});
  	});
  });

  $("#cuatrimestre").change(function() {
  	$("#carrera option:selected").each(function() {
  		carrera = $('#carrera').val();
  		cuatrimestre = $('#cuatrimestre').val();
  		$.post(base_url+"/egel/docente/docente/ajax_llena_materias/", { carrera : carrera, cuatrimestre : cuatrimestre }, function(data) {
  			$("#materia").html(data);
  		});
  	});
  });
});
