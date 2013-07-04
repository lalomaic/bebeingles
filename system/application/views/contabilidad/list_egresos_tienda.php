<script type="text/javascript">
$(document).ready(function() {
	$($.date_input.initialize);
	$('#espacio_fisico_id').val('');
	$('#espacio_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_espacios_tiendas_oficinas_ajax/', {
		//extraParams: {pid: function() { return $("#proveedor").val(); } },
		minLength: 3,
		multiple: false,
		noCache: true,
		parse: function(data) {
			return $.map(eval(data), function(row) {
				return {
					data: row,
					value: row.id,
					result: row.descripcion
				}
			});
		},
		formatItem: function(item) {
			return format(item);
		}
	}).result(function(e, item) {
		$("#espacio_fisico_id").val(""+item.id);
	});
});
function format(r) {
	return r.descripcion;
}

function filtrado(){
	document.form1.submit();
}

</script>


<?php
echo "<h2>$title</h2>";

$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta."/$controller/$funcion/$subfuncion/filtrado", $atrib) . "\n";
echo "<table ><tr>";
echo "<td align=\"center\"><b>Sucursal</b></td>";
echo "<td align=\"center\"><b>Fecha Inicio</b></td>";
echo "<td align=\"center\"><b>Fecha Final</b></td><td></td></tr>";

echo "<tr><td align=\"center\" valign='top'><input type='hidden' name='espacio_fisico_id' id='espacio_fisico_id' value='' size=\"3\"><input id='espacio_drop' class='espacio' value='' size='20'></b><br/><br/></td>";

echo "<td align=\"center\" valign='top'><b><input id='fecha_inicio' name='fecha_inicio' class='date_input' value='".date("d m Y")."' size='12'><br/><button onclick='javascript:filtrado()'>Filtrar</button></td><td>";

echo "<td align=\"center\" valign='top'><b><input id='fecha_final' name='fecha_final' class='date_input' value='".date("d m Y")."' size='12'><br/><br/></td>";
echo "<td style='width:150px'><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_egresos_tienda\" ><img src=\"".base_url()."images/new_reg.png\" width=\"30px\" title=\"Nuevo Concepto Otro Egreso\" align='right'></a>";
echo "</td></tr></table>";
echo form_close();


echo "<br><br><div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";
if($paginacion==true)
	echo $this->pagination->create_links();
?>

<table class="listado" border="0" width="900">
	<tr>
		<th>Id Egreso</th>
		<th width="70">Fecha</th>
		<th width="100">Sucursal</th>
		<th width="150">Rubro</th>
		<th>Concepto</th>
		<th>Monto Egresado</th>
		<th>Capturista</th>
		<th width="70">Edición</th>
	</tr>
	<link href="<?php echo base_url();?>css/default.css" rel="stylesheet"
		type="text/css">
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"Ver Factura\" border=\"0\">";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Eliminar Registro\" border=\"0\">";
	$delete="";
	if($gastos_detalles==false){
		//echo "Sin Registros";
		echo "<br><div align=\"center\"><b>No hay Registros que Cumplan con el Criterio</b></div>";
	}
	else {
		$montot=0;
		foreach($gastos_detalles->all as $row) {
			if(substr(decbin($permisos), 1, 1)==1 ){
				$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_egreso/".$row->id." \" onclick=\"return confirm('¿Estas seguro que deseas borrar el Egreso?');\">$trash</a>";
				$edit="";
			}
			if(substr(decbin($permisos), 2, 1)==1){
				$edit="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_egreso_tienda/$row->id \"><img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Registro\" border=\"0\"></a>";
			}
			if($row->fecha!='0000-00-00'){
				$fechaa=explode("-", $row->fecha);
				$fechai=$fechaa[2]." ".$fechaa[1]." ".$fechaa[0];
			} else {
				$fecha1="Sin fecha";
			}

			echo "<tr background=\"$img_row\"><td align='center'>$row->id</td><td>$fechai</td><td>$row->espacio</td><td align='left'>$row->concepto_gasto</td><td align='left'>$row->concepto</td><td align='right'>$". number_format($row->monto,2,".",",")."</td><td align='center'>$row->usuario</td><td>$delete $edit</td></tr>";
			$montot+=$row->monto;
		}
		if($paginacion==false)
			echo "<tr background=\"$img_row\"><td align='center'></td><td></td><td></td><td align='left'></td><td align='left'><strong>Total</strong></td><td align='right'><strong> $". number_format($montot,2,".",",")."</strong></td><td align='center'></td><td></td></tr>";
	}
	echo "</table></center>";
	if($paginacion==true)
		echo $this->pagination->create_links();
	?>
