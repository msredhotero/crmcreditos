<?php
include ('../../../class_include.php');

$serviciosUsuario = new ServiciosUsuarios();
$serviciosHTML = new ServiciosHTML();
$serviciosFunciones = new Servicios();
$serviciosReferencias   = new ServiciosReferencias();
$baseHTML = new BaseHTML();

$baseHTML->setContentHeader ('Contrato ', 'Home/Contrato');
$idContratoGlobal = 6;

$dataContratoGlobal = new ServiciosSolicitudes($idContratoGlobal);

$nombre = 'enrique';
$paterno = 'peña';
$materno = 'nieto';
$apellido = $paterno." ".$materno;
$curp = 'oiaz840615mmsrln00';
$rfc = 'PENX660720CV0';
$usuarioId= 7869;
$identificacion = $curp."|".$rfc;

#echo  $identificacion;
#$dataContratoGlobal->buscaListaPrevencion($idContratoGlobal, $nombre, $paterno, $materno, $curp, $rfc, $usuarioId);

//$wS = new WebServicePrevencionLavado($nombre, $apellido, $identificacion);


$Usuario = 'anal2';
$Password = '7D434594';
$url = 'https://www.prevenciondelavado.com/listas/api/busqueda';
$ch = curl_init($url);
#echo "entro a busqueda";

        $data = array(
            'Usuario' => $Usuario,
            'Password' => $Password,
            'Apellido' => $apellido,
            'Nombre' => $nombre,
            'Identificacion' => 'PENX660720CV0',
            'Incluye_SAT' => 'S'           
        );

        //attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        //set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        //return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute the POST request
        $result = curl_exec($ch);
        echo    $result;

?>