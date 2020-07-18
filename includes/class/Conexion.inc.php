<?php


$appconfig	= new appconfig();
$datos		= $appconfig->conexion();
$hostname	= $datos['hostname'];
$database	= $datos['database'];
$username	= $datos['username'];
$password	= $datos['password'];

#print_r($datos);

define("SERVIDOR_LOCAL", "http://localhost/crmcreditos.git/trunk/");
define("SERVIDOR",   "http://financieracrea.com/esf/crmcreditos/");
 $directorio_local = ($_SERVER['SERVER_NAME']=='localhost')?'http://localhost/crmcreditos.git/trunk/':'https://'.$_SERVER['SERVER_NAME'].'/esf/crmcreditos/';

define("DIR_LOCAL", $directorio_local);

define("DIR_UPLOAD", $directorio_local."upload/");

/// para actualizar vesrion
/*$mysqli = new mysqli($hostname, $username, $password, $database);
if ($mysqli->connect_errno) {
	echo "Fallo al contenctar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
$codifica =true;
if($codifica){
	$mysqli->set_charset("utf8");
}*/

 $conn = mysql_connect($hostname,$username,$password);
 mysql_select_db($database);
 mysql_set_charset("utf8"); 
 #echo "<br>". $conn."<br>";

 		   #mysql_connect($hostname,$username,$password) or die ("No se puede conectar con el servidor MYSQL".mysql_error());



?>
