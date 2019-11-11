<?php
/*************************************************************************************************************************
													SISTEMA GEBNET
**************************************************************************************************************************/
include_once 'include/pdo/atributo.php';
include_once 'include/fecha.php';
include_once 'include/variables.php';
if(isset($_SESSION['user']) && ($_SESSION['nivel'] < 2)){
?>
<?php echo $doctype?>
<!-- Achivos CSS -->
<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="../DataTables/css/dataTables.bootstrap.css">
<link rel="stylesheet" href="../DataTables/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="../bootstrap/css/bootstrap-submenu.css">
<link rel="stylesheet" href="css/call.css">
<style>
	table.dataTable.select tbody tr,
	table.dataTable thead th:first-child {
	  cursor: pointer;
	}
</style>

<!-- Archivos JavaScript -->	
<script src="../js/jquery.js"></script>
<script src="../bootstrap/js/bootstrap.js"></script>
<script src="../DataTables/js/jquery.dataTables.js"></script>
<script src="../DataTables/js/dataTables.bootstrap.js"></script>
<script src="../DataTables/js/dataTables.responsive.min.js"></script>
<script src="../bootstrap/js/bootstrap-submenu.js"></script>
<script src="../bootstrap/js/bootbox.min.js"></script>
<script src="js/jquery.bpopup.min.js"></script>
<script src="../js/jquery.numeric.js"></script>
<script src="js/libreriajs.js"></script>
<script>
$(document).ready(function(){
var id;
var atributos;
var aspectos;

fillAtributes();

//Activar Menú
	$("#menu2").attr('class','active');	

//Buscar los Aspectos despues de seleccionar un Atributo
	$('#atributosi').change(function () {
		$('#aspectosi').empty();		
		$('#atributosi option:selected').each(function () {
			elegido=$(this).val();
			$.post('include/buscar_asp.php', { elegido: elegido }, function(data){
				$('#aspectosi').html(data);
			});            
        });
   });
  
//Buscar los Aspectos despues de seleccionar un Atributo
	$('#atributosi2').change(function () {
		$('#aspectosi2').empty();		
		$('#atributosi2 option:selected').each(function () {
			elegido=$(this).val();
			$.post('include/buscar_asp.php', { elegido: elegido }, function(data){
				$('#aspectosi2').html(data);
			});            
        });
   });
   
//Mostrar Formulario de Nuevo atributo
	$("#boton").click(function() {
		$('body input[type=text]').val('').parent().removeClass('has-error has-success');		
		$('body input[type=password]').val('').parent().removeClass('has-error has-success');
		$('#motivo option:first').prop("selected", "selected");
		$('#motivo').parent().removeClass('has-error has-success');
		$('body textarea').val('').parent().removeClass('has-error has-success');
	});
	
//Validar y Agregar atributo nuevo
	$("#save").click(function(){		
		var contador = 0;
		$("#nuevo input").filter('.validar').each(function (index) { 
			if ($(this).val() == ''){
				$(this).parent().addClass('has-error');
				contador++;					
			}else{
				$(this).parent().removeClass('has-error has-warning').addClass('has-success');					
			}//End if					 
		 });//End each	 	
	
	//Guardar Nuevo Atributo
		if (contador < 1){			
			bootbox.confirm('¿Seguro que Desea Guardar el Atributo?', function(result){				
				if (result == true){
					descripcion = $('#descripcion').val();
					$.post( "include/pdo/atributo.php", {function:"insertAtribute", descripcion:descripcion}, function(data){							
					})
					.done(function(data) {								
						switch (data){
							case 'repetido':
								$('#mensajes').prepend('<div class="alert alert-warning text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Info!</strong> El Atributo ya Se Encuentra Registrado, Verifique e Intente Nuevamente</div>');
							break;							
							case '1':
								$('#mensajes').prepend('<div class="alert alert-success text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Exito!</strong> Atributo Guardado Correctamente</div>');
								<?php
									$atributos = getAtributes();
								?>
								$('#listaAtributo').DataTable().ajax.reload();										
							break;
							case '0':
								$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');						
							break;								
						}//End switch																						
					 })//End function done
					.fail(function() {
						$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');
					})					
					.always(function() {						
					});					
					$('#nuevoAtributo').modal('toggle');
				}//End if		 
			});//End Function		
		}else{		
			return false;
		}		 
	});//End Function Validar y Agregar

//Mostrar ventana de Edición de Atributo
	$('#listaAtributo tbody').on('click', '.edit', function(){
		id = $(this).attr('id');
		$.post( "include/pdo/atributo.php", {function:"getAtribute", id:id}, function(data){							
		}, "json")//End function
		.done(function(data){								
		 		$('#numero').val(id);
				$('#descripcion2').val(data.descripcion).parent().removeClass('has-error has-success');
				$('#editarAtributo').modal('toggle');																					
		 })//End function done
		.fail(function(){
			$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');
			$('#editarAtributo').modal('toggle');		
		})//End function fail
		.always(function(){						
		});//End Always
	});//End Function

//Validar y Editar Atributo
	$("#save2").click(function(){	
		var contador = 0;
		$("#editar input").filter('.validar').each(function (index) { 
			if ($(this).val() == ''){
				$(this).parent().addClass('has-error');
				contador++;					
			}else{
				$(this).parent().removeClass('has-error has-warning').addClass('has-success');					
			}//End if					 
		 });//End each	 	
	
	//Validamos y Guardamos
		if (contador < 1){			
			bootbox.confirm('¿Seguro que Desea Editar el Atributo?', function(result){				
				if (result == true){
					id = $('#numero').val();
					descripcion = $('#descripcion2').val();
					$.post( "include/pdo/atributo.php", {function:"editAtribute", id:id, descripcion:descripcion}, function(data){							
					})
					.done(function(data) {								
						switch (data){
							case 'repetido':
								$('#mensajes').prepend('<div class="alert alert-warning text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Info!</strong> El Atributo ya Se Encuentra Registrado, Verifique e Intente Nuevamente</div>');
							break;							
							case '1':
								$('#mensajes').prepend('<div class="alert alert-success text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Exito!</strong> Atributo Editardo Correctamente</div>');
								fillAtributes();		
								$('#listaAtributo').DataTable().ajax.reload();
							break;
							case '0':
								$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');						
							break;								
						}//End switch																						
					 })//End function done
					.fail(function() {
						$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');
					})					
					.always(function() {						
					});					
					$('#editarAtributo').modal('toggle');
				}//End if		 
			});//End Function		
		}else{		
			return false;
		}		 
	});//End Function Validar y Editar

//Cambiar el estatus del Atributo
	$('#listaAtributo tbody').on('click', '.camb', function(){	
		var id = $(this).attr('id');
		bootbox.confirm('¿Seguro que desea el cambiar el Estatus del Atributo?', function(result){
			if (result == true){
				$.post( "include/pdo/atributo.php", {function:"statusAtribute", id:id}, function(data){							
					})
					.done(function(data) {								
						switch (data){													
							case '1':
								$('#mensajes').prepend('<div class="alert alert-success text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Exito!</strong> Atributo Editardo Correctamente</div>');						
								$('#listaAtributo').DataTable().ajax.reload();
							break;
							case '0':
								$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');						
							break;								
						}//End switch
						fillAtributes();																			
					 })//End function done
					.fail(function() {
						$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');
					})					
					.always(function() {						
					});					
			}//End if		 
		});//End Function bootbox
	});//End Function

//Mostrar Formulario de Nuevo Aspecto
	$("#boton2").click(function() {		
		$('body input[type=text]').val('').parent().removeClass('has-error has-success');
		$('body textarea').val('').parent().removeClass('has-error has-success');
		$('#atributoe option:first').prop("selected", "selected");
		$('#atributoe').parent().removeClass('has-error has-success');
		$('#atributoe').empty();
		$('#atributoe').append("<option>Seleccionar...</option>");
		$.each(atributos, function(i,item){
				$('#atributoe').append("<option>"+ atributos[i].descripcion +"</option>");    					
		});	
	});

//Validar y Agregar Aspecto
	$("#savee").click(function(){		
		var contador = 0;
		$("#nuevoe input").filter('.validar').each(function (index) { 
			if ($(this).val() == ''){
				$(this).parent().addClass('has-error');
				contador++;					
			}else{
				$(this).parent().removeClass('has-error has-warning').addClass('has-success');					
			}//End if					 
		 });//End each
		$("#nuevoe select").filter('.validar').each(function (index) { 
     		if ($("option:selected", this).prop('index') == 0){
				$(this).parent().addClass('has-error');
				contador++;							
			}else{
				$(this).parent().removeClass('has-error has-warning').addClass('has-success');
			}//End if
		});//End each
	
	//Validamos y Guardamos el Nuevo Aspecto
		if (contador < 1){			
			bootbox.confirm('¿Seguro que Desea Guardar el Aspecto a Evaluar?', function(result){				
				if (result == true){
					atributo = $('#atributoe').val();								
					descripcion = $('#descripcione').val();					
					$.post( "include/pdo/aspecto.php", {function:"insertAspect", atributo:atributo, descripcion:descripcion}, function(data){							
					})
					.done(function(data) {								
						switch (data){
							case 'repetido':
								$('#mensajes').prepend('<div class="alert alert-warning text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Info!</strong> El Aspecto a Evaluar ya Se Encuentra Registrado, Verifique e Intente Nuevamente</div>');
							break;							
							case '1':
								$('#mensajes').prepend('<div class="alert alert-success text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Exito!</strong> Aspecto a Evaluar Guardado Correctamente</div>');
								$('#listaAspecto').DataTable().ajax.reload();						
							break;
							case '0':
								$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');						
							break;								
						}//End switch																						
					 })//End function done
					.fail(function() {
						$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');
					})					
					.always(function() {						
					});					
					$('#nuevoAspecto').modal('toggle');
				}//End if		 
			});//End Function		
		}else{		
			return false;
		}		 
	});//End Function Validar y Agregar

//Mostrar VEntana de Edición de Aspecto
	$('#listaAspecto tbody').on('click', '.edit', function(){
		var id = $(this).attr('id');
		$.post( "include/pdo/aspecto.php", {function:"getAspect", id:id}, function(data){							
		}, "json")//End function
		.done(function(data){
			console.log(data);				
			$('#numeroa').val(id);
			$('#atributoe2').val(data.id_atributo).parent().removeClass('has-error has-success');		
			$('#descripcione2').val(data.descripcion).parent().removeClass('has-error has-success');				
			$('#editarAspecto').modal('toggle');																				
		 })//End function done
		.fail(function(){
			$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');					
		})//End function fail
		.always(function(){						
		});//End Always
	});//End Function

//Validar y Editar Aspecto
	$("#savee2").click(function(){		
		var contador = 0;
		$("#editare input").filter('.validar').each(function (index) { 
			if ($(this).val() == ''){
				$(this).parent().addClass('has-error');
				contador++;					
			}else{
				$(this).parent().removeClass('has-error has-warning').addClass('has-success');					
			}//End if					 
		 });//End each
		 $("#editare select").filter('.validar').each(function (index) { 
     		if ($("option:selected", this).prop('index') == 0){
				$(this).parent().addClass('has-error');
				contador++;
			}else{
				$(this).parent().removeClass('has-error has-warning').addClass('has-success');
			}//End if
		});//End each
	
	//Validamos y Editamos el Aspecto
		if (contador < 1){			
			bootbox.confirm('¿Seguro que Desea Editar el Aspecto a Evaluar?', function(result){				
				if (result == true){
					id = $('#numeroa').val();
					atributo = $('#atributoe2').val();							
					descripcion = $('#descripcione2').val();					
					$.post( "include/pdo/aspecto.php", {function:"editAspect", id:id, atributo:atributo, descripcion:descripcion}, function(data){
					})
					.done(function(data) {								
						switch (data){
							case 'repetido':
								$('#mensajes').prepend('<div class="alert alert-warning text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Info!</strong> El Aspecto a Evaluar ya Se Encuentra Registrado, Verifique e Intente Nuevamente</div>');
							break;
							case '1':
								$('#mensajes').prepend('<div class="alert alert-success text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Exito!</strong> Aspecto a Evaluar Editado Correctamente</div>');
								$('#listaAspecto').DataTable().ajax.reload();						
							break;
							case '0':
								$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');						
							break;								
						}//End switch																						
					 })//End function done
					.fail(function() {
						$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');
					})					
					.always(function() {						
					});					
					$('#editarAspecto').modal('toggle');
				}//End if		 
			});//End Function		
		}else{		
			return false;
		}		 
	});//End Function Validar y Editar Aspecto

//Cambiar el Estatus del Aspecto
	$('#listaAspecto tbody').on('click', '.camb', function(){	
		var accion = "estatus";
		var id = $(this).attr('id');
		bootbox.confirm('¿Seguro que desea el cambiar el Estatus del Aspecto a Evaluar?', function(result){
			if (result == true){
				$.post( "include/pdo/aspecto.php", {function:"statusAspect", id:id}, function(data){							
					})
					.done(function(data) {								
						switch (data){													
							case '1':
								$('#mensajes').prepend('<div class="alert alert-success text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Exito!</strong> Aspecto a Evaluar Editado Correctamente</div>');						
								$('#listaAspecto').DataTable().ajax.reload();
							break;
							case '0':
								$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');						
							break;								
						}//End switch																						
					 })//End function done
					.fail(function() {
						$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');
					})					
					.always(function() {						
					});					
			}//End if		 
		});//End Function bootbox
	});//End Function

//Mostrar Formulario de Nueva Situación
	$("#boton3").click(function() {
		$('body input[type=text]').val('').parent().removeClass('has-error has-success');
		$('body textarea').val('').parent().removeClass('has-error has-success');
		$('#atributosi option:first, #aspectosi option:first').prop("selected", "selected");
		$('#atributosi, #aspectosi').parent().removeClass('has-error has-success');		
	});

//Validar y Agregar Situación
	$('#savesi').click(function(){
		var contador = 0;
		$("#nuevasi input").filter('.validar').each(function (index) { 
			if ($(this).val() == ''){
				$(this).parent().addClass('has-error');
				contador++;					
			}else{
				$(this).parent().removeClass('has-error has-warning').addClass('has-success');					
			}//End if					 
		 });//End each
		 $("#nuevasi select").filter('.validar').each(function (index) { 
     		if ($("option:selected", this).prop('index') == 0){
				$(this).parent().addClass('has-error');
				contador++;							
			}else{
				$(this).parent().removeClass('has-error has-warning').addClass('has-success');
			}//End if
		});//End each
		
	//Validamos y Guardamos Nueva Situación
		if (contador < 1){			
			bootbox.confirm('¿Seguro que Desea Guardar la Situación a Evaluar?', function(result){				
				if (result == true){
					accion = 'nuevo';
					aspecto = $('#aspectosi').val();								
					descripcion = $('#descripcionsi').val();
					grupo = $('#gruposi').val();					
					$.post( "include/guardar_situacion.php", {accion:accion, aspecto:aspecto, descripcion:descripcion, grupo:grupo}, function(data){							
					})
					.done(function(data) {								
						switch (data){
							case 'repetido':
								$('#mensajes').prepend('<div class="alert alert-warning text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Info!</strong> La Situación a Evaluar ya Se Encuentra Registrada, Verifique e Intente Nuevamente</div>');
							break;							
							case '1':
								$('#mensajes').prepend('<div class="alert alert-success text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Exito!</strong> Situación a Evaluar Guardada Correctamente</div>');
								$('#listaSituacion').DataTable().ajax.reload();						
							break;
							case '0':
								$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');						
							break;								
						}//End switch																						
					 })//End function done
					.fail(function() {
						$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');
					})					
					.always(function() {						
					});					
					$('#nuevaSituacion').modal('toggle');
				}//End if		 
			});//End Function		
		}else{		
			return false;
		}
	});//End Function

//Mostrar datos de Edición de Situación
	$('#listaSituacion tbody').on('click', '.edit', function(){
		var id = $(this).attr('id');
		//Obtenemos la tabla
		var table = $('#listaSituacion').DataTable();
		//Obtenemos la fila	y los Datos	
		var fila = table.row( $(this).parents('tr') ).index();
		var situacion = table.cell(fila,1).data();	
		var grupo = table.cell(fila,2).data();	
		$('#numerosi').val(id);
		$('#descripcionsi2').val(situacion);
		$('#gruposi2').val(grupo);		
		$('#atributosi2 option:first, #aspectosi2 option:first').prop("selected", "selected");
		$('#atributosi2, #aspectosi2, #descripcionsi2').parent().removeClass('has-error has-success');	
		$('#editarSituacion').modal('toggle');		
	});//End Function

//Validar y Editar Situacion
	$("#savesi2").click(function(){		
		var contador = 0;
		$("#editarsi input").filter('.validar').each(function (index) { 
			if ($(this).val() == ''){
				$(this).parent().addClass('has-error');
				contador++;					
			}else{
				$(this).parent().removeClass('has-error has-warning').addClass('has-success');					
			}//End if					 
		 });//End each
		 $("#editarsi select").filter('.validar').each(function (index) { 
     		if ($("option:selected", this).prop('index') == 0){
				$(this).parent().addClass('has-error');
				contador++;							
			}else{
				$(this).parent().removeClass('has-error has-warning').addClass('has-success');
			}//End if
		});//End each
	
	//Validamos y Editamos la Situación
		if (contador < 1){			
			bootbox.confirm('¿Seguro que Desea Editar la Situación a Evaluar?', function(result){				
				if (result == true){
					id = $('#numerosi').val();
					accion = 'editar';					
					aspecto = $('#aspectosi2').val();
					descripcion = $('#descripcionsi2').val();
					grupo = $('#gruposi2').val();
					$.post( "include/guardar_situacion.php", {accion:accion, id:id, aspecto:aspecto, descripcion:descripcion, grupo:grupo}, function(data){
					})
					.done(function(data) {								
						switch (data){
							case 'repetido':
								$('#mensajes').prepend('<div class="alert alert-warning text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Info!</strong> La Situación a Evaluar ya Se Encuentra Registrada, Verifique e Intente Nuevamente</div>');
							break;							
							case '1':
								$('#mensajes').prepend('<div class="alert alert-success text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Exito!</strong> Situación a Evaluar Editada Correctamente</div>');
								$('#listaSituacion').DataTable().ajax.reload();						
							break;
							case '0':
								$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');						
							break;								
						}//End switch																						
					 })//End function done
					.fail(function() {
						$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');
					})					
					.always(function() {						
					});					
					$('#editarSituacion').modal('toggle');
				}//End if		 
			});//End Function		
		}else{		
			return false;
		}		 
	});//End Function Validar y Agregar

//Cambiar el Estatus de la Situación
	$('#listaSituacion tbody').on('click', '.camb', function(){	
		var accion = "estatus";
		var id = $(this).attr('id');
		bootbox.confirm('¿Seguro que desea el cambiar el Estatus de la Situación a Evaluar?', function(result){
			if (result == true){
				$.post( "include/guardar_situacion.php", {accion:accion, id:id}, function(data){							
					})
					.done(function(data) {								
						switch (data){													
							case '1':
								$('#mensajes').prepend('<div class="alert alert-success text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Exito!</strong> Situación a Evaluar Editada Correctamente</div>');						
								$('#listaSituacion').DataTable().ajax.reload();
							break;
							case '0':
								$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');						
							break;								
						}//End switch																						
					 })//End function done
					.fail(function() {
						$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');
					})					
					.always(function() {						
					});					
			}//End if		 
		});//End Function bootbox
	});//End Function

// //Marcar y desmarcar todos los items
	$('#all').click(function(){
		estado = $('#all').prop('checked');		
		switch (estado){
			case true: $("#selected input[type=checkbox]").prop('checked', true);
					   $("#selected select").prop('disabled', '');
			break;

			case false: $("#selected input[type=checkbox]").prop('checked', false);
						$("#selected select").prop('disabled', 'disabled');
			break
		}//End switch
	});//End Function

//Check And Uncheck item 
	$('#nuevoFormulario tbody').on('click', '.item', function(){
		var id = $(this).val();
		var estado = $(this).prop('checked');
		switch (estado){
			case true: $('#'+ id).prop('disabled','');					   
			break;
			case false: $('#'+ id).prop('disabled','disabled');						
			break
		}//End switch		
	});


//Mostrar Nuevo Formulario
	$('#boton4').click(function(){
		var formulario = 'nuevo';
		$('#exampleform').dataTable().fnDestroy();
		$('#exampleform').dataTable({
		"ajax": {
			"url": "include/consulta_ca.php",
			"data": {                       
				formulario:formulario             
				},
			"type": 'POST'
		},
		"sPaginationType": "full_numbers",
		"language":{ 
			"url": "../DataTables/locale/Spanish.json"
		},
		"aaSorting": [[ 0, "asc" ],[1, "asc"],[2, "asc"], [3, "asc"]],
		aLengthMenu: [[10,50,100],[10,50,100]],
			"iDisplayLength": 100,
		"bPaginate": false,
	});	
	
		$('#nuevoFormulario').removeClass('oculto');
		$('#all').prop('checked', true);
//		$('#boton4').addClass('oculto');		// Comentado para evitar que el icono de + se esconda luego de cerrar el modal de nuevo formulario!
	});//End Function


//Guardar Formulario
	$('#saveform').click(function(){
		var selected = [];
        $("#selected select").each(function(){
		//Agregamos los items seleccionados al array
			if ($(this).prop('disabled') == false){
				item = $(this).attr('id') + (':') + $(this).val();
				selected.push(item);	
			}			
		});
        
        if (selected == ''){
        	bootbox.alert('Debe Seleccionar al menos un Item');
        	return false;
        } 
        if ($(nombre).val() == ''){
        	bootbox.alert('Debe Colocar un Nombre a la Plantilla o Formulario');
        	return false;
        }
		bootbox.confirm('¿Seguro que desea Guardar el Formulario?', function(result){
			if (result == true){
				accion = 'nuevo';
				nombre = $('#nombre').val();
				$.post( "include/guardar_form.php", {accion:accion, nombre:nombre, situaciones:selected}, function(data){
				})
				.done(function(data) {								
					switch (data){													
						case '1':
							$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde1</div>');				
						break;
						case '0':
							$('#mensajes').prepend('<div class="alert alert-success text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Exito!</strong> Formulario de Evaluación Guardado Correctamente</div>');						
							$('#listaFormulario').DataTable().ajax.reload();
							$('#nombre').val('');	

						break;							
					}//End switch																					
				 })//End function done
				.fail(function() {
					$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde2</div>');
				})					
				.always(function() {						
				});
				$('#nuevoFormulario').modal('toggle');

//				$('#nuevoFormulario').addClass('oculto');
//				$('#boton4').removeClass('oculto');
			}//End if
		});//End Function bootbox	
	});//End Function


//Mostrar datos de Edición de Formulario
	$('#listaFormulario tbody').on('click', '.edit', function(){
		var id = $(this).attr('id');
		//Obtenemos la tabla
		var table = $('#listaFormulario').DataTable();
		//Obtenemos la fila	y los Datos	
		var fila = table.row( $(this).parents('tr') ).index();
		var formulario = table.cell(fila,1).data();	
		var grupo = table.cell(fila,2).data();	
		$('#numerosi').val(id);
		$('#descripcionsi2').val(formulario);
		$('#gruposi2').val(grupo);
		$('#nombre').val(nombre);
		$('#atributosi2 option:first, #aspectosi2 option:first').prop("selected", "selected");
		$('#atributosi2, #aspectosi2, #descripcionsi2').parent().removeClass('has-error has-success');	
		$('#editarFormulario').modal('toggle');		
	});//End Function

//Validar y Editar Formulario
	$("#saveform2").click(function(){
		var contador = 0;
		$("#editarform input").filter('.validar').each(function (index) { 
			if ($(this).val() == ''){
				$(this).parent().addClass('has-error');
				contador++;					
			}else{
				$(this).parent().removeClass('has-error has-warning').addClass('has-success');					
			}//End if					 
		 });//End each
		 $("#editarform select").filter('.validar').each(function (index) { 
     		if ($("option:selected", this).prop('index') == 0){
				$(this).parent().addClass('has-error');
				contador++;							
			}else{
				$(this).parent().removeClass('has-error has-warning').addClass('has-success');
			}//End if
		});//End each
	
	//Validamos y Editamos el Formulario
		if (contador < 1){			
			bootbox.confirm('¿Seguro que Desea Editar el Formulario a Evaluar?', function(result){				
				if (result == true){
					accion = 'editar';
					id = $('#numerosi').val();
					aspecto = $('#aspectosi2').val();
					descripcion = $('#descripcionsi2').val();	// nombre?
					grupo = $('#gruposi2').val();				// gestor?
					porc = $('#porc').val();
					nombre = $('#nombre').val();
					$.post( "include/guardar_form.php", {accion:accion, id:id, aspecto:aspecto, descripcion:descripcion, grupo:grupo, porc:porc, nombre:nombre}, function(data){
					})
					.done(function(data) {
						switch (data){
							case 'repetido':
								$('#mensajes').prepend('<div class="alert alert-warning text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Info!</strong>El formulario ya Se Encuentra registrado, Verifique e Intente Nuevamente</div>');
							break;							
							case '1':
								$('#mensajes').prepend('<div class="alert alert-success text-center"><a href="#" eclass="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Exito!</strong> Formulario editado Correctamente</div>');
								$('#listaFormulario').DataTable().ajax.reload();
							break;
							case '0':
								$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');						
							break;
						}//End switch
					 })//End function done
					.fail(function() {
						$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A ocurrido un error, intente más tarde 4. Id: '+id+', aspecto: '+aspecto+', descripcion:'+descripcion+',grupo:'+grupo+',nombre:'+nombre+',porc:'+porc+'</div>');
					})

					.always(function() {
					});					
					$('#editarFormulario').modal('toggle');
				}//End if		 
			});//End Function		
		}else{		
			return false;
		}		 
	});//End Function Validar y Agregar

//Cambiar el Estatus del Formulario
	$('#listaFormulario tbody').on('click', '.camb', function(){	
		var accion = "estatus";
		var id = $(this).attr('id');
		bootbox.confirm('¿Seguro que desea cambiar el Estatus del Formulario?', function(result){
			if (result == true){
				$.post( "include/guardar_form.php", {accion:accion, id:id}, function(data){							
					})
					.done(function(data) {								
						switch (data){
							case '1':
								$('#mensajes').prepend('<div class="alert alert-success text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Exito!</strong> formulario Editado Correctamente</div>');						
								$('#listaFormulario').DataTable().ajax.reload();
							break;
							case '0':
								$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');						
							break;								
						}//End switch

					 })//End function done
					.fail(function() {
						$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');
					})					
					.always(function() {						
					});					
			}//End if		 
		});//End Function bootbox
	});//End Function

//Convertir la tabla en Datatable
	$('#listaAtributo').dataTable({
		"ajax": {
    		"url": "include/pdo/atributo.php",
    		"data": {
                function:"getAllAtributes"
                },
			"type": 'POST'
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
		              		edit = '<img src="imagenes/edit.png" class="edit cursor" title="Editar Atributo" id="'+ row[0]+'">';
		              		switch (row[2]){
			              		case "1":
			              			block = '<img src="imagenes/block2.png" class="camb cursor" title="Bloquear Atributo" id="'+ row[0] +'">';
			              		break;
			              		case "0":
			              			block = '<img src="imagenes/block.png" class="camb cursor" title="Desbloquear Atributo" id="'+ row[0] +'">';
			              		break;
			              		default:
			              			block = '';
			              		break;
			              	}		              		
		                  return  edit + ' ' + block;
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

	$('#listaAspecto').dataTable({
		"ajax": {
    		"url": "include/pdo/aspecto.php",
    		"data": {
                function:"getAllAspects"
                },
			"type": 'POST'
	  	},
		"sPaginationType": "full_numbers",
		"columnDefs": [
				{         
		              "render": function ( data, type, row ) {
		              	switch (row[3]){
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
		              "targets": 3
		        },
		      	{         
		              "render": function ( data, type, row ) {
		              		edit = '<img src="imagenes/edit.png" class="edit cursor" title="Editar Aspecto" id="'+ row[0]+'">';
		              		switch (row[3]){
			              		case "1":
			              			block = '<img src="imagenes/block2.png" class="camb cursor" title="Bloquear Aspecto" id="'+ row[0] +'">';
			              		break;
			              		case "0":
			              			block = '<img src="imagenes/block.png" class="camb cursor" title="Desbloquear Aspecto" id="'+ row[0] +'">';
			              		break;
			              		default:
			              			block = '';
			              		break;
			              	}		              		
		                  return  edit + ' ' + block;
		              },
		              "targets": 4
		        }           
		      ],
		"language":{
			"url": "../DataTables/locale/Spanish.json"
		},
		aLengthMenu: [[10,50,100],[10,50,100]],
			"iDisplayLength": 10
	});

	/*$('#listaSituacion').dataTable({
		"ajax": {
    		"url": "include/pdo/situacion.php",
    		"data": {
                function:"getSituations"
                },
			"type": 'POST'
	  	},
		"sPaginationType": "full_numbers",
		"language":{
			"url": "../DataTables/locale/Spanish.json"
		},
		aLengthMenu: [[10,50,100],[10,50,100]],
			"iDisplayLength": 10
	});*/

	function fillAtributes(){
		$.post( "include/pdo/atributo.php", {function:"getAtributes"}, function(data){					
		})
		.done(function(data) {
			atributos = jQuery.parseJSON(data);													
		})//End function done
		.fail(function() {
		})//End function fail
		.always(function() {						
		});	//End function always		
	}

});//End Document Ready
</script>
</head>
<body>
	<?php echo $header?>
	<div class="container-fluid contenido">
	<?php echo $menu?>

	    <div class="text-center">
	    	<h4>Criterios de Evaluación</h4>
	    </div>
	       
	    <ul class="nav nav-tabs">
	      	<li id="opcion1"><a data-toggle="tab" href="#atributos">Atributos</a></li>
	      	<li id="opcion2"><a data-toggle="tab" href="#aspectos">Aspectos</a></li>
	        <li id="opcion3"><a data-toggle="tab" href="#situaciones">Situaciones</a></li>
	        <li id="opcion4"><a data-toggle="tab" href="#formularios">Formularios</a></li>	        
		</ul>
	    
	    <div class="tab-content">
	        <div id="atributos" class="tab-pane fade">
	        	<h4 class="text-center">Atributos a Evaluar</h4>
	      		<div class="container-fluid">
	                <div class="row">
	                    <div class="col-xs-12">
	                    <table id="listaAtributo" class="table table-condensed table-striped table-bordered text-center dt-responsive table-hover nowrap" cellspacing="0" width="100%">
	                    <thead>
	                        <tr>
	                            <th>N°</th>
	                            <th>Atributo</th>
	                            <th>Estatus</th>
	                            <th>Comandos</th>             
	                        </tr>
	                    </thead>
	                    <tfoot>        
	                        <tr>
	                            <th>N°</th>
	                            <th>Atributo</th>            
	                            <th>Estatus</th>
	                            <th>Comandos</th>             
	                        </tr>
	                    </tfoot>     
	                    <tbody>      
	                    </tbody>
	                    </table>
	                    <div align="center">
	                        <img src="imagenes/add.png" width="50" height="50" class="cursor" data-toggle="modal" data-placement="bottom" title="Agregar Atributo" id="boton" data-target="#nuevoAtributo">
	                    </div>
	                    </div><!-- End col -->
	                </div><!-- End row -->
				</div><!-- End Container -->                 
	      	</div><!-- End Tab -->
	            	
	        <div id="aspectos" class="tab-pane fade"> 
	        	<div class="container-fluid">
				<h4 class="text-center">Aspectos a Evaluar</h4>
	                <div class="row">
	                    <div class="col-xs-12">
	                    <table id="listaAspecto" class="table table-condensed table-striped table-bordered text-center dt-responsive table-hover nowrap" cellspacing="0" width="100%">
	                    <thead>
	                        <tr>
	                            <th>N°</th>
	                            <th>Atributo</th>                                                       
	                            <th>Aspecto</th>
	                            <th>Estatus</th>
	                            <th>Comandos</th>             
	                        </tr>
	                    </thead>
	                    <tfoot>        
	                        <tr>
	                            <th>N°</th>
	                            <th>Atributo</th>                                                               
	                            <th>Aspecto</th>
	                            <th>Estatus</th>
	                            <th>Comandos</th>             
	                        </tr>
	                    </tfoot>     
	                    <tbody>      
	                    </tbody>
	                    </table>
	                    <div align="center">
	                        <img src="imagenes/add.png" width="50" height="50" class="cursor" data-toggle="modal" data-placement="bottom" title="Agregar Aspecto" id="boton2" data-target="#nuevoAspecto">
	                    </div>
	                    </div><!-- End col -->
	                </div><!-- End row -->
				</div><!-- End Container -->                   		
	      	</div><!-- End Tab -->
	        
	       <div id="situaciones" class="tab-pane fade">
	       		<div class="container-fluid">
				<h4 class="text-center">Situaciones a Evaluar</h4>
	                <div class="row">
	                    <div class="col-xs-12 table-responsive">
	                    <table id="listaSituacion" class="table table-condensed table-striped table-bordered text-center dt-responsive table-hover nowrap" cellspacing="0" width="100%">
	                    <thead>
	                        <tr>
	                            <th>N°</th>
	                            <th>Situación</th>                                                       
	                            <th>Grupo</th>	                            
	                            <th>Aspecto</th>
	                            <th>Atributo</th>
	                            <th>Estatus</th>
	                            <th>Comandos</th>             
	                        </tr>
	                    </thead>
	                    <tfoot>        
	                        <tr>
	                            <th>N°</th>
	                            <th>Situación</th> 
	                            <th>Grupo</th>
	                            <th>Aspecto</th>                                                            
	                            <th>Atributo</th>	                            
	                            <th>Estatus</th>
	                            <th>Comandos</th>             
	                        </tr>
	                    </tfoot>     
	                    <tbody>      
	                    </tbody>
	                    </table>
	                    <div align="center">
	                        <img src="imagenes/add.png" width="50" height="50" class="cursor" data-toggle="modal" data-placement="bottom" title="Agregar Situación" id="boton3" data-target="#nuevaSituacion">
	                    </div>
	                    </div><!-- End col -->
	                </div><!-- End row -->
				</div><!-- End Container -->                    		
	      	</div><!-- End Tab -->	        
	       
	       	<div id="formularios" class="tab-pane fade">
	       		<h4 class="text-center">Formularios de Evaluación</h4>
	      		<div class="container-fluid">
	                <div class="row">
	                    <div class="col-xs-12">
	                    <table id="listaFormulario" class="table table-condensed table-striped table-bordered text-center dt-responsive table-hover nowrap" cellspacing="0" width="100%">
	                    <thead>	                        
	                        <tr>
	                            <th>N°</th>
	                            <th>Nombre</th>
	                            <th>Creador</th>
	                            <th>Estatus</th>
	                            <th>Comandos</th>           
	                        </tr>
	                    </thead>
	                    <tfoot>        
	                        <tr>
	                            <th>N°</th>
	                            <th>Nombre</th>
	                            <th>Creador</th>
	                            <th>Estatus</th>
	                            <th>Comandos</th>             
	                        </tr>
	                    </tfoot>     
	                    <tbody>      
	                    </tbody>
	                    </table>
	                    <div align="center">
	                        <img src="imagenes/add.png" width="50" height="50" class="cursor" data-toggle="modal" data-placement="bottom" title="Agregar Formulario" id="boton4" data-target="#nuevoFormulario">
	                    </div>
	                    </div><!-- End col -->
	                </div><!-- End row -->
				</div><!-- End Container -->

				<!-- Tabla de Nuevo Formulario -->
			    <div id="nuevoFormulario" class="modal fade" role="dialog" tabindex='-1'>
			    	<div class="modal-dialog">	
			            <div class="panel panel-primary luminoso text-center" style="width:180%;margin-left: -40%;">
			                <button type="button" class="close" data-dismiss="modal">&times;</button>
			                <div class="panel-heading">
			                    <h4 class="panel-title">Nuevo Formulario</h4>                    
			                </div>

			                <div class="panel-body">
								<div class="col-xs-12">
				                    <table id="exampleform" class="table-condensed table-striped table-bordered table-hover" cellspacing="0" style="width:100%;">
				                    	<thead>
				                        <tr>
				                        	<th>Atributo</th>
				                            <th>Aspecto</th>
				                            <th>Grupo</th>
				                            <th>Situación</th>
				                            <th><input name="select_all" type="checkbox" id="all" checked></input></th>
				                            <th>Porcentaje</th>
				                        </tr>
					                    </thead>
				                   		<tbody id="selected"> 
				                    	</tbody>
				                    </table>
			                    </div>
			                    <div class="col-xs-12 col-md-8 col-lg-4 col-md-offset-2 col-lg-offset-4 text-center">
									<label for="nombre">Nombre</label>
									<input type="text" id="nombre" class="form-control"></input>
								</div>
								<div class="col-xs-12 col-md-8 col-lg-4 col-md-offset-2 col-lg-offset-4 text-center">
									<input type="image" id="saveform" src="imagenes/save.png" title="Guadar Formulario">
									
								</div>
							</div>


			            </div><!--End panel -->              
			    	</div><!-- End Dialog -->
			    </div><!-- end Modal -->
			</div><!-- End div formularios -->
	    </div><!-- End tab-content -->    
	    
	    <!-- Div para contenido de los Mensajes -->
	    <div id="mensajes" class="col-xs-12 col-md-8 col-md-offset-2 mensajes">
	    </div><!-- End col -->
	          
		<!-- Modal Nuevo Atributo -->
	    <div id="nuevoAtributo" class="modal fade" role="dialog" tabindex='-1'>
	    	<div class="modal-dialog">
	            <div class="panel panel-primary luminoso text-center">
	                <button type="button" class="close" data-dismiss="modal">&times;</button>
	                <div class="panel-heading">
	                    <h4 class="panel-title">Nuevo Atributo</h4>                    
	                </div>                
	                <div class="panel-body">
	                	<form id="nuevo">                 
	                    <div class="form-group col-xs-12 col-md-8 col-md-offset-2">
	                        <label for="descripcion">Descripción</label>
	                        <input type="text" name="descripcion" id="descripcion" class="form-control text-uppercase uncopypaste text-center validar">
	                    </div>
	                    </form> 
	                    <div class="form-group col-xs-12 text-center">
	                        <input type="image" id="save" src="imagenes/save.png" title="Guardar Atributo">
	                    </div>                                     	         
	                </div>
	            </div><!--End panel -->              
	    	</div><!-- End Dialog -->
	    </div><!-- end Modal -->    
	        
	    <!-- Modal Editar Atributo -->
	    <div id="editarAtributo" class="modal fade" role="dialog">
	    	<div class="modal-dialog">	
	            <div class="panel panel-primary luminoso text-center">
	                <button type="button" class="close" data-dismiss="modal">&times;</button>
	                <div class="panel-heading">
	                    <h4 class="panel-title">Editar Atributo</h4>                    
	                </div>                
	                <div class="panel-body">                   
	                    <div class="form-group col-xs-12 col-md-8 col-md-offset-2">
	                        <label for="numero">Id</label>
	                        <input type="text" name="numero" id="numero" class="form-control text-uppercase uncopypaste text-center validar" disabled>
	                    </div>             
	                    <form id="editar">
	                    <div class="form-group col-xs-12 col-md-8 col-md-offset-2">
	                        <label for="descripcion2">Descripción</label>
	                        <input type="text" name="descripcion2" id="descripcion2" class="form-control text-uppercase uncopypaste text-center validar">
	                    </div>
	                    </form> 
	                    <div class="form-group col-xs-12 text-center">
	                        <input type="image" id="save2" src="imagenes/save.png" title="Editar Atributo">
	                    </div>                                     	         
	                </div>
	            </div><!--End panel -->              
	    	</div><!-- End Dialog -->
	    </div><!-- end Modal -->
	    
	    <!-- Modal Nuevo Aspecto -->
	    <div id="nuevoAspecto" class="modal fade" role="dialog" tabindex='-1'>
	    	<div class="modal-dialog">
	            <div class="panel panel-primary luminoso text-center">
	                <button type="button" class="close" data-dismiss="modal">&times;</button>
	                <div class="panel-heading">
	                    <h4 class="panel-title">Nuevo Aspecto a Evaluar</h4>                    
	                </div>                
	                <div class="panel-body">
	                	<form id="nuevoe">                 
	                    <div class="form-group col-xs-12 col-md-8 col-md-offset-2">
	                        <label for="atributoe">Atributo</label>
	                       	<select id="atributoe" class="form-control validar">	                       		               
	                        </select>
	                    </div>                   
	                    <div class="form-group col-xs-12 col-md-8 col-md-offset-2">
	                        <label for="descripcione">Descripción</label>
	                        <input type="text" name="descripcione" id="descripcione" class="form-control text-uppercase uncopypaste text-center validar">
	                    </div>                                       
	                    </form> 
	                    <div class="form-group col-xs-12 text-center">
	                        <input type="image" id="savee" src="imagenes/save.png" title="Guardar Atributo">
	                    </div>                                     	         
	                </div>
	            </div><!--End panel -->              
	    	</div><!-- End Dialog -->
	    </div><!-- end Modal -->
	    
	    <!-- Modal Editar Aspecto -->
	    <div id="editarAspecto" class="modal fade" role="dialog" tabindex='-1'>
	    	<div class="modal-dialog">
	            <div class="panel panel-primary luminoso text-center">
	                <button type="button" class="close" data-dismiss="modal">&times;</button>
	                <div class="panel-heading">
	                    <h4 class="panel-title">Editar Aspecto</h4>                    
	                </div>                
	                <div class="panel-body">
	                	<form id="editare">                 
	                    <div class="form-group col-xs-12 col-md-8 col-md-offset-2">
	                        <label for="numeroa">Id</label>
	                        <input type="text" name="numeroa" id="numeroa" class="form-control text-center" disabled>
	                    </div>
	                    <div class="form-group col-xs-12 col-md-8 col-md-offset-2">
	                        <label for="atributoe2">Atributo</label>
	                       	<select id="atributoe2" class="form-control validar">
	                       		<option>Seleccionar...</option>
	                        	<?php                    
				                    if (!is_null($atributos)){
				               			foreach ($atributos as $key => $value){
				               				echo '<option value="'.$value['id'].'">'.$value['descripcion'].'</option>';
				               			}
				               		}
				                ?>                        
	                        </select>
	                    </div>                    
	                    <div class="form-group col-xs-12 col-md-8 col-md-offset-2">
	                        <label for="descripcione2">Descripción</label>
	                        <input type="text" name="descripcione2" id="descripcione2" class="form-control text-uppercase text-center validar">
	                    </div>					
	                    </form> 
	                    <div class="form-group col-xs-12 text-center">
	                        <input type="image" id="savee2" src="imagenes/save.png" title="Guardar Atributo">
	                    </div>                                     	         
	                </div>
	            </div><!--End panel -->              
	    	</div><!-- End Dialog -->
	    </div><!-- end Modal -->   
	    
	    <!-- Modal Nueva Situación -->
	    <div id="nuevaSituacion" class="modal fade" role="dialog" tabindex='-1'>
	    	<div class="modal-dialog">
	            <div class="panel panel-primary luminoso text-center">
	                <button type="button" class="close" data-dismiss="modal">&times;</button>
	                <div class="panel-heading">
	                    <h4 class="panel-title">Nueva Situación</h4>                    
	                </div>                
	                <div class="panel-body">
	                	<form id="nuevasi">                 
	                    <div class="form-group col-xs-12 col-md-8 col-md-offset-2">
	                        <label for="atributosi">Atributo</label>
	                       	<select id="atributosi" class="form-control validar">
	                        	<?php
									$atributos = mysql_query("SELECT id, descripcion FROM call_evaluacion_atributo WHERE estatus = '1'");
										if (mysql_num_rows($atributos)){
											echo '<option>Seleccionar...</option>';
											while ($row = mysql_fetch_array($atributos)){
												echo '<option value="'.$row['id'].'">'.utf8_encode($row['descripcion']).'</option>';
											}//End while
										}//End if						
								?>                        
	                        </select>
	                    </div>
	                    <div class="form-group col-xs-12 col-md-8 col-md-offset-2">
	                        <label for="aspectosi">Aspecto a Evaluar</label>
	                       	<select id="aspectosi" class="form-control validar">
	                        	<option>Seleccionar...</option>                        	                  
	                        </select>
	                    </div>                            
	                    <div class="form-group col-xs-12 col-md-8 col-md-offset-2">
	                        <label for="descripcions">Descripción</label>
	                        <input type="text" name="descripcionsi" id="descripcionsi" class="form-control text-uppercase text-center validar">
	                    </div>
	                    <div class="form-group col-xs-12 col-md-8 col-md-offset-2">
	                        <label for="gruposi">Grupo</label>
	                        <input type="text" name="gruposi" id="gruposi" class="form-control text-center">
	                    </div>                    
	                    </form> 
	                    <div class="form-group col-xs-12 text-center">
	                        <input type="image" id="savesi" src="imagenes/save.png" title="Guardar Situación">
	                    </div>                                     	         
	                </div>
	            </div><!--End panel -->              
	    	</div><!-- End Dialog -->
	    </div><!-- end Modal -->
	    
	    <!-- Modal Editar Situación -->
	    <div id="editarSituacion" class="modal fade" role="dialog" tabindex='-1'>
	    	<div class="modal-dialog">
	            <div class="panel panel-primary luminoso text-center">
	                <button type="button" class="close" data-dismiss="modal">&times;</button>
	                <div class="panel-heading">
	                    <h4 class="panel-title">Editar Situación</h4>                    
	                </div>                
	                <div class="panel-body">
	                	<form id="editarsi">                 
	                    <div class="form-group col-xs-12 col-md-8 col-md-offset-2">
	                        <label for="numerosi">Id</label>
	                        <input type="text" name="numerosi" id="numerosi" class="form-control text-center" disabled>
	                    </div>
	                    <div class="form-group col-xs-12 col-md-8 col-md-offset-2">
	                        <label for="atributosi2">Atributo</label>
	                       	<select id="atributosi2" class="form-control validar">
	                        	<?php
									$atributos = mysql_query("SELECT id, descripcion FROM call_evaluacion_atributo WHERE estatus = '1'");
										if (mysql_num_rows($atributos)){
											echo '<option>Seleccionar...</option>';
											while ($row = mysql_fetch_array($atributos)){
												echo '<option value="'.$row['id'].'">'.utf8_encode($row['descripcion']).'</option>';
											}//End while
										}//End if						
								?>                        
	                        </select>
	                    </div>
	                    <div class="form-group col-xs-12 col-md-8 col-md-offset-2">
	                        <label for="aspectosi2">Aspecto a Evaluar</label>
	                       	<select id="aspectosi2" class="form-control validar">
	                        	<option>Seleccionar...</option>                        	                  
	                        </select>
	                    </div>                 
	                    <div class="form-group col-xs-12 col-md-8 col-md-offset-2">
	                        <label for="descripcionsi2">Descripción</label>
	                        <input type="text" name="descripcionsi2" id="descripcionsi2" class="form-control text-uppercase text-center validar">
	                    </div>
	                    <div class="form-group col-xs-12 col-md-8 col-md-offset-2">
	                        <label for="gruposi2">Grupo</label>
	                        <input type="text" name="gruposi2" id="gruposi2" class="form-control text-center">
	                    </div> 				
	                    </form> 
	                    <div class="form-group col-xs-12 text-center">
	                        <input type="image" id="savesi2" src="imagenes/save.png" title="Guardar Atributo">
	                    </div>                                     	         
	                </div>
	            </div><!--End panel -->              
	    	</div><!-- End Dialog -->
	    </div><!-- end Modal -->

	    <!-- Modal Editar Formulario -->
	    <div id="editarFormulario" class="modal fade" role="dialog" tabindex='-1'>
	    	<div class="modal-dialog">
	            <div class="panel panel-primary luminoso text-center" style="width:150%;margin-left: -25%;">
	                <button type="button" class="close" data-dismiss="modal">&times;</button>
	                <div class="panel-heading">
	                    <h4 class="panel-title">Editar Formulario</h4>               
	                </div>

	                <div class="panel-body">  
	                <form id="editarform">             
						<div class="col-xs-12">	                    
		                    <table id="tforms" class="table-condensed table-striped table-bordered table-hover" cellspacing="0" style="width:100%;">
		                    	<thead>
			                        <tr>
			                        	<th><center>Atributo</center></th>
			                            <th><center>Aspecto</center></th>
			                            <th><center>Grupo</center></th>
			                            <th><center>Situación</center></th>
			                            <th><center><input name="select_all" value="1" type="checkbox" id="all" checked></input></center></th>
			                            <th><center>Porcentaje</center></th> 
			                        </tr>
			                    </thead>	                   		
		                   		<tbody id="selected">
		                   		<?php
		                   		if(isset($_SESSION['user'])){
			                   		$sql = "SELECT call_evaluacion_situacion.id as idSituacion, call_evaluacion_atributo.descripcion as descripcionAtributo, call_evaluacion_aspecto.descripcion as descripcionAspecto, call_evaluacion_situacion.grupo as situacionGrupo, call_evaluacion_situacion.descripcion as descripcionSituacion FROM call_evaluacion_situacion INNER JOIN call_evaluacion_aspecto ON call_evaluacion_situacion.id_aspecto = call_evaluacion_aspecto.id INNER JOIN call_evaluacion_atributo ON call_evaluacion_aspecto.id_atributo = call_evaluacion_atributo.id ORDER BY call_evaluacion_atributo.id, descripcionAspecto,  descripcionSituacion ASC";
			                   		
			                   		$ver=mysql_query($sql);		
			                   		$data = array();
			                   		$option = '';
			                   		$cont = 0;
			                   		for($i = 100 ; $i >= 1 ; $i-- ){
			                   			$option.= '<option>'.$i.'</option>';
			                   		}//End for

			                   		while($lista=mysql_fetch_array($ver)){
			                   			$idSituacion = $lista[0];
										$descripcionAtributo =  utf8_encode($lista[1]);	
										$descripcionAspecto =  utf8_encode($lista[2]);
										$situacionGrupo =  utf8_encode($lista[3]);							
										$descripcionSituacion = utf8_encode($lista[4]);
										$check = '<input type="checkbox" value = "'.$idSituacion.'" class = "item" checked>';
										$porc = '<select class="form-control" id = "'.$idSituacion.'">'.$option.'</select>';
										$data[] = array($descripcionAtributo, $descripcionAspecto, $situacionGrupo, $descripcionSituacion, $check, $porc);

										echo ' 	<tr>
													<td>'.$descripcionAtributo.'</td>
													<td>'.$descripcionAspecto.'</td>
													<td>'.$$situacionGrup.'</td>
													<td>'.$descripcionSituacion	.'</td>
													<td>'.$check.'</td>
													<td>'.$porc.'</td>
												</tr>
										';
										$cont = $cont + 1;

									}//End While
									
								    //Mostramos los resultados
									//$results = array("aaData"=>$data);
									//echo json_encode($results);
									
									mysql_close($conexion);
									}else{
										header("location:../index.php?error=ingreso");
									}
								?>
		                   		      
		                    	</tbody>                	
		                    </table>
	                    </div>
	                    <div class="col-xs-12 col-md-8 col-lg-4 col-md-offset-2 col-lg-offset-4 text-center">
	                    <br>
							<label for="nombre">Nombre</label>
							<input type="text" id="nombre" class="form-control"></input>
						</div>
					</form>
						<div class="col-xs-12 col-md-8 col-lg-4 col-md-offset-2 col-lg-offset-4 text-center">
							<input type="image" id="saveform2" src="imagenes/save.png" title="Guadar Formulario"><!--
							<input type="image" id="cancelform" src="imagenes/close.png" title="Cancelar">-->
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