<?php
//Definir el numero de renglones a escribirse que caben en una factura
$rows=10;
$url_form=base_url()."index.php/".$ruta."/trans/factura_salida";
//Construir Cliente
$select2[0]="";
if($clientes!= false){
	foreach($clientes->all as $row){
		$y=$row->id;
		$select2[$y]=$row->razon_social;
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
//Inputs
$folio_factura=array(
		'name'=>'folio_factura',
		'id'=>'folio_factura',
		'size'=>'10',
		'value'=>'',
);
$conceptos=array(
		'name'=>'conceptos',
		'size'=>'5',
		'value'=>'',
);
$fecha=array(
		'class'=>'date_input',
		'name'=>'fecha',
		'id'=>'fecha',
		'size'=>'10',
		'readonly'=>'readonly',
		'value'=>'',
);


$monto_total=array(
		'name'=>'monto_total',
		'id'=>'monto_total',
		'size'=>'10',
		'value'=>'',
);

$this->load->view('tienda/js_subformulario_factura_remision');
//Titulo
echo "<h2>$title en $tienda</h2>";
//Abrir Formulario
$atrib=array('name'=>'form1', 'id'=>"form1");
echo form_open($ruta.'/trans/alta_cl_factura', $atrib) . "\n";
echo form_fieldset('<b>Facturaci�n de Nota de Remisi�n</b>') . "\n";
echo "<table width=\"100%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";

//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"empresas\">Vendedor:</label>$empresa->razon_social</td><td class='form_tag'><label for=\"fecha\">Fecha de la Factura:</label>"; echo form_input($fecha); echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"cclientes_id\">Cliente: </label>"; echo form_dropdown('cclientes_id', $select2, "0", "id='clientes' class='cliente'");echo "</td><td class='form_tag'><label for=\"folio_factura\">Folio de la Factura:</label>"; echo form_input($folio_factura); echo "</td></tr>";

echo "<tr><td class='form_tag' colspan='2'><div id='datos_cliente'></div></td></tr>";

//Cerrar el Formulario
echo "<tr><td colspan='2' class=\"form_buttons\">";
echo "<div id=\"out_form1\">";
echo form_hidden('id', "0");
echo form_hidden('monto_total', "0");
echo form_hidden('iva_total', "0");
//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
	echo '<button type="submit" id="boton1" style="display:none;">Paso 2. Guardar Datos Generales</button>';
}
echo "</div>";
echo '</td></tr></table>';
echo form_fieldset_close();
echo form_close();
$r=0;
$total=0;
$subtotal1=0;
$iva=0;
if($salidas_remision!=false){
	?>
<div id="subform_detalle">
	<table width="870" class="row_detail" id="header" border="1">
		<tr>
			<th class='detalle_header'>Estatus</th>
			<th class='detalle_header'>Cantidad</th>
			<th class='detalle_header'>CLAVE - Producto - Presentacion U. Med</th>
			<th class='detalle_header'>Precio U.</th>
			<th class='detalle_header'>IVA</th>
			<th class='detalle_header'>SubTotal.</th>
		</tr>
		<?php
		foreach($salidas_remision->all as $linea){
			$iva1=($linea->costo_total * $linea->tasa_impuesto)/(100+$linea->tasa_impuesto);
			$subtotal=$linea->costo_total-$iva1;
			$costo_unitario=$linea->costo_total-$iva;
			echo    "<tr><td class='detalle' width=\"50\"><form id=\"fdetail$r\" method=\"post\" name=\"fdetail$r\" action=\"$url_form/$r\"><button type=\"submit\" id=\"b$r\" style=\"display:none;\">Guardar Registro</button><div id=\"content$r\">"; echo form_hidden("id_ubicacion_local$r", "$linea->id_ubicacion_local"); echo form_hidden("cl_factura_id$r", "0"); echo form_hidden("cclientes_id$r", "0");  echo "</div></td>".

					"<td class='detalle' ><input type=\"text\" name=\"unidades$r\" size=\"4\" id=\"i$r\" value=\"$linea->cantidad\"></td>".

					"<td class='detalle' width=\"450\">"; echo form_dropdown("producto$r", $productos_list, "$linea->cproductos_id", "id='producto$r'  class='prod'"); echo "</td>".

					"<td class='detalle' align=\"right\"><span id=\"precio$r\"><input type=\"input\" name=\"precio_u$r\" id=\"precio_u$r\" value=\"$costo_unitario\" readonly='readonly' size=\"4\"></span></td>".

					"<td class='detalle' align=\"right\"><span id=\"tasa_i$r\"><input name=\"iva$r\" id=\"tasa_imp$r\" size=\"2\" value=\"$iva1\" ></span></td>".

					"<td class='detalle' align=\"right\"><input type=\"text\" name=\"subtotal$r\" size=\"10\" readonly=\"readonly\" value=\"$linea->costo_total\" class=\"subtotal\"></form></td>".
					"</tr>";

			$subtotal1+= $subtotal;
			$iva+=$iva1;
			$r+=1;
		}
		$subtotal=number_format($subtotal, 2, ".", ",");
		$iva=number_format($iva, 2, ".", ",");
		$total+=$subtotal1+$iva;
		echo "</table>";
		?>
		<table width="870" class="row_detail" id="total">
			<tr>
				<th class='detalle_pie' width="760">Subtotal</th>
				<th class='detalle_pie'><form name="t1" method="post"
						class="row_detail" id="total1">
						<input name="subtotal" type="text" value="<? echo $subtotal1; ?>"
							class="subtotal" size="10">
				
				</th>
			</tr>
			<tr>
				<th class='detalle_pie' width="760">IVA</th>
				<th class='detalle_pie'><form name="t1" method="post"
						class="row_detail" id="total1">
						<input name="iva" type="text" value="<? echo $iva; ?>"
							class="subtotal" size="10">
				
				</th>
			</tr>
			<tr>
				<th class='detalle_pie' width="760">Total</th>
				<th class='detalle_pie'><input name="total" type="text"
					value="<? echo number_format($total,2, ".",","); ?>"
					class="subtotal" size="10">
					</form></th>
			</tr>
			<tr>
				<th class='detalle_pie' width="870" colspan="2"><?php
				echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Men�</button>";
				if(substr(decbin($permisos), 0, 1)==1){
					echo '<button type="button" onclick="javascript:send_detalle()" id="boton_p">Guardar Factura e Imprimir</button><span id="msg1"></span>';
				}
				?> <span id="fin"></span>
				</th>
			</tr>
		</table>
		</div>
		<?php
} else {
	echo "<h3 align='center'>La remisi�n ha sido facturada en su totalidad</h3>";
}
?>