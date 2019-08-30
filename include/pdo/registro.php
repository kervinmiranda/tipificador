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
   				$data.= '<option value="'.$value['secundaria'].'">'.utf8_encode($value['secundaria']).'</option>';
   			}
		}
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
		default:
			break;
	}	
}else{
	echo "notSessionActive";
}

?>