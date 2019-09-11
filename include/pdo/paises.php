<?php
/***************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
	include_once 'database.php';
	function getPaises(){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT DISTINCT descripcion FROM call_pais WHERE estatus = '1' ORDER BY descripcion ASC");
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

?>