<?php
/***************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
require 'include/pdo/database.php';
include_once 'include/pdo/tipificacion.php';
include_once 'include/fecha.php';
include_once 'include/variables.php';
if(isset($_SESSION['user']) && ($_SESSION['nivel'] < 2)){
	$motives = getMotives();
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
<script src="../bootstrap/js/bootstrap-submenu.js"></script>
<script src="../bootstrap/js/bootbox.min.js"></script>
<script src="../js/jquery.numeric.js"></script>
<script src="js/libreriajs.js"></script>
<script>
$(document).ready(function(){

//Activar Menú
	$("#menu2").attr('class','active');

//Mostrar Formulario de nueva Tipificación
	$("#boton").click(function() {
		$('body input[type=text]').val('').attr('placeholder','').parent().removeClass('has-error has-success');
		$('body input[type=password]').val('').attr('placeholder','').parent().removeClass('has-error has-success');
		$('#motivo option:first').prop("selected", "selected");
		$('#motivo').parent().removeClass('has-error has-success');
		$('body textarea').val('').attr('placeholder','').parent().removeClass('has-error has-success');
		$('#nuevo').modal('toggle');
	});	

//Validar y Agregar Tipificación Nueva
	$("#save").click(function(){
		if ($("#motivo option:selected").index() == 0){
			$('#motivo').parent().addClass('has-error');
			$('#motivo').attr('placeholder','Campo Obligatorio');
			return false;
		}else{
			$('#motivo').parent().removeClass('has-error').addClass('has-success');
		}

		if ($("#sub_motivo").val() == ''){
			$('#sub_motivo').parent().addClass('has-error');
			$('#sub_motivo').attr('placeholder','Campo Obligatorio');
			return false;
		}else{
			$('#sub_motivo').parent().removeClass('has-error').addClass('has-success');
		}

		bootbox.confirm('¿Seguro que desea Incluir el Tipo de Tipificación?', function(result){
			if (result == true){
				motivo = $('#motivo').val();
				sub_motivo = $('#sub_motivo').val();
				$.post('include/pdo/tipificacion.php', {function:"newTipification", motivo:motivo, sub_motivo:sub_motivo}, function(data){
					if (data  == '0'){
						$('#error').html('<strong>¡Error!</strong> Error al Incluir el Tipo de Tipificación, Intente más tarde').fadeIn(1000).fadeOut(5000);
					}else if (data == '1'){
						$('#mensaje').html('<strong>¡Exito!</strong> Tipo de Tipificación Incluida Correctamente').fadeIn(1000).fadeOut(5000);
						$('#lista').DataTable().ajax.reload();
					}else if (data == 'repetido'){
						$('#alerta').html('<strong>¡Alerta!</strong> Ya existe una Tipificación con ese Motivo y Sub Motivo, Verifique los Datos e Intente Nuevamente').fadeIn(1000).fadeOut(5000);
					}//End if
				});//End post
				$('#nuevo').modal('toggle');
			}
		});//End Function
	});//End Function

//Mostrar Formulario de editar Tipificación
	$('#lista tbody').on('click', '.edit', function(){
		$('#editar').modal('toggle');
		var element = $(this).attr('id').split('│');
		var id = element[0];
		var motivo = element[1];
		var sub_motivo = element[2];
		$('#id2').val(id).parent().removeClass('has-error has-success');
		$('#motivo2').val(motivo).parent().removeClass('has-error has-success');
		$('#sub_motivo2').val(sub_motivo).parent().removeClass('has-error has-success');
	});

//Validar y Editar Tipificación 
	$("#save2").click(function(){
		if ($("#motivo2 option:selected").index() == 0){
			$('#motivo2').parent().addClass('has-error');
			$('#motivo2').attr('placeholder','Campo Obligatorio');
			return false;
		}else{
			$('#motivo2').parent().removeClass('has-error').addClass('has-success');
		}

		if ($("#sub_motivo2").val() == ''){
			$('#sub_motivo2').parent().addClass('has-error');
			$('#sub_motivo2').attr('placeholder','Campo Obligatorio');
			return false;
		}else{
			$('#sub_motivo2').parent().removeClass('has-error').addClass('has-success');
		}				

		bootbox.confirm('¿Seguro que desea Editar el Tipo de Tipificación?', function(result){
			if (result == true){
				id = $('#id2').val();
				motivo = $('#motivo2').val();
				sub_motivo = $('#sub_motivo2').val();
				$.post('include/pdo/tipificacion.php', {function:"editTipification", id:id, motivo:motivo, sub_motivo:sub_motivo}, function(data){
					if (data  == '0'){
						$('#error').html('<strong>¡Error!</strong> Error al Editar el Tipo de Tipificación, Intente más tarde').fadeIn(1000).fadeOut(5000);
					}else if (data == '1'){
						$('#mensaje').html('<strong>¡Exito!</strong> Tipo de Tipificación Editada Correctamente').fadeIn(1000).fadeOut(5000);
						$('#lista').DataTable().ajax.reload();
					}else if (data == 'repetido'){
						$('#alerta').html('<strong>¡Alerta!</strong> Ya existe una Tipificación con ese Motivo y Sub Motivo, Verifique los Datos e Intente Nuevamente').fadeIn(1000).fadeOut(5000);
					}//End if
				});//End post
			}//End if
		});//End Function
	});//End Function

//Cambiar el estatus del Tipo de Tipificación
	$('#lista tbody').on('click', '.camb', function(){
		var element = $(this).attr('id').split('│');
		var id = element[0];
		var estatus = element[1];
		bootbox.confirm('¿Seguro que desea el cambiar el Estatus del Tipo de Tipificación?', function(result){
			if (result == true){
				$.post("include/pdo/tipificacion.php", {function:"changeStatus", id:id, estatus:estatus}, function(data){
					if (data  == '0'){
						$('#error').html('<strong>¡Error!</strong> Error al Editar el Estatus, Intente mas tarde').fadeIn(1000).fadeOut(5000);
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
			    "url": "include/pdo/tipificacion.php",
			    "type": "POST",
			    "data": {
			        "function": "getTipifications"
			    }
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
    	<h4>Tipificaciones del Sistema</h4>
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
    <table id="lista" class="table table-striped table-bordered text-center dt-responsive table-hover nowrap" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Motivo</th>
            <th>Sub Motivo</th>
            <th>Estatus</th>
            <th>Comandos</th>
        </tr>
    </thead>
  	<tfoot>
        <tr>
            <th>ID</th>
            <th>Motivo</th>
            <th>Sub Motivo</th>
            <th>Estatus</th>
            <th>Comandos</th>
        </tr>
	</tfoot>
    <tbody>
    </tbody>
	</table>

	<div align="center">
    	<img src="imagenes/add.png" width="50" height="50" class="cursor" data-toggle="tooltip" data-placement="bottom" title="Agregar Tipificación" id="boton">
   	</div>
    </div><!-- End col -->
    </div><!-- End row -->
	</div><!-- End Container -->
    

    <!-- Modal Nueva Tipificación  -->
    <div id="nuevo" class="modal fade" role="dialog" tabindex='-1'>
    	<div class="modal-dialog">
            <div class="panel panel-primary luminoso text-center">
            	<button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="panel-heading">
                    <h3 class="panel-title">Nueva Tipificación</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group col-xs-12 col-md-6 text-center">
                        <label for="motivo">Motivo</label>
                        <select name="motivo" id="motivo" class="form-control">
                            <option>Selecionar...</option>
                            <?php	                           		
                           		if (!is_null($motives )){
                           			foreach ($motives as $key => $value){
                           				echo '<option>'.$value['principal'].'</option>';
                           			}
                           		}
	                        ?>                            
                        </select>
                    </div>
                    <div class="form-group col-xs-12 col-md-6 text-center">
                        <label for="sub_motivo">Sub Motivo</label>
                        <input type="text" name="sub_motivo" id="sub_motivo" class="form-control text-uppercase uncopypaste text-center">
                    </div>

                    <div class="form-group col-xs-12 text-center">
                        <input type="image" id="save" src="imagenes/save.png" title="Ingresar Usuario">
                    </div>
                </div>
            </div><!--End panel -->
    	</div><!-- End Dialog -->
    </div><!-- end Modal -->    

    <!-- Modal Nueva Tipificación  -->
    <div id="editar" class="modal fade" role="dialog" tabindex='-1'>
    	<div class="modal-dialog">
        	<div class="panel panel-primary luminoso text-center">
            	<button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="panel-heading">
                    <h3 class="panel-title">Editar Tipificación</h3>
                </div>
                <div class="panel-body">
                	<div class="form-group col-xs-12 col-md-2 text-center">
                        <label for="id2">Motivo</label>
                        <input type="text" name="id2" id="id2" class="form-control text-center" readonly>
               		</div>
                    <div class="form-group col-xs-12 col-md-5 text-center">
                        <label for="motivo2">Motivo</label>
                        <select name="motivo2" id="motivo2" class="form-control">
                            <option>Selecionar...</option>
                            <?php	                           		
                           		if (!is_null($motives )){
                           			foreach ($motives as $key => $value){
                           				echo '<option>'.$value['principal'].'</option>';
                           			}
                           		}
	                        ?>
                        </select>
                    </div>
                    <div class="form-group col-xs-12 col-md-5 text-center">
                        <label for="sub_motivo2">Sub Motivo</label>
                        <input name="sub_motivo2" id="sub_motivo2" type="text" size="40" maxlength="40" class="form-control uncopypaste text-center">
                    </div>
                    <div class="form-group col-xs-12 text-center">
                        <input name="save2" type="image" id="save2" src="imagenes/save.png" title="Editar Usuario">
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