<?php 
echo "<h2>$title</h2>";
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_usuario\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Nuevo Usuario\"></a></div>";
echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";
?>
<?php //echo $this->pagination->create_links();?>
<table class="listado" border="0">
	<tr>
		<th>Id Nota <br />Credito
		</th>
		<th>Id Factura<br />Proveedor
		</th>
		<th>Fecha</th>
		<th>UbicaciÃ³n de Salida</th>
		<th>Proveedor</th>
		<th>Monto</th>
		<th>Estatus</th>
		<th>EdiciÃ³n</th>
	</tr>
	<?php
	function fecha_imp($date){
		if($date!='0000-00-00'){
			$fecha=substr($date, 0, 10);
			$hora=substr($date, 11, strlen($date));
			$new = explode("-",$fecha);
			$a=array ($new[2], $new[1], $new[0]);
			if(strlen($hora)>2){
				return $n_date=implode("-", $a) . " <br/>Hora: ".$hora;
			} else {
				return $n_date=implode("-", $a);
			}
		} else {
			return "Sin fecha";
		}
	}
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"20px\" title=\"ImpresiÃ³n\" border=\"0\">";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Deshabilitar Entrada\" border=\"0\">";
	$delete="";
	$edit="";
	if($salidas!=false){
		foreach($salidas->all as $row) {

			if(substr(decbin($permisos), 1, 1)==1 ){
				$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_salida/".$row->cl_facturas_id." \" onclick=\"return confirm('Â¿Estas seguro que deseas cancelar la Salida por FacturaciÃ³n?');\">$trash</a>";
			}



			echo "<tr background=\"$img_row\" align=\"center\"><td>$row->nota_credito_id</td><td>$row->pr_factura</td><td>".fecha_imp($row->fecha),"</td><td>$row->espacio_fisico</td><td>$row->proveedor</td><td>$ ". number_format($row->monto_total, 2, ".",",")."<td>$row->estatus</td><td><a href=\"".base_url()."index.php/compras/compras_reportes/rep_pr_factura_pdf/$row->pr_factura\" target=\"_blank\" class='modal_pdf1'>$photo</a> $delete</td></tr>";
		}
	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>