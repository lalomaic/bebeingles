<script type="text/javascript"> 
  $(document).ready(function() { 
  $($.date_input.initialize);
});
</script>
<?php
echo "<h2>$title</h2>";
//Abrir Formulario
echo form_fieldset('<b>Datos de la Factura</b>') . "\n";
//Mensajes de validacion
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';

echo "<table width='900' class='form_table'>";

//Campos del Formulario
echo "<tr><td class='form_tag'><label>Id Factura:</label><br/>$factura->id</td><td class='form_tag'>Folio:</label><br/>$factura->folio_factura</td>".
		"<td class='form_tag'><label >Proveedor:</label><br/>$factura->proveedor</td><td class='form_tag'>Fecha:</label><br/>$factura->fecha</td>".
		"<td class='form_tag'>Monto Total:</label><br/>$factura->monto_total</td><td class='form_tag'>Estatus:</label><br/>$factura->estatus_factura</td></tr></table>";
echo form_fieldset_close();

echo form_fieldset('<b>Pagos Realizados a la Factura</b>') . "\n";
echo "<table width='900' class='listado'>";
$img_row="".base_url()."images/table_row.png";
$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Eliminar Registro\" border=\"0\">";
?>
<tr>
	<th>Id Pago</th>
	<th>Fecha Pago</th>
	<th>Forma de Pago</th>
	<th>Referencia</th>
	<th>Monto Pagado</th>
	<th>Cancelar</th>
</tr>
<?php
if($pagos!=false){
	$total=0;
	foreach($pagos->all as $row){
		$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_pago/".$row->id." \" onclick=\"return confirm('¿Estas seguro que deseas borrar el pago por $row->importe ?');\">$trash</a>";
			
		echo "<tr  background=\"$img_row\"><td align='center'>$row->id</td><td align='center'>$row->fecha</td><td align='center'>$row->forma_pago</td><td align='right'>$row->numero_referencia</td><td align='right'><strong>" .number_format($row->importe,2,".",",")."</strong></td><td  align='center'>$delete</td></tr>";
		$total+=$row->importe;
	}
	echo "<tr><th colspan='4' align='right'>Monto Pagado</th><td align='right'><strong>" .number_format($total,2,".",",")."</strong></td></tr>";
	echo "<tr><th colspan='4' align='right'>Adeudo</th><td align='right'><strong>" .number_format($factura->monto_total-$total,2,".",",")."</strong></td></tr>";
} else
	echo "<tr><th colspan='6'>Sin Pagos </th></tr>";
echo "</table>";
echo form_fieldset_close();

//Link al listado
echo "<div align=\"center\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_pagos\"><img src=\"".base_url()."images/consumo.png\" width=\"50px\" title=\"Listado de Pagos\"></a></div>";


?>