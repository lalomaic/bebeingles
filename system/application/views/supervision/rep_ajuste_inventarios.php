<?php
//Construir Espacios Fisicos
$select2=$default;
foreach($espacios->all as $row){
	$y=$row->id;
	$select2.="<option value='$y'>$row->clave - $row->tag</option>";

}
$espacios="<select name='espacios'>".$select2."</select>";
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

?>
<script>
  function send1(){
    document.report.submit();
  }
</script>
<?php 
$atrib=array('name'=>'report', 'target'=>"pdf");
echo form_open($ruta."/".$ruta."_reportes/rep_ajuste_inventario_pdf/", $atrib) . "\n";
echo form_hidden('subfuncion', "$subfuncion");
echo form_fieldset('<b>Argumentos del Reporte</b>') . "\n";
$img_row="".base_url()."images/table_row.png";
?>
<table width=\ "100%\" class='form_table'>
	<tr>
		<td width="30%" valign="top">
			<table border="1">
				<tr>
					<th colspan="4">Paso No 1 - Filtrar</th>
				</tr>
				<tr>
					<td colspan="0">Ubicaciones</td>
					<td colspan="3"><?php echo $espacios;?></td>
				</tr>
				<tr>
					<th colspan="4">Paso No 2 - Periodo</th>
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
		<td width="70%"><iframe src='' name="pdf" width=\ "100%\" height='700'></iframe>
		</td>
	</tr>
</table>
</center>

