<?php
$pdf=new Fpdf_multicell();
$pdf->lMargin=20;
$pdf->AddPage('P');
$pdf->SetFont('Times','',8);
$pdf->Cell(0,5,$title,0,1,'L');
$pdf->ln(0.5);
$pdf->Cell(0,5,'Periodo: '.$periodo,0,1,'L');
$pdf->Cell(0,5,'Impresi�n: '.date("d-m-Y").' Resultados: '.count($salidas),0,1,'L');
$pdf->ln(.5);
//$pdf->ln(5);
$pdf->SetFont('Times','',8);
//263
$pdf->SetAligns(array('C','C','C','C','C'));
$pdf->SetWidths(array(10,15,70,20,20));
$pdf->SetFont('Times','B',8);
$pdf->ln(10);
$pdf->SetFillColor(192,192,192);
$pdf->Row(array('#','Id Prod','Producto','Cantidad','Unidad M.'));
$gtotal=0;
$tu=0;// total unids
$i=1;
$pdf->SetAligns(array('L','C','L','R','R'));
$pdf->SetFont('Times','',8);
foreach($salidas as $row)
{
	if(!($i%2))
		$pdf->SetFillColor(230,230,250);
	else
		$pdf->SetFillColor(255,255,255);
	$tu+=(float)$row->cantidad;
	$pdf->Row(array($i, $row->pid, $row->producto, number_format($row->cantidad,4), $row->unidad));
	$i+=1;
}
$pdf->SetFillColor(192,192,192);
$pdf->Row(array('','','TOTALES', number_format($tu,4), ''));
$pdf->Output();
?>
