<?php
//Hora Local
setlocale(LC_TIME, 'es_VE'); # Localiza en español es_Venezuela
date_default_timezone_set('America/Caracas');
@session_start();
//Variables:
$doctype = '<!DOCTYPE html PUBLIC "Sistema Gebnet"><html lang="es"><head><title>Gebnet</title><link rel="shortcut icon" href="favicon.ico"><meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" charset="UTF-8">';
$titulo = '<h4><b>Gebnet</b></h4>Tipificador'; //Título del Sistema
$pestana = 'Gebnet';//Titulo de la pestaña
$imagen='<img src="imagenes/libertylogo.png">';// Logo de la cabecera
$color= '#1a2848'; // Color del sistema
$header = '<header class="container-fluid">
				<div class="col-xs-12 col-md-4">'.$imagen.'</div>
				<div class="col-xs-12 col-md-4">'.$titulo.'</div>
				<div class="col-xs-12 col-md-4"><br>'.$time.'</div>
		   </header>
		'; //Contenido de la Cabecera
$footer = '<footer class="row vertical-align">
        		<div class="col-xs-12 text-center">│ Liberty Express C.A - RIF J-31116772-2 │ Departamento de Tecnología │ Departamento de Comercialización │</div>
    		</footer>'; //Contenido del pie de página
//Modulos del Sistema
//modulo 0
$principal = '<li id="menu1"><a href="principal.php">Principal</a></li>';
//Módulo 1
$administracion = '<li id="menu2" class="dropdown">
			   		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Administración<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="pais.php">Paises</a></li>							
							<li><a href="tipificacion.php">Tipificaciones</a></li>
							<li><a href="usuario.php">Usuarios</a></li>						
						</ul>
					</li><!-- .dropdown -->';
//Módulo 2
$registro = '<li id="menu3" class="dropdown">
			   		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"aria-expanded="false">Registros<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="registro.php">Nuevo Registro</a></li>
							<li><a href="lista.php">Lista de Registros</a></li>
						</ul>
					</li> <!-- .dropdown -->';
//Módulo 3
$incidencia = '<li id="menu4" class="dropdown">
			   		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Incidencias<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="incidencia.php">Activas</a></li>
							<li><a href="historial.php">Historial</a></li>
						</ul>
				</li> <!-- .dropdown -->';
//Módulo 4
$evaluacion = '<li id="menu5" class="dropdown">
			   		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Evaluación<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="evaluacion.php">Aplicar</a></li>
							<li><a href="listaEvaluacion.php">Consulta</a></li>
							<li><a href="#">Estadísticas</a></li>
						</ul>
				</li> <!-- .dropdown -->';
//Módulo 5
$indicadores = '<li id="menu6"><a href = "indicadores.php" >Indicadores Gráficos</a></li>';
if(isset($_SESSION['user'])){
	//Datos de la Sesión del usuario
	$cedula = $_SESSION['cedula'];
	$nombre = $_SESSION['user'];
	$userid = $_SESSION['nick'];
	$nivel = $_SESSION['nivel'];
	$modulos = explode(",", $_SESSION['modules']);
	$permitidos = '';
//Ultimo Modulo
$sesion = '<ul class="nav navbar-nav navbar-right">
				<li id = "session" class="dropdown"><a href="#" class="dropdown" data-toggle="dropdown">'.$nombre.'<b class="caret"></b></a>
                	<ul class="dropdown-menu">
                        <li><a href="clave.php">Cambiar Contraseña</a></li>
						<li class="divider"></li>
                        <li><a href="include/salir.php">Salir</a></li>
					</ul>
              	</li>
            </ul>';
//Inclusión de los modulos en la interfaz del usuario según la BD
	foreach ($modulos as $mod) {
		switch ($mod){
			case 1: $permitidos = $permitidos.$administracion;
			break;
			case 2: $permitidos = $permitidos.$registro;
			break;
			case 3: $permitidos = $permitidos.$incidencia;
			break;
			//case 4: $permitidos = $permitidos.$evaluacion;
			//break;
			case 5: $permitidos = $permitidos.$indicadores;
			break;
		}
	}
//Variable menú				
$menu='<nav class="navbar navbar-default">
		<div class="navbar-header">
			<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand">Menú</a>
  		</div>
		<div class="navbar-collapse collapse in" aria-expanded="true">
    		<ul class="nav navbar-nav">'.
				$principal.$permitidos.'
			</ul>'.$sesion.'
		</div>
	</nav>';
}//Enf If Isset SESSION