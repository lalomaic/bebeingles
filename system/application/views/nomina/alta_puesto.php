
<?php
$tag=array(
	'name'=>'tag',
	'size'=>'30',
	'value'=>'',
	'id'=>'tag',
	);
//Titulo
echo "<h2>$title</h2>";
//Load validacion
$this->load->view('validation_view');
//Abrir Formulario
$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta.'/puesto_c/alta_puesto', $atrib) . "\n";
echo form_fieldset('<b>Datos del puesto</b>') . "\n";

// crear array de prestaciones
$ncols_p=2;# numero de columnas
$faltan=((count($prestaciones) % $ncols_p)==0)?0:($ncols_p-(count($prestaciones) % $ncols_p));
$nrows_p=(int)(count($prestaciones)/$ncols_p);
if($faltan>0)
	$nrows_p++;
$arr_prestaciones=array();
foreach($prestaciones as $prestacion)
	$arr_prestaciones[]="<label><input type='checkbox' name='prestaciones[p{$prestacion->id}]'>&nbsp;{$prestacion->tag}</label><br>&nbsp;&nbsp;$ <input type='text' value='0.00' name='pc{$prestacion->id}' size='8'>";
while((count($arr_prestaciones) % $ncols_p) > 0)
	$arr_prestaciones[]="&nbsp;";
// crear array de deducciones
$ncols_d=2;# numero de columnas
$faltan=((count($deducciones) % $ncols_d)==0)?0:($ncols_d-(count($deducciones) % $ncols_d));
$nrows_d=(int)(count($deducciones)/$ncols_d);
if($faltan>0)
	$nrows_d++;
$arr_deducciones=array();
foreach($deducciones as $deduccion)
	$arr_deducciones[]="<label><input type='checkbox' name='deducciones[d{$deduccion->id}]'>&nbsp;{$deduccion->tag}</label><br>&nbsp;&nbsp;$ <input type='text' value='0.00' name='dc{$deduccion->id}' size='8'>";
while((count($arr_deducciones) % $ncols_d) > 0)
	$arr_deducciones[]="&nbsp;";

//Mensajes de validacion
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';
echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";
?>
<tr>
	<td class='form_tag'>Puesto:</td>
	<td class="form_input"><?php echo form_input($tag);?></td>
</tr>
<tr>
	<td class='form_tag'>Prestaciones:</td>
	<td class="form_input">
	<table border="0" cellspacing="6" cellpadding="4" align=left>
	<?php
	for($x=0;$x<$nrows_p;$x++)
		{
		echo '<tr>';
		for($y=0;$y<$ncols_p;$y++)
			echo "<td>{$arr_prestaciones[($ncols_p*$x)+$y]}</td>";
		}
		echo '</tr>'
	?>
	</table>
	</td>
</tr>
<tr>
	<td class='form_tag'>Deducciones:</td>
	<td class="form_input">
	<table border="0" cellspacing="6" cellpadding="4" align=left>
	<?php
	for($x=0;$x<$nrows_d;$x++)
		{
		echo '<tr>';
		for($y=0;$y<$ncols_d;$y++)
			echo "<td>{$arr_deducciones[($ncols_d*$x)+$y]}</td>";
		}
		echo '</tr>'
	?>
	</table>
	</td>
</tr>
<?php
//Cerrar el Formulario
echo "<tr><td colspan='6' class=\"form_buttons\">";
echo form_reset('form1', 'Borrar');
echo "<button type='button' onclick=\"window.location='".site_url('/'.$ruta.'/puesto_c/formulario/list_puestos')."'\">Cerrar sin guardar</button>";
echo form_close();
//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
echo '<button type="submit">Guardar Registro</button>';
}
echo '</td></tr></table>';
echo form_fieldset_close();
?>
<a href="<?php echo site_url('/'.$ruta.'/puesto_c/formulario/list_puestos')?>" title="Listado de puestos">
<img src="<?php echo base_url()."/images/adduser.png"?>" width=50 height=50 alt="Listado de puestos" align="absmiddle">
</a>
