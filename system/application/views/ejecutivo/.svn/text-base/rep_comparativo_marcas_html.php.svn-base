<? echo $this->assetlibpro->output('js'); ?>
<style>
table {
	background-color: #FFFFFF;
	border: 1px solid #ccc;
}

table tr td {
	border-bottom: 1px solid #ccc;
	border-right: 1px solid #ccc;
}
</style>
<script>
$(document).ready(function(){

});

	function marcar_linea(linea){
		if($('#producto_'+linea).attr('checked')){
			$('.fila'+linea).attr('checked', true);
		} else {
			$('.fila'+linea).attr('checked', false);
		}
	}
	function marcar_producto(linea){
		$('#producto_'+linea).attr('checked', true);
	}
</script>
<table width='100%' border="0" class='listado'>
	<tr>
		<th>
			<h2>
				<?=$title?>
				<br /> Periodo:
				<?=$fecha1?>
				-
				<?=$fecha2?>
				<br /> <b>Fecha de impresion: <?php echo date("d/m/Y")?><br /> Total
					de Resultados: <? echo count($datos); ?>
			
			</h2>
		</th>
	</tr>
</table>
<?php
$esp=count($espacios)+1;
$width=800/$esp;
$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta.'/ejecutivo_reportes/trans_pre_pedidos', $atrib) . "\n";

if(count($datos)>0){
	echo '<table border="0" class="listado1" border="1" bgcolor="#fff" style="font-size:9pt;">';
	$etiquetas=array(); $anchos=array();
	//Cabeceras de la tabla
	//CAlcular anchos dinamicos en base al maximo y minimo
	echo "<tr><th width='200px'  bgcolor='#ccc'>Descripcion</th><th bgcolor='#ccc'></th>";
	foreach($espacios as $k=>$v){
		echo "<th width='{$width}px' bgcolor='#ccc'>{$espacios_tag[$k]}</th>";
	}
	echo "<th width='{$width}px' bgcolor='#ccc'>Total</th></th>";
	$t_importe=0; $t_pares=0;
	$t=0; $renglon=array(); $total=0; $pares_renglon=0;
	$i=1;
	foreach ($datos as $k=>$v) {

		$suma_compra=0; $suma_venta=0; $suma_existencia=0; $total_existencia=0;
		if(!($i%2))
			$fondo="bgcolor='#cccccc'";
		else
			$fondo="bgcolor='#ffffff'";

		echo "<tr class='listado_marcas' $fondo ><td rowspan='4'>"; echo form_hidden("cproducto_id$i", $k); echo "{$v['descripcion']}</td><td>C</td>";
		//Imprimir las compras
		foreach($espacios as $ek=>$ev){
			echo "<td align='right'>".round($v['compra_'.$ev],0)."</td>";
			$suma_compra+=$v['compra_'.$ev];
		}
		echo "<td align='right'>$suma_compra</td></tr>";

		//imprimir las ventas
		echo "<tr $fondo class='listado_marcas'><td>V</td>";
		foreach($espacios as $ek=>$ev){
			echo "<td align='right'>".round($v['venta_'.$ev],0)."</td>";
			$suma_venta+=$v['venta_'.$ev];
		}
		echo "<td align='right'>$suma_venta</td></tr>";


		//imprimir las Existencias
		echo "<tr $fondo class='listado_marcas'><td>E</td>";
		foreach($espacios as $ek=>$ev){
			echo "<td align='right'>".round($v['existencia_'.$ev])."</td>";
			$suma_existencia+=$v['existencia_'.$ev];
		}
		echo "<td align='right'>$suma_existencia</td></tr>";


		//imprimir la numeracion
		echo "<tr $fondo class='listado_marcas'><td>N</td>";
		foreach($espacios as $ek=>$ev){
			echo "<td align='right'>";  echo form_hidden("numeracion_{$i}_{$ev}", trim($v['numeros_'.$ev])); echo $v['numeros_'.$ev]."</td>";
		}
		echo "<td align='right'>-</td></tr>";

		$i+=1;
	}
	echo "</table>";
	echo form_hidden('filas', $i);
	echo form_close();
} else {
	show_error("No hay datos para los criterios seleccionados");
}

?>