<?php
echo "<h2>$title</h2>";
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_usuario\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Nuevo Usuario\"></a></div>";
echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";
?>
<?php echo $this->pagination->create_links();?>
<table class="listado" border="0" width="900">
	<tr>
		<th>Id</th>
		<th>Nombre Completo</th>
		<th>Usuario</th>
		<th>Empresa</th>
		<th>Ubicación</th>
		<th>Puesto</th>
		<th>Estatus</th>
		<th>Edición</th>
	</tr>
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="";
	$permiso="";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Deshabilitar Usuario\" border=\"0\">";
	$delete="";
	$edit="";
	foreach($usuarios->all as $row) {
		//Permiso de Borrado en el 2 Byte X1X
		if(substr(decbin($permisos), 1, 1)==1 ){
			//Evitar que el mismo usuario se borre
			if($usuarioid !=$row->id)
				$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_usuario/".$row->id." \" onclick=\"return confirm('ÃÂ¿Estas seguro que deseas deshabilitar al usuario?');\">$trash</a>";
		}
		//echo "--".decbin($permisos);
		if(substr(decbin($permisos), 2, 1)==1){
			$edit="<img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Registro\" border=\"0\">";
			$permiso="<img src=\"".base_url()."images/permisos.png\" width=\"25px\" title=\"Asignar permisos al usuario\" border=\"0\">";	
			$permiso_pv="<img src=\"".base_url()."images/compras.png\" width=\"25px\" title=\"Asignar permisos al usuario\" border=\"0\">";
		}

		if($row->estatus_general_id==1){
			$estatus="Habilitado";
		} else {$estatus="Deshabilitado";
		}
		$empresa=substr($row->empresa, 0, 20)."...";

		echo "<tr background=\"$img_row\">
			<td>$row->id</td>
			<td>$row->nombre</td>
			<td>$row->username</td>
			<td>$empresa</td>
			<td>$row->espacio_fisico</td>
			<td align='center'>$row->puesto</td>
			<td>$estatus</td>
			<td>
			<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_usuario/".$row->id."\">$edit</a>		
			<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_permisos_pv/".$row->id."\">$permiso_pv</a>			
			<a href=\"".base_url()."index.php/welcome/sespolice/quejas_pdf/".$row->id."\" target=\"_blank\">$photo</a><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/asignar_permisos_usuario/$row->id\">$permiso</a>			$delete</td></tr>";

	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>
