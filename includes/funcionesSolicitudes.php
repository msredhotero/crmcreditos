<?php
//header('Content-Type: application/json');
date_default_timezone_set('America/Mexico_City');

class ServiciosSolicitudes {
	private $idSolicitud; 
	private $idCliente;
	private $idUsuario;
	private $idDocumentos;
	private $idReferencias;
	private $datos;
	private $idContratoGlobal;
	private $refcontratoglobal;
	private $lectura ;
	private $ultimoStatus ;
	private $idContratoGLobalAnterior;

	public function setSolcitudId($idSolicitud)
	{
		$this->idSolicitud = $IdSolicitud;
	}

	public function setContratoGlobalId($idContratoGlobal)
	{
		$this->idContratoGlobal = $idContratoGlobal;
		$this->refcontratoglobal = $idContratoGlobal;
	}


	public function setDatos($datos){
		$this->datos = $datos;		
	}

	public function getDatos(){
		return $this->datos;
	}

	public function getLectura(){
		return $this->lectura;
	}

	public function setLectura($lectura){
		 $this->lectura = $lectura;

	}


	/**
	 * @return mixed
	 */
	public function getDato($campo)
	{
		if(array_key_exists($campo, $this->datos)){
			return $this->datos->$campo;
		}
		
	}
	
	/**
	 * @param mixed $datos
	 */
	public function setDato($campo, $dato)
	{
		$this->datos->$campo = $dato;
	}

	function __construct($idContratoGlobal = NULL, $nuevaSolicitud = NULL){	

	#echo "IdContratoglobal =>".$idContratoGlobal ."Nueva =>". $nuevaSolicitud	."**<br>";
		 $query = new Query();
		 if(!empty($idContratoGlobal)){		 	
		 	$this->setContratoGlobalId($idContratoGlobal);	 	
		 	$this->refcontratoglobal = $idContratoGlobal;		 	
		 }else{		 	
		 	$usuario = new Usuario();
		 	$usuario-> setUsuarioData();
			$usuario_id = $usuario->getUsuarioId();	
			$whereT = "	usuario_id = ".$usuario_id;

			if(!empty($usuario_id) && is_null($nuevaSolicitud)){				
				$idContratoGlobal = $query->selectCampo('idcontratoglobal','dbcontratosglobales',$whereT );
				$this->setContratoGlobalId($idContratoGlobal);
				$this->refcontratoglobal = $idContratoGlobal;

			}
		 }
		 if(!empty($this->idContratoGlobal)){
		 	$this->setLectura($this->cargarPermisos($this->idContratoGlobal));	
		 	$this->actualizaDocumentosCompletosAdministracion($this->idContratoGlobal);		 	 	
		 }else{		 	
		 	$this->setLectura(false);		 	
		 }	

		 
		 # echo "<p><p><p><p><p>LECTURA =>".$this->lectura;
		
		 // cagar campos que no sean de solicitudes
	}

	public function cargarPermisos($idContratoGlobal){
		$lectura = true;
		$query  = new Query();
		$usuario = new Usuario();
		$usuario-> setUsuarioData();

		$sqlPermisos = "SELECT dbcgp.`refproceso` AS ultimo_proceso,  dbcgp.refusuario, p.descripcion, p.roles AS usuarios_edicion FROM `dbcontratosglobalesprocesos` AS dbcgp JOIN tbproceso AS p ON p.idproceso = dbcgp.refproceso WHERE `refcontratoglobal` = ".$idContratoGlobal." ORDER BY ultimo_proceso DESC";
		$query->setQuery($sqlPermisos);
		$resPermisos = $query->eject();	
		if($query->numRows($resPermisos)){	
			$permisos = $query->fetchObject($resPermisos);
			$usuariosEdicion = $permisos->usuarios_edicion;		
			$arrayUsers = explode(',', $usuariosEdicion);		
			$lectura = !in_array($usuario->getRolId(), $arrayUsers);
		}		

		$sqlStatus = "SELECT max(refstatuscontratoglobal) as status FROM dbcontratosglobalesstatus WHERE refcontratoglobal = ".$idContratoGlobal;
		$query->setQuery($sqlStatus);		
		$resStatus =  $query->eject();
		$objStatus = $query->fetchObject($resStatus);
		$ultimoStatus =  $objStatus->status;
		$arrayLectura = array(4,9,10,11,5,3);
		if(in_array($ultimoStatus, $arrayLectura)){
		#if($ultimoStatus == 10 || $ultimoStatus == 9 ||  $ultimoStatus == 4){
			$lectura = true;
		}

		$arrayAbrirParaOficial = array(10,11); //recahazad;bloqueado PLD
		if(in_array($ultimoStatus, $arrayAbrirParaOficial) &&  $usuario->getRolId() == 20 ){
			$lectura = false;
		}

		
		return $lectura;		
	}

	public function subirDocumentosSolicitudGlobal($idContratoG = NULL){
		
		$query = new Query();
		$cuerpoMail ='';
		#$idContratoGlobal = $this->get_value('idcontratoglobal');
		
		$serviciosUsuarios = new ServiciosUsuarios();
		$usuario = new Usuario();
		$usuario-> setUsuarioData();
		$emailUsuario = $usuario->getUsuario();
		$usuarioId = $usuario->getUsuarioId();
		$usuarioRolId = $usuario->getRolId();
		#$idContratoGlobal = (!empty($idContratoG))?$idContratoG:$this->traercontratoGlobalId();
		
		$idContratoGlobal = $_POST['idcontratoglobal'];		
		$tipoPermitido = array("jpg" => "image/jpg","JPG" => "image/jpg", "JPEG" => "image/jpeg","jpeg" => "image/jpeg", "pdf" => "application/pdf");
		$error = '';
		$_POST['refcontratoglobal'] = $idContratoGlobal; 	
		$directorioCarga = "../upload/".$idContratoGlobal."/";

		#$cuerpoMail .= '<img src="http://financieracrea.com/esfdesarrollo/images/logo.gif" alt="Financiera CREA" >';
    	$cuerpoMail .= '<h2 class=\"p3\"> DOCUMENTOS RECIBIDOS</h2>';
    	$servidor = $_SERVER['SERVER_NAME'];
    	$liga_servidor = ($servidor=='localhost')?SERVIDOR_LOCAL:SERVIDOR;
   		$cuerpoMail .= '<h3><small><p>Hemos recibido sus documentos, en breve nos comunicaremos con usted, por favor espere nuestra llamada. </p></small></h3>';
   		$cuerpoMail .='<p> No responda este mensaje, el remitente es una dirección de notificación</p>';
	

	   	$cuerpoMail .= '<br><img width="393" height="131"  src="http://financieracrea.com/esfdesarrollo/images/firmaCREA24.jpg" alt="Financiera CREA" >';
		if (!file_exists($directorioCarga)) {
		   if(!mkdir($directorioCarga, 0777, true)){
		   	$error .= "Error al crear la carpeta destino";		   	
		   }else{
		   	if(!$fh = fopen($directorioCarga."index.php", 'w') ){	
		   		$error .= "Se produjo un error al crear el archivo index de la carpeta de archivos";   			
		   }

		   }

		    
		}
		
		
		foreach ($_FILES as $file => $filevalues) {
			// para cada archivo insertamos en base de datos y movemos el temporal a la nueva ruta
			# code...
			$nuevoNombreFile =  $idContratoGlobal."_".$this->nombreArchivo($file);	
			if($filevalues["error"] != 4){		
				if( $filevalues["error"] == 0  ){

					$directorioArchivo = $directorioCarga.$this->nombreArchivo($file).'/';
					if (!file_exists($directorioArchivo)) {
					   if(!mkdir($directorioArchivo, 0777, true)){
					   	$error .= "Error al crear la carpeta para el archivo";					   		
					   }else{
					   		if(!$fh = fopen($directorioArchivo."index.php", 'w')){
					   			$error .=("Se produjo un error al crear el archivo index de la carpeta de archivos");					   				
					   		} 
					   }					    
					}	


					$filename = $filevalues["name"];
	        		$filetype = $filevalues["type"];
	        		$filesize = $filevalues["size"];      		

					 $ext = pathinfo($filename, PATHINFO_EXTENSION);
					 $nuevoNombreFile = $nuevoNombreFile.".".$ext;
					 if(!array_key_exists($ext, $tipoPermitido)) {
					 	$error .= "Error: tipo de archivo incorrecto " .$this->nombreArchivo($file);		 	
					 }
						// Verificar MYME tipo de archivo
				        if(in_array($filetype, $tipoPermitido)){
				            // verificamos is ya existe el archivo
				            if(file_exists($directorioArchivo.$filename)){
				                $error .= " ". $filename . " el archivo ya existe. \n <br>";              	
				                // aqui opcion para sobre escribir, si fuera el caso
				            } else{
				                if(!move_uploaded_file($filevalues["tmp_name"], $directorioArchivo. $nuevoNombreFile)){
				                	$error .= " ". "Error al subir el archivo= \n <br>";               		
				                }else{
				                	// el archivo se cargo correctamente alservidor se inserta el registro en la base de datos
				                	$sqlIsertFile = "INSERT INTO `dbcontratosglobalesdocumentos` (`idcontratoglobaldocumento`, `refcontratoglobal`, `refdocumento`, `nombre`, `ruta`, `vigencia_desde`, `vigencia_hasta`) ";
				                	$sqlIsertFile .= " VALUES (NULL, $idContratoGlobal , $file, '".$nuevoNombreFile."', '".trim($directorioArchivo,'.')."', CURDATE(), DATE_ADD(CURDATE(),INTERVAL 1 YEAR)); ";
				                	$query->setQuery($sqlIsertFile);
				                	$query->eject(1);
				                		                	
				                }			               
				            } 
				        } else{
				            $error .= "Error: error al cargar tu archivo por favor intenta nuevamente.".$this->nombreArchivo($file);				            	
				        }
			    } else{
			        
			        if($filevalues["error"] == 1) 
		        		$error .= "Error: " . $filevalues["error"]." El fichero seleccionado excede el tamaño máximo permitido";
			        if($filevalues["error"] == 2) 
		        		$error .= "Error: " . $filevalues["error"]." El archivo subido excede la directiva MAX_FILE_SIZE,";
			        if($filevalues["error"] == 3) 
		        		$error .= "Error: " . $filevalues["error"]." El archivo subido fue sólo parcialmente cargado.";
			         if($filevalues["error"] == 6) 
		        		$error .= "Error: " . $filevalues["error"]." Falta el directorio de almacenamiento temporal.";
			         if($filevalues["error"] == 7) 
		         		$error .= "Error: " . $filevalues["error"]." No se puede escribir el archivo (posible problema relacionado con los permisos de escritura)";
			      	 if($filevalues["error"] == 8) 
		    	 		$error .= "Error: " . $filevalues["error"]." Una extensión PHP detuvo la subida del archivo";   	 		

			    }
			}

		} // foreach
		if($error==''){
			// enviamos correo al usauurio
			if($usuarioRolId ==8){
				$serviciosUsuarios->enviarEmail($emailUsuario,'Recepción de documentos',utf8_decode($cuerpoMail));
			}
			
			// insertamos el proceso 2	
 			if(!$this->insertaProcesoContratoGlobal($idContratoGlobal, 2, $usuarioId))
 			$error = "Error al insertar el proceso 2";	
 		#echo "No hay error";
 			$dosctosAdminCompletos = $this->verificaDoctosAdministracion($idContratoGlobal);
 			if($dosctosAdminCompletos){
 				$sqlUpdateContrato = "UPDATE dbcontratosglobales  SET `documentosadministracioncompletos` = '1' WHERE `idcontratoglobal` =".$idContratoGlobal;
 				$query->setQuery($sqlUpdateContrato);
 				$query->eject();
 			}
		}

		return $error;

	}

	public function insertaProcesoContratoGlobal($idContratoGlobal, $idProceso, $idUsuario){
		$query = new Query();
		$sqlProceso = "INSERT INTO `dbcontratosglobalesprocesos` (`idcontratoglobalproceso`, `refcontratoglobal`, `refproceso`, `refusuario`, `fecha`, `hora`) ";
		$sqlProceso .= " VALUES (NULL, '".$idContratoGlobal."', '".$idProceso."', '".$idUsuario."', CURDATE(), now());";
		$query->setQuery($sqlProceso);
		$rs = $query->eject(1);
		if(!$rs){					
			echo "<br>ERROR EN INSERT proceso</br> ";	
			$rs = 0;						
		}
		return $rs;
	}

	public function actualizaDocumentosCompletosAdministracion($idContratoGlobal){
		$query = new Query();
		$dosctosAdminCompletos = $this->verificaDoctosAdministracion($idContratoGlobal);
		$completos =  ($dosctosAdminCompletos)?1:0;		
 		$sqlUpdateContrato = " UPDATE dbcontratosglobales verificaDoctosAdministracion SET `documentosadministracioncompletos` = '".$completos."' WHERE `idcontratoglobal` =".$idContratoGlobal;  	
 		#echo $sqlUpdateContrato	;
 		$query->setQuery($sqlUpdateContrato);
 		$query->eject();

	}

	public function traercontratoGlobalId(){
		$usuario = new Usuario();
		$usuario-> setUsuarioData();
		$query = new Query();
		$usuario_id = $usuario->getUsuarioId();
		$condicion =  " usuario_id =".$usuario_id;
		$contratoGlobalId = $query->selectCampo('idcontratoglobal', 'dbcontratosglobales', $condicion );
		return $contratoGlobalId ;
	}

	public function nombreArchivo($idArchivo){
		$query = new Query();
		$condicion = "	iddocumento = ".$idArchivo."";
		$nombreArchivo = $query->selectCampo('nombre_archivo', 'tbdocumento', $condicion );
		return $nombreArchivo;
	}

	public function buscarTipoDoctosAdmin(){
		$arrDoctos = array();
		$query = new Query();
		$usuario = new Usuario();
		$usuario-> setUsuarioData();
		$rol = $usuario->getRolId();
		
		$responsable = ($rol == 8 )?1:2; // 1=cliente, 2= financiera, 3 = empresa_afiliada 
		$this->cargarDatosContratoGlobal();
		$empresaAfiliada = $this->getDato('refempresaafiliada');
		$idTipocontratoGlobal = $this->getDato('reftipocontratoglobal');
		
		if(!empty($empresaAfiliada)){
			$wr = ' WHERE `idempresaafiliada` = '.$empresaAfiliada;
			if(!empty($responsable))
			$wr .= ' AND `responsable` = '.$responsable; 
			$wr .= ' AND `idtipocontratoglobal` = '.$idTipocontratoGlobal; 
			$sqlDoctos = "SELECT * FROM  vista_empresa_afialida_tipo_coontrato_glogal_documentos ".$wr;		

			$query->setQuery($sqlDoctos);
			$rsDoctos = $query->eject();
			#while($rowDoctos = $rs->fetch_array(MYSQLI_ASSOC)){			
			while($rowDoctos = mysql_fetch_array($rsDoctos)){	
					$arrDoctos[$rowDoctos['iddocumento']] = array('documento'=> $rowDoctos['documento'],
																  'especificaciones'=> $rowDoctos['especificaciones'],
																  'requerio'=> $rowDoctos['requerio'],
																  'responsable'=> $rowDoctos['responsable']
																 );
			}	
		}
		#documetosSolicitados

		
		return $arrDoctos;

	}
	public function buscarTipoDoctos(){
		$arrDoctos = array();
		$query = new Query();
		$usuario = new Usuario();
		$usuario-> setUsuarioData();
		$rol = $usuario->getRolId();
		$idContratoGlobal = '';
		
		$responsable = ($rol == 8 )?1:2; // 1=cliente, 2= financiera, 3 = empresa_afiliada		
		$this->cargarDatosContratoGlobal($this->idContratoGlobal);
		$empresaAfiliada = $this->getDato('refempresaafiliada');		
		
		$idTipocontratoGlobal = $this->getDato('reftipocontratoglobal');
		 
		if(!empty($empresaAfiliada)){
			$wr = ' WHERE `idempresaafiliada` = '.$empresaAfiliada;
			if(!empty($responsable))
			$wr .= ' AND `responsable` = '.$responsable; 
			$wr .= ' AND `idtipocontratoglobal` = '.$idTipocontratoGlobal; 
			$sqlDoctos = "SELECT * FROM  vista_empresa_afialida_tipo_coontrato_glogal_documentos ".$wr;		
			
			$query->setQuery($sqlDoctos);
			$rsDoctos = $query->eject();
			#while($rowDoctos = $rs->fetch_array(MYSQLI_ASSOC)){			
			while($rowDoctos = mysql_fetch_array($rsDoctos)){	
					$arrDoctos[$rowDoctos['iddocumento']] = array('documento'=> $rowDoctos['documento'],
																  'especificaciones'=> $rowDoctos['especificaciones'],
																  'requerio'=> $rowDoctos['requerio'],
																  'responsable'=> $rowDoctos['responsable']
																 );
			}	
		}
		#documetosSolicitados

		
		return $arrDoctos;

	}
	

	public function cargarDatosContratoGlobal($idContratoG = NULL){
		// cargamos todos los datos de la solicictud de credito		
		$query = new Query();
		$datosSolicitud = array();
		$datosConsulta = array(); // aqui guardaremos los datos de las tabla		
		//$this->idSolicitud =6;	

		if($idContratoG>0){
			$this->idContratoGlobal = $idContratoG;
		}	
			
			//$this->idContratoGlobal = $idContratoG ;
		if(!empty($this->idContratoGlobal) ){			
			// si la solicitud ya existe buscamos los datos
			$wr = ' WHERE `idcontratoglobal` = \''.$this->idContratoGlobal.'\'';
			$qSol = "SELECT * FROM dbcontratosglobales ".$wr;
		
			$query->setQuery($qSol);
			$rs = $query->eject();
			$rw = $query->fetchObject($rs); //zoa
			foreach ($rw as $campo => $valor){
				if(is_null($valor)){
					$valor = '';
				}
				$datosConsulta[$campo] = $valor;	
			}

			$arrayDoctos = array();
			$arrayRutas = array();
			// veificamos si ya existen los documentos
			$wr = ' WHERE `refcontratoglobal` = \''.$this->idContratoGlobal.'\'';
			$sqlDoctos = "SELECT * FROM dbcontratosglobalesdocumentos ".$wr;			
			$query->setQuery($sqlDoctos);
			$rs2 = $query->eject();
			//$rw2= $rs2->fetch_all(MYSQLI_ASSOC);
			#$rw2 = $query->fetchObject($rs2);
			$archivo = 0;

			while($rw2 = $query->fetchObject($rs2)){				
					$archivo = $rw2->refdocumento;
					$valor =  (is_null($rw2->nombre))?'':$rw2->nombre;
					$ruta =  (is_null($rw2->ruta))?'':$rw2->ruta;
					$arrayDoctos[$archivo] = $valor;
					$arrayRutas[$archivo] = $ruta;			

			}
			
			#print_r($arrayDoctos);
			foreach ($arrayDoctos as $campo => $valor) {
				$datosConsulta["documento_".$campo] = $valor;
				$datosConsulta["ruta_".$campo] = $arrayRutas[$campo];	

			}

			// cargamos el ultimo status de la solicitud y si existe la causa de rechazo
			$sqlStatusContrato = "SELECT * FROM dbcontratosglobalesstatus where refcontratoglobal =  $this->idContratoGlobal  ORDER BY `idcontratoglobalstatus` DESC limit 0,1";
			
			$query->setQuery($sqlStatusContrato);
			$rsStatus = $query->eject();
			$objStatus =  $query->fetchObject($rsStatus);
			$datosConsulta['cgs_refcontratoglobal'] = $objStatus->refcontratoglobal ;
			$datosConsulta['cgs_refstatuscontratoglobal'] = $objStatus->refstatuscontratoglobal ;
			$datosConsulta['cgs_refrechazocausa'] = $objStatus->refrechazocausa ;
			$datosConsulta['cgs_refusuario'] = $objStatus->refusuario ;
			$datosConsulta['cgs_fecha'] = $objStatus->fecha ;
			$datosConsulta['cgs_hora'] = $objStatus->hora ;

			// verificamos si la referencia a la UDI esta vacia, si esta vacia se carga los datos del ultimo registro de la tabla UDI y se multiplica por 3000 para mostrar el monto en la solicitud
			if($datosConsulta['refudi'] =='' || empty($datosConsulta['refudi']) ){
				$sqlUDIS = "SELECT * FROM tbudi  ORDER BY `idudi` DESC limit 0,1";			
				$query->setQuery($sqlUDIS);
				$rsUDI = $query->eject();
				$objUDI =  $query->fetchObject($rsUDI);
				$datosConsulta['refudi'] = $objUDI->idudi;
				$datosConsulta['limiteUDI'] = ($objUDI->descripcion * 3000);
				$datosConsulta['limiteUDIF'] = number_format($objUDI->descripcion * 3000);

			}else{
				$sqlUDIS = "SELECT * FROM tbudi WHERE idudi =". $datosConsulta['refudi']." ORDER BY `idudi` DESC limit 0,1";			
				$query->setQuery($sqlUDIS);
				$rsUDI = $query->eject();
				$objUDI =  $query->fetchObject($rsUDI);				
				$datosConsulta['limiteUDI'] = ($objUDI->descripcion * 3000);
				$datosConsulta['limiteUDIF'] = number_format($objUDI->descripcion * 3000);

			}


		}// contratoGlobal			
			
		// asignamos los valores de la consulta a las propiedades de la clase		
		$this->datos = (object)$datosConsulta;
		//print_r($this->datos);
		
	}

	public function cargarDatosContratoGlobalClienteRegistrado(){
		// cargamos todos los datos de la solicictud de credito		
		$query = new Query();
		$datosSolicitud = array();
		$datosConsulta = array(); // aqui guardaremos los datos de las tabla		
		//$this->idSolicitud =6;
		$usuario = new Usuario();
		$usuarioId = $usuario->getUsuarioId();


		// buscamos el contrato global anterior del cliente que ya existe

		$sqlContratoGlobalAnterior = "SELECT idcontratoglobal as idAnterior FROM dbcontratosglobales WHERE  usuario_id = ".$usuarioId ." ORDER BY idcontratoglobal DESC LIMIT 0,1 ";
		$query->setQuery($sqlContratoGlobalAnterior);
		$resAnt = $query->eject();
		$objIdAnterior =  $query->fetchObject($resAnt);
		$idContratoGLobalAnterior = $objIdAnterior->idAnterior;


		if($idContratoGLobalAnterior>0){
			$this->idContratoGLobalAnterior = $idContratoGLobalAnterior;
		}	
			
			//$this->idContratoGlobal = $idContratoG ;
		if(!empty($this->idContratoGLobalAnterior) ){			
			// si la solicitud ya existe buscamos los datos
			$wr = ' WHERE `idcontratoglobal` = \''.$this->idContratoGLobalAnterior.'\'';
			$qSol = "SELECT
					usuario_id,
					refempresaafiliada,
					reftipocliente,
					refsuceptiblect,
					nombre,
					paterno,
					materno,
					fechanacimiento,
					refpais,
					refnacionalidad,
					refentidadnacimiento,
					refgenero,
					refpaisresidencia,
					rfc,
					curp,
					calle,
					numeroexterior,
					numerointerior,
					colonia,
					codigopostal,
					refentidad,
					refmunicipio,
					reflocalidad,
					telefono1,
					reftipotelefono1,
					telefono2,
					celular1,
					refcompania1,
					refdependencia,
					refactividad,
					antiguedadanio,
					antiguedadmes,
					departamento,
					puesto,
					calleempleo,
					numeroexteriorempleo,
					numerointerioremplo,
					coloniaempleo,
					codigopostalempleo,
					refentidadempleo,
					refmunicipioempleo,
					reflocalidadempleo,
					noempleado,
					otroempleo,
					empresa2,
					refpagoalclientecanal,
					refpagodelclientecanal,
					refadelantopagos,
					vigenciaine



			FROM dbcontratosglobales ".$wr;
			#echo $sql;
			$query->setQuery($qSol);
			$rs = $query->eject();
			$rw = $query->fetchObject($rs); //zoa
			foreach ($rw as $campo => $valor){
				if(is_null($valor)){
					$valor = '';
				}
				$datosConsulta[$campo] = $valor;	
			}

			$arrayDoctos = array();
			$arrayRutas = array();
			// veificamos si ya existen los documentos
			$wr = ' WHERE `refcontratoglobal` = \''.$this->idContratoGlobal.'\'';
			$sqlDoctos = "SELECT * FROM dbcontratosglobalesdocumentos ".$wr;			
			$query->setQuery($sqlDoctos);
			$rs2 = $query->eject();
			//$rw2= $rs2->fetch_all(MYSQLI_ASSOC);
			#$rw2 = $query->fetchObject($rs2);
			$archivo = 0;

			while($rw2 = $query->fetchObject($rs2)){				
					$archivo = $rw2->refdocumento;
					$valor =  (is_null($rw2->nombre))?'':$rw2->nombre;
					$ruta =  (is_null($rw2->ruta))?'':$rw2->ruta;
					$arrayDoctos[$archivo] = $valor;
					$arrayRutas[$archivo] = $ruta;			

			}
			
			#print_r($arrayDoctos);
			foreach ($arrayDoctos as $campo => $valor) {
				$datosConsulta["documento_".$campo] = $valor;
				$datosConsulta["ruta_".$campo] = $arrayRutas[$campo];	

			}

			// cargamos el ultimo status de la solicitud y si existe la causa de rechazo
			$sqlStatusContrato = "SELECT * FROM dbcontratosglobalesstatus where refcontratoglobal =  $this->idContratoGLobalAnterior  ORDER BY `idcontratoglobalstatus` DESC limit 0,1";
			
			$query->setQuery($sqlStatusContrato);
			$rsStatus = $query->eject();
			$objStatus =  $query->fetchObject($rsStatus);
			#$datosConsulta['cgs_refcontratoglobal'] = $objStatus->refcontratoglobal ;
			#$datosConsulta['cgs_refstatuscontratoglobal'] = $objStatus->refstatuscontratoglobal ;
			#$datosConsulta['cgs_refrechazocausa'] = $objStatus->refrechazocausa ;
			#$datosConsulta['cgs_refusuario'] = $objStatus->refusuario ;
			#$datosConsulta['cgs_fecha'] = $objStatus->fecha ;
			#$datosConsulta['cgs_hora'] = $objStatus->hora ;

			// verificamos si la referencia a la UDI esta vacia, si esta vacia se carga los datos del ultimo registro de la tabla UDI y se multiplica por 3000 para mostrar el monto en la solicitud
			if($datosConsulta['refudi'] =='' || empty($datosConsulta['refudi']) ){
				$sqlUDIS = "SELECT * FROM tbudi  ORDER BY `idudi` DESC limit 0,1";			
				$query->setQuery($sqlUDIS);
				$rsUDI = $query->eject();
				$objUDI =  $query->fetchObject($rsUDI);
				#$datosConsulta['refudi'] = $objUDI->idudi;
				#$datosConsulta['limiteUDI'] = ($objUDI->descripcion * 3000);
				#$datosConsulta['limiteUDIF'] = number_format($objUDI->descripcion * 3000);

			}else{
				$sqlUDIS = "SELECT * FROM tbudi WHERE idudi =". $datosConsulta['refudi']." ORDER BY `idudi` DESC limit 0,1";			
				$query->setQuery($sqlUDIS);
				$rsUDI = $query->eject();
				$objUDI =  $query->fetchObject($rsUDI);				
				#$datosConsulta['limiteUDI'] = ($objUDI->descripcion * 3000);
				#$datosConsulta['limiteUDIF'] = number_format($objUDI->descripcion * 3000);

			}


		}// contratoGlobal	

		// quitamos los elemento que se deberia de volver a llenar por el cliente

		#$idContratoGLobalAnterior	reftipocontratoglobal
		unset($datosConsulta['idcontratoglobal'],			
			  $datosConsulta['fecha_registro'],
			  $datosConsulta['hora_registro'],
			  $datosConsulta['refformapago'],
			  $datosConsulta['reftipocontratoglobal'],
			  $datosConsulta['cedulasi'],
			  $datosConsulta['cedulano'],
			  $datosConsulta['firmasi'],
			  $datosConsulta['firmano'],
			  $datosConsulta['cargopublico'],
			  $datosConsulta['cargopublicofamiliar'],
			  $datosConsulta['refparentesco'],
			  $datosConsulta['cuentapropia'],
			  $datosConsulta['origenrecursos']);	
		
			
		// asignamos los valores de la consulta a las propiedades de la clase		
		$this->datos = (object)$datosConsulta;
		#var_export ($this->datos);
		
	}


	



	function validaVigenciaINE($vigenciaine, $usuario_id, $idSolicitudNueva){
		$query = new Query();

		$today = date("Y-m-d");	
		$sqlFechas = "SELECT DATEDIFF('".$vigenciaine."','".$today."') as dias_de_vigencia ;";			
		$query->setQuery($sqlFechas);			
		$resVencimiento = $query->eject(); 
		$objFechaVen = $query->fetchObject($resVencimiento);
		$diasVigenciaINE = $objFechaVen->dias_de_vigencia; // dias vigencia del INE		
		if($diasVigenciaINE < 0 ){
			// La INE ya caduco, se de obligar a cargar nuevamente el INE y la comprobacion de la lista nominal
			// se quita la referencia de usuario al registro de INE y de lista nominal
			// como estan requeridos por usuario al quitar la referencia ya no los encontrara y pedira que se carguen nuevamente
			// refdocumento = 1 ; INE amberso ,refdocumento = 2; INE reverso, refdocumento = 10; comprobacion lista nominal
			$updateIne = "UPDATE dbcontratosglobalesdocumentos 	SET `refusuario` = NULL WHERE refusuario = ".$usuario_id." AND (refdocumento = 1 OR refdocumento = 2 OR refdocumento = 10 )" ;
			$query->setQuery($updateIne);
			$query->eject();
			}else{
				// se actualiza la fecha de vigencia del ine en el ultimo contrato
				$sqlUpdateF = "UPDATE dbcontratosglobales SET vigenciaine ='".$vigenciaine."' WHERE idcontratoglobal= ".$idSolicitudNueva."";
				$query->setQuery($sqlUpdateF);
				$query->eject();
			}
	}
	
	function verificaDoctosAdministracion($idContratoGlobal){
		//seleccionamos los archivos que son requeridos para el tiepo de contrato por parte de administracion
		$serviciosReferencias = new ServiciosReferencias();
		$arrDoctos = array();
		$query = new Query();
		
		$responsable = 2; // 1=cliente, 2= financiera, 3 = empresa_afiliada		
		$this->cargarDatosContratoGlobal($idContratoGlobal);
		$empresaAfiliada = $this->getDato('refempresaafiliada');		
		$idTipocontratoGlobal = $this->getDato('reftipocontratoglobal');
		$doctosCompletos = true;		
		if(!empty($empresaAfiliada)){
			$wr = ' WHERE `idempresaafiliada` = '.$empresaAfiliada;
			if(!empty($responsable))
			$wr .= ' AND `responsable` = '.$responsable; 
			$wr .= ' AND `idtipocontratoglobal` = '.$idTipocontratoGlobal; 
			$wr .= ' AND `requerio` = 1'; 
			#$sqlDoctos = "SELECT * FROM  vista_empresa_afialida_tipo_coontrato_glogal_documentos ".$wr;
			#$query->setQuery($sqlDoctos);
			#$rsDoctos = $query->eject();	

			$rsDoctos = $serviciosReferencias->traerDocumentacionPorTipoCreditoDocumentacionCompletaCliente($idContratoGlobal, 2);			
			#while($rowDoctos = $rs->fetch_array(MYSQLI_ASSOC)){			
			while($rowDoctos = mysql_fetch_array($rsDoctos)){
				$idDoctoAdmin = $rowDoctos['iddocumento'];
				$doctoRequerido = $rowDoctos['req'];
				$sqlCargados = " SELECT * FROM dbcontratosglobalesdocumentos WHERE  refdocumento =".$idDoctoAdmin." AND  	refcontratoglobal = ".$idContratoGlobal."";
				$query->setQuery($sqlCargados);
				$rsDocts = $query->eject();
				$noDoctos = $query->numRows($rsDocts);				
				if($doctoRequerido && $noDoctos<1){
					$doctosCompletos = false;
				}
			}
		}
			
			return $doctosCompletos;

	}
	

	

	






	function traerDatosCatalogo($tabla){
		$sql = "SELECT * FROM ".$tabla." WHERE 1";		
		$res = $this->query($sql,0);

		if ($res == false) {
			return 'Error al traer datos';
		} else {
			return $res;
		}
	}

	function insertarSolicitudGlobal(){
		
		$msg = array();
		$msg['error'] = '';		
		$query = new Query();
		$usuario = new Usuario();
		$usuario-> setUsuarioData();
		$errorEnTrasaccion = '';
		$tabla = 'dbcontratosglobales';
		$_POST['usuario_id'] = $usuario->getUsuarioId();
		$_POST['fecha_registro'] =  date('Y-m.d');		
		$valuesSolicitud = $this->traercamposValoresPost($tabla);

		//buscamos la fecha del INE si es que ya existe un contrato activo
		$sqlSelectCon = " SELECT vigenciaine FROM ".$tabla." 	WHERE usuario_id =".$_POST['usuario_id']." ORDER BY 1 DESC LIMIT 0,1 ";
		$query->setQuery($sqlSelectCon);
		$resINE =$query->eject();
		$objINE = $query->fetchObject($resINE);
		$vigenciaINE = $objINE->vigenciaine; 


		
		// iniciamos la transaccion
		// si hay errror en algun query ejecutamos el rollback
		// si todas estan bien ejecuatamos el commit
		$query->beginTrans();			
		$query->insert($tabla,$valuesSolicitud);
		$rs = $query->ejectTrans(1);		
		if(!$rs){
			$errorEnTrasaccion = 1; 
			$query->rollbackTrans();			
			echo "<br>ERROR EN SOLICITUD:</br> ";
			$msg['error'] = 1;	
				
		}else{
			$idSolicitudNueva = $rs;
			$msg['IdSolicitud'] = $idSolicitudNueva;
						
		}

		$sqlUpdateHora = " UPDATE ".$tabla." SET hora_registro = now() WHERE idcontratoglobal =". $idSolicitudNueva;
		$query->setQuery($sqlUpdateHora);
		$rs = $query->ejectTrans(0);		
		if(!$rs){			
			$errorEnTrasaccion = 1;
			$rb = $query->rollbackTrans();
			echo "<br>ERROR EN UPDATE:</br> ";	
			$msg['error'] = 1;
						
		}

		$sqlProceso = "INSERT INTO `dbcontratosglobalesprocesos` (`idcontratoglobalproceso`, `refcontratoglobal`, `refproceso`, `refusuario`, `fecha`, `hora`) ";
		$sqlProceso .= " VALUES (NULL, '".$idSolicitudNueva."', '1', '".$usuario->getUsuarioId()."', CURDATE(), now());";
		$query->setQuery($sqlProceso);
		$rs = $query->ejectTrans(1);
		if(!$rs){			
			$errorEnTrasaccion = 1;			
			$query->rollbackTrans();
			echo "<br>ERROR EN UPDATE:</br> ";	
			$msg['error'] = 1;						
		}

		$sqlStatus = "INSERT INTO `dbcontratosglobalesstatus` (`idcontratoglobalstatus`, `refcontratoglobal`, `refstatuscontratoglobal`, `refusuario`, `fecha`, `hora`) ";
		$sqlStatus .= " VALUES (NULL, '".$idSolicitudNueva."', '1', '".$usuario->getUsuarioId()."', CURDATE(), now());";
		$query->setQuery($sqlStatus);
		$rs = $query->ejectTrans(1);
		if(!$rs){			
			$errorEnTrasaccion = 1;
			$query->rollbackTrans();
			echo "<br>ERROR EN UPDATE:</br> ";	
			echo "se ejetuta rollback";			
			$msg['error'] = 1;						
		}

 
			
		if(!$errorEnTrasaccion){
			// ningun error en los queries
			$query->commitTrans();
		}	

		// aqui se valida la respuesta de PPE y los datos de la lista PLD
		$msgPPE = $this->validaPPE($_POST['cargopublico'] , $_POST['cargopublicofamiliar'] , $idSolicitudNueva, $usuario->getUsuarioId());
		$msgPLD = $this->validaPLD($_POST['nombre'] , $_POST['paterno'],  $_POST['materno'], $idSolicitudNueva, $usuario->getUsuarioId());
		$this->buscaListaPrevencion($idSolicitudNueva, $_POST['nombre'], $_POST['paterno'], $_POST['materno'], $_POST['curp'], $_POST['rfc'], $usuario->getUsuarioId());
		if($msgPPE || $msgPLD){
			$msg['error'] = 1;
		}
		
		if(!empty($vigenciaINE) &&  $vigenciaINE != '0000-00-00'){
			$this->validaVigenciaINE( $vigenciaINE, $_POST['usuario_id'], $idSolicitudNueva);
		}		
		return 	json_encode($msg);
		//return $errorEnTrasaccion;
	}


	public function mailOfac($alertaOfac, $alertaCnbv, $alertaPpe, $nombreCliente, $nombreSDN , $idContratoGlobal, $numeroLista, $usuarioId){
		$query = new Query();
		$serviciosUsuarios = new ServiciosUsuarios();
		$x_mensaje = "";
		$tiposms = "";
		$mail1 ='zuoran_17@hotmail.com';
		$mail2 = 'zuoran_17@hotmail.com';
		//$mail1 ='zuoran_17@hotmail.com'; al oficial 
		//$mail2 = 'zuoran_17@hotmail.com'; a luzmí

		if($alertaOfac){
			$mensajeMailOFAC = "SE REGISTRO UNA SOLICITUD DONDE SE INDICO PERSONA DE LA LISTA OFAC CON EL CLIENTE ".$nombreCliente."\n \n EL DÍA "	.date("Y-m-d")." POR FAVOR REVISE LA SOLITUD Y DIRIJASE AL LISTADO DE OFAC PARA CONFIRMAR LA INFORMACION \n \n DESPUES REALICE EL REPORTE DE OPERACION INUSUAL SI ES NECESARIO, ENT_NUM =  ".$numeroLista." NOMNBRE => ".$nombreSDN;
			$sSqlInsert = "	INSERT INTO `reporte_cnbv` (`reporte_cnbv_id`, `refcontratoglobal`, `tipo_reporte_id`, `descripcion_operacion` , `razon_reporte`, 	`status_datos`, `nombre`) VALUES (NULL, '".$idContratoGlobal."', '2','Reporte de 24 horas == AQUI LA DESCRIPCION DE LA OPERACION ==','El nombre del cliente fue encontrado en la lista OFAC con el ENT_NUM = ".$numeroLista."', '1', '".$nombreSDN."'  )";
			$query->setQuery($sSqlInsert);
			$query->eject();
			$tituloMail = '== SE HA REGISTRADO UNA OPERACIÓN INUSUAL DE 24 HRS ==';
			$serviciosUsuarios->enviarEmail($mail1,utf8_decode($tituloMail),utf8_decode($mensajeMailOFAC));
			$serviciosUsuarios->enviarEmail($mail2,utf8_decode($tituloMail),utf8_decode($mensajeMailOFAC));
			$this->InsertaStatusSolicitud($idContratoGlobal, 10, '', $usuarioId);
		}
		if($alertaCnbv){
			$mensajeCNBV = "SE REGISTRO UNA SOLICITUD DONDE SE INDICO PERSONA EN LA LISTA LPB CON EL CLIENTE ".$nombreCliente."\n \n EL DÍA "	.date("Y-m-d")." POR FAVOR REVISE LA SOLITUD Y DIRIJASE A LA LISTA NEGRA DE LA CNBV PARA CONFIRMAR LA INFORMACION ".$nombreCliente."\n \n DESPUES REALICE EL REPORTE DE OPERACION INUSUAL SI ES NECESARIO";
			$sSqlInsert = "	INSERT INTO `reporte_cnbv` (`reporte_cnbv_id`, `refcontratoglobal`,  `tipo_reporte_id`, `descripcion_operacion` , `razon_reporte`, 	`status_datos`, `nombre`) VALUES (NULL, '".$idContratoGlobal."', '2','Reporte de 24 horas == AQUI LA DESCRIPCION DE LA OPERACION ==','El nombre del cliente fue encontrado en la lista negra de la CNBV ".$numeroLista."', '1', '".$nombreSDN."'  )";
			$query->setQuery($sSqlInsert);
			$query->eject();
			$tituloMail = '== SE HA REGISTRADO UNA OPERACIÓN INUSUAL DE 24 HRS ==';
			$serviciosUsuarios->enviarEmail($mail1,utf8_decode($tituloMail),utf8_decode($mensajeCNBV));
			$serviciosUsuarios->enviarEmail($mail2,utf8_decode($tituloMail),utf8_decode($mensajeCNBV));
			$this->InsertaStatusSolicitud($idContratoGlobal, 10, '', $usuarioId);
		}


	}


	private function validaPPE($cargoPublico, $familiarConCargoPublico,$idContratoGlobal, $usuarioId){		
		$query = new Query();
		$msg = "";
		$error = '';
		if($cargoPublico =='1' || $familiarConCargoPublico == '1' ){
			//cambiamos el status de la solicitud a rechazada x PPE
			$sqlStatus = "INSERT INTO `dbcontratosglobalesstatus` (`idcontratoglobalstatus`, `refcontratoglobal`, `refstatuscontratoglobal`, `refrechazocausa`, `refusuario`, `fecha`, `hora`) ";
			$sqlStatus .= " VALUES (NULL, '".$idContratoGlobal."', '4', '1', '".$usuarioId."', CURDATE(), now());";
			$query->setQuery($sqlStatus);
			$rs = $query->eject(1);
			if(!$rs){
				echo "Error al cambiar status de solictu a PPE"	;		
				$error= 1;				
			}

		}
		return $error;
	}

	private function buscaListaPrevencion($idContratoGlobal, $nombre, $paterno, $materno, $curp, $rfc, $usuarioId){

		$query = new Query();
		$nombreW = '';
		$apellidoW ='';
		$identificacionW = '';
		$nombreW = trim($nombre);
		$apellidoW = (!empty($paterno) && !empty($materno))?trim($paterno." ".$materno):trim($paterno);
		$identificacionW = (!empty($curp) && !empty($rfc))?trim($curp)."|".trim($rfc):trim($curp);
		#$webservice =  new WebServicePrevencionLavado($nombreW,$apellidoW,$identificacionW);
		#$resultado =  $webservice->getCadena();

		$Usuario = 'anal2';
		$Password = '7D434594';
		$url = 'https://www.prevenciondelavado.com/listas/api/busqueda';
		$ch = curl_init($url);
        $data = array(
            'Usuario' => $Usuario,
            'Password' => $Password,
            'Apellido' => $apellidoW,
            'Nombre' => $nombreW,
            'Identificacion' => $identificacionW,
            'Incluye_SAT' => 'S'           
        );

        //attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        //set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        //return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute the POST request
        $result = curl_exec($ch);
       # echo    $result;

        $arregloResultado = array();
        $arregloResultado = json_decode($result);
        $cadena = '';
        if(count($arregloResultado)>0){
	        foreach ($arregloResultado as $key => $value) {        
	        	$cadena .= "<table>";
	         	foreach ($value as $clave => $valor){
	           		$cadena .= "<tr>";
	            	$cadena .= "<th>".$clave ."<th><td>".utf8_decode($valor)."<td>";
	             	$cadena .= "</tr>";
	        	}
	         	$cadena .= "<table>";
	        }
         }
        curl_close($ch);
        $resultado = $cadena;
		$bloquea = ($resultado != '')?true:false;

		$resultado = ($resultado =='')?'<p><center>NO SE ENCONTRÓ COINCIDENCIA</center></p>':'<p><p>'.$resultado.'</p></p>';

		$direcotioPPE = $query->selectCampo('nombre_archivo', 'tbdocumento', 'iddocumento = 26');
		$directorioPricipal1 = "../upload/".$idContratoGlobal."/".$direcotioPPE."/";		
		$directorioPricipal1db = "upload/".$idContratoGlobal."/".$direcotioPPE."/";
		$carpeta1 ="../upload/".$idContratoGlobal."/".$direcotioPPE."/";

		if(!file_exists($carpeta1) ){
			if(!file_exists($carpeta1))
			 mkdir($carpeta1, 0777, true);		
		} 

		if(!file_exists($carpeta1)){
		   	$error .= "Error al crear la carpeta destino";			      	
		   }else{
			   	 // generamos el PDF		   	
			   	$diaBusqueda = date("Y-m-d");
			   	$servicios = new Servicios();
			   	$fecha_busqueda = $servicios->obtenerFechaEnLetra(date("Y-m-d"));
			   	
			    
			    $encabezado1 ='Ciudad de México. '.$fecha_busqueda ;		   
			    #$respuesta1 = "<b><p><center>Consulta a <a>www.prevenciondelavado.com</a> </center><p></b><p>";			   
				$respuesta1  .= $resultado;
				$pdf = new PDF_HTML();
				$pdf->AliasNbPages();
				$pdf->SetHeaderArbol(utf8_decode($encabezado1));
				$pdf->AddPage();
				$pdf->SetFont('Times','B',12);	
				$pdf->Ln(20);
				$pdf->setX(67);
				$pdf->Cell(20,10,'Consulta a ');
				$pdf->SetTextColor(0, 0, 255);
				$pdf->Cell(70,10,'www.prevenciondelavado.com',0,0,'L',0,'https://www.prevenciondelavado.com/portal/mexico/default.aspx')	;	
				$pdf->SetTextColor(0, 0, 0);	
				$pdf->WriteHTML(utf8_decode($respuesta1));
				$archivoPPE = $idContratoGlobal."_".$direcotioPPE.".".time().".pdf";
				$nombreComprobante = $directorioPricipal1.$archivoPPE;
		 		$pdf->Output($nombreComprobante,'F'); 		

	 			// se insertan los registros en docuemntos para que se puedan consultar los archivos desde la parte de administracion
	 			$serviciosReferencias 	= new ServiciosReferencias();
				$resEliminar = $serviciosReferencias->eliminarDocumentacionPorContratoGlobalDocumentacion($idContratoGlobal,26);
				$resInsertar = $serviciosReferencias->insertarDocumentacionContratoGlobal($idContratoGlobal,26,$archivoPPE, '5',$directorioPricipal1db, $usuarioId);
			}

		if($bloquea){
			$sqlStatus = "INSERT INTO `dbcontratosglobalesstatus` (`idcontratoglobalstatus`, `refcontratoglobal`, `refstatuscontratoglobal`, `refrechazocausa`, `refusuario`, `fecha`, `hora`) ";
			$sqlStatus .= " VALUES (NULL, '".$idContratoGlobal."', '4', '17', '".$usuarioId."', CURDATE(), now());";
			$query->setQuery($sqlStatus);
			$rs = $query->eject(1);
			if(!$rs){
				echo "Error al cambiar status de solictu a PPE"	;		
				$error= 1;				
			}
		}
		



	}
	private function validaPLD($nombre, $paterno, $materno, $idContratoGlobal, $usuarioId){
		$esPLD = false;
		$query = new Query();
		$error ='';
		$nombreSDN = '';
		$numeroLista = '';
		// aqui se hace la busqueda en la lista negra
		################################# buscamos en la lista OFAC #######################################
		$nombre_OFAC = $paterno." ".$materno.", ".$nombre; // formato de la lista RUELAS AVILA, Jose Luis
		if($materno =='' || empty($materno))
		$nombre_OFAC = $paterno.", ".$nombre; // formato de la lista RUELAS AVILA, Jose Luis

		$nombre_OFAC = trim($nombre_OFAC);
		$SqlOFAC = "SELECT * FROM `csv_sdn` WHERE `sdn_name` LIKE  _utf8'%".$nombre_OFAC."%' COLLATE utf8_general_ci";
		$query->setQuery($SqlOFAC);
		$rsOFAC = $query->eject();
		$objOFAC = $query->fetchObject($rsOFAC);	
		if(!$rsOFAC){
			echo "Error buscar OFAC";			
			$error= 1;				
			}	
		$estaEnListaNegra = $query->numRows($rsOFAC);
		if($estaEnListaNegra){
			$nombreSDN =$objOFAC->sdn_name;
			$numeroLista =$objOFAC->ent_num;
		}

		
		$estaEnListaCNBV = '';
		$nombre_CNBV = $nombre." ".$paterno." ".$materno; 
		$nombreCliente = $nombre." ".$paterno." ".$materno; 
		if($materno =='' || empty($materno))
		$nombre_CNBV = $nombre." ".$paterno;

		$nombre_CNBV = trim($nombre_CNBV);
		$SqlCNBV = "SELECT * FROM `csv_lista_lpb` WHERE `nombre_completo` LIKE _utf8 '%".$nombre_CNBV."%' COLLATE utf8_general_ci";
		$query->setQuery($SqlCNBV);
		$rsCNBV = $query->eject();
		$objCNBV = $query->fetchObject($rsCNBV);
		if(!$rsCNBV){
				echo "Error al cambiar status de solictu a CNBV";			
				$error= 1;				
			}	

		$estaEnListaCNBV = $query->numRows($rsCNBV);
		if($estaEnListaCNBV){
			$nombreSDN =$objCNBV->nombre_completo;	
			$numeroLista =$objCNBV->csv_lista_lpb_id;			
		}	

		$nombre_CNBV = $paterno." ".$materno." ".$nombre; // formato de la lista RUELAS AVILA, Jose Luis
		if($materno =='' || empty($materno))
		$nombre_CNBV = $paterno." ".$nombre;

		$nombre_CNBV = trim($nombre_CNBV);
		$SqlCNBV = "SELECT * FROM `csv_lista_lpb` WHERE `nombre_completo` LIKE _utf8 '%".$nombre_CNBV."%' COLLATE utf8_general_ci";

		#echo $SqlCNBV;
		$query->setQuery($SqlCNBV);
		$rsCNBV = $query->eject();
		$objCNBV2 = $query->fetchObject($rsCNBV);

		if(!$rsCNBV){
			echo "Error al cambiar status de solictu a CNBV 2";			
			$error= 1;				
		}
		$estaEnListaCNBV2 = $query->numRows($rsCNBV);
		if($estaEnListaCNBV2){
			$nombreSDN =$objCNBV2->nombre_completo;	
			$numeroLista =$objCNBV2->csv_lista_lpb_id;		
		}	
		$causa = 0;
		$alertaOfac = '';
		$alertaCnbv = '';

		if($estaEnListaNegra || $estaEnListaCNBV || $estaEnListaCNBV2){
			//cambiamos el status de la solicitud a rechazada x PPE

			#echo "lista ngra".$estaEnListaNegra." CNBV ".$estaEnListaCNBV." cnbv ".$estaEnListaCNBV2;
			$causa = ($estaEnListaCNBV || $estaEnListaCNBV2)?2:3;
			#$sqlStatus = "INSERT INTO `dbcontratosglobalesstatus` (`idcontratoglobalstatus`, `refcontratoglobal`, `refstatuscontratoglobal`,`refrechazocausa`, `refusuario`, `fecha`, `hora`) ";
			#$sqlStatus .= " VALUES (NULL, '".$idContratoGlobal."', '4', '".$causa."', '".$usuarioId."', CURDATE(), now());";
			#$query->setQuery($sqlStatus);
			#$rs = $query->eject(1);
			#if(!$rs){
			#	echo "Error al cambiar status de solictu a PPE"	;		
			#	$error= 1;				
			#}
			$alertaOfac = ($estaEnListaNegra)?1:0;
			$alertaCnbv = ($estaEnListaCNBV || $estaEnListaCNBV2)?1:0;
			$alertaPpe  = 0;
		
			$numeroLista = 
			$this->mailOfac($alertaOfac, $alertaCnbv, $alertaPpe, $nombreCliente, $nombreSDN , $idContratoGlobal, $numeroLista, $usuarioId);
		}
		$causa0 = ($causa==0)?1:0;
		$this->generaPDFComprobante( $causa0 ,$alertaCnbv,$alertaOfac, $nombreCliente, $idContratoGlobal, $usuarioId);
		   

		return $error;
	}

	private function generaPDFComprobante($causa0, $causa2, $causa3,$nombreCliente, $idContratoGlobal, $usuarioId){
		$query = new Query();
		$fecha = date('Y-m-d-H-i-s');	

		#echo "causa".$causa;
		//	/home/u776896097/domains/financieracrea.com/public_html		
		$direcotioOFAC = $query->selectCampo('nombre_archivo', 'tbdocumento', 'iddocumento = 24');
		$direcotioCNBV = $query->selectCampo('nombre_archivo', 'tbdocumento', 'iddocumento = 25');
		$directorioPricipal1 = "../upload/".$idContratoGlobal."/".$direcotioCNBV."/";
		$directorioPricipal2 = "../upload/".$idContratoGlobal."/".$direcotioOFAC."/";
		$directorioPricipal1db = "upload/".$idContratoGlobal."/".$direcotioCNBV."/";
		$directorioPricipal2db = "upload/".$idContratoGlobal."/".$direcotioOFAC."/";

		#echo $directorioPricipal2;

		$carpeta1 ="../upload/".$idContratoGlobal."/".$direcotioOFAC."/";
		$carpeta2 ="../upload/".$idContratoGlobal."/".$direcotioCNBV."/";

		if(!file_exists($carpeta1) || !file_exists($carpeta2)){
			if(!file_exists($carpeta1))
			 mkdir($carpeta1, 0777, true);
			if(!file_exists($carpeta2))
			mkdir($carpeta2, 0777, true);
		} 

		$servicios = new Servicios();
		$fecha_busqueda = $servicios->obtenerFechaEnLetra(date("Y-m-d"));


		if(!file_exists($carpeta1) || !file_exists($carpeta2) ){
		   	$error .= "Error al crear la carpeta destino";			      	
		   }else{
		   	 // generamos el PDF		   	
		   	$diaBusqueda = date("Y-m-d");
		   	$ecabezado1 ='Consulta a lista de Comision Nacional Bancaria y de Valores (CNBV)'.$fecha_busqueda ;
		    $ecabezado2 =' Consulta a la lista de la Oficina de Control de Bienes Extranjeros (OFAC)'.$fecha_busqueda ;
		    $ecabezado1 ='Ciudad de México. '.$fecha_busqueda ;
		    $ecabezado2 ='Ciudad de México. '.$fecha_busqueda ;
		    $respuesta1 = "<span></span><b><p><center>Consulta a lista de la Comision Nacional Bancaria y de Valores (CNBV) </center><p></b><p>";
		    $respuesta2 = "<span></span><b><p><center>Consulta a la lista de la Oficina de Control de Bienes Extranjeros (OFAC)</center><p></b><p>";
		    #$causa = 3;
			   	if($causa0){
			   		// el cliente no se encontro  en las listas negras
			   		
			   		$respuesta1 .= "<span></span><p>".$nombreCliente ." Se busco en la lista de la CNBV el día " .$fecha_busqueda. " y <b> NO se encontro coincidencia en el listado</b>";		   		
			   		$respuesta2 .= "<span></span><p>".$nombreCliente ." Se busco en la lista de la OFAC el día " .$fecha_busqueda. " y <b> NO se encontro coincidencia en el listado</b>";
			   	}
			   	if($causa2 && !$causa3){
			   		// el cliente se encontro en la lista de la CNBV
			   		$respuesta1 .= "<span></span><p>".$nombreCliente ." Se buscó en la lista de la CNBV el día " .$fecha_busqueda. " y <b> se encontró coincidencia en el listado</b>, es un cliente con riesgo PLD";
			   		$respuesta2 .= "<span></span><p>".$nombreCliente ." Se buscó en la lista de la OFAC el día " .$fecha_busqueda. " y <b> NO se encontró coincidencia en el listado</b>";		

			   	}
			   	 if($causa3 && !$causa2){
			   		// el cliente se encontro en la lista OFAC
			   		$respuesta2 .= "<span></span><p>".$nombreCliente ." Se buscó en la lista de la OFAC el día " .$fecha_busqueda. " y <b> se encontró coincidencia en el listado</b>, es cliente con riesgo PLD";
			   		$respuesta1 .= "<span></span><p>".$nombreCliente ." Se buscó en la lista de la CNBV el día " .$fecha_busqueda. " y <b> NO se encontró coincidencia en el listado</b>";
			   	}

			   	if($causa2 && $causa3 ){
			   		$respuesta1 .= "<span></span><p>".$nombreCliente ." Se buscó en la lista de la CNBV el día " .$fecha_busqueda. " y <b> se encontró coincidencia en el listado</b>, es un cliente con riesgo PLD";
			   		$respuesta2 .= "<span></span><p>".$nombreCliente ." Se buscó en la lista de la OFAC el día " .$fecha_busqueda. " y <b> se encontró coincidencia en el listado</b>, es cliente con riesgo PLD";
			   	}
			
			

			   	$pdf = new PDF_HTML();
				$pdf->AliasNbPages();
				$pdf->SetHeaderArbol(utf8_decode($ecabezado1));
				$pdf->AddPage();
				$pdf->SetFont('arial','',12);			
				$pdf->WriteHTML(utf8_decode($respuesta1));
				$archivoCNBV = $idContratoGlobal."_".$direcotioCNBV.".".time().".pdf";
				$nombreComprobante = $directorioPricipal1.$archivoCNBV;
	 			$pdf->Output($nombreComprobante,'F');
	 			$pdf = new PDF_HTML();
				$pdf->AliasNbPages();
				$pdf->SetHeaderArbol(utf8_decode($ecabezado2));
				$pdf->AddPage();
				$pdf->SetFont('arial','',12);				
				$pdf->WriteHTML(utf8_decode($respuesta2));
				$archivoOFAC = $idContratoGlobal."_".$direcotioOFAC.".".time().".pdf";
				$nombreComprobante = $directorioPricipal2.$archivoOFAC;
	 			$pdf->Output($nombreComprobante,'F');

	 			// se insertan los registros en docuemntos para que se puedan consultar los archivos desde la parte de administracion
	 			$serviciosReferencias 	= new ServiciosReferencias();
	 			

				$resEliminar = $serviciosReferencias->eliminarDocumentacionPorContratoGlobalDocumentacion($idContratoGlobal,24);
				$resInsertar = $serviciosReferencias->insertarDocumentacionContratoGlobal($idContratoGlobal,24,$archivoOFAC, '5',$directorioPricipal2db, $usuarioId);
				$resEliminar = $serviciosReferencias->eliminarDocumentacionPorContratoGlobalDocumentacion($idContratoGlobal,25);
				$resInsertar = $serviciosReferencias->insertarDocumentacionContratoGlobal($idContratoGlobal,25,$archivoCNBV, '5',$directorioPricipal1db, $usuarioId);


		}

		
	}


	function editarSolicitudGlobal(){
		
		$msg = array();
		$msg['error'] = '';		
		$query = new Query();
		$usuario = new Usuario();
		$usuarioId = $usuario->getUsuarioId();
		$usuarioRolId = $usuario->getRolId();
		$errorEnTrasaccion = '';
		$tablaSol = 'dbcontratosglobales';
		$tipoContrato = $_POST["reftipocontratoglobal"];
		$valuesSolGlobal = $this->traercamposValoresPost($tablaSol);
		$idContratoGlobal = isset($_POST['idcontratoglobal'])?$_POST['idcontratoglobal']:$this->idContratoGlobal;
		$wh = " idcontratoglobal = ".$idContratoGlobal;			
		$usuarioContratoGlobal = $query->selectCampo('usuario_id', $tablaSol, $wh);
		
		// iniciamos la transaccion		
		$query->beginTrans();
		$whUpdate = " idcontratoglobal = ".$idContratoGlobal;			
		$query->update($tablaSol,$valuesSolGlobal, $whUpdate);
		$rs = $query->ejectTrans();		
		if(!$rs){
			$errorEnTrasaccion = 1; 
			$query->rollbackTrans();			
			echo "<br>ERROR EN SOLICITUD:</br> ".$rs;
			$msg['error'] = 1;				
		}			
		if(!$errorEnTrasaccion && $usuarioRolId != 8){
			// ningun error en los queries
			$causa = isset($_POST['cgs_refrechazocausa'])?$_POST['cgs_refrechazocausa']:0; 
			$status = isset($_POST['cgs_refstatuscontratoglobal'])?$_POST['cgs_refstatuscontratoglobal']:''; 
			$query->commitTrans();
 			$this->InsertaStatusSolicitud($idContratoGlobal, $status, $causa, $usuarioId);
 			$this->llamadaSeguimiento($idContratoGlobal );

		}	

		$msgPLD = $this->validaPLD($_POST['nombre'] , $_POST['paterno'],  $_POST['materno'], $idContratoGlobal, $usuario->getUsuarioId());

		// si la solicitud se aprueba debe guardar la referencia de la UDI

		$arrayAprobados = array(3,5,12); //aprobado, empleador; autorizado, firmas; aprobado,pendiente entrevista

		if(in_array($_POST['cgs_refstatuscontratoglobal'], $arrayAprobados) &&( $_POST['refudi'] =='' || empty($_POST['refudi']) || $_POST['refudi'] ==0 )){
		
				$sqlUDIS = "SELECT * FROM tbudi  ORDER BY `idudi` DESC limit 0,1";			
				$query->setQuery($sqlUDIS);
				$rsUDI = $query->eject();
				$objUDI =  $query->fetchObject($rsUDI);
				$refUDI = $objUDI->idudi;
				$sqlUpdateUdi = "UPDATE ".$tablaSol." SET refudi =".$refUDI." WHERE ".$whUpdate;				
				$query->setQuery($sqlUpdateUdi);	
				$query->eject();
				#echo 	$sqlUpdateUdi;
				// se debe enviar el mail a la empresa afiliada

			}

		//generamos docto riesgo PLD
		$arrayCalculoPLD = array(3,4,5,12);	//aprobado, empleador; rechazado; autorizado, firmas; aprobado,pendiente entrevista
			if(in_array($_POST['cgs_refstatuscontratoglobal'], $arrayCalculoPLD)){
			// se genera el PDF de nivel de riesgo y se agrega a la galeria de administracion	
			$riesgoPLD = new RiesgoPLD($idContratoGlobal);			
		}
	

		// si se indica en la solictud enviar la autorizacion para circulo de credito
		$autorizacionCirculoCredito = ($_POST['refcirculocredito'] == 1)?true:false;
		if($autorizacionCirculoCredito)	{
			$this->enviarAutorizacionCirculoCredito($idContratoGlobal,$usuarioId);
			
		}

		


				
	

		#aqui se valida el RIESGO PLD  del cliente
		$arrayVerficaPLD = array(3,5,12); // Aprobado, pendiente Empleador;Autorizado, pendientes firmas; Aprobado, pendiente entrevista
		$cambioEnStatus = false;
		// verificamos que esta solicitud no tenga un status de  10,11 "bloquedo;rechazado, por PLD", si existe ese status significa que el sistema lo rechazo y Oficial de cumplimiento lo reviso y le cambio el status en cuyo caso no debe ser revisado nuevamente por sistema
		$condStatus = 'refcontratoglobal = '.$idContratoGlobal. " AND refstatuscontratoglobal IN (10,11)";
		$idStausrechazadoPLD = $query->selectCampo('idcontratoglobalstatus','dbcontratosglobalesstatus',$condStatus);

		$statusContratoGlobal = $_POST['cgs_refstatuscontratoglobal'];
		if(in_array($_POST['cgs_refstatuscontratoglobal'] ,  $arrayVerficaPLD ) && empty($idStausrechazadoPLD)){
			$arraySantoAdelanto = array(1,2);
			$arrayTradiconal = array(3,4);		

			// buscamos el valor de las UDIs			
				$sqlUDIS = "SELECT * FROM tbudi  ORDER BY `idudi` DESC limit 0,1";			
				$query->setQuery($sqlUDIS);
				$rsUDI = $query->eject();
				$objUDI =  $query->fetchObject($rsUDI);
				$refUDI = $objUDI->idudi;
				$valorUDI = $objUDI->descripcion;
				$sqlUpdateUdi = "UPDATE ".$tablaSol." SET refudi =".$refUDI." WHERE ".$whUpdate;				
				$query->setQuery($sqlUpdateUdi);	
				$query->eject();
				$tresMilUdis = $valorUDI * 3000;
				$montoMayorUdies = ($_POST['montootorgamiento'] > $tresMilUdis);				
				if($montoMayorUdies){
					$sqlUpdateUdim = " UPDATE ".$tablaSol." SET udismayor = 1 WHERE ".$whUpdate;
				}else{
					$sqlUpdateUdim = " UPDATE ".$tablaSol." SET udismayor = 0 WHERE ".$whUpdate;
				}
				$query->setQuery($sqlUpdateUdim);	
				$query->eject();
				$condicion = 'refcontratoglobal  = '.$idContratoGlobal;
				
				$riesgoPLD = strtoupper($query->selectCampo('descripcion', 'dbriesgoimpactoclientes', $condicion ));
				#echo "Riesgo =>".$riesgoPLD;
				$nuevoStatus = 0;
				$causaNueva = '';
				//11 rechazo por PLD
				//12 pendiente entrevista
				// verificamos si es santoAdelanto
				if(in_array($_POST['reftipocontratoglobal'], $arraySantoAdelanto)){
					if($riesgoPLD== 'MEDIO' ||  $riesgoPLD== 'ALTO'){
						// si es santo adelanto riego medio cambia a Rechazo por PLD
						$nuevoStatus = 11;
						$causaNueva = 'Cálculo de riesgo PLD '.$riesgoPLD;
					}
				}else{
					// es un tradicional o de compra de deuda con monto mayor a 3000 UDIs
					if($montoMayorUdies){
						if($riesgoPLD== 'ALTO'){
							// mayor a 3000 udies ALTO se cambia a Rechazo por PLD
							$nuevoStatus = 11;
							$causaNueva = 'Cálculo de riesgo PLD '.$riesgoPLD;
						}else if($riesgoPLD== 'MEDIO' ||  $riesgoPLD== 'BAJO'){
							// mayor a 3000 udies medio o bajo se cambia a Autorizado Pendiente entrevista
							$nuevoStatus = 12;
						}


					}else{
						if($riesgoPLD== 'ALTO'){
							// MENOR A a 3000 udies ALTO se cambia a Rechazo por PLD
							$nuevoStatus = 11;
							$causaNueva = 'Cálculo de riesgo PLD '.$riesgoPLD;

						}else if($riesgoPLD== 'MEDIO'){
						// MENOR a 3000 udies medio o bajo se cambia a Autorizado Pendiente entrevista
							$nuevoStatus = 12;
						}
					}

				} // else santo adelanto

				if($nuevoStatus> 0){
					// se actualiza el status de solicitud
					$this->InsertaStatusSolicitud($idContratoGlobal, $nuevoStatus, $causaNueva, $usuarioId);
					$cambioEnStatus = true;
					$statusContratoGlobal = $nuevoStatus;
				}

		} // valida riesgo PLD


		$arrayStatusGeneraPDF = array(3,4,5,8,11);
		// aprobado pendiente empleador,Rechazado, Autorizado Pendiente firmas,Abandonado,	 Rechazado por PLD, 	
		if(in_array($statusContratoGlobal , $arrayStatusGeneraPDF )){
			// se genera el PDF con la galeria
			$name  = $_POST['nombre'] ." ". $_POST['paterno']." ".  $_POST['materno'];
			$pdfGaleria = new ServiciosPDFConctratoGlobal($idContratoGlobal, $name, $usuarioContratoGlobal);
			$texti = $pdfGaleria->generarPDFGlobal();		
			$query->commitTrans();	
		}


		//Autorizado, pendientes firmas
		if($statusContratoGlobal  == 5){
			$this->autorizadoPendienteFirmas($idContratoGlobal, $_POST['reftipocontratoglobal']);
			$query->commitTrans();

		}
		
		return 	json_encode($msg);
		//return $errorEnTrasaccion;
	}

	public function autorizadoPendienteFirmas($idContratoGlobal, $tipoContrato){
		$query = new Query();
		// se debe valida que tipo de contrato es para saber que accio ejecutar
		$arraySantAdelanto =array(1,2,5);
		$arrayCreditoTardicional = array(3);
		$arrayCreditoCompraDeuda = array(4);
		if(in_array($tipoContrato, $arraySantAdelanto)){
			// se manda mail al usuario con NIPS y con documentos adjuntos

			$this->enviarContratoGlobalParaFirma($idContratoGlobal, $tipoContrato);



		}else if(in_array($tipoContrato, $arrayCreditoTardicional)){
			$this->enviarContratoTradicionalParaFirma($idContratoGlobal);

		}else if(in_array($tipoContrato, $arrayCreditoCompraDeuda)){
			$this->enviarContratoDeudaParaFirma($idContratoGlobal);

		}

	}



	public function enviarContratoGlobalParaFirma($idContratoGlobal, $tipoContrato){
		$query = new Query();
		$funcionesUsuario = new ServiciosUsuarios();
		$token = new Token();
		$condicion = 'idcontratoglobal = '.$idContratoGlobal;	
		$usuarioContrato =  $query->selectCampo('usuario_id', 'dbcontratosglobales', $condicion  );		
		// primero verificamos que la solicitud aun no se haya enviado

		$sqlSolicitudFirmaContrato = "SELECT * FROM dbfirmascontratosglobales WHERE refcontratoglobal = $idContratoGlobal ";
		$query->setQuery($sqlSolicitudFirmaContrato);
		$resSolFC = $query->eject();
		$existe =  ($query->numRows($resSolFC)> 0);

		if(!$existe){

			// se generan los contratos en PDF con los datos del cliente, se integran al expediente, se eliminan los individuales y se inserta el registro para guardar la referencia
			#echo "Entra a la funcion para generar expediente";
			$contratosCG = new  ContratosCreditos($idContratoGlobal, $tipoContrato);
			$contratosCG->generaContratos();
	 		$integracion = $contratosCG->integraDocumentos();
		 	if($integracion){
		 		$contratosCG->eliminaDoctosIndividuales();
		 		$contratosCG->insertaExpediente();
		 	}
			
			// se generan los tokens

		 	$reftipoToken = 2; //Firma de documentos
		 	$reftoken = $token->generarToken($idContratoGlobal, $reftipoToken);
		 	$query->commitTrans();
		 	$token->getTokenPorId($reftoken);
		 	
		 	//var_dump($token);	 	
		 	$sqlInsertSolFirmas = " INSERT INTO `dbfirmascontratosglobales` (`idfirmacontratoglobal`, `refcontratoglobal`, `reftoken`, `fecha`, `hora`, `refusuario`, `status`) VALUES (NULL, '".$idContratoGlobal."', '".$reftoken."', CURDATE(), NOW(), '".$refUser."', '1');";
		 	$query->setQuery($sqlInsertSolFirmas);
		 	$refFirmas = $query->eject(1);
		 	$query->commitTrans();		 	
		 	// se envia el correo electrónico al cliente

		 	// formamos la URL
            $url = trim('idCG='.$idContratoGlobal.'&idA='.$refAutorizacion.'&token='.$reftoken.'&idU='.$usuarioContrato);
		 	$url = urlencode(base64_encode($url));
		 	$cuerpoMail .= '<h2 class=\"p3\"> Firmar contrato</h2>';
    		$servidor = $_SERVER['SERVER_NAME'];
    		$liga_servidor = ($servidor=='localhost')?SERVIDOR_LOCAL:SERVIDOR;	
			$cuerpoMail .= '<h3><small><p>Por favor ingrese al siguiente <a href="'.$liga_servidor.'dashboard/contrato/cliente/firmaDigitalDocumentos.php?fddid='.$url.'" target="_blank"> enlace </a> para firmar su contrato. </p> </small></h3><p>';
			$cuerpoMail .= "<center>NIP:<b>".$token->getToken()."</b></center><p> ";
			$cuerpoMail .='<p> No responda este mensaje, el remitente es una dirección de notificación</p>';
	   		$cuerpoMail .= '<br><img width="393" height="131"  src="http://financieracrea.com/esfdesarrollo/images/firmaCREA24.jpg" alt="Financiera CREA" >';
	   		   		
            $emailUsuario = $funcionesUsuario->traerEmailUsuario($usuarioContrato);            
            $emailUsuario = 'zuoran_17@hotmail.com';
            $titulo = 'Firma de contrato';
			//$funcionesUsuario->enviarEmail($emailUsuario,utf8_decode($titulo),utf8_decode($cuerpoMail));

			$phpMailer = new PHPMailer();
			// adjuntamos el documento
			#$nombreDelDocumento = "../upload/".$idContratoGlobal."/Contrato.pdf";

			// ya no se enviara el documento por correo; se debe ver en el portal y solo para verlo sin opcion de descarga

			if (!file_exists($nombreDelDocumento)) {
    		echo ("El archivo $nombreDelDocumento no existe");
			}else{
			echo	"Si encontré el docto";
			}


			try {
			    $phpMailer->setFrom("consulta@financieracrea.com", "Financiera CREA"); # Correo y nombre del remitente
			    $phpMailer->addAddress($emailUsuario); # El destinatario
			    $phpMailer->Subject = utf8_decode("Firma de contrato"); # Asunto

			    $phpMailer->Body = utf8_decode($cuerpoMail); # Cuerpo en texto plano
			    $phpMailer->isHTML(true);
			    // Aquí adjunto:
			   # $phpMailer->addAttachment($nombreDelDocumento);
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

	public function enviarContratoTradicionalParaFirma($idContratoGlobal){

	}

	public function enviarContratoDeudaParaFirma($idContratoGlobal){
		
	}

	public function enviarAutorizacionCirculoCredito($idContratoGlobal, $refUser){
		$query = new Query();
		$funcionesUsuario = new ServiciosUsuarios();
		$token = new Token();

		$condicion = 'idcontratoglobal = '.$idContratoGlobal;	
	   	$usuarioContrato =  $query->selectCampo('usuario_id', 'dbcontratosglobales', $condicion  );	
		// primero verificamos que la solicitud aun no se haya enviado
		$sqlSolicitudCierculoCredito = "SELECT * FROM dbsolicitudesautorizacioncirculocredito WHERE refcontratoglobal = $idContratoGlobal ";


		$query->setQuery($sqlSolicitudCierculoCredito);
		$resSolCC = $query->eject();
		 $existe =  ($query->numRows($resSolCC)> 0);
		 if(!$existe){
		 	$reftipoToken = 1; //Autorización circulo
		 	$reftoken = $token->generarToken($idContratoGlobal, $reftipoToken);
		 	$query->commitTrans();
		 	$token->getTokenPorId($reftoken);

		 	
		 	//var_dump($token);	 	
		 	$sqlInsertSolAutorizacion = " INSERT INTO `dbsolicitudesautorizacioncirculocredito` (`idsolicitudautorizacioncirculocredito`, `refcontratoglobal`, `reftoken`, `fecha`, `hora`, `refusuario`, `status`) VALUES (NULL, '".$idContratoGlobal."', '".$reftoken."', CURDATE(), NOW(), '".$refUser."', '1');";
		 	$query->setQuery($sqlInsertSolAutorizacion);
		 	$refAutorizacion = $query->eject(1);
		 	$query->commitTrans();		 	
		 	// se envia el correo electrónico al cliente
		 	//DATOS url
		 	$url = trim('idCG='.$idContratoGlobal.'&idA='.$refAutorizacion.'&token='.$reftoken.'&idU='.$usuarioContrato);
		 	$url = urlencode(base64_encode($url));
		 	$cuerpoMail .= '<h2 class=\"p3\"> Acción requerida</h2>';
    		$servidor = $_SERVER['SERVER_NAME'];
    		$liga_servidor = ($servidor=='localhost')?SERVIDOR_LOCAL:SERVIDOR;	
			$cuerpoMail .= '<h3><small><p>Por favor ingrese al siguiente <a href="'.$liga_servidor.'dashboard/contrato/cliente/autorizarConsultaHistorial.php?achid='.$url.'" target="_blank"> enlace </a> para autorizar la consulta de su historial crediticio. </p><br> La consulta del historial crediticio en financieraCREA se realiza con el fin de confirmar los datos de identidad, no es necesario contar con un buen historial crediticio para obtener un crédito </small></h3><p>';
			$cuerpoMail .= "<center>NIP:<b>".$token->getToken()."</b></center><p> ";
			$cuerpoMail .='<p> No responda este mensaje, el remitente es una dirección de notificación</p>';
	   		$cuerpoMail .= '<br><img width="393" height="131"  src="http://financieracrea.com/esfdesarrollo/images/firmaCREA24.jpg" alt="Financiera CREA" >';	   		   		
            $emailUsuario = $funcionesUsuario->traerEmailUsuario($usuarioContrato);            
            $emailUsuario = 'zuoran_17@hotmail.com';
			$funcionesUsuario->enviarEmail($emailUsuario,'Autorizar',utf8_decode($cuerpoMail));
		 }
	}



	public function autorizarhistorialCrediticio($idContratoGlobal, $nip){
		$msg = array();		
		$idTipoToken = 1; // firma autorizacion circulo de credito
		$query = new Query();
		$toquen = new Token();
		$usuario = new Usuario();
		$idUsuario = $usuario->getUsuarioId();
		$condicionn = 'idcontratoglobal = '.$idContratoGlobal;
		$nombre = $query->selectCampo('nombre', 'dbcontratosglobales',$condicionn);
		$paterno = $query->selectCampo('paterno', 'dbcontratosglobales',$condicionn);
		$materno = $query->selectCampo('materno', 'dbcontratosglobales',$condicionn);
		$nombre_cliente = $nombre." ". $paterno." ".$materno;
		$msg = $toquen->validarToken($idTipoToken, $idContratoGlobal, $nip);
		if ($msg['tokenValido'] == 1){
			// generamos el PDF con la informacion de la autorizacion
			$query = new Query();
			$fecha = date('Y-m-d-H-i-s');
			#echo "causa".$causa;
			///home/u776896097/domains/financieracrea.com/public_html		
			$direcotioCc = 'CIRCULO_CREDITO';			
			$directorioPricipal1 = "../upload/".$idContratoGlobal."/".$direcotioCc."/";			
			$directorioPricipal1db = "upload/".$idContratoGlobal."/".$direcotioCc."/";			

			#echo $directorioPricipal2;
			$carpeta1 ="../upload/".$idContratoGlobal."/".$direcotioCc."/";	
			if(!file_exists($carpeta1) ){				
				 mkdir($carpeta1, 0777, true);			
			}
			if(!file_exists($carpeta1) ){
			   	$error .= "Error al crear la carpeta destino";			      	
			   }else{
			   	    // generamos el PDF 	
			   		$ecabezado1 ='AUTO'.$fecha_busqueda ;		   
			   		$respuesta1 = "<span></span><b><p><center> </center><p></b><p>";
					$fpdf = new PDF_HTML();
					$fpdf->AliasNbPages();
					$fpdf->SetHeaderArbol('texto header');
					$fpdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
					$fpdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
					$fpdf->AddPage();
					$fpdf->SetFont('DejaVu','B','10');
					$fpdf->SetTextColor(67,67,67);
					$fpdf->SetTextColor(255,255,255);
					$fpdf->setFillColor(192,0,0);
					$fpdf->Ln(5);
					$titulo ="AUTORIZACIÓN PARA CONSULTA A SOCIEDADES DE INFORMACIÓN CREDITICIA ";
					$fpdf->Cell(0,5,$titulo,1,1,'C',1);	
					$fpdf->SetTextColor(0,0,0);			
					$fpdf->SetFont('DejaVu','','9');
					$texto = '<p><p>Autorizo expresamente a <b>MICROFINANCIERA CRECE, S.A. DE C.V. SOFOM E.N.R</b>, para que lleve a cabo investigaciones sobre mi comportamiento crediticio en las Sociedades de Información Crediticia (SIC) que estime conveniente. Conozco la naturaleza y alcance de la información que se solicitará, del uso que se le dará y que se podrán realizar consultas periodicamente de mi historial crediticio. Consiento que esta autorización tenga una vigencia de <b>3 años</b> contando a partir de hoy, y en su caso mientras mantengamos relación jurídica. Acepto que este documento quede bajo propiedad de financieraCREA <b>y/o</b> Círculo de Crédito para efectos de control y cumplimiento del artículo 28 de la LRSIC.'	;
					$fpdf->Ln(20);
					$fpdf->MultiCellDos(0,5,'Autorizo expresamente a ');
					$fpdf->SetFont('DejaVu','B','9');
					$fpdf->MultiCellDos(0,5,'MICROFINANCIERA CRECE, S.A. DE C.V. SOFOM E.N.R ');
					$fpdf->SetFont('DejaVu','','9');
					$fpdf->MultiCellDos(0,5,' para que lleve a cabo investigaciones sobre mi comportamiento crediticio en las Sociedades de Información Crediticia (SIC) que estime conveniente. Conozco la naturaleza y alcance de la información que se solicitará, del uso que se le dará y que se podrán realizar consultas periodicamente de mi historial crediticio. Consiento que esta autorización tenga una vigencia de  ');
					$fpdf->SetFont('DejaVu','B','9');
					$fpdf->MultiCellDos(0,5,'3 años ');
					$fpdf->SetFont('DejaVu','','9');
					$fpdf->MultiCellDos(0,5,' contando a partir de hoy, y en su caso mientras mantengamos relación jurídica. Acepto que este documento quede bajo propiedad de financieraCREA ');
					$fpdf->SetFont('DejaVu','B','9');
					$fpdf->MultiCellDos(0,5,'y/o ');
					$fpdf->SetFont('DejaVu','','9');
					$fpdf->MultiCellDos(0,5,'Círculo de Crédito para efectos de control y cumplimiento del artículo 28 de la LRSIC.');

					$fpdf->Ln(20);
					$fpdf->Cell(0,6,$nip,0,0,'C');
					$fpdf->Ln(3);
					$fpdf->Cell(0,2,'_______________________________',0,0,'C')	;
					$fpdf->Ln(2);
					$fpdf->Cell(0,6,$nombre_cliente,0,0,'C')	;
					$archivoCcf = $idContratoGlobal."_".$direcotioCc.".".time().".pdf";
					$nombreComprobante = $directorioPricipal1.$archivoCcf;
		 			$fpdf->Output($nombreComprobante,'F');
		 			
		 			// guardamos registro en la base de datos

		 			$sqlInsertBuro = " INSERT INTO `dbautorizacionesburo`";
		 			$sqlInsertBuro .= " (`idautorizacionburo`, `refcontratoglobal`, `refusuario`, `fecha`, `ruta`, `documento`) ";
		 			$sqlInsertBuro .= " VALUES (NULL, $idContratoGlobal, $idUsuario, CURDATE(), '".$directorioPricipal1db."', '".$archivoCcf."')";
		 			$query->setQuery($sqlInsertBuro);
		 			$idBuro = $query->eject(1);
		 	    } 
	 	}	
		return 	json_encode($msg);
	}

	public function firmarDocumentosContratoGlobal($idContratoGlobal, $nip){
		$msg = array();		
		$idTipoToken = 2; // firma contrato global
		$query = new Query();
		$toquen = new Token();
		$usuario = new Usuario();
		$usuarioId = $usuario->getUsuarioId();
		$condicion =' idcontratoglobal ='.$idContratoGlobal;
		$nombre_cliente = $query->selectCampo('nombre', 'dbcontratosglobales', $condicion );
		$paterno_cliente = $query->selectCampo('nombre', 'dbcontratosglobales', $condicion );
		$materno_cliente = $query->selectCampo('nombre', 'dbcontratosglobales', $condicion );
		$nombre_completo = $nombre_cliente." ".$paterno_cliente." ".$materno_cliente ;
		$this->insertaProcesoContratoGlobal($idContratoGlobal, 3, $idUsuario); // descargar paquete
		$this->insertaProcesoContratoGlobal($idContratoGlobal, 4, $idUsuario); // solicitar NIP para firma
		$msg = $toquen->validarToken($idTipoToken, $idContratoGlobal, $nip);	
		if($msg["tokenValido"] == 1){
			// si el token es valido entonces se hacen los procesos restantes
			//1.- insertamos el proceso de firma de documentos
			$this->insertaProcesoContratoGlobal($idContratoGlobal, 5, $usuarioId); // firmar paquete
			// se manda un correo a OFICIAL de cumplimiento para visarle

			$cuerpoMail ='';
			$serviciosUsuarios = new ServiciosUsuarios();
			$cuerpoMail .= '<h2 class=\"p3\"> CONTRATO GLOBAL FIRMADO</h2>';    	
	   		$cuerpoMail .= '<h3><small><p> El cliente '.$nombre_completo .' firmo un contrato </p></small></h3>';
	   		$cuerpoMail .='<p> No responda este mensaje, el remitente es una dirección de notificación</p>';
		   	$cuerpoMail .= '<br><img width="393" height="131"  src="http://financieracrea.com/esfdesarrollo/images/firmaCREA24.jpg" alt="Financiera CREA" >';
			$serviciosUsuarios->enviarEmail('oficialdecumplimiento@financieracrea.com','Contrato firmado',utf8_decode($cuerpoMail));


		}

		return 	json_encode($msg);
	}

	public function aprobarContratoGlobalEmpresa(){		
		$msg = array();
		$msg['error'] = '';		
		$query = new Query();
		$usuario = new Usuario();
		$usuarioId = $usuario->getUsuarioId();
		$usuarioRolId = $usuario->getRolId();
		$errorEnTrasaccion = '';		
		$idContratoGlobal = isset($_POST['idcontratoglobal'])?$_POST['idcontratoglobal']:$this->idContratoGlobal;
		$bitacora = isset($_POST['bitacoraempleador'])?$_POST['bitacoraempleador']:'';
		$antiguedadanio = isset($_POST['antiguedadanio'])?$_POST['antiguedadanio']:'';
		$antiguedadmes = isset($_POST['antiguedadmes'])?$_POST['antiguedadmes']:'';
		if( $usuarioRolId == 21){
			// ningun error en los queries
			$causa = isset($_POST['cgs_refrechazocausa'])?$_POST['cgs_refrechazocausa']:0; 
			$status = isset($_POST['cgs_refstatuscontratoglobal'])?$_POST['cgs_refstatuscontratoglobal']:'';	
 			$this->InsertaStatusSolicitud($idContratoGlobal, $status, $causa, $usuarioId);

 			// actualizamos la bitacor del empleador
 			if($bitacora != '' || $antiguedadanio != '' || $antiguedadmes != ''  ){
 				$sqlUpdateBitacora = " UPDATE dbcontratosglobales 
 										SET bitacoraempleador = '".$bitacora."' ,
 										antiguedadanio = '".$antiguedadanio."' ,
 										antiguedadmes = '".$antiguedadmes."' 
 										WHERE idcontratoglobal =".$idContratoGlobal;
 				$query->setQuery($sqlUpdateBitacora);
 				$query->eject();
 			}
 			
		}			
		return 	json_encode($msg);
		//return $errorEnTrasaccion;
	}


	public function llamadaSeguimiento($idContratoGlobal ){
		$query = new Query();
		$usuario = new Usuario();
		$usuarioId = $usuario->getUsuarioId();

		$sqlContrato = " SELECT 	llamada, resposableseguimiento,	fechallamada FROM  dbcontratosglobales WHERE idcontratoglobal =  ".$idContratoGlobal;
		$query->setQuery($sqlContrato);
		$rsCont = $query->eject();
		$objContrato =  $query->fetchObject($rsCont);
		$llamada =  $objContrato->llamada;
		$usuarioResponsable =  $objContrato->resposableseguimiento;
		$fecha = $objContrato->fechallamada;	

		 if($fecha == NULL  &&  $llamada== 1){

			$sqlInsert =  " UPDATE dbcontratosglobales SET 	fechallamada = CURDATE()  WHERE 	idcontratoglobal = ".$idContratoGlobal. " ";
			$query->setQuery($sqlInsert);
			$query->eject();
			if($usuarioResponsable == 0 || $usuarioResponsable == NULL   ){
			$sqlInsert =  " UPDATE dbcontratosglobales SET resposableseguimiento =".$usuarioId." WHERE 	idcontratoglobal = ".$idContratoGlobal. " ";
			$query->setQuery($sqlInsert);
			$query->eject();
		}


		}


	}
	public function InsertaStatusSolicitud($idContratoGlobal, $status, $causa, $usuarioId){
		//verificamos si ya existe el status que intentamos guardar sino existe lo agregamos
		$query = new Query();
		$error = '';
		$idStatusInsertado ='';

		$sqlBuscastatus = "SELECT * FROM `dbcontratosglobalesstatus` WHERE `refcontratoglobal` =".$idContratoGlobal." AND `refstatuscontratoglobal` =".$status. " ";
		$query->setQuery($sqlBuscastatus);
		$rsStatus = $query->eject();
		$existe = ($query->numRows($rsStatus)>0 );
		if(!$existe || $status == 1){
			$sqlStatus = "INSERT INTO `dbcontratosglobalesstatus` (`idcontratoglobalstatus`, `refcontratoglobal`, `refstatuscontratoglobal`, `refrechazocausa`, `refusuario`, `fecha`, `hora`) ";
			$sqlStatus .= " VALUES (NULL, '".$idContratoGlobal."', '".$status."', '".$causa."', '".$usuarioId."', CURDATE(), now());";
			$query->setQuery($sqlStatus);
			$idStatusInsertado = $query->eject(1);
			if(!$idStatusInsertado){			
				$error = 1;				
				echo "<br>ERROR EN INSERT STATUS:</br> ";								
			}

		}
		return $idStatusInsertado;		
	}

	public function actualizarVigenciaINE($id, $fecha){
		$query = new Query();
		$error = '';
		$sqlU = " UPDATE dbcontratosglobales SET vigenciaine = '".$fecha."' WHERE  idcontratoglobal = $id";
		$query->setQuery($sqlU);
		$res = $query->eject();
		
		if(!$res){
			$error = 1;			
		}
		return $error;
	}

	public function insert($table, $values){
		$values = $this->clear($values);
		
		$query = '';
		$query .= 'INSERT INTO `'.$table.'` ';
		$query .= ' (`'.implode('`, `', array_keys($values)).'`) VALUE';
		$query .= ' ('.implode(', ', $values).');';
		
		$this->setQuery($query);
	}
	

	function traercamposValoresPost($tabla){
		$values = array();
		$query = new Query();			
		$q_campos = 'SHOW COLUMNS FROM `'.$tabla.'`';
		
		$query->setQuery($q_campos);
		$rs_campos = $query->eject();		
		#while($rw_campos = $rs_campos->fetch_array(MYSQLI_ASSOC)){
		while($rw_campos = $query->fetchArray($rs_campos,1)){					
			$value = '';			
			if(isset($_POST[$rw_campos['Field']])){
				$value = $_POST[$rw_campos['Field']];
				$values[$rw_campos['Field']] = $value;
			}			
			
		}		
		return $values;
	}

	function traercamposValoresPostStatusEmpresa($tabla){
		$values = array();
		$query = new Query();			
		$q_campos = 'SHOW COLUMNS FROM `'.$tabla.'`';
		
		$query->setQuery($q_campos);
		$rs_campos = $query->eject();		
		#while($rw_campos = $rs_campos->fetch_array(MYSQLI_ASSOC)){
		while($rw_campos = $query->fetchArray($rs_campos,1)){					
			$value = '';			
			if(isset($_POST[$rw_campos['Field']]) && $rw_campos['Field'] ==''){
				$value = $_POST[$rw_campos['Field']];
				$values[$rw_campos['Field']] = $value;
			}			
			
		}		
		return $values;
	}


	function query($sql,$accion) {

		require_once 'appconfig.php';

		$appconfig	= new appconfig();
		$datos		= $appconfig->conexion();
		$hostname	= $datos['hostname'];
		$database	= $datos['database'];
		$username	= $datos['username'];
		$password	= $datos['password'];


		$conex = mysql_connect($hostname,$username,$password) or die ("no se puede conectar".mysql_error());

		mysql_select_db($database);
		mysql_query("SET NAMES 'utf8'");
		        $error = 0;
		mysql_query("BEGIN");
		$result=mysql_query($sql,$conex);
		if ($accion && $result) {
			$result = mysql_insert_id();
		}
		if(!$result){
			$error=1;
		}
		if($error==1){
			mysql_query("ROLLBACK");
			return false;
		}
		 else{
			mysql_query("COMMIT");
			return $result;
		}

	}
	
	
}
?>