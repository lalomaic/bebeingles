<?php echo "<br><br><div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";?>
<table class="listado" border="0" width="900">
	<tr>
		<th>Id Factura</th>
		<th>Folio de Factura</th>
		<th>Fecha</th>
		<th>Proveedor</th>
		<th>Monto Factura</th>
		<th>Monto Pagado</th>
		<th>Estatus</th>
		<th>Edición</th>
	</tr>
	<link href="<?php echo base_url();?>css/default.css" rel="stylesheet"
		type="text/css">
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"Ver Factura\" border=\"0\">";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Eliminar Registro\" border=\"0\">";
	$delete="";
	if($pagos==false){
		//echo "Sin Registros";
		echo "<br><div align=\"center\"><b>No hay Registros que Cumplan con el Criterio</b></div>";
	}
	else{
		foreach($pagos->all as $row) {
			if(substr(decbin($permisos), 1, 1)==1 ){
				$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_usuario/".$row->id." \" onclick=\"return confirm('¿Estas seguro que deseas borrar al usuario?');\">$trash</a>";
				$edit="";
			}
			if(substr(decbin($permisos), 2, 1)==1){
				$edit="<img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Registro\" border=\"0\">";
			}
			echo "<tr background=\"$img_row\"><td align='center'>$row->pr_factura_id</td><td align='center'>$row->factura</td><td>$row->fecha</td><td>$row->proveedor</td><td align='right'>$row->monto_total</td><td align='right'>$row->monto_pagado</td><td align='center'>$row->estatus</td><td><a href=\"".base_url()."index.php/compras/compras_reportes/rep_pr_factura_pdf/".$row->pr_factura_id."\" class=\"modal_pdf\" target=\"_blank\">$photo</a><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_pago/".$row->pago_id."\" target='_blank'>$edit</a></td></tr>";
		}
	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>