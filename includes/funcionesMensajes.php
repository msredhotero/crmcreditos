<?php

/**
 * @mensajes y configuracion del correo
 */

date_default_timezone_set('America/Mexico_City');

class ServiciosMensajes {

	function GUID()
	{
		if (function_exists('com_create_guid') === true)
		{
			return trim(com_create_guid(), '{}');
		}

		return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
	}


	function enviarEmail($destinatario,$asunto,$cuerpo) {

		# Defina el número de e-mails que desea enviar por periodo. Si es 0, el proceso por lotes
		# se deshabilita y los mensajes son enviados tan rápido como sea posible.
		define("MAILQUEUE_BATCH_SIZE",0);

		//para el envío en formato HTML
		//$headers = "MIME-Version: 1.0\r\n";

		// Cabecera que especifica que es un HMTL
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		//dirección del remitente
		$headers .= "From: ASESORES CREA <info@asesorescrea.com>\r\n";

		//ruta del mensaje desde origen a destino
		$headers .= "Return-path: ".$destinatario."\r\n";

		//direcciones que recibirán copia oculta
		$headers .= "Bcc: msredhotero@gmail.com\r\n";

		return mail($destinatario,$asunto,$cuerpo,$headers);
	}

	function traerAsesoresPorId($id) {
		$sql = "select idasesor,refusuarios,nombre,apellidopaterno,apellidomaterno,email,curp,rfc,ine,fechanacimiento,sexo,codigopostal,refescolaridades,telefonomovil,telefonocasa,telefonotrabajo,fechacrea,fechamodi,usuariocrea,usuariomodi from dbasesores where idasesor =".$id;

		$res = $this->query($sql,0);
		return $res;
	}

	function cantidadAsesores() {
		$sql = 'select count(*) from dbasesores';

		$res = $this->query($sql,0);
		return $res;
	}

	function traerAsesoresPorGerente() {
		$sql = "select
			count(a.idasesor) as cantidad,
			usu.nombrecompleto
			from dbasesores a
			inner join dbusuarios u ON u.idusuario = a.refusuarios
			inner join dbpostulantes pp on pp.refusuarios = u.idusuario
		   inner join dbreclutadorasores rrr on rrr.refpostulantes = pp.idpostulante
			inner join dbusuarios usu on usu.idusuario = rrr.refusuarios
			group by usu.nombrecompleto
			order by usu.nombrecompleto";
		$res = $this->query($sql,0);
		return $res;
	}

   function traerPostulantesPorId($id) {
		$sql = "select idpostulante,refusuarios,nombre,apellidopaterno,apellidomaterno,email,curp,rfc,ine,fechanacimiento,sexo,codigopostal,refescolaridades,telefonomovil,telefonocasa,telefonotrabajo,refestadopostulantes,urlprueba,fechacrea,fechamodi,usuariocrea,usuariomodi,refasesores,comision,refsucursalesinbursa, refestadocivil,nss,afore,cedula,folio,refesquemareclutamiento,
		datediff(now(),fechanacimiento)/365 as edad, fechaalta from dbpostulantes where idpostulante =".$id;
		$res = $this->query($sql,0);
		return $res;
	}

   function traerReclutadorasoresPorPostulante($id) {
		$sql = "SELECT
				    r.idreclutadorasor, r.refusuarios, r.refpostulantes, u.email, u.refroles
				FROM
				    dbreclutadorasores r
				    inner join dbusuarios u on u.idusuario = r.refusuarios
				WHERE
				    r.refpostulantes =".$id;
		$res = $this->query($sql,0);
		return $res;
	}

   function traerEntrevistasActivasPorPostulanteEstadoPostulante($id,$idestadopostulante) {
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
		where e.refestadopostulantes = ".$idestadopostulante." and e.refestadoentrevistas in (1,2,3,4,5,6) and e.refpostulantes =".$id;
		$res = $this->query($sql,0);
		return $res;
	}

   function msgActivacionUsuario($idpostulante) {
      $resPostulante = $this->traerPostulantesPorId($idpostulante);

      if (mysql_num_rows($resPostulante) > 0) {
         $nombre           = mysql_result($resPostulante,0,'nombre');
         $apellidopaterno  = mysql_result($resPostulante,0,'apellidopaterno');
         $apellidomaterno  = mysql_result($resPostulante,0,'apellidomaterno');
      } else {
         $nombre           = '';
         $apellidopaterno  = '';
         $apellidomaterno  = '';
      }

      /* email base */
      $destinatario = 'rlinares@asesorescrea.com';
      $asunto = 'Activacion de Usuario';
      $cuerpo = 'El usuario '.$nombre.' '.$apellidopaterno.' '.$apellidomaterno.' se dio de alta al sistema';

      $resEmail = $this->enviarEmail($destinatario,$asunto,$cuerpo, $referencia='');
      /* email referencte si existiera */
      $resReferente = $this->traerReclutadorasoresPorPostulante($idpostulante);
      if (mysql_num_rows($resReferente) > 0) {
         $destinatario = mysql_result($resReferente,0,'email');
         $resEmailReferente = $this->enviarEmail($destinatario,$asunto,$cuerpo, $referencia='');
      }

      echo '';
   }

   function msgExamenVeritas($idpostulante) {
      $asunto = utf8_encode('Evaluación VERITAS');
      $cuerpo = '';

      $cuerpo .= '<img src="http://www.asesorescrea.com/desarrollo/crm/imagenes/logo.png" alt="asesorescrea" width="190">';

      $cuerpo .= '<h2>¡Asesores CREA!</h2>';

      //$destinatario = 'rlinares@asesorescrea.com';

      $resPostulante = $this->traerPostulantesPorId($idpostulante);

      $asesor = mysql_result($resPostulante,0,'nombre').' '.mysql_result($resPostulante,0,'apellidopaterno').' '.mysql_result($resPostulante,0,'apellidomaterno');

      $destinatario = mysql_result($resPostulante,0,'email');

      $urlprueba = mysql_result($resPostulante,0,'urlprueba');


      $resEntrevista = $this->traerEntrevistasActivasPorPostulanteEstadoPostulante($idpostulante,2);
      $estadoVeritas = mysql_result($resEntrevista,0,'estadoentrevista');
      $idEstadoVeritas = mysql_result($resEntrevista,0,'refestadoentrevistas');

      switch ($idEstadoVeritas) {
         case 2:
            $cuerpo .= '<p>Felicitaciones!!!, El estado de su evaluación VERITAS es '.$estadoVeritas.', continua con el Proceso de Reclutamiento';
         break;
         case 3:
            $cuerpo .= '<p>Atención!!!, El estado de su evaluación VERITAS es '.$estadoVeritas.', la nueva fecha para la evaluación es: '.mysql_result($resEntrevista,0,'fecha').' con el entrevistador: '.mysql_result($resEntrevista,0,'entrevistador').' en la direccion: '.mysql_result($resEntrevista,0,'domicilio').' , '.mysql_result($resEntrevista,0,'postalcompleto');
         break;
         case 4:
            $cuerpo .= '<p>Lo sentimos!!!, El estado de su evaluación VERITAS es '.$estadoVeritas.', finaliza su Proceso de Reclutamiento';
         break;
         case 5:
            $cuerpo .= '<p>Lo sentimos!!!, El estado de su evaluación VERITAS es '.$estadoVeritas.', finaliza su Proceso de Reclutamiento';
         break;

         default:
            $cuerpo .= '<p>Felicitaciones!!!, El estado de su evaluación VERITAS es '.$estadoVeritas.', debera realizar la evaluación.';
            break;
      }


      $resEmail = $this->enviarEmail($destinatario,$asunto,$cuerpo);

      return '';
   }


   function msgEntrevistaRegional($idpostulante) {
      $asunto = 'Entrevista Regional';
      $cuerpo = '';

      $cuerpo .= '<img src="http://www.asesorescrea.com/desarrollo/crm/imagenes/logo.png" alt="asesorescrea" width="190">';

      $cuerpo .= '<h2>¡Asesores CREA!</h2>';

      //$destinatario = 'rlinares@asesorescrea.com';

      $resPostulante = $this->traerPostulantesPorId($idpostulante);

      $asesor = mysql_result($resPostulante,0,'nombre').' '.mysql_result($resPostulante,0,'apellidopaterno').' '.mysql_result($resPostulante,0,'apellidomaterno');

      $destinatario = mysql_result($resPostulante,0,'email');

      $urlprueba = mysql_result($resPostulante,0,'urlprueba');


      $resEntrevista = $this->traerEntrevistasActivasPorPostulanteEstadoPostulante($idpostulante,4);
      $cuerpo .= '<p>Tiene un Entrevista programada para la fecha: '.mysql_result($resEntrevista,0,'fecha').' con el entrevistador: '.mysql_result($resEntrevista,0,'entrevistador').' en la direccion: '.mysql_result($resEntrevista,0,'domicilio').' , '.mysql_result($resEntrevista,0,'postalcompleto');


      $resEmail = $this->enviarEmail($destinatario,$asunto,$cuerpo);

      return '';
   }


   function msgURL($idpostulante) {
      $asunto = 'URL Prueba Psicometrica';
      $cuerpo = '';

      $cuerpo .= '<img src="http://www.asesorescrea.com/desarrollo/crm/imagenes/logo.png" alt="asesorescrea" width="190">';

      $cuerpo .= '<h2>¡Asesores CREA!</h2>';

      //$destinatario = 'rlinares@asesorescrea.com';

      $resPostulante = $this->traerPostulantesPorId($idpostulante);

      $asesor = mysql_result($resPostulante,0,'nombre').' '.mysql_result($resPostulante,0,'apellidopaterno').' '.mysql_result($resPostulante,0,'apellidomaterno');

      $destinatario = mysql_result($resPostulante,0,'email');

      $urlprueba = mysql_result($resPostulante,0,'urlprueba');

      $cuerpo .= 'Felicitaciones!!, continua en el proceso de Reclutamiento. Le enviamos la url para realizar el examen Psicometrico: <a href="'.$urlprueba.'">Examen Psicometrico</a>';


      $resEmail = $this->enviarEmail($destinatario,$asunto,$cuerpo);

      return '';
   }


   function msgFirmarContratos($idpostulante) {
      $asunto = 'Firmar Contratos';
      $cuerpo = '';

      $cuerpo .= '<img src="http://www.asesorescrea.com/desarrollo/crm/imagenes/logo.png" alt="asesorescrea" width="190">';

      $cuerpo .= '<h2>¡Asesores CREA!</h2>';

      //$destinatario = 'rlinares@asesorescrea.com';

      $resPostulante = $this->traerPostulantesPorId($idpostulante);

      $asesor = mysql_result($resPostulante,0,'nombre').' '.mysql_result($resPostulante,0,'apellidopaterno').' '.mysql_result($resPostulante,0,'apellidomaterno');

      $destinatario = mysql_result($resPostulante,0,'email');

      $cuerpo .= 'Felicitaciones!!, continua en el proceso de Reclutamiento. Le pedimos por favor si puede acercarse a nuestras oficinas a Firmar las Contratos';


      $resEmail = $this->enviarEmail($destinatario,$asunto,$cuerpo);

      return '';
   }


	function msgAsesor($idpostulante) {
      $asunto = 'Proceso de Reclutamiento - Finalizado';
      $cuerpo = '';

      $cuerpo .= '<img src="http://www.asesorescrea.com/desarrollo/crm/imagenes/logo.png" alt="asesorescrea" width="190">';

      $cuerpo .= '<h2>¡Asesores CREA!</h2>';

      //$destinatario = 'rlinares@asesorescrea.com';

      $resPostulante = $this->traerPostulantesPorId($idpostulante);

      $asesor = mysql_result($resPostulante,0,'nombre').' '.mysql_result($resPostulante,0,'apellidopaterno').' '.mysql_result($resPostulante,0,'apellidomaterno');

      $destinatario = mysql_result($resPostulante,0,'email');

      $cuerpo .= 'Felicitaciones!!, ya es parte de nuestro equipo de Fuerza de Ventas.';


      $resEmail = $this->enviarEmail($destinatario,$asunto,$cuerpo);

      return '';
   }

	function msgAsesorNuevo($idasesor) {
		$resAsesor = $this->traerAsesoresPorId($idasesor);

		$resCantidad = $this->cantidadAsesores();

		$resDetalle = $this->traerAsesoresPorGerente();

		if (mysql_num_rows($resCantidad) > 0) {
			$cantidadAsesores = mysql_result($resCantidad,0,0);
		} else {
			$cantidadAsesores = 0;
		}

		$cantidadDetalle = 0;

		$cuerpo = '';
		$cuerpo .= '<h4>Asesor Nuevo: '.mysql_result($resAsesor,0,'apellidopaterno').' '.mysql_result($resAsesor,0,'apellidomaterno').' '.mysql_result($resAsesor,0,'nombre').'</h4>';

		$cuerpo .= '<h5>Cantidad de Asesores: '.$cantidadAsesores.'</h5>';

		while ($row = mysql_fetch_array($resDetalle)) {
			$cuerpo .= '<p>Gerente: '.$row['nombrecompleto'].' , Cantidad de Asesores: '.$row['cantidad'].'</p>';
			$cantidadDetalle += $row['cantidad'];
		}

		$cuerpo .= '<p>Oficina: '.($cantidadAsesores - $cantidadDetalle).'</p>';

		//$destinatario = 'msredhotero@msn.com, msredhotero@gmail.com';
		$destinatario = 'jfoncerrada@icloud.com, luis@grupojavelly.com';

		$asunto = 'Se genero un nuevo Asesor';

      $resEmail = $this->enviarEmail($destinatario,$asunto,$cuerpo);

      return '';

	}




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
