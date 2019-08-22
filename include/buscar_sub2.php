<?php
//Tomamos los datos de la sesión
include_once 'variables.php';
if(isset($_SESSION['user'])){
	$userid = $_SESSION['nick'];
//Realizamos la conexión
include_once 'conexion.php';
//Recogemos los datos
$motivo = utf8_decode($_POST['elegido']);
$data = "";
if ($motivo != "TODOS"){ //Si la Opción es diferente a Todos
//Buscamos los datos en la tabla Paquete
$buscar = mysql_query("SELECT DISTINCT sub_motivo FROM call_registro WHERE motivo = '$motivo' ORDER BY sub_motivo ASC"); 
	$data.= "<option>Seleccionar...</option>";
	if (mysql_num_rows($buscar)){
		while($lista = mysql_fetch_array($buscar)){
			$data.= "<option>".utf8_encode($lista['sub_motivo'])."</option>";
		}
		$data.= "<option>TODOS</option>";
	}
}elseif ($motivo == "TODOS"){//Si la Opción es todos
//Buscamos los datos en la tabla Paquete
$buscar = mysql_query("SELECT DISTINCT sub_motivo FROM call_registro ORDER BY sub_motivo ASC"); 
	$data.= "<option>Seleccionar...</option>";
	if (mysql_num_rows($buscar)){
		while($lista = mysql_fetch_array($buscar)){
			$data.= "<option>".utf8_encode($lista['sub_motivo'])."</option>";
		}
		$data.= "<option>TODOS</option>";	
	}
}

echo $data;	
}//Enf If Isset SESSION	
?>
