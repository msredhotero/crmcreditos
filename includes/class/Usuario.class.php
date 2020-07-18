<?php
class Usuario{
	private $usuario_id = '';
	private $usuario = '';
	private $nombre = '';
	private $email = '';
	private $rol_id = '';
	private $rol = '';
	private $empresa ='';
	private $empresa_id ='';

	

	public  function __construct($usuario_id = ''){
		$query = new Query();
		$valida_acceso_general = false;
		
		if($usuario_id == ''){			
			if(isset($_SESSION['usuaid_sahilices']) ){
				$this->usuario_id = $_SESSION['usuaid_sahilices'];
				$this->nombre = $_SESSION['nombre_sahilices'];
				$this->usuario = $_SESSION['usua_sahilices'];
				$this->email = $_SESSION['email_sahilices'];
				$this->rol_id = $_SESSION['idroll_sahilices'];
				$this->rol = $_SESSION['refroll_sahilices'];
			}
						
		}else{
			$sqlUser = "SELECT
					   u.usuario_id,
	                   u.nombre,
	                   u.email,
	                   u.usuario,
	                   r.descripcion,
	                   r.usuario_rol_id
	               FROM
	                   usuario u
	                       INNER JOIN
	                   usuario_rol r ON r.usuario_rol_id = u.usuario_rol_id
	               WHERE
	                   usuario_id = ".$usuario_id ;
	                   
			$query->setQuery($sqlUser);
			$rs = $query->eject();
			$objUser = $query->fetchObject($rs);
			$this->usuario_id = $objUser->usuario_id;
			$this->nombre = $objUser->nombre;
			$this->usuario = $objUser->usuario;
			$this->email = $objUser->email;
			$this->rol_id = $objUser->usuario_rol_id;
			$this->rol = $objUser->descripcion;


		}

		// funciones para buscar mas datos del usuario

	}

	public function setUsuarioData(){
		if(isset($_SESSION['usuaid_sahilices']) ){
			$this->usuario_id = $_SESSION['usuaid_sahilices'];
			$this->nombre = $_SESSION['nombre_sahilices'];
			$this->usuario = $_SESSION['usua_sahilices'];
			$this->email = $_SESSION['email_sahilices'];
			$this->rol_id = $_SESSION['idroll_sahilices'];
			$this->rol = $_SESSION['refroll_sahilices'];
		}
	}

	public function getUsuarioId()
	{
		return $this->usuario_id;
	}

	public function getUsuario()
	{
		return $this->usuario;
	}

	public function getNombre()
	{
		return $this->nombre;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function getRol()
	{
		return $this->rol;
	}

	public function getRolId()
	{
		return $this->rol_id;
	}

	public function getEmpresa()
	{
		return $this->empresa;
	}
	public function getEmpresaId()
	{
		return $this->empresa_id;
	}


	public function generaTokenContrato()
	{

	}

	public function generaTokenDispocision()
	{

	}

	public function filtroComboTipoContrato($idContratoGlobal=NULL, $opcSelected){
		// si el cliente ya tiene un contrato global activo ya no puede generar otro contrato global, se debe de filtrar el combo tipo contrato para que lo creditos santo adelanto no se muetren como opciones
		$mostrarOpcion = true;
		$arrayOpciones = array();
		$query  = new Query ();
		$sqlBC = "SELECT usuario_id, `refempresaafiliada` FROM  dbcontratosglobales WHERE reftipocontratoglobal IN (1,2) AND 	usuario_id =  ".$this->getUsuarioId()." ";
		$query->setQuery($sqlBC);
		$res1 = $query->eject();
		$numero = $query->numRows($res1);
		
		if($numero>=1){
			
			$selectOPC = "SELECT dbcea.reftipocontratoglobal as tipoCredito FROM `dbcontratoempresaafiliada` dbcea JOIN dbcontratosglobales dbcg ON dbcg.refempresaafiliada = dbcea.`refempresaafiliada` WHERE dbcg.usuario_id = ".$this->getUsuarioId()."  AND dbcea.reftipocontratoglobal NOT IN (1,2)  ";

			
			$query->setQuery($selectOPC);
			$resQ = $query->eject();
			while($objOPC = $query->fetchObject($resQ) ){
				
				$arrayOpciones[] = $objOPC->tipoCredito;
			}
			if($opcSelected != ''){
				$arrayOpciones[] = $opcSelected;
			}

			

		}else{
			$selectOPC = "SELECT dbcea.reftipocontratoglobal as tipoCredito  FROM `dbcontratoempresaafiliada` dbcea JOIN dbcontratosglobales dbcg ON dbcg.refempresaafiliada = dbcea.`refempresaafiliada` WHERE dbcg.usuario_id = ".$this->getUsuarioId()."    ";
			$query->setQuery($selectOPC);
			$resQ = $query->eject();
			while($objOPC = $query->fetchObject($resQ) ){
				$arrayOpciones[] = $objOPC->tipoCredito;
			}

		}
		$arrayOpciones  = array_unique($arrayOpciones);
		return $arrayOpciones;	

	}

	public function validadUsuarioContrato($idU,$idCG ){
		$urlCorrupta = true;
		$query = new Query();
		if(!empty($idU) && !empty($idCG)){
			$sqlBuscaContrato = "SELECT * FROM dbcontratosglobales WHERE usuario_id = $idU and idcontratoglobal = $idCG ";
			$query->setQuery($sqlBuscaContrato);
			$resCont = $query->eject();
			$registros = $query->numRows($resCont);
			if($registros>=1){
				$urlCorrupta = false;
			}
		}
		return $urlCorrupta;

	}



	
}