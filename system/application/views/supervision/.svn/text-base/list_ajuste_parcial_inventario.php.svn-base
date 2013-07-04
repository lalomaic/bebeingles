<?php

echo "<h2>$title</h2>";
echo "<div align=\"left\"><a href=\"" . base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/alta_ajuste_parcial_inventario\"><img src=\"" . base_url() . "images/factura.png\" width=\"50px\" title=\"Nuevo Ajuste Parcial de Inventario\"></a></div>";
echo "<div align=\"center\"><b>Total de registros:" . $total_registros . "</b></div>";
?>
<?php //echo $this->pagination->create_links(); ?>
<script
	src="<?php echo base_url(); ?>css/md5.js" type="text/javascript"></script>
<script>
    function auth_redo(id){
        key=$('#passwd'+id).val();
        if(key.length>0 && key!= ''){
            llave=hex_md5(key);
            path="<? echo base_url()."index.php/$ruta/" ?>trans/reaplicar_ajuste_parcial_inventario/"+id+"/"+llave;
            location.href=path;
        } else {
            alert("No se ha ingresado la contraseña de autorización");
        }
    }
    $(document).ready(function() {
        $(".autDiv").hide();
        $(".autShow").click(function(e) {
            e.preventDefault();
            $(this).next(".autDiv").fadeIn(2000);
            $(this).next(".autDiv").children("input").focus();
        });
    });
</script>
<table class="listado" border="0" width="900">
	<tr>
		<th>Id Arqueo</th>
		<th>Ubicación</th>
		<th>Fecha</th>
		<th>Hora</th>
		<th>Estatus</th>
		<th>Capturista</th>
		<th>Estatus General</th>
		<th>Edición</th>
	</tr>
	<?php

	//Function for formating the date tags YYYY/MM/DD to DD/MM/YYYY
	function fecha_imp($date) {
		if ($date != '0000-00-00') {
			$new = explode("-", $date);
			$a = array($new[2], $new[1], $new[0]);
			return $n_date = implode("-", $a);
		} else {
			return "Sin fecha";
		}
	}

	//Botones de Edicion
	$img_row = "" . base_url() . "images/table_row.png";
	$photo = "<img src=\"" . base_url() . "images/adobereader.png\" width=\"20px\" title=\"Impresión\" border=\"0\">";
	$trash = "<img src=\"" . base_url() . "images/trash.png\" width=\"20px\" title=\"Eliminar Registro\" border=\"0\">";
	$delete = "";
	$edit = "";
	//$pdf='';

	if ($arqueos != false) {
		foreach ($arqueos->all as $row) {
			$pdf = "";
			if (substr(decbin($permisos), 2, 1) == 1) {
				$edit = "<img src=\"" . base_url() . "images/edit.png\" width=\"25px\" title=\"Editar Registro\" border=\"0\">";
				$delete = "<a href=\"" . base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion . "/cancelar_ajuste_inventario/" . $row->id . " \" onclick=\"return confirm('¿Estas seguro que deseas cancelar el ajuste del inventario?');\">$trash</a>";
			}

			if ($row->cestatus_arqueo_id == 2) {
				$pdf = "<a href=\"" . base_url() . "index.php/supervision/supervision_reportes/rep_ajuste_parcial_pdf/$row->espacio_fisico_id/" . $row->id . "\" target='_blank'>$photo</a>";
				$edit = "";
			}
			if ($row->estatus_general == "Inactivo") {
				$delete = "";
				$pdf = "";
			}else{
				$redo = '<a href="#" class="autShow">
				<img src="'.base_url().'images/redo.png" width="20px" title="Reaplicar Ajuste" alt="Reaplicar Ajuste"/>
				</a>
				<div class="autDiv">
				<input type="password" value="" size="8" id="passwd'.$row->id.'"/>
				<a href="#" onclick="auth_redo('.$row->id.')">Autorizar</a>
				</div>';
			}


			echo "<tr background=\"$img_row\">
			<td align=\"center\">$row->id</td>
			<td align=\"center\">$row->espacio</td>
			<td align=\"center\">$row->fecha_inicio</td>
			<td align=\"center\">$row->hora_inicio</td>
			<td align=\"center\">$row->estatus</td>
			<td align=\"center\">$row->usuario</td>
			<td align=\"center\">$row->estatus_general</td>
			<td>
			$pdf
			<a href=\"" . base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion . "/editar_ajuste_parcial_inventario/" . $row->id . "\"></a>
			$delete
			$redo
			</td>
			</tr>";
		}
	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>