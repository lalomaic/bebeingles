<?= $this->assetlibpro->output('css'); ?>
<table width='100%' border="0" class='listado'>
	<tr>
		<th><h2>
				<? echo $title; ?>
				<br /> <b>Fecha de impresion: <?php echo date("d/m/Y")?>
					</h3>
		
		</th>
	</tr>
</table>
<table width='800' border="0" align="center">
	<tr>
		<th id="header" widht="80">#</th>
		<th id="header">Sucursal</th>
		<th id="header">Venta</th>
		<th id="header">Gastos</th>
		<th id="header">Fiscal</th>
		<th id="header">Efectivo</th>
		<th id="header">Productos</th>
	</tr>
	<?php
	$r=1;
	$total=0; $total_venta=0; $total_gastos=0; $total_fiscal=0; $total_otrose=0; $total_pares=0;
	foreach($global as $row){
		$total+=$row['efectivo'];
		$total_venta+=$row['venta'];
		$total_gastos+=$row['gastos'];
		$total_fiscal+=$row['fiscal'];
		$total_otrose+=$row['otros'];
		$total_pares+=$row['pares'];

		//$total_b+=$row->venta_total;
		echo "<tr><td>$r</td><td>{$row['tag']}</td><td align=\"right\">$ ". number_format($row['venta'], 2, ".",",") ."</td><td align=\"right\">$ ". number_format($row['gastos'], 2, ".",",") ."</td><td align=\"right\">$ ". number_format($row['fiscal'], 2, ".",",") ."</td><td align=\"right\">$ ". number_format($row['efectivo'], 2, ".",",") ."</td><td align='right'> ". number_format($row['pares'], 0) ."</td></tr>";
		$r+=1;
	}
	?>
	<tr>
		<th id="header" widht="80"></th>
		<th id="header" align="right">Totales</th>
		<td id="header" align="right">$ <? echo number_format($total_venta, 2, ".",","); ?>
		</td>
		<td id="header" align="right">$ <? echo number_format($total_gastos, 2, ".",","); ?>
		</td>
		<td id="header" align="right">$ <? echo number_format($total_fiscal, 2, ".",","); ?>
		</td>
		<td id="header" align="right">$ <? echo number_format($total, 2, ".",","); ?>
		</td>
		<td id="header" align="right"><?=number_format($total_pares, 0) ?></td>
	</tr>
</table>
</table>
<table width='100%' border="0" class='listado'>
	<tr>
		<td id="data1"><center>
				<img src="<?php echo base_url();?>tmp/ventas_globales1.jpeg"
					width="600">
			</center></td>
	</tr>
</table>
