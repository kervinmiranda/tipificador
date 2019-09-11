<?php
/***************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
setlocale(LC_TIME, 'es_VE'); # Localiza en español es_Venezuela
date_default_timezone_set('America/Caracas');
include_once 'database.php';
session_start();
if(isset($_SESSION['user'])){

	//Search SubMotive
	function searchSub(){
		$motivo = utf8_decode($_POST['elegido']);
		$objdatabase = new Database();
		$data = "";
		if ($motivo == "TODOS"){
			$sql = $objdatabase->prepare("SELECT DISTINCT secundaria FROM call_tipificacion WHERE estatus = '1' ORDER BY secundaria ASC ");	
		}else{
			$sql = $objdatabase->prepare("SELECT secundaria FROM call_tipificacion WHERE principal =:motivo AND estatus = '1' ORDER BY secundaria ASC");
			//Definimos los parametros de la Query
			$sql->bindParam(':motivo', $motivo, PDO::PARAM_STR);
		}
		$sql->execute();
		//Verificamos el resultado
		$count = $sql->rowCount();
		if ($count){
			$result = $sql->fetchAll();
			foreach ($result as $key => $value){
   				$data.= '<option value="'.utf8_encode($value['secundaria']).'">'.utf8_encode($value['secundaria']).'</option>';
   			}
		}
		$objdatabase = null;
		echo $data;
	}

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
					'usuario' => utf8_encode($value['usuario']),
					'departamento' => utf8_encode($value['departamento']),
					'motivo' => utf8_encode($value['motivo']),
					'sub_motivo' => utf8_encode($value['sub_motivo']),
					'libced' => utf8_encode($value['libced']),
					'usersocial' => utf8_encode($value['usersocial']),
					'guiatracking' => utf8_encode($value['guiatracking']),
					'comentario' => utf8_encode($value['comentario'])
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
	function newRegister(){
		$objdatabase = new Database();
		$pais = utf8_decode($_POST['pais']);
		$motivo = utf8_decode($_POST['motivo']);
		$submotivo = utf8_decode($_POST['submotivo']);
		$codigo = strtoupper(utf8_decode(str_replace(' ', '',$_POST['codigo'])));
		$codigo = preg_replace('/\s+/', '', $codigo);
		$guia = strtoupper(utf8_decode(str_replace(' ', '',$_POST['guia'])));
		$guia = preg_replace('/\s+/', '', $guia);		
		$comentario = utf8_decode(trim($_POST['comentario']));
		switch($_SESSION['departamento']){
			case 'REDES SOCIALES':
				$socialuser = strtolower(utf8_decode(str_replace(' ', '',$_POST['socialuser'])));
				$socialuser = str_replace('@', '', $socialuser);					
				$socialuser = preg_replace('/\s+/', '', $socialuser);					
			break;				
			default:
				$socialuser = '';					
		}
		$sql = $objdatabase->prepare("INSERT INTO call_registro (pais, fecha, usuario, departamento, motivo, sub_motivo, libced, usersocial, guiatracking, comentario, estatus) VALUES (:pais, :fecha, :userid, :departamento, :motivo, :submotivo, :codigo, :socialuser, :guia, :comentario, 1)");
		$sql->bindParam(':pais', $pais, PDO::PARAM_STR);
		$sql->bindParam(':fecha', date('Y/m/d'), PDO::PARAM_STR);
		$sql->bindParam(':userid', $_SESSION['nick'], PDO::PARAM_STR);
		$sql->bindParam(':departamento', utf8_decode($_SESSION['departamento']), PDO::PARAM_STR);
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
		echo $data;
	}

	// Tipifications List
	function lista(){
		$objdatabase = new Database();
		$fechabuscar = $_POST['fecha'];
		if ($_SESSION['nivel'] < 3){
			$sql = $objdatabase->prepare("SELECT * FROM call_registro WHERE fecha LIKE ?");			
		}else{	
			$sql = $objdatabase->prepare("SELECT * FROM call_registro WHERE usuario = '$userid' AND fecha LIKE ?");
			$sql->bindParam(':userid', $_SESSION['nick'], PDO::PARAM_STR);
		}
		$sql->bindValue(1,"%{$fechabuscar}%", PDO::PARAM_STR);
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
				$usuario = utf8_encode($value['usuario']);
				$departamento =  utf8_encode($value['departamento']);
				$motivo = utf8_encode($value['motivo']);
				$sub_motivo = utf8_encode($value['sub_motivo']);
				$libced = utf8_encode($value['libced']);
				$users = utf8_encode($value['usersocial']);
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
				$guiatracking = utf8_encode($value['guiatracking']);
				$estatus = utf8_encode($value['estatus']);
				$comentario =  utf8_encode($value['comentario']);
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
			$data = utf8_encode($sql->fetchColumn());
		}else{
			$data = 'Sin Comentario';
		}
		$objdatabase = null;
		echo $data;
	}
	

	$function  = $_POST['function']; //Obtener la Opción a realizar (Nuevo, editar, bloquear)
	switch ($function) {
		case "searchSub":
			searchSub();
			break;
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
		case "lista":
			lista();
			break;
		case "searchId":
			searchId();
			break;
		default:
			break;
	}	
}else{
	echo "notSessionActive";
}

?>