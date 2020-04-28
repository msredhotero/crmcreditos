<?php

/**
 * @Usuarios clase en donde se accede a la base de datos
 * @ABM consultas sobre las tablas de usuarios y usarios-clientes
 */

date_default_timezone_set('America/Mexico_City');

class ServiciosUsuarios {
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

function traerPostulantesPorId($id) {
	$query = new Query();
   	$sql = "select idpostulante,refusuarios,nombre,apellidopaterno,apellidomaterno,email,curp,rfc,ine,fechanacimiento,sexo,codigopostal,refescolaridades,telefonomovil,telefonocasa,telefonotrabajo,refestadopostulantes,urlprueba,fechacrea,fechamodi,usuariocrea,usuariomodi,refasesores,comision,refsucursalesinbursa, refestadocivil from dbpostulantes where idpostulante =".$id;
   	$query->setQuery($sql);
  	$res = $query->eject();

   return $res;
}

function traerEntrevistasActivasPorPostulanteEstadoPostulante($id,$idestadopostulante) {
	$query = new Query();
   	$sql = "select e.identrevista,
   	e.refpostulantes,
    e.entrevistador,
    e.fecha,
    e.domicilio,
    coalesce( pp.codigo, e.codigopostal) as codigopostal,
    e.refestadopostulantes,
    e.refestadoentrevistas,
    e.fechacrea,
    e.fechamodi,e.usuariocrea,e.usuariomodi,
    concat(pp.estado, ' ', pp.municipio, ' ', pp.colonia, ' ', pp.codigo) as postalcompleto,
    est.estadoentrevista
    from dbentrevistas e
    left join tbentrevistasucursales et on et.identrevistasucursal = e.refentrevistasucursales
    left join postal pp on pp.id = et.refpostal
    inner join tbestadoentrevistas est on est.idestadoentrevista = e.refestadoentrevistas
    where e.refestadopostulantes = ".$idestadopostulante." and e.refestadoentrevistas in (1,2,3) and e.refpostulantes =".$id;
    $query->setQuery($sql);
    $res = $query->eject();

    return $res;
}

function enviarCorreosEtapas( $etapa, $id) {
	$query = new Query();
    $asunto = '';
    $cuerpo = '';

    $cuerpo .= '<img src="http://www.asesorescrea.com/desarrollo/crm/imagenes/logo.png" alt="asesorescrea" width="190">';

    $cuerpo .= '<h2>¡Asesores CREA!</h2>';
    $destinatario = 'rlinares@asesorescrea.com';
    $resPostulante = $this->traerPostulantesPorId($id);
    $Postulanteinfo =  $resPostulante->fetch_field();
    $asesor = $Postulanteinfo->nombre.' '.$Postulanteinfo->apellidopaterno.' '.$Postulanteinfo->apellidomaterno;

    $emailAsesor = $Postulanteinfo->email;
    $urlprueba = $Postulanteinfo->urlprueba;

    switch ($etapa) {
    	case 2:
    		$asunto = 'Entrevista Examen VERITAS';
            $resEntrevista = $this->traerEntrevistasActivasPorPostulanteEstadoPostulante($id,$etapa);
            $Entrevistainfo =  $resEntrevista->fetch_field();
         	$cuerpo .= '<p>Tiene un Entrevista programada para la fecha: '.$Entrevistainfo->fecha.' con el entrevistador: '.$Entrevistainfo->entrevistador.' en la direccion: '.$Entrevistainfo->domicilio.' , '.$Entrevistainfo->postalcompleto;

        break;
      	case 4:
         	$asunto = 'Entrevista Regional I';
         	$resEntrevista = $this->traerEntrevistasActivasPorPostulanteEstadoPostulante($id,$etapa);
         	$Entrevistainfo =  $resEntrevista->fetch_field();
         	$cuerpo .= '<p>Felicitaciones!!, aprobo el examen VERITAS, Tiene un Entrevista programada para la fecha: '.$Entrevistainfo->fecha.' con el entrevistador: '.$Entrevistainfo->entrevistador.' en la direccion: '.$Entrevistainfo->domicilio.' , '.$Entrevistainfo->postalcompleto;

        break;
      	case 5:
         	$asunto = 'URL Prueba Psicometrica';
         	$cuerpo .= 'Felicitaciones!!, continua en el proceso de Reclutamiento. Le enviamos la url para realizar el examen Psicometrico: <a href="'.$urlprueba.'">Examen Psicometrico</a>';
        break;

   }

   $resEmail = $this->enviarEmail($destinatario,$asunto,$cuerpo);

   return $resEmail;
}


function login($usuario,$pass) {
	$query = new Query();
	#$sqlusu = "select * from dbusuarios where email = '".$usuario."'";
	$sqlusu = "select * from usuario where usuario = '".$usuario."'";
	$error = '';

	if (trim($usuario) != '' and trim($pass) != '') {
		$query->setQuery($sqlusu);
		$respusu = $query->eject();
		#$Usurow =  $respusu->fetch_row();
		$Usurow =  $query->fetchRow($respusu);
		if ($query->numRows($respusu) > 0) {
			$idUsua = $Usurow[0];
			$sqlpass = "SELECT
	                   u.nombre,
	                   u.email,
	                   u.usuario,
	                   r.descripcion,
	                   r.usuario_rol_id
	               FROM
	                   usuario u
	                       INNER JOIN
	                   usuario_rol r ON r.usuario_rol_id = u.usuario_rol_id
	               WHERE
	                   clave = '".$pass."' AND u.usuario_status_id = 1
	                       AND usuario_id = ".$idUsua;
			
			$query->setQuery($sqlpass);
			$resppass = $query->eject();
			$passRow = $query->fetchRow($resppass);
			if ($query->numRows($resppass) > 0) {
				$error = '';
				} else {
					$error = 'Usuario o Password incorrecto ' ;
				}
			}
		else
		{
			$error = 'Usuario o Password incorrecto ';
		}

		if ($error == '') {	
			if(session_status() !== PHP_SESSION_ACTIVE) session_start();	
			$_SESSION['usua_sahilices'] = $usuario;
			$_SESSION['nombre_sahilices'] = $passRow[0];
			$_SESSION['usuaid_sahilices'] = $idUsua;
			$_SESSION['email_sahilices'] = $passRow[1];
			$_SESSION['idroll_sahilices'] = $passRow[4];
			$_SESSION['refroll_sahilices'] = $passRow[3];
			return 1;
		}

	}	else {
		$error = 'Usuario y Password son campos obligatorios';
	}
	return $error;

}





function loginUsuario($usuario,$pass) {

	$sqlusu = "select * from dbusuarios where email = '".$usuario."' activo = 1";
	if (trim($usuario) != '' and trim($pass) != '') {
		$respusu = $this->query($sqlusu,0);
			if (mysql_num_rows($respusu) > 0) {
				$error = '';
				$idUsua = mysql_result($respusu,0,0);
				$sqlpass = "select concat(apellido,' ',nombre),email,refroles, refclientes from dbusuarios where password = '".$pass."' and IdUsuario = ".$idUsua;

				$resppass = $this->query($sqlpass,0);
				if (mysql_num_rows($resppass) > 0) {
						$error = '';

				} else {
					if (mysql_result($respusu,0,'activo') == 0) {
						$error = 'El usuario no fue activado, verifique su cuenta de email: '.$usuario;
					} else {
						$error = 'Usuario o Password incorrecto';
					}

				}

			}else{
					$error = 'Usuario o Password incorrecto';
			}

			if ($error == '') {
					session_start();
					$_SESSION['usua_sahilices'] = $usuario;
					$_SESSION['nombre_sahilices'] = mysql_result($resppass,0,0);
					$_SESSION['email_sahilices'] = mysql_result($resppass,0,1);
					$_SESSION['refroll_sahilices'] = mysql_result($resppass,0,2);
		         $_SESSION['idcliente'] = mysql_result($resppass,0,3);
			}


		}else {
				$error = 'Usuario y Password son campos obligatorios';
		}

	return $error;
}


function traerRoles() {
	$query = new Query();
	$sql = "select * from usuario_rol";
	$query->setQuery($sql);
	$res = $query->eject(0);
	if ($res == false) {
		return 'Error al traer datos';
	} else {
		return $res;
	}
}

function traerRolesSimple() {
	$query = new Query();
	$sql = "select * from usuario_rol where usuario_rol_id > 2";	
	$query->setQuery($sql);
	$res = $query->eject(0);
	if ($res == false) {
		return 'Error al traer datos';
	} else {
		return $res;
	}
}


function traerUsuario($email) {
	$query = new Query();
	$sql = "select idusuario,usuario,nombrecompleto,email,password from usuario where email = '".$email."'";
	$query->setQuery($sql);
	$res = $query->eject(0);
	return $res;
}



function traerContratos() {
	$query = new Query();
	$sql = "select c.idcontratoglobal, c.nombre, c.paterno, c.materno, e.descripcion, c.fecha_registro, s.descripcion
			from dbcontratosglobales c
			inner join tbempresaafiliada e on c.refempresaafiliada = e.idempresaafiliada
			inner join tbstatuscontratoglobal s on c.refstatuscontratoglobal = s.idstatuscontratoglobal
			order by fecha_registro";

	$sql = "SELECT r.idcontratoglobalstatus,  r.refstatuscontratoglobal, r.idcontratoglobal, r.ultimo_status_contrato,r.empresa, r.tipo_credito, r.fecha_registro,  r.fecha_status,r.rechazo, r.nombre, r.paterno, r.materno, r.curp, r.refusuario FROM (     
    
SELECT   cg.idcontratoglobal,cg.fecha_registro, cg.refempresaafiliada, cg.reftipocontratoglobal, cg.nombre, cg.paterno, cg.materno, cg.curp, cstatus.idcontratoglobalstatus,  cstatus.refcontratoglobal, cstatus.refstatuscontratoglobal  , cstatus.refrechazocausa, cstatus.refusuario , cstatus.fecha , afiliada.descripcion as empresa , tipoContrato.descripcion as tipo_credito, tbstatus.descripcion as ultimo_status_contrato,  cstatus.fecha as fecha_status, (SELECT descripcion FROM tbrechazocausa WHERE idrechazocausa =cstatus.refrechazocausa ) as rechazo 
    FROM `dbcontratosglobales` cg 
     INNER join tbempresaafiliada afiliada ON cg.refempresaafiliada = afiliada.idempresaafiliada
    INNER join tbtipocontratoglobal tipoContrato on cg.reftipocontratoglobal = tipoContrato.idtipocontratoglobal    
    INNER JOIN dbcontratosglobalesstatus cstatus on cg.idcontratoglobal = cstatus.refcontratoglobal 
    INNER JOIN tbstatuscontratoglobal tbstatus ON cstatus.refstatuscontratoglobal = tbstatus.idstatuscontratoglobal
    
    where cstatus.refstatuscontratoglobal = (SELECT max(refstatuscontratoglobal) FROM dbcontratosglobalesstatus where idcontratoglobal =  cg.idcontratoglobal)
    

)as r";



#echo $sql;
	$query->setQuery($sql);	
	$res = $query->eject(0);
	return $res;
}

function traerUsuarios() {
	$query = new Query();
	$sql = "select u.usuario_id,u.usuario, u.clave, r.descripcion, u.email , u.nombre, u.usuario_rol_id
			from usuario u
			inner join usuario_rol r on u.usuario_rol_id = r.usuario_rol_id
			order by nombre";
	$query->setQuery($sql);	
	$res = $query->eject(0);
	return $res;
}

function traerUsuariosajax($length, $start, $busqueda,$colSort,$colSortDir, $perfil) {
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
		$where = "where ".$roles." (u.usuario like '%".$busqueda."%' or r.descripcion like '%".$busqueda."%' or u.email like '%".$busqueda."%' or u.nombre like '%".$busqueda."%')";
	} else {
      if ($perfil != '') {
         $where = " where u.usuario_rol_id = ".$perfil;
      }
   }


	/*$sql = "select u.idusuario,
                  u.usuario,
                  r.descripcion,
                  u.email ,
                  u.nombrecompleto,
                  (case when u.activo = 1 then 'Si' else 'No' end) as activo,
                  u.refroles
			from dbusuarios u
			inner join tbroles r on u.refroles = r.idrol
         ".$where."
      	order by ".$colSort." ".$colSortDir."
      	limit ".$start.",".$length;*/

    $sql = "select u.usuario_id,
                  u.usuario,
                  r.descripcion,
                  u.email ,
                  u.nombre,
                  (case when u.usuario_status_id = 1 then 'Si' else 'No' end) as activo,
                  u.usuario_rol_id
			from usuario u
			inner join usuario_rol r on u.usuario_rol_id = r.usuario_rol_id
         ".$where."
      	order by ".$colSort." ".$colSortDir."
      	limit ".$start.",".$length;  
  #  echo $sql;
	
	$query->setQuery($sql);
	$res = $query->eject();
	

	return $res;
}

function traerContratosajax($length, $start, $busqueda,$colSort,$colSortDir, $perfil) {
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
		$where = "where ".$roles." (u.usuario like '%".$busqueda."%' or r.descripcion like '%".$busqueda."%' or u.email like '%".$busqueda."%' or u.nombre like '%".$busqueda."%')";
	} else {
      if ($perfil != '') {
         $where = " where u.usuario_rol_id = ".$perfil;
      }
   }



    $sql= "SELECT  dc.idcontratoglobal,
    dc.empresa,
    dc.tipo_credito,
    dc.fecha_registro,
    concat( dc.nombre,' ' ,dc.paterno,' ', dc.materno ) as nombre_cliente,
    dc.curp,
    dc.ultimo_status_contrato,
    dc.rechazo,
    dc.fecha_ultimo_status,
    dc.idcontratoglobalstatus,
    dc.refstatuscontratoglobal,
    dc.idcontratoglobal,
    dc.ultimo_status_contrato,
    dc.fecha_registro,
    dc.fecha_status,
    dc.rechazo,
    dc.nombre,
    dc.paterno,
    dc.materno,
    dc.refusuario 
    FROM (    
		SELECT cg.idcontratoglobal,
		cg.fecha_registro, 
		cg.refempresaafiliada, 
		cg.reftipocontratoglobal, 
		cg.nombre, 
		cg.paterno, 
		cg.materno, 
		cg.curp, 
		cstatus.idcontratoglobalstatus,  
		cstatus.refcontratoglobal, 
		cstatus.refstatuscontratoglobal  , 
		cstatus.refrechazocausa, 
		cstatus.refusuario , 
		cstatus.fecha as fecha_ultimo_status , 
		afiliada.descripcion as empresa , 
		tipoContrato.descripcion as tipo_credito, 
		tbstatus.descripcion as ultimo_status_contrato,  
		cstatus.fecha as fecha_status, 
		(SELECT descripcion FROM tbrechazocausa WHERE idrechazocausa =cstatus.refrechazocausa ) as rechazo 
    	FROM `dbcontratosglobales` cg 
     	INNER join tbempresaafiliada afiliada ON cg.refempresaafiliada = afiliada.idempresaafiliada
    	INNER join tbtipocontratoglobal tipoContrato on cg.reftipocontratoglobal = tipoContrato.idtipocontratoglobal    
    	INNER JOIN dbcontratosglobalesstatus cstatus on cg.idcontratoglobal = cstatus.refcontratoglobal 
    	INNER JOIN tbstatuscontratoglobal tbstatus ON cstatus.refstatuscontratoglobal = tbstatus.idstatuscontratoglobal
    
    where cstatus.refstatuscontratoglobal = (SELECT max(refstatuscontratoglobal) FROM dbcontratosglobalesstatus where idcontratoglobal =  cg.idcontratoglobal)
    )
    as dc
			
         ".$where."
      	order by ".$colSort." ".$colSortDir."
      	limit ".$start.",".$length;  
  #  echo $sql;
	
	$query->setQuery($sql);
	$res = $query->eject();
	

	return $res;
}


function traerUsuariosSimple() {
	$query = new Query();
	$sql = "select u.idusuario,u.usuario, u.password, r.descripcion, u.email , u.nombrecompleto, u.refroles
			from dbusuarios u
			inner join tbroles r on u.refroles = r.idrol
			where r.idrol <> 1
			order by nombrecompleto";
	
	$query->setQuery($sql);
	$res = $query->eject();

	if ($res == false) {
		return 'Error al traer datos';
	} else {
		return $res;
	}
}

function traerUsuariosPorRol($idrol) {
	$query = new Query();
	$sql = "select u.usuario_id,u.usuario, u.email , u.nombre
			from usuario u
			inner join usuario_rol r on u.usuario_rol_id = r.usuario_rol_id
			where r.usuario_rol_id = ".$idrol."
			order by nombre";
	$query->setQuery($sql);
	$res = $query->eject();
	if ($res == false) {
		return 'Error al traer datos';
	} else {
		return $res;
	}
}

function traerUsuariosPorRolIn($idrol) {
	$query = new Query();
	$sql = "select u.idusuario,u.usuario, u.email , u.nombrecompleto, r.descripcion
			from dbusuarios u
			inner join tbroles r on u.refroles = r.idrol
			where r.idrol in (".$idrol.")
			order by nombrecompleto";
	$query->setQuery($sql);
	$res = $query->eject();
	if ($res == false) {
		return 'Error al traer datos';
	} else {
		return $res;
	}
}

function traerTodosUsuarios() {
	$query = new Query();
	$sql = "select u.idusuario,u.usuario,u.nombrecompleto,u.refroll,u.email,u.password
			from se_usuarios u
			order by nombrecompleto";
	$query->setQuery($sql);
	$res = $query->eject();
	if ($res == false) {
		return 'Error al traer datos';
	} else {
		return $res;
	}
}

function traerUsuarioId($id) {
	$query = new Query();
	$sql = "select
            usuario_id,usuario,usuario_rol_id,
            nombre,email,clave,
            (case when usuario_status_id = 1 then 'Si' else 'No' end) as activo
         from usuario where usuario_id = ".$id;
	$query->setQuery($sql);
	$res = $query->eject();
	if ($res == false) {
		return 'Error al traer datos';
	} else {
		return $res;
	}
}

function existeUsuario($usuario, $id = 0) {
	$query = new Query();
    if ($id == 0) {
    	$sql = "select * from usuario where usuario = '".$usuario."'";
    } else {
        $sql = "select * from usuario where usuario = '".$usuario."' and usuario_id <> ".$id;
    }

	$query->setQuery($sql);
	$res = $query->eject();
	
	if ($res->num_rows>0) {
		return true;
	} else {
		return false;
	}
}

function enviarEmail($destinatario,$asunto,$cuerpo, $referencia='') {


	# Defina el número de e-mails que desea enviar por periodo. Si es 0, el proceso por lotes
	# se deshabilita y los mensajes son enviados tan rápido como sea posible.
   if ($referencia == '') {
      $referencia = 'consulta@financieracrea.com';
   }
   # Defina el número de e-mails que desea enviar por periodo. Si es 0, el proceso por lotes
   # se deshabilita y los mensajes son enviados tan rápido como sea posible.
   define("MAILQUEUE_BATCH_SIZE",0);

   //para el envío en formato HTML
   //$headers = "MIME-Version: 1.0\r\n";

   // Cabecera que especifica que es un HMTL
   $headers  = 'MIME-Version: 1.0' . "\r\n";
   $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

   //dirección del remitente
   $headers .= utf8_decode("From: FINANCIERA CREA <consulta@financieracrea.com>\r\n");

	mail($destinatario,$asunto,$cuerpo,$headers);
}




function insertarUsuario($usuario,$password,$rol,$email,$nombrecompleto) {
	$query = new Query();
	$tokenUser = $this->GUID();
	$cuerpo = '';

	$fecha = date_create(date('Y').'-'.date('m').'-'.date('d'));
	date_add($fecha, date_interval_create_from_date_string('30 days'));
	$fechaprogramada =  date_format($fecha, 'Y-m-d');

    $cuerpo .= '<img src="http://financieracrea.com/esfdesarrollo/images/logo.gif" alt="Financiera CREA" >';

    $cuerpo .= '<h2 class=\"p3\"> ¡Bienvenido a Financiera CREA!</h2>';

    $servidor = $_SERVER['SERVER_NAME'];
    $liga_servidor = ($servidor=='localhost')?SERVIDOR_LOCAL:SERVIDOR;
   	$cuerpo .= '<h3><small><p>Por favor ingresa al siguiente <a href="'.$liga_servidor.'activacionUsuario.php?token='.$tokenUser.'" target="_blank">enlace</a> para activar tu cuenta.</p></small></h3>';
   	
	$fecharegistro = date("Y-m-d");
	$sql = "INSERT INTO usuario
				(usuario_id,
				usuario_rol_id,				
				usuario,
				clave,
				nombre,
				fecha_registro,
				email
				)
			VALUES
				(null,				
				".$rol.",				
				'".($usuario)."',
				'".($password)."',
				'".($nombrecompleto)."',
				'".($fecharegistro)."',				
				'".($email)."')";
	if ($this->existeUsuario($email) == true) {
		return " \n Este usuario ya existe ";
	}

	$query->setQuery($sql);
	$res = $query->eject(1);	
	if ($res == false) {
		return 'Error al insertar datos';
	} else {
		$this->insertarActivacionusuarios($res,$tokenUser,'','');
		$retorno = $this->enviarEmail($email,'Alta de Usuario',utf8_decode($cuerpo));
		return $res;	
	}
}


function modificarUsuario($id,$usuario,$password,$refroles,$email,$nombrecompleto,$activo) {
	$sql = "UPDATE dbusuarios
			SET
				usuario = '".($usuario)."',
				password = '".($password)."',
				email = '".($email)."',
				refroles = ".$refroles.",
				nombrecompleto = '".($nombrecompleto)."',
            activo = ".$activo."
			WHERE idusuario = ".$id;
	$res = $this->query($sql,0);
	if ($res == false) {
		return 'Error al modificar datos';
	} else {
		return '';
	}
}

function reenviarActivacion($idusuario,$email) {
	$token = $this->GUID();
	$cuerpo = '';

	$fecha = date_create(date('Y').'-'.date('m').'-'.date('d'));
	date_add($fecha, date_interval_create_from_date_string('5 days'));
	$fechaprogramada =  date_format($fecha, 'Y-m-d');

    $cuerpo .= '<img src="http://financieracrea.com/esfdesarrollo/images/logo.gif" alt="Financiera CREA" >';
    $cuerpo .= '<h2 class=\"p3\"> ¡Bienvenido a Financiera CREA!</h2>';

    $servidor = $_SERVER['SERVER_NAME'];
    $liga_servidor = ($servidor=='localhost')?SERVIDOR_LOCAL:SERVIDOR;
   	$cuerpo .= '<h3><small><p>Por favor ingrese al siguiente <a href="'.$liga_servidor.'activacionUsuario.php?token='.$tokenUser.'" target="_blank"> enlace </a> para activar su cuenta.</p></small></h3>';   

    $resToken = $this->insertarActivacionusuarios($idusuario,$token,'','');
    $resEmail = $this->enviarEmail($email,'Alta de Usuario',utf8_decode($cuerpo));

   return '';
}


function registrarSocio($email, $password,$apellido, $nombre,$refcliente) {

	$token = $this->GUID();
	$cuerpo = '';

	$fecha = date_create(date('Y').'-'.date('m').'-'.date('d'));
	date_add($fecha, date_interval_create_from_date_string('30 days'));
	$fechaprogramada =  date_format($fecha, 'Y-m-d');

   $cuerpo .= '<img src="http://asesorescrea.com/img/logo.png" alt="RIDERZ" width="190">';

   $cuerpo .= '<h2>¡Bienvenido a Asesores CREA!</h2>';


   $cuerpo .= '<p>Usa el siguente <a href="http://asesorescrea.com/desarrollo/crm/activacion.php?token='.$token.'" target="_blank">enlace</a> para confirmar tu cuenta.</p>';


	$sql = "INSERT INTO dbusuarios
				(idusuario,
				usuario,
				password,
				refroles,
				email,
				nombrecompleto,
				activo)
			VALUES
				(null,
				'".$apellido.' '.$nombre."',
				'".$password."',
				4,
				'".$email."',
				'".$apellido.' '.$nombre."',
				0)";

	$res = $this->query($sql,1);

   if ($res == false) {
		return 'Error al insertar datos ';
	} else {
		$this->insertarActivacionusuarios($res,$token,'','');

      $sqlUpdateRelacion = "update dbclientes set refusuarios = ".$res." where idcliente =".$refcliente;
      // actualizo la relacion cliente y usuario
      $resRelacion = $this->query($sqlUpdateRelacion,0);

		$retorno = $this->enviarEmail($email,'Alta de Usuario',utf8_decode($cuerpo));

		return $res;
	}
}


function confirmarEmail($email, $password,$apellido, $nombre, $idusuario) {

	$token = $this->GUID();
	$cuerpo = '';

	$fecha = date_create(date('Y').'-'.date('m').'-'.date('d'));
	date_add($fecha, date_interval_create_from_date_string('30 days'));
	$fechaprogramada =  date_format($fecha, 'Y-m-d');

    $cuerpo .= '<img src="http://asesorescrea.com/img/logo.png" alt="RIDERZ" width="190">';

    $cuerpo .= '<h2>¡Bienvenido a Asesores CREA!</h2>';


    $cuerpo .= '<p>Usa el siguente <a href="http://asesorescrea.com/desarrollo/crm/activacionpostulantes.php?token='.$token.'" target="_blank">enlace</a> para confirmar tu cuenta.</p>';



	 $res = $this->insertarActivacionusuarios($idusuario,$token,'','');
    //return $res;

    $resGuardarMensaje = $this->insertarCorreoselectronicos($idusuario,0,$email,$cuerpo,'Alta de Usuario');

    $retorno = $this->enviarEmail($email,'Alta de Usuario',utf8_decode($cuerpo));
    return '';
}


/* PARA Activacionusuarios */

function insertarActivacionusuarios($usuario_id,$token,$vigenciadesde,$vigenciahasta) {
	$query = new Query();
	$sql = "insert into dbactivacionusuarios(idactivacionusuario,usuario_id,token,vigenciadesde,vigenciahasta)
	values ('',".$usuario_id.",'".($token)."',now(),ADDDATE(now(), INTERVAL 2 DAY))";
	$query->setQuery($sql);	
	$res = $query->eject(1);
	return $res;
}


function modificarActivacionusuarios($id,$refusuarios,$token,$vigenciadesde,$vigenciahasta) {
	$query =  new Query();
	$sql = "update dbactivacionusuarios
	set
	refusuarios = ".$refusuarios.",token = '".($token)."',vigenciadesde = '".($vigenciadesde)."',vigenciahasta = '".($vigenciahasta)."'
	where idactivacionusuario =".$id;

	$query->setQuery($sql);
	$res =  $query->eject(0);
	return $res;
}


function modificarActivacionusuariosConcretada($token) {
	$query =  new Query();
	$sql = "update dbactivacionusuarios
	set
	vigenciadesde = 'NULL',vigenciahasta = 'NULL'
	where token ='".$token."'";
	$query->setQuery($sql);
	$res = $query->eject();	
	return $res;
}


function modificarActivacionusuariosRenovada($refusuarios,$token,$vigenciadesde,$vigenciahasta) {
	$query = new Query();
	$sql = "update dbactivacionusuarios
	set
	vigenciadesde = now(),vigenciahasta = ADDDATE(now(), INTERVAL 15 DAY),token = '".($token)."'
	where usuario_id =".$refusuarios;	
	$query->setQuery($sql);
	$res = $query->eject();
	return $res;
}


function eliminarActivacionusuarios($id) {
	$query = new Query();
	$sql = "delete from dbactivacionusuarios where idactivacionusuario =".$id;
	$query->setQuery($sql);
	$res = $query->eject();
	
	return $res;
}

function eliminarActivacionusuariosPorUsuario($refusuarios) {
	$query = new Query();
	$sql = "delete from dbactivacionusuarios where refusuarios =".$refusuarios;
	$query->setQuery($sql);
	$res = $query->eject();

	return $res;
}


function traerActivacionusuarios() {
	$query = new Query();
	$sql = "select
	a.idactivacionusuario,
	a.refusuarios,
	a.token,
	a.vigenciadesde,
	a.vigenciahasta
	from dbactivacionusuarios a
	order by 1";
	$query->setQuery($sql);
	$res = $query->eject();

	return $res;
}


function traerActivacionusuariosPorId($id) {
	$query = new Query();
	$sql = "select idactivacionusuario,usuario_id,token,vigenciadesde,vigenciahasta from dbactivacionusuarios where idactivacionusuario =".$id;
	$query->setQuery($sql);
	$res = $query->eject();

	return $res;
}


function traerActivacionusuariosPorToken($token) {
	$query = new Query();
	$sql = "select idactivacionusuario,usuario_id,token,vigenciadesde,vigenciahasta from dbactivacionusuarios where token ='".$token."'";
	$query->setQuery($sql);
	$res = $query->eject();

	return $res;
}


function traerActivacionusuariosPorTokenFechas($token) {
	$query = new Query();
	$sql = "select idactivacionusuario, usuario_id AS refusuarios,token,vigenciadesde,vigenciahasta from dbactivacionusuarios where token ='".$token."' and now() between vigenciadesde and vigenciahasta ";	
	$query->setQuery($sql);
	$res = $query->eject();
	
	return $res;
}

function traerActivacionusuariosPorUsuarioFechas($usuario) {
	$query = new Query();
	$sql = "select idactivacionusuario,usuario_id AS refusuarios,token,vigenciadesde,vigenciahasta from dbactivacionusuarios where refusuarios =".$usuario." and now() between vigenciadesde and vigenciahasta ";	
	$query->setQuery($sql);
	$res = $query->eject();

	return $res;
}


function activarUsuario($refusuario) {
	$query = new Query();
	$sql = "update usuario
	set
		usuario_status_id = 1
	where usuario_id =".$refusuario;
	$query->setQuery($sql);
	$res = $query->eject();
	if ($res == false) {
		return 'Error al modificar datos';
	} else {
		return '';
	}
}

function traerTokenUsuariosPorId($usuarioId) {
	$query = new Query();
	$sql = "select idactivacionusuario,usuario_id,token,vigenciadesde,vigenciahasta from dbactivacionusuarios where usuario_id =".$usuarioId;
	$query->setQuery($sql);
	$res = $query->eject();

	return $res;
}

function traerTokenPorUsuario($usuario) {
	$query = new Query();
	$sqlToken = "SELECT
	                   u.usuario_id,
	                   u.nombre,
	                   u.email,                   
	                   a.idactivacionusuario,
	                   a.token
	               FROM
	                   usuario u
	                       INNER JOIN
	                   dbactivacionusuarios a ON a.usuario_id = u.usuario_id
	               WHERE
	                   u.usuario = '".$usuario."'" ;
	$query->setQuery($sql);
	$res = $query->eject();

	return $res;
}

/* Fin */
/* /* Fin de la Tabla: dbactivacionusuarios*/

/* cambiar contraseña */

function cambioClaveUsuario($Usuario){
	$query = new Query();	
	$where = "`usuario` LIKE  '".$Usuario."'";
	$today = date("Y-m-d");
	$idUsuario= $query->selectCampo('usuario_id', 'usuario', $where);

	if(empty($idUsuario)){
		return " \n Este usuario no esta registrado";
	}

	$token =  $this->GUID();
	$sqlInsert = " INSERT INTO usuario_claves_cambio
					(usuario_claves_cambio_id,
					usuario_id,
					token,
					fecha_solicitud
					)
					VALUES
					(NULL,
					".$idUsuario.",	
					'".($token)."',
					'".($today)."' )";

	$query->setQuery($sqlInsert);
	$res = $query->eject(1);
	if ($res == false) {
		return 'Error en solicitud de cambio de password';
	} else	{

	// enviamos mail al usuario para que pueda entrar a cambiar la contaseña
	$cuerpo .= '<img src="http://financieracrea.com/esfdesarrollo/images/logo.gif" alt="Financiera CREA" >';
    $cuerpo .= '<h2 class=\"p3\"> Financiera CREA</h2>';

    $servidor = $_SERVER['SERVER_NAME'];
    $liga_servidor = ($servidor=='localhost')?SERVIDOR_LOCAL:SERVIDOR;

    $cuerpo .= '<p>Recibimos una solicitud para cambiar el password de su usuario</p>';
   	$cuerpo .= '<h3><small><p>Por favor ingrese al siguiente <a href="'.$liga_servidor.'actualizarClave.php?token='.$token .'" target="_blank">enlace</a> para registrar la nueva contraseña.</p></small></h3>';

   	$cuerpo .='<h4><p> Si ustede NO solicitó el cambio por favor comuniquese con finanicera CREA</p></h4>';

   	$cuerpo .='<h4><p> Unidad especializada de atención al cliente: <b> (55) 51350259</b> </p></h4>';

   	$cuerpo .='<p> No responda este mensaje, el remitente es una dirección de notificación</p>';
   	$this->enviarEmail($Usuario,'Cambio de contraseña',utf8_decode($cuerpo));
   	}

   	return $res;
   }

function actualizaPassword($token, $clave){
	$query = new Query();	
	$where = "`token` LIKE  '".$token."'";
	$today = date("Y-m-d");
	$idUsuario= $query->selectCampo('usuario_id', 'usuario_claves_cambio', $where);
	$idCambioClave= $query->selectCampo('usuario_claves_cambio_id', 'usuario_claves_cambio', $where); 	
 	if(empty($idUsuario) || empty($idCambioClave)) {
		return " \n Este usuario no esta registrado";
	}
	$res = $this->cambiarContraseña($idUsuario, $clave );
	$fechaUpdate = date("Y-m-d"); 

	if($res ==''){
		$sqlUpdate =  "UPDATE usuario_claves_cambio
					  SET 
					  fecha_cambio = '".$fechaUpdate."'
					  WHERE  usuario_claves_cambio_id =".$idCambioClave."";
		$query->setQuery($sqlUpdate);
		$rest = $query->eject();

		if(!$rest){
			return "Error al actulizar fecha de cambio de password";
		}
	}
	return $res;	
}

function cambiarContraseña($idUsuario, $passwordNuevo){
	$query = new Query();
	$sql = "UPDATE usuario
	SET
		clave = '".$passwordNuevo."'
	where usuario_id =".$idUsuario;
	$query->setQuery($sql);
	$res = $query->eject();
	if ($res == false) {
		return 'Error al actualizar la contraseña';
	} else {
		return '';
	}

}


/* PARA Correoselectronicos */

function insertarCorreoselectronicos($refusuarios,$refpostulantes,$email,$cuerpo,$asunto) {
	$query = new Query();
	$sql = "insert into dbcorreoselectronicos(iddcorreoelectronico,refusuarios,refpostulantes,email,cuerpo,asunto)
	values ('',".$refusuarios.",".$refpostulantes.",'".$email."',".$cuerpo.",'".$asunto."')";	
	$query->setQuery($sql);
	$res = $query->eject(1);

	return $res;
}


function modificarCorreoselectronicos($id,$refusuarios,$refpostulantes,$email,$cuerpo,$asunto) {
	$query = new Query();
	$sql = "update dbcorreoselectronicos
	set
	refusuarios = ".$refusuarios.",refpostulantes = ".$refpostulantes.",email = '".$email."',cuerpo = ".$cuerpo.",asunto = '".$asunto."'
	where iddcorreoelectronico =".$id;
	$query->setQuery($sql);
	$res = $query->eject();

	return $res;
}


function eliminarCorreoselectronicos($id) {
	$query = new Query();
	$sql = "delete from dbcorreoselectronicos where iddcorreoelectronico =".$id;
	$query->setQuery($sql);
	$res = $query->eject();

	return $res;
}


function traerCorreoselectronicos() {
	$query = new Query();
	$sql = "select
	c.iddcorreoelectronico,
	c.refusuarios,
	c.refpostulantes,
	c.email,
	c.cuerpo,
	c.asunto
	from dbcorreoselectronicos c
	order by 1";
	$query->setQuery($sql);
	$res = $query->eject();

	return $res;
}


function traerCorreoselectronicosPorId($id) {
	$query = new Query();
	$sql = "select iddcorreoelectronico,refusuarios,refpostulantes,email,cuerpo,asunto from dbcorreoselectronicos where iddcorreoelectronico =".$id;	
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
