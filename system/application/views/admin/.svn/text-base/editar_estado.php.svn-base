<?php
foreach($estado->all as $row){
	//Inputs
	$tag=array(
			'name'=>'tag',
			'size'=>'50',
			'id'=>'tag',
			'value'=>''.$row->tag,
	);

	//Titulo
	echo "<h2>$title</h2>";

	//Load validacion
	$this->load->view('validation_view');

	//Abrir Formulario
	$atrib=array('name'=>'form1','id'=>'form1');
	echo form_open($ruta.'/trans/act_estado', $atrib) . "\n";
	echo form_fieldset('<b>Estados</b>') . "\n";
	//Mensajes de validacion
	echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';

	echo "<table width=\"80%\" class='form_table'>";
	$img_row="".base_url()."images/table_row.png";


	//Campos del Formulario
	echo "<tr><td class='form_tag'><label for=\"tag\">Nombre del Estado:</label></td><td class='form_input'>"; echo form_input($tag);"</td></tr>";


	//Cerrar el Formulario
	echo "<tr><td colspan='2' class=\"form_buttons\">";
	echo form_hidden("id","$row->id");
	echo form_reset('form1', 'Borrar');
	echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Cerrar sin guardar</button>";
	//Permisos de Escritura byte 1
	if(substr(decbin($permisos), 0, 1)==1){
		echo '<button type="submit">Guardar Registro</button>';
	}
	echo '</td></tr></table>';
	echo form_close();
	echo form_fieldset_close();

	//Link al listado
	echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_estados\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Listado de Estados\"></a></div>";
}
?>