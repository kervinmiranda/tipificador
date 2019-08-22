<?php
include_once 'conexion.php';
include_once 'fecha.php';
include_once 'variables.php';
if(isset($_SESSION['user'])){
	$sql = "SELECT call_evaluacion_situacion.id as idSituacion, call_evaluacion_atributo.descripcion as descripcionAtributo, call_evaluacion_aspecto.descripcion as descripcionAspecto, call_evaluacion_situacion.grupo as situacionGrupo, call_evaluacion_situacion.descripcion as descripcionSituacion FROM call_evaluacion_situacion INNER JOIN call_evaluacion_aspecto ON call_evaluacion_situacion.id_aspecto = call_evaluacion_aspecto.id INNER JOIN call_evaluacion_atributo ON call_evaluacion_aspecto.id_atributo = call_evaluacion_atributo.id ORDER BY call_evaluacion_atributo.id, descripcionAspecto,  descripcionSituacion ASC";
	
	$ver=mysql_query($sql);		
	$data = array();
	$option = '';
	$cont = 0;
	for($i = 100 ; $i >= 1 ; $i-- ){
		$option.= '<option>'.$i.'</option>';
	}//End for

	while($lista=mysql_fetch_array($ver)){
		$idSituacion = $lista[0];
	$descripcionAtributo =  utf8_encode($lista[1]);	
	$descripcionAspecto =  utf8_encode($lista[2]);
	$situacionGrupo =  utf8_encode($lista[3]);							
	$descripcionSituacion = utf8_encode($lista[4]);
	$check = '<input type="checkbox" value = "'.$idSituacion.'" class = "item" checked>';
	$porc = '<select class="form-control" id = "'.$idSituacion.'">'.$option.'</select>';
	$data[] = array($descripcionAtributo, $descripcionAspecto, $situacionGrupo, $descripcionSituacion, $check, $porc);

	echo ' 	<tr>
				<td>'.$descripcionAtributo.'</td>
				<td>'.$descripcionAspecto.'</td>
				<td>'.$$situacionGrup.'</td>
				<td>'.$descripcionSituacion	.'</td>
				<td>'.$check.'</td>
				<td>'.$porc.'</td>
			</tr>
	';
	$cont = $cont + 1;
}//End While

//Mostramos los resultados
//$results = array("aaData"=>$data);
//echo json_encode($results);

mysql_close($conexion);
}else{
	header("location:../index.php?error=ingreso");
}
		
