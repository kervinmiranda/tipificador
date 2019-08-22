<?php
/*************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/	
session_start();
require 'conexion.php';
if(isset($_SESSION['user']) && ($_SESSION['nivel'] < 2)){
//Recogemos los datos del POST
$id = $_POST['id'];

	//Definimos la consulta
	$buscar = mysql_query("SELECT * FROM call_evaluacion_atributo WHERE id = '$id' ");
		if (mysql_num_rows($buscar)){
			$row = mysql_fetch_row($buscar);
			$json = array(
				'descripcion' => utf8_encode($row[1]),				
			);	
	echo json_encode($json);		
		}//End if		
}else{
	header("location:../index.php?error=ingreso");
}