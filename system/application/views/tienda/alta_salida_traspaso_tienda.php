<?php
$espacio[]="Elija";
if($espacios_fisicos!=false){
	foreach($espacios_fisicos->all as $row){
		$espacio[$row->id]=$row->tag;
	}
}
$url_form=base_url()."index.php/".$ruta."/trans/alta_salida_traspasos";
$this->load->view('tienda/js_alta_salida_tras_tienda');
//Titulo
echo "<h2>$title</h2>";
//Abrir Formulario
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';
$atrib=array('name'=>'form1', 'id'=>"form1");
echo form_open($ruta.'/trans/alta_salida_traspaso_tienda', $atrib) . "\n";
echo form_fieldset('<b>Alta de Salida por Traspaso</b>') . "\n";
echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";
//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"empresa\">Empresa:</label><br/> $empresa->razon_social </td>";
echo "<td class='form_tag'><label for=\"empresa\">Tienda que recibe la mercancia:</label><br/>"; echo form_dropdown('espacio_fisico_recibe_id', $espacio, 0); echo "</td></tr>";

echo '</table>';
?>
<div id="subform_detalle">
	<table width="870" class="row_detail_pred" id="header">
		<tr>
			<th class='detalle_header' width="100">Código Barras</th>
			<th class='detalle_header' width="600">Estilo</th>
		</tr>
		<?php
		$r=0;
		for($x=0;$x<$rows;$x++){
			if($x<15){
				$class="visible";
			} else {
				$class="invisible";
			}
			echo "<tr class='$class' id='row$x'><td class='detalle' width=\"100\"><input type=\"text\" name=\"codigo$x\" size=\"15\" value='' id=\"codigo$x\" class=\"codigos\"></td>".

					"<td class='detalle' width=\"30\">"; echo form_hidden('cproducto_numero_id',''); echo "<span id='descripcion_span$x' style='background-color:red;color:#fff;font-size:10pt;'></span></td>".

					"</tr> \n";
		}
		?>
		<table width="700" class="row_detail_prev" id="total">
			<tr>
				<th class='detalle_header' width="700" colspan="2" align="center"><?php
				echo "<button type='button' onclick=\"open.window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Cerrar</button>";
				if(substr(decbin($permisos), 0, 1)==1){
					echo '<button type="submit"  id="boton_p">Enviar traspaso</button><span id="msg1"></span>';
				}
				?> <span id="fin"></span><br>Al Guardar los detalles del Traspaso
					verifique que se han guardado adecuadamente cada uno de ellos.</th>
			</tr>
		</table>
		</div>
		<?php
		echo form_fieldset_close();
		echo form_close();
		//Link al listado
		echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_transferencias\"><img src=\"".base_url()."images/consumo.png\" width=\"50px\" title=\"Listado de Pedidos de Traspaso\"></a></div>";
		?>