<?php
session_start();
$parametros  = explode('&',base64_decode(urldecode($_GET[achid])));
foreach ($parametros as $key => $value ) {
  $variable = explode('=', $value);
  $var= filter_var($variable[0], FILTER_SANITIZE_URL);
  $$var =  filter_var($variable[1],FILTER_SANITIZE_NUMBER_INT); 
}
if (empty($idU) && !isset($_SESSION['usuaid_sahilices']))
{
  //header('Location: ../../../error.php');

  echo "sale de la aplicaion ";
} else {
include ('../../../class_include.php');
$urlcorrupta = false;
// verificamos que los datos del contrato sean del usuario logeuado, sino pertenecen pedimos que se loguee
$urlNuevoNIP = '';


$destroyS = 0;
$idUsuario = isset($idU)?$idU:'' ;
if(!isset($_SESSION['usuaid_sahilices'])){
  $usuario = new Usuario($idUsuario);
  $_SESSION['usuaid_sahilices'] = $idUsuario ;
  $_SESSION['nombre_sahilices'] = $usuario->getNombre();
  $_SESSION['usua_sahilices']= $usuario->getUsuario();
  $_SESSION['email_sahilices']= $usuario->getEmail();
  $_SESSION['idroll_sahilices']= $usuario->getRolId();
  $_SESSION['refroll_sahilices']= $usuario->getRol();

  $destroyS = 1;
}
$usuario = new Usuario();
$usuarioLogueado = $usuario->getUsuarioId();

$urlCorrupta = $usuario->validadUsuarioContrato($idU,$idCG);
if($urlCorrupta){
   header('Location: ../../../error.php');  
}

$serviciosUsuario = new ServiciosUsuarios();
$serviciosHTML = new ServiciosHTML();
$serviciosFunciones = new Servicios();
$serviciosReferencias   = new ServiciosReferencias();
$baseHTML = new BaseHTML();
$query = new Query();

$baseHTML->setContentHeader ('Contrato ', 'Home/Contrato');
$idContratoGlobal = $idCG;
$idAutorizacion = $idA;

// VERICAMOS QUE LA AUTORIZACION CORRESPONDA CON EL CONTRATO GLOBAL
$condicion = 'refcontratoglobal ='. $idContratoGlobal;
$idAutorizaconContrato = $query->selectCampo('idsolicitudautorizacioncirculocredito', 'dbsolicitudesautorizacioncirculocredito', $condicion );

$idTokenContrato = $query->selectCampo('reftoken', 'dbsolicitudesautorizacioncirculocredito', $condicion );

if($idAutorizaconContrato != $idAutorizacion){
  // link corrupto
  //header('Location: ../../../error.php'); 
  #echo "Dos";
}



$urlNuevoNIP =trim("idU=$idUsuario&idCG=$idContratoGlobal&reftipo=1");
$urlNuevoNIP = urlencode(base64_encode($urlNuevoNIP));

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
$title = 'Autorizar consulta de historial crediticio';
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

             

        <?php echo $formularioCARD ;?>




             
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
        


<script type="text/javascript">
$(document).ready(function () {

  $(".positive").numeric({ decimal: false, negative: false });
  $(".decimal-1-places").numeric({ decimalPlaces: 1, negative: false });
  $(".decimal-2-places").numeric({ decimalPlaces: 2, negative: false });
  $(".decimal-3-places").numeric({ decimalPlaces: 3, negative: false });
  $(".decimal-4-places").numeric({ decimalPlaces: 4, negative: false });
  $('.f1s').bootstrapValidator({ 
  live: 'enabled',
  message: 'This value is not valid',
  submitButton: '$user_fact_form button[type="submit"]',
   fields: {  
    burocredito:{
      validators: {
        notEmpty: {          
          message: 'Para continuar es necesario autorizar la consulta de sus antecedentes créditicios, por favor seleccione la casilla.'
          },         
      }
    },

     NIP:{
      validators: {
        notEmpty: {
          message: ' NIP '
        },
      }
    },
  }
})


.on('error.form.bv', function(e) {
  // Active the panel element containing the first invalid element
 
  swal({
    title: "Respuesta",
    text: 'Verifique las observaciones en rojo',
    type: "error",
    timer: 2000,
    showConfirmButton: false
    });
    //data.bv.disableSubmitButtons(false);       
    })

.on('success.form.bv', function(e, data) {
    // Prevent form submission
    
    // ejecuatamos la funcion para guardar la información
 var action = $('#accion').val();
if(action =='historialCrediticio'){
  guardarAutorizacionHistorial();
}    
});


function guardarAutorizacionHistorial(){
  $(".strtoupper").val (function () {
    return this.value.toUpperCase();
  });
  //información del formulario
      var formData = new FormData($(".f1s")[0]);
      var message = "";
      var dS = <?php echo $destroyS;?> ;
      //hacemos la petición ajax
      $.ajax({
        url: '../../../ajax/ajax.php',
        type: 'POST',
        // Form data
        //datos del formulario
        data: formData,
        //necesario para subir archivos via ajax
        cache: false,
        contentType: false,
        processData: false,
        //mientras enviamos el archivo

        dataType: 'JSON',
        beforeSend: function(){

        },
        //una vez finalizado correctamente
        success: function(result){

          if (result.error == "") {
            swal({
                title: "Respuesta",
                text: "Autorización registrada correctamente.",
                type: "success",
                timer: 3000,
                showConfirmButton: false
            });

           
            var url = '';
            if(dS == 1){
             url = "notificacionHistorialDS.php";
            }else{
              url = "notificacionHistorial.php";
            }            
            setTimeout(function(){              
              location.replace(url);
            },3000); 

            
          } else {
            var text= 'Error: ';      
            text =  text + result.errorMensaje;
            swal({
                title: "Respuesta",
                text: text,
                type: "error",
                timer: 3500,
                showConfirmButton: false
            });
          }


          if(result.error == 3){
             var url = 'solicitarNuevonip.php?snnid=<?php echo $urlNuevoNIP;?>';
            setTimeout(function(){             
              location.replace(url);
            },3000); 


          }

           
        },
        //si ha ocurrido un error
        error: function(){
          $(".alert").html('<strong>Error!</strong> Ocurrio un problema al guardar los datos, F5 para actualizar la página');
          $("#load").html('');
        }
      });


}







$(":checkbox").change(function(){
    var val = ($(this).is(':checked'))?"1":0;
    $(this).val(val);    
  });



});
</script>
</body>
</html>