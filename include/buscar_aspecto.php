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
	$buscar = mysql_query("SELECT call_evaluacion_atributo.id, call_evaluacion_aspecto.descripcion from call_evaluacion_aspecto INNER JOIN call_evaluacion_atributo ON call_evaluacion_aspecto.id_atributo = call_evaluacion_atributo.id WHERE call_evaluacion_aspecto.id = '$id'");
		if (mysql_num_rows($buscar)){
			$row = mysql_fetch_row($buscar);
			$json = array(
				'id_atributo' => $row[0],
				'descripcion' => utf8_encode($row[1])				
			);	
	echo json_encode($json);		
		}//End if		
}else{
	header("location:../index.php?error=ingreso");
}