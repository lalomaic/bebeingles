<?php
echo "<h2>$title</h2>";
echo "<div style='width:920px;margin:auto' align=\"right\">";
echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_familia\" style='margin=15px;'><img src=\"".base_url()."images/new_reg.png\" width=\"30px\" title=\"Nueva Familia de Productos\">Agregar nueva Familia</a>";
echo "<a href=\"".base_url()."index.php/almacen/almacen_reportes/formulario/rep_familias\"><img src=\"".base_url()."images/adobereader.png\" width=\"30px\" title=\"Reporte de Familias\">Reporte de Familias</a></div>";

echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";

?>
<?php //echo $this->pagination->create_links();?>
<table class="listado" border="0">
	<tr>
		<th>Id</th>
		<th>Clave</th>
		<th>Nombre</th>
		<th>Fecha de Alta</th>
		<th>Estatus</th>
		<th>Edición</th>
	</tr>
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="";
	//$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"Impresión\" border=\"0\">";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Deshabilitar Familia\" border=\"0\">";
	$delete="";
	$edit="";
	if($familia_productos!=false){

		foreach($familia_productos->all as $row) {
			//Permiso de Borrado en el 2 Byte X1X
			if(substr(decbin($permisos), 1, 1)==1 ){
				$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_familia/".$row->id." \" onclick=\"return confirm('¿Estas seguro que deseas deshabilitar la Familia de Productos?');\">$trash</a>";
			}
			//echo "--".decbin($permisos);
			if(substr(decbin($permisos), 2, 1)==1){
				$edit="<img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Registro\" border=\"0\">";
			}
			echo "<tr background=\"$img_row\"><td>$row->id</td><td>$row->clave</td><td>$row->tag</td><td>$row->fecha_alta</td><td>$row->estatus</td><td><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_familia/".$row->id."\">$edit</a>$delete<a href=\"".base_url()."index.php/welcome/sespolice/quejas_pdf/".$row->id."\" target=\"_blank\">$photo</a></td></tr>";

		}
	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>