<?php
include('../../../class_include.php');

 $idContratoGlobal = $_GET['id'];
$riesgoPLD = new RiesgoPLD($idContratoGlobal);
$arrdatos = array();
$arrdatos = $riesgoPLD->cargarVariables();


$riesgoPLD->registraVariables();
$riesgoPLD->generaComprobatePDF();

?>