<?php 
include_once 'Conexion.inc.php';

	
class Query{
	private $query = '';
	
	public function __construct(){
		
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
				$value = '\''.$mysqli->real_escape_string($value).'\'';
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
		global $mysqli;

		$sql = $this->setQuery('BEGIN');
		$rs = $this->eject($sql,0);

		if(!$rs){
			echo 'Error al iniciar la Transaccion<br />';
			echo $mysqli->errno.'<br />';
			echo $mysqli->error.'<br />';
			die();
		}else{
			/* Desactivar la autoconsigna */
			$mysqli->autocommit(FALSE);
			return $rs;
		}

	}

	public function commitTrans(){
		global $mysqli;

		$rs = $mysqli->commit();
		if(!$rs){
			echo("Falló la consignación de la transacción\n");
    		die();
		}else{			
			return $rs;
		}
	}

	public function rollbackTrans(){
		global $mysqli;
			
		$rs = $mysqli->rollback();
		if(!$rs){
			echo 'Error en rollback<br />';
			echo $mysqli->errno.'<br />';
			echo $mysqli->error.'<br />';
			return $rs;
		}else{			
			return $rs;
		}
	}


	public function eject($accion = 0){
		global $mysqli;
		
		
		$rs = $mysqli->query($this->query);
		if(!$rs){
			echo 'Error al ejecutar query eject<br />';
			echo $this->query.'<br />';
			echo $mysqli->errno.'<br />';
			echo $mysqli->error.'<br />';
			die();
		}else if($accion && $rs) {
			$Id = $mysqli->insert_id;

			return $Id;
		}else{
			echo "NO 	ACTION";
			return $rs;
		}
	}

	public function ejectTrans($accion = 0){
		global $mysqli;		
		$rs = $mysqli->query($this->query);
		if(!$rs){
			echo 'Error al ejecutar query => :<br />';
			echo $this->query.'<br />';
			echo $mysqli->errno.'<br />';
			echo $mysqli->error.'<br />';			
		}else if($accion && $rs) {
			$id = $mysqli->insert_id;
			return $id;
		}else{
			return $rs;
		}
		
	}
	
	public function table_create($name_table, $fields){
		$_create = 'CREATE TABLE IF NOT EXISTS `'.$name_table.'` (';
		$_create .= implode(',', $fields);
		$_create .= ') ENGINE=MyISAM DEFAULT CHARSET=utf8';
		
		$this->setQuery($_create);
	}
	
	public function table_truncate($name_table){
		$_truncate = 'TRUNCATE TABLE `'.$name_table.'`;';
		$this->setQuery($_truncate);
	}
	
	public function table_optimize($name_table){
		$_optimizer = 'OPTIMIZE TABLE `'.$name_table.'`';
		$this->setQuery($_optimizer);
	}
}

?>