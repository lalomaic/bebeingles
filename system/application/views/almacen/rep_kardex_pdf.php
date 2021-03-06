<?php
$h = 5;
$widths = array(40, 90, 20, 30, 30, 30);
$pdf = new Fpdf_multicell();
$pdf->AddPage('L');
$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(0, $h, utf8_decode("Kardex de Almacén"), 'B', 1, 'C');
$pdf->SetFont('Times', '', 10);
$pdf->Cell(0, $h, utf8_decode("Del $fecha_inicio al $fecha_fin"), 0, 1, 'C');
$marca = 0;
$pdf->SetTopMargin(15);
$pdf->SetLeftMargin(20);
if (!$corrida) {
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
			if (isset($movimientos[$espacio_id][$marca_id])) {
				foreach ($movimientos[$espacio_id][$marca_id] as $producto_id => $movimientos_producto) {
					$pdf->SetFont('Times', 'B', 10);
					$pdf->Cell(0, 5, utf8_decode($movimientos_producto[0]['descripcion']), 0, 1);
					$pdf->SetFillColor(204, 214, 255);
					$pdf->Cell($widths[0], 5, "Fecha", 'TLR', 0, 'C', true);
					$pdf->Cell($widths[1], 5, "Tipo movimiento", 'TLR', 0, 'C', true);
					$pdf->Cell($widths[2], 5, utf8_decode("Núm."), 'TLR', 0, 'C', true);
					$pdf->Cell($widths[3], 5, "Entrada", 'TLR', 0, 'C', true);
					$pdf->Cell($widths[4], 5, "Salida", 'TLR', 0, 'C', true);
					$pdf->Cell($widths[5], 5, "Existencia", 'TLR', 1, 'C', true);
					$pdf->SetFont('Times', '', 10);
					$pdf->SetFillColor(240);
					$fill = false;
					if (isset($inventario[$espacio_id][$marca_id][$producto_id])) {
						$existencia = $inventario[$espacio_id][$marca_id][$producto_id][0];
						unset($inventario[$espacio_id][$marca_id][$producto_id]);
					}else
						$existencia = 0;
					$total_e = $total_s = 0;
					$pdf->Cell($widths[0], 5, '', 'LRB', 0, 'C', $fill);
					$pdf->Cell($widths[1], 5, "Existencia hasta el $fecha_inicio", 'LRB', 0, 'L', $fill);
					$pdf->Cell($widths[2], 5, '', 'LRB', 0, 'R', $fill);
					$pdf->Cell($widths[3], 5, '', 'LRB', 0, 'R', $fill);
					$pdf->Cell($widths[4], 5, '', 'LRB', 0, 'R', $fill);
					$pdf->Cell($widths[5], 5, $existencia, 'LRB', 1, 'R', $fill);
					foreach ($movimientos_producto as $movimiento) {
						$existencia += $movimiento['entrada'] - $movimiento['salida'];
						$pdf->Cell($widths[0], 5, $movimiento['fecha'], 'LR', 0, 'C', $fill);
						$pdf->Cell($widths[1], 5, utf8_decode(($movimiento['tipo_e'] > 0 ?
								$entradas[$movimiento['tipo_e']] :
								$salidas[$movimiento['tipo_s']]) .
								($movimiento['movimiento'] != '' ? " {$movimiento['movimiento']}" : '')), 'LR', 0, 'L', $fill);
						$pdf->Cell($widths[2], 5, $movimiento['numero'], 'LR', 0, 'R', $fill);
						$pdf->Cell($widths[3], 5, $movimiento['entrada'], 'LR', 0, 'R', $fill);
						$pdf->Cell($widths[4], 5, $movimiento['salida'], 'LR', 0, 'R', $fill);
						$pdf->Cell($widths[5], 5, $existencia, 'LR', 1, 'R', $fill);
						$fill = !$fill;
						$total_e += $movimiento['entrada'];
						$total_s += $movimiento['salida'];
					}
					$pdf->Cell($widths[0] + $widths[1] + $widths[2], 5, "Totales", 'T', 0, 'R');
					$pdf->Cell($widths[3], 5, $total_e, 1, 0, 'R');
					$pdf->Cell($widths[4], 5, $total_s, 1, 0, 'R');
					$pdf->Cell($widths[5], 5, '', 'T', 1);
				}
			}
			if (isset($inventario[$espacio_id][$marca_id]) && count($inventario[$espacio_id][$marca_id]) > 0) {
				$pdf->SetFont('Times', 'B', 11);
				$pdf->Cell(0, 5, "Productos sin movimientos", 0, 1);
				$pdf->SetFont('Times', 'B', 10);
				$pdf->SetFillColor(204, 214, 255);
				$pdf->Cell($widths[0] + $widths[1] + $widths[2] + $widths[3] + $widths[4], 5, "Producto", 'TLR', 0, 'C', true);
				$pdf->Cell($widths[5], 5, "Existencia", 'TLR', 1, 'C', true);
				$pdf->SetFont('Times', '', 10);
				$pdf->SetFillColor(240);
				$fill = false;
				foreach ($inventario[$espacio_id][$marca_id] as $producto_id => $movimientos_producto) {
					$pdf->Cell($widths[0] + $widths[1] + $widths[2] + $widths[3] + $widths[4], 5, utf8_decode($movimientos_producto[1]), 'LR', 0, 'L', $fill);
					$pdf->Cell($widths[5], 5, $movimientos_producto[0], 'LR', 1, 'R', $fill);
					$fill = !$fill;
				}
				$pdf->Cell($widths[0] + $widths[1] + $widths[2] + $widths[3] + $widths[4] + $widths[5], 0, '', 'T', 1);
			}
		}
	}
} else {
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
			if (isset($movimientos[$espacio_id][$marca_id])) {
				foreach ($movimientos[$espacio_id][$marca_id] as $producto_id => $movimientos_numero) {
					$pdf->SetFont('Times', 'B', 10);
					$popo = array_keys($movimientos_numero);
					$pdf->Cell(0, 5, utf8_decode($movimientos_numero[array_shift(array_keys($movimientos_numero))][0]['descripcion']), 0, 1);
					$pdf->SetFillColor(204, 214, 255);
					$pdf->Cell($widths[0], 5, "Fecha", 'TLR', 0, 'C', true);
					$pdf->Cell($widths[1], 5, "Tipo movimiento ", 'TLR', 0, 'C', true);
					$pdf->Cell($widths[2], 5, utf8_decode("Núm."), 'TLR', 0, 'C', true);
					$pdf->Cell($widths[3], 5, "Entrada", 'TLR', 0, 'C', true);
					$pdf->Cell($widths[4], 5, "Salida", 'TLR', 0, 'C', true);
					$pdf->Cell($widths[5], 5, "Existencia", 'TLR', 1, 'C', true);
					$gran_total_e = $gran_total_s = $gran_existencia = 0;
					foreach ($movimientos_numero as $numero => $movimientos_producto) {
						if (isset($inventario[$espacio_id][$marca_id][$producto_id])) {
							$numero_inventario = current($inventario[$espacio_id][$marca_id][$producto_id]);
							$fill = false;
							while ($movimientos_producto[0]['numero'] > $numero_inventario[2]) {
								$pdf->SetFillColor(240);
								$pdf->SetFont('Times', '', 10);
								$pdf->Cell($widths[0], 5, '', 'LT', 0, 'C', $fill);
								$pdf->Cell($widths[1], 5, "Existencia hasta el $fecha_inicio", 'LRT', 0, 'L', $fill);
								$pdf->Cell($widths[2], 5, $numero_inventario[2], 'LRT', 0, 'C', $fill);
								$pdf->Cell($widths[3], 5, '', 'LRT', 0, 'R', $fill);
								$pdf->Cell($widths[4], 5, '', 'LRT', 0, 'R', $fill);
								$pdf->Cell($widths[5], 5, $numero_inventario[0], 'LTR', 1, 'R', $fill);
								//array_shift($inventario[$espacio_id][$marca_id][$producto_id]);
								$gran_existencia += $numero_inventario[0];
								unset($inventario[$espacio_id][$marca_id][$producto_id][key($inventario[$espacio_id][$marca_id][$producto_id])]);
								$numero_inventario = current($inventario[$espacio_id][$marca_id][$producto_id]);
								$fill = !$fill;
							}
						}
						$pdf->SetFont('Times', 'B', 10);
						$fill = false;
						if (isset($inventario[$espacio_id][$marca_id][$producto_id][$numero])) {
							$existencia = $inventario[$espacio_id][$marca_id][$producto_id][$numero][0];
							unset($inventario[$espacio_id][$marca_id][$producto_id][$numero]);
						}else
							$existencia = 0;
						$total_e = $total_s = 0;
						$pdf->SetFillColor(230);
						$pdf->Cell($widths[0], 5, '', 'LT', 0, 'C', true);
						$pdf->Cell($widths[1], 5, "Existencia hasta el $fecha_inicio", 'LRT', 0, 'L', true);
						$pdf->Cell($widths[2], 5, $movimientos_producto[0]['numero'], 'LRT', 0, 'C', true);
						$pdf->SetFont('Times', '', 10);
						$pdf->Cell($widths[3], 5, '', 'LRT', 0, 'R', true);
						$pdf->Cell($widths[4], 5, '', 'LRT', 0, 'R', true);
						$pdf->SetFont('Times', '', 10);
						$pdf->Cell($widths[5], 5, $existencia, 'LTR', 1, 'R', true);
						$pdf->SetFillColor(240);
						$pdf->SetFont('Times', '', 10);
						foreach ($movimientos_producto as $movimiento) {
							$existencia += $movimiento['entrada'] - $movimiento['salida'];
							$pdf->Cell($widths[0], 5, $movimiento['fecha'], 'LR', 0, 'C', $fill);
							$pdf->Cell($widths[1] + $widths[2], 5, utf8_decode(($movimiento['tipo_e'] > 0 ?
									$entradas[$movimiento['tipo_e']] :
									$salidas[$movimiento['tipo_s']]) .
									($movimiento['movimiento'] != '' ? " {$movimiento['movimiento']}" : '')), 'LR', 0, 'L', $fill);
							$pdf->Cell($widths[3], 5, $movimiento['entrada'], 'LR', 0, 'R', $fill);
							$pdf->Cell($widths[4], 5, $movimiento['salida'], 'LR', 0, 'R', $fill);
							$pdf->Cell($widths[5], 5, $existencia, 'LR', 1, 'R', $fill);
							$fill = !$fill;
							$total_e += $movimiento['entrada'];
							$total_s += $movimiento['salida'];
						}
						$pdf->SetFillColor(255, 198, 201);
						$pdf->Cell($widths[0] + $widths[1] + $widths[2], 5, "Totales", 'L', 0, 'R', true);
						$pdf->Cell($widths[3], 5, $total_e, 'L', 0, 'R', true);
						$pdf->Cell($widths[4], 5, $total_s, 'L', 0, 'R', true);
						$pdf->Cell($widths[5], 5, '', 'LR', 1, '', true);
						$gran_total_e += $total_e;
						$gran_total_s += $total_s;
						$gran_existencia += $existencia;
					}
					$pdf->Cell($widths[0] + $widths[1] + $widths[2], 5, "Total por producto", 'T', 0, 'R');
					$pdf->Cell($widths[3], 5, $gran_total_e, 1, 0, 'R');
					$pdf->Cell($widths[4], 5, $gran_total_s, 1, 0, 'R');
					$pdf->Cell($widths[5], 5, $gran_existencia, 1, 1, 'R');
				}
			}
			if (isset($inventario[$espacio_id][$marca_id])) {
				$vacio = true;
				foreach ($inventario[$espacio_id][$marca_id] as $producto_id => $movimientos_producto) {
					if (count($movimientos_producto) == 0)
						unset($inventario[$espacio_id][$marca_id][$producto_id]);
					else
						$vacio = false;
				}
				if (!$vacio) {
					$pdf->SetFont('Times', 'B', 11);
					$pdf->Cell(0, 5, "Productos sin movimientos", 0, 1);
					$pdf->SetFont('Times', 'B', 10);
					$pdf->SetFillColor(204, 214, 255);
					$pdf->Cell($widths[0] + $widths[1] + $widths[3] + $widths[4], 5, "Producto", 'TLR', 0, 'C', true);
					$pdf->Cell($widths[2], 5, utf8_decode("Núm."), 'TLR', 0, 'C', true);
					$pdf->Cell($widths[5], 5, "Existencia", 'TLR', 1, 'C', true);
					$pdf->SetFont('Times', '', 10);
					$gran_total = 0;
					foreach ($inventario[$espacio_id][$marca_id] as $movimientos_producto) {
						$pdf->SetFillColor(240);
						$total_producto = 0;
						$fill = false;
						foreach ($movimientos_producto as $movimientos_numero) {
							$pdf->Cell($widths[0] + $widths[1] + $widths[3] + $widths[4], 5, utf8_decode($movimientos_numero[1]), 'LR', 0, 'L', $fill);
							$pdf->Cell($widths[2], 5, $movimientos_numero[2], 'LR', 0, 'C', $fill);
							$pdf->Cell($widths[5], 5, $movimientos_numero[0], 'LR', 1, 'R', $fill);
							$fill = !$fill;
							$total_producto += $movimientos_numero[0];
						}
						$pdf->SetFillColor(255, 198, 201);
						$pdf->Cell($widths[0] + $widths[1] + $widths[2] + $widths[3] + $widths[4], 5, "Total por Modelo", 'LB', 0, 'R', true);
						$pdf->Cell($widths[5], 5, $total_producto, 'LRB', 1, 'R', true);
						$gran_total += $total_producto;
					}
					$pdf->Cell($widths[0] + $widths[1] + $widths[2] + $widths[3] + $widths[4], 5, "Total por Marca", 0, 0, 'R');
					$pdf->Cell($widths[5], 5, $gran_total, 1, 1, 'R');
				}
			}
		}
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
