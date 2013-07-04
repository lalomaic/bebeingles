<?php 
echo "<h2>$title</h2>";
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_poliza_ingreso\"><img src=\"".base_url()."images/factura.png\" width=\"50px\" title=\"Alta de P�lizas de Ingresps\"></a></div>";
echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";
?>
<?php //echo $this->pagination->create_links();?>
<table class="listado" border="0" width="90%">
	<tr>
		<th>Id P�liza</th>
		<th>Fecha P�liza</th>
		<th>Sucursal</th>
		<th>Haber</th>
		<th>Debe</th>
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
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"20px\" title=\"Deshabilitar P�liza\" border=\"0\">";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"20px\" title=\"Ver P�liza a detalle\" border=\"0\">";
	if($polizas!=false){
		foreach($polizas->all as $row) {
			$estatus="Sin definir";
			switch($row->estatus_general_id){
				case 1: $estatus = 'Habilitado'; break;
				case 2: $estatus = 'Cancelado'; break;
			}

			$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_poliza/".$row->id." \" onclick=\"return confirm('�Estas seguro que deseas cancelar la P�liza de Ingreso?');\">$trash</a>";

			echo "<tr background=\"$img_row\"><td align=\"center\">$row->id</td><td align=\"center\">".fecha_imp($row->fecha)."</td><td>$row->espacio</td><td>$ ".number_format($row->haber, 2, ".", ",")."</td><td align=\"right\">$ ".number_format($row->debe, 2, ".", ",")."</td><td align=\"right\">$ ".number_format($row->iva_total, 2, ".", ",")."</td><td>$estatus</td><td><a href=\"".base_url()."index.php/contabilidad/trans/poliza_ingreso/".$row->id."\" target=\"_blank\" class='modal_pdf'>$photo</a>$delete</td></tr>";

		}
	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>