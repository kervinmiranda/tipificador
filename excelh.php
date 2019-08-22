<?php
//Incluimos librerias
include_once 'include/conexion.php';
include_once 'include/fecha.php';
include_once 'include/variables.php';
require_once 'include/PHPExcel.php';
//PROCESO PARA EXPORTAR A EXCEL

//Recibimos los datos del POST
	$tipo = $_POST['tipo'];
	$cel = 2; //número de inicio de la fila
// Crea un nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();
 
// Establecer propiedades
$objPHPExcel->getProperties()
->setCreator("Liberty Express C.A")
->setLastModifiedBy("Liberty Express C.A")
->setTitle("Historial Incidencias")
->setSubject("Documento Excel")
->setDescription("Historial Incidencias")
->setKeywords("Excel Office 2010 openxml php")
->setCategory("Excel");
 
// Agregar Informacion de Encabezado
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'Id')
->setCellValue('B1', 'Apertura')
->setCellValue('C1', 'Cierre')
->setCellValue('D1', 'Usuario')
->setCellValue('E1', 'Departamento')
->setCellValue('F1', 'Motivo')
->setCellValue('G1', 'Sub Motivo')
->setCellValue('H1', 'Código LIB o Cédula')
->setCellValue('I1', 'Usuario Red Social')
->setCellValue('J1', 'Guía o Tracking')
->setCellValue('K1', 'Estatus')
->setCellValue('L1', 'Comentario')
;

//Definimos la consulta
switch($tipo){
	case 'apertura': 	
		$fecha1 = cambiarFormatoFecha($_POST['fecha1']);	
		$fecha2 = cambiarFormatoFecha($_POST['fecha2']);
		$buscar = mysql_query("SELECT DISTINCT call_incidencia.id FROM call_registro INNER JOIN call_incidencia ON call_registro.id = call_incidencia.id WHERE call_incidencia.estatus = 'Cerrada' AND CAST(call_registro.fecha AS DATE) BETWEEN '$fecha1' and '$fecha2'");
	break;	
	
	case 'cierre': 
		$fecha1 = cambiarFormatoFecha($_POST['fecha1']);
		$fecha2 = cambiarFormatoFecha($_POST['fecha2']);
		$buscar = mysql_query("SELECT DISTINCT call_incidencia.id FROM call_incidencia INNER JOIN call_gestion ON call_incidencia.id = call_gestion.id WHERE call_incidencia.estatus = 'Cerrada' AND CAST(call_gestion.fecha AS DATE) BETWEEN '$fecha1' and '$fecha2'");		
	break;

}
	
if ($tipo != 'seleccion'){
//Armamos array con la busqueda de las incidencias
	while ($lista = mysql_fetch_array($buscar)){
		$num = $lista['id'];
		$consulta = mysql_query("SELECT id, fecha, departamento, motivo, sub_motivo, libced, usersocial, guiatracking, comentario FROM call_registro WHERE id = '$num'");
		while($row = mysql_fetch_array($consulta)){
			$id = $row [0];
			$fec = date_create($row[1]);
			$apertura = date_format($fec, 'd/m/Y h:i a');
			$sql = mysql_query("SELECT fecha, gestor, estatus FROM call_gestion WHERE estatus = 'Cerrada' AND id = '$id' limit 1");
				$row2 = mysql_fetch_row($sql);
				$cie = date_create($row2[0]);
				$cierre = date_format($cie, 'd/m/Y h:i a');
				$gestor = utf8_encode($row2[1]);	
				$estatus = utf8_encode($row2[2]);			
			$departamento = utf8_encode($row [2]);
			$motivo = utf8_encode($row [3]);
			$submotivo = utf8_encode($row [4]);
			$libced = utf8_encode($row [5]);
			$usersocial = utf8_encode($row [6]);
			$guiatracking = utf8_encode($row [7]);
			$comentario = utf8_encode($row [8]);			
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$cel, $id)
			->setCellValue('B'.$cel, $apertura)
			->setCellValue('C'.$cel, $cierre)
			->setCellValue('D'.$cel, $gestor)
			->setCellValue('E'.$cel,$departamento)
			->setCellValue('F'.$cel,$motivo)	
			->setCellValue('G'.$cel, $submotivo)
			->setCellValue('H'.$cel, $libced)
			->setCellValue('I'.$cel, $usersocial)		
			->setCellValueExplicit('J'.$cel, $guiatracking,PHPExcel_Cell_DataType::TYPE_STRING)
			->setCellValue('K'.$cel, $estatus)
			->setCellValue('L'.$cel, $comentario);
			$cel++;		
		}//End While	
	}//End While
}else{
//Obtenemos los id de los seleccionados
$seleccionados = $_POST['seleccionados'];
	$incidencias = explode(",", $seleccionados);
//Recorremos la lista y consultamos la BD
	foreach ($incidencias as &$valor) {
		$num = $valor;
		$consulta = mysql_query("SELECT id, fecha, departamento, motivo, sub_motivo, libced, usersocial, guiatracking, comentario FROM call_registro WHERE id = '$num'");
		while($row = mysql_fetch_array($consulta)){
			$id = $row [0];
			$fec = date_create($row[1]);
			$apertura = date_format($fec, 'd/m/Y h:i a');
			$sql = mysql_query("SELECT fecha, gestor, estatus FROM call_gestion WHERE estatus = 'Cerrada' AND id = '$id' limit 1");
				$row2 = mysql_fetch_row($sql);
				$cie = date_create($row2[0]);
				$cierre = date_format($cie, 'd/m/Y h:i a');
				$gestor = utf8_encode($row2[1]);			
				$estatus = utf8_encode($row2[2]);	
			$departamento = utf8_encode($row [2]);
			$motivo = utf8_encode($row [3]);
			$submotivo = utf8_encode($row [4]);
			$libced = utf8_encode($row [5]);
			$usersocial = utf8_encode($row [6]);
			$guiatracking = utf8_encode($row [7]);
			$comentario = utf8_encode($row [8]);			
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$cel, $id)
			->setCellValue('B'.$cel, $apertura)
			->setCellValue('C'.$cel, $cierre)
			->setCellValue('D'.$cel, $gestor)
			->setCellValue('E'.$cel,$departamento)
			->setCellValue('F'.$cel,$motivo)	
			->setCellValue('G'.$cel, $submotivo)
			->setCellValue('H'.$cel, $libced)
			->setCellValue('I'.$cel, $usersocial)		
			->setCellValueExplicit('J'.$cel, $guiatracking,PHPExcel_Cell_DataType::TYPE_STRING)
			->setCellValue('K'.$cel, $estatus)
			->setCellValue('L'.$cel, $comentario);
			$cel++;		
		}//End While	
	}//End While
}

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
$objPHPExcel->getActiveSheet()->setTitle('HistorialIncidencias');
 
// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
$objPHPExcel->setActiveSheetIndex(0);
 
// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Historialcidencias.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>