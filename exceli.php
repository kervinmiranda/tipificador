<?php
//Incluimos librerias
include_once 'include/conexion.php';
include_once 'include/fecha.php';
include_once 'include/variables.php';
require_once 'include/PHPExcel.php';
//PROCESO PARA EXPORTAR A EXCEL
 //Recibimos los datos del POST
	$cel = 2; //número de inicio de la fila
// Crea un nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();
 
// Establecer propiedades
$objPHPExcel->getProperties()
->setCreator("Liberty Express C.A")
->setLastModifiedBy("Liberty Express C.A")
->setTitle("Reporte Incidencias")
->setSubject("Documento Excel")
->setDescription("Reporte Incidencias")
->setKeywords("Excel Office 2010 openxml php")
->setCategory("Excel");
 
// Agregar Informacion de Encabezado
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'Id')
->setCellValue('B1', 'Fecha')
->setCellValue('C1', 'Hora')
->setCellValue('D1', 'Usuario')
->setCellValue('E1', 'Departamento')
->setCellValue('F1', 'Motivo')
->setCellValue('G1', 'Sub Motivo')
->setCellValue('H1', 'Código LIB o Cédula')
->setCellValue('I1', 'Usuario Red Social')
->setCellValue('J1', 'Guía o Tracking')
->setCellValue('K1', 'Comentario')
->setCellValue('L1', 'Estatus')
;
//Definimos la consulta
$consulta = mysql_query("SELECT call_incidencia.id, fecha, usuario, departamento, motivo, sub_motivo, libced, usersocial, guiatracking, comentario, call_incidencia.estatus FROM call_incidencia INNER JOIN call_registro ON call_incidencia.id = call_registro.id WHERE call_incidencia.estatus <> 'Cerrada'");

	if(mysql_num_rows($consulta)){ // if para almacenar el resultado de la consulta
		while($row = mysql_fetch_array($consulta)){
		$id = $row [0];
		$fec = date_create($row[1]);
		$fecha = date_format($fec, 'd/m/Y');
		$hora = date_format($fec, 'h:i a');
		$usuario = utf8_encode($row [2]);
		$departamento = utf8_encode($row [3]);
		$motivo = utf8_encode($row [4]);
		$submotivo = utf8_encode($row [5]);
		$libced = utf8_encode($row [6]);
		$usersocial = utf8_encode($row [7]);
		$guiatracking = utf8_encode($row [8]);
		$comentario = utf8_encode($row [9]);
		$estatus = utf8_encode($row [10]);
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$cel, $id)
		->setCellValue('B'.$cel, $fecha)
		->setCellValue('C'.$cel, $hora)
		->setCellValue('D'.$cel, $usuario)
		->setCellValue('E'.$cel,$departamento)
		->setCellValue('F'.$cel,$motivo)	
		->setCellValue('G'.$cel, $submotivo)
		->setCellValue('H'.$cel, $libced)
		->setCellValue('I'.$cel, $usersocial)
		->setCellValueExplicit('J'.$cel, $guiatracking,PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValue('K'.$cel, $comentario)
		->setCellValue('L'.$cel, $estatus);
		$cel++;		
		}//End WHILE	
	}else{
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A2',"No se encontraron registros en la busqueda")
		->mergeCells('A2:L2');
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
foreach(range('A','L') as $columnID) { $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);}
$objPHPExcel->getActiveSheet()->getStyle('A1:L1')->applyFromArray($styletitle);			

// Renombrar Hoja
$objPHPExcel->getActiveSheet()->setTitle('ReporteIncidencias');
 
// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
$objPHPExcel->setActiveSheetIndex(0);
 
// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ReporteIncidencias.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>