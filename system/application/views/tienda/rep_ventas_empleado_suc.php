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
$atrib=array('name'=>'report', 'target'=>"pdf");
echo form_open($ruta."/".$ruta."_reportes/rep_ventas_empleado_suc_pdf/", $atrib) . "\n";
echo form_hidden('subfuncion', "$subfuncion");
echo form_fieldset('<b>Argumentos del Reporte</b>') . "\n";
$img_row="".base_url()."images/table_row.png";
?>
<table width=\ "100%\" class='form_table'>
	<tr>
		<td valign="top" width="200">
			<table border="1">
				<tr>
					<th colspan="4">Periodo de Tiempo</th>
				</tr>
				<tr>
					<td colspan="0">Fecha inicio:</td>
					<td colspan="3"><?php echo form_input($fecha1);?></td>
				</tr>
				<tr>
					<td colspan="0">Fecha final</td>
					<td colspan="3"><?php echo form_input($fecha2);?></td>
				</tr>
				<tr>
					<td colspan="4" align="right"><button type="button"
							onclick="javascript:send1()">Informe</button>
						</form></td>
				</tr>
			</table>
		</td>
		<td><iframe src='' name="pdf" width=\ "100%\" height='700'></iframe>
		</td>
	</tr>
</table>
</center>
<script>
  function send1(){
    document.report.submit();
  }
  $($.date_input.initialize);
</script>

