<?php


include ('../includes/funcionesUsuarios.php');
include ('../includes/funcionesHTML.php');
include ('../includes/funciones.php');
include ('../includes/funcionesReferencias.php');

$serviciosUsuario = new ServiciosUsuarios();
$serviciosHTML = new ServiciosHTML();
$serviciosFunciones = new Servicios();
$serviciosReferencias 	= new ServiciosReferencias();

$fecha = date('Y-m-d');

session_start();

if (!isset($_SESSION['usua_sahilices']))
{
	$cadError = 'No tienes permiso para la descarga';
} else {
	if (isset($_GET['token'])) {

		$res = $serviciosReferencias->traerArchivosPorIdCliente($_GET['token']);

		if (mysql_num_rows($res)>0) {
		    $file = '../archivos/'.mysql_result($res, 0,'refclientes').'/'.mysql_result($res, 0,'idarchivo').'/'.mysql_result($res, 0,'imagen');
		    //die(var_dump($file));
			 /*
 			 header('Content-type: application/zip');
 		    header('Content-length: ' . filesize($file));
 		    readfile($file);
 			 */
 			 header('Location: '.$file);
		    //$cadError = 'Su descarga fue exitosa';
		} else {
			$cadError = 'No existe el archivo o fue borrado';
		}

	} else {

	    $cadError = 'No se selecciono ningun archivo';
	}

}

$resMenu = $serviciosHTML->menu($_SESSION['nombre_sahilices'],"Dashboard",$_SESSION['refroll_sahilices'],'');
?>


<!DOCTYPE HTML>
<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">



<title>Gesti&oacute;n: Estudio Contable</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">


<link href="../css/estiloDash.css" rel="stylesheet" type="text/css">



    <script type="text/javascript" src="../js/jquery-1.8.3.min.js"></script>
    <link rel="stylesheet" href="../css/jquery-ui.css">

    <script src="../js/jquery-ui.js"></script>

	<!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css"/>
	171
    <!-- Latest compiled and minified JavaScript -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="../css/chosen.css">
	<link rel="stylesheet" href="../css/bootstrap-datetimepicker.min.css">




   <link href="../css/perfect-scrollbar.css" rel="stylesheet">
   <link rel="stylesheet" href="../css/sb-admin-2.css"/>
      <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>-->
      <script src="../js/jquery.mousewheel.js"></script>
      <script src="../js/perfect-scrollbar.js"></script>
      <script>
      jQuery(document).ready(function ($) {
        "use strict";
        $('#navigation').perfectScrollbar();
      });
    </script>

    <style>
    	.valor {
    		color: #922B21;
    		text-align: right;
    	}

    	.horario {
    		display: inline-block;
		    font: normal normal normal 14px/1 FontAwesome;
		    font-size: inherit;
		    text-rendering: auto;
		    -webkit-font-smoothing: antialiased;
    	}

    	a.list-group-item-fuscia.active, a.list-group-item-fuscia.active:hover, a.list-group-item-fuscia.active:focus {
		    z-index: 2;
		    color: #fff;
		    background-color: #FF0DFF;
		    border-color: #FF0DFF;
		}

		a.list-group-item-yellow.active, a.list-group-item-yellow.active:hover, a.list-group-item-yellow.active:focus {
		    z-index: 2;
		    color: #fff;
		    background-color: #f0ad4e;
		    border-color: #f0ad4e;
		}

		.list-group-item-fuscia, .list-group-item-yellow:first-child {
		    border-top-right-radius: 4px;
		    border-top-left-radius: 4px;
		}

		.letraChica {
			display: inline-block;
		    font-size: 11px;
		    text-rendering: auto;
		    -webkit-font-smoothing: antialiased;
		}



    </style>

    <script src="../js/jquery.color.min.js"></script>
	<script src="../js/jquery.animateNumber.min.js"></script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>


</head>

<body>


<?php echo str_replace('..','../dashboard',$resMenu); ?>

<div id="content">
	<h3>Descargar</h3>

    <div class="boxInfoLargo">
        <div id="headBoxInfo">
        	<p style="color: #fff; font-size:18px; height:16px;">Descarga de archivos</p>

        </div>
    	<div class="cuerpoBox">
        	<form class="form-inline formulario" role="form">
        	<div class="row">
        		<div class="col-md-12">
					<h4><?php echo $cadError; ?></h4>
				</div>
            </div>


            </form>
    	</div>
    </div>


</div>









<script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
<script src="../bootstrap/js/dataTables.bootstrap.js"></script>

<script src="../js/bootstrap-datetimepicker.min.js"></script>
<script src="../js/bootstrap-datetimepicker.es.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	var months = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];


	$('table.table').dataTable({
		"order": [[ 0, "asc" ]],
		"language": {
			"emptyTable":     "No hay datos cargados",
			"info":           "Mostrar _START_ hasta _END_ del total de _TOTAL_ filas",
			"infoEmpty":      "Mostrar 0 hasta 0 del total de 0 filas",
			"infoFiltered":   "(filtrados del total de _MAX_ filas)",
			"infoPostFix":    "",
			"thousands":      ",",
			"lengthMenu":     "Mostrar _MENU_ filas",
			"loadingRecords": "Cargando...",
			"processing":     "Procesando...",
			"search":         "Buscar:",
			"zeroRecords":    "No se encontraron resultados",
			"paginate": {
				"first":      "Primero",
				"last":       "Ultimo",
				"next":       "Siguiente",
				"previous":   "Anterior"
			},
			"aria": {
				"sortAscending":  ": activate to sort column ascending",
				"sortDescending": ": activate to sort column descending"
			}
		  }
	} );






});
</script>

<script type="text/javascript">


$('.form_date').datetimepicker({
	language:  'es',
	weekStart: 1,
	todayBtn:  1,
	autoclose: 1,
	todayHighlight: 1,
	startView: 2,
	minView: 2,
	forceParse: 0,
	format: 'dd/mm/yyyy'
});
</script>

    <script src="../js/chosen.jquery.js" type="text/javascript"></script>
<script type="text/javascript">
    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
  </script>

</body>
</html>
