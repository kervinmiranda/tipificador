<?php
include_once 'fecha.php';
include_once 'conexion.php';
include_once 'variables.php';
if(isset($_SESSION['user'])){
$tipo = $_POST['tipo'];
	if ($tipo == 'lib'){
		$libced = $_POST['codigo'];
		$sql = "SELECT * FROM call_registro WHERE libced = '$libced' ORDER BY fecha DESC";
	}
	if ($tipo == 'guia'){
		$guia = $_POST['codigo'];
		$sql = "SELECT * FROM call_registro WHERE guiatracking LIKE '%$guia%' ORDER BY fecha DESC";
	}	
	$ver=mysql_query($sql);		
	if(mysql_num_rows($ver)) {
	$json = array();
		while($row=mysql_fetch_array($ver)){		
				$fec = date_create($row['fecha']);
				$json[] = array(
					'fecha' => date_format($fec, 'd/m/Y h:i a'),				
					'usuario' => utf8_encode($row['usuario']),
					'departamento' => utf8_encode($row['departamento']),
					'motivo' => utf8_encode($row['motivo']),
					'sub_motivo' => utf8_encode($row['sub_motivo']),						
					'libced' => utf8_encode($row['libced']),						
					'usersocial' => utf8_encode($row['usersocial']),						
					'guiatracking' => utf8_encode($row['guiatracking']),
					'comentario' => utf8_encode($row['comentario'])			
				);
			}
	$json['success'] = true;
	echo json_encode($json);
	}
mysql_close($conexion);
}else{
	header("location:../index.php?error=ingreso");
}
?>