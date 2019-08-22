<?php
include_once 'conexion.php';
include_once 'fecha.php';
include_once 'variables.php';
if(isset($_SESSION['user'])){
	$sql="SELECT call_incidencia.id, call_incidencia.estatus, fecha, departamento, motivo, sub_motivo, libced, guiatracking, comentario FROM call_incidencia INNER JOIN call_registro ON call_incidencia.id = call_registro.id WHERE call_incidencia.estatus <> 'Cerrada'";
	$ver=mysql_query($sql);		
   		$data = array();
			while($lista=mysql_fetch_array($ver)){
				$id = $lista['id'];
				$link = '<a class="link" href="#" id="'.$id.'" data-toggle="modal" data-placement="bottom" data-target="#reporte">'.$id.'</a>';
				$fecha = $lista['fecha'];
				$departamento = utf8_encode($lista['departamento']);
				$motivo = utf8_encode($lista['motivo']);
				$sub_motivo = utf8_encode($lista['sub_motivo']);
				$libced = utf8_encode($lista['libced']);
				$guiatracking = utf8_encode($lista['guiatracking']);
				$estatus = utf8_encode($lista['estatus']);
				$mensaje = '<img src="imagenes/comentar.png" class="mensaje cursor" id="'.$id.'" data-toggle="modal" data-placement="bottom" data-target="#comentar" title="Comentar">';	
				$edit = '<img src="imagenes/edit.png" class="edit cursor" id="'.$id.'" data-toggle="modal" data-placement="bottom" data-target="#editar" title="Editar">';						
				$data[] = array($link, $fecha, $departamento, $motivo, $sub_motivo,$libced,$guiatracking,$estatus, $mensaje, $edit);
			}//End While	
    //Mostramos los resultados
	$results = array("aaData"=>$data);
	echo json_encode($results);
	
mysql_close($conexion);
}else{
	header("location:../index.php?error=ingreso");
}
?>