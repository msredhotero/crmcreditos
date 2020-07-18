<?php
session_start();
include('../../../includes/class/EnvioMailContratoCliente.class.php');
$id = base64_decode(urldecode($_GET['1']));
$tipo =base64_decode(urldecode($_GET['2']));
$id = filter_var($id,FILTER_SANITIZE_NUMBER_INT); 
$tipo = filter_var($tipo,FILTER_SANITIZE_NUMBER_INT); 
$mail = new EnvioMailContratoCliente($id, $tipo)
?>
<!DOCTYPE html>
<html>
<head>
	<title>Financiera CREA</title>
</head>
<!--<body onLoad="window.opener.location.reload();self.close ()"> -->
	<body>
<script type="text/javascript">
	
	alert("Documento enviado");
</script>
</body>
</html>
?>