<?php
session_start();
if (!isset($_SESSION['usua_sahilices']))
{
 # header('Location: ../../../../error.php');
} else {
include ('../../../../class_include.php');


$baseHTML = new BaseHTML();
$baseHTML->setContentHeader ('Documentos ', 'Home/Contrato/Cliente/Documentos');

$dataContratoGlobal = new ServiciosSolicitudes();
$dataContratoGlobal->cargarDatosContratoGlobal();
$idContratoGlobal = $dataContratoGlobal->getDato('idcontratoglobal');

$usuario = new Usuario();
$idUsuario =  $usuario->getUsuarioId();

#$dataContratoGlobal->cargarDoctosContratoGlobal();

$form = new FormularioSolicitud();
#$_GET['documento'] = 20;
$iddocumento = isset($_GET['documento'])?$_GET['documento']:1 ;

$lectura = $dataContratoGlobal->getLectura();
$form->setDatos($dataContratoGlobal->getDatos());
$form->set_lectura($lectura);

$contenidoformularioNuevo = $form->formaDocumentos();



$page = new Formulario();
$page->add_content($contenidoformularioNuevo);
$idFormulario = 'Doctos';
$action = (empty($idContratoGlobal))?'agregarDocumentos':'editarDocumentos';
$title = "Cargar la documentación requerida";
$formularioCARD = $page->htmlCardFormulario('', 'card-info', 10, $idFormulario, $action, $title);


$documentos = new ServiciosReferencias();
$resDocumentaciones = $documentos->traerDocumentacionPorTipoCreditoDocumentacionCompleta($idContratoGlobal,'1');
$resDocumentosContratoGlobal = $documentos->traerDocumentacionPorTipoCreditoDocumentacion($idContratoGlobal, $iddocumento);
$resDocumentacion = $documentos->traerDocumentosPorId($iddocumento);
$resEstados = $documentos->traerEstadodocumentos();

if (mysql_num_rows($resDocumentosContratoGlobal) > 0) {
	

	$idcontratoglobaldocumento = mysql_result($resDocumentosContratoGlobal,0,'idcontratoglobaldocumento');

	$estadoDocumentacion = mysql_result($resDocumentosContratoGlobal,0,'descripcion');

	$color = mysql_result($resDocumentosContratoGlobal,0,'color');

	$span = '';
	switch (mysql_result($resDocumentosContratoGlobal,0,'idestadodocumento')) {
		case 1:
			$span = 'text-info glyphicon glyphicon-plus-sign';
		break;
		case 2:
			$span = 'text-danger glyphicon glyphicon-remove-sign';
		break;
		case 3:
			$span = 'text-danger glyphicon glyphicon-remove-sign';
		break;
		case 4:
			$span = 'text-danger glyphicon glyphicon-remove-sign';
		break;
		case 5:
			$span = 'text-success glyphicon glyphicon-remove-sign';
		break;
	}
} else {
	

	$iddocumentacionasociado = 0;

	$estadoDocumentacion = 'Falta Cargar';

	$color = 'gray';

	$span = 'text-info glyphicon glyphicon-plus-sign';
}





}
?>

<!DOCTYPE html>
<html>
<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> CRM | Financiera CREA </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
 <!-- Font Awesome -->  
  <!-- Ionicons -->  
  <!-- Theme style -->  
  <!-- Google Font: Source Sans Pro -->
  <?php echo $baseHTML->getCssAdminLTE(); ?>

  <!-- Dropzone Css -->
	<link href="../../../../plugins/dropzone/dropzone.css" rel="stylesheet">
<style type="text/css">


  .card .encabezadoDatos2{
      color: #ee6e73;
      font-weight: 700;
      padding-top: 15;
      padding-bottom:  15;
      font-size: 18px;
    }

    .card .encabezadoDatos{
      
      font-weight: 100;
      padding-top: 15;
      padding-bottom:  20;
      
     /* border-radius: .25rem;*/
      /*border-left: 5px solid #e9ecef; */
      border-bottom: 2px solid rgba(0,0,0,.125);
      /*border-left-color: #117a8b;*/
      border-bottom-color: #287c8a;
      
    background-color: #fff;
   
    }

    <style>
		.alert > i{ vertical-align: middle !important; }
		.easy-autocomplete-container { width: 400px; z-index:999999 !important; }
		#codigopostal { width: 400px; }
		.pdfobject-container { height: 30rem; border: 1rem solid rgba(0,0,0,.1); }

		  .thumbnail2 {
		    display: block;
		    padding: 4px;
		    margin-bottom: 20px;
		    line-height: 1.42857143;
		    background-color: #fff;
		    border: 1px solid #ddd;
		    border-radius: 4px;
		    -webkit-transition: border .2s ease-in-out;
		    -o-transition: border .2s ease-in-out;
		    transition: border .2s ease-in-out;
			 text-align: center;
		}
		.progress {
			background-color: #1b2646;
		}

		.btnDocumentacion {
			cursor: pointer;
		}

				.img-responsive,
.thumbnail > img,
.thumbnail a > img,
.carousel-inner > .item > img,
.carousel-inner > .item > a > img {
  display: block;
  max-width: 100%;
  height: auto;
}

	</style>
</style>
</head>

<body class="hold-transition sidebar-mini layout-navbar-fixed control-sidebar-push skin-blue-light  ">
<div class="wrapper">
  <!-- Navbar -->
  
  <!-- Navbar -->
  <?php echo $baseHTML->getNavBar(); ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container --> 
  <?php echo $baseHTML->getSideBar(); ?>
  <!-- / sidebar Container -->





  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
   <?php echo $baseHTML->getContentHeader (); ?>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

             

               
       

<div class="row"> 
			<?php
			$x_nombre_docto = '';
			while ($row = mysql_fetch_array($resDocumentaciones)) {
					if ((($row['idestadodocumento'] != 5) && ($row['idestadodocumento'] != 6) && ($row['idestadodocumento'] != 7)) && $row['estadodocumentacion'] != 'Falta') { 

						if( $row['iddocumento'] == $iddocumento){
							$x_nombre_docto = $row["documento"];
						}
				?>
			<div class="col-md-4 col-sm-4 col-xs-12">
         <!-- <div class="info-box <?php echo $row['color'] ?> hover-zoom-effect btnDocumentacion" id="<?php echo $row['iddocumento']; ?>">
            

            <div class="info-box-content">
              <span class="info-box-text"><?php echo $row['documento']; ?></span>
              <span class="info-box-number"><?php echo $row['estadodocumentacion'];?><?php if($row['estadodocumentacion'] == 'Falta') echo   $row['doctoReq']; ?></span>
            </div>
            
          </div>-->
         


          <button type="button" class='btn btn-block <?php echo  str_replace('bg','btn',$row['color']); ?> btn-xs mb-2 p-1 btnDocumentacion' id="<?php echo $row['iddocumento']; ?>">
          	<h6><?php echo $row['documento']; ?><br>
          <?php echo $row['estadodocumentacion']; if($row['estadodocumentacion'] == 'Falta'){ echo  $row['doctoReq'];}else{echo "<br>";} ?> </h6></button>
        </div>
        <!-- /.col -->
        <?php } } ?>

        </div>

       
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="card card-info"><div class="card-header"><h1 class="card-title "><?php echo $x_nombre_docto;?></h1></div>
	<div class="card-body">			<div class="row">
								<!-- <button type="button" class="btn bg-red waves-effect btnEliminar">
									<i class="fa fa-remove"></i>
									<span>ELIMINAR</span>
								</button> -->
							</div>
							<div class="row">
								<a href="javascript:void(0);" class="thumbnail timagen1">
									<img class="img-responsive">
								</a>
								<div id="example1"></div>
							</div>
							<div class="row">
								<div class="alert bg-<?php echo $color; ?>">
									<h4>
										Estado: <b><?php echo $estadoDocumentacion; ?></b>
									</h4>
								</div>

							</div></div>
	</div>
</div>
</div>


        

     

            



    <!-- Modal -->
<div class="modal fade" id="aviso_doctos" tabindex="-1" role="dialog" aria-labelledby="avisodeprivaciadad" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <h5 class="modal-title d-flex  justify-content-end" id="exampleModalLabel"></h5>
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="">
          
          <p>
          <div class="d-flex justify-content-center ">
           <b> IMPORTANTE!</b>
          </div>
          <div class="text-justify">       
          Antes de enviar los documentos por favor verifique que cumplen con los requisitos establecidos, enviar documentos que no cumplen con los requisitos puede retrasar el trámite.

          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        
    </div>
  </div>
</div>
</div>

             



      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

   <!-- Control Sidebar --> 
  <?php echo $baseHTML->getControlSideBar(); ?> 
  <!-- /.control-sidebar -->
   
  <?php echo $baseHTML->getFooter(); ?> 

 
 

</div>
<!-- ./wrapper -->
<!-- jQuery -->
<!-- Bootstrap 4 -->
<!-- AdminLTE App -->
 
 
 <?php echo $baseHTML->getJsAdminLTE(); ?> 
 <!-- Javascript -->
  <script type="text/javascript" src="../../../../plugins/bootstrapvalidator/dist/js/bootstrapValidator.js"></script>
<script src="../../../../plugins/jquery/jquery.min.js"></script>
<script src="../../../../plugins/bootstrap/js/bootstrap.js"></script>
  <script src="../../../../plugins/dropzone/dropzone.js"></script>

<script src="../../../../js/pdfobject.min.js"></script>




        


<script type="text/javascript">
	$(document).ready(function () {


	$('.btnDocumentacion').click(function() {
		console.log("Entra a la funcion:");
			idTable =  $(this).attr("id");
			url = "verdocumentos.php?id=<?php echo $idContratoGlobal;?>&documento=" + idTable;
			$(location).attr('href',url);
		});

	function traerImagen(contenedorpdf, contenedor) {
		
			$.ajax({
				data:  {idContratoGlobal:<?php echo $idContratoGlobal; ?>,
						iddocumento:<?php echo $iddocumento; ?>,
						accion:'traerDocumentacionPorContratoDocumentacion'},
				url:   '../../../../ajax/ajax.php',
				type:  'post',
				beforeSend: function () {
					$("." + contenedor + " img").attr("src",'');
				},
				success:  function (response) {
					var cadena = response.datos.type.toLowerCase();
					

					if (response.datos.type != '') {
						
						if (cadena.indexOf("pdf") > -1) {
							PDFObject.embed(response.datos.imagen, "#"+contenedorpdf);
							$('#'+contenedorpdf).show();
							$("."+contenedor).hide();

						} else {
							$("." + contenedor + " img").attr("src",response.datos.imagen);
							$("."+contenedor).show();
							$('#'+contenedorpdf).hide();	
						}
						
					}

					if (response.error) {

						$('.btnEliminar').hide();
						$('.guardarEstado').hide();
					} else {

						$('.btnEliminar').show();
						$('.guardarEstado').show();
					}

					

				}
			});
		}

		traerImagen('example1','timagen1');

		Dropzone.prototype.defaultOptions.dictFileTooBig = "Este archivo es muy grande ({{filesize}}MiB). Peso Maximo: {{maxFilesize}}MiB.";

		Dropzone.options.frmFileUpload = {
			maxFilesize: 30,
			acceptedFiles: ".jpg,.jpeg,.pdf",
			accept: function(file, done) {
				done();
			},
			init: function() {
				this.on("sending", function(file, xhr, formData){
					formData.append("idContratoGlobal", '<?php echo $idContratoGlobal; ?>');
					formData.append("iddocumentacion", '<?php echo $iddocumento; ?>');
					formData.append("usuarioId", '<?php echo $idUsuario; ?>');
				});
				this.on('success', function( file, resp ){
					traerImagen('example1','timagen1');
					$('.lblPlanilla').hide();
					swal("Correcto!", resp.replace("1", "Documentos completos"), "success");
					$('.btnGuardar').show();
					$('.infoPlanilla').hide();
					//location.reload();
					console.log(resp);
					setTimeout(function(){
						if(resp == 1){
							url = "../notificacionCliente.php";
							$(location).attr('href',url);
						}else{
						//location.reload();
							url = "subirdocumentosi.php?id=<?php echo $idContratoGlobal;?>&documento=<?php echo $iddocumento; ?>";
							$(location).attr('href',url);
						}
					}, 3000);
				});

				this.on('error', function( file, resp ){
					console.log("Error=>"+resp.replace("1", ""));
					//swal("Error!", resp.replace("1", ""), "warning");
				});
			}
		};


        
    

		$(".body").on("click",'.btnEliminar', function(){
			$('#lgmEliminar').modal();

		});

		$('.eliminar').click(function() {
			$.ajax({
				url: '../../../../ajax/ajax.php',
				type: 'POST',
				// Form data
				//datos del formulario
				data: {accion: 'eliminarDocumentacionContratoGlobal',idContratoGlobal: <?php echo $idContratoGlobal; ?>, iddocumento: <?php echo $iddocumento; ?>},
				//mientras enviamos el archivo
				beforeSend: function(){
					$('.btnEliminar').hide();
				},
				//una vez finalizado correctamente
				success: function(data){

					if (data.error == false) {
						swal("Ok!", data.leyenda , "success");
						traerImagen('example1','timagen1');

					} else {
						swal("Error!", data.leyenda, "warning");

						$('.btnEliminar').show();
					}
				},
				//si ha ocurrido un error
				error: function(){
					$(".alert").html('<strong>Error!</strong> Actualice la pagina');
					$("#load").html('');
				}
			});
		});

		
	



		function setButtonWavesEffect(event) {
			$(event.currentTarget).find('[role="menu"] li a').removeClass('waves-effect');
			$(event.currentTarget).find('[role="menu"] li:not(.disabled) a').addClass('waves-effect');
		}






});
</script>
</body>
</html>