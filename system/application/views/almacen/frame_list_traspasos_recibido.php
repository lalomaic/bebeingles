<?php echo "<br><br><div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";?>

<table class="listado" border="0" width="900">
	<tr>
		<th>Id Traspaso</th>
		<th>Fecha Alta</th>
		<th>Ãrea solicitada</th>
		<th>Estatus</th>
		<th>Capturista</th>
		<th>EdiciÃ³n</th>
	</tr>
	<link href="<?php echo base_url();?>css/default.css" rel="stylesheet"
		type="text/css">
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
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"ImpresiÃ³n Orden de Compra\" border=\"0\">";
	$edit="";
	$delete="";


	if($traspasos==false){
		//echo "Sin Registros";
		echo "<br><div align=\"center\"><b>No hay Registros que Cumplan con el Criterio</b></div>";
	}
	else{
		foreach($traspasos->all as $row) {
			if($row->traspaso_estatus==1)
				$estatus="Enviado";
			else if($row->traspaso_estatus==2)
				$estatus="Recibido";
			echo "<tr background=\"$img_row\"><td align=\"center\">$row->traspaso_id</td><td align=\"center\">".fecha_imp("$row->fecha_alta", 2)."</td><td>$row->recibe</td><td align=\"center\">$estatus</td><td align=\"center\">$row->usuario</td><td><a href=\"".base_url()."index.php/almacen/almacen_reportes/rep_pedido_traspaso/".$row->traspaso_id."\" target=\"_blank\" class='modal_pdf'>$photo</a></td></tr>";

		}
	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>