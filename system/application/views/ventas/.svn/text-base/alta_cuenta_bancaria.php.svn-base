<?php
//Construir Tipos de Cuentas Bancarias
$select1[0]="Elija";
if($tipos_cuentas_bancarias!=false){
	foreach($tipos_cuentas_bancarias->all as $row){
		$y=$row->id;
		$select1[$y]=$row->tag;
	}
}

//Construir Proveedores
$select2[0]="Elija";
if($proveedores!=false){
	foreach($proveedores->all as $row){
		$y=$row->id;
		$select2[$y]=$row->razon_social;
	}
}

//Construir Clientes
$select3[0]="Elija";
if($clientes!=false){
	foreach($clientes->all as $row){
		$y=$row->id;
		$select3[$y]=$row->razon_social;
	}
}

//Construir Empresas
$select4[0]="Elija";
if($empresas!=false){
	foreach($empresas->all as $row){
		$y=$row->id;
		$select4[$y]=$row->razon_social;
	}
}

//Inputs
$banco=array(
		'name'=>'banco',
		'size'=>'30',
		'value'=>'',
);

$numero_cuenta=array(
		'name'=>'numero_cuenta',
		'size'=>'10',
		'value'=>'',
);
$clabe=array(
		'name'=>'clabe',
		'size'=>'5',
		'value'=>'',
);

//Titulo
echo "<h2>$title</h2>";
//Abrir Formulario
$atrib=array('name'=>'form1');
echo form_open($ruta.'/trans/alta_cuenta_bancaria', $atrib) . "\n";
echo form_fieldset('<b>Datos de la Cuenta Bancaria</b>') . "\n";
echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";


//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"tipos_cuentas_bancarias\">Tipo de Cuenta Bancaria:</label></td><td class='form_input'>"; echo form_dropdown('ctipo_cuenta_id', $select1);echo "</td><td class='form_tag'><label for=\"banco\">Nombre del Banco:</label></td><td class='form_input'>"; echo form_input($banco); echo "</td><td class='form_tag'><label for=\"numero_cuenta\">Numero de Cuenta:</label></td><td class='form_input'>"; echo form_input($numero_cuenta); echo "</td><td class='form_tag'><label for=\"clabe\">Clabe:</label></td><td class='form_input'>"; echo form_input($clabe); echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"empresas\">A nombre de la Empresa:</label></td><td class='form_input'>"; echo form_dropdown('empresa_id', $select4);echo "</td><td class='form_tag'><label for=\"proveedores\">ó del Proveedor: </label></td><td class='form_input'>"; echo form_dropdown('cproveedor_id', $select2);echo "</td><td class='form_tag'><label for=\"clientes\">ó del Cliente: </label></td><td class='form_input'>"; echo form_dropdown('ccliente_id', $select3);echo "</td></tr>";

//Cerrar el Formulario
echo "<tr><td colspan='8' class=\"form_buttons\">";
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
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_cuentas_bancarias\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Listado de Cuentas Bancarias\"></a></div>";

?>
