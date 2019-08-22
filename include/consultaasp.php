<?php
include_once 'conexion.php';
include_once 'fecha.php';
include_once 'variables.php';
if(isset($_SESSION['user'])){
	$sql="SELECT call_evaluacion_aspecto.id, call_evaluacion_atributo.descripcion, call_evaluacion_aspecto.descripcion, call_evaluacion_aspecto.estatus from call_evaluacion_aspecto INNER JOIN call_evaluacion_atributo ON call_evaluacion_aspecto.id_atributo = call_evaluacion_atributo.id";		
	$ver=mysql_query($sql);		
   		$data = array();
			while($lista=mysql_fetch_array($ver)){
				$id = $lista[0];
				$atributo =  utf8_encode($lista[1]);
				$descripcion = utf8_encode($lista[2]);			
				$est = utf8_encode($lista[3]);
				switch ($est){
					case 0: $estatus = '<img src="imagenes/inactivo.png">';
							$block='<img src="imagenes/block2.png" class="camb cursor" title="Desbloquear Error" id="'.$id.'">';
					break;
					case 1: $estatus = '<img src="imagenes/activo.png">';
							$block='<img src="imagenes/block.png" class="camb cursor" title="Bloquear Error" id="'.$id.'">';
					break;				
				}								
				$editar = '<img src="imagenes/edit.png" class="edit cursor" title="Editara Atributo" id="'.$id.'">';				
				$comando= $editar.$block;			
				$data[] = array($id, $descripcion, $atributo, $estatus, $comando);
			}//End While	
    //Mostramos los resultados
	$results = array("aaData"=>$data);
	echo json_encode($results);
	
mysql_close($conexion);
}else{
	header("location:../index.php?error=ingreso");
}
		
