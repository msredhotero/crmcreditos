<?php

/**
 * @Usuarios clase en donde se accede a la base de datos
 * @ABM consultas sobre las tablas de usuarios y usarios-clientes
 */

date_default_timezone_set('America/Mexico_City');

class ServiciosReferencias {


   /* PARA Configuracion */

   function insertarConfiguracion($razonsocial,$empresa,$sistema,$direccion,$telefono,$email) {
   $sql = "insert into tbconfiguracion(idconfiguracion,razonsocial,empresa,sistema,direccion,telefono,email)
   values (null,'".$razonsocial."','".$empresa."','".$sistema."','".$direccion."','".$telefono."','".$email."')";
   $res = $this->query($sql,1);
   return $res;
   }


   function modificarConfiguracion($id,$razonsocial,$empresa,$sistema,$direccion,$telefono,$email) {
   $sql = "update tbconfiguracion
   set
   razonsocial = '".$razonsocial."',empresa = '".$empresa."',sistema = '".$sistema."',direccion = '".$direccion."',telefono = '".$telefono."',email = '".$email."'
   where idconfiguracion =".$id;
   $res = $this->query($sql,0);
   return $res;
   }


   function eliminarConfiguracion($id) {
   $sql = "delete from tbconfiguracion where idconfiguracion =".$id;
   $res = $this->query($sql,0);
   return $res;
   }


   function traerConfiguracion() {
   $sql = "select
   c.idconfiguracion,
   c.razonsocial,
   c.empresa,
   c.sistema,
   c.direccion,
   c.telefono,
   c.email
   from tbconfiguracion c
   order by 1";
   $res = $this->query($sql,0);
   return $res;
   }


   function traerConfiguracionPorId($id) {
   $sql = "select idconfiguracion,razonsocial,empresa,sistema,direccion,telefono,email from tbconfiguracion where idconfiguracion =".$id;
   $res = $this->query($sql,0);
   return $res;
   }

   /* Fin */
   /* /* Fin de la Tabla: tbconfiguracion*/


 /*****************************       fin         ************************************************/

function query($sql,$accion) {



		require_once 'appconfig.php';

		$appconfig	= new appconfig();
		$datos		= $appconfig->conexion();
		$hostname	= $datos['hostname'];
		$database	= $datos['database'];
		$username	= $datos['username'];
		$password	= $datos['password'];

		$conex = mysql_connect($hostname,$username,$password) or die ("no se puede conectar".mysql_error());

		mysql_select_db($database);

		        $error = 0;
		mysql_query("BEGIN");
		$result=mysql_query($sql,$conex);
		if ($accion && $result) {
			$result = mysql_insert_id();
		}
		if(!$result){
			$error=1;
		}
		if($error==1){
			mysql_query("ROLLBACK");
			return false;
		}
		 else{
			mysql_query("COMMIT");
			return $result;
		}

	}

}

?>
