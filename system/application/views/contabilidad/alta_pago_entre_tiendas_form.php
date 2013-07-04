<?php
echo $this->assetlibpro->output('css');
echo $this->assetlibpro->output('js');
?>
<style>
#etiqueta_espacio {
	text-align: center;
	width: 100%;
	color: #ccc;
	background-color: black;
}

#criterios {
	font-size: 12pt;
	background-color: #ccc;
	color: green;
}
</style>
<script>
	function registrar_pago(linea, envia, recibe){
		pago=$('#abono_'+linea).val();
		concept=$('#tipo'+linea).val();
		fecha="<?echo "$anio-$mes-$dia"; ?>";
		if(pago>0 && concept>0){
			$.post("<? echo base_url();?>index.php/contabilidad/trans/alta_pago_entre_tiendas/",{ envia_id: envia, recibe_id: recibe, importe: pago, fecha_pago:fecha, concepto:concept },  // create an object will all values
			function(data){
				$('#respuesta_'+linea).html(data);
			});
		}
	}
</script>
<?php
echo "<h2>Detalle de Deuda entre Tiendas</h2>";
echo "<div id='criterios'>Se eligio como criterios: Año: <strong>$anio</strong>, Mes: <strong>{$meses[$mes]}</strong><br/>El periodo comprendido es del <strong>$fecha1</strong> a <strong>$fecha2</strong></div>";

$es_anterior=0; $i=0;
foreach($periodo as $row){
	if($row['recibe']!=$es_anterior){
		if($es_anterior>0 ){
			echo "<tr><td>Total</td><td align='right'>".number_format($suma_acum[$es_anterior],2,".",",") ."</td><td  align='right'>".number_format($suma[$es_anterior],2,".",",") ."</td><td  align='right'>".number_format($total[$es_anterior],2,".",",") ."</td><td></td></tr>";
			echo "</table><br/>";
		}
		echo "<table width='650px' class='listados' valign='top'>";
		echo "<tr><th colspan='6'>{$row['recibe']} - ".$espacios[$row['recibe']]."</tr>";
		echo "<tr><th>Sucursal que envió</th><th>Anterior</th><th>Adeudo<br/>Periodo</th><th>SubTotal</th><th>Concepto</th><th>Abonar Pago</th></tr>";
	}

	echo "<tr><td valign='top'>".$espacios[$row['envia']]."</td>";
	echo "<td align='right' valign='top'>".number_format($row['acumulado'],2,".",",")."</td>";
	$acum=$row['acumulado'];

	echo "<td align='right' valign='top'>".number_format($row['costo_total'],2,".",",") ."</td>";

	echo "<td align='right' valign='top'>".number_format($row['costo_total']+$row['acumulado'],2,".",",") ."</td>";
	echo "<td valign='top'>"; echo form_dropdown("ctipo_pago_tienda_id$i", $tipo_pago, 0,"id='tipo$i'"); echo "</td>";
	echo "<td align='right' valign='top'><input type='text' name='abono_$i' id='abono_$i' value='0' size='6' class='subtotal'><span id='boton_$i'><button type='button' onclick='javascript:registrar_pago($i, {$row['envia']},{$row['recibe']})'>Pagar</button></span><br/><span id='respuesta_$i'></span></td></tr>";



	if(isset($suma[$row['recibe']]))
		$suma[$row['recibe']]+=$row['costo_total'];
	else
		$suma[$row['recibe']]=$row['costo_total'];

	if(isset($suma_acum[$row['recibe']]))
		$suma_acum[$row['recibe']]+=$acum;
	else
		$suma_acum[$row['recibe']]=$acum;
	if(isset($total[$row['recibe']]))
		$total[$row['recibe']]+=$row['costo_total']+$row['acumulado'];
	else
		$total[$row['recibe']]=$row['costo_total']+$row['acumulado'];

	$es_anterior=$row['recibe'];
	$i+=1;
}
echo "<tr><td>Total</td><td  align='right'>".number_format($suma_acum[$es_anterior],2,".",",") ."</td><td  align='right'>".number_format($suma[$es_anterior],2,".",",") ."</td><td  align='right'>".number_format($total[$es_anterior],2,".",",") ."</td><td></td></tr>";
echo "</table><br/>";
?>
