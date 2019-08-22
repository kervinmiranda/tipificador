<?php
/*************************************************************************************************************************
													SISTEMA GEBNET
**************************************************************************************************************************/
include_once 'include/conexion.php';
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
					accion = 'nuevo';
					descripcion = $('#descripcion').val();
					$.post( "include/guardar_atributo.php", {accion:accion, descripcion:descripcion}, function(data){							
					})
					.done(function(data) {								
						switch (data){
							case 'repetido':
								$('#mensajes').prepend('<div class="alert alert-warning text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Info!</strong> El Atributo ya Se Encuentra Registrado, Verifique e Intente Nuevamente</div>');
							break;							
							case '1':
								$('#mensajes').prepend('<div class="alert alert-success text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Exito!</strong> Atributo Guardado Correctamente</div>');
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
		var id = $(this).attr('id');
		$.post( "include/buscar_atributo.php", {id:id}, function(data){							
		})//End function
		.done(function(data){								
		 	var obj = jQuery.parseJSON(data);
				$('#numero').val(id);
				$('#descripcion2').val(descripcion = obj.descripcion).parent().removeClass('has-error has-success');
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
					accion = 'editar';
					id = $('#numero').val();
					descripcion = $('#descripcion2').val();
					$.post( "include/guardar_atributo.php", {accion:accion, id:id, descripcion:descripcion}, function(data){							
					})
					.done(function(data) {								
						switch (data){
							case 'repetido':
								$('#mensajes').prepend('<div class="alert alert-warning text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Info!</strong> El Atributo ya Se Encuentra Registrado, Verifique e Intente Nuevamente</div>');
							break;							
							case '1':
								$('#mensajes').prepend('<div class="alert alert-success text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Exito!</strong> Atributo Editardo Correctamente</div>');						
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
		var accion = "estatus";
		var id = $(this).attr('id');
		bootbox.confirm('¿Seguro que desea el cambiar el Estatus del Atributo?', function(result){
			if (result == true){
				$.post( "include/guardar_atributo.php", {accion:accion, id:id}, function(data){							
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
					accion = 'nuevo';
					atributo = $('#atributoe').val();								
					descripcion = $('#descripcione').val();					
					$.post( "include/guardar_aspecto.php", {accion:accion, atributo:atributo, descripcion:descripcion}, function(data){							
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
		$.post( "include/buscar_aspecto.php", {id:id}, function(data){							
		})//End function
		.done(function(data){								
		 	var obj = jQuery.parseJSON(data);
				$('#numeroa').val(id);
				$('#atributoe2').val(obj.id_atributo).parent().removeClass('has-error has-success');				
				$('#descripcione2').val(obj.descripcion).parent().removeClass('has-error has-success');				
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
					accion = 'editar';					
					atributo = $('#atributoe2').val();							
					descripcion = $('#descripcione2').val();					
					$.post( "include/guardar_aspecto.php", {accion:accion, id:id, atributo:atributo, descripcion:descripcion}, function(data){
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
				$.post( "include/guardar_aspecto.php", {accion:accion, id:id}, function(data){							
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


// $("#").click(function updateDataTableSelectAllCtrl(table){
//    var $table             = table.table().node();
//    var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
//    var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
//    var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);

//    // If none of the checkboxes are checked
//    if($chkbox_checked.length === 0){
//       chkbox_select_all.checked = false;
//       if('indeterminate' in chkbox_select_all){
//          chkbox_select_all.indeterminate = false;
//       }

//    // If all of the checkboxes are checked
//    } else if ($chkbox_checked.length === $chkbox_all.length){
//       chkbox_select_all.checked = true;
//       if('indeterminate' in chkbox_select_all){
//          chkbox_select_all.indeterminate = false;
//       }

//    // If some of the checkboxes are checked
//    } else {
//       chkbox_select_all.checked = true;
//       if('indeterminate' in chkbox_select_all){
//          chkbox_select_all.indeterminate = true;
//       }
//    }
// });


// $(document).ready(function (){
//    // Array holding selected row IDs
//    var rows_selected = [];
//    var table = $('#tforms').DataTable({
//      'ajax': {
// //         'url': '/lab/articles/jquery-datatables-checkboxes/ids-arrays.txt' 
// 			"url": "include/guardar_form.php",
// 			"data": {                       
// 				formulario:formulario             
// 				},
// 			"type": 'POST'
//       },
//       'columnDefs': [{
//          'targets': 0,
//          'searchable': false,
//          'orderable': false,
//          'width': '1%',
//          'className': 'dt-body-center',
//          'render': function (data, type, full, meta){
//              return '<input type="checkbox">';
//          }
//       }],
//       'order': [[1, 'asc']],
//       'rowCallback': function(row, data, dataIndex){
//          // Get row ID
//          var rowId = data[0];

//          // If row ID is in the list of selected row IDs
//          if($.inArray(rowId, rows_selected) !== -1){
//             $(row).find('input[type="checkbox"]').prop('checked', true);
//             $(row).addClass('selected');
//          }
//       }
//    });

//    // Handle click on checkbox
//    $('#tforms tbody').on('click', 'input[type="checkbox"]', function(e){
//       var $row = $(this).closest('tr');

//       // Get row data
//       var data = table.row($row).data();

//       // Get row ID
//       var rowId = data[0];

//       // Determine whether row ID is in the list of selected row IDs 
//       var index = $.inArray(rowId, rows_selected);

//       // If checkbox is checked and row ID is not in list of selected row IDs
//       if(this.checked && index === -1){
//          rows_selected.push(rowId);

//       // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
//       } else if (!this.checked && index !== -1){
//          rows_selected.splice(index, 1);
//       }

//       if(this.checked){
//          $row.addClass('selected');
//       } else {
//          $row.removeClass('selected');
//       }

//       // Update state of "Select all" control
//       updateDataTableSelectAllCtrl(table);

//       // Prevent click event from propagating to parent
//       e.stopPropagation();
//    });

//    // Handle click on table cells with checkboxes
//    $('#tforms').on('click', 'tbody td, thead th:first-child', function(e){
//       $(this).parent().find('input[type="checkbox"]').trigger('click');
//    });

//    // Handle click on "Select all" control
//    $('thead input[name="select_all"]', table.table().container()).on('click', function(e){
//       if(this.checked){
//          $('#tforms tbody input[type="checkbox"]:not(:checked)').trigger('click');
//       } else {
//          $('#tforms tbody input[type="checkbox"]:checked').trigger('click');
//       }

//       // Prevent click event from propagating to parent
//       e.stopPropagation();
//    });

//    // Handle table draw event
//    table.on('draw', function(){
//       // Update state of "Select all" control
//       updateDataTableSelectAllCtrl(table);
//    });

//    // Handle form submission event 
//    $('#frm-tforms').on('submit', function(e){
//       var form = this;
      
//       // Iterate over all selected checkboxes
//       $.each(rows_selected, function(index, rowId){
//          // Create a hidden element 
//          $(form).append(
//              $('<input>')
//                 .attr('type', 'hidden')
//                 .attr('name', 'id[]')
//                 .val(rowId)
//          );
//       });
//    });

// });





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
		"language": {
			"sProcessing":     "Procesando...",
			"sLengthMenu":     "Mostrar _MENU_ registros",
			"sZeroRecords":    "No se encontraron resultados",
			"sEmptyTable":     "Ningún dato disponible en esta tabla",
			"sInfo":           "Mostrando del _START_ al _END_ de un total de _TOTAL_ registros",
			"sInfoEmpty":      "Mostrando del 0 al 0 de un total de 0 registros",
			"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix":    "",
			"sSearch":         "Buscar:",
			"sUrl":            "",
			"sInfoThousands":  ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
				"sFirst":    "Primero",
				"sLast":     "Último",
				"sNext":     "Siguiente",
				"sPrevious": "Anterior"
			},
			"oAria": {
				"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
			}	
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



//Mostrar Editar Formulario
// 	$('#editarFormulario').click(function(){
// 		var formulario = 'editar';
// 		$('#tforms').dataTable().fnDestroy();
// 		$('#tforms').dataTable({
// 		"ajax": {
// 			"url": "include/consultaf2.php",
// 			"data": {                       
// 				formulario:formulario             
// 				},
// 			"type": 'POST'
// 		},
// 		"sPaginationType": "full_numbers",
// 		"language": {
// 			"sProcessing":     "Procesando...",
// 			"sLengthMenu":     "Mostrar _MENU_ registros",
// 			"sZeroRecords":    "No se encontraron resultados",
// 			"sEmptyTable":     "Ningún dato disponible en esta tabla",
// 			"sInfo":           "Mostrando del _START_ al _END_ de un total de _TOTAL_ registros",
// 			"sInfoEmpty":      "Mostrando del 0 al 0 de un total de 0 registros",
// 			"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
// 			"sInfoPostFix":    "",
// 			"sSearch":         "Buscar:",
// 			"sUrl":            "",
// 			"sInfoThousands":  ",",
// 			"sLoadingRecords": "Cargando...",
// 			"oPaginate": {
// 				"sFirst":    "Primero",
// 				"sLast":     "Último",
// 				"sNext":     "Siguiente",
// 				"sPrevious": "Anterior"
// 			},
// 			"oAria": {
// 				"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
// 				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
// 			}	
// 		},
// 		"aaSorting": [[ 0, "asc" ],[1, "asc"],[2, "asc"], [3, "asc"]],
// 		aLengthMenu: [[10,50,100],[10,50,100]],
// 			"iDisplayLength": 100,
// 		"bPaginate": false,
// 	});	
	
// 		$('#editarFormulario').removeClass('oculto');
// 		$('#all').prop('checked', true);
// //		$('#boton4').addClass('oculto');		// Comentado para evitar que el icono de + se esconda luego de cerrar el modal de nuevo formulario!
// 	});//End Function






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

     //       			$idSituacion = $lista[0];
					// $descripcionAtributo =  utf8_encode($lista[1]);	
					// $descripcionAspecto =  utf8_encode($lista[2]);
					// $situacionGrupo =  utf8_encode($lista[3]);							
					// $descripcionSituacion = utf8_encode($lista[4]);
					// $check = '<input type="checkbox" value = "'.$idSituacion.'" class = "item" checked>';
					// $porc = '<select class="form-control" id = "'.$idSituacion.'">'.$option.'</select>';
					// $data[] = array($descripcionAtributo, $descripcionAspecto, $situacionGrupo, $descripcionSituacion, $check, $porc);


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


//Cancelar Nuevo Formulario
	// $('#cancelform').click(function(){
	// 	$('#nuevoFormulario').addClass('oculto');
	// 	$('#boton4').removeClass('oculto');
	// });

//Convertir la tabla en Datatable
	$('#listaAtributo').dataTable({
		"ajax": "include/consultaa.php",
		"sPaginationType": "full_numbers",
		"language": {
			"sProcessing":     "Procesando...",
			"sLengthMenu":     "Mostrar _MENU_ registros",
			"sZeroRecords":    "No se encontraron resultados",
			"sEmptyTable":     "Ningún dato disponible en esta tabla",
			"sInfo":           "Mostrando del _START_ al _END_ de un total de _TOTAL_ registros",
			"sInfoEmpty":      "Mostrando del 0 al 0 de un total de 0 registros",
			"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix":    "",
			"sSearch":         "Buscar:",
			"sUrl":            "",
			"sInfoThousands":  ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
				"sFirst":    "Primero",
				"sLast":     "Último",
				"sNext":     "Siguiente",
				"sPrevious": "Anterior"
			},
			"oAria": {
				"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
			}		
		},
		aLengthMenu: [[10,50,100],[10,50,100]],
			"iDisplayLength": 10
	});
	
	$('#listaAspecto').dataTable({
		"ajax": "include/consultaasp.php",
		"sPaginationType": "full_numbers",
		"language": {
			"sProcessing":     "Procesando...",
			"sLengthMenu":     "Mostrar _MENU_ registros",
			"sZeroRecords":    "No se encontraron resultados",
			"sEmptyTable":     "Ningún dato disponible en esta tabla",
			"sInfo":           "Mostrando del _START_ al _END_ de un total de _TOTAL_ registros",
			"sInfoEmpty":      "Mostrando del 0 al 0 de un total de 0 registros",
			"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix":    "",
			"sSearch":         "Buscar:",
			"sUrl":            "",
			"sInfoThousands":  ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
				"sFirst":    "Primero",
				"sLast":     "Último",
				"sNext":     "Siguiente",
				"sPrevious": "Anterior"
			},
			"oAria": {
				"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
			}		
		},
		aLengthMenu: [[10,50,100],[10,50,100]],
			"iDisplayLength": 10
	});
	
	$('#listaSituacion').dataTable({
		"ajax": "include/consultasi.php",
		"sPaginationType": "full_numbers",
		"language": {
			"sProcessing":     "Procesando...",
			"sLengthMenu":     "Mostrar _MENU_ registros",
			"sZeroRecords":    "No se encontraron resultados",
			"sEmptyTable":     "Ningún dato disponible en esta tabla",
			"sInfo":           "Mostrando del _START_ al _END_ de un total de _TOTAL_ registros",
			"sInfoEmpty":      "Mostrando del 0 al 0 de un total de 0 registros",
			"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix":    "",
			"sSearch":         "Buscar:",
			"sUrl":            "",
			"sInfoThousands":  ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
				"sFirst":    "Primero",
				"sLast":     "Último",
				"sNext":     "Siguiente",
				"sPrevious": "Anterior"
			},
			"oAria": {
				"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
			}		
		},
		aLengthMenu: [[10,50,100],[10,50,100]],
			"iDisplayLength": 10
	});	

	$('#listaFormulario').dataTable({
		"ajax": "include/consultaf.php",
		"sPaginationType": "full_numbers",
		"language": {
			"sProcessing":     "Procesando...",
			"sLengthMenu":     "Mostrar _MENU_ registros",
			"sZeroRecords":    "No se encontraron resultados",
			"sEmptyTable":     "Ningún dato disponible en esta tabla",
			"sInfo":           "Mostrando del _START_ al _END_ de un total de _TOTAL_ registros",
			"sInfoEmpty":      "Mostrando del 0 al 0 de un total de 0 registros",
			"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix":    "",
			"sSearch":         "Buscar:",
			"sUrl":            "",
			"sInfoThousands":  ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
				"sFirst":    "Primero",
				"sLast":     "Último",
				"sNext":     "Siguiente",
				"sPrevious": "Anterior"
			},
			"oAria": {
				"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
			}		
		},
		aLengthMenu: [[10,50,100],[10,50,100]],
			"iDisplayLength": 10
	});




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
	                            <th>Aspecto</th>                                                       
	                            <th>Atributo</th>
	                            <th>Estatus</th>
	                            <th>Comandos</th>             
	                        </tr>
	                    </thead>
	                    <tfoot>        
	                        <tr>
	                            <th>N°</th>
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
	                        <label for="descripcione">Descripción</label>
	                        <input type="text" name="descripcione" id="descripcione" class="form-control uncopypaste text-center validar">
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
	                        <label for="descripcione2">Descripción</label>
	                        <input type="text" name="descripcione2" id="descripcione2" class="form-control text-center validar">
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
	                        <input type="text" name="descripcionsi" id="descripcionsi" class="form-control text-center validar">
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
	                        <input type="text" name="descripcionsi2" id="descripcionsi2" class="form-control text-center validar">
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
							<input type="image" id="saveform2" src="imagenes/save.png" title="Guadar Formulario">
<!--
							<input type="image" id="cancelform" src="imagenes/close.png" title="Cancelar">
-->
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
mysql_close($conexion);
}else{
	header("location:index.php?alerta=salir");
}
?>