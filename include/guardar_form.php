<?php
/*************************************************************************************************************************
  										            SISTEMA GEBNET
**************************************************************************************************************************/
							//PROCESO PARA AGREGAR O EDITAR LOS FORMULARIOS
include_once 'conexion.php';
session_start();
if(isset($_SESSION['user'])){

$accion = $_POST['accion']; //Obtener la Opción a realizar (Nuevo, editar, bloquear)

//Proceso de Ingreso de Atributo			
	if($accion == "nuevo"){		
		$nombre = $_POST['nombre'];
		$gestor = $_SESSION['nick'];
		$ingresar = mysql_query("INSERT INTO call_evaluacion_form (nombre, gestor) VALUES ('$nombre', '$gestor')");
			$buscarid = mysql_query("SELECT MAX(id) FROM call_evaluacion_form");
			$row = mysql_fetch_row($buscarid);
			$id = $row[0];
			//Guardar Items Seleccionados
			$error = 0;
			$situaciones = $_POST['situaciones'];
			foreach ($situaciones as &$situacion){	
				$pos = strpos($situacion, ':');
				$item = substr($situacion, 0, $pos);
				$porc = substr($situacion, $pos + 1, strlen($situacion));
				$detalle = mysql_query("INSERT INTO call_evaluacion_form_detalle (id_form, situacion, porc) VALUES ('$id', '$item', '$porc')");
				if ($detalle){
					}else{
						$error++;
					}//End if
			}//End Foreach
			if (($ingresar) && ($error == 0)){
				$data = "1";
			}else{
				$data = "0";
			}
	}//End if accion = nuevo

//Proceso de edición de Atributo
// 	if($accion == "editar"){	
// 		$id = $_POST['id'];
// //		$aspecto = $_POST['aspecto'];
// 		$situacion = utf8_decode(ucwords(strtolower($_POST['situacion']);		
// //		$descripcion = utf8_decode(ucwords(strtolower($_POST['descripcion'])));
// //		$grupo = utf8_decode(ucwords(strtolower($_POST['grupo'])));
// 		$buscar = mysql_query("SELECT id FROM call_evaluacion_form_detalle WHERE situacion = '$situacion'");
// 			if (mysql_num_rows($buscar)){
// 				$data = "repetido";
// 			}else{
// 				$editar = mysql_query("UPDATE call_evaluacion_form_detalle SET situacion = '$situacion' WHERE id = '$id'");
// 					if($editar){ 
// 						$data = "1";
// 					}else{
// 						$data = "0";
// 					}
// 			}//End If repetido
// 		echo $data;
// 	}//End if accion = editar


	if($accion == "editar"){	
		$id = $_POST['id'];
		$aspecto = $_POST['aspecto'];
		$descripcion = utf8_decode(ucwords(strtolower($_POST['descripcion'])));
		$grupo = utf8_decode(ucwords(strtolower($_POST['grupo'])));
		$porc = $_POST['porc'];
//		$gestor = utf8_decode(ucwords(strtolower($_POST['gestor'])));

		$buscar = mysql_query("SELECT id FROM call_evaluacion_situacion WHERE descripcion = '$descripcion' AND id_aspecto = '$aspecto' AND id <> '$id'");

// 		$buscar = mysql_query("SELECT ces.id, cfd.situacion FROM call_evaluacion_situacion ces,call_evaluacion_form_detalle cfd WHERE ces.descripcion = '$descripcion' AND ces.id_aspecto = '$aspecto' AND ces.id <> '$id' AND ces.id = cfd.situacion");
			if (mysql_num_rows($buscar)){
				$data = "repetido";
			}else{
				// se debe hacer un delete primero para cargar solo las situaciones marcadas con el check, pendiente


				$editar = msql_query("DELETE call_evaluacion_form_detalle WHERE id_form = '$id'");

				// mysql_query("UPDATE call_evaluacion_situacion,call_evaluacion_form_detalle SET call_evaluacion_situacion.id_aspecto = '$aspecto', call_evaluacion_situacion.descripcion = '$descripcion', call_evaluacion_situacion.grupo = '$grupo', call_evaluacion_form_detalle.porc = '$porc' WHERE call_evaluacion_situacion.id = '$id' and call_evaluacion_form_detalle.situacion = call_evaluacion_situacion.id");
				// 	if($editar){ 
				// 		$data = "1";
				// 	}else{
				// 		$data = "0";
				// 	}


			}//End If repetido
		echo $data;
	}//End if accion = editar



//Proceso de edición de Estatus		
	if($accion == "estatus"){	
		$id = $_POST['id'];
		$buscar = mysql_query("SELECT estatus FROM call_evaluacion_form WHERE id= '$id'");
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
				$editar = mysql_query("UPDATE call_evaluacion_form SET estatus = '$nuevo_estatus' WHERE id = '$id'");
					if($editar){ 
						$data = "1";
					}else{
						$data = "0";
					}
			}else{
				$data = '';
			}//End If
		echo $data;
	}//End if accion = editar	
		
	echo $data;	
mysql_close($conexion);
}else{
	header("location:index.php?error=ingreso");
}
?>