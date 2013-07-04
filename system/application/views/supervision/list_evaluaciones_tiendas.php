<?php 
echo "<h2>$title</h2>";
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_proveedor\"><img src=\"".base_url()."images/proveedor.png\" width=\"50px\" title=\"Nuevo Proveedor\"></a></div>";
echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";
?>
<?php //echo $this->pagination->create_links();?>
<table class="listado" border="0" width="950">
	<tr>
		<th>Id</th>
		<th>Proveedor</th>
		<th>RFC</th>
		<th>Domicilio</th>
		<th>Tel�fono</th>
		<th>Estado y Municipio</th>
		<th>Limite de Credito</th>
		<th>Estatus</th>
		<th>Edici�n</th>
	</tr>
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"Impresi�n\" border=\"0\">";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Deshabilitar Proveedor\" border=\"0\">";
	$delete="";
	$edit="";

	if($proveedores==false){
		echo "Sin Registros";

	} else {
		foreach($proveedores->all as $row) {
			//Permiso de Borrado en el 2 Byte X1X
			if(substr(decbin($permisos), 1, 1)==1 ){
				//Evitar que el mismo usuario se borre
				$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_proveedor/".$row->id." \" onclick=\"return confirm('�Estas seguro que deseas deshabilitar al proveedor?');\">$trash</a>";
			}
			if(substr(decbin($permisos), 2, 1)==1){
				$edit="<img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Registro\" border=\"0\">";
			}

			echo "<tr background=\"$img_row\"><td>$row->id</td><td>$row->razon_social</td><td>$row->rfc</td><td>$row->domicilio</td><td>$row->telefono</td><td>$row->estado, $row->municipio</td><td>$row->limite_credito</td><td>$row->estatus</td><td><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_proveedor/".$row->id."\">$edit</a>$delete<a href=\"".base_url()."index.php/welcome/sespolice/quejas_pdf/".$row->id."\" target=\"_blank\">$photo</a></td></tr>";

		}
	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>