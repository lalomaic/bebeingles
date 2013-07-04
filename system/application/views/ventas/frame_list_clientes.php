<?php echo "<br><br><div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";?>


<table class="listado" border="0" width="950">
	<tr>
		<th>Id</th>
		<th>Cliente</th>
		<th>RFC</th>
		<th>Teléfono</th>
		<th>Estado y Municipio</th>
		<th>Limite de Credito</th>
		<th>Estatus</th>
		<th>Edición</th>
	</tr>
	<link href="<?php echo base_url();?>css/default.css" rel="stylesheet"
		type="text/css">
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"15px\" title=\"Impresión\" border=\"0\">";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"15px\" title=\"Deshabilitar Cliente\" border=\"0\">";
	$delete="";
	$edit="";

	if($clientes==false){
		echo "Sin Registros";

	} else {

		foreach($clientes->result_array() as $row) {
			if($row['estatus_general_id']==1)
				$row['estatus_general_id']="Habilitado";
			else
				$row['estatus_general_id']="Deshabilitado";

			//Permiso de Borrado en el 2 Byte X1X
			if(substr(decbin($permisos), 1, 1)==1 ){
				//Evitar que el mismo usuario se borre
				$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_cliente/".$row['id']." \" target=\"_parent\" onclick=\"return confirm('¿Estas seguro que deseas deshabilitar al cliente?');\">$trash</a>";
			}
			if(substr(decbin($permisos), 2, 1)==1){
				$edit="<img src=\"".base_url()."images/edit.png\" width=\"15px\" title=\"Editar Registro\" border=\"0\">";
			}

			$photo='';
			echo "<tr background=\"$img_row\" align=\"center\"><td>$row[id]</td><td>$row[razon_social]</td><td>$row[rfc]</td><td>$row[telefono]</td><td>$row[estado], $row[municipio]</td><td align=\"right\">$ ".number_format($row['limite_credito'], 2, ".", ",")."</td><td>$row[estatus_general_id]</td><td><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_cliente/".$row['id']."\" target=\"_parent\">$edit</a>$delete<a href=\"".base_url()."index.php/welcome/sespolice/quejas_pdf/".$row['id']."\" target=\"_blank\">$photo</a></td></tr>";

		}
	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>
