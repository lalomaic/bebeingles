<?php 
echo "<h2>$title</h2>";
echo "<div style='width:920px;margin:auto' align=\"right\">";
echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_subfamilia\" style='margin:10px;'><img src=\"".base_url()."images/new_reg.png\" width=\"30px\" title=\"Nueva Subfamilia de Productos\">Agregar Subfamilia</a>";
echo "<a href=\"".base_url()."index.php/almacen/almacen_reportes/formulario/rep_subfamilias\"><img src=\"".base_url()."images/adobereader.png\" width=\"30px\" title=\"Reporte de Subfamilias de Productos\">Reporte de Subfamilias</a>
    <a href=\"".base_url()."index.php/inicio/acceso/almacen/menu\" style='margin:5px;' align=\"left\"><img src=\"".base_url()."images/menu_back.jpg\" width=\"30px\" title=\"Regresar Menu\">Menu</a></div>";


echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";

?>
<?php //echo $this->pagination->create_links();?>
<table class="listado" border="0" width="500">
	<tr>
		<th>Id</th>
		<th>Clave</th>
		<th>Nombre</th>
		<th>Familia</th>
		<th>Estatus</th>
		<th>Edición</th>
	</tr>
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="";
	//$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"Impresi�n\" border=\"0\">";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Deshabilitar Subfamilia\" border=\"0\">";
	$delete="";
	$edit="";
	if($subfamilia_productos!=false){
		foreach($subfamilia_productos->all as $row) {
			//Permiso de Borrado en el 2 Byte X1X
			if(substr(decbin($permisos), 1, 1)==1 ){
				$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_subfamilia/".$row->id." \" onclick=\"return confirm('�Estas seguro que deseas deshabilitar la SubFamilia de Productos?');\">$trash</a>";
			}
			//echo "--".decbin($permisos);
			if(substr(decbin($permisos), 2, 1)==1){
				$edit="<img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Registro\" border=\"0\">";
			}

			echo "<tr background=\"$img_row\"><td>$row->id</td><td>$row->clave</td><td>$row->tag</td><td>$row->familia</td><td>$row->estatus</td><td><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_subfamilia/".$row->id."\">$edit</a>$delete</td></tr>";

		}
	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>