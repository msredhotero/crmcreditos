<?php
include '../../../class_include.php';

class DowloadDocument{
	private $idContartoGlobal = '';
	private $tipoDocumento = '';
	

	public function __construct($idContratoGlobal , $tipoDocumento){
		$usuario = new Usuario();
		$query = new Query();		
		$this->idContratoGlobal = $idContratoGlobal;
		$this->tipoDocumento =   $tipoDocumento;		
		$usuarioId = $usuario->getUsuarioId();
		$datos = array($idContratoGlobal);
		$datos  = $query->clear($datos);
		$idc = $datos[0];

		$sqlExisteCOntrato = "SELECT * FROM  dbcontratosglobales WHERE idContratoGlobal= $idc";
		$query->setQuery($sqlExisteCOntrato);
		$res =$query->eject();
		$existe = $query->numRows($res);
		if($existe>=1){
			// VERIFICAMOS QUE CORRESPONDA AL USUARIO QUE LO ESTA SOLICITADO
			$sqlVerificaUsuario = 'SELECT usuario_id FROM dbcontratosglobales WHERE idContratoGlobal= '.$idc;		
			$query->setQuery($sqlVerificaUsuario);
			$resU =$query->eject();
			$objU = $query->fetchObject($resU);
			$usuarioContrato = $objU->usuario_id;			
			if($usuarioContrato ==$usuarioId ){
				$this->descargaDocumento();
			}else{
				echo "URL CORRUPTA!";
				}
		}else{
			echo "URL CORRUPTA!";
		}

	}


	private function descargaDocumento(){
		$id = $this->idContratoGlobal;
		$ruta = $this->rutaDocumento($id);  
	    if(!empty($ruta) && file_exists($ruta) && $ruta != 'USUARIO NO VALIDO'){
	        // Define headers

	        header("Cache-Control: public");
	        header("Content-Description: File Transfer");
	        header("Content-Disposition: attachment; filename=Expediente.pdf");
	        header("Content-Type: application/pdf");
	        header("Content-Transfer-Encoding: binary");	        
	        // Read the file
	        readfile($ruta);
	        exit;
	    }else{	    	
	        echo 'No se encuentra el archivo, por favor contacte con Financiera Crea';
	    }
	}

	private function rutaDocumento($id){
		$tipoD =  $this->tipoDocumento;
		$ruta = '';
		switch($tipoD){
			case(1):
			// es un contrato global
			$ruta = '../../../upload/'.$id."/Expediente.pdf";
			break;
			default:
			break;
		}		
		return $ruta;
	}

}

?>