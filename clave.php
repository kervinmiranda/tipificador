<?php
/*************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
include_once 'include/conexion.php';
include_once 'include/fecha.php';
include_once 'include/variables.php';
if(isset($_SESSION['user'])){
?>
<?php echo $doctype?>
<!-- Achivos CSS -->
<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="../DataTables/css/dataTables.bootstrap.css">
<link rel="stylesheet" href="../DataTables/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="../bootstrap/css/bootstrap-submenu.css">
<link rel="stylesheet" href="css/call.css">
<!-- Archivos JavaScript -->	
<script src="../js/jquery.js"></script>
<script src="../bootstrap/js/bootstrap.js"></script>
<script src="../DataTables/js/jquery.dataTables.js"></script>
<script src="../DataTables/js/dataTables.bootstrap.js"></script>
<script src="../DataTables/js/dataTables.responsive.min.js"></script>
<script src="../bootstrap/js/bootbox.min.js"></script>
<script src="../bootstrap/js/bootstrap-submenu.js"></script>
<script src="../js/jquery.numeric.js"></script>
<script src="js/libreriajs.js"></script>
<script>
$(document).ready(function(){
//Activar Menú
	$("#session").attr('class','active');

// validar Guardar Contraseña Nueva
	$('#enviar').click(function() {
		if ($("#claveActual").val() == ''){
			$('#claveActual').parent().addClass('has-error');
			$('#claveActual').attr('placeholder','Campo Obligatorio');			
			return false;				
		}else{
			$('#claveActual').parent().removeClass('has-error').addClass('has-success');
		};
		if ($("#claveNueva").val() == ''){
			$('#claveNueva').parent().addClass('has-error');
			$('#claveNueva').attr('placeholder','Campo Obligatorio');			
			return false;				
		}else{
			$('#claveNueva').parent().removeClass('has-error').addClass('has-success');
		};
		if ($("#claveNueva2").val() == ''){
			$('#claveNueva2').parent().addClass('has-error');
			$('#claveNueva2').attr('placeholder','Campo Obligatorio');			
			return false;				
		}else{
			$('#claveNueva2').parent().removeClass('has-error').addClass('has-success');
		};
		if($('#claveNueva').val() != $('#claveNueva2').val()){
			$('#claveNueva2').parent().addClass('has-error');
			$('#claveNueva2').val('');
			$('#claveNueva2').attr('placeholder','Contraseña no coincide');			
			return false;				
		}else{
			$('#claveNueva2').parent().removeClass('has-error').addClass('has-success');
		};	
		bootbox.confirm('Seguro que desea cambiar la contraseña', function(result){
			if (result == true){
				var actual = $('#claveActual').val();
				var clave1 = $('#claveNueva').val();
				var clave2 = $('#claveNueva2').val();
				var accion = "cambiar";
				var cedula = <?php echo $_SESSION['cedula']?>;	
				
				$.post('include/guardar_usuario.php', {accion:accion, cedula:cedula, actual:actual,clave1:clave1}, function(data){
					if (data  == '0'){
						$('#error').html('<strong>¡Error!</strong> Error al actualizar, Intente mas tarde.').fadeIn(1000).fadeOut(5000);					
					}else if (data == '1'){
						$('#mensaje').html('<strong>¡Exito!</strong> Contraseña Cambiada Correctamente').fadeIn(1000).fadeOut(5000);
						$('#claveActual').val('').parent().removeClass('has-error has-success');
						$('#claveNueva').val('').parent().removeClass('has-error has-success');
						$('#claveNueva2').val('').parent().removeClass('has-error has-success');
					}else if (data == 'error'){
						$('#claveActual').val('');
						$('#alerta').html('<strong>¡Alerta!</strong> Contraseña Actual Incorrecta').fadeIn(1000).fadeOut(5000);						
					}//End if
				});//End post	
			}//End if			 
		});//End Function
	});//End Function
});
</script>
</head>
<body>
	<?php echo $header?>
    <div class="container-fluid contenido">
	
	<?php echo $menu?>
	<div class="text-center">
    	<h4>Cambiar Contraseña</h4>
    </div>
    <div align="center" class="alert alert-success oculto" id="mensaje">
    	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    </div>
    <div align="center" class="alert alert-danger oculto" id="error">
    	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>        
    </div>
    <div align="center" class="alert alert-warning oculto" id="alerta">
    	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>        
    </div>   
	<div class="row" id="formulario">
    <div class="col-xs-1 col-md-3 col-lg-4"></div>    
    <div class="col-xs-12 col-md-6 col-lg-4">   	
    <div class="panel panel-primary text-center">
    	<input type="hidden" name="accion" value="cambiar">
    	<div class="panel-heading">
      		<h3 class="panel-title text-center">Formulario de Cambio de Contraseña</h3>
    	</div>
    	<div class="panel-body">
        	<div class="form-group col-xs-12">
                <label>Contraseña Actual:</label>
                <input name="claveActual" type="password" id="claveActual" size="40" maxlength="40" class="uncopypaste form-control text-center">
            </div>
            <div class="form-group col-xs-12">
                <label>Contraseña Nueva:</label>
                <input name="claveNueva" type="password" id="claveNueva" size="40" maxlength="40" class="uncopypaste form-control text-center">
            </div> 
			<div class="form-group col-xs-12">
                <label>Confirmar Contraseña:</label>
                <input name="claveNueva2" type="password" id="claveNueva2" size="40" maxlength="40" class="uncopypaste form-control text-center">
            </div> 
             <div class="form-group col-xs-12 text-center">
                 <input name="enviar" type="image" id="enviar" src="imagenes/save.png" rtitle="Resetear Contraseña de Usuario">
            </div>           	         
		</div>
  	</div><!--End col -->
    <div class="col-xs-1 col-md-3 col-lg-4"></div>
    </div><!--End row -->
    </div><!--End Formulario -->
        
    </div><!-- End Container -->
    </div><!--End Contenido -->
    <?php echo $footer?>
</body>
</html>
<?php
mysql_close($conexion);
}else{
	header("location:../index.php?error=ingreso");
}
?>