<?php
session_start();
if (!isset($_SESSION['usua_sahilices']))
{
  header('Location: ../../error.php');
} else {
include ('../../class_include.php');

include ('../../includes/class/PasosContrato.class.php');

$serviciosUsuario = new ServiciosUsuarios();
$serviciosHTML = new ServiciosHTML();
$serviciosFunciones = new Servicios();
$serviciosReferencias   = new ServiciosReferencias();
$baseHTML = new BaseHTML();

$idContratoGlobal = (isset($_GET['id']))? $_GET['id']:NULL;
$PasosContrato = new PasosContrato($idContratoGlobal);
#$idContratoGlobal = $_GET['idcg'];

$baseHTML->setContentHeader ('', 'Home');
$bgPaso1 ='bg-gray';
$bgPaso2 ='bg-gray';
$bgPaso3 ='bg-grayligth';
$bgPaso4 ='bg-grayligth';
$bgPaso5 ='bg-grayligth';
$statusPaso1 ='';
$statusPaso2 ='';
$statusPaso3 ='';
$statusPaso4 ='';
$statusPaso5 ='';


$statusCompleto1 = "Datos completos";
$statusCompleto2 = "Documentos completos";





$ultimaAccion  = $PasosContrato->getUltimaAccion();


$texto_aviso ="Realiza estos dos pasos y podrás tener tu contrato activo en 1 día hábil";

for($i= 1; $i<=5; $i++){
  $var = 'bgPaso'.$i;
  $status = 'statusPaso'.$i;
  if($i<= $ultimaAccion){
    $$var ='bg-info';
    $varstatus ='statusCompleto'.$i;
    $$status = $$varstatus;
  }
} 

$ligaDoctos ='cliente/documentos/subirdocumentosi.php';
$ligaDoctos ='cliente/documentos/subirdocumentosiListaDoctos.php?id='.$idContratoGlobal;
$texto_notificacion = '';

if($statusPaso2 =='Completo'){
  $ligaDoctos ='cliente/documentos/verdocumentos.php';
  $texto_notificacion = 'Por favor espere nuestra llamada<p>
  Para cualquier duda o aclaración <p>
  Teléfono: <b>(55) 51350259</b><p>
  Whatsapp: <b>+52 55 75 13 08 48</b>';
}
#echo $statusPaso2."<=";
$muestraPaso3 = false;

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


       
         <div class="row  ">
          <div class="col-xl-12 col-lg-12 col-md-12 col-12 ">
            
         
       

                

                <div class="h3 text-lightblue ">
                                   
                <?php echo $texto_aviso;?>
                </div>
                <hr>
                </div>
           

         </div> 
         <div class="row">
          <div class="col-xl-2 col-lg-6 col-md-6 col-12">
            
          </div>
          <div class="col-xl-4 col-lg-6 col-md-6 col-12">
            <!-- small card -->
            <div class="small-box <?php echo $bgPaso1;?> elevation-3">
              <div class="inner">
                <h2 class=" "><b>1</b><small> <?php echo $statusPaso1 ;?></small></h2>
                <p><h6 class="lead"><b><?php if($statusPaso1 ==''){?>Registra tus datos<?php }?></b><br></h6></p>
                
              </div>
              <div class="icon ">
                <i class="fas fa-user-plus"></i>
              </div>
              <a href="cliente/?id=<?php echo $idContratoGlobal;?>" class="small-box-footer ">
                Click aqui <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>


          <div class="col-xl-4 col-lg-6 col-md-6 col-12">
            <!-- small card -->
            <div class="small-box <?php echo $bgPaso2;?> elevation-3">
              <div class="inner">
                <h2 class=" "><b>2</b><small> <?php echo $statusPaso2 ;?></small></h2>

                <p><h6 class="lead"><b><?php if($statusPaso2 ==''){?>Sube tus documentos<?php }?></b><br></h6></p>
              </div>
              <div class="icon ">
                <i class="fas fa-upload"></i>
              </div>
              <a href="<?php echo  $ligaDoctos;?>" class="small-box-footer ">
                Click aqui <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>             
        </div>
        <!-- /.row -->

        <div row pt-5>
         <div class="lead">
           


         </div>

        </div>
       <?php if( $texto_notificacion != ''){ ?>
        <div class="row  text-center ">
          <div class=" text-justify col-sm-3 "></div>
        <div class="jumbotron text-justify col-sm-6 ">
            <h3 >Proceso de trámite inicial completo</h3>
            <p class="lead"><?php echo $texto_notificacion; ?></p>
            <hr class="my-4">                
        </div>
      </div>
      <?php } ?>
       


      <?php if($muestraPaso3) { ?>

        <div class="row pt-5">
          <div class="col-xl-3 col-lg-6 col-md-6 col-12">
            <!-- small card -->
            <div class="small-box <?php echo $bgPaso3;?> elevation-3 bt-1">
              <div class="inner">
                <h2 class="text-info "><b>3</b><small> <?php echo $statusPaso3 ;?></small></h2>

                <p><h6 class="text-info"><b>Descarga tu paquete</b></h6></p>
              </div>
              <div class="icon text-info">
                <i class="fas fa-file-download"></i>
              </div>
              <a href="#" class="small-box-footer text-info">
                Click aqui <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>


          <div class="col-xl-3 col-lg-6 col-md-6 col-12">
            <!-- small card -->
            <div class="small-box <?php echo $bgPaso4;?> elevation-3">
              <div class="inner">
                <h2 class="text-info "><b>4</b> <small> <?php echo $statusPaso4 ;?></small></h2>

                <p><h6 class="text-info"><b>Obtén tu NIP</b></h6></p>
              </div>
              <div class="icon text-info">
                <i class="fas fa-key"></i>
              </div>
              <a href="#" class="small-box-footer text-info">
                Click aqui <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>    

          <div class="col-xl-3 col-lg-6 col-md-6 col-12">
            <!-- small card -->
            <div class="small-box <?php echo $bgPaso5;?> elevation-3 a">
              <div class="inner">
                <h2 class="text-info "><b>5</b> <small> <?php echo $statusPaso5 ;?></small></h2>

                <p><h6 class=" text-info"><b>Firma tu contrato</b></h6></p>
              </div>
              <div class="icon text-info">
                <i class="fas fa-file-signature"></i>
              </div>
              <a href="#" class="small-box-footer text-info">
                Click aqui <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>         
        </div>
        <!-- /.row -->


<?php } ?>


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