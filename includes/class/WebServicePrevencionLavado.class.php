<?php

class WebServicePrevencionLavado
{

	public $Usuario = '';
	public $Password = '';
	public $url = '';
	public $data = '';
	public $nombre ='';
	public $apellido = '';
	public $identificacion = '';
	public $cadena = '';


	public function __construct($nombre, $apellido, $identificacion){
		$this->Usuario = 'anal2';
		$this->Password = '7D434594';
		$this->url = 'https://www.prevenciondelavado.com/listas/api/busqueda';
		$this->nombre = $nombre;
		$this->apellido = $apellido;
		$this->identificacion = $identificacion;
		$this->busqueda();
	}

	public function getNombre (){
		return $this->nombre;
	}

	public function setNombre ($nombre){
		$this->nombre = $nombre;
	}

	public function getApellido (){
		return $this->apellido;
	}

	public function setApellido ($apellido){
		$this->apellido = $apellido;
	}

	public function getIdentificacion (){
		return $this->identificacion;
	}

	public function setIdentificacion ($identificacion){
		$this->identificacion = $identificacion;
	}

	public function getCadena (){
		return $this->cadena;
	}

	

	public function busqueda(){
		//create a new cURL resource
        $ch = curl_init($this->url);


        $data = array(
            'Usuario' => $this->Usuario,
            'Password' => $this->Password,
            'Apellido' => $this->apellido,
            'Nombre' => $this->nombre,
            'Identificacion' => $this->identificacion,
            'Incluye_SAT' => 'S',
            "PEPS_otros_paises" => "S",            
        );

        //attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        //set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        //return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute the POST request
        $result = curl_exec($ch);
        echo    $result;

        $arregloResultado = array();
        #$arregloResultado = json_decode($result);
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
        $this->cadena = $cadena;
	}


	
	
}




?>