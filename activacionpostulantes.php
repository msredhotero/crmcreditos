<?php

require 'includes/funcionesUsuarios.php';
require 'includes/funcionesReferencias.php';
require 'includes/funciones.php';

session_start();

$serviciosUsuario = new ServiciosUsuarios();
$serviciosReferencias = new ServiciosReferencias();
$serviciosFunciones 	= new Servicios();

if (!isset($_GET['token'])) {
	$ui = 'asda23asd23asd';
} else {
	$ui = $_GET['token'];
}


$resActivacion = $serviciosUsuario->traerActivacionusuariosPorTokenFechas($ui);

if (mysql_num_rows($resActivacion) > 0) {


	$idusuario = mysql_result($resActivacion,0,'refusuarios');

	$resUsuario = $serviciosUsuario->traerUsuarioId($idusuario);

	$resPostulantes = $serviciosReferencias->traerPostulantesPorIdUsuario($idusuario);

	// verifico que el usuario no este activo ya
	if (mysql_result($resUsuario,0,'activo') == 'Si') {
		$arResultado['leyenda'] = 'Usted ya fue dado de alta y esta activo.';
	} else {
		$arResultado['usuario'] = mysql_result($resUsuario,0,'nombrecompleto');
		$arResultado['leyenda'] = '';
	}

	if (mysql_num_rows($resPostulantes) > 0) {
		$arResultado['postulante']['nombre'] = strtoupper( mysql_result($resPostulantes,0,'nombre'));
		$arResultado['postulante']['apellidopaterno'] = strtoupper( mysql_result($resPostulantes,0,'apellidopaterno'));
		$arResultado['postulante']['apellidomaterno'] = strtoupper( mysql_result($resPostulantes,0,'apellidomaterno'));
		$arResultado['postulante']['email'] = mysql_result($resPostulantes,0,'email');
		$arResultado['postulante']['id'] = mysql_result($resPostulantes,0,'idpostulante');
	}

	//pongo al usuario $activo
	//$resUsuario = $serviciosUsuario->activarUsuario($idusuario);

	// concreto la activacion
	//$resConcretar = $serviciosUsuario->eliminarActivacionusuarios(mysql_result($resActivacion,0,0));


} else {

	$resToken = $serviciosUsuario->traerActivacionusuariosPorToken($ui);

	if (mysql_num_rows($resToken) > 0) {

		$arResultado['leyenda'] = 'La vigencia para darse de alta a caducado, haga click <a href="prolongar.php?token='.$ui.'">AQUI</a> para prolongar la activación';
	} else {
		$arResultado['leyenda'] = 'Esta clave de Activación es inexistente';
	}

}



?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Acceder | Asesores CREA</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Sweetalert Css -->
    <link href="plugins/sweetalert/sweetalert.css" rel="stylesheet" />

	 <!-- Bootstrap Material Datetime Picker Css -->
    <link href="plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="css/style.css" rel="stylesheet">

	 <script type='text/javascript'>
 		document.oncontextmenu = function(){return false}

       window.addEventListener("keypress", function(event){
          if (event.keyCode == 13){
             event.preventDefault();
          }
       }, false);

 	</script>
</head>

<body class="login-page">
    <div class="login-box">
        <div class="logo" style="border: 1px solid #277BAE; background-color:#008AD1; padding:10px 10px; color: white;">
			   <h4 style="color:white; text-align:center;">Activación</h4>
				<a href="javascript:void(0);" style="color:white;"><b>Asesores CREA</b></a>

            <small style="color:white;">Administración de Asesores</small>
        </div>
        <div class="card">
            <div class="body demo-masked-input">
               <form id="sign_in" method="POST">
					 	<?php if ($arResultado['leyenda'] == '') { ?>
						<div align="center">
							<h3><?php echo $arResultado['postulante']['nombre'].' '.$arResultado['postulante']['apellidopaterno'].' '.$arResultado['postulante']['apellidomaterno']; ?></h3>
							<div class="alert bg-green">Por favor cargue una password para completar el alta de usuario.</div>
							<div class="alert bg-red">Recuerde que el PASSWORD debe contener (10 caracteres, al menos una mayuscula, al menos una minuscula y un numero).</div>
						</div>

						<div class="input-group">
							<span class="input-group-addon">
								<i class="material-icons">account_box</i>
							</span>
							<div class="form-line">
								<input readonly type="text" class="form-control" name="nombre" id="nombre" placeholder="USUARIO" value="<?php echo $arResultado['usuario']; ?>" required/>

							</div>
						</div>


						<div class="input-group">
							<span class="input-group-addon">
								<i class="material-icons">account_circle</i>
							</span>
							<div class="form-line">
								<input readonly type="text" class="form-control" name="email" id="email" value="<?php echo $arResultado['postulante']['email']; ?>" placeholder="EMAIL" required/>

							</div>
						</div>


						<div class="input-group">
							<span class="input-group-addon">
								<i class="material-icons">lock</i>
							</span>
							<div class="form-line">
								<input type="password" class="form-control" name="password" id="password" maxlength="8" placeholder="Ingrese un Password" required/>

							</div>

						</div>

						<div class="input-group">
							<span class="input-group-addon">
								<i class="material-icons">lock</i>
							</span>
							<div class="form-line">
								<input type="passwordaux" class="form-control" name="passwordaux" id="passwordaux" maxlength="8" placeholder="Confirme su Password" required/>

							</div>

						</div>

						<div class="row js-sweetalert">
							<div class="col-xs-7 p-t-5">
								<a href="index.html">Iniciar sesión</a>
							</div>
							<div class="col-xs-5">
								<button class="btn btn-block bg-riderz waves-effect" data-type="" type="submit" id="login">ACTIVARSE</button>
							</div>
						</div>


						<?php } else { ?>
                  <h4><?php echo $arResultado['leyenda']; ?></h4>

                  <div class="row m-t-15 m-b--20">
                     <div class="col-xs-6">
                        <a href="index.html">Iniciar Sessión!!</a>
                     </div>
                     <div class="col-xs-6 align-right">

                     </div>
                  </div>
						<?php } ?>
						<input type="hidden" name="idusuario" id="idusuario" value="<?php echo $idusuario; ?>" />
						<input type="hidden" name="idpostulante" id="idpostulante" value="<?php echo $arResultado['postulante']['id']; ?>" />
						<input type="hidden" name="accion" id="accion" value="activarUsuarioPostulante" />
						<input type="hidden" name="activacion" id="activacion" value="<?php echo mysql_result($resActivacion,0,0); ?>" />
               </form>
            </div>
        </div>
    </div>

	 <div class="modal fade" id="lgmNuevo" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-riderz">
                    <h4 class="modal-title" id="largeModalLabel">ASESORES CREA ACTIVACION</h4>
                </div>
                <div class="modal-body">
                   <div class="">
                      <div class="row">
                        <h4>Su usuario fue activado con Exito.</h4>
                      </div>

                   </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-blue waves-effect"><a href="index.html" style="color:white; text-decoration:none;">Iniciar sesión</a></button>

                </div>
            </div>
        </div>
    </div>

    <!-- Jquery Core Js -->
    <script src="plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="plugins/node-waves/waves.js"></script>

    <!-- Validation Plugin Js -->
    <script src="plugins/jquery-validation/jquery.validate.js"></script>

    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/examples/sign-in.js"></script>

    <!-- SweetAlert Plugin Js -->
    <script src="plugins/sweetalert/sweetalert.min.js"></script>

    <script src="js/pages/ui/dialogs.js"></script>

	 <script src="plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>

	 <script src="plugins/momentjs/moment.js"></script>

	 <script src="plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>


    <script type="text/javascript">

        $(document).ready(function(){
			  var $demoMaskedInput = $('.demo-masked-input');


				$('.datepicker').bootstrapMaterialDatePicker({
					format: 'YYYY-MM-DD',
					clearButton: true,
					weekStart: 1,
					time: false
				});


				function validarPASS(pass) {
						var re = /(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,})/,
						validado = pass.match(re);

				    if (!validado)  //Coincide con el formato general?
				    	return false;

				    return true; //Validado
				}

				function validaIgualdad(pass, passaux) {
					if (pass <> passaux) {
						return false;
					}

					return true;
				}


				$('#passwordaux').focusout(function() {
					if (validaIgualdad($('#password').val(),$('#passwordaux').val()) == false) {
						swal({
							title: "Error",
							text: "Los PASSWORDs no coinciden",
							type: "error",
							timer: 10000,
							showConfirmButton: true
						});

						$('#login').hide();
					} else {
						$('#login').show();
					}
				});

				$('#password').focusout(function() {
					if (validarPASS($('#password').val()) == false) {
						swal({
							title: "Respuesta",
							text: "PASSWORD no valido. Recuerde que el PASSWORD debe contener (8 caracteres, al menos una mayuscula, al menos una minuscula y un numero)",
							type: "error",
							timer: 10000,
							showConfirmButton: true
						});

						$(this).focus();
					} else {
						if (validaIgualdad($('#password').val(),$('#passwordaux').val()) == false) {
							swal({
								title: "Error",
								text: "Los PASSWORDs no coinciden",
								type: "error",
								timer: 10000,
								showConfirmButton: true
							});

							$('#login').hide();
						} else {
							$('#login').show();
						}
					}
				});


            $("#sign_in").submit(function(e){

                e.preventDefault();
                if ($('#sign_in')[0].checkValidity()) {
						 var formData = new FormData($("#sign_in")[0]);

                   $.ajax({
								data: formData,
								//necesario para subir archivos via ajax
								cache: false,
								contentType: false,
								processData: false,
                       url:   'ajax/ajax.php',
                       type:  'post',
                       beforeSend: function () {
                               $("#load").html('<img src="imagenes/load13.gif" width="50" height="50" />');
										 $('#login').hide();
                       },
                       success:  function (response) {

                            if (isNaN(response)) {

                                swal({
                                    title: "Respuesta",
                                    text: "Se genero un error",
                                    type: "error",
                                    timer: 2000,
                                    showConfirmButton: false
                                });
										  $('#login').show();

                            } else {
                                $('#lgmNuevo').modal();

										  $('#login').hide();

                                //url = "dashboard/";
                                //$(location).attr('href',url);
                            }

                       }
                   });
                }


            });


        });/* fin del document ready */

    </script>
</body>

</html>
