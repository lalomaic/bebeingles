<?php 
echo "<h2>$title</h2>";
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_compra\"><img src=\"".base_url()."images/consumo.png\" width=\"50px\" title=\"Nuevo Pedido de Compra\"></a><a href=\"".base_url()."index.php/".$ruta."/proveedores_c/".$funcion."/list_proveedor\"><img src=\"".base_url()."images/proveedor.png\" width=\"50px\" title=\"Listado de Proveedores\"></a></div>";

echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";

?>
<?php //echo $this->pagination->create_links();?>
<table class="listado" border="0">
	<tr>
		<th>Id Traspaso</th>
		<th>Fecha Alta</th>
		<th>Sucursal Traspasante</th>
		<th>Sucursal Solicitante</th>
		<th>Estatus</th>
		<th>Capturista</th>
		<th>Edici�n</th>
	</tr>
	<?php
	//Function for formating the date tags YYYY/MM/DD to DD/MM/YYYY
	function fecha_imp($date, $case){
		if($date!='0000-00-00'){
			$fecha=substr($date, 1, strpos($date, ' ')-1);
			$hora=substr($date, strpos($date, ' '), strlen($date));
			$new = explode("-",$fecha);
			$a=array ($new[2], $new[1], "2".$new[0]);
			if ($case==1){
				return $n_date=implode("-", $a) . " Hora: ".$hora;
			} else {
				return $n_date=implode("-", $a);
			}
		} else {
			return "Sin fecha";
		}
	}
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$ok="<img src=\"".base_url()."images/bien.png\" width=\"25px\" title=\"Dar entrada al Pedido de Compra, en caso de no tener detalles, se eliminar�\" border=\"0\">";
	if($cl_pedidos==false){
		echo "Sin Registros";
	} else {

		foreach($cl_pedidos->all as $row) {
			//Permiso de Borrado en el 2 Byte X1X

			echo "<tr background=\"$img_row\"><td align=\"center\">$row->traspaso_id</td><td align=\"center\">".fecha_imp($row->fecha_alta,1)."</td><td align=\"center\">$tienda</td><td align=\"center\">$row->recibe</td><td align=\"center\">$row->estatus</td><td align=\"center\">$row->usuario</td><td><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/salida_cl_pedido_tras/".$row->id."\">$ok</a></td></tr>";

		}
	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>