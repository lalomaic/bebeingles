<?php
//Inputs
$razon_social=array(
		'name'=>'razon_social',
		'size'=>'70',
		'value'=>'',
		'id'=>'razon_social',
);
$rfc=array(
		'name'=>'rfc',
		'size'=>'15',
		'value'=>'',
		'id'=>'rfc',
);
$domicilio_fiscal=array(
		'name'=>'domicilio_fiscal',
		'cols'=>'80',
		'rows'=>'4',
		'value'=>'',
		'id'=>'domicilio_fiscal',
);
$telefonos=array(
		'name'=>'telefonos',
		'size'=>'30',
		'value'=>'',
		'id'=>'telefonos',
);
$ciudad=array(
		'name'=>'ciudad',
		'size'=>'30',
		'value'=>'',
		'id'=>'ciudad',
);
$estado=array(
		'name'=>'estado',
		'size'=>'30',
		'value'=>'',
		'id'=>'estado',
);
$pais=array(
		'name'=>'pais',
		'size'=>'30',
		'value'=>'',
		'id'=>'pais',
);
//Titulo
echo "<h2>$title</h2>";

//Load validacion
$this->load->view('validation_view');

//Abrir Formulario
$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta.'/trans/alta_empresa', $atrib) . "\n";
echo form_fieldset('<b>Datos Generales de la Empresa</b>') . "\n";
//Mensajes de validacion
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';

echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";


//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"razon_social\">Razon Social:</label></td><td class='form_input'>"; echo form_input($razon_social); echo "</td><td class='form_tag'><label for=\"rfc\">R.F.C.: </label></td><td class='form_input'>"; echo form_input($rfc);echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"domicilio_fiscal\">Domicilio:</label></td><td class='form_input'>"; echo form_textarea($domicilio_fiscal); echo "</td><td class='form_tag'><label for=\"telefonos\">TelÃ©fonos: </label></td><td class='form_input'>"; echo form_input($telefonos);echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"ciudad\">Ciudad:</label></td><td class='form_input'>"; echo form_input($ciudad); echo "</td><td class='form_tag'><label for=\"estado\">Estado: </label></td><td class='form_input'>"; echo form_input($estado);echo "</td></tr>";

echo "<tr></td><td class='form_tag'><label for=\"pais\">PaÃ­s: </label></td><td class='form_input'>"; echo form_input($pais); echo "</td></tr>";

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
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_empresas\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Listado de Usuarios\"></a></div>";

?>