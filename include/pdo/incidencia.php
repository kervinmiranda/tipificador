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
				$sql = $objdatabase->prepare("SELECT call_incidencia.id, call_incidencia.estatus, fecha, motivo, sub_motivo, libced, guiatracking, comentario FROM call_incidencia INNER JOIN call_registro ON call_incidencia.id = call_registro.id WHERE guiatracking = :guia AND call_incidencia.estatus <> 'Cerrada'");				
				break;
			case "historial":
				$sql = $objdatabase->prepare("SELECT call_incidencia.id, call_incidencia.estatus, fecha, motivo, sub_motivo, libced, guiatracking, comentario FROM call_incidencia INNER JOIN call_registro ON call_incidencia.id = call_registro.id WHERE guiatracking =:guia AND call_incidencia.estatus = 'Cerrada'");
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
					'fecha' => $value['fecha'],
					'motivo' => $value['motivo'],
					'sub_motivo' => $value['sub_motivo'],
					'libced' => $value['libced'],
					'guiatracking' => $value['guiatracking'],
					'estatus' => $value['estatus'],
					'mensaje' => '<img src="imagenes/comentar.png" class="mensaje" id="'.$value['id'].'">',
					'edit' => '<img src="imagenes/gestion.png" class="edit" id="'.$value['id'].'">');
			}
			$json['success'] = true;
			echo json_encode($json);
		}
		$objdatabase = null;
	}

	if (isset($_POST['function'])){
		$function  = $_POST['function']; //Obtener la Opción a realizar
		switch ($function) {
			case "getIncidents":
				getIncidents();
			case "incidentManagement":
				incidentManagement();
				break;
			case "getIncident":
				getIncident();
			default:
				break;
		}
	}
}else{
	echo "notSessionActive";
}
?>