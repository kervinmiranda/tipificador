<?php
/****************************************************************************************************************************
										            SISTEMA GEBNET
****************************************************************************************************************************/
							    //PROCESO PARA AGREGAR O EDITAR LOS REGISTROS DE TIPIFICACIONES
include_once 'conexion.php';
session_start();
if(isset($_SESSION['user'])){
	$userid = $_SESSION['nick'];
	$departamento = utf8_decode($_SESSION['departamento']); 
//Realizamos la conexión
include_once 'conexion.php';
//Obtener la fecha del día

date_default_timezone_set('America/Caracas');
$fecha = date('Y-m-d H:i:s');

//Recogemos los datos Pasados por  POST
//Recogemos la accion
$accion = $_POST['accion'];
//Si la accion =nuevo
	if ($accion == "nuevo"){
		$pais = utf8_decode($_POST['pais']);
		$motivo = utf8_decode($_POST['motivo']);
		$submotivo = utf8_decode($_POST['submotivo']);		
		if (($motivo!= 'Seleccionar...') && ($submotivo != 'Seleccionar...')){		
			$codigo = strtoupper(utf8_decode(str_replace(' ', '',$_POST['codigo'])));
			$codigo = preg_replace('/\s+/', '', $codigo);		
			
			switch($departamento){
				case 'REDES SOCIALES':
					$socialuser = strtolower(utf8_decode(str_replace(' ', '',$_POST['socialuser'])));
					$socialuser = str_replace('@', '', $socialuser);					
					$socialuser = preg_replace('/\s+/', '', $socialuser);					
				break;				
				default:
					$socialuser = '';						
			}
			$guia = strtoupper(utf8_decode(str_replace(' ', '',$_POST['guia'])));
			$guia = preg_replace('/\s+/', '', $guia);		
			$comentario = utf8_decode(trim($_POST['comentario']));				
	//Insertamos los datos en la base de datos
			$ingresar = mysql_query("INSERT INTO call_registro (pais, fecha, usuario, departamento, motivo, sub_motivo, libced, usersocial, guiatracking, comentario, estatus) VALUES ('$pais', '$fecha', '$userid', '$departamento', '$motivo', '$submotivo', '$codigo', '$socialuser', '$guia', '$comentario', '1')");	
			$buscarid = mysql_query("SELECT MAX(id) FROM call_registro WHERE usuario = '$userid'");
					$row = mysql_fetch_row($buscarid);
					$id = $row[0];
				if (isset($ingresar)){
					$data = $id;
				}else{
					$data = "0";
				}
		}else{
			$data = "0";
		}	
	echo $data;		
	}//End if accion nuevo

//Si la accion =incidencia
	if ($accion == "incidencia"){
		$pais = utf8_decode($_POST['pais']);
		$motivo = utf8_decode($_POST['motivo']);
		$submotivo = utf8_decode($_POST['submotivo']);
		$codigo = strtoupper(utf8_decode(str_replace(' ', '',$_POST['codigo'])));	
		$codigo = preg_replace('/\s+/', '', $codigo);
		switch($departamento){
				case 'REDES SOCIALES':
					$socialuser = strtolower(utf8_decode(str_replace(' ', '',$_POST['socialuser'])));
					$socialuser = str_replace('@', '', $socialuser);					
					$socialuser = preg_replace('/\s+/', '', $socialuser);					
				break;				
				default:
					$socialuser = '';						
			}		
		$guia = strtoupper(utf8_decode(str_replace(' ', '',$_POST['guia'])));
		$guia = preg_replace('/\s+/', '', $guia);
		$comentario = utf8_decode(trim($_POST['comentario']));				
	//Insertamos los datos en la base de datos
		$ingresar = mysql_query("INSERT INTO call_registro (pais, fecha,usuario, departamento, motivo, sub_motivo, libced, usersocial, guiatracking, comentario, estatus) VALUES ('$pais', '$fecha', '$userid', '$departamento', '$motivo', '$submotivo', '$codigo', '$socialuser', '$guia', '$comentario', '1')");	
		$buscarid = mysql_query("SELECT MAX(id) FROM call_registro WHERE usuario = '$userid'");
				$row = mysql_fetch_row($buscarid);
				$id = $row[0];
		$incidencia = mysql_query("INSERT INTO call_incidencia (id) VALUES ('$id')");
		$gestion = mysql_query("INSERT INTO call_gestion (id, fecha, gestor, comentario) VALUES ('$id', '$fecha', '$userid', '$comentario')");
		if (isset($ingresar)){
			$data = $id;
		}else{
			$data = "0";
		}
		echo $data;
	}//End if accion incidencia

//Si la accion =editar
	if ($accion == "editar"){
		$id = $_POST['id'];
		$motivo = utf8_decode($_POST['motivo']);
		$submotivo = utf8_decode($_POST['submotivo']);		
		$cedlib = utf8_decode($_POST['cedlib']);	
		$comentario = utf8_decode(trim($_POST['comentario']));				
//Editamos los Datos en la BD
		$editar = mysql_query("UPDATE call_registro SET motivo = '$motivo', sub_motivo = '$submotivo', libced = '$cedlib' WHERE id = '$id'");
		$gestion = mysql_query("INSERT INTO call_gestion (id, fecha, gestor, comentario) VALUES ('$id', '$fecha', '$userid', '$comentario')");		
		if (isset($ingresar) && isset($gestion)){
			$data = $id;
		}else{
			$data = "0";
		}
		echo $id;
	}//End if accion Editar	

	
//Si la accion = gestion
	if ($accion == "gestion"){
		$id = $_POST['id'];
		$estatus = utf8_decode($_POST['estatus']);
		$comentario = utf8_decode(trim($_POST['comentario']));					
	//Insertamos los datos en la base de datos
		$gestion = mysql_query("INSERT INTO call_gestion (id, fecha, gestor, comentario, estatus) VALUES ('$id', '$fecha', '$userid', '$comentario', '$estatus')");
	//Editamos los datos de la Tabla de Incidencias
		$editar = mysql_query("UPDATE call_incidencia SET estatus = '$estatus' WHERE id = '$id'");
		if (isset($gestion) && isset($editar)){
			$data = $id;
		}else{
			$data = "0";
		}
		echo $data;
	}//End if accion gestion	

	
//Si la accion = comentario
	if ($accion == "comentario"){
		$id = $_POST['id'];
		$comentario = utf8_decode(trim($_POST['comentario']));					
		$buscar = mysql_query("SELECT estatus FROM call_incidencia WHERE id = '$id'");
			$row = mysql_fetch_row($buscar);
			$estatus = $row[0];	
//Insertamos los datos en la base de datos
		$comentario = mysql_query("INSERT INTO call_gestion (id, fecha, gestor, comentario, estatus) VALUES ('$id', '$fecha', '$userid', '$comentario', '$estatus')");
	
	//Editamos los datos de la Tabla de Incidencias
		if (isset($comentario)){
			$data = $id;
		}else{
			$data = "0";
		}
		echo $data;
	}//End if accion Comentario
	
//Si la accion = comentario_masivo
	if ($accion == "comentario_masivo"){
		$selected = $_POST['selected'];
		$comentario = utf8_decode(trim($_POST['comentario']));					
		foreach ($selected as &$id) {
			$buscar = mysql_query("SELECT estatus FROM call_incidencia WHERE id = '$id'");
			$row = mysql_fetch_row($buscar);
			$estatus = $row[0];	
	//Insertamos los datos en la base de datos
		$gestion = mysql_query("INSERT INTO call_gestion (id, fecha, gestor, comentario, estatus) VALUES ('$id', '$fecha', '$userid', '$comentario', '$estatus')");
		}
		if (isset($gestion)){
			$data = "1";
		}else{
			$data = "0";
		}
		echo $data;
	}//End if accion Comentario Masivo
	
//Si la accion = gestion_masiva
	if ($accion == "gestion_masiva"){
		$selected = $_POST['selected'];
		$estatus = utf8_decode($_POST['estatus']);
		$comentario = utf8_decode(trim($_POST['comentario']));					
		foreach ($selected as &$id) {
	//Insertamos los datos en la base de datos
		$gestion = mysql_query("INSERT INTO call_gestion (id, fecha, gestor, comentario, estatus) VALUES ('$id', '$fecha', '$userid', '$comentario', '$estatus')");
	//Editamos los datos de la Tabla de Incidencias
		$editar = mysql_query("UPDATE call_incidencia SET estatus = '$estatus' WHERE id = '$id'");	
		}
		if (isset($gestion) && isset($editar)){
			$data = "1";
		}else{
			$data = "0";
		}
		echo $data;
	}//End if accion Gestion Masivo
	
}//Enf If Isset SESSION	
?>