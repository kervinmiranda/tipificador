<?php
	session_start();
	/* codigo para la validacion de los diferentes usuarios que ingresan al sistema*/
	require 'pdo/database.php';
	$objdatabase = new Database();
	//tomamos los datos y los comparamos
	$sql = $objdatabase->prepare('SELECT * FROM call_usuario WHERE userid =:userid AND clave =:clave AND estatus = 1');
	//Definimos los parametros de la Query
	$sql->bindParam(':userid', $_POST['usuario'], PDO::PARAM_STR);
	$sql->bindParam(':clave', md5($_POST['clave']), PDO::PARAM_STR);
	//Exjecutamos la Query
	$sql->execute(); // se confirma que el query exista
	//Verificamos el resultado
	$count = $sql->rowCount();
	if($count){
		$data = $sql->fetch(PDO::FETCH_OBJ);
		$_SESSION['cedula'] = $data->ci;
		$_SESSION['user'] = utf8_encode($data->nombre);
		$_SESSION['nivel'] = $data->nivel;
		$_SESSION['nick'] = $data->userid;
		$_SESSION['departamento'] = $_POST['departamento'];
		$_SESSION['modules'] = $data->modulos;
		header('location:../principal.php');
	}else{
		header('location:../index.php?error=acceso');
	}
	$objdatabase = null;
?>