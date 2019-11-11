<?php
/***************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
setlocale(LC_TIME, 'es_VE'); # Localiza en español es_Venezuela
date_default_timezone_set('America/Caracas');
include_once 'database.php';
@session_start();
if(isset($_SESSION['user'])){

	// Get Aspects
	function getSituations(){
		$objdatabase = new Database();	
		$sql = $objdatabase->prepare("SELECT * FROM call_evaluacion_situation WHERE estatus = '1'");
		$sql->execute();//Exjecutamos la Query		
		$count = $sql->rowCount();//Verificamos el resultado
		$data = array();	
		if($count){
			$data = $sql->fetchAll();				
		}
		$objdatabase = null;
		return $data;	
	}

	// Get Aspect
	function getSituation($id){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT * FROM call_evaluacion_situation WHERE id =:id");
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

	// Insert Aspect
	function inserSituation($aspecto, $descripcion){
		if (searchAspectByName($atributo, $descripcion) == '0'){
			$objdatabase = new Database();
			$sql = $objdatabase->prepare("INSERT INTO call_evaluacion_situation (id_aspecto, descripcion) VALUES (:aspecto, :descripcion)");
			//Definimos los parametros de la Query			
			$sql->bindParam(':aspectoaspecto', strtoupper(trim($aspecto)), PDO::PARAM_STR);
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

	//Edit Aspect
	function editAspect($id, $atributo, $descripcion){
		if (searchAspectByName($atributo, $descripcion) == '0'){
			$objdatabase = new Database();
			$sql = $objdatabase->prepare("UPDATE call_evaluacion_aspecto SET id_atributo =:atributo, descripcion =:descripcion WHERE id =:id");
			$sql->bindParam(':id', $id, PDO::PARAM_STR);
			$sql->bindParam(':atributo', strtoupper(trim($atributo)), PDO::PARAM_STR);
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

	// Search Aspect By Name and Atribute
	function searchAspectByName($atributo, $descripcion){
		$objdatabase = new Database();	
		$sql = $objdatabase->prepare("SELECT * FROM call_evaluacion_aspecto WHERE id_atributo =:atributo AND descripcion= :descripcion");
		//Definimos los parametros de la Query
		$sql->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
		$sql->bindParam(':atributo', $atributo, PDO::PARAM_STR);
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



	if (isset($_POST['function'])){
		$function  = $_POST['function']; //Obtener la Opción a realizar (Nuevo, editar, bloquear)
		switch ($function) {
			case "getAspects":
				echo json_encode(array("aaData"=>getAspects()));
				break;
			case "insertAspect":
				insertAspect($_POST['atributo'], $_POST['descripcion']);
				break;
			case "editAspect":
				editAspect($_POST['id'], $_POST['atributo'], $_POST['descripcion']);
				break;
			default:
				break;
		}
	}
}else{
	echo "notSessionActive";
}

?>