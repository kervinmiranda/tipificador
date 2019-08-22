<?php
include_once 'fecha.php';
include_once 'conexion.php';		
include_once 'variables.php';
if(isset($_SESSION['user'])){	
	$id = $_POST['id'];
		$sql = "SELECT * FROM call_gestion WHERE id = '$id' ORDER BY fecha DESC";
			$ver=mysql_query($sql);		
			$json = array();
				while($row=mysql_fetch_array($ver)){		
						$fec = date_create($row['fecha']);
						$json[] = array(
							'fecha' => date_format($fec, 'd/m/Y h:i a'),				
							'gestor' => utf8_encode($row['gestor']),
							'estatus' => utf8_encode($row['estatus']),
							'comentario' => utf8_encode($row['comentario'])			
						);
					}
		$json['success'] = true;
		echo json_encode($json);

mysql_close($conexion);
}else{
	header("location:../index.php?error=ingreso");
}
?>