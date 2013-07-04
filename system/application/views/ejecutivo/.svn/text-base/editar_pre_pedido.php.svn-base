<?php
$monto_total1;
foreach($pr_pedido->all as $reng ){
	$monto_total1=$reng->monto_total;
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

	//Tipo de Forma de Pago
	$select4=array();
	if($estatus!= false){
		foreach($estatus->all as $row){
			$y=$row->id;
			$select4[$y]=$row->tag;
		}
	}

	$jsp="onchange='javascript:sinc_prod(this.value, 'claveX');'";
	$url_form=base_url()."index.php/".$ruta."/trans/edicion_compra_detalles";

	//Inputs
	$monto_total=array(
			'name'=>'monto_total',
			'size'=>'40',
			'value'=>''.$reng->monto_total,
	);
	//Dates input
	if(substr($reng->fecha_pago,0.10)!='0000-00-00' and $reng->fecha_pago!=""){
		$fecha_pago1=explode("-", substr($reng->fecha_pago,0.10));
		$fecha_pago_db="".$fecha_pago1[2]." ".$fecha_pago1[1]." ".$fecha_pago1[0];
	} else
		$fecha_entrega_db="";
	if(substr($reng->fecha_entrega,0.10)!='0000-00-00' and $reng->fecha_entrega!=""){
		$fecha_entrega1=explode("-", substr($reng->fecha_entrega,0,10));
		$fecha_entrega_db="".$fecha_entrega1[2]." ".$fecha_entrega1[1]." ".$fecha_entrega1[0];
	} else
		$fecha_pago_db="";

	$fecha_entrega=array(
			'class'=>'date_input',
			'name'=>'fecha_entrega',
			'size'=>'10',
			'readonly'=>'readonly',
			'value'=>''.$fecha_entrega_db,
	);

	$fecha_pago=array(
			'class'=>'date_input',
			'name'=>'fecha_pago',
			'size'=>'10',
			'readonly'=>'readonly',
			'value'=>''.$fecha_entrega_db,
	);

	$porcentaje_descuento=array(
			'class'=>'subtotal',
			'name'=>'porcentaje_descuento',
			'size'=>'4',
			'value'=>''.$reng->porcentaje_descuento,
	);
	$this->load->view('compras/js_editar_pedido_compra');
	//  $this->load->view('js_calc');

	//Titulo
	echo "<h2>$title</h2>";
	//Abrir Formulario
	$atrib=array('name'=>'form1', 'id'=>"form1");
	echo form_open($ruta.'/trans/alta_compra', $atrib) . "\n";
	echo form_fieldset('<b>Editar los Datos Generales del Pedido</b>') . "\n";
	echo "<table width=\"900px\" class='form_table'>";
	$img_row="".base_url()."images/table_row.png";

	//Campos del Formulario
	echo "<tr><td class='form_tag' colspan='3'><label for=\"empresa\">Comprador:</label> $empresa->razon_social </td></tr>";
	echo "<tr><td class='form_tag' colspan='3'><label for=\"empresa\">Sucursal:</label> $reng->espacio";  echo form_hidden('espacio_fisico_id', $reng->espacio_fisico_id); echo "</td></tr>";

	echo "<tr><td class='form_tag'><label for=\"cproveedores_id\">Proveedor: </label><br/>"; echo form_dropdown('cproveedores_id', $select2, "$reng->cproveedores_id", "id='proveedores'");echo "</td><td class='form_tag'><label for=\"porcentaje_descuento\">Descuento en %:</label><br/>"; echo form_input($porcentaje_descuento); echo "</td><td class='form_tag'><label for=\"cpr_estatus_pedido_id\">Estatus del Pedido: </label><br/>"; echo form_dropdown('cpr_estatus_pedido_id', $select4, "$reng->cpr_estatus_pedido_id", "disabled='disabled'"); echo "</td></tr>";

	echo "<tr><td class='form_tag'><label for=\"cmarca_id\">Marca:</label><br/>$reng->marca<input type='hidden' value='$reng->cmarca_id' name='cmarca_id' id='cmarca_id'></td><td class='form_tag'><label for=\"fecha_entrega\">Fecha de Entrega:</label><br/>"; echo form_input($fecha_entrega); echo "</td><td class='form_tag'><label for=\"fecha_Pago\">Fecha de Pago:</label><br/>"; echo form_input($fecha_pago); echo "</td><td class='form_tag'><label for=\"cpr_forma_pago_id\">Forma de Pago:</label><br/>"; echo form_dropdown('cpr_forma_pago_id', $select3, "$reng->cpr_forma_pago_id"); echo "</td></tr>";

	//Cerrar el Formulario
	echo "<tr><td colspan='2' class=\"form_buttons\"></td><td class=\"form_buttons\">";
	echo "<div id=\"out_form1\">";
	echo form_hidden('id', "$reng->id");
	//Permisos de Escritura byte 1
	if(substr(decbin($permisos), 0, 1)==1){
		echo '<button type="submit" id="boton1" style="display:none;">Actualizar Registro</button>';
	}
	echo "</div>";
	echo '</td></tr></table>';
	echo form_fieldset_close();
	echo form_close();

}
?>
<div id="subform_detalle">
	<table width="900px" class="row_detail" id="header">
		<tr>
			<th class='detalle_header'>Estatus</th>
			<th class='detalle_header'>Cantidad</th>
			<th class='detalle_header'>CLAVE - Producto - Presentacion U. Med</th>
			<th class='detalle_header'>Precio U.<br />C. IVA
			</th>
			<th class='detalle_header'>IVA <a href='javascript:sin_iva()'
				title="Sin iva">-</a> <a href='javascript:con_iva()' title="Con iva">+</a>
			</th>
			<th class='detalle_header'>SubTotal</th>
		</tr>
		<?php
		//Imprimir valores actuales
		$r=0;
		if($pr_detalle!=false){
			$gtotal=0;
			$giva=0;
			$pares_t=0;
			foreach($pr_detalle->all as $linea){
				$pares_t+=$linea->cantidad;
				$gtotal+=$linea->cantidad*round($linea->costo_unitario,2);
				$giva+=($linea->tasa_impuesto*$linea->costo_total)/(100+$linea->tasa_impuesto);
				echo  "<tr class=\"visible\" id='row$r'><td class='detalle' width=\"50\"><form id=\"fdetail$r\" method=\"post\" name=\"fdetail$r\" action=\"$url_form/$r\"><button type=\"submit\" id=\"b$r\" style=\"display:none;\">Guardar Registro</button><div id=\"content$r\">";  echo form_hidden("id$r", "$linea->pr_detalle_id");  echo form_hidden("pr_pedidos_id$r", "$linea->pr_pedidos_id"); echo "<a href=\"javascript:borrar_detalle($linea->pr_detalle_id,$r)\"><img src=\"".base_url()."images/trash1.png\" width=\"20px\" title=\"Borrar Detalle\"/></a>"; echo "</div></td>".

						"<td class='detalle' width=\"70\"><input type=\"text\" name=\"unidades$r\" size=\"4\" id=\"cantidad$r\" class=\"subtotal\" value=\"".round($linea->cantidad,0)."\"></td>".

						"<td class='detalle' width=\"400\"><input type='hidden' name='producto$r' id='producto$r' value='0' size=\"3\">$linea->producto # ".($linea->numero_mm/10)."</td>".

						"<td class='detalle' width=\"50\"><input type=\"text\" name=\"precio_u$r\" size=\"4\" value=\"".round($linea->costo_unitario,2)."\" id=\"precio_u$r\" class=\"subtotal\"></td>".

						"<td class='detalle' width=\"50\"><span id=\"tasa_i$r\"><input class='subtotal_iva' value=\"$linea->tasa_impuesto\" name=\"iva$r\" id=\"tasa_imp$r\" size=\"4\"></span></td>".

						"<td class='detalle' width=\"50\"><input type=\"text\" name=\"subtotal$r\" size=\"4\" readonly=\"readonly\" value=\"".($linea->cantidad*round($linea->costo_unitario,2))."\" id=\"subtotal$r\" class=\"subtotal\"></form></td>".

						"</tr>";
				$r+=1;
			}
		}
		for($x=$r;$x<$rows;$x++){
			echo  "<tr class=\"invisible\" id='row$x'><td class='detalle' width=\"50\"><form id=\"fdetail$r\" method=\"post\" name=\"fdetail$r\" action=\"$url_form/$x\"><button type=\"submit\" id=\"b$x\" style=\"display:none;\">Guardar Registro</button><div id=\"content$x\">";  echo form_hidden("id$x", "0");  echo form_hidden("pr_pedidos_id$x", "$reng->id"); echo "<a href=\"javascript:borrar_detalle($linea->id,$x)\"><img src=\"".base_url()."images/trash1.png\" width=\"20px\" title=\"Borrar Detalle\"/></a>"; echo "</div></td>".

					"<td class='detalle' width=\"70\"><input type=\"text\" name=\"unidades$x\" size=\"4\" id=\"cantidad$x\" value=\"0\" class='subtotal'></td>".

					//"<td class='detalle' width=\"400\">"; echo form_dropdown("producto$x", $productos_list, "", "class='prod' id='producto$x'"); echo "</td>".

			"<td class='detalle' width=\"400\"><input type='hidden' name='producto$r' id='producto_id$r' value='0' size=\"3\"><input type='hidden' name='producto_numeracion$r' id='producto_numeracion$r' value='0'><input id='producto_drop$r' name='producto_drop$r' value='' size='80'></td>".

			"<td class='detalle' width=\"150\"><input type=\"text\" name=\"precio_u$x\" size=\"4\" value=\"0\" id=\"precio_u$x\" class=\"subtotal\"></td>".

			"<td class='detalle' width=\"50\"><span id=\"tasa_i$x\"><input value=\"\" name=\"iva$x\" id=\"tasa_imp$x\" size=\"3\"></span></td>".

			"<td class='detalle' width=\"150\" align=\"right\"><input type=\"text\" name=\"subtotal$x\" size=\"4\" readonly=\"readonly\" value=\"0\" id=\"subtotal$x\" class=\"subtotal\"></form></td>".

			"</tr>";
			$r+=1;
		}
		echo "<tr><th>Pares</th><td><input type='input' value='$pares_t' name='pares_totales' id='pares_totales' size='4' class='subtotal'></td><td></td><td></td><td></td></tr>";
		echo "</table>";
		$cte=$r;
		$class_prev="";
		$subtotal=number_format($gtotal-$giva,2,".",",");
		?>
		<table width="900px" class="row_detail" id="total">
			<tr>
				<th class='detalle_pie' width="900">Subtotal</th>
				<th class='detalle_pie'><form name="t1" method="post"
						class="row_detail" id="total1">
						<input name="subtotal" type="text" value="<? echo $subtotal; ?>"
							class="subtotal" id="subtotal1" size="10">
				
				</th>
			</tr>
			<tr>
				<th class='detalle_pie'>IVA</th>
				<th class='detalle_pie'><input id="iva_total" name="iva" type="text"
					value="<? echo number_format($giva,2,".",","); ?>" class="subtotal"
					size="10"></th>
			</tr>
			<tr>
				<th class='detalle_pie'>Total</th>
				<th class='detalle_pie'><input name="total" id="total" type="text"
					value="<? echo number_format($gtotal,2,".",","); ?>"
					class="subtotal" size="10">
					</form></th>
			</tr>
			<tr>
				<th class='detalle_pie' width="900px" colspan="2"><?php
				echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Menú</button>";
				if(substr(decbin($permisos), 0, 1)==1){
					echo '<button type="button" onclick="javascript:send_detalle()" id="boton_p">Guardar Cambios</button><span id="msg1"></span>';
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
		<script>
  $(document).ready(function() {
    mostrar_subform();
});
</script>