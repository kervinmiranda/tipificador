<?php
include_once 'conexion.php';
include_once 'fecha.php';
include_once 'variables.php';
if(isset($_SESSION['user'])){
	$sql = mysql_query("SELECT * FROM call_evaluacion_form WHERE estatus != '0'");			
   		$data = array();
			while($row = mysql_fetch_array($sql)){
				$id = $row[0];
				$nombre =  utf8_encode($row[1]);	
				$gestor =  utf8_encode($row[2]);				
				$est = utf8_encode($row[3]);
				switch ($est){
					case 0: $estatus = '<img src="imagenes/inactivo.png">';
							$block='<img src="imagenes/block2.png" class="camb cursor" title="Desbloquear Error" id="'.$id.'">';
					break;
					case 1: $estatus = '<img src="imagenes/activo.png">';
							$block='<img src="imagenes/block.png" class="camb cursor" title="Bloquear Error" id="'.$id.'">';
					break;				
				}								
				$editar = '<img src="imagenes/edit.png" class="edit cursor" title="Editar Formulario" id="'.$id.'">';		
				$comando= $editar.$block;
				$data[] = array($id, $nombre, $gestor, $estatus, $comando);
			}//End While	
    //Mostramos los resultados
	$results = array("aaData" => $data);
	echo json_encode($results);		
mysql_close($conexion);
}else{
	header("location:../index.php?error=ingreso");
}
		
