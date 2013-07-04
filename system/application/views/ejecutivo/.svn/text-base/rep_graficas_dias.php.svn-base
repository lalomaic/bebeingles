<?php

function esPar($num) {
	return!($num % 2);
}
$h = 5;
$pdf = new Fpdf_multicell();
$pdf->AddPage();
$pdf->Cell(0, $h, utf8_decode($title), 'B', 1, 'L');
$pdf->ln($h);
$pdf->Image(base_url() . "tmp/tickets_dias1.jpeg", 10, 20, 190);
$pdf->SetXY(100, 150);
$pdf->ln($h);
$pdf->Cell(0, $h, "Tabla de Comportamiento de Ventas por D�a", 0, 1, 'C');
$pdf->ln(3);
$pdf->Cell(0, $h, "TIENDA: $tienda", 0, 1, 'C');
$pdf->ln(3);
//$pdf->Cell(0,$h,$etiqueta,0,1,'C');
$pdf->SetWidths(array(30, 25, 40, 25, 30, 35));
$pdf->SetFont('Times', 'B', 10);
$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C'));
$pdf->SetFillColor(200, 0, 0);
$pdf->Row(array('DIA', 'TICKETS', 'PROMEDIO TICKETS', 'VENTAS', 'PROMEDIO VENTAS', 'DIAS CONTABILIZADOS'));
$pdf->SetFont('Times', '', 9);
$pdf->SetAligns(array('L', 'R', 'R', 'R', 'R', 'C'));
$r = 1;
$total = 0;
$tot_tic = 0;
$promt = 0;
$dia = "";
$tickets = 0;
$t = 1;
$venta = 0;
foreach ($global->result() as $fila) {
	if (esPar($r))
		$pdf->SetFillColor(220, 0, 0);
	else
		$pdf->SetFillColor(255, 0, 0);

	$prom = round($fila->tickets / $fila->dias, 0);
	if ($prom > 0)
		$promv = round(($fila->venta / $prom), 2);
	else
		$promv = 0;
	$pdf->Row(array($fila->dia, $fila->tickets, $prom, "$" . number_format($fila->venta, 2, ".", ","), "$" . number_format($promv, 2, ".", ","), $fila->dias));
	$total+=$fila->venta;
	$tot_tic+=$fila->tickets;
	$promt+=$prom;
	$r++;
}

if (esPar($r))
	$pdf->SetFillColor(220, 0, 0);
else
	$pdf->SetFillColor(255, 0, 0);
$pdf->SetFont('Times', 'B', 10);
$pdf->SetWidths(array(30, 25, 40, 25, 65));
$pdf->SetAligns(array('C', 'R', 'R', 'R', 'C'));
$pdf->Row(array('TOTAL', $tot_tic, $promt, "$" . number_format($total, 2, ".", ","), ''));
$pdf->Cell(185, 0, '', 1);
$pdf->ln($h);
$pdf->Output();
unset($pdf)
?>