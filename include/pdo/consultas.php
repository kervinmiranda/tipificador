<?php
/***************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
	include_once 'database.php';
	
	//Get Modules
	function getModulos(){
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
		return $data;
	}

	function getMotives(){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT DISTINCT principal FROM call_tipificacion");
		//Exjecutamos la Query
		$sql->execute(); // se confirma que el query exista
		//Verificamos el resultado
		$count = $sql->rowCount();
		$data = null;		
		if($count){
			$data = $sql->fetchAll();					
		}
		return $data;
	}

?>