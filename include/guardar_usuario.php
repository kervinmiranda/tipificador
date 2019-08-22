<?php
/****************************************************************************************************************************
										            SISTEMA GEBNET
****************************************************************************************************************************/
							//PROCESO PARA AGREGAR O EDITAR LOS USUARIOS Y CLAVES DE ACCESO

include_once 'conexion.php';
session_start();
if(isset($_SESSION['user'])){
$fecha = date('Y/m/d'); //Obtener la fecha del día
$accion = $_POST['accion']; //Obtener la Opción a realizar (Nuevo, editar, bloquear)
//Proceso de Ingreso de Usuario

	if($accion == "nuevo"){
		$ci = $_POST['cedula'];
		$nombre = utf8_decode(ucwords(strtolower($_POST['nombre'])));
		$userid = utf8_decode(strtolower($_POST['userid']));
		$cargo = utf8_decode(ucwords(strtolower($_POST['cargo'])));
		$departamento = utf8_decode(ucwords(strtolower($_POST['departamento'])));
		$nivel = $_POST['tipousuario'];
		$modulos = implode(",", $_POST['modulos']);
		$clave = md5($_POST['clave']);
		$buscar = mysql_query("SELECT ci FROM call_usuario WHERE ci= '$ci' OR userid = '$userid'");
		if (mysql_num_rows($buscar)){
			$data = "repetido";
		}else{
			$ingresar = mysql_query("INSERT INTO call_usuario(ci, nombre, cargo, userid, departamento, clave, nivel, modulos,estatus) VALUES ('$ci', '$nombre', '$cargo', '$userid', '$departamento', '$clave', '$nivel', '$modulos', '1')");
				if (isset($ingresar)){
					$data = "1";
				}else{
					$data = "0";
				}
		}//End If repetido
		echo $data;
	}//End if accion = nuevo


//Proceso de Edición de Usuario			

	if($accion == "editar"){
		$ci = $_POST['cedula'];
		$nombre = utf8_decode(ucwords(strtolower($_POST['nombre'])));
		$cargo = utf8_decode(ucwords(strtolower($_POST['cargo'])));
		$departamento = utf8_decode(ucwords(strtolower($_POST['departamento'])));
		$nivel = $_POST['tipousuario'];
		$modulos = implode(",", $_POST['modulos']);
		$actualizar = mysql_query("UPDATE call_usuario SET nombre = '$nombre', cargo = '$cargo', departamento = '$departamento', nivel = '$nivel', modulos = '$modulos' WHERE ci = '$ci'");
			if (isset($actualizar)){
				$data = "1";
			}else{
				$data = "0";
			}// End if
		echo $data;
	}//End if accion = editar



//Proceso de Actualización de Estatus
	if($accion == "estatus"){
		$ci = $_POST['cedula'];
		$estatus = $_POST['estatus'];
			if($estatus == 0){
				$estatus_nuevo = 1;
			}else if($estatus == 1){
				$estatus_nuevo = 0;
			}

		$actualizar = mysql_query("UPDATE call_usuario SET estatus = '$estatus_nuevo' WHERE ci = '$ci'");
		if (isset($actualizar)){
			$data = "1";
		}else{
			$data = "0";
		}// End if
		echo $data;
	}//End if accion = estatus


//Proceso de cambio de contraseña		

	if($accion == "cambiar"){
	$cedula = $_POST['cedula'];
	$claveActual = md5($_POST['actual']);
	$claveNueva = md5($_POST['clave1']);

	//Buscamos los datos en la tabla Paquete
	$consulta = mysql_query("SELECT clave FROM call_usuario WHERE ci = '$cedula' AND clave = '$claveActual'");
		if(mysql_num_rows($consulta)){
			$actualizar = mysql_query("UPDATE call_usuario SET clave = '$claveNueva' WHERE ci = '$cedula'");
			if (isset($actualizar)){
				$data = "1";
			}else{
				$data =  "0";
			}
		}else{
			$data = "error";
		}
		echo $data;
	}//End if accion = cambiar	

//Proceso de Reseteo de Contraseña
	if($accion == "resetear"){
		$ci = $_POST['cedula'];
		$clave_nueva = md5($_POST['clave_nueva']);
		$editar = mysql_query("UPDATE call_usuario SET clave = '$clave_nueva' WHERE ci = '$ci'");
		if (isset($editar)){
			$data = "1";
		}else{
			$data = "0";
		}
		echo $data;
	}//End if accion = cambiar		

mysql_close($conexion);

}else{

	header("location:index.php?error=ingreso");

}

?>