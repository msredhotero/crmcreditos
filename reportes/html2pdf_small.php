<?php
//HTML2PDF by Clément Lavoillotte
//ac.lavoillotte@noos.fr
//webmaster@streetpc.tk
//http://www.streetpc.tk

require_once('tfpdf.php');

//function hex2dec
//returns an associative array (keys: R,G,B) from
//a hex html code (e.g. #3FE5AA)
function hex2dec2($couleur = "#000000"){
	$R = substr($couleur, 1, 2);
	$rouge = hexdec($R);
	$V = substr($couleur, 3, 2);
	$vert = hexdec($V);
	$B = substr($couleur, 5, 2);
	$bleu = hexdec($B);
	$tbl_couleur = array();
	$tbl_couleur['R']=$rouge;
	$tbl_couleur['V']=$vert;
	$tbl_couleur['B']=$bleu;
	return $tbl_couleur;
}

//conversion pixel -> millimeter at 72 dpi
function px2mm2($px){
	return $px*25.4/72;
}

function txtentities2($html){
	$trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
	$trans = array_flip($trans);
	return strtr($html, $trans);
}
////////////////////////////////////

class PDF_HTML_SMALL extends tFPDF
{
//variables of html parser
protected $B;
protected $I;
protected $U;
protected $HREF;
protected $fontList;
protected $issetfont;
protected $issetcolor;
protected $header;
protected $footer;

function __construct($orientation='P', $unit='mm', $format='A4', $utf8 = false)
{
	//Call parent constructor
	parent::__construct($orientation,$unit,$format, $utf8);
	//Initialization
	$this->B=0;
	$this->I=0;
	$this->U=0;
	$this->HREF='';
	$this->fontlist=array('arial', 'times', 'courier', 'helvetica', 'symbol');
	$this->issetfont=false;
	$this->issetcolor=false;
	$this->header ='';
	$this->footer ='';
}

public function SetHeader($header)
{
	$this->header = $header;

}

public function SetFooter($footer)
{
	$this->footer = $footer;
		
}

public function GetHeader()
{
	return $this->header ;

}

public function GetFooter()
{
	return $this->footer ;
		
}

public function Header2()
{
	$this->SetFont('Arial', 'B',11);
	$this->setX(-100);
	$this->Write(5, $this->GetHeader());
}

public function Footer2()
{
	$this->SetFont('Courier', 'B', 12);
	$this->SetY(-15);
	$this->Write(5, $this->GetFooter());


}

function Header()
{
    // Logo
    #$this->Image('logo_pb.png',10,8,33);
    // Arial bold 15
    $this->SetFont('Arial','B',10);
    // Movernos a la derecha
    $this->Cell(50);
    // Título
    $this->Cell(107,10,$this->GetHeader(),0,0,'R');
    // Salto de línea
    $this->Ln(10);
}

// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Número de página
    $this->Cell(0,8,''.$this->PageNo().'/{nb}',0,0,'C');
}


function WriteHTML($html)
{
	//HTML parser
	#echo $html;
	#echo "<br>";
	
	$html= strip_tags($html,"<span><centerb><center><CENTER><b><u><i><a><img><p><br><strong><em><font><tr><blockquote><div><li><h1><h2><h3><h4><h5><h6>"); //
	#echo $html; // supprime tous les tags sauf ceux reconnus
	$html=str_replace("\n",' ',$html); //remplace retour à la ligne par un espace
	$a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE); //éclate la chaîne avec les balises
	$x_temporal = '';
#echo "<pre>";
	#print_r($a);
	#echo "</pre>";
	foreach($a as $i=>$e)
	{
		#echo "<br>".$i ."vale :", $i , "e :=>".$e;
		if($i%2==0)
		{
			#echo "<br> i%2".($i%2)."<br>"; 
			//Text
			if($this->HREF){
				$this->PutLink($this->HREF,$e);
			}
			else{
				
				if($i>0){
					#echo "<br> a vale=>".$a[($i-1)];
					#echo "<br>".$e;					
				if($a[($i-1)] == 'center' || $a[($i-1)] == 'CENTER' || $a[($i-1)] == 'centerb' || $a[($i-1)] == 'CENTERB'){
					$this->Cell(0, 0, stripslashes(txtentities2($e)), '', '', 'C', '', '');
						
				}else if($a[($i-1)]== 'DIV' || $a[($i-1)]== 'div'){
					$this->Cell(0, 0, stripslashes(txtentities2($e)), '', '', 'JF', '', '');

				}else{
					#echo $a[($i-1)];
					#$this->Write(5,stripslashes(txtentities($e)));
					/*if($a[($i-1)]=='B' || $a[($i-1)]=='b'){
						$x_temporal =  stripslashes(txtentities($e));

					}else{
						$x_temporal .= stripslashes(txtentities($e));
						$this->Justify($x_temporal,180,4);
						$x_temporal = '';
					}*/
					$this->MultiCellDos(0,4,stripslashes(txtentities2($e)));
					
				}
				

				}
				
				
			}
		}
		else
		{
			#echo "<br> i%2 !=0".($i%2)."<br>"; 
			//Tag
			if($e[0]=='/')
				$this->CloseTag(strtoupper(substr($e,1)));
			else
			{
				//Extract attributes
				$a2=explode(' ',$e);
				$tag=strtoupper(array_shift($a2));
				$attr=array();
				foreach($a2 as $v)
				{
					if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
						$attr[strtoupper($a3[1])]=$a3[2];
				}
				$this->OpenTag($tag,$attr);
			}
		}
	}
}

function OpenTag($tag, $attr)
{
	//Opening tag
	switch($tag){
		case 'STRONG':
			$this->SetStyle('B',true);
			break;
		case 'EM':
			$this->SetStyle('I',true);
			break;
		case 'B':
		case 'I':
		case 'U':
			$this->SetStyle($tag,true);
			break;
		case 'A':
			$this->HREF=$attr['HREF'];
			break;
		case 'IMG':
			if(isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
				if(!isset($attr['WIDTH']))
					$attr['WIDTH'] = 0;
				if(!isset($attr['HEIGHT']))
					$attr['HEIGHT'] = 0;
				$this->Image($attr['SRC'], $this->GetX(), $this->GetY(), px2mm2($attr['WIDTH']), px2mm2($attr['HEIGHT']));
			}
			break;
		case 'TR':
		case 'BLOCKQUOTE':
		case 'BR':
			$this->Ln(5);
			break;
		case 'P':
			#$this->resetXY();
			$this->Ln(7);
			
			break;
		case 'FONT':
			if (isset($attr['COLOR']) && $attr['COLOR']!='') {
				$coul=hex2dec2($attr['COLOR']);
				$this->SetTextColor($coul['R'],$coul['V'],$coul['B']);
				$this->issetcolor=true;
			}
			if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist)) {
				$this->SetFont(strtolower($attr['FACE']));
				$this->issetfont=true;
			}
			break;

		case 'HR':		
			$this->PutLine();			
			break;		
		case 'H1':
			$this->Ln(5);
			$this->SetFontSize(28);
			break;
		case 'H2':
			$this->Ln(5);
			$this->SetFontSize(24);		
			break;
		case 'H3':
			$this->Ln(5);
			$this->SetFontSize(20);		
			break;
		case 'H4':
			$this->Ln(5);
			$this->SetFontSize(18);
			break;
		case 'H5':
			$this->Ln(5);
			$this->SetFontSize(14);
			break;
		case 'H6':
			$this->Ln(5);
			$this->SetFontSize(10);
			break;
		case 'SPAN':
			$this->Ln(5);
			$this->setX(-100);
			#$this->Write(5,'20 de juenio del 2020');
						
			#$this->SetY($this->GetY());	
			
			break;	
		case 'CENTERB':
			$this->SetStyle('B',true);
			break;
		case 'LI':
			$this->Ln(4);				
			$this->Write(5,'     ');				
			break;					

	}
}

function CloseTag($tag)
{
	//Closing tag
	if($tag=='STRONG')
		$tag='B';
	if($tag=='EM')
		$tag='I';
	if($tag=='B' || $tag=='I' || $tag=='U')
		$this->SetStyle($tag,false);
	if($tag=='A')
		$this->HREF='';
	if($tag=='FONT'){
		if ($this->issetcolor==true) {
			$this->SetTextColor(0);
		}
		if ($this->issetfont) {
			$this->SetFont('arial');
			$this->issetfont=false;
		}
	}
	if ($tag=='H1' || $tag=='H2' || $tag=='H3' || $tag=='H4' || $tag=='H5' || $tag=='H6'){
			$this->Ln(6);
			$this->SetFontSize(12);			
		}

	if($tag=='CENTERB'){
		$tag='B';
		$this->SetStyle($tag,false);
	}

}

function SetStyle($tag, $enable)
{
	//Modify style and select corresponding font
	$this->$tag+=($enable ? 1 : -1);
	$style='';
	foreach(array('B','I','U') as $s)
	{
		if($this->$s>0)
			$style.=$s;
	}
	$this->SetFont('',$style);
}

function PutLink($URL, $txt)
{
	//Put a hyperlink
	$this->SetTextColor(0,0,255);
	$this->SetStyle('U',true);
	$this->Write(5,$txt,$URL);
	$this->SetStyle('U',false);
	$this->SetTextColor(0);
}

function PutLine()
	{
		#Line(float x1, float y1, float x2, float y2)
		#$pdf->Line(50, 45, 210-50, 45); // 50mm from each edge 
		$this->Ln(4);
		#$this->Line($this->GetX()+65,$this->GetY(),$this->GetX()+80,$this->GetY());
		$pdf->Line(50, 45, 210-50, 45);
		$this->Write(5,'linea','');
		$this->Ln(3);
	}

function CellV($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
{
	// Output a cell
	$k=$this->k;
	if($this->y+$h>$this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak())
	{
		// Automatic page break
		$x=$this->x;
		$ws=$this->ws;
		if($ws>0)
		{
			$this->ws=0;
			$this->_out('0 Tw');
		}
		$this->AddPage($this->CurOrientation,$this->CurPageSize,$this->CurRotation);
		$this->x=$x;
		if($ws>0)
		{
			$this->ws=$ws;
			$this->_out(sprintf('%.3F Tw',$ws*$k));
		}
	}
	if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
	$s='';
	if($fill || $border==1)
	{
		if($fill)
			$op=($border==1) ? 'B' : 'f';
		else
			$op='S';
		$s=sprintf('%.2F %.2F %.2F %.2F re %s ',$this->x*$k,($this->h-$this->y)*$k,$w*$k,-$h*$k,$op);
	}
	if(is_string($border))
	{
		$x=$this->x;
		$y=$this->y;
		if(is_int(strpos($border,'L')))
			$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,$x*$k,($this->h-($y+$h))*$k);
		if(is_int(strpos($border,'T')))
			$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-$y)*$k);
		if(is_int(strpos($border,'R')))
			$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',($x+$w)*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
		if(is_int(strpos($border,'B')))
			$s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-($y+$h))*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
	}
	if($txt!='')
	{
		if($align=='R')
			$dx=$w-$this->cMargin-$this->GetStringWidth($txt);
		elseif($align=='C')
			$dx=($w-$this->GetStringWidth($txt))/2;
		elseif($align=='FJ')
		{
			//Set word spacing
			$wmax=($w-2*$this->cMargin);
			$this->ws=($wmax-$this->GetStringWidth($txt))/substr_count($txt,' ');
			$this->_out(sprintf('%.3F Tw',$this->ws*$this->k));
			$dx=$this->cMargin;
		}
		else
			$dx=$this->cMargin;
		$txt=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
		if($this->ColorFlag)
			$s.='q '.$this->TextColor.' ';
		$s.=sprintf('BT %.2F %.2F Td (%s) Tj ET',($this->x+$dx)*$k,($this->h-($this->y+.5*$h+.3*$this->FontSize))*$k,$txt);
		if($this->underline)
			$s.=' '.$this->_dounderline($this->x+$dx,$this->y+.5*$h+.3*$this->FontSize,$txt);
		if($this->ColorFlag)
			$s.=' Q';
		if($link)
		{
			if($align=='FJ')
				$wlink=$wmax;
			else
				$wlink=$this->GetStringWidth($txt);
			$this->Link($this->x+$dx,$this->y+.5*$h-.5*$this->FontSize,$wlink,$this->FontSize,$link);
		}
	}
	if($s)
		$this->_out($s);
	if($align=='FJ')
	{
		//Remove word spacing
		$this->_out('0 Tw');
		$this->ws=0;
	}
	$this->lasth=$h;
	if($ln>0)
	{
		$this->y+=$h;
		if($ln==1)
			$this->x=$this->lMargin;
	}
	else
		$this->x+=$w;
}

function Justify($text, $w, $h)
{
	$tab_paragraphe = explode("\n", $text);
	$nb_paragraphe = count($tab_paragraphe);
	$j = 0;

	while ($j<$nb_paragraphe) {

		$paragraphe = $tab_paragraphe[$j];
		$tab_mot = explode(' ', $paragraphe);
		$nb_mot = count($tab_mot);

		// Handle strings longer than paragraph width
		$k=0;
		$l=0;
		while ($k<$nb_mot) {

			$len_mot = strlen ($tab_mot[$k]);
			if ($len_mot<($w-5) )
			{
				$tab_mot2[$l] = $tab_mot[$k];
				$l++;	
			} else {
				$m=0;
				$chaine_lettre='';
				while ($m<$len_mot) {

					$lettre = substr($tab_mot[$k], $m, 1);
					$len_chaine_lettre = $this->GetStringWidth($chaine_lettre.$lettre);

					if ($len_chaine_lettre>($w-7)) {
						$tab_mot2[$l] = $chaine_lettre . '-';
						$chaine_lettre = $lettre;
						$l++;
					} else {
						$chaine_lettre .= $lettre;
					}
					$m++;
				}
				if ($chaine_lettre) {
					$tab_mot2[$l] = $chaine_lettre;
					$l++;
				}

			}
			$k++;
		}

		// Justified lines
		$nb_mot = count($tab_mot2);
		$i=0;
		$ligne = '';
		while ($i<$nb_mot) {

			$mot = $tab_mot2[$i];
			$len_ligne = $this->GetStringWidth($ligne . ' ' . $mot);

			if ($len_ligne>($w-5)) {

				$len_ligne = $this->GetStringWidth($ligne);
				$nb_carac = strlen ($ligne);
				$ecart = (($w-2) - $len_ligne) / $nb_carac;
				$this->_out(sprintf('BT %.3F Tc ET',$ecart*$this->k));
				$this->MultiCell($w,$h,$ligne);
				$ligne = $mot;

			} else {

				if ($ligne)
				{
					$ligne .= ' ' . $mot;
				} else {
					$ligne = $mot;
				}

			}
			$i++;
		}

		// Last line
		$this->_out('BT 0 Tc ET');
		$this->MultiCell($w,$h,$ligne);
		$tab_mot = '';
		$tab_mot2 = '';
		$j++;
	}
}

}//end of class


?>
