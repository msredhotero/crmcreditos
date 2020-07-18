<?php
session_start();
if (!isset($_SESSION['usua_sahilices']))
{
  header('Location: ../error.php');
} else {
include ('../../../class_include.php');

$serviciosUsuario = new ServiciosUsuarios();
$serviciosHTML = new ServiciosHTML();
$serviciosFunciones = new Servicios();
$serviciosReferencias   = new ServiciosReferencias();
$baseHTML = new BaseHTML();

$baseHTML->setContentHeader ('Contrato ', 'Home/Contrato');

$dataContratoGlobal = new ServiciosSolicitudes();
$page = new Formulario();
$form = new FormularioSolicitud();


$contenidoformularioNuevo = array();
$dataContratoGlobal->cargarDatosSolicitud();
$idFormulario = 'sign_in';
$lectura = false;

$form->setDatos($dataContratoGlobal->getDatos());
$form->set_lectura($lectura);

$page = new Formulario();
$contenidoformularioNuevo = $form->formaA();
$page->add_content($contenidoformularioNuevo);
$classFormulario ='nuevaSolicitud';
$action = 'insertarSolicitudCreditoGlobal';
$title = 'REGISTRAR DATOS DEL ASPIRANTE A CRÉDITO';
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
</style>
 
</head>

<body class="hold-transition sidebar-mini layout-navbar-fixed control-sidebar-push ">
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


       
        <?php echo $formularioCARD;?>





      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
   
  <?php echo $baseHTML->getFooter(); ?> 

  <!-- Control Sidebar --> 
  <?php echo $baseHTML->getControlSideBar(); ?> 
  <!-- /.control-sidebar -->
 

</div>
<!-- ./wrapper -->
<!-- jQuery -->
<!-- Bootstrap 4 -->
<!-- AdminLTE App -->
 <?php echo $baseHTML->getJsAdminLTE(); ?> 


<script type="text/javascript">
$(document).ready(function () {
  //bsCustomFileInput.init();
});
</script>
</body>
</html>