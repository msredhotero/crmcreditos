<?php

session_start();

include ('../includes/funciones.php');
include ('../includes/funcionesUsuarios.php');
include ('../includes/funcionesHTML.php');
include ('../includes/funcionesReferencias.php');

$serviciosFunciones = new Servicios();
$serviciosUsuario 	= new ServiciosUsuarios();
$serviciosHTML 		= new ServiciosHTML();
$serviciosReferencias 	= new ServiciosReferencias();

$fecha = date('Y-m-d');

$secuencia = trim($_GET['secuencia']);

$ar = array();

if ($secuencia != '') {

$res = $serviciosReferencias->traerPreguntasPorSecuencia($secuencia);


$cad = '';
	while ($row = mysql_fetch_array($res)) {

		array_push($ar,array('idpregunta'=>$row['idpregunta'],
                           'secuencia'=> $row['secuencia'],
                           'pregunta'=> ($row['pregunta']),
                           'respuesta1'=> ($row['respuesta1']),
                           'respuesta2'=> ($row['respuesta2']),
                           'respuesta3'=> ($row['respuesta3']),
                           'respuesta4'=> ($row['respuesta4']),
                           'respuesta5'=> ($row['respuesta5']),
                           'respuesta6'=> ($row['respuesta6']),
                           'respuesta7'=> ($row['respuesta7']),
                           'valor'=> ($row['valor']),
                           'depende'=> ($row['depende']),
                           'tiempo'=> ($row['tiempo'])
                        ));
	}

}
//echo "[".substr($cad,0,-1)."]";
//echo "[".json_encode($ar)."]";
echo json_encode($ar);

?>
