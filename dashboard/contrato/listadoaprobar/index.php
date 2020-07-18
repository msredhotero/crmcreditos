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


$baseHTML->setContentHeader ('Listado ', 'Home/Contrato/Listado para aprobar');
$idContratoGlobal = '';

$cadRef2 ='';

$dataContratoGlobal = new ServiciosSolicitudes($idContratoGlobal);
$page = new Formulario();
$form = new FormularioSolicitud();










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
        <link rel="stylesheet" href="../../../bootstrap/bootzard-wizard/assets/css/style.css">-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
        <link href="../../../plugins/animate-css/animate.css" rel="stylesheet" />
        <link href="../../../plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
        <link href="../../../plugins/waitme/waitMe.css" rel="stylesheet" />
<link href="../../../plugins/node-waves/waves.css" rel="stylesheet" />
<link href="../../../plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

  <link rel="stylesheet" href="../../../DataTables/DataTables-1.10.18/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="../../../DataTables/DataTables-1.10.18/css/dataTables.bootstrap.css">
  <link rel="stylesheet" href="../../../DataTables/DataTables-1.10.18/css/dataTables.jqueryui.min.css">
  <link rel="stylesheet" href="../../../DataTables/DataTables-1.10.18/css/jquery.dataTables.css">
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

             

        <div class="row">


        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="card ">
            <div class="header bg-info p-1 b-4">
              <h2  class="lead" style="color:white;">
                Solicitudes pendientes de autorizar
              </h2>
             
            </div>
            <div class="body table-responsive">
              <form class="form" id="formCountry">

                <div class="row">
                  <div class="col-lg-12 col-md-12">
                    <div class="button-demo">
                     

                    </div>
                  </div>
                </div>


                <div class="row" style="padding: 5px 20px;">

                  <table id="example" class="display table " style="width:100%">
                    <thead>
                      <tr>
                        <th>Empresa</th>
                        <th>Tipo</th>
                        
                        <th>Nombre</th>                        
                        <th>CURP</th>
                        <th>Monto aprobado</th>
                        <th>Número pagos</th>
                        <th>periodo</th>
                        <th>Status</th>
                       
                        <th>Fecha cambio status</th>
                        
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>Empresa</th>
                        <th>Tipo</th>
                        
                        <th>Nombre</th>
                        <th>CURP</th>
                        <th>Monto aprobado</th>
                        <th>Número pagos</th>
                        <th>periodo</th>
                        <th>Status</th>
                        
                        <th>Fecha cambio status</th>
                       
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

<!-- MODIFICAR -->
    <form class="formulario" role="form" id="statusEdit">
       <div class="modal fade" id="lgmModificar" tabindex="-1" role="dialog">
           <div class="modal-dialog modal-lg" role="document">
               <div class="modal-content">
                   <div class="modal-header">
                       <h4 class="modal-title" id="largeModalLabel"><?php echo strtoupper($singular); ?></h4>
                   </div>
                   <div class="modal-body">
                <div class="row frmAjaxModificar">

                </div>
                   </div>
                   <div class="modal-footer">
                       <button type="button" class="btn  modificar bg-info">MODIFICAR</button>
                       <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CERRAR</button>
                   </div>
               </div>
           </div>
       </div>
      
    </form>


             
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

 <script src="../../../DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>

 <script type="text/javascript" src="../../../plugins/bootstrapvalidator/dist/js/bootstrapValidator.js"></script>
        


<script type="text/javascript">
$(document).ready(function () {

 var table = $('#example').DataTable({
      "bProcessing": true,
      "bServerSide": true,
      "sAjaxSource": "../../../json/jstablasajax.php?tabla=pendienteEmpleador",
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
      },

      "rowCallback": function( row, data, index ) {
        if (data[8] =='Si' ) {
          $('td', row).css('background-color', '#FFC133');
          $('td', row).css('color', '#4D4D4C');
         
        }
      }
    });


 $("#example").on("click",'.btnModificar', function(){
      idTable =  $(this).attr("id");
      frmAjaxModificar(idTable);
      $('#lgmModificar').modal();
    });//fin del boton modificar

    $("#example .perfilS").each( function ( i ) {
      var select = $('<select><option value="">-- Seleccione Status --</option><?php echo $cadRef2; ?></select>')
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
$('.btn').on('click',  function(){

  

});

 $('#1').each( function ( i ) {

  console.log("*"+ $( this ).text() );
});

function frmAjaxModificar(id) {
      $.ajax({
        url: '../../../ajax/ajax.php',
        type: 'POST',
        // Form data
        //datos del formulario
        data: {accion: 'frmAjaxAprobaRechazar', id: id},
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

   $('.modificar').click(function(){
      //información del formulario
      var formData = new FormData($(".formulario")[0]);
      var message = "";

      var anio = $('#antiguedadanio').val();
      var mes = $('#antiguedadmes').val();
      if(anio =='' || mes ==''){
        swal({
                title: "Información requerida",
                text: 'Por favor indique el año y el mes de contratación',
                type: "error",
                timer: 3500,
                showConfirmButton: false
            });
      }else{
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
         dataType: 'JSON',
        beforeSend: function(){

        },
       
        //una vez finalizado correctamente
        success: function(result){

         if (result.error == "") {
            swal({
                title: "Respuesta",
                text: "Datos actualizadados correctamente",
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
                text: result,
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

      }
      //hacemos la petición ajax
      
    });  



});
</script>
</body>
</html>