<?php
$pdf=new Fpdf_multicell();
$pdf->AddPage('L');
$pdf->Cell(0,7,utf8_decode($title),'T',1,'C');
$pdf->ln(0.5);
$pdf->Cell(0,5,'Per�odo: '.$fecha1.' - '.$fecha2,0,1,'C');
$pdf->Cell(0,5,'Impresi�n: '.date("d-m-Y").' D�as: '.$diff,0,1,'C');
$pdf->ln(.5);
//$pdf->ln(5);
$pdf->SetFont('Times','',9);
//263
$pdf->SetLeftMargin(20);
$pdf->SetAligns(array('L','C','C','R'));
$pdf->SetWidths(array(8,8,40,40,15,15,15,15,20));
$pdf->Row(array('#','Fecha','Hora','Espacio F�sico','Banco','No Cuenta','Referencia','Cantidad', 'Empleado'));
$gtotal=0;
$i=1;
foreach($depositos->all as $row) {
	if(!($i%2))
		$pdf->SetFillColor(200,0,0);
	else
		$pdf->SetFillColor(255,0,0);

	$gtotal+=$row->cantidad;

	$pdf->Row(array($i, $row->fecha_deposito, $row->hora_deposito,utf8_decode($row->espacio),utf8_decode($row->nombre_banco),$row->numero_cuenta,$row->referencia,number_format($row->cantidad, 4, ".",","), utf8_decode($row->nombre_empleado)));
	$i+=1;
}
$pdf->SetFillColor(200,0,0);
//$pdf->Row(array('', '', 'Total', number_format($gtotal, 4, ".",",")));

$pdf->Output();
unset($pdf);
?>
