<?php
//Tomamos los datos de la sesión
include_once 'variables.php';
if(isset($_SESSION['user'])){
	$userid = $_SESSION['nick'];
//Realizamos la conexión
include_once 'conexion.php';
//Recogemos los datos
$atributo = utf8_decode($_POST['elegido']);
$data = "";
$buscar = mysql_query("SELECT id, descripcion FROM call_evaluacion_aspecto WHERE id_atributo = '$atributo' ORDER BY descripcion ASC");
	$data.= '<option value"">Seleccionar...</option>';
	if (mysql_num_rows($buscar)){		
		while($lista = mysql_fetch_array($buscar)){
			$data.= '<option value="'.utf8_encode($lista["id"]).'">'.utf8_encode($lista["descripcion"]).'</option>';
		}//End while	
	}//End if
echo $data;	
}//Enf If Isset SESSION	
?>