<?php

session_start();
if (!isset($_SESSION['usua_sahilices']))
{
	header('Location: ../../error.php');
} else {
include ('../includes/funcionesReferencias.php');
$serviciosReferencias 	= new ServiciosReferencias();

$refgerentecomercial = $_POST['refgerentecomercial'];

switch ($_SESSION['idroll_sahilices']) {
   case 3:
      $resEntrevistasOportunidades = $serviciosReferencias->traerEntrevistaoportunidadesPorUsuarioCalendar($_SESSION['usuaid_sahilices']);
      $resEntrevistasPostulantes = $serviciosReferencias->traerEntrevistasCalendarPorUsuario($_SESSION['usuaid_sahilices']);
		$resVisitasDeSeguimientos = $serviciosReferencias->traerAlertasPorUsuariosCalendario($_SESSION['usuaid_sahilices']);
   break;
   case 1:
      $resEntrevistasOportunidades = $serviciosReferencias->traerEntrevistaoportunidadesCalendar($refgerentecomercial);
      $resEntrevistasPostulantes = $serviciosReferencias->traerEntrevistasCalendar($refgerentecomercial);
		$resVisitasDeSeguimientos = $serviciosReferencias->traerAlertasCalendario($refgerentecomercial);
   break;
   case 8:
      $resEntrevistasOportunidades = $serviciosReferencias->traerEntrevistaoportunidadesCalendar($refgerentecomercial);
      $resEntrevistasPostulantes = $serviciosReferencias->traerEntrevistasCalendar($refgerentecomercial);
		$resVisitasDeSeguimientos = $serviciosReferencias->traerAlertasCalendario($refgerentecomercial);
   break;
   default:
      $resEntrevistasOportunidades = $serviciosReferencias->traerEntrevistaoportunidadesCalendar($refgerentecomercial);
      $resEntrevistasPostulantes = $serviciosReferencias->traerEntrevistasCalendar($refgerentecomercial);
		$resVisitasDeSeguimientos = $serviciosReferencias->traerAlertasCalendario($refgerentecomercial);
   break;
}

//--------------------------------------------------------------------------------------------------
// This script reads event data from a JSON file and outputs those events which are within the range
// supplied by the "start" and "end" GET parameters.
//
// An optional "timeZone" GET parameter will force all ISO8601 date stings to a given timeZone.
//
// Requires PHP 5.2.0 or higher.
//--------------------------------------------------------------------------------------------------

// Require our Event class and datetime utilities
require dirname(__FILE__) . '/utils.php';

// Short-circuit if the client did not give us a date range.
if (!isset($_POST['start']) || !isset($_POST['end'])) {
  die("Please provide a date range.");
}

// Parse the start/end parameters.
// These are assumed to be ISO8601 strings with no time nor timeZone, like "2013-12-29".
// Since no timeZone will be present, they will parsed as UTC.
$range_start = parseDateTime($_POST['start']);
$range_end = parseDateTime($_POST['end']);

// Parse the timeZone parameter if it is present.
$timezone = null;
/*
if (isset($_POST['timezone'])) {
  $timezone = new DateTimeZone($_GET['timezone']);
}
*/

// Read and parse our events JSON file into an array of event data arrays.
//$json = file_get_contents(dirname(__FILE__) . '/../json/events.json');
//$input_arrays = json_decode($json, true);

// Accumulate an output array of event data arrays.
$output_arrays = array();
while ($array = mysql_fetch_array($resEntrevistasOportunidades)) {

  // Convert the input array into a useful Event object
  $event = new Event($array, $timezone);

  // If the event is in-bounds, add it to the output
  if ($event->isWithinDayRange($range_start, $range_end)) {
    $output_arrays[] = $event->toArray();
  }
}

while ($array = mysql_fetch_array($resEntrevistasPostulantes)) {

  // Convert the input array into a useful Event object
  $event = new Event($array, $timezone);

  // If the event is in-bounds, add it to the output
  if ($event->isWithinDayRange($range_start, $range_end)) {
    $output_arrays[] = $event->toArray();
  }
}

while ($array = mysql_fetch_array($resVisitasDeSeguimientos)) {

  // Convert the input array into a useful Event object
  $event = new Event($array, $timezone);

  // If the event is in-bounds, add it to the output
  if ($event->isWithinDayRange($range_start, $range_end)) {
    $output_arrays[] = $event->toArray();
  }
}

// Send JSON to the client.
echo json_encode($output_arrays);

}
