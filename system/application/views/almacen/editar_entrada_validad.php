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
	echo "<h2>$title</h2>";
	//Abrir Formulario
	$atrib=array('name'=>'form1', 'id'=>"form1");
	echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';
	echo form_open($ruta.'/trans/editar_pr_factura', $atrib) . "\n";
	echo form_fieldset('<b>Corregir Factura de Entrada</b>') . "\n";
	echo "<table width=\"90%\" class='form_table'>";
	$img_row="".base_url()."images/table_row.png";

	//Campos del Formulario

	echo "<tr><td class='form_tag' colspan='3'><label for=\"empresas_id\">Comprador: $empresa->razon_social</label><br/><label for=\"espacio\">Sucursal: $espacio->tag </label> </td>";

	echo "<tr><td class='form_tag' colspan='2'><label for=\"cproveedores\">Proveedor:$proveedores->razon_social</label></td><td class='form_tag'><label for=\"ctipo_factura_id\">Tipo de Comprobante: <br/>"; echo form_dropdown('ctipo_factura_id', $select5, "$reng->ctipo_factura_id", "id='ctipo_factura_id' ");echo "</label></td></tr>";

	echo "<tr><td class='form_tag'><label for=\"fecha\">Fecha de la Factura:</label><br/>"; echo form_input($fecha); echo "</td><td class='form_tag'><label for=\"fecha\">Fecha de Pago:</label><br/>"; echo form_input($fecha_pago); echo "</td><td class='form_tag'><label for=\"folio_factura\">Folio de la Factura:</label><br/>"; echo form_input($folio_factura); echo "</td></tr>";

	echo "<tr><td class='form_tag'><label for=\"monto_total\">Monto Total de la Factura:</label><br/>"; echo form_input($monto_total); echo "</td><td class='form_tag'><label for=\"descuento\">Descuento:</label><br/>"; echo form_input($porcentaje_descuento); echo "</td><td class='form_tag'>"; echo form_radio('tipo_descuento','porcentaje',true); echo "Porcentaje<br/>"; echo form_radio('tipo_descuento','pesos',false); echo "Pesos<br/>"; echo "</td></tr>";




	//Cerrar el Formulario
	echo "<tr><td colspan='2' class=\"form_buttons\">";
	echo "<button type='button' style='display:block;' onclick=\"window.location='".base_url()."index.php/almacen/almacen_c/formulario/list_entradas'\">Cerrar sin guardar</button>";
	echo "</td><td class=\"form_buttons\" align='right'>";
	echo form_hidden('id', "$reng->id");
	echo form_hidden('pr_pedido_id', "$reng->pr_pedido_id");
	echo "<div id=\"out_form1\">";
	//Permisos de Escritura byte 1
	if(substr(decbin($permisos), 0, 1)==1){
		echo '<button type="submit" id="boton1" style="display:block;">Guardar Cambios</button>';
	}
	echo "</div>";
	echo '</td></tr></table>';
	echo form_fieldset_close();
	echo form_close();
}

?>