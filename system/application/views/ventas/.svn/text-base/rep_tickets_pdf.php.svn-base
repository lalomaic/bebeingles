<?php
$h=5;
$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->Cell(0,$h,$title.' ('.$periodo.')',0,1,'C');
$pdf->ln($h);
$pdf->SetFont('Times','B',12);
$pdf->Cell(0,$h,$empresa,0,1,'C');
$pdf->SetFont('Times','I',11);
$pdf->Cell(0,$h,$espacio);
$pdf->ln($h);
$anchos=array(35,25,25,25,30,50);
$pdf->SetWidths($anchos);
//201
$pdf->SetFont('Times','B',8);
$pdf->SetAligns(array('C','C','C','C','C','C'));
$pdf->SetFillColor(230,230,250);
$pdf->Row(array('Fecha','Ticket Inicial','Ticket Final','# Tickets','Venta Total','Tickets Cancelados'));
$pdf->SetFillColor(255,255,255);
$pdf->SetAligns(array('L','C','C','C','R','L'));
$pdf->SetFont('Times','',8);
$tt=0;
$it=(float)0;
foreach($tickets as $t)
{
	$tt+=$t['tt'];
	$it+=(float)$t['it'];
	$pdf->Row(array($t['f'],$t['ti'],$t['tf'],number_format($t['tt']),'$ '.number_format($t['it'],2),$t['tc']));
}
$pdf->SetFillColor(230,230,250);
$pdf->SetFont('Times','B',8);
$pdf->Cell($anchos[0]+$anchos[1]);
$pdf->Cell($anchos[2],$h,'TOTALES',1,0,'R',1);
$pdf->Cell($anchos[3],$h,number_format($tt),1,0,'C',1);
$pdf->Cell($anchos[4],$h,'$ '.number_format($it,2),1,0,'R',1);
$pdf->SetFillColor(255,255,255);

$pdf->Output();
unset($pdf)
// $name=uniqid('reporte_').'.pdf';
// $pdf->Output($name);
// echo 'Terminado '.$name;
?>
