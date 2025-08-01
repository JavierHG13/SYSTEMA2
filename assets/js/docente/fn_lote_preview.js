$(".remove_row").each(function(){
	$(this).click(function(){
		$(this).parent("td").parent("tr").fadeTo(400, 0, function () { 
        	$(this).remove();
    	});
		 return false;
	});
});

function asigna_eventos(){
	$("input#int_horas").each(function(e){
		$(this).keydown(function (e) {
	        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 || (e.keyCode == 65 && e.ctrlKey === true) || (e.keyCode >= 35 && e.keyCode <= 39)) {
	                 return;
	        }
	        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
	            e.preventDefault();
	        }
	    });	
	});
   
 	$("input#int_minutos").each(function(){
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
	guardarModal('#form_save_lote');
});

$(document).ready(function(){
	asigna_eventos();
});