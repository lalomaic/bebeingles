<?php
echo "<h2>$title</h2>";
echo "<div style='width:920px;margin:auto' align=\"right\">";
echo "<a href=\"" . base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/alta_color\" style='margin:15px;'><img src=\"" . base_url() . "images/new_reg.png\" width=\"30px\" title=\"Agregar Color\">Agregar Nuevo Color</a>";
echo "<a href=\"".base_url()."index.php/almacen/almacen_reportes/formulario/rep_colores\"><img src=\"".base_url()."images/adobereader.png\" width=\"30px\" title=\"Reporte de Colores\">Reporte de Colores</a></div>";

echo "<div align=\"center\"><b>Total de registros:" . $total_registros . "</b></div>";
?>
<?php //echo $this->pagination->create_links();    ?>
<table class="listado" border="0">
	<tr>
		<th>Id</th>
		<th>Color</th>
		<th>Fecha Captura</th>
		<th>Fecha Ultima edición</th>
		<th>Estado</th>
		<th>Edición</th>
	</tr>
	<?php
	//Botones de Edicion
	$img_row = "" . base_url() . "images/table_row.png";
	$imprime = "<img src=\"" . base_url() . "images/adobereader.png\" width=\"20px\" title=\"Impresión\" border=\"0\">";
	$trash = "<img src=\"" . base_url() . "images/trash.png\" width=\"20px\" title=\"Eliminar Registro\" border=\"0\">";
	$edition="<img src=\"".base_url()."images/edit.png\" width=\"20px\" title=\"Editar Registro\" border=\"0\">";
	$delete = "";
	$edit;
	foreach ($colores as $row) {
		//Permiso de Borrado en el 2 Byte X1X
		if (substr(decbin($permisos), 1, 1) == 1) {
			$delete = "<a href=\"" . base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion . "/borrar_color/" . $row->id . " \"  onclick=\"return confirm('¿Estas seguro de  que deseas borrar la promoción?');\">$trash</a>";
			$edit= "<a href=\"" .base_url()  . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion . "/editar_color/".$row->id."\" >$edition</a>";
		}
		if ($row->estatus_general_id == 2){
			$delete = "";
		}
		?>
	<tr background="<?php echo $img_row ?>">
		<td align='center'><?php echo $row->id ?></td>
		<td align='left'><?php echo $row->tag ?></td>
		<td align='center'><?php echo date_format(date_create($row->fecha_captura), 'd-m-Y') ?>
		</td>
		<td align='center'><?php echo date_format(date_create($row->fecha_cambio), 'd-m-Y') ?>
		</td>
		<td align='center' style="color:#<?php echo $row->estatus_general_id == 1 ? '060':'800' ?>;"><?php $row->estatus_general->get(); echo $row->estatus_general->tag ?>
		</td>
		<td align='left'><?php  echo $edit;echo $delete; echo"</td>";
            }?>

</table>
<?php echo $this->pagination->create_links() ?>