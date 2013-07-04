<?php 
echo 1;
echo "<h2>$title</h2>";
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_usuario\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Nuevo Usuario\"></a></div>";
echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";
?>
<?php //echo $this->pagination->create_links();?>
<table class="listado" border="0" width="900">
	<tr>
		<th>Id Factura</th>
		<th>Fecha</th>
		<th>UbicaciÃ³n de Entrada</th>
		<th>Proveedor</th>
		<th>Monto</th>
		<th>Estatus</th>
		<th>EdiciÃ³n</th>
	</tr>
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"ImpresiÃ³n\" border=\"0\">";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Deshabilitar Entrada\" border=\"0\">";
	$delete="";
	$edit="";
	if($entradas!=false){
		foreach($entradas->all as $row) {

			//    echo "<tr background=\"$img_row\" align=\"center\"><td>$row->entrada_id</td><td>$row->fecha</td><td>$row->tipo_entrada</td><td>$row->proveedor</td> <td>$row->folio_factura</td><td>$row->espacio_fisico</td><td>$row->clave - $row->descripcion - $row->presentacion</td><td>$row->cantidad $row->unidad_medida</td><td>$row->estatus</td><td></td></tr>";

			echo "<tr background=\"$img_row\" align=\"center\"><td>$row->pr_facturas_id</td><td>$row->fecha</td><td>$row->espacio_fisico</td><td>$row->proveedor</td><td>$row->importe_factura<td>$row->estatus</td><td><a href=\"".base_url()."index.php/compras/compras_reportes/rep_pr_factura_pdf/$row->pr_facturas_id\" target=\"_blank\" class='modal_pdf1'>$photo</a></td></tr>";


		}
	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>