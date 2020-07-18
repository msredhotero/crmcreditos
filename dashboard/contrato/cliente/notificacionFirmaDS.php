<?php
session_start();
if (!isset($_SESSION['usuaid_sahilices']))
{
  //header('Location: ../error.php');
print_r($_SESSION);

} else {
include ('../../../class_include.php');

$serviciosUsuario = new ServiciosUsuarios();
$serviciosHTML = new ServiciosHTML();
$serviciosFunciones = new Servicios();
$serviciosReferencias   = new ServiciosReferencias();
$baseHTML = new BaseHTML();
$baseHTML->setContentHeader ('Notificación ', 'Home/Contrato/Cliente');
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

    <div class="jumbotron text-justify col-sm-10 ">
      <h3 >Firma contrato</h3>
      <p class="lead">Contrato firmado correctamente!</p>
      <hr class="my-4">
     
      
    </div>
       
         





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

<?php session_start();
session_destroy();
?>
</body>
</html>