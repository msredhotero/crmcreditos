<?php
include 'Query.class.php';

$query = new Query();


$sqlUsuario = "SELECT * FROM usuario WHERE usuario_id < 20";
$query->setQuery($sqlUsuario);
$rs = $query->eject();
while ($row = mysql_fetch_array($rs)) {

	echo "<br>usuario_id => ".$row['usuario_id']. " ". " nombre completo =>".$row['usuario'];


}

echo "<hr>....................";
$sqlUsuario = "SELECT * FROM usuario WHERE usuario_id < 20";
$query->setQuery($sqlUsuario);
$rs = $query->eject();
while ($row = $query->fetchArray($rs)) {

	echo "<br>usuario_id => ".$row['usuario_id']. " ". " nombre completo =>".$row['usuario'];


}

echo "<hr>....................";

$sqlUsuario = "SELECT * FROM usuario WHERE usuario_id < 20";
$query->setQuery($sqlUsuario);
$rs = $query->eject();
while ($row = $query->fetchObject($rs)) {

	echo "<br>usuario_id => ".$row->usuario_id. " ". " nombre completo =>".$row->usuario;


}

?>