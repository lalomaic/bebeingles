<?php

$pdf=new Fpdf_multicell();
$pdf->AddPage('L');
$pdf->Cell(0,5,$title.' ('.$periodo.')',0,1,'C');
$pdf->ln();
$pdf->Cell(0,5,'Resultados: '.$n,0,1,'C');
$pdf->ln();
$pdf->SetFont('Times','B',10);
$pdf->MultiCell(0,5,'EMPRESA: '.$empresa);
$pdf->MultiCell(0,5,'E. FISICO: '.$espacio);
//P=201
//L=272
$anchos=array(10,18,45,45,20,35,20,65);
$cabs=array('C','C','C','C','C','C','C','C');
$filas=array('L','C','L','L','L','R','C','L');
$pdf->SetWidths($anchos);
$pdf->SetFont('Times','B',8);
$pdf->SetAligns($cabs);
$pdf->Row(array('Id','Fecha del movimiento','Cuenta Origen','Cuenta Destino','Numero Referencia','Monto Pagado','Folio Factura','Cliente'));
$pdf->SetFont('Times','',8);
$pdf->SetAligns($filas);
$total=(float)0;
foreach($movs as &$cobro)
{
	$total+=(float)$cobro['monto_pagado'];
	$origen='';
	$destino='';
	if(array_key_exists($cobro['cuenta_origen_id'],$cuentas))
		$origen=$cuentas[$cobro['cuenta_origen_id']]['cuenta']." (".$cuentas[$cobro['cuenta_origen_id']]['banco'].")";
	if(array_key_exists($cobro['cuenta_destino_id'],$cuentas))
		$destino=$cuentas[$cobro['cuenta_destino_id']]['cuenta']." (".$cuentas[$cobro['cuenta_destino_id']]['banco'].")";
	$pdf->Row(array($cobro['id'], $cobro['fecha'], $origen, $destino, $cobro['numero_referencia'], '$ '.number_format($cobro['monto_pagado'],2), $cobro['folio_factura'], $clientes[$cobro['cliente']]['nombre']." (".$clientes[$cobro['cliente']]['clave'].")"));
}
$pdf->Cell($anchos[0]+$anchos[1]+$anchos[2]+$anchos[3]+$anchos[4]);
$pdf->SetFont('Times','B',8);
$pdf->Cell($anchos[5],5,'TOTAL  $ '.number_format($total,2),1,0,'R');
$pdf->ln();
$pdf->Output();
unset($pdf)
// $name=uniqid('reporte_').'.pdf';
// $pdf->Output($name);
// echo 'Terminado '.$name;
?>
