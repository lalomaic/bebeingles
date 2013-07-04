<script>
function send1(){
	document.report.submit();
}
$($.date_input.initialize);
</script>
<?php
//Titulo
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
$atrib=array('name'=>'report', 'target'=>"pdf");
echo form_open($ruta."/".$ruta."_reportes/rep_rentabilidad_pdf/", $atrib) . "\n";
echo form_hidden('subfuncion', "$subfuncion");
echo form_fieldset('<b>Cálculo de Gastos</b>') . "\n";
$img_row="".base_url()."images/table_row.png";
?>
<table width="100%" class='form_table'>
	<tr>
		<td>
			<table border="1">
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
	</tr>
	<tr>
		<td colspan='3'><iframe src='' name="pdf" width="100%" height='900'></iframe>
		</td>
	</tr>
</table>
</center>

