<?php 
echo "<h2>$title</h2>";
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_poliza_ingreso\"><img src=\"".base_url()."images/factura.png\" width=\"50px\" title=\"Alta de Pólizas de Ingresps\"></a></div>";
echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";
?>
<?php //echo $this->pagination->create_links();?>
<table class="listado" border="0" width="800">
	<tr>
		<th>Id Póliza</th>
		<th>Fecha Póliza</th>
		<th>Concepto</th>
		<th>Debe</th>
		<th>Haber</th>
		<th>Estatus</th>
		<th width="100">Edición</th>
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
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"20px\" title=\"Deshabilitar Póliza\" border=\"0\">";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"20px\" title=\"Ver Póliza a detalle\" border=\"0\">";
	$reload="<img src=\"".base_url()."images/recargar.png\" width=\"20px\" title=\"Regenerar póliza\" border=\"0\">";
	$edit="<img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Registro\" border=\"0\">";

	if($polizas!=false){
		foreach($polizas->all as $row) {
			$estatus="Sin definir";
			switch($row->estatus_general_id){
				case 1: $estatus = 'Habilitado'; break;
				case 2: $estatus = 'Cancelado'; break;
			}

			$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_poliza/".$row->id." \" onclick=\"return confirm('¿Estas seguro que deseas cancelar la Póliza de Diario?');\">$trash</a>";

			$regenerar="<a href=\"".base_url()."index.php/".$ruta."/trans/verificar_poliza_diario/".$row->id." \" onclick=\"return confirm('¿Esta seguro que deseas regenerar la Póliza de Diario?');\">$reload</a>";

			$editar="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_poliza_diario/".$row->id." \";\">$edit</a>";


			echo "<tr background=\"$img_row\"><td align=\"center\">$row->id</td><td align=\"center\">".fecha_imp($row->fecha)."</td><td>$row->concepto</td><td align=\"right\">$ ".number_format($row->debe, 2, ".", ",")."</td><td align=\"right\">$ ".number_format($row->haber, 2, ".", ",")."</td><td align=\"center\">$estatus</td><td><a href=\"".base_url()."index.php/contabilidad/contabilidad_reportes/poliza_diario_pdf/".$row->id."\" target=\"_blank\" >$photo</a> $editar $delete</td></tr>";

		}
	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>