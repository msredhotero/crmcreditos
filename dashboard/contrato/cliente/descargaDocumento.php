<?php
session_start();
include('../../../includes/class/DownloadDocument.class.php');
$id = base64_decode(urldecode($_GET['1']));
$tipo =base64_decode(urldecode($_GET['2']));
$id = filter_var($id,FILTER_SANITIZE_NUMBER_INT); 
$tipo = filter_var($tipo,FILTER_SANITIZE_NUMBER_INT); 
$documento = new DowloadDocument($id, $tipo,$usuario);
?>