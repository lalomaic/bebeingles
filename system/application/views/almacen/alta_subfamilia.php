<style>
    fieldset{
        width: 920px;
        margin: auto;
        margin-bottom: 10px;
    }
</style>
<?php
//Construir Familia de Productos
$select1[0]="Elija";
if($familia_productos!=false){
	foreach($familia_productos->all as $row){
		$y=$row->id;
		$select1[$y]=$row->tag;
	}
} else
	$select1[0]="Sin familias de productos";
//print_r(array_values($select1));
//Inputs
$tag=array(
		'name'=>'tag',
		'size'=>'30',
		'value'=>'',
		'id'=>'tag',
);

$clave=array(
		'name'=>'clave',
		'size'=>'10',
		'value'=>'',
		'id'=>'clave',
);
//Titulo
echo "<h2>$title</h2>";
//Link al listado
echo "<div style='width:920px;margin:auto' align=\"right\">";
echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_subfamilias\" style='margin:10px'><img src=\"".base_url()."images/factura.png\" width=\"30px\" title=\"Listado de Subfamilias de Productos\">Listado de Subfamilia Productos</a>";
echo "<a href=\"".base_url()."index.php/almacen/almacen_reportes/formulario/rep_subfamilias\"><img src=\"".base_url()."images/adobereader.png\" width=\"30px\" title=\"Reporte de Subfamilias de Productos\">Reporte de Subfamilias</a></div>";

//Load validacion
$this->load->view('validation_view');

//Abrir Formulario
$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta.'/trans/alta_subfamilia', $atrib) . "\n";
echo form_fieldset('<b>Datos de la Subfamilia de Productos</b>') . "\n";
//Mensajes de validacion
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';

echo "<table  class='form_table'>";
$img_row="".base_url()."images/table_row.png";


//Campos del Formulario

echo "<tr><td class='form_tag'><label for=\"clave\">Clave:</label></td><td class='form_input'>"; echo form_input($clave); echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"tag\">Nombre de la Subfamilia de Productos:</label></td><td class='form_input'>"; echo form_input($tag); echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"familia_productos\">Familia de Productos:</label></td><td class='form_input'>"; echo form_dropdown('cproducto_familia_id', $select1, 0, "id='familia_productos'");echo "</td></tr>";

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
