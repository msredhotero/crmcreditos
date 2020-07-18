<?php
session_start();
if (!isset($_SESSION['usua_sahilices']))
{
 # header('Location: ../../../../error.php');
} else {
include ('../../../../class_include.php');


$baseHTML = new BaseHTML();

$idContratoGlobal=$_GET["id"];
$dataContratoGlobal = new ServiciosSolicitudes($idContratoGlobal);
$dataContratoGlobal->cargarDatosContratoGlobal();
$idContratoGlobal = $dataContratoGlobal->getDato('idcontratoglobal');
$nombre_cliente = ($dataContratoGlobal->getDato('nombre')." ".$dataContratoGlobal->getDato('paterno')." ".$dataContratoGlobal->getDato('materno'));
$baseHTML->setContentHeader ('<small>Aprobar documentos de '.($nombre_cliente).'</small>', 'Home/Contrato/Cliente/Documentos');
$documentos = new ServiciosReferencias();
$funciones = new Servicios();
$usuario = new Usuario();
$idUsuario =  $usuario->getUsuarioId();
$usuarioRol = $usuario->getRolId();

#$dataContratoGlobal->cargarDoctosContratoGlobal();

$form = new FormularioSolicitud();
#$_GET['documento'] = 20;


$resDocId = $documentos->traerDocumentacionPorTipoCreditoDocumentacionCompletaClienteAdministracion($idContratoGlobal,1);
$rowF = mysql_fetch_array($resDocId);
$idDoc1=$rowF['iddocumento'];
$iddocumento = isset($_GET['documento'])?$_GET['documento']:$idDoc1 ;


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



$resDocumentaciones = $documentos->traerDocumentacionPorTipoCreditoDocumentacionCompletaClienteAdministracion($idContratoGlobal,1);
$resDocumentaciones2 = $documentos->traerDocumentacionPorTipoCreditoDocumentacionCompletaClienteAdministracion($idContratoGlobal,1);
$resDocumentosContratoGlobal = $documentos->traerDocumentacionPorTipoCreditoDocumentacion($idContratoGlobal, $iddocumento);

$resDocumentaciones2 =$documentos->traerDocumentacionPorTipoCreditoDocumentacion($idContratoGlobal, $iddocumento);

$resDocumentacionesCompletas = $documentos->traerDocumentacionPorTipoCreditoDocumentacionCompletaClienteAdministracion($idContratoGlobal,'1');
#$rowDosc = mysql_fetch_array($resDocumentaciones2);

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


$verBotonEnviarDictamen = true;
while ($rowDoctosCompletos = mysql_fetch_array($resDocumentacionesCompletas)) {
	
	$requerido = $rowDoctosCompletos['req'];
	$estadoDocto = $rowDoctosCompletos['estadodocumentacion'];
	$idestadodocumento = $rowDoctosCompletos['idestadodocumento'];

	if($requerido ==1 && ($idestadodocumento == 1 || $idestadodocumento == '' )){
		$verBotonEnviarDictamen = false;
	}

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
		.pdfobject-container { height: 30rem; border: 1rem solid rgba(0,0,0,.1);width: 100% }

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

.btn-green {
    background-color: #28a745;
     border-color: #28a745;
     color: #fff;
}

.btn-green:hover {
    background-color: #1e7e34;
     border-color: #1e7e34;
     color: #fff;
}

.btn-red {
    background-color: #dc3545;
     border-color: #dc3545;
     color: #fff;
}

.btn-red:hover {
    background-color: #bd2130;
     border-color: #bd2130;
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

             
  
 <?php if($verBotonEnviarDictamen){?>        
<div class="row">
	 <div class="row col-md-12 col-sm-12 col-xs-12">

       	<button type="button" class='btn btn-block btn-warning  btn-xs mb-2 p-1' id="enviarDictamen">
          	<h5>Revisión completa!
           </h5>

          	<h6><b><u>Click aquí para enviar la dictaminación</u></b>
           </h6></button>
       	

       </div>

</div>
 <?php }?>
<div class="row"> 
			<?php
			while ($row = mysql_fetch_array($resDocumentaciones)) {
				#print_r($row);
				if( $row['iddocumento'] == $iddocumento){
					$x_nombre_docto = $row["documento"];
				}
				
					if ( ($row['idestadodocumento'] != 6) && ($row['idestadodocumento'] != 7)) { 

						if(($row['iddocumento']== 22 && $row['req'] == 1 ) || ($row['iddocumento']== 21 && $row['req'] == 1 ) || ($row['iddocumento']!= 22 && $row['iddocumento']!= 21)){
				?>
			<div class="col-md-4 col-sm-4 col-xs-12">
          <!--<div class="info-box <?php echo $row['color'] ?> hover-zoom-effect btnDocumentacion" id="<?php echo $row['iddocumento']; ?>">            

            <div class="info-box-content">
              <span class="info-box-text"><?php echo $row['documento']; ?></span>
              <span class="info-box-number"><?php echo $row['estadodocumentacion'];?><?php if($row['estadodocumentacion'] == 'Falta') echo   $row['doctoReq']; ?> </span>
            </div>            
          </div>-->

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
									<h3><u>Haga click aquí para cargar o cambiar una imagen o un PDF</u> </h3>
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
	<div class="card card-info"><div class="card-header"><h1 class="card-title "> <?php echo $x_nombre_docto ;?></h1></div>
	<div class="card-body">			<div class="row">
								<!-- <button type="button" class="btn bg-red waves-effect btnEliminar">
									<i class="fa fa-remove"></i>
									<span>ELIMINAR</span>
								</button> -->
							</div>
							<div class="row d-flex justify-content-center">
								
								<a href="javascript:void(0);" class="thumbnail timagen1">
									<img class="img-responsive ">
								</a>
								<div id="example1"></div>
							</div>
							<div class="row">
								<div class="btn btn-block btn-<?php echo str_replace('bg-','',$color); ?>">
									<h4>
										Estado: <b><?php echo $estadoDocumentacion; ?></b>
									</h4>
									
								</div>

							</div>

							<?php
								while ($rowEstados = mysql_fetch_array($resDocumentaciones2)) {
									#print_r($rowEstados);
									$resEstados = $documentos->traerEstadodocumentos();
									$cadRefEstados = '';
									$cadRefEstados = $funciones->devolverSelectBoxActivo($resEstados,array(1),'', $rowEstados['idestadodocumento']);
									//$iddocumento = $rowEstados['idcontratoglobaldocumento'];
									$estadoDocumentacion = $rowEstados['idestadodocumento'];

									$resRazonRechazo = $documentos->traerRazonRechazoDocumentos();
									$cadRefrechazoDocto = '';
									$cadRefrechazoDocto = $funciones->devolverSelectBoxActivo($resRazonRechazo,array(1),'', $rowEstados['refrechazodocumento']);

									?>

							<div class="row" style="display:block">
										<label for="reftipodocumentos" class="control-label" style="text-align:left">Modificar Estado</label>
										<div class="input-group col-md-6">
											<select class="form-control show-tick" id="refestados<?php echo $rowEstados['idcontratoglobaldocumento']; ?>" name="refestados">
												<?php echo $cadRefEstados; ?>
											</select>
										</div>
										<div id='causaRechazo'>
											<label for="reftipodocumentos" class="control-label" style="text-align:left">Razón del rechazo</label>
											<div class="input-group col-md-12">
												<select class="form-control show-tick" id="refrechazodocumento<?php echo $rowEstados['idcontratoglobaldocumento']; ?>" name="refrechazodocumento">
												<?php echo $cadRefrechazoDocto; ?>
												</select>
											</div>

											<label for="reftipodocumentos" class="control-label" style="text-align:left">Comentario del rechazo</label>
											<div class="input-group col-md-12">
												<textarea id="comentario<?php echo $rowEstados['idcontratoglobaldocumento']; ?>"rows="2" name="comentario" cols="100"><?php echo $rowEstados['comentario'];?></textarea>
											
											</div>
										</div>

										<div>&nbsp;</div>
									<input type="hidden" name="tipodocto" id="tipodocto" value="<?php echo $iddocumento; ?>">
								<?php if($iddocumento == 3) { ?> 
								
									<div id='vigenciaINE' >
											<label for="vigenciadom" class="control-label" style="text-align:left"><p>Fecha de expedición</label>
											

											<div id="div_vigenciadom" class="form-line col-12">
												<input class="form-control strtoupper form-control-sm" type="date" name="vigenciadom" id="vigenciadom" value="<?php echo $fechaDom;?>" />
												<input type="hidden" name="idcontrato" id="idcontrato" value="<?php echo $idContratoGlobal; ?>">

												

											</div>
												

											<p>
										<?php
										if ($usuarioRol == 1 || $usuarioRol == 2 ) {
										?>
										
										<?php } ?>

									<?php }?>

											
										</div>
										<p>
										<?php
										if ($usuarioRol == 1 || $usuarioRol == 2) {
										?>
										<div>
										<button type="button" id="<?php echo $rowEstados['idcontratoglobaldocumento']; ?>" class="btn btn-primary guardarEstado" style="margin-left:0px;">Guardar Estado</button></div>
										<?php } ?>
									
								<?php
							}
							?>

						</div>
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

	statusInico();	

	function statusInico(){
		if($("[name*='refestados']").length>0){
			var stadoInicialDocto = $("[name*='refestados']").val();
			var id = $("[name*='refestados']").attr("id");
			var id_string = id.substring( 10,  id.length+1 );
			bloquear =  true;
			if(stadoInicialDocto == 2 || stadoInicialDocto == 3 || stadoInicialDocto == 4){
				bloquear = false;
			}

			bloqueaCampo('refrechazodocumento'+id_string, bloquear); 
			bloqueaCampo('comentario'+id_string, bloquear);  
  			ocultar_secciones(bloquear, '#causaRechazo');
		}
	}


	$('.btnDocumentacion').click(function() {
		
			idTable =  $(this).attr("id");
			url = "aprobardocumentos.php?id=<?php echo $idContratoGlobal;?>&documento=" + idTable;
			$(location).attr('href',url);
		});

		$("[name*='refestados']").change(function(){			
			var seleccionado = $(this).val();
			var bloquear = '';
			var id = $(this).attr("id");
		    var id_string = id.substring( 10,  id.length+1 );			
			if(seleccionado == 2 || seleccionado == 3 || seleccionado == 4){
				bloquear = false;
			}else{
				bloquear = true;				
			}
			bloqueaCampo('refrechazodocumento'+id_string, bloquear); 
			bloqueaCampo('comentario'+id_string, bloquear);  
  			ocultar_secciones(bloquear, '#causaRechazo');
		});

		$('.guardarEstado').click(function() {
			idTable =  $(this).attr("id");
			modificarEstadoDocumentoContrato($('#refestados' + idTable).val(),idTable);
		});

		$('#enviarDictamen').click(function() {			
			enviarDictamenDocumentos();
		});

		function enviarDictamenDocumentos() {
			$.ajax({
				url: '../../../../ajax/ajax.php',
				type: 'POST',
				// Form data
				//datos del formulario
				data: {
					accion: 'enviaDictamenDoctos',
					idCG:<?php echo $idContratoGlobal;?>,
				},
				//mientras enviamos el archivo
				beforeSend: function(){
					$('.guardarEstado').hide();
				},
				//una vez finalizado correctamente
				success: function(data){

					if (data == 1) {
						swal("Ok!", 'Se envió correctamente la dictaminacion al cliente ', "success",5000);	

					

						location.reload();
					} else {
						swal("Error!", data, "warning");
						$("#load").html('');
					}
				},
				//si ha ocurrido un error
				error: function(){
					$(".alert").html('<strong>Error!</strong> Actualice la pagina');
					$("#load").html('');
				}
			});
		}

		function modificarEstadoDocumentoContrato(idestado, id) {

			var idRechazo = $('#refrechazodocumento' + id).val();
			var comentario =   $('#comentario' + id).val();
			var fechaDom = '';
			var tipoDocto = $('#tipodocto').val();			
			if (tipoDocto == 3){
				fechaDom = $('#vigenciadom').val();
				if(fechaDom == "" ){
					swal("Error!", "Falta la fecha de expedición del documento", "error");
					return false;
				}else{
					alert(fechaDom);
				}
			}
			$.ajax({
				url: '../../../../ajax/ajax.php',
				type: 'POST',
				// Form data
				//datos del formulario

				data: {
					accion: 'modificarEstadoDocumentoContrato',
					iddocumento: id,
					idestado: idestado,
					idusuario:1,
					email:1,
					idrechazo:idRechazo,
					comentario:comentario,
					vigenciaDom:fechaDom,
					tipoDocto:tipoDocto
				},
				//mientras enviamos el archivo
				beforeSend: function(){
					$('.guardarEstado').hide();
				},
				//una vez finalizado correctamente
				success: function(data){

					if (data.error == false) {
						swal("Ok!", 'Se modifico correctamente el estado del documento ', "success");
						$('.guardarEstado').show();
						location.reload();
					} else {
						swal("Error!", data.leyenda, "warning");
						$("#load").html('');
					}
				},
				//si ha ocurrido un error
				error: function(){
					$(".alert").html('<strong>Error!</strong> Actualice la pagina');
					$("#load").html('');
				}
			});
		}


		

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
					swal("Correcto!", resp.replace("1", ""), "success");
					$('.btnGuardar').show();
					$('.infoPlanilla').hide();
					//location.reload();
					url = "aprobardocumentos.php?id=<?php echo $idContratoGlobal;?>&documento=<?php echo $iddocumento; ?>";
						$(location).attr('href',url);
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


function bloqueaCampo(campo, bloquea){  
  if(bloquea){  
    if($("#"+campo).is(":checkbox")) {
          $("#"+campo).attr("checked", false);
          $('#'+campo).attr("disabled", true);
        }  else{
          $('#'+campo).val('');
          $('#'+campo).attr("disabled", true);      

        } 
    campo = '<input type="hidden"  id="'+ campo +'" name="'+ campo +'"  />';
    $('.ocultos').append(campo); 
    
  }else{  
  
    $('#'+campo).attr("disabled", false);
    $(".ocultos [name="+campo+"]").remove();
  }
}

function ocultar_secciones(slide, patron){
  if(slide){
    $(patron).slideUp("slow");
  }else{
    $(patron).slideDown("slow");
  }
}





});
</script>
</body>
</html>