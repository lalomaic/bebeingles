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
//Opciones
echo "<div style='text-align:right;margin-right:50px;margin-top:5px'>";
echo "<a style='margin-right:5px;' href=\"".base_url()."index.php/".$ruta."/contabilidad_reportes/formulario/rep_pr_formas_pago\"><img src=\"".base_url()."images/bitacora.png\" width=\"30px\" title=\"Reporte de Formas de Pago\"> Reporte</a>";
echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_pr_formas_pago\"><img src=\"".base_url()."images/factura.png\" width=\"30px\" title=\"Listado de Formas de Pago\"> Listado</a>";
echo "</div>";
//Load validacion
$this->load->view('validation_view');

//Abrir Formulario
$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta.'/trans/alta_pr_forma_pago', $atrib) . "\n";
echo form_fieldset('<b>Datos de la Forma de Pago</b>') . "\n";
//Mensajes de validacion
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';

echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";


//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"tag\">Forma de Pago:</label></td><td class='form_input'>"; echo form_input($tag); echo "</td></tr>";

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