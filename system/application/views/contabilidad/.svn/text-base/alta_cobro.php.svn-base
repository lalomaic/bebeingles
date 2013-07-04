<?php
//Construir Facturas
$select1[0]="Elija Cliente";

//Construir Formas de Cobro
$select2[0]="Elija";
if($formas_cobros!=false){
	foreach($formas_cobros->all as $row){
		$y=$row->id;
		$select2[$y]=$row->tag;
	}
}
//Construir Cuentas Bancarias
$select3[0]="Elija";
$select6[0]="Elija";
if($cuentas_bancarias!=false){
	foreach($cuentas_bancarias->all as $row){
		$y=$row->id;
		if($row->empresa_id>0)
	  $select3[$y]=$row->banco. " - ".$row->numero_cuenta;
		else if ($row->ccliente_id>0)
	  $select6[$y]=$row->banco. " - ".$row->numero_cuenta;

	}
}

//Construir Tipos de Cobro
$select4[0]="Elija";
if($tipos_cobros!=false){
	foreach($tipos_cobros->all as $row){
		$y=$row->id;
		$select4[$y]=$row->tag;
	}
}

//Construir Proveedores
$select5[0]="Elija";
if($clientes!=false){
	foreach($clientes->all as $row){
		$y=$row->id;
		$select5[$y]=$row->razon_social ." - ". $row->clave;
	}
}

//Inputs
$fecha=array(
		'class'=>'date_input',
		'name'=>'fecha',
		'size'=>'10',
		'value'=>'',
		'id'=>'fecha',
		'readonly'=>'readonly'
);
$numero_referencia=array(
		'name'=>'numero_referencia',
		'size'=>'15',
		'value'=>'',
);
$monto_pagado=array(
		'name'=>'monto_pagado',
		'size'=>'10',
		'value'=>'',
		'id'=>'monto_pagado',
);

//Titulo
echo "<h2>$title</h2>";
?>
<script type="text/javascript"> 
  $(document).ready(function() {
  $('#cliente').select_autocomplete(); 
  $($.date_input.initialize);

  $('#fecha').focus(function() {
    get_facturas_select($('#cliente').val());
    get_cuentas_cliente($('#cliente').val());

  });


  $('#facturas').change(function() {
  $('#detalle_cobros').html("Cargando cobros relacionados con la factura");
    get_cobros_factura($(this).val());
  });
});

 
function get_cobros_factura(id){
    // post(file, data, callback, type); (only "file" is required)
    if(id>0){
    $.post("<? echo base_url();?>index.php/ajax_pet/cobros_factura",{ enviarvalor : id },  // create an object will all values
    //function that is called when server returns a value.
      function(data){
	  $('#detalle_cobros').html(data);
      }
    );
   } else {
      $('#fact').html("Ocurrio un problema con la conexión con el servidor, vuelva a intentar");
   }
}

function get_facturas_select(valor){
    if(valor>0){
      $("#facturas").removeOption(/./);
      $("#facturas").ajaxAddOption("<? echo base_url();?>index.php/ajax_pet/get_facturas_cobros/"+valor);
    } 
} 
function get_cuentas_cliente(valor){
    if(valor>0){
      $("#cuenta_origen").removeOption(/./);
      $("#cuenta_origen").ajaxAddOption("<? echo base_url();?>index.php/ajax_pet/get_cuentas_cliente/"+valor);
    } 
} 
</script>
<?php
$this->load->view('validation_view');
//Abrir Formulario
$atrib=array('name'=>'form1');
echo form_open($ruta.'/trans/alta_cobro', $atrib) . "\n";
echo form_fieldset('<b>Datos del Cobro</b>') . "\n";
echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";

//Campos del Formulario
echo "<tr><td class='form_tag' colspan='3'><label for=\"proveedor\">Cliente:</label><br/>"; echo form_dropdown('cliente', $select5, 0, "id='cliente'");echo "</td><td class='form_tag'><label for=\"fecha\">Fecha: </label><br/>"; echo form_input($fecha);echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"cl_factura_id\">Folio de la Factura:</label><br/>"; echo form_dropdown('cl_factura_id', $select1, 0, 'id="facturas"');echo "</td><td class='form_tag'><label for=\"formas_cobros\">Forma de Cobro: </label><br/>"; echo form_dropdown('ccl_forma_pago_id', $select2);echo "</td><td class='form_tag'><label for=\"tipos_cobros\">Tipo de Cobro:</label><br/>"; echo form_dropdown('ctipo_cobro_id', $select4);echo "</td></tr>";

echo "<tr><td colspan='6'><span id='detalle_cobros'></span></td></tr>";

echo "<tr><td class='form_tag'><label for=\"cuenta_origen_id\">Numero de Cuenta Origen:</label><br/>"; echo form_dropdown('cuenta_origen_id', $select6, 0, "id='cuentas_origen'"); echo "</td><td class='form_tag'><label for=\"cuenta_destino_id\">Numero de Cuenta Destino:</label><br/>"; echo form_dropdown('cuenta_destino_id', $select3, 0, "id='cuenta_destino'"); echo "</td<td class='form_tag'><label for=\"fecha\">No. Autorización: </label><br/>"; echo form_input($numero_referencia);echo "</td><td class='form_tag'><label for=\"fecha\">Monto Pagado: </label><br/>"; echo form_input($monto_pagado);echo "</td></tr>";


//Cerrar el Formulario
echo "<tr><td colspan='6' class=\"form_buttons\">";
echo form_reset('form1', 'Borrar');
echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Menú</button>";
//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1)
	echo '<button type="submit">Guardar Registro</button>';
echo '</td></tr></table>';
echo form_fieldset_close();
echo form_close();

//Link al listado
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_pagos\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Listado de Cobros\"></a></div>";

?>
