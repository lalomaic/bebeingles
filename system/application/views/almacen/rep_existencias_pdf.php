<?php
$h = 5;
$widths = array(40, 20, 30, 30,30, 30);
$pdf = new Fpdf_multicell();
$pdf->AddPage('L');
$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(0, $h, utf8_decode("Reporte de Existencias"), 'B', 1, 'C');
$pdf->SetFont('Times', '', 10);
$pdf->Cell(0, $h, utf8_decode("Al $fecha"), 0, 1, 'C');
$pdf->SetTopMargin(15);
$pdf->SetLeftMargin(20);
foreach ($espacios as $espacio_id => $espacio) {
	if (!isset($movimientos[$espacio_id]) && !isset($inventario[$espacio_id]))
		continue;
	$pdf->ln(10);
	$pdf->SetFont('Times', 'B', 12);
	$pdf->Cell(0, 5, utf8_decode($espacio), 0, 1);
	foreach ($marcas as $marca_id => $marca) {
		if (!isset($movimientos[$espacio_id][$marca_id]) && !isset($inventario[$espacio_id][$marca_id]))
			continue;
		$pdf->ln(5);
		$pdf->SetFont('Times', 'I', 11);
		$pdf->Cell(0, 5, utf8_decode($marca), 0, 1);
		$total_marca_existencia = 0;
		$total_marca = 0;
		if (isset($movimientos[$espacio_id][$marca_id])) {
			foreach ($movimientos[$espacio_id][$marca_id] as $producto_id => $movimientos_producto) {
				$pdf->SetFont('Times', 'B', 10);
				$pdf->Cell(0, 5, utf8_decode($movimientos_producto[0]['descripcion']), 0, 1);
				$pdf->SetFillColor(204, 214, 255);
				//                $pdf->Cell($widths[0], 5, "Lote", 'TLR', 0, 'C', true);
				$pdf->Cell($widths[0], 5, "#", 'TLR', 0, 'C', true);
				$pdf->Cell($widths[2], 5, "Num Id", 'TLR', 0, 'C', true);
				$pdf->Cell($widths[1], 5, "Talla", 'TLR', 0, 'C', true);
				$pdf->Cell($widths[3], 5, "Existencia", 'TLR', 0, 'C', true);
				$pdf->Cell($widths[4], 5, "Costo", 'TLR', 0, 'C', true);
				$pdf->Cell($widths[5], 5, "Total", 'TLR', 1, 'C', true);
				$pdf->SetFont('Times', '', 10);
				$pdf->SetFillColor(240);

				$total_producto_existencia = 0;
				$total_producto = 0;
				$fill = false; $r=1;
				foreach ($movimientos_producto as $movimiento) {
					$total = $movimiento['costo_unitario'] * $movimiento['inventario'];
					//                     $pdf->Cell($widths[0], 5, $movimiento['lote'], 'LR', 0, 'C', $fill);
					$pdf->Cell($widths[0], 5, $r, 'LR', 0, 'C', $fill);
					$pdf->Cell($widths[2], 5, $movimiento['numero_id'], 'LR', 0, 'R', $fill);
					$pdf->Cell($widths[1], 5, $movimiento['numero'], 'LR', 0, 'R', $fill);
					$pdf->Cell($widths[3], 5, $movimiento['inventario'], 'LR', 0, 'R', $fill);
					$pdf->Cell($widths[4], 5, number_format($movimiento['costo_unitario'],2,".",","), 'LR', 0, 'R', $fill);
					$pdf->Cell($widths[5], 5, number_format($total, 2,".",","), 'LR', 1, 'R', $fill);
					$fill = !$fill;
					$total_producto_existencia += $movimiento['inventario'];
					$total_producto += $total;
					$r+=1;
				}
				$pdf->Cell($widths[0] + $widths[1]+ $widths[2], 5, "Totales", 'T', 0, 'R');
				$pdf->Cell($widths[2], 5, number_format($total_producto_existencia, 0), 1, 0, 'R');
				$pdf->Cell($widths[3], 5, '', 1, 0, 'R');
				$pdf->Cell($widths[4], 5, number_format($total_producto, 2), 1, 1, 'R');

				$total_marca_existencia += $total_producto_existencia;
				$total_marca += $total_producto;
			}
		}
		$pdf->Cell($widths[0] + $widths[1], 5, "Total de la marca $marca", 0, 0, 'R');
		$pdf->Cell($widths[2], 5, number_format($total_marca_existencia, 0), 1, 0, 'R');
		$pdf->Cell($widths[3], 5, '', 1, 0, 'R');
		$pdf->Cell($widths[4], 5, number_format($total_marca, 2), 1, 1, 'R');
	}
}
$pdf->SetDisplayMode('fullwidth');
$pdf->Output("tmp/$filename");
unset($pdf);
?>
<a href="<?php echo base_url() ?>tmp/<?php echo $filename ?>"
	class="pdfLink">Descargar archivo</a>
<div>
	<object
		data="<?php echo base_url() ?>tmp/<?php echo $filename ?>?rnd=<?php echo rand() ?>"
		class="pdfObject" type="application/pdf">
		alt : <a href="<?php echo base_url() ?>tmp/<?php echo $filename ?>"><?php echo $filename ?>
		</a>
	</object>
</div>
