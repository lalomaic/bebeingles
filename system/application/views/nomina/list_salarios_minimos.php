<h2>Listado de Salarios Mínimos</h2>
<?php
$anio=array(
	'name'=>'anio',
	'size'=>'5',
	'value'=>''.date("Y"),
	'class'=>'subtotal',
);
$zona_a=array(
	'name'=>'zona_a',
	'size'=>'5',
	'value'=>'0',
	'class'=>'subtotal',
);
$zona_b=array(
	'name'=>'zona_b',
	'size'=>'5',
	'value'=>'0',
	'class'=>'subtotal',
);

$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta.'/empleado_c/alta_salario_minimo', $atrib) . "\n";
echo form_fieldset('<b>Alta de Nuevo Salario Mínimo</b>') . "\n";
$img_row="".base_url()."images/table_row.png";
?>
<table class='form_table'>
	<tr>
		<th class='form_tag'>Año:</th>
		<th class='form_tag'>Zona A:</th>
		<th class='form_tag'>Zona B:</th>

	</tr><tr>
		<td class="form_input"><?php echo form_input($anio);?></td>
		<td class="form_input"><?php echo form_input($zona_a);?></td>
		<td class="form_input"><?php echo form_input($zona_b);?></td>

	</tr><tr>
		<td colspan='4' class="form_buttons">
<?php
	//Cerrar el Formulario
	echo form_reset('form1', 'Borrar');
	//Permisos de Escritura byte 1
	if(substr(decbin($permisos), 0, 1)==1){
		echo '<button type="submit">Guardar</button>';
	}
	echo '</td></tr></table>';
	echo form_close();
	echo form_fieldset_close();
?>
<div align="center">
	<b>Total de registros: <?php echo $total_registros;?></b>
</div>
<?
if($salarios==false){
		echo "<h2>Sin Salarios Mínimos registrados</h2>"; exit();
}
?>
<table  class="listado" border="0" width="500">
  <tr>
    <th>Id</th>
    <th>Año</th>
    <th>Zona A</th>
    <th>Zona B</th>

    <th>Edición</th>
 </tr>
<?php
$img_row=base_url()."images/table_row.png";
$img_edit=base_url()."images/edit.png";
$img_del=base_url()."images/trash.png";

foreach($salarios as $row) {
	$link_del=$link_edit="";
	$link_edit='<a href="'.site_url("/$ruta/$controller/$funcion/$subfuncion/editar_salario_minimo/".$row->id).'" title="editar"><img src="'.$img_edit.'" height="25px" width="25px"></a>';
	$link_del='<a href="'.site_url("/$ruta/$controller/$funcion/$subfuncion/borrar_salario_minimo/".$row->id).'" title="editar"><img src="'.$img_del.'" height="25px" width="25px"></a>';
	
	echo "<tr background='$img_row' style='height:30px;' align='middle'>\n";
	echo "<td>$row->id</td>\n";
	echo "<td>$row->anio</td>\n";
	echo "<td>$row->zona_a</td>\n";
	echo "<td>$row->zona_b</td>\n";

	echo "<td> &nbsp; </td>\n";
	echo "</tr>\n";
}
echo "</table></div>";
echo $this->pagination->create_links();
?>