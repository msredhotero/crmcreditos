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

		$serviciosFunciones 	 = new Servicios();
		$serviciosUsuario 		 = new ServiciosUsuarios();
		$serviciosHTML 			 = new ServiciosHTML();
		$serviciosReferencias 	 = new ServiciosReferencias();
		$serviciosNotificaciones = new ServiciosNotificaciones();
		$serviciosSolicitudes	 = new ServiciosSolicitudes();
		$query = new Query();

		$usuario = new Usuario();
		$usuarioRol = $usuario->getRolId();
		$usuarioMail = $usuario->getUsuario();
		$usuarioId  = $usuario->getUsuarioId();

		$enviarDoctos = isset($_POST['dc'])?$_POST['dc']:0;


		if($enviarDoctos==1){
			// se envia el mail y se agrega el proceso 2 del tramite		
				// documentos completos se manda la pantalla de aviso

			$faltaImagen = false;
			$idContratoGlobal = $_POST['idContratoGlobal'];
			//iddocumentacion

			$resDoctosA = $serviciosReferencias->traerDocumentacionPorTipoCreditoDocumentacionCompletaCliente($idContratoGlobal, 1);
			while($rowDoctosReq = mysql_fetch_array($resDoctosA)){
				$edoDocumento = $rowDoctosReq['estadodocumentacion'];
				$requerido = $rowDoctosReq['req'];

				#echo "<br>estado docto=>".$edoDocumento." requerido => ".$requerido;
				if($edoDocumento =='Falta' && $requerido == '1'){
					$faltaImagen = true;
				}
			}

				if(!$faltaImagen){
					echo "1";
					$cuerpoMail = '';	
					
	    			$cuerpoMail .= '<h2 class=\"p3\"> DOCUMENTOS RECIBIDOS</h2>';
	    			$servidor = $_SERVER['SERVER_NAME'];
	    			$liga_servidor = ($servidor=='localhost')?SERVIDOR_LOCAL:SERVIDOR;
	   				$cuerpoMail .= '<h3><small><p>Hemos recibido sus documentos, en breve nos comunicaremos con usted, por favor espere nuestra llamada. </p></small></h3>';
	   				$cuerpoMail .='<p> No responda este mensaje, el remitente es una dirección de notificación</p>';	

	   				#$cuerpoMail .= '<img src="http://financieracrea.com/esfdesarrollo/images/logo.gif" alt="Financiera CREA" >';
	   				 $cuerpoMail .= '<br><img width="393" height="131"  src="http://financieracrea.com/esfdesarrollo/images/firmaCREA24.jpg" alt="Financiera CREA" >';



					if($usuarioRol ==8){
						$usuarioMail = 'zuoran_17@hotmail.com'; // comentar linea								
						$serviciosUsuario->enviarEmail($usuarioMail,utf8_decode('Recepción de documentos'),utf8_decode($cuerpoMail));
						$serviciosSolicitudes->insertaProcesoContratoGlobal($idContratoGlobal, 2, $usuarioId);
						// se actualiza el campo de actualizacionCLiente  para que salga en el listado en color amarilllo

						$sqlUpdate = "UPDATE dbcontratosglobales SET 	actualizacioncliente = 1 WHERE  idcontratoglobal =  $idContratoGlobal ";
						$query->setQuery($sqlUpdate);
						$query->eject();
					}
				}

				
			

		}else{
			// se hace el proceso para carga de archivo que adjunto el cliente	
		
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

			#print_r($_POST);

			$resImagen = $serviciosReferencias->traerDocumentacionPorTipoDocumentacionContrato($idContratoGlobal,$iddocumentacion);
			#echo "IdDocumentacion=>". $iddocumentacion ."**\n";
			$objImagen = $query->fetchObject($resImagen);
			$numRowsImag = $query->numRows($resImagen);
			#var_dump($objImagen);


			if ($numRowsImag>0) {
				$archivoAnterior = $objImagen->nombre; //mysql_result($resImagen,0,'archivo');
			} else {
				$archivoAnterior = '';
			}

			#echo "archivo ante=>".$archivoAnterior;
			$imagen = $serviciosReferencias->sanear_string(basename($archivo['name']));
			$type = $archivo["type"];

			$resDocumentacion = $serviciosReferencias->traerDocumentosPorId($iddocumentacion);
			$objDocto = $query->fetchObject($resDocumentacion);
			#var_dump($objDocto);
			#var_dump($objImagen);


			// desarrollo
			$dir_destino = '../../../../upload/'.$idContratoGlobal.'/'.$objDocto->nombre_archivo;
			$dir_destino_db = 'upload/'.$idContratoGlobal.'/'.$objDocto->nombre_archivo.'/';
			#list($base,$extension) = explode('.',$name);
			$extension = pathinfo($name, PATHINFO_EXTENSION);
			$newname = implode('.', [$idContratoGlobal."_".$objDocto->nombre_archivo, time(), $extension]);

			// produccion
			//$dir_destino = 'https://www.saupureinconsulting.com.ar/aifzn/data/'.mysql_result($resFoto,0,'iddocumentacionjugadorimagen').'/';

			$imagen_subida = $dir_destino.'/'.$newname;

			// desarrollo
			$nuevo_noentrar = '../../archivos/index.php';

			// produccion
			// $nuevo_noentrar = 'https://www.saupureinconsulting.com.ar/aifzn/data/'.$_SESSION['idclub_aif'].'/'.'index.php';

			if (!file_exists($dir_destino)) {
				mkdir($dir_destino, 0777,true);
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

			$resInsertar = $serviciosReferencias->insertarDocumentacionContratoGlobal($idContratoGlobal,$iddocumentacion,$newname, '1',$dir_destino_db, $usuarioId);

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

			// verificamos si ya cargo la galeria completa si es asi se manda pantalla de notificacion

			$faltaImagen = false;

			$resDoctos = $serviciosReferencias->traerDocumentacionPorTipoCreditoDocumentacionCompletaCliente($idContratoGlobal, 1);
			while($rowDoctosReq = mysql_fetch_array($resDoctos)){
				$edoDocumento = $rowDoctosReq['estadodocumentacion'];
				$requerido = $rowDoctosReq['req'];
				if($edoDocumento =='Falta' && $requerido == '1'){
					$faltaImagen = true;
				}
			$faltaImagen = true; // se envia caon el boton verde	

			}

			if(!$faltaImagen){
				// documentos completos se manda la pantalla de aviso
				echo "DC";

				$cuerpoMail = '';	
				#$cuerpoMail .= '<img src="http://financieracrea.com/esfdesarrollo/images/logo.gif" alt="Financiera CREA" >';
    			$cuerpoMail .= '<h2 class=\"p3\"> DOCUMENTOS RECIBIDOS</h2>';
    			$servidor = $_SERVER['SERVER_NAME'];
    			$liga_servidor = ($servidor=='localhost')?SERVIDOR_LOCAL:SERVIDOR;
   				$cuerpoMail .= '<h3><small><p>Hemos recibido sus documentos, en breve nos comunicaremos con usted, por favor espere nuestra llamada. </p></small></h3>';
   				$cuerpoMail .='<p> No responda este mensaje, el remitente es una dirección de notificación</p>';


	   				 $cuerpoMail .= '<br><img width="393" height="131"  src="http://financieracrea.com/esfdesarrollo/images/firmaCREA24.jpg" alt="Financiera CREA" >';	


				if($usuarioRol ==8){
					$emailUsuario = $serviciosReferencias->regresaMailUsuarioIdDocto($resInsertar);				
					$serviciosUsuario->enviarEmail($emailUsuario,'Recepción de documentos',utf8_decode($cuerpoMail));
					$serviciosSolicitudes->insertaProcesoContratoGlobal($idContratoGlobal, 2, $usuarioId);
				}
			}else{
				echo "Archivo guardado correctamente";
			}

			

		} else {
			echo "Error al guardar el archivo";
		}

	} // else del enviar documentos



	}

	?>
