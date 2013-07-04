<?php
echo "<h2>$title</h2>";
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_gasto\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Nuevo Concepto Gasto\"></a></div>";

echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";
if($paginacion==true)
	echo $this->pagination->create_links();
?>
<table class="listado" border="0" width="400">
	<tr>
		<th>Id CGasto</th>
		<th>Concepto</th>
		<th>Estatus</th>
		<th>Edición</th>
	</tr>
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"Ver Factura\" border=\"0\">";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Eliminar Registro\" border=\"0\">";
	$delete="";
	if($gastos_detalles==false){
		//echo "Sin Registros";
		echo "<br><div align=\"center\"><b>No hay Registros que Cumplan con el Criterio</b></div>";
	}
	else {
		foreach($gastos_detalles->all as $row) {
			if(substr(decbin($permisos), 1, 1)==1 ){
				$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_gasto/".$row->id." \" onclick=\"return confirm('¿Estas seguro que deseas deshabilitar el concepto?');\">$trash</a>";
				$edit="";
			}
			if(substr(decbin($permisos), 2, 1)==1){
				$edit="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_gasto/$row->id \"><img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Registro\" border=\"0\"></a>";
			}

			echo "<tr background=\"$img_row\"><td align='center'>$row->id</td><td>$row->tag</td><td align='left'>$row->estatus</td><td>$delete $edit</td></tr>";
		}
	}
	echo "</table></center>";
	if($paginacion==true)
		echo $this->pagination->create_links();
	?>