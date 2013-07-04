<?php
$h=7;
$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->Cell(0,$h,utf8_decode($title).' ('.$periodo.')',0,1,'C');
$pdf->ln($h);
foreach($bloques as $pid=>$proveedor){
	$global_cargos=(float)0;
	$global_abonos=(float)0;
	$global_saldos=(float)0;
	$pdf->SetFont('Times','B',12);
	$pdf->Cell(0,0,utf8_decode($proveedores[$pid]));
	$pdf->ln($h-4);
	$pdf->Cell(87,$h,'Espacio F�sico',1,0,'L');
	$pdf->Cell(37,$h,'Cargos',1,0,'C');
	$pdf->Cell(37,$h,'Abonos',1,0,'C');
	$pdf->Cell(37,$h,'Saldo',1,0,'C');
	$pdf->ln($h);
	foreach($proveedor as $eid=>$espacio){
		$total_cargos=(float)0;
		$total_abonos=(float)0;
		$total_saldos=(float)0;
		foreach($espacio as $fid=>$id_factura){
			$saldo=(float)$facturas[$fid]['monto_total'];
			$total_cargos+=$saldo;
			$pdf->SetFont('Times','',10);
			$pdf->SetFillcolor(255,255,255);
			foreach($id_factura as $movimiento){
				$saldo-=(float)$movimiento['monto_pagado'];
				$total_abonos+=$movimiento['monto_pagado'];
				$total_saldos=$total_cargos-$total_abonos;
			}
		}
		$global_cargos+=$total_cargos;
		$global_abonos+=$total_abonos;
		$global_saldos=$global_cargos-$global_abonos;
		$pdf->Cell(87,$h,$espacios[$eid],1,0,'L');
		$pdf->Cell(37,$h,($total_cargos)==0?'':number_format($total_cargos,2),1,0,'R');
		$pdf->Cell(37,$h,($total_abonos)==0?'':number_format($total_abonos,2),1,0,'R');
		$pdf->Cell(37,$h,number_format($total_saldos,2),1,0,'R');
		$pdf->ln($h);
	}
	$pdf->SetFont('Arial','B',10);
	$pdf->SetFillcolor(225,0,0);
	$pdf->Cell(87,$h,"Totales globales (Cargos - Abonos = Saldo)",1,0,'L',1);
	$pdf->Cell(37,$h,number_format($global_cargos,2),1,0,'R',1);
	$pdf->Cell(37,$h,number_format($global_abonos,2),1,0,'R',1);
	$pdf->Cell(37,$h,number_format($global_saldos,2),1,0,'R',1 );
	$pdf->ln($h+$h+$h);
}
$pdf->Output();
unset($pdf);
exit();
?>
