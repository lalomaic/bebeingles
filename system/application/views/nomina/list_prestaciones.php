
<h2><?php echo $title;?></h2>
<div align="left">
	<a href="<?php echo site_url('/'.$ruta.'/'.$controller.'/formulario/alta_prestacion')?>" title="Nueva prestación">
		<img src="<?php echo base_url()."/images/adduser.png"?>" alt="Nueva prestación" align="absmiddle" border="0" width="50px">	</a>
</div>
<div align="center">
<b>Total de registros: <?php echo $total_registros;?></b>
</div>
<table  class="listado" border="0">
  <tr>
    <th>Id</th>
    <th>Nombre</th>
    <th>Edición</th>
 </tr>
<?php
$img_row=base_url()."images/table_row.png";
$img_edit=base_url()."images/edit.png";
$img_del=base_url()."images/trash.png";
foreach($prestaciones as $prestacion)
	{
	$link_del=$link_edit="";
	if(substr(decbin($permisos), 2, 1)==1)
		$link_edit='<a href="'.site_url("/$ruta/$controller/$funcion/$subfuncion/editar_prestacion/".$prestacion->id).'" title="editar"><img src="'.$img_edit.'" height="25px" width="25px"></a>';
	echo "<tr background='$img_row'>\n";
	echo "<td>{$prestacion->id}</td>\n";
	echo "<td>{$prestacion->tag}</td>\n";
	echo "<td>$link_edit &nbsp;</td>\n";
	echo "</tr>\n";
	}
echo "</table></div>";
echo $this->pagination->create_links();
?>
