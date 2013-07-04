<?php
//Construir Cliente
$select2[0]="";
if($clientes!= false){
	foreach($clientes->all as $row){
		$y=$row->id;
		$select2[$y]=$row->razon_social ." - ". -$row->clave;
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
		//    $clave_list[$y]=$row->clave;
	}
}

$url_fecha=base_url()."index.php/".$ruta."/trans/alta_venta_detalles";

//Inputs
$monto_total=array(
		'name'=>'monto_total',
		'size'=>'40',
		'value'=>'',
);
$fecha_entrega=array(
		'class'=>'date_input',
		'name'=>'fecha_entrega',
		'id'=>'fecha_entrega',
		'size'=>'10',
		'readonly'=>'readonly'
);

$fecha_pago=array(
		'class'=>'date_input',
		'name'=>'fecha_pago',
		'id'=>'fecha_pago',
		'size'=>'10',
		'readonly'=>'readonly'
);
$observaciones=array(
		'name'=>'observaciones',
		'column'=>'40',
		'rows'=>'5',
		'value'=>'',
);

//Titulo
//$this->load->view('subformulario_default_js.php');
$this->load->view('ventas/js_alta_venta.php');
//$this->load->view('js_subformulario_cl_pedido');
echo "<h2>$title</h2>";
//Abrir Formulario
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';
$atrib=array('name'=>'form1', 'id'=>"form1");
echo form_open($ruta.'/trans/alta_venta', $atrib) . "\n";
echo form_fieldset('<b>Nuevo Pedido de Venta</b>') . "\n";
echo "<table width=\"950\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";

//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"empresas_id\">Vendedor:</label>$empresa->razon_social</td><td class='form_tag' colspan=\"2\"><label for=\"cclientes_id\">Cliente: </label>"; echo form_dropdown('cclientes_id', $select2, "0", "id='clientes' class='cliente'");echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"cpr_forma_pago_id\">Forma de Pago:</label>"; echo form_dropdown('ccl_forma_cobro_id', $select3, 3, "id='cobro'"); echo " </td><td class='form_tag'><label for=\"fecha_entrega\">Fecha de Entrega:</label>"; echo form_input($fecha_entrega); echo "</td><td class='form_tag'><label for=\"fecha_Pago\">Fecha de Pago:</label>"; echo form_input($fecha_pago); echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"observaciones\">Observaciones Generales:</label></td><td class='form_tag' colspan=\"2\">"; echo form_textarea($observaciones); echo "</td></tr>";

//Cerrar el Formulario
echo "<tr><td class='form_tag'><label for=\"ccl_estatus_pedido_id\">Estatus del pedido:</label>"; echo form_dropdown('ccl_estatus_pedido_id', $select5, 1, "disabled='disabled'"); echo "</td><td class=\"form_buttons\" colspan='2'>";
echo "<div id=\"out_form1\">";
echo form_hidden('id', '0');
//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
	echo '<button type="submit" id="boton1" style="display:block;">Paso 1. Guardar Datos Generales</button>';
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
		for($r=0;$r<$rows;$r++){

			echo "<tr><td class='detalle' width=\"50\">".($r+1).".- <form id=\"fdetail$r\" method=\"post\" name=\"fdetail$r\" action=\"$url_fecha/$r\" ><button type=\"submit\" id=\"b$r\" style=\"display:none;\">Guardar Registro</button><div id=\"content$r\">"; echo form_hidden("id$r", '0'); echo form_hidden("cl_pedidos_id$r", ''); echo "</div></td>".

	    "<td class='detalle' width=\"70\"><input type=\"text\" name=\"unidades$r\" size=\"4\" id=\"cantidad$r\"></td>".

	    "<td class='detalle' width=\"400\">"; echo form_dropdown("producto$r", $productos_list, 0, "id='producto$r' class='prod'"); echo "<div id='msg$r'></div></td>".

	    "<td class='detalle' width=\"150\"><span id=\"precio$r\"><select name=\"precio_u$r\" id=\"precio_u$r\"><option value='0'>Elija el producto</option></select></span>";  echo "</td>".

	    "<td class='detalle' width=\"50\"><span id=\"tasa_i$r\"><input class=\"subtotal\" value=\"0\" name=\"iva$r\" id=\"tasa_imp$r\" size=\"2\" ></span></td>".

	    "<td class='detalle' width=\"150\"><input type=\"text\" class=\"subtotal\" name=\"subtotal$r\" size=\"12\" readonly=\"readonly\"></form></td>".
	    "</tr>";
		}
		?>
	</table>
	<table width="870" class="row_detail" id="total">
		<tr>
			<th class='detalle_pie' width="760">Subtotal</th>
			<th class='detalle_pie'><form name="t1" method="post"
					class="row_detail" id="total1">
					<input name="subtotal" type="text" value="0" class="subtotal"
						id="subtotal1" size="10">
			
			</th>
		</tr>
		<tr>
			<th class='detalle_pie' width="760">IVA</th>
			<th class='detalle_pie'><input id="iva_total" name="iva" type="text"
				value="0" class="subtotal" size="10"></th>
		</tr>
		<tr>
			<th class='detalle_pie' width="760">Total</th>
			<th class='detalle_pie'><input name="total" id="total" type="text"
				value="0" class="subtotal" size="10">
				</form></th>
		</tr>
		<tr>
			<th class='detalle_pie' width="870" colspan="2"><?php
			echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Salir al Menú</button>";
			if(substr(decbin($permisos), 0, 1)==1){
				echo '<button type="button" onclick="javascript:send_detalle()">Paso 2. Guardar Detalles</button>';
			}
			?> <span id="fin"></span><br>Al Guardar los detalles del Pedido
				verifique que se han guardado adecuadamente cada uno de ellos.</th>
		</tr>
	</table>
</div>
<?php
unset($productos);
unset($clientes);
//Link al listado
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_ventas\"><img src=\"".base_url()."images/consumo.png\" width=\"50px\" title=\"Listado de Pedidos de Venta\"></a><a href=\"".base_url()."index.php/".$ruta."/clientes_c/".$funcion."/alta_cliente\"><img src=\"".base_url()."images/proveedor.png\" width=\"50px\" title=\"Alta de Cliente\"></a></div>";
?>