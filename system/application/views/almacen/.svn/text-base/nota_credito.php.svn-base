<?php
//##########################################
$nombre = strtoupper($cliente->razon_social);
$dom = strtoupper($cliente->domicilio);
$col = strtoupper($cliente->colonia);
$cp = $cliente->codigo_postal;
$ciudad = strtoupper($cliente->localidad);
$tel = $cliente->telefono;
$rfc = strtoupper($cliente->rfc);
$lugar = strtoupper($cliente->estado);
$curp = strtoupper($cliente->curp);
$fecha = $factura->fecha;
$subtotal = $factura->monto_total - $factura->iva_total;
$monto_letras = $factura->monto_letras;
$total = $factura->monto_total;
$iva = $factura->iva_total;
//##########################################

$pdf = new Fpdf_factura('L','mm',array(214,178));
$pdf->AddPage();
$family = 'arial';
$style = '';
$size = 10;
$border = 0;
$h = 5;
$w1 = 17;
//$w2 = 22;
$w3 = 85;
$w4 = 20;
$w5 = 22;
$w6 = 22;
$pdf->SetFont($family,$style,$size);
$margen = 0;//right margin
// nombre
$o = 5;
$pdf->SetXY($margen+$o,0);
$pdf->MultiCell(100-$o,$h,$nombre,$border);
// dom
$o = 15;
$pdf->SetXY($margen+$o,5);
$pdf->MultiCell(100-$o,$h,$dom,$border);
// colonia
$o = 15;
$pdf->SetXY($margen+$o,10);
$pdf->MultiCell(100-$o-24,$h,$col,$border);
// cp
$o = 84;
$pdf->SetXY($margen+$o,10);
$pdf->MultiCell(100-$o,$h,$cp,$border);
// ciudad
$o = 13;
$pdf->SetXY($margen+$o,15);
$pdf->MultiCell(100-$o-38,$h,$ciudad,$border);
// tel
$o = 72;
$pdf->SetXY($margen+$o,15);
$pdf->MultiCell(100-$o,$h,$tel,$border);
// fecha
$margen = 100;
$pdf->SetXY($margen,0);
$pdf->Cell(20,$h,$fecha,$border,0,'C');
// RFC
$pdf->Cell(48,$h,$rfc,$border,0,'C');
// lugar
$pdf->SetXY($margen,15);
$pdf->Cell(38,$h,$lugar,$border,0,'C');
// CURP
$pdf->Cell(40,$h,$curp,$border,0,'C');
// conceptos
$margen = 2;
$y = 30;
$size = 10;
$pdf->SetFont($family,$style,$size);
foreach($conceptos as $concepto)
{
	$pdf->SetXY($margen,$y);
	$pdf->Cell($w1,$h,$concepto->cantidad,$border,0,'C');
	//$pdf->Cell($w2,$h,$concepto->clave,$border,0,'C');
	$pdf->Cell($w3,$h,$concepto->descripcion,$border,0);
	$pdf->Cell($w4,$h,$concepto->tag,$border,0,'C');
	$pdf->Cell($w5,$h,number_format($concepto->costo_unitario,2),$border,0,'C');
	$pdf->Cell($w6,$h,number_format($concepto->costo_total,2),$border,2,'R');
	$y = $pdf->GetY();
}
// subtotal
$o = $w1 +$w3 +$w4 +$w5;
$y = 83;
$pdf->SetXY($margen+$o,$y);
$pdf->Cell($w6,$h,number_format($subtotal,2),$border,2,'R');
$y = $pdf->GetY();
// iva
$pdf->SetXY($margen+$o,$y);
$pdf->Cell($w6,$h,number_format($iva,2),$border,2,'R');
$y = $pdf->GetY();
// total
$pdf->SetXY($margen+$o,$y);
$pdf->Cell($w6,$h,number_format($total,2),$border,2,'R');
$y = $pdf->GetY();
// letra
$pdf->SetXY($margen,$y-5);
$pdf->Cell($o+$w6,$h,'-- '.$monto_letras.' --',$border);
///
$pdf->Output();
?>
