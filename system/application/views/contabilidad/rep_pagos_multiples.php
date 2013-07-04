<script>
$(document).ready(function() {
	$('#proveedores').select_autocomplete();
	$($.date_input.initialize);
	$('#pdf').hide();
});
	function enviar(){
		$('#pdf').show('slow');
		$("#form1").submit();
	}
	
</script>

<?php 
$default="Elija";
$select1[0]=$default;
if($proveedores!= false){
	foreach($proveedores as $row){
		$y=$row->id;
		$select1[$y]=$row->razon_social;
	}
}

$fecha1=array(
		'class'=>'date_input',
		'name'=>'fecha1',
		'size'=>'10',
		'readonly'=>'readonly',
		'value'=>'',
);
echo "<h2>$title</h2>";

$atrib=array('name'=>'report', 'target'=>"pdf", 'id'=>'form1');
$img_row="".base_url()."images/table_row.png";
?>
<table width="100%" class='form_table'>
	<tr>
		<td width="30%" valign="top"><? echo form_open($ruta."/".$ruta."_reportes/{$subfuncion}_pdf/", $atrib) . "\n";
			echo form_fieldset('<b>Argumentos del Reporte</b>') . "\n"; ?>
			<table border="1">
				<tr>
					<th colspan="4">Paso No 1 - Filtrar Concentrado</th>
				</tr>
				<tr>
					<td colspan="0">Proveedor</td>
					<td colspan="3"><?php echo form_dropdown('proveedor',$select1, 0, "id='proveedores'")?>
					</td>
				</tr>
				<tr>
					<td colspan="0">Fecha de <br />Pago:
					</td>
					<td colspan="0"><?=form_input($fecha1)?> <br /></td>
					<td colspan="0" align="center">Si se deja en blanco<br>se toma la
						fecha de hoy
					</td>
				</tr>
				<tr>
					<td colspan="4" align="right"><?php echo '<button type="button" onclick="javascript:enviar();" id="boton1" >Informe</button>'?>
					</td>
				</tr>
			</table> <?php echo form_close()?>
		</td>
	</tr>
	<td width="70%"><iframe src='' name="pdf" width="100%" height='700'
			id='pdf'> </iframe>
	</td>
</table>
