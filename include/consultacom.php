<?php
include_once 'fecha.php';
include_once 'conexion.php';		
include_once 'variables.php';
if(isset($_SESSION['user'])){	
	$id = $_POST['id'];
		$consulta = mysql_query("SELECT comentario FROM call_registro WHERE id = '$id'");
			if (mysql_num_rows($consulta)){
				$row = mysql_fetch_row($consulta);
				$data = utf8_encode($row[0]);
			}else{
				$data = 'Sin Comentario';
			}//End if
echo ($data);

mysql_close($conexion);
}else{
	header("location:../index.php?error=ingreso");
}
?>