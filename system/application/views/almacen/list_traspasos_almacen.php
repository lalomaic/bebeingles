<?php 
echo "<h2>$title</h2>";
echo "<div align=\"left\"></div>";
echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";
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
$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"20px\" title=\"ImpresiÃ³n Orden de Compra\" border=\"0\">";
$trash="<img src=\"".base_url()."images/trash.png\" width=\"20px\" title=\"Deshabilitar Registro\" border=\"0\">";
$reload="<img src=\"".base_url()."images/recargar.png\" width=\"20px\" title=\"Rectificar Traspaso\" border=\"0\">";
$delete="";
$edit="";
$auth="";

?>
<?php //echo $this->pagination->create_links();?>
<table class="listado" border="0" width="800">
	<tr>
		<th>Id Traspaso</th>
		<th>Fecha Alta</th>
		<th>Recibe</th>
		<th>Estatus</th>
		<th>Capturista</th>
		<th>Envia</th>
		<th width='75px'>EdiciÃ³n</th>
	</tr>
	<?php
	if($transferencias==false){
		echo "Sin Registros";
	} else {
		foreach($transferencias->all as $row) {
			//Permiso de Borrado en el 2 Byte X1X
			if(substr(decbin($permisos), 1, 1)==1 ){
				$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_traspaso/".$row->id." \" onclick=\"return confirm('Â¿Estas seguro que deseas cancelar el pedido de traspaso?');\">$trash</a>";
			}
			if(substr(decbin($permisos), 0, 1)==1 and $row->ccl_estatus_pedido_id!=3 and $row->traspaso_estatus==1){
				/*      $edit="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_transferencias/editar_transferencia/$row->id\"><img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Traspaso\" border=\"0\"></a>";*/
			}

			if($row->ccl_estatus_pedido_id == 3){
				//FActurado y entregado
				$auth=""; $pass=""; $delete=""; $edit="";
				if($row->traspaso_estatus==1){
					$row->estatus="Enviado sin Recibir";
					$edit="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_transferencias/editar_transferencia/$row->id\"><img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Traspaso\" border=\"0\"></a>";
					$verificar="";

				} else if($row->traspaso_estatus==2){
					$row->estatus="Recibido";
					//REctificar Traspaso
					$verificar="<a href=\"".base_url()."index.php/tienda/trans/verificar_traspaso/$row->traspaso_id\" target=\"_blank\" >$reload</a>";
				}

				$pdf="<a href=\"".base_url()."index.php/tienda/tienda_reportes/rep_pedido_traspaso/$row->traspaso_id\" target=\"_blank\" class='modal_pdf'>$photo</a>";
			} else {
				$pdf="<a href=\"".base_url()."index.php/almacen/almacen_reportes/rep_pedido_traspaso_prev/$row->traspaso_id\" target=\"_blank\" class='modal_pdf'>$photo</a>";
				$verificar="";
			}
			$edit="";
			echo "<tr background=\"$img_row\"><td align=\"center\">$row->traspaso_id</td><td align=\"center\">".fecha_imp("$row->fecha_alta", 2)."</td><td>$row->recibe</td><td align=\"center\">$row->estatus</td><td align=\"center\">$row->usuario</td><td align=\"center\">$row->envia</td><td>$edit $delete $pdf $verificar</td></tr>";

		}
	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>