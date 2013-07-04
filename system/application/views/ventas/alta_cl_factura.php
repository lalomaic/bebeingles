<?php
//Inputs
if($estatus!= false){
	foreach($estatus->all as $row){
		$y=$row->id;
		$select3[$y]=$row->tag;
	}
}
if($tipo_factura!= false){
	foreach($tipo_factura->all as $row){
		$y=$row->id;
		$select4[$y]=$row->tag;
	}
}

$select2[0]="";
if($clientes!= false){
	foreach($clientes->all as $row){
		$y=$row->id;
		$select2[$y]=$row->razon_social ." - ". -$row->clave;
	}
}

if($agentes!= false){
	foreach($agentes->all as $row){
		$y=$row->id;
		$select5[$y]="$row->nombre $row->apaterno $row->amaterno";
	}
}

$fecha=array(
		'class'=>'date_input',
		'name'=>'fecha',
		'size'=>'10',
		'readonly'=>'readonly',
		'value'=>'',
);

$folio_factura=array(
		'name'=>'folio_factura',
		'size'=>'10',
		'value'=>'',
);
//Titulo
echo "<h2>$title</h2>";
?>
<script type="text/javascript"> 
 $(document).ready(function() { 
    $('#clientes').select_autocomplete();
    $($.date_input.initialize);
// changing date format to DD/MM/YYYY
    $(".form_table").hide();
    $("#cerrar").hide();
    $(".form_table").fadeOut("4000");
    $(".form_table").fadeIn("4000");
    $("#empr").focus();

    //opciones para el formulario principal
    var options = { 
        target:        '#out_form1',   // target element(s) to be updated with server response 
//        beforeSubmit:  form_principal,  // pre-submit callback 
        success:       despues// post-submit callback 
    }; 


  //Redirecciones a los contenedores de los estatus de los detalles y envio mediante ajax
    $('#form1').submit(function() {
	$('#boton_p').hide();
	$('#fin').html("<br/><img src='<? echo base_url(); ?>images/cargando.gif' width='50px'/><br/>Cargando...<br/>");
        $(this).ajaxSubmit(options);
        return false;
    });
  });

  function despues(){
      var id=eval("document.form1.id.value");
      $('#fin').html('<br/><p><a href="<? echo base_url();?>index.php/ventas/factura/generar/'+id+'"  target="_blanck" class="modal_pdf1"><img src="<? echo base_url();?>images/adobereader.png" width="32"/><br/>Imprimir</a></p>');
      $('#cerrar').show();
  }
</script>
<?php
//Abrir Formulario
$atrib=array('name'=>'form1', 'id'=>'form1', 'target'=>'_blank');
echo form_open($ruta.'/trans/alta_cl_factura', $atrib) . "\n";
echo form_fieldset('<b>Datos Generales de la Factura de Cliente</b>') . "\n";
echo "<table class='form_table'>";
$img_row="".base_url()."images/table_row.png";
//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"empresas_id\">Empresa:</label></td><td class='form_input' colspan='3'>$empresas->razon_social "; echo "</td></tr>";
echo "<tr><td class='form_tag'><label for=\"cliente\">Cliente:</label></td><td colspan='3'>"; echo form_dropdown('cclientes_id', $select2, "$factura->cclientes_id", "id='clientes'"); echo "</td></tr>";
echo "<tr><td class='form_tag'><label for=\"observaciones\">Observaciones:</label></td><td colspan='3'>$pedido->observaciones</td></tr>";
echo "<tr><td class='form_tag'><label for=\"empleado_id\">Agente de Venta:</label></td><td colspan='3'>"; echo form_dropdown('empleado_id', $select5, "1", "id='agente'");echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"nombre\">Folio de la Factura:</label>"; echo form_input($folio_factura); echo "</td><td class='form_tag'><label for=\"fecha\">Fecha de la Factura: </label>"; echo form_input($fecha);echo "</td><td class='form_tag'><label for=\"estatus_factura_id\">Forma de Cobro: </label>"; echo form_dropdown('estatus_factura_id', $select3, "1", "id='estatus'");
echo "</td><td class='form_tag'><label for=\"tipo_factura_id\">Tipo de Factura: </label>"; echo form_dropdown('tipo_factura_id', $select4, "0", "id='tipo'");
echo "</td></tr><tr><td>";
echo "<div id=\"out_form1\">";
echo form_hidden('cl_pedido_id', "$pedido->id");
echo form_hidden("id", "$factura->id");
//Permisos de Escritura byte 1
echo "</div>";
echo "</td></tr>";
echo '</table>';
?>
<br />
<table width="700" class="row_detail" id="header">
	<tr>
		<th class='detalle_header'>#</th>
		<th class='detalle_header'>Cantidad</th>
		<th class='detalle_header'>CLAVE - Producto - Presentacion U. Med</th>
		<th class='detalle_header'>Precio U.</th>
		<th class='detalle_header'>IVA</th>
		<th class='detalle_header'>SubTotal</th>
	</tr>
	<?php
	$i=1;
	$iva_t=0;
	$subtotal=0;
	foreach($salidas->all as $row){
		$iva=$row->tasa_impuesto*$row->costo_unitario*$row->cantidad/(100+$row->tasa_impuesto);
		$iva_t+=$iva;
		$subtotal+=$row->costo_unitario*$row->cantidad;
		echo "<tr><td align='center'>$i</td>".

				"<td class='detalle' width=\"70\" align='center'>".number_format($row->cantidad, 2, ".",",")."</td>".

				"<td class='detalle' width=\"400\">$row->descripcion $row->presentacion $row->unidad_medida</td>".

				"<td class='detalle' width=\"100\" align='right'>".number_format($row->costo_unitario, 2, ".",",") ."</td>".

				"<td class='detalle' width=\"70\" align='right'>".number_format($iva, 2, ".",",")."</td>".

				"<td class='detalle' width=\"100\" align='right'>".number_format($row->costo_total-$iva, 2, ".",",")."</td>".
				"</tr>";
		$i+=1;
	}
	echo "<tr><td align='center' colspan='4'></td>".

			"<th class='detalle' width=\"70\" align='right'>Subtotal</th>".

			"<th class='detalle' width=\"100\" align='right'>".number_format($subtotal-$iva_t, 2, ".",",")."</th>".
			"</tr>";
	echo "<tr><td align='center' colspan='4'></td>".

			"<th class='detalle' width=\"70\" align='right'>IVA</th>".

			"<th class='detalle' width=\"100\" align='right'>".number_format($iva_t, 2, ".",",")."</th>".
			"</tr>";
	echo "<tr><td align='center' colspan='4'></td>".

			"<th class='detalle' width=\"70\" align='right'>Total</th>".

			"<th class='detalle' width=\"100\" align='right'>".number_format($subtotal, 2, ".",",")."</th>".
			"</tr>";

	echo "</table>";
	echo "<tr><td align='center' colspan='6'>";
	echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Menú</button>";
	//Permisos de Escritura byte 1
	if(substr(decbin($permisos), 0, 1)==1){
		echo '<button type="submit" id="boton_p">Guardar</button><span id="fin"></span>';
	}
	echo "<button id='cerrar' type='button' onclick=\"window.location='".base_url()."index.php/ventas/cl_factura_c/formulario/list_cl_facturas'\">Menú</button>";
	echo "</td></tr></table>";
	echo form_close();
	echo form_fieldset_close();

	//Link al listado
	echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_pr_facturas\"><img src=\"".base_url()."images/factura.png\" width=\"50px\" title=\"Listado de Facturas de Proveedores\"></a><a href=\"".base_url()."index.php/".$ruta."/proveedores_c/".$funcion."/list_proveedor\"><img src=\"".base_url()."images/proveedor.png\" width=\"50px\" title=\"Listado de Proveedores\"></a></div>";
	?>