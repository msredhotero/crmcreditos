<?php
session_start();
if (!isset($_SESSION['usua_sahilices']))
{
 # header('Location: ../../../../error.php');
} else {
include ('../../../../class_include.php');


$baseHTML = new BaseHTML();
$baseHTML->setContentHeader ('Documentos ', 'Home/Contrato/Cliente/Documentos');
$idContratoGlobal = isset($_GET['id'])?$_GET['id']:NULL;
$dataContratoGlobal = new ServiciosSolicitudes($idContratoGlobal);
$dataContratoGlobal->cargarDatosContratoGlobal();
$idContratoGlobal = $dataContratoGlobal->getDato('idcontratoglobal');

$usuario = new Usuario();
$idUsuario =  $usuario->getUsuarioId();

#$dataContratoGlobal->cargarDoctosContratoGlobal();

$form = new FormularioSolicitud();
$documentos = new ServiciosReferencias();

$resDocumentaciones1 = $documentos->traerDocumentacionPorTipoCreditoDocumentacionCompletaCliente($idContratoGlobal,'1');
$idDocto = mysql_result($resDocumentaciones1,0,'iddocumento');
#echo $idDocto;
#$_GET['documento'] = 20;
$iddocumento = isset($_GET['documento'])?$_GET['documento']:$idDocto ;

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



$resDocumentaciones = $documentos->traerDocumentacionPorTipoCreditoDocumentacionCompletaCliente($idContratoGlobal,'1');
$resDocumentosContratoGlobal = $documentos->traerDocumentacionPorTipoCreditoDocumentacion($idContratoGlobal, $iddocumento);
$resDocumentacion = $documentos->traerDocumentosPorId($iddocumento);
$resEstados = $documentos->traerEstadodocumentos();
$resDocumentacionesCompletas = $documentos->traerDocumentacionPorTipoCreditoDocumentacionCompletaCliente($idContratoGlobal,'1');


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


#//
$verBotonEnviarDoctos = ($idContratoGlobal >0)?true: false;

while ($rowDoctosCompletos = mysql_fetch_array($resDocumentacionesCompletas)) {
	$requerido = $rowDoctosCompletos['req'];
	$estadoDocto = $rowDoctosCompletos['estadodocumentacion'];

	if(($requerido ==1 && $estadoDocto !='Cargada') || $idContratoGlobal < 1 ){
		$verBotonEnviarDoctos = false;
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
		.pdfobject-container { height: 30rem; border: 1rem solid rgba(0,0,0,.1);
    width: 100% }

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
       width: 70%
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
  max-width: 100%;
  height: auto;
}

.btn-gray {
    background-color: #6c757d;
    border-color: #6c757d;
    color: #fff;
}

.btn-gray:hover {
    background-color: #565d64;
    border-color: #6c757d;
    color: #fff;
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

             
  <div class=" lead  ">
                                   
                Asegurese de que en los documentos que adjunte sean visibles todos los bordes, la información sea legible y las imagenes sean nítidas<br> Puede ver ejemplos de documentos que son aceptables 

                <a href="#" class="alert-link" data-toggle="modal" data-target="#aviso_doctos">aquí</a>
                <p> 
                </div>
                </div>
       
       <?php if($verBotonEnviarDoctos){?> 
       <div class="row col-md-12 col-sm-12 col-xs-12">

       	<button type="button" class='btn btn-block btn-warning  btn-xs mb-2 p-1' id="enviarDoctos">
          	<h5>Documentos completos!
           </h5>

          	<h6><b><u>Click aquí para enviar su documentación</u></b>
           </h6></button>
       	

       </div>
   <?php }?>

<div class="row"> 

  
			<?php
			while ($row = mysql_fetch_array($resDocumentaciones)) {
				#print_r($row);
					if ((($row['idestadodocumento'] != 5) && ($row['idestadodocumento'] != 6) && ($row['idestadodocumento'] != 7)) ) { 

						if(($row['iddocumento']== 22 && $row['req'] == 1 ) || ($row['iddocumento']== 21 && $row['req'] == 1 ) || ($row['iddocumento']!= 22 && $row['iddocumento']!= 21)){
				?>
			<div class="col-md-4 col-sm-4 col-xs-12">
         <!-- <div class="info-box <?php echo $row['color'] ?> hover-zoom-effect btnDocumentacion" id="<?php echo $row['iddocumento']; ?>">
            

            <div class="info-box-content">
              <span class="info-box-text"><?php echo $row['documento']; ?></span>
              <span class="info-box-number"><?php if($row['estadodocumentacion'] == 'Falta') echo   $row['doctoReq']; ?></span>
            </div>


           
          </div>-->
          <!-- /.info-box -->
          <button type="button" class='btn btn-block <?php echo  str_replace('bg','btn',$row['color']); ?> btn-xs mb-2 p-1 btnDocumentacion' id="<?php echo $row['iddocumento']; ?>">
          	<h6><?php echo $row['documento']; ?><br>
          <?php echo $row['estadodocumentacion']; if($row['estadodocumentacion'] == 'Falta'){ echo  $row['doctoReq'];}else{echo "<br>";} ?> </h6></button>
        </div>
        <!-- /.col -->
        <?php }} } ?>

        </div>

        <div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="card card-info">
						<div class="card-header">
							<h1 class="card-title ">
								CARGAR/MODIFICAR <?php echo mysql_result($resDocumentacion,0,'decripcion'); ?> 
							</h1>
							
						</div>
						<div class="body">
							
							<form action="subir.php" id="frmFileUpload" class="dropzone" method="post" enctype="multipart/form-data">
								<div class="dz-message">
									<div class="icon">
										<i class="fa fa-mouse "></i>
									</div>
									<h3><u>Haga click aquí para cargar o cambiar una imagen o un PDF de <?php echo mysql_result($resDocumentacion,0,'decripcion'); ?>  </u></h3>
								</div>
								<div class="fallback">
									<input name="file" type="file" id="archivos" />
									<input type="text" id="idasociado" name="idasociado" value="<?php echo $idContratoGlobal; ?>" />
									<input type="text" id="a" name="a" value="<?php echo $idContratoGlobal; ?>" />
									<input type="text" id="b" name="b" value="<?php echo $idContratoGlobal; ?>" />
								</div>
							</form>
						</div>
					</div>
				</div>
			
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
	<div class="card card-info"><div class="card-header"><h1 class="card-title ">Archivo cargado</h1></div>
	<div class="card-body">			<div class="row">
								<!-- <button type="button" class="btn bg-red waves-effect btnEliminar">
									<i class="fa fa-remove"></i>
									<span>ELIMINAR</span>
								</button> -->
							</div>
							<div class="row d-flex justify-content-center">
								<a href="javascript:void(0);" class="thumbnail timagen1">
									<img class="img-responsive">
								</a>
								<div id="example1"></div>
							</div>
							<div class="row">
								<div class="btn btn-block btn-<?php echo str_replace('bg-','',$color); ?>">
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


 <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Documentos muestra</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                  <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="5"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="6"></li>
                     <li data-target="#carouselExampleIndicators" data-slide-to="7"></li>
                    
                  </ol>
                  <div class="carousel-inner">
                   
                    <div class="carousel-item active">
                    	<div class="h4 strong text-primary"><b>Documento válido</b></div>
                      <img class="d-block w-100" src="../../../../imagenes/muestra/fotosDoctos/COMPROBANTE DE DOMICILIO.jpg" alt="First slide">
                    </div>

                     <div class="carousel-item ">
                    	<div class="h4 strong text-danger"><b>Documento  inválido</b> </div>

                      <img class="d-block w-100" src="../../../../imagenes/muestra/fotosDoctos/COMPROBANTE DE DOMICILIO incorrecto.jpg" alt="First slide">
                    </div>

                    
                    <div class="carousel-item">
                    	<div class="h4 strong text-primary"><b>Documento válido</b></div>
                      <img class="d-block w-100" src="../../../../imagenes/muestra/fotosDoctos/EDO CTA correcto.jpg" alt="First slide">
                    </div>

                    <div class="carousel-item ">
                    	<div class="h4 strong text-danger"><b>Documento  inválido</b> </div>

                      <img class="d-block w-100" src="../../../../imagenes/muestra/fotosDoctos/EDO CTA incorrecto.jpg" alt="First slide">
                    </div>

                     
                    <div class="carousel-item">
                    	<div class="h4 strong text-primary"><b>Documento válido</b></div>
                      <img class="d-block w-100" src="../../../../imagenes/muestra/fotosDoctos/INE frente.jpg" alt="First slide">
                    </div>

                    <div class="carousel-item ">
                    	<div class="h4 strong text-danger"><b>Documento  inválido</b> </div>

                      <img class="d-block w-100" src="../../../../imagenes/muestra/fotosDoctos/ID incorrecta.png" alt="First slide">
                    </div>

					<div class="carousel-item">
                    	<div class="h4 strong text-primary"><b>Documento válido</b></div>
                      <img class="d-block w-100" src="../../../../imagenes/muestra/fotosDoctos/INE reverso.jpg" alt="First slide">
                    </div>
                    <div class="carousel-item ">
                    	<div class="h4 strong text-danger"><b>Documento  inválido</b> </div>

                      <img class="d-block w-100" src="../../../../imagenes/muestra/fotosDoctos/ID incorrecta reverso.png" alt="First slide">
                    </div>
                    
                    




                  </div>
                  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Anterior</span>
                  </a>
                  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Siguiente</span>
                  </a>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>









        
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        
    </div>
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

    function isMobile(){
      
      return (
          (navigator.userAgent.match(/Android/i)) ||
          (navigator.userAgent.match(/webOS/i)) ||
          (navigator.userAgent.match(/iPhone/i)) ||
          (navigator.userAgent.match(/iPod/i)) ||
          (navigator.userAgent.match(/iPad/i)) ||
          (navigator.userAgent.match(/BlackBerry/i))
      );
  }


	$('.btnDocumentacion').click(function() {
		console.log("Entra a la funcion:");
			idTable =  $(this).attr("id");
			url = "subirdocumentosiDocto.php?id=<?php echo $idContratoGlobal;?>&documento=" + idTable;
			url2 = "subirdocumentosiListaDoctos.php?id=<?php echo $idContratoGlobal;?>&documento=" + idTable;
      
     

      if(isMobile()){
        var win = window.open(url, '_blank');
      }else{
         $(location).attr('href',url2);
      }
		});

	$('#enviarDoctos').click(function() {
		console.log("Entra a la funcion:");	
			$.ajax({
				data:  {idContratoGlobal:<?php echo $idContratoGlobal; ?>,
						iddocumento:'1',
						dc:'1'},
				url:   'subir.php',
				type:  'post',
				beforeSend: function () {
					//$("." + contenedor + " img").attr("src",'');
				},
				success:  function (response) {
					if (response== '1') {
						url = "../notificacionCliente.php";
							$(location).attr('href',url);						
					}

					if (response.error) {
						$('.btnEliminar').hide();
						$('.guardarEstado').hide();
					} 

					

				}
			});

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
          formData.append("testpost", '<?php echo "prueba datos post"; ?>');
				});
				this.on('success', function( file, resp ){
					traerImagen('example1','timagen1');
					$('.lblPlanilla').hide();
					swal("Correcto!", resp.replace("DC", "Documentos completos"), "success");
					$('.btnGuardar').show();
					$('.infoPlanilla').hide();
					//location.reload();
					console.log(resp);
					setTimeout(function(){
						if(resp == 'DC'){
							url = "../notificacionCliente.php";
							$(location).attr('href',url);
						}else{
						//location.reload();
							url = "subirdocumentosiListaDoctos.php?id=<?php echo $idContratoGlobal;?>&documento=<?php echo $iddocumento; ?>";
              //url2 = "subirdocumentosiListaDoctos.php?id=<?php echo $idContratoGlobal;?>&documento=" + iddocumento;
							$(location).attr('href',url);
              //location.reload();
						}
					}, 3000);
				});

				this.on('error', function( file, resp ){
					console.log("Error=>"+resp.replace("1", ""));
					//swal("Error!", resp.replace("1", ""), "warning");
				});
			}
		};


	var myDropzone = new Dropzone("#archivos", {
			params: {
				 idasociado: <?php echo $idContratoGlobal; ?>,
				 idasociado2: <?php echo $idContratoGlobal; ?>,				
				 iddocumentacion: <?php echo $iddocumento; ?>
			},
			url: 'subir.php',

		});
        
    

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