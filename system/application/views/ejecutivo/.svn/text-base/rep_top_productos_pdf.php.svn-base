<?php
$h = 5;
$pdf = new Fpdf_multicell();
$pdf->AddPage();
$pdf->ln($h);
$pdf->Cell(0, $h, utf8_decode($title), 'B', 1, 'L');
$pdf->ln($h);
$pdf->SetFont('Times', 'B', 8);
$pdf->SetFillColor(220);

$pdf->Cell(9, 5, '#', 'TB', 0, 'C', 1);
$pdf->Cell(100, 5, 'PRODUCTO', 'TB', 0, 'C', 1);
$pdf->Cell(10, 5, utf8_decode('NÚM'), 'TB', 0, 'C', 1);
$pdf->Cell(20, 5, 'CANTIDAD', 'TB', 0, 'C', 1);
$pdf->Cell(20, 5, 'VENTA', 'TB', 0, 'C', 1);
$pdf->Cell(20, 5, 'COSTO', 'TB', 0, 'C', 1);
$pdf->Cell(20, 5, 'GANANCIA', 'TB', 1, 'C', 1);

$pdf->SetFont('Times', '', 7);
$pdf->SetFillColor(240);
$pdf->SetTopMargin(20);

$r = 1;
$fill = false;
$cantidad_total = 0;
$importe_total = 0;
$costo_total = 0;
$gancia_total = 0;
foreach ($global->result() as $fila) {
	$this->load->model("almacen");
	$costo = ( ((int) $fila->cantidad) * $this->almacen->get_costo_promedio($fila->cproducto_id) );
	$ganancia = $fila->monto - $costo;

	$pdf->Cell(9, 5, $r, 0, 0, 'L', $fill);
	$pdf->Cell(100, 5, utf8_decode($fila->descripcion), 0, 0, 'L', $fill);
	$pdf->Cell(10, 5, $fila->numero, 0, 0, 'C', $fill);
	$pdf->Cell(20, 5, (int) $fila->cantidad, 0, 0, 'C', $fill);
	$pdf->Cell(20, 5, "$ " . number_format($fila->monto, 2), 0, 0, 'R', $fill);
	$pdf->Cell(20, 5, "$ " . number_format($costo, 2), 0, 0, 'R', $fill);
	$pdf->Cell(20, 5, "$ " . number_format($ganancia, 2), 0, 1, 'R', $fill);

	$r+=1;
	$fill = !$fill;
	$cantidad_total += (int) $fila->cantidad;
	$importe_total += $fila->monto;
	$costo_total += $costo;
	$gancia_total += $ganancia;
}


$pdf->SetFont('Times', 'B', 8);
$pdf->Cell(119, 5, 'TOTALES', 'T', 0, 'R');
$pdf->Cell(20, 5, $cantidad_total, 'T', 0, 'C');
$pdf->Cell(20, 5, "$" . number_format($importe_total, 2, ".", ","), 'T', 0, 'R');
$pdf->Cell(20, 5, "$" . number_format($costo_total, 2, ".", ","), 'T', 0, 'R');
$pdf->Cell(20, 5, "$" . number_format($gancia_total, 2, ".", ","), 'T', 1, 'R');
$pdf->Cell(119, 5, 'DEVOLUCIONES POR CAMBIOS', 0, 0, 'R');
$pdf->Cell(20, 5, "", 0, 0, 'R');
$pdf->Cell(20, 5, "$" . number_format($descuento, 2, ".", ","), 'B', 0, 'R');
$pdf->Cell(20, 5, "", 0, 0, 'R');
$pdf->Cell(20, 5, "", 0, 1, 'R');
$pdf->Cell(119, 5, 'TOTAL VENTA', 0, 0, 'R');
$pdf->Cell(20, 5, "", 0, 0, 'R');
$pdf->Cell(20, 5, "$" . number_format($importe_total-$descuento, 2, ".", ","), 0, 0, 'R');
$pdf->Cell(20, 5, "", 0, 0, 'R');
$pdf->Cell(20, 5, "", 0, 1, 'R');

$pdf->Output();
unset($pdf);
?>
