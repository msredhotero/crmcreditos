<?php
class PDFReportesCrea extends FPDFO
{

   

    // Cabecera de página
    function Header()
    {

        $servicios = new Servicios();
        #$fechaContrato = (!empty($this->get_value('fecha_registro')))?$this->get_value('fecha_registro'):$this->getFecha();

           

        
         $fecha = date("Y-m-d");
         $fechaLetras = $servicios->obtenerFechaEnLetra( $fecha);
         $titulo = 'Cuidad de México '.$fechaLetras;
        // Logo
       # $this->Image('../reportes/arbolCrea.jpg',10,8,20, '','jpg');
         $ruta = __DIR__;
         $this->Image($ruta.'/arbolCrea.jpg',10,8,20, '','jpg');
        // Arial bold 15
        $this->SetFont('Arial','',11);
        // Movernos a la derecha
        $this->Cell(80);
        // Título
        $this->Cell(100,10,  $titulo,0,0,'R');
        // Salto de línea
        $this->Ln(20);
    }

    // Pie de página
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Número de página
        $this->AliasNbPages();
        $this->Cell(0,10,('Página ').$this->PageNo().'/{nb}',0,0,'C');
    }

}

?>
