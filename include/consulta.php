<?php
include_once 'conexion.php';
include_once 'fecha.php';
include_once 'variables.php';
$fechabuscar = $_POST['fecha'];
if(isset($_SESSION['user'])){
if ($_SESSION['nivel'] < 3){
		$sql="SELECT * FROM call_registro WHERE fecha LIKE '$fechabuscar%'";	
	}else{
		$userid = $_SESSION['nick'];
		$sql="SELECT * FROM call_registro WHERE usuario = '$userid' AND fecha LIKE '$fechabuscar%'";	
	}
	$ver=mysql_query($sql);		
   		$data = array();
			while($lista=mysql_fetch_array($ver)){
				$id = $lista['id'];
				$link = '<a class="link" href="#" id="'.$id.'" data-toggle="modal" data-placement="bottom" data-target="#observacion">'.$id.'</a>';
				$fecha = $lista['fecha'];
				$usuario = utf8_encode($lista['usuario']);
				$departamento =  utf8_encode($lista['departamento']);
				$motivo = utf8_encode($lista['motivo']);
				$sub_motivo = utf8_encode($lista['sub_motivo']);
				$libced = utf8_encode($lista['libced']);				
				$users = utf8_encode($lista['usersocial']);				
					$findme   = '|';
					$pos = strpos($users, $findme);
					if ($pos === false) {
						$usersocial = $users;
					}else{
						if ($users != ''){						
							$user = substr($users,0, $pos);
							$red = substr($users,$pos + 1, strlen($users));
							$usersocial = $user.'@'.$red;
						}else{
							$usersocial = '';
						}
					}			
				$guiatracking = utf8_encode($lista['guiatracking']);					
				$estatus = utf8_encode($lista['estatus']);
				$comentario =  utf8_encode($lista['comentario']);
				$edit = '<img src="imagenes/gestion.png" class="edit cursor" id="'.$id.'" data-toggle="modal" data-placement="bottom" data-target="#editar" title="Editar Registro">';	
				$data[] = array($link, $fecha, $usuario, $departamento, $motivo, $sub_motivo, $libced, $usersocial, $guiatracking, $edit);
			}//End While	
    //Mostramos los resultados
	$results = array("aaData"=>$data);
	echo json_encode($results);
	
mysql_close($conexion);
}else{
	header("location:../index.php?error=ingreso");
}
?>