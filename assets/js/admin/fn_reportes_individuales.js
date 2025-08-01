/*
 * methods used in administrator
 * 13/06/2016
 */
$(document).ready(function()
{
	if ($("#carrera").val()!='00') {
		//alert("sadasd");
		get_examenes();
	}
	$("#carrera").change(function()
	{
		$("#carrera option:selected").each(function()
		{
			get_examenes();
		});
	})
	$("#cmd_generar").click(function(){
		var examenes = $("#examenes").find(":selected").text();
		if (examenes)
		{
			var string = "";
			string = "GENERAR TODOS LOS REPORTES DEL EXAMEN "+ examenes +"?";
			if (confirm(string))
			{ 
				$("#reportes_individuales #task").val("generar");
				$("#reportes_individuales").submit();
			}
		}
		else
		{
			$("#task").val("");
			alert("Ningun tipo de reporte seleccionado.");
		}// end if-else
	});
});
function get_examenes(){
	carrera = $('#carrera').val();
	$.post( base_url+"/egel/admin/reportes_individuales/get_data_examenes/" + carrera,
	{
		carrera : carrera
	},
	function(data)
	{
		$('#examenes').html(data);
		//get_alumnos();
	});
}
function get_alumnos(){
	$('#tbl_alumnos tbody').html("<tr><td colspan='5'><center><img src='"+base_url+"/assets/img/cargando.gif'/></center></td></tr>");
	carrera = $('#carrera').val();
	examen = $('#examenes').val();
	$.post( base_url+"/egel/admin/reportes_individuales/get_data_alumnos/" + carrera + "/"+examen,
	{
		examen : examen
	},
	function(data)
	{
		$('#tbl_alumnos tbody').html(data);
	});
}