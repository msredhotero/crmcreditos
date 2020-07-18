<?php
include '../../../class_include.php';

$tabla = 'dbcontratosglobales';
$usuario_id = 7795;
$idSolicitudNueva = 34;
$query = new Query();

		$sqlSelectCon = " SELECT vigenciaine FROM ".$tabla." 	WHERE usuario_id =".$usuario_id." ORDER BY 1 DESC LIMIT 0,1 ";
		$query->setQuery($sqlSelectCon);
		$resINE =$query->eject();
		$objINE = $query->fetchObject($resINE);
		$vigenciaINE = $objINE->vigenciaine;

		if(!empty($vigenciaINE) &&  $vigenciaINE != '0000-00-00'){
			echo "Vigencia INE no empty \n";
			validaVigenciaINE( $vigenciaINE, $usuario_id, $idSolicitudNueva);
		} else{
		echo " \n la fecha esta vacia";
		}


		function validaVigenciaINE($vigenciaine, $usuario_id, $idSolicitudNueva){
		$query = new Query();
		echo " \n <br> entra en la funcion de INE =>".$vigenciaine;
		$today = date("Y-m-d");	
		$sqlFechas = "SELECT DATEDIFF('".$vigenciaine."','".$today."') as dias_de_vigencia ;";			
		$query->setQuery($sqlFechas);			
		$resVencimiento = $query->eject(); 
		$objFechaVen = $query->fetchObject($resVencimiento);
		$diasVigenciaINE = $objFechaVen->dias_de_vigencia; // dias vigencia del INE		
		echo " \n dias de vigencia => ".$diasVigenciaINE;
		if($diasVigenciaINE < 0 ){
			echo "Dias menos a 0";
			// el INE ya caduco, se de obligar a cargar nuevamente el INE y la comprobacion de la lista nominal
			// se quita la referencia de usuario al registro de INE y de lista nominal
			// como estan requeridos por usuario al quitar la referencia ya no los encontrara y pedira que se carguen nuevamente
			$updateIne = "UPDATE dbcontratosglobalesdocumentos 	SET `refusuario` = NULL WHERE refusuario = ".$usuario_id." AND (refdocumento = 1 OR refdocumento = 2 OR refdocumento = 10 )" ;
			$query->setQuery($updateIne);
			$query->eject();
			}else{
				// se actualiza la fecha de vigencia del ine en el ultimo contrato
				$sqlUpdateF = "UPDATE dbcontratosglobales SET vigenciaine ='".$vigenciaine."' WHERE idcontratoglobal= ".$idSolicitudNueva."";
				echo "<br>".$sqlUpdateF;
				$query->setQuery($sqlUpdateF);
				$query->eject();
			}
	}


?>