<?= $this->assetlibpro->output('css'); ?>
<?= $this->assetlibpro->output('js'); ?>
<?php
echo "<h2>$title</h2>";
$img_row="".base_url()."images/table_row.png";
$atrib=array('name'=>'form1', 'id'=>'form1');
echo form_open($ruta."/trans/clonar_pedido/", $atrib) . "\n";
?>
<table class="listado" width="90%">
	<tr>
		<th>Id Pedido</th>
		<th>Proveedor</th>
		<th>Sucursal Origen</th>
		<th>Monto</th>
	</tr>
	<tr background="<?=$img_row?>">
		<td align="center"><?=$pedido->id?></td>
		<td align="center"><?=$pedido->razon_social?></td>
		<td align="center"><?=$pedido->espacio?></td>
		<td align="center"><? echo number_format($pedido->monto_total,2,".",","); ?>
		</td>
	</tr>
</table>
<br />
<br />
<table class='listado' width="90%">
	<tr>
		<th colspan="3">Paso No. 1 Seleccione a que Sucursales se clonará el
			actual pedido.</th>
	</tr>
	<?php
	$c=1; $y=1;
	foreach($espacios->all as $row){
		if($row->id!=$pedido->espacio_fisico_id){
			$state=false; $r_eid=array();
			if($c==1)
				echo "<tr>";
			echo "<td><label for=\"chk$y\">";echo form_checkbox("chk$y", $row->id, FALSE); echo "$row->tag:</label></td>";
			if($c==3){
				echo "</tr>";
				$c=0;
			}
			$c+=1;
			$y+=1;
		}
	}
	?>
	<tr>
		<th colspan="3" align="right"><button type="submit">Paso No. 2. Clonar</button>
		</th>
	</tr>
</table>
<?php 
echo form_hidden('pedido_id', $pedido->id);
echo form_fieldset_close();
echo form_close();
?>