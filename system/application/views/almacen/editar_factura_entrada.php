<style>
    fieldset{
        width: 938px;
        margin: auto;
        margin-bottom: 5px;
        border-radius: 3px;
    }
    .form_table{
        height: 200px;
    }
</style>
<script>
    $(document).ready(function() {
    $($.date_input.initialize);
    $('#proveedores').select_autocomplete();
});

</script>
<?php
foreach($pr_factura->all as $reng ){
	//Construir Proveedores
	$select2[0]="Elija";
	if($proveedores!= false){
		foreach($proveedores->all as $row){
			$y=$row->id;
			$select2[$y]=$row->razon_social;
		}
	}

	//Tipo de Factura
	$select5=array();
	if($tipos_facturas!= false){
		foreach($tipos_facturas->all as $row){
			$y=$row->id;
			$select5[$y]=$row->tag;
		}
	}
	$url_form=base_url()."index.php/".$ruta."/trans/editar_pr_factura";

	//Inputs
	$folio_factura=array(
			'name'=>'folio_factura',
			'id'=>'folio_factura',
			'size'=>'8',
			'class'=>'subtotal',
			'value'=>''.$reng->folio_factura,
	);
	$fecha1=$reng->fecha;
	$fecha=explode("-", $fecha1);
	$reng->fecha=$fecha[2]." ".$fecha[1]." ".$fecha[0];
	$fecha=array(
			'class'=>'date_input',
			'name'=>'fecha',
			'id'=>'fecha',
			'size'=>'10',
			'readonly'=>'readonly',
			'value'=>''.$reng->fecha
	);

	$fecha1=$reng->fecha_pago;
	$fecha_b=explode("-", $fecha1);
	$reng->fecha_pago=$fecha_b[2]." ".$fecha_b[1]." ".$fecha_b[0];
	$fecha_pago=array(
			'class'=>'date_input',
			'name'=>'fecha_pago',
			'id'=>'fecha_pago',
			'size'=>'10',
			'readonly'=>'readonly',
			'value'=>''.$reng->fecha_pago
	);
        $fecha1=$reng->vigencia_descuento;
	$fecha_b=explode("-", $fecha1);
	$reng->vigencia_descuento=$fecha_b[2]." ".$fecha_b[1]." ".$fecha_b[0];
	$vigencia_descuento=array(
			'class'=>'date_input',
			'name'=>'vigencia_descuento',
			'id'=>'vigencia_descuento',
			'size'=>'10',
			'readonly'=>'readonly',
			'value'=>''.$reng->vigencia_descuento
	);
        $fecha1=$reng->fecha_ingreso;
	$fecha_b=explode("-", $fecha1);
	$reng->fecha_ingreso=$fecha_b[2]." ".$fecha_b[1]." ".$fecha_b[0];
        $fecha_ingreso=array(
            'class'=>'date_input','name'=>'fecha_ingreso',
            'id'=>'fecha_ingreso','size'=>10,
            'value'=>$reng->fecha_ingreso,'readonly'=>'readonly');



	$numero_referencia=array(
			'name'=>'numero_referencia',
			'id'=>'numero_referencia',
			'size'=>'10',
			'value'=>''.$reng->numero_referencia
	);

	$monto_total=array(
			'name'=>'monto_total',
			'id'=>'monto_total',
			'class'=>'subtotal',
			'size'=>'14',
			'readonly'=>'readonly',
			'value'=>''.$reng->monto_total,
	);

	$porcentaje_descuento=array(
			'name'=>'porcentaje_descuento',
			'id'=>'descuento',
			'class'=>'subtotal',
			'size'=>'10',
			'value'=>''.$reng->porcentaje_descuento
	);
	//Titulo
	$this->load->view('almacen/js_editar_entrada');
	echo "<h2>$title</h2>";

        //Links
        echo "<div style='width:920px;margin:auto' align=\"right\">";
        echo "<a style='margin-right:5px;' href=\"".base_url()."index.php/inicio/acceso/almacen/menu\"  title=\"Regresar al Menu Almacen\"><img src=\"".base_url()."images/menu_back.jpg\" width=\"30px\"> Menu </a>";
        echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_entrada\"><img src=\"".base_url()."images/new_reg.png\" width=\"30px\" title=\"Agregar\" > Agregar </a>";
        echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_entradas\"><img src=\"".base_url()."images/factura.png\" width=\"30px\" title=\"Listado de Entradas\" > Entradas </a></div>";

	//Abrir Formulario
	$atrib=array('name'=>'form1', 'id'=>"form1");
	echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';
	echo form_open($ruta.'/trans/editar_pr_factura', $atrib) . "\n";
	echo form_fieldset('<b>Corregir Factura de Entrada</b>') . "\n";
	echo "<table width=\"90%\" class='form_table'>";
	$img_row="".base_url()."images/table_row.png";

	//Campos del Formulario

	echo "<tr><td class='form_tag' ><label for=\"empresas_id\">Comprador: $empresa->razon_social</label></td>".
                "<td class='form_tag'><label for=\"espacio\">Sucursal: $espacio->tag </label></td>".
                "<td class='form_tag'><label for=\"cproveedores\">Proveedor: $proveedores->razon_social</label></td>".
                "<td class='form_tag'><label for=\"folio_factura\">Folio de la Factura:</label>".
                "<br/>".form_input($folio_factura)."</td>".
              "</tr>";

	echo "<tr>".
                "<td class='form_tag'><label for=\"fecha\">Fecha de la Factura:</label><br/>"; echo form_input($fecha); echo "</td>".
                "<td class='form_tag'><label for=\"fecha\">Fecha de Pago:</label><br/>"; echo form_input($fecha_pago); echo "</td>".
                "<td class='form_tag'><label for=\"fecha_ingreso\">Fecha de Ingreso:</label><br/>".form_input($fecha_ingreso)."</td>".             
                "<td class='form_tag'><label for=\"monto_total\">Monto Total de la Factura:</label><br/>"; echo form_input($monto_total)."</td>".                
             "</tr>";

	echo "<tr>".
                "<td class='form_tag'><label for=\"descuento\">Descuento:</label><br/>"; echo form_input($porcentaje_descuento)."</td>".
                "<td class='form_tag'>"; echo form_radio('tipo_descuento','porcentaje',true); echo "Porcentaje<br/>"; echo form_radio('tipo_descuento','pesos',false); echo "Pesos<br/>"."</td>".
                "<td class='form_tag'><label for=\"fecha\">Vigencia de Descuento:</label><br/>".form_input($vigencia_descuento)."</td>".
                "<td class='form_tag'><label for=\"ctipo_factura_id\">Tipo de Comprobante: <br/>".form_dropdown('ctipo_factura_id', $select5, "$reng->ctipo_factura_id", "id='ctipo_factura_id' ")."</label></td>".
             "</tr>";

	//Cerrar el Formulario
		echo "<tr></td><td class=\"form_buttons\" align='right'>";
	echo form_hidden('id', "$reng->id");
	echo form_hidden('pr_pedido_id', "$reng->pr_pedido_id");
	echo "<div id=\"out_form1\">";
	echo "</div>";
	echo '</td></tr></table>';
	echo form_fieldset_close();
	echo form_close();

}
?>

<div id="subform_detalle" >
	<table class="row_detail" id="header" border='1' width="70%">
		<tr>
			<th class='detalle_header'>Estatus</th>
                        <th class='detalle_header'>Codigo de Barras</th>
			<th class='detalle_header'>Descripción</th>
			<th class='detalle_header'>Cantidad</th>
			<th class='detalle_header'>Precio U.</th>
			<th class='detalle_header'>%IVA</th>
			<th class='detalle_header'>SubTotal</th>
		</tr>
		<?php
                
		//Imprimir valores actuales
                foreach($entra->all as $entra ){
                  
                  $cantidad=  number_format($entra->cantidad,0,'.','');
                  $precio=  number_format($entra->costo_unitario,0,'.','');
                  $total=  number_format($entra->costo_total,0,'.','');
                 echo "<tr valign='top'><td class='detalle' width=\"50\" align='center'><a href=\"javascript:borrar($entra->cproductos_id,$entra->cproducto_numero_id)\">Borrar</a><input type='hidden' value='0' name='id' id='id'><div id=\"content$entra->cproductos_id$entra->cproducto_numero_id\"><img src='".base_url()."images/ok.png' width='20px'/><a href=\"javascript:editar_inventario($entra->cproductos_id,$entra->cproducto_numero_id)\">Editar</a></div></td>".
                                
                                        "<td class='detalle' width=\"10\">".
                                            "<input type=\"text\" class='cod_bar' size=\"15\" value=\"$entra->cod\"  id=\"cod_bar\" disabled=\"disabled\">".
                                        "</td>".

					"<td class='detalle' width=\"400\"><input type='hidden' name='producto_m$entra->cproducto_numero_id' id='producto_m$entra->cproducto_numero_id' value='$entra->cproducto_numero_id' size=\"3\"><input type='hidden' name='id_producto$entra->cproductos_id' id='id_producto$entra->cproductos_id' value='$entra->cproductos_id' size=\"3\">";echo "<input type='text' id='prod' class='prod$entra->cproductos_id' value='$entra->producto_nombre -# $entra->numero_mm' size='80' disabled='disabled'></span></td>".
					"<td class='detalle' align=\"right\"><input type=\"text\" class='unidades' style='text-align:right;' name=\"cantidad$entra->cproducto_numero_id\" size=\"4\"  id=\"cantidad$entra->cproducto_numero_id\" value=\"$cantidad\"></td>".

					"<td class='detalle'><input type=\"text\" name=\"precio_uni\" size=\"7\" value=\"$precio\" id=\"precio_uni$entra->cproducto_numero_id\" class='subtotal' linea=\"$entra->cproducto_numero_id\"></td>".
					"<td class='detalle' width=\"30\"><input value=\"16\" name=\"iva_e\" id=\"iva_e\" size=\"4\" disabled=\"disabled\"></td>".
					"<td class='detalle'><input type=\"text\" name=\"subtotal$entra->cproducto_numero_id\" size=\"12\" readonly=\"readonly\" value=\"$total\" id=\"subtotal$entra->cproducto_numero_id\" class=\"subtotal$entra->cproducto_numero_id\" disabled=\"disabled\"></td>".
					"</tr> \n";   
                     }
		for($r=0;$r<$rows;$r++){
			if($r<15)
				$class="visible";
			else if ($r>16)
				$class="invisible";

			echo "<tr id=\"row$r\"  class=\"$class\" valign='top'><td class='detalle' width=\"50\" align='center'><input type='hidden' value='0' name='id$r' id='id$r'><div id=\"content$r\"></div></td>".
                                
                                        "<td class='detalle' width=\"10\">".
                                            "<input type=\"text\" class='cod_bar' name=\"$r\" size=\"15\"  id=\"cod_bar$r\">".
                                        "</td>".

					"<td class='detalle' width=\"400\"><input type='hidden' name='producto_id$r' id='producto_id$r' value='0' size=\"3\"><input type='hidden' name='producto_nid$r' id='producto_nid$r' value='0' size=\"3\">";echo "<input type='text' id='prod$r' class='prod' value='' size='80'></span></td>".

					"<td class='detalle' align=\"right\"><input type=\"text\" class='unidades' style='text-align:right;' name=\"cantidad$r\" size=\"4\"  id=\"cantidad$r\" value=\"0\"></td>".

					"<td class='detalle'><input type=\"text\" name=\"precio_u$r\" size=\"7\" value=\"0\" id=\"precio_u$r\" class='subtotal'></td>".
					"<td class='detalle' width=\"30\"><input value=\"16\" name=\"iva$r\" id=\"iva$r\" size=\"4\" disabled=\"disabled\"></td>".
					"<td class='detalle'><input type=\"text\" name=\"subtotal$r\" size=\"12\" readonly=\"readonly\" value=\"0\" id=\"subtotal$r\" class=\"subtotal\" disabled=\"disabled\"></td>".
					"</tr> \n";
		}
		?>
			<tr>
				<th class='detalle_pie' colspan='2'></th>
			<th class='detalle_pie'></th>
			<th class='detalle_pie' colspan='3'>Descuento</th>
			<th class='detalle_pie'><input id="descuento_lbl" name="descuento_lbl" type="text"
				value="0" class="subtotal" size="10"></th>
		</tr>	
		<tr>
			<th class='detalle_pie' colspan='2' rowspan='3'></th>
			<th class='detalle_pie'><input name="cantidad_total" type="text"
				value="0" class="subtotal" id="cantidad_total" size="5"></th>
			<th class='detalle_pie' colspan='3'>Subtotal</th>
			<th class='detalle_pie'><input name="subtotal" type="text" value="0"
				class="subtotal" id="subtotal" size="10"></th>
		</tr>
		<tr>
			<th class='detalle_pie'></th>
			<th class='detalle_pie' colspan='3'>IVA</th>
			<th class='detalle_pie'><input id="iva_total" name="iva" type="text"
				value="0" class="subtotal" size="10"></th>
		</tr>
		<tr>
			<th class='detalle_pie'></th>
			<th class='detalle_pie' colspan='3'>Total</th>
			<th class='detalle_pie'><input name="total" id="total" type="text"
				value="0" class="subtotal" size="10"></th>
		</tr>
                
                		<tr>
			<th class='detalle_pie' colspan="7"><?php
			echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Cancelar</button>";
			if(substr(decbin($permisos), 0, 1)==1){
				echo '<button type="button" onclick="javascript:send_detalle()" id="boton_p">Paso 3. Actualizar Cambios</button><div id="msg1"></div>';
			}
			?> <span id="fin"></span><br>Al Guardar los detalles del Pedido
				verifique que se han guardado adecuadamente cada uno de ellos.</th>
		</tr>
        </table></div>
                




