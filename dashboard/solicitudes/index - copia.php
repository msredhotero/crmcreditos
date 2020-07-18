<?php


session_start();

if (!isset($_SESSION['usua_sahilices']))
{
	header('Location: ../../error.php');
} else {


include ('../../includes/funciones.php');
include ('../../includes/funcionesUsuarios.php');
include ('../../includes/funcionesHTML.php');
include ('../../includes/funcionesReferencias.php');
include ('../../includes/base.php');
include ('../../includes/funcionesForma.php');
include ('../../includes/funcionesSolicitudes.php');

$serviciosFunciones 	= new Servicios();
$serviciosUsuario 		= new ServiciosUsuarios();
$serviciosHTML 			= new ServiciosHTML();
$serviciosReferencias 	= new ServiciosReferencias();
$baseHTML = new BaseHTML();

$serviciosSolicitud = new ServiciosSolicitudes();
$serviciosForma 	= new ServiciosForma();



//*** SEGURIDAD ****/
include ('../../includes/funcionesSeguridad.php');
$serviciosSeguridad = new ServiciosSeguridad();
$serviciosSeguridad->seguridadRuta($_SESSION['refroll_sahilices'], '../usuarios/');
//*** FIN  ****/

$fecha = date('Y-m-d');

//$resProductos = $serviciosProductos->traerProductosLimite(6);
$resMenu = $serviciosHTML->menu($_SESSION['nombre_sahilices'],"Usuarios",$_SESSION['refroll_sahilices'],$_SESSION['email_sahilices']);

$configuracion = $serviciosReferencias->traerConfiguracion(); // datos de financieraCrea

$tituloWeb = mysql_result($configuracion,0,'sistema');

$breadCumbs = '<a class="navbar-brand" href="../index.php">Dashboard</a>';

/////////////////////// Opciones pagina ///////////////////////////////////////////////
$singular = "Usuario";
$plural = "Usuarios";
$eliminar = "eliminarUsuarios";
$insertar = "insertarUsuarios";
$modificar = "modificarUsuario";
//////////////////////// Fin opciones ////////////////////////////////////////////////


/////////////////////// Opciones para la creacion del formulario  /////////////////////
$tabla 			= "dbusuarios";
$lblCambio	 	= array('nombrecompleto','refroles');
$lblreemplazo	= array('Nombre Completo','Perfil');



if ($_SESSION['idroll_sahilices'] == 1) {
	$resRoles 	= $serviciosUsuario->traerRoles();
} else {
	$resRoles 	= $serviciosUsuario->traerRolesSimple();
}

$cadRef2 = $serviciosFunciones->devolverSelectBox($resRoles,array(1),'');

$refdescripcion = array(0 => $cadRef2);
$refCampo 	=  array('refroles');

$frmUnidadNegocios 	= $serviciosFunciones->camposTablaViejo($insertar ,$tabla,$lblCambio,$lblreemplazo,$refdescripcion,$refCampo);
//////////////////////////////////////////////  FIN de las opciones //////////////////////////

//////////////////////////////////////////SOLCITUD ///////////////////////////////////////////

$tabla = "dbsolicitudes";

$lblCambio = $serviciosFunciones->traerLblCambioReemplazo('dbsolicitudes','lblCambio');
$lblreemplazo = $serviciosFunciones->traerLblCambioReemplazo('dbsolicitudes','lblreemplazo');

 
if ($_SESSION['idroll_sahilices'] == 1) {
	$resRoles 	= $serviciosUsuario->traerRoles();
} else {
	$resRoles 	= $serviciosUsuario->traerRolesSimple();
}

#echo "resRoles";
#print_r($resRoles);

$cadRef2 = $serviciosFunciones->devolverSelectBox($resRoles,array(1),'');

$refdescripcion = array(0 => $cadRef2);
$refCampo 	=  array('refroles');

$frmUnidadNegocios 	= $serviciosFunciones->camposTablaViejo($insertar ,$tabla,$lblCambio,$lblreemplazo,$refdescripcion,$refCampo);

$frmAltaSolcitud = $serviciosSolicitud->generaFormAltaCliente($serviciosFunciones);
/////////////////////////////////////////////////////////////////////////////////////////

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title><?php echo $tituloWeb; ?></title>
	<!-- Favicon-->
	<link rel="icon" href="../../favicon.ico" type="image/x-icon">

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

	<?php echo $baseHTML->cargarArchivosCSS('../../'); ?>

	<link href="../../plugins/waitme/waitMe.css" rel="stylesheet" />
	<link href="../../plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

	<!-- VUE JS -->
	<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

	<!-- axios -->
	<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

	<script src="https://unpkg.com/vue-swal"></script>

	<!-- Bootstrap Material Datetime Picker Css -->
	<link href="../../plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

	<!-- Dropzone Css -->
	<link href="../../plugins/dropzone/dropzone.css" rel="stylesheet">


	<link rel="stylesheet" href="../../DataTables/DataTables-1.10.18/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="../../DataTables/DataTables-1.10.18/css/dataTables.bootstrap.css">
	<link rel="stylesheet" href="../../DataTables/DataTables-1.10.18/css/dataTables.jqueryui.min.css">
	<link rel="stylesheet" href="../../DataTables/DataTables-1.10.18/css/jquery.dataTables.css">

	<style>
		.alert > i{ vertical-align: middle !important; }
	</style>


</head>



<body class="theme-red">

<!-- Page Loader -->
<div class="page-loader-wrapper">
	<div class="loader">
		<div class="preloader">
			<div class="spinner-layer pl-red">
				<div class="circle-clipper left">
					<div class="circle"></div>
				</div>
				<div class="circle-clipper right">
					<div class="circle"></div>
				</div>
			</div>
		</div>
		<p>Cargando...</p>
	</div>
</div>
<!-- #END# Page Loader -->
<!-- Overlay For Sidebars -->
<div class="overlay"></div>
<!-- #END# Overlay For Sidebars -->
<!-- Search Bar -->
<div class="search-bar">
	<div class="search-icon">
		<i class="material-icons">search</i>
	</div>
	<input type="text" placeholder="Ingrese palabras...">
	<div class="close-search">
		<i class="material-icons">close</i>
	</div>
</div>
<!-- #END# Search Bar -->
<!-- Top Bar -->
<?php echo $baseHTML->cargarNAV($breadCumbs); ?>
<!-- #Top Bar -->
<?php echo $baseHTML->cargarSECTION($_SESSION['usua_sahilices'], $_SESSION['nombre_sahilices'], $resMenu,'../../'); ?>

<section class="content" style="margin-top:-25px;">

	<div class="container-fluid">
		<div class="row clearfix">
		
		
	   

			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
					<div class="card">
						<div class="headerformulario font-26">
							<?php echo strtoupper($plural); ?>
								
						
							
						</div>
						
	<div class="card-content">
	  <p>I am a very simple card. I am good at containing small bits of information. I am convenient because I require little markup to use effectively.</p>
	</div>
	<div class="card-tabs">
	  <ul class="tabs tabs-fixed-width">
		<li class="tab"><a href="#test4">Test 1</a></li>
		<li class="tab"><a class="active" href="#test5">Test 2</a></li>
		<li class="tab"><a href="#test6">Test 3</a></li>
	  </ul>
	</div>
	<div class="card-content grey lighten-4">
	  <div id="test4">Test 1</div>
	  <div id="test5">Test 2</div>
	  <div id="test6">Test 3</div>
	</div>

  
						
						
						
						
						
						
						
					 </div>
			 </div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
			 <div class="panel-group">
				<DIV class="panel-col-red">
			 <div class="panel-title">TITULO</div>
			 <div class="panel-body">
				este es el cuerpo de panel body tiene la consfiguracion del cuerpo rojo

			 </div>
			 </DIV> 
			 </div>
		 </div>

		 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
			 <div class="panel-group">
				<div class="panel">
				<div class="panel-success">
					<div class="panel-title">
						<div class="panel-heading"> header del panel</div>
					</div>
					
					<div class="panel-body"> este es el cuerpo del panel</div>
					<div></div>
				</div>
			 </div>
			 </div>
		 </div>
		  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
			 <div class="panel-group">
				
				<div class="panel-success">
					<div class="panel-title">
						<div class="panel-heading"> header del panel</div>
					</div>
					
					<div class="panel-body"> este es el cuerpo del panel</div>
					<div></div>
				</div>
			 
			 </div>
		 </div>
		 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
			<div class="full-body">
				<div class="panel-col-red">
					<div  class="panel-body"> cuerpo del panel full body</div>
				</div>
			</div>
		 </div>
		 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
			<div class="badge">
				<div class="list-group">
				<a href="#!" class="list-group-item"><span class="badge">1</span>Alan</a>
	<a href="#!" class="list-group-item-danger"><span class="new badge">4</span>Alan</a>
	<a href="#!" class="list-group-item">Alan</a>
	<a href="#!" class="list-group-item"><span class="badge">14</span>Alan</a>
  </div>
				
			</div>
		 </div>

		 <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<div class="panel-group">
    <div class="panel panel-primary">
      <div class="panel-heading">Panel with panel-default class</div>
      <div class="panel-body">Panel Content</div>
    </div>
</div ></div>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
	<div class="card">
		<ul class="nav nav-tabs">
		  <li class="active"><a href="#">Home</a></li>
		  <li><a href="#">Menu 1</a></li>
		  <li><a href="#">Menu 2</a></li>
		  <li><a href="#">Menu 3</a></li>
		</ul>
	</div>
</div>
			<form class="formulario" role="form" id="sign_in">
				<div class="form">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="card">
							<div class="headerformulario">
								Solicitudes			
							</div>
							<div class="body table-responsive">							
								<div class="form-group">
									<?php echo $frmAltaSolcitud; ?>
										
								</div>
								

							</div>
						</div>
					</div>	
				</div>
		</form>

 				<div class="form">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card">
						<div class="headerformulario">
							<?php echo strtoupper($plural); ?>
								
						
							
						</div>
						<div class="body table-responsive">
							<form class="form" id="formCountry">

								<div class="row">
									<div class="col-lg-12 col-md-12">
										<div class="button-demo">
											<button type="button" class="btn bg-light-green waves-effect btnNuevo" data-toggle="modal" data-target="#lgmNuevo">
												<i class="material-icons">add</i>
												<span>NUEVO</span>
											</button>

										</div>
									</div>
								</div>


								<div class="row" style="padding: 5px 20px;">

									<table id="example" class="display table " style="width:100%">
										<thead>
											<tr>
												<th>Usuario</th>
												<th></th>
												<th>Email</th>
												<th>Nombre Completo</th>
												<th>Activo</th>
												<th>Acciones</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th>Usuario</th>
												<th class="perfilS">Perfil</th>
												<th>Email</th>
												<th>Nombre Completo</th>
												<th>Activo</th>
												<th>Acciones</th>
											</tr>
										</tfoot>
									</table>
								</div>
							</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>


<!-- NUEVO -->
	<form class="formulario" role="form" id="sign_in">
	   <div class="modal fade" id="lgmNuevo" tabindex="-1" role="dialog">
		   <div class="modal-dialog modal-lg" role="document">
			   <div class="modal-content">
				   <div class="modal-header">
					   <h4 class="modal-title" id="largeModalLabel">CREAR <?php echo strtoupper($singular); ?></h4>
				   </div>
				   <div class="modal-body">
					  <?php echo $frmUnidadNegocios; ?>
				   </div>
				   <div class="modal-footer">
					   <button type="submit" class="btn btn-primary waves-effect nuevo">GUARDAR</button>
					   <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CERRAR</button>
				   </div>
			   </div>
		   </div>
	   </div>
		<input type="hidden" id="accion" name="accion" value="<?php echo $insertar; ?>"/>
	</form>

	<!-- MODIFICAR -->
		<form class="formulario" role="form" id="sign_in">
		   <div class="modal fade" id="lgmModificar" tabindex="-1" role="dialog">
			   <div class="modal-dialog modal-lg" role="document">
				   <div class="modal-content">
					   <div class="modal-header">
						   <h4 class="modal-title" id="largeModalLabel">MODIFICAR <?php echo strtoupper($singular); ?></h4>
					   </div>
					   <div class="modal-body frmAjaxModificar">

					   </div>
					   <div class="modal-footer">
						   <button type="button" class="btn btn-warning waves-effect modificar">MODIFICAR</button>
						   <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CERRAR</button>
					   </div>
				   </div>
			   </div>
		   </div>
			<input type="hidden" id="accion" name="accion" value="<?php echo $modificar; ?>"/>
		</form>


	<!-- ELIMINAR -->
		<form class="formulario" role="form" id="sign_in">
		   <div class="modal fade" id="lgmEliminar" tabindex="-1" role="dialog">
			   <div class="modal-dialog modal-lg" role="document">
				   <div class="modal-content">
					   <div class="modal-header">
						   <h4 class="modal-title" id="largeModalLabel">ELIMINAR <?php echo strtoupper($singular); ?></h4>
					   </div>
					   <div class="modal-body">
										 <p>¿Esta seguro que desea eliminar el registro?</p>
										 <small>* Si este registro esta relacionado con algun otro dato no se podría eliminar.</small>
					   </div>
					   <div class="modal-footer">
						   <button type="button" class="btn btn-danger waves-effect eliminar">ELIMINAR</button>
						   <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CERRAR</button>
					   </div>
				   </div>
			   </div>
		   </div>
			<input type="hidden" id="accion" name="accion" value="<?php echo $eliminar; ?>"/>
			<input type="hidden" name="ideliminar" id="ideliminar" value="0">
		</form>



<?php echo $baseHTML->cargarArchivosJS('../../'); ?>
<!-- Wait Me Plugin Js -->
<script src="../../plugins/waitme/waitMe.js"></script>

<!-- Custom Js -->
<script src="../../js/pages/cards/colored.js"></script>

<script src="../../plugins/jquery-validation/jquery.validate.js"></script>

<script src="../../js/pages/examples/sign-in.js"></script>

<!-- Bootstrap Material Datetime Picker Plugin Js -->
<script src="../../plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>

<script src="../../DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>


<script>
	$(document).ready(function(){
		var table = $('#example').DataTable({
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "../../json/jstablasajax.php?tabla=usuarios",
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
		});

		$("#example .perfilS").each( function ( i ) {
			var select = $('<select><option value="">-- Seleccione Perfil --</option><?php echo $cadRef2; ?></select>')
				.appendTo( $(this).empty() )
				.on( 'change', function () {
					table.column( i )
						.search( $(this).val() )
						.draw();
				} );
			table.column( i ).data().unique().sort().each( function ( d, j ) {
				select.append( '<option value="'+d+'">'+d+'</option>' )
			} );
		} );

		$("#sign_in").submit(function(e){
			e.preventDefault();
		});

		$('#activo').prop('checked',true);

		function frmAjaxModificar(id) {
			$.ajax({
				url: '../../ajax/ajax.php',
				type: 'POST',
				// Form data
				//datos del formulario
				data: {accion: 'frmAjaxModificar',tabla: '<?php echo $tabla; ?>', id: id},
				//mientras enviamos el archivo
				beforeSend: function(){
					$('.frmAjaxModificar').html('');
				},
				//una vez finalizado correctamente
				success: function(data){

					if (data != '') {
						$('.frmAjaxModificar').html(data);
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


		function frmAjaxEliminar(id) {
			$.ajax({
				url: '../../ajax/ajax.php',
				type: 'POST',
				// Form data
				//datos del formulario
				data: {accion: '<?php echo $eliminar; ?>', id: id},
				//mientras enviamos el archivo
				beforeSend: function(){

				},
				//una vez finalizado correctamente
				success: function(data){

					if (data == '') {
						swal({
								title: "Respuesta",
								text: "Registro Eliminado con exito!!",
								type: "success",
								timer: 1500,
								showConfirmButton: false
						});
						$('#lgmEliminar').modal('toggle');
						table.ajax.reload();
					} else {
						swal({
								title: "Respuesta",
								text: data,
								type: "error",
								timer: 2000,
								showConfirmButton: false
						});

					}
				},
				//si ha ocurrido un error
				error: function(){
					swal({
							title: "Respuesta",
							text: 'Actualice la pagina',
							type: "error",
							timer: 2000,
							showConfirmButton: false
					});

				}
			});

		}

		function reenviarActivacion(id) {
			$.ajax({
				url: '../../ajax/ajax.php',
				type: 'POST',
				// Form data
				//datos del formulario
				data: {accion: 'reenviarActivacion', idusuario: id},
				//mientras enviamos el archivo
				beforeSend: function(){

				},
				//una vez finalizado correctamente
				success: function(data){

					if (data.error == false) {
						swal({
								title: "Respuesta",
								text: data.mensaje,
								type: "success",
								timer: 1500,
								showConfirmButton: false
						});

					} else {
						swal({
								title: "Respuesta",
								text: data.mensaje,
								type: "error",
								timer: 2000,
								showConfirmButton: false
						});

					}
				},
				//si ha ocurrido un error
				error: function(){
					swal({
							title: "Respuesta",
							text: 'Actualice la pagina',
							type: "error",
							timer: 2000,
							showConfirmButton: false
					});

				}
			});
		}

		$("#example").on("click",'.btnEnviar', function(){
			idTable =  $(this).attr("id");
			reenviarActivacion(idTable);
		});//fin del boton eliminar

		$("#example").on("click",'.btnEliminar', function(){
			idTable =  $(this).attr("id");
			$('#ideliminar').val(idTable);
			$('#lgmEliminar').modal();
		});//fin del boton eliminar

		$('.eliminar').click(function() {
			frmAjaxEliminar($('#ideliminar').val());
		});

		$("#example").on("click",'.btnModificar', function(){
			idTable =  $(this).attr("id");
			frmAjaxModificar(idTable);
			$('#lgmModificar').modal();
		});//fin del boton modificar

		$('.nuevaSolcitud').click(function(){
			// jalamos la informacion del formulario
			var formData = new FormData($(".formulario")[0]);
			var message = "";
			$.ajax({
				url: '../../ajax/ajax.php',
				type: 'POST',
				// datos del formulario
				data: formData,
				//necesario para subir archivos via ajax
				cache: false,
				contentType: false,
				processData: false,
				//mientras enviamos el archivo
				beforeSend: function(){
					alert("Enviando datos...");
				},
				//una vez finalizado correctamente
				success: function(data){
					if (data == '') {
						swal({
								title: "Respuesta",
								text: "Registro Creado con exito!!",
								type: "success",
								timer: 1500,
								showConfirmButton: false
						});

						
						$('#unidadnegocio').val('');
						table.ajax.reload();
					} else {
						swal({
								title: "Respuesta",
								text: data,
								type: "error",
								timer: 2500,
								showConfirmButton: false
						});


					}
				},
				//si ha ocurrido un error
				error: function(){
					$(".alert").html('<strong>Error!</strong> Actualice la pagina');
					$("#load").html('');
				}

			});

		});

		$('.nuevo').click(function(){

			//información del formulario
			var formData = new FormData($(".formulario")[1]);
			var message = "";
			//hacemos la petición ajax
			$.ajax({
				url: '../../ajax/ajax.php',
				type: 'POST',
				// Form data
				//datos del formulario
				data: formData,
				//necesario para subir archivos via ajax
				cache: false,
				contentType: false,
				processData: false,
				//mientras enviamos el archivo
				beforeSend: function(){

				},
				//una vez finalizado correctamente
				success: function(data){
					if (data == '') {
						swal({
								title: "Respuesta",
								text: "Registro Creado con exito!!",
								type: "success",
								timer: 1500,
								showConfirmButton: false
						});

						$('#lgmNuevo').modal('hide');
						$('#unidadnegocio').val('');
						//table.ajax.reload();
					} else {
						swal({
								title: "Respuesta",
								text: data,
								type: "error",
								timer: 2500,
								showConfirmButton: false
						});


					}
				},
				//si ha ocurrido un error
				error: function(){
					$(".alert").html('<strong>Error!</strong> Actualice la pagina');
					$("#load").html('');
				}
			});
		});


		$('.modificar').click(function(){

			//información del formulario
			var formData = new FormData($(".formulario")[1]);
			var message = "";
			//hacemos la petición ajax
			$.ajax({
				url: '../../ajax/ajax.php',
				type: 'POST',
				// Form data
				//datos del formulario
				data: formData,
				//necesario para subir archivos via ajax
				cache: false,
				contentType: false,
				processData: false,
				//mientras enviamos el archivo
				beforeSend: function(){

				},
				//una vez finalizado correctamente
				success: function(data){

					if (data == '') {
						swal({
								title: "Respuesta",
								text: "Registro Modificado con exito!!",
								type: "success",
								timer: 1500,
								showConfirmButton: false
						});

						$('#lgmModificar').modal('hide');
						table.ajax.reload();
					} else {
						swal({
								title: "Respuesta",
								text: data,
								type: "error",
								timer: 2500,
								showConfirmButton: false
						});


					}
				},
				//si ha ocurrido un error
				error: function(){
					$(".alert").html('<strong>Error!</strong> Actualice la pagina');
					$("#load").html('');
				}
			});
		});
	});


function frmAjaxinsertarSolicitud(id) {
			$.ajax({
				url: '../../ajax/ajax.php',
				type: 'POST',
				// Form data
				//datos del formulario
				data: {accion: 'frmAjaxinsertarSolicitud',tabla: '<?php echo $tabla; ?>', id: id},
				//mientras enviamos el archivo
				beforeSend: function(){
					$('.frmAjaxModificar').html('');
				},
				//una vez finalizado correctamente
				success: function(data){

					if (data != '') {
						$('.frmAjaxModificar').html(data);
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
</script>








</body>
<?php } ?>
</html>
