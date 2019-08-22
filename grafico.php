<?php
//incluimos las librerias
include("include/conexion.php");
include("include/fecha.php");
require_once('../jpgraph/src/jpgraph.php');
require_once('../jpgraph/src/jpgraph_pie.php');
require_once('../jpgraph/src/jpgraph_pie3d.php');
require_once('../jpgraph/src/jpgraph_bar.php');
require_once('../jpgraph/src/jpgraph_line.php');

//Realizamos la conexión y la busqueda
$grafico = $_POST['grafico'];

if ($grafico == "grafico1"){
$fecha1= $_POST['fecha1'];
$fecha2= $_POST['fecha2'];
$desde= cambiarFormatoFecha($fecha1);
$hasta= cambiarFormatoFecha($fecha2);
	$consulta = mysql_query("SELECT COUNT(departamento) as cantidad , departamento FROM call_registro WHERE CAST(fecha AS DATE) BETWEEN '$desde' and '$hasta' GROUP BY departamento ORDER BY cantidad DESC");		
		if($total = mysql_num_rows($consulta)){ // if para almacenar el resultado de la consulta
			while ($row = mysql_fetch_array($consulta)){    			
					$data[] = $row['cantidad'];
					$etiqueta[] = $row['departamento'].": ".$row['cantidad'];		
			}//End While
//Obtenemos la Data
$datos= $data;
$labels=$etiqueta;
$grafico = new Graph(1000, 500, 'auto');
$grafico->SetScale("textlin");
$grafico->title->Set("Estadística por Departamentos desde el ".$fecha1." hasta el ".$fecha2);
$grafico->xaxis->title->Set("Departamentos");
$grafico->xaxis->SetTickLabels($labels);
$grafico->yaxis->title->Set("Cantidad de Registros");
$barplot1 =new BarPlot($datos);
$barplot1->SetWidth(20); // 30 pixeles de ancho para cada barra
$grafico->Add($barplot1);
$barplot1->value->Show();
$grafico->Stroke();	
	}else{
	echo "NO se ha podido crear el gr&aacute;fico, Verifique el rango de fechas";
	}//End if
}//End if Grafico1 

if ($grafico == "grafico2"){
$fecha1= $_POST['fecha1'];
$fecha2= $_POST['fecha2'];
$desde= cambiarFormatoFecha($fecha1);
$hasta= cambiarFormatoFecha($fecha2);
	$consulta = mysql_query("SELECT COUNT(motivo) as cantidad , motivo FROM call_registro WHERE CAST(fecha AS DATE) BETWEEN '$desde' and '$hasta' GROUP BY motivo ORDER BY cantidad DESC");		
		if($total = mysql_num_rows($consulta)){ // if para almacenar el resultado de la consulta
			while ($row = mysql_fetch_array($consulta)){
    			$motivo = $row['motivo'];	
					$data[] = $row['cantidad'];
					$etiqueta[] = $row['motivo'].": ".$row['cantidad'];		
			}//End While

//Obtenemos la Data
$datos= $data;
$labels=$etiqueta;
// Creaamos el grafico 3D Pie 
$grafico = new PieGraph(600,500);
$theme_class= new VividTheme;
$grafico->SetTheme($theme_class);
$grafico->legend->Pos(0.2, 0.9); 			
// Asignamos un Título para el Gráfico
$grafico->title->Set("Estadística Por Motivos desde el ".$fecha1." hasta el ".$fecha2);
// Create
$p1 = new PiePlot3D($datos);
$grafico->Add($p1);
$p1->SetColor('black');
$p1->SetLegends($labels);
$grafico->Stroke();		
	}else{
	echo "NO se ha podido crear el gr&aacute;fico, Verifique el rango de fechas";
	}//End if
}//End if Grafico2

if ($grafico == "grafico3"){
$fecha= $_POST['fecha3'];
 	//echo $fecha."<br>";
	$anio= substr($fecha,0,4);
	$mes= substr($fecha,5,7);
	switch ($mes){
	case "01":
	case "03":
	case "05":
	case "07":
	case "08":
	case "10":
	case "12":
		$dias = 31;
		break;
	case "04":
	case "06":
	case "09":
	case "11":
		$dias = 30;
		break;
	case "02":
		$dias = 28;
		break;
	}
	
	switch ($mes){
	case "01": $month = "Enero"; break;
	case "02": $month = "Febrero"; break;
	case "03": $month = "Marzo"; break;
	case "04": $month = "Abril"; break;
	case "05": $month = "Mayo"; break;
	case "06": $month = "Junio"; break;
	case "07": $month = "Julio"; break;
	case "08": $month = "Agosto"; break;
	case "09": $month = "Septiembre"; break;
	case "10": $month = "Octubre"; break;
	case "11": $month = "Noviembre"; break;
	case "12": $month = "Diciembre"; break;	
	}

$titulos = array();
$valores = array();
$etiquetas = array();
$titulos[] = $sub = "Total Registros";

	for ($i = 1; $i<= $dias; $i++){
		if ($i < 10){
			$dia = "0".$i;
		}else{
		$dia = $i;
		}
		$fechabuscar = $fecha."-".$dia;
		$dia_semana = date("w", strtotime($fechabuscar));//Excluimos los Domingos		
		if ($dia_semana != 0){ 		
			$buscar = mysql_query("SELECT COUNT(id) FROM call_registro WHERE CAST(fecha AS DATE) = '$fechabuscar'");
			$cantidad = mysql_fetch_row($buscar);	
			$valores[] = $cantidad[0];
			$etiquetas[] = $dia;
		}
	}//End for
	
// Setup the graph
$grafico = new Graph(1300,1000,'auto');
$grafico->SetScale("textlin");
$theme_class=new UniversalTheme;

$grafico->SetTheme($theme_class);
$grafico->legend->SetPos(0.5,0.8,'center','top');
$grafico->SetMargin(40,10,20,20);
$grafico->legend->SetColumns(6);
$grafico->img->SetAntiAliasing(false);
$grafico->title->Set($month." de ".$anio);
$grafico->SetBox(false);
$grafico->xgrid->Show();
$grafico->xgrid->SetLineStyle("solid");
$grafico->xaxis->SetTickLabels($etiquetas);
$grafico->xgrid->SetColor('#E3E3E3');

// Creamos la Linea
$p1 = new LinePlot($valores);
$grafico->Add($p1);
$p1->SetLegend($sub);		
$p1->mark->SetType(MARK_FILLEDCIRCLE,'',1.0);
$p1->mark->SetColor('#55bbdd');
$p1->mark->SetFillColor('#55bbdd');
$p1->SetCenter();
$grafico->legend->SetFrameWeight(1);
$grafico->legend->SetColor('#4E4E4E','#00A78A');
$grafico->legend->SetMarkAbsSize(8);












// Output line
$grafico->Stroke();
}//End if Grafico3

if ($grafico == "grafico4"){
$fecha1= $_POST['fecha1'];
$fecha2= $_POST['fecha2'];
$desde= cambiarFormatoFecha($fecha1);
$hasta= cambiarFormatoFecha($fecha2);
	$consulta = mysql_query("SELECT COUNT(usuario) as cantidad , usuario FROM call_registro WHERE CAST(fecha AS DATE) BETWEEN '$desde' and '$hasta' GROUP BY usuario ORDER BY cantidad DESC");			
		if($total = mysql_num_rows($consulta)){ // if para almacenar el resultado de la consulta
			while ($row = mysql_fetch_array($consulta)){
					$data[] =  $row['cantidad'];
					$etiqueta[] = $row['usuario'].": ".$row['cantidad'];		
			}//End While
//Obtenemos la Data
$datos= $data;
$labels=$etiqueta;
$grafico = new Graph(1300, 500, 'auto');
$grafico->SetScale("textlin");
$grafico->title->Set("Estadística por Analistas desde el ".$fecha1." hasta el ".$fecha2);
$grafico->xaxis->SetTickLabels($labels);
$grafico->xaxis->SetLabelAngle(50);
$grafico->yaxis->title->Set("Cantidad de Registros");
$barplot1 =new BarPlot($datos);
$barplot1->SetWidth(20); // 30 pixeles de ancho para cada barra
$grafico->Add($barplot1);
$barplot1->value->Show();
$grafico->Stroke();	
	}else{
	echo "NO se ha podido crear el gr&aacute;fico, Verifique el rango de fechas";
	}//End if
}//End if Grafico1
?>