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

	function __construct($idContratoGlobal = NULL){
		echo "Constructor=>";
		 $query = new Query();

		 if(!empty($idContratoGlobal)){
		 	$this->setContratoGlobalId($idContratoGlobal);	 	
		 	$this->refcontratoglobal = $idContratoGlobal;
		 }else{
		 	$usuario = new Usuario();
		 	$usuario-> setUsuarioData();
			$usuario_id = $usuario->getUsuarioId();	
			$whereT = "	usuario_id = ".$usuario_id;

			if(!empty($usuario_id)){
				$idContratoGlobal = $query->selectCampo('idcontratoglobal','dbcontratosglobales',$whereT );
				$this->setContratoGlobalId($idContratoGlobal);
				$this->refcontratoglobal = $idContratoGlobal;

			}
		 }

		 if(!empty($this->idContratoGlobal)){
		 	$this->setLectura($this->cargarPermisos($this->idContratoGlobal));			 	 	
		 }else{		 	
		 	$this->setLectura(false);		 	
		 }	
		  
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
		$permisos = $query->fetchObject($resPermisos);
		$usuariosEdicion = $permisos->usuarios_edicion;		
		$arrayUsers = explode(',', $usuariosEdicion);		
		$lectura = !in_array($usuario->getRolId(), $arrayUsers);		
		return $lectura;		
	}



	public function subirDocumentosSolicitudGlobal($idContratoG = NULL){
		$query = new Query();
		$cuerpoMail ='';
		$serviciosUsuarios = new ServiciosUsuarios();
		$usuario = new Usuario();
		$usuario-> setUsuarioData();
		$emailUsuario = $usuario->getUsuario();
		$usuarioId = $usuario->getUsuarioId();
		$idContratoGlobal = (!empty($idContratoG))?$idContratoG:$this->traercontratoGlobalId();
		$tipoPermitido = array("jpg" => "image/jpg","JPG" => "image/jpg", "JPEG" => "image/jpeg","jpeg" => "image/jpeg", "pdf" => "application/pdf");
		$error = '';
		$_POST['refcontratoglobal'] = $this->traercontratoGlobalId();
		$directorioCarga = "../upload/".$idContratoGlobal."/";

		$cuerpoMail .= '<img src="http://financieracrea.com/esfdesarrollo/images/logo.gif" alt="Financiera CREA" >';
    	$cuerpoMail .= '<h2 class=\"p3\"> DOCUMENTOS RECIBIDOS</h2>';
    	$servidor = $_SERVER['SERVER_NAME'];
    	$liga_servidor = ($servidor=='localhost')?SERVIDOR_LOCAL:SERVIDOR;
   		$cuerpoMail .= '<h3><small><p>Hemos recibido sus documentos, en breve nos comunicaremos con usted, por favor espere nuestra llamada. </p></small></h3>';
   		$cuerpoMail .='<p> No responda este mensaje, el remitente es una dirección de notificación</p>';


		if (!file_exists($directorioCarga)) {
		   if(!mkdir($directorioCarga, 0777, true)){
		   	$error .= "Error al crear la carpeta destino";
		   }
		}

		
		foreach ($_FILES as $file => $filevalues) {
			// para cada archivo insertamos en base de datos y movemos el temporal a la nueva ruta
			# code...
			$nuevoNombreFile =  $idContratoGlobal."_".$this->nombreArchivo($file);	
			if($filevalues["error"] != 4){		
				if( $filevalues["error"] == 0  ){
					$filename = $filevalues["name"];
	        		$filetype = $filevalues["type"];
	        		$filesize = $filevalues["size"];      		

					 $ext = pathinfo($filename, PATHINFO_EXTENSION);
					 $nuevoNombreFile = $nuevoNombreFile.".".$ext;
					 if(!array_key_exists($ext, $tipoPermitido)) 
					 	$error .= "Error: tipo de archivo incorrecto " .$this->nombreArchivo($file);
						// Verificar MYME tipo de archivo
				        if(in_array($filetype, $tipoPermitido)){
				            // verificamos is ya existe el archivo
				            if(file_exists($directorioCarga.$filename)){
				                $error .= " ". $filename . " el archivo ya existe. \n <br>";
				                // aqui opcion para sobre escribir, si fuera el caso
				            } else{
				                if(!move_uploaded_file($filevalues["tmp_name"], $directorioCarga. $nuevoNombreFile)){
				                	$error .= " ". "Error al subir el archivo= \n <br>";
				                }else{
				                	// el archivo se cargo correctamente alservidor se inserta el registro en la base de datos
				                	$sqlIsertFile = "INSERT INTO `dbcontratosglobalesdocumentos` (`idcontratoglobaldocumento`, `refcontratoglobal`, `refdocumento`, `nombre`, `ruta`) ";
				                	$sqlIsertFile .= " VALUES (NULL, $idContratoGlobal , $file, '".$nuevoNombreFile."', '".$directorioCarga."'); ";
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
			$serviciosUsuarios->enviarEmail($emailUsuario,'Recepción de documentos',utf8_decode($cuerpoMail));
			// insertamos el proceso 2	
 			if(!$this->insertaProcesoContratoGlobal($idContratoGlobal, 2, $usuarioId))
 			$error = "Error al insertar el proceso 2";	
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

	public function buscarTipoDoctos(){
		$arrDoctos = array();
		$query = new Query();
		$usuario = new Usuario();
		$usuario-> setUsuarioData();
		$rol = $usuario->getRolId();
		
		$responsable = ($rol == 8 || $rol == 1)?1:2; // 1=cliente, 2= financiera, 3 = empresa_afiliada 
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
	

	public function cargarDatosContratoGlobal($idContratoG = NULL){
		// cargamos todos los datos de la solicictud de credito
		$query = new Query();
		$datosSolicitud = array();
		$datosConsulta = array(); // aqui guardaremos los datos de las tabla		
		//$this->idSolicitud =6;
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
					$arrayDoctos[$archivo] = $valor;			

			}
			
			#print_r($arrayDoctos);
			foreach ($arrayDoctos as $campo => $valor) {
				$datosConsulta["documento_".$campo] = $valor;				
			}

			// cargamos el ultimo status de la solicitud y si existe la causa de rechazo
			$sqlStatusContrato = "SELECT * FROM dbcontratosglobalesstatus where refcontratoglobal =  $this->idContratoGlobal  ORDER BY `idcontratoglobalstatus` DESC limit 0,1";
			
			$query->setQuery($sqlStatusContrato);
			$rsStatus = $query->eject();
			$objStatus =  $query->fetchObject($rsStatus);
			$datosConsulta['cgs.refcontratoglobal'] = $objStatus->refcontratoglobal ;
			$datosConsulta['cgs.refstatuscontratoglobal'] = $objStatus->refstatuscontratoglobal ;
			$datosConsulta['cgs.refrechazocausa'] = $objStatus->refrechazocausa ;
			$datosConsulta['cgs.refusuario'] = $objStatus->refusuario ;
			$datosConsulta['cgs.fecha'] = $objStatus->fecha ;
			$datosConsulta['cgs.hora'] = $objStatus->hora ;
		}// contratoGlobal			
			
		// asignamos los valores de la consulta a las propiedades de la clase		
		$this->datos = (object)$datosConsulta;
		
	}


	

	


	
	
	

	function traerCamposSolicitudCliente(){
		$tabla = 'dbSolicitudes';		
		$renglones = '';
		$forma =  new ServiciosForma();
		
		#$r1c1 =  $forma->columnaTabla($tabla,$refdescripcion,$refCampo,'reftipocredito' , 6);
		$r1c1 =  $forma->columnaTabla($tabla,'reftipocredito' , 6, 'tbtiposcredito');
		$r1c2 =  $forma->columnaTabla($tabla,'reftipocreditoer' , 6, 'tbtiposcredito' );		
		$camposR1 = array($r1c1,$r1c2);
		$renglones .= $forma->generaFormGroup($r1c1);
		$renglones .= $forma->generaFormGroup($r1c1);
		$renglones .= $forma->generaFormGroup($camposR1);
		$renglones .= $forma->generaFormGroupEmcabezado('Datos personales');
		return $renglones;

	}

	function traerCamposSolicitudClientePersonales(){
		$tabla = 'dbclientes';		
		$renglones = '';
		$forma =  new ServiciosForma();
		
		#$r1c1 =  $forma->columnaTabla($tabla,$refdescripcion,$refCampo,'reftipocredito' , 6);
		$formulario .= $forma->generaFormGroupEmcabezado('Datos personales');
		$r1c1 =  $forma->columnaTabla($tabla,'nombre' , 4,'');
		$r1c2 =  $forma->columnaTabla($tabla,'apellidopaterno',4,'');	
		$r1c3 =  $forma->columnaTabla($tabla,'apellidomaterno',4,'');	
		$camposR1 = array($r1c1,$r1c2,$r1c3);
		
		$renglones .= $forma->generaFormGroup($camposR1);
		
		return $renglones;

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
			var_dump($rb);
			$msg['error'] = 1;						
		}

 
			
		if(!$errorEnTrasaccion){
			// ningun error en los queries
			$query->commitTrans();
		}	

		// aqui se valida la respuesta de PPE y los datos de la lista PLD
		$msgPPE = $this->validaPPE($_POST['cargopublico'] , $_POST['cargopublicofamiliar'] , $idSolicitudNueva, $usuario->getUsuarioId());
		$msgPLD = $this->validaPLD($_POST['nombre'] , $_POST['paterno'],  $_POST['materno'], $idSolicitudNueva, $usuario->getUsuarioId());
		if($msgPPE || $msgPLD){
			$msg['error'] = 1;
		}
		
		
		return 	json_encode($msg) ;
		//return $errorEnTrasaccion;
	}


	private function validaPPE($cargoPublico, $familiarConCargoPublico,$idContratoGlobal, $usuarioId){		
		$query = new Query();
		$msg = "";
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
	private function validaPLD($nombre, $paterno, $materno, $idContratoGlobal, $usuarioId){
		$esPLD = false;
		$query = new Query();
		// aqui se hace la busqueda en la lista negra
		################################# buscamos en la lista OFAC #######################################
		$nombre_OFAC = $paterno." ".$materno.", ".$nombre; // formato de la lista RUELAS AVILA, Jose Luis
		if($materno =='' || empty($materno))
		$nombre_OFAC = $paterno.", ".$nombre; // formato de la lista RUELAS AVILA, Jose Luis

		$nombre_OFAC = trim($nombre_OFAC);
		$SqlOFAC = "SELECT * FROM `csv_sdn` WHERE `sdn_name` LIKE  _utf8'%".$nombre_OFAC."%' COLLATE utf8_general_ci";
		$query->setQuery($SqlOFAC);
		$rsOFAC = $query->eject();	
		if(!$rsOFAC){
				echo "Error al cambiar status de solictu a OFAC";			
				$error= 1;				
			}	
		$estaEnListaNegra = $query->numRows($rsOFAC);

		$estaEnListaCNBV = '';
		$nombre_CNBV = $nombre." ".$paterno." ".$materno; 
		if($materno =='' || empty($materno))
		$nombre_CNBV = $nombre." ".$paterno;

		$nombre_CNBV = trim($nombre_CNBV);
		$SqlCNBV = "SELECT * FROM `csv_lista_lpb` WHERE `nombre_completo` LIKE _utf8 '%".$nombre_CNBV."%' COLLATE utf8_general_ci";
		$query->setQuery($SqlCNBV);
		$rsCNBV = $query->eject();
		if(!$rsCNBV){
				echo "Error al cambiar status de solictu a CNBV";			
				$error= 1;				
			}	

		$estaEnListaCNBV = $query->numRows($rsCNBV);	

		$nombre_CNBV = $paterno." ".$materno." ".$nombre; // formato de la lista RUELAS AVILA, Jose Luis
		if($materno =='' || empty($materno))
		$nombre_CNBV = $paterno." ".$nombre;

		$nombre_CNBV = trim($nombre_CNBV);
		$SqlCNBV = "SELECT * FROM `csv_lista_lpb` WHERE `nombre_completo` LIKE _utf8 '%".$nombre_CNBV."%' COLLATE utf8_general_ci";
		$query->setQuery($SqlCNBV);
		$rsCNBV = $query->eject();
		if(!$rsCNBV){
				echo "Error al cambiar status de solictu a CNBV 2";			
				$error= 1;				
			}
		$estaEnListaCNBV2 = $query->numRows($rsCNBV);


		if($estaEnListaNegra || $estaEnListaCNBV || $estaEnListaCNBV2){
			//cambiamos el status de la solicitud a rechazada x PPE
			$causa = ($estaEnListaCNBV || $estaEnListaCNBV2)?2:3;
			$sqlStatus = "INSERT INTO `dbcontratosglobalesstatus` (`idcontratoglobalstatus`, `refcontratoglobal`, `refstatuscontratoglobal`,`refrechazocausa`, `refusuario`, `fecha`, `hora`) ";
			$sqlStatus .= " VALUES (NULL, '".$idContratoGlobal."', '4', '".$causa."', '".$usuarioId."', CURDATE(), now());";
			$query->setQuery($sqlStatus);
			$rs = $query->eject(1);
			if(!$rs){
				echo "Error al cambiar status de solictu a PPE"	;		
				$error= 1;				
			}
		}
		return $error;
	}


	function editarSolicitudGlobal(){
		
		$msg = array();
		$msg['error'] = '';		
		$query = new Query();
		$errorEnTrasaccion = '';
		$tablaSol = 'dbcontratosglobales';
		$valuesSolGlobal = $this->traercamposValoresPost($tablaSol);
		$idContratoGlobal = isset($_POST['idcontratoglobal'])?$_POST['idcontratoglobal']:$this->idContratoGlobal;
		
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
		if(!$errorEnTrasaccion){
			// ningun error en los queries
			$query->commitTrans();
		}	
		
		return 	json_encode($msg);
		//return $errorEnTrasaccion;
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