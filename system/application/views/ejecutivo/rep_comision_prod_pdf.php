<?php
$h=5;
$pdf=new Fpdf_multicell();
$pdf->AddPage('L');
$pdf->Cell(0,$h,utf8_decode($title).' ('.$periodo.')',0,1,'C');
$pdf->ln($h);
$anchos=array(80,25,25,35,30,30,30);
$pdf->SetWidths($anchos);
//201
//$pdf->ln(5);
foreach($bloques as $eid=>$espacio)
{
	$pdf->SetFont('Times','B',12);
	$pdf->Cell(0,0,utf8_decode($espacios[$eid]));
	$pdf->ln($h);
	$pdf->SetAligns(array('C','C','C','C','C'));
	$pdf->SetFont('Times','B',8);
	$pdf->Row(array('Producto','Presentaci�n','Cantidad','Precio unitario (promedio)','Costo total','% Comisi�n','Comisi�n'));
	$pdf->SetAligns(array('L','L','R','R','R','R','R'));
	$pdf->SetFont('Times','',8);
	$total=$total_comision=(float)0;
	foreach($espacio as $pid=>$producto)
	{
		foreach($producto as $movimiento)
		{
			$total+=(float)$movimiento['total'];
			$total_comision+=(float)$movimiento['comision_total'];
			$pdf->Row(array(utf8_decode($productos[$pid]['descripcion']),utf8_decode($productos[$pid]['presentacion']),number_format($movimiento['cantidad'],4),'$ '.number_format($movimiento['costo'],2),'$ '.number_format($movimiento['total'],2),number_format($movimiento['comision'],2)." %",'$ '.number_format($movimiento['comision_total'],2)));
		}
	}
	$pdf->Cell($anchos[0]+$anchos[1]+$anchos[2]);
	$pdf->SetFont('Times','B',8);
	$pdf->Cell($anchos[3],5,'TOTALES',1,0,'R');
	$pdf->Cell($anchos[4],5,'$ '.number_format($total,2),1,0,'R');
	$pdf->Cell($anchos[5],5,number_format(($comision>0)?($comision):($total_comision/$total*100),2).' %',1,0,'R');
	$pdf->Cell($anchos[6],5,'$ '.number_format($total_comision,2),1,0,'R');
}
$pdf->Output();
unset($pdf);
// $name=uniqid('reporte_').'.pdf';
// $pdf->Output($name);
// echo 'Terminado '.$name;
?>
