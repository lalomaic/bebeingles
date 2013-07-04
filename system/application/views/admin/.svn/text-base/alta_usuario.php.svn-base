<?php
//Construir Empresas
$select1[0]="Elija";
if($empresas!=false){
	foreach($empresas->all as $row){
		$y=$row->id;
		$select1[$y]=$row->razon_social;
	}
}
//print_r(array_values($select1));

//Construir Espacios Fisicos
$select2[0]="Elija";
if($espacios_fisicos!=false){
	foreach($espacios_fisicos->all as $row){
		$y=$row->id;
		$select2[$y]=$row->clave."-".$row->tag;
	}
}

//Construir Grupo
$select3[0]="Elija";
if($grupos!=false){
	foreach($grupos->all as $row){
		$y=$row->id;
		$select3[$y]=$row->nombre;
	}
}

//Construir Puesto
$select4[0]="Elija";
if($puestos!=false){
	foreach($puestos->all as $row){
		$y=$row->id;
		$select4[$y]=$row->tag;
	}
}

//Inputs
$nombre=array(
		'name'=>'nombre',
		'size'=>'40',
		'value'=>'',
		'id'=>'nombre',
);

$username=array(
		'name'=>'username',
		'size'=>'30',
		'value'=>'',
);

$email=array(
		'name'=>'email',
		'size'=>'30',
		'value'=>'',
		'id'=>'email',
);

$domicilio=array(
		'name'=>'domicilio',
		'cols'=>'50',
		'rows'=>'4',
		'value'=>'',
);

$telefono=array(
		'name'=>'telefono',
		'size'=>'40',
		'value'=>'',
);
?>
<script>
 jQuery(document).ready(function() {
    jQuery("select").AddIncSearch({
        maxListSize: 200,
        maxMultiMatch: 100,
        warnMultiMatch: 'top {0} matches ...',
        warnNoMatch: 'no encontrado ...'
    });
 });
</script>
<?php

//Array de Campos con ValidaciÃ³n
//Titulo
echo "<h2>$title</h2>";

//Load validacion
$this->load->view('validation_view');

//Abrir Formulario
$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta.'/trans/alta_usuario', $atrib) . "\n";
echo form_fieldset('<b>Datos Generales</b>') . "\n";
//Mensajes de validacion
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';

echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";

//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"empresas_id\">Empresa contratante:</label></td><td class='form_input'>"; echo form_dropdown('empresas_id', $select1,0, "id='empresa'");echo "</td><td class='form_tag'><label for=\"espacios_fisicos_id\">Espacio Fí­sico asignado: </label></td><td class='form_input'>"; echo form_dropdown('espacio_fisico_id', $select2, 0, "id='espacio'");echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"grupo_id\">Grupo:</label></td><td class='form_input'>"; echo form_dropdown('grupo_id', $select3,0, "id='grupo'");echo "</td><td class='form_tag'><label for=\"puesto_id\">Puesto asignado: </label></td><td class='form_input'>"; echo form_dropdown('puesto_id', $select4,0, "id='puesto'");echo "</td></tr>";


echo "<tr><td class='form_tag'><label for=\"nombre\">Nombre Completo:</label></td><td class='form_input'>"; echo form_input($nombre); echo "</td><td class='form_tag'><label for=\"username\">Nombre de usuario: </label></td><td class='form_input'>"; echo form_input($username);echo "<span id='validate_user'></span></td></tr>";

echo "<tr><td class='form_tag'><label for=\"telefono\">Telefono:</label></td><td class='form_input'>"; echo form_input($telefono); echo "</td><td class='form_tag'><label for=\"email\">Correo electrónico: </label></td><td class='form_input'>"; echo form_input($email);echo "</td></tr>";

echo "<tr><td class='form_tag' ><label for=\"domicilio\">Domicilio Completo:</label></td><td class='form_input' colspan='3'>"; echo form_textarea($domicilio); echo "</td></tr>";


//Cerrar el Formulario
echo "<tr><td colspan='4' class=\"form_buttons\">";
echo form_reset('form1', 'Borrar');
echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Cerrar sin guardar</button>";


//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
	echo '<button type="submit" id="submit">Guardar Registro</button>';
}
echo '</td></tr></table>';
echo form_close();
echo form_fieldset_close();

//Link al listado
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_usuarios\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Listado de Usuarios\"></a></div>";

?>