<?php 
echo "<h2>$title : $tienda</h2>";
echo "<br/><div align=\"center\"><b>Cortes sin Facturaci�n: ".$total_registros."</b></div><br/>";
?>
<?php //echo $this->pagination->create_links();?>
<table class="listado" border="0">
	<tr>
		<th>Id Corte</th>
		<th>Fecha</th>
		<th>Sucursal</th>
		<th>Monto Total</th>
		<th>Capturista</th>
		<th>Edici�n</th>
	</tr>
	<?php
	//Function for formating the date tags YYYY/MM/DD to DD/MM/YYYY
	function fecha_imp($date, $case){
		if($date!='0000-00-00'){
			$new= explode("-",$date);
			$a=array ($new[2], $new[1], $new[0]);
			return implode("-", $a);
		} else {
			return "Sin fecha";
		}
	}
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$ok="<img src=\"".base_url()."images/bien.png\" width=\"25px\" title=\"Facturar Corte\" border=\"0\">";
	if($cortes==false){
		echo "Sin Registros";
	} else {

		foreach($cortes->all as $row) {
			echo "<tr background=\"$img_row\"><td align=\"center\">$row->id</td><td align=\"center\">". fecha_imp($row->fecha_corte, 1)."</td><td align=\"right\">$row->tag</td><td align=\"center\">$ ".number_format($row->venta_total, 2, ".", ",")."</td><td align=\"center\">$row->usuario</td><td><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/folio_factura/".$row->id."\">$ok</a></td></tr>";

		}
	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>