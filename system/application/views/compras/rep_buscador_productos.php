<?php
echo "<h2>$title</h2>";

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

?>
<script>
function send1(){
	$('#frame').show('slow');
	document.forms[0].submit();
}

$(document).ready(function(){

	$($.date_input.initialize);
	$('#frame').hide();
	$('#producto_id').val('0');
	$("#prod").autocomplete('<?=base_url()?>index.php/ajax_pet/get_productos/', {
			extraParams: {pid: function() { return 0; }, mid: function() { return 0; }},
			minLength: 5,
			multiple: false,
			cacheLength:0,
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
			$("#producto_id").val(""+item.id);
	  });
 $('#espacio_fisico_id').val('');
	  $('#espacio_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_espacios_ajax/', {
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
</script>
<?php
$atrib=array('name'=>'report', 'target'=>"pdf");
echo form_open($ruta."/".$ruta."_reportes/{$subfuncion}_html/", $atrib) . "\n";
echo form_fieldset('<b>Argumentos del Reporte</b>') . "\n";
$img_row="".base_url()."images/table_row.png";
?>
<table width="100%" class='form_table'>
	<tr>
		<td width="30%" valign="top">
			<table border="1">
				<tr>
					<th>Paso No 1 - Ingrese el Producto</th>
					<th>Paso No 2 - Seleccione el Periodo</th>
					<th>Paso No 3 - Seleccione la Sucursal</th>
				</tr>
				<tr>
					<td><input type='hidden' name='producto' id='producto_id' value='0'
						size="3"><input id='prod' class='prod' value='' size='50'
						name="tag_producto"></td>
					<td align="right">Inicio <?=form_input($fecha1)?><br />Final <?=form_input($fecha2)?>
					</td>
					<td><input type='hidden' name='espacio_fisico_id'
						id='espacio_fisico_id' value='0' size="3"><input id='espacio_drop'
						class='espacio' value='TODAS' size='40'></td>
				</tr>
				<tr>
					<td colspan="2" align="right"><input type="reset"
						value="Borrar campos">&nbsp;&nbsp;&nbsp;
						<button type="button" onclick="javascript:send1()">Mostrar</button>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="70%"><iframe src='' name="pdf" width="100%" height='900'
				id='frame'></iframe>
		</td>
	</tr>
</table>
</center>
</form>
