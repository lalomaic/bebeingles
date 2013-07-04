<?php
echo "<h2>$title</h2>";
echo "<div align=\"left\"><a href=\"" . base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/alta_promociones\"><img src=\"" . base_url() . "images/adduser.png\" width=\"50px\" title=\"Agregar promoción\"></a></div>";

echo "<div align=\"center\"><b>Total de registros:" . $total_registros . "</b></div>";
?>
<?php //echo $this->pagination->create_links();    ?>
<table class="listado" border="0">
	<tr>
		<th>Id</th>
		<th>Producto</th>
		<th>Fecha inicio</th>
		<th>Fecha Final</th>
		<th>% Descuento</th>
		<th>Limite cantidad</th>
		<th>Estado</th>
		<th>Edición</th>
	</tr>
	<?php
	//Botones de Edicion
	$img_row = "" . base_url() . "images/table_row.png";
	$imprime = "<img src=\"" . base_url() . "images/adobereader.png\" width=\"20px\" title=\"Impresión\" border=\"0\">";
	$trash = "<img src=\"" . base_url() . "images/trash.png\" width=\"20px\" title=\"Eliminar Registro\" border=\"0\">";
	$activate = "<img src=\"" . base_url() . "images/entrada18.png\" width=\"20px\" title=\"Eliminar Registro\" border=\"0\">";
	$edition="<img src=\"".base_url()."images/edit.png\" width=\"20px\" title=\"Editar Registro\" border=\"0\">";
	$delete = "";
	$edit;
	foreach ($promociones as $row) {
		//Permiso de Borrado en el 2 Byte X1X
		if (substr(decbin($permisos), 1, 1) == 1) {
			$delete = "<a href=\"" . base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion . "/cancelar_promocion/" . $row->id . " \" target=\"_blank\" onclick=\"return confirm('¿Estas seguro de  que deseas borrar la promoción?');\">$trash</a>";
			$activar="";
			$edit= "<a href=\"" .base_url()  . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion . "/editar_promocion/".$row->id."\" target=\"_blank\">$edition</a>";
		}
		if ($row->estatus_general_id == 2){
			$delete = "";
			$edit="";
			$activar= "<a href=\"" . base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion . "/activar_promocion/" . $row->id . " \" target=\"_blank\" onclick=\"return confirm('¿Estas seguro de que deseas activar la promoción?');\">$activate</a>";
		}
		if($row->cproducto_id==0)
			$cproducto="TODOS";
		else {
			$row->cproductos->get();
			$cproducto=$row->cproductos->descripcion;
		}

		?>
	<tr background="<?php echo $img_row ?>">
		<td align='center'><?php echo $row->id ?></td>
		<td align='left'><?=$cproducto?></td>
		<td align='center'><?php echo date_format(date_create($row->fecha_inicio), 'd-m-Y') ?>
		</td>
		<td align='center'><?php echo date_format(date_create($row->fecha_final), 'd-m-Y') ?>
		</td>
		<td align='center'><?php echo number_format($row->precio_venta,2,".",",")."%" ?>
		</td>
		<td align='center'><?php echo $row->limite_cantidad ?></td>
		<td align='center' style="color:#<?php echo $row->estatus_general_id == 1 ? '060':'800' ?>;"><?php $row->estatus_general->get(); echo $row->estatus_general->tag ?>
		</td>
		<!--"index.php/" . $ruta . "/" .
                $controller . "/" . $funcion . "/" . $subfuncion .
                "/imprime_pdf/" . $row->id  -->
		<td align='left'><?php  echo $edit;echo $delete; echo $activar; echo "</td>";
            }?>

</table>
<?php echo $this->pagination->create_links() ?>
