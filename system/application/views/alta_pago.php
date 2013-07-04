<?php
//Construir Facturas
$select1[0]="Elija Proveedor";

//Construir Formas de Pago
$select2[0]="Elija";
if($formas_pagos!=false){
	foreach($formas_pagos->all as $row){
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
		else if ($row->cproveedor_id>0)
	  $select6[$y]=$row->banco. " - ".$row->numero_cuenta;

	}
}
//Construir Tipos de Pago
$select4[0]="Elija";
if($tipos_pagos!=false){
	foreach($tipos_pagos->all as $row){
		$y=$row->id;
		$select4[$y]=$row->tag;
	}
}
//Construir Proveedores
$select5[0]="Elija";
if($proveedores!=false){
	foreach($proveedores->all as $row){
		$y=$row->id;
		$select5[$y]=$row->razon_social;
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
  $('#proveedor').select_autocomplete(); 
  $($.date_input.initialize);

  $('#fecha').focus(function() {
    get_facturas_select($('#proveedor').val());
    get_cuentas_prov($('#proveedor').val());

  });


    $('#facturas').blur(function() {
  $('#detalle_pagos').html("Cargando pagos relacionados con la factura");
    get_pagos_factura($(this).val());
  });
});

 
function get_pagos_factura(id){
    // post(file, data, callback, type); (only "file" is required)
    if(id>0){
    $.post("<? echo base_url();?>index.php/ajax_pet/pagos_factura",{ enviarvalor : id },  // create an object will all values
    //function that is called when server returns a value.
      function(data){
	  $('#detalle_pagos').html(data);
      }
    );
   } else {
      $('#fact').html("Ocurrio un problema con la conexión con el servidor, vuelva a intentar");
   }
}

function get_facturas_select(valor){
    if(valor>0){
      $("#facturas").removeOption(/./);
      $("#facturas").ajaxAddOption("<? echo base_url();?>index.php/ajax_pet/get_facturas_pagos/"+valor);
    } 
} 
function get_cuentas_prov(valor){
    if(valor>0){
      $("#cuenta_destino").removeOption(/./);
      $("#cuenta_destino").ajaxAddOption("<? echo base_url();?>index.php/ajax_pet/get_cuentas_prov/"+valor);
    } 
} 

</script>
<?php

//Load validacion
$this->load->view('validation_view');

//Abrir Formulario
$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta.'/trans/act_pago', $atrib) . "\n";
echo form_fieldset('<b>Datos del Pago</b>') . "\n";
//Mensajes de validacion
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';

echo "<table width=\"900\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";


//Campos del Formulario
echo "<tr><td class='form_tag' colspan='3'><label for=\"proveedor\">Proveedor:</label><br/>"; echo form_dropdown('proveedor', $select5, 0, "id='proveedor'");echo "</td><td class='form_tag'><label for=\"fecha\">Fecha: </label><br/>"; echo form_input($fecha);echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"pr_facturas_id\">Folio de la Factura:</label><br/><span id=\"fact\">"; echo form_dropdown('pr_factura_id', $select1, 0, "id='facturas'");echo "</span></td><td class='form_tag'><label for=\"formas_pagos\">Forma de Pago: </label><br/>"; echo form_dropdown('cpr_forma_pago_id', $select2, 4, "id='formas_pagos'");echo "</td><td class='form_tag'><label for=\"tipos_pagos\">Tipo de Pago:</label><br/>"; echo form_dropdown('ctipo_pago_id', $select4, 0, "id='tipos_pagos'");echo "</td></tr>";

echo "<tr><td colspan='4'><span id='detalle_pagos'></span></td></tr>";


echo "<tr><td class='form_tag'><label for=\"cuenta_origen_id\">Numero de Cuenta Origen:</label><br/>"; echo form_dropdown('cuenta_origen_id', $select3, 0, "id='cuentas_bancarias'"); echo "</td><td class='form_tag'><label for=\"cuenta_destino_id\">Numero de Cuenta Destino:</label><br/>"; echo form_dropdown('cuenta_destino_id', $select6, 0, "id='cuenta_destino'"); echo "</td><td class='form_tag'><label for=\"fecha\">No. Autorización: </label><br/>"; echo form_input($numero_referencia);echo "</td><td class='form_tag'><label for=\"fecha\">Monto Pagado: </label><br/>"; echo form_input($monto_pagado);echo "</td></tr>";

//Cerrar el Formulario
echo "<tr><td colspan='6' class=\"form_buttons\">";
echo form_reset('form1', 'Limpiar');
echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Menú</button>";
//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
	echo '<button type="submit">Guardar Registro</button>';
}
echo '</td></tr></table>';
echo form_fieldset_close();
echo form_close();
//Link al listado
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_pagos\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Listado de Pagos\"></a></div>";

?>