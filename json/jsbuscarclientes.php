<?php

session_start();

if (!isset($_SESSION['usua_sahilices']))
{
	header('Location: ../../error.php');
} else {


include ('../includes/funciones.php');
include ('../includes/funcionesUsuarios.php');
include ('../includes/funcionesHTML.php');
include ('../includes/funcionesReferencias.php');

$serviciosFunciones = new Servicios();
$serviciosUsuario 	= new ServiciosUsuarios();
$serviciosHTML 		= new ServiciosHTML();
$serviciosReferencias 	= new ServiciosReferencias();

$fecha = date('Y-m-d');

$busqueda = trim($_POST['busqueda']);

//$busqueda = 'a';


//$arBusqueda = explode(" ", $busqueda);

//$cantidad = count($arBusqueda);

$ar = array();

if ($busqueda != '') {
/*
switch ($cantidad) {
	case 1:
		$resTraerJugadores = $serviciosReferencias->nuevoBuscador($arBusqueda[0]);
		break;
	case 2:
		$resTraerJugadores = $serviciosReferencias->nuevoBuscador($arBusqueda[0],$arBusqueda[1]);
		break;
	case 3:
		$resTraerJugadores = $serviciosReferencias->nuevoBuscador($arBusqueda[0],$arBusqueda[1],$arBusqueda[2]);
		break;

	default:
		$resTraerJugadores = $serviciosReferencias->nuevoBuscador($arBusqueda[0],$arBusqueda[1],$arBusqueda[2],$arBusqueda[3]);
		break;
}
*/

$resTraerClientes = $serviciosReferencias->nuevoBuscador($busqueda);


$cad = '';
	while ($row = mysql_fetch_array($resTraerClientes)) {

		array_push($ar,array('id'=>$row['idcliente'], 'apellido'=> $row['apellido'], 'nombre'=> $row['nombre'], 'cuit'=> $row['cuit']));
	}

}
//echo "[".substr($cad,0,-1)."]";
//echo "[".json_encode($ar)."]";
echo json_encode($ar);
}
?>
