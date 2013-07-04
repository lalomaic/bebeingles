<?php
//Ubicaciones
$select2[0]="Elija";
if($espacios_fisicos!= false){
	foreach($espacios_fisicos->all as $row){
		$y=$row->id;
		$select2[$y]=$row->tag;
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
$url_form=base_url()."index.php/".$ruta."/trans/alta_pedido_transferencia_detalles";
$this->load->view('tienda/js_alta_traspaso_plantilla');
//Titulo
echo "<h2>$title en $tienda</h2>";
//Abrir Formulario
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';
$atrib=array('name'=>'form1', 'id'=>"form1");
echo form_open($ruta.'/trans/alta_transferencia', $atrib) . "\n";
echo form_fieldset('<b>Paso 1. Generales del Pedido de Traspaso</b>') . "\n";
echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";

//Campos del Formulario
echo "<tr><td class='form_tag' colspan='2'><label for=\"empresa\">Empresa:</label> $empresa->razon_social </td></tr>";
echo "<tr><td class='form_tag'><label for=\"ubicacion_salida_id\">Almac�n/Tienda a la que solicita el pedido: </label>"; echo form_dropdown('ubicacion_salida_id', $select2, "2", "id='ubicacion_salida'");echo "</td><td class='form_input' align=\"right\"><label for=\"cpr_estatus_pedido_id\">Estatus del Pedido: </label>"; echo form_dropdown('cpr_estatus_pedido_id', $select4, "2", "disabled='disabled'"); echo "</td></tr>";

//Cerrar el Formulario
echo "<tr><td class=\"form_buttons\" align=\"center\" colspan='4'>";
echo "<div id=\"out_form1\" style='text-align:center;'>";
echo form_hidden('id', "0");
//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
	echo '<button type="submit" id="boton1" style="display:none">Iniciar Pedido Traspaso</button>';
}
echo "</div>";
echo '</td></tr></table>';
echo form_fieldset_close();
echo form_close();
?>
<div id="subform_detalle">
	<table width="700" class="row_detail_pred" id="header">
		<tr>
			<th class='detalle_header'>Estatus</th>
			<th class='detalle_header'>Producto</th>
			<th class='detalle_header'>Cantidad Plantilla</th>
			<th class='detalle_header'>Cantidad Solicitada</th>
		</tr>
		<?php
		//Imprimir valores actuales
		for($r=0;$r<count($productos);$r++){
			if ($productos[$r]['cantidad_default']==0){
				$class="invisible";
			} else
				$class="visible";

			echo "<tr id=\"row$r\"  class=\"$class\" ><td  class='detalle' width=\"50\" align=\"center\">". ($r+1) .".<form id=\"fdetail$r\" method=\"post\" name=\"fdetail$r\" action=\"$url_form/$r\"><button type=\"submit\" id=\"b$r\" style=\"display:none;\">Guardar Registro</button><div id=\"content$r\">"; echo form_hidden("id$r", '0');  echo form_hidden("producto$r", "{$productos[$r]['cproducto_id']}"); echo form_hidden("cl_pedidos_id$r", "0"); echo "</div></td>".
					"<td class='detalle' width=\"30\">{$productos[$r]['descripcion']}</td>".
					"<td class='detalle' align='right' width=\"10\">".number_format($productos[$r]['cantidad_default'], 2, ".",",")."</td>".
					"<td class='detalle' width=\"30\" align='right'><input
					type=\"text\" name=\"unidades$r\" size=\"4\" value='{$productos[$r]['cantidad']}' class=\"cantidad\"></form></td>".
					"</tr> \n";

		}
		?>
		<table width="700" class="row_detail_prev" id="total">
			<tr>
				<th class='detalle_header' width="700" colspan="2" align="center"><?php
				echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Cerrar sin guardar</button>";
				if(substr(decbin($permisos), 0, 1)==1){
					echo '<button type="button" onclick="javascript:send_detalle1()">Paso 2. Guardar Detalles</button>';
				}
				?> <span id="fin"></span><br>Al Guardar los detalles del Traspaso
					verifique que se han guardado adecuadamente cada uno de ellos.</th>
			</tr>
		</table>
		</div>
		<?php
		//Link al listado
		echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_transferencias\"><img src=\"".base_url()."images/consumo.png\" width=\"50px\" title=\"Listado de Pedidos de Traspaso\"></a></div>";
?>