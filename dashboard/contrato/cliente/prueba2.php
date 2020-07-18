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
$contenidoformularioNuevo = $form->formaSolicitud();
$page->add_content($contenidoformularioNuevo);
$classFormulario ='nuevaSolicitud';
$action = 'insertarSolicitudCredito';
$btonOpc = array('class'=>'NuevaSolcitud', 'label'=>'AgregrarSolcitud');
$title = 'REGISTRAR DATOS DEL ASPIRANTE A CRÉDITO';
$formularioCARD = $page->htmlCardFormulario('', 'card-info', 10, $idFormulario, $action, $title);

$page = new Formulario();
$contenidoformularioNuevo = $form->wizardSolicicitud();
$page->add_content($contenidoformularioNuevo);
$classFormulario ='nuevaSolicitud';
$action = 'insertarSolicitudCredito';
$btonOpc = array('class'=>'NuevaSolcitud', 'label'=>'AgregrarSolcitud');
$title = array('Solicitud de crédito','Por favor registra tu información');
$formularioAltaWizard = $page->htmlWizardDos(false, 11, 'Solicitud de crédito***','Por favor registra tu información' ,$idFormulario,$action);




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
        


<script type="text/javascript">
$(document).ready(function () {
  //bsCustomFileInput.init();


  $('.f1s').bootstrapValidator({ 
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
            apellidomaterno: {
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
 
  swal({
    title: "Respuesta",
    text: 'Es necesario que corrijas la informacion marcada en rojo',
    type: "error",
    timer: 2000,
    showConfirmButton: false
    });
    //data.bv.disableSubmitButtons(false);       
    })

.on('success.form.bv', function(e, data) {
    // Prevent form submission
    
    // ejecuatamos la funcion para guardar la información

    guardarSolicitudContrato();
    
});

function guardarSolicitudContrato(){

  //información del formulario
      var formData = new FormData($(".f1s")[0]);
      var message = "";
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
                text: "Tus datos han sido registrados, ahora por favor sube tus documentos",
                type: "success",
                timer: 3000,
                showConfirmButton: false
            });

            $('#accion').val('editarDatosSolicitud');
            $('#btn_guardar').html('Editar');

            var url = "../";
            setTimeout(function(){
              $(location).attr('href',url);
            },3000); 

            
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
          $(".alert").html('<strong>Error!</strong> Ocurrio un problema al gaurdar los datos, F5 para actualizar la página');
          $("#load").html('');
        }
      });


}

});
</script>
</body>
</html>