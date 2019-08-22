<?php
/*****************************************************************************************************************************
										            SISTEMA GEBNET
*****************************************************************************************************************************/
							//PROCESO PARA AGREGAR O EDITAR LOS TIPOS DE ATRIBUTOS
include_once 'conexion.php';
session_start();
if(isset($_SESSION['user'])){

$accion = $_POST['accion']; //Obtener la Opción a realizar (Nuevo, editar, bloquear)

//Proceso de Ingreso de Atributo			
	if($accion == "nuevo"){	
		$descripcion = utf8_decode(strtoupper($_POST['descripcion']));		
		$buscar = mysql_query("SELECT id FROM call_evaluacion_atributo WHERE descripcion= '$descripcion'");
			if (mysql_num_rows($buscar)){
				$data = "repetido";
			}else{
				$ingresar = mysql_query("INSERT INTO call_evaluacion_atributo (descripcion) VALUES ('$descripcion')");
					if ($ingresar){ 
						$data = "1";
					}else{
						$data = "0";
					}
			}//End If repetido
		echo $data;
	}//End if accion = nuevo

//Proceso de edición de Atributo		
	if($accion == "editar"){	
		$id = $_POST['id'];
		$descripcion = utf8_decode(strtoupper($_POST['descripcion']));		
		$buscar = mysql_query("SELECT id FROM call_evaluacion_atributo WHERE descripcion= '$descripcion'");
			if (mysql_num_rows($buscar)){
				$data = "repetido";
			}else{
				$editar = mysql_query("UPDATE call_evaluacion_atributo SET descripcion = '$descripcion' WHERE id = '$id'");
					if($editar){ 
						$data = "1";
					}else{
						$data = "0";
					}
			}//End If repetido
		echo $data;
	}//End if accion = editar

//Proceso de edición de Estatus		
	if($accion == "estatus"){	
		$id = $_POST['id'];
		$buscar = mysql_query("SELECT estatus FROM call_evaluacion_atributo WHERE id= '$id'");
			if (mysql_num_rows($buscar)){
				$row = mysql_fetch_row($buscar);
				$estatus = $row[0];
					switch ($estatus){
						case '0':
							$nuevo_estatus = '1';
						break;
						
						case '1':
							$nuevo_estatus = '0';						
						break;
					}				
				$editar = mysql_query("UPDATE call_evaluacion_atributo SET estatus = '$nuevo_estatus' WHERE id = '$id'");
					if($editar){ 
						$data = "1";
					}else{
						$data = "0";
					}
			}else{
				$data = '';
			}//End If repetido
		echo $data;
	}//End if accion = editar
		
		
		
mysql_close($conexion);
}else{
	header("location:index.php?error=ingreso");
}
?>