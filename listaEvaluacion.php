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
<script src="../js/jquery.numeric.js"></script>
<script src="js/libreriajs.js"></script>
<script>
$(document).ready(function(){
//Activar Menú
	$("#menu5").attr('class','active');
	
//Convertir la tabla en Datatable
	$('#lista').dataTable({
		"ajax": "include/consultae.php",
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
    	<h4>Lista de Evaluaciones</h4>
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
            <th>N°</th>
            <th>Cédula</th>
            <th>Nombre</th>
            <th>Ingreso</th>            
            <th>Tiempo en la Empresa</th>  
            <th>Supervisor</th>   
            <th>Evaluador</th>                   
        </tr>
    </thead>
  	<tfoot>        
        <tr>
           <th>N°</th>
            <th>Cédula</th>
            <th>Nombre</th>
            <th>Ingreso</th>            
            <th>Tiempo en la Empresa</th>  
            <th>Supervisor</th>   
            <th>Evaluador</th>          
        </tr>
	</tfoot>     
    <tbody>      
    </tbody>
	</table>	
    </div><!-- End col -->
    </div><!-- End row -->
	</div><!-- End Container -->   
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