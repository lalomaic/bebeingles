<?php 
echo "<h2>$title en $tienda</h2>";
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_cl_facturas_tienda\"><img src=\"".base_url()."images/factura.png\" width=\"50px\" title=\"Alta de Facturaci�n de Corte\"></a><a href=\"".base_url()."index.php/".$ruta."/$controller/".$funcion."/list_cortes_diarios\"><img src=\"".base_url()."images/bitacora.png\" width=\"50px\" title=\"Cortes de la Tienda\"></a></div>";

echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";

?>
<?php //echo $this->pagination->create_links();?>
<table class="listado" border="0" width="90%">
	<tr>
		<th>Folio Factura</th>
		<th>Fecha Factura</th>
		<th>Fecha Captura</th>
		<th>Cliente</th>
		<th>Empresa</th>
		<th>Total</th>
		<th>Iva</th>
		<th>Capturista</th>
		<th>Estatus</th>
		<th width="40">Edici�n</th>
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
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"20px\" title=\"Deshabilitar Registro\" border=\"0\">";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"20px\" title=\"Impresi�n\" border=\"0\">";
	if($cl_facturas!=false){
		foreach($cl_facturas->all as $row) {
			$estatus="Sin definir";
			switch($row->estatus_general_id){
				case 1: $estatus = 'Habilitado'; break;
				case 2: $estatus = 'Cancelado'; break;
			}

			$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/cancelar_factura/".$row->factura_id." \" onclick=\"return confirm('�Estas seguro que deseas cancelar la factura del cliente?');\">$trash</a>";

			$empresa=substr($row->empresa,0,20)." ...";
			echo "<tr background=\"$img_row\"><td align=\"center\">$row->folio_factura</td><td align=\"center\">".fecha_imp($row->fecha)."</td><td align=\"center\">$row->fecha_captura</td><td>$row->cliente</td><td>$empresa</td><td align=\"right\">$ ".number_format($row->monto_total, 2, ".", ",")."</td><td align=\"right\">$ ".number_format($row->iva_total, 2, ".", ",")."</td><td>$row->usuario</td><td>$estatus</td><td><a href=\"".base_url()."index.php/ventas/factura/generar/".$row->factura_id."\" target=\"_blank\" class='modal_pdf'>$photo</a>$delete</td></tr>";

		}
	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>