/*
 * methods used in administrator
 * 13/06/2016
 */
$(document).ready(function()
{
	$("#cmd_exportar_excel").hide();
	$("#cmd_exportar_pdf").hide();
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
	$("#cmd_exportar_excel").click(function(){
		exportar("excel");
	});
	$("#cmd_exportar_pdf").click(function(){
		exportar("pdf");
	});
});
function exportar(tipo){
	var examenes = $("#examenes").find(":selected").text();
	var carrera = $("#carrera").find(":selected").text();
	if (examenes)
	{
		var string = "";
		string = "EXPORTAR A "+tipo.toUpperCase()+" "+ examenes +"?";
		if (confirm(string))
		{ 
			$("#reporte_examen_escalas #task").val(tipo);
			$("#reporte_examen_escalas #tbl").val($("div.tabla").html());
			$("#reporte_examen_escalas #name_file").val(carrera+"-"+examenes);
			
			$("#reporte_examen_escalas").submit();
		}
	}
	else
	{
		$("#task").val("");
		alert("Ningun tipo de reporte seleccionado.");
	}// end if-else
}
function get_examenes(){
	carrera = $('#carrera').val();
	$.post( base_url+"/egel/admin/reportes_individuales/get_data_examenes/" + carrera,
	{
		carrera : carrera
	},
	function(data)
	{
		$('#examenes').html(data);
		get_resultados();
	});
}
function get_resultados(){
	$("#cmd_exportar_excel").hide();
	$("#cmd_exportar_pdf").hide();
	$('#tbl_alumnos').html("<tbody><tr><td colspan='5'><center><img src='"+base_url+"/assets/img/cargando.gif'/></center></td></tr></tbody>");
	carrera = $('#carrera').val();
	examen = $('#examenes').val();
	$.post( base_url+"/egel/admin/reporte_examen_escalas/get_resultados_examen/" + carrera + "/"+examen,
	{
		examen : examen
	},
	function(data)
	{
		$('#tbl_alumnos').html(data);
		$("#cmd_exportar_excel").show();
		$("#cmd_exportar_pdf").show();
	});
}