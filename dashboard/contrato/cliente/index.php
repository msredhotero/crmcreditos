<?php
session_start();
if (!isset($_SESSION['usua_sahilices']))
{
  header('Location: ../../../error.php');
} else {
include ('../../../class_include.php');

$serviciosUsuario = new ServiciosUsuarios();
$serviciosHTML = new ServiciosHTML();
$serviciosFunciones = new Servicios();
$serviciosReferencias   = new ServiciosReferencias();
$baseHTML = new BaseHTML();

$baseHTML->setContentHeader ('Contrato ', 'Home/Contrato');

$nuevaSolicitud = (!empty($_GET['ns']))?1:NULL;
if($nuevaSolicitud){
  $idContratoGlobal = '';
}else{
  $idContratoGlobal = isset($_GET['id'])?$_GET['id']:NULL;
}

$dataContratoGlobal = new ServiciosSolicitudes($idContratoGlobal, $nuevaSolicitud);
$page = new Formulario();
$form = new FormularioSolicitud();

$contenidoformularioNuevo = array();

if($nuevaSolicitud){
  $dataContratoGlobal->cargarDatosContratoGlobalClienteRegistrado();
}else{
  $dataContratoGlobal->cargarDatosContratoGlobal();
}
$idFormulario = 'sign_in';
$lectura = $dataContratoGlobal->getLectura();
$form->setDatos($dataContratoGlobal->getDatos());
$form->set_lectura($lectura);

$page = new Formulario();
$contenidoformularioNuevo = $form->formaContratoGlobal2();
$page->add_content($contenidoformularioNuevo);
$classFormulario ='nuevaSolicitud';



$idContratoGlobal = $dataContratoGlobal->getDato('idcontratoglobal');

$action = (empty($idContratoGlobal))?'insertarSolContGlobal':'editarSolContGlobal';
$title = 'Regristrar datos del aspirante a crédito';
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
 //camposBloquedosInicio();
  
 Inputmask.extendAliases({
  'celularCodigo': {
    autoUnmask: true,
    mask: "99-9999-9999",
    oncomplete: function() {
     // do something
    },
    onincomplete: function() {
     // do something
    }
  }
});
 //camposBloquedosInicio();
  
$('#celular1').attr('data-inputmask',"'alias': 'celularCodigo'");
$('#telefono1').attr('data-inputmask',"'alias': 'celularCodigo'");

$(":input").inputmask();

  //bsCustomFileInput.init();


  $('.f1s').bootstrapValidator({ 
  live: 'enabled',
  message: 'This value is not valid',
  submitButton: '$user_fact_form button[type="submit"]',
 
 
   fields: {
    refempresaafiliada:{
      validators: {
        notEmpty: {
          message: 'Requerido'
        },
      }
    },
    reftipocontratoglobal:{
      validators: {
        notEmpty: {
          message: 'Requerido'
        },
      }
    },           
    nombre: {
      message: 'No valido',
      validators: {
        notEmpty: {
          message: 'Requerido'
        },
        stringLength: {
          max: 300,
          message: 'Muy largo'
        },
        regexp: {
          regexp: /^[a-zA-Z áéíóúñÁÉÍÓÚÑ]+$/,
          message: 'Solo letras '
        },
      }
    },
    paterno: {
      message: 'No valido',
      validators: {
        notEmpty: {
          message: 'Requerido'
        },
        stringLength: {
          max: 300,
          message: 'Muy largo'
        },
        regexp: {
          regexp: /^[a-zA-Z áéíóúñÁÉÍÓÚÑ]+$/,
          message: 'Solo letras '
        },
      }
    },
    materno: {
      message: 'No valido',
      validators: {
        notEmpty: {
          message: 'Requerido'
        },
        stringLength: {
          max: 300,
          message: 'Muy largo'
        },
        regexp: {
          regexp: /^[a-zA-Z áéíóúñÁÉÍÓÚÑ]+$/,
          message: 'Solo letras '
        },
      }
    },
    fechanacimiento: {
      validators: {
        notEmpty: {
          message: 'Requerido'
          },
          date: {
            format: 'YYYY/MM/DD',
            message: '"AAAA/MM/DD" 4 digitos para año / 2 digitos para mes / 2 digitos para día '
            }
      }
    },
    refnacionalidad:{
      validators: {
        notEmpty: {
          message: 'Requerido'
        },
      }
    },
    refentidadnacimiento:{
      validators: {
        notEmpty: {
          message: 'Requerido'
        },
      }
    },
    refgenero:{
      validators: {
        notEmpty: {
          message: 'Requerido'
        },
      }
    },  
    rfc: {
      message: 'No valido',
      validators: {
        notEmpty: {
          enabled: false,
          message: 'Requerido'
        },        
        regexp: {
          regexp: /^([A-ZÑ\x26]{3,4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1]))([A-Z\d]{3})?$/,
          message: 'Formato RFC no valido '
        },
      }
    },
    curp: {
      message: 'No valido',
      validators: {
        notEmpty: {
          enabled: false,
          message: 'Requerido'
        },        
        regexp: {
          regexp: /^[a-zA-Z]{1}[aeiouxAEIOUX]{1}[a-zA-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[hmHM]{1}(as|bc|bs|cc|cs|ch|cl|cm|df|dg|gt|gr|hg|jc|mc|mn|ms|nt|nl|oc|pl|qt|qr|sp|sl|sr|tc|ts|tl|vz|yn|zs|ne|AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[b-df-hj-np-tv-zB-DF-HJ-NP-TV-Z]{3}[0-9a-zA-Z]{1}[0-9]{1}$/,
          message: 'Formato de CURP no valido '
        },
      }
    },


    refdestino:{
      validators: {
        notEmpty: {
          message: 'Requerido'
        },
      }
    },

     reforigen:{
      validators: {
        notEmpty: {
          message: 'Requerido'
        },
      }
    },


    cnombre: {
      message: 'No valido',
      validators: {
        notEmpty: {
          enabled: false,
          message: 'Requerido'
        },
        stringLength: {
          max: 300,
          message: 'Muy largo'
        },
        regexp: {
          regexp: /^[a-zA-Z áéíóúñÁÉÍÓÚÑ]+$/,
          message: 'Solo letras '
        },
      }
    },
    cpaterno: {
      message: 'No valido',
      validators: {
        notEmpty: {
          enabled: false,
          message: 'Requerido'
        },
        stringLength: {
          max: 300,
          message: 'Muy largo'
        },
        regexp: {
          regexp: /^[a-zA-Z áéíóúñÁÉÍÓÚÑ]+$/,
          message: 'Solo letras '
        },
      }
    },
     cmaterno: {
      message: 'No valido',
      validators: {
        notEmpty: {
          enabled: false,
          message: 'Requerido'
        },
        stringLength: {
          max: 300,
          message: 'Muy largo'
        },
        regexp: {
          regexp: /^[a-zA-Z áéíóúñÁÉÍÓÚÑ]+$/,
          message: 'Solo letras '
        },
      }
    },
    calle: {
      message: 'No valido',
      validators: {
        notEmpty: {
          message: 'Requerido'
        },
        stringLength: {
          max: 350,
          message: 'Muy largo'
        },
        regexp: {
          regexp: /^[a-zA-Z áéíóúñÁÉÍÓÚÑ0-9.]+$/,
          message: 'Solo letras y números'
        },       
      }
    },
   
    calle: {
      message: 'No valido',
      validators: {
        notEmpty: {
          message: 'Requerido'
        },
        stringLength: {
          max: 350,
          message: 'Muy largo'
        },
        regexp: {
          regexp: /^[a-zA-Z áéíóúñÁÉÍÓÚÑ0-9.]+$/,
          message: 'Solo letras y números'
        },       
      }
    },

    colonia: {
      message: 'No valido',
      validators: {
        notEmpty: {
          message: 'Requerido'
        },
        stringLength: {
          max: 350,
          message: 'Muy largo'
        },
        regexp: {
          regexp: /^[a-zA-Z áéíóúñÁÉÍÓÚÑ0-9.]+$/,
          message: 'Solo letras y números'
        },       
      }
    },

    numeroexterior: {
      message: 'No valido',
      validators: {
        notEmpty: {          
          message: 'Requerido'
        },
        stringLength: {
          max: 50,
          message: 'Muy largo'
        },        
      }
    },

    codigopostal: {
      message: 'No valido',
      validators: {
        notEmpty: {
          message: 'Requerido'
        },
        stringLength: {
          max: 350,
          message: 'Muy largo'
        },
        regexp: {
          regexp: /^\d{4,5}$/,
          message: 'Formato C.P. no valido'
        },       
      }
    },

    refentidad:{
      validators: {
        notEmpty: {
          message: 'Requerido'
        },
      }
    },
    refmunicipio:{
      validators: {
        notEmpty: {
          message: 'Requerido'
        },
      }
    },
    reflocalidad:{
      validators: {
        notEmpty: {
          message: 'Requerido'
        },
      }
    },

    celular1:{
      validators: {
        notEmpty: {
          message: 'Requerido'
        },
        regexp: {
          regexp: /^\d{10}$/,
          message: 'Celular a 10 dígitos'
        }, 

      }
    },
    refcompania1:{
      validators: {
        notEmpty: {          
          message: 'Requerido'
        },
      }
    },    
    celular2:{
      validators: {        
        regexp: {
          regexp: /^\d{10}$/,
          message: 'Celular a 10 dígitos'
        }, 

      }
    },
     refcompania2:{
      validators: {
        notEmpty: {
          enabled: false,
          message: 'Requerido'
        },
      }
    },
    telefono1:{
      validators: {        
        regexp: {
          regexp: /^[0-9]{8,12}$/,
          message: 'Número'
        }, 

      }
    },
     reftipotelefono1:{
      validators: {
        notEmpty: {
          enabled: false,
          message: 'Requerido'
        },
      }
    },
    telefono2:{
      validators: {        
        regexp: {
          regexp: /^[0-9]{8,12}$/,
          message: 'Número de 8 a 10 dígitos'
        }, 

      }
    },

    reftipotelefono2:{
      validators: {
        notEmpty: {
          enabled: false,
          message: 'Requerido'
        },
      }
    },

    creditohipotecario:{
      validators: {
        notEmpty: {
          message: 'Requerido'
        },
      }
    },

    creditoautomotriz:{
      validators: {
        notEmpty: {
          message: 'Requerido'
        },
      }
    },

    tarjetacredito:{
      validators: {
        notEmpty: {
          message: 'Requerido'
        },
      }
    },
    digitostarjeta:{
      validators: {
        notEmpty: {
          enabled: false,
          message: 'Requerido'
          },
          digits: {
            enabled: false,
            message: 'Números'
          },
          stringLength: {
          min: 4,
          max: 4,
          message: '4 últimos digitos'
        },
      }
    },
    cargopublico:{
      validators: {
        notEmpty: {          
          message: 'Requerido'
          },         
      }
    },
    cargopublicofamiliar:{
      validators: {
        notEmpty: {          
          message: 'Requerido'
          },         
      }
    },
    //burocredito:{
     // validators: {
       // notEmpty: {          
         // message: 'Para continuar es necesario autorizar la consulta de sus antecedentes créditicios, por favor seleccione la casilla.'
         // },         
     // }
  //  },

     refpropietarioreal:{
      validators: {
        notEmpty: {
          message: 'Requerido'
        },
      }
    },

     refaportacionpropia:{
      validators: {
        notEmpty: {
          message: 'Requerido'
        },
      }
    },
   pnombre: {
      message: 'No valido',
      validators: {
        notEmpty: {
          enabled: false,
          message: 'Requerido'
        },
        
        stringLength: {
          max: 300,
          message: 'Muy largo'
        },
        regexp: {
          regexp: /^[a-zA-Z áéíóúñÁÉÍÓÚÑ]+$/,
          message: 'Solo letras '
        },
      }
    },
    ppaterno: {
      message: 'No valido',
      validators: {
        notEmpty: {
          enabled: false,
          message: 'Requerido'
        },
        stringLength: {
          max: 300,
          message: 'Muy largo'
        },
        regexp: {
          regexp: /^[a-zA-Z áéíóúñÁÉÍÓÚÑ]+$/,
          message: 'Solo letras '
        },
      }
    },
     pmaterno: {
      message: 'No valido',
      validators: {
        notEmpty: {
          enabled: false,
          message: 'Requerido'
        },
        stringLength: {
          max: 300,
          message: 'Muy largo'
        },
        regexp: {
          regexp: /^[a-zA-Z áéíóúñÁÉÍÓÚÑ]+$/,
          message: 'Solo letras '
        },
      }
    },


    cuentapropia:{
      validators: {     
        notEmpty: {                 
          message: 'Para continuar es necesario seleccionar la casilla'
          },  

      }
    },

    origenrecursos:{
      validators: {     
        notEmpty: {                 
          message: 'Para continuar es necesario seleccionar la casilla'
          },  

      }
    },

    cuentatercero:{
      validators: {       
        notEmpty: { 
        enabled: false,                
          message: 'Seleccione opción'
          },  

      }
    },

    recursopropio:{
      validators: {      
        notEmpty: { 
         enabled: false,                  
          message: 'Seleccione opción'
          },  

      }
    },

    recursotercero:{
      validators: {      
        notEmpty: { 
          enabled: false,         
          message: 'Requerido'
          },  

      }
    },

     cedulasi:{
      validators: {     
        notEmpty: {                 
          message: 'Seleccione opción'
          },  

      }
    },

     firmasi:{
      validators: {     
        notEmpty: {                 
          message: 'Seleccione opción'
          },  

      }
    },
    fnombre: {
      message: 'No valido',
      validators: {
        notEmpty: {
          enabled: false,
          message: 'Requerido'
        },
        stringLength: {
          max: 300,
          message: 'Muy largo'
        },
        regexp: {
          regexp: /^[a-zA-Z áéíóúñÁÉÍÓÚÑ]+$/,
          message: 'Solo letras '
        },
      }
    },
    fpaterno: {
      message: 'No valido',
      validators: {
        notEmpty: {
          enabled: false,
          message: 'Requerido'
        },
        stringLength: {
          max: 300,
          message: 'Muy largo'
        },
        regexp: {
          regexp: /^[a-zA-Z áéíóúñÁÉÍÓÚÑ]+$/,
          message: 'Solo letras '
        },
      }
    },
     fmaterno: {
      message: 'No valido',
      validators: {
        notEmpty: {
          enabled: false,
          message: 'Requerido'
        },
        stringLength: {
          max: 300,
          message: 'Muy largo'
        },
        regexp: {
          regexp: /^[a-zA-Z áéíóúñÁÉÍÓÚÑ]+$/,
          message: 'Solo letras '
        },
      }
    },

     refparentesco: {
      message: 'No valido',
      validators: {
        notEmpty: {
          enabled: false,
          message: 'Requerido'
        },      
      }
    },
    
    refmediodifusion: {
      message: 'No valido',
      validators: {
        notEmpty: {
          message: 'Requerido'
        },      
      }
    },

    refasesores: {
      message: 'No valido',
      validators: {
        notEmpty: {
          enabled: false,
          message: 'Requerido'
        },      
      }
    },


    difusionesp: {
      message: 'No valido',
      validators: {
        notEmpty: {
          enabled: false,
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
}else if(action =='insertarSolContGlobal')
    guardarSolicitudContratoGlobal();    
});


camposBloquedosInicio();

$("#nombre,#paterno,#materno").change(function(){
  var nombreCliente = $('#nombre').val()+" "+$('#paterno').val()+" "+$('#materno').val();
  $('.nombreClienteAutoriza').html(nombreCliente);
});

$("#refentidad").change(function(){ 
    filter_mun(this.id, "refmunicipio");  
}); 

$("#refmunicipio").change(function(){  
    filter_loc("refentidad", this.id, "reflocalidad");    
});

$("#refempresaafiliada").change(function(){  
    filter_emp(this.id, "reftipocontratoglobal");    
});

$("#tarjetacredito").change(function(){  
    var isRequired = $(this).val() == 1;
    $('.f1s').bootstrapValidator('enableFieldValidators', 'digitostarjeta', isRequired);
        if (isRequired ){            
           bloqueaCampo('digitostarjeta', false);    
           $('.f1s').bootstrapValidator('validateField', 'digitostarjeta');                           
        }else{         
          bloqueaCampo('digitostarjeta', true);    
        }

});

$("#telefono1").change(function(){
    var isRequired = $(this).val().length > 1;
    $('.f1s').bootstrapValidator('enableFieldValidators', 'reftipotelefono1', isRequired);
        if (isRequired ){             
           bloqueaCampo('reftipotelefono1', false);      
           $('.f1s').bootstrapValidator('validateField', 'reftipotelefono1');                          
        }else{
          bloqueaCampo('reftipotelefono1', true);  
        }

});

$("#telefono2").change(function(){
    var isRequired = $(this).val().length > 1;
    $('.f1s').bootstrapValidator('enableFieldValidators', 'reftipotelefono2', isRequired);
        if (isRequired ){            
           bloqueaCampo('reftipotelefono2', false);       
           $('.f1s').bootstrapValidator('validateField', 'reftipotelefono2');                           
        }else{
          bloqueaCampo('reftipotelefono2', true);
        }

});

$("#celular2").change(function(){
    var isRequired = $(this).val().length > 1;
    $('.f1s').bootstrapValidator('enableFieldValidators', 'refcompania2', isRequired);
        if (isRequired ){            
           bloqueaCampo('refcompania2', false);     
           $('.f1s').bootstrapValidator('validateField', 'refcompania2');                           
        }else{
         bloqueaCampo('refcompania2', true);  
        }       

});

$("#celular1").change(function(){
  console.log ("Entra a celular 1");
    var isRequired = $(this).val().length > 1;
    $('.f1s').bootstrapValidator('enableFieldValidators', 'refcompania1', isRequired);
        if (isRequired ){            
          bloqueaCampo('refcompania1', false);     
           $('.f1s').bootstrapValidator('validateField', 'refcompania1');                           
        }else{
         bloqueaCampo('refcompania1', true);  
        }       

});

$("#cuentatercero").change(function(){
   console.log("Cuentapropia");
  var bloqueaCampos ='false';
  var desBloqueaCampos ='true';
  if($(this).prop("checked") == true){
    bloqueaCampos = true;
    desBloqueaCampos =  false;
  }else{
    bloqueaCampos = false;
    desBloqueaCampos =  true;
  }

  $('.f1s').bootstrapValidator('enableFieldValidators', 'cuentapropia', desBloqueaCampos);
  $('.f1s').bootstrapValidator('validateField', 'cuentapropia');
  bloqueaCampo('cuentapropia', bloqueaCampos); 
  bloqueaCampo('cnombre', desBloqueaCampos); 
  bloqueaCampo('cpaterno', desBloqueaCampos);
  bloqueaCampo('cmaterno', desBloqueaCampos);    
  ocultar_secciones(desBloqueaCampos, '#frm_g_propietarioReal');

  $('.f1s').bootstrapValidator('enableFieldValidators', 'cnombre', bloqueaCampos);
  $('.f1s').bootstrapValidator('enableFieldValidators', 'cpaterno', bloqueaCampos);
  $('.f1s').bootstrapValidator('enableFieldValidators', 'cmaterno', bloqueaCampos);

  $('.f1s').bootstrapValidator('validateField', 'cnombre'); 
  $('.f1s').bootstrapValidator('validateField', 'cpaterno'); 
  $('.f1s').bootstrapValidator('validateField', 'cmaterno'); 



});
$("#cuentapropia").change(function(){
  
 // var bloqueaCampos ='false';
 // var desBloqueaCampos ='true';
 // var val = ($(this).is(':checked')) ? "1" : "";

 // if($(this).prop("checked") == true){
  //  bloqueaCampos = true;
 //   desBloqueaCampos =  false;
 // }else{
 //   bloqueaCampos = false;
//    desBloqueaCampos =  true;
//  }
//  bloqueaCampo('cuentatercero', bloqueaCampos); 
//  bloqueaCampo('cnombre', bloqueaCampos); 
 // bloqueaCampo('cpaterno', bloqueaCampos);
 // bloqueaCampo('cmaterno', bloqueaCampos);

 // if(bloqueaCampos)
 // ocultar_secciones(bloqueaCampos, '#frm_g_propietarioReal');

});


$("#recursotercero").change(function(){
   console.log("Cuentapropia");
  var bloqueaCampos ='false';
  var desBloqueaCampos ='true';
  if($(this).prop("checked") == true){
    bloqueaCampos = true;
    desBloqueaCampos =  false;
  }else{
    bloqueaCampos = false;
    desBloqueaCampos =  true;
  }

  $('.f1s').bootstrapValidator('enableFieldValidators', 'recursopropio', desBloqueaCampos);
  $('.f1s').bootstrapValidator('validateField', 'recursopropio'); 
  bloqueaCampo('recursopropio', bloqueaCampos); 
  bloqueaCampo('pnombre', desBloqueaCampos); 
  bloqueaCampo('ppaterno', desBloqueaCampos);
  bloqueaCampo('pmaterno', desBloqueaCampos);    
  ocultar_secciones(desBloqueaCampos, '#frm_g_recursoPropio');

  $('.f1s').bootstrapValidator('enableFieldValidators', 'pnombre', bloqueaCampos);
  $('.f1s').bootstrapValidator('enableFieldValidators', 'ppaterno', bloqueaCampos);
  $('.f1s').bootstrapValidator('enableFieldValidators', 'pmaterno', bloqueaCampos);

  $('.f1s').bootstrapValidator('validateField', 'pnombre'); 
  $('.f1s').bootstrapValidator('validateField', 'ppaterno'); 
  $('.f1s').bootstrapValidator('validateField', 'pmaterno'); 

});
$("#recursopropio").change(function(){
  console.log("Cuentapropia");
  var bloqueaCampos ='false';
  var desBloqueaCampos ='true';
  var val = ($(this).is(':checked')) ? "1" : "";
 // $(this).val(val);

  if($(this).prop("checked") == true){
    bloqueaCampos = true;
    desBloqueaCampos =  false;
  }else{
    bloqueaCampos = false;
    desBloqueaCampos =  true;
  }
  bloqueaCampo('recursotercero', bloqueaCampos); 
  bloqueaCampo('pnombre', bloqueaCampos); 
  bloqueaCampo('ppaterno', bloqueaCampos);
  bloqueaCampo('pmaterno', bloqueaCampos);

  if(bloqueaCampos)
  ocultar_secciones(bloqueaCampos, '#frm_g_recursoPropio');

});

$("#firmasi").change(function(){
  
  var bloqueaCampos ='false';  
  var val = ($(this).is(':checked')) ? "1" : "";
  if($(this).prop("checked") == true){
    bloqueaCampos = true;    
  }else{
    bloqueaCampos = false;    
  }
  bloqueaCampo('firmano', bloqueaCampos);  
});

$("#firmano").change(function(){
  console.log("Cuentapropia");
  var bloqueaCampos ='false';  
  var val = ($(this).is(':checked')) ? "1" : "";
  if($(this).prop("checked") == true){
    bloqueaCampos = true;    
  }else{
    bloqueaCampos = false;    
  }
  $('.f1s').bootstrapValidator('enableFieldValidators', 'firmasi', !bloqueaCampos);
  $('.f1s').bootstrapValidator('validateField', 'firmasi');

  bloqueaCampo('firmasi', bloqueaCampos);  
});

$("#cedulasi").change(function(){  
  var bloqueaCampos ='false';  
  var val = ($(this).is(':checked')) ? "1" : "";
  if($(this).prop("checked") == true){
    bloqueaCampos = true;    
  }else{
    bloqueaCampos = false;    
  }
  bloqueaCampo('cedulano', bloqueaCampos);  
});

$("#cedulano").change(function(){  
  var bloqueaCampos ='false';  
  var val = ($(this).is(':checked')) ? "1" : "";
  if($(this).prop("checked") == true){
    bloqueaCampos = true;    
  }else{
    bloqueaCampos = false;    
  }

  $('.f1s').bootstrapValidator('enableFieldValidators', 'cedulasi', !bloqueaCampos);
  $('.f1s').bootstrapValidator('validateField', 'cedulasi');
  bloqueaCampo('cedulasi', bloqueaCampos);  
});

function ocultar_secciones(slide, patron){
  if(slide){
    $(patron).slideUp("slow");
  }else{
    $(patron).slideDown("slow");
  }
}



$("#refpropietarioreal").change(function(){
 
    var isRequired = $(this).val() == 2;
    
    $('.f1s').bootstrapValidator('enableFieldValidators', 'cnombre', isRequired);
    $('.f1s').bootstrapValidator('enableFieldValidators', 'cpaterno', isRequired);
    $('.f1s').bootstrapValidator('enableFieldValidators', 'cmaterno', isRequired);
        if (isRequired ){            
          bloqueaCampo('cnombre', false);
           bloqueaCampo('cpaterno', false);
            bloqueaCampo('cmaterno', false);     
           $('.f1s').bootstrapValidator('validateField', 'cnombre'); 
           $('.f1s').bootstrapValidator('validateField', 'cpaterno'); 
           $('.f1s').bootstrapValidator('validateField', 'cmaterno');                           
        }else{
         bloqueaCampo('cnombre', true);  
         bloqueaCampo('cpaterno', true);  
         bloqueaCampo('cmaterno', true);  
        }       

});


$("#refaportacionpropia").change(function(){
 
    var isRequired = $(this).val() == 2;
    $('.f1s').bootstrapValidator('enableFieldValidators', 'pnombre', isRequired);
    $('.f1s').bootstrapValidator('enableFieldValidators', 'ppaterno', isRequired);
    $('.f1s').bootstrapValidator('enableFieldValidators', 'pmaterno', isRequired);
        if (isRequired ){            
          bloqueaCampo('pnombre', false);
           bloqueaCampo('ppaterno', false);
            bloqueaCampo('pmaterno', false);     
           $('.f1s').bootstrapValidator('validateField', 'pnombre'); 
           $('.f1s').bootstrapValidator('validateField', 'ppaterno'); 
           $('.f1s').bootstrapValidator('validateField', 'pmaterno');                           
        }else{
         bloqueaCampo('pnombre', true);  
         bloqueaCampo('ppaterno', true);  
         bloqueaCampo('pmaterno', true);  
        }       

});

$("#cargopublicofamiliar").change(function(){
  
 
    var isRequired = $(this).val() == 1;
    console.log("Es requerido =>"+isRequired);
    $('.f1s').bootstrapValidator('enableFieldValidators', 'fnombre', isRequired);
    $('.f1s').bootstrapValidator('enableFieldValidators', 'fpaterno', isRequired);
    $('.f1s').bootstrapValidator('enableFieldValidators', 'fmaterno', isRequired);
    $('.f1s').bootstrapValidator('enableFieldValidators', 'refparentesco', isRequired);
        if (isRequired ){     
        console.log("Es requerido=>1");       
          bloqueaCampo('fnombre', false);
          bloqueaCampo('fpaterno', false);
          bloqueaCampo('fmaterno', false);   
          bloqueaCampo('refparentesco', false);  
          ocultar_secciones(!bloqueaCampo, '#frm_g_familiarPPE');
           $('.f1s').bootstrapValidator('validateField', 'fnombre'); 
           $('.f1s').bootstrapValidator('validateField', 'fpaterno'); 
           $('.f1s').bootstrapValidator('validateField', 'fmaterno');
           $('.f1s').bootstrapValidator('validateField', 'refparentesco');
                                      
        }else{
          bloqueaCampo('refparentesco', true);  
         bloqueaCampo('fnombre', true);  
         bloqueaCampo('fpaterno', true);  
         bloqueaCampo('fmaterno', true);  
          ocultar_secciones(bloqueaCampo, '#frm_g_familiarPPE');
        }       

});



//function bloqueaCampo(campo, bloquea){
 // if(bloquea){    
  //  $('#'+campo).val('');
  //  $('#'+campo).attr("disabled", true); 
  //  campo = '<input type="hidden"  id="'+ campo +'" name="'+ campo +'"  />';
  //  $('.ocultos').append(campo); 
 // }else{     
 //   $('#'+campo).attr("disabled", false);
 //   $(".ocultos [name="+campo+"]").remove();
 // }
// }

$("#refmediodifusion").change(function(){   
  var bloqueaCampos ='false';
  var desBloqueaCampos ='true';
  var isRequiredAsesor = $(this).val() == 4;
  var isRequiredEsp = $(this).val() == 5;
  $('.f1s').bootstrapValidator('enableFieldValidators', 'refasesores', isRequiredAsesor);
  
  $('.f1s').bootstrapValidator('enableFieldValidators', 'difusionesp', isRequiredEsp);
 
  bloqueaCampo('refasesores', !isRequiredAsesor); 
  bloqueaCampo('difusionesp', !isRequiredEsp);  
  
  ocultar_secciones(!isRequiredAsesor, '#seccion_asesores');
  ocultar_secciones(!isRequiredEsp, '#seccion_otro_medio');
  $('.f1s').bootstrapValidator('validateField', 'refasesores');
  $('.f1s').bootstrapValidator('validateField', 'difusionesp');

});

function bloqueaCampo(campo, bloquea){  
  if(bloquea){  
    if($("#"+campo).is(":checkbox")) {
          $("#"+campo).attr("checked", false);
          $('#'+campo).attr("disabled", true);
        }  else{
          $('#'+campo).val('');
          $('#'+campo).attr("disabled", true);      

        } 
    campo = '<input type="hidden"  id="'+ campo +'" name="'+ campo +'"  />';
    $('.ocultos').append(campo); 
    
  }else{  
  
    $('#'+campo).attr("disabled", false);
    $(".ocultos [name="+campo+"]").remove();
  }
}

function camposBloquedosInicio(){

  var digitostarjetaBloquedo =  $("#tarjetacredito").val() ==2;
  bloqueaCampo('digitostarjeta', digitostarjetaBloquedo);

  var telefono1Bloquedo = $("#telefono1").val().length > 1 ;
  bloqueaCampo('reftipotelefono1', !telefono1Bloquedo);

  //var telefono2Bloquedo = $("#telefono2").val().length > 1 ;
  //bloqueaCampo('reftipotelefono2', !telefono2Bloquedo);

  var celular1Bloquedo = $("#celular1").val().length > 1 ;
  bloqueaCampo('refcompania1', !celular1Bloquedo);

  //var celular2Bloquedo = $("#celular2").val().length > 1 ;
  //bloqueaCampo('refcompania2', !celular2Bloquedo);

  //var refpropietarioreal =  $("#refpropietarioreal").val() ==1;
  //bloqueaCampo('cnombre', refpropietarioreal); 
  //bloqueaCampo('cpaterno', refpropietarioreal);  
  //bloqueaCampo('cmaterno', refpropietarioreal);

 //  var refaportacionpropia =  $("#refaportacionpropia").val() ==1;
  //bloqueaCampo('pnombre', refaportacionpropia);   
  //bloqueaCampo('ppaterno', refaportacionpropia);   
  //bloqueaCampo('pmaterno', refaportacionpropia);

  //var cuentapropia =  ($("#cuentapropia").prop("checked") == true || $("#cuentatercero").prop("checked") == false );
  //bloqueaCampo('cnombre', cuentapropia);   
  //bloqueaCampo('cpaterno', cuentapropia);   
  //bloqueaCampo('cmaterno', cuentapropia); 
  //ocultar_secciones(cuentapropia, '#frm_g_propietarioReal');

 // var cuentapropiaR = $("#cuentatercero").prop("checked") == false; 
  //$('.f1s').bootstrapValidator('enableFieldValidators', 'cuentapropia', cuentapropiaR);
 // $('.f1s').bootstrapValidator('validateField', 'cuentapropia');

 // var recursopropio =  ($("#recursopropio").prop("checked") == true || $("#recursotercero").prop("checked") == false) ;
 // bloqueaCampo('pnombre', recursopropio);   
 // bloqueaCampo('ppaterno', recursopropio);   
//  bloqueaCampo('pmaterno', recursopropio);   
//  ocultar_secciones(recursopropio, '#frm_g_recursoPropio');

  // var recursopropioR = $("#recursotercero").prop("checked") == false; 
 // $('.f1s').bootstrapValidator('enableFieldValidators', 'recursopropio', recursopropioR);
 // $('.f1s').bootstrapValidator('validateField', 'recursopropio');


// se coculta la seccion de datos del empleo esta seccion soloa la llena administracion
  
  ocultar_secciones(true, '#seccion_datos_empleo');



  var cargopublicofamiliar =  $("#cargopublicofamiliar").val() !=1;
  bloqueaCampo('fnombre', cargopublicofamiliar); 
  bloqueaCampo('fpaterno', cargopublicofamiliar);  
  bloqueaCampo('fmaterno', cargopublicofamiliar);
  bloqueaCampo('refparentesco', cargopublicofamiliar);
  ocultar_secciones(cargopublicofamiliar, '#frm_g_familiarPPE');
  
  ocultar_secciones(true, '#seccion_seguimiento');

 var cedulasiR = $("#cedulano").prop("checked") == false; 
  $('.f1s').bootstrapValidator('enableFieldValidators', 'cedulasi', cedulasiR);
  $('.f1s').bootstrapValidator('validateField', 'cedulasi');
    
   var firmasiR = $("#firmano").prop("checked") == false; 
  $('.f1s').bootstrapValidator('enableFieldValidators', 'firmasi', firmasiR);
  $('.f1s').bootstrapValidator('validateField', 'firmasi');  
  
  // medios de difusion
  var isRequiredAsesor = $("#refmediodifusion").val() == 4;
  var isRequiredEsp = $("#refmediodifusion").val() == 5;
  $('.f1s').bootstrapValidator('enableFieldValidators', 'refasesores', isRequiredAsesor);  
  $('.f1s').bootstrapValidator('enableFieldValidators', 'difusionesp', isRequiredEsp); 
  bloqueaCampo('refasesores', !isRequiredAsesor); 
  bloqueaCampo('difusionesp', !isRequiredEsp);    
  ocultar_secciones(!isRequiredAsesor, '#seccion_asesores');
  ocultar_secciones(!isRequiredEsp, '#seccion_otro_medio');
  $('.f1s').bootstrapValidator('validateField', 'refasesores');
  $('.f1s').bootstrapValidator('validateField', 'difusionesp');  
 
}

function guardarSolicitudContratoGlobal(){
  $(".strtoupper").val (function () {
    return this.value.toUpperCase();
  });
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

            $('#accion').val('editarSolContGlobal');
            $('#btn_guardar').html('Editar');

           var IdSolicitud =  result.IdSolicitud ;

            var url = "../?id="+IdSolicitud+"";
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
          $(".alert").html('<strong>Error!</strong> Ocurrio un problema al guardar los datos, F5 para actualizar la página');
          $("#load").html('');
        }
      });


}

function filter_options(data, fields){ 
  $.ajax({
    "method":"POST",
    "url":"../../../assets/ajax/select_filter.ajax.php",
    "cache":false,
    "dataType": "json",
    "contentType": "application/json; charset=utf-8",
    "data":JSON.stringify(data),
    "success":function(result){
      $.each(fields, function(index, field){
        console.log("Field =>");
        console.log(field);
        $("#"+field).html("");
        $.each(result.options, function(index, option){
          $("#"+field).append(
              $("<option />")
              .text(option.inside.join(""))
              .val(option.value)
              .data("description", option["data-description"])
          );
        });
        $("#"+field).change();
        $("#"+field).data("spc", result.spc);
      });
    }
  });
}








/**
 * Filtra los municipios de un estado seleccionado
 * @param name_cmp_edo {string} Nombre del campo del estado
 * @param name_cmp_mun {string} Nombre del campo del municipio
 */
function filter_mun(name_cmp_edo, name_cmp_mun){ 
  var data = {      
      "filtros":[
        {"field":"refestado", "value":$("#"+name_cmp_edo).val()}
        ],
        "cat_nombre":"inegi2020_municipio",
        "id_cat":"municipio_id"
  };

  filter_options(data, [name_cmp_mun]);
}

/**
 * Filtra las localidades de un municipio seleccionado
 * @param name_cmp_edo {string} 
 * @param name_cmp_mun {string} 
 * @param name_cmp_loc {string} 
 */
function filter_loc(name_cmp_edo, name_cmp_mun, name_cmp_loc){
  var data = {      
      "filtros":[
        {"field":"refestado", "value":$("#"+name_cmp_edo).val()},
        {"field":"refmunicipio", "value":$("#"+name_cmp_mun).val()}
        ],
        "cat_nombre":"inegi2020_localidad",
        "id_cat":"localidad_id"
  };

  filter_options(data, [name_cmp_loc]);
}

function filter_emp(name_cmp_empresa, name_cmp_credito){
  console.log("entra e empresa.change");
  var data = {      
      "filtros":[
        {"field":"idempresaafiliada", "value":$("#"+name_cmp_empresa).val()}
        ],
        "cat_nombre":"vista_tipo_credito_empresa_afiliada",
        "id_cat":"idtipocontratoglobal",
        "descripcion":"descripcion_credito",
  };

  filter_options(data, [name_cmp_credito]);
}

$(":checkbox").change(function(){
    var val = ($(this).is(':checked'))?"1":0;
    $(this).val(val);    
  });

function editarSolicitudContratoGlobal(){

      $(".strtoupper").val (function () {
        return this.value.toUpperCase();
        });

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
                text: "Tus datos han sido actualizados corectamente",
                type: "success",
                timer: 3000,
                showConfirmButton: false
            });

           

            var url = "../";
            setTimeout(function(){
             location.reload();
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