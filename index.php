<html lang="es"><head>
    <title>Gebnet</title>
    <link rel="shortcut icon" href="favicon.ico">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" charset="UTF-8"><!-- Archivos CSS -->

    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <!-- Archivos JavaScript -->
    <script src="../js/jquery.js"></script>
    <script src="../bootstrap/js/bootstrap.js"></script>
    <script src="../bootstrap/js/bootstrap-submenu.js"></script>
    <script src="js/libreriajs.js"></script>
    <script>
    $(document).ready(function(){
    	$('#boton').click(function(){
    		if ($("#usuario").val() == ''){
    			$('#usuario').parent().addClass('has-error');
    			$('#usuario').attr('placeholder','Escribe tu usuario');
    			return false;
    		}else{
    			$('#usuario').parent().removeClass('has-error').addClass('has-success');
    		};		

    		if ($("#clave").val() == ''){
    			$('#clave').parent().addClass('has-error');
    			$('#clave').attr('placeholder','Escribe tu Contraseña');
    			return false;
    		}else{
    			$('#clave').parent().removeClass('has-error').addClass('has-success');
    		};
    		if ($("#departamento option:selected").index() == 0){
    			$('#departamento').parent().addClass('has-error');
    			return false;
    		}else{
    			$('#departamento').parent().removeClass('has-error').addClass('has-success');
    		}
    		$('#formulario').submit();
    	});
    });
    </script>
    </head>
    <body>
        <div class="container">
            <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-4 col-md-offset-4 col-sm-8 col-sm-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title text-center">Sistema Gebnet</div>
                        <div style="float:right; font-size: 80%; position: relative; top:-10px"></div>
                    </div>
                    <div class="list-group-item-info">
                        <div class="panel-title text-center">Tipificador</div>
                        <div style="float:right; font-size: 80%; position: relative; top:-10px"></div>
                    </div>
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                        <form id="formulario" class="form-group" action="include/validar.php" method="POST">
                            <div style="margin-bottom: 25px" class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                <input id="usuario" type="text" class="form-control" name="usuario" value="" placeholder="Usuario">
                        	</div>
                            <div style="margin-bottom: 25px" class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                <input id="clave" type="password" class="form-control" name="clave" placeholder="Contraseña">
                            </div>
                            <div style="margin-bottom: 25px" class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-filter"></i></span>
                            <select name="departamento" id="departamento" class="form-control">
                                <option>Seleccionar...</option>
                                <option>CALL INTERNACIONAL</option>
                                <option>EMAIL ATC</option>
                                <option>EMAIL IDENTIFICACI&Oacute;N</option>
                                <option>INBOUND</option>
                                <option>INCIDENCIAS</option>
                                <option>REDES SOCIALES</option>
                            </select>
                            </div>
                            <div style="margin-top:10px" class="form-group text-center"><!-- Button -->
                            <div class="col-sm-12 controls">
                                <a id="boton" href="#" class="btn btn-default">Entrar</a>
                            </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    	<?php
    	   if (isset($_GET['error'])){
    	?>
    	   <div class="container-fluid">
            <div align="center" class="alert alert-danger" id="error">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>¡Error!</strong> Verifique sus datos e intente de nuevo
            </div>
       	</div>
    	<?php
    	   }
    	?>
    </body>
</html>