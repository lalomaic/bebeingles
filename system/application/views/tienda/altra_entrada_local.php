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
$url_form=base_url()."index.php/".$ruta."/trans/alta_salida_traspasos";
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
$this->load->view('almacen/js_subformulario_tras');

//Titulo
echo "<h2>$title</h2>";
//Abrir Formulario
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';
$atrib=array('name'=>'form1', 'id'=>"form1");
echo form_open($ruta.'/trans/alta_traspaso', $atrib) . "\n";
echo form_fieldset('<b>Pedido de Traspaso</b>') . "\n";
echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";

//Campos del Formulario
echo "<tr><td class='form_tag' colspan='2'><label for=\"empresa\">Empresa:</label> $empresa->razon_social </td></tr>";

echo "<tr><td class='form_tag'><label for=\"ubicacion_salida_id\">Almac�n/Tienda solicitante: </label>"; echo form_dropdown('ubicacion_salida_id', $select2, "2", "disabled='disabled' id='ubicacion_salida'");echo "</td><td class='form_input'><label for=\"cpr_estatus_pedido_id\">Estatus del Pedido: </label>"; echo form_dropdown('cpr_estatus_pedido_id', $select4, "2", "disabled='disabled'"); echo "</td></tr>";

//Cerrar el Formulario
echo "<tr><td class=\"form_buttons\"></td><td class=\"form_buttons\">";
echo "<div id=\"out_form1\">";
echo form_hidden("traspasos_id", "$traspaso->id");
//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
	echo '<button type="submit" id="boton1" style="display:none;">Paso 1. Guardar Generales</button>';
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
	if($cl_detalle!=false){
		$suma=0;
		foreach($cl_detalle->all as $linea){
			echo "<p id=\"detail$r\" ><form id=\"fdetail$r\" method=\"post\" name=\"fdetail$r\" action=\"$url_form/$r\"><table border=\"1\" width=\"870\" class=\"row_detail_prev\" id=\"table_row$r\"><tr>".

					"<td class='detalle' width=\"50\"><button type=\"submit\" id=\"b$r\" style=\"display:none;\">Guardar Registro</button><div id=\"content$r\">"; echo form_hidden("traspaso_id$r", "$traspaso->id"); echo "</div></td>".

					"<td class='detalle' width=\"70\"><input type=\"text\" name=\"unidades$r\" size=\"4\" id=\"i$r\" value=\"$linea->cantidad\"></td>".

					"<td class='detalle' width=\"400\">"; echo form_dropdown("producto$r", $productos_list, "$linea->cproductos_id", "id='producto$r'  class='prod'"); echo "</td>".

					"<td class='detalle' width=\"150\"><span id=\"precio$r\"><input type=\"input\" name=\"precio_u$r\" id=\"precio_u$r\" value=\"$linea->costo_unitario\" readonly='readonly' size=\"4\"></span></td>".

					"<td class='detalle' width=\"50\"><span id=\"tasa_i$r\"><input name=\"iva$r\" id=\"tasa_imp$r\" size=\"2\" value=\"$linea->tasa_impuesto\" ></span></td>".

					"<td class='detalle' width=\"150\" align='right'><input type=\"text\" name=\"subtotal$r\" size=\"10\" readonly=\"readonly\" value=\"$linea->costo_total\" style='text-align:right;'></td>".
					"</tr></table></form></p>";
			$suma +=$linea->costo_total;
			$r+=1;

		}
	}
	?>
	<table width="870" class="row_detail_prev" id="total">
		<tr>
			<th class='detalle_header' width="730">Total</th>
			<th class='detalle_header' width="150" align='right'><form name="t1"
					method="post" class="row_detail_prev" id="total1">
					<input name="total" type="text" value="<? echo $suma; ?>"
						style='text-align: right;' size='10'>
				</form></th>
		</tr>
		<tr>
			<th class='detalle_header' width="730" colspan="2" align="right"><?php
			echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Cerrar sin guardar</button>";
			if(substr(decbin($permisos), 0, 1)==1){
				echo '<button type="button" onclick="javascript:send_detalle()">Paso 2. Dar Salida al Traspaso</button>';
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