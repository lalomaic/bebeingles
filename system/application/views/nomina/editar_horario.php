
<?php
$tag=array(
	'name'=>'tag',
	'size'=>'30',
	'value'=>$reg->tag,
	'id'=>'tag',
	);
$hmin=(int)0;
$hmax=(int)23;
$mmin=(int)0;
$mmax=(int)55;
$opts_h=$opts_m=array();
for($h=$hmin;$h<=$hmax;$h+=1)
	{
	$hora=sprintf("%02d",$h);
	$opts_h[$hora]=$hora;
	}
for($m=$mmin;$m<=$mmax;$m+=5)
	{
	$hora=sprintf("%02d",$m);
	$opts_m[$hora]=$hora;
	}
# entrada
$entrada_h=form_dropdown('entrada_h', $opts_h,substr($reg->entrada, 0, 2));
$entrada_m=form_dropdown('entrada_m', $opts_m,substr($reg->entrada, 3, 2));
# salida
$salida_h=form_dropdown('salida_h', $opts_h,substr($reg->salida, 0, 2));
$salida_m=form_dropdown('salida_m', $opts_m,substr($reg->salida, 3, 2));

//Titulo
echo "<h2>$title</h2>";

//Load validacion
$this->load->view('validation_view');

//Abrir Formulario
$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta.'/horario_c/alta_horario', $atrib) . "\n";
echo form_fieldset('<b>Datos del horario</b>') . "\n";
//Mensajes de validacion
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';

echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";
echo form_hidden('id',$reg->id);
?>
<tr>
	<td class='form_tag'>Nombre:</td>
	<td class="form_input"><?php echo form_input($tag);?></td>
	<td class='form_tag'>Entrada:</td>
	<td class="form_input"><?php echo $entrada_h;?>:<?php echo $entrada_m;?> Hrs.</td>
	<td class='form_tag'>Salida:</td>
	<td class="form_input"><?php echo $salida_h;?>:<?php echo $salida_m;?> Hrs.</td>
</tr>
<?php

//Cerrar el Formulario
echo "<tr><td colspan='6' class=\"form_buttons\">";
echo form_reset('form1', 'Borrar');
echo "<button type='button' onclick=\"window.location='".site_url('/'.$ruta.'/horario_c/formulario/list_horarios')."'\">Cerrar sin guardar</button>";
echo form_close();

//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
echo '<button type="submit">Guardar Registro</button>';
}
echo '</td></tr></table>';
echo form_fieldset_close();

?>
<a href="<?php echo site_url('/'.$ruta.'/horario_c/formulario/list_horarios')?>" title="Listado de horarios">
<img src="<?php echo base_url()."/images/adduser.png"?>" width=50 height=50 alt="Listado de horarios" align="absmiddle">
</a>
