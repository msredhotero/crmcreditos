<?php

	session_start();

	$servidorCarpeta = 'aifzn';

	if (!isset($_SESSION['usua_sahilices']))
	{
		header('Location: ../../error.php');
	} else {

		

		include '../../../../class_include.php';

		include '../../../../includes/ImageResize.php';
		include '../../../../includes/ImageResizeException.php';

		$serviciosFunciones 	= new Servicios();
		$serviciosUsuario 		= new ServiciosUsuarios();
		$serviciosHTML 			= new ServiciosHTML();
		$serviciosReferencias 	= new ServiciosReferencias();
		$serviciosNotificaciones	= new ServiciosNotificaciones();
		$serviciosSolicitudes	 = new ServiciosSolicitudes();
		$query = new Query();

		$archivo = $_FILES['file'];

		$templocation = $archivo['tmp_name'];

		$name = $serviciosReferencias->sanear_string(str_replace(' ','',basename($archivo['name'])));


		if (!$templocation) {
		die('No ha seleccionado ningun archivo');
		}

		$noentrar = '../../imagenes/index.php';

		if ($_SESSION['idroll_sahilices'] == 10) {
			$idusuario = $_SESSION['usuaid_sahilices'];
			$resultado 		= 	$serviciosReferencias->traerAsociadosPorUsuario($idusuario);
			$id = mysql_result($resultado,0,'idasociado');
		} else {			
			$idContratoGlobal = $_POST['idContratoGlobal'];
			$resultado = $serviciosReferencias->traerDatosContratoGlobalPorId($idContratoGlobal);
		}

		$iddocumentacion = $_POST['iddocumentacion'];
		$usuarioId = $_POST['usuarioId'];
		$resImagen = $serviciosReferencias->traerDocumentacionPorTipoCreditoDocumentacion($idContratoGlobal,$iddocumentacion);
		$objImagen = $query->fetchObject($resImagen);
		$numRowsImag = $query->numRows($resImagen);


		if ($numRowsImag>0) {
			$archivoAnterior = $objImagen->nombre; //mysql_result($resImagen,0,'archivo');
		} else {
			$archivoAnterior = '';
		}


		$imagen = $serviciosReferencias->sanear_string(basename($archivo['name']));
		$type = $archivo["type"];

		$resDocumentacion = $serviciosReferencias->traerDocumentosPorId($iddocumentacion);
		$objDocto = $query->fetchObject($resDocumentacion);
		#var_dump($objDocto);
		#var_dump($objImagen);


		// desarrollo
		$dir_destino = '../../../../upload/'.$idContratoGlobal.'/'.$objDocto->nombre_archivo;
		$dir_destino_db = 'upload/'.$idContratoGlobal.'/'.$objDocto->nombre_archivo.'/';
		list($base,$extension) = explode('.',$name);
		$newname = implode('.', [$idContratoGlobal."_".$objDocto->nombre_archivo, time(), $extension]);

		// produccion
		//$dir_destino = 'https://www.saupureinconsulting.com.ar/aifzn/data/'.mysql_result($resFoto,0,'iddocumentacionjugadorimagen').'/';

		$imagen_subida = $dir_destino.'/'.$newname;

		// desarrollo
		$nuevo_noentrar = '../../archivos/index.php';

		// produccion
		// $nuevo_noentrar = 'https://www.saupureinconsulting.com.ar/aifzn/data/'.$_SESSION['idclub_aif'].'/'.'index.php';

		if (!file_exists($dir_destino)) {
			mkdir($dir_destino, 0777);
		}

		if (!file_exists($dir_destino.'/')) {
			mkdir($dir_destino.'/', 0777);
		}

		//borro el archivo anterior
		if ($archivoAnterior != '') {
			unlink($dir_destino.'/'.$archivoAnterior);
		}

		if (move_uploaded_file($templocation, $imagen_subida)) {
			$pos = strpos( strtolower($type), 'pdf');

			$resEliminar = $serviciosReferencias->eliminarDocumentacionPorContratoGlobalDocumentacion($idContratoGlobal,$iddocumentacion);		

			$resInsertar = $serviciosReferencias->insertarDocumentacionContratoGlobal($idContratoGlobal,$iddocumentacion,$newname, '5',$dir_destino_db, $usuarioId);

			/**** creo la notificacion ******/
			#$emailReferente = 'lreyes@financieracrea.com'; //por ahora fijo
			##$mensaje = 'Se presento una documentacion: '.$objDocto->descripcion;
			#$idpagina = 3;
			#$autor = mysql_result($resultado, 0, 'apellidopaterno').' '.mysql_result($resultado, 0, 'apellidomaterno').' '.mysql_result($resultado, 0, 'nombre');
			#$destinatario = $emailReferente;
			#$id1 = $id;
			#$id2 = 0;
			#$id3 = 0;
			#$icono = 'person_add';
			#$estilo = 'bg-light-green';
			#$fecha = date('Y-m-d H:i:s');
			#$url = "asociados/subirdocumentacioni.php?id=".$id."&documentacion=".$iddocumentacion;

			#$res = $serviciosNotificaciones->insertarNotificaciones($mensaje,$idpagina,$autor,$destinatario,$id1,$id2,$id3,$icono,$estilo,$fecha,$url);
			/*** fin de la notificacion ****/

			if ($pos === false) {
				$image = new \Gumlet\ImageResize($imagen_subida);
				$image->scale(50);
				$image->save($imagen_subida);
			}

			// update a la tabla dbplanillasarbitros
			//$resEstado = $serviciosReferencias->modificarEstadoPostulante($idpostulante,3);

			

 		#echo "No hay error";
 			$dosctosAdminCompletos = $serviciosSolicitudes->verificaDoctosAdministracion($idContratoGlobal);
 			if($dosctosAdminCompletos){
 				$sqlUpdateContrato = "UPDATE dbcontratosglobales  SET `documentosadministracioncompletos` = '1' WHERE `idcontratoglobal` =".$idContratoGlobal;
 				$query->setQuery($sqlUpdateContrato);
 				$query->eject();
 				echo "1";
 			}else{
 				echo "Archivo guardado correctamente";
 			}




		} else {
			echo "Error al guardar el archivo";
		}



	}

	?>
