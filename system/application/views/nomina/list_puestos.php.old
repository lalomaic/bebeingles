
<h2><?php echo $title;?></h2>
<div align="left">
	<a href="<?php echo site_url('/'.$ruta.'/puesto_c/formulario/alta_puesto')?>" title="Nuevo puesto">
		<img src="<?php echo base_url()."/images/adduser.png"?>" alt="Nuevo puesto" align="absmiddle" border="0" width="50px">	</a>
</div>
<div align="center">
<b>Total de registros: <?php echo $total_registros;?></b>
</div>
<table  class="listado" border="0">
  <tr>
    <th>Id</th>
    <th>Nombre</th>
    <th>Sueldo Neto</th>
    <th>Edición</th>
 </tr>
<?php
$img_row=base_url()."images/table_row.png";
$img_edit=base_url()."images/edit.png";
$img_del=base_url()."images/trash.png";
foreach($puestos as $puesto)
	{
	// calcular sueldo
	$sueldo=(float)0;
	if(strlen($puesto->prestaciones)>0)
		{
		$prestaciones=explode(',',$puesto->prestaciones);
		if(count($prestaciones)>0)
			{
			foreach($prestaciones as $p)
				{
				list($pid,$cantidad)=explode(':',$p);
				$pid=(int)$pid;
				if($pid>0)
					$sueldo+=(float)$cantidad;
				}
			}
		}
	if(strlen($puesto->prestaciones)>0)
		{
		$deducciones=explode(',',$puesto->deducciones);
		if(count($deducciones)>0)
			{
			foreach($deducciones as $d)
				{
				list($did,$cantidad)=explode(':',$d);
				$did=(int)$did;
				if($did>0)
					$sueldo-=(float)$cantidad;
				}
			}
		}
		
	$link_del=$link_edit="";
	if(substr(decbin($permisos), 2, 1)==1)
		$link_edit='<a href="'.site_url("/$ruta/$controller/$funcion/$subfuncion/editar_puesto/".$puesto->id).'" title="editar"><img src="'.$img_edit.'" height="25px" width="25px"></a>';
	echo "<tr background='$img_row'>\n";
	echo "<td>{$puesto->id}</td>\n";
	echo "<td>{$puesto->tag}</td>\n";
	echo "<td align=right>$ ".number_format($sueldo,2)."</td>\n";
	echo "<td>$link_edit &nbsp;</td>\n";
	echo "</tr>\n";
	}
echo "</table></div>";
echo $this->pagination->create_links();
?>
