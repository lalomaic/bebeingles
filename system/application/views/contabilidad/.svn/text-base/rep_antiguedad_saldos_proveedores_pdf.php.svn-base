<?php
$h=5;
//$pdf=new Fpdf_multicell();
$pdf= new Optimizar_memoria();
$name='antiguedad_proveedores_'. date("d_m_Y") .'.pdf';
$pdf->Open($name);
$pdf->AddPage();
$pdf->SetFont('Times','B',15);
$pdf->Cell(0,$h,utf8_decode($empresa->razon_social),0,1,'C');
$pdf->ln($h);
$pdf->SetFont('Times','B',14);
$pdf->Cell(0,$h,utf8_decode($title).' ('.$periodo.')',0,1,'C');
$pdf->ln($h);
if($ef){
	$pdf->SetFont('Times','I',12);
	$pdf->Cell(0,$h,utf8_decode($esp[0]['tag']),'B',1,'C');
	$pdf->ln($h);
}
$total_cargos=(float)0;
$total_abonos=(float)0;
$total_saldos=(float)0;
$is=0;

foreach($bloques as $eid=>$espacio){
	if($is>0 and $salto_pagina==true)
		$pdf->AddPage();
	$total_cargos_esp=(float)0;
	$total_abonos_esp=(float)0;
	$total_saldos_esp=(float)0;
	$saldo_ini=(float)0;
	$abono=(float)0;
	$pdf->SetFont('Times','B',12);
	$pdf->Cell(0,0,utf8_decode($espacios[$eid]));
	//$pdf->ln($h-4);
	//		print_r($espacios);
	//		print_r($bloq);
	foreach($espacio as $pid=>$proveedor){
		$pdf->ln($h);
		if(!$ef){
			$pdf->SetFont('Times','I',12);
			$pdf->Cell(0,0,utf8_decode($proveedores[$pid])."  (".$bloq[$eid][$pid]['marca'].")");
			$pdf->ln($h-2);
		}
		$pdf->SetFont('Times','B',10);
		$pdf->Cell(20,$h,'Id Factura',1,0,'C');
		$pdf->Cell(30,$h,'Fecha',1,0,'C');
		$pdf->Cell(20,$h,'Folio',1,0,'C');
		$pdf->Cell(25,$h,'Vencimiento',1,0,'C');
		$pdf->Cell(30,$h,'Cargos',1,0,'C');
		$pdf->Cell(30,$h,'Abonos',1,0,'C');
		$pdf->Cell(30,$h,'Saldo',1,0,'C');
		$pdf->ln($h);
		foreach($proveedor as $fid=>$id_factura){
			//$pdf->SetFont('Times','I',11);
			//$pdf->Cell(0,0,'Id Factura ('.$facturas[$fid]['id']).')';
			//$pdf->ln($h-2);
			//print_r($facturas[$fid]);exit;
			$saldo=$saldo_ini+$facturas[$fid]['monto_total'];
			$total_cargos_esp+=$facturas[$fid]['monto_total'];
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
							$total_cargos_esp-=$movimiento['monto_total'];
						}
						if(!$movimiento['fecha'] || $movimiento['fecha'] == $fecha){
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
						$total_cargos_esp-=$movimiento['monto_total'];
						if($movimiento['fecha']){
							$saldo-=0;
							$abono+=0;
							$abono_factura+=0;
						}
					}
				}
			}
			$ban=false;
			$total_abonos_esp=$abono;
			$total_saldos_esp=$total_cargos_esp-$total_abonos_esp;
			$saldo_ini=$saldo;
			$pdf->Cell(20,$h,$movimiento['id_factura'],1,0,'C',1);
			$pdf->Cell(30,$h,implode("-", array_reverse(explode("-", $movimiento['fecha_alta']))),1,0,'C',1);
			$pdf->Cell(20,$h,$movimiento['factura'],1,0,'L',1);
			//Calcular dias de vencimiento
			$hoy=date("Y-m-d");
			$dias_vencimiento=round((strtotime($hoy)-strtotime($movimiento['vencimiento']))/(3600* 24), 0);
			if($dias_vencimiento<0)
				$dias_vencimiento=0;
			$pdf->Cell(25,$h,implode("-", array_reverse(explode("-", $movimiento['vencimiento'])))."  $dias_vencimiento",1,0,'L',1);
			$pdf->Cell(30,$h,($cargo_factura)==0?'':number_format($cargo_factura,2),1,0,'R',1);
			$pdf->Cell(30,$h,($abono_factura)==0?'':number_format($abono_factura,2),1,0,'R',1);
			$pdf->Cell(30,$h,number_format($saldo,2),1,0,'R',1);
			$pdf->ln($h);
			$pdf->SetFillcolor(187,187,187);
			$pdf->Cell(180,$h-4,'',1,0,'C',1);
			$pdf->ln();

		}
	}
	$total_cargos+=$total_cargos_esp;
	$total_abonos+=$total_abonos_esp;
	$total_saldos=$total_cargos-$total_abonos;
	$pdf->SetFont('Arial','B',10);
	$pdf->SetFillcolor(187,187,187);
	$pdf->Cell(95,$h,"Total por Sucursal",1,0,'L',1);
	$pdf->Cell(30,$h,number_format($total_cargos_esp,2),1,0,'R',1);
	$pdf->Cell(30,$h,number_format($total_abonos_esp,2),1,0,'R',1);
	$pdf->Cell(30,$h,number_format($total_saldos_esp,2),1,0,'R',1);
	$pdf->ln($h*3);
	$is+=1;
}
$pdf->SetFont('Arial','B',10);
$pdf->SetFillcolor(225,0,0);
$pdf->Cell(95,$h,"Total Global",1,0,'L',1);
$pdf->Cell(30,$h,number_format($total_cargos,2),1,0,'R',1);
$pdf->Cell(30,$h,number_format($total_abonos,2),1,0,'R',1);
$pdf->Cell(30,$h,number_format($total_saldos,2),1,0,'R',1);
$pdf->Output();
unset($pdf);
?>
<script>location.href='<?=base_url()?>tmp/<?=$name?>'</script>
<? unset($pdf);?>
