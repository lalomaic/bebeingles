<?php
//Inputs
$monto=array(
		'name'=>'monto',
		'size'=>'15',
		'value'=>'',
);

$fecha=array(
		'name'=>'fecha',
		'class'=>'date_input',
		'size'=>'12',
		'value'=>''.date("d m Y"),
		'onlyread'=>'onlyread'
);
$hora=array(
		'name'=>'hora',
		'size'=>'7',
		'value'=>'',
);

$concepto=array(
		'name'=>'concepto',
		'rows'=>'4',
		'cols'=>'60',
		'value'=>'',
);

?>
<script>
	$(document).ready(function() {
		$($.date_input.initialize);
	});


</script>
<?php
//Titulo
echo "<h2>$title</h2>";
//Abrir Formulario
$atrib=array('name'=>'form1');
echo form_open($ruta.'/trans/act_egresos_tienda', $atrib) . "\n";
echo form_fieldset('<b>Datos del Otro Egreso Realizado</b>') . "\n";
echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";

//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"espacios_fisicos_id\">Sucursal:</label><br/>"; 
echo form_dropdown('espacios_fisicos_id', $espacios, 0); 
echo "</td><td class='form_tag'><label for=\"cotros_egresos_id\">Concepto:</label><br/>"; 
echo form_dropdown('cotros_egresos_id', $catalogo_egresos, 0); 
echo "</td><td class='form_tag'><label for=\"fecha\">Fecha del Egreso:</label><br/>"; 
echo form_input($fecha); 
echo "</td><td class='form_tag'><label for=\"hora\">Hora del Egreso (24 hrs)</label><br/>"; 
echo form_input($hora); 
echo "Ej. 16:00, 13:20";
echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_egresos_tienda\" ><img src=\"".base_url()."images/factura.png\" width=\"30px\" title=\"Lista de Egresos\" align='right'></a></td></tr>";

echo "<tr><td class='form_tag'><label for=\"monto\">Monto del Egreso:</label><br/>"; echo form_input($monto); echo "</td><td class='form_tag' colspan='3'><label for=\"concepto\">Descripción del Egreso:</label><br/>"; echo form_textarea($concepto); echo "</td></tr>";


echo "<tr><td colspan='4' class=\"form_buttons\">";
echo form_reset('form1', 'Borrar');
echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Cerrar sin guardar</button>";

//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
	echo '<button type="submit">Guardar Registro</button>';
}
echo '</td></tr></table>';
echo form_fieldset_close();
echo form_close();
//Link al listado
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_egresos_tienda\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Listado de Otros Egresos\"></a></div>";

?>
