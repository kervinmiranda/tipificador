<?php
/***************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
include_once 'database.php';
@session_start();
	if(isset($_SESSION['user'])){
		//Get Modules
		function getModules(){
			$objdatabase = new Database();
			$sql = $objdatabase->prepare("SELECT * FROM call_modulo");
			//Exjecutamos la Query
			$sql->execute(); // se confirma que el query exista
			//Verificamos el resultado
			$count = $sql->rowCount();
			$data = null;		
			if($count){
				$data = $sql->fetchAll();					
			}
			$objdatabase = null;
			return $data;
		}
	}else{
	echo "notSessionActive";
}
?>