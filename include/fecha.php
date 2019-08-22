<?php
//Funcion para mostrar la fecha completa
function fecha_completa($fecha){
	$fcexp = strtotime($fecha);
	$dia_semana = date('w',$fcexp);
	$dia_mes = date('d',$fcexp);
	switch ($dia_semana) {
		case 0:
			$dia_semana = "Domingo";
			break;
		case 1:
			$dia_semana = "Lunes";
			break;
		case 2:
			$dia_semana = "Martes";
			break;
		case 3:
			$dia_semana = "Miércoles";
			break;
		case 4:
			$dia_semana = "Jueves";
			break;
		case 5:
			$dia_semana = "Viernes";
			break;
		case 6:
			$dia_semana = "Sábado";
			break;
	}
	$mes = date('n',$fcexp);
	switch ($mes) {
		case 1:
			$mes = "Enero";
			break;
		case 2:
			$mes = "Febrero";
			break;
		case 3:
			$mes = "Marzo";
			break;
		case 4:
			$mes = "Abril";
			break;
		case 5:
			$mes = "Mayo";
			break;
		case 6:
			$mes = "Junio";
			break;
		case 7:
			$mes = "Julio";
			break;
		case 8:
			$mes = "Agosto";
			break;
		case 9:
			$mes = "Septiembre";
			break;
		case 10:
			$mes = "Octubre";
			break;
		case 11:
			$mes = "Noviembre";
			break;
		case 12:
			$mes = "Diciembre";
			break;
	}
	$ano = date('Y', $fcexp);
	$fecha_mostrar = $dia_semana.", ".$dia_mes." de ".$mes." de ".$ano;
	return $fecha_mostrar;
}

//Funcion para cambiar el formato de la fecha dia/mes/año a año-mes-día 
function cambiarFormatoFecha($fecha){
	if ($fecha != ""){ 
		list($dia,$mes,$anio)=explode("/",$fecha); 
		return $anio."-".$mes."-".$dia; 
	}else{
		return "";	
	}
  }

//Funcion para cambiar el formato de la fecha año-mes-día a dia/mes/año 
function cambiarFormatoFecha2($fecha){
	if ($fecha != ""){ 
		list($anio,$mes,$dia)=explode("-",$fecha); 
		return $dia."/".$mes."/".$anio;
	}else{
		return "";	
	}
  }

$time= date_default_timezone_set("America/Caracas").date(".d/m/y H:i:s a", time());



		

		?>