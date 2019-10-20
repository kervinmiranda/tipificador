<?php

/*************************************************************************************************************************

                                                         SISTEMA GEBNET

****************************************************************************************************************************/


include_once 'include/fecha.php';

include_once 'include/variables.php';

if(isset($_SESSION['user']) && ($_SESSION['nivel'] < 3)){

?>

<?php echo $doctype?>

<!-- Achivos CSS -->

<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">

<link rel="stylesheet" href="../DataTables/css/dataTables.bootstrap.css">

<link rel="stylesheet" href="../DataTables/css/responsive.bootstrap.min.css">

<link rel="stylesheet" href="../bootstrap/css/bootstrap-submenu.css">

<link rel="stylesheet" href="css/jquery-ui.css">

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



//Activa Menú

	$("#menu6").attr('class','active');

	

	//Mostrar Ventana Gráfico 1

	$(".grafico").click(function() {	

		id = ($(this).attr('id'));		 

		$('#ventana1').modal('toggle');

					

	});

	//Mostrar Ventana Gráfico 1

	$(".grafico2").click(function() {	

		id = ($(this).attr('id'));		 

		$('#ventana2').modal('toggle');					

	});

	

//Generar Grafico

	$("#boton").click(function (){

		if ($("#fecha1").val() == ''){

			$('#fecha1').parent().addClass('has-error');

			$('#fecha1').attr('placeholder','Campo Obligatorio');			

			return false;				

		}else{

			$('#fecha1').parent().removeClass('has-error').addClass('has-success');

		}

		if ($("#fecha2").val() == ''){

			$('#fecha2').parent().addClass('has-error');

			$('#fecha2').attr('placeholder','Campo Obligatorio');			

			return false;				

		}else{

			$('#fecha2').parent().removeClass('has-error').addClass('has-success');

		}

		fecha1 = $("#fecha1").val();

		fecha2 = $("#fecha2").val();		

		var form = document.createElement("form");

		var element1 = document.createElement("input"); 

		var element2 = document.createElement("input"); 

		var element3 = document.createElement("input");  

		form.method = "POST";

		form.target = "_blank";

		form.action = "grafico.php";   

		element1.name="fecha1";

		element1.value=fecha1;

		form.appendChild(element1);	

		element2.name="fecha2";

		element2.value=fecha2;		

		form.appendChild(element2);

		element3.name="grafico";

		element3.value=id;		

		form.appendChild(element3);
        
        $(document.body).append(form);

		form.submit();		

		$("#fecha1").val("").parent().removeClass('has-error has-success');

		$("#fecha2").val("").parent().removeClass('has-error has-success');

		$('#ventara1').modal('toggle');

	});//End Function

	

	//Generar Grafico Lineal

	$("#boton2").click(function (){

		if ($("#fecha3").val() == ''){

			$('#fecha3').parent().addClass('has-error');

			$('#fecha3').attr('placeholder','Campo Obligatorio');			

			return false;				

		}else{

			$('#fecha3').parent().removeClass('has-error').addClass('has-success');

		}

		fecha3 = $("#fecha3").val();				

		var form = document.createElement("form");

		var element1 = document.createElement("input"); 

		var element2 = document.createElement("input"); 

		form.method = "POST";

		form.target = "_blank";

		form.action = "grafico.php";

		element1.name="fecha3";

		element1.value=fecha3;

		form.appendChild(element1);	

		element2.name="grafico";

		element2.value=id;		

		form.appendChild(element2);		

		form.submit();		

		$("#fecha3").val("").parent().removeClass('has-error has-success');

		$('#ventara2').modal('toggle');

	});//End Function

	

   //Función para colocar los Textos a tipo fecha

	$('#fecha1').datepicker({dateFormat: 'dd/mm/yy', maxDate: 0, minDate:'-5Y', viewMode: 'years', onSelect: function(dateText, inst) {var lockDate = new Date($('#fecha1').datepicker('getDate'));

			lockDate.setDate(lockDate.getDate());

		$('input#fecha2').datepicker('option', 'minDate', lockDate);

			}

		});

		$("#fecha2").datepicker({dateFormat: 'dd/mm/yy', maxDate: 0 });	

		

		$("#fecha3").datepicker({ 

        dateFormat: 'yy-mm',

        changeMonth: true,

        changeYear: true,

        showButtonPanel: true,

		minDate:'-5Y',

		maxDate: 0,

		onClose: function(dateText, inst) {  

            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val(); 

            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val(); 

            $(this).val($.datepicker.formatDate('yy-mm', new Date(year, month, 1)));

        	}

    	});

    	$("#fecha3").focus(function () {

        	$(".ui-datepicker-calendar").hide();

        	$("#ui-datepicker-div").position({

            my: "center top",

            at: "center bottom",

            of: $(this)

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

    	<h4>Indicadores Gráficos</h4>

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



   	<div class="row" align="center">

    <div class="col-xs-12 col-sm-2"></div>

    <div class="col-xs-12 col-sm-8">   	

    <div class="panel panel-primary luminoso text-center">

    	<div class="panel-heading">

      		<h3 class="panel-title">Estadísticas</h3>

    	</div>

    	<div class="panel-body">

        	<div class="col-xs-12 col-sm-6 col-md-3">

                <label for="grafico1" class="col-xs-12 col-sm-12">Por Departamentos</label>

                <img src="imagenes/graph_02.gif" width="101" height="90" class="grafico cursor" id="grafico1"/>    

            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">

                <label for="grafico2" class="col-xs-12 col-sm-12">Por Motivos</label>

                <img src="imagenes/graph_03.gif" width="101" height="90" class="grafico cursor" id="grafico2"/>    

            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">

                <label for="grafico3" class="col-xs-12 col-sm-12">Total Mensual</label>

                <img src="imagenes/graph_01.gif" width="101" height="90" class="grafico2 cursor" id="grafico3"/>    

            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">

                <label for="grafico4" class="col-xs-12 col-sm-12">Por Analistas</label>

                <img src="imagenes/graph_04.gif" width="101" height="90" class="grafico cursor" id="grafico4"/> 

            </div>                     	         

		</div>

  	</div><!--End panel -->

    </div><!--End col -->

    <div class="col-xs-12 col-sm-2"></div>

    </div><!--End row -->

        

    <!-- Modal Reporte 1 -->

    <div id="ventana1" class="modal fade" role="dialog" tabindex='-1'>

    	<div class="modal-dialog modal-sm">

        	<div class="panel panel-primary luminoso text-center">

            	<button type="button" class="close" data-dismiss="modal">&times;</button>

                <div class="panel-heading">

                    <h3 class="panel-title">Generar Gráfico</h3>

                </div>

                <div class="panel-body">        	

                    <div class="form-group col-xs-12 text-center">

                        <label for="fecha1">Fecha Inicial</label>

                        <input type="text" name="fecha1" id="fecha1" class="form-control" readonly style="background-color:#FFF">

                    </div>

                    <div class="form-group col-xs-12 text-center">

                        <label for="fecha2">Fecha Final</label>

                        <input type="text" name="fecha2" id="fecha2" class="form-control" readonly style="background-color:#FFF">

                    </div>            

                    <div class="form-group col-xs-12 text-center">

                        <img src="imagenes/grafico.png" id="boton" class="cursor"/>

                    </div>                    	         

                </div>

            </div><!--End panel -->        	      	                                   

    	</div><!-- End Dialog -->

    </div><!-- end Modal -->    

    

    <!-- Modal Reporte 2 -->

    <div id="ventana2" class="modal fade" role="dialog" tabindex='-1'>

    	<div class="modal-dialog modal-sm">

        	<div class="panel panel-primary luminoso text-center">

                <div class="panel-heading">

                    <h3 class="panel-title">Generar Gráfico</h3>

                </div>

                <div class="panel-body">        	

                    <div class="form-group col-xs-12 text-center">

                        <label for="fecha3">Mes y A&ntilde;o</label>

                        <input type="text" name="fecha3" id="fecha3" class="form-control" readonly>

                    </div>                        

                    <div class="form-group col-xs-12 text-center">

                        <img src="imagenes/grafico.png" id="boton2" class="cursor"/>

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