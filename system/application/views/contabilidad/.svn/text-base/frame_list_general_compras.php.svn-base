<?php echo "<br><br><div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";?>

<table class="listado" border="0" width="900">
	<tr>
		<th>Id Factura</th>
		<th>Id Pedido</th>
		<th>Fecha</th>
		<th>Ubicación de Entrada</th>
		<th>Proveedor</th>
		<th>Monto</th>
		<th>Estatus</th>
		<th>Edición</th>
	</tr>
	<link href="<?php echo base_url();?>css/default.css" rel="stylesheet"
		type="text/css">
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"Impresión\" border=\"0\">";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Deshabilitar Entrada\" border=\"0\">";
	$delete="";
	$edit="";
	if($entradas==false){
		//echo "Sin Registros";
		echo "<br><div align=\"center\"><b>No hay Registros que Cumplan con el Criterio</b></div>";
	}
	else{
		foreach($entradas->all as $row) {
			echo "<tr background=\"$img_row\" align=\"center\"><td>$row->pr_facturas_id</td><td>$row->pr_pedido_id</td><td>$row->fecha</td><td>$row->espacio_fisico</td><td>$row->proveedor</td><td>$row->importe_factura<td>$row->estatus</td><td><a href=\"".base_url()."index.php/compras/compras_reportes/rep_pr_factura_pdf/$row->pr_facturas_id\" target=\"_blank\" class='modal_pdf1'>$photo</a></td></tr>";

		}
	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>