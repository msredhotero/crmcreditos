<?php

date_default_timezone_set('America/Mexico_City');

class appconfig {

function conexion() {
	/*
	

	$hostname = "localhost";
	$database = "u776896097_esf";
	$username = "u776896097_jfonc";
	$password = "rhcp7575"; */

	$hostname = "localhost";
	$database = "financ13_esf";
	$username = "root";
	$password = "";

		$conexion = array("hostname" => $hostname,
						  "database" => $database,
						  "username" => $username,
						  "password" => $password);

		return $conexion;
}

}




?>
