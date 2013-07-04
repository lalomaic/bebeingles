<?php
foreach($pedido_venta->all as $reng){
	//Definir el numero de renglones a escribirse que caben en una factura
	$rows=20;
	//Construir Cliente
	$select2[0]="Elija";
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
	$url_fecha=base_url()."index.php/".$ruta."/trans/alta_salidas";
	//Inputs
	$monto_total=array(
			'name'=>'monto_total',
			'id'=>'monto_total',
			'size'=>'10',
			'value'=>''.$reng->monto_total,
	);
	$observaciones=array(
			'name'=>'observaciones',
			'column'=>'40',
			'rows'=>'5',
			'value'=>''.$reng->observaciones,
	);

	$this->load->view('almacen/js_alta_salidas');
	//$this->load->view('js_subformulario_salida');
	//Titulo
	echo "<h2>$title</h2>";
	//Abrir Formulario
	$atrib=array('name'=>'form1', 'id'=>"form1");
	echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';
	echo form_open($ruta.'/trans/alta_cl_factura', $atrib) . "\n";
	echo form_fieldset('<b>Alta de Salida de Pedido de Venta</b>') . "\n";
	echo "<table width=\"100%\" class='form_table'>";
	$img_row="".base_url()."images/table_row.png";

	//Campos del Formulario
	echo "<tr><td class='form_tag'><label for=\"empresas\">Vendedor:</label>$empresa->razon_social </td><td class='form_tag'><label for=\"cclientes_id\">Cliente: </label>"; echo form_dropdown('cclientes_id', $select2, "$reng->cclientes_id", "id='clientes'");echo "</td><td class='form_tag'><label for=\"monto_total\">Monto Total de la Factura:</label>"; echo form_input($monto_total); echo "</td></tr>";

	echo "<tr><td class='form_tag'><label for=\"observaciones\">Observaciones Generales:</label></td><td class='form_tag' colspan=\"2\">"; echo form_textarea($observaciones); echo "</td></tr>";

	//Cerrar el Formulario
	echo "<tr><td colspan='2' class=\"form_buttons\"></td><td class=\"form_buttons\">";
	echo "<div id=\"out_form1\">";
	echo form_hidden('cl_pedido_id', "$reng->id");
	echo form_hidden('id', "");
	//Permisos de Escritura byte 1
	if(substr(decbin($permisos), 0, 1)==1){
		echo '<button type="submit" id="boton1" style="display:none;">Paso 2. Guardar Datos Generales</button>';
	}
	echo "</div>";
	echo '</td></tr></table>';
	echo form_fieldset_close();
	echo form_close();
	?>
<div id="subform_detalle">
	<table width="870" class="row_detail" id="header">
		<tr>
			<th class='detalle_header'>Estatus</th>
			<th class='detalle_header'>Cantidad</th>
			<th class='detalle_header'>CLAVE - Producto - Presentacion U. Med</th>
			<th class='detalle_header'>Precio U.</th>
			<th class='detalle_header'>IVA</th>
			<th class='detalle_header'>SubTotal</th>
		</tr>
		<?php
		$iva=0;
		$iva_t=0;
		$subtotal_t=0;
		$r=0;
		if($cl_detalle!=false){
			foreach($cl_detalle->all as $linea){
				$iva=$linea->cantidad*$linea->costo_unitario*$linea->tasa_impuesto/(100+$linea->tasa_impuesto);
				$subtotal=($linea->costo_unitario*$linea->cantidad)-$iva;
				$iva_t+=$iva;
				$subtotal_t+=$subtotal;

				echo "<tr><td class='detalle' width=\"50\">".($r+1).".- <form id=\"fdetail$r\" method=\"post\" name=\"fdetail$r\" action=\"$url_fecha/$r\" ><button type=\"submit\" id=\"b$r\" style=\"display:none;\">Guardar Registro</button><div id=\"content$r\">"; echo form_hidden("cl_facturas_id$r", "$linea->cl_factura_id"); echo form_hidden("id$r", '0'); echo "</div></td>".

						"<td class='detalle' width=\"70\"><input type=\"text\" name=\"unidades$r\" size=\"4\" id=\"cantidad$r\" value=\"$linea->cantidad\"></td>".

						"<td class='detalle' width=\"400\">"; echo form_dropdown("producto$r", $productos_list, $linea->cproductos_id, "id='producto$r' class='prod'"); echo "<div id='msg$r'></div></td>".

						"<td class='detalle' width=\"150\"><span id=\"precio$r\"><input type=\"text\" name=\"precio_u$r\" id=\"precio_u$r\" value=\"$linea->costo_unitario\" size=\"6\" class=\"subtotal\"></span></td>".

						"<td class='detalle' width=\"50\"><span id=\"tasa_i$r\"><input class=\"subtotal\" name=\"iva$r\" id=\"tasa_imp$r\" size=\"6\" value='". number_format($iva, 3,".",","). "'></span></td>".

						"<td class='detalle' width=\"150\"><input type=\"text\" class=\"subtotal\" name=\"subtotal$r\" size=\"12\" readonly=\"readonly\" value='". number_format($subtotal, 3,".",","). "'></form></td>".
						"</tr>";  $r+=1;
			}
		}
		echo "</table>";
		?>
		<table width="900" class="row_detail" id="total">
			<tr>
				<th class='detalle_pie' width="760">Subtotal</th>
				<th class='detalle_pie'><form name="t1" method="post"
						class="row_detail" id="total1">
						<input name="subtotal" type="text"
							value="<? echo number_format($subtotal_t, 3, '.',','); ?>"
							class="subtotal" id="subtotal1" size="10">
				
				</th>
			</tr>
			<tr>
				<th class='detalle_pie' width="760">IVA</th>
				<th class='detalle_pie'><input id="iva_total" name="iva" type="text"
					value="<? echo number_format($iva_t,3,'.',','); ?>"
					class="subtotal" size="10"></th>
			</tr>
			<tr>
				<th class='detalle_pie' width="760">Total</th>
				<th class='detalle_pie'><input name="total" id="total" type="text"
					value="<? echo number_format(($subtotal_t+$iva_t),3,'.',','); ?>"
					class="subtotal" size="10">
					</form></th>
			</tr>
			<tr>
				<th class='detalle_pie' width="870" colspan="2"><?php
				echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Cerrar</button>";
				if(substr(decbin($permisos), 0, 1)==1){
					echo '<button type="button" onclick="javascript:send_detalle()" id="boton_p">Orden de Salida</button><span id="msg1"></span>';
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