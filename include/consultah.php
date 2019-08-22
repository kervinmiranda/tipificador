<?php
include_once 'conexion.php';
include_once 'fecha.php';
include_once 'variables.php';
if(isset($_SESSION['user'])){
$contador = 0;
$guias = $_POST['lista'];
$data = array();

//Buscamos todo
	if ($guias == 'Todo'){
		$sql = "SELECT call_incidencia.id, call_incidencia.estatus, fecha, departamento, motivo, sub_motivo, libced, guiatracking, comentario FROM call_incidencia INNER JOIN call_registro ON call_incidencia.id = call_registro.id WHERE call_incidencia.estatus = 'Cerrada'";
		$ver = mysql_query($sql);			
				while($lista = mysql_fetch_array($ver)){
					$id = $lista['id'];
					$link = '<a class="link" href="#" id="'.$id.'">'.$id.'</a>';
					$apertura = $lista['fecha'];
					$consulta = mysql_query("SELECT fecha FROM call_gestion WHERE estatus = 'Cerrada' AND id = '$id' limit 1");
						$row = mysql_fetch_row($consulta);
						$cierre = $row[0];
					$departamento = utf8_encode($lista['departamento']);
					$motivo = utf8_encode($lista['motivo']);
					$sub_motivo = utf8_encode($lista['sub_motivo']);
					$libced = utf8_encode($lista['libced']);
					$guiatracking = utf8_encode($lista['guiatracking']);
					$estatus = utf8_encode($lista['estatus']);
					$edit = '<img src="imagenes/edit.png" class="edit cursor" id="'.$id.'" title="Editar Estatus">';			
					$data[] = array($link, $apertura, $cierre, $departamento, $motivo, $sub_motivo,$libced,$guiatracking,$estatus, $edit);
				}//End While	
	}else{
//Buscamos el arreglo enviado		
		foreach ($guias as &$valor) {
		$guia = $valor;
		$buscar = mysql_query("SELECT call_incidencia.id, call_incidencia.estatus, fecha, departamento, motivo, sub_motivo, libced, guiatracking, comentario FROM call_incidencia INNER JOIN call_registro ON call_incidencia.id = call_registro.id WHERE guiatracking = '$guia' AND call_incidencia.estatus = 'Cerrada'");
			if (mysql_num_rows($buscar)){		
				//Recorremos los datos obtenidos
					while($row = mysql_fetch_array($buscar)){
						$id = $row['id'];
						$link = '<a class="link" href="#" id="'.$id.'">'.$id.'</a>';
						$apertura = $row['fecha'];
						$consulta = mysql_query("SELECT fecha FROM call_gestion WHERE estatus = 'Cerrada' AND id = '$id' limit 1");
							$row2 = mysql_fetch_row($consulta);
							$cierre = $row2[0];
						$motivo = utf8_encode($row['motivo']);
						$departamento = utf8_encode($row['departamento']);
						$sub_motivo = utf8_encode($row['sub_motivo']);
						$libced = utf8_encode($row['libced']);
						$guiatracking = utf8_encode($row['guiatracking']);
						$estatus = utf8_encode($row['estatus']);
						$edit = '<img src="imagenes/edit.png" class="edit cursor" id="'.$id.'" title="Editar Estatus">';
						$data[] = array($link, $apertura, $cierre, $departamento, $motivo, $sub_motivo,$libced,$guiatracking,$estatus, $edit);							
					}//End While
				$contador++;
			}//End if
		}//End Foreach
	}//End if
		
    //Mostramos los resultados
	$results = array("aaData"=>$data);
	echo json_encode($results);
	
mysql_close($conexion);
}else{
	header("location:../index.php?error=ingreso");
}
?>