<?php
//Ubicaciones
$select2[0]="Elija";
if($espacios_fisicos!= false){
	foreach($espacios_fisicos->all as $row){
		$y=$row->id;
		$select2[$y]=$row->tag;
	}
}

//Estatus
$select4=array();
if($estatus!= false){
	foreach($estatus->all as $row){
		$y=$row->id;
		$select4[$y]=$row->tag;
	}
}

//Productos
$productos_list[0]="Elija";
if($productos != false){
	foreach($productos->all as $row){
		$y=$row->id;
		$productos_list[$y]=$row->clave." - ".$row->descripcion ." - ".$row->presentacion." - ".$row->unidad_medida;
	}
}
$url_form=base_url()."index.php/".$ruta."/trans/alta_pedido_transferencia_detalles";

//Inputs
$monto_total=array(
		'name'=>'monto_total',
		'size'=>'40',
		'value'=>'',
);

//Dates input
$fecha_entrega=array(
		'class'=>'date_input',
		'name'=>'fecha_entrega',
		'id'=>'fecha_entrega',
		'size'=>'10',
		'readonly'=>'readonly',
		'value'=>'',
);

$fecha_pago=array(
		'class'=>'date_input',
		'name'=>'fecha_pago',
		'id'=>'fecha_pago',
		'size'=>'10',
		'readonly'=>'readonly',
		'value'=>'',
);
$this->load->view('tienda/js_subformulario');

//Titulo
echo "<h2>$title</h2>";
//Abrir Formulario
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';
$atrib=array('name'=>'form1', 'id'=>"form1");
echo form_open($ruta.'/trans/alta_transferencia', $atrib) . "\n";
echo form_fieldset('<b>Datos Generales del Pedido de Traspaso</b>') . "\n";
echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";

//Campos del Formulario
echo "<tr><td class='form_tag' colspan='2'><label for=\"empresa\">Empresa:</label> $empresa->razon_social </td></tr>";

echo "<tr><td class='form_tag'><label for=\"ubicacion_salida_id\">AlmacÃ©n/Tienda de surtido: </label>"; echo form_dropdown('ubicacion_salida_id', $select2, "2", "id='ubicacion_salida'");echo "</td><td class='form_input'><label for=\"cpr_estatus_pedido_id\">Estatus del Pedido: </label>"; echo form_dropdown('cpr_estatus_pedido_id', $select4, "2", "disabled='disabled'"); echo "</td></tr>";

//Cerrar el Formulario
echo "<tr><td class=\"form_buttons\"></td><td class=\"form_buttons\">";
echo "<div id=\"out_form1\">";
//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
	echo '<button type="submit" id="boton1" style="display:block;">Paso 1. Guardar Generales</button>';
}
echo "</div>";
echo '</td></tr></table>';
echo form_fieldset_close();
echo form_close();
?>
<div id="subform_detalle">
	<table width="870" class="row_detail_pred" id="header">
		<tr>
			<th class='detalle_header' width="50px" heigth="20px">Estatus</th>
			<th class='detalle_header' width="70">Cantidad</th>
			<th class='detalle_header' width="400">CLAVE - Producto -
				Presentacion U. Med</th>
			<th class='detalle_header' width="150">Precio U.</th>
			<th class='detalle_header' width="50">IVA</th>
			<th class='detalle_header' width="150">SubTotal</th>
		</tr>
	</table>
	<?php
	//Imprimir valores actuales
	$r=0;
	for($r;$r<$rows;$r++){
		echo "<p id=\"detail$r\" ><form id=\"fdetail$r\" method=\"post\" name=\"fdetail$r\" action=\"$url_form/$r\"><table border=\"1\" width=\"870\" class=\"row_detail\" id=\"table_row$r\"><tr>".

				"<td class='detalle' width=\"50\"><button type=\"submit\" id=\"b$r\" style=\"display:none;\">Guardar Registro</button><div id=\"content$r\">"; echo form_hidden("id$r", '0');  echo form_hidden("cl_pedidos_id$r", "0"); echo "</div></td>".

				"<td class='detalle' width=\"70\"><input type=\"text\" name=\"unidades$r\" size=\"4\" value='0' onchange=\"javascript:calc($r)\" id=\"i$r\"></td>".
				/*"<td class='detalle' width=\"100\">"; echo form_dropdown("clave$r", $clave_list, 0, "onchange='javascript:sinc_clav(this.value, \"$r\");'"); echo "</td>".*/
		"<td class='detalle' width=\"400\">"; echo form_dropdown("producto$r", $productos_list, 0, "id='producto$r' class='prod'"); echo "</td>".

		"<td class='detalle' width=\"150\"><span id=\"precio$r\"><input type=\"text\" name=\"precio_u$r\" size=\"12\" onchange=\"javascript:calc($r)\" value=\"0\" id=\"precio_u$r\"></span></td>".

		"<td class='detalle' width=\"50\"><span id=\"tasa_i$r\"><input value=\"0\" name=\"iva$r\" id=\"tasa_imp$r\" size=\"2\" onchange=\"javascript:calc($r)\"></span></td>".

		"<td class='detalle' width=\"150\"><input type=\"text\" name=\"subtotal$r\" size=\"6\" readonly=\"readonly\" id=\"subtotal\"></td>".
		"</tr></table></form</p>";
	}
	?>
	<table width="870" class="row_detail_prev" id="total">
		<tr>
			<th class='detalle_header' width="720">Total</th>
			<th class='detalle_header' width="150"><form name="t1" method="post"
					class="row_detail_prev" id="total1">
					<input name="total" type="text" value="0">
				</form></th>
		</tr>
		<tr>
			<th class='detalle_header' width="730" colspan="2" align="right"><?php
			echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Cerrar sin guardar</button>";
			if(substr(decbin($permisos), 0, 1)==1){
				echo '<button type="button" onclick="javascript:send_detalle()">Paso 2. Guardar Detalles</button>';
			}
			?> <span id="fin"></span><br>Al Guardar los detalles del Traspaso
				verifique que se han guardado adecuadamente cada uno de ellos.</th>
		</tr>
	</table>
</div>
<?php
//Link al listado
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_transferencias\"><img src=\"".base_url()."images/consumo.png\" width=\"50px\" title=\"Listado de Pedidos de Traspaso\"></a></div>";

?>