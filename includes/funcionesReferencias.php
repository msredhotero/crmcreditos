<?php

/**
 * @Usuarios clase en donde se accede a la base de datos
 * @ABM consultas sobre las tablas de usuarios y usarios-clientes
 */

date_default_timezone_set('America/Mexico_City');

class ServiciosReferencias {


   /* PARA Configuracion */

   function insertarConfiguracion($razonsocial,$empresa,$sistema,$direccion,$telefono,$email) {
   $sql = "insert into tbconfiguracion(idconfiguracion,razonsocial,empresa,sistema,direccion,telefono,email)
   values (null,'".$razonsocial."','".$empresa."','".$sistema."','".$direccion."','".$telefono."','".$email."')";
   $res = $this->query($sql,1);
   return $res;
   }


   function modificarConfiguracion($id,$razonsocial,$empresa,$sistema,$direccion,$telefono,$email) {
   $sql = "update tbconfiguracion
   set
   razonsocial = '".$razonsocial."',empresa = '".$empresa."',sistema = '".$sistema."',direccion = '".$direccion."',telefono = '".$telefono."',email = '".$email."'
   where idconfiguracion =".$id;
   $res = $this->query($sql,0);
   return $res;
   }


   function eliminarConfiguracion($id) {
   $sql = "delete from tbconfiguracion where idconfiguracion =".$id;
   $res = $this->query($sql,0);
   return $res;
   }


   function traerConfiguracion() {
   $sql = "select
   c.idconfiguracion,
   c.razonsocial,
   c.empresa,
   c.sistema,
   c.direccion,
   c.telefono,
   c.email
   from tbconfiguracion c
   order by 1";
   $res = $this->query($sql,0);
   return $res;
   }


   function traerConfiguracionPorId($id) {
   $sql = "select idconfiguracion,razonsocial,empresa,sistema,direccion,telefono,email from tbconfiguracion where idconfiguracion =".$id;
   $res = $this->query($sql,0);
   return $res;
   }

   /* Fin */
   /* /* Fin de la Tabla: tbconfiguracion*/

  


function traerEstadodocumentos() {
   $sql = "SELECT
   e.idestadodocumento,
   e.descripcion,
   e.color
   from  tbestadodocumentos e
   order by 1";
   $res = $this->query($sql,0);
   return $res;
   }

function traerRazonRechazoDocumentos() {
   $sql = "SELECT
   r.idrechazodocumento,
   r.descripcion  
   from  tdrechazodocumentos r
   order by 1";
   $res = $this->query($sql,0);
   return $res;
   }

   function traerDocumentosPorId($idDocto) {
      $sql = "SELECT iddocumento,
      decripcion,
      especificaciones,
      requerido,
      responsable,
      nombre_archivo     
      FROM tbdocumento WHERE iddocumento =".$idDocto;
      $res = $this->query($sql,0);     
      return $res;
   }

   



   function eliminarDocumentacionPorContratoGlobalDocumentacion($idCG,$iddocumento) {
      $sql = "delete from dbcontratosglobalesdocumentos where  refcontratoglobal =".$idCG." and refdocumento = ".$iddocumento;
      $res = $this->query($sql,0);
      return $res;
   }

   function insertarDocumentacionContratoGlobal($idContratoGlobal,$idDocto,$archivo, $refEstado,$directorio,$usuarioId) {

      // VERIFICAMOS SI EL DOCUMENTO PATERNECE AL CONTRATO O PERTENECE AL USUARIO
      //SI PERTENECE AL USUARIO SE DEBE INSERTAR EL REFUSUARIO EN LA TABLA; SINO EL CAMPO SE QUEDA NULL
      $sqlDocumentodeUsuario = "SELECT    adjuntoausuario FROM tbdocumento WHERE iddocumento = ".$idDocto;
      $resD = $this->query($sqlDocumentodeUsuario,0);
      $rowDA = mysql_fetch_array($resD);
      $adjuntarDocumentoAUsuario = $rowDA['adjuntoausuario'];

      // seleccionamos el usuario del contrato global
      $sqlUsuario = "SELECT usuario_id FROM dbcontratosglobales WHERE idcontratoglobal = ".$idContratoGlobal;
      $resU = $this->query($sqlUsuario,0);
      $rowUI = mysql_fetch_array($resU);
      $refusuario = $rowUI['usuario_id'];

      if( $adjuntarDocumentoAUsuario){
         $sqlIsertFile = "INSERT INTO `dbcontratosglobalesdocumentos` (`idcontratoglobaldocumento`, `refcontratoglobal`,  `refusuario`, `refdocumento`, 
      `refestadodocumento`, `nombre`, `ruta`, `vigencia_desde`, `vigencia_hasta` , `fechacaptura` , `refusuariocaptura`) ";
                              $sqlIsertFile .= " VALUES (NULL, $idContratoGlobal , $refusuario, $idDocto, '".$refEstado."' , '".$archivo."', '".trim($directorio,'.')."', CURDATE(), DATE_ADD(CURDATE(),INTERVAL 1 YEAR),CURDATE(),$usuarioId); "; 

      }else{
         $sqlIsertFile = "INSERT INTO `dbcontratosglobalesdocumentos` (`idcontratoglobaldocumento`, `refcontratoglobal`, `refdocumento`, 
      `refestadodocumento`, `nombre`, `ruta`, `vigencia_desde`, `vigencia_hasta` , `fechacaptura` , `refusuariocaptura`) ";
                              $sqlIsertFile .= " VALUES (NULL, $idContratoGlobal , $idDocto, '".$refEstado."' , '".$archivo."', '".trim($directorio,'.')."', CURDATE(), DATE_ADD(CURDATE(),INTERVAL 1 YEAR),CURDATE(),$usuarioId); "; 

      }

     
      $res = $this->query($sqlIsertFile,1);
     
      return $res;
   }

   function traerDocumentacionPorTipoCreditoDocumentacion($idContratoGlobal, $iddocumento) {
      $sqlUsuario = "SELECT usuario_id FROM dbcontratosglobales WHERE idcontratoglobal = ". $idContratoGlobal;
      $resUser = $this->query($sqlUsuario,0);
       $row = mysql_fetch_array($resUser);
       $usuario = $row['usuario_id'];
      $sql = "SELECT
      da.idcontratoglobaldocumento,
      da.refcontratoglobal,
      da.refdocumento,
      da.refestadodocumento,
      da.nombre,
      da.refrechazodocumento,
      da.comentario ,
      d.nombre_archivo as nombre_de_carpeta,
      da.ruta as carpeta,
      da.vigencia_desde,
      da.vigencia_hasta,
      e.idestadodocumento,   
      e.descripcion, 
      e.color      
      FROM dbcontratosglobalesdocumentos da
      INNER JOIN tbdocumento d ON d.iddocumento = da.refdocumento
      INNER JOIN  tbestadodocumentos e ON e.idestadodocumento = da.refestadodocumento
      JOIN dbcontratosglobales cg on cg.idcontratoglobal = da.refcontratoglobal 
      where ( da.refcontratoglobal =".$idContratoGlobal."  || da.refusuario  =".$usuario." ) and da.refdocumento = ".$iddocumento;

      #where ( da.refcontratoglobal =".$idContratoGlobal." || da.refusuario = cg.usuario_id) and da.refdocumento = ".$iddocumento;
      
      $res = $this->query($sql,0);
     #echo $sql;
      return $res;
   }

   function traerDocumentacionPorTipoCreditoDocumentacionResponsable($idContratoGlobal, $responsable) {
      $sql = "SELECT
      da.idcontratoglobaldocumento,
      da.refcontratoglobal,
      da.refdocumento,
      da.refestadodocumento,
      da.nombre,
      da.refrechazodocumento,
      da.comentario ,
      d.nombre_archivo as nombre_de_carpeta,
      da.ruta as carpeta,
      da.vigencia_desde,
      da.vigencia_hasta,
      e.idestadodocumento,   
      e.descripcion, 
      e.color      
      FROM dbcontratosglobalesdocumentos da
      INNER JOIN tbdocumento d ON d.iddocumento = da.refdocumento
      INNER JOIN  tbestadodocumentos e ON e.idestadodocumento = da.refestadodocumento
      where da.refcontratoglobal =".$idContratoGlobal." and d.responsable = ".$responsable;
      $res = $this->query($sql,0);
     #echo $sql;
      return $res;
   }

   function traerDocumentacionPorTipoDocumentacionContrato($idContratoGlobal, $idDocumentacion) {
      $sql = "SELECT
      da.idcontratoglobaldocumento,
      da.refcontratoglobal,
      da.refdocumento,
      da.refestadodocumento,
      da.nombre,
      da.refrechazodocumento,
      da.comentario ,
      d.nombre_archivo as nombre_de_carpeta,
      da.ruta as carpeta,
      da.vigencia_desde,
      da.vigencia_hasta,
      e.idestadodocumento,   
      e.descripcion, 
      e.color      
      FROM dbcontratosglobalesdocumentos da
      INNER JOIN tbdocumento d ON d.iddocumento = da.refdocumento
      INNER JOIN  tbestadodocumentos e ON e.idestadodocumento = da.refestadodocumento
      where da.refcontratoglobal =".$idContratoGlobal." and d.iddocumento = ".$idDocumentacion;
      $res = $this->query($sql,0);
     #echo $sql;
      return $res;
   }

function traerDocumentacionPorTipoCreditoDocumentacionResponsableAdjuntoClienteAll($idContratoGlobal, $refUsuario) {
      $sql = "SELECT
      da.idcontratoglobaldocumento,
      da.refcontratoglobal,
      da.refusuario,
      da.refdocumento,
      da.refestadodocumento,
      da.nombre,
      da.refrechazodocumento,
      da.comentario ,
      d.nombre_archivo as nombre_de_carpeta,
      da.ruta as carpeta,
      da.vigencia_desde,
      da.vigencia_hasta,
      e.idestadodocumento,   
      e.descripcion, 
      e.color      
      FROM dbcontratosglobalesdocumentos da
      INNER JOIN tbdocumento d ON d.iddocumento = da.refdocumento
      INNER JOIN  tbestadodocumentos e ON e.idestadodocumento = da.refestadodocumento
      where (da.refcontratoglobal =".$idContratoGlobal." || da.refusuario =".$refUsuario.") ORDER BY  da.refdocumento ASC";
      $res = $this->query($sql,0);
     #echo $sql;
      return $res;
   }

function traerDocumentacionPorTipoCreditoDocumentacionResponsableAdjuntoCliente($idContratoGlobal, $responsable, $refUsuario) {
      $sql = "SELECT
      da.idcontratoglobaldocumento,
      da.refcontratoglobal,
      da.refusuario,
      da.refdocumento,
      da.refestadodocumento,
      da.nombre,
      da.refrechazodocumento,
      da.comentario ,
      d.nombre_archivo as nombre_de_carpeta,
      da.ruta as carpeta,
      da.vigencia_desde,
      da.vigencia_hasta,
      e.idestadodocumento,   
      e.descripcion, 
      e.color      
      FROM dbcontratosglobalesdocumentos da
      INNER JOIN tbdocumento d ON d.iddocumento = da.refdocumento
      INNER JOIN  tbestadodocumentos e ON e.idestadodocumento = da.refestadodocumento
      where (da.refcontratoglobal =".$idContratoGlobal." || da.refusuario =".$refUsuario.") and d.responsable = ".$responsable;
      $res = $this->query($sql,0);
     #echo $sql;
      return $res;
   }

   function  traerDocumentacionPorTipoCreditoDocumentacionCompletaCliente($idContratoGlobal, $responsable){
      $query = new Query();
      $idContratoGlobal =  ($idContratoGlobal!='')?$idContratoGlobal:0;
      $sqlDoctos = "SELECT CG.idcontratoglobal as contratoId,
                  CG.reftipocontratoglobal as tipo_credito, 
                  CG.cedulasi,
                  CG.firmasi,
                  CG.entrevistacliente,
                  Docto.iddocumento,
                  Docto.documento,                  
                  Docto.requerio,
                  Docto.adjuntoausuario,
                  CGD.idcontratoglobaldocumento,  
                  CGD.nombre, 
                  CGD.refrechazodocumento,
                  CGD.comentario ,
                  CGD.refusuario ,
                   CGD.ruta ,
                  RD.descripcion as razonRechazo,
                  coalesce( ED.descripcion, 'Falta') as estadodocumentacion,
                  ED.idestadodocumento,  
                  coalesce( ED.color, 'bg-gray') as color,
                  (case when Docto.requerio = 1 then ' (Requerido) ' end) as docto_requerido,

                  (case when Docto.requerio = 1 then ' (Requerido) '
            when (CG.cedulasi = 1 and Docto.iddocumento =21)  then ' (Requerido) '
            when (CG.firmasi = 1 and Docto.iddocumento =22)  then ' (Requerido) '
            when (CG.entrevistacliente = 1 and Docto.iddocumento =27)  then ' (Requerido) '
            else '' end
           ) as doctoReq,

           (case when Docto.requerio = 1 then '1'
            when (CG.cedulasi = 1 and Docto.iddocumento =21)  then '1'
            when (CG.firmasi = 1 and Docto.iddocumento =22)  then '1'
            when (CG.entrevistacliente = 1 and Docto.iddocumento =27)  then '1'
            else '' end
           ) as req
                  
                  FROM dbcontratosglobales  CG 
                  JOIN vista_empresa_afialida_tipo_coontrato_glogal_documentos Docto ON CG.refempresaafiliada = Docto.idempresaafiliada 
                  AND CG.reftipocontratoglobal = Docto.idtipocontratoglobal 
                  LEFT JOIN dbcontratosglobalesdocumentos CGD ON CGD.refdocumento = Docto.iddocumento 
                  AND (CGD.refcontratoglobal = CG.idcontratoglobal  || CGD.refusuario = CG.usuario_id)
                  LEFT JOIN tbestadodocumentos ED ON CGD.refestadodocumento = ED.idestadodocumento 
                  LEFT JOIN tdrechazodocumentos RD ON CGD.refrechazodocumento = RD.idrechazodocumento 
                  WHERE CG.idcontratoglobal = $idContratoGlobal AND (Docto.adjuntoausuario = 0 || CGD.refusuario IS NULL  || CGD.refcontratoglobal = CG.idcontratoglobal)  
                  AND Docto.responsable = $responsable 
                  ORDER BY Docto.orden

                  ";
                    //echo $sqlDoctos."<br>"; 
   $query->setQuery($sqlDoctos) ;
   $rsDoctos = $query->eject();  

   return $rsDoctos;
}

function  traerDocumentacionPorTipoCreditoDocumentacionCompletaClienteAdministracion($idContratoGlobal, $responsable){
   $query = new Query();

   $idContratoGlobal =  ($idContratoGlobal!='')?$idContratoGlobal:0;

   $sqlDoctos = "SELECT CG.idcontratoglobal as contratoId,
                  CG.reftipocontratoglobal as tipo_credito, 
                  CG.cedulasi,
                  CG.firmasi,
                  CG.entrevistacliente,
                  Docto.iddocumento,
                  Docto.documento,                  
                  Docto.requerio,
                  Docto.adjuntoausuario,
                  CGD.idcontratoglobaldocumento,  
                  CGD.nombre, 
                  CGD.refrechazodocumento,
                  CGD.comentario ,
                  CGD.refusuario ,
                   CGD.ruta ,
                  RD.descripcion as razonRechazo,
                  coalesce( ED.descripcion, 'Falta') as estadodocumentacion,
                  ED.idestadodocumento,  
                  coalesce( ED.color, 'bg-gray') as color,
                  (case when Docto.requerio = 1 then ' (Requerido) ' end) as docto_requerido,

                  (case when Docto.requerio = 1 then ' (Requerido) '
            when (CG.cedulasi = 1 and Docto.iddocumento =21)  then ' (Requerido) '
            when (CG.firmasi = 1 and Docto.iddocumento =22)  then ' (Requerido) '
            when (CG.entrevistacliente = 1 and Docto.iddocumento =27)  then ' (Requerido) '
            else '' end
           ) as doctoReq,

           (case when Docto.requerio = 1 then '1'
            when (CG.cedulasi = 1 and Docto.iddocumento =21)  then '1'
            when (CG.firmasi = 1 and Docto.iddocumento =22)  then '1'
            when (CG.entrevistacliente = 1 and Docto.iddocumento =27)  then '1'
            else '' end
           ) as req
                  
                  FROM dbcontratosglobales  CG 
                  JOIN vista_empresa_afialida_tipo_coontrato_glogal_documentos Docto ON CG.refempresaafiliada = Docto.idempresaafiliada 
                  AND CG.reftipocontratoglobal = Docto.idtipocontratoglobal 
                  LEFT JOIN dbcontratosglobalesdocumentos CGD ON CGD.refdocumento = Docto.iddocumento 
                  AND (CGD.refcontratoglobal = CG.idcontratoglobal  || CGD.refusuario = CG.usuario_id)
                  LEFT JOIN tbestadodocumentos ED ON CGD.refestadodocumento = ED.idestadodocumento 
                  LEFT JOIN tdrechazodocumentos RD ON CGD.refrechazodocumento = RD.idrechazodocumento 
                  WHERE CG.idcontratoglobal = $idContratoGlobal  
                  AND Docto.responsable = $responsable 
                  ORDER BY Docto.orden

                  ";
                    #echo $sqlDoctos."<br>"; 
   $query->setQuery($sqlDoctos) ;
   $rsDoctos = $query->eject();  

   return $rsDoctos;
}

function  traerDocumentacionPorTipoCreditoDocumentacionId($idContratoGlobal, $responsable, $idDocto){
   $query = new Query();

   $idContratoGlobal =  ($idContratoGlobal!='')?$idContratoGlobal:0;

   $sqlDoctos = "SELECT CG.idcontratoglobal as contratoId,
                  CG.reftipocontratoglobal as tipo_credito, 
                  CG.cedulasi,
                  CG.firmasi,
                  CG.entrevistacliente,
                  Docto.iddocumento,
                  Docto.documento,                  
                  Docto.requerio,
                  CGD.idcontratoglobaldocumento,  
                  CGD.nombre, 
                  CGD.refrechazodocumento,
                  CGD.comentario ,
                  RD.descripcion as razonRechazo,
                  coalesce( ED.descripcion, 'Falta') as estadodocumentacion,
                  ED.idestadodocumento,  
                  coalesce( ED.color, 'bg-gray') as color,
                  (case when Docto.requerio = 1 then ' (Requerido) ' end) as docto_requerido,

                  (case when Docto.requerio = 1 then ' (Requerido) '
            when (CG.cedulasi = 1 and Docto.iddocumento =21)  then ' (Requerido) '
            when (CG.firmasi = 1 and Docto.iddocumento =22)  then ' (Requerido) '
            when (CG.entrevistacliente = 1 and Docto.iddocumento =27)  then ' (Requerido) '
            else '' end
           ) as doctoReq,

           (case when Docto.requerio = 1 then '1'
            when (CG.cedulasi = 1 and Docto.iddocumento =21)  then '1'
            when (CG.firmasi = 1 and Docto.iddocumento =22)  then '1'
            when (CG.entrevistacliente = 1 and Docto.iddocumento =27)  then '1'
            else '' end
           ) as req
                  
                  FROM dbcontratosglobales  CG 
                  JOIN vista_empresa_afialida_tipo_coontrato_glogal_documentos Docto ON CG.refempresaafiliada = Docto.idempresaafiliada 
                  AND CG.reftipocontratoglobal = Docto.idtipocontratoglobal 
                  LEFT JOIN dbcontratosglobalesdocumentos CGD ON CGD.refdocumento = Docto.iddocumento 
                  AND CGD.refcontratoglobal = CG.idcontratoglobal 
                  LEFT JOIN tbestadodocumentos ED ON CGD.refestadodocumento = ED.idestadodocumento 
                  LEFT JOIN tdrechazodocumentos RD ON CGD.refrechazodocumento = RD.idrechazodocumento 
                  WHERE CG.idcontratoglobal = $idContratoGlobal 
                  AND Docto.responsable = $responsable 
                  AND Docto.iddocumento = $idDocto
                  ORDER BY Docto.orden

                  ";
                    #echo $sqlDoctos."<br>"; 
   $query->setQuery($sqlDoctos) ;
   $rsDoctos = $query->eject();  

   return $rsDoctos;
}



  function  traerDocumentacionPorTipoCreditoDocumentacionReemplazoGral($idContratoGlobal, $responsable, $rechazados){
   $query = new Query();

   $sqlDoctos = "SELECT CG.idcontratoglobal as contratoId,
                  CG.reftipocontratoglobal as tipo_credito, 
                  CG.cedulasi,
                  CG.firmasi,
                  CG.entrevistacliente,
                  Docto.iddocumento,
                  Docto.documento, 
                  Docto.requerio,
                  CGD.idcontratoglobaldocumento,  
                  CGD.nombre, 
                  coalesce( ED.descripcion, 'Falta') as estadodocumentacion,
                  ED.idestadodocumento,  
                  coalesce( ED.color, 'bg-gray') as color,
                  (case when Docto.requerio = 1 then ' (Requerido) ' end) as docto_requerido,

                  (case when Docto.requerio = 1 then ' (Requerido) '
            when (CG.cedulasi = 1 and Docto.iddocumento =21)  then ' (Requerido) '
            when (CG.firmasi = 1 and Docto.iddocumento =22)  then ' (Requerido) '
            when (CG.entrevistacliente = 1 and Docto.iddocumento =27)  then ' (Requerido) '
            else '' end
           ) as doctoReq
                  
                  FROM dbcontratosglobales  CG 
                  JOIN vista_empresa_afialida_tipo_coontrato_glogal_documentos Docto ON CG.refempresaafiliada = Docto.idempresaafiliada 
                  AND CG.reftipocontratoglobal = Docto.idtipocontratoglobal 
                  LEFT JOIN dbcontratosglobalesdocumentos CGD ON CGD.refdocumento = Docto.iddocumento 
                  AND CGD.refcontratoglobal = CG.idcontratoglobal 
                  LEFT JOIN tbestadodocumentos ED ON CGD.refestadodocumento = ED.idestadodocumento 
                  WHERE CG.idcontratoglobal = $idContratoGlobal 
                  AND Docto.responsable = $responsable 
                  AND   Docto.iddocumento IN ($rechazados)
                  ORDER BY Docto.orden

                  ";
                    #echo $sqlDoctos; 
   $query->setQuery($sqlDoctos) ;
   $rsDoctos = $query->eject();  

   return $rsDoctos;
}

  function  traerDocumentacionPorTipoCreditoDocumentacionReemplazo($idContratoGlobal, $responsable,$iddocumento){
   $query = new Query();

   $sqlDoctos = "SELECT CG.idcontratoglobal as contratoId,
                  CG.reftipocontratoglobal as tipo_credito, 
                  CG.cedulasi,
                  CG.firmasi,
                  CG.entrevistacliente,
                  Docto.iddocumento,
                  Docto.documento, 
                  Docto.requerio,
                  CGD.idcontratoglobaldocumento,  
                  CGD.nombre, 
                  coalesce( ED.descripcion, 'Falta') as estadodocumentacion,
                  ED.idestadodocumento,  
                  coalesce( ED.color, 'bg-gray') as color,
                  (case when Docto.requerio = 1 then ' (Requerido) ' end) as docto_requerido,

                  (case when Docto.requerio = 1 then ' (Requerido) '
            when (CG.cedulasi = 1 and Docto.iddocumento =21)  then ' (Requerido) '
            when (CG.firmasi = 1 and Docto.iddocumento =22)  then ' (Requerido) '
             when (CG.entrevistacliente = 1 and Docto.iddocumento =27)  then ' (Requerido) '
            else '' end
           ) as doctoReq
                  
                  FROM dbcontratosglobales  CG 
                  JOIN vista_empresa_afialida_tipo_coontrato_glogal_documentos Docto ON CG.refempresaafiliada = Docto.idempresaafiliada 
                  AND CG.reftipocontratoglobal = Docto.idtipocontratoglobal 
                  LEFT JOIN dbcontratosglobalesdocumentos CGD ON CGD.refdocumento = Docto.iddocumento 
                  AND CGD.refcontratoglobal = CG.idcontratoglobal 
                  LEFT JOIN tbestadodocumentos ED ON CGD.refestadodocumento = ED.idestadodocumento 
                  WHERE CG.idcontratoglobal = $idContratoGlobal 
                  AND Docto.responsable = $responsable 
                  AND Docto.iddocumento = $iddocumento 
                  ORDER BY Docto.orden

                  ";
                    #echo $sqlDoctos; 
   $query->setQuery($sqlDoctos) ;
   $rsDoctos = $query->eject();  

   return $rsDoctos;
}



  function  traerDocumentacionPorTipoCreditoDocumentacionCompletaGral($idContratoGlobal){
   $query = new Query();

   $sqlDoctos = "SELECT CG.idcontratoglobal as contratoId,
                  CG.reftipocontratoglobal as tipo_credito, 
                  CG.cedulasi,
                  CG.firmasi,
                  CG.entrevistacliente,
                  Docto.iddocumento,
                  Docto.documento, 
                  Docto.requerio, 
                  CGD.idcontratoglobaldocumento, 
                  CGD.nombre, 
                  coalesce( ED.descripcion, 'Falta') as estadodocumentacion,
                  ED.idestadodocumento,  
                  coalesce( ED.color, 'bg-gray') as color,
                  (case when Docto.requerio = 1 then ' (Requerido) ' end) as docto_requerido,

                  (case when Docto.requerio = 1 then ' (Requerido) '
                  when CG.cedulasi = 1  then ' (Requerido) '
                  when CG.firmasi = 1  then ' (Requerido) '
                  when CG.entrevistacliente = 1  then ' (Requerido) '
                  else '' end
                  ) as doctoReq
                  
                  FROM dbcontratosglobales  CG 
                  JOIN vista_empresa_afialida_tipo_coontrato_glogal_documentos Docto ON CG.refempresaafiliada = Docto.idempresaafiliada 
                  AND CG.reftipocontratoglobal = Docto.idtipocontratoglobal 
                  LEFT JOIN dbcontratosglobalesdocumentos CGD ON CGD.refdocumento = Docto.iddocumento 
                  AND CGD.refcontratoglobal = CG.idcontratoglobal 
                  LEFT JOIN tbestadodocumentos ED ON CGD.refestadodocumento = ED.idestadodocumento 
                  WHERE CG.idcontratoglobal = $idContratoGlobal 
                   
                  ORDER BY Docto.orden

                  ";
   $query->setQuery($sqlDoctos) ;
   $rsDoctos = $query->eject();  
   return $rsDoctos;
}


function traerDatosContratoGlobalPorId($idContratoGlobal){
   $sql = "SELECT idcontratoglobal,usuario_id,paterno,Materno,nombre,email,fechanacimiento,  celular1,telefono1 FROM  dbcontratosglobales WHERE idcontratoglobal =".$idContratoGlobal;
      $res = $this->query($sql,0);
      return $res;
}

function eliminarDocumentoContratoGlobal($idContratoGlobal, $iddocumento){
   
}


function modificarEstadoDocumentoContrato($iddocumento,$idestado,$idUsuario, $idRechazo, $comentario, $fechaVigenciaDomicilio, $tipoDocto){
  $query = new Query();
  #echo   $idRechazo;
  $idRechazo =($idRechazo > 0)?$idRechazo:NULL;
  $condicion = ' idcontratoglobaldocumento = '.$iddocumento;

   // cuando el administrador entra y cambia algun documento se debe quitar el color amarillo del listado de gsestion
  $idContratoGlobal =  $query->selectCampo('refcontratoglobal', 'dbcontratosglobalesdocumentos', $condicion );
  $sqlUpdate = "UPDATE dbcontratosglobales SET   actualizacioncliente = NULL WHERE  idcontratoglobal =  $idContratoGlobal ";
   $query->setQuery($sqlUpdate);
   $query->eject();
   if($tipoDocto==3){
      $sqlUpdateF = "UPDATE dbcontratosglobales SET   vigenciadomicilio = '".$fechaVigenciaDomicilio."' WHERE  idcontratoglobal =  $idContratoGlobal ";
      $query->setQuery($sqlUpdateF);
      $query->eject();
   }         

  if($idRechazo > 0){
  $sql = "UPDATE dbcontratosglobalesdocumentos   SET
    refestadodocumento = ".$idestado." , refrechazodocumento = ".$idRechazo.",   comentario = '".$comentario."' WHERE idcontratoglobaldocumento =".$iddocumento;
    $res = $this->query($sql,0);
  }else{
    $sql = "UPDATE dbcontratosglobalesdocumentos   SET
    refestadodocumento = ".$idestado." , refrechazodocumento = NULL,   comentario = NULL WHERE idcontratoglobaldocumento =".$iddocumento;
    $res = $this->query($sql,0);

  }


    #echo $sql;
    if($idestado == 5){
       $sql = "UPDATE dbcontratosglobalesdocumentos   SET  fechaaprobacion = NOW(), refusuarioaprueba = '".$idUsuario."' WHERE idcontratoglobaldocumento =".$iddocumento;
    $res = $this->query($sql,0);
    }



    if($idestado == 2  || $idestado == 3 || $idestado == 4){
      // se manda correo al cliente para que cambie  la imagen
      #$this->enviaCorreoNuevaImagen($iddocumento, $idestado);
      #$sqlDelete = "DELETE FROM dbcontratosglobalesdocumentos  WHERE idcontratoglobaldocumento =".$iddocumento;
      #$res2 = $this->query($sqlDelete,0);


    }
    return $res;
}

 function enviarDictaminacionDocumentos($idContratoGlobal){
  $query = new Query();
  $listaDoctos = array();
  $listaDoctosA = '';
  $idDoctoRechazado = '';
  $destinatario = '';
  $idDoctoC = $query->selectCampo('idcontratoglobaldocumento',  'dbcontratosglobalesdocumentos', '  refcontratoglobal = '.$idContratoGlobal.'' );
  $idUsuario = $query->selectCampo('usuario_id', 'dbcontratosglobales', '  idcontratoglobal = '.$idContratoGlobal.'' );
  $destinatario = $this->regresaMailUsuarioIdDocto($idDoctoC);
  $resDoctosCompletos = $this->traerDocumentacionPorTipoCreditoDocumentacionCompletaClienteAdministracion($idContratoGlobal, 1);
  $rechazos ='';
  while($rowDoctos = mysql_fetch_array($resDoctosCompletos)){
    $estadoDocto = $rowDoctos['estadodocumentacion'];
    $IdEstadoDocto = $rowDoctos['idestadodocumento'];
    $iddocumentoRef =  $rowDoctos['iddocumento'];
    $idcontratoglobaldocumento =  $rowDoctos['idcontratoglobaldocumento'];
    $doctoRequerido = $rowDoctos['req'];
    $refrechazodocumento = $rowDoctos['refrechazodocumento'];
    $comentario  = $rowDoctos['comentario'];
    $razonRechazo = $rowDoctos['razonRechazo'];

    $documento = $rowDoctos['documento'];
    if($IdEstadoDocto == 2 ||  $IdEstadoDocto == 3 || $IdEstadoDocto == 4 && $doctoRequerido ){
      $listaDoctos[]=array($documento,$razonRechazo,$comentario) ;
      #$listaDoctos[]['razon']=$razonRechazo;
     # $listaDoctos[]['comentario']=$comentario;
      $listaDoctosA .=  $documento .", ";      
      // se elimina el documento de la lista 
       $rechazos .=  $iddocumentoRef."_";
      $idDoctoRechazado =  $idcontratoglobaldocumento;    
      $sqlDelete = "DELETE FROM dbcontratosglobalesdocumentos  WHERE idcontratoglobaldocumento =".$idcontratoglobaldocumento;
      $res2 = $this->query($sqlDelete,0);
      
    }

  }

  $rechazos = trim($rechazos,'_');
  if($idDoctoRechazado !=''){    
    $this->enviaCorreoDictaminacionImagen($idContratoGlobal, $destinatario, $listaDoctos, $idUsuario, $rechazos);   
  }
  // ya se eliminaron los archivos ahorase manda el correo al cliente con la liga  
  return 1;
}

private function enviaCorreoDictaminacionImagen($idContratoGlobal, $destinatario, $arrayDoctos, $idUser, $rechazos){
   $serviciouser = new ServiciosUsuarios();
   $query = new Query();
   #$destinatario = $this->regresaMailUsuarioIdDocto($idDoctoRechazado);

  $nombreDocto = '<br>';
  $listadoDoctos = '';
  $contador = 1;
  foreach ($arrayDoctos as $docto => $valor) {

   $comentario_extra = (!empty($valor[2]))?', '.$valor[2]:'';
   $nombreDocto .= $contador.".- ".  $valor[0] ." Motivo : ".$valor[1]. $comentario_extra.' <br>  ';
   $listadoDoctos = $valor[0].", ";
  $contador ++;
  }

  $nombreDocto =  trim($nombreDocto,', <br>');
  $listadoDoctos =  trim($listadoDoctos,', ');

  $asunto ='Documentacion necesaria';
  $cuerpo = '';
  
  $cuerpo .= '<h2 class=\"p3\"> Financiera CREA</h2>';

  $servidor = $_SERVER['SERVER_NAME'];
  $liga_servidor = ($servidor=='localhost')?SERVIDOR_LOCAL:SERVIDOR;
 # $cuerpo .= '<h3><small><p>Hemos revisado sus documentos y es necesario que nos vuelva a enviar los siguientes comprobantes, por favor verifique que las imagenes sean legibles antes de enviarlas, gracias! <p> Entre a la siguiente liga para subir los documentos <a href="'.$liga_servidor.'dashboard/contrato/cliente/documentos/cargarDocumentosnoval.php?id='.$idContratoGlobal.'&idU='.$idUser.'&rechazos='.$rechazos.'" target="_blank"> '.utf8_decode($nombreDocto).' </a> </p></small></h3>';   
  
      $cuerpo .= '<h3><small><p>Desafortunadamente no pudimos visualizar correctamente los siguientes documentos,<br>'.utf8_decode($nombreDocto).'<br> Por favor adjuntelos nuevamente dando click en  el siguiente enlace<a href="'.$liga_servidor.'dashboard/contrato/cliente/documentos/cargarDocumentosnoval.php?id='.$idContratoGlobal.'&idU='.$idUser.'&rechazos='.$rechazos.'" target="_blank"> Adjuntar documentos </a> </p></small></h3>'; 

  $cuerpo .= '<br><img width="393" height="131"  src="http://financieracrea.com/esfdesarrollo/images/firmaCREA24.jpg" alt="Financiera CREA" >';

  
  $destinatario = 'zuoran_17@hotmail.com';
 # $destinatario =  $idUser ;
  $serviciouser->enviarEmail($destinatario,$asunto,$cuerpo, $referencia='');

}

private function enviaCorreoNuevaImagen($idDocto, $status){
  $serviciouser = new ServiciosUsuarios();;
  $query = new Query();
  $destinatario = $this->regresaMailUsuarioIdDocto($idDocto);
  $idTipoDocto = $query->selectCampo('refdocumento',  'dbcontratosglobalesdocumentos', 'idcontratoglobaldocumento = '.$idDocto.'' );
  $contratoGlobalId = $query->selectCampo('refcontratoglobal',  'dbcontratosglobalesdocumentos', 'idcontratoglobaldocumento = '.$idDocto.'' );
  $nombreDocto = $query->selectCampo('decripcion',  'tbdocumento', 'iddocumento = '.$idTipoDocto.'' );
  $asunto ='Documentacion necesaria';
  $cuerpo = '';
#  $cuerpo .= '<img src="http://financieracrea.com/esfdesarrollo/images/logo.gif" alt="Financiera CREA" >';
  $cuerpo .= '<h2 class=\"p3\"> Financiera CREA</h2>';

  $servidor = $_SERVER['SERVER_NAME'];
  $liga_servidor = ($servidor=='localhost')?SERVIDOR_LOCAL:SERVIDOR;
  $cuerpo .= '<h3><small><p>Hemos revisado sus documentos es necesario que nos vuelva enviar el siguiente documento, por favor verifique que la imagen es legible antes de enviarla, gracias!  <a href="'.$liga_servidor.'dashboard/contrato/cliente/documentos/cargarDocumento.php?id='.$contratoGlobalId.'&docto='.$idTipoDocto.'" target="_blank"> '.utf8_decode($nombreDocto).' </a> </p></small></h3>';  


  $cuerpo .= '<br><img width="393" height="131"  src="http://financieracrea.com/esfdesarrollo/images/firmaCREA24.jpg" alt="Financiera CREA" >'; 

  
  $destinatario = 'zuoran_17@hotmail.com';
  $serviciouser->enviarEmail($destinatario,$asunto,$cuerpo, $referencia='');

}

public function regresaMailUsuarioIdDocto($idDocto){
  $query = new Query();
  $sqlUser = "SELECT U.usuario AS email_cliente
              FROM usuario U INNER JOIN  dbcontratosglobales CG on CG.usuario_id = U. usuario_id 
              INNER JOIN dbcontratosglobalesdocumentos CGD ON CGD.refcontratoglobal = CG.idcontratoglobal
              WHERE CGD.idcontratoglobaldocumento = $idDocto " ;
  $query->setQuery($sqlUser); 
  $res =$query->eject();
  $objMail = $query->fetchObject($res);
  return $objMail->email_cliente;
}

 
   function sanear_string($string)
   {

       $string = trim($string);

       $string = str_replace(
           array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
           array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
           $string
       );

       $string = str_replace(
           array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
           array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
           $string
       );

       $string = str_replace(
           array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
           array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
           $string
       );

       $string = str_replace(
           array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
           array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
           $string
       );

       $string = str_replace(
           array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
           array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
           $string
       );

       $string = str_replace(
           array('ñ', 'Ñ', 'ç', 'Ç'),
           array('n', 'N', 'c', 'C',),
           $string
       );

       $string = str_replace(
           array('(', ')', '{', '}',' '),
           array('', '', '', '',''),
           $string
       );



       return $string;
   }
 /*****************************       fin         ************************************************/

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
