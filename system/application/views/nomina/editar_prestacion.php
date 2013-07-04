
<?php
$tag=array(
	'name'=>'tag',
	'size'=>'30',
	'value'=>$reg->tag,
	'id'=>'tag',
	);

//Titulo
echo "<h2>$title</h2>";

//Load validacion
$this->load->view('validation_view');

//Abrir Formulario
$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta.'/prestacion_c/alta_prestacion', $atrib) . "\n";
echo form_fieldset('<b>Datos de la prestación</b>') . "\n";
//Mensajes de validacion
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';

echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";
echo form_hidden('id',$reg->id);
?>
<tr>
	<td class='form_tag'>Nombre:</td>
	<td class="form_input"><?php echo form_input($tag);?></td>
</tr>
<?php

//Cerrar el Formulario
echo "<tr><td colspan='6' class=\"form_buttons\">";
echo form_reset('form1', 'Borrar');
echo "<button type='button' onclick=\"window.location='".site_url('/'.$ruta.'/prestacion_c/formulario/list_prestaciones')."'\">Cerrar sin guardar</button>";
echo form_close();

//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
echo '<button type="submit">Guardar Registro</button>';
}
echo '</td></tr></table>';
echo form_fieldset_close();

?>
<a href="<?php echo site_url('/'.$ruta.'/prestacion_c/formulario/list_prestaciones')?>" title="Listado de prestaciones">
<img src="<?php echo base_url()."/images/adduser.png"?>" width=50 height=50 alt="Listado de prestaciones" align="absmiddle">
</a>
