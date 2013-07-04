<?php 
echo "<h2>$title : $tienda</h2>";
echo "<br/><div align=\"center\"><b>Total: ".$total_registros."</b></div><br/>";
?>
<?php //echo $this->pagination->create_links();?>
<table class="listado" border="0" width="700">
	<tr>
		<th>Id Factura</th>
		<th>Folio</th>
		<th>No. Remisi�n</th>
		<th>Cliente</th>
		<th>Fecha</th>
		<th>Hora</th>
		<th>Importe Total</th>
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
	if($remisiones==false){
		echo "Sin Registros";
	} else {

		foreach($remisiones->all as $row) {
			echo "<tr background=\"$img_row\"><td align=\"center\">$row->id</td><td align=\"center\">$row->tag</td><td align='center'>$row->numero_remision</td><td align=\"center\">". fecha_imp($row->fecha, 1)."</td><td align=\"right\">$row->hora</td><td align=\"right\">$ ".number_format($row->importe_total, 2, ".", ",")."</td><td></td></tr> \n";

		}
	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>