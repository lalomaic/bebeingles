<?php echo "<br><br><div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";?>

<table class="listado" border="0" width="900">
	<tr>
		<th>Id Factura</th>
		<th>Id Pedido</th>
		<th>Folio Factura</th>
		<th>Fecha Factura</th>
		<th>Fecha Captura</th>
		<th>Cliente</th>
		<th>Empresa</th>
		<th>Monto Total</th>
		<th>Estatus</th>
		<th>Capturista</th>
		<th width='60'>Edición</th>
	</tr>
	<link href="<?php echo base_url();?>css/default.css" rel="stylesheet"
		type="text/css">
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"20px\" title=\"Impresión\" border=\"0\">";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"20px\" title=\"Cancelar Factura\" border=\"0\">";
	$delete="";
	$edit="";
	if($cl_facturas==false){
		//echo "Sin Registros";
		echo "<br><div align=\"center\"><b>No hay Registros que Cumplan con el Criterio</b></div>";
	}
	else{
		foreach($cl_facturas->all as $row) {
			//Permiso de Borrado en el 2 Byte X1X
			if(substr(decbin($permisos), 1, 1)==1 ){
				//Evitar que el mismo usuario se borre
				$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_cl_factura/".$row->id." \" onclick=\"return confirm('¿Estas seguro que deseas cancelar la factura?');\">$trash</a>";
			}
			if(substr(decbin($permisos), 2, 1)==1){
				$edit="<img src=\"".base_url()."images/edit.png\" width=\"20px\" title=\"Editar Registro\" border=\"0\">";
			} else {
				$edit="";
			}
			if($row->estatus=="Deshabilitado") {
				$edit="";
			}
			$empresa=substr($row->empresa, 0, 15)." ...";
			echo "<tr background=\"$img_row\"><td align=\"center\">$row->id</td><td align=\"center\">$row->cl_pedido_id</td><td align=\"center\">$row->folio_factura</td><td align=\"center\">$row->fecha</td><td align=\"center\">$row->fecha_captura</td><td>$row->cliente</td><td>$empresa</td><td align=\"right\">$ ".number_format($row->monto_total, 2, ".", ",")."</td><td>$row->usuario</td><td>$row->estatus</td><td><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_cl_factura/".$row->id."\">$edit</a>$delete<a href=\"".base_url()."index.php/ventas/factura/generar/".$row->id."\" target=\"_blank\">$photo</a></td></tr>";

		}
	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>