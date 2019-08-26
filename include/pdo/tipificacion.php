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

	function getTipifications(){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT * FROM call_tipificacion");
		$sql->execute(); // se confirma que el query exista
		//Verificamos el resultado
		$count = $sql->rowCount();
		if ($count){
			$result = $sql->fetchAll();
			$data = array();
			foreach ($result as $key => $value){
				$id = $value['id'];
				$motivo = utf8_encode($value['principal']);
				$sub_motivo = utf8_encode($value['secundaria']);
				$est = utf8_encode($value['estatus']);
				switch ($est){
					case 0: $estatus = '<img src="imagenes/inactivo.png">';
							$block='<img src="imagenes/block2.png" class="camb cursor" title="Desbloquear Tipificación" id="'.$id.'│'.$est.'">';
					break;
					case 1: $estatus = '<img src="imagenes/activo.png">';
							$block='<img src="imagenes/block.png" class="camb cursor" title="Bloquear Tipificación" id="'.$id.'│'.$est.'">';
					break;
				}	

				$comando= '<img src="imagenes/edit.png" class="edit cursor" title="Editar Usuario" id="'.$id.'│'.$motivo.'│'.$sub_motivo.'">'.$block;
				$data[] = array($id, $motivo, $sub_motivo, $estatus, $comando);
			}	
			//Mostramos los resultados
			$results = array("aaData"=>$data);
			echo json_encode($results);
		}
		$objdatabase = null;
	}

	function newTipification(){
		$motivo = utf8_decode(strtoupper($_POST['motivo']));
		$sub_motivo = utf8_decode(strtoupper($_POST['sub_motivo']));
		$objdatabase = new Database();
		$buscar = $objdatabase->prepare("SELECT id FROM call_tipificacion WHERE principal =:motivo AND secundaria =:sub_motivo");
		//Definimos los parametros de la Query
		$buscar->bindParam(':motivo', $motivo, PDO::PARAM_STR);
		$buscar->bindParam(':sub_motivo', $sub_motivo, PDO::PARAM_STR);
		


	}

	$function  = $_POST['function']; //Obtener la Opción a realizar (Nuevo, editar, bloquear)
	switch ($function) {
		case "getTipifications":
			getTipifications();
			break;
		case "newTipification"
			newTipification();
			break;
		default:
			break;
	}

}else{
	echo "notSessionActive";
}
?>