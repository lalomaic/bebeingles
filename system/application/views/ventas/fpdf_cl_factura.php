<?php

class Fpdf_cl_factura extends Fpdf_class
{
//variables of html parser
var $B;
var $I;
var $U;
var $HREF;
var $fontList;
var $issetfont;
var $issetcolor;
var $widths;
var $aligns;
var $con_color;
var $border;
var $f;
var $outlines=array();
var $OutlineRoot;
var $_toc=array();
var $_numbering=false;
var $_numberingFooter=false;
var $_numPageNum=1;
var $file;
var $orientation;

function Fpdf_cl_factura($orientation="P", $unit='mm', $format='letter', $bgcolor=0)
{
    //Call parent constructor
    $this->Fpdf_class($orientation,$unit,$format,$bgcolor);
    //Initialization
    $this->B=0;
    $this->I=0;
    $this->U=0;
    $this->HREF='';
    $this->con_color=1;
    $this->tableborder=1;
    $this->tdbegin=false;
    $this->tdwidth=0;
    $this->tdheight=0;
    $this->tdalign="L";
    $this->tdbgcolor=false;
    $this->border='LTR';
    $this->oldx=0;
    $this->oldy=0;
    $this->SetFillColor(255,255,255);
    $this->fontlist=array("arial","times","courier","helvetica","symbol");
    $this->issetfont=false;
    $this->issetcolor=false;
	$this->bgcolor=$bgcolor;
}


function SetWidths($w)
{
	//Set the array of column widths
	$this->widths=$w;
}

function SetAligns($a)
{
	//Set the array of column alignments
	$this->aligns=$a;
}

function Row($data)
{
	//Calculate the height of the row
	$nb=0;
	for($i=0;$i<count($data);$i++)
		$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
	$h=5*$nb;
	//Issue a page break first if needed
	$this->CheckPageBreak($h);
	//Draw the cells of the row
	for($i=0;$i<count($data);$i++)
	{
		$w=$this->widths[$i];
		$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
		//Save the current position
		$x=$this->GetX();
		$y=$this->GetY();
		//Draw the border
		$this->Rect($x,$y,$w,$h);
		//Print the text
		$this->MultiCell($w,5,$data[$i],$this->border,$a, $this->bgcolor);
		//Put the position to the right of the cell
		$this->SetXY($x+$w,$y);
	}
	//Go to the next line
	$this->Ln($h);
}

function CheckPageBreak($h)
{
	//If the height h would cause an overflow, add a new page immediately
	if($this->GetY()+$h>$this->PageBreakTrigger)
		$this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
	//Computes the number of lines a MultiCell of width w will take
	$cw=&$this->CurrentFont['cw'];
	if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	$s=str_replace("\r",'',$txt);
	$nb=strlen($s);
	if($nb>0 and $s[$nb-1]=="\n")
		$nb--;
	$sep=-1;
	$i=0;
	$j=0;
	$l=0;
	$nl=1;
	while($i<$nb)
	{
		$c=$s[$i];
		if($c=="\n")
		{
			$i++;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			continue;
		}
		if($c==' ')
			$sep=$i;
		$l+=$cw[$c];
		if($l>$wmax)
		{
			if($sep==-1)
			{
				if($i==$j)
					$i++;
			}
			else
				$i=$sep+1;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
		}
		else
			$i++;
	}
	return $nl;
}

function Header()
{
    //Logo
    if($this->orientation=="P" or $this->orientation=='')
		$this->Image(base_url().'images/logo_pdf.jpg',10,10,95);
    $this->SetFont('Times','B',16);
	$this->ln(1);
    //Movernos a la derecha
}

//Pie de p gina
function Footer()
{
    //Posicion: a 1,2 cm del final
    $this->SetY(-12);
    //Arial italic 8
    $this->SetFont('Times','I',8);
    //Numero de pagina
    $this->Cell(0,10,'Pagina No '.$this->PageNo(),0,0,'C');
}




}//End of class

?>
