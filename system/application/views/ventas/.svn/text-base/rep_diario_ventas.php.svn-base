<script src="<?php echo base_url();?>css/calendar.js"></script>
<script>
$(document).ready(function() {
	$($.date_input.initialize);
});
</script>

<?php 
$default="TODOS";
$select1[0]=$default;
if($espacios!= false){
	foreach($espacios as $row){
		$y=$row->id;
		$select1[$y]=$row->tag;
	}
}
$fecha=array(
		'class'=>'date_input',
		'name'=>'fecha',
		'size'=>'10',
		'readonly'=>'readonly',
		'value'=>'',
);

echo "<h2>$title</h2>";

$atrib=array('name'=>'report', 'target'=>"pdf");
echo form_open($ruta."/".$ruta."_reportes/formulario/$subfuncion/pdf/", $atrib) . "\n";
echo form_fieldset('<b>Argumentos del Reporte</b>') . "\n";
$img_row="".base_url()."images/table_row.png";
?>
<table width="100%" class='form_table'>
	<tr>
		<td width="30%" valign="top">
			<table border="1">
				<tr>
					<th colspan="4">Paso No 1 - Filtrar Concentrado</th>
				</tr>
				<tr>
					<td colspan="0">Espacio Físico</td>
					<td colspan="3"><?php echo form_dropdown('espacio',$select1, 0)?></td>
				</tr>
				<tr>
					<td colspan="0">Fecha:</td>
					<td colspan="3"><?=form_input($fecha)?></td>
				</tr>
				<tr>
					<input type="hidden" name="title" value="<?=$title?>" />
					<td colspan="4" align="right"><?php echo '<button type="submit" id="boton1" >Informe</button>'?>

						</form></td>
				</tr>
			</table>
		</td>
		<?php echo form_close()?>
	</tr>
	<td width="70%"><iframe src='' name="pdf" width="100%" height='700'></iframe>
	</td>
</table>
</center>
