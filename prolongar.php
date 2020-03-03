<?php

require 'includes/funcionesUsuarios.php';


session_start();

$serviciosUsuario = new ServiciosUsuarios();


$ui = $_GET['token'];

$resActivacion = $serviciosUsuario->traerActivacionusuariosPorToken($ui);

$cadResultado = '';

if (mysql_num_rows($resActivacion) > 0) {
	$idusuario = mysql_result($resActivacion,0,'refusuarios');

	// prolongo la activacion
	$resConcretar = $serviciosUsuario->modificarActivacionusuariosRenovada($idusuario,$ui,'','');

	$cadResultado = 'Vuelva intentar activarse haciendo click <a href="activacion.php?token='.$ui.'">AQUI</a>!!';
} else {

	$cadResultado = 'Esta clave de Activación es inexistente';
}



?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Acceder | RIDERZ</title>
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

    <!-- Custom Css -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="login-page">
    <div class="login-box">
        <div class="logo" style="background-color:#0F0; padding:10px 10px;">
            <a href="javascript:void(0);" style="color:#000;">Prolongar <b>RIDERZ</b></a>
            <small style="color:#000;">Administración Sistema de Clientes</small>
        </div>
        <div class="card">
            <div class="body">
                <form id="sign_in" method="POST">
                     <h4><?php echo $cadResultado; ?></h4>

                     <div class="row m-t-15 m-b--20">
                        <div class="col-xs-6">
                           <a href="index.html">Iniciar Sessión!!</a>
                        </div>
                        <div class="col-xs-6 align-right">

                        </div>
                     </div>
               </form>
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


    <script type="text/javascript">

        $(document).ready(function(){



        });/* fin del document ready */

    </script>
</body>

</html>
