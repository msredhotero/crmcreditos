<?php
#include ('../../reportes/PDFReportesCrea.class.php');
class RiesgoPLD{

	private $idContratoGlobal = '';	
	private $idCliente = '';	
	private $fecha = '';
	private $dataContrato = array();
	private $elementos = array();
	private $indicadores = array();
	private $variables = array();
	private $arrayElementos = array();
	private $arrayVariables = array();


	public function __construct($idContratoGlobal= NULL){
		$dataContrato = array();		
		if(!empty($idContratoGlobal)){
			$this->idContratoGlobal = $idContratoGlobal;
			$datosContrato = new ServiciosSolicitudes($idContratoGlobal);
			$datosContrato->cargarDatosContratoGlobal();
			
			$this->dataContrato = $datosContrato->getDatos();
			$this->cargarVariables();
			$this->registraVariables();
			$this->generaComprobatePDF();

		}
		
	}

	public function setData($data){
		$this->dataContrato = $data;
	}

	public function getData($data){
		return $this->dataContrato ;
	}

	public function getDato($campo)
	{
		if(array_key_exists($campo, $this->dataContrato)){
			return $this->dataContrato->$campo;
		}
		
	}
	

	public function registraVariables(){
		$query = new Query();
		$listadoVariables = array();
		$riesgoCliente = 0;

		$listadoVariables = $this->arrayVariables;
		$refcontratoglobal = ($this->idContratoGlobal >0)?$this->idContratoGlobal:0;
		$refCliente = ($this->idCliente >0)?$this->idCliente:0;

		// verificamos que no exista registro para este contrato global o cliente, si es asi se insertan los datos en la tabla

		$existeRegistro = 0;
		if($refCliente > 0){
		 $sqlBuscariesgo = " SELECT * FROM dbriesgoimpactoclientes WHERE refcliente = ".$refcliente;
		}else{
		 $sqlBuscariesgo = " SELECT * FROM dbriesgoimpactoclientes WHERE refcontratoglobal = ".$refcontratoglobal;
		}
		#echo "sql :", $sqlBuscariesgo;
		$query->setQuery($sqlBuscariesgo);
		$resRiesgo = $query->eject();
		$registrosRiesgos = $query->numRows($resRiesgo);
		#echo "resgistros =>".$registrosRiesgos;
		$existeRegistro = ($registrosRiesgos>0);
		if(!$existeRegistro){
			foreach ($listadoVariables as $variable => $valores) {
				if(!array_key_exists($valores['idelemento'], $this->arrayElementos)){
				 	$this->arrayElementos[$valores['idelemento']]=array('elemento'=>$valores['elemento'], 
				 													  'pesoElemento'=>$valores['pesoEmelemento']);
				}

				$impactoPonderado = ($valores['pesoIndicador'] * $valores['valorIndicador'])/100;

				$sqlInsert = "INSERT INTO `dbriesgoimpactoindicadores`
							 (`idriesgoimpactoindicador`, `refcontratoglobal`, `refcliente`, 
							 `refriesgoelemento`, `refriesgoindicador`, `refriesgovariable`,
							  `indicador`, `variable`, `impacto`, `ponderacionindicador`, `impactoponderado`)";
				$sqlInsert .="  VALUES (NULL, $refcontratoglobal, $refCliente, ".$valores['idelemento'].", ";
				$sqlInsert .= "".$valores['idIndicador'].", ".$valores['idVarible']."";
				$sqlInsert .= ", '".$valores['indicador']."', '".$valores['variable']."'";
				$sqlInsert .= ",". $valores['valorIndicador'].", ".$valores['pesoIndicador'].", $impactoPonderado); ";
				$query->setQuery($sqlInsert);
				#echo $sqlInsert ."<br>";
				$query->eject();
				$query->commitTrans();
				
			}

			#echo"<pre>";
			#print_r($this->arrayElementos);
			#echo"</pre>";
		
			foreach ($this->arrayElementos as $idElemento => $valores) {
				//sumatoria

				$sqlSum = "SELECT SUM(impactoponderado) as riesgoImpacto
							FROM dbriesgoimpactoindicadores 
							WHERE (refcontratoglobal = $refcontratoglobal || ( refcliente >0 and refcliente= $refCliente)) && (refriesgoelemento = $idElemento)";
				$query->setQuery($sqlSum);
				$resSum =$query->eject();
				$objSuma = $query->fetchObject($resSum);
				$riesgoImpacto = $objSuma->riesgoImpacto;
				$riesgoPonderado = round($riesgoImpacto * $valores['pesoElemento'])/100;
				$riesgoCliente = $riesgoCliente + $riesgoPonderado;
				#echo "<br> riesgo ponderado =>  ".$riesgoPonderado;
				#echo  "  vale==>  ".$riesgoCliente;

				$sqlInsert = "INSERT INTO `dbriesgoimpactoelementos`
							 (`idriesgoimpactoelemento`, `refcontratoglobal`, `refcliente`, `refriesgoelemento`, `elemento`, `riesgo`, `ponderacion`, `riesgoponderado`)
							 VALUES (NULL, $refcontratoglobal, $refCliente, $idElemento, '".$valores['elemento']."', $riesgoImpacto,".$valores['pesoElemento'].", $riesgoPonderado);";
				$query->setQuery($sqlInsert);
				$query->eject();
				$query->commitTrans();

							#echo "<br>".$sqlSum ;
			}

			// insertamos en riesgo cliente

			$sqlNivelRiesgo = "SELECT * FROM `tbriesgoniveles` ";
			$query->setQuery($sqlNivelRiesgo);
			$resRiesgos =$query->eject();
			#echo  "vale==>".$riesgoCliente;
			while($objNivelR = $query->fetchObject($resRiesgos)){
				$idNivelr = $objNivelR->idriesgonivel;
				$descripcion = $objNivelR->descripcion;
				$valor =  $objNivelR->valor;
				#echo "valor=>".$valor;

				$arraLimites = explode("-",$valor);
				$inferior = $arraLimites[0];
				$superior = $arraLimites[1];
				#echo "inferiros".$arraLimites[0]." sup ". $arraLimites[1];
				#echo "<br>riego cliente".$riesgoCliente;
				#if(floatval($riesgoCliente) >=  floatval($arraLimites[0]) && floatval($riesgoCliente) <= floatval($arraLimites[0])){

				if(floatval($riesgoCliente) >=  $inferior && floatval($riesgoCliente) <= $superior){	
					#echo "**************";
					$insertRiesgoCliente = "INSERT INTO `dbriesgoimpactoclientes` 
								(`idriesgoimpactocliente`, `refcontratoglobal`, `refcliente`, `refriesgonivel`, `descripcion`, `limites`, `valor`)
								 VALUES (NULL, $refcontratoglobal,  $refCliente, $idNivelr, '".$descripcion."', '".$valor."', $riesgoCliente);";
					#echo $insertRiesgoCliente;			 
					$query->setQuery($insertRiesgoCliente);			 
					$query->eject();
					$query->commitTrans();
				}
			}
		}// $existeregistro
		
	}

	public function registrarElemntos(){

		$query = new Query();
		$sqlVaribles = " SELECT re.idriesgoelemento, re.descripcion as elemento, re.peso as pesoelemento
						 FROM  tbriesgoelementos re ON re.idriesgoelemento = ri.refriesgoelemento 
						WHERE rv.activo = 1";
		$query->setQuery($sqlVaribles);
		$resVariables = $query->eject();
		while($objVariable = $query->fetchObject($resVariables)){

	}
}


	public function cargarVariables (){
		$arrayVariables =  array();
		$query = new Query();
		$sqlVaribles = " SELECT re.idriesgoelemento, re.descripcion as elemento, re.peso as pesoelemento, ri.idriesgoindicador, ri.descripcion as indicador, ri.peso as pesoindicador, ri.maximo, ri.minimo, ri.tablasql as tabla, ri.variablesql as campo, rv.idriesgovariable, rv.descripcion as variable, rv.peso as pesovariable, rv.tipovariable as tipo, rv.valoresvariable as valoresposibles  FROM tbriesgovariables rv
						JOIN tbriesgoindicadores ri ON ri.idriesgoindicador = rv.refriesgoindicador
						JOIN tbriesgoelementos re ON re.idriesgoelemento = ri.refriesgoelemento 
						WHERE rv.activo = 1";
		$query->setQuery($sqlVaribles);
		$resVariables = $query->eject();
		while($objVariable = $query->fetchObject($resVariables)){
			$arrayDatosvariable = array();				 	
			$tipoVariable = $objVariable->tipo;
			$opciones =  $objVariable->valoresposibles;
			$campo = $objVariable->campo;
			$valorVaribleContrato = $this->getDato($campo);
			$pesoVariable = $objVariable->pesovariable;
					
			
			$arrayDatosvariable['idelemento'] = $objVariable->idriesgoelemento;
			$arrayDatosvariable['elemento'] = $objVariable->elemento;
			$arrayDatosvariable['pesoEmelemento'] = $objVariable->pesoelemento;
			$arrayDatosvariable['idIndicador'] = $objVariable->idriesgoindicador;
			$arrayDatosvariable['indicador'] = $objVariable->indicador;
			$arrayDatosvariable['pesoIndicador'] = $objVariable->pesoindicador;
			
			$arrayDatosvariable['idVarible'] = $objVariable->idriesgovariable;
			$arrayDatosvariable['variable'] = $objVariable->variable;	
			$arrayDatosvariable['valorVariable'] = $objVariable->pesovariable;
			$arrayDatosvariable['valorContrato'] = $valorVaribleContrato;
			$arrayDatosvariable['query'] = $tipoVariable ." ==> ".$opciones;
			$arrayDatosvariable['campo'] = $campo;

			$valorIndicador = false;

			switch ($tipoVariable) {
				case 'array':
					$arrayOpciones = explode(",", $opciones);
					if(in_array($valorVaribleContrato, $arrayOpciones)){
						$arrayDatosvariable['valorIndicador'] = $pesoVariable;
						$valorIndicador = true;
					}
					break;
				case '!array':
					$arrayOpciones = explode(",", $opciones);
					if(!in_array($valorVaribleContrato, $arrayOpciones)){
						$arrayDatosvariable['valorIndicador'] = $pesoVariable;
						$valorIndicador = true;
					}	

				case '>':
					if($valorVaribleContrato > $opciones ){
						$arrayDatosvariable['valorIndicador'] = $pesoVariable;
						$valorIndicador = true;
					}
					break;

				case '>=':
					if($valorVaribleContrato >= $opciones ){
						$arrayDatosvariable['valorIndicador'] = $pesoVariable;
						$valorIndicador = true;
					}
					break;

				case '<':
					if($valorVaribleContrato < $opciones ){
						$arrayDatosvariable['valorIndicador'] = $pesoVariable;
						$valorIndicador = true;
					}
					break;
				case '<=':
					if($valorVaribleContrato <= $opciones ){
						$arrayDatosvariable['valorIndicador'] = $pesoVariable;
						$valorIndicador = true;
					}
					break;					
				case '==':
					if($valorVaribleContrato == $opciones ){
						$arrayDatosvariable['valorIndicador'] = $pesoVariable;
						$valorIndicador = true;
					}
					break;
					
				case '!=':
					if($valorVaribleContrato != $opciones ){
						$arrayDatosvariable['valorIndicador'] = $pesoVariable;
						$valorIndicador = true;
					}					
					break;
				case 'rango':
					$arrayOpciones = explode("-", $opciones);
					if($valorVaribleContrato >= $arrayOpciones[0] &&  $valorVaribleContrato <= $arrayOpciones[1] ){
						$arrayDatosvariable['valorIndicador'] = $pesoVariable;
						$valorIndicador = true;
					}					
					break;
				case 'UDI>':
					$refudi = ($this->getDato('refudi')>0)?$this->getDato('refudi'):0;
					$valorUdi = $this->regresaValorUdi($refudi);
					if($valorVaribleContrato > ($valorUdi * $opciones )){
						$arrayDatosvariable['valorIndicador'] = $pesoVariable;
						$valorIndicador = true;
						$arrayDatosvariable['valorcalculo'] = ($valorUdi * $opciones);
					}					
					break;
				case 'UDI<':
					$refudi = ($this->getDato('refudi')>0)?$this->getDato('refudi'):0;
					$valorUdi = $this->regresaValorUdi($refudi);
					if($valorVaribleContrato < ($valorUdi * $opciones )){
						$arrayDatosvariable['valorIndicador'] = $pesoVariable;
						$valorIndicador = true;
						$arrayDatosvariable['valorcalculo'] = ($valorUdi * $opciones);
					}	
					break;
				case 'UDI>=':
					$refudi = ($this->getDato('refudi')>0)?$this->getDato('refudi'):0;
					$valorUdi = $this->regresaValorUdi($refudi);
					if($valorVaribleContrato >= ($valorUdi * $opciones )){
						$arrayDatosvariable['valorIndicador'] = $pesoVariable;
						$valorIndicador = true;
						$arrayDatosvariable['valorcalculo'] = ($valorUdi * $opciones);
					}					
					break;
				case 'UDI<=':
					$refudi = ($this->getDato('refudi')>0)?$this->getDato('refudi'):0;
					$valorUdi = $this->regresaValorUdi($refudi);
					if($valorVaribleContrato <= ($valorUdi * $opciones )){
						$arrayDatosvariable['valorIndicador'] = $pesoVariable;
						$valorIndicador = true;
						$arrayDatosvariable['valorcalculo'] = ($valorUdi * $opciones);
					}	
					break;	
				case 'rangoUDI':
					$refudi = ($this->getDato('refudi')>0)?$this->getDato('refudi'):0;
					$valorUdi = $this->regresaValorUdi($refudi);
					$arrayOpciones = explode("-", $opciones);
					if(($valorVaribleContrato >= ($valorUdi * $arrayOpciones[0]) )&&  ($valorVaribleContrato <= ($valorUdi * $arrayOpciones[1]) )){
						$arrayDatosvariable['valorIndicador'] = $pesoVariable;
						$valorIndicador = true;
						$arrayDatosvariable['valorcalculo'] = (($valorUdi * $arrayOpciones[0]) ."-". ($valorUdi * $arrayOpciones[1]));
					}					
					break;

				case 'edadrango':					
					$edad = $this->calcularEdad($valorVaribleContrato);
					$arrayOpciones = explode("-", $opciones);
					if($edad >= $arrayOpciones[0]  &&  $edad <=  $arrayOpciones[1] ){
						$arrayDatosvariable['valorIndicador'] = $pesoVariable;
						$valorIndicador = true;
						$arrayDatosvariable['valorcalculo'] = $edad;
					}					
					break;	
				case 'edad>':					
					$edad = $this->calcularEdad($valorVaribleContrato);					
					if($edad > $opciones ){
						$arrayDatosvariable['valorIndicador'] = $pesoVariable;
						$valorIndicador = true;
						$arrayDatosvariable['valorcalculo'] = $edad;
					}					
					break;										
				
				default:
					$arrayDatosvariable['valorIndicador'] = 0;
					$valorIndicador = true;
					break;
			}
			
			if($valorIndicador ){
				$arrayVariables[] = $arrayDatosvariable;
			}
			

		} 

		$this->arrayVariables = $arrayVariables;
	}

	

	public function calcularEdad($fechaNacimiento){
		$fechaNac = str_replace('-','',$fechaNacimiento);
		
		$query  = new Query();
		$sqlFecha = "SELECT FLOOR( (curdate() - ".$fechaNac.") / 10000 ) AS edad";
		$query->setQuery($sqlFecha);
		$res = $query->eject();
		$objFecha = $query->fetchObject($res);
		$edadCliente = $objFecha->edad;
		return $edadCliente ;
	}

	public function regresaValorUdi($refUDI){
		$query = new Query();
		if($refUDI>0){
			$where =  " WHERE idudi = ".$refUDI." ";
		}
		$sqlUdi = "SELECT descripcion  as valor	 FROM tbudi". $where ." ORDER BY idudi DESC LIMIT 0,1";
		$query->setQuery($sqlUdi);
		$resUdi = $query->eject();
		$objUDI = $query->fetchObject($resUdi);
		$valorUdi =$objUDI->valor;

		return $valorUdi;
	}

	public function cargarIndicadoresDB(){
		$query = new Query();
		$indicadores = array();		
		$idContratoGlobal = $this->idContratoGlobal;
		$idCliente = $this->idCliente;
		$where = ($idCliente > 0)? ' WHERE refcliente = '.$idCliente:($idContratoGlobal>0)?' WHERE refcontratoglobal = '.$idContratoGlobal:' WHERE refcontratoglobal = 0';
		$sqlIndicadores ="SELECT * FROM dbriesgoimpactoindicadores".$where;
		$query->setQuery($sqlIndicadores);
		$resINDI = $query->eject();
		while($objIndicador = $query->fetchObject($resINDI)){
			$arrayDatosvariable = array();
			$arrayDatosvariable['refriesgoelemento'] = $objIndicador->refriesgoelemento;
			$arrayDatosvariable['refriesgoindicador'] = $objIndicador->refriesgoindicador;
			$arrayDatosvariable['refriesgovariable'] = $objIndicador->refriesgovariable;			
			$arrayDatosvariable['indicador'] = $objIndicador->indicador;
			$arrayDatosvariable['variable'] = $objIndicador->variable;
			$arrayDatosvariable['impacto'] = $objIndicador->impacto;			
			$arrayDatosvariable['ponderacionindicador'] = $objIndicador->ponderacionindicador;
			$arrayDatosvariable['impactoponderado'] = $objIndicador->impactoponderado;	
			$indicadores[] = $arrayDatosvariable;
		}
		return $indicadores;
	}

	public function cargarElementosDB(){
		$query = new Query();
		$elementos = array();		
		$idContratoGlobal = $this->idContratoGlobal;
		$idCliente = $this->idCliente;
		$where = ($idCliente > 0)? ' WHERE refcliente = '.$idCliente:($idContratoGlobal>0)?' WHERE refcontratoglobal = '.$idContratoGlobal:' WHERE refcontratoglobal = 0';
		$sqlIndicadores ="SELECT * FROM dbriesgoimpactoelementos".$where;
		$query->setQuery($sqlIndicadores);
		$resINDI = $query->eject();
		while($objIndicador = $query->fetchObject($resINDI)){
			$arrayDatosvariable = array();
			$arrayDatosvariable['refriesgoelemento'] = $objIndicador->refriesgoelemento;
			$arrayDatosvariable['elemento'] = $objIndicador->elemento;			
			$arrayDatosvariable['riesgo'] = $objIndicador->riesgo;			
			$arrayDatosvariable['ponderacion'] = $objIndicador->ponderacion;
			$arrayDatosvariable['riesgoponderado'] = $objIndicador->riesgoponderado;	
			$elementos[] = $arrayDatosvariable;
		}
		return $elementos;
		
	}

	public function cargarRiesgoDB(){
		$query = new Query();
		$riesgoCliente = array();		
		$idContratoGlobal = $this->idContratoGlobal;
		$idCliente = $this->idCliente;
		$where = ($idCliente > 0)? ' WHERE refcliente = '.$idCliente:($idContratoGlobal>0)?' WHERE refcontratoglobal = '.$idContratoGlobal:' WHERE refcontratoglobal = 0';
		$sqlIndicadores ="SELECT * FROM dbriesgoimpactoclientes".$where;
		$query->setQuery($sqlIndicadores);
		$resINDI = $query->eject();
		while($objIndicador = $query->fetchObject($resINDI)){
			$arrayDatosvariable = array();
			$arrayDatosvariable['refriesgonivel'] = $objIndicador->refriesgonivel;
			$arrayDatosvariable['valor'] = $objIndicador->valor;	
			$arrayDatosvariable['descripcion'] = $objIndicador->descripcion;			
			$riesgoCliente = $arrayDatosvariable;
		}
		return $riesgoCliente;
		
	}

	public function cargarCatoloNivelRiesgo(){
		$query = new Query();
		$riesgos = array();		
		$sqlIndicadores ="SELECT * FROM  tbriesgoniveles";
		$query->setQuery($sqlIndicadores);
		$resINDI = $query->eject();
		while($objRiesgo= $query->fetchObject($resINDI)){
			$arrayDatosvariable = array();
			$arrayDatosvariable['idriesgonivel'] = $objRiesgo->idriesgonivel;
			$arrayDatosvariable['descripcion'] = $objRiesgo->descripcion;			
			$arrayDatosvariable['valor'] = $objRiesgo->valor;			
			$riesgos[] = $arrayDatosvariable;
		}
		return $riesgos;
	}


	public function generaComprobatePDF(){
		
		$riesgoCliente = $this->cargarRiesgoDB();
		$idContratoGlobal = $this->idContratoGlobal;
		$nombreCliente =  $this->getDato('nombre')." ".$this->getDato('paterno'). " ".$this->getDato('materno');
		$fpdf = new PDFReportesCrea('P','mm','letter',true);
		$fpdf->AddPage('PORTRAIL', 'LETTER');
		$fpdf->SetFont('Arial','B','14');
		$fpdf->SetTextColor(67,67,67);
		$fpdf->Cell(0,5,'Cálculo del riesgo PLD del cliente ',0,0,'C');
		$fpdf->Ln(20);
		$fpdf->SetFont('Arial','B','11');
		$fpdf->Write(10,"Nombre cliente : ");
		$fpdf->SetFont('Arial','','11');
		$fpdf->Write(10,$nombreCliente);
		$fpdf->Ln(5);
		$fpdf->SetFont('Arial','B','11');
		$fpdf->Write(10,"Nivel de riesgo: ");
		$fpdf->SetFont('Arial','','11');		
		$fpdf->Write(10,$riesgoCliente['descripcion']);
		$fpdf->Ln(5);
		$fpdf->SetFont('Arial','B','11');
		$fpdf->Write(10,"Puntuación: ");
		$fpdf->SetFont('Arial','','11');		
		$fpdf->Write(10,$riesgoCliente['valor']);
		$fpdf->Ln(20);
		

		$elementos = $this->cargarElementosDB();
		$variables = $this->cargarIndicadoresDB();

		
		#print_r($elementos);
		#die();
		$fpdf->setFillColor(255,255,255);
		$fpdf->SetFont('Arial','','8');
		$fpdf->SetFont('Arial','B','9');

		foreach ($elementos as $key => $valoresE) {
			$fpdf->SetFont('Arial','B','8');
			$fpdf->Cell(0,5,$valoresE['elemento'],0,0,'',1);
			$fpdf->Ln();
			$elemetoId = $valoresE['refriesgoelemento'];
			$fpdf->SetFont('Arial','','10');
			$fpdf->SetTextColor(255,255,255);
			$fpdf->setFillColor(47,141,65);			
			$fpdf->Cell(70,5,'',0,0,'C',1);
			$fpdf->Cell(60,5,'',0,0,'C',1);
			$fpdf->Cell(15,5,'',0,0,'C',1);
			$fpdf->Cell(20,5,'',0,0,'C',1);
			$fpdf->Cell(20,5,'Impacto ',0,0,'C',1);
			$fpdf->Ln();
			
			$fpdf->Cell(70,5,'Indicador',0,0,'C',1);
			$fpdf->Cell(60,5,'Variable',0,0,'C',1);
			$fpdf->Cell(15,5,'Impacto',0,0,'C',1);
			$fpdf->Cell(20,5,'Ponderación',0,0,'C',1);
			$fpdf->Cell(20,5,'Ponderado',0,0,'C',1);
			

			$fpdf->SetTextColor(40,40,40);
			$fpdf->setFillColor(255,255,255);
			$fpdf->SetFont('Arial','','8');

			$sumaPorcetajes = 0;
			$sumaImpacto = 0;
			foreach ($variables as $key => $valoresI) {

				if($valoresI['refriesgoelemento'] ==$elemetoId ){
					$fpdf->Ln();
					
					$fpdf->Cell(70,5,$valoresI['indicador'] ,0,0,'',1);
					$fpdf->Cell(60,5,$valoresI['variable'] ,0,0,'',1);
					$fpdf->Cell(15,5,$valoresI['impacto'] ,0,0,'R',1);
					$fpdf->Cell(20,5,$valoresI['ponderacionindicador']."%" ,0,0,'R',1);
					$fpdf->Cell(20,5,$valoresI['impactoponderado'] ,0,0,'R',1);
					$sumaImpacto  = $sumaImpacto  + $valoresI['impactoponderado'];
					$sumaPorcetajes  = $sumaPorcetajes  + $valoresI['ponderacionindicador'];
				}
				
			# code...
		}
					$fpdf->Ln(5);
					$fpdf->SetFont('Arial','B','8');
					$fpdf->Cell(10,5,'' ,0,0,'',1);
					$fpdf->Cell(60,5,'',0,0,'',1);
					$fpdf->Cell(60,5,'' ,0,0,'',1);
					$fpdf->Cell(15,5,'' ,0,0,'R',1);
					$fpdf->Cell(20,5,$sumaPorcetajes.'%' ,0,0,'R',1);
					$fpdf->Cell(20,5,number_format($sumaImpacto,2) ,0,0,'R',1);
					$fpdf->Ln(25);


		}

		#print_r($elementos);
		#die();
		$elementos = $this->cargarElementosDB();
		$fpdf->Ln(1);
			$elemetoId = $valoresE['refriesgoelemento'];
			$fpdf->SetFont('Arial','','10');
			$fpdf->SetTextColor(255,255,255);
			$fpdf->setFillColor(37,96,154);
			$fpdf->Cell(160,5,'RIESGO TOTAL DEL CLIENTE',0,0,'C',1);			
			$fpdf->Ln();
			$fpdf->Cell(60,5,'ELEMENTOS',0,0,'C',1);
			$fpdf->Cell(20,5,'IMPACTO',0,0,'C',1);
			$fpdf->Cell(40,5,'PONDERACIÓN',0,0,'C',1);
			$fpdf->Cell(40,5,'RIESGO PONDERADO',0,0,'C',1);
			$sumaElemento = 0;
		foreach ($elementos as $key => $valoresE) {		
		   # echo "<br>key =>".$key;
		   # echo "<pre>";
		   # print_r($valoresE);
		    #    echo "</pre>";	
			$fpdf->Ln();
			$fpdf->SetTextColor(40,40,40);
			$fpdf->setFillColor(255,255,255);
			$fpdf->SetFont('Arial','','8');
			$fpdf->Cell(60,5,$valoresE['elemento'],0,0,'',1);
			$fpdf->Cell(20,5,$valoresE['riesgo'],0,0,'R',1);
			$fpdf->Cell(40,5,$valoresE['ponderacion']."%",0,0,'R',1);
			$fpdf->Cell(40,5,$valoresE['riesgoponderado'],0,0,'R',1);
			$sumaElemento = $sumaElemento +$valoresE['riesgoponderado'];	
		}
			
			$fpdf->Ln();
			$fpdf->SetFont('Arial','B','9');
			$fpdf->Cell(60,5,'',0,0,'',1);
			$fpdf->Cell(20,5,'',0,0,'R',1);
			$fpdf->Cell(40,5,'',0,0,'R',1);
			#$fpdf->setFillColor(47,141,65);
			$fpdf->Cell(40,5,number_format($sumaElemento,2),0,0,'R',1);
			$fpdf->Ln(5);
			$riesgos = $this->cargarCatoloNivelRiesgo();
			$riesgoCliente = $this->cargarRiesgoDB();
			$fpdf->SetFont('Arial','','10');
			$fpdf->SetTextColor(255,255,255);
			$fpdf->setFillColor(234,94,14);
			$fpdf->Cell(90,5,'Niveles de riesgo',0,0,'C',1);			
			
			$fpdf->Ln();
			$fpdf->Cell(40,5,'Descripción',0,0,'C',1);
			$fpdf->Cell(25,5,'Límite inferior',0,0,'C',1);
			$fpdf->Cell(25,5,'Límite superior',0,0,'C',1);
			#$fpdf->SetDrawColor(230,230,230);
			foreach ($riesgos as $key => $valoresR) {	
				$fpdf->Ln();
				$rangos = explode('-', $valoresR['valor']);
				$fpdf->SetFont('Arial','','9');
				$fpdf->SetTextColor(40,40,40);
				$fpdf->setFillColor(255,255,255);
				if(in_array($valoresR['idriesgonivel'], $riesgoCliente)){
					$fpdf->setFillColor(255,253,129);
					$fpdf->setFillColor(72,250,14);
				}
				$fpdf->Cell(40,5,$valoresR['descripcion'],0,0,'',1);
				$fpdf->Cell(25,5,number_format($rangos[0],1),0,0,'R',1);
				$fpdf->Cell(25,5,number_format($rangos[1],1),0,0,'R',1);

				
				

			}
		
		$directorioPricipal1 = "../upload/".$idContratoGlobal."/PLD_RIESGO/";
		$directorioPricipalDB = "upload/".$idContratoGlobal."/PLD_RIESGO/";

		if (!file_exists($directorioPricipal1)) {
		   if(!mkdir($directorioPricipal1, 0777, true)){
		   	$error .= "Error al crear la carpeta destino";	
		   	die();	   	
		   }else{
		   	if(!$fh = fopen($directorioPricipal1."index.php", 'w') ){	
		   		$error .= "Se produjo un error al crear el archivo index de la carpeta de archivos";   			
		   }

		   }
		}
		$archivoRiesgos = $idContratoGlobal."_PLD_RIESGO".time().".pdf";
		$nombreComprobante = $directorioPricipal1.$archivoRiesgos;
		$fpdf->outPut($nombreComprobante, 'F');
		// Se agrega el registro ala tabla de documentos
			
		
			
			$query = new Query();
			$deleteD = "DELETE FROM `dbcontratosglobalesdocumentos`  WHERE  `refcontratoglobal` = $idContratoGlobal AND `refdocumento` = 28";
			$query->setQuery($deleteD);
			$query->eject();
			$query->commitTrans();
			$sqlIsertFile = "INSERT INTO `dbcontratosglobalesdocumentos` (`idcontratoglobaldocumento`, `refcontratoglobal`, `refdocumento`, `refestadodocumento`, `nombre`, `ruta`, `vigencia_desde`, `vigencia_hasta`) ";
			$sqlIsertFile .= " VALUES (NULL, $idContratoGlobal , 28, 5,'".$archivoRiesgos."', '".$directorioPricipalDB."', CURDATE(), DATE_ADD(CURDATE(),INTERVAL 1 YEAR)); ";
			$query->setQuery($sqlIsertFile);
			$query->eject(1);
			$query->commitTrans();

			
		
		#$fpdf->outPut();

	}

}

?>