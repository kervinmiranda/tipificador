<?php
/***************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
setlocale(LC_TIME, 'es_VE'); # Localiza en español es_Venezuela
date_default_timezone_set('America/Caracas');
include_once 'database.php';
session_start();
if(isset($_SESSION['user'])){
	$fecha = date('Y/m/d'); //Obtener la fecha del día	
	
	// New User
	function newUser(){
		$ci = $_POST['cedula'];
		$userid = $_POST['userid'];
		$exists = searchUser($ci, $userid);
		if ($exists ==  false){
			$objdatabase = new Database();	
		 	$sql = $objdatabase->prepare("INSERT INTO call_usuario(ci, nombre, cargo, userid, departamento, clave, nivel, modulos, estatus) VALUES (:ci, :nombre, :cargo, :userid, :departamento, :clave, :nivel, :modulos, 1)");
			//Definimos los parametros de la Query
			$sql->bindParam(':ci', $ci, PDO::PARAM_STR);
			$sql->bindParam(':nombre', ucwords(strtolower($_POST['nombre'])), PDO::PARAM_STR);
			$sql->bindParam(':cargo', ucwords(strtolower($_POST['cargo'])), PDO::PARAM_STR);
			$sql->bindParam(':userid', $userid, PDO::PARAM_STR);
			$sql->bindParam(':departamento',ucwords(strtolower($_POST['departamento'])), PDO::PARAM_STR);
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

	// Edit User
	function editUser(){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("UPDATE call_usuario SET nombre =:nombre, cargo =:cargo, departamento =:departamento, nivel =:nivel, modulos =:modulos WHERE ci =:ci");
		//Definimos los parametros de la Query
		$sql->bindParam(':ci', $_POST['cedula'], PDO::PARAM_STR);
		$nombre = mb_convert_case($_POST['nombre'], MB_CASE_TITLE, "UTF-8");
		$cargo = mb_convert_case($_POST['cargo'], MB_CASE_TITLE, "UTF-8");
		$depratamento = mb_convert_case($_POST['departamento'], MB_CASE_TITLE, "UTF-8");
		$sql->bindParam(':nombre', $nombre, PDO::PARAM_STR);
		$sql->bindParam(':cargo', $cargo, PDO::PARAM_STR);
		$sql->bindParam(':departamento', $depratamento, PDO::PARAM_STR);
		$sql->bindParam(':nivel', $_POST['tipousuario'], PDO::PARAM_STR);
		$sql->bindParam(':modulos', implode(",", $_POST['modulos']), PDO::PARAM_STR);
		if ($sql->execute()) {
		   $data = "1";
		} else {
		   $data = "0";
		}
		$objdatabase = null;
		echo $data;
	}

	// Search User
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

	// Get User
	function getUser(){
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
				'nombre' => $data->nombre,
				'userid' => $data->userid,
				'cargo' => $data->cargo,			
				'departamento' => $data->departamento,
				'nivel' => $data->nivel,
				'modulos' => $data->modulos,
			);	
			echo json_encode($json);
		}//End if
		$objdatabase = null;
	}
	
	// Reset Password
	function resetPassword(){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("UPDATE call_usuario SET clave =:clave_nueva WHERE ci =:ci");
		$sql->bindParam(':ci', $_POST['cedula'], PDO::PARAM_STR);
		$sql->bindParam(':ci', $_POST['clave_nueva'], PDO::PARAM_STR);
		$sql->execute(); // se confirma que el query exista	
		//Verificamos el resultado
		$count = $sql->rowCount();
			if ($count){
				$data = "1";
			}else{
				$data = "0";
			}
		$objdatabase = null;
		echo $data;
	}

	// Change Status
	function changeStatus(){
		$objdatabase = new Database();
		if($_POST['estatus'] == 0){
			$estatus_nuevo = 1;
		}else if($_POST['estatus'] == 1){
			$estatus_nuevo = 0;
		}
		$sql = $objdatabase->prepare("UPDATE call_usuario SET estatus =:estatus_nuevo WHERE ci =:ci");
		$sql->bindParam(':ci', $_POST['cedula'], PDO::PARAM_STR);
		$sql->bindParam(':estatus_nuevo', $estatus_nuevo, PDO::PARAM_STR);
		$sql->execute(); // se confirma que el query exista	
		//Verificamos el resultado
		$count = $sql->rowCount();
			if ($count){
				$data = "1";
			}else{
				$data = "0";
			}
		$objdatabase = null;
		echo $data;
	}

	// Get All Users
	function getUsers(){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT * FROM call_usuario");
		$sql->execute(); // se confirma que el query exista
		//Verificamos el resultado
		$count = $sql->rowCount();
		if ($count){
			$result = $sql->fetchAll();
			$data = array();
			foreach ($result as $key => $value){
				$ci = $value['ci'];
				$nombre = $value['nombre'];
				$userid = $value['userid'];
				$cargo = $value['cargo'];
				$departamento = $value['departamento'];
				$est = $value['estatus'];
				switch ($est){
					case 0: $estatus = '<img src="imagenes/inactivo.png">';
							$block='<img src="imagenes/block2.png" class="camb cursor" title="Desbloquear Usuario" id="'.$ci.'│'.$est.'">';
					break;
					case 1: $estatus = '<img src="imagenes/activo.png">';
							$block='<img src="imagenes/block.png" class="camb cursor" title="Bloquear Usuario" id="'.$ci.'│'.$est.'">';
					break;
				}
				$niv = $value['nivel'];
				switch ($niv){
					case 1: $nivel = "Administrador";
					break;
					case 2: $nivel = "Supervisor";
					break;
					case 3: $nivel = "Usuario";
					break;
				}
				$comando= '<img src="imagenes/clave.png" class="reset cursor" title="Resetear Contraseña" id="'.$ci.'│'.$nombre.'│'.$cargo.'│'.$userid.'│'.$departamento.'">'.
					'<img src="imagenes/edit.png" class="edit cursor" title="Editar Usuario" id="'.$ci.'">'.$block;
				$data[] = array($ci, $nombre, $userid, $cargo, $departamento, $estatus, $nivel,$comando);			
			}	
			//Mostramos los resultados
			$results = array("aaData"=>$data);
			echo json_encode($results);
		}
		$objdatabase = null;
	}

	// Change Password
	function changePassword(){
		$objdatabase = new Database();
		$cedula = $_POST['cedula'];
		$claveActual = md5($_POST['actual']);
		$claveNueva = md5($_POST['clave1']);
		//tomamos los datos y los comparamos
		$sql = $objdatabase->prepare("SELECT clave FROM call_usuario WHERE ci =:cedula AND clave =:claveActual AND estatus = 1");
		//Definimos los parametros de la Query
		$sql->bindParam(':cedula', $cedula, PDO::PARAM_STR);
		$sql->bindParam(':claveActual', $claveActual, PDO::PARAM_STR);
		//Exjecutamos la Query
		$sql->execute(); // se confirma que el query exista
		//Verificamos el resultado
		$count = $sql->rowCount();
		if($count){
			$objdatabase2 = new Database();
			$sql2 = $objdatabase2->prepare("UPDATE call_usuario SET clave =:claveNueva WHERE ci =:cedula");
			$sql2->bindParam(':cedula', $cedula, PDO::PARAM_STR);
			$sql2->bindParam(':claveNueva', $claveNueva, PDO::PARAM_STR);
			$sql2->execute(); // se confirma que el query exista
			//Verificamos el resultado
			$count2 = $sql2->rowCount();
			if ($count2){
				$data = "1";
			}else{
				$data = "0";
			}
			$objdatabase2 = null;
		}else{
			$data = "error";
		}
		$objdatabase = null;
		echo $data;
	}

	$function  = $_POST['function']; //Obtener la Opción a realizar (Nuevo, editar, bloquear)
	switch ($function) {
		case "newUser":
			newUser();
			break;
		case "editUser":
			editUser();
			break;
		case "getUser":
			getUser();
			break;
		case "resetPassword":
			resetPassword();
			break;
		case "changeStatus":
			changeStatus();
			break;
		case "getUsers":
			getUsers();
			break;
		case "changePassword":
			changePassword();
			break;
		default:
			break;
	}	
}else{
	echo "notSessionActive";
}

?>