<html>
<head>
<title><?=$estatus?>
</title>
<?= $this->assetlibpro->output('css'); ?>
<?= $this->assetlibpro->output('js'); ?>
<script>
	function cerrar(){
		alert("Recuerde Actualizar la página del Listado de Pre Pedidos Automatizados para ver los nuevos pedidos");
	}
	function imprimir(){
		window.print();
	}
</script>
</head>
<body onunload="javascript:cerrar()">
	<?php
	echo "<h2>Grupo Pavel</h2>";
	echo "<h2>$estatus</h2>";
	$img_row="".base_url()."images/table_row.png";
	?>
	<table class="listado" width="90%">
		<tr>
			<th colspan='4'>Fecha de Clonación: <? echo date('d-m-Y');?>
			</th>
		</tr>
		<tr>
			<th>Id Pedido</th>
			<th>Proveedor</th>
			<th>Sucursal Origen</th>
			<th>Monto</th>
		</tr>
		<tr background="<?=$img_row?>">
			<td align="center"><?=$pedido->id?>
			</td>
			<td align="center"><?=$pedido->razon_social?>
			</td>
			<td align="center"><?=$pedido->espacio?>
			</td>
			<td align="center"><? echo number_format($pedido->monto_total,2,".",","); ?>
			</td>
		</tr>
	</table>
	<br />
	<br />
	<table class='listado' width="90%">
		<tr>
			<th colspan="2">Detalle de los Pedidos Generados a partir del Inicial</th>
		</tr>
		<tr>
			<th>Id Pedido</th>
			<th>Sucursal</th>
		</tr>
		<?php
		$c=1; $y=1;
		foreach($detalles as $row){
			echo "<tr><td align=\"center\">{$row['id']}</td><td align=\"center\">{$row['espacio']}</td></tr>";
		}
		?>
		<tr>
			<th colspan="3" align="right">
				<button type="button" onclick="javascript:imprimir()">Imprimir
					Constancia</button>
			</th>
		</tr>
	</table>
	<?php 
	echo form_hidden('pedido_id', $pedido->id);
echo form_fieldset_close();
echo form_close();
?>
</body>
</html>
