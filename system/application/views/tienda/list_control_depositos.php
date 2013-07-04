<?php 
echo "<h2>$title en $tienda</h2>";
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
$trash="<img src=\"".base_url()."images/trash.png\" width=\"20px\" title=\"Deshabilitar Registro\" border=\"0\">";
$delete="";
$edit="";

?>
<?php //echo $this->pagination->create_links();?>
<table class="listado" border="0" width="950">
	<tr>
		<th>Id Dep�sito</th>
		<th>Fecha Venta</th>
		<th>Fecha Dep�sito</th>
		<th>Cuenta Bancaria</th>
		<th>Referencia</th>
		<th>Cantidad</th>
		<th>Empleado</th>
		<th>Capturista</th>
		<th width='50px'>Edici�n</th>
	</tr>
	<?php
	if($depositos==false){
		echo "Sin Registros";
	} else {
		foreach($depositos->all as $row) {
			//Permiso de Borrado en el 2 Byte X1X
			if(substr(decbin($permisos), 1, 1)==1 ){
				$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_deposito/".$row->id." \" onclick=\"return confirm('�Estas seguro que deseas cancelar el Dep�sito de Tienda?');\">$trash</a>";
			}
			if(substr(decbin($permisos), 0, 1)==1){
				$edit="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_control_depositos/editar_deposito/$row->id\"><img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Dep�sito\" border=\"0\"></a>";
			}

			if($row->fecha_deposito!='0000-00-00'){
				$fecha=explode("-", $row->fecha_deposito);
				$fecha_d=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			}
			if($row->fecha_venta!='0000-00-00' and is_null($row->fecha_venta)==false){
				$fecha=explode("-", $row->fecha_venta);
				//		echo $row->fecha_venta."<br/>";
				$fecha_v=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			} else {
				$fecha_v="Vac�o";
			}
			echo "<tr background=\"$img_row\"><td align=\"center\">$row->id</td><td align=\"center\">$fecha_v</td><td align=\"center\">$fecha_d - $row->hora_deposito </td><td>$row->banco - $row->numero_cuenta </td><td align='right'>$row->referencia </td><td align=\"right\">$ ".number_format($row->cantidad, 2, ".",","),"</td><td align=\"center\">$row->nombre_empleado</td><td align=\"center\">$row->usuario</td><td>$edit $delete </td></tr>";

		}
	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>