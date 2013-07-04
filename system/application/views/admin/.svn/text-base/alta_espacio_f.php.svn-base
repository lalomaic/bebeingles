<?php
//Construir Empresas
$select1[0]="Elija";
foreach($empresas->all as $row){
	$y=$row->id;
	$select1[$y]=$row->razon_social;
}

//Construir Tipos_Espacios
$select2[0]="Elija";
foreach($tipos_espacios->all as $row){
	$y=$row->id;
	$select2[$y]=$row->tag;
}

//Construir SubTipos_Espacios
$select2s[0]="Elija";
foreach($subtipos_espacios->all as $row){
	$y=$row->id;
	$select2s[$y]=$row->tag;
}

//Construir Estados
$matriz[0]="Elija";
foreach($espacios_fisicos->all as $row){
	$y=$row->id;
	$matriz[$y]=$row->tag;
}


//Inputs
$tag=array(
		'name'=>'tag',
		'size'=>'30',
		'id'=>'tag',
		'value'=>'',
);

$clave=array(
		'name'=>'clave',
		'size'=>'10',
		'id'=>'clave',
		'value'=>'',
);
$localidad=array(
		'name'=>'localidad',
		'size'=>'30',
		'id'=>'localidad',
		'value'=>'',
);
$municipio=array(
		'name'=>'municipio',
		'size'=>'30',
		'id'=>'municipio',
		'value'=>'',
);
$estado=array(
		'name'=>'estado',
		'size'=>'30',
		'id'=>'estado',
		'value'=>'',
);

//Calle
$calle=array(
		'name'=>'calle',
		'size'=>'30',
		'id'=>'calle',
		'value'=>'',
);
//Numero_exterior
$numero_exterior=array(
		'name'=>'numero_exterior',
		'size'=>'5',
		'id'=>'numero_exterior',
		'value'=>'',
);

//colonia
$colonia=array(
		'name'=>'colonia',
		'size'=>'30',
		'id'=>'colonia',
		'value'=>'',
);
//codigo_postal
$codigo_postal=array(
		'name'=>'codigo_postal',
		'size'=>'30',
		'id'=>'codigo_postal',
		'value'=>'',
);
//rfc
$rfc=array(
		'name'=>'rfc',
		'size'=>'30',
		'id'=>'rfc',
		'value'=>'',
);
//razon_social
$razon_social=array(
		'name'=>'razon_social',
		'size'=>'30',
		'id'=>'razon_social',
		'value'=>'',
);
 
$telefono=array(
		'name'=>'telefono',
		'size'=>'20',
		'id'=>'telefono',
		'value'=>'',
);

$porcentaje_compra=array(
		'name'=>'porcentaje_compra',
		'size'=>'5',
		'id'=>'telefono',
		'title'=>'Valores numericos 0-100',
		'value'=>'65',
);

//Titulo
echo "<h2>Edición del Espacio Físico</h2>";

//Load validacion
$this->load->view('validation_view');

//Abrir Formulario
$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta.'/trans/act_espacio_f', $atrib) . "\n";
echo form_fieldset('<b>Datos del Local</b>') . "\n";
//Mensajes de validacion
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';

echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";

//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"empresas_id\">Empresa:</label><br/>"; echo form_dropdown('empresas_id', $select1, 1,"id='empresa'");echo "</td><td class='form_tag'><label for=\"tipo_espacio_id\">Tipo de Local: </label><br/>"; echo form_dropdown('tipo_espacio_id', $select2, 0,"id='tipo_espacio'");echo "</td><td class='form_tag'><label for=\"subtipo_espacio_id\">Subtipo de Local: </label><br/>"; echo form_dropdown('subtipo_espacio_id', $select2s, 0,"id='subtipo_espacio'"); echo "</td></tr>";


echo "<tr><td class='form_tag'><label for=\"razon_social\">Razón Social:</label><br/>"; echo form_input($razon_social); echo "</td><td class='form_tag'><label for=\"tag\">Nombre del Local:</label><br/>"; echo form_input($tag); echo "</td><td class='form_tag'><label for=\"clave\">Clave del Local:</label><br/>"; echo form_input($clave); echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"calle\">Calle:</label><br/>"; echo form_input($calle); echo "</td><td class='form_tag'><label for=\"numero_exterior\">Número Ext.:</label><br/>"; echo form_input($numero_exterior); echo "</td><td class='form_tag'><label for=\"colonia\">Colonia:</label><br/>"; echo form_input($colonia); echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"localidad\">Localidad:</label><br/>"; echo form_input($localidad); echo "</td><td class='form_tag'><label for=\"municipio\">Municipio.:</label><br/>"; echo form_input($municipio); echo "</td><td class='form_tag'><label for=\"estado\">Estado:</label><br/>"; echo form_input($estado); echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"codigo_postal\">Código Postal:</label><br/>"; echo form_input($codigo_postal); echo "</td><td class='form_tag'><label for=\"telefono\">Teléfono:</label><br/>"; echo form_input($telefono); echo "</td><td class='form_tag'><label for=\"porcentaje_compra\">Porcentaje de Compra del Lote 0:</label><br/>"; echo form_input($porcentaje_compra); echo "%</td></tr>";

echo "<tr><td class='form_tag'><label for=\"espacio_fisico_matriz_id\">Matriz del Espacio Físico actual:</label><br/>"; echo form_dropdown('espacio_fisico_matriz_id', $matriz, 0,"id='espacio_fisico_matriz_id'"); echo "</td><td class='form_tag'><label for=\"rfc\">R.F.C.:</label><br/>"; echo form_input($rfc); echo "</td></tr>";

//Cerrar el Formulario
echo "<tr><td colspan='6' class=\"form_buttons\">";
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
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_espacios_f\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Listado de Espacios Físicos\"></a></div>";
?>