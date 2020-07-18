<?php

class PasosContrato{
	private $idContrato;
	private $ultimaAccion;
	private $usuarioId ;


	public function getUltimaAccion(){
		return $this->ultimaAccion;
	}

	public function __construct($idContratoGlobal = NULL){
		
		
		$usuario = new Usuario();
		$usuarioId = $usuario->getUsuarioId();
		$this->usuarioId = $usuarioId;
		if(is_null($idContratoGlobal)){
			$idContrato = $this->buscaContratoActual();
			$this->idContrato = $idContrato;
			
		}else{
			$this->idContrato = $idContratoGlobal;
			

		}		
		$ultimoPasoContrato = $this->buscaUltimaAccionContrato();
		$this->ultimaAccion = $ultimoPasoContrato;

			#echo "contrato id =>".$this->idContrato." =>".$this->ultimaAccion;

	}

	private function buscaContratoActual(){
		$query = new Query();		
		$sqlContrato = "SELECT * FROM  dbcontratosglobales 	WHERE `usuario_id` = ".$this->usuarioId." ORDER BY idcontratoglobal DESC LIMIT 0,1";		
		$query->setQuery($sqlContrato);
		$rsContrato = $query->eject();
		if($query->numRows($rsContrato) > 0){
			$objetoContrato = $query->fetchObject($rsContrato);
			return $objetoContrato->idcontratoglobal;
		}else{
			return 0;
		}
		
	}

	private function buscaUltimaAccionContrato(){
		$query = new Query();

		if($this->idContrato > 0){
			$sqlContratoProceso = "SELECT refproceso FROM  dbcontratosglobalesprocesos 	WHERE `refcontratoglobal` = ".$this->idContrato." ORDER BY refproceso DESC LIMIT 0,1";
			#echo $sqlContratoProceso,"<br>";
			$query->setQuery($sqlContratoProceso);
			$rsContratoProceso = $query->eject();
			#echo $sqlContratoProceso;			
			if($query->numRows($rsContratoProceso) > 0){			
				$objetoContratoProceso = $query->fetchObject($rsContratoProceso);
				return $objetoContratoProceso->refproceso;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}


}
?>