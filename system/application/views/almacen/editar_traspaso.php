<style>
    fieldset{
        width: 940px;
        margin: auto;
        margin-bottom: 10px;
        border-radius: 3px;
    }
</style>
<?php
//$url_form=base_url()."index.php/".$ruta."/trans/alta_salida_traspasos";
$this->load->view('almacen/js_editar_tras.php');
echo "<h2>$title</h2>";
echo "<div style='width:920px;margin:auto' align=\"right\">";
echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_traspasos\" title=\"Listado de Traspasos\"><img src=\"".base_url()."images/icons/icons_03.png\" width=\"25px\">Listado de Traspasos </a><a href=\"".base_url()."index.php/almacen/almacen_reportes/formulario/rep_salidas\" title=\"Reporte Salidas\"><img src=\"".base_url()."images/adobereader.png\" width=\"25px\">Reporte Salidas </a></div>";


$atrib=array('name'=>'form1', 'id'=>"form1");
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';
//echo form_open($ruta.'/trans/alta_traspaso', $atrib) . "\n";
echo form_fieldset('<b>Paso 1: Datos del Traspaso</b>') . "\n";
echo "<table class='form_table' width='900px'>";
$img_row="".base_url()."images/table_row.png";

//Campos del Formulario
echo "<tr><td class='form_tag' ><label for=\"empresas_id\">Razón Social:</td><td class='form_tag'>$empresa->razon_social</label></td></tr>";
echo "<tr><td class='form_tag' ><label for=\"espacio\">Envia:</label></td><td class='form_tag'>$espacios_fisicos_tag</td>";
echo "<input type='hidden' name='espacio_fisico' id='espacio_fisico' value='$espacios_fisicos_id' />";
echo "<tr><td class='form_tag' ><label for=\"espacio_fisico_recibe_id\">Tienda que recibe: </td><td class='form_tag'>"; echo form_dropdown('espacio_fisico_recibe_id', $espacios, 0, "id='espacio_fisico_recibe_id' disabled='disabled'");echo "</label></td></tr>";
echo "<input type='hidden' name='id' id='id' value='$id_tras' size=\"3\">";
echo "<input type='hidden' name='espacio' id='espacio' value='$espacios' size=\"3\">";
//Cerrar el Formulario
echo "<tr><td colspan='2' class=\"form_buttons\" align='right'>";
//echo "<div id=\"out_form1\">";
//echo form_hidden('id', $id_tras);
//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
	//echo "<button type=\"submit\"  id=\"boton1\" style=\"display:line;\">Paso 2. Detalles del Traspaso</button>";
}
echo '</td></tr></table>';
echo form_fieldset_close();
echo form_close();
?>
<div id="subform_detalle">
<table  class="row_detail" id="header" border='1' width='960px' style='border-radius:3px;'>
	<tr>
		<th class='detalle_header'>Estatus</th>
		<th class='detalle_header'>Cod. Bar.</th>
		<th class='detalle_header'>Descripción</th>
		<th class='detalle_header'>Cantidad</th>
	</tr>
<?php
foreach($entra->all as $entra ){
                  
                  $cantidad=  number_format($entra->cantidad,0,'.','');
                  $precio=  number_format($entra->costo_unitario,0,'.','');
                  $total=  number_format($entra->costo_total,0,'.','');
                 echo "<tr valign='top'><td class='detalle' width=\"50\" align='center'><a href=\"javascript:borrar($entra->cproductos_id,$entra->cproducto_numero_id)\">Borrar</a><input type='hidden' value='0' name='id' id='id'><div id=\"content$entra->cproductos_id$entra->cproducto_numero_id\"><img src='".base_url()."images/ok.png' width='20px'/><a href=\"javascript:editar_inventario($entra->cproductos_id,$entra->cproducto_numero_id)\">Editar</a></div></td>".
                                
                                        "<td class='detalle' width=\"10\">".
                                            "<input type=\"text\" class='cod_bar' size=\"15\" value=\"$entra->cod\"  id=\"cod_bar\" disabled=\"disabled\">".
                                        "</td>".

					"<td class='detalle' width=\"400\"><input type='hidden' name='producto_m$entra->cproducto_numero_id' id='producto_m$entra->cproducto_numero_id' value='$entra->cproducto_numero_id' size=\"3\"><input type='hidden' name='id_producto$entra->cproductos_id' id='id_producto$entra->cproductos_id' value='$entra->cproductos_id' size=\"3\">";echo "<input type='text' id='prod' class='prod$entra->cproductos_id' value='$entra->producto_nombre -# $entra->numero_mm - $entra->tag' size='80' disabled='disabled'></span></td>".
					"<td class='detalle' align=\"right\"><input type=\"text\" class='unidades' style='text-align:right;' name=\"cantidad$entra->cproducto_numero_id\" size=\"4\"  id=\"cantidad$entra->cproducto_numero_id\" value=\"$cantidad\"></td>".

					"</tr> \n";   
                     }


for($r=0;$r<$rows;$r++){
		if($r<13)
			$class="visible";
		else if ($r>10)
			$class="invisible";

		echo "<tr id=\"row$r\"  class=\"$class\" valign='top'><td class='detalle' width=\"50\" align='center'><input type='hidden' value='0' name='id$r' id='id$r'><input type='hidden' value='0' name='entrada_id$r' id='entrada_id$r'><div id=\"content$r\"><div id=\"content$r\"></div></td>".

		"<td class='detalle' align=\"right\"><input class='codigo_barras' type=\"text\" linea='$r' name=\"codigo_barras$r\" size=\"15\"  id=\"codigo_barras$r\" value=\"\"></td>".
		"<td class='detalle'>".
                        "<input type='hidden' name='producto_nid$r' id='producto_nid$r'>".
                        "<input type='hidden' name='producto_id$r' id='producto_id$r'>".
                        "<input type='text' id='prod$r' class='prod' linea='$r' value='' size='90'>".
                "</td>".
		"<td class='detalle' align=\"right\">".
                        "<input type=\"text\" class='unidades cantidad' linea='$r' style='text-align:right;' name=\"cantidad$r\" size=\"4\"  id=\"cantidad$r\" value=\"0\">".
                "</td>".
		"</tr> \n";
  }
?>
  <tr>
		<th class='detalle_pie'></th>
		<th class='detalle_pie' colspan='2'>Total</th>
		<th class='detalle_pie'><input name="cantidad_total" id="cantidad_total" type="text" value="0" class="subtotal" size="10"></th>
  </tr>
  <tr>
    <th class='detalle_pie' colspan="7">
	<?php
		echo "<button id='cancelar' type='button' onclick=\"window.location='".base_url()."index.php/almacen/almacen_c/cancelar_traspaso/'+$('#id').val()\">Cancelar</button>";
		if(substr(decbin($permisos), 0, 1)==1){
		//echo '<button type="button" onclick="javascript:send_detalle()" id="boton_p">Enviar Traspaso</button><div id="msg1"></div>';	
		echo '<button type="button" onclick="javascript:verificar()" id="boton_p">Enviar traspaso</button><div id="msg1"></div>';
		}
	?>
		<input id="finalizar" type="button" onclick="finalizar()" value="Finalizar" style="display: none"/>
		<span id="fin"></span><br>Al Guardar los detalles del Pedido verifique que se han guardado adecuadamente cada uno de ellos.</th>
	</tr>
</table>
</div>