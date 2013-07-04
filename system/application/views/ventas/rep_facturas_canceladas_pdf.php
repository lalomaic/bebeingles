<?php
$h=7;
$total=(float)0;
$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->Cell(0,$h,$title.' ('.$periodo.')',0,1,'C');
$pdf->ln($h-3);
$pdf->SetFont('Times','B',11);
$pdf->Cell(0,0,$espacio,0,1,'C');
$pdf->ln($h);
$pdf->SetFont('Times','I',11);
$pdf->Cell(0,0,'Total de Facturas: '.$total_registros,0,1,'C');
$pdf->ln($h);
$pdf->SetFont('Times','B',10);
$pdf->Cell(10,$h,'Id',1,0,'C');
$pdf->Cell(22,$h,'Folio Factura',1,0,'C');
if($fech==true)
	$pdf->Cell(18,$h,'Fecha',1,0,'C');
$pdf->Cell(123,$h,'Cliente',1,0,'C');
$pdf->Cell(20,$h,'Monto',1,0,'C');
$pdf->ln($h);
$pdf->SetFont('Times','',8);
foreach($facturas as $factura){
	$pdf->Cell(10,$h,$factura->id_factura,1,0,'C');
	$pdf->Cell(22,$h,$factura->factura,1,0,'C');
	if($fech==true)
		$pdf->Cell(18,$h,implode("-", array_reverse(explode("-", $factura->fecha))),1,0,'C');
	$pdf->Cell(123,$h,$factura->cliente.'  ('.$factura->clave.')',1,0,'L');
	$pdf->Cell(20,$h,number_format($factura->monto_total,2),1,0,'R');
	$total+=$factura->monto_total;
	$pdf->ln($h);

}
$pdf->SetFont('Times','B',10);
$pdf->SetFillColor(225,0,0);
if($fech==true)
	$pdf->Cell(173,$h,'Total',1,0,'C',1);
else
	$pdf->Cell(155,$h,'Total',1,0,'C',1);
$pdf->Cell(20,$h,number_format($total,2),1,0,'R',1);
$pdf->Output();
unset($pdf);
exit();
?>
