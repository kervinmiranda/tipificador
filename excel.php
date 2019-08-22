<?php
//Incluimos librerias
include_once 'include/conexion.php';
include_once 'include/fecha.php';
include_once 'include/variables.php';
require_once 'include/PHPExcel.php';
//PROCESO PARA EXPORTAR A EXCEL
 //Recibimos los datos del POST
	$mot = utf8_decode($_POST['motivo']);
	$sub = utf8_decode($_POST['submotivo']);
	$dep = utf8_decode($_POST['departamento']);
	$userid = $_POST['usuario'];	
	$fecha1 = cambiarFormatoFecha($_POST['fecha1']);	
	$fecha2 = cambiarFormatoFecha($_POST['fecha2']);	
	$cel = 2; //número de inicio de la fila
// Crea un nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();
 
// Establecer propiedades
$objPHPExcel->getProperties()
->setCreator("Liberty Express C.A")
->setLastModifiedBy("Liberty Express C.A")
->setTitle("Reporte Tipificador")
->setSubject("Documento Excel")
->setDescription("Reporte Tipificador")
->setKeywords("Excel Office 2010 openxml php")
->setCategory("Excel");
 
// Agregar Informacion de Encabezado
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'Id')
->setCellValue('B1', 'País')
->setCellValue('C1', 'Fecha')
->setCellValue('D1', 'Hora')
->setCellValue('E1', 'Usuario')
->setCellValue('F1', 'Departamento')
->setCellValue('G1', 'Motivo')
->setCellValue('H1', 'Sub Motivo')
->setCellValue('I1', 'Código LIB o Cédula')
->setCellValue('J1', 'Usuario Red Social')
->setCellValue('K1', 'Red Social')
->setCellValue('L1', 'Guía o Tracking')
->setCellValue('M1', 'Comentario')
->setCellValue('N1', 'Estatus')
;

//Definimos las consultas según las selecciones
if (($mot <> "TODOS")&&($sub <> "TODOS")&&($dep <> "TODOS")&&($userid <> "TODOS")){
$consulta = mysql_query("SELECT * FROM call_registro WHERE motivo = '$mot' AND sub_motivo = '$sub' AND departamento = '$dep' AND usuario = '$userid' AND CAST(fecha AS DATE) BETWEEN '$fecha1' and '$fecha2'");
}//0000
if (($mot <> "TODOS")&&($sub <> "TODOS")&&($dep <> "TODOS")&&($userid == "TODOS")){
$consulta = mysql_query("SELECT * FROM call_registro WHERE motivo = '$mot' AND sub_motivo = '$sub' AND departamento = '$dep' AND CAST(fecha AS DATE) BETWEEN '$fecha1' and '$fecha2'");
}//0001
if (($mot <> "TODOS")&&($sub <> "TODOS")&&($dep == "TODOS")&&($userid <> "TODOS")){
$consulta = mysql_query("SELECT * FROM call_registro WHERE motivo = '$mot' AND sub_motivo = '$sub' AND usuario = '$userid' AND CAST(fecha AS DATE) BETWEEN '$fecha1' and '$fecha2'");
}//0010
if (($mot <> "TODOS")&&($sub <> "TODOS")&&($dep == "TODOS")&&($userid == "TODOS")){
$consulta = mysql_query("SELECT * FROM call_registro WHERE motivo = '$mot' AND sub_motivo = '$sub' AND CAST(fecha AS DATE) BETWEEN '$fecha1' and '$fecha2'");
}//0011
if (($mot <> "TODOS")&&($sub == "TODOS")&&($dep <> "TODOS")&&($userid <> "TODOS")){
$consulta = mysql_query("SELECT * FROM call_registro WHERE motivo = '$mot' AND departamento = '$dep' AND usuario = '$userid' AND CAST(fecha AS DATE) BETWEEN '$fecha1' and '$fecha2'");
}//0100
if (($mot <> "TODOS")&&($sub == "TODOS")&&($dep <> "TODOS")&&($userid == "TODOS")){
$consulta = mysql_query("SELECT * FROM call_registro WHERE motivo = '$mot' AND departamento = '$dep' AND CAST(fecha AS DATE) BETWEEN '$fecha1' and '$fecha2'");
}//0101
if (($mot <> "TODOS")&&($sub == "TODOS")&&($dep == "TODOS")&&($userid <> "TODOS")){
$consulta = mysql_query("SELECT * FROM call_registro WHERE motivo = '$mot' AND usuario = '$userid' AND CAST(fecha AS DATE) BETWEEN '$fecha1' and '$fecha2'");
}//0110
if (($mot <> "TODOS")&&($sub == "TODOS")&&($dep == "TODOS")&&($userid == "TODOS")){
$consulta = mysql_query("SELECT * FROM call_registro WHERE motivo = '$mot' AND CAST(fecha AS DATE) BETWEEN '$fecha1' and '$fecha2'");
}//0111
if (($mot == "TODOS")&&($sub <> "TODOS")&&($dep <> "TODOS")&&($userid <> "TODOS")){
$consulta = mysql_query("SELECT * FROM call_registro WHERE sub_motivo = '$sub' AND departamento = '$dep' AND usuario = '$userid' AND CAST(fecha AS DATE) BETWEEN '$fecha1' and '$fecha2'");
}//1000
if (($mot == "TODOS")&&($sub <> "TODOS")&&($dep <> "TODOS")&&($userid == "TODOS")){
$consulta = mysql_query("SELECT * FROM call_registro WHERE sub_motivo = '$sub' AND departamento = '$dep' AND CAST(fecha AS DATE) BETWEEN '$fecha1' and '$fecha2'");
}//1001
if (($mot == "TODOS")&&($sub <> "TODOS")&&($dep == "TODOS")&&($userid <> "TODOS")){
$consulta = mysql_query("SELECT * FROM call_registro WHERE sub_motivo = '$sub' AND usuario = '$userid' AND CAST(fecha AS DATE) BETWEEN '$fecha1' and '$fecha2'");
}//1010
if (($mot == "TODOS")&&($sub <> "TODOS")&&($dep == "TODOS")&&($userid == "TODOS")){
$consulta = mysql_query("SELECT * FROM call_registro WHERE sub_motivo = '$sub' AND CAST(fecha AS DATE) BETWEEN '$fecha1' and '$fecha2'");
}//1011
if (($mot == "TODOS")&&($sub == "TODOS")&&($dep <> "TODOS")&&($userid <> "TODOS")){
$consulta = mysql_query("SELECT * FROM call_registro WHERE departamento = '$dep' AND usuario = '$userid' AND CAST(fecha AS DATE) BETWEEN '$fecha1' and '$fecha2'");
}//1100
if (($mot == "TODOS")&&($sub == "TODOS")&&($dep <> "TODOS")&&($userid == "TODOS")){
$consulta = mysql_query("SELECT * FROM call_registro WHERE departamento = '$dep' AND CAST(fecha AS DATE) BETWEEN '$fecha1' and '$fecha2'");
}//1101
if (($mot == "TODOS")&&($sub == "TODOS")&&($dep == "TODOS")&&($userid <> "TODOS")){
$consulta = mysql_query("SELECT * FROM call_registro WHERE usuario = '$userid' AND CAST(fecha AS DATE) BETWEEN '$fecha1' and '$fecha2'");
}//1110
if (($mot == "TODOS")&&($sub == "TODOS")&&($dep == "TODOS")&&($userid == "TODOS")){
$consulta = mysql_query("SELECT * FROM call_registro WHERE CAST(fecha AS DATE) BETWEEN '$fecha1' and '$fecha2'");
}//1111

	if(mysql_num_rows($consulta)){ // if para almacenar el resultado de la consulta
		while($row = mysql_fetch_array($consulta)){
		$id = $row [0];
		$pais = utf8_encode($row [1]);
		$fec = date_create($row[2]);
		$fecha = date_format($fec, 'd/m/Y');
		$hora = date_format($fec, 'h:i a');
		$usuario = utf8_encode($row [3]);
		$departamento = utf8_encode($row [4]);
		$motivo = utf8_encode($row [5]);
		$submotivo = utf8_encode($row [6]);
		$libced = utf8_encode($row [7]);
		$social = utf8_encode($row [8]);
			if ($social == ''){
				$usersocial = '';
				$red = '';
			}else{
				$findme   = '|';
				$pos = strpos($social, $findme);
				if ($pos === false) {
						$usersocial = $social;
						$red = '';
				}else{
						$usersocial = substr($social, 0 , $pos);
						$red = substr($social, $pos + 1, strlen($social));						
				}										
			}		
		$guiatracking = utf8_encode($row [9]);
		$comentario = utf8_encode($row [10]);
		$estatus = utf8_encode($row [11]);
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$cel, $id)
		->setCellValue('B'.$cel, $pais)
		->setCellValue('C'.$cel, $fecha)
		->setCellValue('D'.$cel, $hora)		
		->setCellValue('E'.$cel, $usuario)
		->setCellValue('F'.$cel,$departamento)
		->setCellValue('G'.$cel,$motivo)	
		->setCellValue('H'.$cel, $submotivo)
		->setCellValue('I'.$cel, $libced)
		->setCellValue('J'.$cel, $usersocial)
		->setCellValue('K'.$cel, $red)
		->setCellValueExplicit('L'.$cel, $guiatracking,PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValue('M'.$cel, $comentario)	
		->setCellValue('N'.$cel, $estatus);
		$cel++;		
		}//End WHILE	
	}else{
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A2',"No se encontraron registros en la busqueda")
		->mergeCells('A2:N2');
	}//End IF
//Formato de los titulos
$styletitle = array(
    'font' => array(
        'bold' => true,
		'color' => array('rgb' => '000000'),
		'size'  => 12,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
            'argb' => 'C0C0C0',
        ),
    ),
);
foreach(range('A','N') as $columnID) { $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);}
$objPHPExcel->getActiveSheet()->getStyle('A1:N1')->applyFromArray($styletitle);			
// Renombrar Hoja
$objPHPExcel->getActiveSheet()->setTitle('ReporteTipificador');
 
// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
$objPHPExcel->setActiveSheetIndex(0);
 
// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ReporteTipificador.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>