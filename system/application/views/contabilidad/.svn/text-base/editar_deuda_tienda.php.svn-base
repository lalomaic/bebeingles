<?php
//Inputs
$monto_deuda=array(
		'name'=>'monto_deuda',
		'size'=>'15',
		'value'=>"$row->monto_deuda",
		'class'=>"subtotal"
);
if($row->fecha!='0000-00-00'){
	$fechaa=explode("-", $row->fecha);
	$fechai=$fechaa[2]." ".$fechaa[1]." ".$fechaa[0];
} else {
	$fecha1="";
}
$fecha=array(
		'name'=>'fecha',
		'class'=>'date_input',
		'size'=>'12',
		'value'=>"$fechai",
		'onlyread'=>'onlyread'
);
$hora=array(
		'name'=>'hora',
		'size'=>'7',
		'value'=>''.$row->hora,
);

$concepto=array(
		'name'=>'concepto',
		'rows'=>'4',
		'cols'=>'60',
		'value'=>''.$row->concepto,
);

?>
<script>
	$(document).ready(function() {
		$($.date_input.initialize);
	});
	function enviar(){
		if($("#ctipo_gasto_id").val()==0){
			alert("Seleccionar el tipo de gasto");
		} else {
			document.form1.submit();
		}
	}
</script>
<?php
//Titulo
echo "<h2>$title</h2>";
//Abrir Formulario
$atrib=array('name'=>'form1');
echo form_open($ruta.'/trans/alta_deuda_tienda/form', $atrib) . "\n";
echo form_fieldset('<b>Datos del Gasto Realizado</b>') . "\n";
echo "<table  class='form_table'>";
$img_row="".base_url()."images/table_row.png";

//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"espacios_fisicos_debe_id\">Tienda que adeuda:</label></td><td class='form_tag'>"; echo form_dropdown('espacio_fisico_debe_id', $tiendas, $row->espacio_fisico_debe_id); echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"espacios_fisicos_recibe_id\">Tienda a la que se le debe:</label></td><td class='form_tag'>"; echo form_dropdown('espacio_fisico_recibe_id', $tiendas, $row->espacio_fisico_recibe_id); echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"ctipo_deuda_tienda_id\">Concepto:</label></td><td class='form_tag'>"; echo form_dropdown('ctipo_deuda_tienda_id', $conceptos, $row->ctipo_deuda_tienda_id); echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"fecha\">Fecha del Prestamo:</label></td><td class='form_tag'>"; echo form_input($fecha); echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"monto_deuda\">Monto del Prestamo:</label></td><td class='form_tag'>"; echo form_input($monto_deuda); echo "</td></tr>";

echo "<tr><td colspan='2' class=\"form_buttons\">";
echo form_hidden('id', $row->id);
echo form_reset('form1', 'Borrar');
echo "<button type='button' onclick=\"window.location='".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_gastos_tiendas'\">Cerrar sin guardar</button>";

//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
	echo '<button type="button" onclick="javascript:enviar()">Guardar Registro</button>';
}
echo '</td></tr></table>';
echo form_fieldset_close();
echo form_close();
//Link al listado
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_gastos_tiendas\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Listado de Cuentas Bancarias\"></a></div>";

?>
