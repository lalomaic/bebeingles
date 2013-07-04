<?php 
echo "<h2>Listado General de Depositos Bancarios</h2>";
echo "<div align=\"left\"></div>";
echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";
//Function for formating the date tags YYYY/MM/DD to DD/MM/YYYY

//Botones de Edicion
$img_row="".base_url()."images/table_row.png";
$trash="<img src=\"".base_url()."images/trash.png\" width=\"20px\" title=\"Deshabilitar Registro\" border=\"0\">";
$delete="";
$edit="";

?>
<?php echo $this->pagination->create_links();?>
<table class="listado" border="0" width="1000">
	<tr>
		<th>Id Depósito</th>
		<th>Fecha Venta</th>
		<th>Fecha Depósito</th>
		<th>Cuenta Bancaria</th>
		<th>Referencia</th>
		<th>Sucursal</th>
		<th>Cantidad</th>
		<th>Empleado</th>
		<th>Capturista</th>
		<th width='50px'>Edición</th>
	</tr>
	<?php
	if($depositos==false){
		echo "Sin Registros";
	} else {
		foreach($depositos->all as $row) {
			//Permiso de Borrado en el 2 Byte X1X
			if(substr(decbin($permisos), 1, 1)==1 ){
				$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_deposito/".$row->id." \" onclick=\"return confirm('¿Estas seguro que deseas cancelar el Depósito de Tienda?');\">$trash</a>";
			}
			if(substr(decbin($permisos), 0, 1)==1){
				$edit="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_control_depositos_general/editar_deposito/$row->id\"><img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Depósito\" border=\"0\"></a>";
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
				$fecha_v="Vacío";
			}


			echo "<tr background=\"$img_row\"><td align=\"center\">$row->id</td><td align=\"center\">$fecha_v</td><td align=\"center\">$fecha_d - $row->hora_deposito </td><td>$row->banco - $row->numero_cuenta </td><td align='right'>$row->referencia </td><td align=\"right\">$row->espacio</td><td align=\"right\">$ ".number_format($row->cantidad, 2, ".",","),"</td><td align=\"center\">$row->nombre_empleado</td><td align=\"center\">$row->usuario</td><td>$edit $delete </td></tr>";

		}
	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>