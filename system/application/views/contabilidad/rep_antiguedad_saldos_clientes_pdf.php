<?php
$h=5;
//$pdf=new Fpdf_multicell();
$pdf= new Optimizar_memoria();
$name='antiguedad_clientes'. date("d_m_Y") .'.pdf';
$pdf->Open($name);
$pdf->AddPage();
$pdf->SetFont('Times','B',15);
$pdf->Cell(0,$h,utf8_decode($empresa[0]['razon_social']),0,1,'C');
$pdf->ln($h);
$pdf->SetFont('Times','B',14);
$pdf->Cell(0,$h,utf8_decode($title).' ('.$periodo.')',0,1,'C');
$pdf->ln($h);
if($ef){
	$pdf->SetFont('Times','I',12);
	$pdf->Cell(0,$h,utf8_decode($esp[0]['tag']),0,1,'C');
	$pdf->ln($h);
}
$total_cargos=(float)0;
$total_abonos=(float)0;
$total_saldos=(float)0;
foreach($bloques as $cid=>$cliente){
	$total_cargos_clien=(float)0;
	$total_abonos_clien=(float)0;
	$total_saldos_clien=(float)0;
	$saldo_ini=(float)0;
	$abono=(float)0;
	$pdf->SetFont('Times','B',12);
	$pdf->Cell(0,0,utf8_decode($clientes[$cid]));
	//$pdf->ln($h-4);
	foreach($cliente as $eid=>$espacio){
		$pdf->ln($h);
		if(!$ef){
			$pdf->SetFont('Times','I',12);
			$pdf->Cell(0,0,utf8_decode($espacios[$eid]));
			$pdf->ln($h-2);
		}
		$pdf->SetFont('Times','B',10);
		$pdf->Cell(20,$h,'Id Factura',1,0,'C');
		$pdf->Cell(30,$h,'Fecha',1,0,'C');
		$pdf->Cell(37,$h,'Folio Factura',1,0,'C');
		$pdf->Cell(37,$h,'Cargos',1,0,'C');
		$pdf->Cell(37,$h,'Abonos',1,0,'C');
		$pdf->Cell(37,$h,'Saldo',1,0,'C');
		$pdf->ln($h);
		foreach($espacio as $fid=>$id_factura){
			//$pdf->SetFont('Times','I',11);
			//$pdf->Cell(0,0,'Id Factura ('.$facturas[$fid]['id']).')';
			//$pdf->ln($h-2);
			$saldo=$saldo_ini+$facturas[$fid]['monto_total'];
			$total_cargos_clien+=$facturas[$fid]['monto_total'];
			$cargo_factura=$facturas[$fid]['monto_total'];
			$abono_factura=(float)0;
			$ban=true;
			foreach($id_factura as $movimiento){
				$pdf->SetFont('Times','',10);
				$pdf->SetFillcolor(255,255,255);
				if($fecha!=''){
					$saldo-=$movimiento['monto_pagado'];
					$abono+=$movimiento['monto_pagado'];
					$abono_factura+=$movimiento['monto_pagado'];
					if($ban==true){
							
						if($movimiento['fecha_alta']>$fecha){
							$saldo-=$movimiento['monto_total'];
							$total_cargos_clien-=$movimiento['monto_total'];
						}
						if(!$movimiento['fecha'] || $movimiento['fecha']>$fecha){
							$saldo+=$movimiento['monto_pagado'];
							$abono-=$movimiento['monto_pagado'];
							$abono_factura-=$movimiento['monto_pagado'];
						}
							
					}
					else{
						if(!$movimiento['fecha'] || $movimiento['fecha']>$fecha){
							$saldo+=$movimiento['monto_pagado'];
							$abono-=$movimiento['monto_pagado'];
							$abono_factura-=$movimiento['monto_pagado'];
						}
					}
				}
				if($fecha==''){
					$saldo-=$movimiento['monto_pagado'];
					$abono+=$movimiento['monto_pagado'];
					$abono_factura+=$movimiento['monto_pagado'];
					if($ban==true){
						if($movimiento['fecha']){
							$saldo-=0;
							$abono+=0;
							$abono_factura+=0;
						}
					}
					else{
						$total_cargos_clien-=$movimiento['monto_total'];
						if($movimiento['fecha']){
							$saldo-=0;
							$abono+=0;
							$abono_factura+=0;
						}
					}
				}
			}
			$ban=false;
			$total_abonos_clien=$abono;
			$total_saldos_clien=$total_cargos_clien-$total_abonos_clien;
			$saldo_ini=$saldo;
			$pdf->Cell(20,$h,$movimiento['id_factura'],1,0,'C',1);
			$pdf->Cell(30,$h,implode("-", array_reverse(explode("-", $movimiento['fecha_alta']))),1,0,'C',1);
			$pdf->Cell(37,$h,$movimiento['factura'],1,0,'L',1);
			$pdf->Cell(37,$h,($cargo_factura)==0?'':number_format($cargo_factura,2),1,0,'R',1);
			$pdf->Cell(37,$h,($abono_factura)==0?'':number_format($abono_factura,2),1,0,'R',1);
			$pdf->Cell(37,$h,number_format($saldo,2),1,0,'R',1);
			$pdf->ln($h);
			$pdf->SetFillcolor(225,0,0);
			$pdf->Cell(198,$h-4,'',1,0,'C',1);
			$pdf->ln();
		}
	}
	$total_cargos+=$total_cargos_clien;
	$total_abonos+=$total_abonos_clien;
	$total_saldos=$total_cargos-$total_abonos;
	$pdf->SetFont('Arial','B',10);
	$pdf->SetFillcolor(225,0,0);
	$pdf->Cell(87,$h,"Total x Proveedor",1,0,'L',1);
	$pdf->Cell(37,$h,number_format($total_cargos_clien,2),1,0,'R',1);
	$pdf->Cell(37,$h,number_format($total_abonos_clien,2),1,0,'R',1);
	$pdf->Cell(37,$h,number_format($total_saldos_clien,2),1,0,'R',1);
	$pdf->ln($h*3);
}
$pdf->SetFont('Arial','B',10);
$pdf->SetFillcolor(225,0,0);
$pdf->Cell(87,$h,"Total Global",1,0,'L',1);
$pdf->Cell(37,$h,number_format($total_cargos,2),1,0,'R',1);
$pdf->Cell(37,$h,number_format($total_abonos,2),1,0,'R',1);
$pdf->Cell(37,$h,number_format($total_saldos,2),1,0,'R',1);
$pdf->Output();
?>
<script>location.href='<?=base_url()?>tmp/<?=$name?>'</script>
<? unset($pdf);?>

