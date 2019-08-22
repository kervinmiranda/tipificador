<?php
class codigo{
	public function  __construct() {
		$dbhost = 'localhost';
		$dbuser = 'liberty';
		$dbpass = '..libertY21';
		$dbname = 'gebnet';
		mysql_connect($dbhost, $dbuser, $dbpass);
		mysql_select_db($dbname);
	}
public function buscarCodigo($nombreUsuario){
	$datos = array();
	$sql = "SELECT DISTINCT libced FROM call_registro WHERE libced LIKE '%$nombreUsuario%' ORDER BY libced";
	$resultado = mysql_query($sql);
	while ($row = mysql_fetch_array($resultado, MYSQL_ASSOC)){
	$datos[] = array("value" => $row['libced']);
	}
	return $datos;
	}
}
$codigo = new codigo();
echo json_encode($codigo->buscarCodigo($_GET['term']));
?>