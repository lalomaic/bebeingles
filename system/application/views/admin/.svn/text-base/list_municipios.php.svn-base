<?php 
echo "<h2>$title</h2>";
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_municipio\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Nuevo Municipio\"></a></div>";

echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";

?>
<?php //echo $this->pagination->create_links();?>
<table class="listado" border="0">
	<tr>
		<th>Id</th>
		<th>Estado</th>
		<th>Nombre del Municipio</th>
		<th>Estatus</th>
		<th>EdiciÃ³n</th>
	</tr>
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"ImpresiÃ³n\" border=\"0\">";
	$photo="";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Eliminar Registro\" border=\"0\">";
	$delete="";
	$edit="";
	foreach($municipios->all as $row) {
		//Permiso de Borrado en el 2 Byte X1X
		//$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_municipio/".$row->id." \" onclick=\"return confirm('Â¿Estas seguro que deseas borrar el municipio?');\">$trash</a>";

		if(substr(decbin($permisos), 2, 1)==1){
			$edit="<img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Municipio\" border=\"0\">";
		}
		if($row->estatus_general_id==1){
			$estatus="Habilitado";
		} else {$estatus="Habilitado";
		}


		echo "<tr background=\"$img_row\"><td>$row->id</td><td>$row->estado</td><td>$row->tag</td><td>$estatus</td><td><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_municipio/".$row->id."\">$edit</a>$delete<a href=\"".base_url()."index.php/welcome/sespolice/quejas_pdf/".$row->id."\" target=\"_blank\">$photo</a></td></tr>";

	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>