<?php
echo "<h2>$title</h2>";
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_pr_factura\"><img src=\"".base_url()."images/factura.png\" width=\"50px\" title=\"Nueva Factura de Proveedor\"></a><a href=\"".base_url()."index.php/".$ruta."/proveedores_c/".$funcion."/list_proveedor\"><img src=\"".base_url()."images/proveedor.png\" width=\"50px\" title=\"Listado de Proveedores\"></a></div>";

echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";

?>
<?php //echo $this->pagination->create_links();?>
<table class="listado" border="0" width="90%">
	<tr>
		<th>Id Factura</th>
		<th>Fecha Factura</th>
		<th>Fecha Captura</th>
		<th>Proveedor</th>
		<th>Empresa</th>
		<th>Folio</th>
		<th>No. Conceptos</th>
		<th>Monto Total</th>
		<th>Capturista</th>
		<th width="80">Edición</th>
	</tr>
	<?php
	//Function for formating the date tags YYYY/MM/DD to DD/MM/YYYY
	function fecha_imp($date){
		if($date!='0000-00-00'){
			$fecha=substr($date, 0, 10);
			$hora=substr($date, 11, strlen($date));
			$new = explode("-",$fecha);
			$a=array ($new[2], $new[1], $new[0]);
			if(strlen($hora)>2){
				return $n_date=implode("-", $a) . " Hora: ".$hora;
			} else {
				return $n_date=implode("-", $a);
			}
		} else {
			return "Sin fecha";
		}
	}
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"20px\" title=\"Impresión\" border=\"0\">";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"20px\" title=\"Deshabilitar Factura\" border=\"0\">";
	$delete="";
	$edit="";

	if($pr_facturas!=false){
		foreach($pr_facturas->all as $row) {
			//Permiso de Borrado en el 2 Byte X1X
			if(substr(decbin($permisos), 1, 1)==1 ){
				$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_pr_factura/".$row->id." \" onclick=\"return confirm('¿Estas seguro que deseas deshabilitar la factura?,\n lo cual cancelará el pedido y entrada del mismo');\">$trash</a>";
			}
			//echo "--".decbin($permisos);
			if(substr(decbin($permisos), 2, 1)==1){
				$edit="<img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Registro\" border=\"0\">";
			}

			echo "<tr background=\"$img_row\"><td align=\"center\">$row->id</td><td align=\"center\">".fecha_imp($row->fecha)."</td><td align=\"center\">".fecha_imp($row->fecha_captura)."</td><td>$row->proveedor</td><td>$row->empresa</td><td align=\"center\">$row->folio_factura</td><td align=\"right\">$row->conceptos</td><td align=\"right\">$ ".number_format($row->monto_total, 2, ".", ",")."</td><td>$row->usuario</td><td><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_pr_factura/".$row->id."\">$edit</a>$delete<a href=\"".base_url()."index.php/compras/compras_reportes/rep_pr_factura_pdf/".$row->id."\" class=\"model_pdf\">$photo</a></td></tr>";

		}
	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>