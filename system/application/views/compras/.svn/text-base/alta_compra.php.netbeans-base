<?php
//Construir Proveedores
$select2[0]="Elija";
if($proveedores!= false){
	foreach($proveedores->all as $row){
		$y=$row->id;
		$select2[$y]=$row->razon_social;
	}
}

//Tipo de Forma de Pago
$select3=array();
if($formas_pago != false){
	foreach($formas_pago->all as $row){
		$y=$row->id;
		$select3[$y]=$row->tag;
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

// //Productos
// $productos_list[0]="";
// if($productos != false){
// foreach($productos->all as $row){
//   $y=$row->id;
//     $productos_list[$y]=$row->clave." - ".$row->descripcion ." - ".$row->presentacion." - ".$row->unidad_medida;
// }
// }
$url_form=base_url()."index.php/".$ruta."/trans/alta_compra_detalles_multiples";

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
$this->load->view('js_calc');
$this->load->view('compras/js_alta_compra');

//Titulo
echo "<br/><h2>$title</h2>";
//Abrir Formulario
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';
$atrib=array('name'=>'form1', 'id'=>"form1");
echo form_open($ruta.'/trans/alta_compra_multiple', $atrib) . "\n";
echo form_fieldset('<b>Datos Generales del Pedido</b>') . "\n";
echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";

//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"empresa\">Comprador:</label><br/>$empresa->razon_social </td><td class='form_tag'><label for=\"proveedor_id\">Proveedor: </label><br/><input type='hidden' name='cproveedores_id' id='proveedor' value='' size=\"3\"><input id='proveedores_drop' class='proveedor' value='' size='60'></td><td class='form_tag'><label for=\"cpr_estatus_pedido_id\">Estatus del Pedido: </label><br/>"; echo form_dropdown('cpr_estatus_pedido_id', $select4, "1", "disabled='disabled'"); echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"fecha_entrega\">Fecha de Entrega:</label><br/>"; echo form_input($fecha_entrega); echo "</td> <td class='form_tag'><label for=\"cpr_forma_pago_id\">Forma de Pago:</label><br/>"; echo form_dropdown('cpr_forma_pago_id', $select3, "3","id='pago'"); echo "</td></tr>";

echo "<tr><th colspan='4'><a href='javascript:marcar()'>Todos</a> -Elija las Sucursales que estan incluidas en este pedido- <a href='javascript:desmarcar()'>Ninguno</a></th></tr>";
echo "<tr><td colspan='4'><table width='100%'>";
$tr=false; $c=1; $y=1;
foreach($espacios->all as $row){
	if($c==1)
		echo "<tr>";
	echo "<td><label for=\"chk$y\">";echo form_checkbox("chk$y", $row->id, true); echo "$row->tag:</label></td>";
	if($c==3){
		echo "</tr>";
		$c=0;
	}
	$c+=1;

	$y+=1;
}

echo "</table></td></tr><tr><th colspan='4'>.</th></tr>";
//Cerrar el Formulario
echo "<tr><td colspan='3' class=\"form_buttons\"></td><td class=\"form_buttons\">";
echo "<div id=\"out_form1\">";
//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
	echo '<button type="submit" id="boton1" style="display:block;">Guardar e Iniciar Pedido de Compra</button>';
}
echo "</div>";
echo '</td></tr></table>';
echo form_fieldset_close();
echo form_close();
?>
<div id="subform_detalle">
	<table width="870" class="row_detail_pred" id="header">
		<tr>
			<th class='detalle_header'>Estatus</th>
			<th class='detalle_header'>Cantidad</th>
			<th class='detalle_header'>CLAVE - Producto - Presentacion U. Med</th>
			<th class='detalle_header'>Precio U.<br>C. IVA
			</th>
                        <th>
                            Ultima fecha <br/>Compra                        
                        </th>
			<th class='detalle_header'>IVA % <br /> <a
				href='javascript:sin_iva()' title="Sin iva">-</a> <a
				href='javascript:con_iva()' title="Con iva">+</a>
			</th>
			<th class='detalle_header'>SubTotal</th>
		</tr>
		<?php
		//Imprimir valores actuales
		$r=1;
		for($r;$r<=$rows;$r++){
			if($r<9)
				$class="visible";
			else if ($r>10)
				$class="invisible";

			echo "<tr id=\"row$r\"  class=\"$class\" valign='top'><td class='detalle' width=\"50\"><strong><a href='javascript:ocultar(\"detalle_num$r\")' title='Ocultar detalle de la numeraci�n'>-</a> <a href='javascript:mostrar(\"detalle_num$r\")' title='Mostrar detalle de la numeraci�n'>+</a></strong>$r.-<form id=\"fdetail$r\" method=\"post\" name=\"fdetail$r\" action=\"$url_form/$r\"><button type=\"submit\" id=\"b$r\" style=\"display:none;\">Guardar Registro</button><div id=\"content$r\">"; echo form_hidden("pr_pedidos_id$r", "0"); echo "</div></td>".

					"<td class='detalle' width=\"30\"><input type=\"text\" name=\"unidades$r\" size=\"6\" value='0' id=\"cantidad$r\" class=\"subtotal\"></td>".

					"<td class='detalle' width=\"400\"><input type='hidden' name='producto$r' id='producto_id$r' value='0' size=\"3\"><input id='prod$r' class='prod' value='' size='60'><br/>";

			echo "<div id='detalle_num$r'><table>";
			for($x=1;$x<=20;$x=$x+2){
				echo "<tr><td><label id='l{$r}_{$x}'> Etiqueta $x</label></td><td><input type='hidden' name='cproducto_numero_id{$r}_{$x}' id='cproducto_numero_id{$r}_{$x}' value='0'><input type='text' name='numeracion{$r}_{$x}' id='numeracion{$r}_{$x}' value='0' size='3' class='cant$r'></td>".
						"<td><label id='l{$r}_".($x+1)."'> Etiqueta". ($x+1)."</label></td>".
						"<td><input type='hidden' name='cproducto_numero_id{$r}_".($x+1)."' id='cproducto_numero_id{$r}_".($x+1)."' value='0'><input type='text' name='numeracion{$r}_".($x+1)."' id='numeracion{$r}_".($x+1)."' value='0' size='3' class='cant$r'></td></tr>";
			}


			echo "</table></div></td>".

					"<td class='detalle' width=\"150\"><span id=\"precio$r\"><input class=\"subtotal\" type=\"text\" name=\"precio_u$r\" size=\"10\" onchange=\"javascript:calc($r)\" value=\"0\" id=\"precio_u$r\"></span></td>".
                                        "<td class='detalle' width=\"50\"><span id=\"fech_compra_i$r\"><input value=\"00-00-00\" name=\"fech_compr$r\" id=\"fecha_compr$r\" size=\"8\" ></span></td>".
					"<td class='detalle' width=\"50\"><span id=\"tasa_i$r\"><input class=\"subtotal_iva\" value=\"16\" name=\"iva$r\" id=\"tasa_imp$r\" size=\"2\" ></span></td>".

					"<td class='detalle' width=\"150\"><input class=\"subtotal\" type=\"text\" name=\"subtotal$r\" size=\"10\" readonly=\"readonly\" id=\"subtotal$r\" value=''></form></td>".
					"</tr> \n";
		}
		echo "</table>";
		?>
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
				echo "<button type='button' onclick=\"javascript:verificar();\">Cancelar pedido</button>";
				if(substr(decbin($permisos), 0, 1)==1){
					echo '<button type="button" onclick="javascript:send_detalle()">Paso No 2. Guardar Detalle</button>';
				}
				?> <span id="fin"></span><br>Al Guardar los detalles del Pedido
					verifique que se han guardado adecuadamente cada uno de ellos.</th>
			</tr>
		</table>
		</div>
		<?php
		//Link al listado
		echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_compras\"><img src=\"".base_url()."images/consumo.png\" width=\"50px\" title=\"Listado de Pedidos de Compra\"></a><a href=\"".base_url()."index.php/".$ruta."/proveedores_c/".$funcion."/alta_proveedor\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Nuevo Usuario\"></a></div>";
?>