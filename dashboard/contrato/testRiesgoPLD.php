<?php
include('../../class_include.php');

 $idContratoGlobal = 27;
$riesgoPLD = new RiesgoPLD($idContratoGlobal);
$arrdatos = array();
$arrdatos = $riesgoPLD->cargarVariables();


$riesgoPLD->registraVariables();
$riesgoPLD->generaComprobatePDF();

?>