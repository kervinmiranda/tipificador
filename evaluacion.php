<?php
/**********************************************************************************************************************
                                                      SISTEMA GEBNET
***********************************************************************************************************************/
include_once 'include/conexion.php';
include_once 'include/fecha.php';
include_once 'include/variables.php';
include('lib/nusoap.php');
if(isset($_SESSION['user'])){
?>
<?php echo $doctype?>
<!-- Achivos CSS -->
<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="../DataTables/css/dataTables.bootstrap.css">
<link rel="stylesheet" href="../DataTables/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="../DataTables/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="css/call.css">
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
<script src="../js/libreriajs.js"></script>
<script>
$(document).ready(function(){
//Activar Menú
  $("#menu5").attr('class','active');

//Buscar datos del Colaborador
$('#agente').change(function (){
  var cedula = $(this).val();
  $(this).parent().removeClass('has-success has-error');;
  $.post( "include/buscarColaborador.php", {cedula:cedula}, function(data){
  })
  .done(function(data) {
   var obj = jQuery.parseJSON(data);
    $('#ci').val(obj.ci).parent().removeClass('has-error has-success');
    $('#ingreso').val(obj.ingreso).parent().removeClass('has-error has-success');
    $('#tiempo').val(obj.tiempo).parent().removeClass('has-error has-success');
    $('#usuario').val(obj.usuario).parent().removeClass('has-error has-success');
    $('#supervisor').val(obj.supervisor).parent().removeClass('has-error has-success');
  })//End function done
  .fail(function() {
  $('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');
  })//End funtion fail
  .always(function() {            
  });//End function always
});

//Mostrar Formulario Seleccionado
  $('#formulario').change(function (){
  	id = $(this).val();
  	$.post( "include/buscar_formulario.php", {id:id}, function(data) {    		    
      })
    .done(function(data) {
      $('#grupo').empty();
      //$('#mensajes').prepend(data);
      $.each(JSON.parse(data), function(idx, obj) {
        //Incluimos el panel
        $('#grupo').append('<div class="panel panel-primary"><div class="panel-heading text-center">' + obj.descripcion + '</div><div class="panel-body" id="panel'+ obj.id +'"></div></div>');
        var table = $('<table><thead></thead></table>');
        //Creamos la tabla de cada atributo
        $(table).attr('id', 'table'+ obj.id);
			  table.appendTo($('#panel' + obj.id));
        //mostramos el contenido
          $.each(obj.aspectos, function(index, cont){
            var row = $('<tr />');
  				  var aspecto = $('<th />');
              aspecto.addClass('text-center bg-primary').attr('colspan', '4');;
              aspecto.appendTo(row).append(cont.aspecto);
              row.appendTo($('#table' + obj.id));
              var titulos = $('<tr />');
              var grupo = $('<th />').addClass('text-center').append('Grupo').appendTo(titulos);
              var situacion = $('<th />').addClass('text-center').append('Situación').appendTo(titulos);
              var check = $('<th />').addClass('text-center').append('Cumple').appendTo(titulos);
              var puntos = $('<th />').addClass('text-center').append('%').appendTo(titulos);
              titulos.appendTo($('#table' + obj.id));
              $.each(cont.situaciones, function(index2, cont2){
                  var tr = $('<tr />');
                  var situacion = $('<td />');
                  var grupo = $('<td />');
                  var check = $('<td />').addClass('text-center');
                  var porc = $('<td />').addClass('text-center');
                  grupo.appendTo(tr).append(cont2.grupo);
                  situacion.appendTo(tr).append(cont2.situacion);
                  check.appendTo(tr).append('<input type="checkbox" class = "item ' +cont.aspecto+ '" atributo = "' + obj.descripcion + '" aspecto ="'+ cont.aspecto+ '" cambio = "0" checked>');
                  porc.appendTo(tr).append(cont2.porc);
                  tr.appendTo($('#table' + obj.id));
               });              
        	});//End each
        
        var total = $('<tr> />');
        var td1 = $('<td />').attr('colspan',3).addClass('text-right').append('TOTAL').appendTo(total);
        var td2 = $('<td />').attr('id', 'table'+ obj.id + 'total').append('100').appendTo(total);        
        total.appendTo($('#table' + obj.id));

         $(table).attr('class', 'table table-condensed table-striped table-bordered table-hover');			
       });//End each
      //Agregamos el boton guardar
       if (data.length >2){
       $('#grupo').append('<div class="col-xs-12 text-center"><button type="button" id="guardar" class="btn btn-primary" style="margin-top: 15px">Guardar</button></div>');
       }
    })
    .fail(function() {
      $('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');
      })    
    .always(function() {            
    });
  });//End Function

  //Selecionar / deseleccionar items
  $('body').on('change', '.item', function() {
    table = $(this).closest('table');
    cambio = $(this).attr('cambio');
    aspecto = $(this).attr('aspecto');
    aspecto = aspecto.replace(/ /g,".");
    //Obtenemos la fila y los Datos
    fila = $(this).closest('tr');
    porc = fila.find("td").eq(3).html();
    //Total de la tabla
    totalTable = $("#" + table.attr('id') + 'total').html();
    //Recorremos la tabla
    contador = 0;
    $("#" + table.attr('id') + ' tbody tr td input').filter('.' + aspecto).each(function (index){
        if ($(this).is(':checked')) {           
        }else{
          contador++;
        }
    });
    
    if ((contador == 1) && (parseInt(cambio) == 0)){
      nuevo = parseInt(totalTable) - parseInt(porc);
      $("#" + table.attr('id') + ' tbody tr td input').filter('.' + aspecto).each(function (index){
        $(this).attr('cambio', parseInt(cambio) + 1);
      });      
    }
    if (contador == 0){
      nuevo = parseInt(totalTable) + parseInt(porc);
      $("#" + table.attr('id') + ' tbody tr td input').filter('.' + aspecto).each(function (index){
          $(this).attr('cambio', 0);
      });      
    }

    if (nuevo < 0){
      nuevo = 0;
    }else if (nuevo > 100){
      nuevo = 100;
    }

    $("#" + table.attr('id') + 'total').html(nuevo);
  });//End Function

//Guardar la evaluación
  $('body').on('click', '#guardar', function(){
      var contador = 0;
    $("body select").each(function (index) { 
        if ($("option:selected", this).prop('index') == 0){
        $(this).parent().addClass('has-error');
        contador++;             
      }else{
        $(this).parent().removeClass('has-error has-warning').addClass('has-success');
      }//End if
    });//End each
    $("body input").each(function (index) { 
      if ($(this).val() == ''){
        $(this).parent().addClass('has-error');
        contador++;         
      }else{
        $(this).parent().removeClass('has-error has-warning').addClass('has-success');          
      }//End if          
     });//End each
    
    if (contador == 0){
      bootbox.confirm('¿Seguro que desea Guardar la Evaluación?', function(result){
        if (result == true){
          accion = 'nuevo';
          nombre = $( "#agente option:selected" ).text();
          cedula = $('#agente').val();
          ingreso = $('#ingreso').val();
          tiempo = $('#tiempo').val();
          supervisor = $('#supervisor').val();
          Usuario = $('#usuario').val();          

          //Recorremos los items para guardarlos
          var datos = [];
          $('body table tbody tr td input').each(function (index){
            atributo =  $(this).attr('atributo');
            aspecto =  $(this).attr('aspecto');
            fila = $(this).closest('tr');
            situacion = fila.find("td").eq(1).html();
            porc = fila.find("td").eq(3).html();
              if ($(this).is(':checked')) {           
                cumple = 1;
              }else{
                cumple = 0;
              }
              item =  atributo + '|' + aspecto + '|' + situacion + '|' + cumple + '|' + porc;  
              datos.push(item);
          });
          
          $.post( "include/guardar_evaluacion.php", {accion:accion, nombre:nombre, cedula:cedula, ingreso:ingreso, tiempo:tiempo, supervisor:supervisor, userid:usuario, datos:datos}, function(data){
            })
            .done(function(data) {            
              switch (data){                          
                case '1':
                  $('#grupo').empty();
                    $("body input").each(function (index) { 
                      $(this).val('').parent().removeClass('has-success has-error');
                    });
                    $("body select").each(function (index) { 
                      $(this).prop('selectedIndex',0).parent().removeClass('has-error has-success');
                    });//End each

                  $('#mensajes').prepend('<div class="alert alert-success text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Exito!</strong> Evaluación Guardada Correctamente</div>');                  
                break;
                case '0':
                  $('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');       
                break;              
              }//End switch                                         
            })//End function done
            .fail(function() {
              $('#mensajes').prepend('<div class="alert alert-danger text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Error!</strong> A Ocurrido un error, Intente más tarde</div>');
            })//End function fail          
            .always(function() {            
            });//End Allways
        }//End if
      });//End Function bootbox

    }//End if
  });

});//End Document Ready
</script>
</head>
<body>
	<?php echo $header?>
	<div class="container-fluid contenido">
	<?php echo $menu?>
	    <!-- Título -->
	    <div class="text-center">
	    	<h4>Evaluación de Calidad de Atención</h4>            
	    </div>
	    <div class="col-xs-10 col-xs-offset-1">
	    <div class="panel panel-primary text-center">
                <div class="panel-heading">
                    <h4 class="panel-title">Datos del Agente</h4>
                </div>            
                <div class="panel-body">
                    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                        <label for="agente">Nombre</label>  
                        <select id="agente" class="form-control">
                        	<option>Seleccionar...</option>
                        	<?php
                        		$buscar = mysql_query("SELECT nombre, ci FROM call_usuario WHERE nivel > 1 AND estatus != '0' ORDER BY nombre ASC");
                        		while($row = mysql_fetch_array($buscar)){
                        			echo '<option value="'.$row[1].'">'.utf8_encode($row[0]).'</option>';
                        		}//End while
                        	?>                        	
                        </select>      
                    </div>
                    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                        <label for="ci">CI</label>  
                 		    <input id="ci" class="form-control text-center" readonly="" />
                    </div>
                    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                        <label for="usuario">Nombre de Usuario</label>  
                        <input id="userid" class="form-control text-center" readonly="" />
                    </div>                    
                    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                        <label for="ingreso">Ingreso</label>  
                 		    <input id="ingreso" class="form-control text-center" readonly="" />
                    </div>
                    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                      <label for="tiempo">Tiempo en la Empresa (días)</label>  
                      <input id="tiempo" class="form-control text-center" readonly="" />
                    </div> 
                    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                      <label for="supervisor">Supervisor</label>  
                      <input id="supervisor" class="form-control text-center" readonly="" />
                    </div>
                    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                      <?php
                        $buscarForm = mysql_query("SELECT * FROM call_evaluacion_form WHERE estatus = '1'");
                        if(mysql_num_rows($buscarForm)){              
                          echo '<label for="formulario">Formulario</label>';
                          echo '<select id="formulario" class="form-control"><option>Seleccionar...</option>';
                          while($row = mysql_fetch_array($buscarForm)){
                            echo '<option value="'.$row[0].'">'.utf8_encode($row[1]).'</opction>';
                          }//End While
                          echo '</select>';           
                        }else{
                          echo 'No se Encontraron Formularios de Evaluación';
                        }//End if
                      ?>
                    </div>                    
                </div><!--End Pannel Body-->

		</div><!--End Pannel Primary-->
		</div><!-- End col -->
    <div class="panel-group col-xs-10 col-xs-offset-1" id="grupo">    
    </div><!-- End col -->      
    <!-- Div para contenido de los Mensajes -->
    <div id="mensajes" class="col-xs-12 col-md-8 col-md-offset-2 mensajes">
    </div><!-- End col -->
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