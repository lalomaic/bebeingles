<?php
echo "<h2>$title</h2>";
echo "<div align=\"left\"><a href=\"" . base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/alta_ajuste_inventario\"><img src=\"" . base_url() . "images/factura.png\" width=\"50px\" title=\"Nuevo Ajuste de Inventario\"></a></div>";
echo "<div align=\"center\"><b>Total de registros:" . $total_registros . "</b></div>";
?>
<?php //echo $this->pagination->create_links(); ?>
<script
	src="<?php echo base_url(); ?>css/md5.js" type="text/javascript"></script>
<script>
    function auth(id){
        key=$('#passwd'+id).val();
        if(key.length>0 && key!= ''){
            llave=hex_md5(key);
            path="<? echo base_url()."index.php/$ruta/" ?>trans/desaplicar_ajuste_inventario/"+id+"/"+llave;
            location.href=path;
        } else {
            alert("No se ha ingresado la contraseña de autorización");
        }
    }
    
    function auth_redo(id){
        key=$('#passwd'+id).val();
        if(key.length>0 && key!= ''){
            llave=hex_md5(key);
            path="<? echo base_url()."index.php/$ruta/" ?>trans/reaplicar_ajuste_inventario/"+id+"/"+llave;
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
		<th>Fecha/Hora<br />Inicio
		</th>
		<th>Fecha/Hora<br />Final
		</th>
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
	if ($arqueos != false) {
		foreach ($arqueos->all as $row) {
			if (substr(decbin($permisos), 2, 1) == 1) {
				$edit = "<img src=\"" . base_url() . "images/edit.png\" width=\"25px\" title=\"Editar Registro\" border=\"0\">";
				$delete = "<a href=\"" . base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion . "/cancelar_ajuste_inventario/" . $row->id . " \" onclick=\"return confirm('¿Estas seguro que deseas cancelar el ajuste del inventario?');\">$trash</a>";
				$pdf = '';
				$reapl = "<a href=\"#\" class=\"autShow\">
				<img src=\"".base_url()."images/redo.png\" width=\"20px\" title=\"Recalcular Stock\" alt=\"Recalcular Stock\"/>
				</a>
				<div class=\"autDiv\">
				<input type=\"password\" value=\"\" size=\"8\" id=\"passwd$row->id\"/>
				<a href=\"#\" onclick=\"auth_redo($row->id)\">Autorizar</a>
				</div>";
			}

			if ($row->cestatus_arqueo_id == 3) {
				$pdf = "<a href=\"" . base_url() . "index.php/supervision/supervision_reportes/rep_ajuste_pdf/$row->espacio_fisico_id/" . $row->id . "\" target='_blank'>$photo</a>";
				$edit = "";
			}
			if ($row->estatus_general == "Inactivo") {
				$delete = "";
				$pdf = "";
			}
			?>
	<tr background="<?php echo$img_row ?>">
		<td align="center"><?php echo$row->id ?></td>
		<td align="center"><?php echo$row->espacio ?></td>
		<td align="center"><?php echo$row->fecha_inicio."<br/>".$row->hora_inicio ?>
		</td>
		<td align="center"><?php echo$row->fecha_final."<br/>".$row->hora_final ?>
		</td>
		<td align="center"><?php echo$row->estatus ?></td>
		<td align="center"><?php echo$row->usuario ?></td>
		<td align="center"><?php echo$row->estatus_general ?></td>
		<td><?php 
		if ($row->estatus_general != "Inactivo") {
                        ?>
                    <? if($row->cestatus_arqueo_id == 1) { ?>
                    <a href="<?php echo base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion . "/editar_ajuste_inventario/" . $row->id ?>">
				<img src="<?php echo base_url() ?>images/edit.png" width="25px"
				title="Editar Registro" border="0" />
                    </a>
                    <? } else {
                        echo "<a href=\"" . base_url() . "index.php/supervision/supervision_reportes/rep_ajuste_pdf/" . $row->id . "\" target='_blank'>$photo</a>";
                     }?>
                    <a
			href="<?php echo base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion . "/cancelar_ajuste_inventario/" . $row->id ?>"
			onclick="return confirm('¿Estas seguro que deseas cancelar el ajuste del inventario?');">
				<img src="<?php echo base_url()?>images/trash.png" width="20px"
				title="Eliminar Registro" border="0" />
		</a> 
                    
                        <div class="autDiv">
				<input type="password" value="" size="8"
					id="passwd<?php echo $row->id ?>" /> <a href="#"
					onclick="auth_redo(<?php echo $row->id ?>)">Autorizar</a>
			</div> <?php } 
                        if ($row->cestatus_arqueo_id == 3) { ?> <a
			href="<?php echo base_url() . "index.php/supervision/supervision_reportes/rep_ajuste_pdf/$row->espacio_fisico_id/" . $row->id ?>"
			target='_blank'> <img
				src="<?php echo base_url()?>images/adobereader.png" width="20px"
				title="Impresión" border="0" />
		</a> 
			<a href="#" class="autShow"> <img
				src="<?php echo base_url() ?>images/deshacer.gif" width="20px"
				title="Deshacer Registro" alt="Deshacer Registro" />
		</a>
			<div class="autDiv">
				<input type="password" value="" size="8"
					id="passwd<?php echo $row->id ?>" /> <a href="#"
					onclick="auth(<?php echo $row->id ?>)">Autorizar</a>
			</div> <?php 
                        
		}?></td>
	</tr>
	<?php
		}
	}
	?>
</table>
</center>
<?php
echo $this->pagination->create_links();
?>
