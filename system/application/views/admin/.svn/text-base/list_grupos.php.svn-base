<?php 
echo "<h2>$title</h2>";
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_grupo\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Nuevo Grupo\"></a></div>";

echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";

?>
<?php //echo $this->pagination->create_links();?>
<table class="listado" border="0">
	<tr>
		<th>Id</th>
		<th>Nombre del Grupo</th>
		<th>Descripción</th>
		<th>Estatus</th>
		<th>Edición</th>
	</tr>
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"ImpresiÃ³n\" border=\"0\">";
	$photo="";
	$permiso="";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Deshabilitar Grupo\" border=\"0\">";
	$delete="";
	$edit="";
	foreach($grupos->all as $row) {
		//Permiso de Borrado en el 2 Byte X1X
		if(substr(decbin($permisos), 1, 1)==1 ){

			$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/bloquear_grupo/".$row->id." \" onclick=\"return confirm('Â¿Estas seguro que deseas deshabilitar al grupo?');\">$trash</a>";
		}
		//echo "--".decbin($permisos);
		if(substr(decbin($permisos), 2, 1)==1){
			$edit="<img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Registro\" border=\"0\">";
			$permiso="<img src=\"".base_url()."images/permisos.png\" width=\"25px\" title=\"Asignar permisos al grupo\" border=\"0\">";
		}
		if($row->estatus_general_id==1){
			$estatus="Habilitado";
		} else {$estatus="Deshabilitado";
		}


		echo "<tr background=\"$img_row\"><td>$row->id</td><td>$row->nombre</td><td>$row->descripcion</td><td>$estatus</td><td><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_grupo/".$row->id."\">$edit</a>$delete<a href=\"".base_url()."index.php/welcome/sespolice/quejas_pdf/".$row->id."\" target=\"_blank\">$photo</a><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/asignar_permisos_grupo/$row->id\">$permiso</a></td></tr>";

	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>