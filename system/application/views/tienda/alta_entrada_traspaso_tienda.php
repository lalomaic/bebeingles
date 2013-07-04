<script>
 $(document).ready(function() {
    $('#codigo_general').focus();
	$('#codigo_general').keypress(function(e) { 
        if(e.keyCode == 13) {
            buscar_productos($(this).val(),<?=$rows?>);
	        return false;

        }
    });
 });
</script>
<?php
$url_form=base_url()."index.php/".$ruta."/trans/alta_salida_traspasos";
$this->load->view('tienda/js_alta_entrada_tras_tienda');
//Titulo
echo "<h2>$title</h2>";
//Abrir Formulario
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';
$atrib=array('name'=>'form1', 'id'=>"form1");
echo form_hidden('lineas', $rows);
echo form_open($ruta.'/trans/alta_entrada_traspaso_tienda/'.$rows, $atrib) . "\n";
echo form_fieldset('<b>Alta de Salida por Traspaso</b>') . "\n";
echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";
//Campos del Formulario
echo "<tr><td class='form_tag' colspan='2'><label for=\"empresa\">Empresa:</label><br/> $empresa->razon_social </td></tr>";
echo "<tr><td class='form_tag' colspan='3' align='center'><label for=\"empresa\">Código del Producto:</label><br/><input type=\"text\" name=\"codigo_general\" size=\"15\" value='' id=\"codigo_general\" class=\"codigo_general_clase\" style='background-color:#ccc;'><br/><div id='area_notificacion'></div> </td></tr>";
echo '</table>';
?>
<div id="subform_detalle">
	<table width="870" class="row_detail_pred" id="header">
		<tr>
			<th class='detalle_header' width="30">Código Barras</th>
			<th class='detalle_header' width="400">Estilo</th>
			<th class='detalle_header' width="100">Sucursal que Envia</th>
			<th class='detalle_header' width="100">Fecha de Envío</th>

		</tr>
		<?php
		$x=0;
		if($traspasos!=false){
			$class="visible"; $value='';
			foreach($traspasos->all as $linea){
				$char=strlen($linea->cproducto_numero_id);
				$numero=$linea->cproducto_numero_id;
				if($linea->lote_id!=0){
					for($char;$char< 8;$char++){
						$numero="0".$numero;
					}
					$cod=$linea->lote_id.$numero;
				} else
					$cod=$linea->clave_anterior;
				//if($linea->envia=='PAVEL III')
				//$value=$cod;
				echo "<tr class='$class' id='row$x'><td class='detalle' width=\"30\">".($x+1).".<input type=\"hidden\" name=\"codigo$x\" size=\"13\" value='".$value."' id=\"codigo$x\" class=\"codigos\"><input type='hidden' id='etiqueta_$x' value='$cod' name='etiqueta_$x'></td>".
						"<td class='detalle' width=\"30\"><span id='descripcion_span$x'>$linea->descripcion # ".($linea->numero_mm/10). "</span></td>".
						"<td class='detalle'>$linea->envia</td>".
						"<td class='detalle'>$linea->fecha_salida</td>".
						"</tr> \n";
				$x+=1;
			}
		}
		?>
		<table width="700" class="row_detail_prev" id="total">
			<tr>
				<th class='detalle_header' width="700" colspan="2" align="center"><?php
				echo "<button type='button' onclick=\"open.window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Cerrar</button>";
				if(substr(decbin($permisos), 0, 1)==1){
					echo '<button type="submit"  id="boton_p">Recibir Mercancia</button><span id="msg1"></span>';
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