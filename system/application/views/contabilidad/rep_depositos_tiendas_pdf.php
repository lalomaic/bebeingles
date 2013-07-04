<?php
$pdf=new Fpdf_multicell();
$pdf->AddPage('L');
$pdf->Cell(0,7,utf8_decode($title),'T',1,'C');
$pdf->ln(0.5);
$pdf->Cell(0,5,'Per�odo: '.$fecha1.' - '.$fecha2,0,1,'C');
$pdf->Cell(0,5,'Impresi�n: '.date("d-m-Y")." - Resultados:".$depositos->num_rows(),0,1,'C');
$pdf->ln(.5);
//$pdf->ln(5);
$pdf->SetFont('Times','',8);
//263
$pdf->SetLeftMargin(20);
$pdf->SetAligns(array('L','C','C','R'));
$pdf->SetWidths(array(8,18,18,30,45,40,35,20,40));
$pdf->Row(array('#','Fecha','Hora','Espacio F�sico','Banco','No Cuenta','Referencia','Cantidad', 'Empleado'));
$gtotal=0;
$i=1;
foreach($depositos->result() as $row) {
	if(!($i%2))
		$pdf->SetFillColor(200,0,0);
	else
		$pdf->SetFillColor(255,0,0);

	$gtotal+=$row->cantidad;
	$fecha=explode("-", $row->fecha_deposito);
	$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];

	$pdf->Row(array($i, $fecha1, $row->hora_deposito,utf8_decode($row->espacio),utf8_decode($row->nombre_banco),$row->numero_cuenta,$row->referencia,number_format($row->cantidad, 2, ".",","), utf8_decode($row->nombre_empleado)));
	$i+=1;
}
$pdf->SetFillColor(200,0,0);
$pdf->Row(array('', '', '', '', '', '', 'Total', number_format($gtotal, 4, ".",","),''));

$pdf->Output();
unset($pdf);
?>
