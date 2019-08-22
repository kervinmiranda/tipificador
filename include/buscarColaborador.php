<?php
/*************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/	
session_start();
if(isset($_SESSION['user']) && ($_SESSION['nivel'] < 2)){
require_once '../../../ws/lib/nusoap.php';

$cliente = new nusoap_client("http://network-libertyexpress.com/ws/servicio.php", false);
$cedula = $_POST['cedula'];
$parametros = array('cedula' => $cedula);
$respuesta = $cliente->call("colaborador", $parametros);
echo $respuesta;
}else{
	header("location:../index.php?error=ingreso");
}
?>