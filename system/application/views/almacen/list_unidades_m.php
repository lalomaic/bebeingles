<?php 
echo "<h2>$title</h2>";
echo "<div style='width:920px;margin:auto' align=\"right\">";
echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_unidad_m\" style='margin:10px'><img src=\"".base_url()."images/new_reg.png\" width=\"30px\" title=\"Nueva Unidad de Medida\">Agregar Unidad de Medida</a>";
echo "<a href=\"".base_url()."index.php/almacen/almacen_reportes/formulario/rep_unidades_m\"><img src=\"".base_url()."images/adobereader.png\" width=\"30px\" title=\"Reporte de Unidades de Medidas\">Reporte de unidades</a></div>";

echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";

?>
<?php //echo $this->pagination->create_links();?>
<table class="listado" border="0">
	<tr>
		<th>Id</th>
		<th>Unidad de Medida</th>
		<th>Fecha de Alta</th>
		<th>Edición</th>
	</tr>
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="";
	//$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"ImpresiÃ³n\" border=\"0\">";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Deshabilitar Unidad de Medida\" border=\"0\">";
	$delete="";
	$edit="";
	foreach($unidades_medidas->all as $row) {
		//Permiso de Borrado en el 2 Byte X1X
		if(substr(decbin($permisos), 1, 1)==1 ){
			$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_unidad_m/".$row->id." \" onclick=\"return confirm('¿Estas seguro que deseas deshabilitar la Unidad de Medida?');\">$trash</a>";
		}

		if(substr(decbin($permisos), 2, 1)==1){
			$edit="<img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Registro\" border=\"0\">";
		}

		echo "<tr background=\"$img_row\"><td>$row->id</td><td>$row->tag</td><td>$row->fecha_alta</td><td><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_unidad_m/".$row->id."\">$edit</a>$delete<a href=\"".base_url()."index.php/welcome/sespolice/quejas_pdf/".$row->id."\" target=\"_blank\">$photo</a></td></tr>";

	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>