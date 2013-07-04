<script>
$(document).ready(function() {
	$($.date_input.initialize);
});
</script>

<?php 
$default="TODOS";
$select1[0]=$default;
echo "<h2>$title</h2>";
$atrib=array('name'=>'report', 'target'=>"pdf");
echo form_open($ruta."/".$ruta."_reportes/{$subfuncion}_pdf/", $atrib) . "\n";
echo form_fieldset('<b>Argumentos del Reporte</b>') . "\n";
$img_row="".base_url()."images/table_row.png";
?>
<table width="100%" class='form_table'>
	<tr>
		<th align="right">Tienda Deudora</th>
		<th align="left">Fecha Límite</th>
	</tr>
	<tr>
		<td align="right"><?php echo form_dropdown('espacio',$espacios, 0)?></td>
		<td>Año:<? echo form_dropdown('anio', $anios, 0);?><br />Mes:<? echo form_dropdown('mes', $meses, 0);?>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center"><?='<button type="submit" id="boton1" >Informe</button>'?>
			<?=form_close()?></td>
	</tr>
	<tr>
		<td width="100%" colspan='4'><iframe src='' name="pdf" width="100%"
				height='700'></iframe>
		</td>

</table>
