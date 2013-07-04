<?php
$espacios_str=implode(",",$espacios);
?>
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
<? echo $this->assetlibpro->output('js'); ?>
<script>
$(document).ready(function(){
	<?
		$i=1;
		foreach ($datos as $k=>$v) {
			if(isset($v['ruta_foto'])){
				echo " $('#foto$k').focus(function(){\n";
				echo " get_imagen($k,'{$v['ruta_foto']}'); \n";
				echo " }); \n";
				echo " $('#descuento$i').focus(function(){\n";
				echo "  $('#descuento$i').val('');\n";
				echo " }); \n";
		}
			$i+=1;
		}
	?>
});
	function get_imagen(linea, ruta){
		$.post("<? echo base_url();?>index.php/ajax_pet/get_imagen/", { ruta_foto: ruta},  // create an object will all values
			function(data){
				$('#foto'+linea).html(data);
			});

	}
	
	function descontar(linea){
		id=$('#cproducto_id'+linea).val();
		des=$('#descuento'+linea).val();
		if($('#bar'+linea).attr('checked')){
			familia=5;
		} else
			familia=0;
		if(des>0){
			$.post("<? echo base_url();?>index.php/ajax_pet/prod_estacionado_descuento/", { line: linea, cproducto_id: id, espacios:'<?=$espacios_str?>', descuento: des, fam:familia},  // create an object will all values
			function(data){
				$('#estatus'+linea).html(data);
			});
		}
	}
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
	echo "<tr><th width='200px'  bgcolor='#ccc'>Descripcion</th><th bgcolor='#ccc'>Precio<br/>Global</th><th bgcolor='#ccc'>Precio<br/>Compra</th><th bgcolor='#ccc'>Depto.<br/>Ofertas</th><th bgcolor='#ccc'></th>";
	foreach($espacios as $k=>$v){
		echo "<th width='{$width}px' bgcolor='#ccc'>{$espacios_tag[$k]}</th>";
	}
	echo "<th width='{$width}px' bgcolor='#ccc'>Total</th></th>";
	$t_importe=0; $t_pares=0;
	$t=0; $renglon=array(); $total=0; $pares_renglon=0;
	$i=1;
	foreach ($datos as $k=>$v) {

		$suma_compra=0; $suma_venta=0; $suma_existencia=0; $total_existencia=0; $suma_existencia_abs=0;
		if(!($i%2))
			$fondo="bgcolor='#cccccc'";
		else
			$fondo="bgcolor='#ffffff'";

		if(strlen($v['ruta_foto'])>2){
			//$foto="<img src='".base_url()."/".$v['ruta_foto']."' width='150px'>";
			$foto="<div  id='foto$k' style='color:blue;'><a href=\"javascript:get_imagen('$k', '".$v['ruta_foto']."');\">-Ver Foto-</a></div>";
		} else {
			$foto="Sin foto";
		}
		if($v['familia']==5)
			$fam="<input type=\"checkbox\" name=\"bar$i\" value='1' id=\"bar$i\" class=\"bar\" checked='checked'>";
		else
			$fam="<input type=\"checkbox\" name=\"bar$i\" value='1' id=\"bar$i\" class=\"bar\">";

		echo "<tr class='listado_marcas' $fondo ><td rowspan='7'><input type='hidden' name='cproducto_id$i' id='cproducto_id$i' value='$k'>";  echo "{$v['descripcion']}<br/>$foto<br/><input type='text' value='0' id='descuento$i' size='4' class='desc'><button type='button' onclick='javascript:descontar($i);'>Descuento</button></td><td rowspan='7'><div id='estatus$i'>{$v['precio_venta']}</div></td><td rowspan='7'>{$v['precio_compra']}</td><td rowspan='7'>$fam </td><td>C</td>";
		//Imprimir las compras
		foreach($espacios as $ek=>$ev){
			echo "<td align='right'>";  echo round($v['compra_'.$ev],0)."</td>";
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
		echo "<tr $fondo class='listado_marcas'><td>EI</td>";
		foreach($espacios as $ek=>$ev){
			echo "<td align='right'  title='{$espacios_tag[$ek]}'>".round($v['existencia_abs2_'.$ev])."</td>";
			$suma_existencia+=$v['existencia_abs2_'.$ev];
		}
		echo "<td align='right'>$suma_existencia</td></tr>";

		echo "<tr $fondo class='listado_marcas'><td>EF</td>";
		foreach($espacios as $ek=>$ev){
			echo "<td align='right'  title='{$espacios_tag[$ek]}'>".round($v['existencia_abs_'.$ev]); echo form_hidden("numeracion_{$i}_{$ev}", trim($v['numeros_'.$ev])); echo "</td>";
			$suma_existencia_abs+=$v['existencia_abs_'.$ev];
		}
		echo "<td align='right'>$suma_existencia_abs</td></tr>";

		//imprimir la numeracion
		// 		echo "<tr $fondo class='listado_marcas'><td>N</td>";
		// 		foreach($espacios as $ek=>$ev){
		// 			echo "<td align='right'  title='{$espacios_tag[$ek]}' >";  echo form_hidden("numeracion_{$i}_{$ev}", trim($v['numeros_'.$ev])); echo $v['numeros_'.$ev]."</td>";
		// 		}
		// 		echo "<td align='right'>-</td></tr>";

		//imprimir la fecha de traspaso salida
		echo "<tr $fondo class='listado_marcas'><td>Tra</td>";
		foreach($espacios as $ek=>$ev){
			echo "<td align='right'  title='{$espacios_tag[$ek]}'>";   echo $v['tr_entrada_'.$ev]."</td>";
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



		$i+=1;
	}
	echo "</table>";
	echo "<div style='width:100%;background-color:#ccc;text-align:right;'></div>";
	echo form_hidden('filas', $i);
	echo form_hidden('espacios', $espacios_str);
	echo form_close();

} else {
	show_error("No hay datos para los criterios seleccionados");
}

?>