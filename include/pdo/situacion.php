<?php
/***************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
setlocale(LC_TIME, 'es_VE'); # Localiza en español es_Venezuela
date_default_timezone_set('America/Caracas');
include_once 'database.php';
@session_start();
if(isset($_SESSION['user'])){

	// Get Situations
	function getSituations(){
		$objdatabase = new Database();	
		$sql = $objdatabase->prepare("SELECT * FROM call_evaluacion_situacion WHERE estatus = '1'");
		$sql->execute();//Exjecutamos la Query		
		$count = $sql->rowCount();//Verificamos el resultado
		$data = array();	
		if($count){
			$data = $sql->fetchAll();				
		}
		$objdatabase = null;
		return $data;	
	}

	// Get Situations
	function getAllSituations(){
		$objdatabase = new Database();	
		$sql = $objdatabase->prepare("SELECT call_evaluacion_atributo.id, call_evaluacion_situacion.descripcion, call_evaluacion_situacion.grupo, call_evaluacion_aspecto.descripcion, call_evaluacion_atributo.descripcion, call_evaluacion_situacion.estatus 
			FROM call_evaluacion_situacion
			INNER JOIN  call_evaluacion_aspecto ON call_evaluacion_situacion.id_aspecto = call_evaluacion_aspecto.id
			INNER JOIN call_evaluacion_atributo ON call_evaluacion_aspecto.id_atributo = call_evaluacion_atributo.id");
		$sql->execute();//Exjecutamos la Query		
		$count = $sql->rowCount();//Verificamos el resultado
		$data = array();	
		if($count){
			$data = $sql->fetchAll();				
		}
		$objdatabase = null;
		return $data;	
	}

	// Get Situations
	function getSituation($id){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT call_evaluacion_situacion.id, call_evaluacion_aspecto.id as id_aspecto, 
			call_evaluacion_aspecto.descripcion as aspecto, call_evaluacion_atributo.id as id_atributo,
			call_evaluacion_atributo.descripcion as atributo, call_evaluacion_situacion.descripcion,
			call_evaluacion_situacion.grupo, call_evaluacion_situacion.estatus
			FROM call_evaluacion_situacion
			INNER JOIN call_evaluacion_aspecto ON call_evaluacion_situacion.id_aspecto = call_evaluacion_aspecto.id
			INNER JOIN call_evaluacion_atributo ON call_evaluacion_aspecto.id_atributo = call_evaluacion_atributo.id WHERE call_evaluacion_situacion.id =:id");
		$sql->bindParam(':id', $id, PDO::PARAM_STR);
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

	// Insert Situations
	function insertSituation($aspecto, $descripcion, $grupo){
		if (searchSituationByName($aspecto, $descripcion) == '0'){
			$objdatabase = new Database();
			$sql = $objdatabase->prepare("INSERT INTO call_evaluacion_situacion (id_aspecto, descripcion, grupo) VALUES (:aspecto, :descripcion, :grupo)");
			//Definimos los parametros de la Query			
			$sql->bindParam(':aspecto', strtoupper(trim($aspecto)), PDO::PARAM_STR);
			$sql->bindParam(':descripcion', strtoupper(trim($descripcion)), PDO::PARAM_STR);
			$sql->bindParam(':grupo', strtoupper(trim($grupo)), PDO::PARAM_STR);
			$sql->execute(); // se confirma que el query existas		
			$count = $sql->rowCount();//Verificamos el resultado
			if ($count){
			   $data = "1";
			} else {
			   $data = "0";
			}
			$objdatabase = null;
		}else{
			$data = 'repetido';
		}
		return $data;
	}

	// function Edit Situation
	function editSituation($id, $aspecto, $descripcion, $grupo){
		if (searchSituationByName($aspecto, $descripcion) == '0'){
			$objdatabase = new Database();
			$sql = $objdatabase->prepare("UPDATE call_evaluacion_situacion SET id_aspecto =:aspecto, descripcion =:descripcion, grupo =:grupo WHERE id =:id");
			//Definimos los parametros de la Query
			$sql->bindParam(':aspecto', $aspecto, PDO::PARAM_STR);
			$sql->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
			$sql->bindParam(':grupo', $grupo, PDO::PARAM_STR);
			$sql->bindParam(':id', $id, PDO::PARAM_STR);
			$sql->execute(); // se confirma que el query existas	
			$count = $sql->rowCount();//Verificamos el resultado
			if ($count){
			   $data = "1";
			} else {
			   $data = "0";
			}
			$objdatabase = null;
		}else{
			$data = 'repetido';
		}
		return $data;
	}

	// Search Situation By Name and Atribute
	function searchSituationByName($aspecto, $descripcion){
		$objdatabase = new Database();	
		$sql = $objdatabase->prepare("SELECT * FROM call_evaluacion_situacion WHERE id_aspecto =:aspecto AND descripcion= :descripcion");
		//Definimos los parametros de la Query
		$sql->bindParam(':aspecto', $aspecto, PDO::PARAM_STR);
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

	// Status Aspect
	function statusSituation($id){
		$search = getSituation($id);
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
			$sql = $objdatabase->prepare("UPDATE call_evaluacion_situacion SET estatus =:newStatus WHERE id =:id");
			$sql->bindParam(':id', $id, PDO::PARAM_STR);
			$sql->bindParam(':newStatus', $newStatus, PDO::PARAM_STR);
			$sql->execute(); // se confirma que el query existas		
			$count = $sql->rowCount();//Verificamos el resultado
			if ($count){
			   $data = "1";
			} else {
			   $data = "0";
			}
			$objdatabase = null;
		}else{
			$data = "0";
		}
		return $data;
	}

	function newForm(){
		$objdatabase = new Database();	
		$sql = $objdatabase->prepare("SELECT call_evaluacion_situacion.id as idSituacion, call_evaluacion_atributo.descripcion as descripcionAtributo, call_evaluacion_aspecto.descripcion as descripcionAspecto, call_evaluacion_situacion.grupo as situacionGrupo, call_evaluacion_situacion.descripcion as descripcionSituacion FROM call_evaluacion_situacion INNER JOIN call_evaluacion_aspecto ON call_evaluacion_situacion.id_aspecto = call_evaluacion_aspecto.id INNER JOIN call_evaluacion_atributo ON call_evaluacion_aspecto.id_atributo = call_evaluacion_atributo.id ORDER BY call_evaluacion_atributo.id, descripcionAspecto,  descripcionSituacion ASC");
		$sql->execute();//Exjecutamos la Query		
		$count = $sql->rowCount();//Verificamos el resultado
		$data = array();	
		if($count){
			$data = $sql->fetchAll();				
		}
		$objdatabase = null;
		return $data;		
	}
	
	if (isset($_POST['function'])){
		$function  = $_POST['function']; //Obtener la Opción a realizar (Nuevo, editar, bloquear)
		switch ($function) {
			case "getAllSituations":
				echo json_encode(array("aaData"=>getAllSituations()));
				break;
			case "getSituations":
				echo json_encode(getSituations());
				break;
			case "getSituation":
				echo json_encode(getSituation($_POST['id']));
				break;
			case "insertSituation":
				echo json_encode(insertSituation($_POST['aspecto'], $_POST['descripcion'], $_POST['grupo']));
				break;
			case "editSituation":
				echo editSituation($_POST['id'], $_POST['aspecto'], $_POST['descripcion'], $_POST['grupo']);
				break;
			case "statusSituation":
				echo statusSituation($_POST['id']);
				break;
			case "newForm":
				echo json_encode(array("aaData"=>newForm()));
				break;	
			default:
				break;
		}
	}
}else{
	echo "notSessionActive";
}

?>