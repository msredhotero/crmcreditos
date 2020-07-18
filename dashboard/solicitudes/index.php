<?php


session_start();

if (!isset($_SESSION['usua_sahilices']))
{
	header('Location: ../../error.php');
} else {


include '../../class_include.php';

$Query = new Query();
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
//$serviciosSeguridad->seguridadRuta($_SESSION['refroll_sahilices'], '../usuarios/');
//*** FIN  ****/

$fecha = date('Y-m-d');

//$resProductos = $serviciosProductos->traerProductosLimite(6);
$resMenu = $serviciosHTML->menu($_SESSION['nombre_sahilices'],"Usuarios",$_SESSION['refroll_sahilices'],$_SESSION['email_sahilices']);

$configuracion = $serviciosReferencias->traerConfiguracion();

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
//////////////////////////////////////////////  FIN de los opciones //////////////////////////
//////////////////////////////////////////SOLCITUD ///////////////////////////////////////////

$tabla = "dbsolicitudes";
$singular = "Usuario";
$plural = "Usuarios";
$eliminar = "eliminarUsuarios";
$insertarSol = "insertarSolicitudCredito";
$modificar = "modificarUsuario";

#$frmAltaSolcitud = $serviciosSolicitud->generaFormAltaCliente();
/////////////////////////////////////////////////////////////////////////////////////////

#$frmEditar = $serviciosSolicitud->gerarFormEditaSolicitudCliente(12);

// cargamos el formulario de solicitud Nueva

$dataSol = new ServiciosSolicitudes();

#var_dump($dataSol);
$page = new Formulario();
#var_dump($page);
$contenidoformularioNuevo = array();
$dataSol->cargarDatosSolicitud();

#var_dump($dataSol->cargarDatosSolicitud);
$idFormulario = 'sign_in';
$lectura = false;
$form = new FormularioSolicitud();
$form->setDatos($dataSol->getDatos());
$form->set_lectura($lectura);
$contenidoformularioNuevo = $form->altaSolicitud();
$page->add_content($contenidoformularioNuevo);
$classFormulario ='nuevaSolicitud';
$action = 'insertarSolicitudCredito';
$btonOpc = array('class'=>'NuevaSolcitud', 'label'=>'AgregrarSolcitud');
$formularioAlta = $page->html('',  true, 11, 'Registrar nueva solicitud de crédito',$idFormulario,$action);


$page = new Formulario();
$contenidoformularioNuevo = $form->wizardSolicicitud();
$page->add_content($contenidoformularioNuevo);
$classFormulario ='nuevaSolicitud';
$action = 'insertarSolicitudCredito';
$btonOpc = array('class'=>'NuevaSolcitud', 'label'=>'AgregrarSolcitud');
$title = array('Solicitud de crédito','Por favor registra tu información');
$formularioAltaWizard = $page->htmlWizard(false, 11, 'Solicitud de crédito***','Por favor registra tu información' ,$idFormulario,$action);


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
	<link rel="stylesheet" href="../../bootstrap/bootzard-wizard/assets/css/style.css">
	<link rel="stylesheet" href="../../bootstrap/bootzard-wizard/assets/css/form-elements.css">
	<link rel="stylesheet" href="../../bootstrap/bootzard-wizard/assets/font-awesome/css/font-awesome.min.css">

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


<section class="content" style="margin-top:-55px;">
	<div class="container-fluid">
		<div class="row clearfix">
			<div class="row">
				<?php echo $formularioAlta; ?>
				
			</div> <!--row-->			
		</div> <!--container fluid-->
	</div> <!--row clearfix-->
</section>






<section class="content" style="margin-top:-75px;">

	<div class="container-fluid">
		<div class="row clearfix">

			
			<div class="row">
				<form class="formularioSS" role="form" id="sign_inSS">
					<div class="form">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="card">
								<div class="headerformulario">
									Solicitudes			
								</div>
								<div class="body">							
									<div class="form-group">
										<?php # echo $frmAltaSolcitud; ?>	
											
									</div>
								</div>
								<div class="footer">
									 <button type="submit" class="btn btn-primary waves-effect nuevaSolicitud">Guardar</button>	
									  <button type="submit" class="btn btn-primary waves-effect editarSolicitud">Editar</button>					   
								</div>
							</div>
						</div>
					</div>
					<input type="hidden" id="accion" name="accion" value="<?php echo $insertarSol; ?>"/>
					
				</form>
			</div>

			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card ">
						<div class="header bg-orange">
							<h2 style="color:white;">
								<?php echo strtoupper($plural); ?>
							</h2>
							<ul class="header-dropdown m-r--5">
								<li class="dropdown">
									<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
										<i class="material-icons">more_vert</i>
									</a>
									<ul class="dropdown-menu pull-right">

									</ul>
								</li>
							</ul>
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


<section class='content' style="margin-top: 55px;">
	<div class="container-fluid">
		<div class="row clearfix">
			<div class="row">
				<div class="wizardCard">
                    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-10 col-lg-offset-1 form-box">
                    	
                    	<div class="card">
							<div class="headerformulario">
								Solicitud de crédito
		                    		<h4>Por favor registra tu información</h4>
		                    </div>	
		                    <div class="body">	
		                    	<form role="form" action="" method="post" class="f12">

		                    		
		                    		<div class="f1-steps">
		                    			<div class="f1-progress">
		                    			    <div class="f1-progress-line" data-now-value="16.66" data-number-of-steps="3" style="width: 16.66%;"></div>
		                    			</div>
		                    			<div class="f1-step active">
		                    				<div class="f1-step-icon"><i class="fa fa-user"></i></div>
		                    				<p>about</p>
		                    			</div>
		                    			<div class="f1-step">
		                    				<div class="f1-step-icon"><i class="fa fa-key"></i></div>
		                    				<p>account</p>
		                    			</div>
		                    		    <div class="f1-step">
		                    				<div class="f1-step-icon"><i class="fa fa-twitter"></i></div>
		                    				<p>social</p>
		                    			</div>
		                    		</div>
		                    		
		                    		<fieldset>
		                    		    <h4>Tell us who you are:</h4>
		                    			<div class="form-group">
		                    			    <label class="sr-only" for="f1-first-name">First name</label>
		                                    <input type="text" name="f1-first-name" placeholder="First name..." class="f1-first-name form-control" id="f1-first-name">
		                                </div>
		                                <div class="form-group">
		                                    <label class="sr-only" for="f1-last-name">Last name</label>
		                                    <input type="text" name="f1-last-name" placeholder="Last name..." class="f1-last-name form-control" id="f1-last-name">
		                                </div>
		                                <div class="form-group">
		                                    <label class="sr-only" for="f1-about-yourself">About yourself</label>
		                                    <textarea name="f1-about-yourself" placeholder="About yourself..." 
		                                    	                 class="f1-about-yourself form-control" id="f1-about-yourself"></textarea>
		                                </div>
		                                <div class="f1-buttons">
		                                    <button type="button" class="btnw btn-next">Next</button>
		                                </div>
		                            </fieldset>

		                            <fieldset>
		                                <h4>Set up your account:</h4>
		                                <div class="form-group">
		                                    <label class="sr-only" for="f1-email">Email</label>
		                                    <input type="text" name="f1-email" placeholder="Email..." class="f1-email form-control" id="f1-email">
		                                </div>
		                                <div class="form-group">
		                                    <label class="sr-only" for="f1-password">Password</label>
		                                    <input type="password" name="f1-password" placeholder="Password..." class="f1-password form-control" id="f1-password">
		                                </div>
		                                <div class="form-group">
		                                    <label class="sr-only" for="f1-repeat-password">Repeat password</label>
		                                    <input type="password" name="f1-repeat-password" placeholder="Repeat password..." 
		                                                        class="f1-repeat-password form-control" id="f1-repeat-password">
		                                </div>
		                                <div class="f1-buttons">
		                                    <button type="button" class="btnw btn-previous">Previous</button>
		                                    <button type="button" class="btnw btn-next">Next</button>
		                                </div>
		                            </fieldset>

		                            <fieldset>
		                                <h4>Social media profiles:</h4>
		                                <div class="form-group">
		                                    <label class="sr-only" for="f1-facebook">Facebook</label>
		                                    <input type="text" name="f1-facebook" placeholder="Facebook..." class="f1-facebook form-control" id="f1-facebook">
		                                </div>
		                                <div class="form-group">
		                                    <label class="sr-only" for="f1-twitter">Twitter</label>
		                                    <input type="text" name="f1-twitter" placeholder="Twitter..." class="f1-twitter form-control" id="f1-twitter">
		                                </div>
		                                <div class="form-group">
		                                    <label class="sr-only" for="f1-google-plus">Google plus</label>
		                                    <input type="text" name="f1-google-plus" placeholder="Google plus..." class="f1-google-plus form-control" id="f1-google-plus">
		                                </div>
		                                <div class="f1-buttons">
		                                    <button type="button" class="btnw btn-previous">Previous</button>
		                                    <button type="submit" class="btnw btn-submit">Submit</button>
		                                </div>
		                            </fieldset>
		                    	
		                    	</form>
		                    </div>
		                </div> <!-- card -->
		            </div> <!-- cols -->
		        </div> <!-- row -->
		    </div><!-- row -->
		</div> <!-- row clearfix -->
	</div> <!-- container-fluid -->
</section> <!-- content -->


<section class='content' style="margin-top: 55px;">
	<div class="container-fluid">
		<div class="row clearfix">
			<div class="row">
				<div class="wizardCard">
                    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-10 col-lg-offset-1 form-box">
                    	
                    	<div class="card">
							<div class="headerformulario">
								Solicitud de crédito
		                    		<h4>Por favor registra tu información</h4>
		                    </div>	
		                    <div class="body">	
		                    	<form role="form" action="" method="post" class="f12">

		                    		
		                    		<div class="f1-steps">
		                    			<div class="f1-progress">
		                    			    <div class="f1-progress-line" data-now-value="16.66" data-number-of-steps="3" style="width: 16.66%;"></div>
		                    			</div>
		                    			<div class="f1-step active">
		                    				<div class="f1-step-icon"><i class="fa fa-user"></i></div>
		                    				<p>about</p>
		                    			</div>
		                    			<div class="f1-step">
		                    				<div class="f1-step-icon"><i class="fa fa-key"></i></div>
		                    				<p>account</p>
		                    			</div>
		                    		    <div class="f1-step">
		                    				<div class="f1-step-icon"><i class="fa fa-twitter"></i></div>
		                    				<p>social</p>
		                    			</div>
		                    		</div>
		                    		
		                    		<fieldset>
		                    		    <h4>Tell us who you are:</h4>
		                    			<div class="form-group">
		                    			    <label class="sr-only" for="f1-first-name">First name</label>
		                                    <input type="text" name="f1-first-name" placeholder="First name..." class="f1-first-name form-control" id="f1-first-name">
		                                </div>
		                                <div class="form-group">
		                                    <label class="sr-only" for="f1-last-name">Last name</label>
		                                    <input type="text" name="f1-last-name" placeholder="Last name..." class="f1-last-name form-control" id="f1-last-name">
		                                </div>
		                                <div class="form-group">
		                                    <label class="sr-only" for="f1-about-yourself">About yourself</label>
		                                    <textarea name="f1-about-yourself" placeholder="About yourself..." 
		                                    	                 class="f1-about-yourself form-control" id="f1-about-yourself"></textarea>
		                                </div>
		                                <div class="f1-buttons">
		                                    <button type="button" class="btnw btn-next">Next</button>
		                                </div>
		                            </fieldset>

		                            <fieldset>
		                                <h4>Set up your account:</h4>
		                                <div class="form-group">
		                                    <label class="sr-only" for="f1-email">Email</label>
		                                    <input type="text" name="f1-email" placeholder="Email..." class="f1-email form-control" id="f1-email">
		                                </div>
		                                <div class="form-group">
		                                    <label class="sr-only" for="f1-password">Password</label>
		                                    <input type="password" name="f1-password" placeholder="Password..." class="f1-password form-control" id="f1-password">
		                                </div>
		                                <div class="form-group">
		                                    <label class="sr-only" for="f1-repeat-password">Repeat password</label>
		                                    <input type="password" name="f1-repeat-password" placeholder="Repeat password..." 
		                                                        class="f1-repeat-password form-control" id="f1-repeat-password">
		                                </div>
		                                <div class="f1-buttons">
		                                    <button type="button" class="btnw btn-previous">Previous</button>
		                                    <button type="button" class="btnw btn-next">Next</button>
		                                </div>
		                            </fieldset>

		                            <fieldset><p>
		                                <h4>Social media profiles:</h4>
		                                <div class="form-group">
		                                    <label class="sr-only" for="f1-facebook">Facebook</label>
		                                    <input type="text" name="f1-facebook" placeholder="Facebook..." class="f1-facebook form-control" id="f1-facebook">
		                                </div>
		                                <div class="form-group">
		                                    <label class="sr-only" for="f1-twitter">Twitter</label>
		                                    <input type="text" name="f1-twitter" placeholder="Twitter..." class="f1-twitter form-control" id="f1-twitter">
		                                </div>
		                                <div class="form-group">
		                                    <label class="sr-only" for="f1-google-plus">Google plus</label>
		                                    <input type="text" name="f1-google-plus" placeholder="Google plus..." class="f1-google-plus form-control" id="f1-google-plus">
		                                </div>
		                                <div class="f1-buttons">
		                                    <button type="button" class="btnw btn-previous">Previous</button>
		                                    <button type="submit" class="btnw btn-submit">Submit</button>
		                                </div>
		                                </p>
		                            </fieldset>
		                    	
		                    	</form>
		                    </div>
		                </div> <!-- card -->
		            </div> <!-- cols -->
		        </div> <!-- row -->
		    </div><!-- row -->
		</div> <!-- row clearfix -->
	</div> <!-- container-fluid -->
</section> <!-- content -->
	


<section class='content' style="margin-top: 55px;">
	<div class="container-fluid">
		<div class="row clearfix">
			<div class="row">
				<?php echo $formularioAltaWizard; ?>
				
			</div> <!--row-->			
		</div> <!--container fluid-->
	</div> <!--row clearfix-->
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
<script type="text/javascript" src="../../plugins/bootstrapvalidator/dist/js/bootstrapValidator.js"></script>


<script>


function scroll_to_class(element_class, removed_height) {
	var scroll_to = $(element_class).offset().top - removed_height;
	if($(window).scrollTop() != scroll_to) {
		$('html, body').stop().animate({scrollTop: scroll_to}, 0);
	}
}

function bar_progress(progress_line_object, direction) {
	var number_of_steps = progress_line_object.data('number-of-steps');
	var now_value = progress_line_object.data('now-value');
	var new_value = 0;
	if(direction == 'right') {
		new_value = now_value + ( 100 / number_of_steps );
	}
	else if(direction == 'left') {
		new_value = now_value - ( 100 / number_of_steps );
	}
	progress_line_object.attr('style', 'width: ' + new_value + '%;').data('now-value', new_value);
}

	$(document).ready(function(){
		// funciones para wizardForm

		/*
        Form
	    */
	    $('.f1 fieldset:first').fadeIn('slow');
	    
	    $('.f1 input[type="text"], .f1 input[type="password"], .f1 textarea').on('focus', function() {
	    	$(this).removeClass('input-error');
	    });



	    // next step
    $('.f1 .btn-next').on('click', function() {
    	var parent_fieldset = $(this).parents('fieldset');
    	var parent_fieldset_siguiente = $(this).parents('fieldset').nextAll().find("input").first().attr('id');
    	console.log(parent_fieldset_siguiente);
    	var next_step = true;

    	//validacion manual

    	//$('.f1').bootstrapValidator('validate');
    	// navigation steps / progress steps
    	//var validate = $('.f1').bootstrapValidator('validate');
    	var current_active_step = $(this).parents('.f1').find('.f1-step.active');
    	var progress_line = $(this).parents('.f1').find('.f1-progress-line');
    	
    	// fields validation
    	parent_fieldset.find('input[type="text"], input[type="password"], textarea, select').each(function() {
    		
			// if($(this).checkValidity()){
			// 	alert("se reviso la validación de elemto");
			// }
    		if( $(this).val() == "" ) {
    			$(this).addClass('input-error');
    			next_step = false;
    			swal({
								title: "Información incompleta",
								text: "Por favor llene todos los campor indicados en rojo",
								type: "error",
								timer: 4000,
								showConfirmButton: false
						});

    		}
    		else {
    			$(this).removeClass('input-error');
    		}
    	});
    	// fields validation

    	if( next_step  ) {
    		parent_fieldset.fadeOut(400, function() {
    			// change icons
    			current_active_step.removeClass('active').addClass('activated').next().addClass('active');
    			// progress bar
    			bar_progress(progress_line, 'right');
    			// show next step
	    		$(this).next().fadeIn();
	    		// scroll window to beginning of the form
    			scroll_to_class( $('.f1'), 20 );
    			
    			//$('.f1').bootstrapValidator('validate');
	    	});

	    	console.log("entra en next");
	    	//parent_fieldset_siguiente.change();
	    	////parent_fieldset_siguiente.trigger('change');
	    	//$("'#"+parent_fieldset_siguiente+"'").change();
	    	//var original = parent_fieldset_siguiente.val();
	    	//parent_fieldset_siguiente.val('');
	    	//parent_fieldset_siguiente.trigger('change');
	    	//parent_fieldset_siguiente.val(original);
			//var idCampo =  parent_fieldset_siguiente.attr("id");
	    	
	    	var a = $('.f1').data('bootstrapValidator').revalidateField('nombre');
	    	console.log("entra en next se ejecuta eñl evento" + Object.values(a));
	    	//a.forEach(element => console.log(element));
	    	//$('.f1').bootstrapValidator('validate');
    	}

    	
    	
    });

     // previous step
    $('.f1 .btn-previous').on('click', function() {
    	// navigation steps / progress steps
    	var current_active_step = $(this).parents('.f1').find('.f1-step.active');
    	var progress_line = $(this).parents('.f1').find('.f1-progress-line');
    	// Active the panel element containing the first invalid element
      		
    	
    	$(this).parents('fieldset').fadeOut(400, function() {
    		// change icons
    		current_active_step.removeClass('active').prev().removeClass('activated').addClass('active');
    		// progress bar
    		bar_progress(progress_line, 'left');
    		// show previous step
    		$(this).prev().fadeIn();
    		// scroll window to beginning of the form
			scroll_to_class( $('.f1'), 20 );
    	});
    	//$('.f1').data('bootstrapValidator');
    });

     // submit
    $('.f1').on('submite', function(e) {
    	e.preventDefault();
    	var camposVacios = 0;
    	// fields validation
    	$(this).find('input[type="text"], input[type="password"], textarea').each(function() {
    		if( $(this).val() == "" ) {
    			e.preventDefault();
    			$(this).addClass('input-error');    			
    			camposVacios =1;
    		}
    		else {
    			$(this).removeClass('input-error');
    		}
    	});

    	if(camposVacios =1 ){
    		swal({
    			title: "Información incompleta",
				text: "Por favor verifique que todos los campos requeridos contienen información",
				type: "error",
				timer: 3000,
				showConfirmButton: false
				});
    	}
    	// fields validation
    	
    });

		$('.editarSolicitud').hide();
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
		$(".f1").submit(function(e){
			e.preventDefault();
			alert("prevenet default desde .f1");
		});


		$('#activo').prop('checked',true);

		function validateData(id){

		}

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




$('.f1').bootstrapValidator({	
  live: 'enabled',
  message: 'This value is not valid',
  submitButton: '$user_fact_form button[type="submit"]',
 
 
   fields: {
            nombre: {
                message: 'Nombre invalido',
                validators: {
                    notEmpty: {
                        message: 'Requerido'
                    },
                    stringLength: {
                        min: 6,
                        max: 30,
                        message: '6 letras'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z]+$/,
                        message: 'Solo letras'
                    },
                    different: {
                        field: 'password',
                        message: 'No el mismo'
                    }
                }
            },

            apellidopaterno: {
                message: 'Nombre invalido',
                validators: {
                    notEmpty: {
                        message: 'Requerido'
                    },
                    stringLength: {
                        min: 6,
                        max: 30,
                        message: '6 letras'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z]+$/,
                        message: 'Solo letras'
                    },
                    different: {
                        field: 'password',
                        message: 'Diferente que el password'
                    }
                }
            },
            reftipocredito:{
            	 validators: {
                    notEmpty: {
                        message: 'Debe indicar el tipo de crédito'
                    },
                    
                }
            },            
        }
})


.on('error.form.bv', function(e) {
	// Active the panel element containing the first invalid element
	var $form = $(e.target),
    validator = $form.data('bootstrapValidator'),
    $invalidField = validator.getInvalidFields().eq(0),
    $parent_fieldset = $invalidField.parents('fieldset');
    var fieldset_button_next = $invalidField.parents('fieldset').find('button').children('.btn-next');    
    fieldset_button_next.attr("disabled", true);	
    var current_active_step = $(this).parents('.f1').find('.f1-step.active');
    var progress_line = $(this).parents('.f1').find('.f1-progress-line');    
    current_active_step.removeClass('active').addClass('activated');
    $parent_fieldset.removeClass('activated').addClass('active');
    //data.bv.disableSubmitButtons(false);       
    })

.on('success.form.bv', function(e, data) {
    // Prevent form submission
    e.preventDefault();
    // Get the form instance
    var $form = $(e.target);
    // data.bv.disableSubmitButtons(false);
              
   // $('.f1').data('bootstrapValidator').disableSubmitButtons(false);	
    // Get the BootstrapValidator instance
    var bv = $form.data('bootstrapValidator');
});



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




		$('.nuevaSolicitud').click(function(){
		// jalamos la informacion del formulario

		
			var camposVacios = 0;
			 $('.f1').find('input[type="text"], input[type="password"], textarea, select').each(function(){
			 		var id =	$(this).attr( "id" );			 
		    		if( $(this).val() == "" ) {		    			
		    			$(this).addClass('input-error');    			
		    			camposVacios =1;
		    		}
		    		else {
		    			$(this).removeClass('input-error');
		    		}
	    	});
			//camposVacios  = 1;
	    	if(camposVacios ==1 ){
	    		swal({
	    			title: "Información incompleta",
					text: "Por favor verifique que todos los marcados en rojo contienen información",
					type: "error",
					timer: 5000,
					showConfirmButton: false
					});
	    	}
	    	// fields validation
			if(!camposVacios){
			var formData = new FormData($(".f1")[0]);
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
				dataType: 'JSON', 
				//mientras enviamos el archivo
				beforeSend: function(){
					alert("Enviando datos por favor espere un momento...");
				},
				//una vez finalizado correctamente
				success: function(result, textStatus){	
				//console.log( JSON.stringify(result))	;
				alert(result.error);
				alert(result.IdCliente);
				//var resultado = JSON.parse(result);	

					if (result.error == "") {
						swal({
								title: "Respuesta",
								text: "Su solictud ha sido registrada, en breve nos pondremos en contacto con usted!!",
								type: "success",
								timer: 2500,
								showConfirmButton: false
						});

						$('#idprueba2').val(result.IdCliente);
						$('#idprueba1').val(result.IdSolicitud);

						$('#unidadnegocio').val('');
						$('.editarSolicitud').show();
						$('.nuevaSolicitud').hide();
						$('#accion').val('editarSolicitudCredito');
						//alert("RESULT=> IdSol : "+ result.data.IdSolicitud);
						//alert("RESULT=> IdClien : "+ result.data.IdCliente);

						// aqui cargamos los Id de las tablas que se generaron para poder poner los valores en lo Hidden y cuando editen la solicitud ya tengan todos los datos completos
						 url = "../";
                                   $(location).attr('href',url);

					} else {
						//console.log( JSON.stringify(result))		;
						alert("eRROR");
						alert(result.error);
						console.log(result);
						swal({
								title: "Respuesta",
								text: result,
								type: "error",
								timer: 2500,
								showConfirmButton: false
						});


					}
				},
				//si ha ocurrido un error
				error: function( tipoError , textStatus,errorThrown ){
					alert("Eror en el insert " + tipoError + " => " + textStatus );
					$(".alert").html('<strong>Error!</strong> Actualice la pagina');
					$("#load").html('');
				}

			});
		}

		});

		$('.editarSolicitud').click(function(){
		// jalamos la informacion del formulario
		alert("entra a la funcion editar");
		var id1 = $('#idprueba1').val();
		var id2 = $('#idprueba2').val();

		alert('IDS=>'+ id1 + " =>"+ id2);
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
				dataType: 'JSON', 
				//mientras enviamos el archivo
				beforeSend: function(){
					alert("Enviando datos...");
				},
				//una vez finalizado correctamente
				success: function(result){
					if (result.error == '') {
						swal({
								title: "Respuesta",
								text: "Sus datos han sido actualizados, gracias!",
								type: "success",
								timer: 2500,
								showConfirmButton: false
						});

						
						$('#unidadnegocio').val('');
						$('.editarSolicitud').show();
						$('.nuevaSolicitud').hide();
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
			var formData = new FormData($(".formulario")[0]);
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
</script>








</body>
<?php } ?>
</html>
