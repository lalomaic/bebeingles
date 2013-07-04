<?php 
echo "<h2>$title</h2>";
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_pr_factura\"><img src=\"".base_url()."images/factura.png\" width=\"50px\" title=\"Nueva Factura de Proveedor\"></a><a href=\"".base_url()."index.php/".$ruta."/proveedores_c/".$funcion."/list_proveedor\"><img src=\"".base_url()."images/proveedor.png\" width=\"50px\" title=\"Listado de Proveedores\"></a></div>";
echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";
?>
<?php //echo $this->pagination->create_links();?>
<table class="listado" border="0" width="700">
	<tr>
		<th>Id Arqueo</th>
		<th>Ubicaci�n</th>
		<th>Fecha</th>
		<th>Hora</th>
		<th>Estatus</th>
		<th>Capturista</th>
		<th>Edici�n</th>
	</tr>
	<?php
	//Function for formating the date tags YYYY/MM/DD to DD/MM/YYYY
	function fecha_imp($date){
		if($date!='0000-00-00'){
			$new = explode("-",$date);
			$a=array ($new[2], $new[1], $new[0]);
			return $n_date=implode("-", $a);
		} else {
			return "Sin fecha";
		}
	}
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"Impresi�n\" border=\"0\">";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Eliminar Registro\" border=\"0\">";
	$delete="";
	$edit="";
	if($arqueos!=false){
		foreach($arqueos->all as $row) {
			//Permiso de Borrado en el 2 Byte X1X
			if(substr(decbin($permisos), 1, 1)==1 ){
				//Evitar que el mismo usuario se borre
				//      $delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_pr_factura/".$row->id." \" onclick=\"return confirm('�Estas seguro que deseas borrar la factura?');\">$trash</a>";
			}
			//echo "--".decbin($permisos);
			if(substr(decbin($permisos), 2, 1)==1 && $row->estatus=='Capturado'){
				//      $edit="<img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Registro\" border=\"0\">";
				//echo '<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_/".$row->id."\">$edit</a>';
			}

			echo "<tr background=\"$img_row\"><td align=\"center\">$row->id</td><td align=\"center\">$row->espacio</td><td align=\"center\">".fecha_imp($row->fecha)."</td><td align=\"center\">$row->hora</td><td align=\"center\">$row->estatus</td><td align=\"center\">$row->usuario</td><td><a href=\"".base_url()."index.php/".$ruta."/tienda_reportes/rep_existencia_pdf/".$row->id."\" class='modal_pdf'>$photo</a></td></tr>";

		}
	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>