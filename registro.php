<?php
/*************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
include_once 'include/pdo/pais.php';
include_once 'include/pdo/tipificacion.php';
include_once 'include/fecha.php';
include_once 'include/variables.php';
if(isset($_SESSION['user'])){
	$paises = getCountries();
	$motivos = getMotives();	
	$submotivos = getSubMotives();
?>
<?php echo $doctype?>
<!-- Achivos CSS -->
<link rel="stylesheet" href="css/jquery-ui.css">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="../DataTables/css/dataTables.bootstrap.css">
<link rel="stylesheet" href="../DataTables/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="../bootstrap/css/bootstrap-submenu.css">
<link rel="stylesheet" href="css/call.css">

<!-- Archivos JavaScript -->
<script src="../js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="../bootstrap/js/bootstrap.js"></script>
<script src="../DataTables/js/jquery.dataTables.js"></script>
<script src="../DataTables/js/dataTables.bootstrap.js"></script>
<script src="../DataTables/js/dataTables.responsive.min.js"></script>
<script src="../bootstrap/js/bootstrap-submenu.js"></script>
<script src="../bootstrap/js/bootbox.min.js"></script>
<script src="../js/jquery.numeric.js"></script>
<script src="js/libreriajs.js"></script>
<script type="text/javascript">

$(document).ready(function(){
//Activar Menú
	$("#menu3").attr('class','active');

//Parse Array de Submotivos
var obj = jQuery.parseJSON('<?php echo json_encode($submotivos)?>');

//Función para buscar los submotivos despues de seleccionar un motivo
	$('#motivo').change(function () {		
		$('#submotivo').empty();
		$('#submotivo').append("<option>Seleccionar...</option>")	
		$('#motivo option:selected').each(function () {
			elegido=$(this).val();			
        	//Buscamos los submotivos
        	$.each(obj, function(i,item){
        		if (elegido == obj[i].principal){
        			$('#submotivo').append("<option>"+ obj[i].secundaria +"</option>")
        		}				
			})
        });
   });
  
//Validar entrada de Codigo LIB
	$('#socialuser').keypress(function(e){
		validarKey(e);
	});
  
//Función para Guardar Tipificación 
   $('#boton1').click(function (){
	 var contador = 0;
		var d = new Date();
		var dia = new Array(7);
		dia[0] = "Domingo";
		dia[1] = "Lunes";
		dia[2] = "Martes";
		dia[3] = "Miercoles";
		dia[4] = "Jueves";
		dia[5] = "Viernes";
		dia[6] = "Sabado";

		var mes= d.getMonth()+1;
		var dia_aux = 0;

	  	$("#formulario input").filter('.validar').each(function (index) { 
        	if ($(this).val() == ''){
				$(this).parent().addClass('has-error');
				contador++;							
			}else{
				$(this).parent().removeClass('has-error has-warning').addClass('has-success');
			}//End if	
     	});//End each
		
		$("#formulario select").filter('.validar').each(function (index) { 
     		if ($("option:selected", this).prop('index') == 0){
				$(this).parent().addClass('has-error');
				contador++;							
			}else{
				$(this).parent().removeClass('has-error has-warning').addClass('has-success');
			}//End if
		});//End each
		
		$("#formulario textarea").filter('.validar').each(function (index) { 
        	if ($(this).val() == ''){
				$(this).parent().addClass('has-error');
				contador++;							
			}else{
				$(this).parent().removeClass('has-error has-warning').addClass('has-success');
			}//End if	
     	});//End each
		
		if (contador < 1 ){		
			bootbox.confirm('¿Seguro que Desea Cargar el Registro?', function(result){
				if (result == true){		
					pais = $('#pais').val();
					motivo = $('#motivo').val();
					submotivo = $('#submotivo').val();
					codigo = $('#codigo').val();
					guia = $('#guia').val();
					socialuser = $('#socialuser').val() + '|' + $('#red').val();
					comentario = $('#comentario').val();

					dia_aux = d.getDay() + 2;	// parche por diferencia de hora server
					fecha = dia_aux+'/'+mes+'/'+d.getFullYear()+' '+d.getHours()+':'+d.getUTCHours()+':'+d.getMinutes()+':'+d.getSeconds();

					if ($('#incidencia').is(':checked')){
						funcion = "newRegisterIncidence";
					}else{
						funcion = "newRegister";
					}

					$.post('include/pdo/registro.php', {function:funcion, pais:pais, fecha:fecha, motivo:motivo, submotivo:submotivo, codigo:codigo, guia:guia, socialuser:socialuser, comentario:comentario}, function(data){
							id = data;
						if (id == 0){
							$('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error al incluir los datos, Intente más tarde</div>');
						}else{
							$('#mensajes').prepend('<div class="alert alert-success text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Exito!</strong> Tipificación Ingresada Correctamente con el N°: <strong>'+ id +'</strong></div>');
							$('#formulario input').val('').parent().removeClass('has-error has-success');
							$('#formulario textarea').val('').parent().removeClass('has-error has-success');
							$('#formulario select').prop('selectedIndex',0).parent().removeClass('has-error has-success');

							$('#motivo').change();
						}//End if
					});//End post
				}//End if
			});//End Function bootbox
		}//End if contador == 0
	});//End Function

//Función para buscar los registros de cedulas o lib con el boton de busqueda
	$('#search').click(function(){		
		var value = $('#codigo').val();	
		var tipo = 'lib';
		if (value == ''){
			bootbox.alert('Debe Escribir un Código LIB o Cédula');
			return false;
		}		
		$('#lista > tbody').empty();		
		$.ajax({
			url: 'include/pdo/registro.php',
			type: 'post',
			data: {function:"searchLib", value:value, tipo:tipo},
			dataType: 'json',
			success: function (data) {
				if (data.success) {
					$.each(data, function (index, record) {
						if ($.isNumeric(index)) { 
							var
							fecha = record.fecha;							
							usuario = record.usuario;							
							departamento = record.departamento;							
							motivo = record.motivo;							
							sub_motivo = record.sub_motivo;							
							libced = record.libced;						
							usersocial = record.usersocial;						
							guiatracking = record.guiatracking;											
							var row = $('<tr />');
							$(row).attr('title', 'Comentario: '+ record.comentario);
							$('<td />').appendTo(row).append(fecha);
							$('<td />').appendTo(row).append(usuario);
							$('<td />').appendTo(row).append(departamento);
							$('<td />').appendTo(row).append(motivo);
							$('<td />').appendTo(row).append(sub_motivo);						
							$('<td />').appendTo(row).append(libced);
							$('<td />').appendTo(row).append(usersocial);
							$('<td />').appendTo(row).append(guiatracking)					
							row.appendTo('#lista');
						}//End if $.isNumeric(index)
					})//End each			
				}//End if data.success		
			},//End Function	data
			error: function() {
				var row = $('<tr />');
					var td = $('<td />').attr('colspan',8).attr('style','text-align:center').append('No hay resultados en la busqueda');
					td.appendTo(row)	
				row.appendTo('#lista');
			},
		});//End Ajax	
		//Mostrar la tabla de los resultados buscados
		$('#reporte').modal('toggle');
	});//End Function

//Función para buscar los registros de Guía o Tracking con el boton de Búsqueda
	$('#search2').click(function(){		
		var value = $('#guia').val();
		var tipo = 'guia';
		if (value == ""){
			bootbox.alert ('Debe Escribir la Guía o Traking');
			return false;
		}		
		$('#lista > tbody').empty();	
		$.ajax({
			url: 'include/pdo/registro.php',
			type: 'post',
			data: {function:"searchLib", value:value, tipo:tipo},
			dataType: 'json',
			success: function (data) {
				if (data.success) {
					$.each(data, function (index, record) {
						if ($.isNumeric(index)) { 
							var
							fecha = record.fecha;							
							usuario = record.usuario;							
							departamento = record.departamento;							
							motivo = record.motivo;							
							sub_motivo = record.sub_motivo;							
							libced = record.libced;						
							usersocial = record.usersocial;						
							guiatracking = record.guiatracking;											
							var row = $('<tr />');
							$(row).attr('title', 'Comentario: '+record.comentario);
							$('<td />').appendTo(row).append(fecha);
							$('<td />').appendTo(row).append(usuario);
							$('<td />').appendTo(row).append(departamento);
							$('<td />').appendTo(row).append(motivo);
							$('<td />').appendTo(row).append(sub_motivo);						
							$('<td />').appendTo(row).append(libced);
							$('<td />').appendTo(row).append(usersocial);
							$('<td />').appendTo(row).append(guiatracking)					
							row.appendTo('#lista');
						}//End if $.isNumeric(index)
					})//End each			
				}//End if data.success		
			},//End Function	data
			error: function() {
				var row = $('<tr />');
					var td = $('<td />').attr('colspan',8).attr('style','text-align:center').append('No hay resultados en la busqueda');
					td.appendTo(row)	
				row.appendTo('#lista');
			},
		});//End Ajax	
		//Mostrar la tabla de los resultados buscados
		$('#reporte').modal('toggle');
	});//End Function
	    
//Función para buscar el autocompletado del código o códula
	$(function(){
		$('#codigo').autocomplete({
			minLength: 5,
			source: function( request, response ) {
			   	// Fetch data
			   	$.ajax({
			    	url: "include/pdo/registro.php",
			    	type: 'post',
			    	dataType: "json",
			    	data: {
			     		function:"autocompleteCode",
			     		codigo: request.term
			    	},
			    	success: function( data ) {
			     		response(data);
			    	}
			   	});
		  	},
			select : function(event, ui){
				$('#resultados').slideUp('slow', function(){
					$('#resultados').html(
				 );
		});
		$('#resultados').slideDown('slow');
		} 
		});
	});
	
//Función para buscar el autocompletado de Guía o Tracking
	$(function(){
		$('#guia').autocomplete({
			minLength: 8,
			minLength: 5,
			source: function( request, response ) {
			   	// Fetch data
			   	$.ajax({
			    	url: "include/pdo/registro.php",
			    	type: 'post',
			    	dataType: "json",
			    	data: {
			     		function:"autocompleteGuide",
			     		guia: request.term
			    	},
			    	success: function( data ) {
			     		response(data);
			    	}
			   	});
		  	},
			select : function(event, ui){
				$('#resultados').slideUp('slow', function(){
					$('#resultados').html(
				 );
		});
		$('#resultados').slideDown('slow');
		} 
		});
	});  

});
</script>
</head>
<body>
	<?php echo $header?>
    <div class="container-fluid contenido">
	<?php echo $menu?>
    <div class="text-center">
    	<h4>Registro de Tipificación</h4>
    </div>
   	
    <div class="row">   
    <div class="col-xs-12 col-md-8 col-md-offset-2">   	
    <div class="panel panel-primary text-center">
    	<div class="panel-heading">
      		<h3 class="panel-title">Formulario de Registro</h3>
    	</div>
    	<div class="panel-body">
       		<form id="formulario">
            <div class="form-group col-xs-12 col-md-6 col-lg-4">				
                <label for="pais">País</label>  
                <select name="pais" id="pais" class="form-control validar">
                <option>Seleccionar...</option>
                <?php                    
                    if (!is_null($paises)){
               			foreach ($paises as $key => $value){
               				echo '<option>'.$value['descripcion'].'</option>';
               			}
               		}
                ?>            
                </select>      	                
            </div>
        	<div class="form-group col-xs-12 col-md-6 col-lg-4">
				<label for="motivo">Motivo de Contacto</label>  
                <select name="motivo" id="motivo" class="form-control validar">
                <option>Seleccionar...</option>
                <?php                    
                    if (!is_null($motivos)){
               			foreach ($motivos as $key => $value){
               				echo '<option>'.$value['principal'].'</option>';
               			}
               		}
                ?>            
                </select>      	                
            </div>
            <div class="form-group col-xs-12 col-md-6 col-lg-4">
				<label for="submotivo">Sub-Motivo</label>
                <select name="submotivo" id="submotivo" class="form-control validar">
                <option>Seleccionar...</option>
                </select>
            </div>
            <div class="form-group col-xs-12 col-md-6 col-lg-4">
            	<label for="codigo">C&oacute;digo LIB o C&eacute;dula</label> 
                <div class="input-group">
                    <input type="text" name="codigo" id="codigo" class="form-control text-uppercase validar" placeholder="VE-XXXXXXX" maxlength="10">
                    <div class="input-group-btn">                    
                    <button class="btn" type="button" data-toggle="tooltip" id="search" title="Consultar"><i class="glyphicon glyphicon-search"></i></button>
                    </div>
                </div>
            </div>      
            <div class="form-group col-xs-12 col-md-6 col-lg-4">
               	<label class="col-xs-12" for="guia">Gu&iacute;a o Tracking</label>
                <div class="input-group">
	                <input type="text" name="guia" id="guia" class="form-control text-uppercase validar" placeholder="WR01-XXXXXXXX" maxlength="35">
	                <div class="input-group-btn">                    
	                    <button class="btn" type="button" data-toggle="tooltip" id="search2" title="Consultar"><i class="glyphicon glyphicon-search"></i>
	                    </button>
	                </div>
                </div>
           	</div>
            <?php
			if ($_SESSION['departamento'] == 'REDES SOCIALES'){
			?>		
            <div class="form-group col-xs-12 col-md-6 col-lg-4">
				<div class="col-xs-12">
                	<label for="usersocial">Usuario Red Social</label>
               	</div>
                <div class="col-xs-6 agrupado">
                	<input type="text" name="socialuser" id="socialuser" class="form-control text-lowercase validar">
               	</div>
                <div class="col-xs-6 agrupado">
                    <select id="red" class="form-control validar">
                        <option>Seleccionar...</option>
                        <option>Correo</option>
                        <option>Facebook Chat</option>
                        <option>Facebook Post</option>
                        <option>Instagram</option>
                        <option>Llamada</option>
                        <option>Twitter DM</option>
                        <option>Twitter Mención</option>                                              
                    </select>            
                </div>
            </div>
			<?php
            }
            ?>
            <div class="form-group col-xs-12">
               	<label for="comentario">Comentario</label>
	      		<textarea name="comentario" id="comentario" class="form-control validar"></textarea>
            </div>
            </form>  
			<div class="form-group col-xs-12">
	            <input type="image" src="imagenes/save.png" name="enviar" id="boton1" data-toggle="tooltip" data-placement="bottom" title="Guardar" class="imagen">
	            <!-- Material unchecked -->
				<div class="form-check">
				    <input type="checkbox" class="form-check-input" id="incidencia">
				    <label class="form-check-label" for="materialUnchecked">Agregar a Incidencias</label>
				</div>
            </div>                        	         
		</div><!--End Pannel Body-->
  	</div><!--End col -->
    </div><!--End Pannel Primary-->
    </div><!--End row -->
    
    <div class="row">
        <div id="mensajes" class="col-xs-12 col-md-8 col-md-offset-2">
        </div><!-- Div para contenido de los Mensajes -->
	</div>
    
    <!-- Modal Editar seleccion de Registros -->
    <div id="reporte" class="modal fade" role="dialog" tabindex='-1'>
    	<div class="modal-dialog modal-lg">
        	<div class="panel panel-primary luminoso text-center">
            	<button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="panel-heading">
                    <h3 class="panel-title">Consulta de Registros Previos</h3>
                </div>
                <div class="panel-body">
                <div class="table-responsive">
                <table id="lista" class="table table-striped table-bordered text-center dt-responsive table-hover nowrap formulario" cellspacing="0" width="100%">      
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Usuario</th>
                                <th>departamento</th>
                                <th>Motivo</th>
                                <th>Sub Motivo</th>
                                <th>LIB o Cédula</th>
                                <th>User Social Media</th>
                                <th>Gu&iacute;a o Tracking</th>   
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                </div><!--End panel -->
            </div><!--End panel -->                                    
    	</div><!-- End Dialog -->
    </div><!-- end Modal -->
    
    </div><!--End Container -->
	 <?php echo $footer?>
</body>
</html>
<?php
}else{
	header("location:../index.php?error=ingreso");
}
?>