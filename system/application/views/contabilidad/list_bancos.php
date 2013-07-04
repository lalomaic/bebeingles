<script type="text/javascript">
$(document).ready(function() {
});
</script>
<?php
echo "<h2>$title</h2>";
echo "<div style='width:920px;margin:auto' align=\"right\">";
echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_bancos\" style='margin:15px;' ><img src=\"".base_url()."images/new_reg.png\" width=\"30px\" title=\"Nueva Marca\">Agregar Nueva Banco</a>";
echo "</div>";

echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";
if($paginacion==true)
	echo $this->pagination->create_links();
?>
<table class="listado" border="0">
	<tr>
		<th>Id</th>
		<th>Nombre del banco</th>
		<th>Estatus</th>
		<th>Edición</th>
	</tr>
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="";
	//$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"ImpresiÃÂ³n\" border=\"0\">";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Deshabilitar Banco\" border=\"0\">";
	$delete="";
	$edit="";
	if($marca_productos!=false){
		foreach($marca_productos->all as $row) {
			//Permiso de Borrado en el 2 Byte X1X
			if(substr(decbin($permisos), 1, 1)==1 ){
				$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_bancos/".$row->id." \" onclick=\"return confirm('¿Estas seguro que deseas deshabilitar este banco?');\">$trash</a>";
			}

			if(substr(decbin($permisos), 2, 1)==1){
				$edit="<img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Registro\" border=\"0\">";
			}

			echo "<tr background=\"$img_row\"><td>$row->id</td><td>$row->tag</td><td>$row->estatus</td><td><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_bancos/".$row->id."\">$edit</a>$delete<a href=\"".base_url()."index.php/welcome/sespolice/quejas_pdf/".$row->id."\" target=\"_blank\">$photo</a></td></tr>";

		}
	}
	echo "</table></center>";

	if($paginacion==true)
		echo $this->pagination->create_links();
	?>
