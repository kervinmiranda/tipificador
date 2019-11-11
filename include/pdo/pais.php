<?php
/***************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
setlocale(LC_TIME, 'es_VE'); # Localiza en español es_Venezuela
date_default_timezone_set('America/Caracas');
include_once 'database.php';
@session_start();
if(isset($_SESSION['user'])){
	
	//Get Active Countries
	function getCountries(){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT * FROM call_pais WHERE estatus = '1' ORDER BY descripcion ASC");		
		$sql->execute(); //Exjecutamos la Query
		$count = $sql->rowCount(); // se confirma que el query exista	
		$data = null;		
		if($count){
			$data = $sql->fetchAll();				
		}
		$objdatabase = null;
		return $data;
	}

	// Get All Countries
	function getAllCountries(){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT * FROM call_pais ORDER BY descripcion ASC");		
		$sql->execute();//Exjecutamos la Query		
		$count = $sql->rowCount();//Verificamos el resultado
		$data = null;		
		if($count){
			$data = $sql->fetchAll();				
		}
		$objdatabase = null;
		$results = array("aaData"=>$data);
		echo json_encode($results);
	}

	// Insert Country
	function insertCountry($name){
		if (searchByName($name) == '0'){
			$objdatabase = new Database();
			$sql = $objdatabase->prepare("INSERT INTO call_pais (descripcion, estatus) VALUES (:name, 1)");
			$sql->bindParam(':name', strtoupper(trim($name)), PDO::PARAM_STR);
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

	// Get Country
	function getCountry($id){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT * FROM call_pais WHERE id =:id");
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

	// Edit Country
	function editCountry($id, $name){
		if (searchByName($name) == '0'){
			$objdatabase = new Database();
			$sql = $objdatabase->prepare("UPDATE call_pais SET descripcion =:name WHERE id =:id");
			$sql->bindParam(':id', $id, PDO::PARAM_STR);
			$sql->bindParam(':name', strtoupper(trim($name)), PDO::PARAM_STR);
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

	// Search By Name
	function searchByName($name){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT * FROM call_pais WHERE descripcion =:name");
		$sql->bindParam(':name', $name, PDO::PARAM_STR);
		$sql->execute(); // se confirma que el query existas		
		$count = $sql->rowCount();//Verificamos el resultado
		if ($count){
			$data = $sql->fetch(PDO::FETCH_OBJ);
		}else{
			$data = "0";
		}
		$objdatabase = null;
		return $data;
	}

	// Change Status
	function statusCountry($id){
		$search = getCountry($id);
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
			$sql = $objdatabase->prepare("UPDATE call_pais SET estatus =:newStatus WHERE id =:id");
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
		$function  = $_POST['function']; //Obtener la Opción a realizar
		switch ($function) {
			case "getAllCountries":
				getAllCountries();
				break;
			case "getCountry":
				echo json_encode(getCountry($_POST['id']));
				break;
			case "editCountry":
				echo editCountry($_POST['id'], $_POST['nombre']);
				break;
			case "insertCountry":
				echo insertCountry($_POST['nombre']);
				break;
			case "statusCountry":
				echo statusCountry($_POST['id']);
				break;
			default:
				break;
		}
	}

	
}else{
	echo "notSessionActive";
}

?>