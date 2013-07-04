<?php 
echo "<h2>$title</h2>";
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_cobro\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Nuevo Cobro\"></a></div>";

echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";

?>
<?php //echo $this->pagination->create_links();?>
<table class="listado" border="0">
	<tr>
		<th>Id Factura</th>
		<th>Folio de Factura</th>
		<th>Fecha</th>
		<th>Cliente</th>
		<th>Monto Factura</th>
		<th>Monto Pagado</th>
		<th>Estatus</th>
		<th>Edición</th>
	</tr>
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"Impresión\" border=\"0\">";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Eliminar Registro\" border=\"0\">";
	$delete="";
	$edit="";
	foreach($cobros->all as $row) {
		//Permiso de Borrado en el 2 Byte X1X
		if(substr(decbin($permisos), 1, 1)==1 ){
			$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_usuario/".$row->id." \" onclick=\"return confirm('¿Estas seguro que deseas borrar al usuario?');\">$trash</a>";
		}
		//echo "--".decbin($permisos);
		if(substr(decbin($permisos), 2, 1)==1){
			$edit="<img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Registro\" border=\"0\">";
		}

		echo "<tr background=\"$img_row\"><td align='center'>$row->cl_factura_id</td><td align='center'>$row->factura</td><td align='center'>$row->fecha</td><td align='center'>$row->cliente</td><td align='right'>$row->monto_total</td><td align='right'>$row->monto_pagado</td><td align='right'>$row->estatus</td><td><a href=\"".base_url()."index.php/ventas/ventas_reportes/rep_cl_factura_pdf/".$row->cl_factura_id."\" class=\"modal_pdf\" target=\"_blank\">$photo</a><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_cobro/".$row->cobro_id."\">$edit</a></td></tr>";

	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>