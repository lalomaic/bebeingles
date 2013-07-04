<?php
$h=5;
$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->Cell(0,$h,utf8_decode($title),0,1,'C');
$pdf->ln($h);
if($ef){
	$pdf->SetFont('Times','I',12);
	$pdf->Cell(0,$h,utf8_decode($esp[0]['tag']),0,1,'C');
	$pdf->ln($h);
}
$total_cargos=(float)0;
$total_abonos=(float)0;
$total_saldos=(float)0;
foreach($bloques as $pid=>$proveedor){
	$total_cargos_prov=(float)0;
	$total_abonos_prov=(float)0;
	$total_saldos_prov=(float)0;
	$pdf->SetFont('Times','B',12);
	$pdf->Cell(0,0,utf8_decode($proveedores[$pid]));
	//$pdf->ln($h-4);
	foreach($proveedor as $eid=>$espacio){
		$pdf->ln($h);
		if(!$ef){
			$pdf->SetFont('Times','I',12);
			$pdf->Cell(0,0,utf8_decode($espacios[$eid]));
			$pdf->ln($h-2);
		}
		foreach($espacio as $fid=>$id_factura){
			//$pdf->SetFont('Times','I',11);
			//$pdf->Cell(0,0,'Id Factura ('.$facturas[$fid]['id']).')';
			//$pdf->ln($h-2);
			$saldo=(float)$facturas[$fid]['monto_total'];
			$total_cargos+=$saldo;
			$total_cargos_prov+=$saldo;
			$ban=true;
			$pdf->SetFont('Times','B',10);
			$pdf->Cell(20,$h,'Id Factura',1,0,'C');
			$pdf->Cell(30,$h,'Fecha',1,0,'C');
			$pdf->Cell(37,$h,'Folio Factura',1,0,'C');
			$pdf->Cell(37,$h,'Monto Total',1,0,'C');
			$pdf->Cell(37,$h,'Abonos',1,0,'C');
			$pdf->Cell(37,$h,'Saldo',1,0,'C');
			$pdf->ln($h);
			foreach($id_factura as $movimiento){
				$pdf->SetFont('Times','',10);
				$pdf->SetFillcolor(255,255,255);
				$saldo-=(float)$movimiento['monto_pagado'];
				if($ban==true){
					$pdf->Cell(20,$h,$movimiento['id_factura'],1,0,'C',1);
					$pdf->Cell(30,$h,implode("-", array_reverse(explode("-", $movimiento['fecha_alta']))),1,0,'C',1);
					$pdf->Cell(37,$h,$movimiento['factura'],1,0,'L',1);
					$pdf->Cell(37,$h,number_format($movimiento['monto_total'],2),1,0,'R',1);
					$pdf->Cell(37,$h,'',1,0,'R',1);
					$pdf->Cell(37,$h,number_format($movimiento['monto_total'],2),1,0,'R',1);
					$pdf->ln($h);
					if($movimiento['fecha']){
						$pdf->Cell(20,$h,$movimiento['id_factura'],1,0,'C',1);
						$pdf->Cell(30,$h,implode("-", array_reverse(explode("-", $movimiento['fecha']))),1,0,'C',1);
						$pdf->Cell(37,$h,$movimiento['factura'],1,0,'L',1);
						$pdf->Cell(37,$h,'',1,0,'R',1);
						$pdf->Cell(37,$h,number_format($movimiento['monto_pagado'],2),1,0,'R',1);
						$pdf->Cell(37,$h,number_format($saldo,2),1,0,'R',1);
						$pdf->ln($h);
					}
				}
				else{
					$pdf->Cell(20,$h,$movimiento['id_factura'],1,0,'C',1);
					$pdf->Cell(30,$h,implode("-", array_reverse(explode("-", $movimiento['fecha']))),1,0,'C',1);
					$pdf->Cell(37,$h,$movimiento['factura'],1,0,'L',1);
					$pdf->Cell(37,$h,'',1,0,'R',1);
					$pdf->Cell(37,$h,number_format($movimiento['monto_pagado'],2),1,0,'R',1);
					$pdf->Cell(37,$h,number_format($saldo,2),1,0,'R',1);
					$pdf->ln($h);
				}
				$ban=false;
				$total_abonos+=$movimiento['monto_pagado'];
				$total_abonos_prov+=$movimiento['monto_pagado'];
				$total_saldos=$total_cargos-$total_abonos;
				$total_saldos_prov=$total_cargos_prov-$total_abonos_prov;
			}
			//$pdf->ln($h);
		}
		//$pdf->ln($h);
	}
	$pdf->SetFont('Arial','B',10);
	$pdf->SetFillcolor(225,0,0);
	$pdf->Cell(87,$h,"Total x Proveedor",1,0,'L',1);
	$pdf->Cell(37,$h,number_format($total_cargos_prov,2),1,0,'R',1);
	$pdf->Cell(37,$h,number_format($total_abonos_prov,2),1,0,'R',1);
	$pdf->Cell(37,$h,number_format($total_saldos_prov,2),1,0,'R',1);
	$pdf->ln($h*3);
}
$pdf->SetFont('Arial','B',10);
$pdf->SetFillcolor(225,0,0);
$pdf->Cell(87,$h,"Total Global",1,0,'L',1);
$pdf->Cell(37,$h,number_format($total_cargos,2),1,0,'R',1);
$pdf->Cell(37,$h,number_format($total_abonos,2),1,0,'R',1);
$pdf->Cell(37,$h,number_format($total_saldos,2),1,0,'R',1);
$pdf->Output();
unset($pdf);
exit();
?>
