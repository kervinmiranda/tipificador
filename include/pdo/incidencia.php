<?php
/***************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
setlocale(LC_TIME, 'es_VE'); # Localiza en español es_Venezuela
date_default_timezone_set('America/Caracas');
include_once 'database.php';
session_start();
if(isset($_SESSION['user'])){

	// Get  Incidents
	function getIncidents(){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT call_incidencia.id, call_incidencia.estatus, fecha, departamento, motivo, sub_motivo, libced, guiatracking, comentario FROM call_incidencia INNER JOIN call_registro ON call_incidencia.id = call_registro.id WHERE call_incidencia.estatus <> 'Cerrada'");
		$sql->execute(); // se confirma que el query exista
		//Verificamos el resultado
		$count = $sql->rowCount();
		$data = array();
		if ($count){
			$result = $sql->fetchAll();
			foreach ($result as $key => $value){
				$id = $value['id'];
				$link = '<a class="link" href="#" id="'.$id.'" data-toggle="modal" data-placement="bottom" data-target="#reporte">'.$id.'</a>';
				$fecha = $value['fecha'];
				$departamento = $value['departamento'];
				$motivo = $value['motivo'];
				$sub_motivo = $value['sub_motivo'];
				$libced = $value['libced'];
				$guiatracking = $value['guiatracking'];
				$estatus = $value['estatus'];
				$mensaje = '<img src="imagenes/comentar.png" class="mensaje cursor" id="'.$id.'" data-toggle="modal" data-placement="bottom" data-target="#comentar" title="Comentar">';
				$edit = '<img src="imagenes/edit.png" class="edit cursor" id="'.$id.'" data-toggle="modal" data-placement="bottom" data-target="#editar" title="Editar">';
				$data[] = array($link, $fecha, $departamento, $motivo, $sub_motivo,$libced,$guiatracking,$estatus, $mensaje, $edit);
			}
		}
		$objdatabase = null;
		$results = array("aaData"=>$data);
		echo json_encode($results);
	}

	// Incident Management
	function incidentManagement(){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT * FROM call_gestion WHERE id =:id ORDER BY fecha DESC");
		//Definimos los parametros de la Query
		$sql->bindParam(':id', $_POST['id'], PDO::PARAM_STR);
		$sql->execute(); // se confirma que el query exista
		//Verificamos el resultado
		$count = $sql->rowCount();
		$data = array();
		if ($count){
			$result = $sql->fetchAll();
			foreach ($result as $key => $value){
				$fec = date_create($value['fecha']);
				$json[] = array(
					'fecha' => date_format($fec, 'd/m/Y h:i a'),
					'gestor' => $value['gestor'],
					'estatus' => $value['estatus'],
					'comentario' => $value['comentario']
				);
			}
			$objdatabase = null;
			$json['success'] = true;
			echo json_encode($json);
		}
	}

	// Get Incident
	function getIncident(){
		$guia = $_POST['guia'];
		$tipo = $_POST['tipo'];
		$objdatabase = new Database();
		switch ($tipo) {
			case "activa":
				$sql = $objdatabase->prepare("SELECT call_incidencia.id, call_incidencia.estatus, fecha, motivo, sub_motivo, libced, guiatracking, comentario, departamento FROM call_incidencia INNER JOIN call_registro ON call_incidencia.id = call_registro.id WHERE guiatracking = :guia AND call_incidencia.estatus <> 'Cerrada'");				
				break;
			case "historial":
				$sql = $objdatabase->prepare("SELECT call_incidencia.id, call_incidencia.estatus, fecha, motivo, sub_motivo, libced, guiatracking, comentario, departamento FROM call_incidencia INNER JOIN call_registro ON call_incidencia.id = call_registro.id WHERE guiatracking =:guia AND call_incidencia.estatus = 'Cerrada'");
				break;			
			default:				
				break;
		}
		$sql->bindParam(':guia', $guia, PDO::PARAM_STR);
		$sql->execute(); // se confirma que el query exista	
		//Verificamos el resultado
		$count = $sql->rowCount();
		if ($count){
			$json = array();
			$result = $sql->fetchAll();
			foreach ($result as $key => $value){
				$json[] = array(
					'id' => '<a class="link" href="#" id="'.$value['id'].'" data-toggle="modal" data-placement="bottom" data-target="#reporte">'.$value['id'].'</a>',
					'departamento' => $value['departamento'],
					'fecha' => $value['fecha'],
					'motivo' => $value['motivo'],
					'sub_motivo' => $value['sub_motivo'],
					'libced' => $value['libced'],
					'guiatracking' => $value['guiatracking'],
					'estatus' => $value['estatus'],
					'mensaje' => '<img src="imagenes/comentar.png" class="mensaje cursor" id="'.$value['id'].'" data-toggle="modal" data-placement="bottom" data-target="#comentar" title="Comentar">',
					'edit' => '<img src="imagenes/edit.png" class="edit cursor" id="'.$value['id'].'" data-toggle="modal" data-placement="bottom" data-target="#editar" title="Editar">'
			);
			}
			$json['success'] = true;
			echo json_encode($json);
		}
		$objdatabase = null;
	}

	// Search Incident
	function searchIncident($id){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT estatus FROM call_incidencia WHERE id =:id");
		$sql->bindParam(':id', $id, PDO::PARAM_STR);
		$sql->execute(); // se confirma que el query exista		
		//Verificamos el resultado
		$count = $sql->rowCount();
		if ($count){
			$data = $sql->fetchColumn();
		}else{
			$data = '0';
		}
		$objdatabase = null;
		return $data;
	}

	// Insert Comment
	function insertcomment(){
		$objdatabase = new Database();
		$estatus = searchIncident($_POST['id']);
		if ($estatus != '0'){
			$objdatabase = new Database();
			$sql = $objdatabase->prepare("INSERT INTO call_gestion (id, fecha, gestor, comentario, estatus) VALUES (:id, :fecha, :userid, :comentario, :estatus)");
			$sql->bindParam(':id', $_POST['id'], PDO::PARAM_STR);
			$sql->bindParam(':fecha', date('Y-m-d H:i:s'), PDO::PARAM_STR);
			$sql->bindParam(':userid', $_SESSION['nick'], PDO::PARAM_STR);
			$sql->bindParam(':comentario', trim($_POST['comentario']), PDO::PARAM_STR);
			$sql->bindParam(':estatus', $estatus, PDO::PARAM_STR);
			$sql->execute(); // se confirma que el query exista		
			$count = $sql->rowCount();//Verificamos el resultado
			if ($count){
			   	$data = "1";
			}else{
				$data = "0";
			}		
			$objdatabase = null;
		}else{
			$data = "0";
		}
		return $data;
	}

	if (isset($_POST['function'])){
		$function  = $_POST['function']; //Obtener la Opción a realizar
		switch ($function) {
			case "getIncidents":
				getIncidents();
				break;
			case "incidentManagement":
				incidentManagement();
				break;
			case "getIncident":
				getIncident();
			case "insertcomment":
				echo insertcomment();
				break;
			default:
				break;
		}
	}
}else{
	echo "notSessionActive";
}
?>