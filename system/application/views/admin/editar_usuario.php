<?php
//Construir Empresas
foreach ($usuario->all as $data1){
	$select1[0]="Elija";
	foreach($empresas->all as $row){
		$y=$row->id;
		$select1[$y]=$row->razon_social;
	}
	//Construir Espacios Fisicos
	$select2[0]="Elija";
	foreach($espacios_fisicos->all as $row){
		$y=$row->id;
		$select2[$y]=$row->clave."-".$row->tag;
	}

	//Inputs
	$nombre=array(
			'name'=>'nombre',
			'size'=>'40',
			'value'=>''.$data1->nombre,
	);

	$username=array(
			'name'=>'username',
			'size'=>'30',
			'value'=>''.$data1->username,
	);
	$email=array(
			'name'=>'email',
			'size'=>'30',
			'value'=>''.$data1->email,
	);
	$domicilio=array(
			'name'=>'domicilio',
			'cols'=>'50',
			'rows'=>'4',
			'value'=>''.$data1->domicilio,
	);

	$telefono=array(
			'name'=>'telefono',
			'size'=>'40',
			'value'=>''.$data1->telefono,
	);

	//Titulo
	echo "<h2>$title</h2>";


	//Abrir Formulario
	$atrib=array('name'=>'form1');
	echo form_open($ruta.'/trans/act_usuario', $atrib) . "\n";
	echo form_fieldset('<b>Datos Generales</b>') . "\n";
	echo "<table width=\"80%\" class='form_table'>";
	$img_row="".base_url()."images/table_row.png";


	//Campos del Formulario
	echo "<tr><td class='form_tag'><label for=\"empresas_id\">Empresa contratante:</label></td><td class='form_input'>"; echo form_dropdown('empresas_id', $select1, $data1->empresas_id);echo "</td><td class='form_tag'><label for=\"espacios_fisicos_id\">Espacio FÃ­Â­sico asignado: </label></td><td class='form_input'>"; echo form_dropdown('espacio_fisico_id', $select2, $data1->espacio_fisico_id);echo "</td></tr>";

	echo "<tr><td class='form_tag'><label for=\"nombre\">Nombre Completo:</label></td><td class='form_input'>"; echo form_input($nombre); echo "</td><td class='form_tag'><label for=\"username\">Nombre de usuario: </label></td><td class='form_input'>"; echo form_input($username);echo "<span id='validate_user'></span></td></tr>";

	echo "<tr><td class='form_tag'><label for=\"telefono\">TelÃ©fono:</label></td><td class='form_input'>"; echo form_input($telefono); echo "</td><td class='form_tag'><label for=\"email\">Correo electrÃ³nico: </label></td><td class='form_input'>"; echo form_input($email);echo "</td></tr>";

	echo "<tr><td class='form_tag' ><label for=\"domicilio\">Domicilio Completo:</label></td><td class='form_input' colspan='3'>"; echo form_textarea($domicilio); echo "</td></tr>";


	//Cerrar el Formulario
	echo "<tr><td colspan='4' class=\"form_buttons\">";
	echo form_hidden('id', "$data1->id");
	echo form_reset('form1', 'Borrar');
	echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Cerrar sin guardar</button>";
	echo form_close();

	//Permisos de Escritura byte 1
	if(substr(decbin($permisos), 2, 1)==1){
		echo '<button type="submit">Guardar Registro</button>';
	}
	echo '</td></tr></table>';
	echo form_fieldset_close();

	//Link al listado
	echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_usuarios\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Listado de Usuarios\"></a></div>";
}
?>