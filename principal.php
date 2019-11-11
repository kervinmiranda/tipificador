<?php

/*************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/	
include_once 'include/fecha.php';
include_once 'include/variables.php';

if(isset($_SESSION['user'])){
?>
    <?php echo $doctype?>
    <!-- Archivos CSS -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap-submenu.css">
    <link rel="stylesheet" href="css/call.css">

    <!-- Archivos JavaScript -->
    <script src="../js/jquery.js"></script>
    <script src="../bootstrap/js/bootstrap.js"></script>
    <script src="../bootstrap/js/bootstrap-submenu.js"></script>
    <script src="../js/jquery.numeric.js"></script>
    <script src="js/libreriajs.js"></script>
    <script>

    $(document).ready(function(){
    //Activar Menú
    	$("#menu1").attr('class','active');
    });
    </script>
    </head>
    <body>
    	<?php echo $header?>
    	<div class="container-fluid" style="min-height:78%">
        	<?php echo $menu?>
    	</div><!--End Container -->
    	<div class="container-fluid" style="min-height:7%"> 
            <di class="row">
            	<div class="col-xs-12 text-center">
                Compatible con <img src="imagenes/navegadores.png" width="151" height="31">
        		</div>
            </div>
        </div><!--End Container -->
    	<?php echo $footer?>
        </div>
    </div>
    </body>
    </html>
    <?php    
    }else{
    	header("location:index.php?alerta=salir");
    }
?>