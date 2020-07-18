<?php

session_start();

/*include ('../includes/funciones.php');
include ('../includes/funcionesReferencias.php');
include ('../includes/funcionesUsuarios.php');*/

include '../class_include.php';

$serviciosFunciones = new Servicios();
$serviciosReferencias 	= new ServiciosReferencias();
$serviciosUsuarios  		= new ServiciosUsuarios();
$serviciosCatalogos = new ServiciosCatalogos();

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

function armarAccionesCG($id,$label='',$class,$icon) {
	$cad = "";
	for ($j=0; $j<count($class); $j++) {
		$cad .= '<a href="editaContratoGlobal.php?idContratoGlobal='.$id.'" target="_blank"> <button type="button" class="btn '.$class[$j].' btn-circle waves-effect waves-circle waves-float '.$label[$j].'" id="'.$id.'">
				<i class="material-icons">'.$icon[$j].'</i>
			</button></a> ';
	}

	return $cad;
}


function armarLinkCirculo($id,$label,$class,$icon,$opc){
	$cad = '';
	$desc = '';
	$desc = ($opc==1)?'Consultar autorizacion':'Autorizacion pendiente';
	for ($j=0; $j<count($class); $j++) {
		$cad .= '<a href="../../contrato/cliente/AutorizacionCirculoCredito.php?idContratoGlobal='.$id.'" target="_blank"> '.$desc .'</a> ';
		}
		return $cad;
}

function armarLinkOtrosProductos($id,$label,$class,$icon,$credito,$servicios){
	$cad = '';
	$desc1 = 'Otro credito';
	$desc2 = 'Otro servicio';
	$cad .= '<small><a href="../../contrato/listado/otorgaCreditoAutomatico.php?idContratoGlobal='.$id.'" target="_blank"> '.$desc1 .'</a></small> ';
	$cad .= '<br><small><a href="../../contrato/cliente/AutorizacionCirculoCredito.php?idContratoGlobal='.$id.'" target="_blank"> '.$desc2 .'</a></small> ';

	return $cad;


}

function armarLinkFirmaContrato($id,$label,$class,$icon,$tipoContrato){
	$cad = "";
	if($tipoContrato == 1){
		$cad .= '<a href="../../contrato/cliente/firmaDigitalDocumentos.php?idCG='.$id.'" target="_blank"> Firmar ahora</a> ';

	}
	return $cad;
}


function armarLinkVerContrato($id,$label,$class,$icon,$tipoContrato){
	$cad = "";
	if($tipoContrato == 1){
		$cad .= '<a href="../../contrato/cliente/verContrato.php?idCG='.$id.'" target="_blank"> Ver contrato</a> ';

	}
	return $cad;

}

function armarAccionesCGCliente($id,$label='',$class,$icon) {
	$cad = "";
	for ($j=0; $j<count($class); $j++) {
		$cad .= '<a href="../?id='.$id.'" target="_blank"> <button type="button" class="btn '.$class[$j].' btn-circle waves-effect waves-circle waves-float '.$label[$j].'" id="'.$id.'">
				<i class="material-icons">'.$icon[$j].'</i>
			</button></a> ';
	}

	return $cad;
}

function armarAccionesEmpresa($id,$label='',$class,$icon) {
	$cad = "";
	for ($j=0; $j<count($class); $j++) {
		$cad .= ' <button type="button" class="btn  btnModificar '.$class[$j].' btn-circle waves-effect waves-circle waves-float '.$label[$j].'" id="'.$id.'">
				<i class="material-icons">'.$icon[$j].'</i>
			</button> ';
	}

	return $cad;
}

function armarAccionesEmpresaTemp($id,$label='',$class,$icon) {
	$cad = "";
	for ($j=0; $j<count($class); $j++) {
		$cad .= '<a href="../listadoaprobar/editaContratoGlobalEmpresa.php?idContratoGlobal='.$id.'" target="_blank"> <button type="button" class="btn '.$class[$j].' btn-circle waves-effect waves-circle waves-float '.$label[$j].'" id="'.$id.'">
				<i class="material-icons">'.$icon[$j].'</i>
			</button></a> ';
	}

	return $cad;
}


function armarAcciones($id,$label='',$class,$icon) {
	$cad = "";

	for ($j=0; $j<count($class); $j++) {
		$cad .= '<button type="button" class="btn '.$class[$j].' btn-circle waves-effect waves-circle waves-float '.$label[$j].'" id="'.$id.'">
				<i class="material-icons">'.$icon[$j].'</i>
			</button> ';
	}

	return $cad;
}


function armarAccionesTemp($id,$label='',$class,$icon) {
	$cad = "";

	for ($j=0; $j<count($class); $j++) {
		$cad .= '<a href="../listado/editaContratoGlobal.php?idContratoGlobal='.$id.'"> <button type="button" class="btn '.$class[$j].' btn-circle waves-effect waves-circle waves-float '.$label[$j].'" id="'.$id.'">
				<i class="material-icons">'.$icon[$j].'</i>
			</button></a> ';
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
	case 'asesores2':
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
			
			
			case 4:
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
			
			case 3:
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
			

			default:
				// code...
				break;
		}


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
	
	
	
	
	
	case 'usuarios':
		$resAjax = $serviciosUsuarios->traerUsuariosajax($length, $start, $busqueda,$colSort,$colSortDir, $_GET['sSearch_0']);
		if ($_GET['sSearch_0'] != '') {			
			$res = $serviciosUsuarios->traerUsuariosPorRol($_GET['sSearch_0']);
		} else {			
			$res = $serviciosUsuarios->traerUsuarios();
		}

		$label = array('btnModificar');
		$class = array('bg-info');
		$icon = array('editar');
		$indiceID = 0;
		$empieza = 1;
		$termina = 5;

	break;

	case 'contratosGlobales':
		// muestra el listado de todos los contratos globales para administracion
		$resAjax = $serviciosUsuarios->traerContratosajax($length, $start, $busqueda,'idcontratoglobal','DESC', $_GET['sSearch_0']);
		if ($_GET['sSearch_0'] != '') {	
		
			$res = $serviciosUsuarios->traerContratorPorStatus($_GET['sSearch_0']);
		} else {		
			
			$res = $serviciosUsuarios->traerContratos();
		}

		$label = array('btnModificar');
		$class = array('bg-info');
		$icon = array('editar');
		$indiceID = 0;
		$empieza = 1;
		$termina = 9;

	break;

	case 'contratosGlobalesCliente':
		// muestra el listado de los contratos de un cliente
		$resAjax = $serviciosUsuarios->traerContratosClienteajax($length, $start, $busqueda,$colSort,$colSortDir, $_GET['sSearch_0']);
		if ($_GET['sSearch_0'] != '') {	
		
			$res = $serviciosUsuarios->traerContratorPorStatus($_GET['sSearch_0']);
		} else {		
			
			$res = $serviciosUsuarios->traerContratosCliente();
		}

		$label = array('btnModificar');
		$class = array('bg-info');
		$icon = array('editar');
		$indiceID = 0;
		$empieza = 1;
		$termina = 6;

	break;

	case 'contratosGlobalesIncompletos':
		$resAjax = $serviciosUsuarios->traerContratosajaxIncompletos($length, $start, $busqueda,$colSort,$colSortDir, $_GET['sSearch_0']);
		if ($_GET['sSearch_0'] != '') {	
		
			$res = $serviciosUsuarios->traerContratorPorStatus($_GET['sSearch_0']);
		} else {		
			
			$res = $serviciosUsuarios->traerContratosIncompletos();
		}

		$label = array('btnModificar');
		$class = array('bg-info');
		$icon = array('editar');
		$indiceID = 0;
		$empieza = 1;
		$termina = 7;

	break;

	case 'contratosGlobalesRechazados':
		$resAjax = $serviciosUsuarios->traerContratosajaxRechazados($length, $start, $busqueda,$colSort,$colSortDir, $_GET['sSearch_0']);
		if ($_GET['sSearch_0'] != '') {	
		
			$res = $serviciosUsuarios->traerContratorPorStatus($_GET['sSearch_0']);
		} else {		
			
			$res = $serviciosUsuarios->traerContratosRechazados();
		}

		$label = array('btnModificar');
		$class = array('bg-info');
		$icon = array('editar');
		$indiceID = 0;
		$empieza = 1;
		$termina = 8;

	break;

	case 'contratosGlobalesAbandonados':
		$resAjax = $serviciosUsuarios->traerContratosajaxAbandonados($length, $start, $busqueda,$colSort,$colSortDir, $_GET['sSearch_0']);
		if ($_GET['sSearch_0'] != '') {	
		
			$res = $serviciosUsuarios->traerContratorPorStatus($_GET['sSearch_0']);
		} else {		
			
			$res = $serviciosUsuarios->traerContratosIncompletos();
		}

		$label = array('btnModificar');
		$class = array('bg-info');
		$icon = array('editar');
		$indiceID = 0;
		$empieza = 1;
		$termina = 7;

	break;

	case 'tramitesPLD':
		$resAjax = $serviciosUsuarios->traerContratosPLDajax($length, $start, $busqueda,$colSort,$colSortDir, $_GET['sSearch_0']);
		if ($_GET['sSearch_0'] != '') {	
		
			$res = $serviciosUsuarios->traerContratorPorStatus($_GET['sSearch_0']);
		} else {		
			
			$res = $serviciosUsuarios->traerContratosPLD();
		}

		$label = array('btnModificar');
		$class = array('bg-info');
		$icon = array('editar');
		$indiceID = 0;
		$empieza = 1;
		$termina = 8;

	break;

	case 'rechazo':
		$resAjax = $serviciosCatalogos->traerCatalogoRechazoajax($length, $start, $busqueda,'idrechazocausa',$colSortDir, $_GET['sSearch_0']);
		if ($_GET['sSearch_0'] != '') {	
		
			$res = $serviciosUsuarios->traerContratorPorStatus($_GET['sSearch_0']);
		} else {		
			
			$res = $serviciosCatalogos->traerCatalogoRechazo();
		}

		$label = array('btnModificar');
		$class = array('bg-info');
		$icon = array('editar');
		$indiceID = 0;
		$empieza = 0;
		$termina = 1;

	break;

	case 'asesores':
		$resAjax = $serviciosCatalogos->traerCatalogoAsesoresajax($length, $start, $busqueda,'idasesor',$colSortDir, $_GET['sSearch_0']);
		if ($_GET['sSearch_0'] != '') {	
		
			$res = $serviciosUsuarios->traerContratorPorStatus($_GET['sSearch_0']);
		} else {		
			
			$res = $serviciosCatalogos->traerCatalogoAsesor();
		}

		$label = array('btnModificar');
		$class = array('bg-info');
		$icon = array('editar');
		$indiceID = 0;
		$empieza = 0;
		$termina = 1;

	break;

	case 'UDI':
		$resAjax = $serviciosCatalogos->traerCatalogoUDijax($length, $start, $busqueda,'idudi',$colSortDir, $_GET['sSearch_0']);
		if ($_GET['sSearch_0'] != '') {	
		
			$res = $serviciosUsuarios->traerContratorPorStatus($_GET['sSearch_0']);
		} else {		
			
			$res = $serviciosCatalogos->traerCatalogoUDI();
		}

		$label = array('btnModificar');
		$class = array('bg-info');
		$icon = array('editar');
		$indiceID = 0;
		$empieza = 0;
		$termina = 3;

	break;

	case 'pendienteEmpleador':
		$resAjax = $serviciosUsuarios->traerContratosPendienteEmpleadorajax($length, $start, $busqueda,$colSort,$colSortDir, $_GET['sSearch_0']);
		if ($_GET['sSearch_0'] != '') {	
		
			$res = $serviciosUsuarios->traerContratorPorStatus($_GET['sSearch_0']);
		} else {		
			
			$res = $serviciosUsuarios->traerContratosPendienteEmpleador();
		}

		$label = array('btnModificar');
		$class = array('bg-info');
		$icon = array('editar');
		$indiceID = 0;
		$empieza = 1;
		$termina = 9;

	break;

	case 'rechazoEmpresa':
		$resAjax = $serviciosUsuarios->traerContratosajaxRechazadosEmpresa($length, $start, $busqueda,'idcontratoglobal',$colSortDir, $_GET['sSearch_0']);
		if ($_GET['sSearch_0'] != '') {	
		
			$res = $serviciosUsuarios->traerContratorPorStatus($_GET['sSearch_0']);
		} else {		
			
			$res = $serviciosUsuarios->traerContratosRechazadosEmpresa();
		}

		$label = array('btnModificar');
		$class = array('bg-info');
		$icon = array('editar');
		$indiceID = 0;
		$empieza = 1;
		$termina = 8;

	break;

	case 'aprobadoEmpresa':
		$resAjax = $serviciosUsuarios->traerContratosajaxAutorizadosEmpresa($length, $start, $busqueda,'idcontratoglobal',$colSortDir, $_GET['sSearch_0']);
		if ($_GET['sSearch_0'] != '') {	
		
			$res = $serviciosUsuarios->traerContratorPorStatus($_GET['sSearch_0']);
		} else {		
			
			$res = $serviciosUsuarios->traerContratosAutorizadosEmpresa();
		}

		$label = array('btnModificar');
		$class = array('bg-info');
		$icon = array('editar');
		$indiceID = 0;
		$empieza = 1;
		$termina = 7;
	break;

	case 'confirmarEmpresa':
		$resAjax = $serviciosUsuarios->traerContratosajaxConfirmacionAnualEmpresa($length, $start, $busqueda,'idcontratoglobal',$colSortDir, $_GET['sSearch_0']);
		if ($_GET['sSearch_0'] != '') {	
		
			$res = $serviciosUsuarios->traerContratorPorStatus($_GET['sSearch_0']);
		} else {		
			
			$res = $serviciosUsuarios->traerContratosConfirmacionAnualEmpresa();
		}

		$label = array('btnModificar');
		$class = array('bg-info');
		$icon = array('editar');
		$indiceID = 0;
		$empieza = 1;
		$termina = 7;
	break;

	case 'riesgoElemento':
		$resAjax = $serviciosCatalogos->traerCatalogoRiesgoElementojax($length, $start, $busqueda,'idriesgoelemento',$colSortDir, $_GET['sSearch_0']);
		if ($_GET['sSearch_0'] != '') {	
		
			$res = $serviciosUsuarios->traerContratorPorStatus($_GET['sSearch_0']);
		} else {		
			
			$res = $serviciosCatalogos->traerCatalogoRiesgoElemento();
		}

		$label = array('btnModificar');
		$class = array('bg-info');
		$icon = array('editar');
		$indiceID = 0;
		$empieza = 0;
		$termina = 2;

	break;

	case 'riesgoIndicador':
		$resAjax = $serviciosCatalogos->traerCatalogoRiesgoIndicadorjax($length, $start, $busqueda,'idriesgoindicador',$colSortDir, $_GET['sSearch_0']);
		if ($_GET['sSearch_0'] != '') {	
		
			$res = $serviciosUsuarios->traerContratorPorStatus($_GET['sSearch_0']);
		} else {		
			
			$res = $serviciosCatalogos->traerCatalogoRiesgoIndicador();
		}

		$label = array('btnModificar');
		$class = array('bg-info');
		$icon = array('editar');
		$indiceID = 0;
		$empieza = 0;
		$termina = 6;
	break;

	case 'riesgoVariable':
		$resAjax = $serviciosCatalogos->traerCatalogoRiesgoVariablejax($length, $start, $busqueda,'idriesgovariable',$colSortDir, $_GET['sSearch_0']);
		if ($_GET['sSearch_0'] != '') {		
			$res = $serviciosUsuarios->traerContratorPorStatus($_GET['sSearch_0']);
		} else {			
			$res = $serviciosCatalogos->traerCatalogoRiesgoVariable();
		}
		$label = array('btnModificar');
		$class = array('bg-info');
		$icon = array('editar');
		$indiceID = 0;
		$empieza = 0;
		$termina = 7;
	break;

	case 'riesgoNivel':
		$resAjax = $serviciosCatalogos->traerCatalogoRiesgoNiveljax($length, $start, $busqueda,'idriesgonivel',$colSortDir, $_GET['sSearch_0']);
		if ($_GET['sSearch_0'] != '') {		
			$res = $serviciosUsuarios->traerContratorPorStatus($_GET['sSearch_0']);
		} else {			
			$res = $serviciosCatalogos->traerCatalogoRiesgoNivel();
		}
		$label = array('btnModificar');
		$class = array('bg-info');
		$icon = array('editar');
		$indiceID = 0;
		$empieza = 0;
		$termina = 3;
	break;

	case 'contratosGlobalesFirmasPendientes':
		$resAjax = $serviciosCatalogos->traerContratoGlobalesFirmasPendientesjax($length, $start, $busqueda,'idfirmacontratoglobal','DESC', $_GET['sSearch_0']);
		if ($_GET['sSearch_0'] != '') {		
			$res = $serviciosUsuarios->traerContratorPorStatus($_GET['sSearch_0']);
		} else {			
			$res = $serviciosCatalogos->traerContratoGlobalesFirmasPendientes();
		}
		$label = array('btnModificar');
		$class = array('bg-info');
		$icon = array('editar');
		$indiceID = 0;
		$empieza = 1;
		$termina = 6;
	break;

	case 'contratosGlobalesActivos':
		$resAjax = $serviciosCatalogos->traerContratosActivosjax($length, $start, $busqueda,'idfirmacontratoglobal','DESC', $_GET['sSearch_0']);
		if ($_GET['sSearch_0'] != '') {		
			$res = $serviciosUsuarios->traerContratorPorStatus($_GET['sSearch_0']);
		} else {			
			$res = $serviciosCatalogos->traerContratosActivos();
		}
		$label = array('btnModificar');
		$class = array('bg-info');
		$icon = array('editar');
		$indiceID = 0;
		$empieza = 1;
		$termina = 6;
	break;

	case 'listadoClientes':
		$resAjax = $serviciosCatalogos->traerClientesAjax($length, $start, $busqueda,'ultimoContrato','DESC', $_GET['sSearch_0']);
		if ($_GET['sSearch_0'] != '') {		
			$res = $serviciosUsuarios->traerContratorPorStatus($_GET['sSearch_0']);
		} else {			
			$res = $serviciosCatalogos->traerClientes();
		}
		$label = array('btnModificar');
		$class = array('bg-info');
		$icon = array('editar');
		$indiceID = 0;
		$empieza = 1;
		$termina = 10;
	break;

	case 'listadoClientesConCirculoCredito':
		$resAjax = $serviciosCatalogos->traerClientesCirculoCreditoAjax($length, $start, $busqueda,'ultimoContrato','DESC', $_GET['sSearch_0']);
		if ($_GET['sSearch_0'] != '') {		
			$res = $serviciosUsuarios->traerContratorPorStatus($_GET['sSearch_0']);
		} else {			
			$res = $serviciosCatalogos->traerClientesCirculoCredito();
		}
		$label = array('btnModificar');
		$class = array('bg-info');
		$icon = array('editar');
		$indiceID = 0;
		$empieza = 1;
		$termina = 7;
	break;

	


	

	default:
		// code...
		break;
}

$query = new Query();
$cantidadFilas = $query->numRows($res);


header("content-type: Access-Control-Allow-Origin: *");

$ar = array();
$arAux = array();
$cad = '';
$id = 0;
#print_r($resAjax);
	while ($row = mysql_fetch_array($resAjax, MYSQLI_BOTH)) {	
		for ($i=$empieza;$i<=$termina;$i++) {

			switch($tabla){

				case 'listadoClientesConCirculoCredito':
					if(  $i==7 && $row[$i]=='Si' || $row[$i]=='Si/Pendiente'){
						$opc = ($row[$i]=='Si')?1:2;
						if($opc ==1)
						array_push($arAux, armarLinkCirculo($row[8],$label,$class,$icon,$opc));
						if($opc ==2)
						array_push($arAux, armarLinkCirculo($row[8],$label,$class,$icon,$opc));
					}else{
						array_push($arAux, ($row[$i]));
					}
				break;

				case 'contratosGlobalesFirmasPendientes':
					if(  $i==6){
						$tipoContrato =$row[6];
						array_push($arAux, armarLinkFirmaContrato($row[11],$label,$class,$icon,$tipoContrato));		
					}else{
						array_push($arAux, ($row[$i]));
					}
				break;
				case 'contratosGlobalesActivos':
					if(  $i==6){
							$tipoContrato =$row[6];
							array_push($arAux, armarLinkVerContrato($row[11],$label,$class,$icon,$tipoContrato));		
						}else{
							array_push($arAux, ($row[$i]));
						}
				break; // 
				case 'listadoClientes':
					if(  $i==8 && ($row[$i]=='Si' || $row[$i]=='Si/Pendiente')){
						$opc = ($row[$i]=='Si')?1:2;
						if($opc ==1)
						array_push($arAux, armarLinkCirculo($row[11],$label,$class,$icon,$opc));
						if($opc ==2)
						array_push($arAux, armarLinkCirculo($row[11],$label,$class,$icon,$opc));
					}else if($i==9){
						
						if(($row[$i]==1 || $row[$i+1]==1) && $row[12] ==1 ){
							array_push($arAux, armarLinkOtrosProductos($row[0],$label,$class,$icon,$credito,$servicios));
							//array_push($arAux, '+');
						}else{
							array_push($arAux, '');
						}

					}else{
						array_push($arAux, ($row[$i]));
					}
				break;
				default:
					array_push($arAux, ($row[$i]));
					break;
			}
			/*if($tabla == 'listadoClientesConCirculoCredito'){
				if(  $i==7 && $row[$i]=='Si' || $row[$i]=='Si/Pendiente'){
					$opc = ($row[$i]=='Si')?1:2;
					if($opc ==1)
					array_push($arAux, armarLinkCirculo($row[8],$label,$class,$icon,$opc));
					if($opc ==2)
					array_push($arAux, armarLinkCirculo($row[8],$label,$class,$icon,$opc));
				}else{
					array_push($arAux, ($row[$i]));
				}
			}else{
				array_push($arAux, ($row[$i]));
			}*/
		}
#echo "Tbala =>".$tabla;
		if (($tabla == 'contratosGlobales') || ($tabla == 'asociados')) {
			array_push($arAux, armarAccionesCG($row[0],$label,$class,$icon));
		} else if(($tabla == 'contratosGlobalesIncompletos')  || ($tabla == 'contratosGlobalesRechazados') ||($tabla == 'contratosGlobalesAbandonados') || ($tabla == 'tramitesPLD')) {
			array_push($arAux, armarAccionesTemp($row[0],$label,$class,$icon));
		}else if($tabla =='pendienteEmpleador' || $tabla == 'rechazoEmpresa' || $tabla == 'aprobadoEmpresa' || $tabla == 'confirmarEmpresa'){
			if($tabla != 'pendienteEmpleador'){
				array_push($arAux, armarAccionesEmpresaTemp($row[0],$label,$class,$icon));
			}else{
				array_push($arAux, armarAccionesEmpresa($row[0],$label,$class,$icon));
			}
			
		}else if($tabla == 'contratosGlobalesCliente'){
			
			array_push($arAux, armarAccionesCGCliente($row[0],$label,$class,$icon));
		}else{
			$listadoSinAcciones = array('listadoClientesConCirculoCredito',
										'contratosGlobalesFirmasPendientes',
										'contratosGlobalesActivos');
			if(!in_array($tabla, $listadoSinAcciones)){
				array_push($arAux, armarAcciones($row[0],$label,$class,$icon));
			}
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
