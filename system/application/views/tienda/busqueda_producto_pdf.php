<? echo $this->assetlibpro->output('js'); ?>
<? echo $this->assetlibpro->output('css'); ?>
<?php
echo "<h2>Resultado de la busqueda del producto</h2>";
?>
<table class="listado" border="1" width="600px">
	<tr>
		<th width="300">Tienda</th>
		<th width="500">Descripcion</th>
                <th width="200">Talla</th>
		<th>Existencia</th>
                   <th>Apartados</th>
                <th>Disponibles</th>
	</tr>
	<?php
	foreach ($detalles as $row) {
		echo "<tr><td>{$row['tienda']}</td><td>{$row['tag']}</td><td>{$row['talla']}</td><td align='right' >{$row['existencias']}</td><td align='right'>{$row['apartados']}</td><td align='right'>{$row['disponibles']}</td></tr>";
	}
	?>
</table>
