<?php
$select1="<option value='0'>Todos</option>";
$select2=$select1;

//EMpresas
$empresa[0]="Todas";
if($empresas!= false){
	foreach($empresas->all as $row){
		$y=$row->id;
		$empresa[$y]=$row->razon_social;
	}
}
// Espacio Fisico **************************
foreach($espacios as $row){
	$select2 .= "<option value='$row->id'>$row->tag</option>";
}
$espaciof = "<select name='espaciof' style='width:200px'>".$select2."</select>";
echo "<h2>$title</h2>";
//Selects para ordenar la consulta
$options="<option value='0'>Elija</option>
<option value='1'>Fecha</option>
<option value='2'>Espacio Fisico</option>";

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

$n = 2; // numero de selects
$selects = array();
for($i = 0;$i < $n;$i++)
{
	$selects[] = "<select name='nivel_".($i+1)."'>".$options."</select>";
}
?>
<script>
  function send1(){
    document.forms[0].submit();
  }
$($.date_input.initialize);
</script>
<?php
$atrib=array('name'=>'report', 'target'=>"pdf");
echo form_open($ruta."/".$ruta."_reportes/rep_depositos_tiendas_pdf/", $atrib) . "\n";
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
					<td colspan="3"><?php echo form_dropdown("empresa", $empresa, 0, "style='width:200px'"); ?>
					</td>
				</tr>
				<tr>
					<td colspan="0">Espacio fisico</td>
					<td colspan="3"><?php echo $espaciof;?></td>
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
					<td colspan="4" align="right"><input type="hidden" name="title"
						value="<?=$title?>" />
						<button type="button" onclick="javascript:send1()">Informe</button>
						</form></td>
				</tr>
			</table>
		</td>
	
	
	<tr>
		<td width="70%"><iframe src='' name="pdf" width="100%" height='700'></iframe>
		</td>
	</tr>
</table>
