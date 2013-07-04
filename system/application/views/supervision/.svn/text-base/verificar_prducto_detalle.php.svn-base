<style>
.table tr td {
	padding: 0 10px;
}

.par {
	background: #EEE;
}
</style>
<h1 style="text-align: center">
	<?php echo "$producto->cproductos_descripcion Número $producto->cproducto_numero_numero_mm" ?>
</h1>
<table class="table">
	<tr>
		<th>E/S</th>
		<th>Operación</th>
		<th>Tienda</th>
		<th>Fecha</th>
		<th>Descripción</th>
		<th>Cantidad</th>
	</tr>
	<?php
	$par = false;
	foreach ($movimientos as $movimiento) {
		?>
	<tr <?php echo $par ? ' class="par"' : '' ?>>
		<td><?php echo $movimiento->entrada == 1 ? '<img src="'.base_url().'images/entrada18.png" alt="Entrada" title="Entrada" width="18" height="18"/>' : '<img src="'.base_url().'images/salida18.png" alt="Salida" title="Salida" width="18" height="18"/>' ?>
		</td>
		<td><?php echo $movimiento->movimiento ?></td>
		<td><?php echo $movimiento->tienda ?></td>
		<td><?php echo $movimiento->fecha_format ?></td>
		<td><?php
		if ($movimiento->entrada == 1)
			switch ($movimiento->ctipo_entrada) {
				case 1:
					$datos = explode(',', $movimiento->id_movimiento);
					?>Factura <a
			href='<?php echo base_url() ?>index.php/compras/compras_reportes/rep_pr_factura_pdf/<?php echo $datos[0] ?>'
			target="_blank"> <?php echo $datos[0] ?>
		</a>, Folio <?php echo $datos[1] ?> <?php
		break;
case 2:
	?>Traspaso (<a
			href='<?php echo base_url() ?>index.php/almacen/almacen_reportes/rep_pedido_traspaso/<?php echo $producto->lote_id ?>/3'
			target="_blank">Ir</a>)<?php
			break;
case 7:
	$datos = explode(',', $movimiento->id_movimiento);
	?>Arqueo <a
			href='<?php echo base_url() ?>index.php/supervision/supervision_reportes/rep_ajuste_pdf/<?php echo "$movimiento->espacios_fisicos_id/$datos[0]" ?>'
			target="_blank"> <?php echo $datos[0] ?>
		</a>, Detalle <?php echo $datos[1] ?> <?php
		break;
default:
	echo $movimiento->id_movimiento;
		}
		else
			switch ($movimiento->ctipo_entrada) {
				case 1:
					?>Remisión <?php echo $movimiento->id_movimiento ?> <?php
					break;
case 2:
	?>Traspaso (<a
			href='<?php echo base_url() ?>index.php/almacen/almacen_reportes/rep_pedido_traspaso/<?php echo $producto->lote_id ?>/3'
			target="_blank">Ir</a>)<?php
			break;
case 7:
	$datos = explode(',', $movimiento->id_movimiento);
	?>Arqueo <a
			href='<?php echo base_url() ?>index.php/supervision/supervision_reportes/rep_ajuste_pdf/<?php echo "$movimiento->espacios_fisicos_id/$datos[0]" ?>'
			target="_blank"> <?php echo $datos[0] ?>
		</a>, Detalle <?php echo $datos[1] ?> <?php
		break;
default:
	echo $movimiento->id_movimiento;
		}
		$par = !$par;
		?></td>
		<td style="text-align: right"><?php echo $movimiento->cantidad ?></td>
	</tr>
	<?php
	}
	?>
</table>
