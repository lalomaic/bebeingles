<script>
$(document).ready(function() {
	$($.date_input.initialize);
	$('#proveedor').val('');
	  $('#proveedores_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_proveedores_ajax/', {
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
		  $("#proveedor").val(""+item.id);
	  });

	  $('#cmarca_id').val('');
	  $('#marca_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_marcas_ajax/', {
			extraParams: {pid: function() { return $("#proveedor").val(); } },
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
		  get_proveedor_marca_ajax(item.id);
	  });
});
	function format(r) {
		return r.descripcion;
	}

function send1(){
	document.report.submit();
}
	function get_proveedor_marca_ajax(mid){
		//Obtener los datos via ajax_pet
		if(mid>0){
			$.post("<? echo base_url();?>index.php/ajax_pet/get_proveedor_marca_ajax/", { arg_id1: mid},       //function that is called when server returns a value.
			function(data1){
				data=JSON.parse(data1);
				$('#proveedores_drop').val(data[0].descripcion);
				$('#proveedor').val(data[0].id);

			});
		}
		//Procesarlos y escribirlos
	}
</script>
<?php
//Titulo
echo "<h2>$title</h2>";
$select3[0]="TODAS";
if($espacios!=false){
	foreach($espacios->all as $row){
		$y=$row->id;
		$select3[$y]=$row->tag;
	}
}

$fecha1=array(
		'class'=>'date_input',
		'name'=>'fecha1',
		'size'=>'10',
		'readonly'=>'readonly',
		'value'=>'',
);
$fecha2=array(
		'class'=>'date_input',
		'name'=>'fecha2',
		'size'=>'10',
		'readonly'=>'readonly',
		'value'=>'',
);

$atrib=array('name'=>'report', 'target'=>"pdf");
echo form_open($ruta."/".$ruta."_reportes/rep_pr_pedidos_pendientes_pdf/", $atrib) . "\n";
echo form_hidden('subfuncion', "$subfuncion");
echo form_fieldset('<b>Cálculo de Gastos</b>') . "\n";
$img_row="".base_url()."images/table_row.png";
?>
<table width="100%" class='form_table'>
	<tr>
		<th>Filtrar por Sucursal</th>
		<th valign="top">Filtrar por Proveedor</th>
		<th valign="top">Filtrar por Marca</th>

	</tr>
	<tr>
		<td align="center"><? echo form_dropdown('espacio', $select3, 0);?></td>
		<td><? echo "<div align=\"center\"><input type='hidden' name='proveedor' id='proveedor' value='' size=\"3\"><input id='proveedores_drop' class='proveedor' value='' size='40'></div>"; ?>
		</td>
		<td align="center"><? echo "<div align=\"center\"><b><input type='hidden' name='cmarca_id' id='cmarca_id' value='' size=\"3\"><input id='marca_drop' class='marca' name='marca_drop' value='' size='30'></b></div>"; ?>
		</td>
	</tr>
	<tr>
		<td colspan="3"><center>
				<button type="button" onclick="javascript:send1()">Informe</button>
			</center></td>
	</tr>
	<tr>
		<td colspan='3'><iframe src='' name="pdf" width="100%" height='900'></iframe>
		</td>
	</tr>
</table>
<? form_close();?>
</center>

