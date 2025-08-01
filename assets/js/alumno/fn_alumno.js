function confirm_start()
{
	var examen = valid_test.options[valid_test.selectedIndex].text;
	string = "¿Iniciar el Examen " + examen + "?";
	if (confirm(string))
	{ 
		return true;
	}
	else
	{ 
		return false;
	}// end if-else

}// end function
