<?php

session_start();

include ('../includes/funciones.php');
include ('../includes/funcionesReferencias.php');
include ('../includes/funcionesUsuarios.php');

$serviciosFunciones = new Servicios();
$serviciosReferencias 	= new ServiciosReferencias();
$serviciosUsuarios  		= new ServiciosUsuarios();

$tabla = $_GET['tabla'];
$draw = $_GET['sEcho'];
$start = $_GET['iDisplayStart'];
$length = $_GET['iDisplayLength'];
$busqueda = $_GET['sSearch'];


$idcliente = 0;

if (isset($_GET['idcliente'])) {
	$idcliente = $_GET['idcliente'];
} else {
	$idcliente = 0;
}


$referencia1 = 0;

if (isset($_GET['referencia1'])) {
	$referencia1 = $_GET['referencia1'];
} else {
	$referencia1 = 0;
}

$colSort = (integer)$_GET['iSortCol_0'] + 2;
$colSortDir = $_GET['sSortDir_0'];

function armarAcciones($id,$label='',$class,$icon) {
	$cad = "";

	for ($j=0; $j<count($class); $j++) {
		$cad .= '<button type="button" class="btn '.$class[$j].' btn-circle waves-effect waves-circle waves-float '.$label[$j].'" id="'.$id.'">
				<i class="material-icons">'.$icon[$j].'</i>
			</button> ';
	}

	return $cad;
}

function armarAccionesDropDown($id,$label='',$class,$icon) {
	$cad = '<div class="btn-group">
					<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						 Accions <span class="caret"></span>
					</button>
					<ul class="dropdown-menu">';

	for ($j=0; $j<count($class); $j++) {
		$cad .= '<li><a href="javascript:void(0);" id="'.$id.'" class=" waves-effect waves-block '.$label[$j].'">'.$icon[$j].'</a></li>';

	}

	$cad .= '</ul></div>';

	return $cad;
}

switch ($tabla) {
	case 'asesores':
		$filtro = "where p.nombre like '%_busqueda%' or p.apellidopaterno like '%_busqueda%' or p.apellidomaterno like '%_busqueda%' or p.email like '%_busqueda%' or p.idclienteinbursa like '%_busqueda%' or p.claveinterbancaria like '%_busqueda%' or p.claveasesor like '%_busqueda%' or  DATE_FORMAT( p.fechaalta, '%Y-%m-%d') like '%_busqueda%'";

		$consulta = 'select
			p.idasesor,
			p.nombre,
			p.apellidopaterno,
			p.apellidomaterno,
			p.email,
			p.idclienteinbursa,
			p.claveinterbancaria,
			p.claveasesor,
			p.fechaalta
		from dbasesores p
		';
		if ($_SESSION['idroll_sahilices'] == 3) {
			$consulta .= ' inner join dbusuarios usu ON usu.idusuario = p.refusuarios
			inner join dbpostulantes pp on pp.refusuarios = usu.idusuario
			inner join dbreclutadorasores rrr on rrr.refpostulantes = pp.idpostulante and rrr.refusuarios = '.$_SESSION['usuaid_sahilices'].' ';
			$res = $serviciosReferencias->traerAsesoresPorGerente($_SESSION['usuaid_sahilices']);
		} else {
			if ($_SESSION['idroll_sahilices'] == 7) {
				$consulta .= ' inner join dbusuarios usu ON p.refusuarios = '.$_SESSION['usuaid_sahilices'].' ';
				$res = $serviciosReferencias->traerAsesoresPorUsuario($_SESSION['usuaid_sahilices']);
			} else {
				$consulta .= ' inner join dbusuarios usu ON usu.idusuario = p.refusuarios ';
				$res = $serviciosReferencias->traerAsesores();
			}

		}


		$resAjax = $serviciosReferencias->traerGrillaAjax($length, $start, $busqueda,$colSort,$colSortDir,$filtro,$consulta);


		switch ($_SESSION['idroll_sahilices']) {
			case 1:
				$label = array('btnModificar','btnEliminar');
				$class = array('bg-amber','bg-red');
				$icon = array('Modificar','Eliminar');
				$indiceID = 0;
				$empieza = 1;
				$termina = 8;
			break;
			case 2:
				$label = array('btnModificar');
				$class = array('bg-amber');
				$icon = array('Modificar');
				$indiceID = 0;
				$empieza = 1;
				$termina = 8;
			break;
			case 3:
				$label = array('btnModificar');
				$class = array('bg-amber');
				$icon = array('Modificar');
				$indiceID = 0;
				$empieza = 1;
				$termina = 8;
			break;
			case 4:
				$label = array('btnModificar');
				$class = array('bg-amber');
				$icon = array('Modificar');
				$indiceID = 0;
				$empieza = 1;
				$termina = 8;
			break;
			case 5:
				$label = array('btnModificar');
				$class = array('bg-amber');
				$icon = array('Modificar');
				$indiceID = 0;
				$empieza = 1;
				$termina = 8;
			break;
			case 6:
				$label = array('btnModificar');
				$class = array('bg-amber');
				$icon = array('Modificar');
				$indiceID = 0;
				$empieza = 1;
				$termina = 8;
			break;

			default:
				$label = array();
				$class = array();
				$icon = array();
				$indiceID = 0;
				$empieza = 1;
				$termina = 8;
			break;
		}
	break;

	case 'asociados':


		$resAjax = $serviciosReferencias->traerAsociadosajax($length, $start, $busqueda,$colSort,$colSortDir);
		$res = $serviciosReferencias->traerAsociados();
		$label = array('btnModificar','btnEliminar','btnDocumentacion');
		$class = array('bg-amber','bg-red','bg-blue');
		$icon = array('Modificar','Eliminar','Documentaciones');
		$indiceID = 0;
		$empieza = 1;
		$termina = 7;

	break;
	case 'postulantes':


		$filtro = "where rr.idasesor is null and (p.nombre like '%_busqueda%' or p.apellidopaterno like '%_busqueda%' or p.apellidomaterno like '%_busqueda%' or p.email like '%_busqueda%' or p.telefonomovil like '%_busqueda%' or ep.estadopostulante like '%_busqueda%' or est.estadocivil like '%_busqueda%' or DATE_FORMAT( p.fechacrea, '%Y-%m-%d') like '%_busqueda%')";

		$pre = "where rr.idasesor is null";

		$consulta = 'select
			p.idpostulante,
			p.nombre,
			p.apellidopaterno,
			p.apellidomaterno,
			p.email,
			p.codigopostal,
			p.fechacrea,
			ep.estadopostulante,
			p.telefonomovil,
			est.estadocivil,
			p.curp,
			p.rfc,
			p.ine,
			p.fechanacimiento,
			p.sexo,
			p.refescolaridades,
			p.refestadocivil,
			p.nacionalidad,
			p.telefonocasa,
			p.telefonotrabajo,
			p.refestadopostulantes,
			p.urlprueba,
			p.fechamodi,
			p.usuariocrea,
			p.usuariomodi,
			p.refasesores,
			p.comision,
			p.refusuarios,
			p.refsucursalesinbursa
		from dbpostulantes p
		inner join dbusuarios usu ON usu.idusuario = p.refusuarios
		inner join tbescolaridades esc ON esc.idescolaridad = p.refescolaridades
		inner join tbestadocivil est ON est.idestadocivil = p.refestadocivil
		inner join tbestadopostulantes ep ON ep.idestadopostulante = p.refestadopostulantes
		left join dbasesores rr on rr.refusuarios = p.refusuarios ';
		if ($_SESSION['idroll_sahilices'] == 3) {
			$consulta .= 'inner join dbreclutadorasores rrr on rrr.refpostulantes = p.idpostulante and rrr.refusuarios = '.$_SESSION['usuaid_sahilices'].' ';
			$res = $serviciosReferencias->traerPostulantesPorGerente($_SESSION['usuaid_sahilices']);
		} else {
			$res = $serviciosReferencias->traerPostulantes();
		}

		$resAjax = $serviciosReferencias->traerGrillaAjax($length, $start, $busqueda,$colSort,$colSortDir,$filtro,$consulta,$pre);


		switch ($_SESSION['idroll_sahilices']) {
			case 1:
				$label = array('btnVer','btnModificar','btnEliminar','btnEliminarDefinitivo');
				$class = array('bg-blue','bg-amber','bg-red','bg-red');
				$icon = array('Ver','Modificar','Eliminar','Eliminar Def.');
				$indiceID = 0;
				$empieza = 1;
				$termina = 8;
			break;
			case 2:
				$label = array('btnVer','btnModificar');
				$class = array('bg-blue','bg-amber');
				$icon = array('Ver','Modificar');
				$indiceID = 0;
				$empieza = 1;
				$termina = 7;
			break;
			case 3:
				$label = array('btnVer','btnModificar');
				$class = array('bg-blue','bg-amber');
				$icon = array('Ver','Modificar');
				$indiceID = 0;
				$empieza = 1;
				$termina = 7;
			break;
			case 4:
				$label = array('btnVer','btnModificar');
				$class = array('bg-blue','bg-amber');
				$icon = array('Ver','Modificar');
				$indiceID = 0;
				$empieza = 1;
				$termina = 7;
			break;
			case 5:
				$label = array('btnVer','btnModificar');
				$class = array('bg-blue','bg-amber');
				$icon = array('Ver','Modificar');
				$indiceID = 0;
				$empieza = 1;
				$termina = 7;
			break;
			case 6:
				$label = array('btnVer','btnModificar');
				$class = array('bg-blue','bg-amber');
				$icon = array('Ver','Modificar');
				$indiceID = 0;
				$empieza = 1;
				$termina = 7;
			break;

			default:
				// code...
				break;
		}


	break;
	case 'entrevistas':

		$id = $_GET['id'];
		$idestado = $_GET['idestado'];

		$resultado = $serviciosReferencias->traerPostulantesPorId($id);

		if ($busqueda == '') {
			$colSort = 'e.fechacrea';
			$colSortDir = 'desc';
		}

		$consulta = 'select
		e.identrevista,
		e.entrevistador,
		e.fecha,
		e.domicilio,
		coalesce( pp.codigo, e.codigopostal) as codigo,
		ep.estadopostulante,
		est.estadoentrevista,
		e.fechacrea,

		e.refestadopostulantes,
		e.refestadoentrevistas,
		e.fechamodi,
		e.usuariocrea,
		e.usuariomodi,
		e.refpostulantes
		from dbentrevistas e
		inner join dbpostulantes pos ON e.refpostulantes = pos.idpostulante and pos.idpostulante = '.$id.'
		inner join tbestadopostulantes ep ON ep.idestadopostulante = e.refestadopostulantes
		left join tbentrevistasucursales et on et.identrevistasucursal = e.refentrevistasucursales
		left join postal pp on pp.id = et.refpostal
		inner join tbestadoentrevistas est ON est.idestadoentrevista = e.refestadoentrevistas';

		if ($idestado == '') {
			$filtro = "where e.entrevistador like '%_busqueda%' or cast(e.fecha as unsigned) like '%_busqueda%' or e.domicilio like '%_busqueda%' or e.codigopostal like '%_busqueda%' or est.estadoentrevista like '%_busqueda%'";

			$resAjax = $serviciosReferencias->traerGrillaAjax($length, $start, $busqueda,$colSort,$colSortDir,$filtro,$consulta);

			$res = $serviciosReferencias->traerEntrevistasPorPostulante($id);

			$termina = 7;
		} else {
			$filtro = "where e.refestadopostulantes = ".$idestado." and (e.entrevistador like '%_busqueda%' or cast(e.fecha as unsigned) like '%_busqueda%' or e.domicilio like '%_busqueda%' or e.codigopostal like '%_busqueda%' or est.estadoentrevista like '%_busqueda%')";

			$pre = " where e.refestadopostulantes = ".$idestado;
			//die(var_dump($filtro));

			$resAjax = $serviciosReferencias->traerGrillaAjax($length, $start, $busqueda,$colSort,$colSortDir,$filtro,$consulta,$pre);

			$termina = 6;

			$res = $serviciosReferencias->traerEntrevistasPorPostulanteEstado($id,mysql_result($resultado,0,'refestadopostulantes'));
		}



		switch ($_SESSION['idroll_sahilices']) {
			case 1:
				$label = array('btnModificar','btnEliminar');
				$class = array('bg-amber','bg-red');
				$icon = array('create','delete');
				$indiceID = 0;
				$empieza = 1;
			break;
			case 2:
				$label = array();
				$class = array();
				$icon = array();
				$indiceID = 0;
				$empieza = 1;
				$termina = 6;
			break;
			case 3:
				$label = array('btnModificar','btnEliminar');
				$class = array('bg-amber','bg-red');
				$icon = array('create','delete');
				$indiceID = 0;
				$empieza = 1;
			break;
			case 4:
				$label = array('btnModificar','btnEliminar');
				$class = array('bg-amber','bg-red');
				$icon = array('create','delete');
				$indiceID = 0;
				$empieza = 1;
			break;
			case 5:
				$label = array('btnModificar','btnEliminar');
				$class = array('bg-amber','bg-red');
				$icon = array('create','delete');
				$indiceID = 0;
				$empieza = 1;
			break;
			case 6:
				$label = array();
				$class = array();
				$icon = array();
				$indiceID = 0;
				$empieza = 1;
				$termina = 6;
			break;
			case 7:
				$label = array();
				$class = array();
				$icon = array();
				$termina = 6;
				$indiceID = 0;
				$empieza = 1;
			break;

			default:
				// code...
				break;
		}


		break;
	case 'referentes':
		$resAjax = $serviciosReferencias->traerReferentesajax($length, $start, $busqueda,$colSort,$colSortDir);
		$res = $serviciosReferencias->traerReferentes();
		$label = array('btnModificar','btnEliminar');
		$class = array('bg-amber','bg-red');
		$icon = array('create','delete');
		$indiceID = 0;
		$empieza = 1;
		$termina = 6;

	break;
	case 'referentescomisiones':
		$resReferente = $serviciosReferencias->traerReferentesPorUsuario($_SESSION['usuaid_sahilices']);

		if (mysql_num_rows($resReferente) > 0) {
			$idreferente = mysql_result($resReferente,0,'idreferente');
		} else {
			$idreferente = 0;
		}

		$resAjax = $serviciosReferencias->traerComisionesReferentesajax($length, $start, $busqueda,$colSort,$colSortDir,$idreferente);
		$res = $serviciosReferencias->traerComisionesReferentes($idreferente);
		$label = array();
		$class = array();
		$icon = array();
		$indiceID = 0;
		$empieza = 1;
		$termina = 4;

	break;
	case 'oportunidades':

		if ($_SESSION['idroll_sahilices'] == 3) {

			$resAjax = $serviciosReferencias->traerOportunidadesajaxPorUsuario($length, $start, $busqueda,$colSort,$colSortDir,$_SESSION['usuaid_sahilices']);
			$res = $serviciosReferencias->traerOportunidadesPorUsuario($_SESSION['usuaid_sahilices']);
			$label = array('btnModificar','btnEntrevista');
			$class = array('bg-amber','bg-green');
			$icon = array('create','assignment');
		} else {
			if ($_SESSION['idroll_sahilices'] == 9) {
				$resReferentes 	= $serviciosReferencias->traerReferentesPorUsuario($_SESSION['usuaid_sahilices']);
				// traigo el recomendador o referente a traves del usuario para filtrar
				$resAjax = $serviciosReferencias->traerOportunidadesajaxPorRecomendador($length, $start, $busqueda,$colSort,$colSortDir, mysql_result($resReferentes,0,0));
				$res = $serviciosReferencias->traerOportunidadesPorRecomendador(mysql_result($resReferentes,0,0));
				$label = array('btnModificar','btnEliminar');
				$class = array('bg-amber','bg-red');
				$icon = array('create','delete');
			} else {
				$responsableComercial = $_GET['sSearch_0'];
				$resAjax = $serviciosReferencias->traerOportunidadesajax($length, $start, $busqueda,$colSort,$colSortDir,$responsableComercial);
				$res = $serviciosReferencias->traerOportunidadesGrid($responsableComercial);
				$label = array('btnModificar','btnEliminar');
				$class = array('bg-amber','bg-red');
				$icon = array('create','delete');
			}

		}

		$indiceID = 0;
		$empieza = 1;
		$termina = 11;

		break;
	case 'oportunidadeshistorico':

		if ($_SESSION['idroll_sahilices'] == 3) {
			$resAjax = $serviciosReferencias->traerOportunidadesajaxPorUsuarioHistorico($length, $start, $busqueda,$colSort,$colSortDir,$_SESSION['usuaid_sahilices']);
			$res = $serviciosReferencias->traerOportunidadesPorUsuarioEstadoH($_SESSION['usuaid_sahilices'],'3');
			$label = array();
			$class = array();
			$icon = array();
		} else {
			if ($_SESSION['idroll_sahilices'] == 9) {
				$resReferentes 	= $serviciosReferencias->traerReferentesPorUsuario($_SESSION['usuaid_sahilices']);
				// traigo el recomendador o referente a traves del usuario para filtrar
				$resAjax = $serviciosReferencias->traerOportunidadesajaxPorRecomendadorHistorico($length, $start, $busqueda,$colSort,$colSortDir,mysql_result($resReferentes,0,0));
				$res = $serviciosReferencias->traerOportunidadesPorRecomendadorEstadoH(mysql_result($resReferentes,0,0),'3');
				$label = array();
				$class = array();
				$icon = array();
			} else {
				$resAjax = $serviciosReferencias->traerOportunidadesajaxPorHistorico($length, $start, $busqueda,$colSort,$colSortDir);
				$res = $serviciosReferencias->traerOportunidadesPorEstadoH('3');
				$label = array();
				$class = array();
				$icon = array();
			}

		}


		$indiceID = 0;
		$empieza = 1;
		$termina = 11;
	break;
	case 'relaciones':
		$resAjax = $serviciosReferencias->traerReclutadorasoresajax($length, $start, $busqueda,$colSort,$colSortDir);
		$res = $serviciosReferencias->traerReclutadorasores();
		$label = array('btnModificar','btnEliminar');
		$class = array('bg-amber','bg-red');
		$icon = array('create','delete');

		$indiceID = 0;
		$empieza = 1;
		$termina = 3;
	break;
	case 'entrevistaoportunidades':
		if ($_SESSION['idroll_sahilices'] == 3) {
			$resAjax = $serviciosReferencias->traerEntrevistaoportunidadesPorUsuarioajax($length, $start, $busqueda,$colSort,$colSortDir,$_SESSION['usuaid_sahilices']);
			$res = $serviciosReferencias->traerEntrevistaoportunidadesPorUsuario($_SESSION['usuaid_sahilices']);
		} else {
			$resAjax = $serviciosReferencias->traerEntrevistaoportunidadesajax($length, $start, $busqueda,$colSort,$colSortDir);
			$res = $serviciosReferencias->traerEntrevistaoportunidades();
		}

		$label = array('btnModificar','btnEliminar');
		$class = array('bg-amber','bg-red');
		$icon = array('create','delete');
		$indiceID = 0;
		$empieza = 1;
		$termina = 4;

		break;
	case 'entrevistasucursales':
		$resAjax = $serviciosReferencias->traerEntrevistasucursalesajax($length, $start, $busqueda,$colSort,$colSortDir);
		$res = $serviciosReferencias->traerEntrevistasucursales();
		$label = array('btnModificar','btnEliminar');
		$class = array('bg-amber','bg-red');
		$icon = array('create','delete');
		$indiceID = 0;
		$empieza = 1;
		$termina = 6;

		break;
	case 'tipoubicacion':
		$resAjax = $serviciosReferencias->traerTipoubicacionajax($length, $start, $busqueda,$colSort,$colSortDir);
		$res = $serviciosReferencias->traerTipoubicacion();
		$label = array('btnModificar','btnEliminar');
		$class = array('bg-amber','bg-red');
		$icon = array('create','delete');
		$indiceID = 0;
		$empieza = 1;
		$termina = 1;

	break;
	case 'ubicaciones':
		$resAjax = $serviciosReferencias->traerUbicacionesajax($length, $start, $busqueda,$colSort,$colSortDir);
		$res = $serviciosReferencias->traerUbicaciones();
		$label = array('btnModificar','btnEliminar');
		$class = array('bg-amber','bg-red');
		$icon = array('create','delete');
		$indiceID = 0;
		$empieza = 1;
		$termina = 5;

	break;
	case 'tarifes':
		$resAjax = $serviciosReferencias->traerTarifasajax($length, $start, $busqueda,$colSort,$colSortDir);
		$res = $serviciosReferencias->traerTarifas();
		$label = array('btnModificar','btnEliminar');
		$class = array('bg-amber','bg-red');
		$icon = array('create','delete');
		$indiceID = 0;
		$empieza = 1;
		$termina = 4;

	break;
	case 'periodes':
		$resAjax = $serviciosReferencias->traerPeriodosajax($length, $start, $busqueda,$colSort,$colSortDir);
		$res = $serviciosReferencias->traerPeriodos();
		$label = array('btnModificar','btnEliminar');
		$class = array('bg-amber','bg-red');
		$icon = array('create','delete');
		$indiceID = 0;
		$empieza = 1;
		$termina = 4;

	break;
	case 'usuarios':

		//die(var_dump($_GET['sSearch_0']));

		$resAjax = $serviciosUsuarios->traerUsuariosajax($length, $start, $busqueda,$colSort,$colSortDir, $_GET['sSearch_0']);
		if ($_GET['sSearch_0'] != '') {
			$res = $serviciosUsuarios->traerUsuariosPorRol($_GET['sSearch_0']);
		} else {
			$res = $serviciosUsuarios->traerUsuarios();
		}

		$label = array('btnModificar','btnEliminar');
		$class = array('bg-amber','bg-red');
		$icon = array('create','delete');
		$indiceID = 0;
		$empieza = 1;
		$termina = 5;

	break;

	default:
		// code...
		break;
}


$cantidadFilas = mysql_num_rows($res);


header("content-type: Access-Control-Allow-Origin: *");

$ar = array();
$arAux = array();
$cad = '';
$id = 0;
	while ($row = mysql_fetch_array($resAjax)) {
		//$id = $row[$indiceID];
		// forma local utf8_decode
		for ($i=$empieza;$i<=$termina;$i++) {
			array_push($arAux, ($row[$i]));
		}

		if (($tabla == 'postulantes') || ($tabla == 'asesores') || ($tabla == 'asociados')) {
			array_push($arAux, armarAccionesDropDown($row[0],$label,$class,$icon));
		} else {
			array_push($arAux, armarAcciones($row[0],$label,$class,$icon));
		}


		array_push($ar, $arAux);

		$arAux = array();
		//die(var_dump($ar));
	}

$cad = substr($cad, 0, -1);

$data = '{ "sEcho" : '.$draw.', "iTotalRecords" : '.$cantidadFilas.', "iTotalDisplayRecords" : 10, "aaData" : ['.$cad.']}';

//echo "[".substr($cad,0,-1)."]";
echo json_encode(array(
			"draw"            => $draw,
			"recordsTotal"    => $cantidadFilas,
			"recordsFiltered" => $cantidadFilas,
			"data"            => $ar
		));

?>
