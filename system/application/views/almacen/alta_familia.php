<style>
    fieldset{
        width: 920px;
        margin: auto;
        margin-bottom: 10px;
    }
</style>

<?php
//Inputs
$clave=array(
		'name'=>'clave',
		'size'=>'10',
		'value'=>'',
		'id'=>'clave',
);

$tag=array(
		'name'=>'tag',
		'size'=>'30',
		'value'=>'',
		'id'=>'tag',
);
//Titulo
echo "<h2>$title</h2>";
echo "<div style='width:920px;margin:auto' align=\"right\">";
//Link al listado
echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_familias\"><img src=\"".base_url()."images/factura.png\" width=\"30px\" title=\"Listado de Familia de Productos\">Lista de Familia Productos</a>";
echo "<a href=\"".base_url()."index.php/almacen/almacen_reportes/formulario/rep_familias\"><img src=\"".base_url()."images/adobereader.png\" width=\"30px\" title=\"Reporte de Familias\">Reporte de Familias</a></div>";

//Load validacion
$this->load->view('validation_view');


//Abrir Formulario
$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta.'/trans/alta_familia', $atrib) . "\n";
echo form_fieldset('<b>Datos de Familia de Productos</b>') . "\n";
//Mensajes de validacion
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';

echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";
//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"clave\">Clave:</label></td><td class='form_input'>"; echo form_input($clave); echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"tag\">Nombre de la Familia de Productos:</label></td><td class='form_input'>"; echo form_input($tag); echo "</td></tr>";

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


?>
