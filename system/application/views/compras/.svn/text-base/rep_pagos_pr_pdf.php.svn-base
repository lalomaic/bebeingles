<?php
$pdf=new Fpdf_multicell();
$pdf->AddPage('L');
$pdf->Cell(0,5,utf8_decode($title).' ('.$periodo.')',0,1,'C');
$pdf->ln();
$pdf->Cell(0,5,'Resultados: '.$n,0,1,'C');
$pdf->ln();
$pdf->SetFont('Times','B',10);
$pdf->MultiCell(0,5,'EMPRESA: '.utf8_decode($empresa));
$pdf->MultiCell(0,5,'E. FISICO: '.utf8_decode($espacio));
//P=201
//L=272
$anchos=array(10,18,45,45,20,35,20,65);
$cabs=array('C','C','C','C','C','C','C','C');
$filas=array('L','C','L','L','L','R','C','L');
$pdf->SetWidths($anchos);
foreach($proveedores as &$proveedor)
{
	if(count($proveedor['pagos'])>0)
	{
		$pdf->SetFont('Times','B',8);
		$pdf->Cell(0,5,$proveedor['nombre']);
		$pdf->ln();
		$pdf->SetAligns($cabs);
		$pdf->Row(array('Id','Fecha','Cuenta Origen','Cuenta Destino','N�mero Referencia','Monto Pagado','Folio Factura','Usuario'));
		$pdf->SetFont('Times','',8);
		$pdf->SetAligns($filas);
		$total=(float)0;
		foreach($proveedor['pagos'] as &$pago)
		{
			$total+=(float)$pago['monto_pagado'];
			$pdf->Row(array($pago['id'], $pago['fecha'], $cuentas[$pago['cuenta_origen_id']]['cuenta']." (".$cuentas[$pago['cuenta_origen_id']]['banco'].")" , $cuentas[$pago['cuenta_destino_id']]['cuenta']." (".$cuentas[$pago['cuenta_destino_id']]['banco'].")", $pago['numero_referencia'], '$ '.number_format($pago['monto_pagado'],2), $pago['folio_factura'], $pago['nombre']));
		}
		$pdf->Cell($anchos[0]+$anchos[1]+$anchos[2]+$anchos[3]+$anchos[4]);
		$pdf->SetFont('Times','B',8);
		$pdf->Cell($anchos[5],5,'TOTAL  $ '.number_format($total,2),1,0,'R');
		$pdf->ln();
	}
}
$pdf->Output();
unset($pdf);
// $name=uniqid('reporte_').'.pdf';
// $pdf->Output($name);
// echo 'Terminado '.$name;
?>
