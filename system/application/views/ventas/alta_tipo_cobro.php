<?php
//Inputs

$tag=array(
		'name'=>'tag',
		'size'=>'40',
		'value'=>'',
);

//Titulo
echo "<h2>$title</h2>";


//Abrir Formulario
$atrib=array('name'=>'form1');
echo form_open($ruta.'/trans/alta_tipo_cobro', $atrib) . "\n";
echo form_fieldset('<b>Datos del Tipo de Cobro</b>') . "\n";
echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";


//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"tag\">Tipo de Cobro:</label></td><td class='form_input'>"; echo form_input($tag); echo "</td></tr>";

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

//Link al listado
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_tipos_cobro\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Listado de Tipos de Cobro\"></a></div>";

?>