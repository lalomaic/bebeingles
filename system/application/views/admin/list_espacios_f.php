<?php 
echo "<h2>$title</h2>";
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_espacio_f\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Nuevo Espacio Físico\"></a></div>";
echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";
?>
<?php //echo $this->pagination->create_links();?>
<table class="listado" border="0">
	<tr>
		<th>Id</th>
		<th>Local</th>
		<th>Empresa</th>
		<th>Tipo de Local</th>
		<th>Domicilio</th>
		<th>Teléfono</th>
		<th>Estatus</th>
		<th>Edición</th>

	</tr>
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"Impresión\" border=\"0\">";
	$photo="";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Deshabilitar Ubicación\" border=\"0\">";
	$delete="";
	$edit="";
	foreach($espacio_fisico->all as $row) {
		//Permiso de Borrado en el 2 Byte X1X
		if(substr(decbin($permisos), 1, 1)==1 ){
			$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_espacio_f/".$row->id." \" onclick=\"return confirm('Â¿Estas seguro que deseas deshabilitar la ubicaciÃ³n?');\">$trash</a>";
		}
		//echo "--".decbin($permisos);
		if(substr(decbin($permisos), 2, 1)==1){
			$edit="<img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Registro\" border=\"0\">";
		}

		if($row->estatus_general_id==1){
			$estatus="Habilitado";
		} else {$estatus="Deshabilitado";
		}

		echo "<tr background=\"$img_row\"><td>$row->id</td><td>$row->tag</td><td>$row->empresa</td><td>$row->tipo_espacio</td><td>$row->domicilio</td><td>$row->telefono</td><td>$estatus</td><td><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_espacio_f/".$row->id."\">$edit</a>$delete<a href=\"".base_url()."index.php/welcome/sespolice/quejas_pdf/".$row->id."\" target=\"_blank\">$photo</a></td></tr>";

	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>