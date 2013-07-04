<?php
//echo $fecha;echo "<br><br>";
//echo print_r($bloques);
//exit;

$h=5;
$pdf=new Fpdf_multicell();
$pdf->AddPage("L");
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
$total_dif1=(float)0;
$total_dif2=(float)0;
$total_dif3=(float)0;
$total_dif4=(float)0;
foreach($bloques as $pid=>$proveedor){
	$total_cargos_prov=(float)0;
	$total_abonos_prov=(float)0;
	$total_saldos_prov=(float)0;
	$total_dif1_prov=(float)0;
	$total_dif2_prov=(float)0;
	$total_dif3_prov=(float)0;
	$total_dif4_prov=(float)0;
	$saldo_ini=(float)0;
	$abono=(float)0;
	$pdf->SetFont('Times','B',12);
	$pdf->Cell(0,0,$proveedores[$pid]);
	//$pdf->ln($h-4);
	foreach($proveedor as $eid=>$espacio){
		$pdf->ln($h);
		if(!$ef){
			$pdf->SetFont('Times','I',12);
			$pdf->Cell(0,0,$espacios[$eid]);
			$pdf->ln($h-2);
		}
		$pdf->SetFont('Times','B',10);
		$pdf->Cell(20,$h,'Id Factura',1,0,'C');
		$pdf->Cell(25,$h,'Fecha',1,0,'C');
		$pdf->Cell(30,$h,'Folio Factura',1,0,'C');
		$pdf->Cell(30,$h,'Fecha Vencimiento',1,0,'C');
		$pdf->Cell(30,$h,'8 Dias',1,0,'C');
		$pdf->Cell(30,$h,'15 Dias',1,0,'C');
		$pdf->Cell(30,$h,'30 Dias',1,0,'C');
		$pdf->Cell(30,$h,'+30 Dias',1,0,'C');
		$pdf->Cell(35,$h,'Acumulado',1,0,'C');
		$pdf->ln($h);
		foreach($espacio as $fid=>$id_factura){
			//$pdf->SetFont('Times','I',11);
			//$pdf->Cell(0,0,'Id Factura ('.$facturas[$fid]['id']).')';
			//$pdf->ln($h-2);
			//print_r($facturas[$fid]);exit;
			$saldo=$saldo_ini+$facturas[$fid]['monto_total'];
			$total_cargos_prov+=$facturas[$fid]['monto_total'];
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
							$total_cargos_prov-=$movimiento['monto_total'];
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
						$total_cargos_prov-=$movimiento['monto_total'];
						if($movimiento['fecha']){
							$saldo-=0;
							$abono+=0;
							$abono_factura+=0;
						}
					}
				}
			}
				
			list($a1,$m1,$d1) = explode("-",$fecha);
			$timestamp1 = mktime(0,0,0,$m1,$d1,$a1);
			if($movimiento['fecha_pago']!=''){
				list($a2,$m2,$d2) = explode("-",$movimiento['fecha_pago']);
				//calculo timestam de las dos fechas
				$timestamp2 = mktime(0,0,0,$m2,$d2,$a2);
				//resto a una fecha la otra
				$segundos_diferencia = $timestamp2 - $timestamp1;
				//convierto segundos en d�as
				$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
                                //obtengo el valor absoulto de los días (quito el posible signo negativo) 
                                    $dias_diferencia = abs($dias_diferencia);
				//quito los decimales a los d�as de diferencia
				$dias_diferencia = floor($dias_diferencia);
                               
                                
			} else {
					
				$dias_diferencia=0;
                                
			}
				
			$ban=false;
			$total_abonos_prov=$abono;
			$total_saldos_prov=$total_cargos_prov-$total_abonos_prov;
			$saldo_ini=$saldo;
			if ($dias_diferencia<8)
				$total_dif1_prov+=($cargo_factura-$abono_factura);
			if ($dias_diferencia>8 and $dias_diferencia<=15)
				$total_dif2_prov+=($cargo_factura-$abono_factura);
			if ($dias_diferencia>15 and $dias_diferencia<=30)
				$total_dif3_prov+=($cargo_factura-$abono_factura);
			if ($dias_diferencia>30)
				$total_dif4_prov+=($cargo_factura-$abono_factura);
			$pdf->Cell(20,$h,$movimiento['id_factura'],1,0,'C',1);
			$pdf->Cell(25,$h,implode("-", array_reverse(explode("-", $movimiento['fecha_alta']))),1,0,'C',1);
			$pdf->Cell(30,$h,$movimiento['factura'],1,0,'L',1);
			$pdf->Cell(30,$h,implode("-", array_reverse(explode("-", $movimiento['fecha_pago']))),1,0,'C',1);
			$pdf->Cell(30,$h,($dias_diferencia)<=8 ?number_format($cargo_factura-$abono_factura,2):'',1,0,'R',1);
			$pdf->Cell(30,$h,($dias_diferencia>8 and $dias_diferencia<=15)?number_format($cargo_factura-$abono_factura,2):'',1,0,'R',1);
			$pdf->Cell(30,$h,($dias_diferencia>15 and $dias_diferencia<=30)?number_format($cargo_factura-$abono_factura,2):'',1,0,'R',1);
			$pdf->Cell(30,$h,($dias_diferencia>30)?number_format($cargo_factura-$abono_factura,2):'',1,0,'R',1);
			$pdf->Cell(35,$h,number_format($saldo,2),1,0,'R',1);
			$pdf->ln($h);
			$pdf->SetFillcolor(225,0,0);
			$pdf->Cell(260,$h-4,'',1,0,'C',1);
			$pdf->ln();
				
		}
	}
	$total_cargos+=$total_cargos_prov;
	$total_abonos+=$total_abonos_prov;
	$total_dif1+=$total_dif1_prov;
	$total_dif2+=$total_dif2_prov;
	$total_dif3+=$total_dif3_prov;
	$total_dif4+=$total_dif4_prov;
	$total_saldos=$total_cargos-$total_abonos;
	$pdf->SetFont('Arial','B',10);
	$pdf->SetFillcolor(225,0,0);
	$pdf->Cell(105,$h,"Total x Proveedor",1,0,'L',1);
	$pdf->Cell(30,$h,number_format($total_dif1_prov,2),1,0,'R',1);
	$pdf->Cell(30,$h,number_format($total_dif2_prov,2),1,0,'R',1);
	$pdf->Cell(30,$h,number_format($total_dif3_prov,2),1,0,'R',1);
	$pdf->Cell(30,$h,number_format($total_dif4_prov,2),1,0,'R',1);
	$pdf->Cell(35,$h,number_format($total_saldos_prov,2),1,0,'R',1);
	$pdf->ln($h*3);
}
$pdf->SetFont('Arial','B',10);
$pdf->SetFillcolor(225,0,0);
$pdf->Cell(105,$h,"Total Global",1,0,'L',1);
$pdf->Cell(30,$h,number_format($total_dif1,2),1,0,'R',1);
$pdf->Cell(30,$h,number_format($total_dif2,2),1,0,'R',1);
$pdf->Cell(30,$h,number_format($total_dif3,2),1,0,'R',1);
$pdf->Cell(30,$h,number_format($total_dif4,2),1,0,'R',1);
$pdf->Cell(35,$h,number_format($total_saldos,2),1,0,'R',1);
$pdf->Output();
unset($pdf);
exit();
?>
