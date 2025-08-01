/*
 * methods used in test.php
 * 06/07/2016
 */

// variable global
// array for the questions
var array = new Array();
// accountant
var contador = 1;
// runs the array
var recorre = 0;
// number of questions
var num_ques = 0;
// id_test
var id;
// matricula
var matricula = "";
//time
var tiempo = "";

// get questions of the database
function upload_questions(id_test)
{
	id = id_test;
	matricula = String($("#matricula").text());
	especialidad= String($("#especialidad").val());

	var url =  base_url+'/egel/alumno/start_test/get_questions/' + id+'/'+matricula;
	$.ajax({
		type: 'GET',
		url: url,
		dataType:'json',
		success: function(data)
		{
		
			array = data;
			num_ques = array.length;
			
			_time_left(id_test);

			i=0;
			do{
				i++;
			}while(array[i].Respuesta!=null&& i<num_ques);
			
			contador = i;
			recorre = i - 1;

			_change(contador, recorre);
			_mostrar_numero();
			
		},
		error: function()
		{
			alert('Error al cargar las preguntas.');
		}
	});// end function ajax
}// end function

$(document).ready(function(){
	 $('.nav-tabs li a').click(function(event){
        if ($(this).parent('li').hasClass('disabled')) {
            return false;
        }
    });
	$("input:radio").each(function(){
	 	$(this).click(function(){
	 		$("#Si").show();
	 	});
	});
	$(document).keydown(function(tecla){
	 	if (tecla.keyCode==65) {
	 		_change_value_radio("A");
	 	}else if (tecla.keyCode==66) {
	 		_change_value_radio("B");
	 	}else if (tecla.keyCode==67) {
	 		_change_value_radio("C");
	 	}else if (tecla.keyCode==68) {
	 		_change_value_radio("D");
	 	}else if (tecla.keyCode==73) {
	 		start();
	 	}else if (tecla.keyCode==78) {
	 		previous();
	 	}else if (tecla.keyCode==83) {
	 		next();
	 	}else if (tecla.keyCode==69) {
	 		finish_test();
	 	}
		console.log(tecla.keyCode);
	});
});

function _time_left(id_test)
{

	var url =  base_url+'/egel/alumno/start_test/get_time/' + matricula;
	$.ajax({
		type: 'GET',
		url: url,
		dataType:'json',
		success: function(data)
		{
			var time = data;
			_change_time_left(time[0].time_left);
		},
		error: function()
		{
			alert('Error al cargar tiempo restante.');
		}
	});// end function ajax
}// end function

// remaining time to answer the exam
function _change_time_left(time)
{
	if (time > 0)
	{
		var seconds = Math.round( time/1000 ); 
		var seconds_s = seconds%60;
		var minutes = Math.floor( seconds/60 ); 
		var minutes_s = minutes%60; 
		var hours = Math.floor( minutes/60 ); 
		var hours_s = hours%24; 
		tam_sec = String(seconds_s).length;
		tam_min = String(minutes_s).length;
		if (tam_min == 1)
		{
			minutes_s = "0" + minutes_s;
		}
		if (tam_sec == 1)
		{
			seconds_s = "0" + seconds_s;
		}
		if(hours_s.length==1)
			tiempo = "0" + hours_s + ":" +minutes_s + ":" + seconds_s + "";
		else
			tiempo = hours_s + ":" +minutes_s + ":" + seconds_s + "";
		$( "#Cronometro" ).text(tiempo);
		time = time-1000;
	} 
	if (time <= 0)
	{
		tiempo = "00:00:00";
		_save_response(recorre);
		alert("Tiempo agotado");
		location = (base_url+"/login/logout");
	}
	else
	{
		setTimeout("_change_time_left("+time+")", 1000);
	}
}// end function

function _change(c, r)
{
	_clear_radios();
	$("#numPre").text(c);
	$("#Pregunta").html(array[r].txt_base);
	$("#textA").text(array[r].nvch_opcionA);
	$("#textB").text(array[r].nvch_opcionB);
	$("#textC").text(array[r].nvch_opcionC);
	$("#textD").text(array[r].nvch_opcionD);

	if (array[r].txt_planeamiento!=''||array[r].path_imagen!='') {
		$("#planteamiento").html(array[r].txt_planeamiento)
		_change_image( array[r].path_imagen,"#img-plantea");
		$("#link-planteamiento").removeClass('disabled');
	}
	else
	{
		$("#link-planteamiento").addClass('disabled');
	}


	_change_image( array[r].path_imagen_base,"#img-base","#img");
	_change_image( array[r].path_imagenA,"#img-a","#cont_img-a");
	_change_image( array[r].path_imagenB,"#img-b","#cont_img-b");
	_change_image( array[r].path_imagenC,"#img-c","#cont_img-c");
	_change_image( array[r].path_imagenD,"#img-d","#cont_img-d");
	
	if(array[r].Respuesta != null)
	{
		_change_value_radio(array[r].Respuesta);
	}
	
}// end function

function _change_image(path,elemento,contenedor)
{
	if (path.trim()=='') {
		 $(contenedor).hide();
		 $(elemento).attr('src','');
	}else{
	 	$(elemento).attr('src',base_url+'/uploads/'+path);
		$(contenedor).show();
	}
	
}

function _clear_radios()
{
	$("#A").prop( "checked", false);
	$("#B").prop( "checked", false);
	$("#C").prop( "checked", false);
	$("#D").prop( "checked", false);
}// end function

function _mostrar_numero(){
	var cad="";
	for (var i = array.length - 1; i >= 0; i--) {
		if (array[i].Respuesta!=null) 
			cad="<a href='#' id='num"+i+"' class='label label-default'>"+(i+1)+"</a> "+ cad;
		else
			cad="<a href='#' id='num"+i+"'  class='label label-info'>"+(i+1)+"</a> "+ cad;
	}
	cad="<p style='text-align: justify-all'>"+cad+"</p>";
	$("#numeros").html(cad);

	 $("#numeros a.label").each(function(k,v){
	 	$(this).click(function(event){
	 		event.preventDefault();
	 		_save_response(recorre);
	 		contador = k+1;
			recorre = k;
	 		_change(contador, recorre);
	 	});
	 });
}

function start()
{
	var resp = String($('input:radio[name=radio]:checked').val());
	if (resp == 'A' || resp == 'B' || resp == 'C' || resp == 'D')
	{
		_save_response(recorre);
		contador = 1;
		recorre = 0;
		_change(contador, recorre);
	}
	else
	{
		alert("Seleccione una opción.");
	}// end if'else
}// end function

// previous questions
function previous()
{
	var resp = String($('input:radio[name=radio]:checked').val());
	if (resp == 'A' || resp == 'B' || resp == 'C' || resp == 'D')
	{
		if (contador > 1)
		{
			_save_response(recorre);
			contador--;
			recorre--;
			_change(contador, recorre);
		}// end if
	}
	else
	{
		contador--;
		recorre--;
		_change(contador, recorre);
	}// end if'else
}// end function

// next questions
function next()
{
	var resp = String($('input:radio[name=radio]:checked').val());
	if (resp == 'A' || resp == 'B' || resp == 'C' || resp == 'D')
	{
		if (num_ques == contador)
		{
			_save_response(recorre);
			$("#finalizar").click();
		}
		if (contador != num_ques)
		{
			_save_response(recorre);
			contador++;
			recorre++;
			_change(contador, recorre);
		}// end if
	}
	else
	{
		alert("Seleccione una opción.");
	}// end if'else
}// end function

function _save_response(r)
{
	var id_reactivo_detail = String(array[r].id_reactivo_detail);
	var id_reactivo_main = String(array[r].id_reactivo_main);
	var resp = String($('input:radio[name=radio]:checked').val());
	
	if (resp!="undefined") {
		//aconsole.log(array[r].Respuesta);
		//aconsole.log(resp);
		if (array[r].Respuesta!== resp)
		{
			array[r].Respuesta = resp;
			var url =  base_url+'/egel/alumno/start_test/save_response/' + matricula + '/' + id + '/' + id_reactivo_main+'/'+id_reactivo_detail + '/' + resp +'/'+ tiempo;
			$.ajax({
			type: 'GET',
			url: url,
			dataType:'json',
			success: function(){
				
				 $("#numeros a#num"+(recorre-1)+"").removeClass("label-info").addClass("label-default");

			},
			error: function()
			{
				alert('Error al guardar respuesta, regrese a la pregunta anterior y vuelva a intentar.');
			}
			});// end function ajax
		}// end if
	}
}// end function


function _change_value_radio(name)
{
	$( "#"+name ).prop( "checked", true );
}

function finish_test(name)
{
	if (num_ques == contador)
	{
		var resp = String($('input:radio[name=radio]:checked').val());
		if (resp == 'A' || resp == 'B' || resp == 'C' || resp == 'D')
		{
			_save_response(recorre);
		}
		else
		{
			alert("Seleccione una opción.")					
		}// end  if-else
	}// end if

	var pendientes=get_preguntas_pendientes();
	if (pendientes==0)
	{
		alert('Has terminado el examen, \n  Gracias!! ');
		location = (base_url+"/login/logout");
	}
	else
	{
		
		ban = confirm("Realmente desea salir. \n le falta "+pendientes+" preguntas por contestar!");
		if (ban == true)
		{
			location = (base_url+"/login/logout");
		}
	}// end if-else
}// end function
function get_preguntas_pendientes(){
	var result=0;
	for (var i = array.length - 1; i >= 0; i--) {
		if (array[i].Respuesta===null) {
			result++;
		}
	}
	return result;
}
