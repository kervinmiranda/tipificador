<?php
class guia{
	public function  __construct() {
		$dbhost = 'localhost';
		$dbuser = 'liberty';
		$dbpass = '..libertY21';
		$dbname = 'gebnet';
		mysql_connect($dbhost, $dbuser, $dbpass);
		mysql_select_db($dbname);
	}
public function buscarGuia($guiaTracking){
	$datos = array();
	$sql = "SELECT DISTINCT guiatracking FROM call_registro WHERE guiatracking LIKE '%$guiaTracking%' ORDER BY guiatracking";
	$resultado = mysql_query($sql);
	while ($row = mysql_fetch_array($resultado, MYSQL_ASSOC)){
	$datos[] = array("value" => $row['guiatracking']);
	}
	return $datos;
	}
}

$guia = new guia();
echo json_encode($guia->buscarGuia($_GET['term']));
?>