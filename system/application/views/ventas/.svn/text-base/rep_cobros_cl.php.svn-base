<?php
$select2="<option value='0'>Todos</option>";
$select3=$select1='';
# empresas **************************
foreach($empresas as $row){
	$select1 .= "<option value='{$row->id}'>".$row->razon_social."</option>";
}
$empresa = "<select name='empresa' style='width:200px'>".$select1."</select>";
# clientes **************************
foreach($clientes as $row){
	$select2 .= "<option value='{$row->id}'>".$row->razon_social."</option>";
}
$cliente = "<select name='cliente' style='width:200px'>".$select2."</select>";
# espacios **************************
foreach($espacios as $row){
	$select3 .= "<option value='{$row->id}'>".$row->tag."</option>";
}
$espacio = "<select name='espacio' style='width:200px'>".$select3."</select>";

echo "<h2>$title</h2>";
# Selects para ordenar la consulta
$options="<option value='0'>Elija</option>
<option value='1'>Fecha</option>
<option value='2'>Monto pagado</option>";
$fecha1=array(
		'class'=>'date_input',
		'name'=>'fecha1',
		'size'=>'10',
		'readonly'=>'readonly',
		'value'=>''
);
$fecha2=array(
		'class'=>'date_input',
		'name'=>'fecha2',
		'size'=>'10',
		'readonly'=>'readonly',
		'value'=>''
);

$n = 2; # numero de selects
$selects = array();
for($i = 0;$i < $n;$i++)
{
	$selects[] = "<select name='nivel_".($i+1)."'>".$options."</select>";
}
?>
<script
	src="<?php echo base_url();?>css/calendar.js"></script>
<script>
  function send1(){
    document.forms[0].submit();
  }
$($.date_input.initialize);
</script>
<?php
$atrib=array('name'=>'report', 'target'=>"pdf");
echo form_open($ruta."/".$ruta."_reportes/{$funcion}_pdf/", $atrib) . "\n";
echo form_fieldset('<b>Argumentos del Reporte</b>') . "\n";
$img_row=base_url()."images/table_row.png";
?>
<table width="100%" class='form_table'>
	<tr>
		<td width="30%" valign="top">
			<table border="1">
				<tr>
					<th colspan="4">Paso No 1 - Filtrar Concentrado</th>
				</tr>
				<tr>
					<td colspan="0">Empresa</td>
					<td colspan="3"><?php echo $empresa;?></td>
				</tr>
				<tr>
					<td colspan="0">Espacio fisico</td>
					<td colspan="3"><?php echo $espacio;?></td>
				</tr>
				<tr>
					<td colspan="0">Cliente</td>
					<td colspan="3"><?php echo $cliente;?></td>
				</tr>
				<tr>
					<td colspan="0">Fecha:</td>
					<td colspan="3">Del <?=form_input($fecha1)?> <br />Al <?=form_input($fecha2)?>
					</td>
				</tr>
				<tr>
					<th colspan="4">Paso No 2 - Ordenar Por</th>
				</tr>
				<?php
				foreach($selects as $i=>$select)
				{
					echo '<tr><td>Nivel '.($i+1).'</td><td colspan="3">'.$select.'</td></tr>';
				}
				?>
				<tr>
					<input type="hidden" name="title" value="<?=$title?>" />
					<td colspan="4" align="right"><input type="reset"
						value="Borrar campos">&nbsp;&nbsp;&nbsp;
						<button type="button" onclick="javascript:send1()">Informe</button>
						</form></td>
				</tr>
			</table>
		</td>
		<td width="70%"><iframe src='' name="pdf" width="100%" height='700'> </iframe>
		</td>
	</tr>
</table>
</center>
