<?php
/*************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
include_once 'include/pdo/modulos.php';
include_once 'include/fecha.php';
include_once 'include/variables.php';
if(isset($_SESSION['user']) && ($_SESSION['nivel'] < 2)){
	$modules = getModules();
?>
<?php echo $doctype?>

<!-- Achivos CSS -->
<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="../DataTables/css/dataTables.bootstrap.css">
<link rel="stylesheet" href="../DataTables/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="../bootstrap/css/bootstrap-submenu.css">
<link rel="stylesheet" href="../bootstrap/css/bootstrap-multiselect.css">
<link rel="stylesheet" href="../css/jquery-ui.css">
<link rel="stylesheet" href="css/call.css">

<!-- Archivos JavaScript -->
<script src="../js/jquery.js"></script>
<script src="../js/jquery-ui.js"></script>
<script src="../bootstrap/js/bootstrap.js"></script>
<script src="../DataTables/js/jquery.dataTables.js"></script>
<script src="../DataTables/js/dataTables.bootstrap.js"></script>
<script src="../DataTables/js/dataTables.responsive.min.js"></script>
<script src="../bootstrap/js/bootstrap-submenu.js"></script>
<script src="../bootstrap/js/bootbox.min.js"></script>
<script src="../bootstrap/js/bootstrap-multiselect.js"></script>
<script src="../js/jquery.numeric.js"></script>
<script src="js/libreriajs.js"></script>
<script>
	$(document).ready(function(){
	//Activar Menú
		$("#menu2").attr('class','active');

	//Activar Multiselect Boostrap
	$('#modulos, #modulos2').multiselect({
		 includeSelectAllOption: true
	});

	//Mostrar Formulario de nuevo Usuario
		$("#boton").click(function() {
			$('body input[type=text]').val('').attr('placeholder','').parent().removeClass('has-error has-success');
			$('body input[type=password]').val('').attr('placeholder','').parent().removeClass('has-error has-success');
			$('#agencia option:first').prop("selected", "selected");
			$('#tipousuario option:first').prop("selected", "selected");
			$('#agencia2').parent().removeClass('has-error has-success');
			$('#tipousuario').parent().removeClass('has-error has-success');
			$('body textarea').val('').attr('placeholder','').parent().removeClass('has-error has-success');
			$('option', $('#modulos')).each(function(element) {
				$(this).removeAttr('selected').prop('selected', false);
	  		});
	  		$("#modulos").multiselect('refresh');
			$('#nuevo').modal('toggle');
		});		

	//Validar y Agregar Usuario Nuevo
		$("#save").click(function(){
			if ($("#cedula").val() == ''){
				$('#cedula').parent().addClass('has-error');
				$('#cedula').attr('placeholder','Campo Obligatorio');
				return false;
			}else{
				$('#cedula').parent().removeClass('has-error').addClass('has-success');
			}
			if ($("#nombre").val() == ''){
				$('#nombre').parent().addClass('has-error');
				$('#nombre').attr('placeholder','Campo Obligatorio');
				return false;
			}else{
				$('#nombre').parent().removeClass('has-error').addClass('has-success');
			}
			if ($("#userid").val() == ''){
				$('#userid').parent().addClass('has-error');
				$('#userid').attr('placeholder','Campo Obligatorio');
				return false;
			}else{
				$('#userid').parent().removeClass('has-error').addClass('has-success');
			}
			if ($("#cargo").val() == ''){
				$('#cargo').parent().addClass('has-error');
				$('#cargo').attr('placeholder','Campo Obligatorio');	
				return false;
			}else{
				$('#cargo').parent().removeClass('has-error').addClass('has-success');
			}
			if ($("#departamento").val() == ''){
				$('#departamento').parent().addClass('has-error');
				$('#departamento').attr('placeholder','Campo Obligatorio');
				return false;
			}else{
				$('#departamento').parent().removeClass('has-error').addClass('has-success');
			}
			if ($("#tipousuario option:selected").index() == 0){
				$('#tipousuario').parent().addClass('has-error');
				$('#tipousuario').attr('placeholder','Campo Obligatorio');
				return false;
			}else{
				$('#tipousuario').parent().removeClass('has-error').addClass('has-success');
			}
			if ($("#modulos").val() == null){
				bootbox.alert("Debe Seleccionar al menos un módulo para el usuario");
				return false;
			}
			if ($("#clave").val() == ''){
				$('#clave').parent().addClass('has-error');
				$('#clave').attr('placeholder','Campo Obligatorio');
				return false;
			}else{
				$('#clave').parent().removeClass('has-error').addClass('has-success');
			}
			if ($("#clave2").val() == ''){
				$('#clave2').parent().addClass('has-error');
				$('#clave2').attr('placeholder','Campo Obligatorio');
				return false;
			}else{
				$('#clave2').parent().removeClass('has-error').addClass('has-success');
			}
			if ($("#clave").val()!= $("#clave2").val()){
					bootbox.alert("Las Contraseñas no Coinciden");
				return false;
			}
			bootbox.confirm('¿Seguro que desea Incluir el Usuario?', function(result){
				if (result == true){
					cedula = $('#cedula').val();
					nombre = $('#nombre').val();
					userid = $('#userid').val();
					cargo = $('#cargo').val();
					departamento = $('#departamento').val();
					tipousuario = $('#tipousuario').val();
					modulos = $('#modulos').val();
					clave = $('#clave').val();
					$.post('include/pdo/usuario.php', {function:'newUser', cedula:cedula, nombre:nombre, userid:userid, cargo:cargo, departamento:departamento, tipousuario:tipousuario, modulos:modulos, clave:clave}, function(data){
						if (data  == '0'){
							$('#error').html('<strong>¡Error!</strong> Error a Incluir el Usuario, Intente Nuevamente').fadeIn(1000).fadeOut(5000);
						}else if (data == '1'){
							$('#mensaje').html('<strong>¡Exito!</strong> Usuario Incluido Correctamente').fadeIn(1000).fadeOut(5000);
							$('#lista').DataTable().ajax.reload();
						}else if (data == 'repetido'){
							$('#alerta').html('<strong>¡Alerta!</strong> Ya existe una Usuario con esa Cédula o con ese Userid').fadeIn(1000).fadeOut(5000);
							$('#cedula').val('');
							$('#userid').val('');
						}//End if
					});//End post
					('#nuevo').modal('toggle');
				}//End if
			});//End Function
		});//End Function		

	//Mostrar Formulario de editar Usuario
		$('#lista tbody').on('click', '.edit', function(){
			var modulos;
			var cedula = $(this).attr('id');
			$('#cedula2').val(cedula).parent().removeClass('has-error has-success');
			$('option', $('#modulos2')).each(function(element) {
				$(this).removeAttr('selected').prop('selected', false);
	  		});
	  		$("#modulos2").multiselect('refresh');
			$.post('include/pdo/usuario.php', {function:'getUser', cedula:cedula}, function(data){
				var obj = jQuery.parseJSON(data);
				$('#nombre2').val(obj.nombre).parent().removeClass('has-error has-success');
				$('#cargo2').val(obj.cargo).parent().removeClass('has-error has-success');
				$('#userid2').val(obj.userid).parent().removeClass('has-error has-success');
				$('#departamento2').val(obj.departamento).parent().removeClass('has-error has-success');
				$('#tipousuario2').val(obj.nivel).parent().removeClass('has-error has-success');
				mod = obj.modulos;
				var array = mod.split(',');
				$('#modulos2').multiselect('deselectAll', true);
				$.each(array, function( index, value ) {
					$('#modulos2').multiselect('select', value);
				});//End Each
			});//End post
			$('#editar').modal('toggle');
		});

	//Validar y Editar Usuario
		$("#enviar").click(function(){
			if ($("#nombre2").val() == ''){
				$('#nombre2').parent().addClass('has-error');
				$('#nombre2').attr('placeholder','Campo Obligatorio');
				return false;
			}else{
				$('#nombre2').parent().removeClass('has-error').addClass('has-success');
			}

			if ($("#cargo2").val() == ''){
				$('#cargo2').parent().addClass('has-error');
				$('#cargo2').attr('placeholder','Campo Obligatorio');
				return false;
			}else{
				$('#cargo2').parent().removeClass('has-error').addClass('has-success');
			}

			if ($("#departamento2").val() == ''){
				$('#departamento2').parent().addClass('has-error');
				$('#departamento2').attr('placeholder','Campo Obligatorio');
				return false;
			}else{
				$('#departamento2').parent().removeClass('has-error').addClass('has-success');
			}

			if ($("#tipousuario2 option:selected").index() == 0){
				$('#tipousuario2').parent().addClass('has-error');
				$('#tipousuario2').attr('placeholder','Campo Obligatorio');
				return false;
			}else{
				$('#tipousuario2').parent().removeClass('has-error').addClass('has-success');
			}

			if ($("#modulos2").val() == null){
				bootbox.alert("Debe Seleccionar al menos un módulo para el usuario");
				return false;
			}else{
				$('#modulos2').parent().removeClass('has-error').addClass('has-success');
			}

			cedula = $('#cedula2').val();
			nombre = $('#nombre2').val();
			userid = $('#userid2').val();
			cargo = $('#cargo2').val();
			departamento = $('#departamento2').val();
			tipousuario = $('#tipousuario2').val();
			modulos = $('#modulos2').val();
				bootbox.confirm('¿Seguro que desea el Editar el Usuario?', function(result){
				if (result == true){
					$.post('include/pdo/usuario.php', {function:'editUser', cedula:cedula, nombre:nombre, cargo:cargo, departamento:departamento, tipousuario:tipousuario, modulos:modulos}, function(data){
						if (data  == '0'){
							$('#error').html('<strong>¡Error!</strong> "Error al Editar el Usuario, Intente mas tarde"').fadeIn(1000).fadeOut(5000);
						}else if (data == '1'){
							$('#mensaje').html('<strong>¡Exito!</strong> Usuario Editado Correctamente').fadeIn(1000).fadeOut(5000);
							$('#lista').DataTable().ajax.reload();
						}//End if						
					});//End post
					$('#editar').modal('toggle');
				}//End if
			});//End Function
		});			

	//Mostra el formulario Resetear Clave
		$('#lista tbody').on('click', '.reset', function(){
			$('#resetear').modal('toggle');
			var element = $(this).attr('id').split('│');
			var cedula = element[0];
			var nombre = element[1];
			var cargo = element[2];
			var userid = element[3];
			$('#cedula3').val(cedula);
			$('#userid3').val(userid);
			$('#nombre3').val(nombre);
			$('#clave_nueva').val('').attr('placeholdeer','').parent().removeClass('has-error has-success');
			$('#clave_nueva2').val('').attr('placeholdeer','').parent().removeClass('has-error has-success');
		});

	//Validar y Resetear Clave
		$("#enviar2").click(function(){
			if ($("#clave_nueva").val() == ''){
				$('#clave_nueva').parent().addClass('has-error');
				$('#clave_nueva').attr('placeholder','Campo Obligatorio');
				return false;
			}else{
				$('#clave_nueva').parent().removeClass('has-error').addClass('has-success');
			}
			
			if ($("#clave_nueva2").val() == ''){
				$('#clave_nueva2').parent().addClass('has-error');
				$('#clave_nueva2').attr('placeholder','Campo Obligatorio');
				return false;
			}else{
				$('#clave_nueva2').parent().removeClass('has-error').addClass('has-success');
			}

			if ($("#clave_nueva").val()!= $("#clave_nueva2").val()){
					bootbox.alert("Las Contraseñas no Coinciden");
				return false;
			}

			cedula = $('#cedula3').val();
			clave_nueva = $('#clave_nueva').val();
			bootbox.confirm('¿Seguro que desea el resetear la contraseña del Usuario?', function(result){
				if (result == true){
					$.post("include/pdo/usuario.php", {function:'resetPassword', cedula:cedula, clave_nueva:clave_nueva}, function(data){
						if (data  == '0'){
							$('#error').html('<strong>¡Error!</strong> Error al Editar la Contraseña, Intente mas tarde').fadeIn(1000).fadeOut(5000);
							}else if (data == '1'){
							$('#clave_nueva').val('');
							$('#clave_nueva2').val('');
							$('#mensaje').html('<strong>¡Exito!</strong> Contraseña Reseteada Correctamente').fadeIn(1000).fadeOut(5000);
						}//End if
					});//End post
					$('#resetear').modal('toggle');
				}//End if
			});//End Function
		});	

	//Cambiar el estatus del usuario
		$('#lista tbody').on('click', '.camb', function(){
			var element = $(this).attr('id').split('│');
			var cedula = element[0];
			var estatus = element[1];
			bootbox.confirm('¿Seguro que desea el cambiar el Estatus del Usuario?', function(result){
				if (result == true){
					$.post("include/pdo/usuario.php", {function:'changeStatus', cedula:cedula, estatus:estatus}, function(data){
						if (data  == '0'){
							$('#error').html('<strong>¡Error!</strong> Error al Editar el Estatus, Intente mas tarde').$('#error').fadeIn(1000).fadeOut(5000);
						}else if (data == '1'){
							$('#mensaje').html('<strong>¡Exito!</strong> Estatus Editado Correctamente').fadeIn(1000).fadeOut(5000);
							$('#lista').DataTable().ajax.reload();
						}//End if
					});//End post
				}//End if
			});//End Function
		});//End Function		

	//Convertir la tabla en Datatable
		$('#lista').dataTable({
			"ajax": {
			    "url": "include/pdo/usuario.php",
			    "type": "POST",
			    "data": {
			        "function": "getUsers"
			    }
			},
			"sPaginationType": "full_numbers",
			"language":{ 
				"url": "../DataTables/locale/Spanish.json"
			},
			aLengthMenu: [[10,50,100],[10,50,100]],
				"iDisplayLength": 10
		});
	});
</script>
</head>
<body>
	<?php echo $header?>
    <div class="container-fluid contenido">
		<?php echo $menu?>
	    <div class="text-center">
	    	<h4>Usuarios del Sistema</h4>
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
	    <div class="container-fluid">
		    <div class="row">
			    <div class="col-xs-12">
				    <table id="lista" class="table table-striped table-bordered table-condensed text-center dt-responsive table-hover nowrap" cellspacing="0" width="100%">
					    <thead>
					        <tr>
					            <th>CI</th>
					            <th>Nombre</th>
					            <th>Usuario</th>
					            <th>Cargo</th>
					            <th>Departamento</th>
					            <th>Estatus</th>
					            <th>Nivel</th>
					            <th>Comandos</th>
					        </tr>
					    </thead>
					  	<tfoot>
					        <tr>
					            <th>CI</th>
					            <th>Nombre</th>
					            <th>Usuario</th>
					            <th>Cargo</th>
					            <th>Departamento</th>
					            <th>Estatus</th>
					            <th>Nivel</th>
					            <th>Comandos</th>
					        </tr>
						</tfoot>
					    <tbody>
					    </tbody>
					</table>
				<div align="center">
			    	<img src="imagenes/usuarioadd.png" width="50" height="50" class="cursor" data-toggle="tooltip" data-placement="bottom" title="Agregar Usuario" id="boton">
			   	</div>
			    </div><!-- End col -->
		    </div><!-- End row -->
		</div><!-- End Container -->    

	    <!-- Modal Nuevo Usuario -->
	    <div id="nuevo" class="modal fade" role="dialog" tabindex='-1'>
	    	<div class="modal-dialog modal-lg">
	        	<div class="panel panel-primary luminoso text-center">
	            	<button type="button" class="close" data-dismiss="modal">&times;</button>
	                <div class="panel-heading">
	                    <h3 class="panel-title">Nuevo Usuario</h3>
	                </div>
	                <div class="panel-body">
	                    <input type="hidden" name="accion" value="nuevo">
	                    <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
	                        <label>Cédula</label>
	                        <input type="text" name="cedula" id="cedula" class="form-control integer uncopypaste text-center" maxlength="8">
	                    </div>
	                    <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
	                        <label>Nombre</label>
	                        <input type="text" name="nombre" id="nombre" class="form-control uncopypaste text-center">
	                    </div>
	                    <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
	                        <label>User Id</label>
	                        <input type="text" name="userid" id="userid" class="form-control uncopypaste text-center">
	                    </div>
	                    <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
	                        <label>Cargo</label>
	                        <input type="text" name="cargo" id="cargo" class="form-control uncopypaste text-center">
	                    </div>			

	                    <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
	                        <label>Departamento</label>
	                        <input type="text" name="departamento" id="departamento" class="form-control uncopypaste text-center">
	                    </div>
	                    <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
	                        <label>Tipo de Usuario</label>
	                        <select name="tipousuario" id="tipousuario" class="form-control text-center">
	                            <option value="" selected>Seleccionar...</option>
	                            <option value="1">Administrador</option>
	                            <option value="2">Supervisor</option>
	                            <option value="3">Usuario</option>
	                        </select>
	                    </div>

	                    <div class="form-group col-xs-12 col-md-6 col-lg-4">
	                        <label>Módulos</label><br>
	                        <select class="form-control" id="modulos" multiple>
	                            <?php	                           		
	                           		if (!is_null($modules )){
	                           			foreach ($modules as $key => $value){
	                           				echo '<option value="'.$value['id'].'">'.$value['descripcion'].'</option>';
	                           			}
	                           		}
	                           ?>
	                        </select>
	                    </div>
	                    <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
	                        <label>Contraseña</label>
	                        <input type="password" name="clave" id="clave" class="form-control uncopypaste text-center">
	                    </div>
	                    <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
	                        <label>Confirmar</label>
	                        <input type="password" name="clave2" id="clave2" class="form-control uncopypaste text-center">
	                    </div>
	                    <div class="form-group col-xs-12 text-center">
	                        <input type="image" id="save" src="imagenes/save.png" title="Ingresar Usuario">
	                    </div>
	                </div>
	            </div><!--End panel -->
	    	</div><!-- End Dialog -->
	    </div><!-- end Modal -->    

	  	<!-- Modal Editar Usuario -->
	    <div id="editar" class="modal fade" role="dialog" tabindex='-1'>
	    	<div class="modal-dialog modal-lg">
	        	<div class="panel panel-primary luminoso text-center">
	            	<button type="button" class="close" data-dismiss="modal">&times;</button>
	                <div class="panel-heading">
	                    <h3 class="panel-title">Editar Usuario</h3>
	                </div>
	                <div class="panel-body">
	                    <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
	                        <label>Cédula</label>
	                        <input  type="text" name="cedula2" id="cedula2" size="15" maxlength="8" class="form-control uncopypaste text-center" readonly>
	                    </div>
	                    <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
	                        <label>User Id</label>
	                        <input name="userid2" type="text" id="userid2" size="15" maxlength="15" class="form-control uncopypaste text-center" readonly>
	                    </div>
	                    <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
	                        <label>Nombre</label>
	                        <input name="nombre2" id="nombre2" type="text" size="40" maxlength="40" class="form-control uncopypaste text-center">
	                    </div>
	                    <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
	                        <label>Cargo</label>
	                        <input name="cargo2" type="text" id="cargo2" size="40" maxlength="40" class="form-control uncopypaste text-center">
	                    </div>
	                    <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
	                        <label>Departamento</label>
	                        <input name="departamento2" type="text" id="departamento2" size="40" maxlength="40" class="form-control uncopypaste text-center">
	                    </div>
	                    <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
	                        <label>Tipo de Usuario</label>
	                        <select name="tipousuario2" id="tipousuario2" class="form-control text-center">
	                            <option value="" selected>Seleccionar...</option>
	                            <option value="1">Administrador</option>
	                            <option value="2">Supervisor</option>
	                            <option value="3">Usuario</option>
	                        </select>
	                    </div>
	                    <div class="form-group col-xs-12 col-md-6 col-lg-4">
	                        <label>Módulos</label><br>
	                        <select class="form-control" id="modulos2" multiple>
	                           <?php	                           		
	                           		if (!is_null($modules )){
	                           			foreach ($modules as $key => $value){
	                           				echo '<option value="'.$value['id'].'">'.$value['descripcion'].'</option>';
	                           			}
	                           		}
	                           ?>
	                        </select> 
	                    </div>
	                    <div class="form-group col-xs-12 text-center">
	                        <input name="enviar" type="image" id="enviar" src="imagenes/save.png" title="Editar Usuario">
	                    </div>
	                </div>
	            </div><!--End panel -->
	    	</div><!-- End Dialog -->
	    </div><!-- end Modal -->	

	    <!-- Modal resetear Usuario -->
	    <div id="resetear" class="modal fade" role="dialog" tabindex='-1'>
	    	<div class="modal-dialog">
	        	<div class="panel panel-primary luminoso text-center">
	            	<button type="button" class="close" data-dismiss="modal">&times;</button>
	                <div class="panel-heading">
	                    <h3 class="panel-title">Resetear Contraseña</h3>
	                </div>
	                <div class="panel-body">
	                      <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
	                        <label>Cédula:</label>
	                        <input  type="text" name="cedula3" id="cedula3" size="15" maxlength="8" style="text-transform:uppercase" readonly class="form-control text-center">
	                    </div>
	                    <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
	                        <label>Nombre:</label>
	                        <input name="nombre3" id="nombre3" type="text" size="40" maxlength="40" readonly class="form-control text-center">
	                    </div>
	                    <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
	                        <label>Userid:</label>
	                        <input name="userid3" type="text" id="userid3" size="15" maxlength="15" readonly class="form-control text-center">
	                    </div>
	                    <div class="form-group col-xs-12 col-md-6 text-center">
	                        <label>Contraseña Nueva:</label>
	                        <input name="clave_nueva" type="password" id="clave_nueva" size="40" maxlength="40" class="form-control uncopypaste text-center">
	                    </div>
	                    <div class="form-group col-xs-12 col-md-6 text-center">
	                        <label>Confirmar Contraseña:</label>
	                        <input name="clave_nueva2" type="password" id="clave_nueva2" size="40" maxlength="40" class="uncopypaste form-control text-center">
	                    </div>
	                    <div class="form-group col-xs-12 text-center">
	                        <input name="enviar" type="image" id="enviar2" src="imagenes/save.png" title="Resetear Contraseña de Usuario">
	                    </div>
	                </div>
	            </div><!--End panel -->
	    	</div><!-- End Dialog -->
	    </div><!-- end Modal -->
	</div><!--End Contenido -->
   	<?php echo $footer?>
</body>
</html>
<?php
}else{
	header("location:index.php?alerta=salir");
}
?>