<?php

//Campos
$nombre=array(
		'name'=>'nombre',
		'size'=>'70',
		'value'=>'',
		'id'=>'nombre',
);

$comentario=array(
		'name'=>'comentario',
		'column'=>'50',
		'rows'=>'4',
		'value'=>'',
		'id'=>'comentario',
);

//Titulo
echo "<h2>$title</h2>";
$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta.'/trans/alta_stock', $atrib) . "\n";
echo form_fieldset('<b>Alta de Plantilla de Stock</b>') . "\n";

echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";


//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"nombre\">Nombre de la Plantilla:</label></td><td class='form_input'>"; echo form_input($nombre); echo "</td></tr>";


echo "<tr><td class='form_tag'><label for=\"comentario\">Comentario: </label></td><td class='form_input'>"; echo form_textarea($comentario);echo "</td></tr>";
//Cerrar el Formulario
echo "<tr><td colspan='4' class=\"form_buttons\">";
echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Salir al Men�</button>";
echo form_close();
//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
	echo '<button type="submit">Guardar y Agregar Detalles</button>';
}
echo '</td></tr></table>';
echo form_fieldset_close();
//Link al listado
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_stock\"><img src=\"".base_url()."images/stock.png\" width=\"50px\" title=\"Listado de Plantillas de Stock\"></a></div>";
?>