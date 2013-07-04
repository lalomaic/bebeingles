<?php
$h=7;
//print_r($facturas);
//exit;
$contado=(float)0;
$credito=(float)0;
$consignacion=(float)0;
$canceladas=(float)0;
$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->Cell(0,$h,$title.' (DEL DÍA '.implode("-", array_reverse(explode("-", $fecha))).')',0,1,'C');
$pdf->ln($h-3);
$pdf->SetFont('Times','B',11);
$pdf->Cell(0,0,$espacio,0,1,'C');
$pdf->ln($h);
$pdf->SetFont('Times','I',11);
$pdf->Cell(0,0,'Total de Facturas: '.$total_registros,0,1,'C');
$pdf->ln($h);
$pdf->SetFont('Times','B',9);
$pdf->Cell(10,$h,'Id',1,0,'C');
$pdf->Cell(22,$h,'Folio Factura',1,0,'C');
$pdf->Cell(93,$h,'Cliente',1,0,'C');
$pdf->Cell(18,$h,'Contado',1,0,'C');
$pdf->Cell(18,$h,'Crédito',1,0,'C');
$pdf->Cell(18,$h,'Consignación',1,0,'C');
$pdf->Cell(18,$h,'Canceladas',1,0,'C');
$pdf->ln($h);
$pdf->SetFont('Times','',8);
foreach($facturas as $factura){
	$pdf->Cell(10,$h,$factura->id_factura,1,0,'C');
	$pdf->Cell(22,$h,$factura->factura,1,0,'C');
	$pdf->Cell(93,$h,$factura->cliente.'  ('.$factura->clave.')',1,0,'L');

	if($factura->estatus_factura==1 && $factura->estatus_general==1 && $factura->tipof<3){
		$contado+=$factura->monto_total;
		$pdf->Cell(18,$h,number_format($factura->monto_total,2),1,0,'R');
		$pdf->Cell(18,$h,'',1,0,'R');
		$pdf->Cell(18,$h,'',1,0,'R');
		$pdf->Cell(18,$h,'',1,0,'R');
	}

	if($factura->estatus_factura>1 && $factura->estatus_general==1 && $factura->tipof<3){
		$credito+=$factura->monto_total;
		$pdf->Cell(18,$h,'',1,0,'R');
		$pdf->Cell(18,$h,number_format($factura->monto_total,2),1,0,'R');
		$pdf->Cell(18,$h,'',1,0,'R');
		$pdf->Cell(18,$h,'',1,0,'R');
	}

	if($factura->estatus_factura>1 && $factura->estatus_general==1 && $factura->tipof==3){
		$consignacion+=$factura->monto_total;
		$pdf->Cell(18,$h,'',1,0,'R');
		$pdf->Cell(18,$h,'',1,0,'R');
		$pdf->Cell(18,$h,number_format($factura->monto_total,2),1,0,'R');
		$pdf->Cell(18,$h,'',1,0,'R');
	}
		
	if($factura->estatus_general==2){
		$canceladas+=$factura->monto_total;
		$pdf->Cell(18,$h,'',1,0,'R');
		$pdf->Cell(18,$h,'',1,0,'R');
		$pdf->Cell(18,$h,'',1,0,'R');
		$pdf->Cell(18,$h,number_format($factura->monto_total,2),1,0,'R');
	}
	$pdf->ln($h);

}
$venta_total=$contado+$credito;
$pdf->SetFont('Times','B',10);
$pdf->SetFillColor(225,0,0);
$pdf->Cell(125,$h,'Totales',1,0,'C',1);
$pdf->Cell(18,$h,number_format($contado,2),1,0,'R',1);
$pdf->Cell(18,$h,number_format($credito,2),1,0,'R',1);
$pdf->Cell(18,$h,number_format($consignacion,2),1,0,'R',1);
$pdf->Cell(18,$h,number_format($canceladas,2),1,0,'R',1);
$pdf->ln($h*3);
$pdf->SetFont('Times','B',11);
$pdf->Cell(0,0,'Venta Total del Día: '."\$".number_format($venta_total,2),0,1,'C');
unset($factura);
$pdf->Output();
exit();
?>
