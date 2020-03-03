<?php

date_default_timezone_set('America/Mexico_City');

class appconfig {

function conexion() {
	/*
	$hostname = "localhost";
	$database = "u115752684_desa";
	$username = "u115752684_desa";
	$password = "@Chivas11";
	*/

	$hostname = "localhost";
	$database = "u115752684_asesores";
	$username = "root";
	$password = "";

/*
		$hostname = "PMYSQL105.dns-servicio.com:3306";
		$database = "6435338_riderz";
		$username = "alexriderz";
		$password = "_alexriderz123*";
		//u235498999_kike usuario
		*/

		$conexion = array("hostname" => $hostname,
						  "database" => $database,
						  "username" => $username,
						  "password" => $password);

		return $conexion;
}

}




?>
