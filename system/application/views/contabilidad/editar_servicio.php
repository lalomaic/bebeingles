<?php
foreach($marca->all as $reg){



	//Inputs
	$tag=array(
			'name'=>'descripcion',
			'size'=>'40',
			'value'=>''.$reg->descripcion,
			'id'=>'descripcion',
	);
	
		$precio=array(
			'name'=>'costo_unitario',
			'size'=>'40',
			'value'=>''.$reg->costo_unitario,
			'id'=>'costo_unitario',
	);
		$iva=array(
			'name'=>'tasa_impuesto',
			'size'=>'40',
			'value'=>''.$reg->tasa_impuesto,
			'id'=>'tasa_impuesto',
	);

	//Titulo
	echo "<h2>$title</h2>";

	//Load validacion

	//Abrir Formulario
	$atrib=array('name'=>'form1','id'=>'form1');
	echo form_open($ruta.'/trans/alta_servicio', $atrib) . "\n";
	echo form_fieldset('<b>Datos Del Banco Registrado</b>') . "\n";
	//Mensajes de validacion
	echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';
	echo "<table width=\"50%\" class='form_table'>";
	$img_row="".base_url()."images/table_row.png";

	//Campos del Formulario
	echo "<tr><td class='form_tag'><label for=\"tag\">Descripcion:</label>"; echo form_input($tag); echo "</td><td class='form_tag'><label for=\"tag\">Tasa de Impuesto:</label></td><td class='form_input'>"; echo form_input($iva); 
echo"</td><td class='form_tag'><label for=\"tag\">Costo Unitario:</label></td><td class='form_input'>"; echo form_input($precio); echo"</td></tr>";


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
	echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_servicios\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Listado de Marcas de Productos\"></a></div>";
}

?>