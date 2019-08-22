<?php
/*************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/	
session_start();
require 'conexion.php';
if(isset($_SESSION['user']) && ($_SESSION['nivel'] < 2)){
//Recogemos los datos del POST
$id = $_POST['id']; //Id de formulario
$data = array();

	//consulta de atributos
	$buscarAtributos = mysql_query("SELECT DISTINCT call_evaluacion_atributo.id, call_evaluacion_atributo.descripcion FROM call_evaluacion_form_detalle INNER JOIN call_evaluacion_situacion ON situacion = call_evaluacion_situacion.id
INNER JOIN call_evaluacion_aspecto ON call_evaluacion_situacion.id_aspecto = call_evaluacion_aspecto.id INNER JOIN call_evaluacion_atributo ON call_evaluacion_aspecto.id_atributo = call_evaluacion_atributo.id WHERE call_evaluacion_form_detalle.id_form = '$id' ORDER BY id");
		
		while($row = mysql_fetch_array($buscarAtributos)){
			$id_atributo = $row [0];
			$descripcion = utf8_encode($row[1]);
			$buscarAspectos = mysql_query("SELECT DISTINCT call_evaluacion_aspecto.id, call_evaluacion_aspecto.descripcion FROM call_evaluacion_form_detalle INNER JOIN call_evaluacion_situacion ON call_evaluacion_form_detalle.situacion = call_evaluacion_situacion.id INNER JOIN call_evaluacion_aspecto ON call_evaluacion_situacion.id_aspecto = call_evaluacion_aspecto.id INNER JOIN call_evaluacion_atributo ON call_evaluacion_aspecto.id_atributo = call_evaluacion_atributo.id WHERE call_evaluacion_form_detalle.id_form = '$id' AND call_evaluacion_atributo.id = '$id_atributo' ORDER BY descripcion ASC");		

			$aspectos = array();			
			while ($row2 = mysql_fetch_array($buscarAspectos)){
				$id_aspecto = $row2[0];
				$buscarSituaciones = mysql_query("SELECT situacion, call_evaluacion_situacion.descripcion, grupo, porc, call_evaluacion_aspecto.descripcion, call_evaluacion_atributo.descripcion FROM call_evaluacion_form_detalle INNER JOIN call_evaluacion_situacion ON situacion = call_evaluacion_situacion.id INNER JOIN call_evaluacion_aspecto ON call_evaluacion_situacion.id_aspecto = call_evaluacion_aspecto.id INNER JOIN call_evaluacion_atributo ON call_evaluacion_aspecto.id_atributo = call_evaluacion_atributo.id WHERE call_evaluacion_atributo.id = '$id_atributo' AND call_evaluacion_form_detalle.id_form = '$id' AND call_evaluacion_aspecto.id = '$id_aspecto'");
				$situaciones = array();
					while ($row3 = mysql_fetch_array($buscarSituaciones)){
						$situacion = utf8_encode($row3[1]);
						$grupo = utf8_encode($row3[2]);
						$porc = utf8_encode($row3[3]);
						$situaciones[] = array('situacion' => $situacion, 'grupo' => $grupo, 'porc' => $porc);
					}//End While
				$aspectos[] = array('aspecto' => utf8_encode($row2[1]), 'situaciones' => $situaciones);
			}//End While
		$data[] = array('id'=> $id_atributo, 'descripcion' => $descripcion, 'aspectos' => $aspectos);
		}//End While

echo json_encode($data);
}else{
	header("location:../index.php?error=ingreso");
}