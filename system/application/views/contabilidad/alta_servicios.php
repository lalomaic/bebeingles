<style>
    fieldset{
        width: 920px;
        margin: auto;
        margin-bottom: 10px;
    }
</style>

<?php

//Inputs

$descripcion=array(
		'name'=>'descripcion',
		'size'=>'50',
		'value'=>'',
		'id'=>'descripcion',
);
$tasa_impuesto=array(
		'name'=>'tasa_impuesto',
		'size'=>'5',
		'value'=>'16',
		'id'=>'tasa_impuesto',
);
$precio2=array(
		'name'=>'costo_unitario',
		'size'=>'10',
			'value'=>'',
		'id'=>'costo_unitaario',
);


//Titulo
echo "<h2>$title</h2>";
echo "<div style='width:920px;margin:auto' align=\"right\">";
echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_servicios\"  ><img src=\"".base_url()."images/icons/icons_03.png\" width=\"30px\" title=\"Listado de Servicios\">Listado de Servicios</a></div>";
$this->load->view('js_funciones_generales');
?>
<?php
//Load validacion
$this->load->view('validation_view');
//Abrir Formulario
$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta.'/trans/alta_servicio', $atrib) . "\n";
echo form_fieldset('<b>Nueva Registro de Bancos</b>') . "\n";
//Mensajes de validacion
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';

echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";


//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"tag\">Descripcion:</label>"; echo form_input($descripcion); echo "</td><td class='form_tag'><label for=\"tag\">Tasa de Impuesto:</label></td><td class='form_input'>"; echo form_input($tasa_impuesto); 
echo"</td><td class='form_tag'><label for=\"tag\">Costo Unitario:</label></td><td class='form_input'>"; echo form_input($precio2); echo"</td></tr>";


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
