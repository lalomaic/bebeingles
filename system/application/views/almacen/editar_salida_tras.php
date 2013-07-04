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

//Productos
$productos_list[0]="Elija";
if($productos != false){
	foreach($productos->all as $row){
		$y=$row->id;
		$productos_list[$y]=$row->clave." - ".$row->descripcion ." - ".$row->presentacion." - ".$row->unidad_medida;
	}
}
$url_form=base_url()."index.php/".$ruta."/trans/editar_salida_traspasos";
$this->load->view('almacen/js_editar_salida_tras');

//Titulo
echo "<h2>$title</h2>";
//Abrir Formulario
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';
$atrib=array('name'=>'form1', 'id'=>"form1");
echo form_open($ruta.'/trans/alta_traspaso', $atrib) . "\n";
echo form_fieldset('<b>Pedido de Traspaso</b>') . "\n";
echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";

//Campos del Formulario
echo "<tr><td class='form_tag' colspan='2'><label for=\"empresa\">Empresa:</label> $empresa->razon_social </td></tr>";

echo "<tr><td class='form_tag'><label for=\"ubicacion_salida_id\">AlmacÃ©n/Tienda solicitante: </label>"; echo form_dropdown('ubicacion_salida_id', $select2, "$traspaso->ubicacion_entrada_id", "disabled='disabled' id='ubicacion_salida'");echo "</td><td class='form_input'><label for=\"cpr_estatus_pedido_id\">Estatus del Pedido: </label>"; echo form_dropdown('cpr_estatus_pedido_id', $select4, "2", "disabled='disabled'"); echo "</td></tr>";

//Cerrar el Formulario
echo "<tr><td class=\"form_buttons\"></td><td class=\"form_buttons\">";
echo "<div id=\"out_form1\">";
echo form_hidden("traspasos_id", "$traspaso->id");
//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
	echo '<button type="submit" id="boton1" style="display:none;">Paso 1. Guardar Generales</button>';
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
			<th class='detalle_header' width="600">CLAVE - Producto -
				Presentacion U. Med</th>
		</tr>
		<?php
		//Imprimir valores actuales
		$r=0;
		if($cl_detalle!=false){
			$suma=0;
			foreach($traspaso_salidas->all as $linea){
				echo "<tr id='row$r' class='visible'><td class='detalle' width=\"100\">". ($r+1)."<form id=\"fdetail$r\" method=\"post\" name=\"fdetail$r\" action=\"$url_form/$r\"><button type=\"submit\" id=\"b$r\" style=\"display:none;\">Guardar Registro</button><div id=\"content$r\">"; echo form_hidden("id$r", "$linea->salidas_id"); echo form_hidden("traspaso_id$r", "$traspaso->id");  echo "<a href=\"javascript:borrar_detalle($r)\"><img src=\"".base_url()."images/trash1.png\" width=\"20px\" title=\"Borrar Detalle\"/></a>"; echo "</div></td>".

						"<td class='detalle' width=\"30\"><input type=\"text\" name=\"unidades$r\" size=\"4\" value='$linea->cantidad' id=\"cantidad$r\" class=\"cantidad\"></td>".

						"<td class='detalle'>"; echo form_dropdown("producto$r", $productos_list, "$linea->cproductos_id", "id='producto$r' class='prod'"); echo "</form></td>".

						"</tr> \n";
				$r+=1;
			}

			for($x=$r;$x<$r+$renglon_adic;$x++){

				echo "<tr class='invisible' id='row$x'><td class='detalle' width=\"50\">". ($x+1)."<form id=\"fdetail$x\" method=\"post\" name=\"fdetail$x\" action=\"$url_form/$x\"><button type=\"submit\" id=\"b$x\" style=\"display:none;\">Guardar Registro</button><div id=\"content$x\">"; echo form_hidden("id$x", "0"); echo form_hidden("traspaso_id$x", "$traspaso->id"); echo "</div></td>".

						"<td class='detalle' width=\"30\"><input type=\"text\" name=\"unidades$x\" size=\"4\" value='0' id=\"cantidad$x\" class=\"cantidad\"></td>".

						"<td class='detalle'>"; echo form_dropdown("producto$x", $productos_list, "", "id='producto$x' class='prod'"); echo "</form></td>".

						"</tr> \n";
			}

		}
		?>
		<table width="700" class="row_detail_prev" id="total">
			<tr>
				<th class='detalle_header' width="700" colspan="2" align="center"><?php
				echo "<button type='button' onclick=\"window.location='".base_url()."index.php/".$ruta."/almacen_c/formulario/list_traspasos/'\">Cerrar</button>";
				if(substr(decbin($permisos), 0, 1)==1){
					echo '<button type="button" onclick="javascript:send_detalle()">Guardar Cambios</button>';
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