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
var id;
var fila;
var fecha;
//Activar Menú
	$("#menu3").attr('class','active');

//Función para buscar Comentario de la Gestión
	$('#lista tbody').on('click', '.link', function(){
		id = ($(this).attr('id'));
			$.post('include/consultacom.php', {id:id}, function(data){	
			$('#registro').html('Registro: ' + id);
			$('#comen').html(data);	
		});//End post		
	});//End Function
	
//Función para buscar los submotivos despues de seleccionar un motivo
	$("#motivo").change(function () {
           $("#motivo option:selected").each(function () {
            elegido=$(this).val();
            $.post("include/buscar_sub2.php", { elegido: elegido }, function(data){
            $("#submotivo").html(data);
            });            
        });
   });

//Función para buscar los submotivos despues de seleccionar un motivo
	$("#motivo2").change(function () {
           $("#motivo2 option:selected").each(function () {
            elegido=$(this).val();
            $.post("include/buscar_sub.php", { elegido: elegido }, function(data){
            $("#submotivo2").html(data);
            });            
        });
   });

//Función para mostar ventana de edición	
	$('#lista tbody').on('click', '.edit', function(){		
		id = ($(this).attr('id'));
		$('#incidencia').html('Incidencia: ' + id);
		fila = $(this).parents().get(1);
		$('#motivo2').val($("td:eq(4)", fila).text());
		$("#submotivo2").append('<option>' + $("td:eq(5)", fila).text()+ '</option>');
		$('#submotivo2').val($("td:eq(5)", fila).text());
		$('#cedlib2').val($("td:eq(6)", fila).text());
		$('#motivo2, #submotivo2, #cedlib2, #comentario').parent().removeClass('has-error has-success');	
		$('#comentario').val('');		
	});
	
//Guardar Edición
	$('#enviar').click(function(){
		if ($('#motivo2 option:selected').index() == 0){
			$('#motivo2').parent().addClass('has-error');					
			return false;				
		}else{
			$('#motivo2').parent().removeClass('has-error').addClass('has-success');
		};
		if ($('#submotivo2 option:selected').index() == 0){
			$('#submotivo2').parent().addClass('has-error');					
			return false;				
		}else{
			$('#submotivo2').parent().removeClass('has-error').addClass('has-success');
		};
		if ($('#cedlib2').val() == ''){
			$('#cedlib2').parent().addClass('has-error');
			$('#cedlib2').attr('placeholder','Campo Obligatorio');			
			return false;				
		}else{
			$('#cedlib2').parent().removeClass('has-error').addClass('has-success');
		};
		if ($('#comentario').val() == ''){
			$('#comentario').parent().addClass('has-error');
			$('#comentario').attr('placeholder','Campo Obligatorio');			
			return false;				
		}else{
			$('#comentario').parent().removeClass('has-error').addClass('has-success');
		};
		bootbox.confirm('¿Seguro que Desea Editar el Registro?', function(result){
			if (result == true){		
				accion = "editar";
				motivo = $("#motivo2").val();
				submotivo = $("#submotivo2").val();
				cedlib = $('#cedlib2').val();
				comentario = $("#comentario").val();
				$.post("include/guardar_registro.php", {accion:accion, id:id, motivo:motivo,submotivo:submotivo, cedlib: cedlib, comentario:comentario}, function(data){
					if (data == 0){
						$('#error').html('<strong>¡Error!</strong> Error al Editar el Registro, Intente más Tarde').fadeIn(1000).fadeOut(10000);
					}else{
					$('#anio').change();							
					$('#mensaje').html('<strong>¡Exito!</strong> Tipificación '+ id + ' Editada Correctamente').fadeIn(1000).fadeOut(10000);					
					}//End if
				});//End post
				$('#editar').modal('toggle');
			}
		});//End Function bootbox	
	});//End Function
	
//Mostrar ventana para exportar a Excel
	$("#filtro").click(function() {
		$('.form-control').parent().removeClass('has-error has-success');
		$('#motivo option:first-child, #submotivo option:first-child, #departamento option:first-child, #usuario option:first-child').attr('selected', 'selected');
		$('#fecha1, #fecha2').val('');				
		$('#reporte').modal('toggle');				
	});
	
//Validar la Exportación a Excel
	$("#botongonep").click(function() {
		if ($('#motivo option:selected').index() == 0){
			$('#motivo').parent().addClass('has-error');					
			return false;				
		}else{
			$('#motivo').parent().removeClass('has-error').addClass('has-success');
		};			
		if ($('#submotivo option:selected').index() == 0){
			$('#submotivo').parent().addClass('has-error');					
			return false;				
		}else{
			$('#submotivo').parent().removeClass('has-error').addClass('has-success');
		};
		if ($('#departamento option:selected').index() == 0){
			$('#departamento').parent().addClass('has-error');					
			return false;				
		}else{
			$('#departamento').parent().removeClass('has-error').addClass('has-success');
		};
		if ($('#usuario option:selected').index() == 0){
			$('#usuario').parent().addClass('has-error');					
			return false;				
		}else{
			$('#usuario').parent().removeClass('has-error').addClass('has-success');
		};
		if ($('#fecha1').val() == ''){
			$('#fecha1').parent().addClass('has-error');
			$('#fecha1').attr('placeholder','Campo Obligatorio');			
			return false;				
		}else{
			$('#fecha1').parent().removeClass('has-error').addClass('has-success');
		};
		if ($('#fecha2').val() == ''){
			$('#fecha2').parent().addClass('has-error');
			$('#fecha2').attr('placeholder','Campo Obligatorio');			
			return false;				
		}else{
			$('#fecha2').parent().removeClass('has-error').addClass('has-success');
		};
		$('#reporteExcel').submit();		
		$('#reporte').modal('toggle');	
	});

//Convertir la tabla en Datatable
	fecha = $('#anio').val() + "-" + $('#mes').val();
	$('#lista').dataTable({
		"ajax": {
    		"url": "include/consulta.php",
    		"data": {                       
                fecha:fecha                
                },
			"type": 'POST'
  		},	
		"sPaginationType": "full_numbers",
		"language": {
			"sProcessing":     "Procesando...",
			"sLengthMenu":     "Mostrar _MENU_ registros",
			"sZeroRecords":    "No se encontraron resultados",
			"sEmptyTable":     "Ningún dato disponible en esta tabla",
			"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
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
		aLengthMenu: [[10,50,100,-1],[10,50,100,'Todo']],
			"iDisplayLength": 10
	});
	
//Función para colocar los Textos a tipo fecha
	$('#fecha1').datepicker({
		dateFormat: 'dd/mm/yy',
		maxDate: 0, minDate:'-5Y',
		onSelect: function(dateText, inst) {
			var minimo = new Date($('#fecha1').datepicker('getDate'));
			var maximo = new Date($('#fecha1').datepicker('getDate'));			
			minimo.setDate(minimo.getDate());
			maximo.setDate(maximo.getDate() + 15);
			$('input#fecha2').datepicker('option', 'minDate', minimo);
			$('input#fecha2').datepicker('option', 'maxDate', maximo);
			}
		});
	$("#fecha2").datepicker({dateFormat: 'dd/mm/yy'});

//Escoger otra fecha o Mes
	$('#anio, #mes').change(function(){
		$('#lista').dataTable().fnDestroy();
		fecha = $('#anio').val() + "-" + $('#mes').val();
	
	$('#lista').dataTable({
		"ajax": {
    		"url": "include/consulta.php",
    		"data": {                       
                fecha:fecha                
                },
			"type": 'POST'
  			},	
			"sPaginationType": "full_numbers",
			"language": {
				"sProcessing":     "Procesando...",
				"sLengthMenu":     "Mostrar _MENU_ registros",
				"sZeroRecords":    "No se encontraron resultados",
				"sEmptyTable":     "Ningún dato disponible en esta tabla",
				"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
				"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
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
			aLengthMenu: [[10,50,100,-1],[10,50,100,'Todo']],
				"iDisplayLength": 10
		});					 				
	});//End Function
});
</script>
</head>
<body>
	<?php echo $header?>
    <div class="container-fluid contenido">
	<?php echo $menu?>
    <div class="text-center">
    	<h4>Registros de Tipificaciones</h4>
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
    <div class="row">
	<div class="col-xs-12 col-sm-3 col-md-4"></div>
    <div class="col-xs-12 col-sm-3 col-md-2" align="center">
    	<label for="mes">Mes</label>
    	<select name="mes" id="mes" class="form-control">
        	<?php
				$mes_actual = date('n');					
				$meses = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio', 'Agosto','Septiembre','Octubre','Noviembre','Diciembre');
				for ($i = 0; $i < sizeof($meses); $i++){					
					if ($mes_actual == $i + 1){
						echo '<option value="'.str_pad(($i + 1), 2, "0", STR_PAD_LEFT).'" selected>'.$meses[$i].'</option>';
					}else{
						echo '<option value="'.str_pad(($i + 1), 2, "0", STR_PAD_LEFT).'">'.$meses[$i].'</option>';
					}//End if				
				}//End For							
			?>                   
        </select>
    </div>
    <div class="col-xs-12 col-sm-3 col-md-2" align="center">
    	<label for="anio">Año</label>
    	<select name="anio" id="anio" class="form-control">
        	<?php
				$anio_actual = date('Y');				
				for($i = $anio_actual; $i >= 2015; $i--){
					echo '<option>'.$i.'</option>';				
				}						
						
			?>
        </select>
    </div>    
    <div class="col-xs-12 col-sm-3 col-md-4"></div>	
	</div><!-- End row -->    

    <div class="row">
    <div class="col-xs-12 table-responsive">
           <table id="lista" class="table table-striped table-bordered table-condensed text-center dt-responsive table-hover nowrap" cellspacing="0" width="100%">  
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Departamento</th>
                <th>Motivo</th>
                <th>Sub Motivo</th>
                <th>LIB o Cédula</th>
                <th>Social Media</th>
                <th>Gu&iacute;a o Tracking</th>
                <?php
                if ($nivel < 2){
                    echo "<th>Editar</th>";
                    }			
                ?>      
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Departamento</th>
                <th>Motivo</th>
                <th>Sub Motivo</th>
                <th>LIB o Cédula</th>
                <th>User Social Media</th>
                <th>Gu&iacute;a o Tracking</th>
                <?php
                if ($nivel < 2){
                    echo "<th>Editar</th>";
                    }			
                ?>        
            </tr>
        </tfoot>   
        <tbody>   
        </tbody>
    </table>
    <?
	  if ($nivel < 3){
	?>
    <div align="center"><img  name="filtro" id="filtro" src="imagenes/excel.png" alt="Exportar a Excel" class="cursor" data-toggle="tooltip" title="Exportar a Excel"/>
   	</div>
    <br>
	<?
		}
	?>
    </div><!-- End col -->
    </div><!-- End row -->
      
    <!-- Modal Reporte a Excel -->
    <div id="observacion" class="modal fade" role="dialog" tabindex='-1'>
    	<div class="modal-dialog modal-lg">
        	<div class="panel panel-primary luminoso text-center">
            	<button type="button" class="close" data-dismiss="modal">&times;</button> 
                <div class="panel-heading">
                    <h3 class="panel-title" id="registro"></h3>
                </div>       
                <div class="panel-body">
                <div class="table-responsive">
                <table id="gestion" class="table table-striped table-bordered text-center dt-responsive table-hover nowrap formulario" cellspacing="0" width="100%">      
                    <thead>
                    </thead>
                <tbody>
                    <tr>
                        <td><div id="comen"></div></td>
                    </tr>        		
                </tbody>
                </table>
                </div>     
                </div><!--End panel -->
            </div><!--End panel -->        	        	        	                                   
    	</div><!-- End Dialog -->
    </div><!-- end Modal -->
    
    <!-- Modal Reporte a Excel -->
    <div id="reporte" class="modal fade" role="dialog" tabindex='-1'>
    	<div class="modal-dialog">
        	<div class="panel panel-primary luminoso text-center"> 
            	<button type="button" class="close" data-dismiss="modal">&times;</button>   
                <div class="panel-heading">
                    <h3 class="panel-title">Exportar a Excel</h3>
                </div>
                <div class="panel-body">        	
                    <form name="reporteExcel" id="reporteExcel" action="excel.php" method="POST">
                    <div class="form-group col-xs-12 col-md-6">
                        <label for="motivo">Motivo de Contacto</label>  
                        <select name="motivo" id="motivo" class="form-control">
                        <option>Seleccionar...</option>
                        <?php
                            $consulta = mysql_query("SELECT DISTINCT motivo FROM call_registro WHERE estatus != '0' ORDER BY motivo ASC");
                                if(mysql_num_rows($consulta)){ // if para almacenar el resultado de la consulta
                                    while($lista = mysql_fetch_array($consulta)){
                                    echo "<option>".utf8_encode($lista['motivo'])."</option>";
                                    }//End While
                                }//End If		
                        
                        ?>            
                        <option>TODOS</option>
                        </select>
                    </div>
                    <div class="form-group col-xs-12 col-md-6">
                        <label for="submotivo">Sub-Motivo</label>
                          <select name="submotivo" id="submotivo" class="form-control" >
                          <option>Seleccionar...</option>
                          </select>
                    </div>
                    <div class="form-group col-xs-12 col-md-6">
                        <label for="departamento">Departamento</label>
                        <select name="departamento" id="departamento" class="form-control" >
                            <option>Seleccionar...</option>
                            <?php
                                $consulta = mysql_query("SELECT DISTINCT departamento FROM call_registro WHERE estatus != '0' ORDER BY departamento ASC");
                                    if(mysql_num_rows($consulta)){ // if para almacenar el resultado de la consulta
                                        while($lista = mysql_fetch_array($consulta)){
                                        echo "<option>".utf8_encode($lista['departamento'])."</option>";
                                        }//End While
                                    }//End If		
                            
                            ?>            
                        <option>TODOS</option>
                        </select>
                    </div>
                    <div class="form-group col-xs-12 col-md-6">
                        <label for="usuario">Usuario</label>
                        <select name="usuario" id="usuario" class="form-control" >
                            <?php
                                if ($_SESSION['nivel'] >2){
                                    echo "<option>Seleccionar...</option>";
                                    echo "<option>".$_SESSION['nick']."</option>";
                                }else{				
                                $consulta = mysql_query("SELECT DISTINCT usuario FROM call_registro ORDER BY usuario ASC");
                                    if(mysql_num_rows($consulta)){ // if para almacenar el resultado de la consulta
                                        echo "<option>Seleccionar...</option>";
                                            while($lista = mysql_fetch_array($consulta)){
                                        echo "<option>".utf8_encode($lista['usuario'])."</option>";
                                        }//End While
                                        echo "<option>TODOS</option>";
                                    }//End If			
                                }
                            ?>           
                        </select>
                    </div>			
                    <div class="form-group col-xs-12 col-md-6 text-center">
                        <label for="fecha1">Fecha Inicial</label>
                        <input type="text" name="fecha1" id="fecha1" class="form-control" readonly style="background-color:#FFF">
                    </div>
                    <div class="form-group col-xs-12 col-md-6 text-center">
                        <label for="fecha2">Fecha Final</label>
                        <input type="text" name="fecha2" id="fecha2" class="form-control" readonly style="background-color:#FFF">
                    </div>
                    <div class="form-group col-xs-12">
                        <input type="image" id="botongonep" src="imagenes/excel.png" title="Exportar para Excel"/>
                    </div>		                   	         
                </form>
                </div>  	
            </div><!--End panel -->        	                                   
    	</div><!-- End Dialog -->
    </div><!-- end Modal -->    
        
	<!-- Modal Editar Registro -->
    <div id="editar" class="modal fade" role="dialog" tabindex='-1'>
    	<div class="modal-dialog">
        	<div class="panel panel-primary luminoso text-center">
            	<button type="button" class="close" data-dismiss="modal">&times;</button> 
                <div class="panel-heading">
                    <h3 class="panel-title">Editar Registro</h3>    		
                </div>
                <h5 id="incidencia"></h5>
                <div class="panel-body">    
                    <div class="form-group col-xs-12">
                        <label for="motivo2">Motivo de Contacto</label>  
                        <select name="motivo2" id="motivo2" class="form-control">
                        <option>Seleccionar...</option>
                        <?php
                            $consulta = mysql_query("SELECT DISTINCT principal FROM call_tipificacion WHERE estatus = '1' ORDER BY principal ASC");
                                if(mysql_num_rows($consulta)){ // if para almacenar el resultado de la consulta
                                    while($lista = mysql_fetch_array($consulta)){
                                    echo "<option>".utf8_encode($lista['principal'])."</option>";
                                    }//End While
                                }//End If		
                        
                        ?>            
                        </select>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="submotivo2">Sub-Motivo</label>
                        <select name="submotivo2" id="submotivo2" class="form-control" >
                            <option>Seleccionar...</option>
                        </select>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="cedlib2">LIB o Cédula</label>
                        <input type="text" id="cedlib2" class="form-control text-center" />
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="comentario">Motivo de Edición</label>
                        <textarea name="comentario" id="comentario" class="form-control"></textarea>
                    </div>	
                    <div class="form-group col-xs-12">
                        <input type="image" src="imagenes/save.png" name="enviar" id="enviar" title="Editar Registro" class="imagen">
                    </div>		                   	         
                </form>
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
	header("location:../index.php?error=ingreso");
}
?>