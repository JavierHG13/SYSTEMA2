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
// save temporalty responses
var responses = new Array();
// matricula
var matricula = "";
//time
var tiempo = "";

// get questions of the database
function upload_questions(id_test)
{
	id = id_test;
	matricula = String($("#matricula").text());

	var url =  base_url+'/egel/admin/ver_examen/get_questions/' + id;
	$.ajax({
		type: 'GET',
		url: url,
		dataType:'json',
		success: function(data)
		{
			array = data;
			num_ques = array.length;
			contador = 1;
			recorre = 1 - 1;
			_change(contador, recorre);
			$("#tot").text(array.length);
			
		},
		error: function()
		{
			_upload();
		}
	});// end function ajax


	
}// end function

function _upload()
{
	var url =  base_url+'/egel/admin/ver_examen/get_questions/' + id;
	$.ajax({
		type: 'GET',
		url: url,
		dataType:'json',
		success: function(data)
		{
			array = data;
			num_ques = array.length;
			_change(contador, recorre);
			_time_left(id);
		},
		error: function()
		{
			alert('Error al cargar las preguntas3.');
		}
	});// end function ajax
}// end function

function _time_left(id_test)
{

	var url =  base_url+'/egel/admin/ver_examen/get_time/' + matricula;
	$.ajax({
		type: 'GET',
		url: url,
		dataType:'json',
		success: function(data)
		{
			var time = data;
			responses[recorre] = "";
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
		tiempo = "0" + hours_s + ":" +minutes_s + ":" + seconds_s + "";
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
	$("#numPre").text(c);
	$("#Pregunta").html(array[r].txt_base);
	$("#textA").text(array[r].nvch_opcionA);
	$("#textB").text(array[r].nvch_opcionB);
	$("#textC").text(array[r].nvch_opcionC);
	$("#textD").text(array[r].nvch_opcionD);

	// Lógica para mostrar o no el planteamiento según el contenido
	if (array[r].txt_planeamiento != '' || array[r].path_imagen != '') {
		$("#planteamiento").html(array[r].txt_planeamiento);
		_change_image(array[r].path_imagen, "#img-plantea");
		$("#link-planteamiento").removeClass('disabled');
	} else {
		$("#planteamiento").empty(); // Vacía el contenido si no hay planteamiento
		$("#link-planteamiento").addClass('disabled');
	}

	_change_image(array[r].path_imagen_base, "#img-base", "#img");
	_change_image(array[r].path_imagenA, "#img-a", "#cont_img-a");
	_change_image(array[r].path_imagenB, "#img-b", "#cont_img-b");
	_change_image(array[r].path_imagenC, "#img-c", "#cont_img-c");
	_change_image(array[r].path_imagenD, "#img-d", "#cont_img-d");
}

function _change_image(path, elemento, contenedor) {
    if (path.trim() === '') {
        $(contenedor).hide();
        $(elemento).attr('src', '').hide(); // Limpia y oculta la imagen
    } else {
        $(elemento).attr('src', base_url + '/uploads/' + path).show();
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

function start()
{
		contador = 1;
		recorre = 0;
		_change(contador, recorre);
	
}// end function

// previous questions
function previous()
{
		if (contador != 1)
		{

			contador--;
			recorre--;
			_clear_radios();

			_change(contador, recorre);
		}// end if
	
}// end function

// next questions
function next()
{
		if (contador != num_ques)
		{
			contador++;
			recorre++;
			_clear_radios();
			_change(contador, recorre);
		}// end if
	
}// end function

function _change_value_radio(name)
{
	$( "#"+name ).prop( "checked", true );
}

function finish_test(name)
{
	var url =  base_url+'/egel/admin/ver_examen/check_responses/' +matricula+'/'+ id;
	$.ajax({
		type: 'GET',
		url: url,
		dataType:'json',
		success: function(data_res2)
		{
			var array_responses2;
			var num_resp;
			array_responses2 = data_res2;
			num_resp = array_responses2.length;
			
			if (num_ques == contador)
			{
				var resp = String($('input:radio[name=radio]:checked').val());
				if (resp == 'A' || resp == 'B' || resp == 'C' || resp == 'D')
				{
					_save_response(recorre);
					num_resp = num_resp + 1;
				}
				else
				{
					alert("Seleccione una opción.")					
				}// end  if-else
			}// end if

			if (num_resp == num_ques)
			{
				alert('cerrando examen');
				location = (base_url+"/login/logout");
			}
			else
			{
				falta = num_ques - num_resp;
				ban = confirm("Realmente desea salir. \n le falta "+falta+" preguntas por contestar!");
				if (ban == true)
				{
					location = (base_url+"/login/logout");
				}
			}// end if-else
		},
		error: function()
		{
			alert('Responda las preguntas.');
		}
	});// end function ajax

}// end function
