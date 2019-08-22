<?php
/*************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
session_start();
require 'pdo/database.php';
if(isset($_SESSION['user']) && ($_SESSION['nivel'] < 2)){
//Recogemos los datos del POST
$ci = $_POST['cedula'];
	$objdatabase = new Database();
	$sql = $objdatabase->prepare("SELECT * FROM call_usuario WHERE ci =:ci");
	$sql->bindParam(':ci', $ci, PDO::PARAM_STR);
	$sql->execute(); // se confirma que el query exista
	
	//Verificamos el resultado
	$count = $sql->rowCount();
		if ($count){
			$data = $sql->fetch(PDO::FETCH_OBJ);
			$json = array(
				'nombre' => utf8_encode($data->nombre),
				'userid' => utf8_encode($data->userid),
				'cargo' => utf8_encode($data->cargo),			
				'departamento' => utf8_encode($data->departamento),
				'nivel' => utf8_encode($data->nivel),
				'modulos' => utf8_encode($data->modulos),
			);	
		echo json_encode($json);
		}//End if
}else{
	header("location:index.php?alerta=salir");
}
?>