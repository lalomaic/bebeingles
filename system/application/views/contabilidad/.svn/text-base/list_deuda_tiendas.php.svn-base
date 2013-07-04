<script type="text/javascript">
$(document).ready(function() {
	$($.date_input.initialize);
	$('#espacio_debe_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_espacios_tiendas_oficinas_ajax/', {
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
		$("#espacio_fisico_debe_id").val(""+item.id);
	});
	$('#espacio_recibe_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_espacios_tiendas_oficinas_ajax/', {
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
		$("#espacio_fisico_recibe_id").val(""+item.id);
	});
});
function format(r) {
	return r.descripcion;
}

function filtrado(){
	document.form1.submit();
}

function borrar_ajax(id){
	$.post("<? echo base_url();?>index.php/ajax_pet/cancelar_gasto_tienda",{ enviarvalor : id },  // create an object will all values
	//function that is called when server returns a value.
	function(data){
		$('#r'+id).html(data);
	});
}
</script>


<?php
echo "<h2>$title</h2>";
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_gasto_tienda\"><img src=\"".base_url()."images/factura.png\" width=\"50px\" title=\"Alta de Gasto de la Tienda\" align='right'></a></div>";
$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta."/$controller/$funcion/$subfuncion/filtrado", $atrib) . "\n";
echo "<table ><tr>";
echo "<td align=\"center\"><b>Tienda que Debe</b></td>";
echo "<td align=\"center\"><b>A que Tienda</b></td>";
echo "<td align=\"center\"><b>Concepto de Deuda</b></td>";
echo "<td align=\"center\"><b>Fecha Inicio</b></td>";
echo "<td align=\"center\"><b>Fecha Final</b></td></tr>";

echo "<tr><td align=\"center\" valign='top'><input type='hidden' name='espacio_fisico_debe_id' id='espacio_fisico_debe_id' value='$espacio_debe_id' size=\"3\"><input id='espacio_debe_drop' class='espacio' value='$espacio_debe_tag' size='20' name='espacio_debe_drop'></b><br/><br/></td>";

echo "<td align=\"center\" valign='top'><input type='hidden' name='espacio_fisico_recibe_id' id='espacio_fisico_recibe_id' value='$espacio_recibe_id' size=\"3\"><input id='espacio_recibe_drop' class='espacio' value='$espacio_recibe_tag' size='20' name='espacio_recibe_drop'></b><br/><br/></td>";

echo "<td align=\"center\" valign='top'>"; echo form_dropdown('concepto_id', $conceptos, $concepto_id); echo "</b><br/><br/></td>";


echo "<td align=\"center\" valign='top'><b><input id='fecha_inicio' name='fecha_inicio' class='date_input' value='$fecha1' size='12'><br/></td>";

echo "<td align=\"center\" valign='top'><b><input id='fecha_final' name='fecha_final' class='date_input' value='$fecha2' size='12'><br/><br/></td></tr>";

echo "<tr><td align=\"center\" valign='top' colspan='5'><button  type='button' onclick=\"window.location='".base_url()."index.php/contabilidad/pagos_c/formulario/list_deuda_tiendas'\">Listado Normal</button><button onclick='javascript:filtrado()' type='button'>Filtrar Listado</button></td></tr>";

echo "</table>";
echo form_close();

echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";
if($paginacion==true)
	echo $this->pagination->create_links();
?>
<table class="listado" border="0" width="900">
	<tr>
		<th>Id</th>
		<th>Fecha</th>
		<th>Tienda deudora</th>
		<th>Tienda acredora</th>
		<th>Concepto</th>
		<th>Monto</th>
		<th>Estatus Pago</th>
		<th>Estatus</th>
		<th>Capturista</th>
		<th width="70">Edición</th>
	</tr>
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"Ver Factura\" border=\"0\">";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Eliminar Registro\" border=\"0\">";
	$delete="";
	if($entradas==false){
		echo "<br><div align=\"center\"><b>No hay Registros que Cumplan con el Criterio</b></div>";
	} else {
		$montot=0;
		foreach($entradas->all as $row) {
			if(substr(decbin($permisos), 1, 1)==1 ){
				$delete="<a href=\"javascript:borrar_ajax($row->id) \" onclick=\"return confirm('¿Estas seguro que deseas borrar el gasto?');\">$trash</a>";
				$edit="";
			}
			if(substr(decbin($permisos), 2, 1)==1){
				$edit="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_deuda_tienda/$row->id \"><img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Registro\" border=\"0\"></a>";
			}
			if($row->fecha!='0000-00-00') {
				$fechaa=explode("-", $row->fecha);
				$fechai=$fechaa[2]." ".$fechaa[1]." ".$fechaa[0];
			} else
				$fecha1="Sin fecha";
			if ($row->pagado==0){
				$estatus="Sin pagar";
			} else {
				$estatus='Liquidado';
				$edit=''; $delete='';
			}
			if ($row->estatus_general_id==1)
				$estatus_g="Habilitado";
			else {
				$estatus_g='<span style="color:red;">Deshabilitado</span>';
				$edit=''; $delete='';
			}

			echo "<tr background=\"$img_row\"><td align='center'>$row->id</td><td>$fechai</td><td>$row->debe</td><td>$row->recibe</td><td align='left'>$row->concepto</td><td align='right'>$". number_format($row->monto_deuda,2,".",",")."</td><td align='center'>$estatus</td><td align='center'><span id='r$row->id'>$estatus_g</span></td><td align='center'>$row->usuario</td><td>$delete $edit</td></tr>";
			$montot+=$row->monto_deuda;
		}
		if($paginacion==false)
			echo "<tr background=\"$img_row\"><td align='center'></td><td></td><td></td><td align='left'></td><td align='left'><strong>Total</strong></td><td align='right'><strong> $". number_format($montot,2,".",",")."</strong></td><td align='center'></td><td></td><td></td><td></td></tr>";
	}
	echo "</table></center>";
	if($paginacion==true)
		echo $this->pagination->create_links();
	?>