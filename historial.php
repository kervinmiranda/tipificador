<?php
/***************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
include_once 'include/fecha.php';
include_once 'include/variables.php';
if(isset($_SESSION['user'])){
?>
<?php echo $doctype?>
<!-- CSS files -->
<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="../DataTables/css/dataTables.bootstrap.css">
<link rel="stylesheet" href="../DataTables/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="../DataTables/css/buttons.dataTables.min.css"> 
<link rel="stylesheet" href="../DataTables/css/select.dataTables.min.css">
<link rel="stylesheet" href="../bootstrap/css/bootstrap-submenu.css">
<link rel="stylesheet" href="../bootstrap/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="css/call.css">
<!-- jQuery files -->
<script src="../js/jquery.js"></script>
<script src="../bootstrap/js/bootstrap.js"></script>
<script src="../DataTables/js/jquery.dataTables.js"></script>
<script src="../DataTables/js/dataTables.bootstrap.js"></script>
<script src="../DataTables/js/dataTables.select.js"></script>
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
<script src="js/moment.js"></script>
<script type="text/javascript">

$(document).ready(function(){
	var id;
	var seleccion = false;
	var nivel = <?php echo $_SESSION['nivel'];?>
	
	//Aplica los filtros cuando se selecciona una fecha
	$.fn.dataTable.ext.search.push(
	    function( settings, data, dataIndex ) {
	        if (moment($('#apertura').val(), 'YYYY-MM-DD', true).isValid()){
	        	var min = moment($('#apertura').val());        	
	        }
	      	if (moment($('#cierre').val(), 'YYYY-MM-DD', true).isValid()){
	        	var max = moment($('#cierre').val());        	
	        }        
	        var apertura = moment(data[1], 'YYYY-MM-DD');
	       	if ( (isNaN( min ) && isNaN( max )) || (apertura >= min && isNaN( max )) || (apertura <= max && isNaN(min)) || (apertura >= min && apertura <= max)     
	            )
	        {
	           return true; 
	        }
	        return false;
	    }
	);

	//Activa Menú
	$("#menu4").attr('class','active');
	
	//Función para buscar las gestiones de la incidencia
	$('#lista tbody').on('click', '.link', function(){
		id = ($(this).attr('id'));
		$("#gestion > tbody").empty();
		$.ajax({
			url: "include/pdo/incidencia.php",
			type: 'post',
			data: {id:id, function:"incidentManagement"},
			dataType: 'json',
			success: function (data) {
				if (data.success) {
					$.each(data, function (index, record) {
						if ($.isNumeric(index)) { 
							var
							fecha = record.fecha;
							gestor = record.gestor;
							estatus = record.estatus;
							comentario = record.comentario;							
							var row = $("<tr />");
							$("<td />").appendTo(row).append(fecha);
							$("<td />").appendTo(row).append(gestor);
							$("<td />").appendTo(row).append(estatus);
							$("<td />").appendTo(row).append(comentario)
							row.appendTo('#gestion');
						}//End if $.isNumeric(index)
					})//End each
				}//End if data.success
			},//End Function	data
			error: function() {
				var row = $("<tr />");
					var td = $("<td />").attr('colspan',4).attr('style','text-align:center').append('No hay resultados en la busqueda');
					td.appendTo(row)
				row.appendTo('#gestion');
			},
		});//End Ajax
		//Mostrar la tabla de los resultados buscados
		$("#incidencia2").html('Inicidencia: '+id);
		$("#reporte").modal('toggle');
	});//End Function

	//Función para mostar ventana de edición
	$('#lista tbody').on('click', '.edit', function(){
		id = ($(this).attr('id'));
		fila = $(this).parents().get(1);
		$("#incidencia").html('Inicidencia: '+id);
		$('#estatus option:first-child').attr('selected', 'selected');
		$('#comentario').val('');
		$('#estatus, #comentario').parent().removeClass('has-error has-success');
		$("#editar").modal('toggle');
	});

	//Función para Editar Incidencia
   	$("#boton1").click(function (){
		if ($("#estatus option:selected").index() == 0){
			$('#estatus').parent().addClass('has-error');
			return false;
		}else{
			$('#estatus').parent().removeClass('has-error').addClass('has-success');
		}

		if ($("#comentario").val().length < 6){
			$('#comentario').parent().addClass('has-error');
			$('#comentario').attr('placeholder','Campo Obligatorio');
			return false;
		}else{
			$('#comentario').parent().removeClass('has-error').addClass('has-success');
		}
		bootbox.confirm('¿Seguro que desea Editar la Incidencia?', function(result){
			if (result == true){
				accion = 'gestion';
				estatus = $('#estatus').val();
				comentario = $('#comentario').val();
				$.post('include/guardar_registro.php', {id:id,accion:accion,estatus:estatus,comentario:comentario}, function(data){
					if (data  == 0){
						$('#error').html('<strong>¡Error!</strong> Error al Editar la Incidencia, Intente Nuevamente').fadeIn(1000).fadeOut(5000);
					}else{
						$('#mensaje').html('<strong>¡Exito!</strong> Incidencia ' + id + ' Editada Correctamente').fadeIn(1000).fadeOut(5000);
						$('#lista').dataTable().fnDeleteRow(fila);
					}//End if
				});//End post
				$('#editar').modal('toggle');
			}//End if
		});//End Bootbox Function
	});//End Function

	//Mostrar Ventana de Búsqueda Masiva
	$("#boton_buscar_masiva").click(function(){
		$('#buscar_masiva').modal('toggle');
		$('#guias').val('');
	});

	//Consulta Masiva de Guía o Tracking
	$("#search").click(function() {
		$('#lista').DataTable().clear().draw();
		tipo = 'historial';
		lines = [];
			$.each($('#guias').val().split(/\n/), function(i, line){
				if(line && line.length){
				lines.push(line);
			  	}
			});
		jQuery.unique(lines);
		$.each(lines, function(i,l){
			guia = l;
			$.ajax({
				url: "include/pdo/incidencia.php",
				type: 'post',
				data: {guia:guia, tipo:tipo, function:"getIncident"},
				dataType: 'json',
				success: function (data) {
					if (data.success) {
						$.each(data, function (index, record) {
							if ($.isNumeric(index)) {
								var
								id = record.id;
								apertura = record.apertura;
								cierre = record.cierre;
								departamento = record.departamento;
								motivo = record.motivo;
								sub_motivo = record.sub_motivo;
								libced = record.libced;
								guiatracking = record.guiatracking;
								estatus = record.estatus;
								edit = record.edit
								$('#lista').DataTable().row.add( [
									id,
									apertura,
									cierre,
									departamento,
									motivo,
									sub_motivo,
									libced,
									guiatracking,
									estatus,
									edit
								] ).draw();
							}//End if $.isNumeric(index)
						})//End each
					}//End if data.success
				}//End Function data				
			});//End Ajax
			});
		$('#mensaje').html('<strong>¡Exito!</strong> Consulta Procesada').fadeIn(1000).fadeOut(5000);
		$('#buscar_masiva').modal('toggle');
	});

	//Mostrar opciones al seleccionar fila
	$('#lista tbody').on('click', 'tr', function(){
		var table = $('#lista').DataTable();
		var selected = [];
		$("#lista tbody tr").filter(".selected").each(function (index){
			selected.push(table.row( this ).index());
		});//End Each

		if (selected.length > 1){
			$('#masivo').show(300);
		}else{
			$('#masivo').hide(300);
		}
	});


	//Mostrar ventana edición masivo
	$("#edit_masivo").click(function(){
	var table = $('#lista').DataTable();
	var selected = [];
	$('#estatus2 option:first-child').attr('selected', 'selected');
	$('#estatus2').parent().removeClass('has-error has-success');
	$('#comentario4').val('').parent().removeClass('has-error has-success');
		$("#lista tbody tr").filter(".selected").each(function (index) {
			 idx = table.row( this ).index();
			 selected.push( idx );
		});
		if (selected != ""){
			$('#editar_selección').modal('toggle');
		}else{
			bootbox.alert("Debe Seleccionar al menos una fila");
		}
	});

	//Editar seleccion masiva
	$("#boton4").click(function(){
	var table = $('#lista').DataTable();
	var selected = [];
		if ($("#estatus2 option:selected").index() == 0){
			$('#estatus2').parent().addClass('has-error');
			return false;
		}else{
			$('#estatus2').parent().removeClass('has-error').addClass('has-success');
		}

		if ($("#comentario4").val().length < 6){
			$('#comentario4').parent().addClass('has-error');
			$('#comentario4').attr('placeholder','Campo Obligatorio');
			return false;
		}else{
			$('#comentario4').parent().removeClass('has-error').addClass('has-success');
		}
		bootbox.confirm('¿Seguro que Desea Editar la Selección Masiva??', function(result){
			if (result == true){
				estatus = $("#estatus2").val();
				comentario = $("#comentario4").val();
				$("#lista tbody tr").filter(".selected").each(function (index) {
					idx = table.row( this ).index();
					idxid =  table.cell(idx,0).data();
					var id = $(idxid).text();
					selected.push( id );
				});//End Each
				$.post("include/pdo/incidencia.php", {selected:selected, function:"massiveEdit", estatus:estatus, comentario:comentario}, function(data){
					if (data  != 1){
						$('#error').html('<strong>¡Error!</strong> Error al Editar los Registros ' + data + ', Intente Nuevamente').fadeIn(1000).fadeOut(5000);
					}
					if (data == 1){
						$('#mensaje').html('<strong>¡Exito!</strong> Incidencias Editadas Correctamente').fadeIn(1000).fadeOut(5000);
						$('#lista').DataTable().ajax.reload();
					}
					//End if
				});//End post
					$('#editar_selección').modal('toggle');
			}
		});//End Bootbox Function
	});	

	//Convertir la tabla en datatable
	$('#lista').DataTable( {
		"bDestroy": true,
        select: true,
		"ajax": {
    		"url": "include/pdo/incidencia.php",
    		"data": {
                function:"getIncidents",
                estatus: "Cerrada" 
                },
			"type": 'POST'
	  	},
		"sPaginationType": "full_numbers",
		"language":{ 
			"url": "../DataTables/locale/Spanish.json"
		},		
		aLengthMenu: [[10,50,100],[10,50,100]],
		"iDisplayLength": 10,
		dom: 'Bflrtip',
		buttons: getbuttons(nivel, [0,1,2,3,4,5,6,7,8], 'Historial de Incidencias')
	});

	// Al cambiar lasfechas aplica el filtro
    $('#apertura, #cierre').change( function() {
        $('#lista').DataTable().draw();
    });

    //Función para colocar los Textos a tipo fecha
	$("#apertura").datepicker({
		language: "es",
	  	format: "yyyy-mm-dd",
	  	endDate: new Date(),
	  	autoclose: true,
	    todayBtn:  1,
	    autoclose: true,
	}).on('changeDate', function (selected) {
	    var minDate = new Date(selected.date.valueOf());
	    $('#cierre').datepicker('setStartDate', minDate);
	});
	$("#cierre").datepicker({
		language: "es",
	  	format: "yyyy-mm-dd",
	  	endDate: new Date(),
	  	autoclose: true,
	}).on('changeDate', function (selected) {
	        var maxDate = new Date(selected.date.valueOf());
	        $('#apertura').datepicker('setEndDate', maxDate);
	});

	//Borrar filtro de fechas
	$('#clearInit').click( function() {
		if ($('#apertura').val()!=''){
         	$('#apertura').val('');
			$('#lista').DataTable().draw();
		}
    });
    $('#clearEnd').click( function() {
		if ($('#cierre').val()!=''){
         	$('#cierre').val('');
			$('#lista').DataTable().draw();
		}
    });
});

</script>
</head>
<body>
	<?php echo $header?>
    <div class="container-fluid contenido">
	<?php echo $menu?>

    <div class="text-center">
    	<h4>Historial de Incidencias</h4>
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
		<div class="form-group col-xs-12 col-md-3 col-md-offset-3 col-lg-2 col-lg-offset-4 text-center">
	        <label>Fecha Apertura</label>
	        <div class="input-group">
		        <input  type="text" name="apertura" id="apertura" class="form-control text-center" readonly>
		        <div class="input-group-btn">                    
                    <button class="btn" data-toggle="tooltip" id="clearInit" title="borrar"><i class="glyphicon glyphicon-remove"></i>
                    </button>
                </div>
	        </div>
	    </div>

	    <div class="form-group col-xs-12 col-md-3 col-lg-2 text-center">
	        <label>Fecha Cierre</label>
	        <div class="input-group">
	        	<input  type="text" name="cierre" id="cierre" class="form-control text-center" readonly>
	        	<div class="input-group-btn">
                    <button class="btn" data-toggle="tooltip" id="clearEnd" title="borrar"><i class="glyphicon glyphicon-remove"></i>
                    </button>
                </div>
	        </div>
	    </div>
	    <div class="col-xs-12">    	
	    	<table id="lista" class="table table-striped table-bordered text-center dt-responsive table-hover nowrap" cellspacing="0" width="100%">
	            <thead>
	                <tr>
	                    <th>Id</th>
	                    <th>Apertura</th>
	                    <th>Cierre</th>
	                    <th>Departamento</th>
	                    <th>Motivo</th>
	                    <th>Sub Motivo</th>
	                    <th>LIB o C&eacute;dula</th>
	                    <th>Gu&iacute;a o Tracking</th>
	                    <th>Estatus</th>
	                    <?php
						if ($nivel < 2){
						echo '<th>Editar</th>';
						}
						?>
	                </tr>
	            </thead>
	            <tfoot>
	                <tr>
	                    <th>Id</th>
	                    <th>Apertura</th>
	                    <th>Cierre</th>
	                    <th>Departamento</th>
	                    <th>Motivo</th>
	                    <th>Sub Motivo</th>
	                    <th>LIB o C&eacute;dula</th>
	                    <th>Gu&iacute;a o Tracking</th> 
	                    <th>Estatus</th>
	                    <?php
						if ($nivel < 2){
						echo '<th>Editar</th>';
						}
						?>
	                </tr>
	            </tfoot>
	            <tbody>
	            </tbody>
			</table>		
		</div><!-- End col -->	
    </div><!-- End row --> 
	<?php
    if($nivel < 2){
    ?>

    <div class="row">
    <div class="col-xs-12 col-md-5" align="center"></div>
    <div class="col-xs-12 col-md-6" align="center">
        <div id="masivo" class="oculto col-xs-12 col-sm-1">
            <img src="imagenes/gestion2.png" name="edit_masivo" id="edit_masivo" class="cursor" title="Editar Selección">
        </div>
        <?php
            }if($nivel < 3){
        ?>
        <div align="center" class="col-xs-12 col-sm-1">
            <img src="imagenes/filter-icon.png" name="boton_buscar_masiva" id="boton_buscar_masiva" class="cursor" data-toggle="tooltip" title="Filtro o Búsqueda Masiva">
        </div>        
	<?php
    	}
    ?>
	</div><!-- End col -->
    <div class="col-xs-12 col-md-3" align="center"></div>
    </div><!-- End row -->    

    <!-- Modal Mostra Actividad de Registro -->
    <div id="reporte" class="modal fade" role="dialog" tabindex='-1'>
    	<div class="modal-dialog modal-lg">
        	<div class="panel panel-primary luminoso text-center">
            	<button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="panel-heading">
                    <h3 class="panel-title">Consulta de Incidencia</h3>
                </div>
                <div class="panel-body">
                <div class="table-responsive">
                <table id="gestion" class="table table-striped table-bordered text-center dt-responsive table-hover nowrap formulario" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th><div align="center">Fecha</div></th>
                            <th><div align="center">Gestor</div></th>
                            <th><div align="center">Estatus</div></th>
                            <th><div align="center">Comentario</div></th>
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

    <!-- Modal Reabrir Registro -->
    <div id="editar" class="modal fade" role="dialog" tabindex='-1'>
    	<div class="modal-dialog">
        	<div class="panel panel-primary luminoso text-center">
            	<button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="panel-heading">
                    <h3 class="panel-title">Reabrir Incidencia</h3>
                </div>
                <h5 id="incidencia"></h5>
                <div class="panel-body">
                    <div class="form-group col-xs-12 text-center">
	                    <label for="estatus">Estatus</label>
	                    <select name="estatus" id="estatus" class="form-control">
	                        <option>Seleccionar...</option>
	                        <option>Abierta</option>
	                        <option>En Verificaci&oacute;n</option>
	                    </select>
                    </div>
                    <div class="form-group col-xs-12 text-center">
                        <label for="comentario">Comentario</label>
                        <textarea name="comentario" id="comentario" class="form-control"></textarea>
                    </div>
                    <div class="form-group col-xs-12 text-center">
                        <input type="image" src="imagenes/save.png" name="enviar" id="boton1" title="Editar Incicencia" class="imagen">
                    </div>
                </div>
            </div><!--End panel -->
    	</div><!-- End Dialog -->
    </div><!-- end Modal -->

    <!-- Modal Editar seleccion de Registros -->
    <div id="editar_selección" class="modal fade" role="dialog" tabindex='-1'>
    	<div class="modal-dialog">
            <div class="panel panel-primary luminoso text-center">
            	<button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="panel-heading">
                    <h3 class="panel-title">Reabrir Incidencias</h3>
                </div>

                <div class="panel-body">
                    <div class="form-group col-xs-12 text-center">
	                    <label for="estatus2">Estatus</label>
	                    <select name="estatus" id="estatus2" class="form-control">
	                        <option>Seleccionar...</option>
	                        <option>Abierta</option>
	                        <option>En Verificaci&oacute;n</option>
	                    </select>
                    </div>

                    <div class="form-group col-xs-12 text-center">
                        <label for="comentario4">Comentario</label>
                        <textarea name="comentario" id="comentario4" class="form-control"></textarea>
                    </div>

                    <div class="form-group col-xs-12 text-center">
                        <input type="image" src="imagenes/save.png" name="enviar" id="boton4" title="Editar Incidencias" class="imagen">
                    </div>
                </div>
            </div><!--End panel -->
    	</div><!-- End Dialog -->
    </div><!-- end Modal -->   

    <!-- Modal Busqueda Masiva -->
    <div id="buscar_masiva" class="modal fade" role="dialog" tabindex='-1'>
    	<div class="modal-dialog modal-sm">
        	<div class="panel panel-primary luminoso text-center">
            	<button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="panel-heading">
                    <h3 class="panel-title">Búsqueda Masiva</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group col-xs-12 col-sm-12 text-center">
                     <label for="guias">Búsqueda Masiva</label>
                     <textarea id="guias" name="guias" class="form-control" rows="15"></textarea>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 text-center">
                         <input type="image" src="imagenes/search.png" id="search">
                    </div>
                </div>
            </div><!--End panel -->
    	</div><!-- End Dialog -->
    </div><!-- end Modal -->
    
    </div><!--end. content-->
     <?php echo $footer?>
</body>
</html>
<?php
}else{
	header("location:../index.php?error=ingreso");
}
?>