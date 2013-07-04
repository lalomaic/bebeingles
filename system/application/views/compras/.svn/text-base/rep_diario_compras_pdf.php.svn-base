<?php
$pdf=new Fpdf_multicell();
$pdf->AddPage('L');
$pdf->Cell(0,5,utf8_decode($title).' ('.$periodo.')',0,1,'C');
$pdf->ln();
$pdf->Cell(0,5,'Resultados: '.count($diario),0,1,'C');
$pdf->ln();
$pdf->SetFont('Times','B',10);
$pdf->MultiCell(0,5,'EMPRESA: '.utf8_decode($empresa->razon_social));
$pdf->MultiCell(0,5,'E. FISICO: '.utf8_decode($espacio->tag));
//P=201
//L=262
$anchos=array(15,18,129,35,30,35);
$pdf->SetWidths($anchos);
$pdf->SetFont('Times','B',8);
$pdf->SetAligns(array('C','C','C','C','C','C'));
$pdf->Row(array('Id','Fecha','Proveedor','Subtotal','IVA','Monto Total'));
$pdf->SetFont('Times','',8);
$pdf->SetAligns(array('L','L','L','R','R','R'));
$sub=$total=$iva=$subtotal=(float)0;
foreach($diario as $mov)
{
	$sub=(float)$mov['monto_total']-(float)$mov['iva_total'];
	$pdf->Row(array($mov['id'], $mov['fecha'], utf8_decode($mov['proveedor']), '$ '.number_format($sub,2), '$ '.number_format($mov['iva_total'],2), '$ '.number_format($mov['monto_total'],2)));
	$subtotal+=$sub;
	$iva+=(float)$mov['iva_total'];
	$total+=(float)$mov['monto_total'];
}
$pdf->Cell($anchos[0]+$anchos[1]);
$pdf->SetFont('Times','B',8);
$pdf->Cell($anchos[2],5,'TOTALES',0,0,'R');
$pdf->Cell($anchos[3],5,'$ '.number_format($subtotal,2),1,0,'R');
$pdf->Cell($anchos[4],5,'$ '.number_format($iva,2),1,0,'R');
$pdf->Cell($anchos[5],5,'$ '.number_format($total,2),1,0,'R');
$pdf->ln();
$pdf->Output();
unset($pdf);
// $name=uniqid('reporte_').'.pdf';
// $pdf->Output($name);
// echo 'Terminado '.$name;
?>
