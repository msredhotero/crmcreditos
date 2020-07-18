<?php
include('../../../class_include.php');
class EnvioMailContratoCliente{
	private $idContratoGlobal ='';
	private $rutaDocto = '';
	private $usuarioId = '';
	private $emailEnvio = '';

	public function __construct($idContratoGlobal, $tipo){
		$user = new Usuario();
		$this->idContratoGlobal = $idContratoGlobal;
		$mail = $user->getUsuario();
		$this->usuarioId = $user->getUsuarioId();
		$this->emailEnvio =  $user->getUsuario();
		$this->enviarMail();
	}	


	private function enviarMail(){
		// se envia el correo electrónico al cliente
	 	$cuerpoMail .= '<h2 class=\"p3\"> Contrato Fiananciera CREA</h2>';
		$servidor = $_SERVER['SERVER_NAME'];
		$liga_servidor = ($servidor=='localhost')?SERVIDOR_LOCAL:SERVIDOR;	
		$cuerpoMail .'Adjunto encontrará el documento solicitado';
		$cuerpoMail .='<p> No responda este mensaje, el remitente es una dirección de notificación</p>';
   		$cuerpoMail .= '<br><img width="393" height="131"  src="http://financieracrea.com/esfdesarrollo/images/firmaCREA24.jpg" alt="Financiera CREA" >';
   		   		
        $emailUsuario = $this->emailEnvio;      
        $emailUsuario = 'zuoran_17@hotmail.com';
        $titulo = 'Firma de contrato';
		//$funcionesUsuario->enviarEmail($emailUsuario,utf8_decode($titulo),utf8_decode($cuerpoMail));

		$phpMailer = new PHPMailer();
		// adjuntamos el documento
		$nombreDelDocumento = "../../../upload/".$this->idContratoGlobal."/Expediente.pdf";

		// ya no se enviara el documento por correo; se debe ver en el portal y solo para verlo sin opcion de descarga

		if (!file_exists($nombreDelDocumento)) {
		echo ("El archivo $nombreDelDocumento no existe");
		}else{
		echo	"Si encontré el docto";
		}


		try {
		    $phpMailer->setFrom("consulta@financieracrea.com", "Financiera CREA"); # Correo y nombre del remitente
		    $phpMailer->addAddress($emailUsuario); # El destinatario
		    $phpMailer->Subject = utf8_decode("Contrato Financiera CREA"); # Asunto

		    $phpMailer->Body = utf8_decode($cuerpoMail); # Cuerpo en texto plano
		    $phpMailer->isHTML(true);
		    // Aquí adjunto:
		    $phpMailer->addAttachment($nombreDelDocumento);
		    if (!$phpMailer->send()) {
		        echo "Error enviando correo: " . $phpMailer->ErrorInfo;
		    }
		    # eliminar el archivo después de enviarlo
		    // if (file_exists($nombreDelDocumento)) {
		    // unlink($nombreDelDocumento);
		    // }		    
		} catch (Exception $e) {
		    echo "Excepción: " . $e->getMessage();
		    $sqlInsertError =  " INSERT INTO `dbfallasenvioscontratos` ";
		    $sqlInsertError .= " (`idfallaenviocontrato`, `refcontratoglobal`, `fecha`, `mensaje`) ";
		    $sqlInsertError .= " VALUES (NULL, ".$idContratoGlobal.", CURDATE(), '".$e->getMessage()."');";
		    $query->setQuery($sqlInsertError);
		    $query->eject();
		}
	}		
}

?>