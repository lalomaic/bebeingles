<?php
foreach($marca->all as $reg){
	//Construir Estatus
	$select1[0]="Elija";
	foreach($estatus->all as $row){
		$y=$row->id;
		$select1[$y]=$row->tag;
	}


	//Inputs
	$tag=array(
			'name'=>'tag',
			'size'=>'40',
			'value'=>''.$reg->tag,
			'id'=>'tag',
	);

	//Titulo
	echo "<h2>$title</h2>";

	//Load validacion
	$this->load->view('validation_view');


	//Abrir Formulario
	$atrib=array('name'=>'form1','id'=>'form1');
	echo form_open($ruta.'/trans/alta_bancos', $atrib) . "\n";
	echo form_fieldset('<b>Datos Del Banco Registrado</b>') . "\n";
	//Mensajes de validacion
	echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';
	echo "<table width=\"50%\" class='form_table'>";
	$img_row="".base_url()."images/table_row.png";

	//Campos del Formulario
	echo "<tr><td class='form_tag'><label for=\"tag\">Nombre del Banco:</label></td><td class='form_input'>"; echo form_input($tag); echo "</td></tr>";
	echo "<tr><td class='form_tag'><label for=\"estatus\">Estatus del Banco:</label></td><td class='form_input'>"; echo form_dropdown('estatus_general_id', $select1, "$reg->estatus_general_id","id='estatus'");echo "</td></tr>";


	//Cerrar el Formulario
	echo "<tr><td colspan='2' class=\"form_buttons\">";
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
	echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_marcas\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Listado de Marcas de Productos\"></a></div>";
}

?>
