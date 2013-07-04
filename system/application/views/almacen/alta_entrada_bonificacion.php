<?php
//Construir Proveedores
$select2[0]="Elija";
if($proveedores!= false){
	foreach($proveedores->all as $row){
		$y=$row->id;
		$select2[$y]=$row->razon_social;
	}
}



//Productos
$productos_list[0]="";
if($productos != false){
	foreach($productos->all as $row){
		$y=$row->id;
		$productos_list[$y]=$row->clave." - ".$row->descripcion ." - ".$row->presentacion." - ".$row->unidad_medida;
	}
}
$url_form=base_url()."index.php/".$ruta."/trans/alta_entrada_bonificacion";

//Inputs
$folio_factura=array(
		'name'=>'folio_factura',
		'id'=>'folio_factura',
		'size'=>'10',
		'value'=>'',
);
$fecha=array(
		'class'=>'date_input',
		'name'=>'fecha',
		'id'=>'fecha',
		'size'=>'10',
		'readonly'=>'readonly'
);

$numero_referencia=array(
		'name'=>'numero_referencia',
		'id'=>'numero_referencia',
		'size'=>'10',
		'value'=>''
);

$this->load->view('almacen/js_alta_entrada_bonificacion.php');
//js_subformulario_entrada');
//Titulo
echo "<h2>$title</h2>";
//Abrir Formulario
$atrib=array('name'=>'form1', 'id'=>"form1");
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';
echo form_open($ruta.'/trans/alta_pr_factura_bonificacion', $atrib) . "\n";
echo form_fieldset('<b>Factura de Entrada</b>') . "\n";
echo "<table width=\"90%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";

//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"empresas_id\">Comprador:</label> $empresa->razon_social </td><td class='form_tag' ><label for=\"cproveedores\">Proveedor: </label>"; echo form_dropdown('cproveedores_id', $select2, "0", "id='proveedores' class='prov'");echo "</td><td class='form_tag'><label for=\"fecha\">Fecha de la Factura:</label>"; echo form_input($fecha); echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"folio_factura\">Folio de la Factura:</label>"; echo form_input($folio_factura); echo "</td><td class='form_tag'><label for=\"numero_refencia\">Referencia SAGARPA:</label>"; echo form_input($numero_referencia); echo "</td><td class='form_tag'></td></tr>";

//Cerrar el Formulario
echo "<tr><td colspan='2' class=\"form_buttons\"></td><td class=\"form_buttons\">";
echo "<div id=\"out_form1\">";
echo form_hidden('id', "0");
//Permisos de Escritura byte 1
echo "</div>";
if(substr(decbin($permisos), 0, 1)==1){
	echo '<button type="submit" id="boton1" style="display:none;">Paso 1. Guardar Datos Generales</button>';
	//  echo '<button type="submit" id="boton1" style="display:inline;">Registrar Factura</button>';
}
echo '</td></tr></table>';
echo form_fieldset_close();
echo form_close();
?>
<div id="subform_detalle">
	<table width="870" class="row_detail" id="header" border='1'>
		<tr>
			<th class='detalle_header'>Estatus</th>
			<th class='detalle_header'>Cantidad</th>
			<th class='detalle_header'>CLAVE - Producto - Presentacion U. Med</th>
		</tr>
		<?php
		//Imprimir valores actuales
		for($r=0;$r<$rows;$r++){
			echo "<tr> <td class='detalle' width=\"50\"><form id=\"fdetail$r\" method=\"post\" name=\"fdetail$r\" action=\"$url_form/$r\"><button type=\"submit\" id=\"b$r\" style=\"display:none;\">Guardar Registro</button><div id=\"content$r\">";  echo form_hidden("id$r", "0");  echo form_hidden("pr_facturas_id$r", ""); echo "</div></td>".

					"<td class='detalle' width=\"70\"><input type=\"text\" name=\"unidades$r\" size=\"4\" id=\"cantidad$r\" value=\"0\"></td>".

					"<td class='detalle' width=\"400\">"; echo form_dropdown("producto$r", $productos_list, "0", "class='prod' id='producto$r'"); echo "</form></td>".
					"</tr> \n";
		}
		echo "</table>";
		?>
		<table width="970" class="row_detail" id="total">
			<tr>
				<th class='detalle_pie' width="970" colspan="2"><?php
				echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Cerrar sin guardar</button>";
				if(substr(decbin($permisos), 0, 1)==1){
					echo '<button type="button" onclick="javascript:send_detalle()">Dar ingreso a la Mercancia</button>';
				}
				?> <span id="fin"></span><br>Al Guardar los detalles, verifique que
					se han guardado adecuadamente cada uno de ellos.</th>
			</tr>
		</table>
		</div>
		<?php
		//Link al listado
		echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_compras\"><img src=\"".base_url()."images/consumo.png\" width=\"50px\" title=\"Listado de Pedidos de Compra\"></a><a href=\"".base_url()."index.php/".$ruta."/proveedores_c/".$funcion."/alta_proveedor\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Nuevo Usuario\"></a></div>";
		?>