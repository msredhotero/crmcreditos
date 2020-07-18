<?php
#include '../../../class_include.php';
#include '../../../includes/class/ServiciosPDFConctratoGlobal.class.php';

#include_once '../../../reportes/fpdf.php';
include_once '../../../reportes/PDFMerger.php';
include_once '../../../includes/class/ServiciosPDFConctratoGlobal.class.php';
include_once '../../../includes/funcionesReferencias.php';
include_once '../../../includes/class/Query.class.php';

$idContratoGlobal =  14;
$nombreCliente = "Nombre del cliente";

$pdgGral =  new ServiciosPDFConctratoGlobal($idContratoGlobal, $nombreCliente);

$pdgGral->generarPDFGlobal();


?>