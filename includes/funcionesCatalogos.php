<?php

/**
 * @Usuarios clase en donde se accede a la base de datos
 * @ABM consultas sobre las tablas de usuarios y usarios-clientes
 */

date_default_timezone_set('America/Mexico_City');

class ServiciosCatalogos {
	//$query = new Query();

function __contruct(){
	$query = new Query();
}	

function GUID()
{
    if (function_exists('com_create_guid') === true)
    {
        return trim(com_create_guid(), '{}');
    }

    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}


function traerCatalogoRechazo() {
	$query = new Query();
	$sql = "SELECT rc.idrechazocausa,
                   rc.descripcion               
			FROM tbrechazocausa rc	";       
	$query->setQuery($sql);	
	$res = $query->eject(0);
	return $res;
}

function traerCatalogoAsesor() {
	$query = new Query();
	$sql = "SELECT ase.idasesor,
                   ase.nombre               
			FROM  tbasesores ase	";       
	$query->setQuery($sql);	
	$res = $query->eject(0);
	return $res;
}



function traerCatalogoUDI() {
  $query = new Query();
  $sql = "SELECT u.idudi,
                   u.descripcion               
      FROM  tbudi u ";       
  $query->setQuery($sql); 
  $res = $query->eject(0);
  return $res;
}

function traerCatalogoRiesgoElemento() {
  $query = new Query();
  $sql = "SELECT re.idriesgoelemento,
                   re.descripcion ,
                   re.peso 
                   FROM  tbriesgoelementos re ";       
  $query->setQuery($sql); 
  $res = $query->eject(0);
  return $res;
}

function traerCatalogoRiesgoElementoPorId($id) {
  $query = new Query();
  $sql = "SELECT re.idriesgoelemento,
                   re.descripcion ,
                   re.peso 
                   FROM  tbriesgoelementos re  
                   WHERE re.idriesgoelemento = ".$id;       
  $query->setQuery($sql); 
  $res = $query->eject(0);
  return $res;
}

function traerCatalogoRiesgoIndicador() {
  $query = new Query();
  $sql = "SELECT ri.idriesgoindicador,                  
                   ri.descripcion,
                   ri.peso,
                   ri.maximo,
                   ri.minimo
                   FROM  tbriesgoindicadores ri";       
  $query->setQuery($sql); 
  $res = $query->eject(0);
  return $res;
}

function traerRiesgoIndicadorPorId($id){
  $query = new Query();
  $sql = "SELECT ri.idriesgoindicador,  
                  ri.refriesgoelemento,                
                   ri.descripcion,
                   ri.peso,
                   ri.maximo,
                   ri.minimo                   
                   FROM  tbriesgoindicadores ri 
                   WHERE ri.idriesgoindicador = ".$id;       
  $query->setQuery($sql); 
  $res = $query->eject(0);
  return $res;

}

function traerCatalogoRiesgoVariable() {
  $query = new Query();
  $sql = "SELECT re.idriesgovariable,
                   re.descripcion ,
                   re.peso 
                   FROM  tbriesgovariables re ";       
  $query->setQuery($sql); 
  $res = $query->eject(0);
  return $res;
}
         
function traerRiesgoVariablePorId($id) {
  $query = new Query();
  $sql = "SELECT re.idriesgovariable,
                   re.refriesgoindicador,
                   re.descripcion ,
                   re.peso 
                   FROM  tbriesgovariables re  
                   WHERE re.idriesgovariable = ".$id;       
  $query->setQuery($sql); 
  $res = $query->eject(0);
  return $res;
}

function traerCatalogoRiesgoNivel() {
  $query = new Query();
  $sql = "SELECT re.idriesgonivel,
                   re.descripcion ,
                   re.valor,
                   re.activo

                   FROM  tbriesgoniveles re ";       
  $query->setQuery($sql); 
  $res = $query->eject(0);
  return $res;
}


function traerRiesgoNivelPorId($id) {
  $query = new Query();
  $sql = "SELECT re.idriesgonivel,                   
                   re.descripcion ,
                   re.valor,
                   re.activo
                   FROM  tbriesgoniveles re  
                   WHERE re.idriesgonivel = ".$id;       
  $query->setQuery($sql); 
  $res = $query->eject(0);
  return $res;
}

function traerContratoGlobalesFirmasPendientes() {
  $query = new Query();
  $usuario = new Usuario();
  $usuarioId = $usuario->getUsuarioId();
  $sql = "SELECT fc.idfirmacontratoglobal ,
                 CONCAT(dbcg.nombre, ' ' , dbcg.paterno,  ' ',dbcg.materno ) AS nombre_cliente,
                 tc.descripcion as tipocredito, 
                 CONCAT('$',FORMAT(dbcg.montootorgamiento,2)) AS monto,                
                 fc.fecha,                 
                 'PENDIENTE DE FIRMA ' as status_firma ,
                 dbcg.nombre,              
                fc.status,
                fc.hora,
                fc.reftoken                                        
                FROM  dbfirmascontratosglobales fc
                JOIN dbcontratosglobales dbcg ON fc.refcontratoglobal = dbcg.idcontratoglobal
                JOIN tbtipocontratoglobal tc ON dbcg.reftipocontratoglobal = tc.idtipocontratoglobal
                JOIN usuario user ON  dbcg.usuario_id = user.usuario_id AND   user.usuario_id = ".  $usuarioId." WHERE   fc.status = 1 ";
                        
  $query->setQuery($sql); 
  $res = $query->eject(0);
  return $res;
}

function traerContratosActivos() {
  $query = new Query();
  $usuario = new Usuario();
  $usuarioId = $usuario->getUsuarioId();
  $sql = "SELECT fc.idfirmacontratoglobal ,
                 CONCAT(dbcg.nombre, ' ' , dbcg.paterno,  ' ',dbcg.materno ) AS nombre_cliente,
                 tc.descripcion as tipocredito, 
                 CONCAT('$',FORMAT(dbcg.montootorgamiento,2)) AS monto,                
                 fc.fecha,                 
                 'PENDIENTE DE FIRMA ' as status_firma ,
                 dbcg.nombre,              
                fc.status,
                fc.hora,
                fc.reftoken                                        
                FROM  dbfirmascontratosglobales fc
                JOIN dbcontratosglobales dbcg ON fc.refcontratoglobal = dbcg.idcontratoglobal
                JOIN tbtipocontratoglobal tc ON dbcg.reftipocontratoglobal = tc.idtipocontratoglobal
                JOIN usuario user ON  dbcg.usuario_id = user.usuario_id AND   user.usuario_id = ".  $usuarioId." WHERE   fc.status = 2 ";
                        
  $query->setQuery($sql); 
  $res = $query->eject(0);
  return $res;
}

function traerCatalogoRechazoajax($length, $start, $busqueda,$colSort,$colSortDir, $perfil) {
	$query = new Query();
    $where = '';
    $roles = '';

    if ($perfil != '') {
     	$roles = " u.usuario_rol_id = ".$perfil." and ";
    } else {
     	$roles = '';
    }

	$busqueda = str_replace("'","",$busqueda);
	if ($busqueda != '') {
		$where = "where ".$roles." (rc.descripcion like '%".$busqueda."%' ";
	} else {
      if ($perfil != '') {
         $where = " where u.usuario_rol_id = ".$perfil;
      }
   }

    $sql = "SELECT rc.idrechazocausa,
                   rc.descripcion               
			FROM tbrechazocausa rc			
         ".$where."
      	order by ".$colSort." ".$colSortDir."
      	limit ".$start.",".$length;  
  
	$query->setQuery($sql);
	$res = $query->eject();
	

	return $res;
}

function traerCatalogoAsesoresajax($length, $start, $busqueda,$colSort,$colSortDir, $perfil) {
	$query = new Query();
    $where = '';
    $roles = '';
	$busqueda = str_replace("'","",$busqueda);
	if ($busqueda != '') {
		$where = "where ".$roles." (ase.nombre like '%".$busqueda."%' ";
	} else {
      if ($perfil != '') {
         $where = " where u.usuario_rol_id = ".$perfil;
      }
   }

    $sql = "SELECT ase.idasesor,
                   ase.nombre               
			FROM  tbasesores ase	
         ".$where."
      	order by ".$colSort." ".$colSortDir."
      	limit ".$start.",".$length;  
	$query->setQuery($sql);
	$res = $query->eject();
	return $res;
}


function traerCatalogoUDIjax($length, $start, $busqueda,$colSort,$colSortDir, $perfil) {
	$query = new Query();
    $where = '';
    $roles = '';
	$busqueda = str_replace("'","",$busqueda);
	if ($busqueda != '') {
		$where = "where ".$roles." (u.descripcion, like '%".$busqueda."%' )  ";
	} else {
      if ($perfil != '') {
         $where = " where u.usuario_rol_id = ".$perfil;
      }
   }

    $sql = "SELECT u.idudi,
                   u.descripcion,
                   u.fecha,
                   usuario.usuario as user               
			FROM  tbudi u	JOIN usuario ON  u.refusuario = usuario.usuario_id
         ".$where."
      	order by ".$colSort." ".$colSortDir."
      	limit ".$start.",".$length;  
	$query->setQuery($sql);
	$res = $query->eject();
	return $res;
}


function traerCatalogoRiesgoElementojax($length, $start, $busqueda,$colSort,$colSortDir, $perfil) {
  $query = new Query();
    $where = '';
    $roles = '';
  $busqueda = str_replace("'","",$busqueda);
  if ($busqueda != '') {
    $where = "where ".$roles." (re.descripcion like '%".$busqueda."%' )  ||  (re.peso like '%".$busqueda."%' )  ";
  } else {
      if ($perfil != '') {
         $where = " where u.usuario_rol_id = ".$perfil;
      }
   }

    $sql = "SELECT re.idriesgoelemento,
                   re.descripcion,
                   re.peso                                
               FROM  tbriesgoelementos re 
         ".$where."
        order by ".$colSort." ".$colSortDir."
        limit ".$start.",".$length;  
  $query->setQuery($sql);
  $res = $query->eject();
  return $res;
}


function traerCatalogoRiesgoIndicadorjax($length, $start, $busqueda,$colSort,$colSortDir, $perfil) {
  $query = new Query();
    $where = '';
    $roles = '';
  $busqueda = str_replace("'","",$busqueda);
  if ($busqueda != '') {
    $where = "where ".$roles." (re.descripcion like '%".$busqueda."%' )  ||  (ri.peso like '%".$busqueda."%' ) || (ri.descripcion like '%".$busqueda."%' )  ||  (ri.maximo like '%".$busqueda."%' ) ||  (ri.minimo like '%".$busqueda."%' )";
  } else {
      if ($perfil != '') {
         $where = " where u.usuario_rol_id = ".$perfil;
      }
   }

    $sql = "SELECT ri.idriesgoindicador,
                   re.descripcion as elemento,
                   ri.descripcion,
                   ri.peso,
                   ri.maximo,
                   ri.minimo,
                   ri.variablesql 
                   FROM  tbriesgoindicadores ri JOIN tbriesgoelementos re ON re.idriesgoelemento = ri.refriesgoelemento
         ".$where."
        order by ".$colSort." ".$colSortDir."
        limit ".$start.",".$length;  
  $query->setQuery($sql);
  $res = $query->eject();
  return $res;
}


function traerCatalogoRiesgoVariablejax($length, $start, $busqueda,$colSort,$colSortDir, $perfil) {
  $query = new Query();
    $where = '';
    $roles = '';
  $busqueda = str_replace("'","",$busqueda);
  if ($busqueda != '') {
    $where = "where ".$roles." (re.descripcion like '%".$busqueda."%' )  ||  (re.peso like '%".$busqueda."%' )  || (ri.descripcion like '%".$busqueda."%' ) || (rv.descripcion like '%".$busqueda."%' ) ";
  } else {
      if ($perfil != '') {
         $where = " where u.usuario_rol_id = ".$perfil;
      }
   }

    $sql = "SELECT rv.idriesgovariable,
                   re.descripcion,
                   ri.descripcion,
                   rv.descripcion,
                   rv.peso,
                   rv.tipovariable,
                   rv.valoresvariable,
                   rv.activo                               
               FROM  tbriesgovariables rv 
               JOIN  tbriesgoindicadores ri ON ri.idriesgoindicador = rv.refriesgoindicador 
               JOIN tbriesgoelementos re ON re.idriesgoelemento =  ri.refriesgoelemento
         ".$where."
        order by ".$colSort." ".$colSortDir."
        limit ".$start.",".$length;  
  $query->setQuery($sql);
  $res = $query->eject();
  return $res;
}

function traerCatalogoRiesgoniveljax($length, $start, $busqueda,$colSort,$colSortDir, $perfil) {
  $query = new Query();
    $where = '';
    $roles = '';
  $busqueda = str_replace("'","",$busqueda);
  if ($busqueda != '') {
    $where = "where ".$roles." (re.descripcion like '%".$busqueda."%' )  ||  (re.peso like '%".$busqueda."%' )  || (ri.descripcion like '%".$busqueda."%' ) || (rv.descripcion like '%".$busqueda."%' ) ";
  } else {
      if ($perfil != '') {
         $where = " where u.usuario_rol_id = ".$perfil;
      }
   }

  $sql = "SELECT rn.idriesgonivel,
                   rn.descripcion,
                  
                   rn.valor,                   
                   rn.activo                               
               FROM  tbriesgoniveles rn
               
         ".$where."
        order by ".$colSort." ".$colSortDir."
        limit ".$start.",".$length;  
  $query->setQuery($sql);
  $res = $query->eject();
  return $res;
}

function traerContratoGlobalesFirmasPendientesjax($length, $start, $busqueda,$colSort,$colSortDir, $perfil) {
      $usuario = new Usuario();
      $usuarioId = $usuario->getUsuarioId();
      $query = new Query();
      $where = '';
      $roles = '';
      $busqueda = str_replace("'","",$busqueda);
      if ($busqueda != '') {
        $where = " WHERE ".$roles." ( CONCAT(dbcg.nombre, ' ' , dbcg.paterno,  ' ',dbcg.materno ) like '%".$busqueda."%' )  ||  (tc.descripcion like '%".$busqueda."%' )   ";
      } else {
          if ($where != '') {
             $where = " AND fc.status = 1";
          }

           $where = " where fc.status = 1 ";
       }

    $sql = "SELECT fc.idfirmacontratoglobal ,
                 CONCAT(dbcg.nombre, ' ' , dbcg.paterno,  ' ',dbcg.materno ) AS nombre_cliente,
                 tc.descripcion as tipocredito, 
                 CONCAT('$',FORMAT(dbcg.montootorgamiento,2)) AS monto,                
                 fc.fecha,                 
                 'PENDIENTE DE FIRMA ' as status_firma ,
                 dbcg.reftipocontratoglobal as tipo_credito, 
                 dbcg.nombre,              
                fc.status,
                fc.hora,
                fc.reftoken,
                fc.refcontratoglobal 

                FROM  dbfirmascontratosglobales fc
                JOIN dbcontratosglobales dbcg ON fc.refcontratoglobal = dbcg.idcontratoglobal
                JOIN tbtipocontratoglobal tc ON dbcg.reftipocontratoglobal = tc.idtipocontratoglobal
                JOIN usuario user ON  dbcg.usuario_id = user.usuario_id AND   user.usuario_id = ".  $usuarioId.
                $where."
        order by ".$colSort." ".$colSortDir."
        limit ".$start.",".$length;  

       
  $query->setQuery($sql);
  $res = $query->eject();
  return $res;
}


function traerContratosActivosjax($length, $start, $busqueda,$colSort,$colSortDir, $perfil) {
      $usuario = new Usuario();
      $usuarioId = $usuario->getUsuarioId();
      $query = new Query();
      $where = '';
      $roles = '';
      $busqueda = str_replace("'","",$busqueda);
      if ($busqueda != '') {
        $where = " WHERE ".$roles." ( CONCAT(dbcg.nombre, ' ' , dbcg.paterno,  ' ',dbcg.materno ) like '%".$busqueda."%' )  ||  (tc.descripcion like '%".$busqueda."%' )   ";
      } else {
          if ($where != '') {
             $where = " AND fc.status = 2";
          }

           $where = " where fc.status = 2 ";
       }

    $sql = "SELECT fc.idfirmacontratoglobal ,
                 CONCAT(dbcg.nombre, ' ' , dbcg.paterno,  ' ',dbcg.materno ) AS nombre_cliente,
                 tc.descripcion as tipocredito, 
                 CONCAT('$',FORMAT(dbcg.montootorgamiento,2)) AS monto,                
                 fc.fecha,                 
                 'CONTRATO ACTIVO ' as status_firma ,
                  dbcg.reftipocontratoglobal as tipo_credito, 
                 dbcg.nombre,              
                fc.status,
                fc.hora,
                fc.reftoken,
                 fc.refcontratoglobal                                         
                FROM  dbfirmascontratosglobales fc
                JOIN dbcontratosglobales dbcg ON fc.refcontratoglobal = dbcg.idcontratoglobal
                JOIN tbtipocontratoglobal tc ON dbcg.reftipocontratoglobal = tc.idtipocontratoglobal
                JOIN usuario user ON  dbcg.usuario_id = user.usuario_id AND   user.usuario_id = ".  $usuarioId.
                $where."
        order by ".$colSort." ".$colSortDir."
        limit ".$start.",".$length;  

       #echo  $sql;
  $query->setQuery($sql);
  $res = $query->eject();
  return $res;
}

function traerClientes(){
   $query = new Query();
   $sql = " SELECT a.ultimoContrato,
              emp.descripcion,
              CONCAT(dbcg.nombre, ' ' , dbcg.paterno,  ' ',dbcg.materno) AS nombre_cliente,
              dbcg.curp,
              dbcg.celular1,
              u.usuario,
              
               (case when  cgINE.max_vigencia_ine = '0000-00-00' then '' else  cgINE.max_vigencia_ine end) as vigenciaINE, 
              (case when cgDOM.max_vigencia_dom = '0000-00-00' then '' else cgDOM.max_vigencia_dom end) as vigenciaBuro,
               (case when cg.burocredito = 1 then 'Si' else 'No' end) as buro, 
              cg.idcontratoglobal as cg_id_buro,
              cg.burocredito, dbcg.idcontratoglobal as dbcg_id,
              CONCAT(dbcg.nombre, ' ' , dbcg.paterno,  ' ',dbcg.materno) AS nombre_cliente,              
              a.usuario_id, 
              a.ultimoContrato as a_id_maxmo_contrato_global,
              cgINE.idcontratoglobal, 
              cgINE.max_vigencia_ine, 
              cgDOM.idcontratoglobal, 
              cg.burocredito,
              cgDOM.max_vigencia_dom,
              CONCAT(dbcg.calle ,' ', dbcg.numeroexterior ,' Colonia:', dbcg.colonia ,' ') as direccion_cliente
              FROM (SELECT max(idcontratoglobal) as ultimoContrato, usuario_id,idcontratoglobal  FROM dbcontratosglobales GROUP BY usuario_id) as a
                LEFT join (select max(idcontratoglobal) as idcontratoglobal, nombre, paterno , materno, usuario_id, burocredito FROM dbcontratosglobales GROUP BY usuario_id HAVING burocredito= 1 )as cg on cg.usuario_id = a.usuario_id
                inner join (select max(vigenciaine) as max_vigencia_ine,  usuario_id, idcontratoglobal FROM dbcontratosglobales GROUP BY usuario_id  )as cgINE on cgINE.usuario_id = a.usuario_id
                inner join (select max(vigenciadomicilio) as max_vigencia_dom,  usuario_id, idcontratoglobal FROM dbcontratosglobales GROUP BY usuario_id  )as cgDOM on cgDOM.usuario_id = a.usuario_id           
                JOIN dbcontratosglobales dbcg ON dbcg.idcontratoglobal= a.ultimoContrato
                JOIN  tbempresaafiliada emp ON  dbcg.refempresaafiliada = emp.idempresaafiliada
                JOIN usuario u ON dbcg.usuario_id = u.usuario_id ".
                $where." ";

          $query->setQuery($sql);
          $res = $query->eject();
          return $res;
}


function traerClientesAjax($length, $start, $busqueda,$colSort,$colSortDir, $perfil) {
      $usuario = new Usuario();
      $usuarioId = $usuario->getUsuarioId();
      $query = new Query();
      $where = '';
      $roles = '';
      $busqueda = str_replace("'","",$busqueda);
      if ($busqueda != '') {
        $where = " WHERE ".$roles." ( CONCAT(dbcg.nombre, ' ' , dbcg.paterno,  ' ',dbcg.materno ) like '%".$busqueda."%' )  ||  (emp.descripcion like '%".$busqueda."%' )  ||  (dbcg.curp like '%".$busqueda."%' )  ||  (dbcg.celular1 like '%".$busqueda."%' ) ";
      } else {
          if ($where != '') {
             #$where = " AND fc.status = 2";
          }

           #$where = " where fc.status = 2 ";
       }

   

     $sql = " SELECT a.ultimoContrato,
              CONCAT('No. cliente ', a.ultimoContrato),    
              emp.descripcion,
              CONCAT(dbcg.nombre, ' ' , dbcg.paterno,  ' ',dbcg.materno) AS nombre_cliente,
              dbcg.curp,
              ( case when  cgINE.max_vigencia_ine = '0000-00-00' or  cgINE.max_vigencia_ine IS NULL  then  ''
            when   DATEDIFF( cgINE.max_vigencia_ine, CURDATE()) >= 1  then CONCAT(cgINE.max_vigencia_ine,'<br><span style=\"color:#2980B9\">vigente</span>')
              when   DATEDIFF( cgINE.max_vigencia_ine, CURDATE()) < 1  then CONCAT(cgINE.max_vigencia_ine,'<br><span style=\"color:#CD6155\">vencida</span></B>')           
            else '_________' end) as ine,


              ( case when  cgDOM.max_vigencia_dom = '0000-00-00' or  cgDOM.max_vigencia_dom IS NULL  then  ''
            when   DATEDIFF( DATE_ADD(cgDOM.max_vigencia_dom, INTERVAL 3 MONTH), CURDATE()) >= 1  then CONCAT(cgDOM.max_vigencia_dom,'<br><span style=\"color:#2980B9\">vigente</span>')
              when   DATEDIFF( DATE_ADD(cgDOM.max_vigencia_dom, INTERVAL 3 MONTH), CURDATE()) < 1  then CONCAT(cgDOM.max_vigencia_dom,'<br> <span style=\"color:#CD6155\">vencido</span></B>')           
            else '_________' end) as comprobante_domicilio,

             

            (case when DATEDIFF(DATE_ADD(st.fecha, INTERVAL 1 MONTH), CURDATE()) >=1  then CONCAT(st.fecha,'<br><span style=\"color:#27AE60\">menor 1 mes</span>') else '' end) as ultimo_otorgamiento_menor_a_un_mes,

            (case when cg.refcirculocredito = 1 and acc.status = 1 and  firmasBuro.firmasT IS NULL  then  '<b> Autorizacion pendiente </b>'
            when (cg.refcirculocredito = 1 and acc.status = 1 and  firmasBuro.firmasT>= 1)  then 'Si/Pendiente'
             when (cg.refcirculocredito = 1 and acc.status = 2)  then 'Si'
           
            else '_________' end
           ) as buro,
          
           ( case when dbcg.refsuceptiblect = 1 AND (DATEDIFF(DATE_ADD(max_vigencia_dom, INTERVAL 3 MONTH), CURDATE()) >= 1) then 1 else 0 end) as otorga_nuevo_credito ,
             
             
             
             ( case when dbcg.refsuceptibleserv = 1 AND (DATEDIFF(CURDATE(), max_vigencia_dom) <= 90) then 1 else  0 end) as otorga_nuevo_servicio ,

 firmasBuro.idContratoFirma,
 (case when DATEDIFF(DATE_ADD(st.fecha, INTERVAL 1 MONTH), CURDATE()) >=1  then 1 else 0 end) as ultimo_otorgamiento_apto,

               (case when  cgINE.max_vigencia_ine = '0000-00-00' then '' else  cgINE.max_vigencia_ine end) as vigenciaINE, 
              (case when cgDOM.max_vigencia_dom = '0000-00-00' then '' else cgDOM.max_vigencia_dom end) as vigenciaBuro,
              CONCAT(DATE_ADD(cgDOM.max_vigencia_dom, INTERVAL 3 MONTH),'MAS TRES MESES') AS vigencia_3_meses,
              st.fecha,
              (case when DATEDIFF(DATE_ADD(st.fecha, INTERVAL 1 MONTH), CURDATE()) >=1  then 1 else 0 end) as ultimo_otorgamiento_menor_a_un_mes,
               (case when cg.refcirculocredito = 1 then 'Si' else 'No' end) as buro, 
              cg.idcontratoglobal as cg_id_buro,
              cg.refcirculocredito, dbcg.idcontratoglobal as dbcg_id,
              CONCAT(dbcg.nombre, ' ' , dbcg.paterno,  ' ',dbcg.materno) AS nombre_cliente,              
              a.usuario_id, 
              a.ultimoContrato as a_id_maxmo_contrato_global,
              cgINE.idcontratoglobal, 
              cgINE.max_vigencia_ine, 
              cgDOM.idcontratoglobal, 
              cg.refcirculocredito,
              cgDOM.max_vigencia_dom,
               dbcg.curp,
              dbcg.celular1,
              u.usuario,
              DATE_ADD(cgDOM.max_vigencia_dom, INTERVAL 3 MONTH) AS vigencia_3_meses,
              dbcg.refsuceptiblect as otrocredito,
              dbcg.refsuceptibleserv as otroservicio,
              DATEDIFF(CURDATE(), max_vigencia_dom) as dias_vigencia_comprobante,             
              DATEDIFF( DATE_ADD(max_vigencia_dom, INTERVAL 3 MONTH), CURDATE()) as vigencia_mas_3_meses_menos_hoy,
              
             
             
              CONCAT(dbcg.calle ,' ', dbcg.numeroexterior ,' Colonia:', dbcg.colonia ,' ') as direccion_cliente,
               tb1.ultimo_aprobado

              FROM (SELECT max(idcontratoglobal) as ultimoContrato, usuario_id,idcontratoglobal  FROM dbcontratosglobales GROUP BY usuario_id) as a
                LEFT join (select max(idcontratoglobal) as idcontratoglobal, nombre, paterno , materno, usuario_id, refcirculocredito FROM dbcontratosglobales GROUP BY usuario_id HAVING   refcirculocredito= 1 )as cg on cg.usuario_id = a.usuario_id
                inner join (select max(vigenciaine) as max_vigencia_ine,  usuario_id, idcontratoglobal FROM dbcontratosglobales GROUP BY usuario_id  )as cgINE on cgINE.usuario_id = a.usuario_id
                inner join (select max(vigenciadomicilio) as max_vigencia_dom,  usuario_id, idcontratoglobal FROM dbcontratosglobales GROUP BY usuario_id  )as cgDOM on cgDOM.usuario_id = a.usuario_id           
                JOIN dbcontratosglobales dbcg ON dbcg.idcontratoglobal= a.ultimoContrato
                JOIN  tbempresaafiliada emp ON  dbcg.refempresaafiliada = emp.idempresaafiliada
                JOIN usuario u ON dbcg.usuario_id = u.usuario_id  
                LEFT JOIN (SELECT max(ax.idcontratoglobal) as ultimo_aprobado, usuario_id, idcontratoglobal FROM dbcontratosglobales ax JOIN dbcontratosglobalesstatus sst on  ax.idcontratoglobal = sst.refcontratoglobal and sst.refstatuscontratoglobal =5 group by usuario_id ) as tb1 ON a.usuario_id = tb1.usuario_id  
                LEFT JOIN dbcontratosglobalesstatus st ON tb1.ultimo_aprobado = st.refcontratoglobal and st.refstatuscontratoglobal=5
                LEFT join (SELECT max(idcontratoglobal) as idcontratoglobal_buro, nombre, paterno , materno, usuario_id, refcirculocredito FROM dbcontratosglobales where `refcirculocredito` =1 GROUP BY usuario_id)as cgb on a.usuario_id = cgb.usuario_id  

                 LEFT JOIN dbsolicitudesautorizacioncirculocredito acc ON cgb.idcontratoglobal_buro = acc.refcontratoglobal

                  LEFT JOIN (SELECT COUNT(*) as firmasT, refusuario,  refcontratoglobal as idContratoFirma FROM dbautorizacionesburo WHERE 1 GROUP BY refusuario) as firmasBuro ON a.usuario_id = firmasBuro.refusuario

                ".
                $where."
        order by ".$colSort." ".$colSortDir."
        limit ".$start.",".$length;      

       
  $query->setQuery($sql);
  $res = $query->eject();
  return $res;
}



function traerClientesCirculoCredito(){
   $query = new Query();
   $sql = " SELECT a.ultimoContrato,
              emp.descripcion,
              CONCAT(dbcg.nombre, ' ' , dbcg.paterno,  ' ',dbcg.materno) AS nombre_cliente,
              dbcg.curp,
              dbcg.celular1,
              u.usuario,
              
               (case when  cgINE.max_vigencia_ine = '0000-00-00' then '' else  cgINE.max_vigencia_ine end) as vigenciaINE, 
              (case when cgDOM.max_vigencia_dom = '0000-00-00' then '' else cgDOM.max_vigencia_dom end) as vigenciaBuro,
               (case when cg.burocredito = 1 then 'Si' else 'No' end) as buro, 
              cg.idcontratoglobal as cg_id_buro,
              cg.burocredito, dbcg.idcontratoglobal as dbcg_id,
              CONCAT(dbcg.nombre, ' ' , dbcg.paterno,  ' ',dbcg.materno) AS nombre_cliente,              
              a.usuario_id, 
              a.ultimoContrato as a_id_maxmo_contrato_global,
              cgINE.idcontratoglobal, 
              cgINE.max_vigencia_ine, 
              cgDOM.idcontratoglobal, 
              cg.burocredito,
              cgDOM.max_vigencia_dom,
              CONCAT(dbcg.calle ,' ', dbcg.numeroexterior ,' Colonia:', dbcg.colonia ,' ') as direccion_cliente
              FROM (SELECT max(idcontratoglobal) as ultimoContrato, usuario_id,idcontratoglobal  FROM dbcontratosglobales GROUP BY usuario_id) as a
                LEFT join (select max(idcontratoglobal) as idcontratoglobal, nombre, paterno , materno, usuario_id, burocredito FROM dbcontratosglobales GROUP BY usuario_id HAVING burocredito= 1 )as cg on cg.usuario_id = a.usuario_id
                inner join (select max(vigenciaine) as max_vigencia_ine,  usuario_id, idcontratoglobal FROM dbcontratosglobales GROUP BY usuario_id  )as cgINE on cgINE.usuario_id = a.usuario_id
                inner join (select max(vigenciadomicilio) as max_vigencia_dom,  usuario_id, idcontratoglobal FROM dbcontratosglobales GROUP BY usuario_id  )as cgDOM on cgDOM.usuario_id = a.usuario_id           
                JOIN dbcontratosglobales dbcg ON dbcg.idcontratoglobal= a.ultimoContrato
                JOIN  tbempresaafiliada emp ON  dbcg.refempresaafiliada = emp.idempresaafiliada
                JOIN usuario u ON dbcg.usuario_id = u.usuario_id ".
                $where." ";

          $query->setQuery($sql);
          $res = $query->eject();
          return $res;
}

function traerClientesCirculoCreditoAjax($length, $start, $busqueda,$colSort,$colSortDir, $perfil) {
      $usuario = new Usuario();
      $usuarioId = $usuario->getUsuarioId();
      $query = new Query();
      $where = '';
      $roles = '';
      $busqueda = str_replace("'","",$busqueda);
      if ($busqueda != '') {
        if($busqueda =='CIRCULO' || $busqueda =='CÍRCULO' || $busqueda =='circulo' || $busqueda =='círculo') {
           $where = " WHERE  cg.refcirculocredito = 1";

        }else{
        $where = " WHERE ".$roles." ( CONCAT(dbcg.nombre, ' ' , dbcg.paterno,  ' ',dbcg.materno ) like '%".$busqueda."%' )  ||  (emp.descripcion like '%".$busqueda."%' )  ||  (dbcg.curp like '%".$busqueda."%' )  ||  (dbcg.celular1 like '%".$busqueda."%' ) ";
      }
      } else {
          if ($where != '') {
             #$where = " AND fc.status = 2";
          }

           #$where = " where fc.status = 2 ";
       }

    

     $sql = " SELECT a.ultimoContrato,
              emp.descripcion,
              CONCAT(dbcg.nombre, ' ' , dbcg.paterno,  ' ',dbcg.materno) AS nombre_cliente,
              dbcg.curp,
              dbcg.celular1,
              u.usuario,
              acc.fecha,
              
               
              
 (case when cg.refcirculocredito = 1 and acc.status = 1 and  firmasBuro.firmasT IS NULL  then  '<b> Autorizacion pendiente </b>'
            when (cg.refcirculocredito = 1 and acc.status = 1 and  firmasBuro.firmasT>= 1)  then 'Si/Pendiente'
             when (cg.refcirculocredito = 1 and acc.status = 2)  then 'Si'
           
            else '_________' end
           ) as buro,
           firmasBuro.idContratoFirma,



              cg.idcontratoglobal_buro as cg_id_buro,
              cg.refcirculocredito, dbcg.idcontratoglobal as dbcg_id,
              CONCAT(dbcg.nombre, ' ' , dbcg.paterno,  ' ',dbcg.materno) AS nombre_cliente,              
              a.usuario_id, 
              a.ultimoContrato as a_id_maxmo_contrato_global,
              cgINE.idcontratoglobal, 
              cgINE.max_vigencia_ine, 
              cgDOM.idcontratoglobal, 
              cg.refcirculocredito,
              cgDOM.max_vigencia_dom,
              CONCAT(dbcg.calle ,' ', dbcg.numeroexterior ,' Colonia:', dbcg.colonia ,' ') as direccion_cliente
              FROM (SELECT max(idcontratoglobal) as ultimoContrato, usuario_id,idcontratoglobal  FROM dbcontratosglobales GROUP BY usuario_id) as a
                LEFT join (SELECT max(idcontratoglobal) as idcontratoglobal_buro, nombre, paterno , materno, usuario_id, refcirculocredito FROM dbcontratosglobales where `refcirculocredito` =1 GROUP BY usuario_id)as cg on a.usuario_id = cg.usuario_id  
                inner join (select max(vigenciaine) as max_vigencia_ine,  usuario_id, idcontratoglobal FROM dbcontratosglobales GROUP BY usuario_id  )as cgINE on cgINE.usuario_id = a.usuario_id
                inner join (select max(vigenciadomicilio) as max_vigencia_dom,  usuario_id, idcontratoglobal FROM dbcontratosglobales GROUP BY usuario_id  )as cgDOM on cgDOM.usuario_id = a.usuario_id           
                JOIN dbcontratosglobales dbcg ON dbcg.idcontratoglobal= a.ultimoContrato
                JOIN  tbempresaafiliada emp ON  dbcg.refempresaafiliada = emp.idempresaafiliada
                JOIN usuario u ON dbcg.usuario_id = u.usuario_id 
                LEFT JOIN dbsolicitudesautorizacioncirculocredito acc ON cg.idcontratoglobal_buro = acc.refcontratoglobal

                  LEFT JOIN (SELECT COUNT(*) as firmasT, refusuario,  refcontratoglobal as idContratoFirma FROM dbautorizacionesburo WHERE 1 GROUP BY refusuario) as firmasBuro ON a.usuario_id = firmasBuro.refusuario
                
                ".
                $where."
        order by ".$colSort." ".$colSortDir."
        limit ".$start.",".$length;      

      # echo $sql;
  $query->setQuery($sql);
  $res = $query->eject();
  return $res;
}









function modificarRechazos($id,$descripcion) {
   $query = new Query(); 
   $sql = "UPDATE  tbrechazocausa
   set
   descripcion = '".$descripcion."'
   where idrechazocausa =".$id;
   $query->setQuery($sql);
   $res = $query->eject();
   
   return $res;
}

   
function insertarRechazos($descripcion) {
    $sql = "INSERT INTO tbrechazocausa(idrechazocausa,descripcion)
    values ('','".$descripcion."')";
    $res = $this->query($sql,1);
    return $res;
}

function modificarAsesor($id,$nombre) {
   $query = new Query(); 
   $sql = "UPDATE  tbasesores
   set
   nombre = '".$nombre."'
   where idasesor =".$id;
   $query->setQuery($sql);
   $res = $query->eject();
   
   return $res;
}

function insertarAsesor($nombre) {
    $sql = "INSERT INTO tbasesores(idasesor,nombre)
    values ('','".$nombre."')";
    $res = $this->query($sql,1);
    return $res;
  }

 function modificarUDI($id,$valor) {
   $query = new Query(); 
   // verificamos antes de modificar el valor de algun UDI que este registro no exista como referencia en algun contrato glogal, si se uso en algun contrato global este registro no podra ser modificado
   $sqlUdiContrato = "SELECT * FROM dbcontratosglobales WHERE refudi = ".$id;
   $query->setQuery($sqlUdiContrato);
   $resUdi = $query->eject();
   if($query->numRows($resUdi) >0){
   		return "Esta UDI se utilizó en el calculo del monto para algún contrato global, por lo tanto no se puede modificar la información";
   }else{
   		$sql = "UPDATE  tbudi
   		set
   			descripcion = '".$valor."'
   			WHERE idudi =".$id;
   		$query->setQuery($sql);
   		$res = $query->eject();   
   		return $res;
   	}   
}

function insertarUDI($valor) {
	$query = new Query();
	$fecha = date("Y-m-d");
	$usuario = new Usuario();
	$idUsuario = $usuario->getUsuarioId();
	// verificar que no exista registro para el día de hoy
	$sql = "SELECT * FROM tbudi WHERE fecha ='".$fecha."'";
	$query->setQuery($sql);
	$res = $query->eject();
	if($query->numRows($res) >0){
		return "El valor para  la UDI del día de hoy ya fue registrado, no puede existir más de un registro por día";
	}else{
		$sql = "INSERT INTO tbudi(idudi, descripcion, fecha, refusuario)
    values ('','".$valor."', '".$fecha."', '".$idUsuario."')";
    	$res = $this->query($sql,1);
        return $res;
	}    
  } 


function insertarRiesgoElemento($descripcion, $peso) {
    $sql = "INSERT INTO tbriesgoelementos(idriesgoelemento,descripcion,peso)
    values ('','".$descripcion."', '".$peso."')";
    $res = $this->query($sql,1);
    return $res;
  }

 function modificarRiesgoElemento($id,$descripcion, $peso) {
   $query = new Query();   
      $sql = "UPDATE  tbriesgoelementos
      set
        descripcion = '".$descripcion."',
        peso = ".$peso."
        WHERE idriesgoelemento =".$id;
      $query->setQuery($sql);
      $res = $query->eject();   
      return $res;  
}  

function insertarRiesgoIndicador($descripcion, $peso, $maximo, $minimo, $refElemento,  $variablesql) {
    $query = new Query(); 
    $tablasql = 'dbcontratosglobales';
    $sql = "INSERT INTO tbriesgoindicadores(idriesgoindicador, refriesgoelemento, descripcion,peso, maximo, minimo,   tablasql,   variablesql )
    values ('','".$refElemento."','".$descripcion."', '".$peso."', '".$maximo."', '".$minimo."', '".$tablasql."', '".$variablesql."')";
    $query->setQuery($sql);
    $res = $query->eject();
    return $res;
  }
function modificarRiesgoIndicador($id,$descripcion, $peso,  $maximo, $minimo, $refElemento, $variablesql) {
   $query = new Query();   
      $sql = "UPDATE  tbriesgoindicadores
      set
        descripcion = '".$descripcion."',
        peso = ".$peso.",
        maximo = ".$maximo.",
        minimo = ".$minimo.",
        variablesql = ".$variablesql.",
        refriesgoelemento = ".$refElemento."
                WHERE idriesgoindicador =".$id;
      $query->setQuery($sql);
      $res = $query->eject();   
      return $res;  
}    

function insertarRiesgoVariable($descripcion, $peso,  $refIndicador, $tipoVariable, $valoresvariable,$activo) {
    $sql = "INSERT INTO tbriesgovariables(idriesgovariable,refriesgoindicador,descripcion,peso,tipovariable, valoresvariable,activo)
    values ('','".$refIndicador."','".$descripcion."', '".$peso."', '".$tipoVariable."', '".$valoresvariable."', '".$activo."')";  
    $res = $this->query($sql,1);
    return $res;
  } 

function modificarRiesgoVariable($id,$descripcion, $peso, $tipovariable, $valoresvariable, $activo) {
   $query = new Query();   
      $sql = "UPDATE  tbriesgovariables
      set
        descripcion = '".$descripcion."',
        peso = '".$peso."',
        tipovariable = '".$tipovariable."',
        valoresvariable = '".$valoresvariable."',
        activo = '".$activo."'
        WHERE idriesgovariable =".$id;
        
      $query->setQuery($sql);
      $res = $query->eject();   
      return $res;  
}

function insertarRiesgoNivel($descripcion, $valor, $activo) {
    $sql = "INSERT INTO tbriesgoniveles(idriesgonivel,descripcion,valor,activo)
    values ('','".$descripcion."', '".$valor."', '".$activo."')";
    $res = $this->query($sql,1);
    return $res;
  }

 function modificarRiesgoNivel($id,$descripcion, $valor, $activo) {
   $query = new Query();   
      $sql = "UPDATE  tbriesgoniveles
      set
        descripcion = '".$descripcion."',
        valor = '".$valor."',
        activo = '".$activo."'
        WHERE idriesgonivel =".$id;
      $query->setQuery($sql);
      $res = $query->eject();   
      return $res;  
}  


function traerCausaRechazoPorId($idRechazo) {
      $query = new Query();
      $sql = "SELECT  idrechazocausa,
      descripcion          
      FROM  tbrechazocausa WHERE  idrechazocausa =".$idRechazo;
      $query->setQuery($sql);
      $res = $query->eject();    
      return $res;
   }
function traerAsesorPorId($idRechazo) {
      $query = new Query();
      $sql = "SELECT  idrechazocausa,
      descripcion          
      FROM  tbrechazocausa WHERE  idrechazocausa =".$idRechazo;
      $query->setQuery($sql);
      $res = $query->eject();    
      return $res;
   }   
function traerUDIPorId($idRechazo) {
      $query = new Query();
      $sql = "SELECT  idrechazocausa,
      descripcion          
      FROM  tbrechazocausa WHERE  idrechazocausa =".$idRechazo;
      $query->setQuery($sql);
      $res = $query->eject();    
      return $res;
   }



/* Fin */
/* /* Fin de la Tabla: dbcorreoselectronicos*/


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
