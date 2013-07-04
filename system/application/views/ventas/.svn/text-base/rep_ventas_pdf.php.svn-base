<?php
$h=7;
$subtotal=(float)0;
$iva=(float)0;
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
$pdf->Cell(21,$h,'Folio Factura',1,0,'C');
if($fech==true)
	$pdf->Cell(16,$h,'Fecha',1,0,'C');
$pdf->Cell(98,$h,'Cliente',1,0,'C');
$pdf->Cell(18,$h,'Subtotal',1,0,'C');
$pdf->Cell(18,$h,'Iva',1,0,'C');
$pdf->Cell(18,$h,'Total',1,0,'C');
$pdf->ln($h);
$pdf->SetFont('Times','',8);
foreach($facturas as $factura){
	$pdf->Cell(10,$h,$factura->id_factura,1,0,'C');
	$pdf->Cell(21,$h,$factura->factura,1,0,'C');
	if($fech==true)
		$pdf->Cell(16,$h,implode("-", array_reverse(explode("-", $factura->fecha))),1,0,'C');
	$pdf->Cell(98,$h,$factura->cliente.'  ('.$factura->clave.')',1,0,'L');
	$pdf->Cell(18,$h,number_format($factura->monto_total-$factura->iva_total,2),1,0,'R');
	$pdf->Cell(18,$h,number_format($factura->iva_total,2),1,0,'R');
	$pdf->Cell(18,$h,number_format($factura->monto_total,2),1,0,'R');
	$subtotal+=$factura->monto_total-$factura->iva_total;
	$iva+=$factura->iva_total;
	$total+=$factura->monto_total;
	$pdf->ln($h);

}
$pdf->SetFont('Times','B',10);
$pdf->SetFillColor(225,0,0);
if($fech==true)
	$pdf->Cell(145,$h,'Total',1,0,'C',1);
else
	$pdf->Cell(129,$h,'Total',1,0,'C',1);
$pdf->Cell(18,$h,number_format($subtotal,2),1,0,'R',1);
$pdf->Cell(18,$h,number_format($iva,2),1,0,'R',1);
$pdf->Cell(18,$h,number_format($total,2),1,0,'R',1);
$pdf->Output();
unset($pdf);
exit();
?>
