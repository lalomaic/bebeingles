<?php
$n = count($generales->all);
$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->SetFont('Times','B',10);
$pdf->Cell(0,5,$title,'B',1,'L');
//$pdf->ln(3);
$pdf->SetFont('Times','',8);
$h = 5;// altura
$pdf->ln($h);
$x1 = $pdf->GetX();
$x2 = $x1+25;
$y = $pdf->GetY();
//201
$generalesw= array(7,16,35,35,20,20,20,16,30);
$detallesw= array(10,15,100,25,20,20);
$i = 1;
$borde = 1;
foreach($generales->all as $row) {
	$pdf->SetXY($x1,$y);
	$pdf->SetWidths($generalesw);
	$pdf->Cell(15,$h,"Id Compra:");
	$pdf->Cell(15,$h,$row->id,$borde);
	$pdf->Cell(4);
	$pdf->Cell(26,$h,"FECHA ENTREGA:");
	$pdf->Cell(17,$h,$row->fecha_entrega_f,$borde);
	$pdf->Cell(4);
	$pdf->Cell(21,$h,"CAPTURISTA:");
	$pdf->Cell(0,$h,utf8_decode($row->usuario." (Alta: ".$row->fecha_alta.")"),$borde);
	$pdf->ln($h+1);
	$pdf->Cell(16,$h,"EMPRESA:");
	$pdf->Cell(0,$h,utf8_decode($row->empresa),$borde);
	$pdf->ln($h+1);
	$pdf->Cell(16,$h,"SUCURSAL:");
	$pdf->Cell(0,$h,utf8_decode($row->espacio),$borde);
	$pdf->ln($h+1);
	$pdf->Cell(20,$h,"PROVEEDOR:");
	$pdf->Cell(0,$h,utf8_decode($row->proveedor),$borde);
	$pdf->ln($h+1);
	$pdf->Cell(20,$h,"MARCA:");
	$pdf->Cell(0,$h,utf8_decode($row->marca),$borde);
	$pdf->ln($h+1);
	$pdf->Cell(28,$h,"TEL�FONOS Y FAX:");
	$pdf->Cell(0,$h,utf8_decode("LADA: $row->lada TEL: $row->telefono FAX: $row->fax"),$borde);
	$pdf->ln($h+3);
	// detalles de la factura
	$pdf->SetWidths($detallesw);
	$pdf->SetAligns(array("C","R","L","R","R","R","R"));
	$pdf->SetFont('Times','B',9);
	$pdf->SetFillColor(200,0,0);

	$pdf->Row(array("#","Cantidad","Producto","Costo unitario","IVA","SubTotal"));
	$gtotal=0;
	$giva=0;
	$pdf->SetFont('Times','',8);
	$i=1; $pares_t=0;
	foreach($detalles->all as $lista){
		$subtotal=($lista->cantidad*$lista->costo_unitario);
		$iva=(($lista->cantidad*$lista->costo_unitario)*$lista->tasa_impuesto/(100+ $lista->tasa_impuesto));
		if(!($i%2))
			$pdf->SetFillColor(200,0,0);
		else
			$pdf->SetFillColor(255,0,0);

		$pdf->Row(array($i, round($lista->cantidad), utf8_decode("$lista->producto # ". ($lista->presentacion/10)), number_format($lista->costo_unitario, 4, ".", ","), number_format($iva, 4, ".", ","), number_format($subtotal, 4, ".", ",")));
		$gtotal+=$subtotal;
		$giva+=$iva;
		$pares_t+=$lista->cantidad;
		$i+=1;
	}
	$pdf->SetFont('Times','B',9);
	$pdf->SetFillColor(200,0,0);
	$pdf->Row(array('',$pares_t, "", 'TOTALES',  number_format($giva, 3, ".", ","),number_format($gtotal, 3, ".", ",")));
	$pdf->SetFont('Times','',8);
	if($i++ < $n) $pdf->AddPage();
}
$pdf->Output();
?>
