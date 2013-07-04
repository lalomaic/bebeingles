<?php
//Productos
$productos_list[0]="";
if($productos != false){
	foreach($productos->all as $row){
		$y=$row->id;
		$productos_list[$y]=$row->clave." - ".$row->descripcion ." - ".$row->presentacion." - ".$row->unidad_medida;
	}
}
$url_form=base_url()."index.php/".$ruta."/trans/alta_arqueo_detalles";
$this->load->view('tienda/js_alta_existencia');
//Titulo
echo "<h2>$title en $tienda</h2>";
//Abrir Formulario
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';
$atrib=array('name'=>'form1', 'id'=>"form1");
echo form_open($ruta.'/trans/alta_arqueo', $atrib) . "\n";
echo form_fieldset('<b>Paso 1. Generales del Pedido de Traspaso</b>') . "\n";
echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";
//Cerrar el Formulario
echo "<tr><td class=\"form_buttons\" align=\"center\">";
echo "<div id=\"out_form1\" style='text-align:center;'>";
echo form_hidden('id', "");
//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
	echo '<button type="submit" id="boton1">Iniciar Inventario</button>';
}
echo "</div>";
echo '</td></tr></table>';
echo form_fieldset_close();
echo form_close();
?>
<div id="subform_detalle">
	<table width="700" class="row_detail_pred" id="header">
		<tr>
			<th class='detalle_header'>#</th>
			<th class='detalle_header'>Estatus</th>
			<th class='detalle_header' width="600">CLAVE - Producto -
				Presentacion U. Med</th>
			<th class='detalle_header'>Existencia F�sica</th>
		</tr>
		<?php
		function esPar($num){
			return !($num%2);
		}
		//Imprimir valores actuales
		$r=0;
		$class="visible";
		if($inventario!=false){
			foreach($inventario as $row){
				if(esPar($r))
					$color="_gris";
				else
					$color="_blanco";


				echo "<tr id=\"row$r\"  class=\"$class\" ><td  class='detalle$color' align=\"center\">". ($r+1) ."</td><td  class='detalle$color' width=\"50\" align=\"center\"><form id=\"fdetail$r\" method=\"post\" name=\"fdetail$r\" action=\"$url_form/$r\"><button type=\"submit\" id=\"b$r\" style=\"display:none;\">Guardar Registro</button><div id=\"content$r\">"; echo form_hidden("id$r", '0');  echo form_hidden("arqueo_id$r", "0");echo form_hidden("cproducto_id$r", "{$row['id']}"); echo form_hidden("concepto$r", "{$row['existencias']}");echo "</div></td>".
						"<td class='detalle$color' width=\"30\">{$row['descripcion']}</td>".
						"<td class='detalle$color' width=\"30\"><input type=\"text\" name=\"cantidad_real$r\" size=\"10\" value='0' id=\"cantidad_real$r\" class=\"cantidad\"></form></td>".
						"</tr> \n";
				$r+=1;
			}
		}
		?>
		<table width="700" class="row_detail_prev" id="total">
			<tr>
				<th class='detalle_header' width="700" colspan="2" align="center"><?php
				echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Men�</button>";
				if(substr(decbin($permisos), 0, 1)==1)
					echo '<button type="button" onclick="javascript:send_detalle()" id="boton_p">Guardar Existencias F�sicas</button><span id="msg1"></span>';
				?> <span id="fin"></span><br>Al Guardar los detalles del Traspaso
					verifique que se han guardado adecuadamente cada uno de ellos.</th>
			</tr>
		</table>
		</div>
		<?php
		//Link al listado
		echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_transferencias\"><img src=\"".base_url()."images/consumo.png\" width=\"50px\" title=\"Listado de Pedidos de Traspaso\"></a></div>";
?>