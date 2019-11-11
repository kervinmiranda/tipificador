<?php
/***************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
setlocale(LC_TIME, 'es_VE'); # Localiza en español es_Venezuela
date_default_timezone_set('America/Caracas');
include_once 'database.php';
@session_start();
if(isset($_SESSION['user'])){
	
	// Get Active Atributes
	function getAtributes(){
		$objdatabase = new Database();	
		$sql = $objdatabase->prepare("SELECT * FROM call_evaluacion_atributo WHERE estatus = '1'");
		$sql->execute();//Exjecutamos la Query		
		$count = $sql->rowCount();//Verificamos el resultado
		$data = array();		
		if($count){
			$data = $sql->fetchAll();				
		}
		$objdatabase = null;
		return $data;
	}

	// Get Atributes
	function getAllAtributes(){
		$objdatabase = new Database();	
		$sql = $objdatabase->prepare("SELECT * FROM call_evaluacion_atributo");
		$sql->execute();//Exjecutamos la Query		
		$count = $sql->rowCount();//Verificamos el resultado
		$data = array();		
		if($count){
			$data = $sql->fetchAll();				
		}
		$objdatabase = null;
		return $data;
	}

	// Get Atribute
	function getAtribute($id){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT * FROM call_evaluacion_atributo WHERE id =:id");
		$sql->bindParam(':id', $id, PDO::PARAM_STR);
		$sql->execute(); // se confirma que el query existas		
		$count = $sql->rowCount();//Verificamos el resultado
		if ($count){
			$result = $sql->fetch(PDO::FETCH_OBJ);
		}else{
			$result = "0";
		}
		$objdatabase = null;
		return $result;
	}

	// Search By Name
	function searchByName($descripcion){
		$objdatabase = new Database();	
		$sql = $objdatabase->prepare("SELECT * FROM call_evaluacion_atributo WHERE descripcion= :descripcion");
		//Definimos los parametros de la Query
		$sql->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
		$sql->execute();//Exjecutamos la Query
		$count = $sql->rowCount();//Verificamos el resultado
		if ($count){
			$data = $sql->fetch(PDO::FETCH_OBJ);
		}else{
			$data = "0";
		}
		$objdatabase = null;
		return $data;
	}

	// Insert Atribute
	function insertAtribute($descripcion){
		if (searchByName($descripcion) == "0"){
			$objdatabase = new Database();
			$sql = $objdatabase->prepare("INSERT INTO call_evaluacion_atributo (descripcion) VALUES (:descripcion)");
			$sql->bindParam(':descripcion', strtoupper(trim($descripcion)), PDO::PARAM_STR);
			if ($sql->execute()){
			   	$data = "1";
			}else{
				$data = "0";
			}		
			$objdatabase = null;
		}else{
			$data = 'repetido';
		}
		return $data;
	}

	// Update Atribute
	function editAtribute($id, $descripcion){
		if (searchByName($descripcion) == '0'){
			$objdatabase = new Database();
			$sql = $objdatabase->prepare("UPDATE call_evaluacion_atributo SET descripcion =:descripcion WHERE id =:id");
			$sql->bindParam(':id', $id, PDO::PARAM_STR);
			$sql->bindParam(':descripcion', strtoupper(trim($descripcion)), PDO::PARAM_STR);
			$sql->execute(); // se confirma que el query existas		
			$count = $sql->rowCount();//Verificamos el resultado
			if ($count){
			   $data = "1";
			} else {
			   $data = "0";
			}
		}else{
			$data = 'repetido';
		}
		return $data;
	}

	// Status Atribute
	function statusAtribute($id){
		$search = getAtribute($id);
		if ($search != "0"){
			$estatus = $search->estatus;
			switch ($estatus) {
				case '0':
					$newStatus = "1";
					break;
				case '1':
					$newStatus = "0";
					break;				
				default:
					$newStatus = "0";
					break;
			}
			$objdatabase = new Database();
			$sql = $objdatabase->prepare("UPDATE call_evaluacion_atributo SET estatus =:newStatus WHERE id =:id");
			$sql->bindParam(':id', $id, PDO::PARAM_STR);
			$sql->bindParam(':newStatus', $newStatus, PDO::PARAM_STR);
			$sql->execute(); // se confirma que el query existas		
			$count = $sql->rowCount();//Verificamos el resultado
			if ($count){
			   $data = "1";
			} else {
			   $data = "0";
			}
		}else{
			$data = "0";
		}
		return $data;
	}
	
	if (isset($_POST['function'])){
		$function  = $_POST['function']; //Obtener la Opción a realizar (Nuevo, editar, bloquear)
		switch ($function) {
			case "getAtributes":
				echo json_encode(getAtributes());
				break;
			case "getAllAtributes":
				echo json_encode(array("aaData"=>getAllAtributes()));
				break;
			case "insertAtribute":
				echo insertAtribute($_POST['descripcion']);
				break;
			case "getAtribute":
				echo json_encode(getAtribute($_POST['id']));
				break;
			case "editAtribute":
				echo editAtribute($_POST['id'], $_POST['descripcion']);
				break;
			case "statusAtribute":
				echo statusAtribute($_POST['id']);
				break;
			default:
				break;
		}
	}
}else{
	echo "notSessionActive";
}

?>