<?php
/***************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
include_once 'include/fecha.php';
include_once 'include/variables.php';
if(isset($_SESSION['user'])){
?>
<?php echo $doctype?>
<!-- Arquivos utilizados pelo jQuery lightBox plugin -->
<!-- include CSS & JS files -->
<!-- CSS file -->
<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="../DataTables/css/dataTables.bootstrap.css">
<link rel="stylesheet" href="../DataTables/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="../DataTables/css/select.dataTables.min.css">
<link rel="stylesheet" href="../bootstrap/css/bootstrap-submenu.css">
<link rel="stylesheet" href="css/call.css">

<!-- jQuery files -->
<script src="../js/jquery.js"></script>
<script src="../bootstrap/js/bootstrap.js"></script>
<script src="../DataTables/js/jquery.dataTables.js"></script>
<script src="../DataTables/js/dataTables.bootstrap.js"></script>
<script src="../DataTables/js/dataTables.select.js"></script>
<script src="../DataTables/js/dataTables.responsive.min.js"></script>
<script src="../bootstrap/js/bootstrap-submenu.js"></script>
<script src="../bootstrap/js/bootbox.min.js"></script>
<script src="../js/jquery.numeric.js"></script>
<script src="js/libreriajs.js"></script>
<script type="text/javascript">
$(document).ready(function(){
var id;
var fila;

//Activar Menú
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
							$("<td />").appendTo(row).append(comentario);
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
	});//End Function

//Función para mostar ventana de edición
	$('#lista tbody').on('click', '.edit', function(){
		id = ($(this).attr('id'));
		fila = $(this).parents().get(1);
		$("#incidencia").html('Inicidencia: '+id);
		$('#estatus option:first-child').prop('selected', 'selected');
		$('#comentario').val('');
		$('#estatus, #comentario').parent().removeClass('has-error has-success');
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
		bootbox.confirm('¿Seguro que desea Incluir el Usuario?', function(result){
			if (result == true){
				accion = 'gestion';
				estatus = $('#estatus').val();
				comentario = $('#comentario').val();
				$.post('include/guardar_registro.php', {id:id,accion:accion,estatus:estatus,comentario:comentario}, function(data){
					if (data  == 0){
					$('#error').html('<strong>¡Error!</strong> Error al Editar la Incidencia, Intente Nuevamente').fadeIn(1000).fadeOut(5000);
					}else{
						$('#mensaje').html('<strong>¡Exito!</strong> Incidencia ' + id + ' Editada Correctamente').fadeIn(1000).fadeOut(5000);
						$('#lista').DataTable().ajax.reload();
						$('#editar').modal('toggle');
					}//End if
				});//End post
			}else{
			}//End if
		});//End Bootbox Function
	});//End Function	

//Función para mostar ventana de Comentario
	$('#lista tbody').on('click', '.mensaje', function(){
		id = ($(this).attr('id'));
		$('#incidencia3').html('Inicidencia: '+ id);
		$('#comentario2').val('').parent().removeClass('has-error has-success');
	});	

//Función para Guardar comentario
   $('#boton2').click(function (){
		if ($("#comentario2").val().length < 6){
			$('#comentario2').parent().addClass('has-error');
			$('#comentario2').attr('placeholder','Campo Obligatorio');
			return false;
		}else{
			$('#comentario2').parent().removeClass('has-error').addClass('has-success');
		}

		bootbox.confirm('¿Seguro que desea guardar el comentario?', function(result){
			if (result == true){
				accion = "comentario";
				comentario = $("#comentario2").val();
				$.post("include/guardar_registro.php", {id:id,accion:accion,comentario:comentario}, function(data){
					if (data  == 0){
						$('#error').html('<strong>¡Error!</strong> Error al Editar la Incidencia, Intente Nuevamente').fadeIn(1000).fadeOut(5000);
					}else{
						$('#mensaje').html('<strong>¡Exito!</strong> Incidencia ' + id + ' Comentada Correctamente').fadeIn(1000).fadeOut(5000);
					}//End if
				});//End post
				$('#comentar').modal('toggle');
			}else{
			}//End if
		});//End Bootbox Function
	});//End Function

//Mostrar ventana comentar masivo
	$("#coment_masivo").click(function(){
	$("#comentario3").val('').parent().removeClass('has-error has-success');
	var table = $('#lista').DataTable();
	var selected = [];
		$("#lista tbody tr").filter(".selected").each(function (index) {
			 idx = table.row( this ).index();
			 selected.push( idx );
		});
		if (selected != ""){
			$('#comentar_seleccion').modal('toggle');
		}else{
			bootbox.alert("Debe Seleccionar al menos una fila");
		}
	});

//Guardar comentario seleccion masiva
	$("#boton3").click(function(){
	var table = $('#lista').DataTable();
	var selected = [];
		if ($("#comentario3").val().length < 6){
			$('#comentario3').parent().addClass('has-error');
			$('#comentario3').attr('placeholder','Campo Obligatorio');
			return false;
		}else{
			$('#comentario3').parent().removeClass('has-error').addClass('has-success');
		}
		bootbox.confirm('¿Seguro que Desea Agregar el Comentario Masivo?', function(result){
			if (result == true){
				accion = "comentario_masivo";
				comentario = $("#comentario3").val();
				$("#lista tbody tr").filter(".selected").each(function (index) {
					idx = table.row( this ).index();
					idxid =  table.cell(idx,0).data();
					var id = $(idxid).text();
					selected.push( id );
				});//End Each
				$.post("include/guardar_registro.php", {selected:selected,accion:accion,comentario:comentario}, function(data){
					if (data  == 0){
						$('#error').html('<strong>¡Error!</strong> Error al incluir los datos, Intente Nuevamente').fadeIn(1000).fadeOut(5000);
					}
					if (data == 1){
						$('#mensaje').html('<strong>¡Exito!</strong> Comentario Masivo Inlcuido Correctamente').fadeIn(1000).fadeOut(5000);
					}
					//End if
				});//End post
					$("#comentario3").val("");
					$('#comentar_seleccion').modal('toggle');
			}
		});//End Bootbox Function
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
				accion = "gestion_masiva";
				estatus = $("#estatus2").val();
				comentario = $("#comentario4").val();
				$("#lista tbody tr").filter(".selected").each(function (index) {
					idx = table.row( this ).index();
					idxid =  table.cell(idx,0).data();
					var id = $(idxid).text();
					selected.push( id );
				});//End Each
				$.post("include/guardar_registro.php", {selected:selected,accion:accion,estatus:estatus,comentario:comentario}, function(data){
					if (data  == 0){
						$('#error').html('<strong>¡Error!</strong> Error al Editar los Registros, Intente Nuevamente').fadeIn(1000).fadeOut(5000);
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

//Mostrar/ Ocultar Botones Edición, Comentario Masivo
	$('#lista tbody').on('click', 'tr', function(){
		var table = $('#lista').DataTable();
		var selected = [];
		$("#lista tbody tr").filter(".selected").each(function (index){
			selected.push(table.row( this ).index());
		});//End Each

		if (selected.length > 1){
			$('#masivo1').show(300);
			$('#masivo2').show(300);
		}else{
			$('#masivo1').hide(300);
			$('#masivo2').hide(300);
		}
	});

//Mostrar Ventana de Búsqueda Masiva
$("#boton_buscar_masiva").click(function(){
	$('#buscar_masiva').modal('toggle');
	$('#guias').val('');
});

//Consulta Masiva de Guía o Tracking
	$("#search").click(function() {
		$('#lista').dataTable().fnClearTable();
		tipo = 'activa';
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
				url: "include/buscar_masiva.php",
				type: 'post',
				data: {guia:guia, tipo:tipo},
				dataType: 'json',
				success: function (data) {
					if (data.success) {
						$.each(data, function (index, record) {
							if ($.isNumeric(index)) {
								var
								id = record.id;
								fecha = record.fecha;
								motivo = record.motivo;
								sub_motivo = record.sub_motivo;
								libced = record.libced;
								guiatracking = record.guiatracking;
								estatus = record.estatus;
								mensaje = record.mensaje;
								edit = record.edit
								$('#lista').DataTable().row.add( [
									id,
									fecha,
									motivo,
									sub_motivo,
									libced,
									guiatracking,
									estatus,
									mensaje,
									edit
								] ).draw();
							}//End if $.isNumeric(index)
						})//End each

					}//End if data.success
				},//End Function data
				error: function() {
					var row = $("<tr />");
						var td = $("<td />").attr('colspan',4).attr('style','text-align:center').append('No hay resultados en la busqueda');
						td.appendTo(row)
					row.appendTo('#lista');
				},
			});//End Ajax
			});
		$('#mensaje').html('<strong>¡Exito!</strong> Consulta Procesada').fadeIn(1000).fadeOut(5000);
		$('#buscar_masiva').modal('toggle');
	});

	//Convertir la tabla en datatable
	$('#lista').DataTable( {
        select: true,
		"ajax": {
	    		"url": "include/pdo/incidencia.php",
	    		"data": {
	                function:"getIncidents"
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
			"select": {
                "rows": {
                    "_": "Ha seleccionado %d filas",
                    "0": "Clic sobre una fila para Seleccionarla",
                    "1": "Ha seleccionado %d fila"
                }
            },
			"oAria": {
				"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
			}
		},
		aLengthMenu: [[10,50,100],[10,50,100]],
			"iDisplayLength": 10
	});

//Función para colocar los Textos a tipo fecha
	$('#fecha1').datepicker({
		dateFormat: 'dd/mm/yy', 
		maxDate: 0, minDate:'-5Y',
		onSelect: function(dateText, inst) {
			var lockDate = new Date($('#fecha1').datepicker('getDate'));
			//lockDate.setDate(lockDate.getDate() + 1);
			$('input#fecha2').datepicker('option', 'minDate', lockDate);
		}
	});	

	$("#fecha2").datepicker({dateFormat: 'dd/mm/yy', maxDate: 0 });
});

</script>
</head>
<body>
	<?php echo $header?>
    <div class="container-fluid contenido">
	<?php echo $menu?>
    <div class="text-center">
    	<h4>Incidencias Activas</h4>
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
    <div class="col-xs-12">
    <table id="lista" class="table table-striped table-bordered text-center dt-responsive table-hover table-condensed nowrap" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Fecha</th>
                <th>Departamento</th>
                <th>Motivo</th>
                <th>Sub Motivo</th>
                <th>LIB o C&eacute;dula</th>
                <th>Gu&iacute;a o Tracking</th>
                <th>Estatus</th>
                <th>Comentar</th>
                <?php
                if ($_SESSION['departamento'] == 'INCIDENCIAS'){
                    echo "<th>Editar</th>";
                    }
                ?>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Id</th>
                <th>Fecha</th>
                <th>Departamento</th>
                <th>Motivo</th>
                <th>Sub Motivo</th>
                <th>LIB o C&eacute;dula</th>
                <th>Gu&iacute;a o Tracking</th>
                <th>Estatus</th>
                <th>Comentar</th>
                <?php
                if ($_SESSION['departamento'] == 'INCIDENCIAS'){
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
	<?php
    if($nivel < 2){
    ?>

    <div class="row">
    <div class="col-xs-12 col-md-5" align="center"></div>
    <div class="col-xs-12 col-md-6" align="center">
        <div id="masivo1" class="oculto col-xs-12 col-sm-1">
            <img src="imagenes/comentar2.png" name="coment_masivo" id="coment_masivo" class="cursor" title="Comentar Selección" data-toggle="tooltip" data-target="#comentar_seleccion">
        </div>

        <div id="masivo2" class="oculto col-xs-12 col-sm-1">
            <img src="imagenes/gestion2.png" name="edit_masivo" id="edit_masivo" class="cursor" data-toggle="tooltip" title="Editar Selección">
        </div>
        <?php
            }if($nivel < 3){
        ?>

        <div align="center" class="col-xs-12 col-sm-1">
            <img src="imagenes/filter-icon.png" name="boton_buscar_masiva" id="boton_buscar_masiva" class="cursor" data-toggle="tooltip" title="Filtro o Búsqueda Masiva">
        </div>

        <div align="center" class="col-xs-12 col-sm-1">
        <form method="POST">
            <input type="image" id="boton" src="imagenes/excel.png" onclick = "this.form.action = 'exceli.php'" data-toggle="tooltip" title="Exportar para Excel"/>
        </form>
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
            	<h5 id="incidencia2"></h5>
                <div class="panel-body">
                    <div class="table-responsive">
                    <table id="gestion" class="table table-striped table-bordered table-condensed text-center dt-responsive table-hover nowrap formulario" cellspacing="0" width="100%">
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

    <!-- Modal Comentar Registro -->
    <div id="comentar" class="modal fade" role="dialog" tabindex='-1'>
    	<div class="modal-dialog">
            <div class="panel panel-primary luminoso text-center">
            	<button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="panel-heading">
                    <h3 class="panel-title">Comentar Incidencia</h3>
                </div>
                <h5 id="incidencia3"></h5>
                <div class="panel-body">
                    <div class="form-group col-xs-12 text-center">
                        <label for="comentario2">Comentario</label>
                        <textarea name="comentario" id="comentario2" class="form-control"></textarea>
                    </div>

                    <div class="form-group col-xs-12 text-center">
                        <input type="image" src="imagenes/save.png" name="enviar" id="boton2" title="Agregar Registro" class="imagen">
                    </div>
                </div>
            </div><!--End panel -->
    	</div><!-- End Dialog -->
    </div><!-- end Modal -->   

	<!-- Modal Comentar seleccion de Registros -->
    <div id="comentar_seleccion" class="modal fade" role="dialog" tabindex='-1'>
    	<div class="modal-dialog">
            <div class="panel panel-primary luminoso text-center">
            	<button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="panel-heading">
                    <h3 class="panel-title">Comentar Incidencias Seleccionadas</h3>
                </div>

                <div class="panel-body">
                    <div class="form-group col-xs-12 text-center">
                        <label for="comentario">Comentario</label>
                        <textarea name="comentario" id="comentario3" class="form-control"></textarea>
                    </div>

                    <div class="form-group col-xs-12 text-center">
                        <input type="image" src="imagenes/save.png" name="enviar" id="boton3" title="Agregar Registro" class="imagen">
                    </div>
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
                <h3 class="panel-title">Editar Incidencia</h3>
            </div>

            <h5 id="incidencia"></h5>
            <div class="panel-body">
                <div class="form-group col-xs-12 text-center">
	                <label for="estatus">Estatus</label>
	                <select name="estatus" id="estatus" class="form-control">
	                    <option>Seleccionar...</option>
	                    <option>Abierta</option>
	                    <option>En Verificaci&oacute;n</option>
	                    <option>Cerrada</option>
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
                    <h3 class="panel-title">Editar Incidencia</h3>
                </div>

                <div class="panel-body">
                    <div class="form-group col-xs-12 text-center">
	                    <label for="estatus2">Estatus</label>
	                    <select name="estatus" id="estatus2" class="form-control">
	                        <option>Seleccionar...</option>
	                        <option>Abierta</option>
	                        <option>En Verificaci&oacute;n</option>
	                        <option>Cerrada</option>
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

	<!-- Modal Búsqueda masiva de Registros -->
    <div id="buscar_masiva" class="modal fade" role="dialog" tabindex='-1'>
    	<div class="modal-dialog modal-sm">
        	<div class="panel panel-primary luminoso text-center">
            	<button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="panel-heading">
                    <h3 class="panel-title">Búsqueda Masiva</h3>
                </div>

                <div class="panel-body">
                    <div class="form-group col-xs-12 col-sm-12 text-center">
                     <label for="guias">Guía o Tracking</label>
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