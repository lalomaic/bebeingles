<?php
$h=5;
//$pdf=new Fpdf_multicell();
$pdf= new Optimizar_memoria();
$name='formato_pago_'. date("d_m_Y") .'.pdf';
$pdf->Open($name);
$pdf->AddPage();
$pdf->SetFont('Times','B',15);
$pdf->Cell(0,$h,"Formato de Pago ",0,1,'C');
$pdf->ln($h);
$pdf->Cell(0,$h,utf8_decode($proveedor_tag),0,1,'C');
$pdf->ln($h);
$pdf->SetFont('Times','B',14);
$pdf->Cell(0,$h,utf8_decode($periodo),0,1,'C');
$pdf->ln($h);
if($ef){
	$pdf->SetFont('Times','I',12);
	$pdf->Cell(0,$h,utf8_decode($esp->tag),'B',1,'C');
	$pdf->ln($h);
}
$total_cargos=(float)0;
$total_abonos=(float)0;
$total_saldos=(float)0;
$is=0;
$total_por_devolucion=0; $total_por_abonos=0;
foreach($bloques as $eid=>$espacio){
	$total_cargos_esp=(float)0;
	$total_abonos_esp=(float)0;
	$total_saldos_esp=(float)0;
	$saldo_ini=(float)0;
	$abono=(float)0;
	$pdf->SetFont('Times','B',12);
	$pdf->Cell(0,0,utf8_decode($espacios[$eid]));
	foreach($espacio as $pid=>$proveedor){
		$pdf->ln($h);
		if(!$ef){
			$pdf->SetFont('Times','I',12);
			$pdf->Cell(0,0,utf8_decode($bloq[$eid][$pid]['marca']));
			$pdf->ln($h-2);
		}
		$pdf->SetFont('Times','B',10);
		$pdf->Cell(20,$h,'Id Factura',1,0,'C');
		$pdf->Cell(30,$h,'Fecha',1,0,'C');
		$pdf->Cell(20,$h,'Folio',1,0,'C');
		$pdf->Cell(25,$h,'Vencimiento',1,0,'C');
		$pdf->Cell(30,$h,'Abono',1,0,'C');
		$pdf->Cell(30,$h,'Importe',1,0,'C');
		$pdf->Cell(30,$h,'Tipo Pago',1,0,'C');

		// 		$pdf->Cell(30,$h,'Importe',1,0,'C');
		// 		$pdf->Cell(30,$h,'Saldo',1,0,'C');
		$pdf->ln($h);
		foreach($proveedor as $fid=>$id_factura){
			$saldo=$saldo_ini+$facturas[$fid]['monto_total'];
			$total_cargos_esp+=$facturas[$fid]['monto_total'];
			$cargo_factura=$facturas[$fid]['monto_total'];
			$abono_factura=(float)0;
			$ban=true;
			foreach($id_factura as $movimiento){
				$pdf->SetFont('Times','',10);
				$pdf->SetFillcolor(255,255,255);
				$pdf->Cell(20,$h,$movimiento['id_factura'],1,0,'C',1);
				$pdf->Cell(30,$h,implode("-", array_reverse(explode("-", $movimiento['fecha_alta']))),1,0,'C',1);
				$pdf->Cell(20,$h,$movimiento['factura'],1,0,'L',1);
				//Calcular dias de vencimiento
				$hoy=date("Y-m-d");
				$dias_vencimiento=round((strtotime($hoy)-strtotime($movimiento['vencimiento']))/(3600* 24), 0);
				if($dias_vencimiento<0)
					$dias_vencimiento=0;
				$pdf->Cell(25,$h,implode("-", array_reverse(explode("-", $movimiento['vencimiento'])))."  $dias_vencimiento",1,0,'L',1);
				$pdf->Cell(30,$h,($movimiento['monto_pagado'])==0?'':number_format($movimiento['monto_pagado'],2),1,0,'R',1);
				$pdf->Cell(30,$h,($cargo_factura)==0?'':number_format($cargo_factura,2),1,0,'R',1);
				if($movimiento['numero_referencia']=="DEVOLUCION POR DEFECTO FISICO"){
					$pdf->Cell(30,$h,"Devolucion",1,0,'R',1);
					$total_por_devolucion+=$movimiento['monto_pagado'];
				} else
					$pdf->Cell(30,$h,"Normal",1,0,'R',1);

				$total_por_abonos+=$movimiento['monto_pagado'];
				$pdf->ln($h);
				$pdf->SetFillcolor(187,187,187);
				$pdf->Cell(155,$h-4,'',1,0,'C',1);
				$pdf->ln();
				if($fecha!=''){
					$saldo-=$movimiento['monto_pagado'];
					$abono+=$movimiento['monto_pagado'];
					$abono_factura+=$movimiento['monto_pagado'];
					if($ban==true){
						if($movimiento['fecha_alta']==$fecha){
							$saldo-=$movimiento['monto_total'];
							$abono+=$movimiento['monto_pagado'];
							$total_cargos_esp-=$movimiento['monto_total'];
						}
						if(!$movimiento['fecha'] || $movimiento['fecha']>$fecha){
							$saldo+=$movimiento['monto_pagado'];
							$abono-=$movimiento['monto_pagado'];
							$abono_factura-=$movimiento['monto_pagado'];
						}

					}
					else{
						if(!$movimiento['fecha'] || $movimiento['fecha']==$fecha){
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
					} else{
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
		}
	}
	$total_cargos+=$total_cargos_esp;
	$total_abonos+=$total_abonos_esp;
	$total_saldos=$total_cargos-$total_abonos;
	// 	$pdf->SetFont('Arial','B',10);
	// 	$pdf->SetFillcolor(187,187,187);
	// 	$pdf->Cell(95,$h,"Total por Sucursal",1,0,'L',1);
	// 	$pdf->Cell(30,$h,number_format($total_cargos_esp,2),1,0,'R',1);
	// 	$pdf->Cell(30,$h,number_format($total_abonos_esp,2),1,0,'R',1);
	// 	$pdf->Cell(30,$h,number_format($total_saldos_esp,2),1,0,'R',1);
	$pdf->ln($h*2);
	$is+=1;
}
$pdf->SetFont('Arial','B',10);
$pdf->SetFillcolor(225,0,0);
$pdf->Cell(95,$h,"Pago Global ",1,0,'L',1);
$pdf->Cell(30,$h,number_format($total_por_abonos,2),1,1,'R',1);
// $pdf->Cell(30,$h,number_format($total_cargos,2),1,0,'R',1);
// $pdf->Cell(30,$h,number_format($total_saldos,2),1,0,'R',1);
$pdf->Cell(95,$h,"Devoluciones Globales ",1,0,'L',1);
$pdf->Cell(30,$h,number_format($total_por_devolucion,2),1,1,'R',1);
$pdf->Cell(95,$h,"Pago Efectivo ",1,0,'L',1);
$pdf->Cell(30,$h,number_format($total_por_abonos-$total_por_devolucion,2),1,0,'R',1);
$pdf->ln(15);
//Agregar Fecha y Nombre y firma de quien recibe
$pdf->SetFillcolor(225,255,255);
$pdf->Cell(140,$h,"Nombre y Firma",'B',1,'L',1);
$pdf->Cell(140,$h,"Fecha",'B',1,'L',1);
//Comentar los abonos y saldos

if(isset($devolucion) and count($devolucion)>0 and $devolucion!=false){
	$pdf->AddPage();
	$pdf->SetFont('Times','B',15);
	$pdf->Cell(0,$h,"Detalle de Devoluciones Consideradas en el Pago",0,1,'C');
	$pdf->ln($h);
	$pdf->Cell(0,$h,utf8_decode($proveedor_tag),0,1,'C');
	$pdf->ln($h);
	$pdf->SetFont('Times','B',14);
	$pdf->Cell(0,$h,utf8_decode($periodo),0,1,'C');
	$pdf->ln($h);
	$pdf->SetFont('Times','B',10);
	$pdf->SetFillcolor(187,187,187);
	$pdf->Cell(15,$h,'Cantidad',1,0,'C');
	$pdf->Cell(140,$h,'Descripcion',1,0,'C');
	$pdf->Cell(15,$h,'C. Unit.',1,0,'C');
	$pdf->Cell(15,$h,'SubTotal',1,0,'C');
	$pdf->ln($h);
	$pdf->SetFont('Times','',9);
	$total_devolucion_detalle=0; $total_devolucion_cantidad=0;
	foreach($devolucion as $k=>$v){
		foreach($v as $k2=>$v1){
			$pdf->SetFillcolor(255,255,255);
			$pdf->Cell(15,$h,number_format($v1['cantidad'],2,".",","),1,0,'R',1);
			$pdf->Cell(140,$h,$v1['descripcion']. " # ".$v1['numero'],1,0,'L',1);
			$pdf->Cell(15,$h,number_format($v1['costo_unitario'],2,".",","),1,0,'R',1);
			$pdf->Cell(15,$h,number_format($v1['cantidad']*$v1['costo_unitario'],2,".",","),1,0,'R',1);
			$pdf->ln($h);
			$pdf->SetFillcolor(187,187,187);
			$pdf->Cell(185,$h-4,'',1,0,'C',1);
			$pdf->ln();
			$total_devolucion_detalle+=$v1['cantidad']*$v1['costo_unitario'];
			$total_devolucion_cantidad+=$v1['cantidad'];
		}
	}
	$pdf->SetFont('Times','B',10);
	$pdf->SetFillcolor(187,187,187);
	$pdf->Cell(15,$h,number_format($total_devolucion_cantidad,2,".",","),1,0,'R');
	$pdf->Cell(140,$h,'',1,0,'C');
	$pdf->Cell(15,$h,'Total',1,0,'R');
	$pdf->Cell(15,$h,number_format($total_devolucion_detalle,2,".",","),1,0,'R');
	$pdf->ln(15);

	if(isset($devolucion_0) and count($devolucion_0)>0 and $devolucion_0!=false){
		$pdf->SetFont('Times','B',15);
		$pdf->Cell(0,$h,"Detalle de Devoluciones del Lote 0",0,1,'L');
		$pdf->SetFont('Times','B',10);
		$pdf->SetFillcolor(187,187,187);
		$pdf->Cell(15,$h,'Cantidad',1,0,'C');
		$pdf->Cell(140,$h,'Descripcion',1,0,'C');
		$pdf->Cell(15,$h,'C. Unit.',1,0,'C');
		$pdf->Cell(15,$h,'SubTotal',1,0,'C');
		$pdf->ln($h);
		$pdf->SetFont('Times','',9);
		$total_devolucion_cantidad=0;
		foreach($devolucion_0 as $k=>$v){
			foreach($v as $k2=>$v1){
				$pdf->SetFillcolor(255,255,255);
				$pdf->Cell(15,$h,number_format($v1['cantidad'],2,".",","),1,0,'R',1);
				$pdf->Cell(140,$h,$v1['descripcion']. " # ".$v1['numero'],1,0,'L',1);
				$pdf->Cell(15,$h,"0.00",1,0,'R',1);
				$pdf->Cell(15,$h,"0.00",1,0,'R',1);
				$pdf->ln($h);
				$pdf->SetFillcolor(187,187,187);
				$pdf->Cell(185,$h-4,'',1,0,'C',1);
				$pdf->ln();
				$total_devolucion_cantidad+=$v1['cantidad'];
			}
		}
		$pdf->SetFont('Times','B',10);
		$pdf->SetFillcolor(187,187,187);
		$pdf->Cell(15,$h,number_format($total_devolucion_cantidad,2,".",","),1,0,'R');
		$pdf->Cell(140,$h,'',1,0,'C');
		$pdf->Cell(15,$h,'Total',1,0,'R');
		$pdf->Cell(15,$h,number_format($total_por_devolucion-$total_devolucion_detalle,2,".",","),1,0,'R');
		$pdf->ln(15);
	}
	$pdf->SetFillcolor(225,255,255);
	$pdf->Cell(140,$h,"Nombre y Firma",'B',1,'L',1);
	$pdf->Cell(140,$h,"Fecha",'B',1,'L',1);

}


$pdf->Output();
unset($pdf);
?>
<script>location.href='<?=base_url()?>tmp/<?=$name?>'</script>
<? unset($pdf);?>
