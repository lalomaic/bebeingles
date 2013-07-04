<script type="text/javascript">
$(document).ready(function() {
	$($.date_input.initialize);
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
function limpiar(){
		window.location="<?=base_url()?>index.php/contabilidad/contabilidad_c/formulario/list_gastos_tiendas";
	}
function listado(){
	window.location="<?=base_url()?>index.php/contabilidad/contabilidad_c/formulario/list_gastos_tiendas";
}


	function borrar_ajax(id){
		$.post("<? echo base_url();?>index.php/ajax_pet/borrar_gasto_tienda",{ enviarvalor : id },  // create an object will all values
		//function that is called when server returns a value.
		function(data){
			document.form1.submit();
			//$(renglon).html(data);
		}
		);
	}
</script>


<?php
echo "<h2>$title</h2>";

$catalogo_gastos[0]="TODOS";
$tipos_gastos[0]="TODOS";
$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta."/$controller/$funcion/$subfuncion/filtrado", $atrib) . "\n";
echo "<table ><tr>";
echo "<td align=\"center\"><b>Sucursal</b></td>";
echo "<td align=\"center\"><b>Rubro</b></td>";
echo "<td align=\"center\"><b>Tipo</b></td>";
echo "<td align=\"center\"><b>Fecha Inicio</b></td>";
echo "<td align=\"center\"><b>Fecha Final</b></td>";
echo "<td style='width:100px'> <a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_gasto_tienda\"><img src=\"".base_url()."images/new_reg.png\" width=\"30px\" title=\"Alta de Gasto de la Tienda\" align='right'></a></td>";
echo "</tr><tr><td align=\"center\" valign='top'><input type='hidden' name='espacio_fisico_id' id='espacio_fisico_id' value='$espacio_id' size=\"3\"><input id='espacio_drop' class='espacio' value='$espacio_tag' size='20' name='espacio_drop'></b><br/><br/></td>";

echo "<td align=\"center\" valign='top'>"; echo form_dropdown('concepto_id', $catalogo_gastos, $concepto_id); echo "</b><br/><br/></td>";

echo "<td align=\"center\" valign='top'>"; echo form_dropdown('tipo_gasto_id', $tipos_gastos, $tipo_gasto_id); echo "</b><br/><br/></td>";

echo "<td align=\"center\" valign='top'><b><input id='fecha_inicio' name='fecha_inicio' class='date_input' value='$fecha1' size='12'><br/></td>";

echo "<td align=\"center\" valign='top'><b><input id='fecha_final' name='fecha_final' class='date_input' value='$fecha2' size='12'><br/><br/></td></tr>";

echo "<tr><td align=\"center\" valign='top' colspan='5'><button  type='button' onclick = 'javascript:limpiar()'> Listado Normal </button><button onclick='javascript:filtrado()'>Buscar</button></td></tr>";

echo "</table>";
echo form_close();

echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";
if($paginacion==true)
	echo $this->pagination->create_links();
?>
<table class="listado" border="0" width="900">
	<tr>
		<th>Id Gasto</th>
		<th width="70">Fecha</th>
		<th width="100">Sucursal</th>
		<th width="150">Rubro</th>
		<th width="150">Tipo</th>
		<th>Concepto</th>
		<th>Monto Pagado</th>
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
		echo "<br><div align=\"center\"><b>No hay Registros que Cumplan con el Criterio</b></div>";
	} else {
		$montot=0;
		foreach($gastos_detalles->all as $row) {
			if(substr(decbin($permisos), 1, 1)==1 ){
				//$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_gasto/".$row->id." \" onclick=\"return confirm('¿Estas seguro que deseas borrar al usuario?');\">$trash</a>";
				$delete="<a href=\"javascript:borrar_ajax($row->id) \" onclick=\"return confirm('¿Estas seguro que deseas borrar el gasto?');\">$trash</a>";
				$edit="";
			}
			if(substr(decbin($permisos), 2, 1)==1){
				$edit="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_gasto_detalle/$row->id \"><img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Registro\" border=\"0\"></a>";
			}
			if($row->fecha!='0000-00-00') {
				$fechaa=explode("-", $row->fecha);
				$fechai=$fechaa[2]." ".$fechaa[1]." ".$fechaa[0];
			} else {
				$fecha1="Sin fecha";
			}

			echo "<tr background=\"$img_row\"><td align='center'>$row->id</td><td>$fechai</td><td>$row->espacio</td><td align='left'>$row->concepto_gasto</td><td align='left'>$row->tipo</td><td align='left'>$row->concepto</td><td align='right'>$". number_format($row->monto,2,".",",")."</td><td align='center'>$row->usuario</td><td>$delete $edit</td></tr>";
			$montot+=$row->monto;
		}
		if($paginacion==false)
			echo "<tr background=\"$img_row\"><td align='center'></td><td></td><td></td><td align='left'></td><td align='left'><strong>Total</strong></td><td align='right'><strong> $". number_format($montot,2,".",",")."</strong></td><td align='center'></td><td></td></tr>";
	}
	echo "</table></center>";
	if($paginacion==true)
		echo $this->pagination->create_links();
	?>
