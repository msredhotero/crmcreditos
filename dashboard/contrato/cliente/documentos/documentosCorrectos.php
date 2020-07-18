<?php
session_start();
if (!isset($_SESSION['usua_sahilices']))
{
  header('Location: ../../../../error.php');
} else {
include ('../../../../class_include.php');


$baseHTML = new BaseHTML();

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
     <style>

        .jumbotron {
  padding-top: 3rem;
  padding-bottom: 3rem;
  margin-bottom: 0;
  background-color: #fff;
}
@media (min-width: 768px) {
  .jumbotron {
    padding-top: 6rem;
    padding-bottom: 6rem;
  }
}

.jumbotron p:last-child {
  margin-bottom: 0;
}

.jumbotron h1 {
  font-weight: 300;
}

.jumbotron .container {
  max-width: 40rem;
}


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

        <main role="main" class="mx-auto row-cols-1 row-cols-sm-3 row-cols-md-3  row-cols-lg-2 row-cols-xl-2 ">  
          <div class="mx-auto pt-5">
            <div class="jumbotron">
            <h3 >Documentos recibidos!</h3>
            <p class="lead">La información y documentos que nos ha proporcionado serán validados por Finanaciera CREA, y posteriormente nuestro personal se ponadrá en contacto con usted vía telefónica para continuar con el proceso.</p>
            <hr class="my-4">
            <p>Por favor active su cuenta lo antes posible</p>
            
          </div>
          </div>
  
</main>   

       

            



  
  
             



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
          extension: 'pdf,jpg,jpeg',
          type: 'application/pdf,image/jpeg',
          //minSize: 1024*1024,
          //maxSize: 10*1024*1024,
          message: 'Por favor selecciones un archivo pdf o jpg  de 1M a 10M.'          
        }
      }
    },
    22:{
      validators: {
        notEmpty: {
          message: 'Requerido'
        },
      }
    },
    32:{
      validators: {
        notEmpty: {
          message: 'Requerido'
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
if(action =='editarSolContGlobal'){
  editarSolicitudContratoGlobal();
}else if(action =='insertarSolContGlobal'){

}
   console.log('validado correctamente');  
   

   guardarDocumentosContratoGlobal();  
});



 



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

        dataType: 'JSON',
        beforeSend: function(){

        },
        //una vez finalizado correctamente
        success: function(result){

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

            var url = "documentosCorrectos.php";
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