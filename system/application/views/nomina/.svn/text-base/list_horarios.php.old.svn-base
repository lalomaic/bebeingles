
<h2><?php echo $title;?></h2>
<div align="left">
	<a href="<?php echo site_url('/'.$ruta.'/horario_c/formulario/alta_horario')?>" title="Nuevo horario">
		<img src="<?php echo base_url()."/images/adduser.png"?>" alt="Nuevo horario" align="absmiddle" border="0" width="50px">	</a>
</div>
<div align="center">
<b>Total de registros: <?php echo $total_registros;?></b>
</div>
<table  class="listado" border="0">
  <tr>
    <th>Id</th>
    <th>Nombre</th>
    <th>Hora Entrada</th>
    <th>Hora Salida</th>
    <th>Edición</th>
 </tr>
<?php
$img_row=base_url()."images/table_row.png";
$img_edit=base_url()."images/edit.png";
$img_del=base_url()."images/trash.png";
foreach($horarios as $horario)
	{
	$link_del=$link_edit="";
	if(substr(decbin($permisos), 2, 1)==1)
		$link_edit='<a href="'.site_url("/$ruta/$controller/$funcion/$subfuncion/editar_horario/".$horario->id).'" title="editar"><img src="'.$img_edit.'" height="25px" width="25px"></a>';
	echo "<tr background='$img_row'>\n";
	echo "<td>{$horario->id}</td>\n";
	echo "<td>{$horario->tag}</td>\n";
	echo "<td>{$horario->entrada}</td>\n";
	echo "<td>{$horario->salida}</td>\n";
	echo "<td>$link_edit &nbsp;</td>\n";
	echo "</tr>\n";
	}
echo "</table></div>";
echo $this->pagination->create_links();
?>
