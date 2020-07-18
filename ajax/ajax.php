<?php
/*include ('../includes/class/Conexion.inc.php');
include ('../includes/class/Query.class.php');
include ('../includes/funcionesUsuarios.php');
include ('../includes/funciones.php');
include ('../includes/funcionesHTML.php');
include ('../includes/funcionesReferencias.php');
include ('../includes/funcionesNotificaciones.php');
include ('../includes/funcionesMensajes.php');
include ('../includes/validadores.php');
include ('../includes/funcionesSolicitudes.php');*/
include'../class_include.php';

$dbQuery = new Query();
$serviciosUsuarios  		= new ServiciosUsuarios();
$serviciosFunciones 		= new Servicios();
$serviciosHTML				= new ServiciosHTML();
$serviciosReferencias		= new ServiciosReferencias();
$serviciosNotificaciones	= new ServiciosNotificaciones();
$serviciosMensajes			= new ServiciosMensajes();
$serviciosValidador         = new serviciosValidador();
$serviciosSolicitudes       = new ServiciosSolicitudes();
$serviciosToken =  new Token();

$serviciosCatalogos = new serviciosCatalogos();


$accion = $_POST['accion'];


$resV['error'] = '';
$resV['mensaje'] = '';
$datosPost = array();
$datosPost = $_POST;

#print_r($_POST);
#die();
date_default_timezone_set('America/Mexico_City');
#print_r($_POST);

switch ($accion) {
    case 'login':
      validarAcceso($serviciosUsuarios);
      break;
    case 'register':
      RegistrarUsuario($serviciosUsuarios);
      break;
    case 'solicitarCambioClave':
    	solicitarCambioClaveUsuario($serviciosUsuarios);
    	break;
    case 'actualizarClave':
    	cambiarClaveUsuario($serviciosUsuarios);
    	break;	    	 
	  case 'insertarSolContGlobal':
		  insertaNuevaSolicitudGlobal($serviciosSolicitudes);
		  break;
	  case 'editarSolContGlobal':
		  editaSolicitudGlobal($serviciosSolicitudes);
		  break;
	  case 'editarDocumentos':
		  guardaDocumentosSolicitudGlobal($serviciosSolicitudes);
		  break;
	  case 'traerDocumentacionPorContratoDocumentacion':
      traerDocumentacionPorContratoDocumentacion($serviciosReferencias);
   		break;
    case 'eliminarDocumentacionContratoGlobal':
      eliminarDocumentacionContratoGlobal($serviciosReferencias);
   		break;
    case 'modificarEstadoDocumentoContrato':
   		modificarEstadoDocumentoContrato($serviciosReferencias);
   		break;
   	case 'enviaDictamenDoctos':
   		enviarDictaminancionDocumento($serviciosReferencias);
   		break;
   	case 'frmAjaxModificar':
    	frmAjaxModificar($serviciosFunciones, $serviciosReferencias, $serviciosUsuarios, $serviciosCatalogos);
   		break;	
   	case 'modificarRechazo';
   		modificarRechazos($serviciosCatalogos);
   		break;
   	case 'insertarRechazo';
   		insertarRechazos($serviciosCatalogos);
   		break;
    case 'modificarAsesor';
      modificarAsesor($serviciosCatalogos);
      break;
    case 'insertarAsesor';
      insertarAsesor($serviciosCatalogos);
      break;
    case 'modificarUDI';
      modificarUDI($serviciosCatalogos);
      break;
    case 'insertarUDI';
      insertarUDI($serviciosCatalogos);
      break;   
    case 'aprobarCGEmpresa';
      aprobarContratoGlobalEmpresa($serviciosSolicitudes); 
      break;
    case 'historialCrediticio';
      autorizarhistorialCrediticio($serviciosSolicitudes); 
      break;
    case 'frmAjaxAprobaRechazar';
      frmAjaxAprobaRechazar($serviciosFunciones, $serviciosReferencias, $serviciosUsuarios);
      break;
    case 'insertarRiesgoElemento';
      insertarRiesgoElemento($serviciosCatalogos);
      break;
    case 'insertarRiesgoIndicador';
      insertarRiesgoIndicador($serviciosCatalogos);
      break;      
    case 'insertarRiesgoVariable'; 
      insertarRiesgoVariable($serviciosCatalogos);
      break;
    case 'modificarRiesgoElemento';
      modificarRiesgoElemento($serviciosCatalogos);
      break;      
    case 'modificarRiesgoIndicador';
      modificarRiesgoIndicador($serviciosCatalogos);
      break;
    case 'modificarRiesgoVariable';
      modificarRiesgoVariable($serviciosCatalogos);
      break;
    case 'insertarRiesgoNivel';
      insertarRiesgoNivel($serviciosCatalogos);
      break;
    case 'modificarRiesgoNivel';
      modificarRiesgoNivel($serviciosCatalogos);
      break;         
    case 'modificarVigenciaINE';
      modificarVencimientoINE($serviciosSolicitudes);
      break;
    case'firmarDocumentosCG';
      firmarDocumentosContratoGlobal($serviciosSolicitudes);
      break;  
    case 'generarNuevoToken';
      generarNuevoToken($serviciosToken);
      break;
       
   
      
	default:
		print	"No teien deficion de procedimiento". $accion. "**";	

}
/* Fin */


function validarAcceso($serviciosUsuarios) {
	$email		=	$_POST['usuario'];
	$pass		=	$_POST['clave'];
	//$idempresa  =	$_POST['idempresa'];
	echo $serviciosUsuarios->login($email,$pass);
}

function registrarUsuario($serviciosUsuarios){

	$usuario = $_POST["usuario"];
	$password = $_POST["clave"];
	$rol = 8; // cliente
	$email = $_POST["usuario"];
	$nombrecompleto = $_POST["nombre"];
	$res = $serviciosUsuarios->insertarUsuario($usuario,$password,$rol,$email,$nombrecompleto);
	echo $res;
}

function solicitarCambioClaveUsuario($serviciosUsuarios){
	$usuario = $_POST["usuario"];
	$res = $serviciosUsuarios->cambioClaveUsuario($usuario);
	echo $res;
}


function cambiarClaveUsuario($serviciosUsuarios){
	$token = $_POST['token'];
	$password = $_POST['clave'];
	$res = $serviciosUsuarios->actualizaPassword($token, $password);
	echo $res;
}


function insertaNuevaSolicitudGlobal($serviciosSolicitudes){
	// insertamos los datos de la nueva solicitud en la base
	$res = $serviciosSolicitudes->insertarSolicitudGlobal();
	echo $res;	
	//return $res["error"];
	}

function editaSolicitudGlobal($serviciosSolicitudes){
	// insertamos los datos de la nueva solicitud en la base
	
	$res = $serviciosSolicitudes->editarSolicitudGlobal();
	echo $res;	
	//return $res["error"];
	}	

function guardaDocumentosSolicitudGlobal($serviciosSolicitudes){	
	$res = $serviciosSolicitudes->subirDocumentosSolicitudGlobal();	
	echo $res;
}

function traerDocumentacionPorContratoDocumentacion($serviciosReferencias){
	$query = new Query();
	$idContratoGlobal = $_POST['idContratoGlobal'];
    $iddocumento = $_POST['iddocumento'];

    $resV['datos'] = '';
    $resV['error'] = false;

    $resFoto = $serviciosReferencias->traerDocumentacionPorTipoCreditoDocumentacion($idContratoGlobal,$iddocumento);

    $imagen = '';
    $objImagen = $query->fetchObject($resFoto);
    $numrowsImage = $query->numRows($resFoto);

   if ($numrowsImage > 0) {
      /* produccion
      $imagen = 'https://www.saupureinconsulting.com.ar/aifzn/'.mysql_result($resFoto,0,'archivo').'/'.mysql_result($resFoto,0,'imagen');
      */
      #print_r($resFoto);

      //desarrollo
      $type ='';
      $nombre = $objImagen->nombre;
      $arrNombre = explode('.', $nombre);
      $ext = count($arrNombre) -1;
      if(count($arrNombre)>2)
      $extension =$arrNombre[2];
  	  else
  	  $extension =$arrNombre[($elementosNombre-1)];

    $extension =$arrNombre[$ext];

      
      $type = ($extension =='jpg' || $extension=='jpeg')?'image/jpeg':(($extension=='mp4' || $extension=='avi' || $extension=='mpg' ||  $extension=='mpeg')?'video/x-msvideo':($extension=='pdf')?'pdf':'');

      if ($extension == '') {
         $imagen = '../../../../imagenes/sin_img2.jpg';

         $resV['datos'] = array('imagen' => $imagen, 'type' => 'imagen');
         $resV['error'] = true;
      } else {
         $imagen = '../../archivos/asociados/'.$objImagen->carpeta.'/'.$objImagen->nombre;

         $imagen = '../../../../'.$objImagen->carpeta.$objImagen->nombre;

         $resV['datos'] = array('imagen' => $imagen, 'type' => $type);

         $resV['error'] = false;
      }



   } else {
      $imagen = '../../../../imagenes/sin_img2.jpg';


      $resV['datos'] = array('imagen' => $imagen, 'type' => 'imagen');
      $resV['error'] = true;
   }


   header('Content-type: application/json');
   echo json_encode($resV);

}	

function eliminarDocumentacionContratoGlobal($serviciosReferencias) {
   $idContratoGlobal = $_POST['idContratoGlobal'];
   $iddocumento = $_POST['iddocumento'];

   $res = $serviciosReferencias->eliminarDocumentoContratoGlobal($idContratoGlobal, $iddocumento);

   header('Content-type: application/json');
   echo json_encode($res);
}





function modificarEstadoDocumentoContrato($serviciosReferencias){
   $iddocumento = $_POST['iddocumento'];
   $idestado = $_POST['idestado'];   
   $idUsuario = $_POST['idusuario'];
   $idRechazo = $_POST['idrechazo'];
   $comentario =  $_POST['comentario'];
   $fechaDomicilio = $_POST['vigenciaDom'];
   $tipoDocto = $_POST['tipoDocto'];
   if ($iddocumento == 0) {
      $resV['leyenda'] = 'Todavia no cargo el archivo, no podra modificar el estado de la documentación';
      $resV['error'] = true;
   } else {
      $res = $serviciosReferencias->modificarEstadoDocumentoContrato($iddocumento,$idestado,$idUsuario, $idRechazo, $comentario, $fechaDomicilio, $tipoDocto);

      if ($res == true) {
         $resV['leyenda'] = '';
         $resV['error'] = false;
      } else {
         $resV['leyenda'] = 'Hubo un error al modificar datos';
         $resV['error'] = true;
      }
   }


   header('Content-type: application/json');
   echo json_encode($resV);
}


function enviarDictaminancionDocumento($serviciosReferencias){
	$idCG = $_POST['idCG'];
	$res = $serviciosReferencias->enviarDictaminacionDocumentos($idCG);
	echo $res;
}


function devolverImagen($nroInput) {

	if( $_FILES['archivo'.$nroInput]['name'] != null && $_FILES['archivo'.$nroInput]['size'] > 0 ){
	// Nivel de errores
	  error_reporting(E_ALL);
	  $altura = 100;
	  // Constantes
	  # Altura de el thumbnail en píxeles
	  //define("ALTURA", 100);
	  # Nombre del archivo temporal del thumbnail
	  //define("NAMETHUMB", "/tmp/thumbtemp"); //Esto en servidores Linux, en Windows podría ser:
	  //define("NAMETHUMB", "c:/windows/temp/thumbtemp"); //y te olvidas de los problemas de permisos
	  $NAMETHUMB = "c:/windows/temp/thumbtemp";
	  # Servidor de base de datos
	  //define("DBHOST", "localhost");
	  # nombre de la base de datos
	  //define("DBNAME", "portalinmobiliario");
	  # Usuario de base de datos
	  //define("DBUSER", "root");
	  # Password de base de datos
	  //define("DBPASSWORD", "");
	  // Mime types permitidos
	  $mimetypes = array("image/jpeg", "image/pjpeg", "image/gif", "image/png");
	  // Variables de la foto
	  $name = $_FILES["archivo".$nroInput]["name"];
	  $type = $_FILES["archivo".$nroInput]["type"];
	  $tmp_name = $_FILES["archivo".$nroInput]["tmp_name"];
	  $size = $_FILES["archivo".$nroInput]["size"];
	  // Verificamos si el archivo es una imagen válida
	  if(!in_array($type, $mimetypes))
		die("El archivo que subiste no es una imagen válida");
	  // Creando el thumbnail
	  switch($type) {
		case $mimetypes[0]:
		case $mimetypes[1]:
		  $img = imagecreatefromjpeg($tmp_name);
		  break;
		case $mimetypes[2]:
		  $img = imagecreatefromgif($tmp_name);
		  break;
		case $mimetypes[3]:
		  $img = imagecreatefrompng($tmp_name);
		  break;
	  }

	  $datos = getimagesize($tmp_name);

	  $ratio = ($datos[1]/$altura);
	  $ancho = round($datos[0]/$ratio);
	  $thumb = imagecreatetruecolor($ancho, $altura);
	  imagecopyresized($thumb, $img, 0, 0, 0, 0, $ancho, $altura, $datos[0], $datos[1]);
	  switch($type) {
		case $mimetypes[0]:
		case $mimetypes[1]:
		  imagejpeg($thumb, $NAMETHUMB);
			  break;
		case $mimetypes[2]:
		  imagegif($thumb, $NAMETHUMB);
		  break;
		case $mimetypes[3]:
		  imagepng($thumb, $NAMETHUMB);
		  break;
	  }
	  // Extrae los contenidos de las fotos
	  # contenido de la foto original
	  $fp = fopen($tmp_name, "rb");
	  $tfoto = fread($fp, filesize($tmp_name));
	  $tfoto = addslashes($tfoto);
	  fclose($fp);
	  # contenido del thumbnail
	  $fp = fopen($NAMETHUMB, "rb");
	  $tthumb = fread($fp, filesize($NAMETHUMB));
	  $tthumb = addslashes($tthumb);
	  fclose($fp);
	  // Borra archivos temporales si es que existen
	  //@unlink($tmp_name);
	  //@unlink(NAMETHUMB);
	} else {
		$tfoto = '';
		$type = '';
	}
	$tfoto = utf8_decode($tfoto);
	return array('tfoto' => $tfoto, 'type' => $type);
}



function frmAjaxModificar($serviciosFunciones, $serviciosReferencias, $serviciosUsuarios, $serviciosCatalogos) {
   $tabla = $_POST['tabla'];
   $id = $_POST['id'];
   $url = '';
   session_start();
   switch ($tabla) {      
      case 'rechazo':
         $lblCambio	 	= array();
         $lblreemplazo	= array();

         $modificar = "modificarEspecialidades";
         $idTabla = 'idrechazocausa';

         $refdescripcion = array();
         $refCampo 	=  array();
      break;

       case 'tbasesores':
         $lblCambio   = array();
         $lblreemplazo  = array();

         $modificar = "modificarEspecialidades";
         $idTabla = 'idasesor';

         $refdescripcion = array();
         $refCampo  =  array();
      break;

       case 'tbudi': 
         $lblCambio   = array();
         $lblreemplazo  = array();
         $modificar = "modificarEspecialidades";
         $idTabla = 'idudi';
         $refdescripcion = array();
         $refCampo  =  array();
      break;

     case 'tbriesgoelementos': 
         $lblCambio   = array();
         $lblreemplazo  = array();
         $modificar = "modificarEspecialidades";
         $idTabla = 'idriesgoelemento';
         $refdescripcion = array();
         $refCampo  =  array();
      break;

     case 'tbriesgoindicadores': 
         $resultado = $serviciosCatalogos->traerRiesgoIndicadorPorId($id);
         $lblCambio   = array('refriesgoelemento','variablesql');
         $lblreemplazo  = array('elemento','Variable SQL');
         $modificar = "modificarEspecialidades";
         $idTabla = 'idriesgoindicador';
         $resElementos = $serviciosCatalogos->traerCatalogoRiesgoElemento();
         $cadRef2 = $serviciosFunciones->devolverSelectBoxActivo($resElementos,array(1),' ',mysql_result($resultado,0,'refriesgoelemento'));
         $refdescripcion = array(0=> $cadRef2);
         $refCampo  =  array('refriesgoelemento');
         break;

       case 'tbriesgovariables': 
         $resultado = $serviciosCatalogos->traerRiesgoVariablePorId($id);
         $lblCambio   = array('refriesgoindicador','tipovariable', 'valoresvariable', 'activo');
         $lblreemplazo  = array('Indicador','Formula de cálculo','opciones de formula','Activo <small>Poner en 0 para quitar del cálculo</small>');
         $modificar = "modificarEspecialidades";
         $idTabla = 'idriesgovariable';
         $resIndicador = $serviciosCatalogos->traerCatalogoRiesgoIndicador();
         $cadRef2 = $serviciosFunciones->devolverSelectBoxActivo($resIndicador,array(1),' ',mysql_result($resultado,0,'refriesgoindicador'));
         $refdescripcion = array(0=> $cadRef2);
         $refCampo  =  array('refriesgoindicador');
        
         break; 
       case 'tbriesgoniveles': 
         $lblCambio   = array();
         $lblreemplazo  = array();
         $modificar = "modificarEspecialidades";
         $idTabla = 'idriesgonivel';
         $refdescripcion = array();
         $refCampo  =  array();
      break;        


      default:
         // code...
         break;
   }

   $formulario = $serviciosFunciones->camposTablaModificar($id, $idTabla,$modificar,$tabla,$lblCambio,$lblreemplazo,$refdescripcion,$refCampo);

   if ($url != '') {
      echo $url;
   } else {
    $hoy = date("d-m-Y");
      switch ($tabla) {
         case 'dbentrevistas':
         echo str_replace('codigopostal','codigopostal2',$formulario);
         break;
         case 'dbentrevistaoportunidades':
         echo str_replace('codigopostal','codigopostal2',$formulario);
         break;
         case 'tbudi':
         echo str_replace('Descripcion','Valor UDI de fecha '.$hoy,$formulario);
         break;        
         default:
            echo $formulario;
         break;
      }

   }

}


function frmAjaxAprobaRechazar($serviciosFunciones, $serviciosReferencias, $serviciosUsuarios) {
   $tabla = $_POST['tabla'];
   $id = $_POST['id'];
   $url = '';
  $dataContratoGlobal = new ServiciosSolicitudes($id);
  $page = new Formulario();
  $form = new FormularioSolicitud();

  $contenidoformularioNuevo = array();
  $dataContratoGlobal->cargarDatosContratoGlobal();
  $idFormulario = 'ModificarStatus';
  $lectura = false;
$form->setDatos($dataContratoGlobal->getDatos());
$form->set_lectura($lectura);

$page = new Formulario();
$contenidoformularioNuevo = $form->apruebaRechazaEmpleador();
$page->add_content($contenidoformularioNuevo);
$classFormulario ='nuevaSolicitud';
$idContratoGlobal = $dataContratoGlobal->getDato('idcontratoglobal'); 
$action = (!empty($idContratoGlobal))?'aprobarCGEmpresa':'';
$title = 'Autorizar crédito';
$formularioCARD = $page->htmlCardFormulario('', 'card-info', 12, $idFormulario, $action, $title);
echo $formularioCARD;

}
function modificarRechazos($serviciosReferencias) {
	$id					=	$_POST['id'];
	$descripcion		=	$_POST['descripcion'];
	$res = $serviciosReferencias->modificarRechazos($id,$descripcion);

   if ($res == true) {
      echo '';
   } else {
      echo 'Hubo un error al modificar datos';
   }
}

function insertarRechazos($serviciosReferencias) {
	$descripcion =	$_POST['descripcion'];  

	$res = $serviciosReferencias->insertarRechazos($descripcion);
	if ((integer)$res > 0) {
		echo '';
	} else {
		echo $res;
	}
}

function modificarAsesor($serviciosCatalogos) {
  $id         = $_POST['id'];
  $nombre    = $_POST['nombre'];
  $res = $serviciosCatalogos->modificarAsesor($id,$nombre);

   if ($res == true) {
      echo '';
   } else {
      echo 'Hubo un error al modificar datos de asesor';
   }
}

function insertarAsesor($serviciosCatalogos) {
  $nombre =  $_POST['nombre'];  

  $res = $serviciosCatalogos->insertarAsesor($nombre);
  if ((integer)$res > 0) {
    echo '';
  } else {
    echo $res;
  }
}

function modificarUDI($serviciosCatalogos) {
  $id = $_POST['id'];
  $nombre = $_POST['descripcion'];
  $res = $serviciosCatalogos->modificarUDI($id,$nombre);
   if ($res == true) {
    if($res != 1)
      echo $res;
    else
      echo '';

   } else {
      echo $res;
   }
}

function insertarUDI($serviciosCatalogos) {
  $valor =  $_POST['descripcion'];  

  $res = $serviciosCatalogos->insertarUDI($valor);
  if ((integer)$res > 0) {
    echo '';
  } else {
    echo $res;
  }
}


function aprobarContratoGlobalEmpresa($serviciosSolicitudes){
  // Actualizamos el status delc ontrato, puede ser aprobada o rechazada por el empleador  
  $res = $serviciosSolicitudes->aprobarContratoGlobalEmpresa();
  echo $res;  
  //return $res["error"];
  } 

function autorizarhistorialCrediticio($serviciosSolicitudes){
  $id = $_POST['idcontratoglobal'];
  $nip = $_POST['NIP'];  
  $res = $serviciosSolicitudes->autorizarhistorialCrediticio($id, $nip);
  echo $res;  
  //return $res["error"];
}  



function insertarRiesgoNivel($serviciosCatalogos) {
  $descripcion =  $_POST['descripcion'];
  $valor =  $_POST['valor'];
  $activo =  $_POST['activo'];
  $res = $serviciosCatalogos->insertarRiesgoNivel($descripcion, $valor, $activo);
  if ((integer)$res > 0) {
    echo '';
  } else {
    echo $res;
  }
}


function insertarRiesgoElemento($serviciosCatalogos) {
  $descripcion =  $_POST['descripcion'];
  $peso =  $_POST['peso'];
  $res = $serviciosCatalogos->insertarRiesgoElemento($descripcion, $peso);
  if ((integer)$res > 0) {
    echo '';
  } else {
    echo $res;
  }
}

function modificarRiesgoElemento($serviciosCatalogos) {
  $id = $_POST['id'];
  $descripcion = $_POST['descripcion'];
  $peso = $_POST['peso'];
  $res = $serviciosCatalogos->modificarRiesgoElemento($id,$descripcion, $peso);
   if ($res == true) {
    if($res != 1)
      echo $res;
    else
      echo '';

   } else {
      echo $res;
   }
}


function modificarRiesgoNivel($serviciosCatalogos) {
  $id = $_POST['id'];
  $descripcion = $_POST['descripcion'];
  $valor = $_POST['valor'];
  $activo = $_POST['activo'];
  $res = $serviciosCatalogos->modificarRiesgoNivel($id,$descripcion, $valor, $activo);
   if ($res == true) {
    if($res != 1)
      echo $res;
    else
      echo '';

   } else {
      echo $res;
   }
}


function insertarRiesgoIndicador($serviciosCatalogos) {
  $descripcion =  $_POST['descripcion'];
  $peso =  $_POST['peso'];
  $refelemento =  $_POST["refriesgoelemento"];
  $variablesql =  $_POST["variablesql"];
  $maximo =  $_POST['maximo'];
  $minimo =  $_POST['minimo'];
  $res = $serviciosCatalogos->insertarRiesgoIndicador($descripcion, $peso, $maximo, $minimo, $refelemento, $variablesql);
  if ((integer)$res > 0) {
    echo '';
  } else {
    echo $res;
  }
}


function modificarRiesgoIndicador($serviciosCatalogos) {
  $id = $_POST['id'];
  $descripcion = $_POST['descripcion'];
  $refelemento =  $_POST["refriesgoelemento"];
  $peso =  $_POST['peso'];
  $maximo =  $_POST['maximo'];
  $minimo =  $_POST['minimo'];
  $res = $serviciosCatalogos->modificarRiesgoIndicador($id,$descripcion, $peso, $maximo, $minimo, $refelemento);
   if ($res == true) {
    if($res != 1)
      echo $res;
    else
      echo '';
   } else {
      echo $res;
   }
}

function insertarRiesgoVariable($serviciosCatalogos) {
 $descripcion = $_POST['descripcion'];
  $peso =  $_POST['peso'];
  $refindicador =  $_POST["refriesgoindicador"];
  $tipoVariable =  $_POST["tipovariable"];
  $valoresvariable =  $_POST["valoresvariable"];
  $activo =  $_POST["activo"];
  $res = $serviciosCatalogos->insertarRiesgoVariable($descripcion,$peso,$refindicador,$tipoVariable, $valoresvariable,$activo);
  if ((integer)$res > 0) {
    echo '';
  } else {
    echo $res;
  }
}

function modificarRiesgoVariable($serviciosCatalogos) {
  $id = $_POST['id'];
  $descripcion = $_POST['descripcion'];
  $peso = $_POST['peso'];
  $tipovariable = $_POST['tipovariable'];
  $valoresvariable = $_POST['valoresvariable'];
  $activo = $_POST['activo'];
  $res = $serviciosCatalogos->modificarRiesgoVariable($id,$descripcion, $peso, $tipovariable, $valoresvariable,$activo );
   if ($res == true) {
    if($res != 1)
      echo $res;
    else
      echo '';
   } else {
      echo $res;
   }
}

function modificarVencimientoINE($serviciosSolicitudes){
  $id = $_POST['idContratoGlobal'];
  $fecha = $_POST['fecha'];
  $res = $serviciosSolicitudes->actualizarVigenciaINE($id, $fecha);
  echo $res;
 }

function firmarDocumentosContratoGlobal($serviciosSolicitudes){
  $id = $_POST['idcontratoglobal'];
  $nip = $_POST['NIP'];  
  $res = $serviciosSolicitudes->firmarDocumentosContratoGlobal($id, $nip);
  echo $res;  
}

function generarNuevoToken($serviciosToken){
   $id = $_POST['idContratoGlobal'];
   $refTipo = $_POST['tipoToken'];
   $res =  $serviciosToken->generarNuevoTokenParaCliente( $id, $refTipo );
   echo $res;

}


?>
