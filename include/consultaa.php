<?php
include_once 'conexion.php';
include_once 'fecha.php';
include_once 'variables.php';
if(isset($_SESSION['user'])){
	$sql="SELECT * FROM call_evaluacion_atributo";	
	$ver=mysql_query($sql);		
   		$data = array();
			while($lista=mysql_fetch_array($ver)){
				$id = $lista[0];
				$descripcion = utf8_encode($lista[1]);
				$est = utf8_encode($lista[2]);
				switch ($est){
					case 0: $estatus = '<img src="imagenes/inactivo.png">';
							$block='<img src="imagenes/block2.png" class="camb cursor" title="Desbloquear Atributo" id="'.$id.'">';
					break;
					case 1: $estatus = '<img src="imagenes/activo.png">';
							$block='<img src="imagenes/block.png" class="camb cursor" title="Bloquear Atributo" id="'.$id.'">';
					break;				
				}								
				$comando= '<img src="imagenes/edit.png" class="edit cursor" title="Editar Atributo" id="'.$id.'">'.$block;			
				$data[] = array($id, $descripcion, $estatus, $comando);
			}//End While	
    //Mostramos los resultados
	$results = array("aaData"=>$data);
	echo json_encode($results);
	
mysql_close($conexion);
}else{
	header("location:../index.php?error=ingreso");
}
		
