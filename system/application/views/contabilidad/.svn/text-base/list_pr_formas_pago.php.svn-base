<?php 
echo "<h2>$title</h2>";
//Opciones
echo "<div style='text-align:right;margin-right:50px;margin-top:5px'>";
echo "<a style='margin-right:5px;' href=\"".base_url()."index.php/".$ruta."/contabilidad_reportes/formulario/rep_pr_formas_pago\"><img src=\"".base_url()."images/bitacora.png\" width=\"30px\" title=\"Reporte Formas de Pago\"> Reporte</a>";
echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_pr_forma_pago\"><img src=\"".base_url()."images/new_reg.png\" width=\"30px\" title=\"Nueva Forma de Pago\"> Agregar</a>";
echo "</div>";

echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";

?>
<?php //echo $this->pagination->create_links();?>
<table class="listado" border="0">
	<tr>
		<th>Id</th>
		<th>Forma de Pago</th>
		<th>Edición</th>
	</tr>
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"Impresión\" border=\"0\">";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Eliminar Registro\" border=\"0\">";
	$delete="";
	$edit="";
	foreach($formas_pagos->all as $row) {
		//Permiso de Borrado en el 2 Byte X1X
		if(substr(decbin($permisos), 1, 1)==1 ){
			$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_usuario/".$row->id." \" onclick=\"return confirm('¿Estas seguro que deseas borrar al usuario?');\">$trash</a>";
		}
		//echo "--".decbin($permisos);
		if(substr(decbin($permisos), 2, 1)==1){
			$edit="<img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Registro\" border=\"0\">";
		}

		echo "<tr background=\"$img_row\"><td>$row->id</td><td>$row->tag</td><td><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_pr_forma_pago/".$row->id."\">$edit</a>$delete<a href=\"".base_url()."index.php/welcome/sespolice/quejas_pdf/".$row->id."\" target=\"_blank\">$photo</a></td></tr>";

	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>