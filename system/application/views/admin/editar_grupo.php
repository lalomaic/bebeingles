<?php
foreach($grupo->all as $row){
	//Inputs
	$nombre=array(
			'name'=>'nombre',
			'size'=>'30',
			'id'=>'nombre',
			'value'=>''.$row->nombre,
	);
	$descripcion=array(
			'name'=>'descripcion',
			'size'=>'30',
			'id'=>'descripcion',
			'value'=>''.$row->descripcion,
	);

	//Titulo
	echo "<h2>$title</h2>";


	//Load validacion
	$this->load->view('validation_view');

	//Abrir Formulario
	$atrib=array('name'=>'form1','id'=>'form1');
	echo form_open($ruta.'/trans/act_grupo', $atrib) . "\n";
	echo form_fieldset('<b>Grupos</b>') . "\n";
	//Mensajes de validacion
	echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';

	echo "<table width=\"80%\" class='form_table' border='1'>";
	$img_row="".base_url()."images/table_row.png";


	//Campos del Formulario
	echo "<tr><td class='form_tag'><label for=\"tag\">Nombre del Grupo:</label></td><td class='form_input'>"; echo form_input($nombre);"</td></tr>";

	echo "<tr><td class='form_tag'><label for=\"descripcion\">DescripciÃ³n:</label></td><td class='form_input'>"; echo form_input($descripcion);"</td></tr>";


	//Cerrar el Formulario
	echo "<tr><td colspan='2' class=\"form_buttons\">";
	echo form_hidden("id","$row->id");
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
	echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_grupos\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Listado de Grupos\"></a></div>";
}
?>