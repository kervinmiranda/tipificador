<?php
include_once 'fecha.php';
include_once 'conexion.php';		
include_once 'variables.php';
if(isset($_SESSION['user'])){	
$guia = $_POST['guia'];
$tipo = $_POST['tipo'];

switch ($tipo){
	case 'activa': $sql = "SELECT call_incidencia.id, call_incidencia.estatus, fecha, motivo, sub_motivo, libced, guiatracking, comentario FROM call_incidencia INNER JOIN call_registro ON call_incidencia.id = call_registro.id WHERE guiatracking = '$guia' AND call_incidencia.estatus <> 'Cerrada'";
	break;
	
	case 'historial': $sql = "SELECT call_incidencia.id, call_incidencia.estatus, fecha, motivo, sub_motivo, libced, guiatracking, comentario FROM call_incidencia INNER JOIN call_registro ON call_incidencia.id = call_registro.id WHERE guiatracking = '$guia' AND call_incidencia.estatus = 'Cerrada'";
}	
		$ver=mysql_query($sql);			
			$json = array();
				while($row=mysql_fetch_array($ver)){					
					$json[] = array('id' => '<a class="link" href="#" id="'.$row['id'].'" data-toggle="modal" data-placement="bottom" data-target="#reporte">'.$row['id'].'</a>',
						'fecha' => $row['fecha'],				
						'motivo' => utf8_encode($row['motivo']),
						'sub_motivo' => utf8_encode($row['sub_motivo']),
						'libced' => utf8_encode($row['libced']),
						'guiatracking' => utf8_encode($row['guiatracking']),
						'estatus' => utf8_encode($row['estatus']),
						'mensaje' => '<img src="imagenes/comentar.png" class="mensaje" id="'.$row['id'].'">',
						'edit' => '<img src="imagenes/gestion.png" class="edit" id="'.$row['id'].'">');
				}//End While		

$json['success'] = true;
echo json_encode($json);

mysql_close($conexion);
}else{
	header("location:../index.php?error=ingreso");
}
?>