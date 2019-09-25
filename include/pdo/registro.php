<?php
/***************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
setlocale(LC_TIME, 'es_VE'); # Localiza en español es_Venezuela
date_default_timezone_set('America/Caracas');
include_once 'database.php';
session_start();
if(isset($_SESSION['user'])){

	//Search Lib o cédula
	function searchLib(){
		$tipo = $_POST['tipo'];
		$objdatabase = new Database();
		$value = $_POST['value'];
		switch ($tipo) {
			case "lib":
				$sql = $objdatabase->prepare("SELECT * FROM call_registro WHERE libced =:libced ORDER BY fecha DESC");
				$sql->bindParam(':libced', $value, PDO::PARAM_STR);
				break;
			case "guia":
				$sql = $objdatabase->prepare("SELECT * FROM call_registro WHERE guiatracking LIKE ? ORDER BY fecha DESC");
				$sql->bindValue(1,"%{$value}%", PDO::PARAM_STR);
				break;			
			default:				
				break;
		}
		$sql->execute(); // se confirma que el query exista	
		//Verificamos el resultado
		$count = $sql->rowCount();
		if ($count){
			$json = array();
			$result = $sql->fetchAll();
			foreach ($result as $key => $value){
				$fec = date_create($value['fecha']);
				$json[] = array(
					'fecha' => date_format($fec, 'd/m/Y h:i a'),
					'usuario' => $value['usuario'],
					'departamento' => $value['departamento'],
					'motivo' => $value['motivo'],
					'sub_motivo' => $value['sub_motivo'],
					'libced' => $value['libced'],
					'usersocial' => $value['usersocial'],
					'guiatracking' => $value['guiatracking'],
					'comentario' => $value['comentario']
				);
			}
			$json['success'] = true;
			echo json_encode($json);
		}
		$objdatabase = null;
	}

	// Search Code
	function autocompleteCode(){
		$objdatabase = new Database();
		$codigo = $_POST['codigo'];
		$sql = $objdatabase->prepare("SELECT DISTINCT libced FROM call_registro WHERE libced LIKE ? ORDER BY libced");
		$sql->bindValue(1,"%{$codigo}%", PDO::PARAM_STR);
		$sql->execute(); // se confirma que el query exista	
		//Verificamos el resultado
		$count = $sql->rowCount();
		if ($count){
			$json = array();
			$result = $sql->fetchAll();
			foreach ($result as $key => $value){
				$json[] = array("value" => $value['libced']);
			}
			$json['success'] = true;
			echo json_encode($json);
		}
		$objdatabase = null;
	}

	// Search Guide
	function autocompleteGuide(){
		$objdatabase = new Database();
		$guia = $_POST['guia'];
		$sql = $objdatabase->prepare("SELECT DISTINCT guiatracking FROM call_registro WHERE guiatracking LIKE ? ORDER BY guiatracking");
		$sql->bindValue(1,"%{$guia}%", PDO::PARAM_STR);
		$sql->execute(); // se confirma que el query exista	
		//Verificamos el resultado
		$count = $sql->rowCount();
		if ($count){
			$json = array();
			$result = $sql->fetchAll();
			foreach ($result as $key => $value){
				$json[] = array("value" => $value['guiatracking']);
			}
			$json['success'] = true;
			echo json_encode($json);
		}
		$objdatabase = null;
	}

	//New Register
	function insertRegister(){
		$objdatabase = new Database();
		$pais = $_POST['pais'];
		$motivo = $_POST['motivo'];
		$submotivo = $_POST['submotivo'];
		$codigo = strtoupper(str_replace(' ', '',$_POST['codigo']));
		$codigo = preg_replace('/\s+/', '', $codigo);
		$guia = strtoupper(str_replace(' ', '',$_POST['guia']));
		$guia = preg_replace('/\s+/', '', $guia);		
		$comentario = trim($_POST['comentario']);
		switch($_SESSION['departamento']){
			case 'REDES SOCIALES':
				$socialuser = strtolower(str_replace(' ', '',$_POST['socialuser']));
				$socialuser = str_replace('@', '', $socialuser);					
				$socialuser = preg_replace('/\s+/', '', $socialuser);					
			break;				
			default:
				$socialuser = '';
			break;				
		}
		$sql = $objdatabase->prepare("INSERT INTO call_registro (pais, fecha, usuario, departamento, motivo, sub_motivo, libced, usersocial, guiatracking, comentario, estatus) VALUES (:pais, :fecha, :userid, :departamento, :motivo, :submotivo, :codigo, :socialuser, :guia, :comentario, 1)");
		$sql->bindParam(':pais', $pais, PDO::PARAM_STR);
		$sql->bindParam(':fecha', date('Y/m/d'), PDO::PARAM_STR);
		$sql->bindParam(':userid', $_SESSION['nick'], PDO::PARAM_STR);
		$sql->bindParam(':departamento', $_SESSION['departamento'], PDO::PARAM_STR);
		$sql->bindParam(':motivo', $motivo, PDO::PARAM_STR);
		$sql->bindParam(':submotivo', $submotivo, PDO::PARAM_STR);
		$sql->bindParam(':codigo', $codigo, PDO::PARAM_STR);
		$sql->bindParam(':socialuser', $socialuser, PDO::PARAM_STR);
		$sql->bindParam(':guia', $guia, PDO::PARAM_STR);
		$sql->bindParam(':comentario', $comentario, PDO::PARAM_STR);
		if ($sql->execute()) { 
		   $data = $objdatabase->lastInsertId();
		}else{
			$data = "0";
		}		
		$objdatabase = null;
		return $data;
	}

	//Insert Incidence
	function insertIncidence($id){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("INSERT INTO call_incidencia (id) VALUES (:id)");
		$sql->bindParam(':id', $id, PDO::PARAM_STR);
		if ($sql->execute()) { 
		   $data = $objdatabase->lastInsertId();
		}else{
			$data = "0";
		}		
		$objdatabase = null;
		return $data;
	}
	
	//New Register
	function newRegister(){
		$data = insertRegister();
		echo $data;
	}

	//New Register And Incidence
	function newRegisterIncidence(){
		$data = "0";
		$insert = insertRegister();
		if ($insert != "0"){
			insertIncidence($insert);
			$data = $insert;
		}
		echo $data;
	}

	// Tipifications List
	function lista(){
		$objdatabase = new Database();
		$fechabuscar = $_POST['fecha'];
		if ($_SESSION['nivel'] < 3){
			$sql = $objdatabase->prepare("SELECT * FROM call_registro WHERE fecha LIKE ?");
			$sql->bindValue(1,"%{$fechabuscar}%", PDO::PARAM_STR);
		}else{	
			$sql = $objdatabase->prepare("SELECT * FROM call_registro WHERE fecha LIKE ? AND usuario = ?");
			$sql->bindValue(1,"%{$fechabuscar}%", PDO::PARAM_STR);
			$sql->bindValue(2, $_SESSION['nick'], PDO::PARAM_STR);
		}			
		$sql->execute(); // se confirma que el query exista
		//Verificamos el resultado
		$count = $sql->rowCount();
		$data = array();
		if ($count){
			$result = $sql->fetchAll();
			foreach ($result as $key => $value){
				$id = $value['id'];
				$link = '<a class="link" href="#" id="'.$id.'" data-toggle="modal" data-placement="bottom" data-target="#observacion">'.$id.'</a>';
				$fecha = $value['fecha'];
				$usuario = $value['usuario'];
				$departamento =  $value['departamento'];
				$motivo = $value['motivo'];
				$sub_motivo = $value['sub_motivo'];
				$libced = $value['libced'];
				$users = $value['usersocial'];
					$findme   = '|';
					$pos = strpos($users, $findme);
					if ($pos === false) {
						$usersocial = $users;
					}else{
						if ($users != ''){
							$user = substr($users,0, $pos);
							$red = substr($users,$pos + 1, strlen($users));
							$usersocial = $user.'@'.$red;
						}else{
							$usersocial = '';
						}
					}
				$guiatracking = $value['guiatracking'];
				$estatus = $value['estatus'];
				$comentario =  $value['comentario'];
				$edit = '<img src="imagenes/gestion.png" class="edit cursor" id="'.$id.'" data-toggle="modal" data-placement="bottom" data-target="#editar" title="Editar Registro">';
				$data[] = array($link, $fecha, $usuario, $departamento, $motivo, $sub_motivo, $libced, $usersocial, $guiatracking, $edit);
			}			
		}
		$results = array("aaData"=>$data);
		echo json_encode($results);
	}

	//Search Register by Id
	function searchId(){
		$id = 
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT comentario FROM call_registro WHERE id =:id");
		$sql->bindParam(':id', $_POST['id'], PDO::PARAM_STR);
		$sql->execute(); // se confirma que el query exista	
		//Verificamos el resultado
		$count = $sql->rowCount();
		if ($count){
			$data = $sql->fetchColumn();
		}else{
			$data = 'Sin Comentario';
		}
		$objdatabase = null;
		echo $data;
	}

	//Edit
	function edit(){
		$comentario = utf8_decode(trim($_POST['comentario']));
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("UPDATE call_registro SET motivo =:motivo, sub_motivo =:submotivo, libced =:cedlib WHERE id = '$id'");
		$sql->bindParam(':id', $_POST['id'], PDO::PARAM_STR);
		$sql->bindParam(':motivo', $_POST['motivo'], PDO::PARAM_STR);
		$sql->bindParam(':submotivo', $_POST['submotivo'], PDO::PARAM_STR);
		$sql->bindParam(':cedlib', $_POST['cedlib'], PDO::PARAM_STR);
		if ($sql->execute()) {
		   $data = "1";
		} else {
		   $data = "0";
		}
		$objdatabase = null;
		echo $data;
	}

	$function  = $_POST['function']; //Obtener la Opción a realizar (Nuevo, editar, bloquear)
	switch ($function) {
		case "searchLib":
			searchLib();
			break;
		case "autocompleteCode":
			autocompleteCode();
			break;
		case "autocompleteGuide":
			autocompleteGuide();
			break;
		case "newRegister":
			newRegister();
			break;
		case "newRegisterIncidence":
			newRegisterIncidence();
		case "lista":
			lista();
			break;
		case "searchId":
			searchId();
			break;
		case "edit":
			edit();
		default:
			break;
	}	
}else{
	echo "notSessionActive";
}

?>