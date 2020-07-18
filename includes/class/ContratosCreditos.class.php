<?php
//include('../../class_include.php');
/*include ('../../includes/appconfig.php');
include ('../../includes/class/Conexion.inc.php');
include ('../../includes/class/Query.class.php');
include ('../../reportes/fpdfo.php');
include('../../reportes/html2pdf.php');
include('../../reportes/html2pdfutf8.php');
include('../../reportes/html2pdf_small.php');
include('MontoLetras.class.php');
include_once('../../reportes/tfpdf.php');
include_once('../../reportes/PDFMerger.php');*/


include('MontoLetras.class.php');



class ContratosCreditos{
	public $idContratoGlobal = '';
	public $arrayContratos = array();
	public $arrayDatosCliente = array();
	public $cabecera = '';
	public $piePagina = '';
	public $nombreFile = '';
	public $carpeta = '';
	public $carpetaElimina = '';

	public $arrDoctos = array();
	public $directorio = '';



public function setIdContratoGlobal($id){
	$this->idContratoGlobal = $id;
}

public function getIdContratoGlobal(){
	return $this->idContratoGlobal;
}	

public function setArrayContratos($arrayDatos){
	$this->arrayContratos = $arrayDatos;
}

public function getArrayContratos(){
	return $this->arrayContratos;
}




public  function __construct($idContratoGlobal = NULL, $tipoContrato = NULL){
	$query = new Query();
	
	if(!empty($idContratoGlobal)){
		$servidor = $_SERVER['SERVER_NAME'];
	    $dir = ($servidor=='localhost')?$_SERVER['DOCUMENT_ROOT']."/crmcreditos.git/trunk/":$_SERVER['DOCUMENT_ROOT']."esf/crmcreditosonline/";
		#$directorio = $_SERVER['DOCUMENT_ROOT']."crmcreditos.git/trunk/";

		$this->directorio = $dir.'upload/'.$idContratoGlobal.'/';
		if(!empty($idContratoGlobal)){
			$this->setIdContratoGlobal($idContratoGlobal);
		}

		$this->$idContratoGlobal =  $idContratoGlobal;
		$sqlSel = " SELECT * FROM dbcontratosgloblalespdfcontratos 	WHERE refcontratoglobal = ".$idContratoGlobal.";";
		 // "and refpdfcontrato IN (4)";  not in (6,7)

		
		
		$query->setQuery($sqlSel);
		$resContratos = $query->eject();
		$numerodecontratos =  $query->numRows($resContratos);	
		if($numerodecontratos>=1){
			// ya existe el registro, se llena el arreglo		
			$query->setQuery($sqlSel);
			$resContratos1 = $query->eject();		
			while ($objContrato = $query->fetchObject($resContratos1)) {
				$idpdfcontrato = $objContrato->refpdfcontrato;
				$sqlSelect1 =   "SELECT * FROM   tbpdfcontratos WHERE idpdfcontrato = ".$idpdfcontrato." ";			
				$query->setQuery($sqlSelect1);
				$res11 = $query->eject();			
				while ($objContratoPDF = $query->fetchObject($res11)) {
					$arreglorRegistro = array();				
					$arreglorRegistro['idpdfcontrato']= $objContratoPDF->idpdfcontrato;
					$arreglorRegistro['reftiporecurso']= $objContratoPDF->reftiporecurso;
					$arreglorRegistro['descripcion']= $objContratoPDF->descripcion;
					$arreglorRegistro['nombredocto']= $objContratoPDF->nombredocto;
					$arreglorRegistro['carpeta']= $objContratoPDF->carpeta;
					$arreglorRegistro['reca']= $objContratoPDF->reca;
					$arreglorRegistro['contenido']= $objContratoPDF->contenido;
					$arreglorRegistro['metodo']= $objContratoPDF->metodo;				
					$arreglorRegistro['smallformat']= $objContratoPDF->smallformat;
					$this->arrayContratos[] = $arreglorRegistro;  
				}			
			}

		}else{
			//no existe el registro, se crea y se genera el arreglo
			$sqlSelect =   "SELECT * FROM   tbpdfcontratos WHERE reftipocontratoglobal = ".$tipoContrato." ";
			$query->setQuery($sqlSelect);
			$res1 = $query->eject();
			while ($objContrato = $query->fetchObject($res1)) {
				# code...
				$arreglorRegistro = array();
				$arreglorRegistro['idpdfcontrato']= $objContrato->idpdfcontrato;
				$arreglorRegistro['reftiporecurso']= $objContrato->reftiporecurso;
				$arreglorRegistro['descripcion']= $objContrato->descripcion;
				$arreglorRegistro['nombredocto']= $objContrato->nombredocto;
				$arreglorRegistro['carpeta']= $objContrato->carpeta;
				$arreglorRegistro['reca']= $objContrato->reca;
				$arreglorRegistro['contenido']= $objContrato->contenido;
				$arreglorRegistro['metodo']= $objContrato->metodo; 
				$arreglorRegistro['smallformat']= $objContrato->smallformat; 			
				$idpdfcontrato = $objContrato->idpdfcontrato; 
				$sqlInsert = " INSERT INTO `dbcontratosgloblalespdfcontratos` ";
				$sqlInsert .= " (`idcontratogloblalpdfcontrato`, `refcontratoglobal`, `refpdfcontrato`, `fecha`) ";
				$sqlInsert .= " VALUES (NULL, ".$idContratoGlobal.", ".$idpdfcontrato.", CURDATE());";
				$query->setQuery($sqlInsert);
				$idReg = $query->eject(1);
				$this->arrayContratos[] = $arreglorRegistro; 

			}

		}

		$this->carpetaElimina = $arreglorRegistro['carpeta'];
	#	echo  "entra  a la clase =>2";

		$this->datosCliente();	 
		#$this->generaContratos();
 	

 	}
 
	 


}



public function generaContratos(){
	$idContrato = $this->getIdContratoGlobal();
	$documentos = $this->arrayContratos;	
	
	foreach ($documentos as $fila => $datosFila) {
		$arrayDatosCliente =array();
		$funcionCargaDatos = $datosFila['metodo'];
		$contenidoDocumento = $datosFila['contenido'];
		$cabecera =  $datosFila['reca'];
		$piePagina = $datosFila[''];
		$nombreFile = $datosFila['nombredocto'];
		$carpeta =  $datosFila['carpeta'];
		$tipoRecurso = $datosFila['reftiporecurso'];
		$smallFormat = $datosFila['smallformat'];

		if($tipoRecurso ==1){
			$this->$funcionCargaDatos();
			$arrayDatosCliente = $this->arrayDatosCliente;
			foreach($arrayDatosCliente as $llave=>$valor){
				// sustituimos dentro del contenido las etiquetas de las variables por los valores de las variables
				#echo "<br> llave=>".$llave. " valor =>".$valor;
				$contenidoDocumento = str_replace($llave,$valor,$contenidoDocumento);
			}
			#echo $contenidoDocumento;
			// ya que tenemos el contenido generamos los PDF
			$this->generaDocumentoPDF($contenidoDocumento ,$cabecera, $piePagina,$nombreFile, $carpeta,$smallFormat );
		}else if($tipoRecurso ==2){
			// es un docuemto que tiene tablas se dege generar con codigo desde php
			$this->cabecera = $cabecera;			
			$this->piePagina = $piePagina;
			$this->nombreFile =$nombreFile;
			$this->carpeta = $carpeta;
			$this->$funcionCargaDatos();
		}
	} 
}

private function generaDocumentoPDF($contenido, $cabecera, $piePagina,$nombreFile, $carpeta, $smallFormat){
	
	$orientation='P';
	$unit='mm';
	$size='LETTER';
	$utf8=true;
	if($smallFormat == 1){
     	$fpdf = new PDF_HTML_SMALL($orientation, $unit, $size, $utf8);
     	$fpdf->SetMargins(28, 10);
     	$fpdf->AliasNbPages();
		$fpdf->SetHeader($cabecera);
		$fpdf->AddPage();
		$fpdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
		$fpdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);	
		#$fpdf->AddFont('arial','','arial.ttf',true);
		#$fpdf->SetFont('arial','',8);
		#$fpdf->AddFont('arial','B','arialbd.ttf',true);	
		#$fpdf->AddFont('times','','times.ttf',true);
		#$fpdf->SetFont('times','',8);
		#$fpdf->AddFont('times','B','timesbd.ttf',true);	
			
     	$fpdf->SetFont('DejaVu','',8);
	}else{
		#$fpdf = new PDF_HTML($orientation, $unit, $size, $utf8);
		$fpdf = new PDF_HTML_SMALL($orientation, $unit, $size, $utf8);
		$fpdf->SetMargins(28, 10);
		#$fpdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
		#$fpdf->SetFont('DejaVu','',10);
		#$fpdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);	
		#$fpdf->AddFont('DejaVu','I','DejaVuSansCondensed-Oblique.ttf',true);
		$fpdf->AddFont('arial','','arial.ttf',true);
		$fpdf->SetFont('arial','',10);
		$fpdf->AddFont('arial','B','arialbd.ttf',true);	
		$fpdf->AddFont('arial','I','ariali.ttf',true);	
		$fpdf->AliasNbPages();
		$fpdf->SetHeader($cabecera);
		$fpdf->AddPage();
		$fpdf->SetFont('arial','',10);
	}
	
		
	#$pdf->Ln(20);
	$fpdf->WriteHTML($contenido);	
	$ubicacionDocto ="";
	$idContratoGlobal = $this->getIdContratoGlobal();

	$carpeta1 ="../upload/".$idContratoGlobal."/".$carpeta."/";
	#$carpeta1 = $this->directorio."/".$carpeta."/";
	$nameFile = $idContratoGlobal."_".$nombreFile.".".time().".pdf";
	$nombreContrato = $carpeta1.$nameFile;
		if(!file_exists($carpeta1) ){			
			 mkdir($carpeta1, 0777, true);			
		}			
	$fpdf->Output($nombreContrato,'F');	
	array_push($this->arrDoctos,$nombreContrato);

	// ya se genero el documento PDF se debe registrar en la base de datos y adjunto al contrato

}

public function integraDocumentos(){
	$merge = false;
	$ar = $this->arrDoctos;
	$pdfi = new PDFMerger;
	$id = $this->idContratoGlobal;
	$carpeta1 ="../upload/".$id."/";

	#array_push($this->arrDoctos, $carpeta1.'Contrato.pdf');
	array_unshift($this->arrDoctos, $carpeta1.'Contrato.pdf');
	$ar = $this->arrDoctos;
	$servidor = $_SERVER['SERVER_NAME'];
	$directorio = ($servidor=='localhost')?$_SERVER['DOCUMENT_ROOT']."/crmcreditos.git/trunk/":$_SERVER['DOCUMENT_ROOT']."/esf/crmcreditosonline/";
	#$directorio = $_SERVER['DOCUMENT_ROOT']."crmcreditos.git/trunk/";
		//$directorio = "../";
	

	if (count($ar)>0) {	    
	    foreach ($ar as $value) {
	      // code...
	      //die(var_dump($ar));
	      $pdfi->addPDF($value, 'all');
	      //echo $value.'<br>';
	    }
	    //die(var_dump($ar));
	   $merge = $pdfi->merge('file',$directorio.'/upload/'.$id.'/Expediente.pdf');
	} 

	return $merge;


}


public function eliminaDoctosIndividuales(){
	$carpeta = $this->carpetaElimina;
	echo("CARPETA=>".$carpeta);
	if(!empty($this->carpetaElimina)){
		$this->rmDir_rf($carpeta);
	}
	$this->crearIndexCarpetasDoctos('');	
}

public function insertaExpediente(){
	$query = new Query();
	$id = $this->idContratoGlobal;
	$ruta = "upload/".$id;
	$archivo = "Expediente.pdf";
	$sqlInsert = " INSERT INTO `dbcontratosglobalesexpedientes` ";
	$sqlInsert .= "(`dbcontratoglobalexpediente`, `refcontratoglobal`, `esdisposicion`, `refdisposicion`, `ruta`, `documento`, `fecha`) ";
	$sqlInsert .= " VALUES (NULL, '$id', '0', '', '".$ruta."', '".$archivo."', CURDATE());";
	$query->setQuery($sqlInsert);
	$res= $query->eject(1);
	if(!$res){
		echo "**Error al insertar el expediente en la DB**";
		echo "<br>".$sqlInsert."";
		die();	
	}


}

private function insertaContratoDB($fileName, $carpeta){
	$id = $this->idContratoGlobal;
	$query = new Query();
	$sqlIsertFile = "INSERT INTO `dbcontratosglobalesdocumentos`";
	$sqlIsertFile .= " (`idcontratoglobaldocumento`, `refcontratoglobal`, `refdocumento`, `nombre`, `ruta`, `vigencia_desde`, `vigencia_hasta`) ";
	$sqlIsertFile .= " VALUES (NULL, $id , 1000, '".$fileName."', '".$carpeta."', CURDATE(), DATE_ADD(CURDATE(),INTERVAL 1 YEAR)); ";
	$query->setQuery($sqlIsertFile);
	$query->eject(1);
}

private function datosCliente(){	
	$arrayDatos = array();
	$arrayDatosCliente = array();
	$id = $this->idContratoGlobal;
	$query = new Query();

	$selectCG = "SELECT cg.*, 
				cg.refformapago as forma_pago,
				empresa.nombre_empresa, 
				fp.descripcion as forma_pago, fp.valor as fp_valor ,fp.forma_pago_id,
				pais.pais_nombre as pais_nacimiento,
				nac.descripcion as nacionalidad,
				ent_nac.descripcion as entidad_nacimiento,
				gen.descripcion as genero,
				residencia.pais_nombre as residencia,
				domedo.descripcion as domicilio_estado,
				dommun.descripcion as domicilio_municipio,
				domloc.descripcion as domicilio_localidad,
				dependencia.descripcion as dependenciaCU,
				estado.fecha	as fecha_firma_contrato,
				empleoedo.descripcion as empleo_estado,
				empleomun.descripcion as empleo_municipio,
				empleoloc.descripcion as empleo_localidad,
				parentesco.descripcion as parentesco,
				u.usuario as email,
				asesores.nombre as nombre_asesor
				FROM dbcontratosglobales  cg 
				JOIN tbempresaafiliada empresa ON cg.refempresaafiliada = empresa.idempresaafiliada 
				JOIN forma_pago fp ON cg.refformapago = fp.forma_pago_id
				JOIN nacionalidad pais ON  cg.refpais =  pais.nacionalidad_id
				JOIN dbnacionalidades nac ON  cg.refnacionalidad  = nac.idnacionalidad 
				JOIN entidad_nacimiento ent_nac ON  cg.refentidadnacimiento  = ent_nac.entidad_nacimiento_id 
				JOIN tbgenero gen ON  cg.refgenero = gen.idgenero 
				JOIN nacionalidad residencia ON cg.refpaisresidencia =  residencia.nacionalidad_id
				JOIN inegi2020_estado domedo ON cg.refentidad = domedo.estado_id
				JOIN inegi2020_municipio dommun ON cg.refmunicipio = dommun.municipio_id and dommun.refestado = cg.refentidad
				JOIN inegi2020_localidad domloc ON cg.reflocalidad = domloc.localidad_id and domloc.refmunicipio= cg.refmunicipio and domloc.refestado = cg.refentidad

				JOIN inegi2020_estado empleoedo ON cg.refentidadempleo = empleoedo.estado_id
				JOIN inegi2020_municipio empleomun ON cg.refmunicipioempleo = empleomun.municipio_id and empleomun.refestado = cg.refentidadempleo
				JOIN inegi2020_localidad empleoloc ON cg.reflocalidadempleo = empleoloc.localidad_id and empleoloc.refmunicipio= cg.refmunicipioempleo and empleoloc.refestado = cg.refentidadempleo
				LEFT JOIN tbdependeciascu dependencia ON cg.refdependencia = dependencia.iddependeciacu	
				JOIN dbcontratosglobalesstatus estado ON cg.idcontratoglobal =  estado.refcontratoglobal and estado.refstatuscontratoglobal = 5
				JOIN usuario u ON cg.usuario_id = u.usuario_id
				LEFT JOIN tbparentescos parentesco ON cg.refparentesco = parentesco.idparentesco	
				LEFT JOIN tbasesores asesores ON cg.refpromotor = asesores.idasesor
				WHERE idcontratoglobal = ".$id." ";
#echo  	$selectCG ;
				
				
	$query->setQuery($selectCG);
	$resD = $query->eject();
	$rw = $query->fetchObject($resD); //zoa
	foreach ($rw as $campo => $valor){
				if(is_null($valor)){
					$valor = '';
				}
				$arrayDatos[$campo] = $valor;	
			}
			
			$arrayDatosCliente['$x_nombre_cliente'] = $arrayDatos['nombre']." ".$arrayDatos['paterno']." ".$arrayDatos['materno'];
			$arrayDatosCliente['$x_empresa'] = $arrayDatos['nombre_empresa'];
			$arrayDatosCliente['$x_reftipocontratoglobal'] = $arrayDatos['reftipocontratoglobal'];
			$arrayDatosCliente['$x_forma_pago'] = $arrayDatos['forma_pago'];
			$arrayDatosCliente['$x_fp_valor'] = $arrayDatos['fp_valor'];
			$arrayDatosCliente['$x_forma_pago_id'] = $arrayDatos['forma_pago_id'];
			$arrayDatosCliente['$x_tasaanual'] = $arrayDatos['tasaanual'];
			$arrayDatosCliente['$x_numeropagos'] = $arrayDatos['numeropagos'];
			$arrayDatosCliente['$x_montootorgamiento'] = $arrayDatos['montootorgamiento'];
			$arrayDatosCliente['$x_fechanacimiento'] = $arrayDatos['fechanacimiento'];
			$arrayDatosCliente['$x_pais_nacimiento'] = $arrayDatos['pais_nacimiento'];
			$arrayDatosCliente['$x_nacionalidad'] = $arrayDatos['nacionalidad'];
			$arrayDatosCliente['$x_entidad_nacimiento'] = $arrayDatos['entidad_nacimiento'];
			$arrayDatosCliente['$x_refgenero'] = $arrayDatos['refgenero'];
			$arrayDatosCliente['$x_rfc'] = $arrayDatos['rfc'];
			$arrayDatosCliente['$x_curp'] = $arrayDatos['curp'];
			$arrayDatosCliente['$x_cedulasi'] = $arrayDatos['cedulasi'];
			$arrayDatosCliente['$x_cedulano'] = $arrayDatos['cedulano'];
			$arrayDatosCliente['$x_firmasi'] = $arrayDatos['firmasi'];
			$arrayDatosCliente['$x_firmano'] = $arrayDatos['firmano'];
			$arrayDatosCliente['$x_email'] = $arrayDatos['email'];

			$arrayDatosCliente['$x_calle'] = $arrayDatos['calle'];
			$arrayDatosCliente['$x_num_exterior'] = $arrayDatos['numeroexterior'];
			$arrayDatosCliente['$x_num_interior'] = $arrayDatos['numerointerior'];
			$arrayDatosCliente['$x_colonia'] = $arrayDatos['colonia'];
			$arrayDatosCliente['$x_codigopostal'] = $arrayDatos['codigopostal'];
			$arrayDatosCliente['$x_refentidad'] = $arrayDatos['refentidad'];
			$arrayDatosCliente['$x_refmunicipio'] = $arrayDatos['refmunicipio'];
			$arrayDatosCliente['$x_reflocalidad'] = $arrayDatos['reflocalidad'];
			$arrayDatosCliente['$x_domicilio_estado'] = $arrayDatos['domicilio_estado'];
			$arrayDatosCliente['$x_domicilio_municipio'] = $arrayDatos['domicilio_municipio'];
			$arrayDatosCliente['$x_domicilio_localidad'] = $arrayDatos['domicilio_localidad'];


			$arrayDatosCliente['$x_emp_calle'] = $arrayDatos['calleempleo'];
			$arrayDatosCliente['$x_emp_num_ext'] = $arrayDatos['numeroexteriorempleo'];
			$arrayDatosCliente['$x_emp_num_int'] = $arrayDatos['numerointerioremplo'];
			$arrayDatosCliente['$x_emp_col'] = $arrayDatos['coloniaempleo'];
			$arrayDatosCliente['$x_emp_cp'] = $arrayDatos['codigopostalempleo'];
			$arrayDatosCliente['$x_emp_refent'] = $arrayDatos['refentidadempleo'];
			$arrayDatosCliente['$x_emp_refmun'] = $arrayDatos['refmunicipioempleo'];
			$arrayDatosCliente['$x_emp_refloc'] = $arrayDatos['reflocalidadempleo'];
			$arrayDatosCliente['$x_emp_est'] = $arrayDatos['empleo_estado'];
			$arrayDatosCliente['$x_emp_mun'] = $arrayDatos['empleo_municipio'];
			$arrayDatosCliente['$x_emp_loc'] = $arrayDatos['empleo_localidad'];


			$arrayDatosCliente['$x_telefono1'] = $arrayDatos['telefono1'];
			$arrayDatosCliente['$x_reftipotelefono1'] = $arrayDatos['reftipotelefono1'];
			$arrayDatosCliente['$x_celular1'] = $arrayDatos['celular1'];
			$arrayDatosCliente['$x_dependenciaCU'] = $arrayDatos['dependenciaCU'];
			$arrayDatosCliente['$x_puesto'] = $arrayDatos['puesto'];
			$arrayDatosCliente['$x_num_empleado'] = $arrayDatos['noempleado'];
			$arrayDatosCliente['$x_otroempleo'] = $arrayDatos['otroempleo'];
			$arrayDatosCliente['$x_segunda_empresa'] = $arrayDatos['empresa2'];
			$arrayDatosCliente['$x_departamento'] = $arrayDatos['departamento'];
			$arrayDatosCliente['$x_ppe'] = $arrayDatos['cargopublico'];

			$arrayDatosCliente['$x_fnombre'] = $arrayDatos['fnombre'];	
			$arrayDatosCliente['$x_fpaterno'] = $arrayDatos['fpaterno'];
			$arrayDatosCliente['$x_fmaterno'] = $arrayDatos['fmaterno'];
			$arrayDatosCliente['$x_refparentesco'] = $arrayDatos['refparentesco'];
			$arrayDatosCliente['$x_parentesco'] = $arrayDatos['parentesco'];

			$arrayDatosCliente['$x_nombre_asesor'] = $arrayDatos['nombre_asesor'];

			$arrayDatosCliente['$x_fecha_firma_contrato'] = $arrayDatos['fecha_firma_contrato'];
			$fecha_en_letras =  $this->obtenerFechaEnLetra($arrayDatos['fecha_firma_contrato']);
			$arrayDatosCliente['$x_fecha_firma_contrato_letras'] = $fecha_en_letras;
			$numero_Dom = $arrayDatos['numeroexterior'] ;
			if(!empty($arrayDatos['numerointerior'])){
				$numero_Dom = $arrayDatos['numeroexterior'] ." interior : ".$arrayDatos['numerointerior']." ";
			}
			$tex_municipio ="municipio ";
			if($arrayDatos['refentidad'] ==  9){
				$tex_municipio = 'delegación ';
			}
			$direccion_completa = " calle ". $arrayDatos['calle']." ". $numero_Dom." colonia ".$arrayDatos['colonia']. " localidad ". $arrayDatos['domicilio_localidad']."  ".$tex_municipio." ".$arrayDatos['domicilio_municipio'].", ". $arrayDatos['domicilio_estado'];


			$arrayDatosCliente['$x_direccion_cliente'] =$direccion_completa;
			
			$numero_Emp = $arrayDatos['numeroexteriorempleo'] ;
			if(!empty($arrayDatos['numerointerioremplo'])){
				$numero_Emp = $arrayDatos['numeroexteriorempleo'] ." interior : ".$arrayDatos['numerointerioremplo']." ";
			}
			$tex_municipio ="; Mun: ";
			if($arrayDatos['refentidadempleo'] ==  9){
				$tex_municipio = ';Del: ';
			}

			$direccion_completa_emp = "". $arrayDatos['calleempleo']." ". $numero_Emp.", ".$arrayDatos['coloniaempleo']. "; LOC: ". $arrayDatos['empleo_localidad']."  ".$tex_municipio." ".$arrayDatos['empleo_municipio'].", ". $arrayDatos['empleo_estado']." ";
			


			$arrayDatosCliente['$x_dir_empleo'] =$direccion_completa_emp;

			$fecha_formato_contrato = $this->obtenerFechaEnLetraFormatoContrato($arrayDatos['fecha_firma_contrato']);
			$arrayDatosCliente['$x_fecha_firma_formato_contrato'] =$fecha_formato_contrato;
			$telefonos = 'Celular :'.$arrayDatos['celular1'];
			if(!empty($arrayDatos['telefono1'])){
				$telefonos .= " Tel :". $arrayDatos['telefono1'];
			}


			$arrayDatosCliente['$x_telefonos_cliente'] = $telefonos;

			$fecha_domiciliacion  = $this->obtenerFechaEnFormatoDomiciliacion($arrayDatos['fecha_firma_contrato']);
			$fecha_elaboracion  = $this->obtenerFechaEnFormatoDomiciliacion($arrayDatos['fecha_firma_contrato']);
			$fecha_elaboracion2 = $this->obtenerFechaEnLetraFormatoElaboracionTablaAmortizacion($arrayDatos['fecha_firma_contrato']);
			$arrayDatosCliente['$x_fecha_domiciliacion'] = $fecha_domiciliacion;
			$arrayDatosCliente['$x_fecha_elaboracion'] = $fecha_elaboracion;
			$arrayDatosCliente['$x_fecha_text_elaboracion'] = $fecha_elaboracion2;

			$arrayDatosCliente['$x_fam_nomb'] = $arrayDatos['fnombre']." ".$arrayDatos['fpaterno']." ".$arrayDatos['fmaterno'];
			
	$this->arrayDatosCliente = $arrayDatosCliente;
}
public  function consentimientoDeRetencion(){
	
	$aInfo = $this->arrayDatosCliente;
 	$cabecera = 	$this->cabecera ;
	$piePagina =  	$this->piePagina ;
	$nombreFile = 	$this->nombreFile ;
	$carpeta =		$this->carpeta;
	$fecha_firma = $this->obtenerFechaEnFormatoConsentimientoRetencion($aInfo['$x_fecha_firma_contrato']);

	$trabajo = $aInfo['$x_empresa']."; ".$aInfo['$x_dependenciaCU'];
	$nombre_cliente =  $aInfo['$x_nombre_cliente'];
	$fpdf = new PDF_HTML();
	$fpdf->AliasNbPages();
	$fpdf->SetHeader($cabecera );
	$fpdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
	$fpdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
	$fpdf->AddPage();
	$fpdf->SetFont('DejaVu','B','10');
	$fpdf->SetTextColor(67,67,67);
	$fpdf->SetTextColor(255,255,255);
	$fpdf->setFillColor(192,0,0);
	$fpdf->Ln(5);
	$titulo ="CONSENTIMIENTO DE RETENCIÓN"
	 ;
	$fpdf->Cell(0,5,$titulo,1,1,'C',1);
	
	$fpdf->SetTextColor(0,0,0);

	$fpdf->SetFont('DejaVu','B','9');
	$fpdf->Ln(10);
	$fpdf->Write(10,"Lugar y Fecha: ");
	$fpdf->SetFont('Dejavu','','9');
	$fpdf->Write(10,$fecha_firma);
	$fpdf->Ln(8);
	$fpdf->SetFont('DejaVu','B','9');
    $fpdf->Write(10,"Nombre del Patrón (Empresa / Dependencia / Organismo)");
    $fpdf->SetFont('DejaVu','','9');
    $fpdf->Ln(8);
	$fpdf->Write(10,$trabajo);

	$fpdf->Ln(8);
	$fpdf->SetFont('DejaVu','B','9');
	$fpdf->Write(10,"Nombre del cliente: ");
	$fpdf->SetFont('DejaVu','','9'); 
	$fpdf->Write(10,$nombre_cliente);

	$fpdf->Ln(8);
	$fpdf->SetFont('DejaVu','B','9');
	$fpdf->Write(10,"Presente.");
	$fpdf->SetFont('DejaVu','','9');
	$fpdf->Ln(10);

	
	#echo "***<br>". $fpdf->getX()."***<br>";
		$fpdf->MultiCellDos(0,5, 'Por así convenir a mis intereses personales y de conformidad con lo dispuesto por el art. 98 y demás artículos aplicables de la Ley Federal del Trabajo, atento a la relación de trabajo que sostengo con Ustedes, por medio de la presente solicito atentamente se sirva a girar instrucciones a quien corresponda para que, por mi cuenta y orden se realicen los pagos que a continuación se detallan a favor de ');

		#echo "***<br>". $fpdf->getX()."***<br>";
		$fpdf->SetFont('DejaVu','B','9');

		$fpdf->MultiCellDos(0,5, 'MICROFINANCIERA CRECE, SOCIEDAD ANÓNIMA DE CAPITAL VARIABLE, SOCIEDAD FINANCIERA DE OBJETO MÚLTIPLE, ENTIDAD NO REGULADA (En adelante FINANCIERA CREA)');
		$fpdf->resetXY();

	$fpdf->SetFont('DejaVu','B','9');
	
	
	$fpdf->Ln(10);
	
	$fpdf->MultiCellDos(0,5, "Así mismo manifiesto que esta instrucción es con carácter de IRREVOCABLE, en virtud de que se otorga como un medio para cumplir la obligación de pago contraída con FINANCIERA CREA y que solo dejará de surtir efectos, una vez que el crédito otorgado por FINANCIERA CREA sea liquidado en su totalidad. Para lo anterior se tendrá como prueba de dicho pago únicamente una 'CARTA DE LIQUIDACION', expedida en su momento por FINANCIERA CREA, en donde conste efectivamente que el crédito se encuentra pagado en su totalidad. ");
	$fpdf->SetFont('DejaVu','','9');
	$fpdf->MultiCellDos(0,5, "Por lo que solicito lo siguiente:");
	$fpdf->resetXY();
	$fpdf->Ln(10);





	$fpdf->Write(5, "a) Del salario que percibo, se pague de manera quincenal, en forma consecutiva y sin interrupciones, o hasta que se cumplan ".$x_numero_pagos." pagos quincenales, todas ellas por una cantidad de".$x_monto_pago." derivado del contrato de crédito suscrito con FINANCIERA CREA");
	$fpdf->Ln(10);
	$fpdf->Write(5, "b) Las cantidades mencionadas en el inciso a) precedente, deberán ser depositadas, en mi nombre y por mi cuenta, sin reserva ni limitación alguna, en la cuenta No. 50009212472 que FINANCIERA CREA tiene suscrita en BANCO INBURSA S.A.");
	$fpdf->Ln(20);
	$titulo = utf8_decode('ATENTAMENTE ');
	$fpdf->Cell(0,5,'ATENTAMENTE',0,0,'C');
	$fpdf->Ln(20);
	$fpdf->Cell(0,5,'________________________________',0,0,'C');
	$fpdf->Ln(5);
	$fpdf->Cell(0,5,$nombre_cliente ,0,0,'C');
	$idContratoGlobal = $this->getIdContratoGlobal();

	$carpeta1 ="../upload/".$idContratoGlobal."/".$carpeta."/";
	$nameFile = $idContratoGlobal."_".$nombreFile.".".time().".pdf";
	$nombreContrato = $carpeta1.$nameFile;
		if(!file_exists($carpeta1) ){			
			 mkdir($carpeta1, 0777, true);			
		}
		

	$fpdf->outPut($nombreContrato,'F');	
	array_unshift($this->arrDoctos,$nombreContrato);

	#$fpdf->outPut();	

}

public function tablaAmortizacionCG(){
	$aInfo = $this->arrayDatosCliente;
	$cabecera = 	$this->cabecera ;
	$piePagina =  	$this->piePagina ;
	$nombreFile = 	$this->nombreFile ;
	$carpeta =		$this->carpeta;
	$fecha_firma = $this->obtenerFechaEnFormatoConsentimientoRetencion($aInfo['$x_fecha_firma_contrato']);
	$trabajo = $aInfo['$x_empresa']."; ".$aInfo['$x_dependenciaCU'];
	$nombre_cliente =  $aInfo['$x_nombre_cliente'];

	$orientation='P';
	$unit='mm';
	$size='A4';
	$utf8=true;

	$fpdf = new PDF_HTML($orientation, $unit, $size, $utf8);
	#$fpdf = new tFPDF();
	#$fpdf->AliasNbPages();
	$fpdf->SetHeader($cabecera );
	// Add a Unicode font (uses UTF-8)
	$fpdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
	$fpdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
	$fpdf->AddFont('DejaVu','I','DejaVuSerifCondensed-Italic.ttf',true);
	$fpdf->AddFont('DejaVu','BI','DejaVuSansCondensed-Oblique.ttf',true);
	$fpdf->AddFont('arial','','arial.ttf',true);
	$fpdf->AddFont('arial','B','arialbd.ttf',true);
	$fpdf->AddFont('arial','I','ariali.ttf',true);
	$fpdf->AddFont('arial','BI','arialbi.ttf',true);
	#$fpdf->AddFont('times','','times.ttf',true);
	#$fpdf->AddFont('times','B','timesbd.ttf',true);
	#$fpdf->AddFont('times','I','timesi.ttf',true);
	#$fpdf->AddFont('times','BI','timesbi.ttf',true);
	
	#$fpdf->SetFont('DejaVu','',14);
	$fpdf->AddPage();
	$fpdf->SetFont('DejaVu','B','10');
	$fpdf->SetTextColor(67,67,67);
	$fpdf->SetTextColor(0,0,0);
	$fpdf->setFillColor(192,0,0);
	$fpdf->Ln(5);
	$titulo = 'TABLA DE AMORTIZACION  ';
	$fpdf->Cell(0,5,$titulo,0,1,'C',0);
	$fpdf->SetTextColor(0,0,0);
	$fpdf->Ln(15);
	$fpdf->SetFont('DejaVu','B','10');
	$fpdf->Write(5,'Nombre del cliente: ');
	$fpdf->Write(5,$aInfo['$x_nombre_cliente']);
	$fpdf->Ln(7);
	$fpdf->Write(5,'Número de Crédito: ');
	$fpdf->Write(5,$x_numero_credito);

	$fpdf->Ln(7);
	$fpdf->Write(5,'Fecha de Elaboración: ');
	$fpdf->Write(5,$aInfo['$x_fecha_elaboracion']);
	#print_r($aInfo);

	$fpdf->Ln(20);
	$fpdf->SetTextColor(40,40,40);
	$fpdf->setFillColor(255,255,255);
	$fpdf->SetTextColor(0,0,0);
	$fpdf->SetFont('DejaVu','','8');
	$fpdf->Cell(25,4,'Número de ','LRT',0,'L',1);
	$fpdf->Cell(35,4,'Fecha de Pago ','LRT',0,'L',1);
	$fpdf->Cell(43,4,'Saldo Insoluto ','LRT',0,'L',1);
	$fpdf->Cell(43,4,'Capital pagado','LRT',0,'L',1);
	$fpdf->Cell(43,4,'Pago Total de','LRT',0,'L',1);
	$fpdf->Ln(4);
	$fpdf->Cell(25,4,'pago o','LR',0,'L',1);
	$fpdf->Cell(35,4,'','LR',0,'L',1);
	$fpdf->Cell(43,4,'de Capital ','LR',0,'L',1);
	$fpdf->Cell(43,4,'en cada','LR',0,'L',1);
	$fpdf->Cell(43,4,'cada','LR',0,'L',1);
	$fpdf->Ln(4);
	$fpdf->Cell(25,4,'vencimiento ','LRB',0,'L',1);
	$fpdf->Cell(35,4,'','LRB',0,'L',1);
	$fpdf->Cell(43,4,''."",'LRB',0,'L',1);
	$fpdf->Cell(43,4,'vencimiento ','LRB',0,'L',1);
	$fpdf->Cell(43,4,'vencimiento ','LRB',0,'L',1);
	$fpdf->SetTextColor(0,0,0);

	
	$fpdf->SetFont('times','','8');
	$fpdf->Ln(4);



	$fpdf->SetWidths(array(25,35,43,43,43));
	$cadenaa = 1;

	$cadena1 = "15-06-2020";
	$cadena2 = '$1,550';
	$cadena3 = '700 ';
	$cadena4 = '750';
	$fpdf->Row(array($cadenaa,$cadena1,$cadena2,$cadena3,$cadena4));

	# $fpdf->SetWidths(array(25,35,43,43,43));
	 $cadenaa = 2;

	$cadenaa = 1;

	$cadena1 = "15-06-2020";
	$cadena2 = '$1,550';
	$cadena3 = '$700 ';
	$cadena4 = '$750';
	$fpdf->Row(array($cadenaa,$cadena1,$cadena2,$cadena3,$cadena4));
    $fpdf->SetFont('arial','','8');
	$fpdf->Ln(20);

		$fpdf->MultiCell(0,5,'Esta tabla de amortización detalla el calendario de pagos así como el desglose por concepto de pago en cada uno de los vencimientos. La presente tabla forma parte del contrato de crédito simple con interés vía descuento de nómina elaborada  '.$aInfo['$x_fecha_text_elaboracion'].', entre el cliente '.$aInfo['$x_nombre_cliente'].' y Microfinanciera Crece, SA de CV SOFOM ENR',0,'J');


	  


	 
		
	$fpdf->Ln(30);
	$fpdf->Cell(0,5,'________________________________________','',0,'C',1);
	$fpdf->Ln(4);
	$fpdf->Cell(0,5,$aInfo['$x_nombre_cliente'],'',0,'C',1);


	$ubicacionDocto ="";
	$idContratoGlobal = $this->getIdContratoGlobal();

	$carpeta1 ="../upload/".$idContratoGlobal."/".$carpeta."/";
	$nameFile = $idContratoGlobal."_".$nombreFile.".".time().".pdf";
	$nombreContrato = $carpeta1.$nameFile;
		if(!file_exists($carpeta1) ){			
			 mkdir($carpeta1, 0777, true);			
		}			
	$fpdf->Output($nombreContrato,'F');	
	array_unshift($this->arrDoctos,$nombreContrato);
	#$fpdf->Output();
}


public function textoJustiificado(){


}

public function caratulaDeCreditoCG(){
	$aInfo = $this->arrayDatosCliente;
	/*echo "<pre>";
	print_r($aInfo);
    echo "</pre>";*/
	$cabecera = 	$this->cabecera ;
	$piePagina =  	$this->piePagina ;
	$nombreFile = 	$this->nombreFile ;
	$carpeta =		$this->carpeta;
	$fecha_firma = $this->obtenerFechaEnFormatoConsentimientoRetencion($aInfo['$x_fecha_firma_contrato']);
	$trabajo = $aInfo['$x_empresa']."; ".$aInfo['$x_dependenciaCU'];
	$nombre_cliente =  $aInfo['$x_nombre_cliente'];

	$orientation='P';
	$unit='mm';
	$size='A4';
	$utf8=true;

	$fpdf = new PDF_HTML_UTF8($orientation, $unit, $size, $utf8);

	$fpdf->AliasNbPages();
	$fpdf->SetHeader($cabecera );
	$fpdf->AddPage();
	$fpdf->SetFont('Arial','B','10');
	$fpdf->SetTextColor(67,67,67);
	$fpdf->SetTextColor(255,255,255);
	$fpdf->setFillColor(192,0,0);
	$fpdf->Ln(5);
	$titulo = 'CARÁTULA DE CRÉDITO  ';
	$fpdf->Cell(0,5,$titulo,1,1,'C',1);
	$fpdf->SetTextColor(0,0,0);

	
	$fpdf->SetFont('Arial','B','10');
	$fpdf->MultiCell(0,4," Nombre comercial del Producto:  CRÉDITO REVOLVENTE SIN INTERÉS VÍA DESCUENTO DE NÓMINA \"ANTICIPO DE QUINCENA\" "."\n".' ','LR','J',0);
	$fpdf->SetFont('Arial','','9');



	$fpdf->MultiCell(0,4,"Tipo de Crédito: Crédito de Nómina",'BLR','J',0);
	
	$fpdf->SetTextColor(40,40,40);
	$fpdf->setFillColor(192,0,0);
	$fpdf->SetTextColor(255,255,255);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(49,5,'CAT (Costo Anual Total)','LRT',0,'C',1);
	$fpdf->Cell(47,5,'TASA DE INTERÉS ANUAL','LRT',0,'C',1);
	$fpdf->Cell(47,5,'MONTO O LÍNEA DE CREDITO','LRT',0,'C',1);
	$fpdf->Cell(47,5,'MONTO TOTAL A PAGAR','LRT',0,'C',1);
	$fpdf->Ln(5);
	$fpdf->Cell(49,5,'','LRB',0,'C',1);
	$fpdf->Cell(47,5,' ORDINARIA Y MORATORIA','LRB',0,'C',1);
	$fpdf->Cell(47,5,''."",'LRB',0,'C',1);
	$fpdf->Cell(47,5,'','LRB',0,'R',1);
	$fpdf->SetTextColor(0,0,0);

	
	$fpdf->SetFont('Arial','','8');
	$fpdf->Ln(5);



	$fpdf->SetWidths(array(49,47,47,47));
	$x_cat = 52;
	$montotexto = new MontoLetras($x_cat,2);
	$x_catTexto = $montotexto->getCadenaMonto();
	

	$cadena1 = $x_cat." % (-". $x_catTexto . "-)\n
		Sin IVA para fines informativos y de comparación.";
	$tasa_moratoria = 40;	
	$montotexto = new MontoLetras($tasa_moratoria,2);
	$x_catTextoMora = $montotexto->getCadenaMonto();
	$cadena2 = 'Tasa Anual Fija  Moratoria'."\n".
		$tasa_moratoria." % (-".$x_catTextoMora."-)
		Tasa Anual Ordinaria 
		(No Aplica)	";
	$monto_credito = 10000;	
	$montotexto = new MontoLetras($monto_credito,1);
	$montoCreditoTexto = $montotexto->getCadenaMonto();
	$cadena3 = '$'.number_format($monto_credito,2).' (-'.$montoCreditoTexto.'-) ';
	$monto_pago = 17328;
	$montotexto = new MontoLetras($monto_pago,1);
	$montoMontoPago= $montotexto->getCadenaMonto();
	$cadena4 = '$'.number_format($monto_pago,2).' (-'.$montoMontoPago.'-)
	 ';
	 $fpdf->Row(array($cadena1,$cadena2,$cadena3,$cadena4));

	

	#$fpdf->Ln(5);

	$fpdf->Cell(49,5,'PLAZO DEL CRÉDITO:','LRT',0,'L',0);
	$fpdf->Cell(141,5,'','LRT',0,'C',0);
	
	$fpdf->Ln(5);
	$fpdf->Cell(49,5,'','LR',0,'L',0);
	$fpdf->Cell(141,5,'','LR',0,'C',0);

	
	$fpdf->SetTextColor(0,0,0);
	$fpdf->Ln(5);
	$fpdf->SetFont('Arial','','8');
	$plazoCredito = $this->plazoCredito($aInfo['$x_numeropagos'], $aInfo['$x_forma_pago_id'] );
	$fpdf->Cell(49,5,$plazoCredito,'LR',0,'L',0);
	$fpdf->SetFont('Arial','','8');

	$fpdf->Cell(29,5,'Fecha Límite de pago:','L',0,'L',0);
	$fpdf->SetFont('Arial','U','8');
	$fpdf->Cell(112,5,'ESCRIBIR LA FECHA LIMITE DEL PAGO','R',0,'L',0);
	$fpdf->SetFont('Arial','','8');
	
	$fpdf->Ln(5);
	$fpdf->Cell(49,5,' ','LR',0,'L',0);
	$fpdf->Cell(141,5,'','LR',0,'L',0);
	
	$fpdf->SetTextColor(0,0,0);
	$fpdf->Ln(5);
	$fpdf->Cell(49,5,' ','LRB',0,'L',0);
	$fpdf->Cell(21,5,'Fecha de Corte:','LB',0,'L',0);
	$fpdf->SetFont('Arial','U','8');
	$fpdf->Cell(120,5,'ESCRIBIR LA FECHA LIMITE DEL PAGO','RB',0,'L',0);
	$fpdf->SetFont('Arial','','8');

	$fpdf->SetTextColor(0,0,0);

	$fpdf->SetFont('Arial','B','10');
	$fpdf->SetTextColor(67,67,67);
	$fpdf->SetTextColor(255,255,255);
	$fpdf->setFillColor(192,0,0);
	$fpdf->Ln(5);
	
	$fpdf->Cell(0,5,'COMISIONES RELEVANTES:',1,1,'C',1);
	$fpdf->SetTextColor(0,0,0);
	$fpdf->SetFont('Arial','','8');
	$x_com_dispo = 500;
	$montotexto = new MontoLetras($x_com_dispo,1);
	$montoDisp= $montotexto->getCadenaMonto();
	$x_comision_por_disposicion= 'Comisión por disposición $'.number_format($x_com_dispo,2)." (-" .$montoDisp.'-)';
	$fpdf->Cell(0,5,$x_comision_por_disposicion,1,1,'C',0);

	$fpdf->SetFont('Arial','B','10');
	$fpdf->SetTextColor(67,67,67);
	$fpdf->SetTextColor(255,255,255);
	$fpdf->setFillColor(192,0,0);
	
	
	$fpdf->Cell(0,5,'ADVERTENCIAS:',1,1,'C',1);
	$fpdf->SetTextColor(0,0,0);
	$fpdf->SetFont('Arial','','8');	
	$fpdf->MultiCell(0,5,'"Incumplir tus obligaciones te puede generar comisiones e intereses moratorios", "Contratar créditos que excedan tu capacidad de pago afecta tu historial crediticio"',1,'L',0);

	$fpdf->SetFont('Arial','B','10');
	$fpdf->SetTextColor(67,67,67);
	$fpdf->SetTextColor(255,255,255);
	$fpdf->setFillColor(192,0,0);
	
	
	$fpdf->Cell(0,5,'SEGUROS',1,1,'C',1);
	$fpdf->SetTextColor(0,0,0);
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(63,5,'Seguro: N/A','LBR',0,'L',0);
	$fpdf->Cell(64,5,'Aseguradora: N/A','LB',0,'L',0);
	$fpdf->Cell(63,5,'Cláusula: N/A','LBR',0,'L',0);

	$fpdf->SetFont('Arial','B','8');
	$fpdf->Ln(5);
	$fpdf->Cell(0,5,'ESTADO DE CUENTA ','LR',0,'L',0);
	$fpdf->Ln(5);
	$fpdf->Cell(0,7,' ','LR',0,'L',0);
	$fpdf->Ln(7);
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(15,15,'Enviar a: ','LB',0,'L',0);
	$fpdf->Cell(40,15,'domicilio____ ','B',0,'L',0);
	$fpdf->Cell(60,15,'Consulta: vía Internet____ ','B',0,'L',0);
	$fpdf->Cell(75,15,'Envío por correo electrónico____ ','RB',0,'L',0);
	$fpdf->Ln(15);
	$fpdf->Cell(0,5,'Aclaraciones y reclamaciones:  ','LR',0,'L',0);
		$fpdf->Ln(5);
	$fpdf->Cell(0,15,'Unidad Especializada de Atención a Usuarios (UNE)  ','LR',0,'L',0);
	$fpdf->Ln(15);
	$fpdf->SetFont('Arial','B','9');
	
	
	$fpdf->Cell(10,5,'*','L',0,'R',0);
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(180,5,'Boulevard Adolfo Ruiz Cortines 4302, Interior 212, Colonia Jardines del Pedregal de San Ángel, Delegación Coyoacán, Ciudad De ','R',0,'L',0);
	$fpdf->Ln(5);
	$fpdf->Cell(10,5,'','L',0,'R',0);
	$fpdf->Cell(180,5,' México C.P. 04500. ','R',0,'L',0);
	$fpdf->Ln(5);
	$fpdf->SetFont('Arial','B','9');
	$fpdf->Cell(10,5,'*','L',0,'R',0);
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(180,5,'A través de su línea telefónica en los números: 800 837 6133 y 55 5135 0259, ext. 110','R',0,'L',0);
	$fpdf->Ln(5);
	$fpdf->SetFont('Arial','B','9');
	$fpdf->Cell(10,5,'*','L',0,'R',0);
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(28,5,'Dirección electrónica: ','',0,'L',0);
	$fpdf->SetFont('Arial','U','8');
	$fpdf->SetTextColor(0,0,255);
	$fpdf->Cell(0,5,'www.financieracrea.com','R',0,'L',0,'www.financieracrea.com');
	$fpdf->SetTextColor(0,0,0);
	$fpdf->Ln(5);
	$fpdf->SetFont('Arial','B','9');
	$fpdf->Cell(10,5,'*','L',0,'R',0);
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(0,5,'Correo electrónico: clientes@financieracrea.com','R',0,'L',0);
	$fpdf->Ln(5);
	$fpdf->Cell(0,4,'','LRB',0,'L',0);
	$fpdf->Ln(4);
	$numero_reca = str_replace ( 'RECA ' , '' , $cabecera );
	$fpdf->Cell(0,6,'Registro de Contratos de Adhesión Número (RECA): '.$numero_reca,'RL',0,'L',0);
	$fpdf->Ln(6);
	$fpdf->MultiCell(0,4,'Comisión Nacional para la Protección y Defensa de los Usuarios de Servicios Financieros (CONDUSEF): Teléfono: 55 5340 0999. Página de Internet. www.condusef.gob.mx','RLB','L',0);
	$fpdf->Ln(20);


	 
	 




	$idContratoGlobal = $this->getIdContratoGlobal();

	$carpeta1 ="../upload/".$idContratoGlobal."/".$carpeta."/";
	$nameFile = $idContratoGlobal."_".$nombreFile.".".time().".pdf";
	$nombreContrato = $carpeta1.$nameFile;
		if(!file_exists($carpeta1) ){			
			 mkdir($carpeta1, 0777, true);			
		}
		

	$fpdf->outPut($nombreContrato,'F');	
	array_unshift($this->arrDoctos,$nombreContrato);
	#$fpdf->outPut();
}


public  function solicitudCredito(){
	
	$aInfo = $this->arrayDatosCliente;
 	$cabecera = 	$this->cabecera ;
	$piePagina =  	$this->piePagina ;
	$nombreFile = 	$this->nombreFile ;
	$carpeta =		$this->carpeta;
	$fecha_firma = $this->obtenerFechaEnFormatoConsentimientoRetencion($aInfo['$x_fecha_firma_contrato']);

	$trabajo = $aInfo['$x_empresa']."; ".$aInfo['$x_dependenciaCU'];
	$nombre_cliente =  $aInfo['$x_nombre_cliente'];
	$orientation='P';
	$unit='mm';
	$size='A4';
	$utf8=true;

	$fpdf = new PDF_HTML_UTF8($orientation, $unit, $size, $utf8);
	$fpdf->AliasNbPages();
	$fpdf->SetHeader($cabecera );
	$fpdf->AddPage();
	$fpdf->SetAutoPageBreak(true, 10);
	$fpdf->SetFont('Arial','','8');
	$fpdf->SetTextColor(67,67,67);
	$fpdf->SetTextColor(255,255,255);
	$fpdf->setFillColor(212,38,38);
	$fpdf->SetDrawColor(212,38,38);
	$fpdf->Ln(0);
	$titulo = utf8_decode('SOLICITUD DE CREDITO ');
	$ruta = __DIR__;
	$fpdf->image($ruta.'/arbol.jpg', 12,40,35,28);
	$fpdf->Cell(0,8,$titulo,1,1,'C',1);
	$fpdf->Ln(8);
    $fpdf->SetTextColor(0,0,0);
    $fpdf->SetFont('Arial','B','8');
    $fpdf->SetDrawColor(0,0,0);
    $fpdf->SetX(50);
	$fpdf->Cell(20,6,'CANAL',0,0,'R',0);
	$fpdf->Cell(130,6,$aInfo['$x_empresa'],1,1,'L',0);
	 
	$fpdf->Sety(53);
	$fpdf->SetX(50);
	$fpdf->Cell(20,6,'ASESOR',0,0,'R',0);
	$fpdf->Cell(50,6,$aInfo['$x_nombre_asesor'],1,1,'L',0);
	$fpdf->Sety(53);
	$fpdf->SetX(120);
	$fpdf->Cell(30,6,'MONTO SOLICITADO',0,0,'R',0);
	$fpdf->Cell(50,6,number_format($aInfo['$x_montootorgamiento'],2),1,1,'C',0);

$x_plazo =$this->plazoCredito($aInfo['$x_numeropagos'], $aInfo['$x_forma_pago_id']);

	$fpdf->Sety(60);
	$fpdf->SetX(45);
	$fpdf->Cell(25,7,'FORMA DE PAGO',0,0,'C',0);
	$fpdf->Cell(50,6,$aInfo['$x_forma_pago'],1,1,'C',0);
	$fpdf->Sety(60);
	$fpdf->SetX(120);
	$fpdf->Cell(30,7,'PLAZO',0,0,'R',0);
	$fpdf->Cell(50,6,$x_plazo ,1,1,'C',0);
	$fpdf->Ln(5);

	$fpdf->SetTextColor(255,255,255);
	$fpdf->setFillColor(212,38,38);
	 $fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(0,6,'Información General',0,0,'C',1);
	$fpdf->Ln(8);
	$fpdf->SetX(12);
	$fpdf->SetTextColor(0,0,0);
	$fpdf->Cell(15,6,'Nombre:',0,0,'r',0);
	#$fpdf->SetX(12);
	 $fpdf->SetFont('Arial','','8');
	$fpdf->Cell(0,6,$aInfo['$x_nombre_cliente'],0,0,'r',0);
	$fpdf->Line(27, 84, 200, 84);
	$fpdf->Ln(8);
	$fpdf->SetX(12);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(30,6,'Fecha de nacimiento:',0,0,'r',0);
	$fpdf->SetFont('Arial','','9');
	$fpdf->Cell(25,6,$aInfo['$x_fechanacimiento'],0,0,'r',0);
	$fpdf->Line(43, 92, 70, 92);
	$fpdf->SetX(70);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(20,6,'CURP:',0,0,'R',0);
	$fpdf->SetFont('Arial','','8');
	$CURP = $aInfo['$x_curp'];
	$arrayCurp = str_split($CURP);
	$valorX = 92;
	foreach ($arrayCurp as $key => $letra) {		
		$fpdf->SetY(86);
		$fpdf->SetX($valorX);
		$fpdf->Cell(6,6,$letra,1,1,'C',0);
		$valorX += 6;
	}
	$fpdf->Ln(4); // no se porque fue 4
	$fpdf->SetX(12);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(22,6,'Nacionalidad:',0,0,'r',0);
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(25,6,$aInfo['$x_nacionalidad'],0,0,'r',0);
	$fpdf->Line(33, 101, 70, 101);
	$fpdf->SetX(90);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(20,6,'Estado de nacimiento: ',0,0,'R',0);
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(25,6,$aInfo['$x_entidad_nacimiento'],0,0,'r',0); 
	$fpdf->Line(110, 101, 200, 101);

	$fpdf->Ln(8);
	$fpdf->SetX(12);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(30,6,'País de nacimiento:',0,0,'r',0);
	$fpdf->SetFont('Arial','','9');
	$fpdf->Cell(150,6,$aInfo['$x_pais_nacimiento'],0,0,'r',0);
	$fpdf->Line(41, 109, 130, 109);
	$fpdf->SetX(122);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(20,6,'Sexo: ',0,0,'R',0);

	$mujer = ($aInfo['$x_refgenero'] ==2)?'X':'';
	$hombre = ($aInfo['$x_refgenero'] ==1)?'X':'';

	$fpdf->SetX(142);
	$y = $fpdf->getY();
	$fpdf->Cell(6,6,'H',0,0,'r',0); 
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(6,5,$hombre,1,1,'C',0);
   
    $fpdf->SetY($y);
    $fpdf->SetX(162);
    $fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(6,6,'M',0,0,'r',0); 
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(6,5,$mujer,1,1,'C',0);
	//	$fpdf->Cell(25,6,$aInfo['$x_entidad_nacimiento'],0,0,'r',0); 
	$fpdf->Line(110, 101, 200, 101);

	$cedulaSi = ($aInfo['$x_cedulasi'] ==1)?'X':'';
	$cedulaNo = ($aInfo['$x_cedulano'] ==1)?'X':'';
	$firmaSi = ($aInfo['$x_firmasi'] ==1)?'X':'';
	$firmaNo = ($aInfo['$x_firmano'] ==1)?'X':'';

	$fpdf->Ln(4);
	$fpdf->SetX(12);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(60,6,'¿Cuenta con cédula de identificación fiscal?',0,0,'r',0);	
	$fpdf->SetX(74);
	$y = $fpdf->getY();
	$x= $fpdf->getX();
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(6,5,$cedulaSi,1,1,'C',0);	
	$fpdf->SetY($y);
	$fpdf->SetX($x +10);	
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(6,6,'Sí',0,0,'r',0);   
    $fpdf->SetY($y);
    $fpdf->SetX(92);
	$x= $fpdf->getX();
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(6,5,$cedulaNo,1,1,'C',0);
	$fpdf->SetY($y);
	$fpdf->SetX($x +10);	
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(6,6,'No',0,0,'r',0);  

	$fpdf->Ln(8);
	$fpdf->SetX(12);
	$fpdf->Cell(60,6,'¿Cuenta con firma electrónica avanzada? ',0,0,'r',0);	
	$fpdf->SetX(74);
	$y = $fpdf->getY();
	$x= $fpdf->getX();
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(6,5,$firmaSi,1,1,'C',0);	
	$fpdf->SetY($y);
	$fpdf->SetX($x +10);
	$fpdf->SetFont('Arial','B','8');	
	$fpdf->Cell(6,6,'Sí',0,0,'r',0);   
    $fpdf->SetY($y);
    $fpdf->SetX(92);
	$x= $fpdf->getX();
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(6,5,$firmaNo,1,1,'C',0);
	$fpdf->SetY($y);
	$fpdf->SetX($x +10);
	$x= $fpdf->getX();	
	$y = $fpdf->getY();
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(6,6,utf8_decode('No'),0,0,'r',0); 
	$fpdf->Line(110, $y+5, 200, $y+5);

	$fpdf->Ln(8);

	$fpdf->SetTextColor(255,255,255);
	$fpdf->setFillColor(212,38,38);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(0,6,'Datos de Contacto',0,0,'C',1);
	$fpdf->Ln(8);
	$fpdf->SetX(12);
	$fpdf->SetTextColor(0,0,0);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(60,6,'¿Cuenta con correo electrónico? ',0,0,'r',0);	
	$fpdf->SetX(59);
	$y = $fpdf->getY();
	$x= $fpdf->getX();
	
	$fpdf->SetFont('Arial','B','8');	
	$fpdf->Cell(6,6,'Sí',0,0,'r',0);
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(6,5,'X',1,1,'C',0);	
	$fpdf->SetY($y);
	$fpdf->SetX($x +15);

	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(6,6,'No',0,0,'r',0);
	   
    $fpdf->SetY($y);
    $fpdf->SetX(81);
	$x= $fpdf->getX();
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(6,5,'',1,1,'C',0);
	$fpdf->SetY($y);
	$fpdf->SetX($x +12);
	$x= $fpdf->getX();	
	$y = $fpdf->getY();
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(25,5,'Correo electrónico: ',0,0,'R',0);
	$fpdf->SetFont('Arial','','9');
	$fpdf->Cell(83,6,$aInfo['$x_email'],0,0,'L',0);	 
	$fpdf->Line(117, $y+5, 180, $y+5);

	$x_telefono_domicilio  = ($aInfo['$x_reftipotelefono1'] ==1)?$aInfo['$x_telefono1'] :'';
	$x_telefono_oficina  =($aInfo['$x_reftipotelefono1'] ==2)?$aInfo['$x_telefono1'] :'';

	$fpdf->Ln(8);
	$fpdf->SetX(12);
	$fpdf->SetTextColor(0,0,0);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(60,6,'Teléfono de domicilio  ',0,0,'L',0);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(25,5,'No :',0,0,'R',0); 
	$x= $fpdf->getX();	
	$y = $fpdf->getY();
	$fpdf->SetFont('Arial','','9');
	$fpdf->Cell(83,6,$x_telefono_domicilio,0,0,'r',0);	 
	$fpdf->Line($x, $y+5, 180, $y+5);

	$fpdf->Ln(8);
	$fpdf->SetX(12);
	$fpdf->SetTextColor(0,0,0);
	$fpdf->SetFont('Arial','B','9');
	$fpdf->Cell(60,6,'Teléfono de  oficina  ',0,0,'L',0);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(25,5,'No :',0,0,'R',0); 
	$x= $fpdf->getX();	
	$y = $fpdf->getY();
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(83,6,$x_telefono_oficina,0,0,'r',0);	 
	$fpdf->Line($x, $y+5, 180, $y+5);

	$fpdf->Ln(8);
	$fpdf->SetX(12);
	$fpdf->SetTextColor(0,0,0);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(60,6,'Teléfono celular  ',0,0,'L',0);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(25,5,'No :',0,0,'R',0); 
	$x= $fpdf->getX();	
	$y = $fpdf->getY();
	$fpdf->SetFont('Arial','','9');
	$fpdf->Cell(83,6,$aInfo['$x_celular1'],0,0,'r',0);	 
	$fpdf->Line($x, $y+5, 180, $y+5);


	$fpdf->Ln(8);
	$fpdf->SetTextColor(255,255,255);
	$fpdf->setFillColor(212,38,38);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(0,6,'Empleo',0,0,'C',1);
	$fpdf->Ln(8);
	$fpdf->SetX(12);
	$fpdf->SetTextColor(0,0,0);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(20,6,'Dependencia: ',0,0,'r',0);
	$fpdf->SetFont('Arial','','8');
	$x= $fpdf->getX();
	$y = $fpdf->getY();
	$fpdf->Cell(180,6,$aInfo['$x_dependenciaCU'],0,0,'r',0);
	$fpdf->Line($x, $y+5, 200, $y+5);	

	$fpdf->Ln(8);
	$fpdf->SetX(12);
	$fpdf->SetTextColor(0,0,0);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(20,6,'Área o Depto: ',0,0,'r',0);
	$fpdf->SetFont('Arial','','8');
	$x= $fpdf->getX();
	$y = $fpdf->getY();
	$fpdf->Cell(80,6,$aInfo['$x_departamento'],0,0,'r',0);
	$fpdf->Line($x, $y+5, 110, $y+5);	
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(12,6,'Puesto: ',0,0,'r',0);
	$fpdf->SetFont('Arial','','8');
	$x = $fpdf->getX();
	$y = $fpdf->getY();
	$fpdf->Cell(76,6,$aInfo['$x_puesto'],0,0,'L',0);
	$fpdf->Line($x, $y+5, 200, $y+5);

	$fpdf->Ln(8);
	$fpdf->SetX(12);
	$fpdf->SetTextColor(0,0,0);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(15,6,'Dirección: ',0,0,'r',0);
	$fpdf->SetFont('Arial','','8');
	$x= $fpdf->getX();
	$y = $fpdf->getY();
	$fpdf->Cell(180,6,$aInfo['$x_dir_empleo'],0,0,'r',0);
	$fpdf->Line($x, $y+5, 200, $y+5);	


	$fpdf->Ln(8);
	$fpdf->SetX(12);
	$fpdf->SetTextColor(0,0,0);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(30,6,'Numero de empleado: ',0,0,'r',0);
	$fpdf->SetFont('Arial','','8');
	$x= $fpdf->getX();
	$y = $fpdf->getY();
	$fpdf->SetFont('Arial','','9');	
	$fpdf->Cell(30,6,$aInfo['$x_num_empleado'],0,0,'r',0);
	$fpdf->Line($x, $y+5, $x+30, $y+5);	
	$fpdf->SetFont('Arial','B','8');	
	$fpdf->Cell(27,6,'Tiene otro empleo:',0,0,'r',0);


	$y = $fpdf->getY();
	$x= $fpdf->getX();
	$fpdf->SetX($x);
	$otroEmplSi = ($aInfo['$x_otroempleo'] == 1)?'X':'';
	$otroEmplNo = ($aInfo['$x_otroempleo'] == 2)?'X':'';
	$fpdf->SetFont('Arial','B','8');	
	$fpdf->Cell(4,6,'Sí',0,0,'r',0);
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(4,5,$otroEmplSi,1,1,'C',0);	
	$fpdf->SetY($y);
	$fpdf->SetX($x+9);
	$x= $fpdf->getX();
	$y= $fpdf->getY();
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(4,6,'No',0,0,'r',0);
	$fpdf->SetX($x +5);	
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(4,5,$otroEmplNo,1,1,'C',0);
	$fpdf->SetY($y);
	$fpdf->SetX($x +10);
		
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(13,6,'Empresa: ',0,0,'r',0);
	$fpdf->Cell(70,6,$aInfo['$x_segunda_empresa'],0,0,'r',0);	
	$fpdf->Line($x+23, $y+5, 200, $y+5);

	/******************** REFERENCIAS ***************/ 	
	$fpdf->Ln(8);
	$fpdf->SetTextColor(255,255,255);
	$fpdf->setFillColor(212,38,38);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(0,6,'Referencias',0,0,'C',1);
	$fpdf->Ln(8);
	$fpdf->SetX(15);
	$fpdf->SetTextColor(0,0,0);
	$fpdf->SetFont('Arial','B','8');
	$x= $fpdf->getX();
	$y = $fpdf->getY();
	$fpdf->Cell(60,6,'Nombre',1,1,'C',0);	
	$fpdf->SetY($y);
	$fpdf->SetX($x+60);
	$x= $fpdf->getX();
	$y = $fpdf->getY();
	$fpdf->Cell(60,6,'PARENTESCO/RELACIÓN',1,1,'C',0);
	$fpdf->SetY($y);
	$fpdf->SetX($x+60);
	$fpdf->Cell(60,6,'TELÉFONO',1,1,'C',0);
	$fpdf->SetFont('Arial','','8');
		

	
	$fpdf->Ln(0);
	$fpdf->SetX(15);
	$fpdf->SetTextColor(0,0,0);
	$fpdf->SetFont('Arial','','8');
	$x= $fpdf->getX();
	$y = $fpdf->getY();
	$fpdf->Cell(60,5,'Sin referencias',1,1,'C',0);	
	$fpdf->SetY($y);
	$fpdf->SetX($x+60);
	$x= $fpdf->getX();
	$y = $fpdf->getY();
	$fpdf->Cell(60,5,'Sin referencias',1,1,'C',0);
	$fpdf->SetY($y);
	$fpdf->SetX($x+60);
	$fpdf->Cell(60,5,'Sin referencias',1,1,'C',0);
	$fpdf->SetFont('Arial','','8');

	$fpdf->Ln(3);
	$fpdf->SetTextColor(255,255,255);
	$fpdf->setFillColor(212,38,38);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(0,6,'Documentación',0,0,'C',1);
	$fpdf->Ln(8);
	$fpdf->SetX(12);
	$fpdf->SetTextColor(0,0,0);
	//MultiCell(float w, float h, string txt [, mixed border [, string align [, boolean fill]]])
	$fpdf->MultiCell(0,4,'Estoy consciente que para que la presente solicitud pueda ser gestionada por el departamento de crédito debo entregar una identificación oficial, un comprobante de domicilio con antigüedad no mayor a tres meses, los recibos de nómina de las últimas tres quincenas y la carátula del estado de cuenta donde se deposita mi pago de nómina por la Institución donde laboro actualmente',0,'J',0);

	$fpdf->Ln(5);
	$fpdf->SetTextColor(255,255,255);
	$fpdf->setFillColor(212,38,38);
	#$fpdf->SetFont('Arial','B','8');
	#$fpdf->Cell(0,6,'Observaciones del Asesor',0,0,'C',1);
	#$fpdf->Ln(8);
	#$fpdf->SetX(12);
	#$fpdf->SetTextColor(0,0,0);
	#$fpdf->SetFont('Arial','','8');
	//MultiCell(float w, float h, string txt [, mixed border [, string align [, boolean fill]]])
	#$fpdf->MultiCell(0,4,'Declaro bajo protesta de decir verdad que todos los datos recabados en este documento fueron obtenidos en una entrevista personal con el cliente o apoderado que solicita el crédito '."\n".' Nombre del asesor:____________________________________________ Firma:____________________________',1,'J',0);
	

	$fpdf->Ln(40);
	
	$fpdf->SetTextColor(0,0,0);
	$fpdf->Write(8,' ');
	$fpdf->Ln(0);
	$fpdf->SetTextColor(255,255,255);
	$fpdf->setFillColor(212,38,38);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(0,6,'Aviso de Privacidad ',0,0,'C',1);
	$fpdf->Ln(8);
	
	$fpdf->SetTextColor(0,0,0);
	$fpdf->SetFont('Arial','','8');
	//MultiCell(float w, float h, string txt [, mixed border [, string align [, boolean fill]]])
	$fpdf->Write(5,'Manifiesto bajo protesta de decir verdad, que personal facultado de Microfinanciera Crece S.A. de C.V. SOFOM ENR, me ha proporcionado, he leído');
	$fpdf->SetFont('Arial','B','8');

	//MultiCell(float w, float h, string txt [, mixed border [, string align [, boolean fill]]])
	$fpdf->Write(5,' y estoy de acuerdo con los términos y condiciones del de aviso de privacidad y uso de datos personales,');
	$fpdf->SetFont('Arial','','8');

	$fpdf->Write(5,' que se encuentran vigentes a la fecha establecida al calce de esta leyenda. También he sido enterado que puedo consultar el aviso de privacidad en el sitio ');
	$fpdf->SetTextColor(0,0,255);
	$fpdf->SetFont('','U','9');
	$fpdf->Write(5,'www.financieracrea.com/aviso-privacidad.html','https://www.financieracrea.com/aviso-privacidad.html');

	$fpdf->SetTextColor(0,0,0);
	$fpdf->Ln(6);
	$fpdf->SetX(12);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(15,6,'Nombre:',0,0,'C',0);
	$x= $fpdf->getX();
	$y= $fpdf->getY();
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(120,6,$aInfo['$x_nombre_cliente'],0,0,'L',0);
	$fpdf->Line($x, $y+5, 145, $y+5);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(10,6,'Firma:',0,0,'L',0);
	$x= $fpdf->getX();
	$y= $fpdf->getY();
	$fpdf->Line($x, $y+5, 200, $y+5);
	$fpdf->Ln(6);

	
	$fpdf->SetTextColor(255,255,255);
	$fpdf->setFillColor(212,38,38);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(0,6,'Declaración sobre Exposición Política  ',0,0,'C',1);
	$fpdf->Ln(8);
	$fpdf->SetX(12);
	$fpdf->SetTextColor(0,0,0);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Write(4,'"Persona Políticamente Expuesta');
	$fpdf->SetFont('Arial','','8');

	$fpdf->Write(4,' es aquel individuo que desempeña o ha desempeñado funciones públicas destacadas en un país extranjero o en territorio nacional, considerando entre otros, a los jefes de estado o de gobierno, líderes políticos, funcionarios gubernamentales, judiciales o militares de alta jerarquía, altos ejecutivos de empresas estatales o funcionarios o miembros importantes de partidos políticos. Se asimilan a las Personas Políticamente Expuestas, el cónyuge, la concubina, el concubinario y las personas con las que mantengan parentesco por consanguinidad o afinidad hasta el segundo grado, así como las personas morales con las que la Persona Políticamente Expuesta mantenga vínculos patrimoniales. Al respecto, se continuará considerando Personas Políticamente Expuestas nacionales a aquellas personas que hubiesen sido catalogadas con tal carácter, durante el año siguiente a aquel en que hubiesen dejado su encargo.');
	$fpdf->Ln(5);
	$fpdf->Write(4,'A mi leal saber y entender, y de acuerdo con la definición anterior ¿me considero o considero que alguna persona con la que mantenga parentesco por consanguinidad o afinidad hasta el segundo grado como ');
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Write(4,' persona políticamente expuesta?');
	$fpdf->Ln(5);
	$ppeSi = ($aInfo['$x_ppe'] == 1)?'X':'';
	$ppeNo = ($aInfo['$x_ppe'] == 2)?'X':'';
	$fpdf->SetFont('Arial','','8');
	$fpdf->SetX(85);	
	$y= $fpdf->getY();
	$x= $fpdf->getX();
	$fpdf->Cell(4,6,'Sí',0,0,'r',0);
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(5,5,$ppeSi,1,1,'C',0);	
	$fpdf->SetY($y);
	$fpdf->SetX($x+20);
	$x= $fpdf->getX();
	$y= $fpdf->getY();
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(4,6,'No',0,0,'r',0);
	$fpdf->SetX($x +5);	
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(5,5,$ppeNo,1,1,'C',0);
	$fpdf->Ln(1);
	$fpdf->SetFont('Arial','','8');

	$fpdf->Write(4,'En caso afirmativo,');
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Write(4,' Nombre delfamiliar:');
	$fpdf->SetX(65);	
	$fpdf->SetFont('Arial','','8');
	$y= $fpdf->getY();
	$x= $fpdf->getX();
	$fpdf->Cell(80,5,$aInfo['$x_fam_nomb'],0,0,'L',0);
	$fpdf->Line($x, $y+5, 200, $y+5);

	$fpdf->Ln(6);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(52,5,'Puesto político y parentesco:',0,0,'R',0);
	$fpdf->SetFont('Arial','','8');
	$y= $fpdf->getY();
	$x= $fpdf->getX();
	$fpdf->Cell(80,5,$aInfo['$x_parentesco'],0,0,'L',0);
	$fpdf->Line($x, $y+5, 200, $y+5);

	$fpdf->Ln(6);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(47,5,'Nombre del prospectode crédito:',0,0,'L',0);
	$fpdf->SetFont('Arial','','8');
	$y= $fpdf->getY();
	$x= $fpdf->getX();
	$fpdf->Cell(83,5,$aInfo['$x_nombre_cliente'],0,0,'L',0);
	$fpdf->Line($x, $y+5, 140, $y+5);
	$y= $fpdf->getY();
	
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(10,5,'Firma: ',0,0,'L',0);
	$x= $fpdf->getX();
	$fpdf->Line($x, $y+5, 200, $y+5);

	$fpdf->Ln(8);
	$fpdf->SetTextColor(255,255,255);
	$fpdf->setFillColor(212,38,38);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(0,6,'Términos y Condiciones',0,0,'C',1);
	$fpdf->Ln(5);
	$fpdf->SetTextColor(0,0,0);
	$fpdf->SetFont('Arial','','8');
	$fpdf->Write(5,'1.- Veracidad de la información presentada');
	$fpdf->Ln(5);

	$fpdf->Write(3,'Manifiesto bajo protesta de decir verdad, que la información y toda clase de documentos presentados a Microfinanciera Crece S.A. de C.V. SOFOM ENR, en lo sucesivo "CREA", mediante la presente solicitud de crédito y adjuntos, son de carácter lícito y no carecen de validez alguna, Así mismo, estoy consciente de las consecuencias legales aplicables en caso de falta a la verdad. Asimismo, manifiesto que la información contenida en la presente solicitud es resultado de la entrevista personal que CREA realizó al (a la) suscrito(a) a través de su personal facultado.');
	$fpdf->Ln(4);
	$fpdf->Write(4,'2.- Legalidad de sus ingresos y utilización de los recursos.');
	$fpdf->Ln(4);
	$fpdf->Write(3,'Manifiesto bajo protesta de decir verdad, que no se me ha sido sentenciado por delitos en materia de operaciones con recursos de procedencia ilícita y financiamiento al terrorismo, o de cualquier otra índole. También me comprometo a utilizar los recursos proporcionados por CREA para un fin lícito y deslindo a CREA de cualquier responsabilidad o consecuencia derivada de hacer un mal uso de los recursos.');
	$fpdf->Ln(4);
	$fpdf->Write(4,'3.- Buro de Crédito');
	$fpdf->Ln(4);
	$fpdf->Write(3,'Por este conducto autorizo expresamente a CREA, consultar mi historial crediticio ante cualquier Sociedad de Información Crediticia, teniendo pleno conocimiento del alcance de la información que la Sociedad proporcionará a dicha Institución, así como del uso que hará de tal información. Autorizo que dichas consultas las pueda realizar de manera periódica con posterioridad de hasta 3 años contados a partir de la fecha del presente documento o durante la vigencia de mi relación jurídica y/o comercial con CREA. Estoy consciente y acepto que este documento, así como la documentación anexa quede bajo propiedad de CREA, por lo que no será devuelto sin importar si el crédito es autorizado.');
	$fpdf->Ln(4);
	$fpdf->Write(4,'4.- Conocimiento Costo Anual Total de Crédito Ofrecido');
	$fpdf->Ln(4);

	$fpdf->Write(3,'Por este medio expreso mi consentimiento que, a través del personal facultado de CREA, he sido enterado del Costo Anual Total del crédito que estoy interesado en celebrar. También he sido enterado de la tasa de interés moratoria que se cobrará en caso de presentar atraso(s) en alguno(s) de los vencimientos del préstamo');

	$fpdf->SetFont('Arial','B','8');
	$fpdf->Ln(4);
	$fpdf->SetX(80);
	$fpdf->Cell(40,6,'Acepto Términos y Condiciones  ',0,0,'C',0);
	$fpdf->Ln(4);
	$fpdf->SetX(120);

	$fpdf->Cell(15,6,'Fecha:',0,0,'C',0);
	$x= $fpdf->getX();
	$y= $fpdf->getY();
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(30,6,$aInfo['$x_fecha_firma_contrato_letras'],0,0,'L',0);
	$fpdf->SetFont('Arial','B','8');

	$fpdf->Line($x, $y+5, 200, $y+5);
	$fpdf->Ln(4);
	$fpdf->SetX(12);
	$fpdf->Cell(15,6,'Nombre:',0,0,'C',0);
	$x= $fpdf->getX();
	$y= $fpdf->getY();
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(120,6,$aInfo['$x_nombre_cliente'],0,0,'L',0);
	$fpdf->Line($x, $y+5, 145, $y+5);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(10,6,'Firma:',0,0,'L',0);
	$x= $fpdf->getX();
	$y= $fpdf->getY();
	$fpdf->Line($x, $y+5, 200, $y+5);

	$fpdf->Ln(6);
	$fpdf->SetTextColor(255,255,255);
	$fpdf->setFillColor(212,38,38);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(0,6,'Actuación por Cuenta Propia y Proveedor de Recursos ',0,0,'C',1);
	$fpdf->SetTextColor(0,0,0);
	$fpdf->SetFont('Arial','','8');
	$fpdf->Ln(6);
	$fpdf->Write(4,'El verdadero propietario de los recursos que se otorgarán bajo contrato por parte de Microfinanciera Crece S.A. de C.V. SOFOM ENR será la persona que dé uso, disfrute, aprovechamiento, dispersión o disposición de los mismos. Declaro bajo protesta de decir verdad, que para efectos de la realización de las operaciones con Microfinanciera Crece, S.A. de C.V. SOFOM ENR. Estoy actuando de la siguiente manera:');
	$fpdf->Ln(5);
	$fpdf->SetFont('Arial','B','8');
	$y= $fpdf->getY();
	$fpdf->Cell(40,6,'Por cuenta propia',0,0,'L',0);
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(5,5,'X',1,1,'C',0);
	$x= $fpdf->getX();

	$fpdf->SetY($y);
	$fpdf->SetX($x +40);

	$fpdf->Cell(20,6,'',0,0,'L',0);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(40,6,'Por cuenta de un tercero',0,0,'L',0);
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(5,5,'',1,1,'C',0);
	$fpdf->Ln(2);
	$fpdf->SetFont('Arial','B','8');
	$y= $fpdf->getY();
	$x= $fpdf->getX();

	$fpdf->Cell(30,6,'Nombre del tercero:',0,0,'L',0);
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(160,5,$aInfo['$x_tecero'],0,0,'L',0);
	$y= $fpdf->getY();
	$fpdf->Line($x+30, $y+5, 200, $y+5);
	$fpdf->Ln(8);
	$fpdf->Write(3,'¿Será usted quien aporte los recursos de manera regular para el cumplimiento de las obligaciones derivadas del contrato suscrito con Microfinanciera Crece, S.A. de C.V. SOFOM ENR?');
	$fpdf->Ln(4);
	$fpdf->SetX(80);
	$y = $fpdf->getY();
	$fpdf->Cell(6,5,'Sí',0,0,'L',0);
	$fpdf->Cell(6,5,'X',1,1,'C',0);
	$fpdf->SetY($y);
	$fpdf->SetX(95);
	$fpdf->Cell(6,5,'No',0,0,'L',0);
	$fpdf->Cell(6,5,'',1,1,'L',0);

	$fpdf->Ln(2);
	$fpdf->SetFont('Arial','B','8');
	$y= $fpdf->getY();
	$x= $fpdf->getX();

	$fpdf->Cell(40,6,'Proveedor de los recursos:',0,0,'L',0);
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(160,5,$aInfo['$x_proveedor_rec'],0,0,'L',0);
	$y= $fpdf->getY();
	$fpdf->Line($x+40, $y+5, 200, $y+5);

	$fpdf->Ln(5);
	$fpdf->SetFont('Arial','B','8');
	$y= $fpdf->getY();
	$x= $fpdf->getX();

	$fpdf->Cell(47,6,'Nombre del prospecto de crédito:',0,0,'L',0);
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(160,5,$aInfo['$x_prospecto'],0,0,'L',0);
	$y= $fpdf->getY();
	$fpdf->Line($x+47, $y+5, 200, $y+5);


	$fpdf->Ln(4);

	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(10,6,'Fecha',0,0,'L',0);
	$x= $fpdf->getX();
	$y= $fpdf->getY();
	$fpdf->SetFont('Arial','','8');
	$fpdf->Cell(120,6,$aInfo['$x_fecha_firma_contrato_letras'],0,0,'L',0);
	$fpdf->Line($x, $y+5, 140, $y+5);
	$fpdf->SetFont('Arial','B','8');
	$fpdf->Cell(10,6,'Firma:',0,0,'L',0);
	$x= $fpdf->getX();
	$y= $fpdf->getY();
	$fpdf->Line($x, $y+5, 200, $y+5);
	
	$idContratoGlobal = $this->getIdContratoGlobal();

	$carpeta1 ="../upload/".$idContratoGlobal."/".$carpeta."/";
	$nameFile = $idContratoGlobal."_".$nombreFile.".".time().".pdf";
	$nombreContrato = $carpeta1.$nameFile;
		if(!file_exists($carpeta1) ){			
			 mkdir($carpeta1, 0777, true);			
		}
		

	$fpdf->outPut($nombreContrato,'F');	
	array_unshift($this->arrDoctos,$nombreContrato);
	#$fpdf->outPut();
	

}
public function domiciliacionDeRecuersos(){

	$x_nombre_proveedor = 'NOMBRE PROVEEDOR';
	$x_nombre_credito = 'NOMBRE CREDITO';
	$x_periodicidad_facturacion = 'PERIODICIDAD DE FACTURACION';
	$x_dia_pago = 'DIA DE PAGO';
	$x_banco = 'BANCO';
	$x_no_tarjeta = 'NO TARJETA';
	$x_clabe = 'CLABE';
	$x_monto_cargo_autorizado = 'MONTO CARGO';
	$x_monto_fijo = 'MONTO FIJO';
	$x_fecha_vencimiento = 'FECHA VENCIMIENTO';
	$this->arrayDatosCliente['$x_nombre_proveedor'] = $x_nombre_proveedor;
	$this->arrayDatosCliente['$x_nombre_credito'] = $x_nombre_credito; 
	$this->arrayDatosCliente['$x_periodicidad_facturacion'] =  $x_periodicidad_facturacion;
	$this->arrayDatosCliente['$x_dia_pago'] = $x_dia_pago;
	$this->arrayDatosCliente['$x_banco'] = $x_banco;
	$this->arrayDatosCliente['$x_no_tarjeta'] = $x_no_tarjeta;
	$this->arrayDatosCliente['$x_clabe'] = $x_clabe;
	$this->arrayDatosCliente['$x_monto_cargo_autorizado'] = $x_monto_cargo_autorizado;
	$this->arrayDatosCliente['$x_monto_fijo'] = $x_monto_fijo;
	$this->arrayDatosCliente['$x_fecha_vencimiento'] = 	$x_fecha_vencimiento;
#echo "<br>\n ***** 1";
#	 print_r($this->arrayDatosCliente);
#	 echo "<br>\n 1*****<br>\n";

}

public function creditoRevolvente(){
	$x_comision_por_dispocicion = 600;

	$this->arrayDatosCliente['$x_comision_por_dispocicion'] = '$'.$x_comision_por_dispocicion;
	
}



                 
public function descuentosPorComision(){
	#echo "***Carga datos**";
	$arrayDatosCliente = array();
	$id = $this->idContratoGlobal;

	$query = new Query();
	$selectCG = "SELECT nombre, paterno, materno FROM dbcontratosglobales WHERE idcontratoglobal = ".$id." ";
	$query->setQuery($selectCG);
	$resD = $query->eject();
	$rw = $query->fetchObject($resD); //zoa				
	$arrayDatosCliente['$x_nombre_cliente'] = $rw->nombre." ".$rw->paterno." ".$rw->materno;

	return $arrayDatosCliente;
}

public function avisoDePrivacidad(){
	#echo "***Carga datos**";
	$arrayDatosCliente = array();
	$id = $this->idContratoGlobal;

	$query = new Query();
	$selectCG = "SELECT nombre, paterno, materno FROM dbcontratosglobales WHERE idcontratoglobal = ".$id." ";
	$query->setQuery($selectCG);
	$resD = $query->eject();
	$rw = $query->fetchObject($resD); //zoa				
	$arrayDatosCliente['$x_nombre_cliente'] = $rw->nombre." ".$rw->paterno." ".$rw->materno;

	return $arrayDatosCliente;
}


public function obtenerFechaEnLetra($fecha){
	    $dia= $this->conocerDiaSemanaFecha($fecha);
	    $num = date("j", strtotime($fecha));
	    $anno = date("Y", strtotime($fecha));
	    $mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
	    $mes = $mes[(date('m', strtotime($fecha))*1)-1];
	    return $dia.', '.$num.' de '.$mes.' del '.$anno;
	}

public function obtenerFechaEnLetraFormatoContrato($fecha){
	    $dia= $this->conocerDiaSemanaFecha($fecha);
	    $num = date("j", strtotime($fecha));
	    $anio = date("Y", strtotime($fecha));
	    $mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
	    $mes = $mes[(date('m', strtotime($fecha))*1)-1];
	    return  "a los ".$num.' días  del mes de '.$mes.' de '.$anio;
	}

public function obtenerFechaEnFormatoDomiciliacion($fecha){
	    $dia= $this->conocerDiaSemanaFecha($fecha);
	    $num = date("j", strtotime($fecha));
	    $anio = date("Y", strtotime($fecha));
	    $mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
	    $mes = $mes[(date('m', strtotime($fecha))*1)-1];
	    return  " ".$num.' de '.$mes.' de '.$anio.".";
	}

public function obtenerFechaEnLetraFormatoElaboracionTablaAmortizacion($fecha){
	    $dia= $this->conocerDiaSemanaFecha($fecha);
	    $num = date("j", strtotime($fecha));
	    $anio = date("Y", strtotime($fecha));
	    $mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
	    $mes = $mes[(date('m', strtotime($fecha))*1)-1];
	    return  "el día  ".$num.'  del mes de '.$mes.' del año '.$anio;
	}

public function obtenerFechaEnFormatoConsentimientoRetencion($fecha){
	    $dia= $this->conocerDiaSemanaFecha($fecha);
	    $num = date("j", strtotime($fecha));
	    $anio = date("Y", strtotime($fecha));
	    $mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
	    $mes = $mes[(date('m', strtotime($fecha))*1)-1];
	    return  "Ciudad de México, ".$num.' de '.$mes.' de '.$anio.".";
	}

public function conocerDiaSemanaFecha($fecha) {
	    $dias = array('domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado');
	    $dia = $dias[date('w', strtotime($fecha))];
	    return $dia;
	}


	public function plazoCredito($numeroPagos, $formaPago){
		$cadena = '';

		$arraySingular = array(1=>'semana', 2=>'catorcena', 3=>'mes',4=>'quincena');
		$arrayPlural = array(1=>'semanas', 2=>'catorcenas', 3=>'meses',4=>'quincenas');
		if($numeroPagos>1){
			$cadena =  $numeroPagos." ".$arrayPlural[$formaPago];
		}else{
			$cadena =  $numeroPagos." ".$arraySingular[$formaPago];
		}
		return $cadena;

	}

	public function rmDir_rf($carpeta)
    {
    	$id = $this->idContratoGlobal;
    	
      $carpeta = "../upload/".$id."/".$carpeta;
      if(file_exists($carpeta) ){      	 
      	 foreach(glob($carpeta . "/*") as $archivos_carpeta){             
        if (is_dir($archivos_carpeta)){
          $this->rmDir_rf($archivos_carpeta);        
        } else {
        unlink($archivos_carpeta);
    	}
      }
      rmdir($carpeta); 
      }else{
      	echo "no existe carpeta =>".$carpeta."<br>";
      	
      }
    }

    public function crearIndexCarpetasDoctos($carpeta)
    {    	  		
    	$id = $this->idContratoGlobal;  
    	if(!empty($carpeta)){
    		$carpeta = $carpeta;
    	}else{
    		$carpeta = "../upload/".$id;
    	}       		
        	if(file_exists($carpeta) ){        		      	 
	      	 	foreach(glob($carpeta . "/*") as $archivos_carpeta){             
	        		if (is_dir($archivos_carpeta)){
	          			$this->crearIndexCarpetasDoctos($archivos_carpeta);        
	        		} else {
	        			if(!$fh = fopen($carpeta."/index.php", 'w')){
	        				echo "Error al crear index";
	        			}
	    			}
	    		}
	      		if(!$fh = fopen($carpeta."/index.php", 'w')){
	        				echo "Error al crear index";
	        			}
	      	}
    }





}







?>