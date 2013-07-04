<?php

$n = count($facturas);

$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->Cell(0,5,$title.' ('.$periodo.')',0,1,'C');
$pdf->ln(3);
$pdf->Cell(0,5,'Resultados: '.$n,0,1,'C');
$pdf->SetFont('Times','',8);
$h = 5;// altura
$pdf->ln($h);
$x1 = $pdf->GetX();
$x2 = $x1+25;
$y = $pdf->GetY();
//201
$detalle = array(25,80,30,30,30);
$i = 1;
$borde = 1;
foreach($facturas as $row)
{
	$pdf->SetXY($x1,$y);
	$pdf->Cell(11,$h,"FOLIO:");
	$pdf->Cell(23,$h,$row->folio,$borde);
	$pdf->Cell(4);
	$pdf->Cell(12,$h,"FECHA:");
	$pdf->Cell(17,$h,$row->fecha,$borde);
	$pdf->Cell(0);
	$pdf->ln($h+1);
	$pdf->Cell(16,$h,"EMPRESA:");
	$pdf->Cell(0,$h,$row->empresa,$borde);
	$pdf->ln($h+1);
	$pdf->Cell(16,$h,"E. FISICO:");
	$pdf->Cell(0,$h,$espacio,$borde);
	$pdf->ln($h+1);
	$pdf->Cell(20,$h,"CLIENTE:");
	$pdf->Cell(0,$h,$row->cliente,$borde);
	$pdf->ln($h+1);
	$pdf->Cell(23,$h,"MONTO TOTAL:");
	$pdf->Cell(20,$h,'$ '.number_format($row->monto_total,4) ,$borde);
	$pdf->ln($h+3);
	// detalles de la factura
	$pdf->SetWidths($detalle);
	$pdf->SetAligns(array('C','C','C','C','C'));
	$pdf->Row(array("Cantidad","Producto","Costo unitario","Total","Impuesto"));
	$pdf->SetAligns(array('R','L','R','R','R'));
	foreach($detalles[$row->pedido] as $elemento)
	{
		$pdf->Row(array(number_format($elemento->cantidad,4), $elemento->producto, '$ '.number_format($elemento->costo_unitario,2), '$ '.number_format($elemento->costo_total,2), number_format($elemento->tasa_impuesto,2)));
	}
	if($i++ < $n) $pdf->AddPage();
}
$pdf->Output();
unset($pdf)
// $name=uniqid('reporte_').'.pdf';
// $pdf->Output($name);
// echo 'Terminado '.$name;
?>
