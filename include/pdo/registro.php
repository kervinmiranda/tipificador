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
	
	function buscarSub(){
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
	

	$function  = $_POST['function']; //Obtener la Opción a realizar (Nuevo, editar, bloquear)
	switch ($function) {
		case "buscarSub":
			buscarSub();
			break;
		default:
			break;
	}	
}else{
	echo "notSessionActive";
}

?>