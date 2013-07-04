<?php
$pdf=new Fpdf_multicell();
$pdf->AddPage('P');
$pdf->Cell(0,7,"Reporte General de Ventas por D�a",'T',1,'C');
$pdf->ln(0.5);
$pdf->Cell(0,5,'Periodo: '.$fecha1.' - '.$fecha2,0,1,'C');
$pdf->Cell(0,5,'Impresi�n: '.date("d-m-Y").' D�as: '.$diff,0,1,'C');
$pdf->ln(.5);
//$pdf->ln(5);
$pdf->SetFont('Times','',9);
//263
//$pdf->SetLeftMargin(2);
$pdf->SetAligns(array('L','C','C','R','R','R'));
$pdf->SetWidths(array(10,50,70,25,20,25));
$pdf->Row(array('#','Fecha','Espacio F�sico','Subtotal', 'IVA', 'Total'));
$gtotal=0;
$subtotal=0;
$iva=0;
$i=1;
foreach($salidas as $row => $k) {
	foreach($k as $valor){
		if(!($i%2))
			$pdf->SetFillColor(200,0,0);
		else
			$pdf->SetFillColor(255,0,0);

		$gtotal+=$valor['monto_total'];
		$iva+=$valor['iva'];

		$total=$valor['monto_total']-$valor['iva'];
		$subtotal+=$total;
		$pdf->Row(array($i, $valor['fecha'], utf8_decode($valor['tag']), number_format($total, 4, ".",","), number_format($valor['iva'], 4, ".",","),number_format($valor['monto_total'], 4, ".",",")));
		$i+=1;
	}
}
$pdf->SetFillColor(200,0,0);
$pdf->Row(array('', '', 'Total', number_format($subtotal, 4, ".",","),number_format($iva, 4, ".",","), number_format($gtotal, 4, ".",",")));

$pdf->Output();
unset($pdf);
?>
