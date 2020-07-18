<?php 
#include_once 'Conexion.inc.php';

	
class Query{
	private $query = '';
	private $resultSet = '';
	
	public function __construct(){
		
	}

	public function getResultSet()
	{
		return $this->resultSet;
	}

	public function setResultSet( $rs)
	{
		$this->resultSet = $rs;
	}
	
	/**
	 * @return string $query
	 */
	public function getQuery()
	{
		return $this->query;
	}

	/**
	 * @param string $query
	 */
	public function setQuery($query)
	{
		$this->query = $query;
	}

	public function clear($values){
		global $mysqli;
		
		foreach ($values as $field => $value){
			if($value === '' || $value === null){
				$value = 'NULL';
			}else{
				#$value = '\''.$mysqli->real_escape_string($value).'\'';
				$value = '\''.mysql_real_escape_string($value).'\'';
			}
			
			$values[$field] = $value;
		}
		
		return $values;
	}
	
	public function insert($table, $values){
		$values = $this->clear($values);
		
		$query = '';
		$query .= 'INSERT INTO `'.$table.'` ';
		$query .= ' (`'.implode('`, `', array_keys($values)).'`) VALUE';
		$query .= ' ('.implode(', ', $values).');';
		
		$this->setQuery($query);
	}
	
	public function update($table, $values, $where){
		$update = array();
		
		$values = $this->clear($values);		
		foreach ($values as $field => $value){
			$update[] = '`'.$field.'` = '.$value;
		}
		
		$query  = '';
		$query .= 'UPDATE `'.$table.'` SET';
		$query .= implode(', ', $update);
		$query .= ' WHERE '.$where;		
		$this->setQuery($query);
	}
	
	public function beginTrans(){
		#global $mysqli;

		$sql = $this->setQuery('BEGIN');
		$rs = $this->eject($sql,0);


		if(!$rs){
			echo 'Error al iniciar la Transaccion<br />';
			#echo $mysqli->errno.'<br />';
			#echo $mysqli->error.'<br />';
			echo mysql_error().'<br />';
			echo mysql_error().'<br />';
			die();
		}else{
			/* Desactivar la autoconsigna */
			#$mysqli->autocommit(FALSE);
			$sql = $this->setQuery('SET AUTOCOMMIT=0');
			$rs = $this->eject($sql);
			return $rs;
		}

	}

	public function commitTrans(){
		#global $mysqli;

		#$rs = $mysqli->commit();
		$sql = $this->setQuery('COMMIT');
		$rs = $this->eject($sql);
		if(!$rs){
			echo("Falló la consignación de la transacción\n");
    		die();
		}else{			
			return $rs;
		}
	}

	public function rollbackTrans(){
		#global $mysqli;
			
		#$rs = $mysqli->rollback();
		$sql = $this->setQuery('ROLLBACK');
		$rs = $this->eject($sql);
		if(!$rs){
			echo 'Error en rollback<br />';
			#echo $mysqli->errno.'<br />';
			#echo $mysqli->error.'<br />';
			echo mysql_error().'<br />';
			return $rs;
		}else{			
			return $rs;
		}
	}


	public function eject($accion = 0){
		#global $mysqli;	
		global $conn;
		
		#$rs = $mysqli->query($this->query);
		$rs = mysql_query($this->query, $conn);
		if(!$rs){
			echo 'Error al ejecutar query<br />';
			echo $this->query.'<br />';
			#echo $mysqli->errno.'<br />';
			echo mysql_error().'<br />';
			die();
		}else if($accion && $rs) {
			#$Id = $mysqli->insert_id;
			$Id = mysql_insert_id();				
			return $Id;
		}else{	
			$this->setResultSet($rs);		
			return $rs;
		}
	}



	public function ejectTrans($accion = 0){
		#global $mysqli;		
		#$rs = $mysqli->query($this->query);
		$rs = mysql_query($this->query);

		if(!$rs){
			echo 'Error al ejecutar query  1 => :<br />';
			echo $this->query.'<br />';
			#echo $mysqli->errno.'<br />';
			#echo $mysqli->error.'<br />';
			echo mysql_error().'<br />';			
		}else if($accion && $rs) {
			#$id = $mysqli->insert_id;
			$id = mysql_insert_id();
			return $id;
		}else{
			return $rs;
		}
		
	}

	public function selectCampo($campo, $tabla, $condicion = '' ){
		$where =  (!empty($condicion))? " WHERE ".$condicion :'';
		$sql = "SELECT ".$campo ." FROM  ". $tabla ." ".$where;		
		
		$this->setQuery($sql);
		$result = $this->eject();
		if(!$result){
			#return "Error al seleccionar el campo: ".$mysqli->errno;
			return "Error al seleccionar el campo: ".mysql_error();
		}
		#$rowCampo = $result->fetch_array(MYSQLI_ASSOC);
		$rowCampo = mysql_fetch_array($result, MYSQL_ASSOC);
		return $rowCampo[$campo]; 
	}

	public function fetchArray($resultSet, $tipo =2){
		#echo "\n <br>FETCHA_ARRAY()";
		$fetcharrayS = array();
		
		#$fetcharray  = $resultSet->fetch_array(MYSQLI_ASSOC);
		#var_dump($resultSet);
		$fetcharrayS = mysql_fetch_array($resultSet,MYSQL_BOTH);
		if($tipo ==2){
			$fetcharrayS = mysql_fetch_array($resultSet,MYSQL_ASSOC) ;			
		}
		if($tipo ==3){
			$fetcharrayS = mysql_fetch_array($resultSet,MYSQL_NUM);
			#ECHO "\n TIPO 3";
		}

		return $fetcharrayS;
	}

	public function fetchAll($resultSet, $tipo =2){
		$fetcharray = array();
		$resultS = $this->getResultSet();		
		#$fetcharray  = $resultSet->fetch_array(MYSQLI_ASSOC);
		$fetcharray = mysql_fetch_array($resultSet,MYSQL_BOTH);
		if($tipo ==2)
			$fetcharray = mysql_fetch_array($resultS,MYSQL_ASSOC);
		if($tipo ==3)
			$fetcharray = mysql_fetch_array($resultSet,MYSQL_NUM);

		return $fetcharray;
	}

	public function fetchRow($resultSet){
		$fetchRow = array();		
		#$fetchObject = $resultSet->fetch_row();
		$fetchRow = mysql_fetch_row($resultSet);
		return $fetchRow;
	}

	public function fetchObject($resultSet){
		$fetchObject = array();		
		#$fetchObject = $resultSet->fetch_object();
		$fetchObject = mysql_fetch_object($resultSet);
		return $fetchObject;
	}

	public function numRows($resultSet){
		$numRosw ='';		
		#$numRosw = $resultSet->num_rows;
		$numRosw = mysql_num_rows($resultSet);
		return $numRosw;
	}

	
	
	public function table_create($name_table, $fields){
		$_create = 'CREATE TABLE IF NOT EXISTS `'.$name_table.'` (';
		$_create .= implode(',', $fields);
		$_create .= ') ENGINE=MyISAM DEFAULT CHARSET=utf8';
		
		#$this->setQuery($_create);
	}
	
	public function table_truncate($name_table){
		$_truncate = 'TRUNCATE TABLE `'.$name_table.'`;';
		#$this->setQuery($_truncate);
	}
	
	public function table_optimize($name_table){
		$_optimizer = 'OPTIMIZE TABLE `'.$name_table.'`';
		$this->setQuery($_optimizer);
	}
}

?>