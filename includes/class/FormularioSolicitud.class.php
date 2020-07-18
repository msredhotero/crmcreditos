<?php
include_once('Forma.class.php');
/**
 * 
 */
class FormularioSolicitud extends Forma
{
	private $fecha ='';

	private function getFecha()
	{		
		return $this->fecha;
	}


	private function getFechaLetras()
	{	$fechaLetras ='';	
		$servicios = new Servicios();
		#$fechaContrato = (!empty($this->get_value('fecha_registro')))?$this->get_value('fecha_registro'):$this->getFecha();

			$fechaContrato = $this->getFecha();
		$fechaLetras = $servicios->obtenerFechaEnLetra($fechaContrato);
		return $fechaLetras;
	}
	
	private function getNombreusuario()
	{
		$usuario = new Usuario();
		$usuario-> setUsuarioData();
		$nombre = $usuario->getNombre();
		$nombreContratoGlobal = $this->get_value('nombre')." ". $this->get_value('paterno')." ".$this->get_value('materno');

		$nombre = ($nombreContratoGlobal !="  ") ?$nombreContratoGlobal :$usuario->getNombre();

		return strtoupper($nombre);
	}
	public function __construct()
	{

		#echo "nombre ".$this->get_value('nombre');
		#$this->fecha = (!empty($this->get_value('fecha_registro')))?$this->get_value('fecha_registro'): date("Y-m-d");
		$this->fecha =  date("Y-m-d");
		$content = array(
			$this->section(true, array(
				$this->input_hidden('idprueba2'),
		 		$this->input_hidden('idprueba1'),	
			)),
			$this->section(true, array(



			)),

		);// content
	}

	public function accesoIncorrecto(){
		$content = array();
		$content = array('tag'=>'br', 'inside'=>array('Acceso incorrecto!!1'));

		return $content;

	}

	public function  formaDocumentos(){
		$idcontratoglobal = $this->get_value('idcontratoglobal');		
		$serviciosSol = new ServiciosSolicitudes($idcontratoglobal);		
		$documetosSolicitados =  $serviciosSol->buscarTipoDoctos();
		$arrayFiles = array();
		$titulo = 'Atención!' ;
		$mensaje = 'Antes de adjuntar los documentos por favor verifique que cumplen los requisitos establecidos para cada tipo de documento, como la vigencia o datos que debe contener el documento';
		foreach ($documetosSolicitados as $idDoc => $detalle) {			
			$requerido =$detalle['requerio'];
			
			$arrayFiles[] = $this->input_file($idDoc,  array('req'=>$detalle['requerio'], 'det'=>$detalle['especificaciones'], 'desc'=>$detalle['documento']));

			# code...
		}
			$arrayFiles[] = array('tag'=>'br');
			$arrayFiles[] = array('tag'=>'row col-sm-2', 'class'=>'', 'inside'=>array(
					array('tag'=>'button', 'id'=>'btn_guardar', 'value'=>'Guardar', 'class'=>'btn btn-block btn-info btn-sm col-sm-1 col-12 float-right', 'inside'=>array('Guardar')),
				));

		
		
		$content = array();
		$content[] = array('tag'=>'div', 'inside'=>array(
					array('tag'=>'hidden', 'name'=>'refcontratoglobal', 'id'=>'refcontratoglobal'),
					array('tag'=>'input','type'=>'hidden', 'name'=>'idcontratoglobal', 'id'=>'idcontratoglobal', 'value'=>$this->get_value('idcontratoglobal')),
					

				));

		$content[] = array('tag'=>'div', 'inside'=>array(					
					array('tag'=>'input','type'=>'hidden', 'name'=>'cedulasi', 'id'=>'cedulasi', 'value'=>$this->get_value('cedulasi')),
				));

		$content[] = array('tag'=>'div', 'inside'=>array(					
					array('tag'=>'input','type'=>'hidden', 'name'=>'firmasi', 'id'=>'firmasi', 'value'=>$this->get_value('firmasi')),
				));

		$content[] = array(
					'tag' => 'div',
					'id' => 'doctos',
					'inside' => array(					
						$this->section(true, $arrayFiles)
					)
				);


		return $content;
	}


	public function seccionEmpleo(){

		$empresaAfiliada = $this->get_value('idempresaafiliada');

		if($empresaAfiliada ==1){
			// se se trata de la UNAM
			$seccionEmpleo = array(
				$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Qué tipo de crédito te interesa?'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('reftipocontratoglobal', array('cat_nombre'=>'tbtipocontratoglobal','id_cat'=>'idtipocontratoglobal')), 
			 			$this->input_help('Tipo de crédito',true),
			 		)),
		 		)),
		 		$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Qué tipo de crédito te interesa?'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('reftipocontratoglobal', array('cat_nombre'=>'tbtipocontratoglobal','id_cat'=>'idtipocontratoglobal')), 
			 			$this->input_help('Tipo de crédito',true),
			 		)),
		 		)),
			);

			
		}else{
			// se trata de otra empresa
			$seccionEmpleo = array(
			$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Qué tipo de crédito te interesa?'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('reftipocontratoglobal', array('cat_nombre'=>'tbtipocontratoglobal','id_cat'=>'idtipocontratoglobal')), 
			 			$this->input_help('Tipo de crédito',true),
			 		)),
		 		)),
			$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Qué tipo de crédito te interesa?'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('reftipocontratoglobal', array('cat_nombre'=>'tbtipocontratoglobal','id_cat'=>'idtipocontratoglobal')), 
			 			$this->input_help('Tipo de crédito',true),
			 		)),
		 		)),
			$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Qué tipo de crédito te interesa?'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('reftipocontratoglobal', array('cat_nombre'=>'tbtipocontratoglobal','id_cat'=>'idtipocontratoglobal')), 
			 			$this->input_help('Tipo de crédito',true),
			 		)),
		 		)),
			);

		}

		$seccionEmpleo ="$this->form_group(array(
		 			$this->form_label_muted(2,'', true, 'Monto máximo'),
		 			$this->form_column(3, '', array(
		 				$this->input_number('montootorgamiento',2),			 			
			 			$this->input_help('Monto máximo para otorgar',true),
			 		)),
			 		$this->form_label_muted(2,1, true, 'Número de pagos'),
		 			$this->form_column(3, '', array(
		 				$this->input_number('numeropagos',2),			 			
			 			$this->input_help('Total de pagos',true),
			 		)),
		 		)),";
		return $seccionEmpleo;

	}

	Public function cabeceraContratoGlobbal(){
		// verificacmos si el usuario es un cliente o es administración
		$usuario = new Usuario();		
		$usuario-> setUsuarioData();
		$nombre = $usuario->getNombre();
		$rolId = $usuario->getRolId();
		$usuario_id = $usuario->getUsuarioId();
		$mostrarOpciopnTipoGlobal =  array();
		$mostrarOpciopnTipoGlobal = $usuario->filtroComboTipoContrato($this->get_value('idContratoGlobal'), $this->get_value('reftipocontratoglobal'));
		
  		$tipoContrato_filter = array(); 		
		$tipoContrato_filter = $mostrarOpciopnTipoGlobal;
		


		$textoSuceptibleaCredito  = '';

		$tipoContrato = $this->get_value('reftipocontratoglobal');
		if($tipoContrato == 1 || $tipoContrato == 2){
			$textoSuceptibleaCredito  = 'Suceptible a un crédito tradicional?';
		}else{
			$textoSuceptibleaCredito  = 'Suceptible a un crédito Santo adelanto?';
		}

		#para santo
		$tipoCredito = '';
		if($rolId == 8){
			$tipoCredito_filter = array();		
			$tipoCredito_filter = $tipoContrato_filter;	
			$cabecera = array(
				$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'En dondé trabajas?'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('refempresaafiliada', array('cat_nombre'=>'tbempresaafiliada','id_cat'=>'idempresaafiliada')), 
			 			$this->input_help('Empresa',true),
			 		)),
		 		)),
		 		
		 		$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Qué tipo de crédito te interesa?'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('reftipocontratoglobal', array('cat_nombre'=>'tbtipocontratoglobal','id_cat'=>'idtipocontratoglobal', 'filtros' => array(
								array(
									'field' => 'idtipocontratoglobal',
									'value' => $tipoCredito_filter
								)
							)

			 		)), 
			 			$this->input_help('Tipo de crédito',true),
			 		)),
		 		)),
			);

		} else{

			#if($rolId == 20){
				#$causa_rechazo_filter =  array('' => , );

			#}

				
			$status_contrato = $this->get_value('cgs_refstatuscontratoglobal')	;
			$doctosAdminCompletos = $this->get_value('documentosadministracioncompletos');
			#	echo "=>". $this->get_value('cgs_refstatuscontratoglobal');

			$montoCredito =  $this->get_value('montootorgamiento');
			$limiteUDIS = $this->get_value('limiteUDI');

			// si el credito es UNAM no se puede poner en Aprobado, pendiente Empleador, se debe ir directo a Autorizado, pendientes firmas

			$empresa = $this->get_value('refempresaafiliada'); 

			$val = $status_contrato;
  			$status_filter = array();

   			switch ($val) {
			  case "1":
				  if($doctosAdminCompletos){
				  	$status_filter = array("1", "2",);
				  }else{
				  	$status_filter = array("1","8");
				  }
			   
			    break;
			  case "2":
			  		if($empresa == 1){
			  			if($montoCredito <= $limiteUDIS)
			  			$status_filter = array("2", "4", "5",);
			  			else
			  			$status_filter = array("2", "12",);

			  		}else{
			  			if($montoCredito <= $limiteUDIS)
			  				$status_filter = array("2", "3", "4",);
			  			else
			  				$status_filter = array("2", "12",);
			  		}			 				    
			    break;

			  case "3":
			    $status_filter = array("3", "4", "5",);
			    break;
			  case "4":
			    $status_filter = array("4",);			    
			    break;
			  case "5":
			  	if($rolId == 21){
			  		$status_filter = array("5",);
			  	}else{
			  		$status_filter = array("5", "7","8");
			  	}			  				    
			    break;
			  case "6":
			  	$status_filter = array("6", "7", "4",);			    
			    break;
			  case "7":
			    $status_filter = array( "7", "9",);			    
			    break; 

			  case "8":
			    $status_filter = array("8",);			    
			    break; 
			  case "9":
			    $status_filter = array("9",);			    
			    break; 
			  case "10":
			    $status_filter = array("10","1", "11");			    
			    break; 
			  case "11":
			    $status_filter = array("11",);			    
			    break;  
			  case "12":
			  	if($empresa == 1){
			  		if($doctosAdminCompletos)
			  			$status_filter = array("12","4","5",);
			  		else
			  			$status_filter = array("12", "4",);
			  		}else{
			  			if($doctosAdminCompletos)
			  				$status_filter = array("12", "3", "4",);
			  			else
			  				$status_filter = array("12", "4",);				  			
			  		}	    
			    break;      
			  default:			   
			    break;
			  }

 

			$cabecera = array(
				$this->input_hidden('idcontratoglobal'),
				
							
				
				$this->form_group(array(
		 			$this->form_label_muted(2,'', true, 'Empresa'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('refempresaafiliada', array('cat_nombre'=>'tbempresaafiliada','id_cat'=>'idempresaafiliada')), 
			 			$this->input_help('Empresa',true),
			 		)),
			 		$this->form_label_muted(2,1, true, 'Tipo de crédito'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('reftipocontratoglobal', array('cat_nombre'=>'tbtipocontratoglobal','id_cat'=>'idtipocontratoglobal')), 
			 			$this->input_help('Tipo de crédito',true),
			 		)),


		 		)),

		 		
		 		$this->title_seccion('Análisis de crédito'),


		 		$this->form_group(array(
		 			$this->form_label_muted(2,'', true, 'Status'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('cgs_refstatuscontratoglobal', array('cat_nombre'=>'tbstatuscontratoglobal','id_cat'=>'idstatuscontratoglobal','filtros' => array(
								array(
									'field' => 'idstatuscontratoglobal',
									'value' => $status_filter
								)
							))), 
			 			$this->input_help('Status',true),
			 		)),
			 		$this->form_label_muted(1,1, true, 'UDI'),
			 			$this->form_column(1,'', array(
			 				$this->muestra_descripcion('descripcion','tbudi','idudi',$this->get_value('refudi')),
				 			$this->input_help('Valor',true),
				 		)),

				 	$this->form_label_muted(1,1, true, '3000 UDIs'),
			 			$this->form_column(2,'', array(
			 				$this->muestra_descripcion_val('limiteUDIF'),
				 			$this->input_help('Limite',true),
				 			$this->input_hidden('limiteUDI'),
				 		)),		 

			 		
		 			
			 		
		 		)),
		 		$this->form_group(array(		 			
			 		$this->form_label_muted(2,'', true, 'Causa'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('cgs_refrechazocausa', array('cat_nombre'=>'tbrechazocausa','id_cat'=>'idrechazocausa')), 
			 			$this->input_help('Motivo',true),
			 		)),

		 		),'causa_rechazo'),

		 		$this->form_group(array(
		 			$this->form_label_muted(2,'', true, 'Forma de pago'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('refformapago', array('cat_nombre'=>'forma_pago','id_cat'=>'forma_pago_id','filtros' => array(
								array(
									'field' => 'forma_pago_id',
									'value' => 4,
								)
							))), 
			 			$this->input_help('Motivo',true),
			 		)),
		 			$this->form_label_muted(2,1, true, 'Tasa anual'),
		 			$this->form_column(3, '', array(
		 				$this->input_number('tasaanual',2),
			 			
			 			$this->input_help('Tasa de interes',true),
			 		)),
		 		)),

		 		$this->form_group(array(
		 			$this->form_label_muted(2,'', true, 'Monto máximo'),
		 			$this->form_column(3, '', array(
		 				$this->input_number('montootorgamiento',2),			 			
			 			$this->input_help('Monto máximo para otorgar',true),
			 		)),
			 		$this->form_label_muted(2,1, true, 'Número de pagos'),
		 			$this->form_column(3, '', array(
		 				$this->input_number('numeropagos',2),			 			
			 			$this->input_help('Total de pagos',true),
			 		)),
			 		$this->input_hidden('entrevistacliente'),
			 		
		 		)),

		 		$this->form_group(array(
		 			$this->form_label_muted(2,'', true, 'Circulo de crédito'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('refcirculocredito', array('cat_nombre'=>'tbautorizacioncirculo','id_cat'=>'idautorizacioncirculo')), 
			 			$this->input_help('Solicitar autorización?',true),
			 		)),
			 		$this->form_label_muted(2,1, true, 'Historial crediticio'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('reftipocliente', array('cat_nombre'=>'cliente_tipo','id_cat'=>'cliente_tipo_id')), 
			 			$this->input_help('Historial crediticio',true),
			 		)),

		 			
		 			
		 		)),

		 		$this->form_group(array(
		 			$this->form_label_muted(2,'', true, $textoSuceptibleaCredito),
		 			$this->form_column(3, '', array(
			 			$this->input_select('refsuceptiblect', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
			 			$this->input_help('Viable',true),
			 		)),
			 		
			 		$this->form_label_muted(2,1, true, 'Suceptible a Servicios'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('refsuceptibleserv', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
			 			$this->input_help('Ofrecer seguros crea',true),
			 		)),

		 			
		 			
		 		)),

		 		$this->form_group(array(
		 			$this->form_label_muted(2,'', true, 'Promotor del crédito'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('refpromotor', array('cat_nombre'=>'tbasesores','id_cat'=>'idasesor', 'descripcion'=>'nombre')), 
			 			$this->input_help('Asesor',true),
			 		)),
			 		
			 		

		 			
		 			
		 		)),


		 		
		 		
			);

		}



		return $cabecera;


	}


	Public function cabeceraOtorgamiento(){
		// verificacmos si el usuario es un cliente o es administración
		$usuario = new Usuario();		
		$usuario-> setUsuarioData();
		$nombre = $usuario->getNombre();
		$rolId = $usuario->getRolId();
		$usuario_id = $usuario->getUsuarioId();
		$mostrarOpciopnTipoGlobal =  array();
		$mostrarOpciopnTipoGlobal = $usuario->filtroComboTipoContrato($this->get_value('idContratoGlobal'), $this->get_value('reftipocontratoglobal'));
		
  		$tipoContrato_filter = array(); 		
		$tipoContrato_filter = $mostrarOpciopnTipoGlobal;
		


		$textoSuceptibleaCredito  = '';

		$tipoContrato = $this->get_value('reftipocontratoglobal');
		if($tipoContrato == 1 || $tipoContrato == 2){
			$textoSuceptibleaCredito  = 'Suceptible a un crédito tradicional?';
		}else{
			$textoSuceptibleaCredito  = 'Suceptible a un crédito Santo adelanto?';
		}

		#para santo
		$tipoCredito = '';
		if($rolId == 8){
			$tipoCredito_filter = array();		
			$tipoCredito_filter = $tipoContrato_filter;	
			$cabecera = array(
				$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'En dondé trabajas?'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('refempresaafiliada', array('cat_nombre'=>'tbempresaafiliada','id_cat'=>'idempresaafiliada')), 
			 			$this->input_help('Empresa',true),
			 		)),
		 		)),
		 		
		 		$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Qué tipo de crédito te interesa?'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('reftipocontratoglobal', array('cat_nombre'=>'tbtipocontratoglobal','id_cat'=>'idtipocontratoglobal', 'filtros' => array(
								array(
									'field' => 'idtipocontratoglobal',
									'value' => $tipoCredito_filter
								)
							)

			 		)), 
			 			$this->input_help('Tipo de crédito',true),
			 		)),
		 		)),

		 		

			);

		} else{

			#if($rolId == 20){
				#$causa_rechazo_filter =  array('' => , );

			#}

				
			$status_contrato = $this->get_value('cgs_refstatuscontratoglobal')	;
			$doctosAdminCompletos = $this->get_value('documentosadministracioncompletos');
			#	echo "=>". $this->get_value('cgs_refstatuscontratoglobal');

			$montoCredito =  $this->get_value('montootorgamiento');
			$limiteUDIS = $this->get_value('limiteUDI');

			// si el credito es UNAM no se puede poner en Aprobado, pendiente Empleador, se debe ir directo a Autorizado, pendientes firmas

			$empresa = $this->get_value('refempresaafiliada'); 

			$val = $status_contrato;
  			$status_filter = array();

   			switch ($val) {
			  case "1":
				  if($doctosAdminCompletos){
				  	$status_filter = array("1", "2",);
				  }else{
				  	$status_filter = array("1","8");
				  }
			   
			    break;
			  case "2":
			  		if($empresa == 1){
			  			if($montoCredito <= $limiteUDIS)
			  			$status_filter = array("2", "4", "5",);
			  			else
			  			$status_filter = array("2", "12",);

			  		}else{
			  			if($montoCredito <= $limiteUDIS)
			  				$status_filter = array("2", "3", "4",);
			  			else
			  				$status_filter = array("2", "12",);
			  		}			 				    
			    break;

			  case "3":
			    $status_filter = array("3", "4", "5",);
			    break;
			  case "4":
			    $status_filter = array("4",);			    
			    break;
			  case "5":
			  	if($rolId == 21){
			  		$status_filter = array("5",);
			  	}else{
			  		$status_filter = array("5", "7","8");
			  	}			  				    
			    break;
			  case "6":
			  	$status_filter = array("6", "7", "4",);			    
			    break;
			  case "7":
			    $status_filter = array( "7", "9",);			    
			    break; 

			  case "8":
			    $status_filter = array("8",);			    
			    break; 
			  case "9":
			    $status_filter = array("9",);			    
			    break; 
			  case "10":
			    $status_filter = array("10","1", "11");			    
			    break; 
			  case "11":
			    $status_filter = array("11",);			    
			    break;  
			  case "12":
			  	if($empresa == 1){
			  		if($doctosAdminCompletos)
			  			$status_filter = array("12","4","5",);
			  		else
			  			$status_filter = array("12", "4",);
			  		}else{
			  			if($doctosAdminCompletos)
			  				$status_filter = array("12", "3", "4",);
			  			else
			  				$status_filter = array("12", "4",);				  			
			  		}	    
			    break;      
			  default:			   
			    break;
			  }

 

			$cabecera = array(
				$this->input_hidden('idcontratoglobal'),
				
							
				
				$this->form_group(array(
		 			$this->form_label_muted(2,'', true, 'Empresa'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('refempresaafiliada', array('cat_nombre'=>'tbempresaafiliada','id_cat'=>'idempresaafiliada')), 
			 			$this->input_help('Empresa',true),
			 		)),
			 		$this->form_label_muted(2,1, true, 'Tipo de crédito'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('reftipocontratoglobal', array('cat_nombre'=>'tbtipocontratoglobal','id_cat'=>'idtipocontratoglobal')), 
			 			$this->input_help('Tipo de crédito',true),
			 		)),


		 		)),

		 		$this->form_group(array(	
			 			$this->form_label_muted(12,'', true, 'Nombre completo'),
			 			$this->form_column(4,'', array(
				 		
				 			$this->input_text('nombre'),
				 			$this->input_help('Nombre',true),
				 		)),
				 		$this->form_column(4,'', array(
				 			$this->input_text('paterno'),
				 			$this->input_help('Apellido paterno',true),
				 		)),
				 		$this->form_column(4,'', array(
				 			$this->input_text('materno'),
				 			$this->input_help('Apellido Materno',true),
				 		)),
				 	
				 	)),


		 		
		 		$this->title_seccion('Análisis de crédito'),


		 		$this->form_group(array(
		 			$this->form_label_muted(2,'', true, 'Status'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('cgs_refstatuscontratoglobal', array('cat_nombre'=>'tbstatuscontratoglobal','id_cat'=>'idstatuscontratoglobal','filtros' => array(
								array(
									'field' => 'idstatuscontratoglobal',
									'value' => $status_filter
								)
							))), 
			 			$this->input_help('Status',true),
			 		)),
			 		$this->form_label_muted(1,1, true, 'UDI'),
			 			$this->form_column(1,'', array(
			 				$this->muestra_descripcion('descripcion','tbudi','idudi',$this->get_value('refudi')),
				 			$this->input_help('Valor',true),
				 		)),

				 	$this->form_label_muted(1,1, true, '3000 UDIs'),
			 			$this->form_column(2,'', array(
			 				$this->muestra_descripcion_val('limiteUDIF'),
				 			$this->input_help('Limite',true),
				 			$this->input_hidden('limiteUDI'),
				 		)),		 

			 		
		 			
			 		
		 		)),
		 		$this->form_group(array(		 			
			 		$this->form_label_muted(2,'', true, 'Causa'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('cgs_refrechazocausa', array('cat_nombre'=>'tbrechazocausa','id_cat'=>'idrechazocausa')), 
			 			$this->input_help('Motivo',true),
			 		)),

		 		),'causa_rechazo'),

		 		$this->form_group(array(
		 			$this->form_label_muted(2,'', true, 'Forma de pago'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('refformapago', array('cat_nombre'=>'forma_pago','id_cat'=>'forma_pago_id','filtros' => array(
								array(
									'field' => 'forma_pago_id',
									'value' => 4,
								)
							))), 
			 			$this->input_help('Motivo',true),
			 		)),
		 			$this->form_label_muted(2,1, true, 'Tasa anual'),
		 			$this->form_column(3, '', array(
		 				$this->input_number('tasaanual',2),
			 			
			 			$this->input_help('Tasa de interes',true),
			 		)),
		 		)),

		 		$this->form_group(array(
		 			$this->form_label_muted(2,'', true, 'Monto máximo'),
		 			$this->form_column(3, '', array(
		 				$this->input_number('montootorgamiento',2),			 			
			 			$this->input_help('Monto máximo para otorgar',true),
			 		)),
			 		$this->form_label_muted(2,1, true, 'Número de pagos'),
		 			$this->form_column(3, '', array(
		 				$this->input_number('numeropagos',2),			 			
			 			$this->input_help('Total de pagos',true),
			 		)),
			 		$this->input_hidden('entrevistacliente'),
			 		
		 		)),

		 		
		 		


		 		
		 		
			);

		}



		return $cabecera;


	}

	
	public function formaContratoGlobal(){

		$content = array(					
				
				

		 		$this->title_seccion('Datos personales'),
			 	$this->form_group(array(	
		 			$this->form_label_muted(12,'', true, 'Nombre completo'),
		 			$this->form_column(4,'', array(
			 		
			 			$this->input_text('nombre'),
			 			$this->input_help('Nombre',true),
			 		)),
			 		$this->form_column(4,'', array(
			 			$this->input_text('paterno'),
			 			$this->input_help('Apellido paterno',true),
			 		)),
			 		$this->form_column(4,'', array(
			 			$this->input_text('materno'),
			 			$this->input_help('Apellido Materno',true),
			 		)),
			 	
			 	)),


			 	

			 	$this->form_group(array(		 			
		 			$this->form_column(3,'', array(			 		
			 			$this->input_date('fechanacimiento'),
			 			$this->input_help('Fecha de nacimiento',true),
			 		)),
			 		$this->form_column(3,1, array(
			 			$this->input_select('refpais', array('cat_nombre'=>'nacionalidad','id_cat'=>'nacionalidad_id', 'descripcion'=>'pais_nombre')), 
			 			$this->input_help('País de nacimiento',true),
			 		)),

			 		$this->form_column(3,1, array(
			 			$this->input_select('refnacionalidad', array('cat_nombre'=>'dbnacionalidades','id_cat'=>'idnacionalidad', 'descripcion'=>'descripcion')), 
			 			$this->input_help('Nacionalidad',true),
			 		)),			 	
			 	)),

			 	$this->form_group(array(	
			 		$this->form_column(3,'', array(
			 			$this->input_select('refentidadnacimiento', array('cat_nombre'=>'entidad_nacimiento','id_cat'=>'entidad_nacimiento_id')), 
			 			$this->input_help('Entidad de nacimiento',true),
			 		)),
		 			
		 			$this->form_column(3,1, array(
			 			$this->input_select('refgenero', array('cat_nombre'=>'tbgenero','id_cat'=>'idgenero')), 
			 			
			 			$this->input_help('Genero',true),
			 		)),			 	
			 	)),


			 	$this->form_group(array(
			 		$this->form_column(4,'', array(
			 			$this->input_text('rfc'),
			 			$this->input_help('RFC',true),
			 		)),

			 		$this->form_column(4,'', array(
			 			$this->input_text('curp'),
			 			$this->input_help('CURP',true),
			 		)),
			 	
			 	)),

			 	$this->form_group(array(
		 			$this->form_label_muted(5,'', true, '¿Cuenta con cédula de identificación fiscal?'),
		 			$this->input_help('Si usted contesta sí, le solicitaremos el documento probatorio',true),
		 			$this->form_column(1,'', array(
		 				$this->input_checkbox('cedulasi', 'Sí', '', '')

		 			)),
		 			$this->form_column(1,'', array(
		 				$this->input_checkbox('cedulano', 'No', '', '')

		 			)),
		 		)),
		 		$this->form_group(array(
		 			$this->form_label_muted(5,'', true, '¿Cuenta con firma electrónica avanzada?'),
		 			$this->form_column(1,'', array(
		 				$this->input_checkbox('firmasi', 'Sí', '', '')

		 			)),
		 			$this->form_column(1,'', array(
		 				$this->input_checkbox('firmano', 'No', '', '')

		 			)),
		 			
		 		)),

			 		
			 	$this->title_seccion('Domicilio'),
		 							
				$this->form_group(array(	
		 			$this->form_label_muted(12,'', true, 'Dirección'),
		 			$this->form_column(8,'', array(
			 		
			 			$this->input_text('calle'),
			 			$this->input_help('Calle',true),
			 		)),
			 		$this->form_column(2,'', array(
			 			$this->input_text('numeroexterior'),
			 			$this->input_help('Número exterior',true),
			 		)),
			 		$this->form_column(2,'', array(
			 			$this->input_text('numerointerior'),
			 			$this->input_help('Número interior',true),
			 		)),			 	
			 	)),


			 	$this->form_group(array(		 			
		 			$this->form_column(6,'', array(			 		
			 			$this->input_text('colonia'),
			 			$this->input_help('Colonia',true),
			 		)),
			 		$this->form_column(2,'', array(
			 			$this->input_text('codigopostal'),
			 			$this->input_help('C.P.',true),
			 		)),			 	
			 	)),

				$this->form_group(array(	
		 			$this->form_column(3, '', array(
			 			$this->input_select('refentidad', array('cat_nombre'=>'inegi2020_estado','id_cat'=>'estado_id')), 
			 			$this->input_help('Estado',true),
			 		)),

			 		$this->form_column(3, 1, array(			 			 
			 			$this->input_select('refmunicipio', array('cat_nombre'=>'inegi2020_municipio','id_cat'=>'municipio_id', 'filtros'=>array(
			 					array('field'=>'refestado', 'value'=>$this->get_value('refentidad')),
			 			))), 
			 			$this->input_help('Municipio',true),
			 		)),

			 		$this->form_column(3, 1, array(
			 			$this->input_select('reflocalidad', array('cat_nombre'=>'inegi2020_localidad','id_cat'=>'localidad_id', 'filtros'=>array(
                            array('field'=>'refestado', 'value'=>$this->get_value('refentidad')),
                            array('field'=>'refmunicipio', 'value'=>$this->get_value('refmunicipio')),
                        ))), 
			 			$this->input_help('Localidad',true),
			 		)),
			 	
			 	)),

				$this->form_group(array(		 			
			 		$this->form_column(4,'', array(
			 			$this->form_label_muted(12,'', true, 'Celular <li class=\'fa fa-mobile\'></li> '),

			 			array('tag'=>'div', 'class'=>'input-group', 'inside'=>array(
			 				array('tag'=>'div', 'class'=>'input-group-prepend', 'inside'=>array(
								array('tag'=>'span', 'class'=>'text-lead', 'inside'=>array('+52 1'))
			 				)),	
			 				$this->input_text_addon('celular1'),
			 			$this->input_help('Celular',true),

			 			)),
			 			
			 		)),
			 		$this->form_column(2, '', array(
			 			$this->form_label_muted(12,'', true, '&nbsp;'),
			 			$this->input_select('refcompania1', array('cat_nombre'=>'compania_celular','id_cat'=>'compania_celular_id','descripcion'=>'nombre')), 
			 			$this->input_help('Compañia',true),
			 		)),

			 		$this->form_column(3,'1', array(
			 		$this->form_label_muted_hidden(12,'', true, 'Teléfono <li class=\'fa fa-phone\'></li> ', true),			 		
			 			$this->input_text('telefono1'),
			 			$this->input_help('Fijo',true),
			 		)),
			 		$this->form_column(2, '', array(
			 			$this->form_label_muted(12,'', true, '&nbsp;'),
			 			$this->input_select('reftipotelefono1', array('cat_nombre'=>'tbtipotelefono','id_cat'=>'idtipotelefono')), 
			 			$this->input_help('Tipo',true),
			 		)),



			 	
			 	)),

				/*$this->form_group(array(	
		 			
		 			$this->form_column(3,'', array(			 		
			 			$this->input_text('telefono2'),
			 			$this->input_help('Fijo',true),
			 		)),
			 		$this->form_column(2, '', array(
			 			$this->input_select('reftipotelefono2', array('cat_nombre'=>'tbtipotelefono','id_cat'=>'idtipotelefono')), 
			 			$this->input_help('Tipo',true),
			 		)),
			 		$this->form_column(3,1, array(
			 			$this->input_text('celular2'),
			 			$this->input_help('Celular',true),
			 		)),
			 		$this->form_column(2, '', array(
			 			$this->input_select('refcompania2', array('cat_nombre'=>'compania_celular','id_cat'=>'compania_celular_id', 'descripcion'=>'nombre')), 
			 			$this->input_help('Compañia',true),
			 		)),			 	
			 	)),*/
			 	

			 	$this->section(true, array(
			 		$this->title_seccion('Datos del empleo'),
			 		$this->form_group(array(	
			 			$this->form_label_muted(2,'', true, 'Dependencia'),
			 			$this->form_column(10,'', array(			 		
				 			$this->muestra_descripcion('nombre_empresa','tbempresaafiliada','idempresaafiliada',$this->get_value('refempresaafiliada')),
				 			$this->input_help('Empresa',true),
				 		)),		 	
			 		)),

			 		$this->form_group(array(			 			
			 			$this->form_column(8,'', array(			 		
				 			$this->input_text('departamento'),
				 			$this->input_help('Area o departamento',true),
				 		)),
				 		$this->form_column(4,'', array(
				 			$this->input_text('puesto'),
				 			$this->input_help('Puesto',true),
				 		)),			 				 	
			 		)),

			 		$this->form_group(array(	
			 			$this->form_label_muted(2,'', true, 'Dirección'),
			 			$this->form_column(10,'', array(			 		
				 			$this->muestra_descripcion('direccion','tbempresaafiliada','idempresaafiliada',$this->get_value('refempresaafiliada')),
				 			$this->input_help('Dirección de la empresa',true),
				 		)),		 	
			 		)),

			 		

			 		$this->form_group(array(			 			
			 			$this->form_column(3,'', array(			 		
				 			$this->input_text('noempleado'),
				 			$this->input_help('Número de empleado',true),
				 		)),		
				 		
				 		$this->form_column(3, '', array(
			 			$this->input_select('otroempleo', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
			 			$this->input_help('Tiene otro empleo?',true),
			 			)),
			 			$this->form_column(6,'', array(			 		
				 			$this->input_text('empresa2'),
				 			$this->input_help('Empresa del segundo trabajo',true),
				 		)),	
		 			
			 		)),

			 		



			 	),'datos_empleo'),

				$this->title_seccion('Persona Polícamente Expuesta (PPE)'),

				$this->form_group(array(
		 			$this->form_label_muted_justify(12,'', true, '   A) ¿Usted desempeña o ha desempeñado funciones públicas destacadas en un país extranjero o en territorio nacional, como son, entre otros, jefes de estado o de gobierno, líderes políticos, funcionarios gubernamentales, judiciales o militares de alta jerarquía, altos ejecutivos de empresas estatales o funcionarios o miembros importantes de partidos políticos?'),
		 			$this->form_column(3, 4, array(
			 			$this->input_select('cargopublico', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
			 			$this->input_help('Cargo público',true),
			 		)),
		 		)),

		 		$this->form_group(array(
		 			$this->form_label_muted_justify(12,'', true, 'B) ¿Usted es cónyuge o tiene parentesco por consanguinidad o afinidad hasta el segundo grado con personas que caen en el supuesto de la pregunta anterior? '),
		 			$this->form_column(3, 4, array(
			 			$this->input_select('cargopublicofamiliar', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
			 			$this->input_help('Familiar con cargo público',true),
			 		)),

			 		$this->form_column(3, 1, array(
			 			$this->input_select('refparentesco', array('cat_nombre'=>'tbparentescos','id_cat'=>'idparentesco')), 
			 			$this->input_help('Parentesco',true),
			 		)),
		 		)),

		 		$this->form_group( array(	
		 			$this->form_label_muted(12,'', true, 'Nombre del familiar'),
		 			$this->form_column(4,'', array(

			 		
			 			$this->input_text('fnombre'),
			 			$this->input_help('Nombre',true),
			 		)),
			 		$this->form_column(4,'', array(
			 			$this->input_text('fpaterno'),
			 			$this->input_help('Apellido paterno',true),
			 		)),
			 		$this->form_column(4,'', array(
			 			$this->input_text('fmaterno'),
			 			$this->input_help('Apellido Materno',true),
			 		)),
			 	
			 	), 'familiarPPE' ),

		 		$this->title_seccion('Actuación por Cuenta Própia y Proveedor de Recursos'),

			 		$this->form_group(array(
		 			$this->form_label_muted_justify(12,'', true, ' Declaro que para efectos de las operaciones realizadas con financiera CREA  estoy actuando de la siguiente manera: '),
		 			$this->form_column(4,2, array(
		 				$this->input_checkbox('cuentapropia', 'Actuo por cuenta propia', '', '')

		 			)),
		 			$this->form_column(4,2, array(
		 				$this->input_checkbox('cuentatercero', 'Actuo por cuenta de un tercero', '', '')
		 			)),

		 			
		 		)),


				$this->form_group( array(	
		 			$this->form_label_muted(12,'', true, 'Nombre del tercero'),
		 			$this->form_column(4,'', array(

			 		
			 			$this->input_text('cnombre'),
			 			$this->input_help('Nombre',true),
			 		)),
			 		$this->form_column(4,'', array(
			 			$this->input_text('cpaterno'),
			 			$this->input_help('Apellido paterno',true),
			 		)),
			 		$this->form_column(4,'', array(
			 			$this->input_text('cmaterno'),
			 			$this->input_help('Apellido Materno',true),
			 		)),
			 	
			 	), 'propietarioReal' ),
		 		$this->form_group(array(
		 			$this->form_label_muted_justify(12,'', true, 'El pago del crédito se realizara con:  '),
		 			$this->form_column(4,2, array(
		 				$this->input_checkbox('recursopropio', 'Recursos propios', '', '')

		 			)),
		 			$this->form_column(4,2, array(
		 				$this->input_checkbox('recursotercero', 'Recursos de un tercero', '', '')
		 			)),
		 		)),



			 	$this->form_group(array(	
		 			$this->form_label_muted(12,'', true, 'Nombre del tercero'),
		 			$this->form_column(4,'', array(
			 		
			 			$this->input_text('pnombre'),
			 			$this->input_help('Nombre',true),
			 		)),
			 		$this->form_column(4,'', array(
			 			$this->input_text('ppaterno'),
			 			$this->input_help('Apellido paterno',true),
			 		)),
			 		$this->form_column(4,'', array(
			 			$this->input_text('pmaterno'),
			 			$this->input_help('Apellido Materno',true),
			 		)),
			 	
			 	),'recursoPropio'),

			 	$this->title_seccion('Historial créditicio'),

			 	/*$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Cuenta con algun crédito hipotecario?'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('creditohipotecario', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
			 			$this->input_help('Tipo de crédito',true),
			 		)),
		 		)),

				$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Ha ejercido en los últimos 2 años algún crédito automotriz?'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('creditoautomotriz', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
			 			$this->input_help('Empresa',true),
			 		)),
		 		)),

		 		$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Cuenta con alguna tarjeta de crédito?'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('tarjetacredito', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
			 			$this->input_help('Empresa',true),
			 		)),

			 		$this->form_column(2, '', array(
			 			$this->input_number('digitostarjeta'),			 			
			 			$this->input_help('Últimos 4 digitos',true),
			 		)),
		 		)),*/


		 		array('tag'=>'br', 'class'=>'','inside'=>array()),

		 		$this->form_group(array(
		 			
		 			$this->form_column(12, '', array(
			 			$this->input_checkbox('burocredito', '<small class="text-muted text-justify">Hoy siendo '.$this->getFechaLetras().', <span class="nombreClienteAutoriza">'.$this->getNombreusuario().'</span> autoriza a  <b>MICROFINANCIERA CRECE, S.A. DE C.V., SOFOM, E.N.R.</b>  a consultar sus antecedentes crediticios por única ocasión ante las Sociedades de Información Crediticia que estime conveniente, declarando que conoce la naturaleza, alcance y uso que <b>MICROFINANCIERA CRECE, S.A. DE C.V., SOFOM, E.N.R.</b> hará de tal información.</small> ', '', '')
			 		)),

			 		
		 		)),

		 			$this->section(true, array(
			 		$this->title_seccion('Llamada de seguimiento'),
			 		$this->form_group(array(	
			 			
			 			$this->form_label_muted(4,'', true, 'Reponsable'),
			 			$this->form_column(4,'', array(				

				 			$this->muestra_descripcion('nombre','usuario','usuario_id',$this->get_value('resposableseguimiento')),
				 			$this->input_help('Seguimiento',true),
				 		)),	 

				 		$this->form_label_muted(1,'', true, 'Fecha'),
			 			$this->form_column(2,'', array(		
			 			$this->muestra_descripcion('fechallamada','dbcontratosglobales','	idcontratoglobal',$this->get_value('idcontratoglobal')),	 		
				 			
			 			$this->input_help('Día de la llamada',true),
				 		)),	

				 			 	
			 		),'resposableSeg'),

			 		$this->form_group(array(	
			 			$this->form_label_muted(4,'', true, 'Se realizó llamada de seguimiento?'),
			 			$this->form_column(2,'', array(			 		
				 			$this->input_select('llamada', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
				 			$this->input_help('Seguimiento',true),
				 		)),		

				 		

				 			 	
			 		)),


			 		$this->form_group(array(	
			 			$this->form_label_muted(4,'', true, 'La información valorada es consistente?'),
			 			$this->form_column(2,'', array(			 		
				 			$this->input_select('veraz', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
				 			$this->input_help('Información veraz',true),
				 		)),		 	
			 		)),

			 		$this->form_group(array(	
			 			$this->form_label_muted(4,'', true, 'Resultado de la llamada'),
			 			$this->form_column(2,'', array(			 		
				 			$this->input_select('resultadollamada', array('cat_nombre'=>'tbresultadosgestion','id_cat'=>'idresultadogestion')), 
				 			$this->input_help('Resultado',true),
				 		)),		 	
			 		)),

			 		$this->form_group(array(	
			 			$this->form_label_muted(4,'', true, 'Observaciones :'),
			 			$this->form_column(8,'', array(			 		
				 			 $this->input_textarea('observaciones',''),
				 			
				 		)),		

				 		

				 			 	
			 		)),

			 	

			 		

			 		

			 		

			 		



			 	),'seguimiento'),

				
				/*array('tag'=>'row col-sm-2', 'class'=>'', 'inside'=>array(
					array('tag'=>'button', 'id'=>'btn_guardar', 'value'=>'Guardar', 'class'=>'btn btn-block btn-info btn-sm col-sm-1 col-12 float-right', 'inside'=>array('Guardar')),
				))*/
				$this->botonGuardar('btn_guardar', 'Guardar', '', 'Guardar'),
				

					
				);
			$arrayEncabezadoFormulario = $this->cabeceraContratoGlobbal();			
			$content = array_merge($arrayEncabezadoFormulario,$content);
		return $content;
	}

	public function formaOtorgamientoAutomatico(){

		$empresaAfiliada = $this->get_value('refempresaafiliada');
		$textoDepartamento ='';
		$textoDepartamento =	($empresaAfiliada == 1) ?'Área / Departamento':'Empresa a la que presta servicios';

		// SELECCIONAMOS EL TIPO DE CREDITO PARA LA LEYENDA DE DESTINO DE CRÉDITO Y ORIGEN DE LOS RECURSOS
		$content = array();
		$content2 = array();
		$creditoTipoId = $this->get_value('reftipocontratoglobal');
		$leyendaDestinoOrigenRecursos = '';

		if($creditoTipoId == 4){
			$leyendaDestinoOrigenRecursos = "Declaro que el destino del crédito será preponderantemente utilizado para el pago de otras deudas, de existir un remanente será utilizado para gastos personales. Declaro que el origen de los recursos con los cuales pagaré el crédito  serán derivados de los ingresos provenientes de mi nómina, entendiendo que si por alguna razón dejo de recibir la nómina ello no me exenta de cumplir los compromisos de pago que suscriba con MICROFINANCIERA CRECE, S.A. DE C.V";
		}else{
			$leyendaDestinoOrigenRecursos = "Declaro que el destino del crédito es para gastos personales y el origen de los recursos con los cuales pagaré el crédito es derivado de los ingresos que provienen de mi nómina, entendiendo que si por alguna razón dejo de recibir la nómina ello no me exenta de cumplir los compromisos de pago que suscriba con MICROFINANCIERA CRECE, S.A. DE C.V ";
		}


					
				
				

		
		

		$content[] =$this->botonGuardar('btn_guardar', 'Guardar', '', 'Guardar');
				

					
				
			$arrayEncabezadoFormulario = $this->cabeceraOtorgamiento();			
			$content = array_merge($arrayEncabezadoFormulario,$content);
			return $content;
	}

	public function formaContratoGlobal2(){
		$empresaAfiliada = $this->get_value('refempresaafiliada');
		$textoDepartamento ='';
		$textoDepartamento =	($empresaAfiliada == 1) ?'Área / Departamento':'Empresa a la que presta servicios';

		// SELECCIONAMOS EL TIPO DE CREDITO PARA LA LEYENDA DE DESTINO DE CRÉDITO Y ORIGEN DE LOS RECURSOS
		$content = array();
		$content2 = array();
		$creditoTipoId = $this->get_value('reftipocontratoglobal');
		$leyendaDestinoOrigenRecursos = '';

		if($creditoTipoId == 4){
			$leyendaDestinoOrigenRecursos = "Declaro que el destino del crédito será preponderantemente utilizado para el pago de otras deudas, de existir un remanente será utilizado para gastos personales. Declaro que el origen de los recursos con los cuales pagaré el crédito  serán derivados de los ingresos provenientes de mi nómina, entendiendo que si por alguna razón dejo de recibir la nómina ello no me exenta de cumplir los compromisos de pago que suscriba con MICROFINANCIERA CRECE, S.A. DE C.V";
		}else{
			$leyendaDestinoOrigenRecursos = "Declaro que el destino del crédito es para gastos personales y el origen de los recursos con los cuales pagaré el crédito es derivado de los ingresos que provienen de mi nómina, entendiendo que si por alguna razón dejo de recibir la nómina ello no me exenta de cumplir los compromisos de pago que suscriba con MICROFINANCIERA CRECE, S.A. DE C.V ";
		}


					
				
				

		$content[] = $this->title_seccion('Datos personales');
		$content[] = $this->form_group(array(	
			 			$this->form_label_muted(12,'', true, 'Nombre completo'),
			 			$this->form_column(4,'', array(
				 		
				 			$this->input_text('nombre'),
				 			$this->input_help('Nombre',true),
				 		)),
				 		$this->form_column(4,'', array(
				 			$this->input_text('paterno'),
				 			$this->input_help('Apellido paterno',true),
				 		)),
				 		$this->form_column(4,'', array(
				 			$this->input_text('materno'),
				 			$this->input_help('Apellido Materno',true),
				 		)),
				 	
				 	));


			 	

		$content[] = $this->form_group(array(		 			
		 			$this->form_column(3,'', array(			 		
			 			$this->input_date('fechanacimiento'),
			 			$this->input_help('Fecha de nacimiento',true),
			 		)),
			 		$this->form_column(3,1, array(
			 			$this->input_select('refpais', array('cat_nombre'=>'nacionalidad','id_cat'=>'nacionalidad_id', 'descripcion'=>'pais_nombre')), 
			 			$this->input_help('País de nacimiento',true),
			 		)),

			 		$this->form_column(3,1, array(
			 			$this->input_select('refnacionalidad', array('cat_nombre'=>'dbnacionalidades','id_cat'=>'idnacionalidad', 'descripcion'=>'descripcion')), 
			 			$this->input_help('Nacionalidad',true),
			 		)),			 	
			 	));

			$content[] =$this->form_group(array(	
					 		$this->form_column(3,'', array(
					 			$this->input_select('refentidadnacimiento', array('cat_nombre'=>'entidad_nacimiento','id_cat'=>'entidad_nacimiento_id')), 
					 			$this->input_help('Entidad de nacimiento',true),
					 		)),
				 			
				 			$this->form_column(3,1, array(
					 			$this->input_select('refgenero', array('cat_nombre'=>'tbgenero','id_cat'=>'idgenero')), 
					 			
					 			$this->input_help('Genero',true),
					 		)),

					 		$this->form_column(3,1, array(
			 			$this->input_select('refpaisresidencia', array('cat_nombre'=>'nacionalidad','id_cat'=>'nacionalidad_id', 'descripcion'=>'pais_nombre')), 
			 			$this->input_help('País de residencia',true),
			 		)),			 	
					 	));


			$content[] = $this->form_group(array(
					 		$this->form_column(4,'', array(
					 			$this->input_text('rfc'),
					 			$this->input_help('RFC',true),
					 		)),

					 		$this->form_column(4,'', array(
					 			$this->input_text('curp'),
					 			$this->input_help('CURP',true),
					 		)),
					 	
					 	));

			$content[] = $this->form_group(array(
			 			$this->form_label_muted(5,'', true, '¿Cuenta con cédula de identificación fiscal?'),

			 			$this->form_column(1,'', array(
			 				$this->input_checkbox('cedulasi', 'Sí', '', '')

			 			)),
			 			$this->form_column(1,'', array(
			 				$this->input_checkbox('cedulano', 'No', '', '')

			 			)),
			 			$this->form_column(12,'0', array(
			 				$this->input_help('Si usted contesta sí, le solicitaremos el documento probatorio',true),
			 			)),
			 		));
		 	$content[] = $this->form_group(array(
			 			$this->form_label_muted(5,'', true, '¿Cuenta con firma electrónica avanzada?'),
			 			$this->form_column(1,'', array(
			 				$this->input_checkbox('firmasi', 'Sí', '', '')

			 			)),
			 			$this->form_column(1,'', array(
			 				$this->input_checkbox('firmano', 'No', '', '')

			 			)),
			 			$this->form_column(12,'0', array(
			 				$this->input_help('Si usted contesta sí, le solicitaremos el documento probatorio',true),
			 			)),
			 			
			 		));



		 	$content2[] =$this->form_group(array(	
					 		$this->form_column(3,'', array(
					 			$this->input_select('refdestino', array('cat_nombre'=>'tbdestinoscredito','id_cat'=>'iddestinocredito')), 
					 			$this->input_help('Destino del crédito',true),
					 		)),
				 			
				 			$this->form_column(3,1, array(
					 			$this->input_select('reforigen', array('cat_nombre'=>'tborigenesrecursos','id_cat'=>'idorigenrecurso')), 
					 			
					 			$this->input_help('Origen de los recursos',true),
					 		)),			 	
					 	));

			 		
			$content[] = $this->title_seccion('Domicilio');
		 							
			$content[] = $this->form_group(array(	
		 			$this->form_label_muted(12,'', true, 'Dirección'),
		 			$this->form_column(8,'', array(
			 		
			 			$this->input_text('calle'),
			 			$this->input_help('Calle',true),
			 		)),
			 		$this->form_column(2,'', array(
			 			$this->input_text('numeroexterior'),
			 			$this->input_help('Número exterior',true),
			 		)),
			 		$this->form_column(2,'', array(
			 			$this->input_text('numerointerior'),
			 			$this->input_help('Número interior',true),
			 		)),			 	
			 	));


			$content[] = $this->form_group(array(		 			
		 			$this->form_column(6,'', array(			 		
			 			$this->input_text('colonia'),
			 			$this->input_help('Colonia',true),
			 		)),
			 		$this->form_column(2,'', array(
			 			$this->input_text('codigopostal'),
			 			$this->input_help('C.P.',true),
			 		)),			 	
			 	));

			$content[] =	$this->form_group(array(	
		 			$this->form_column(3, '', array(
			 			$this->input_select('refentidad', array('cat_nombre'=>'inegi2020_estado','id_cat'=>'estado_id')), 
			 			$this->input_help('Estado',true),
			 		)),

			 		$this->form_column(3, 1, array(			 			 
			 			$this->input_select('refmunicipio', array('cat_nombre'=>'inegi2020_municipio','id_cat'=>'municipio_id', 'filtros'=>array(
			 					array('field'=>'refestado', 'value'=>$this->get_value('refentidad')),
			 			))), 
			 			$this->input_help('Municipio',true),
			 		)),

			 		$this->form_column(3, 1, array(
			 			$this->input_select('reflocalidad', array('cat_nombre'=>'inegi2020_localidad','id_cat'=>'localidad_id', 'filtros'=>array(
                            array('field'=>'refestado', 'value'=>$this->get_value('refentidad')),
                            array('field'=>'refmunicipio', 'value'=>$this->get_value('refmunicipio')),
                        ))), 
			 			$this->input_help('Localidad',true),
			 		)),
			 	
			 	));

			$content[] =	$this->form_group(array(		 			
			 		$this->form_column(4,'', array(
			 			$this->form_label_muted(12,'', true, 'Celular <li class=\'fa fa-mobile\'></li> '),

			 			array('tag'=>'div', 'class'=>'input-group', 'inside'=>array(
			 				array('tag'=>'div', 'class'=>'input-group-prepend', 'inside'=>array(
								array('tag'=>'span', 'class'=>'text-lead', 'inside'=>array('+52 1'))
			 				)),	
			 				$this->input_text_addon('celular1'),
			 			$this->input_help('Celular',true),

			 			)),
			 			
			 		)),
			 		$this->form_column(2, '', array(
			 			$this->form_label_muted(12,'', true, '&nbsp;'),
			 			$this->input_select('refcompania1', array('cat_nombre'=>'compania_celular','id_cat'=>'compania_celular_id','descripcion'=>'nombre')), 
			 			$this->input_help('Compañia',true),
			 		)),

			 		$this->form_column(3,'1', array(
			 		$this->form_label_muted_hidden(12,'', true, 'Teléfono <li class=\'fa fa-phone\'></li> ', true),			 		
			 			$this->input_text('telefono1'),
			 			$this->input_help('Fijo',true),
			 		)),
			 		$this->form_column(2, '', array(
			 			$this->form_label_muted(12,'', true, '&nbsp;'),
			 			$this->input_select('reftipotelefono1', array('cat_nombre'=>'tbtipotelefono','id_cat'=>'idtipotelefono')), 
			 			$this->input_help('Tipo',true),
			 		)),



			 	
			 	));

				/*$this->form_group(array(	
		 			
		 			$this->form_column(3,'', array(			 		
			 			$this->input_text('telefono2'),
			 			$this->input_help('Fijo',true),
			 		)),
			 		$this->form_column(2, '', array(
			 			$this->input_select('reftipotelefono2', array('cat_nombre'=>'tbtipotelefono','id_cat'=>'idtipotelefono')), 
			 			$this->input_help('Tipo',true),
			 		)),
			 		$this->form_column(3,1, array(
			 			$this->input_text('celular2'),
			 			$this->input_help('Celular',true),
			 		)),
			 		$this->form_column(2, '', array(
			 			$this->input_select('refcompania2', array('cat_nombre'=>'compania_celular','id_cat'=>'compania_celular_id', 'descripcion'=>'nombre')), 
			 			$this->input_help('Compañia',true),
			 		)),			 	
			 	)),*/
			 	$content[] = $this->section(true, array(
			 		
			 		
			 	));

			$content[] = 	$this->section(true, array(
			 		$this->title_seccion('Datos del empleo'),
			 		$this->form_group(array(	
			 			$this->form_label_muted(3,'', true, 'Empresa con la que colabora:'),
			 			$this->form_column(9,'', array(			 		
				 			$this->muestra_descripcion('nombre_empresa','tbempresaafiliada','idempresaafiliada',$this->get_value('refempresaafiliada')),
				 			$this->input_help('Empresa',true),
				 		)),		 	
			 		)),

			 		$this->form_group(array(	
			 			$this->form_label_muted(3,'', true, 'Dependencia:'),
			 			$this->form_column(9,'', array(			 		
				 			$this->input_select('refdependencia', array('cat_nombre'=>'tbdependeciascu','id_cat'=>'iddependeciacu')), 
			 			$this->input_help('Dependencia',true),
				 		)),		 	
			 		),'dependencia'),

			 		$this->form_group(array(	
			 			$this->form_label_muted(3,'', true, 'Empleador directo:'),
			 			$this->form_column(9,'', array(			 		
				 			$this->input_select('refempleador', array('cat_nombre'=>'tbempleadoresdirectos','id_cat'=>'idempleadorDirecto','filtros'=>array(
			 					array('field'=>'refempresaafiliada', 'value'=>$this->get_value('refempresaafiliada')),
			 			))), 
			 			$this->input_help('Empleador',true),
				 		)),		 	
			 		),'empleador_directo'),

			 		$this->form_group(array(	
			 			$this->form_label_muted(3,'', true, 'Actividad:'),
			 			$this->form_column(9,'', array(			 		
				 			$this->input_select('refactividad', array('cat_nombre'=>'tbactividades','id_cat'=>'idactividad')), 
			 			$this->input_help('Empleador',true),
				 		)),		 	
			 		),'actividad'),

			 		$this->form_group(array(			 			
			 			$this->form_column(3,3, array(			 		
				 			$this->input_number('antiguedadanio'),
				 			$this->input_help('Año contratación',true),
				 		)),
				 		$this->form_column(4,2, array(				 			
				 			$this->input_select('antiguedadmes', array('cat_nombre'=>'tbmeses','id_cat'=>'idmes')),
				 			$this->input_help('Mes contratación',true),
				 		)),			 				 	
			 		),'antiguedad'),


			 		$this->form_group(array(			 			
			 			$this->form_column(8,'', array(			 		
				 			$this->input_text('departamento'),
				 			$this->input_help($textoDepartamento,true),
				 		)),
				 		$this->form_column(4,'', array(
				 			$this->input_text('puesto'),
				 			$this->input_help('Puesto',true),
				 		)),			 				 	
			 		)),

			 		

			 		

			 		 
		 							
					$this->form_group(array(	
		 			$this->form_label_muted(12,'', true, 'Dirección'),
		 			$this->form_column(8,'', array(
			 		
			 			$this->input_text('calleempleo'),
			 			$this->input_help('Calle',true),
			 		)),
			 		$this->form_column(2,'', array(
			 			$this->input_text('numeroexteriorempleo'),
			 			$this->input_help('Número exterior',true),
			 		)),
			 		$this->form_column(2,'', array(
			 			$this->input_text('numerointeriorempleo'),
			 			$this->input_help('Número interior',true),
			 		)),			 	
			 	)),


				$this->form_group(array(		 			
		 			$this->form_column(6,'', array(			 		
			 			$this->input_text('coloniaempleo'),
			 			$this->input_help('Colonia',true),
			 		)),
			 		$this->form_column(2,'', array(
			 			$this->input_text('codigopostalempleo'),
			 			$this->input_help('C.P.',true),
			 		)),			 	
			 	)),

				$this->form_group(array(	
		 			$this->form_column(3, '', array(
			 			$this->input_select('refentidadempleo', array('cat_nombre'=>'inegi2020_estado','id_cat'=>'estado_id')), 
			 			$this->input_help('Estado',true),
			 		)),

			 		$this->form_column(3, 1, array(			 			 
			 			$this->input_select('refmunicipioempleo', array('cat_nombre'=>'inegi2020_municipio','id_cat'=>'municipio_id', 'filtros'=>array(
			 					array('field'=>'refestado', 'value'=>$this->get_value('refentidadempleo')),
			 			))), 
			 			$this->input_help('Municipio',true),
			 		)),

			 		$this->form_column(3, 1, array(
			 			$this->input_select('reflocalidadempleo', array('cat_nombre'=>'inegi2020_localidad','id_cat'=>'localidad_id', 'filtros'=>array(
                            array('field'=>'refestado', 'value'=>$this->get_value('refentidadempleo')),
                            array('field'=>'refmunicipio', 'value'=>$this->get_value('refmunicipioempleo')),
                        ))), 
			 			$this->input_help('Localidad',true),
			 		)),
			 	
			 	)),

			 		$this->form_group(array(			 			
			 			$this->form_column(3,'', array(			 		
				 			$this->input_text('noempleado'),
				 			$this->input_help('Número de empleado',true),
				 		)),		
				 		
				 		$this->form_column(3, '', array(
			 			$this->input_select('otroempleo', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
			 			$this->input_help('Tiene otro empleo?',true),
			 			)),
			 			$this->form_column(6,'', array(			 		
				 			$this->input_text('empresa2'),
				 			$this->input_help('Nombre de la empresa del segundo trabajo',true),
				 		)),	
		 			
			 		)),

			 		$this->form_group(array(	
		 			$this->form_column(3, '', array(
			 			$this->input_select('refpagoalclientecanal', array('cat_nombre'=>'tbpagoalclientecanales','id_cat'=>'idpagoalclientecanal')), 
			 			$this->input_help('Canal de pago al cliente',true),
			 		)),

			 		$this->form_column(3, 1, array(			 			 
			 			$this->input_select('refpagodelclientecanal', array('cat_nombre'=>'tbpagodelclientecanales','id_cat'=>'idpagodelclientecanal')), 
			 			$this->input_help('Canal de pago del cliente',true),
			 		)),

			 		$this->form_column(3, 1, array(
			 			$this->input_select('refadelantopagos', array('cat_nombre'=>'tbadelantapagos','id_cat'=>'idadelantapago')), 
			 			$this->input_help('Pagos adelantados',true),
			 		)),
			 	
			 	)),

			 		$this->form_group(array(		 			
			 		$this->form_column(3, '', array(			 			 
			 			$this->input_select('refbanco', array('cat_nombre'=>'tbbancos','id_cat'=>'idbanco')), 
			 			$this->input_help('Banco cliente',true),
			 		)),

			 		$this->form_column(3, 1, array(
			 			$this->input_text('clabeinterbancaria'), 
			 			$this->input_help('Clabe interbancaria',true),
			 		)),
			 	
			 	)),

			 		



			 	),'datos_empleo');


			 	

			

			$content[] =	$this->title_seccion('Persona Polícamente Expuesta (PPE)');

			$content[] =	$this->form_group(array(
		 			$this->form_label_muted_justify(12,'', true, '   A) ¿Usted desempeña o ha desempeñado funciones públicas destacadas en un país extranjero o en territorio nacional, como son, entre otros, jefes de estado o de gobierno, líderes políticos, funcionarios gubernamentales, judiciales o militares de alta jerarquía, altos ejecutivos de empresas estatales o funcionarios o miembros importantes de partidos políticos?'),
		 			$this->form_column(3, 4, array(
			 			$this->input_select('cargopublico', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
			 			$this->input_help('Cargo público',true),
			 		)),
		 		));

		 	$content[] =	$this->form_group(array(
		 			$this->form_label_muted_justify(12,'', true, 'B) ¿Usted es cónyuge o tiene parentesco por consanguinidad o afinidad hasta el segundo grado con personas que caen en el supuesto de la pregunta anterior? '),
		 			$this->form_column(3, 4, array(
			 			$this->input_select('cargopublicofamiliar', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
			 			$this->input_help('Familiar con cargo público',true),
			 		)),

			 		$this->form_column(3, 1, array(
			 			$this->input_select('refparentesco', array('cat_nombre'=>'tbparentescos','id_cat'=>'idparentesco')), 
			 			$this->input_help('Parentesco',true),
			 		)),
		 		));

		 	$content[] =	$this->form_group( array(	
		 			$this->form_label_muted(12,'', true, 'Nombre del familiar'),
		 			$this->form_column(4,'', array(

			 		
			 			$this->input_text('fnombre'),
			 			$this->input_help('Nombre',true),
			 		)),
			 		$this->form_column(4,'', array(
			 			$this->input_text('fpaterno'),
			 			$this->input_help('Apellido paterno',true),
			 		)),
			 		$this->form_column(4,'', array(
			 			$this->input_text('fmaterno'),
			 			$this->input_help('Apellido Materno',true),
			 		)),
			 	
			 	), 'familiarPPE' );

		 	$content[] =	$this->title_seccion('Actuación por Cuenta Própia y Proveedor de Recursos');

			$content2[] =	$this->form_group(array(
		 			$this->form_label_muted_justify(12,'', true, ' Declaro que para efectos de las operaciones realizadas con financiera CREA  estoy actuando de la siguiente manera: '),
		 			$this->form_column(4,2, array(
		 				$this->input_checkbox('cuentapropia', 'Actuo por cuenta propia', '', '')

		 			)),
		 			$this->form_column(4,2, array(
		 				$this->input_checkbox('cuentatercero', 'Actuo por cuenta de un tercero', '', '')
		 			)),

		 			
		 		));
			$content2[] =	$this->form_group( array(	
		 			$this->form_label_muted(12,'', true, 'Nombre del tercero'),
		 			$this->form_column(4,'', array(

			 		
			 			$this->input_text('cnombre'),
			 			$this->input_help('Nombre',true),
			 		)),
			 		$this->form_column(4,'', array(
			 			$this->input_text('cpaterno'),
			 			$this->input_help('Apellido paterno',true),
			 		)),
			 		$this->form_column(4,'', array(
			 			$this->input_text('cmaterno'),
			 			$this->input_help('Apellido Materno',true),
			 		)),
			 	
			 	), 'propietarioReal' );
		 	$content2[] =	$this->form_group(array(
		 			$this->form_label_muted_justify(12,'', true, 'El pago del crédito se realizara con:  '),
		 			$this->form_column(4,2, array(
		 				$this->input_checkbox('recursopropio', 'Recursos propios', '', '')

		 			)),
		 			$this->form_column(4,2, array(
		 				$this->input_checkbox('recursotercero', 'Recursos de un tercero', '', '')
		 			)),
		 		));



			$content2[] = 	$this->form_group(array(	
		 			$this->form_label_muted(12,'', true, 'Nombre del tercero'),
		 			$this->form_column(4,'', array(
			 		
			 			$this->input_text('pnombre'),
			 			$this->input_help('Nombre',true),
			 		)),
			 		$this->form_column(4,'', array(
			 			$this->input_text('ppaterno'),
			 			$this->input_help('Apellido paterno',true),
			 		)),
			 		$this->form_column(4,'', array(
			 			$this->input_text('pmaterno'),
			 			$this->input_help('Apellido Materno',true),
			 		)),
			 	
			 	),'recursoPropio');

		$content[] = 	$this->form_group(array(
		 			
		 			$this->form_column(12, '', array(
			 			$this->input_checkbox('cuentapropia', '<small class="text-muted text-justify">Para efectos del crédito que solicita, el cliente manifiesta que actua a nombre y por cuenta propia y declara que los recursos que recibirá  por el crédito que solicita son para beneficio propio y no para una tercera persona.</small> ', '', '')
			 		)),

			 		
		 		));

		$content[] = 	$this->form_group(array(
		 			
		 			$this->form_column(12, '', array(
			 			$this->input_checkbox('origenrecursos', '<small class="text-muted text-justify">'.$leyendaDestinoOrigenRecursos.'</small> ', '', '')
			 		)),

			 		
		 		));

		

			$content2[] = 	$this->title_seccion('Historial créditicio');

			 	/*$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Cuenta con algun crédito hipotecario?'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('creditohipotecario', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
			 			$this->input_help('Tipo de crédito',true),
			 		)),
		 		)),

				$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Ha ejercido en los últimos 2 años algún crédito automotriz?'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('creditoautomotriz', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
			 			$this->input_help('Empresa',true),
			 		)),
		 		)),

		 		$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Cuenta con alguna tarjeta de crédito?'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('tarjetacredito', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
			 			$this->input_help('Empresa',true),
			 		)),

			 		$this->form_column(2, '', array(
			 			$this->input_number('digitostarjeta'),			 			
			 			$this->input_help('Últimos 4 digitos',true),
			 		)),
		 		)),*/


		 		//array('tag'=>'br', 'class'=>'','inside'=>array()),

		 	$content2[] =	$this->form_group(array(		 			
		 			$this->form_column(12, '', array(
			 			$this->input_checkbox('burocredito', '<small class="text-muted text-justify">Hoy '.$this->getFechaLetras().', <span class="nombreClienteAutoriza">'.$this->getNombreusuario().'</span> autoriza a  <b>MICROFINANCIERA CRECE, S.A. DE C.V., SOFOM, E.N.R.</b>  a consultar sus antecedentes crediticios por única ocasión ante las Sociedades de Información Crediticia que estime conveniente, declarando que conoce la naturaleza, alcance y uso que <b>MICROFINANCIERA CRECE, S.A. DE C.V., SOFOM, E.N.R.</b> hará de tal información.</small> ', '', '')
			 		)),			 		
		 		));

			$content[] = $this->title_seccion('Medios de difusión');
			$content[] = $this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Cómo se enteró de Financiera CREA?'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('refmediodifusion', array('cat_nombre'=>'tbmediosdifusion','id_cat'=>'idmediosdifusion')), 
			 			$this->input_help('Medio de difusión',true),
			 		)),
		 		));

			$content[] =	$this->section(true, array(			 		
			 		$this->form_group(array(
		 				$this->form_label_muted(4,'', true, 'Nombre del asesor'),
		 				$this->form_column(3, '', array(
			 				$this->input_select('refasesores', array('cat_nombre'=>'tbasesores','id_cat'=>'idasesor', 'descripcion'=>'nombre')), 
			 				$this->input_help('Medio de difusitbasesoresón',true),
			 			)),
		 			)),			 			 	
			 		),'asesores');

			$content[] =	$this->section(true, array(			 		
			 		$this->form_group(array(
		 				$this->form_label_muted(4,'', true, 'Especifique el medio'),
		 				$this->form_column(3, '', array(
		 					$this->input_text('difusionesp'),			 				 
			 				$this->input_help('Cúal?',true),
			 			)),
		 			)),			 			 	
			 		),'otro_medio');

		 		$content[] =	$this->section(true, array(
			 		$this->title_seccion('Llamada de seguimiento'),
			 		$this->form_group(array(	
			 			
			 			$this->form_label_muted(4,'', true, 'Reponsable'),
			 			$this->form_column(4,'', array(				

				 			$this->muestra_descripcion('nombre','usuario','usuario_id',$this->get_value('resposableseguimiento')),
				 			$this->input_help('Seguimiento',true),
				 		)),	 

				 		$this->form_label_muted(1,'', true, 'Fecha'),
			 			$this->form_column(2,'', array(		
			 			$this->muestra_descripcion('fechallamada','dbcontratosglobales','	idcontratoglobal',$this->get_value('idcontratoglobal')),	 		
				 			
			 			$this->input_help('Día de la llamada',true),
				 		)),	

				 			 	
			 		),'resposableSeg'),

			$this->form_group(array(	
			 			$this->form_label_muted(4,'', true, 'Se realizó llamada de seguimiento?'),
			 			$this->form_column(2,'', array(			 		
				 			$this->input_select('llamada', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
				 			$this->input_help('Seguimiento',true),
				 		)),		

				 		

				 			 	
			 		)),


					$this->form_group(array(	
			 			$this->form_label_muted(4,'', true, 'La información valorada es consistente?'),
			 			$this->form_column(2,'', array(			 		
				 			$this->input_select('veraz', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
				 			$this->input_help('Información veraz',true),
				 		)),		 	
			 		)),

					$this->form_group(array(	
			 			$this->form_label_muted(4,'', true, 'Resultado de la llamada'),
			 			$this->form_column(2,'', array(			 		
				 			$this->input_select('resultadollamada', array('cat_nombre'=>'tbresultadosgestion','id_cat'=>'idresultadogestion')), 
				 			$this->input_help('Resultado',true),
				 		)),		 	
			 		)),

			 		$this->form_group(array(	
			 			$this->form_label_muted(4,'', true, 'Tiene ingresos adicionales?'),
			 			$this->form_column(2,'', array(			 		
				 			$this->input_select('ingresoadicional', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
				 			$this->input_help('Ingreso extra',true),
				 		)),		 	
			 		),'ingresos_adiconales'),

				$this->form_group(array(	
			 			$this->form_label_muted(4,'', true, 'Observaciones :'),
			 			$this->form_column(8,'', array(			 		
				 			 $this->input_textarea('observaciones',''),
				 			
				 		)),		

				 		

				 			 	
			 		)),

			 	

			 		

			 		

			 		

			 		



			 	),'seguimiento');

				
				/*array('tag'=>'row col-sm-2', 'class'=>'', 'inside'=>array(
					array('tag'=>'button', 'id'=>'btn_guardar', 'value'=>'Guardar', 'class'=>'btn btn-block btn-info btn-sm col-sm-1 col-12 float-right', 'inside'=>array('Guardar')),
				))*/
		$content[] =$this->botonGuardar('btn_guardar', 'Guardar', '', 'Guardar');
				

					
				
			$arrayEncabezadoFormulario = $this->cabeceraContratoGlobbal();			
			$content = array_merge($arrayEncabezadoFormulario,$content);
		return $content;
	}


	public function autorizarConsultaHistoriaCrediticio(){
		$content = array();


		$content[] = 	$this->title_seccion('Historial créditicio');



		 	$content[] =	$this->form_group(array(
		 			
		 			$this->form_column(12, '', array(
			 			$this->input_checkbox('burocredito', '<small class="text-muted text-justify">Autorizo expresamente a MICROFINANCIERA CRECE, S.A. DE C.V., para que lleve a cabo investigaciones sobre mi comportamiento crediticio en las Sociedades de Información Crediticia (SIC) que estime conveniente. Conozco la naturaleza y alcance de la información que se solicitará, del uso que se le dará y que se podrán realizar consultas periodicamente de mi historial crediticio. Consiento que esta autorización tenga una vigencia de <b>3 años</b> contando a partir de hoy, y en su caso mientras mantengamos relación jurídica. Acepto que este documento quede bajo propiedad de financieraCREA <b>y/o</b> Círculo de Crédito para efectos de control y cumplimiento del artículo 28 de la LRSIC.</small> ', '', '')
			 		)),

			 		
		 		));

		 	$content[] = $this->form_column(2,5, array(
			 			$this->input_number('NIP'),
			 			$this->input_help('&nbsp;&nbsp;&nbsp;Introduzca el NIP',true),
			 		));

		 	$content[] =$this->botonGuardar('btn_guardar', 'Guardar', '', 'Autorizar');
		 	$content[] = $this->form_group(array(		 			
		 			$this->form_column(3,'', array(			 		
			 			$this->input_hidden('idcontratoglobal'),

			 			
			 		)), ));



		

		return $content;



	}

	public function autorizarFirmarDocumentos($id){
		$content = array();
		$content2 = array();

		$idurl = hash(sha1, $id);
		$tipo = hash(sha1, 1);

		$idurl = urlencode(base64_encode($id));
		$tipo = urlencode( base64_encode(1));
		

		$content[] = 	$this->title_seccion('NIP:');

		$content[] =$this->form_group(array(
		 					$this->form_column(12, '', array(
		 		array('tag'=>'a', 'href'=>'descargaDocumento.php?1='.$idurl.'&2='.$tipo.'','target'=>'_blank', 'class'=>'embed-link' , 'inside'=>array('<center>Descargar el documento</center>')),)), 

		 	));

		$content[] =$this->form_group(array(
		 					$this->form_column(12, '', array(
		 		array('tag'=>'a', 'href'=>'enviarDocumentoMail.php?1='.$idurl.'&2='.$tipo.'','target'=>'_blank', 'class'=>'embed-link' , 'inside'=>array('<center>Enviar el contrato a mi correo electrónico</center>')),)), 

		 	));

		$content[] = array('tag'=>'div',  'id'=>'results', 'class'=>'hidden' );
		$content[] = array('tag'=>'div',  'id'=>'pdf', 'class'=>'hidden' );
		$content[] = array('tag'=>'div',  'id'=>'rest', 'class'=>'hidden', 'inside'=>array('') );	
		$content[] =	$this->form_group(array(
		 			$this->form_column(12, '', array(
			 			$this->input_checkbox('firmapaquete', '<small class="text-muted text-justify">Manifiesto que he leído y estoy conforme con los documentos que
integran el <b>contrato global</b> del crédito <b> anticipo de quincena </b>, así como con la forma como podré llevar a cabo las
disposiciones del crédito</small> ', '', '')
			 		)),
));
		 	

		 			
		 	$content[] =	$this->form_group(array(		$this->form_column(12, '', array(
		 				array('tag'=>'div', 'id'=>'mensaje', 'inside'=>array(
		 					array('tag'=>'small', 'class'=>'text-muted text-justify', 'inside'=>array('Al momento en que Usted ingrese el NIP que se le envió a su correo y a su teléfono
móvil <b>(“NIP de Autorización del Crédito”)</b> en el sitio web de <b>MICROFINANCIERA
CRECE, S.A. DE C.V., SOFOM, E.N.R.</b> y oprima el botón de <b>"Firmar"</b>, Usted está
aceptando expresamente lo siguiente:' ))))
			 			
			 		)),

			 		$this->form_column(12, '', array(
		 				array('tag'=>'div', 'id'=>'mensaje', 'inside'=>array(
		 					array('tag'=>'small', 'class'=>'text-muted text-justify', 'inside'=>array(
		 						array('tag'=>'ol', 'class'=>'', 'inside'=>array(
		 							array('tag'=>'li', 'inside'=>array('Su conformidad con los términos y condiciones señalados en el Paquete de Disposición'))	,
		 							array('tag'=>'li', 'inside'=>array('Su conformidad para que una copia del expediente le sea entregado de manera electrónica en su correo electrónico, en términos de lo señalado por el artículo 9 de las Disposiciones de carácter general en materia de transparencia aplicables a las Sociedades Financieras de Objeto Múltiple, Entidades No Reguladas. Asimismo, Usted podrá consultar el Paquete de Disposición en las sucursales de MICROFINANCIERA CRECE, S.A. DE C.V., SOFOM, E.N.R.'))	,
		 						     array('tag'=>'li', 'inside'=>array('Que el o los comprobantes de los depósitos o transferencias de dinero a la cuenta de depósito señalada en el estado de cuenta que entregó a MICROFINANCIERA CRECE, S.A. DE C.V., SOFOM, E.N.R. serán prueba irrefutable del otorgamiento y disposición del crédito a su favor.'))	,
		 						     array('tag'=>'li', 'inside'=>array('Que la documentación e información proporcionada a MICROFINANCIERA CRECE, S.A. DE C.V., SOFOM, E.N.R. para el otorgamiento del crédito es verídica, actual y propia'))	,
		 						))
		 					))
		 				))
			 			
			 		)),


		 			$this->form_column(12, '', array(
		 				array('tag'=>'div', 'id'=>'mensaje', 'inside'=>array(
		 					array('tag'=>'small', 'class'=>'text-muted text-justify', 'inside'=>array('El ingreso del NIP de Autorización del Crédito es el medio a través del cual Usted manifiesta su consentimiento expreso con lo señalado en los incisos anteriores, en sustitución a su firma autógrafa, en términos de lo señalado por los artículos 1803, 1811, 1834, 1834 Bis del Código Civil Federal, los artículos 89 y 89 bis del Código de Comercio y el artículo 7 de las Disposiciones de carácter general en materia de transparencia aplicables a las Sociedades Financieras de Objeto Múltiple, Entidades No Reguladas'
		 					))
		 				))
		 			)),		 			
		 		));

		 	$content[] = $this->form_column(2,5, array(
			 			$this->input_number('NIP'),
			 			$this->input_help('&nbsp;&nbsp;&nbsp;Introduzca el NIP',true),
			 		));

		 	$content[] =$this->botonGuardar('btn_guardar', 'Guardar', '', 'Firmar');
		 	$content[] = $this->form_group(array(		 			
		 			$this->form_column(3,'', array(			 		
			 			$this->input_hidden('idcontratoglobal'),		 			
			 		)), ));
		return $content;



	}

	public function solicitarNuevoNIP($tipo){

		$content = array();
		$texto = array(
					1=>'por favor  firme la autorización lo antes posible,',
					2=>'por favor  firme su contrato lo antes posible,',
					3=>'',
					4=>'',
					5=>'');
		

		


		$content[] = 	$this->title_seccion('Solicitar nuevo NIP:');
		 	$content[] =	$this->form_group(array(

		 			

			 		$this->form_column(12, '', array(
		 				array('tag'=>'div', 'id'=>'mensaje', 'inside'=>array(
		 					array('tag'=>'small', 'class'=>'text-muted text-justify', 'inside'=>array('Click en el botón <b>"Solicitar NIP"</b>. El nuevo NIP le llegará a su correo electrónico, '.$texto[$tipo].' Gracias!' )))),
		 				
			 			
			 		)),
			 		



		 		));

		 	$content[] =	$this->form_group(array(
		 		$this->form_column(12, 5, array(
			 		array('tag'=>'button',  'class'=>'btn btn-block btn-info btn-sm col-sm-1 col-12 float-center  nuevoNIP', 'inside'=>array('Solicitar NIP')),
			 	)),
	));
		 	

			 		return $content;


	}

	public function apruebaRechazaEmpleador(){
		$usuario = new Usuario();
		$usuario-> setUsuarioData();
		$nombre = $usuario->getNombre();
		$rolId = $usuario->getRolId();

		$status_contrato = $this->get_value('cgs_refstatuscontratoglobal')	;
		//echo "sss=>".$status_contrato;

		$val = $status_contrato;
  			$status_filter = array();

   			switch ($val) {	
   			 	case "3":
			   	 	$status_filter = array("3", "4", "5",);
			    break;		 
			  	case "5":
			  		if($rolId == 21){
			  			$status_filter = array("5",);
			  		}else{
			  			$status_filter = array("5", "7","8");
			  		}			  				    
			    break;
			  
			  default:			   
			    break;
			  }




		$content = array();


		$content[] = $this->form_group(array(	
			 			$this->form_label_muted(12,'', true, 'Nombre completo'),
			 			$this->form_column(4,'', array(
				 		
				 			$this->muestra_descripcion_val('nombre'),
				 			$this->input_help('Nombre',true),
				 		)),
				 		$this->form_column(4,'', array(
				 			$this->muestra_descripcion_val('paterno'),
				 			$this->input_help('Apellido paterno',true),
				 		)),
				 		$this->form_column(4,'', array(
				 			$this->muestra_descripcion_val('materno'),
				 			$this->input_help('Apellido Materno',true),
				 		)),
				 	
				 	));



		$content[] = $this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Monto de otorgamiento'),
		 			$this->form_column(6, '', array(
		 				$this->muestra_descripcion_val('montootorgamiento',2),			 			
			 			
			 		)),
			 		
		 		));

		$content[] = $this->form_group(array(
		 			
		 			
			 		$this->form_label_muted(4,'', true, 'Número de pagos'),
		 			$this->form_column(6, '', array(
		 				$this->muestra_descripcion_val('numeropagos',2),			 			
			 			
			 		)),
		 		));


		$content[] =$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Forma de pago'),
		 			$this->form_column(4, '', array(
		 				$this->muestra_descripcion('descripcion','forma_pago','forma_pago_id',$this->get_value('refformapago')),

			 		 
			 			
			 		)),
		 			
		 			
		 		));

		$content[] =$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Año de contratación'),
		 			$this->form_column(4, '', array(
		 				$this->input_number('antiguedadanio'),

			 		 
			 			
			 		)),
		 			
		 			
		 		));


		$content[] =$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Mes de contratación'),
		 			$this->form_column(4, '', array(
		 				$this->input_select('antiguedadmes', array('cat_nombre'=>'tbmeses','id_cat'=>'idmes')), 
			 		)),
		 			
		 			
		 		));
		$content[] = $this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Aprobar crédito'),
		 			$this->form_column(6, '', array(
			 			$this->input_select('cgs_refstatuscontratoglobal', array('cat_nombre'=>'tbstatuscontratoglobal','id_cat'=>'idstatuscontratoglobal','filtros' => array(
								array(
									'field' => 'idstatuscontratoglobal',
									'value' => $status_filter
								)
							))), 
			 			$this->input_help('Autorizar / rechazar',true),
			 		)), 				
			 		
		 		));


		$content[] = $this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Observaciones'),
		 			$this->form_column(8,'', array(			 		
				 			 $this->input_textarea('bitacoraempleador',''),
				 			
				 		)),	
				 		$this->input_hidden('idcontratoglobal'),	

			 		
		 		));

	

	return $content;


	}
	public function formaContratoGlobalAdmin(){

		$content = array(
			$this->input_hidden('idcontratoglobal'),

		$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Status'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('refstatuscontratoglobal', array('cat_nombre'=>'tbstatuscontratoglobal','id_cat'=>'idstatuscontratoglobal')), 
			 			$this->input_help('Empresa',true),
			 		)),
		 		)),					
				
				$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Empresa'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('refempresaafiliada', array('cat_nombre'=>'tbempresaafiliada','id_cat'=>'idempresaafiliada')), 
			 			$this->input_help('Empresa',true),
			 		)),
		 		)),
		 		
		 		$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Tipo de crédito'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('reftipocontratoglobal', array('cat_nombre'=>'tbtipocontratoglobal','id_cat'=>'idtipocontratoglobal')), 
			 			$this->input_help('Tipo de crédito',true),
			 		)),
		 		)),

		 		$this->title_seccion('Datos personales'),

		 		

			 	$this->form_group(array(	
		 			$this->form_label_muted(12,'', true, 'Nombre completo'),
		 			$this->form_column(4,'', array(
			 		
			 			$this->input_text('nombre'),
			 			$this->input_help('Nombre',true),
			 		)),
			 		$this->form_column(4,'', array(
			 			$this->input_text('paterno'),
			 			$this->input_help('Apellido paterno',true),
			 		)),
			 		$this->form_column(4,'', array(
			 			$this->input_text('materno'),
			 			$this->input_help('Apellido Materno',true),
			 		)),
			 	
			 	)),


			 	

			 	$this->form_group(array(	
		 			
		 			$this->form_column(3,'', array(			 		
			 			$this->input_date('fechanacimiento'),
			 			$this->input_help('Fecha de nacimiento',true),
			 		)),
			 		$this->form_column(3,1, array(
			 			$this->input_select('refnacionalidad', array('cat_nombre'=>'nacionalidad','id_cat'=>'nacionalidad_id', 'descripcion'=>'pais_nombre')), 
			 			$this->input_help('País de nacimiento',true),
			 		)),

			 		$this->form_column(3,1, array(
			 			$this->input_select('refentidadnacimiento', array('cat_nombre'=>'entidad_nacimiento','id_cat'=>'entidad_nacimiento_id')), 
			 			$this->input_help('Estado',true),
			 		)),
			 		

			 		
			 	
			 	)),

			 	$this->form_group(array(	
		 			
		 			$this->form_column(2,'', array(
			 			$this->input_select('refgenero', array('cat_nombre'=>'tbgenero','id_cat'=>'idgenero')), 
			 			
			 			$this->input_help('Genero',true),
			 		)),

			 		$this->form_column(3,2, array(
			 			$this->input_text('rfc'),
			 			$this->input_help('RFC',true),
			 		)),

			 		$this->form_column(4,1, array(
			 			$this->input_text('curp'),
			 			$this->input_help('CURP',true),
			 		)),
			 	
			 	)),


			 	$this->form_group(array(	
		 			$this->form_label_muted(12,'', true, 'Datos del conyugue'),
		 			$this->form_column(4,'', array(
			 		
			 			$this->input_text('cnombre'),
			 			$this->input_help('Nombre',true),
			 		)),
			 		$this->form_column(4,'', array(
			 			$this->input_text('cpaterno'),
			 			$this->input_help('Apellido paterno',true),
			 		)),
			 		$this->form_column(4,'', array(
			 			$this->input_text('cmaterno'),
			 			$this->input_help('Apellido Materno',true),
			 		)),
			 	
			 	)),
			 	$this->title_seccion('Domicilio'),
		 							
				$this->form_group(array(	
		 			$this->form_label_muted(12,'', true, 'Dirección'),
		 			$this->form_column(8,'', array(
			 		
			 			$this->input_text('calle'),
			 			$this->input_help('Calle',true),
			 		)),
			 		$this->form_column(2,'', array(
			 			$this->input_text('numeroexterior'),
			 			$this->input_help('Número exterior',true),
			 		)),
			 		$this->form_column(2,'', array(
			 			$this->input_text('numerointerior'),
			 			$this->input_help('Número interior',true),
			 		)),

			 		
			 	
			 	)),


			 	$this->form_group(array(	
		 			
		 			$this->form_column(6,'', array(
			 		
			 			$this->input_text('colonia'),
			 			$this->input_help('Colonia',true),
			 		)),
			 		$this->form_column(2,'', array(
			 			$this->input_text('codigopostal'),
			 			$this->input_help('C.P.',true),
			 		)),
			 	
			 	)),

				$this->form_group(array(	
		 			$this->form_column(3, '', array(
			 			$this->input_select('refentidad', array('cat_nombre'=>'inegi2020_estado','id_cat'=>'estado_id')), 
			 			$this->input_help('Estado',true),
			 		)),

			 		$this->form_column(3, 1, array(			 			 
			 			$this->input_select('refmunicipio', array('cat_nombre'=>'inegi2020_municipio','id_cat'=>'municipio_id', 'filtros'=>array(
			 					array('field'=>'refestado', 'value'=>$this->get_value('refentidad')),
			 			))), 
			 			$this->input_help('Municipio',true),
			 		)),

			 		$this->form_column(3, 1, array(
			 			$this->input_select('reflocalidad', array('cat_nombre'=>'inegi2020_localidad','id_cat'=>'localidad_id', 'filtros'=>array(
                            array('field'=>'refestado', 'value'=>$this->get_value('refentidad')),
                            array('field'=>'refmunicipio', 'value'=>$this->get_value('refmunicipio')),
                        ))), 
			 			$this->input_help('Localidad',true),
			 		)),
			 	
			 	)),

			$this->form_group(array(	
		 			$this->form_label_muted(12,'', true, 'Telefonos'),
		 			$this->form_column(3,'', array(			 		
			 			$this->input_text('telefono1'),
			 			$this->input_help('Fijo',true),
			 		)),
			 		$this->form_column(2, '', array(
			 			$this->input_select('reftipotelefono1', array('cat_nombre'=>'tbtipotelefono','id_cat'=>'idtipotelefono')), 
			 			$this->input_help('Tipo',true),
			 		)),
			 		$this->form_column(3,1, array(
			 			$this->input_text('celular1'),
			 			$this->input_help('Celular',true),
			 		)),
			 		$this->form_column(2, '', array(
			 			$this->input_select('refcompania1', array('cat_nombre'=>'compania_celular','id_cat'=>'compania_celular_id','descripcion'=>'nombre')), 
			 			$this->input_help('Compañia',true),
			 		)),





			 	
			 	)),

				$this->form_group(array(	
		 			
		 			$this->form_column(3,'', array(			 		
			 			$this->input_text('telefono2'),
			 			$this->input_help('Fijo',true),
			 		)),
			 		$this->form_column(2, '', array(
			 			$this->input_select('reftipotelefono2', array('cat_nombre'=>'tbtipotelefono','id_cat'=>'idtipotelefono')), 
			 			$this->input_help('Tipo',true),
			 		)),
			 		$this->form_column(3,1, array(
			 			$this->input_text('celular2'),
			 			$this->input_help('Celular',true),
			 		)),
			 		$this->form_column(2, '', array(
			 			$this->input_select('refcompania2', array('cat_nombre'=>'compania_celular','id_cat'=>'compania_celular_id', 'descripcion'=>'nombre')), 
			 			$this->input_help('Compañia',true),
			 		)),			 	
			 	)),
				$this->title_seccion('Persona Polícamente Expuesta (PPE)'),

				$this->form_group(array(
		 			$this->form_label_muted_justify(12,'', true, '   A) ¿Desempeña o ha desempeñado funciones públicas destacadas en un país extranjero o en territorio nacional, como son, entre otros, jefes de estado o de gobierno, líderes políticos, funcionarios gubernamentales, judiciales o militares de alta jerarquía, altos ejecutivos de empresas estatales o funcionarios o miembros importantes de partidos políticos?'),
		 			$this->form_column(3, 4, array(
			 			$this->input_select('cargopublico', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
			 			$this->input_help('Cargo público',true),
			 		)),
		 		)),

		 		$this->form_group(array(
		 			$this->form_label_muted_justify(12,'', true, 'B) ¿Es cónyuge o tiene parentesco por consanguinidad o afinidad hasta el segundo grado con personas que caen en el supuesto de la pregunta anterior? '),
		 			$this->form_column(3, 4, array(
			 			$this->input_select('cargopublicofamiliar', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
			 			$this->input_help('Familiar con cargo público',true),
			 		)),
		 		)),

			 	$this->title_seccion('Historial créditicio'),

			 	$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Cuenta con algun crédito hipotecario?'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('creditohipotecario', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
			 			$this->input_help('Tipo de crédito',true),
			 		)),
		 		)),

				$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Ha ejercido en los últimos 2 años algún crédito automotriz?'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('creditoautomotriz', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
			 			$this->input_help('Empresa',true),
			 		)),
		 		)),

		 		$this->form_group(array(
		 			$this->form_label_muted(4,'', true, 'Cuenta con alguna tarjeta de crédito?'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('tarjetacredito', array('cat_nombre'=>'tbsino','id_cat'=>'idsino')), 
			 			$this->input_help('Empresa',true),
			 		)),

			 		$this->form_column(2, '', array(
			 			$this->input_number('digitostarjeta'),			 			
			 			$this->input_help('Últimos 4 digitos',true),
			 		)),
		 		)),


		 		array('tag'=>'br', 'class'=>'','inside'=>array()),

		 		$this->form_group(array(
		 			
		 			$this->form_column(12, '', array(
			 			$this->input_checkbox('burocredito', '<small class="text-muted text-justify">Hoy siendo '.$this->getFechaLetras().', <span class="nombreClienteAutoriza">'.$this->getNombreusuario().'</span> autoriza a  <b>MICROFINANCIERA CRECE, S.A. DE C.V., SOFOM, E.N.R.</b>  a consultar sus antecedentes crediticios por única ocasión ante las Sociedades de Información Crediticia que estime conveniente, declarando que conoce la naturaleza, alcance y uso que <b>MICROFINANCIERA CRECE, S.A. DE C.V., SOFOM, E.N.R.</b> hará de tal información.</small> ', '', '')
			 		)),

			 		
		 		)),
				
				/*array('tag'=>'row col-sm-2', 'class'=>'', 'inside'=>array(
					array('tag'=>'button', 'id'=>'btn_guardar', 'value'=>'Guardar', 'class'=>'btn btn-block btn-info btn-sm col-sm-1 col-12 float-right', 'inside'=>array('Guardar')),
				))*/
				$this->botonGuardar('btn_guardar', 'Guardar', '', 'Guardar'),
				

					
				);

		return $content;
	}


	public function wizardSolicicitud(){
		$title_seccion = array('zulma');
		$content =  array(

			array('tag'=>'div', 'class'=>'f1-steps', 'inside'=>array(
				array('tag'=>'div', 'class'=>'f1-progress', 'inside'=>array(
					array('tag'=>'div', 'class'=>'f1-progress-line', 'data-now-value'=>'16.66','data-number-of-steps'=>'3', 'style'=>'width: 16.66%;', 'inside'=>array())
				)),
				$this->f1_step(true,'fa-user','Datos personales'),
				$this->f1_step('','fa-key','Cuenta'),
				$this->f1_step('','fa-twitter','Redes sociales'),	
			)),


			// $this->f1_steps('', '', '', array(
			// 	$this->f1_step(true,'fa-user','Datos personales'),
			// 	$this->f1_step('','fa-key','Cuenta'),
			// 	$this->f1_step('','fa-twitter','Redes sociales'),				
			// )),

			
			$this->f1_fieldset('Datos personales', array(
				$this->form_group(array(
		 			$this->form_label(3,'', true, 'Catalogo'),
		 			$this->form_column(3, '', array(
			 			$this->input_select('credito_tipo_id', array('cat_nombre'=>'credito_tipo','id_cat'=>'credito_tipo_id')), 
			 			$this->input_help('Mes',true),
			 		)),
		 		)),
		 		$this->form_group(array(		 	
			 		$this->row_col_label(4,'Nombre','idprueba1',array(
			 			$this->input_text('nombre')
			 		)),
			 		$this->row_col_label(4,'Apellido paterno','idprueba1',array(
			 			$this->input_text('apellidopaterno')
			 		)),
			 		$this->row_col_label(4,'Apellido Materno','idprueba1',array(
			 			$this->input_text('apellidomaterno')
			 		)),
			 	)),
		 		$this->f1_buttons(true, false),
			)),

			$this->f1_fieldset('Datos personales 2', array(
				$this->form_group(array(		 	
			 		$this->row_col_label(4,'Nombre','idprueba1',array(
			 			$this->input_text('nombre')
			 		)),
			 		$this->row_col_label(4,'Apellido paterno','idprueba1',array(
			 			$this->input_text('apellidopaterno')
			 		)),
			 		$this->row_col_label(4,'Apellido Materno','idprueba1',array(
			 			$this->input_text('apellidomaterno')
			 		)),
			 	)),

			 	$this->form_group(array(		 	
			 		$this->row_col_label(4,'Nombre','idprueba1',array(
			 			$this->input_text('nombre')
			 		)),
			 		$this->row_col_label(4,'Apellido paterno','idprueba1',array(
			 			$this->input_text('apellidopaterno')
			 		)),
			 		$this->row_col_label(4,'Apellido Materno','idprueba1',array(
			 			$this->input_text('apellidomaterno')
			 		)),
			 	)),
			 	$this->f1_buttons(true, true,false, 'nuevaSolicitud'),
			)),		


			$this->f1_fieldset('Datos personales 2', array(
				$this->form_group(array(		 	
			 		$this->row_col_label(4,'Nombre','idprueba1',array(
			 			$this->input_text('nombre')
			 		)),
			 		$this->row_col_label(4,'Apellido paterno','idprueba1',array(
			 			$this->input_text('apellidopaterno')
			 		)),
			 		$this->row_col_label(4,'Apellido Materno','idprueba1',array(
			 			$this->input_text('apellidomaterno')
			 		)),
			 	)),

			 	$this->form_group(array(		 	
			 		$this->row_col_label(4,'Nombre','idprueba1',array(
			 			$this->input_text('nombre')
			 		)),
			 		$this->row_col_label(4,'Apellido paterno','idprueba1',array(
			 			$this->input_text('apellidopaterno')
			 		)),
			 		$this->row_col_label(4,'Apellido Materno','idprueba1',array(
			 			$this->input_text('apellidomaterno')
			 		)),
			 	)),
			 	$this->f1_buttons(false, true,true, 'nuevaSolicitud'),
			)),		
		);

		

		return $content;


	}

	public function altaSolicitud(){
		$content =  array(		
		 $this->section(true, array(
		 	$this->input_hidden('idprueba2'),
		 	$this->input_hidden('idprueba1'),
		 )),
		 $this->section(true, array(
		 	
		 	$this->form_group(array(
		 		$this->form_label(3,'', true, 'Catalogo'),
		 		$this->form_column(3, '', array(
			 		$this->input_select('credito_tipo_id', array('cat_nombre'=>'credito_tipo','id_cat'=>'credito_tipo_id')), 
			 			$this->input_help('Mes',true),
			 		)),
		 	)),

		 	$this->title_seccion('Datos personales del cliente'),
		 	$this->form_group(array(		 	
		 		$this->row_col_label(4,'Nombre','idprueba1',array(
		 			$this->input_text('nombre')
		 		)),
		 		$this->row_col_label(4,'Apellido paterno','idprueba1',array(
		 			$this->input_text('apellidopaterno')
		 		)),
		 		$this->row_col_label(4,'Apellido Materno','idprueba1',array(
		 			$this->input_text('apellidomaterno')
		 		)),
		 		
		 	)),


		 	$this->form_group(array(		 	
		 		
		 		$this->row_col_label(3,'Tipo Cliente','reftipocredito',array(
		 			$this->input_select('credito_tipo_id', array('cat_nombre'=>'credito_tipo','id_cat'=>'credito_tipo_id')), 	
		 		)),
		 	
		 	$this->footer_form( array(
		 		array('tag'=>'button','class'=>'btn btn-primary waves-effect nuevaSolicitud', 'type'=>'submit' ,'inside'=>array('Guardar') ),
		 		array('tag'=>'button','class'=>'btn btn-primary waves-effect editarSolicitud', 'type'=>'submit' ,'inside'=>array('Editar')),

		 	
		 	)),	
		 	)),
		 )),// seccion
		);

		return $content;
	}




	public function cargaDoctos(){

	}

	public function aceptapaquete(){

	}

	public function generaNip(){

	}

	public function buroCredito(){

	}
}


?>