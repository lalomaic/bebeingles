<style>
    fieldset{
        width: 938px;
        margin: auto;
        margin-bottom: 5px;
        border-radius: 3px;
    }
    #cproveedores input{
        width: 300px;
    }
    .form_table{
        height: 220px;
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
                if($row->id!=3)
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
$fecha_ingreso=array(
    'class'=>'date_input','name'=>'fecha_ingreso',
    'id'=>'fecha_ingreso','size'=>10,
    'value'=>$fecha1);
$vigencia_descuento=array(
    'class'=>'date_input','name'=>'vigencia_descuento',
    'id'=>'fecha_ingreso','size'=>10,
    'value'=>$fecha1);
$fecha=array(
		'class'=>'date_input',
		'name'=>'fecha',
		'id'=>'fecha',
		'size'=>'10',
		'value'=>''.$fecha1
);
//Calcular la fecha de pago en base a los dias de credito del proveedor
//$dias=$proveedor->dias_credito+1;
$hoy=mktime();
//$fpago=$hoy+($dias*24*3600);
$fecha_pago1=date("d m Y", $hoy);
$fecha_pago=array(
		'class'=>'date_input',
		'name'=>'fecha_pago',
		'id'=>'fecha_pago',
		'size'=>'10',
		'value'=>''.$fecha_pago1
);
$monto_total=array(
		'name'=>'monto_total',
		'id'=>'monto_total',
		'class'=>'subtotal',
		'size'=>'10',
		'value'=>'0',
);

$descuento=array(
		'name'=>'descuento',
		'id'=>'descuento',
		'class'=>'subtotal',
		'size'=>'10',
		'value'=>'0'
);
?>
<script>
  function send1(){
    document.report.submit();
  }
  $($.date_input.initialize);
  jQuery(document).ready(
	function(){
		jQuery(jQuery.date_input.initialize);
		}
	);

</script>
<?
$this->load->view('almacen/js_alta_entrada.php');
//Titulo
echo "<h2>$title</h2>";

//Link al listado
echo "<div style='width:920px;margin:auto' align=\"right\">";
echo "<a style='margin-right:5px;' href=\"".base_url()."index.php/inicio/acceso/almacen/menu\"  title=\"Regresar al Menu Almacen\"><img src=\"".base_url()."images/menu_back.jpg\" width=\"30px\"> Menu </a>";
echo "<a href=\"".base_url()."index.php/".$ruta."/productos_c/".$funcion."/alta_producto\" target='_blank'><img src=\"".base_url()."images/new_reg.png\" width=\"30px\" title=\"Nuevo Producto\" > Producto </a>";
echo "<a href=\"".base_url()."index.php/".$ruta."/productos_c/".$funcion."/list_productos\" target='_blank'><img src=\"".base_url()."images/icons/icons_03.png\" width=\"30px\" title=\"Listado de Productos\" target='_blank'> Productos </a>";
echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_entradas\"><img src=\"".base_url()."images/factura.png\" width=\"30px\" title=\"Listado de Entradas\" > Entradas </a></div>";

//Abrir Formulario
$atrib=array('name'=>'form1', 'id'=>"form1");
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';
echo form_open($ruta.'/trans/alta_pr_factura', $atrib) . "\n";
echo form_fieldset('<b>Paso 1: Datos de la Factura</b>') . "\n";
echo "<table class='form_table' width='100%'>";
$img_row="".base_url()."images/table_row.png";

//Campos del Formulario
echo "<tr><td class='form_tag' colspan='2'><label for=\"empresas_id\">Comprador: $empresa->razon_social</label></td><td class='form_tag' colspan='2'><label for=\"espacio\">Sucursal: $espacios_fisicos_tag</label> </td><td class='form_tag'><label for=\"folio_factura\">Folio de la Factura: "; echo form_input($folio_factura); echo "</label></td>";

echo "<tr><td class='form_tag' colspan='2'><label id=\"cproveedores\" for=\"cproveedores\">Proveedor: <br/>"; echo form_dropdown('cproveedores_id', $select2, 0, "id='proveedores' class='prov'");echo "</label></td><td class='form_tag'>Dias de credito: <label id='dias_credito'>0</label></td><td class='form_tag'><label for=\"ctipo_factura_id\">Tipo de Comprobante: <br/>"; echo form_dropdown('ctipo_factura_id', $select5, 1, "id='ctipo_factura_id'");echo "</label></td><td class='form_tag'><label for=\"estatus_factura_id\">Estatus: <br/>"; echo form_dropdown('estatus_factura_id', $select3, 2, "id='estatus_factura_id' ");echo "</label></td></tr>";

echo "<tr><td class='form_tag'><label for=\"fecha\">Fecha de la Factura:</label><br/>"; echo form_input($fecha); echo "</td>".
     "<td class='form_tag'><label for=\"fecha_ingreso\">Fecha de Ingreso:</label><br/>".form_input($fecha_ingreso).
     "</td><td class='form_tag'><label for=\"fecha\">Fecha de Pago:</label><br/>".form_input($fecha_pago).
     "</td><td class='form_tag' >
     <label>Flete:</label><br/>
     <input type='text' name='flete' id='flete' size='10'/>
     </td>
     <td class='form_tag'><label for=\"monto_total\">Monto Total de la Factura:</label><br/>"; echo form_input($monto_total); echo "</td></tr>";

echo "<tr></td><td class='form_tag'><label for=\"fecha\">Vigencia de Descuento:</label><br/>".form_input($vigencia_descuento).
     "</td><td class='form_tag'>
     <label for=\"descuento\">Descuento:</label><br/>";
     echo form_input($descuento); 
     echo "</td><td class='form_tag'>"; 
     echo form_radio('tipo_descuento','porcentaje',true);
      echo "Porcentaje<br/>"; echo form_radio('tipo_descuento','pesos',false); echo "Pesos<br/>"; 
//Cerrar el Formulario

echo "<td colspan='2' class='form_tag' align='right'>";
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
	<table class="row_detail" id="header" border='1' style="width:960px;border-radius: 3px;">
		<tr>
			<th class='detalle_header'>Estatus</th>
                        <th class='detalle_header'>Codigo de Barras</th>
			<th class='detalle_header' colspan="2">Descripción</th>
			<th class='detalle_header'>Cantidad</th>
			<th class='detalle_header'>Precio U.</th>
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
                                
                                        "<td class='detalle' width=\"10\">".
                                            "<input type=\"text\" class='cod_bar' name=\"$r\" size=\"15\"  id=\"cod_bar$r\">".
                                        "</td>".

					"<td class='detalle' colspan=\"2\" width=\"400\"><input type='hidden' name='producto_id$r' id='producto_id$r' value='0' size=\"3\"><input type='hidden' name='producto_nid$r' id='producto_nid$r' value='0' size=\"3\">";
                                        echo "<input type='text' id='prod$r' class='prod' value='' style='width:395px;'></span></td>".

					"<td class='detalle' align=\"right\"><input type=\"text\" class='unidades' style='text-align:right;' name=\"cantidad$r\" size=\"4\"  id=\"cantidad$r\" value=\"0\"></td>".

					"<td class='detalle'><input type=\"text\" name=\"precio_u$r\" size=\"7\" value=\"0\" id=\"precio_u$r\" class='subtotal'></td>".
"<input type=\"hidden\" name=\"precio_iva$r\" size=\"7\" value=\"0\" id=\"precio_iva$r\"></td>".
					"<td class='detalle'><input type=\"text\" name=\"subtotal$r\" size=\"12\" readonly=\"readonly\" value=\"0\" id=\"subtotal$r\" class=\"subtotal\" disabled=\"disabled\"></td>".
					"</tr> \n";
		}
		?>
		<tr>
			<th class='detalle_pie' colspan='4' rowspan='5'></th>
			<th class='detalle_pie'></th>
			<th class='detalle_pie'>Subtotal</th>
			<th class='detalle_pie'>
			    <input name="subtotal_sin_descuento" type="text" value="0" id="subtotal_sin_descuento" class="subtotal" size="10">			    
			</th>
		</tr>
		<tr>
			<th class='detalle_pie'></th>			
			<th class='detalle_pie'>Descuento</th>
			<th class='detalle_pie'>
    			    <input id="descuento_lbl" name="descuento_lbl" type="text" value="0" class="subtotal" size="10">
			</th>
		</tr>		
		<tr>
			<th class='detalle_pie'></th>
			<th class='detalle_pie'>Subtotal 2</th>
			<th class='detalle_pie'>
			    <input name="subtotal" type="text" value="0" class="subtotal" id="subtotal" size="10">			    
			</th>
		</tr>
		<tr>
			<th class='detalle_pie'></th>
			<th class='detalle_pie'>IVA</th>
			<th class='detalle_pie'><input id="iva_total" name="iva" type="text"
				value="0" class="subtotal" size="10"></th>
		</tr>
		
		<tr>
			<th class='detalle_pie'><input name="cantidad_total" type="text"
				value="0" class="subtotal" id="cantidad_total" size="5"></th>
			<th class='detalle_pie'>Total</th>
			<th class='detalle_pie'><input name="total" id="total" type="text"
				value="0" class="subtotal" size="10"></th>
		</tr>
		<tr>
			<th class='detalle_pie' colspan="7">
			<button type="button" onclick="cancelar_entrada()">Cancelar</button>
			<?php
			if(substr(decbin($permisos), 0, 1)==1){
				echo '<button type="button" onclick="javascript:send_detalle()" id="boton_p">Paso 3. Actualizar Cambios</button><div id="msg1"></div>';
			}
			?> <span id="fin"></span><br>Al Guardar los detalles del Pedido
				verifique que se han guardado adecuadamente cada uno de ellos.</th>
		</tr>
	</table>
</div>
