<?php
/***************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
include_once 'include/pdo/tipificacion.php';
include_once 'include/fecha.php';
include_once 'include/variables.php';
if(isset($_SESSION['user'])){
	$motivos = getMotives();	
	$submotivos = getSubMotives();
?>
<?php echo $doctype?>
<!-- Achivos CSS -->
<link rel="stylesheet" href="css/jquery-ui.css">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="../DataTables/css/dataTables.bootstrap.css">
<link rel="stylesheet" href="../DataTables/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="../DataTables/css/buttons.dataTables.min.css">  
<link rel="stylesheet" href="../bootstrap/css/bootstrap-submenu.css">
<link rel="stylesheet" href="../bootstrap/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="css/call.css">

<!-- Archivos JavaScript -->	
<script src="../js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="../bootstrap/js/bootstrap.js"></script>
<script src="../DataTables/js/jquery.dataTables.js"></script>
<script src="../DataTables/js/dataTables.bootstrap.js"></script>
<script src="../DataTables/js/dataTables.responsive.min.js"></script>
<script src="../DataTables/js/dataTables.buttons.min.js"></script>
<script src="../DataTables/js/buttons.flash.min.js"></script>
<script src="../DataTables/js/jszip.min.js"></script>
<script src="../DataTables/js/pdfmake.min.js"></script>
<script src="../DataTables/js/vfs_fonts.js"></script>
<script src="../DataTables/js/buttons.html5.min.js"></script>
<script src="../DataTables/js/buttons.print.min.js"></script>
<script src="../bootstrap/js/bootstrap-submenu.js"></script>
<script src="../bootstrap/js/bootbox.min.js"></script>
<script src="../bootstrap/js/bootstrap-datepicker.js"></script>
<script src="../bootstrap/js/locale/bootstrap-datepicker.es.js"></script>
<script src="../js/jquery.numeric.js"></script>
<script src="js/libreriajs.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	var id;
	var fila;
	var fecha;
	var date;

	//Activar Menú
	$("#menu3").attr('class','active');

	//Parse Array de Submotivos
	var obj = jQuery.parseJSON('<?php echo json_encode($submotivos)?>');

	//Función para buscar Comentario de la Gestión
	$('#lista tbody').on('click', '.link', function(){
		id = ($(this).attr('id'));
			$.post('include/pdo/registro.php', {id:id, function:"searchId"}, function(data){
			$('#registro').html('Registro: ' + id);
			$('#comen').html(data);
		});//End post
	});//End Function	

	//Función para buscar los submotivos despues de seleccionar un motivo
	$("#motivo").change(function () {
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

	//Función para buscar los submotivos despues de seleccionar un motivo
	$("#motivo2").change(function () {
        $('#submotivo2').empty();
		$('#submotivo2').append("<option>Seleccionar...</option>")	
		$('#motivo2 option:selected').each(function () {
			elegido=$(this).val();			
        	//Buscamos los submotivos
        	$.each(obj, function(i,item){
        		if (elegido == obj[i].principal){
        			$('#submotivo2').append("<option>"+ obj[i].secundaria +"</option>")
        		}				
			})
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
	date = new Date();
	var mes = getTwoDigitDateFormat(date.getMonth()+1);
	var anio = date.getFullYear();
	fecha = anio + "-" + mes;
	datatable(fecha);

	function datatable(fecha){
		$('#lista').dataTable().fnDestroy();
		$('#lista').dataTable({
			"bDestroy": true,
			"ajax": {
	    		"url": "include/pdo/registro.php",
	    		"data": { 
	                fecha:fecha,
	                function:"lista"
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
				"iDisplayLength": 10,
			dom: 'Bfrtip',
			buttons: [
				{ 
					extend: 'copy',
					title: 'Reporte de Tipificaciones',
					text: 'Copiar',
					exportOptions: {
					  columns: [ 0,1,2,3,4,5,6,7,8]
					}
				},
				{
					extend: 'excel',
					title: 'Reporte de Tipificaciones',
					exportOptions: {
					  columns: [ 0,1,2,3,4,5,6,7,8]
					}
				}
	    	]
		});
	}

	function getTwoDigitDateFormat(month) {
	  return (month < 10) ? '0' + month : '' + month;
	}

	$('#month').datepicker({
	  language: "es",
	  format: "MM yyyy",
	  minViewMode: 1,
	  endDate: new Date(),
	  autoclose: true
	}).on("changeMonth", function(e) {
		mes = getTwoDigitDateFormat(e.date.getMonth()+1);
		anio = e.date.getFullYear()
		fecha = anio + "-" + mes;
		datatable(fecha)
	});

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
		<div class="form-group text-center">
    		<div class="col-xs-12  ol-md-4 col-lg-2 col-md-offset-4 col-lg-offset-5">
	    		<label>Mes y Año</label>
	    		<input type="text" class="form-control form_datetime text-center" id="month" readonly>
			</div>
		</div>
	</div>
    
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
    	</div><!-- End col -->
    </div><!-- End row -->      

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
		                    if (!is_null($motivos)){
		               			foreach ($motivos as $key => $value){
		               				echo '<option>'.$value['principal'].'</option>';
		               			}
		               		}
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
	header("location:../index.php?error=ingreso");
}
?>