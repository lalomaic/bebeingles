<script>
$(document).ready(function() {
	$($.date_input.initialize);	

	  $('#cproveedor_id').val('');
	  $('#proveedor_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_proveedores_ajax_autocomplete/', {
		  extraParams: {pid: 0 },
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
		  $("#cproveedor_id").val(""+item.id);
	  });

	  $('#cmarca_id').val('');
	  $('#marca_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_marcas_ajax/', {
		  extraParams: {pid: 0 },
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
		  $("#cmarca_id").val(""+item.id);
	  });	
	
	
});

function format(r) {
	return r.descripcion;
}

function fil_proveedor(){
	pag=<? echo $pag;?>;
	ped=$("#cproveedor_id").val();
	location.href="<?php echo base_url(); ?>index.php/contabilidad/pagos_c/formulario/list_pagos_multiples/"+pag+"/proveedor/"+ped;
}
function fil_marca(){
	pag=<? echo $pag;?>;
	ped=$("#cmarca_id").val();
	location.href="<?php echo base_url(); ?>index.php/contabilidad/pagos_c/formulario/list_pagos_multiples/"+pag+"/marcas/"+ped;
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
echo "<td align=\"center\"><b>Proveedor</b></td>";
echo "<td align=\"center\"><b>Marca</b></td>";
echo "<td align=\"center\"><b>Fecha Inicio</b></td>";
echo "<td align=\"center\"><b>Fecha Final</b></td></tr>";

echo "<td align=\"center\"><b><input type='hidden' name='cproveedor_id' id='cproveedor_id' value='' size=\"3\"><input id='proveedor_drop' class='proveedor' value='' size='30'></b></br><button type='button' onclick='javascript:fil_proveedor()'>Filtrar</button></td>";

echo "<td align=\"center\"><b><input type='hidden' name='cmarca_id' id='cmarca_id' value='' size=\"3\"><input id='marca_drop' class='marca' value='' size='20'></b><br/><button type='button' onclick='javascript:fil_marca()'>Filtrar</button></td>";

echo "<td align=\"center\" valign='top' colspan='2'><b><input id='fecha_inicio' name='fecha_inicio' class='date_input' value='$fecha1' size='12'><input id='fecha_final' name='fecha_final' class='date_input' value='$fecha2' size='12'><br/><button type='button' onclick='javascript:filtrado()'>Filtrado Múltiple</button></td>";

echo "</tr></table></form>";

echo "<br><br><div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";
if($paginacion==true)
	echo $this->pagination->create_links();
?>
<table class="listado" border="0">
	<tr>
		<th>Fecha Pago</th>
		<th>Proveedor</th>
		<th>Marca</th>
		<th>Importe Facturas</th>
		<th>Monto Pagado</th>
		<th>Edición</th>
	</tr>
	<link href="<?php echo base_url();?>css/default.css" rel="stylesheet"
		type="text/css">
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"Ver Formato de Pago\" border=\"0\">";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\"  border=\"0\">";
	$delete="";
	if($pagos==false){
		//echo "Sin Registros";
		echo "<br><div align=\"center\"><b>No hay Registros que Cumplan con el Criterio</b></div>";
	}
	else{
		foreach($pagos->all as $row) {
			if(substr(decbin($permisos), 1, 1)==1 ){
				$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_pago_multiple/$row->proveedor_id/$row->fecha_pago\" onclick=\"return confirm('¿Estas seguro que deseas borrar al usuario?');\" title='Eliminar pago de $row->proveedor'>$trash</a>";
			}
			echo "<tr background=\"$img_row\"><td>$row->fecha_pago</td><td>$row->proveedor</td><td>$row->marca</td><td align='right'>".number_format($row->monto_total,2,".",",")."</td><td align='right'>".number_format($row->monto_pagado,2,".",",")."</td><td><a href=\"".base_url()."index.php/contabilidad/contabilidad_reportes/rep_pagos_multiples_pdf/$row->proveedor_id/$row->fecha_pago\" target=\"_blank\">$photo</a> $delete</td></tr>";
		}
	}
	echo "</table></center>";
	if($paginacion==true)
		echo $this->pagination->create_links();
	?>