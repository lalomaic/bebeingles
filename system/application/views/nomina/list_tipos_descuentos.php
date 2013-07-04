<h2>Listado de Tipos de Descuento a Empleados</h2>
<?php
$tag=array(
	'name'=>'tag',
	'size'=>'50',
	'value'=>'',
);
$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta.'/empleado_c/alta_tipo_descuento', $atrib) . "\n";
echo form_fieldset('<b>Alta de Nuevo Tipo de Descuento</b>') . "\n";
$img_row="".base_url()."images/table_row.png";
?>
<table class='form_table'>
	<tr>
		<th class='form_tag'>Descripción:</th>
	</tr><tr>
		<td class="form_input"><?php echo form_input($tag);?></td>
	</tr><tr>
		<td  class="form_buttons">
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
if($tipos==false){
		echo "<h2>Sin Tipos de Descuentos registrados</h2>"; exit();
}
?>
<table  class="listado" border="0" width="500">
  <tr>
    <th>Id</th>
    <th>Descripción</th>
    <th>Estatus</th>
    <th>Edición</th>
 </tr>
<?php
$img_row=base_url()."images/table_row.png";
$img_edit=base_url()."images/edit.png";
$img_del=base_url()."images/trash.png";

foreach($tipos as $row) {
	$link_del=$link_edit="";
	$link_edit='<a href="'.site_url("/$ruta/$controller/$funcion/$subfuncion/editar_tipo_descuento/".$row->id).'" title="editar"><img src="'.$img_edit.'" height="25px" width="25px"></a>';
	$link_del='<a href="'.site_url("/$ruta/$controller/$funcion/$subfuncion/borrar_tipo_descuento/".$row->id).'" title="editar"><img src="'.$img_del.'" height="25px" width="25px"></a>';
	$estatus="Activo";
	if($row->estatus_general_id==2)
		$estatus="Inactivo";
	
	echo "<tr background='$img_row' style='height:30px;' align='middle'>\n";
	echo "<td>$row->id</td>\n";
	echo "<td>$row->tag</td>\n";
	echo "<td>$estatus</td>\n";
	echo "<td> &nbsp; </td>\n";
	echo "</tr>\n";
}
echo "</table></div>";
echo $this->pagination->create_links();
?>