<style>
    fieldset{
        width: 920px;
        margin: auto;
        margin-bottom: 5px;
    }
</style>

<?php
//Construir Proveedores
$select2[0]="Elija";
if($proveedor!= false){
	foreach($proveedor->all as $row){
		$y=$row->id;
		$select2[$y]=$row->razon_social;
	}
}
//Tipo de Forma de Pago
$select3=array();
if($estatus_facturas!= false){
	foreach($estatus_facturas->all as $row){
		$y=$row->id;
		$select3[$y]=$row->tag;
	}
}
$select3[0]="Elija";


//Tipo de Factura
$select5=array();
if($tipos_facturas!= false){
	foreach($tipos_facturas->all as $row){
		$y=$row->id;
		$select5[$y]=$row->tag;
	}
}
$url_form=base_url()."index.php/".$ruta."/trans/alta_entrada";
//Inputs
$folio_factura=array(
		'name'=>'folio_factura',
		'id'=>'folio_factura',
		'size'=>'10',
		'value'=>'',
);
$fecha1=date("d m Y");
$fecha=array(
		'class'=>'date_input',
		'name'=>'fecha',
		'id'=>'fecha',
		'size'=>'10',
		'value'=>''.$fecha1,
		'readonly'=>'readonly'
);
//Calcular la fecha de pago en base a los dias de credito del proveedor
$dias=$proveedor->dias_credito+1;
$hoy=mktime();
$fpago=$hoy+($dias*24*3600);
$fecha_pago1=date("d m Y", $fpago);
$fecha_pago=array(
		'class'=>'date_input',
		'name'=>'fecha_pago',
		'id'=>'fecha_pago',
		'size'=>'10',
		'value'=>''.$fecha_pago1,
		'readonly'=>'readonly'
);
$monto_total=array(
		'name'=>'monto_total',
		'id'=>'monto_total',
		'class'=>'subtotal',
		'size'=>'14',
		'value'=>'0',
);

$descuento=array(
		'name'=>'descuento',
		'id'=>'descuento',
		'class'=>'subtotal',
		'size'=>'10',
		'value'=>'0'
);

$ctipo=array(
         'name'=>'ctipo',
          'id'=>'ctipo',
           'value'=>'Nota de Remision',
           'disabled'=>'disabled',
          
);


$this->load->view('almacen/js_alta_inventario');
//Titulo
echo "<h2>$title</h2>";

//Link al listado
echo "<div style='width:920px;margin:auto' align=\"right\"><a href=\"".base_url()."index.php/".$ruta."/productos_c/".$funcion."/alta_producto\" target='_blank'><img src=\"".base_url()."images/new_reg.png\" width=\"30px\" title=\"Nuevo Producto\" > +Producto </a>";
echo "<a href=\"".base_url()."index.php/".$ruta."/productos_c/".$funcion."/list_productos\" target='_blank'><img src=\"".base_url()."images/icons/icons_03.png\" width=\"30px\" title=\"Listado de Productos\" target='_blank'> Productos </a>";
echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_entradas\" target='_blank'><img src=\"".base_url()."images/consumo.png\" width=\"30px\" title=\"Listado de Pedidos de Compra\" > Pedidos </a></div>";

//Abrir Formulario
$atrib=array('name'=>'form1', 'id'=>"form1");
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';
echo form_open($ruta.'/trans/alta_inventario', $atrib) . "\n";
echo form_fieldset('<b>Paso 1: Datos de la Factura</b>') . "\n";
echo "<table class='form_table' width='600px'>";
$img_row="".base_url()."images/table_row.png";

//Campos del Formulario
echo "<tr><td class='form_tag' colspan='2'><label for=\"empresas_id\">Comprador: $empresa->razon_social</label></td><td class='form_tag' colspan='2'><label for=\"espacio\">Sucursal: $espacios_fisicos_tag</label> </td>";

echo "<tr><td class='form_tag' colspan='3'><label for=\"cproveedores\">Proveedor: <br/>"; 
echo form_dropdown('cproveedores_id', $select2, 1,"id='proveedores' class='prov' disabled='disabled'");

echo "<tr><td class='form_tag'><label for=\"fecha\">Fecha de la Captura:</label><br/>"; 
echo form_input($fecha); 

echo "</td><td class='form_tag'><label for=\"ctipo_factura_id\">Tipo de Comprobante: <br/>"; 
echo form_input($ctipo);

//Cerrar el Formulario
echo "<tr><td colspan='4' class=\"form_buttons\" align='right'>";
echo form_hidden('espacios_fisicos_id', $espacios_fisicos_id);
echo "<div id=\"out_form1\"></div>";
//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
	echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\" style=\"display:inline;\">Cerrar sin guardar</button><button type=\"submit\"  id=\"boton1\" style=\"display:inline;\">Paso 2. Guardar y alta de detalles</button>";
}
echo '</td></tr></table>';
echo form_fieldset_close();
echo form_close();
?>
<div id="subform_detalle">
	<table class="row_detail" id="header" border='1'>
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
		for($r=0;$r<$rows;$r++){
			if($r<15)
				$class="visible";
			else if ($r>16)
				$class="invisible";

			echo "<tr id=\"row$r\"  class=\"$class\" valign='top'><td class='detalle' width=\"50\" align='center'>".($r+1).".<input type='hidden' value='0' name='id$r' id='id$r'><div id=\"content$r\"></div></td>".
                                
                                        "<td class='detalle' width=\"10\">".
                                            "<input type=\"text\" class='cod_bar' name=\"$r\" size=\"15\"  id=\"cod_bar$r\">".
                                        "</td>".

					"<td class='detalle' width=\"400\"><input type='hidden' name='producto_id$r' id='producto_id$r' value='0' size=\"3\"><input type='hidden' name='producto_nid$r' id='producto_nid$r' value='0' size=\"3\">";echo "<input type='text' id='prod$r' class='prod' value='' size='80'></span></td>".

					"<td class='detalle' align=\"right\"><input type=\"text\" class='unidades' style='text-align:right;' name=\"cantidad$r\" size=\"4\"  id=\"cantidad$r\" value=\"0\"></td>".

					"<td class='detalle'><input type=\"text\" name=\"precio_u$r\" size=\"7\" value=\"0\" id=\"precio_u$r\" class='subtotal'></td>".
					"<td class='detalle' width=\"50\"><input value=\"16\" name=\"iva$r\" id=\"iva$r\" size=\"4\" disabled=\"disabled\"></td>".
					"<td class='detalle'><input type=\"text\" name=\"subtotal$r\" size=\"12\" readonly=\"readonly\" value=\"0\" id=\"subtotal$r\" class=\"subtotal\" disabled=\"disabled\"></td>".
					"</tr> \n";
		}
		?>
		<tr>
			<th class='detalle_pie' colspan='2' rowspan='3'></th>
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
			echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Cancelar</button>";
			if(substr(decbin($permisos), 0, 1)==1){
				echo '<button type="button" onclick="javascript:send_detalle()" id="boton_p">Paso 3. Actualizar Cambios</button><div id="msg1"></div>';
			}
			?> <span id="fin"></span><br>Al Guardar los detalles del Pedido
				verifique que se han guardado adecuadamente cada uno de ellos.</th>
		</tr>
	</table>
</div>
