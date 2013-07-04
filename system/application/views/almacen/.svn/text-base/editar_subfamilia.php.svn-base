<?php
foreach($subfamilia->all as $reg){
	//Construir Familia de Productos
	$select1[0]="Elija";
	foreach($familia_productos->all as $row){
		$y=$row->id;
		$select1[$y]=$row->tag;
	}
	//print_r(array_values($select1));

	//Construir Estatus
	$select2[0]="Elija";
	foreach($estatus->all as $row){
		$y=$row->id;
		$select2[$y]=$row->tag;
	}

	//Inputs
	$tag=array(
			'name'=>'tag',
			'size'=>'30',
			'value'=>''.$reg->tag,
			'id'=>'tag',
	);

	$clave=array(
			'name'=>'clave',
			'size'=>'10',
			'value'=>''.$reg->clave,
			'id'=>'clave',
	);
	$fecha_alta=array(
			'name'=>'fecha_alta',
			'size'=>'30',
			'value'=>''.$reg->fecha_alta,
	);

	//Titulo
	echo "<h2>$title</h2>";

	//Load validacion
	$this->load->view('validation_view');


	//Abrir Formulario
	$atrib=array('name'=>'form1','id'=>'form1');
	echo form_open($ruta.'/trans/act_subfamilia', $atrib) . "\n";
	echo form_fieldset('<b>Datos de la Subfamilia de Productos</b>') . "\n";
	//Mensajes de validacion
	echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';

	echo "<table width=\"80%\" class='form_table'>";
	$img_row="".base_url()."images/table_row.png";


	//Campos del Formulario
	echo "<tr><td class='form_tag'><label for=\"clave\">Clave:</label></td><td class='form_input'>"; echo form_input($clave); echo "</td><td class='form_tag'><label for=\"tag\">Nombre de la Subfamilia de Productos:</label></td><td class='form_input'>"; echo form_input($tag); echo "</td><td class='form_tag'><label for=\"fecha_alta\">Fecha de Alta:</label></td><td class='form_input'>"; echo form_input($fecha_alta); echo "</td></tr>";

	echo "<tr><td class='form_tag'><label for=\"familia_productos\">Familia de Productos:</label></td><td class='form_input'>"; echo form_dropdown('familia_id', $select1, "$reg->cproducto_familia_id", "id='familia_productos'");echo "</td><td class='form_tag'><label for=\"estatus\">Estatus de la Familia de Productos:</label></td><td class='form_input'>"; echo form_dropdown('estatus_general_id', $select2, "$reg->estatus_general_id", "id='estatus'");echo "</td></tr>";


	//Cerrar el Formulario
	echo "<tr><td colspan='6' class=\"form_buttons\">";
	echo form_reset('form1', 'Borrar');
	echo form_hidden('id', "$reg->id");
	echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Cerrar sin guardar</button>";
	echo form_close();

	//Permisos de Escritura byte 1
	if(substr(decbin($permisos), 0, 1)==1){
		echo '<button type="submit">Guardar Registro</button>';
	}
	echo '</td></tr></table>';
	echo form_fieldset_close();

	//Link al listado
	echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_subfamilias\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Listado de Subfamilias de Productos\"></a></div>";
}

?>