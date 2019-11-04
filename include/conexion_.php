<?php
	$servidor = 'localhost'; //casi siempre es asi
	$usuario = 'root';
	$clave = '';
//	$basedatos = 'gebnet'; //nombre de la base de datos
	$basedatos = 'gebnet'; //nombre de la base de datos
	$conexion = mysql_connect($servidor, $usuario, $clave);
	if (!isset($conexion)){
		die("NI A PALOS");
	}else{
		mysql_select_db($basedatos, $conexion); 
		}
?>