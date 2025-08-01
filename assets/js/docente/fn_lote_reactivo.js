$('#btn_guardar').click(function(){
	guardarModal('#form_lote');
});
$(document).ready(function() {
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