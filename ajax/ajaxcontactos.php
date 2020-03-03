<?php


include ('../includes/funciones.php');

include ('../includes/funcionesContactos.php');

$serviciosFunciones 		= new Servicios();

$serviciosContactos		= new ServiciosContactos();

$accion = $_POST['accion'];

$resV['error'] = '';
$resV['mensaje'] = '';

switch ($accion) {
   case 'insertarContactos':
      insertarContactos($serviciosContactos);
   break;

}

function insertarContactos($serviciosContactos) {
   $pregunta      =     $_POST['pregunta'];
   $nombre        =     $_POST['nombre'];
   $apellido      =     $_POST['apellido'];
   $agencia       =     $_POST['agencia'];
   //$refproductos  =     $_POST['refproductos'];
   $observaciones =     $_POST['observaciones'];
   $telefono = '';
   $tipotelefono = 0;
   $email = '';

   $res = $serviciosContactos->insertarContactos($nombre,$apellido,$email,$tipotelefono,$telefono,$agencia,$pregunta,$observaciones);

   if ((integer)$res > 0) {
      $resV['error'] = false;

      if ($_POST['productos'] != '') {
         foreach ($_POST['productos'] as $productos) {
            $resSeguro = $serviciosContactos->insertarContactoproductos($res,$productos);
         }
      }


   } else {
      $resV['error'] = true;

   }

   header('Content-type: application/json');
   echo json_encode($resV);
}


?>
