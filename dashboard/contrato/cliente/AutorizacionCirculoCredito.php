<?php
session_start();
if(!isset($_SESSION['usuaid_sahilices'] ))
{
  header('Location: ../../../error.php');
} else {


include ('../../../class_include.php');



$idContrato = $_GET['idContratoGlobal'];
$idDocumentos = $_GET['idDocto'];

$query = new Query();

	$condicion = 'refcontratoglobal ='.$idContrato;
	$usuarioAutorizacion = $query->selectCampo('refusuario', 'dbautorizacionesburo', $condicion);
	$sqlDocto = "SELECT * FROM 	dbautorizacionesburo WHERE 	refusuario = $usuarioAutorizacion ";
	$query->setQuery($sqlDocto);
	$resD = $query->eject();
$cadena_div = '';
$cadena1 = '';
$cadena2 = '';
	while($objD = $query->fetchObject($resD)){
		$idDiv = $objD->idautorizacionburo;
		$ruta =$objD->ruta;
		$docto = $objD->documento;
		$documento = $ruta.$docto;
		$cadena_div .= '<div id="my-pdf'.$idDiv.'"></div>';
		$cadena_div .= '<p><p>';

		$cadena1 .= ' var container = $("#my-pdf'.$idDiv.'");';
		$cadena1 .=  'PDFObject.embed("../../../'.$documento.'pdf", container, options);';
	}

$serviciosUsuario = new ServiciosUsuarios();
$serviciosHTML = new ServiciosHTML();
$serviciosFunciones = new Servicios();
$serviciosReferencias   = new ServiciosReferencias();
$baseHTML = new BaseHTML();
$query = new Query();

$baseHTML->setContentHeader ('Autorización para consulta a Círculo de Crédito ', 'Home/Autorizacion');
$idContratoGlobal = $_GET['idCG'];
$idAutorizacion = $_GET['idA'];

// VERICAMOS QUE LA AUTORIZACION CORRESPONDA CON EL CONTRATO GLOBAL
$condicion = 'refcontratoglobal ='. $idContratoGlobal;





$dataContratoGlobal = new ServiciosSolicitudes($idContratoGlobal);
$page = new Formulario();
$form = new FormularioSolicitud();

$contenidoformularioNuevo = array();
$dataContratoGlobal->cargarDatosContratoGlobal();
$idFormulario = 'sign_in';
$lectura = false;
$form->setDatos($dataContratoGlobal->getDatos());
$form->set_lectura($lectura);

$page = new Formulario();
$contenidoformularioNuevo = $form->autorizarConsultaHistoriaCrediticio();
$page->add_content($contenidoformularioNuevo);
$classFormulario ='nuevaSolicitud';



$idContratoGlobal = $dataContratoGlobal->getDato('idcontratoglobal');

$action = (!empty($idContratoGlobal))?'historialCrediticio':'';
$title = 'Autorización Círculo de Crédito';
$formularioCARD = $page->htmlCardFormulario('', 'card-info', 10, $idFormulario, $action, $title);







}
?>

<!DOCTYPE html>
<html>
<head>
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
      <!--    <link rel="stylesheet" href="../../../bootstrap/bootzard-wizard/assets/bootstrap/css/bootstrap.min.css"> -->
        <!-- <link rel="stylesheet" href="../../../bootstrap/bootzard-wizard/assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../../bootstrap/bootzard-wizard/assets/css/form-elements.css">
        <link rel="stylesheet" href="../../../bootstrap/bootzard-wizard/assets/css/style.css"> -->
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

	.pdfobject-container {
	    max-width: 100%;
		width: 90%;
		height: 550px;
		border: 10px solid rgba(0,0,0,.2);
		margin: 0;
	}


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

             

        

        <?php echo $cadena_div;?>



             
<p>


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

 <script type="text/javascript" src="../../../plugins/bootstrapvalidator/dist/js/bootstrapValidator.js"></script>

  <script type="text/javascript" src="../../../plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>
        
<script type="text/javascript"  src="../../../plugins/PDFObject-master/pdfobject.min.js"></script>


<script type="text/javascript">
$(document).ready(function () {

	

	var options = {
    pdfOpenParams: {
     
      navpanes: 1,
      toolbar: 1,
      statusbar: 0,
      
    }
  };

	
<?php echo $cadena1;?>
<?php echo $cadena2;?>
 


  
});





</script>
</body>
</html>
