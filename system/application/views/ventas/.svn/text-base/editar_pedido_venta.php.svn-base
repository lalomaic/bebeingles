<?php
foreach($pedido_venta->all as $reng){
	//Definir el numero de renglones a escribirse que caben en una factura
	$rows=20;
	//Construir Cliente
	$select2[0]="";
	if($clientes!= false){
		foreach($clientes->all as $row){
			$y=$row->id;
			$select2[$y]=$row->razon_social;
		}
	}

	//Tipo de Forma de Pago
	$select3=array();
	if($formas_cobro!= false){
		foreach($formas_cobro->all as $row){
			$y=$row->id;
			$select3[$y]=$row->tag;
		}
	}

	//Estatus del Pedido
	$select5=array();
	if($estatus!= false){
		foreach($estatus->all as $row){
			$y=$row->id;
			$select5[$y]=$row->tag;
		}
	}

	//Productos
	$productos_list[0]="";
	//$clave_list[0]="";
	if($productos != false){
		foreach($productos->all as $row){
			$y=$row->id;
			$productos_list[$y]=$row->clave." - ".$row->descripcion ." - ".$row->presentacion." - ".$row->unidad_medida;
		}
	}
	$url_fecha=base_url()."index.php/".$ruta."/trans/alta_venta_detalles";
	//Inputs
	$monto_total=array(
			'name'=>'monto_total',
			'size'=>'40',
			'value'=>''.$reng->monto_total,
	);
	//Dates input
	$fecha_pago1=explode("-", $reng->fecha_pago);
	$fecha_entrega1=explode("-", $reng->fecha_entrega);
	$fecha_pago_db="".$fecha_pago1[2]." ".$fecha_pago1[1]." ".$fecha_pago1[0];
	$fecha_entrega_db="".$fecha_entrega1[2]." ".$fecha_entrega1[1]." ".$fecha_entrega1[0];

	$fecha_entrega=array(
			'class'=>'date_input',
			'name'=>'fecha_entrega',
			'size'=>'10',
			'readonly'=>'readonly',
			'value'=> ''.$fecha_entrega_db
	);

	$fecha_pago=array(
			'class'=>'date_input',
			'name'=>'fecha_pago',
			'size'=>'10',
			'readonly'=>'readonly',
			'value'=> ''.$fecha_pago_db
	);

	$this->load->view('js_subformulario_cl_pedido_editar');
	//Titulo
	echo "<h2>$title</h2>";
	//Abrir Formulario
	$atrib=array('name'=>'form1', 'id'=>"form1");
	echo form_open($ruta.'/trans/alta_venta', $atrib) . "\n";
	echo form_fieldset('<b>Editar Pedido de Venta</b>') . "\n";
	echo "<table width=\"100%\" class='form_table'>";
	$img_row="".base_url()."images/table_row.png";


	//Campos del Formulario
	echo "<tr><td class='form_tag'><label for=\"empresas_id\">Vendedor:</label>$empresa</td><td class='form_tag' colspan=\"2\"><label for=\"cclientes_id\">Cliente: </label>"; echo form_dropdown('cclientes_id', $select2, "$reng->cclientes_id");echo "</td><td class='form_tag'><label for=\"origen_pedido_id\">Origen del Pedido:</label>"; echo form_dropdown('ccl_forma_cobro_id', $select3, "$reng->ccl_forma_cobro_id"); echo "</td></tr>";

	echo "<tr><td class='form_tag'><label for=\"ccl_estatus_pedido_id\">Estatus del pedido:</label>"; echo form_dropdown('ccl_estatus_pedido_id', $select5, "$reng->ccl_estatus_pedido_id", "disabled='disabled'"); echo "</td><td class='form_tag'><label for=\"fecha_entrega\">Fecha de Entrega:</label>"; echo form_input($fecha_entrega); echo "</td><td class='form_tag'><label for=\"fecha_Pago\">Fecha de Pago:</label>"; echo form_input($fecha_pago); echo "</td><td class='form_tag'></td></tr>";

	//Cerrar el Formulario
	echo "<tr><td colspan='3' class=\"form_buttons\"></td><td class=\"form_buttons\">";
	echo "<div id=\"out_form1\">";
	echo form_hidden('id', "$reng->id");
	//Permisos de Escritura byte 1
	if(substr(decbin($permisos), 0, 1)==1){
		echo '<button type="submit" id="boton1" style="display:none;">Paso 1. Guardar Datos Generales</button>';
	}
	echo "</div>";
	echo '</td></tr></table>';
	echo form_fieldset_close();
	echo form_close();
	?>
<div id="subform_detalle">
	<table width="900" class="row_detail" id="header">
		<tr>
			<th class='detalle_header' width="50">Estatus</th>
			<th class='detalle_header' width="70">Cantidad</th>
			<th class='detalle_header' width="400">CLAVE - Producto -
				Presentacion U. Med</th>
			<th class='detalle_header' width="200">Precio U.</th>
			<th class='detalle_header' width="50">IVA</th>
			<th class='detalle_header' width="100">SubTotal</th>
		</tr>
	</table>
	<?php
	$r=0;
	if($cl_detalle!=false){
		$costo_pedido=0;
		foreach($cl_detalle->all as $linea){
			echo "<p id=\"detail$r\" ><form id=\"fdetail$r\" method=\"post\" name=\"fdetail$r\" action=\"$url_fecha/$r\"><table border=\"1\" width=\"900\" class=\"row_detail_prev\" id=\"table_row$r\"><tr>".

					"<td class='detalle' width=\"50\"><button type=\"submit\" id=\"b$r\" style=\"display:none;\">Guardar Registro</button><div id=\"content$r\">"; echo form_hidden("id$r", "$linea->id"); echo form_hidden("cl_pedidos_id$r", "$linea->cl_pedidos_id"); echo "</div></td>".

					"<td class='detalle' width=\"70\"><input type=\"text\" name=\"unidades$r\" size=\"4\" id=\"i$r\" value=\"$linea->cantidad\"></td>".

					"<td class='detalle' width=\"400\">"; echo form_dropdown("producto$r", $productos_list, "$linea->cproductos_id", "id='producto$r'  class='prod'"); echo "</td>".

					"<td class='detalle' width=\"200\"><span id=\"precio$r\"><select name=\"precio_u$r\" id=\"precio_u$r\"><option value='$linea->costo_unitario'>$linea->costo_unitario</option></select></span></td>".

					"<td class='detalle' width=\"50\"><span id=\"tasa_i$r\"><input name=\"iva$r\" id=\"tasa_imp$r\" size=\"2\" value=\"$linea->tasa_impuesto\" ></span></td>".

					"<td class='detalle' width=\"100\"><input type=\"text\" name=\"subtotal$r\" size=\"10\" readonly=\"readonly\" value=\"$linea->costo_total\"></td>".
					"</tr></table></form></p>";
			$costo_pedido +=$linea->costo_total;
			$r+=1;
		}
	}


	for($r;$r<$rows;$r++){
		echo "<p id=\"detail$r\" ><form id=\"fdetail$r\" method=\"post\" name=\"fdetail$r\" action=\"$url_fecha/$r\"><table border=\"1\" width=\"870\" class=\"row_detail\" id=\"table_row$r\"><tr>".

				"<td class='detalle' width=\"50\"><button type=\"submit\" id=\"b$r\" style=\"display:none;\">Guardar Registro</button><div id=\"content$r\">";echo form_hidden("cl_pedidos_id$r", ''); echo "</div></td>".

				"<td class='detalle' width=\"70\"><input type=\"text\" name=\"unidades$r\" size=\"4\" onchange=\"javascript:calc($r)\" id=\"i$r\"></td>".

				"<td class='detalle' width=\"400\">"; echo form_dropdown("producto$r", $productos_list, 0, "id='producto$r' class='prod'"); echo "</td>".

				"<td class='detalle' width=\"150\"><span id=\"precio$r\"><select name=\"precio_u$r\" id=\"precio_u$r\"><option value='0'>Elija el producto</option><option value=\"1\">precio1</option></select></span></td>".

				"<td class='detalle' width=\"50\"><span id=\"tasa_i$r\"><input value=\"0\" name=\"iva$r\" id=\"tasa_imp$r\" size=\"2\" value=\"0\" onchange=\"javascript:calc($r)\"></span></td>".

				"<td class='detalle' width=\"150\"><input type=\"text\" name=\"subtotal$r\" size=\"12\" readonly=\"readonly\"></td>".
				"</tr></table></form></p>";
	}
	?>
	<table width="870" class="row_detail" id="total">
		<tr>
			<th class='detalle_header' width="720">Total</th>
			<th class='detalle_header' width="150"><form name="t1" method="post"
					class="row_detail" id="total1">
					<input name="total" type="text"
						value="<?php echo $costo_pedido; ?>">
				</form></th>
		</tr>
		<tr>
			<th class='detalle_header' width="870" colspan="2" align="right"><?php
			echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Cerrar sin guardar</button>";
			if(substr(decbin($permisos), 0, 1)==1){
				echo '<button type="button" onclick="javascript:send_detalle()">Actualizar Pedido de Venta</button>';
			}
			?> <span id="fin"></span><br>Al Guardar los detalles del Pedido
				verifique que se han guardado adecuadamente cada uno de ellos.</th>
		</tr>
	</table>
</div>
<?php
//Link al listado
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_ventas\"><img src=\"".base_url()."images/consumo.png\" width=\"50px\" title=\"Listado de Pedidos de Venta\"></a><a href=\"".base_url()."index.php/".$ruta."/clientes_c/".$funcion."/alta_cliente\"><img src=\"".base_url()."images/proveedor.png\" width=\"50px\" title=\"Alta de Cliente\"></a></div>";
}
?>