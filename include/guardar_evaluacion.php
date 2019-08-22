<?php
/*************************************************************************************************************************
  										            SISTEMA GEBNET
**************************************************************************************************************************/
							//PROCESO PARA AGREGAR O EDITAR LOS FORMULARIOS
include_once 'conexion.php';
include_once 'fecha.php';
session_start();
if(isset($_SESSION['user'])){
$userid = $_SESSION['nick'];
$accion = $_POST['accion']; //Obtener la Opción a realizar (Nuevo, editar, bloquear)

//Proceso de Ingreso de Atributo			
	if($accion == "nuevo"){		
		$datos = $_POST['datos'];
		$nombre = utf8_decode($_POST['nombre']);
		$cedula = $_POST['cedula'];	
		$ingreso = cambiarFormatoFecha($_POST['ingreso']);
		$tiempo = $_POST['tiempo'];
		$supervisor = utf8_decode($_POST['supervisor']);
		$usuario = utf8_decode($_POST['usuario']);
		$error = 0;
		$ingreso = mysql_query("INSERT INTO call_evaluacion_registro(cedula, nombre, ingreso, tiempo, supervisor, usuario) VALUES('$cedula', '$nombre', '$ingreso', '$tiempo', '$supervisor', '$usuario')");
		$buscarid = mysql_query("SELECT MAX(id) FROM call_evaluacion_registro WHERE usuario = '$userid'");
			$row = mysql_fetch_row($buscarid);	
			$id = $row[0];		
			foreach ($datos as &$item) {
				$row = explode("|", $item);
				$atributo = utf8_decode($row[0]);
				$aspecto = utf8_decode($row[1]);
				$situacion = utf8_decode($row[2]);
				$cumple = $row[3];
				$porc = $row[4];
			//insertamos la fila
				$detalle = mysql_query("INSERT INTO call_evaluacion_registro_detalle(id, atributo, aspecto, situacion, cumple, porc) VALUES ('$id', '$atributo', '$aspecto', '$situacion', '$cumple', '$porc')");
				if (isset($detalle)){
				}else{
					$error++;
				}							
			}//End Foreach
			
		switch($error){
			case 0: $data = '1';
			break;
			default: $data = '0';
		}			
	}//End if accion = nuevo

//Proceso de edición de Atributo		
	if($accion == "editar"){	
		
	}//End if accion = editar

//Proceso de edición de Estatus		
	if($accion == "estatus"){	
		
	}//End if accion = editar

echo $data;
	
mysql_close($conexion);
}else{
	header("location:index.php?error=ingreso");
}
?>