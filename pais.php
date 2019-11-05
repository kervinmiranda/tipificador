<?php
/*************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
require 'include/pdo/database.php';
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
	var id;

	//Activar Menú
		$("#menu2").attr('class','active');

	//Activar Multiselect Boostrap
	$('#modulos, #modulos2').multiselect({
		 includeSelectAllOption: true
	});

	//Mostrar Formulario de nuevo País
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

	//Validar y Agregar Pais
		$("#save").click(function(){
			if ($("#nombre").val() == ''){
				$('#nombre').parent().addClass('has-error');
				$('#nombre').attr('placeholder','Campo Obligatorio');
				return false;
			}else{
				$('#nombre').parent().removeClass('has-error').addClass('has-success');
			}
			bootbox.confirm('¿Seguro que desea Incluir el País?', function(result){
				if (result == true){
					nombre = $('#nombre').val();					
					$.post('include/pdo/pais.php', {function:'insertCountry', nombre:nombre}, function(data){
						if (data  == '0'){
							$('#error').html('<strong>¡Error!</strong> Error a Incluir el País, Intente Nuevamente').fadeIn(1000).fadeOut(5000);
						}else if (data == '1'){
							$('#mensaje').html('<strong>¡Exito!</strong> País Incluido Correctamente').fadeIn(1000).fadeOut(5000);
							$('#lista').DataTable().ajax.reload();
						}else if (data == 'repetido'){
							$('#alerta').html('<strong>¡Alerta!</strong> Ya existe un País con ese nombre').fadeIn(1000).fadeOut(5000);
							$('#cedula').val('');
							$('#userid').val('');
						}//End if
					});//End post
					$('#nuevo').modal('toggle');
				}//End if
			});//End Function
		});//End Function		

	//Mostrar Formulario de editar País
		$('#lista tbody').on('click', '.edit', function(){
			var modulos;
			id = $(this).attr('id');
			$.post('include/pdo/pais.php', {function:'getCountry', id:id}, function(data){
				if (data != 0){
					$('#nombre2').val(data.descripcion).parent().removeClass('has-error has-success');
				}		
			}, "json");//End post
		});

	//Validar y Editar País
		$("#enviar").click(function(){
			if ($("#nombre2").val() == ''){
				$('#nombre2').parent().addClass('has-error');
				$('#nombre2').attr('placeholder','Campo Obligatorio');
				return false;
			}else{
				$('#nombre2').parent().removeClass('has-error').addClass('has-success');
			}
			nombre = $('#nombre2').val();			
			bootbox.confirm('¿Seguro que desea el Editar el País?', function(result){
				if (result == true){
					$.post('include/pdo/pais.php', {function:'editCountry', nombre:nombre, id:id}, function(data){
						console.log(data);
						if (data  == '0'){
							$('#error').html('<strong>¡Error!</strong> "Error al Editar el País, Intente mas tarde"').fadeIn(1000).fadeOut(5000);
						}else if (data == '1'){
							$('#mensaje').html('<strong>¡Exito!</strong> País Editado Correctamente').fadeIn(1000).fadeOut(5000);
							$('#lista').DataTable().ajax.reload();
						}else if (data == 'repetido'){
							$('#alerta').html('<strong>¡Alerta!</strong> Ya existe un País con ese nombre').fadeIn(1000).fadeOut(5000);
							$('#cedula').val('');
							$('#userid').val('');
						}//End if						
					});//End post
					$('#editar').modal('toggle');
				}//End if
			});//End Function
		});

	//Cambiar el estatus del Tipo de Tipificación
		$('#lista tbody').on('click', '.block', function(){
			id = $(this).attr('id');
			bootbox.confirm('¿Seguro que desea el cambiar el Estatus del País?', function(result){
				if (result == true){
					$.post("include/pdo/pais.php", {function:"statusCountry", id:id }, function(data){
						if (data  == '0'){
							$('#error').html('<strong>¡Error!</strong> Error al Editar el PAís, Intente mas tarde').fadeIn(1000).fadeOut(5000);
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
			    "url": "include/pdo/pais.php",
			    "type": "POST",
			    "data": {
			        "function": "getAllCountries"
			    }
			},
			"sPaginationType": "full_numbers",
			"columnDefs": [
				{         
		              "render": function ( data, type, row ) {
		              	switch (row[2]){
		              		case "1":
		              		status = '<img src="imagenes/activo.png">';
		              		break;
		              		case "0":
		              		status = '<img src="imagenes/inactivo.png">';
		              		break;
		              		default:
		              		status = '';
		              		break;
		              	}
		                  return status;
		              },
		              "targets": 2
		        },
		      	{         
		              "render": function ( data, type, row ) {
		                  return '<img src="imagenes/block.png" class="block cursor" id="'+ row[0] +'" data-toggle="modal" data-placement="bottom" data-target="#block" title="habilitar / deshabilitar País">' + ' ' +'<img src="imagenes/gestion.png" class="edit cursor" id="'+ row[0] +'" data-toggle="modal" data-placement="bottom" data-target="#editar" title="Editar País">';
		              },
		              "targets": 3
		        }           
		      ],
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
	    	<h4>Lista de Paises</h4>
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
					            <th>Id</th>
					            <th>Nombre</th>
					            <th>Estatus</th>
					            <th>Comandos</th>
					        </tr>
					    </thead>
					  	<tfoot>
					        <tr>
					            <th>ID</th>
					            <th>Nombre</th>
					            <th>Estatus</th>
					            <th>Comandos</th>
					        </tr>
						</tfoot>
					    <tbody>
					    </tbody>
					</table>
				<div align="center">
			    	<img src="imagenes/add.png" width="50" height="50" class="cursor" data-toggle="tooltip" data-placement="bottom" title="Agregar País" id="boton">
			   	</div>
			    </div><!-- End col -->
		    </div><!-- End row -->
		</div><!-- End Container -->    

	    <!-- Modal Nuevo País -->
	    <div id="nuevo" class="modal fade" role="dialog" tabindex='-1'>
	    	<div class="modal-dialog modal-xs">
	        	<div class="panel panel-primary luminoso text-center">
	            	<button type="button" class="close" data-dismiss="modal">&times;</button>
	                <div class="panel-heading">
	                    <h3 class="panel-title">Nuevo País</h3>
	                </div>
	                <div class="panel-body">
	                    <input type="hidden" name="accion" value="nuevo">
	                    <div class="form-group col-xs-12 text-center">
	                        <label>Nombre</label>
	                        <input type="text" name="nombre" id="nombre" class="form-control uncopypaste text-center">
	                    </div>	                    
	                    <div class="form-group col-xs-12 text-center">
	                        <input type="image" id="save" src="imagenes/save.png" title="Ingresar País">
	                    </div>
	                </div>
	            </div><!--End panel -->
	    	</div><!-- End Dialog -->
	    </div><!-- end Modal -->    

	  	<!-- Modal Editar País -->
	    <div id="editar" class="modal fade" role="dialog" tabindex='-1'>
	    	<div class="modal-dialog modal-xs">
	        	<div class="panel panel-primary luminoso text-center">
	            	<button type="button" class="close" data-dismiss="modal">&times;</button>
	                <div class="panel-heading">
	                    <h3 class="panel-title">Editar País</h3>
	                </div>
	                <div class="panel-body">
	                    <div class="form-group col-xs-12 text-center">
	                        <label>Nombre</label>
	                        <input name="nombre2" id="nombre2" type="text" size="40" maxlength="40" class="form-control uncopypaste text-center">
	                    </div>	                    
	                    <div class="form-group col-xs-12 text-center">
	                        <input name="enviar" type="image" id="enviar" src="imagenes/save.png" title="Editar País">
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