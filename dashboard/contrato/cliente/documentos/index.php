<?php
session_start();
if (!isset($_SESSION['usua_sahilices']))
{
  header('Location: ../../../../error.php');
} else {
include ('../../../../class_include.php');


$baseHTML = new BaseHTML();
$baseHTML->setContentHeader ('Documentos ', 'Home/Contrato/Cliente/Documentos');

$dataContratoGlobal = new ServiciosSolicitudes();
$dataContratoGlobal->cargarDatosContratoGlobal();
$idContratoGlobal = $dataContratoGlobal->getDato('idcontratoglobal');

#$dataContratoGlobal->cargarDoctosContratoGlobal();

$form = new FormularioSolicitud();

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

             

        <?php  echo $formularioCARD ;?>

            



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


        


<script type="text/javascript">
$(document).ready(function () {
 //camposBloquedosInicio();
   bsCustomFileInput.init();
  //$('#carga_aviso_doctos').load('aviso_documentos.html');

  //setTimeout(function(){
    $('#aviso_doctos').modal('show');
  //},1000);

 
   $('.f1s').bootstrapValidator({ 
  live: 'enabled',
  message: 'This value is not valid',
  submitButton: '$user_fact_form button[type="submit"]',
 
 
   fields: {
    1:{
      validators: {
        notEmpty: {                    
          message: 'Requerido'
        },
        file: {                  
         // extension: 'pdf,jpg,jpeg',
          extension: 'pdf,jpg,jpeg',
          type: 'application/pdf,image/jpeg',
          minSize: 1024,
          maxSize: 2*1024*1024,
          message: 'Por favor selecciones un archivo .pdf máximo de 2M.'          
        },
        different: {
          field: '2,3,4,5,6,7,8,9',
          message: 'No puede cargar el mismo archivo en dos campos diferentes'
        }
      }
    },
    2:{
      validators: {
        notEmpty: {
          enabled: false,          
          message: 'Requerido'
        },
        file: {                    
          extension: 'pdf,jpg,jpeg',
          type: 'application/pdf,image/jpeg',
          minSize: 1024,
          maxSize: 2*1024*1024,
          message: 'Por favor selecciones un archivo .pdf máximo de 2M.'          
        },
        different: {
          field: '1,3,4,5,6,7,8,9',
          message: 'No puede cargar el mismo archivo en dos campos diferentes'
        }
      }
    },
    3:{
      validators: {
        notEmpty: {
          enabled: false,          
          message: 'Requerido'
        },
        file: {                  
          extension: 'pdf,jpg,jpeg',
          type: 'application/pdf,image/jpeg',
          minSize: 1024,
          maxSize: 2*1024*1024,
          message: 'Por favor selecciones un archivo .pdf máximo de 2M.'          
        },
        different: {
          field: '1,2,4,5,6,7,8,9',
          message: 'No puede cargar el mismo archivo en dos campos diferentes'
        }
      }
    },
    4:{
      validators: {
        notEmpty: {
          enabled: false,          
          message: 'Requerido'
        },
        file: {                   
          extension:'pdf,jpg,jpeg',
          type: 'application/pdf,image/jpeg',
          minSize: 1024,
          maxSize: 2*1024*1024,
          message: 'Por favor selecciones un archivo .pdf máximo de 2M.'          
        },
        different: {
          field: '1,2,3,5,6,7,8,9',
          message: 'No puede cargar el mismo archivo en dos campos diferentes'
        }
      }
    },
    5:{
      validators: {
        notEmpty: {
          enabled: false,          
          message: 'Requerido'
        },
        file: {                   
          extension:'pdf,jpg,jpeg',
          type: 'application/pdf,image/jpeg',
          minSize: 1024,
          maxSize: 2*1024*1024,
          message: 'Por favor selecciones un archivo .pdf máximo de 2M.'          
        },
        different: {
          field: '1,2,3,4,6,7,8,9',
          message: 'No puede cargar el mismo archivo en dos campos diferentes'
        }
      }
    },
    6:{
      validators: {
        notEmpty: {
          enabled: false,          
          message: 'Requerido'
        },
        file: {                    
          extension: 'pdf,jpg,jpeg',
          type: 'application/pdf,image/jpeg',
          minSize: 1024,
          maxSize: 2*1024*1024,
          message: 'Por favor selecciones un archivo .pdf máximo de 2M.'          
        },
        different: {
          field: '1,2,3,4,5,7,8,9',
          message: 'No puede cargar el mismo archivo en dos campos diferentes'
        }
      }
    },
    7:{
      validators: {
        notEmpty: {
          enabled: false,          
          message: 'Requerido'
        },
        file: {                  
          extension:'pdf,jpg,jpeg',
          type: 'application/pdf,image/jpeg',
          minSize: 1024,
          maxSize: 2*1024*1024,
          message: 'Por favor selecciones un archivo .pdf máximo de 2M.'          
        },
        different: {
          field: '1,2,3,4,5,6,8,9',
          message: 'No puede cargar el mismo archivo en dos campos diferentes'
        }
      }
    },
    8:{
      validators: {
        notEmpty: {
          enabled: false,          
          message: 'Requerido'
        },
        file: {                  
          extension: 'pdf,jpg,jpeg',
          type: 'application/pdf,image/jpeg',
          minSize: 1024,
          maxSize: 2*1024*1024,
          message: 'Por favor selecciones un archivo .pdf máximo de 2M.'          
        },
        different: {
          field: '1,2,3,4,5,6,7,9',
          message: 'No puede cargar el mismo archivo en dos campos diferentes'
        }
      }
    },
    9:{
      validators: {
        notEmpty: {
          enabled: false,          
          message: 'Requerido'
        },
        file: {                    
          extension: 'pdf,jpg,jpeg',
          type: 'application/pdf,image/jpeg',
          minSize: 1024,
          maxSize: 2*1024*1024,
          message: 'Por favor selecciones un archivo .pdf máximo de 2M.'          
        },
        different: {
          field: '1,2,3,4,5,6,7,8',
          message: 'No puede cargar el mismo archivo en dos campos diferentes'
        }
      }
    },

    21:{
      validators: {
        notEmpty: {
          enabled: false,          
          message: 'Requerido'
        },
        file: {                    
          extension: 'pdf,jpg,jpeg',
          type: 'application/pdf,image/jpeg',
          minSize: 1024,
          maxSize: 2*1024*1024,
          message: 'Por favor selecciones un archivo .pdf máximo de 2M.'          
        },
        different: {
          field: '1,2,3,4,5,6,7,8',
          message: 'No puede cargar el mismo archivo en dos campos diferentes'
        }
      }
    },

    22:{
      validators: {
        notEmpty: {
          enabled: false,          
          message: 'Requerido'
        },
        file: {                    
          extension: 'pdf,jpg,jpeg',
          type: 'application/pdf,image/jpeg',
          minSize: 1024,
          maxSize: 2*1024*1024,
          message: 'Por favor selecciones un archivo .pdf máximo de 2M.'          
        },
        different: {
          field: '1,2,3,4,5,6,7,8',
          message: 'No puede cargar el mismo archivo en dos campos diferentes'
        }
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
  
   guardarDocumentosContratoGlobal();  
});




$("[name='1']").on("click ",function(){
  activaRequeridos();
});


 function activaRequeridos(){ 
  console.log("Activa reuqridos");
  $(":file").each(function() {
      var name = $(this).attr('name');      
      var isRequired = $('#'+name+'_requerido').val() ==  1;
      console.log( "file=>"+ name +" Requerido =>"+ isRequired);
      if(isRequired){
        // si esta requerido activamos la validacion de requerido
        $('.f1s').bootstrapValidator('enableFieldValidators', name, isRequired);
        $('.f1s').bootstrapValidator('validateField', name);        
      }
  });


  var requiereCedula = $('#cedulasi').val() == 1;
  var requiereFirma = $('#firmasi').val() == 1;

  if(requiereCedula){
    $('.f1s').bootstrapValidator('enableFieldValidators', '21', requiereCedula);
    $('.f1s').bootstrapValidator('validateField', '21');  
  }

  if(requiereFirma){
    $('.f1s').bootstrapValidator('enableFieldValidators', '22', requiereFirma);
    $('.f1s').bootstrapValidator('validateField', '22');  
  }
 }



function guardarDocumentosContratoGlobal(){
  $(".strtoupper").val (function () {
    return this.value.toUpperCase();
  });

  
  //información del formulario
      var formData = new FormData($(".f1s")[0]);
      var message = "";
      //hacemos la petición ajax
      $.ajax({
        url: '../../../../ajax/ajax.php',
        type: 'POST',
        // Form data
        //datos del formulario
        data: formData,
        //necesario para subir archivos via ajax
        enctype: 'multipart/form-data',
        cache: false,
        contentType: false,
        processData: false,
        //mientras enviamos el archivo       
        beforeSend: function(){

        },
        //una vez finalizado correctamente
        success: function(result){
          console.log("resultado de la funcion=>"+result+"---");


          if (result == "") {
            swal({
                title: "Respuesta",
                text: "Documentos cargados correctamente",
                type: "success",
                timer: 3000,
                showConfirmButton: false
            });

            $('#accion').val('editarSolContGlobal');
            $('#btn_guardar').html('Editar');

            var url = "notificacionCliente.php";
            setTimeout(function(){
              $(location).attr('href',url);
            },3000); 

            
          } else {
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