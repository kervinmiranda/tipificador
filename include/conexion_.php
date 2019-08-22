<?php
	$servidor = 'localhost'; //casi siempre es asi
	$usuario = 'networkl_tipific';
	$clave = 'd3s4rr0ll05l1b';
//	$basedatos = 'gebnet'; //nombre de la base de datos
	$basedatos = 'networkl_gebnet_tipificador'; //nombre de la base de datos
	$conexion = mysql_connect($servidor, $usuario, $clave);
	if (!isset($conexion)){
		die("NI A PALOS");
	}else{
		mysql_select_db($basedatos, $conexion); 
		}
?>