<?php 
	function get_periodo(){
		/*$mes=date("m");
		$anio=date("Y");
		$periodo=date("m");
		switch ($mes) {
			case ($mes>=1 && $mes<=4):
				$periodo=$anio."1";
				break;
			case ($mes>=5 && $mes<=8):
				$periodo=$anio."2";
				break;
			case ($mes>=9 && $mes<=12):
				$periodo=$anio."3";
				break;
		}*/
		$periodo=date("Y");
		return $periodo;
	}
	function get_periodo_anterior(){
		/*$mes=date("m");
		$anio=date("Y");
		$periodo=date("m");
		switch ($mes) {
			case ($mes>=1 && $mes<=4):
				$anio--;		
				$periodo=$anio."3";
				break;
			case ($mes>=5 && $mes<=8):

				$periodo=$anio."1";
				break;
			case ($mes>=9 && $mes<=12):

				$periodo=$anio."2";
				break;
		}*/
		$periodo=date("Y")-1;
		return $periodo;
	}
	function get_periodo_siguiente(){
		/*$mes=date("m");
		$anio=date("Y");
		$periodo=date("m");
		switch ($mes) {
			case ($mes>=1 && $mes<=4):
				$periodo=$anio."2";
				break;
			case ($mes>=5 && $mes<=8):
				$periodo=$anio."3";
				break;
			case ($mes>=9 && $mes<=12):
				$anio++;
				$periodo=$anio."1";
				break;
		}
		*/
		$periodo=date("Y")+1;
		return $periodo;
	}
?>