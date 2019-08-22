<?php
include_once 'conexion.php';
include_once 'fecha.php';
include_once 'variables.php';
if(isset($_SESSION['user'])){
	$sql="SELECT * FROM call_tipificacion";	
	$ver=mysql_query($sql);		
   		$data = array();
			while($lista=mysql_fetch_array($ver)){
				$id = $lista['id'];
				$motivo = utf8_encode($lista['principal']);
				$sub_motivo = utf8_encode($lista['secundaria']);
				$est = utf8_encode($lista['estatus']);
				switch ($est){
					case 0: $estatus = '<img src="imagenes/inactivo.png">';
							$block='<img src="imagenes/block2.png" class="camb cursor" title="Desbloquear Tipificación" id="'.$id.'│'.$est.'">';
					break;
					case 1: $estatus = '<img src="imagenes/activo.png">';
							$block='<img src="imagenes/block.png" class="camb cursor" title="Bloquear Tipificación" id="'.$id.'│'.$est.'">';
					break;				
				}								
				$comando= '<img src="imagenes/edit.png" class="edit cursor" title="Editar Usuario" id="'.$id.'│'.$motivo.'│'.$sub_motivo.'">'.$block;				
				$data[] = array($id, $motivo, $sub_motivo, $estatus, $comando);
			}//End While	
    //Mostramos los resultados
	$results = array("aaData"=>$data);
	echo json_encode($results);
	
mysql_close($conexion);
}else{
	header("location:../index.php?error=ingreso");
}
		
