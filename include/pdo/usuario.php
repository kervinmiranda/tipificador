<?php
/***************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
include_once 'database.php';
session_start();
if(isset($_SESSION['user'])){
	$fecha = date('Y/m/d'); //Obtener la fecha del día
	$accion = $_POST['accion']; //Obtener la Opción a realizar (Nuevo, editar, bloquear)
	
	function newUser(){
		$ci = $_POST['cedula'];
		$userid = $_POST['userid'];
		$exists = searchUser($ci, $userid);
		if ($exists ==  false){
			$objdatabase = new Database();	
		 	$sql = $objdatabase->prepare("INSERT INTO call_usuario(ci, nombre, cargo, userid, departamento, clave, nivel, modulos, estatus) VALUES (:ci, :nombre, :cargo, :userid, :departamento, :clave, :nivel, :modulos, 1)");
			//Definimos los parametros de la Query
			$sql->bindParam(':ci', $ci, PDO::PARAM_STR);
			$sql->bindParam(':nombre', utf8_decode(ucwords(strtolower($_POST['nombre']))), PDO::PARAM_STR);
			$sql->bindParam(':cargo', utf8_decode(ucwords(strtolower($_POST['cargo']))), PDO::PARAM_STR);
			$sql->bindParam(':userid', $userid, PDO::PARAM_STR);
			$sql->bindParam(':departamento',utf8_decode(ucwords(strtolower($_POST['departamento']))), PDO::PARAM_STR);
			$sql->bindParam(':clave', md5($_POST['clave']), PDO::PARAM_STR);
			$sql->bindParam(':nivel', $_POST['tipousuario'], PDO::PARAM_STR);
			$sql->bindParam(':modulos', implode(",", $_POST['modulos']), PDO::PARAM_STR);
			if ($sql->execute()) { 
			   	$data = "1";
			}else{
				$data = "0";
			}
			$objdatabase = null;		
		}else{
			$data = "repetido";
		}
		$objdatabase = null;
		echo $data;
	}

	function editUser(){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("UPDATE call_usuario SET nombre =:nombre, cargo =:cargo, departamento =:departamento, nivel =:nivel, modulos =:modulos WHERE ci =:ci");
		//Definimos los parametros de la Query
		$sql->bindParam(':ci', $_POST['cedula'], PDO::PARAM_STR);
		$sql->bindParam(':nombre', utf8_decode(ucwords(strtolower($_POST['nombre']))), PDO::PARAM_STR);
		$sql->bindParam(':cargo', utf8_decode(ucwords(strtolower($_POST['cargo']))), PDO::PARAM_STR);
		$sql->bindParam(':departamento', utf8_decode(ucwords(strtolower($_POST['departamento']))), PDO::PARAM_STR);
		$sql->bindParam(':nivel', $_POST['tipousuario'], PDO::PARAM_STR);
		$sql->bindParam(':modulos', implode(",", $_POST['modulos']), PDO::PARAM_STR);
		if ($sql->execute()) {
		   $data = "1";
		} else {
		   $data = "0";
		}
		echo $data;
	}

	function searchUser($ci, $userid){
		$boolean = false;
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT ci FROM call_usuario WHERE ci =:ci OR userid =:userid");
		//Definimos los parametros de la Query
		$sql->bindParam(':ci', $ci, PDO::PARAM_STR);
		$sql->bindParam(':userid', $userid, PDO::PARAM_STR);
		//Exjecutamos la Query
		$sql->execute(); // se confirma que el query exista
		//Verificamos el resultado
		$count = $sql->rowCount();
		if ($count){
			$boolean = true;
		}else{
		}
		$objdatabase = null;
		return $boolean;
	}	
	
	switch ($accion) {
		case "nuevo":
			newUser();
			break;
		case "editar":
			editUser();
			break;
	}	
}else{
	echo "notSessionActive";
}

?>