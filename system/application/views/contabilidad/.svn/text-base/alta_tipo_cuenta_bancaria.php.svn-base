<?php

//Inputs
$tag=array(
		'name'=>'tag',
		'size'=>'40',
		'value'=>'',
);


//Titulo
echo "<h2>$title</h2>";

echo "<div style='text-align: right;margin-right: 50px;'>";
//Link al listado
echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_tipos_cuentas_bancarias\"><img src=\"".base_url()."images/factura.png\" width=\"30px\" title=\"Listado de Tipos de Cuentas Bancarias\"></a>";
echo "</div>";
//Abrir Formulario
$atrib=array('name'=>'form1');
echo form_open($ruta.'/trans/alta_tipo_cuenta_bancaria', $atrib) . "\n";
echo form_fieldset('<b>Datos del Tipo de Cuenta Bancaria</b>') . "\n";
echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";


//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"tag\">Tipo de Cuenta Bancaria:</label></td><td class='form_input'>"; echo form_input($tag); echo "</td></tr>";

//Cerrar el Formulario
echo "<tr><td colspan='4' class=\"form_buttons\">";
echo form_reset('form1', 'Borrar');
echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Cerrar sin guardar</button>";
echo form_close();

//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
	echo '<button type="submit">Guardar Registro</button>';
}
echo '</td></tr></table>';
echo form_fieldset_close();

?>
