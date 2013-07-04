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
	<?
		$i=1;
		foreach ($datos as $k=>$v) {
				echo " $('#foto$k').focus(function(){\n";
				echo " get_imagen($k,'{$v['ruta_foto']}'); \n";
				echo " }); \n";
		}
	?>
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
	function send(){
		document.form1.submit();
	}
	
	function get_imagen(linea, ruta){
		$.post("<? echo base_url();?>index.php/ajax_pet/get_imagen/", { ruta_foto: ruta},  // create an object will all values
			function(data){
				$('#foto'+linea).html(data);
			});
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
echo form_open($ruta.'/trans/pre_traspasos', $atrib) . "\n";

if(count($datos)>0){
	echo '<table border="0" class="listado1" border="1" bgcolor="#fff" style="font-size:9pt;">';
	$etiquetas=array(); $anchos=array();
	//Cabeceras de la tabla
	//CAlcular anchos dinamicos en base al maximo y minimo
	echo "<tr><th width='200px'  bgcolor='#ccc'>Descripcion</th><th bgcolor='#ccc'></th>";
	foreach($espacios as $k=>$v){
		echo "<th width='{$width}px' bgcolor='#ccc'>{$espacios_tag[$k]}</th>";
		$espacios_dd[$v]=$espacios_tag[$k];
	}
	echo "<th width='{$width}px' bgcolor='#ccc'>Total</th></th>";
	$t_importe=0; $t_pares=0;
	$t=0; $renglon=array(); $total=0; $pares_renglon=0;
	$i=1;
	foreach ($datos as $k=>$v) {

		$suma_compra=0; $suma_venta=0; $suma_existencia=0; $total_existencia=0;$suma_existencia_abs=0;
		if(!($i%2))
			$fondo="bgcolor='#cccccc'";
		else
			$fondo="bgcolor='#ffffff'";

		if(strlen($v['ruta_foto'])>2){
			$foto="<div  id='foto$k' style='color:blue;'><a href=\"javascript:get_imagen('$k', '".$v['ruta_foto']."');\">-Ver Foto-</a></div>";
		} else {
			$foto="Sin foto";
		}

		echo "<tr class='listado_marcas' $fondo ><td rowspan='9'>"; echo form_hidden("cproducto_id$i", $k); echo form_hidden("proveedor_id$i", $v['proveedor_id']); echo "{$v['descripcion']}<br/>$foto</td><td>C</td>";
		//Imprimir las compras
		foreach($espacios as $ek=>$ev){
			echo "<td align='right'  title='{$espacios_tag[$ek]}'>";  echo round($v['compra_'.$ev],0)."</td>";
			$suma_compra+=$v['compra_'.$ev];
		}
		echo "<td align='right'>$suma_compra</td></tr>";

		//imprimir las ventas
		echo "<tr $fondo class='listado_marcas'><td>V</td>";
		foreach($espacios as $ek=>$ev){
			echo "<td align='right'  title='{$espacios_tag[$ek]}'>".round($v['venta_'.$ev],0)."</td>";
			$suma_venta+=$v['venta_'.$ev];
		}
		echo "<td align='right'>$suma_venta</td></tr>";


		//imprimir las Existencias
		echo "<tr $fondo class='listado_marcas'><td>EP</td>";
		foreach($espacios as $ek=>$ev){
			echo "<td align='right'  title='{$espacios_tag[$ek]}'>".round($v['existencia_'.$ev]);  echo form_hidden("existencia_{$i}_$ev", round($v['existencia_'.$ev]))."</td>";
			$suma_existencia+=$v['existencia_'.$ev];
		}
		echo "<td align='right'>$suma_existencia</td></tr>";

		//imprimir las Existencias Absolutas
		echo "<tr $fondo class='listado_marcas'><td>ET</td>";
		foreach($espacios as $ek=>$ev){
			echo "<td align='right'  title='{$espacios_tag[$ek]}'>".round($v['existencia_abs2_'.$ev]); echo "</td>";
			$suma_existencia_abs+=$v['existencia_abs2_'.$ev];
		}
		echo "<td align='right'>$suma_existencia_abs</td></tr>";

		//imprimir la numeracion
		echo "<tr $fondo class='listado_marcas'><td>N</td>";
		foreach($espacios as $ek=>$ev){
			echo "<td align='right'>";  echo form_hidden("numeracion_{$i}_{$ev}", trim($v['numeros_'.$ev])); echo $v['numeros_'.$ev]."</td>";
		}
		echo "<td align='right'>-</td></tr>";


		//imprimir la fecha de compra
		echo "<tr $fondo class='listado_marcas'><td>F</td>";
		foreach($espacios as $ek=>$ev){
			echo "<td align='right'  title='{$espacios_tag[$ek]}'>";   echo $v['entrada_compra_'.$ev]."</td>";
		}
		echo "<td align='right'>-</td></tr>";

		//imprimir la fecha de compra
		echo "<tr $fondo class='listado_marcas'><td>P</td>";
		foreach($espacios as $ek=>$ev){
			echo "<td align='right'  title='{$espacios_tag[$ek]}'>";   echo $v['pr_pedido_'.$ev]."</td>";
		}
		echo "<td align='right'>-</td></tr>";


		//imprimir la fecha de traspaso salida
		echo "<tr $fondo class='listado_marcas'><td>TE</td>";
		foreach($espacios as $ek=>$ev){
			echo "<td align='right'  title='{$espacios_tag[$ek]}'>";   echo $v['tr_entrada_'.$ev]."</td>";
		}
		echo "<td align='right'>-</td></tr>";

		//Imprimir el select para el traspaso
		echo "<tr $fondo class='listado_marcas'><td>Tras</td>";
		foreach($espacios as $ek=>$ev){
			$espacio_local=$espacios_dd;
			unset($espacio_local[$ev]);
			$espacio_local[0]="Sin traspaso";
			echo "<td align='right' title='{$espacios_tag[$ek]}'>";   echo form_hidden("espacio_origen_id_{$i}_{$ev}", $ev); echo form_dropdown("espacio_destino_{$i}_$ev", $espacio_local,0)."</td>";
		}
		echo "<td align='right'>-</td></tr>";

		$i+=1;
	}
	$espacios_str=implode(",",$espacios);
	echo "</table>";
	echo "<div style='width:100%;background-color:#ccc;text-align:right;'><button type='submit'>Generar Pre-Traspasos</button>";
	echo form_hidden('filas', $i);
	echo form_hidden('espacios', $espacios_str);
	echo form_close();

} else {
	show_error("No hay datos para los criterios seleccionados");
}

?>