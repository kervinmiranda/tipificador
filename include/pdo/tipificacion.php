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

	//New Tipification
	function newTipification(){
		$motivo = utf8_decode(strtoupper($_POST['motivo']));
		$sub_motivo = utf8_decode(strtoupper($_POST['sub_motivo']));
		$exists = searchTipification($motivo, $sub_motivo);
		if ($exists ==  false){
			$objdatabase = new Database();
			$sql = $objdatabase->prepare("INSERT INTO call_tipificacion (principal, secundaria) VALUES (:motivo, :sub_motivo)");
			//Definimos los parametros de la Query
			$sql->bindParam(':motivo', $motivo, PDO::PARAM_STR);
			$sql->bindParam(':sub_motivo', $sub_motivo, PDO::PARAM_STR);
			if ($sql->execute()) { 
			   	$data = "1";
			}else{
				$data = "0";
			}
			$objdatabase = null;		
		}else{
			$data = "repetido";
		}
		$objdatabase = null;
		echo $data;
	}

	// Edit Tipification
	function editTipification(){
		$id = $_POST['id'];
		$motivo = utf8_decode(strtoupper($_POST['motivo']));
		$sub_motivo = utf8_decode(strtoupper($_POST['sub_motivo']));
		$exists = searchTipification($motivo, $sub_motivo);
		if ($exists ==  false){
			$objdatabase = new Database();
			$sql = $objdatabase->prepare("UPDATE call_tipificacion SET principal =:motivo, secundaria =:sub_motivo WHERE id =:id");
			//Definimos los parametros de la Query
			$sql->bindParam(':id', $id, PDO::PARAM_STR);
			$sql->bindParam(':motivo', $motivo, PDO::PARAM_STR);
			$sql->bindParam(':sub_motivo', $sub_motivo, PDO::PARAM_STR);
			if ($sql->execute()) { 
			   	$data = "1";
			}else{
				$data = "0";
			}
			$objdatabase = null;		
		}else{
			$data = "repetido";
		}
		$objdatabase = null;
		echo $data;
	}

	// Search Tipification
	function searchTipification($motivo, $sub_motivo){
		$boolean = false;
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT id FROM call_tipificacion WHERE principal =:motivo AND secundaria =:sub_motivo");
		//Definimos los parametros de la Query
		$sql->bindParam(':motivo', $motivo, PDO::PARAM_STR);
		$sql->bindParam(':sub_motivo', $sub_motivo, PDO::PARAM_STR);
		//Exjecutamos la Query
		$sql->execute(); // se confirma que el query exista
		//Verificamos el resultado
		$count = $sql->rowCount();
		if ($count){
			$boolean = true;
		}else{
		}
		$objdatabase = null;
		return $boolean;
	}

	// Change Status
	function changeStatus(){
		$objdatabase = new Database();
		if($_POST['estatus'] == 0){
			$estatus_nuevo = 1;
		}else if($_POST['estatus'] == 1){
			$estatus_nuevo = 0;
		}
		$sql = $objdatabase->prepare("UPDATE call_tipificacion SET estatus =:estatus_nuevo WHERE id =:id");
		$sql->bindParam(':id', $_POST['id'], PDO::PARAM_STR);
		$sql->bindParam(':estatus_nuevo', $estatus_nuevo, PDO::PARAM_STR);
		$sql->execute(); // se confirma que el query exista	
		//Verificamos el resultado
		$count = $sql->rowCount();
			if ($count){
				$data = "1";
			}else{
				$data = "0";
			}
		$objdatabase = null;
		echo $data;
	}

	//Search Lib o cédula
	function searchLib(){
		$tipo = $_POST['tipo'];
		$objdatabase = new Database();
		$codigo = $_POST['codigo'];
		switch ($tipo) {
			case "lib":
				$sql = $objdatabase->prepare("SELECT * FROM call_registro WHERE libced =:libced ORDER BY fecha DESC");
				$sql->bindParam(':libced', $codigo, PDO::PARAM_STR);
				break;
			case "guia":
				$sql = $objdatabase->prepare("SELECT * FROM call_registro WHERE guiatracking LIKE '%:guia%' ORDER BY fecha DESC");
				$sql->bindParam(':guia', $codigo, PDO::PARAM_STR);
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

	function searchCode(){
		$objdatabase = new Database();
		$codigo = $_POST['codigo'];
		$sql = $objdatabase->prepare("SELECT DISTINCT libced FROM call_registro WHERE libced LIKE '%$codigo%' ORDER BY libced");
		$sql->bindParam(':codigo', $codigo, PDO::PARAM_STR);
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

	$function  = $_POST['function']; //Obtener la Opción a realizar (Nuevo, editar, bloquear)
	switch ($function) {
		case "getTipifications":
			getTipifications();
			break;
		case "newTipification":
			newTipification();
			break;
		case "editTipification":
			editTipification();
			break;
		case "changeStatus":
			changeStatus();
			break;
		case "searchLib":
			searchLib();
			break;
		case "searchCode":
			searchCode();
			break;
		default:
			break;
	}

}else{
	echo "notSessionActive";
}
?>