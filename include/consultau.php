<?php
require 'pdo/database.php';
include_once 'fecha.php';
include_once 'variables.php';
if(isset($_SESSION['user'])){
	$objdatabase = new Database();
	$sql = $objdatabase->prepare("SELECT * FROM call_usuario");
	$sql->execute(); // se confirma que el query exista
	//Verificamos el resultado
	$count = $sql->rowCount();
	if ($count){
		$result = $sql->fetchAll();
		$data = array();
		foreach ($result as $key => $value){
			$ci = $value['ci'];
			$nombre = utf8_encode($value['nombre']);
			$userid = utf8_encode($value['userid']);
			$cargo = utf8_encode($value['cargo']);
			$departamento =  utf8_encode($value['departamento']);
			$est = utf8_encode($value['estatus']);
			switch ($est){
				case 0: $estatus = '<img src="imagenes/inactivo.png">';
						$block='<img src="imagenes/block2.png" class="camb cursor" title="Desbloquear Usuario" id="'.$ci.'│'.$est.'">';
				break;
				case 1: $estatus = '<img src="imagenes/activo.png">';
						$block='<img src="imagenes/block.png" class="camb cursor" title="Bloquear Usuario" id="'.$ci.'│'.$est.'">';
				break;
			}
			$niv = utf8_encode($value['nivel']);
			switch ($niv){
				case 1: $nivel = "Administrador";
				break;
				case 2: $nivel = "Supervisor";
				break;
				case 3: $nivel = "Usuario";
				break;
			}
			$comando= '<img src="imagenes/clave.png" class="reset cursor" title="Resetear Contraseña" id="'.$ci.'│'.$nombre.'│'.$cargo.'│'.$userid.'│'.$departamento.'">'.
				'<img src="imagenes/edit.png" class="edit cursor" title="Editar Usuario" id="'.$ci.'">'.$block;
			$data[] = array($ci, $nombre, $userid, $cargo, $departamento, $estatus, $nivel,$comando);			
		}	
		//Mostramos los resultados
		$results = array("aaData"=>$data);
		echo json_encode($results);
	}
}else{
	header("location:index.php?alerta=salir");
}
?>