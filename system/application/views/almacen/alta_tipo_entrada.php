<style>
    fieldset{
        width: 900px;
        margin: auto;
        margin-bottom: 30px;
    }
</style>

<?php
//Inputs
$tag=array(
		'name'=>'tag',
		'size'=>'40',
		'value'=>'',
		'id'=>'tag',
);

//Titulo
echo "<h2>$title</h2>";
//Link al listado
echo "<div style='width:920px;margin:auto' align=\"right\">";
echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_tipos_entradas\"><img src=\"".base_url()."images/factura.png\" width=\"30px\" title=\"Listado de Tipos de Entrada\">Listado Tipos Entradas</a></div>";

//Load validacion
$this->load->view('validation_view');


//Abrir Formulario
$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta.'/trans/alta_tipo_entrada', $atrib) . "\n";
echo form_fieldset('<b>Datos de Tipo de Entrada</b>') . "\n";
//Mensajes de validacion
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';

echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";


//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"tag\">Nombre del Tipo de Entrada:</label></td><td class='form_input'>"; echo form_input($tag); echo "</td></tr>";

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