<?php
/*****************************************************************************************************************************
										            SISTEMA GEBNET
*****************************************************************************************************************************/
							//PROCESO PARA AGREGAR O EDITAR LOS TIPOS DE TIPIFICACION
include_once 'conexion.php';
include_once 'variables.php';
if(isset($_SESSION['user'])){
$fecha = date('Y/m/d'); //Obtener la fecha del día
$accion = $_POST['accion']; //Obtener la Opción a realizar (Nuevo, editar, bloquear)

//Proceso de Ingreso de Tipo de Tipificación			
	if($accion == "nuevo"){	
		$motivo = utf8_decode(strtoupper($_POST['motivo']));
		$sub_motivo = utf8_decode(strtoupper($_POST['sub_motivo']));
		$buscar = mysql_query("SELECT id FROM call_tipificacion WHERE principal= '$motivo' AND secundaria = '$sub_motivo'");
			if (mysql_num_rows($buscar)){
				$data = "repetido";
			}else{
				$ingresar = mysql_query("INSERT INTO call_tipificacion (principal, secundaria) VALUES ('$motivo', '$sub_motivo')");
					if ($ingresar){ 
						$data = "1";
					}else{
						$data = "0";
					}
			}//End If repetido
			echo $data;
		}//End if accion = nuevo

//Proceso de Edición de Tipo de Tipificación		
	if($accion == "editar"){
		$id = $_POST['id'];
		$motivo = utf8_decode(strtoupper($_POST['motivo']));
		$sub_motivo = utf8_decode(strtoupper($_POST['sub_motivo']));	
		$buscar = mysql_query("SELECT id FROM call_tipificacion WHERE principal= '$motivo' AND secundaria = '$sub_motivo'");
			if (mysql_num_rows($buscar)){
				$data = "repetido";
			}else{
				$actualizar = mysql_query("UPDATE call_tipificacion SET principal = '$motivo', secundaria = '$sub_motivo' WHERE id = '$id'");
					if ($actualizar){
						$data = "1";
					}else{
						$data = "0";
					}// End if
			}//End If repetido
			echo $data;
	}//End if accion = editar

//Proceso de Actualización de Estatus		
	if($accion == "estatus"){
		$id = $_POST['id'];
		$estatus = $_POST['estatus'];
			if($estatus == 0){
				$estatus_nuevo = 1;
			}else if($estatus == 1){
				$estatus_nuevo = 0;
			}
		$actualizar = mysql_query("UPDATE call_tipificacion SET estatus = '$estatus_nuevo' WHERE id = '$id'");
		if (isset($actualizar)){
			$data = "1";
		}else{
			$data = "0";
		}// End if
		echo $data;
	}//End if accion = estatus

		
mysql_close($conexion);
}else{
	header("location:index.php?error=ingreso");
}
?>