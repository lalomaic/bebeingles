<script>
$(document).ready(function() {
	$($.date_input.initialize);
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

function send1(){
	document.report.submit();
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
echo form_open($ruta."/".$ruta."_reportes/rep_resurtidos_marcas_pdf/", $atrib) . "\n";
echo form_hidden('subfuncion', "$subfuncion");
echo form_fieldset('<b>Cálculo de Gastos</b>') . "\n";
$img_row="".base_url()."images/table_row.png";
?>
<table width="100%" class='form_table'>
	<tr>
		<th>Paso No.1 Elija la Sucursal</th>
		<th valign="top">Paso No 2 - Periodo del Reporte</th>
		<th valign="top">Paso No 3 - Elija la Marca</th>

	</tr>
	<tr>
		<td align="center"><? echo form_dropdown('espacio', $select3, 0);?></td>
		<td>
			<table border="0">
				<tr>
					<td colspan="0">Fecha:</td>
					<td colspan="3">Del<?=form_input($fecha1)?><br />Al<?=form_input($fecha2)?>
					</td>
				</tr>
				<tr>
					<td colspan="4" align="right"><button type="button"
							onclick="javascript:send1()">Informe</button>
						</form></td>
				</tr>
			</table>
		</td>
		<td align="center"><? echo "<div align=\"center\"><b><input type='hidden' name='cmarca_id' id='cmarca_id' value='' size=\"3\"><input id='marca_drop' class='marca' name='marca_drop' value='' size='30'></b></div>"; ?>
		</td>
	</tr>
	<tr>
		<td colspan='3'><iframe src='' name="pdf" width="100%" height='900'></iframe>
		</td>
	</tr>
</table>
<? form_close();?>
</center>

