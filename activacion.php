<?php

require 'includes/funcionesUsuarios.php';
require 'includes/funcionesReferencias.php';
require 'includes/funciones.php';

session_start();

$serviciosUsuario = new ServiciosUsuarios();
$serviciosReferencias = new ServiciosReferencias();
$serviciosFunciones 	= new Servicios();


$ui = $_GET['token'];

$resActivacion = $serviciosUsuario->traerActivacionusuariosPorTokenFechas($ui);

$cadResultado = '';

if (mysql_num_rows($resActivacion) > 0) {


	$idusuario = mysql_result($resActivacion,0,'refusuarios');

	$resUsuario = $serviciosUsuario->traerUsuarioId($idusuario);

	$resCliente = $serviciosReferencias->traerClientesPorIdUsuario($idusuario);

	// verifico que el usuario no este activo ya
	if (mysql_result($resUsuario,0,'activo') == 'Si') {
		$cadResultado = 'Usted ya fue dado de alta y esta activo.';
	} else {
		$nombrecompleto = mysql_result($resUsuario,0,'nombrecompleto');
		$cadResultado = '';
	}

	$cadEC 			 =	$serviciosReferencias->ComboBoxSelect('EstadoCivil',0);
	$cadRH 			 =	$serviciosReferencias->ComboBoxSelect('RolHogar',0);
	$cadTC 			 =	$serviciosReferencias->ComboBoxSelect('TipoClientes',0);
	$cadEN 			 =	$serviciosReferencias->ComboBoxSelect('EntidadNacimiento',1);

	if (mysql_num_rows($resCliente) > 0) {
		$nombre = mysql_result($resCliente,0,'nombre');
		$apellidopaterno = mysql_result($resCliente,0,'apellidopaterno');
		$apellidomaterno = mysql_result($resCliente,0,'apellidomaterno');
		$anioNacimiento = mysql_result($resCliente,0,'anioNacimiento');
		$mesNaciemiento = mysql_result($resCliente,0,'mesNacimiento');
		$diaNaciemiento = mysql_result($resCliente,0,'diaNacimiento');
		$sexo				 = mysql_result($resCliente,0,'sexo');
		$idcliente		 = mysql_result($resCliente,0,'idcliente');
	}

	//pongo al usuario $activo
	//$resUsuario = $serviciosUsuario->activarUsuario($idusuario);

	// concreto la activacion
	//$resConcretar = $serviciosUsuario->eliminarActivacionusuarios(mysql_result($resActivacion,0,0));


} else {

	$resToken = $serviciosUsuario->traerActivacionusuariosPorToken($ui);

	if (mysql_num_rows($resToken) > 0) {

		$cadResultado = 'La vigencia para darse de alta a caducado, haga click <a href="prolongar.php?token='.$ui.'">AQUI</a> para prolongar la activación';
	} else {
		$cadResultado = 'Esta clave de Activación es inexistente';
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
</head>

<body class="login-page">
    <div class="login-box">
        <div class="logo" style="background-color:#008AD1; padding:10px 10px; color: white;">
			   <h4 style="color:white; text-align:center;">Activación</h4>
				<a href="javascript:void(0);" style="color:white;"><b>Asesores CREA</b></a>

            <small style="color:white;">Administración de Servicios de Clientes</small>
        </div>
        <div class="card">
            <div class="body demo-masked-input">
               <form id="sign_in" method="POST">
					 	<?php if ($cadResultado == '') { ?>
						<div align="center">
							<h3><?php echo $nombrecompleto; ?></h3>
							<p>Por favor complete sus datos personales para activar su usuario</p>
						</div>

						<div class="input-group">
							<span class="input-group-addon">
								<i class="material-icons">account_box</i>
							</span>
							<div class="form-line">
								<input type="text" class="form-control" name="rfc" id="rfc" maxlength="13" placeholder="RFC" required/>

							</div>
							<span class="input-group-addon hidden">
								<button class="btn btn-block bg-blue-grey waves-effect buscarRFC" data-type="" type="button" >RFC</button>
							</span>
						</div>


						<div class="input-group">
							<span class="input-group-addon">
								<i class="material-icons">account_circle</i>
							</span>
							<div class="form-line">
								<input type="text" class="form-control" name="curp" id="curp" maxlength="18" placeholder="CURP" required/>

							</div>
							<span class="input-group-addon hidden">
								<button class="btn btn-block bg-blue-grey waves-effect buscarCURP" data-type="" type="button" >CURP</button>
							</span>
						</div>

						<div class="input-group">
							<span class="input-group-addon">
								<i class="material-icons">language</i>
							</span>
							<div class="form-line">
								<input type="text" class="form-control" name="nacionalidad" id="nacionalidad" maxlength="20" placeholder="Nacionalidad" value="Mexico" required/>
							</div>
						</div>


						<div class="input-group">
							<span class="input-group-addon">
								<i class="material-icons">home</i>
							</span>
							<div class="form-line">
								<select type="text" class="form-control" name="refrolhogar" id="refrolhogar" placeholder="Rol Hogar">
									<?php echo $cadRH; ?>
								</select>
							</div>
						</div>


						<div class="input-group">
							<span class="input-group-addon">
								<i class="material-icons">chrome_reader_mode</i>
							</span>
							<div class="form-line">
								<select type="text" class="form-control" name="refestadocivil" id="refestadocivil" placeholder="Estado Civil">
									<?php echo $cadEC; ?>
								</select>
							</div>
						</div>

						<div class="input-group">
							<span class="input-group-addon">
								<i class="material-icons">location_on</i>
							</span>
							<div class="form-line">
								<select type="text" class="form-control" name="refentidadnacimiento" id="refentidadnacimiento" placeholder="Entidad Nacimiento" required>
									<?php echo $cadEN; ?>
								</select>
							</div>
						</div>

						<div class="input-group">
							<span class="input-group-addon">
								<i class="material-icons">lock</i>
							</span>
							<div class="form-line">
								<input type="password" class="form-control" name="password" id="password" maxlength="10" placeholder="Ingrese un Password" required/>

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
                  <h4><?php echo $cadResultado; ?></h4>

                  <div class="row m-t-15 m-b--20">
                     <div class="col-xs-6">
                        <a href="index.html">Iniciar Sessión!!</a>
                     </div>
                     <div class="col-xs-6 align-right">

                     </div>
                  </div>
						<?php } ?>
						<input type="hidden" name="idusuario" id="idusuario" value="<?php echo $idusuario; ?>" />
						<input type="hidden" name="idcliente" id="idcliente" value="<?php echo $idcliente; ?>" />
						<input type="hidden" name="accion" id="accion" value="activarUsuario" />
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

			  $demoMaskedInput.find('#rfc').inputmask('AAAA9999999A9', { placeholder: '_____________'});


				$('body').keyup(function(e) {
                if(e.keyCode == 13) {
                    $("#login").click();
                }
            });

				$('.datepicker').bootstrapMaterialDatePicker({
					format: 'YYYY-MM-DD',
					clearButton: true,
					weekStart: 1,
					time: false
				});



				function validarCURP(curp) {
						var re = /^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/,
						validado = curp.match(re);

				    if (!validado)  //Coincide con el formato general?
				    	return false;

				    //Validar que coincida el dígito verificador
				    function digitoVerificador(curp17) {
				        //Fuente https://consultas.curp.gob.mx/CurpSP/
				        var diccionario  = "0123456789ABCDEFGHIJKLMNÑOPQRSTUVWXYZ",
				            lngSuma      = 0.0,
				            lngDigito    = 0.0;
				        for(var i=0; i<17; i++)
				            lngSuma = lngSuma + diccionario.indexOf(curp17.charAt(i)) * (18 - i);
				        lngDigito = 10 - lngSuma % 10;
				        if (lngDigito == 10) return 0;
				        return lngDigito;
				    }

				    if (validado[2] != digitoVerificador(validado[1]))
				    	return false;

				    return true; //Validado
				}


				function validarPASS(pass) {
						var re = /(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{10,})/,
						validado = pass.match(re);

				    if (!validado)  //Coincide con el formato general?
				    	return false;

				    return true; //Validado
				}

				$('#curp').focusout(function() {
					if (validarCURP($('#curp').val()) == false) {
						swal({
							title: "Respuesta",
							text: "CURP no valido",
							type: "error",
							timer: 2000,
							showConfirmButton: false
						});

						$(this).focus();
					}
				});

				$('#password').focusout(function() {
					if (validarPASS($('#password').val()) == false) {
						swal({
							title: "Respuesta",
							text: "PASSWORD no valido. Recuerde que el PASSWORD debe contener (10 caracteres, al menos una mayuscula, al menos una minuscula y un numero)",
							type: "error",
							timer: 10000,
							showConfirmButton: true
						});

						$(this).focus();
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
