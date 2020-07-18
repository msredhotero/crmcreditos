<?php




class ServiciosPDFConctratoGlobal{

	public $idContratoGlobal = '';
	public $nombre = '';
	public $usuarioContrato = '';

	public function __construct($idContratoGlobal ,$nombre, $usuarioContratoGlobal){
		$this->idContratoGlobal = $idContratoGlobal;
		$this->nombre = $nombre;
		$this->usuarioContrato = $usuarioContratoGlobal;
		
		

	}


	public function generarPDFGlobal(){
		$query = new Query();		
		$pdfi = new PDFMerger;
		$pdf = new FPDF();
		$text = '';
		$id = $this->idContratoGlobal;

		$contrato = '../upload/'.$id."/Contrato.pdf";		
		if(!file_exists($contrato)){
			$serRef = new ServiciosReferencias();				
			$ref_user = $this->usuarioContrato;
			$pdf->AddPage();
	      	$pdf->SetFont('Times','',12); 
	     	$pdf->Ln(20);
	     	$pdf->Write(5,"Expediente de: ".$this->nombre);


	     	/* desarrollo   ****************************************/
	     	$servidor = $_SERVER['SERVER_NAME'];
	    	$directorio = ($servidor=='localhost')?$_SERVER['DOCUMENT_ROOT']."crmcreditos.git/trunk/":$_SERVER['DOCUMENT_ROOT']."esf/crmcreditosonline/";
			$directorio = $_SERVER['DOCUMENT_ROOT']."crmcreditos.git/trunk/";
			//$directorio = "../";
			
	   		$ar = array();
			//$borrarAr = $serRef->borrarDirecctorio($directorio.'/archivos/postulantes/'.$id.'/foliocompleto');
			$nombreCaratula = $directorio."upload/".$id."/".$id."_CaratualaExpediente.pdf";		
	     	$pdf->Output($nombreCaratula,'F');
	  
	     	array_push($ar,$nombreCaratula);
			$rsDocumentosCliente =  $serRef->traerDocumentacionPorTipoCreditoDocumentacionResponsableAdjuntoClienteAll($id,$ref_user );
			/*echo "<pre>";
			print_r($rsDocumentosCliente);
			echo "</pre>"; */

			$rsDocumentosCliente2 =  $serRef->traerDocumentacionPorTipoCreditoDocumentacion($id, 1);

			$incluirEnPDFGral = '';
			while ($row = mysql_fetch_array($rsDocumentosCliente)) {
				$incluirEnPDFGral = false;			
				$pdf = new FPDF();
				$pdf->AddPage();
			    $idDocumento = $row["idcontratoglobaldocumento"];
			    $refcontratoglobal = $row["refcontratoglobal"];
			    $refdocumento = $row["refdocumento"];
				$nombre = $row["nombre"]; // formato 1_IDENTIFICACION.pdf
				$ruta = $row["carpeta"];	// /upload/1/IDENTIFICACION/
				$carpeta =  $row['nombre_de_carpeta']; // IDENTIFICACION
				$idStatusDocto =  $row['idestadodocumento']; //1				
				$statusDesc =  $row['descripcion']; // Cargada
				$name = array();
				$name = explode(".", $nombre);
				$longitud = count($name);
				$pdf->Write(1,"Documento: ".$nombre);
				if($refdocumento != 20){
					//20 	Consulta DGP
					$incluirEnPDFGral = true;
				}

				#echo  "IdDocuemnto=>".$idDocumento." =>".$nombre;



			   $pos = strpos(strtolower($nombre), 'pdf');
			  # echo "<br>POS=>".$pos;
			   if ($pos === false) {
			      $pdf->Image($directorio.$ruta.$nombre,10,10,190);
			      #$pdf->Image($directorio.'upload/19/IDENTIFICACION/19_IDENTIFICACION.1591375056.jpg',10,10,190);	
			      $nombreTurno = $directorio.$ruta.$refcontratoglobal."_".$carpeta.".pdf";			 
			     # $pdf->SetFont('Times','',12); 
			     # $pdf->Ln(20);
			      #$pdf->Write(5,"contenido");
			      $pdf->Output($nombreTurno,'F');
			      if($incluirEnPDFGral){
			      	array_push($ar,$nombreTurno);
			      }
			      	
			     
			   } else {
			      //array_push($ar,$_SERVER['DOCUMENT_ROOT'].'asesorescrea.git/trunk/crm/archivos/postulantes/'.$id.'/'.$row['carpeta'].'/'.$row['archivo']);
			   	 if($incluirEnPDFGral){
			     	array_push($ar,$directorio.$ruta.$nombre);
			     }
			      #echo $directorio.$ruta.$nombre."<p>";
			      //$pdf = new FPDF();
			   }
			} // termona ciclo archivos

		if (count($ar)>0) {
		    if ($nombreTurno != '') {
		       #$pdfi->addPDF($nombreTurno, 'all');
		    }
		    foreach ($ar as $value) {
		      // code...
		      //die(var_dump($ar));
		      $pdfi->addPDF($value, 'all');
		      //echo $value.'<br>';
		    }
		    //die(var_dump($ar));
		   $pdfi->merge('file',$directorio.'/upload/'.$id.'/Contrato.pdf');
			} else {
				$nombreTurno2 ='a';
				$pdf->Output($nombreTurno2,'I');
			}

			// eliminamos los archivos de la base de datos que pertenezcan al contrato pero que no esten adjuntos al cliente

			$rsDocumentosClienteEliminar =  $serRef->traerDocumentacionPorTipoCreditoDocumentacionResponsableAdjuntoClienteAll($id,$ref_user );
			$text .= $rsDocumentosClienteEliminar;

			$text .= "Inicia \n";


			while ($rowEliminar = mysql_fetch_array($rsDocumentosClienteEliminar)) {
					$referenciaCliente = $rowEliminar['refusuario'];
					$idDocumentoEmilinar = $rowEliminar['idcontratoglobaldocumento'];
					$ruta =$rowEliminar['carpeta'];
					$idDocumentoA = $rowEliminar['refdocumento']; // 20 Consulta DGP
					$nombre_arch = $rowEliminar['nombre_de_carpeta'];
					$idConG = $rowEliminar['refcontratoglobal'];
					$nombre_nuevo = $idConG."_".$nombre_arch.'.pdf';
					$nombre = $rowEliminar["nombre"];
					#$ruta = "upload/49/EDO_CUENTA/";
					$archivo = $rowEliminar['nombre'];
					if(empty($referenciaCliente) && $idDocumentoA != 20){
						// se borra el archivo y el registro de la base de datos
						$dir_destino = $directorio.$ruta."";
							#$se = $this->rmDir_rf($dir_destino);
						$se = $dir_destino;
						$text .= "****\n".$se;	
						if($this->rmDir_rf($dir_destino)){
						$text .= $dir_destino."n <br>";
						$text .= $texta;					
							// eliminamos el registro de la base de datos
							$sqlDelete = "DELETE FROM dbcontratosglobalesdocumentos WHERE idcontratoglobaldocumento = ".$idDocumentoEmilinar." ";
							$text .=  $sqlDelete." \n ". "=> ".$archivo;
							$query->setQuery($sqlDelete);
							$query->eject();						
						}				
					}else{
						if($idDocumentoA != 20){
							$dir_destino = $directorio.$ruta."";
							$sqlUpdate = "UPDATE dbcontratosglobalesdocumentos SET nombre='".$nombre_nuevo."' WHERE idcontratoglobaldocumento = ".$idDocumentoEmilinar." ";
							$query->setQuery($sqlUpdate); 	
							$query->eject();
							if($nombre_nuevo != $nombre ){
								unlink($directorio.$ruta.$nombre);
							}
						}
					}
			}

			 // eliminamos la caratula del pdf		
			unlink($nombreCaratula);	
			$text = '';	
		}
		return $text;		
	}


	public function rmDir_rf($carpeta)
    {

    	 if(file_exists($carpeta) ){    	
	      foreach(glob($carpeta . "/*") as $archivos_carpeta){             
	        if (is_dir($archivos_carpeta)){
	          rmDir_rf($archivos_carpeta);        
	        } else {
	        unlink($archivos_carpeta);
	    	}
	      }
	      rmdir($carpeta); 	             
	     }
	     return true;
	}


	

}

?>