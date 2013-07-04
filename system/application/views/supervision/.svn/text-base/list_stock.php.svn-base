<?php 
echo "<h2>$title</h2>";
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_stock\"><img src=\"".base_url()."images/stock.png\" width=\"50px\" title=\"Nueva Plantilla de Stock\"></a></div>";
echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";
?>
<table class="listado" border="0" width="600">
	<tr>
		<th>Id Plantilla</th>
		<th>Nombre de la Plantilla</th>
		<th>Fecha</th>
		<th>Estatus</th>
		<th>Edici�n</th>
	</tr>
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"Impresi�n\" border=\"0\">";
	$photo="";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Borrar plantilla\" border=\"0\">";
	if($stocks==false){
		echo "Sin registro de plantillas de Stocks";
	} else {
		foreach($stocks->all as $row) {
			//Permiso de Borrado en el 2 Byte X1X
			$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_plantilla/".$row->id." \" onclick=\"return confirm('�Estas seguro que deseas borrar la plantilla?');\">$trash</a>";

			$edit="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_stock/".$row->id."\"><img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Datos Generales\" border=\"0\"></a>";

			$edit_productos="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_detalles_stock/".$row->id."\"><img src=\"".base_url()."images/productos.png\" width=\"25px\" title=\"Editar Productos y Cantidades\" border=\"0\"></a>";

			$edit_espacios="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_espacios/".$row->id."\"><img src=\"".base_url()."images/recargar.png\" width=\"25px\" title=\"Editar Sucursales\" border=\"0\"></a>";

			if($row->estatus_general_id==1)
				$estatus="Habilitado";
			else
				$estatus="Deshabilitado";
			echo "<tr background=\"$img_row\"><td>$row->id</td><td>$row->nombre</td><td>$row->fecha_captura</td><td>$estatus</td><td> $edit $edit_productos $edit_espacios $delete</td></tr>";
		}
	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>