<?php
include_once 'conexion.php';
include_once 'fecha.php';
include_once 'variables.php';
if(isset($_SESSION['user'])){
	$sql="SELECT * FROM call_evaluacion_registro";	
	$ver=mysql_query($sql);		
   		$data = array();
			while($row = mysql_fetch_array($ver)){
				$id = $row['id'];
				$cedula = $row['cedula'];
				$nombre = utf8_encode($row['nombre']);
				$ingreso = cambiarFormatoFecha2($row['ingreso']);
				$tiempo =  $row['tiempo'];
				$supervisor = utf8_encode($row['supervisor']);
				$usuario = utf8_encode($row['usuario']);							
				$data[] = array($id, $cedula, $nombre, $ingreso, $tiempo, $supervisor, $usuario);
			}//End While	
    //Mostramos los resultados
	$results = array("aaData"=>$data);
	echo json_encode($results);
	
mysql_close($conexion);
}else{
	header("location:../index.php?error=ingreso");
}
		
