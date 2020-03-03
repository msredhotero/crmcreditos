<?php

/**
 * @Usuarios clase en donde se accede a la base de datos
 * @ABM consultas sobre las tablas de usuarios y usarios-clientes
 */

date_default_timezone_set('America/Mexico_City');

class ServiciosContactos {

   /* PARA Contactoproductos */

   function Resultados() {
      $sql = "SELECT
         	 sum(case when pregunta = 1 then 1 end) as 'Si',
             sum(case when pregunta = 2 then 1 end) as 'Me interesa',
             sum(case when pregunta = 3 then 1 end) as 'No'
         	FROM dbcontactos";
      $res = $this->query($sql,0);

      return $res;
   }

   function insertarContactoproductos($refcontactos,$refproductos) {
   $sql = "insert into dbcontactoproductos(idcontactoproducto,refcontactos,refproductos)
   values ('',".$refcontactos.",".$refproductos.")";
   $res = $this->query($sql,1);
   return $res;
   }


   function modificarContactoproductos($id,$refcontactos,$refproductos) {
   $sql = "update dbcontactoproductos
   set
   refcontactos = ".$refcontactos.",refproductos = ".$refproductos."
   where idcontactoproducto =".$id;
   $res = $this->query($sql,0);
   return $res;
   }


   function eliminarContactoproductos($id) {
   $sql = "delete from dbcontactoproductos where idcontactoproducto =".$id;
   $res = $this->query($sql,0);
   return $res;
   }


   function traerContactoproductos() {
   $sql = "select
   c.idcontactoproducto,
   c.refcontactos,
   c.refproductos
   from dbcontactoproductos c
   order by 1";
   $res = $this->query($sql,0);
   return $res;
   }


   function traerContactoproductosPorId($id) {
   $sql = "select idcontactoproducto,refcontactos,refproductos from dbcontactoproductos where idcontactoproducto =".$id;
   $res = $this->query($sql,0);
   return $res;
   }


   /* Fin */
   /* /* Fin de la Tabla: dbcontactoproductos*/


   /* PARA Contactos */

   function insertarContactos($nombre,$apellido,$email,$tipotelefono,$telefono,$nombreagencia,$pregunta,$observaciones) {
   $sql = "insert into dbcontactos(idcontacto,nombre,apellido,email,tipotelefono,telefono,nombreagencia,pregunta,observaciones)
   values ('','".$nombre."','".$apellido."','".$email."',".$tipotelefono.",'".$telefono."','".$nombreagencia."',".$pregunta.",'".$observaciones."')";
   $res = $this->query($sql,1);
   return $res;
   }


   function modificarContactos($id,$nombre,$apellido,$email,$tipotelefono,$telefono,$nombreagencia,$pregunta) {
   $sql = "update dbcontactos
   set
   nombre = '".$nombre."',apellido = '".$apellido."',email = '".$email."',tipotelefono = ".$tipotelefono.",telefono = '".$telefono."',nombreagencia = '".$nombreagencia."',pregunta = ".$pregunta."
   where idcontacto =".$id;
   $res = $this->query($sql,0);
   return $res;
   }


   function eliminarContactos($id) {
   $sql = "delete from dbcontactos where idcontacto =".$id;
   $res = $this->query($sql,0);
   return $res;
   }


   function traerContactos() {
   $sql = "select
   c.idcontacto,
   c.nombre,
   c.apellido,
   c.email,
   c.tipotelefono,
   c.telefono,
   c.nombreagencia,
   c.pregunta
   from dbcontactos c
   order by 1";
   $res = $this->query($sql,0);
   return $res;
   }

   function traerContactosCompleto() {
      $sql = "select
            c.idcontacto,
            c.nombre,
            c.apellido,
            c.nombreagencia,
            (case when c.pregunta = 1 then 'Si'
         		 when c.pregunta = 2 then 'Me interesa recibir mas informacion'
                  when c.pregunta = 3 then 'No' end) as respuesta,
            c.observaciones,
            p.producto
            from dbcontactos c
            left join dbcontactoproductos cp on c.idcontacto = cp.refcontactos
            left join tbproductos p on p.idproducto = cp.refproductos";
      $res = $this->query($sql,0);
      return $res;
   }


   function traerContactosPorId($id) {
   $sql = "select idcontacto,nombre,apellido,email,tipotelefono,telefono,nombreagencia,pregunta from dbcontactos where idcontacto =".$id;
   $res = $this->query($sql,0);
   return $res;
   }


   /* Fin */
   /* /* Fin de la Tabla: dbcontactos*/


   /* PARA Productos */

   function insertarProductos($producto) {
   $sql = "insert into tbproductos(idproducto,producto)
   values ('','".$producto."')";
   $res = $this->query($sql,1);
   return $res;
   }


   function modificarProductos($id,$producto) {
   $sql = "update tbproductos
   set
   producto = '".$producto."'
   where idproducto =".$id;
   $res = $this->query($sql,0);
   return $res;
   }


   function eliminarProductos($id) {
   $sql = "delete from tbproductos where idproducto =".$id;
   $res = $this->query($sql,0);
   return $res;
   }


   function traerProductos() {
   $sql = "select
   p.idproducto,
   p.producto
   from tbproductos p
   order by 1";
   $res = $this->query($sql,0);
   return $res;
   }


   function traerProductosPorId($id) {
   $sql = "select idproducto,producto from tbproductos where idproducto =".$id;
   $res = $this->query($sql,0);
   return $res;
   }


   /* Fin */
   /* /* Fin de la Tabla: tbproductos*/



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
