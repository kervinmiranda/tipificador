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
->setCellValue('B1', 'Fecha')
->setCellValue('C1', 'Usuario')
->setCellValue('D1', 'Departamento')
->setCellValue('E1', 'Motivo')
->setCellValue('F1', 'Sub Motivo')
->setCellValue('G1', 'Código LIB o Cédula')
->setCellValue('H1', 'Usuario Red Social')
->setCellValue('I1', 'Guía o Tracking')
->setCellValue('J1', 'Estatus')
;

//Definimos la consulta
$consulta = mysql_query("SELECT * FROM call_registro");
	if(mysql_num_rows($consulta)){ // if para almacenar el resultado de la consulta
		while($row = mysql_fetch_array($consulta)){
		$id = $row [0];
		$fec = date_create($row[1]);
		$fecha = date_format($fec, 'd/m/Y h:i a');
		$usuario = utf8_encode($row [2]);
		$departamento = utf8_encode($row [3]);
		$motivo = utf8_encode($row [4]);
		$submotivo = utf8_encode($row [5]);
		$libced = utf8_encode($row [6]);
		$usersocial = utf8_encode($row [7]);
		$guiatracking = utf8_encode($row [8]);
		$estatus = utf8_encode($row [10]);
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$cel, $id)
		->setCellValue('B'.$cel, $fecha)
		->setCellValue('C'.$cel, $usuario)
		->setCellValue('D'.$cel,$departamento)
		->setCellValue('E'.$cel,$motivo)	
		->setCellValue('F'.$cel, $submotivo)
		->setCellValue('G'.$cel, $libced)
		->setCellValue('H'.$cel, $usersocial)
		->setCellValueExplicit('I'.$cel, $guiatracking,PHPExcel_Cell_DataType::TYPE_STRING)		
		->setCellValue('J'.$cel, $estatus);
		$cel++;		
		}//End WHILE	
	}else{
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A2',"No se encontraron registros en la busqueda")
		->mergeCells('A2:J2');
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
foreach(range('A','J') as $columnID) { $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);}
$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($styletitle);			
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