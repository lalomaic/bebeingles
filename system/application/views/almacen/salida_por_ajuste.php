<style>
    fieldset{
        margin: auto;
        width: 960px;
    }
</style>
<?php
$this->load->view('almacen/js_salida_por_ajuste.php');
echo "<h2>$title</h2>";
//Abrir Formulario
$atrib = array('name' => 'form1', 'id' => "form1");
echo form_fieldset('<b>Salida por Ajuste de Inventario</b>') . "\n";
?>
<div
	id="validation_result" class="result_validator" align="center"
	width="200px"></div>
<table class='form_table'>
	<tr>
		<td class="form_tag"><?php echo form_hidden('tienda_id', $espaciosf->id, "id='tienda_id'") ?>
		</td>
	</tr>
</table>
<div id="subform_detalle">
	<table class="row_detail" id="header" border='1'>
		<tr>
			<th class='detalle_header'>Estatus</th>
			<th class='detalle_header'>Codigo</th>
			<th class='detalle_header'>Descripción</th>
			<th class='detalle_header'>Cantidad</th>
			<th class='detalle_header'>Precio U.</th>
			<th class='detalle_header'>%IVA</th>
			<th class='detalle_header'>SubTotal</th>
		</tr>
		<?php
		//Imprimir valores actuales
		for($r=0;$r<$rows;$r++){
			if($r<9)
				$class="visible";
			else if ($r>10)
				$class="invisible";

			echo "<tr id=\"row$r\"  class=\"$class\" valign='top'><td class='detalle' width=\"50\" align='center'>".($r+1).".<input type='hidden' value='0' name='id$r' id='id$r'><div id=\"content$r\"></div></td>".
					"<td class='detalle'><input class=\"cod_bar\" name=\"$r\" id=\"cod_bar$r\" size=\"12\"></td>".
					"<td class='detalle' width=\"400\"><input type='hidden' name='producto_id$r' id='producto_id$r' value='0' size=\"3\"><input type='hidden' name='producto_nid$r' id='producto_nid$r' value='0' size=\"3\">";echo "<input type='text' style='width:450px;' id='prod$r' class='prod' value='' size='80'></span></td>".

					"<td class='detalle' align=\"right\"><input type=\"text\" class='unidades' style='text-align:right;' name=\"cantidad$r\" size=\"4\"  id=\"cantidad$r\" value=\"0\"></td>".

					"<td class='detalle'><input type=\"text\" name=\"precio_u$r\" size=\"7\" value=\"0\" id=\"precio_u$r\" class='subtotal'></td>".
					"<td class='detalle' width=\"50\"><input value=\"16\" name=\"iva$r\" id=\"iva$r\" size=\"4\"></td>".
					"<td class='detalle'><input type=\"text\" name=\"subtotal$r\" size=\"12\" readonly=\"readonly\" value=\"0\" id=\"subtotal$r\" class=\"subtotal\"></td>".
					"</tr> \n";
		}
		?>
		<tr>
			<th class='detalle_pie' colspan='3' rowspan='3'></th>
			<th class='detalle_pie'><input name="cantidad_total" type="text"
				value="0" class="subtotal" id="cantidad_total" size="5"></th>
			<th class='detalle_pie' colspan='2'>Subtotal</th>
			<th class='detalle_pie'><input name="subtotal" type="text" value="0"
				class="subtotal" id="subtotal" size="10"></th>
		</tr>
		<tr>
			<th class='detalle_pie'></th>
			<th class='detalle_pie' colspan='2'>IVA</th>
			<th class='detalle_pie'><input id="iva_total" name="iva" type="text"
				value="0" class="subtotal" size="10"></th>
		</tr>
		<tr>
			<th class='detalle_pie'></th>
			<th class='detalle_pie' colspan='2'>Total</th>
			<th class='detalle_pie'><input name="total" id="total" type="text"
				value="0" class="subtotal" size="10"></th>
		</tr>
		<tr>
			<th class='detalle_pie' colspan="7"><?php
			echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Cerrar</button>";
			if(substr(decbin($permisos), 0, 1)==1){
				echo '<button type="button" onclick="javascript:send_detalle()" id="boton_p">Paso 3. Actualizar Cambios</button><div id="msg1"></div>';
			}
			?> <span id="fin"></span><br>Al Guardar los detalles del Pedido
				verifique que se han guardado adecuadamente cada uno de ellos.</th>
		</tr>
	</table>
</div>
<?=form_fieldset_close();?>
