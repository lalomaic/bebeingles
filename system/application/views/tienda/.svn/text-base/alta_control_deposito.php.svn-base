<?php 
//Ubicaciones
$select1[0]="Elija";
if($bancos!= false){
	foreach($bancos->all as $row){
		$y=$row->id;
		$select1[$y]=$row->banco. " - ".$row->numero_cuenta;
	}
}

$fecha_deposito=array(
		'class'=>'date_input',
		'name'=>'fecha_deposito',
		'id'=>'fecha_deposito',
		'size'=>'10',
		'readonly'=>'readonly'
);

$hora="";
for($x=8;$x<22;$x++){
	$hora.="<option value='$x'>$x</option>";
}
$min="";
for($x=0;$x<60;$x++){
	$min.="<option value='$x'>$x</option>";
}

$fecha_venta=array(
		'class'=>'date_input',
		'name'=>'fecha_venta',
		'id'=>'fecha_venta',
		'size'=>'10',
		'readonly'=>'readonly'
);
$cantidad=array(
		'class'=>'subtotal',
		'name'=>'cantidad',
		'id'=>'cantidad',
		'size'=>'20',
		'value'=>'0'
);
$referencia=array(
		'name'=>'referencia',
		'id'=>'referencia',
		'size'=>'20',
		'value'=>''
);
$nombre_empleado=array(
		'name'=>'nombre_empleado',
		'id'=>'nombre_empleado',
		'size'=>'40',
		'value'=>''
);
$this->load->view('validation_view');
$this->load->view('tienda/js_alta_control_deposito');

//Titulo
echo "<h2>$title</h2>";
//Abrir Formulario
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';
$atrib=array('name'=>'form1', 'id'=>"form1");
echo form_open($ruta.'/trans/alta_control_deposito', $atrib) . "\n";
echo form_fieldset('<b>Datos del Dep�sito</b>') . "\n";
echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";

echo "<tr><td class='form_tag'><label for=\"cuenta_bancaria_id\">Cuenta bancaria: </label></td><td>"; echo form_dropdown('cuenta_bancaria_id', $select1, "0", "id='cuenta'");echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"fecha_deposito\">Fecha Dep�sito:</label><br/>"; echo form_input($fecha_deposito); echo "</td><td class='form_tag'><label for=\"hora_deposito\">Hora Dep�sito:</label><br/><select name='hora'>$hora</select>Hora<select name='min'>$min</select>min</td></tr>";

echo "<tr><td class='form_tag'><label for=\"fecha_venta\">Fecha de venta de la cantidad depositada:</label><br/>"; echo form_input($fecha_venta); echo "</td><td class='form_tag'><label for=\"cantidad\">Cantidad Depositada:</label><br/>"; echo form_input($cantidad); echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"referencia\">Referencia del dep�sito:</label><br/>"; echo form_input($referencia); echo "</td><td class='form_tag'><label for=\"nombre_empleado\">Nombre del Empleado:</label><br/>"; echo form_input($nombre_empleado); echo "</td></tr>";
//Cerrar el Formulario
echo "<tr><td class=\"form_buttons\"></td><td class=\"form_buttons\">";
echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Men�</button>";
//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
	echo '<button type="submit">Guardar</button>';
}
echo '</td></tr></table>';
echo form_fieldset_close();
echo form_close();
?>