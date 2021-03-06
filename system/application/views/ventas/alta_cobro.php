<?php
//Construir Facturas
$select1[0]="Elija";
if($facturas!=false){
	foreach($facturas->all as $row){
		$y=$row->id;
		$select1[$y]=$row->folio_factura;
	}
}
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
if($cuentas_bancarias!=false){
	foreach($cuentas_bancarias->all as $row){
		$y=$row->id;
		$select3[$y]=$row->numero_cuenta;
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

//Inputs
$fecha=array(
		'class'=>'date_input',
		'name'=>'fecha',
		'size'=>'30',
		'value'=>'',
		'readonly'=>'readonly',
);

$monto_pagado=array(
		'name'=>'monto_pagado',
		'size'=>'30',
		'value'=>'',
);

//Titulo
echo "<h2>$title</h2>";
echo <<<end
<script>
  $($.date_input.initialize);
</script>
end;
//Abrir Formulario
$atrib=array('name'=>'form1');
echo form_open($ruta.'/trans/alta_cobro', $atrib) . "\n";
echo form_fieldset('<b>Datos del Cobro</b>') . "\n";
echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";


//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"facturas\">Folio de la Factura:</label></td><td class='form_input'>"; echo form_dropdown('cl_facturas_id', $select1);echo "</td><td class='form_tag'><label for=\"formas_cobros\">Forma de Cobro: </label></td><td class='form_input'>"; echo form_dropdown('ccl_forma_pago_id', $select2);echo "</td><td class='form_tag'><label for=\"tipos_cobros\">Tipo de Cobro:</label></td><td class='form_input'>"; echo form_dropdown('ctipo_cobro_id', $select4);echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"cuentas_bancarias\">Numero de Cuenta:</label></td><td class='form_input'>"; echo form_dropdown('ccuenta_bancaria_id', $select3);echo "</td><td class='form_tag'><label for=\"monto_pagado\">Monto Pagado:</label></td><td class='form_input'>"; echo form_input($monto_pagado); echo "</td><td class='form_tag'><label for=\"fecha\">Fecha: </label></td><td class='form_input'>"; echo form_input($fecha);echo "</td></tr>";

//Cerrar el Formulario
echo "<tr><td colspan='6' class=\"form_buttons\">";
echo form_reset('form1', 'Borrar');
echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Cerrar sin guardar</button>";
echo form_close();

//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
	echo '<button type="submit">Guardar Registro</button>';
}
echo '</td></tr></table>';
echo form_fieldset_close();

//Link al listado
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_cobros\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Listado de Cobros\"></a></div>";

?>
