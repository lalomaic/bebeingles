<?php
$h=5;
$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->Cell(0,$h,$empresa,0,1,'C');
$pdf->ln($h/2);
$pdf->Cell(0,$h,$title.' ('.$periodo.')',0,1,'C');
$pdf->ln($h);
$anchos=array(15,25,25,35,30);
$pdf->SetWidths($anchos);
//201
$total_global=(float)0;
foreach($datos as $cid=>$esp)
{
	$pdf->SetFont('Times','B',12);
	$pdf->Cell(0,0,$clientes[$cid]);
	$pdf->ln($h);
	foreach($esp as $eid=>$factura)
	{
		$pdf->SetFont('Times','I',12);
		$pdf->Cell(0,0,$espacios[$eid]);
		$pdf->ln($h/2);
		$pdf->SetAligns(array('C','C','C','C','C'));
		$pdf->SetFont('Times','B',8);
		$pdf->Row(array('Id','Fecha factura','Folio factura','Monto','Fecha vencimiento'));
		$pdf->SetAligns(array('L','C','C','R','C'));
		$pdf->SetFont('Times','',8);
		$total=(float)0;
		foreach($factura as $movimiento)
		{
			$total+=(float)$movimiento['monto_total'];
			$pdf->Row(array($movimiento['id'],$movimiento['fecha'],$movimiento['folio_factura'],'$ '.number_format($movimiento['monto_total'],2),$movimiento['fecha_pago']));
		}
		$pdf->Cell($anchos[0]+$anchos[1]);
		$pdf->SetFont('Times','B',8);
		$pdf->Cell($anchos[2],5,'TOTAL',1,0,'R');
		$pdf->Cell($anchos[3],5,'$ '.number_format($total,2),1,2,'R');
		$total_global+=$total;
		$pdf->ln($h*2);
	}
}
$pdf->Cell(28,5,'TOTAL GENERAL:');
$pdf->Cell(35,5,'$ '.number_format($total_global,2),1,2);
$pdf->Output();
unset($pdf)
// $name=uniqid('reporte_').'.pdf';
// $pdf->Output($name);
// echo 'Terminado '.$name;
?>
