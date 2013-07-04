<style>
    fieldset{
        width: 920px;
        margin: auto;
        margin-bottom: 10px;
    }
</style>

<?php
//Inputs
$tag=array(
		'name'=>'tag',
		'size'=>'30',
		'value'=>'',
		'id'=>'tag',
);
$fecha_alta=array(
		'name'=>'fecha_alta',
		'size'=>'30',
		'readonly'=>'readonly',
		'value'=>''.date('Y-m-d'),
);

//Titulo
echo "<h2>$title</h2>";
echo "<div style='width:920px;margin:auto' align=\"right\">";
echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_unidades_m\" style='margin:10px;'><img src=\"".base_url()."images/factura.png\" width=\"30px\" title=\"Listado de Unidades de Medida\">Listado de Unidades</a>";
echo "<a href=\"".base_url()."index.php/almacen/almacen_reportes/formulario/rep_unidades_m\"><img src=\"".base_url()."images/adobereader.png\" width=\"30px\" title=\"Reporte de Unidades de Medidas\">Reporte de unidades</a></div>";

//Load validacion
$this->load->view('validation_view');

//Abrir Formulario
$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta.'/trans/alta_unidad_m', $atrib) . "\n";
echo form_fieldset('<b>Datos de la Unidad de Medida</b>') . "\n";
//Mensajes de validacion
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';

echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";

//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"tag\">Nombre de la Unidad de Medida:</label></td><td class='form_input'>"; echo form_input($tag); echo "</td><td class='form_tag'><label for=\"fecha_alta\">Fecha de Alta:</label></td><td class='form_input'>"; echo form_input($fecha_alta); echo "</td></tr>";

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