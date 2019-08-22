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
$buscar = mysql_query("SELECT secundaria FROM call_tipificacion WHERE principal = '$motivo' AND estatus = '1' ORDER BY secundaria ASC"); 
	$data.= '<option value"">Seleccionar...</option>';
	if (mysql_num_rows($buscar)){
		while($lista = mysql_fetch_array($buscar)){
			$data.= '<option value="'.utf8_encode($lista["secundaria"]).'">'.utf8_encode($lista["secundaria"]).'</option>';
		}
	}
}elseif ($motivo == "TODOS"){//Si la Opción es todos
//Buscamos los datos en la tabla Paquete
$buscar = mysql_query("SELECT DISTINCT secundaria FROM call_tipificacion WHERE estatus = '1' ORDER BY secundaria ASC"); 
	$data.= '<option value="">Seleccionar...</option>';
	if (mysql_num_rows($buscar)){
		while($lista = mysql_fetch_array($buscar)){
			$data.= '<option value="'.utf8_encode($lista["secundaria"]).'">'.utf8_encode($lista["secundaria"]).'</option>';
		}
			$data.= '<option value="TODOS">TODOS</option>';
	}
}
echo $data;	
}//Enf If Isset SESSION	
?>
