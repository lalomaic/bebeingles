<?php
$select1="<option value='0'>Todos</option>";
$select2=$select1;
$select3=$select1;
// familia **************************
$fams = array();
foreach($familias->all as $row){
	$select1 .= "<option value='{$row->id}'>".$row->tag."</option>";
	$fams[$row->id] = $row->clave;
}
$familia = "<select name='familia' style='width:200px'>".$select1."</select>";//familia
// subfamilia **************************
foreach($subfamilias->all as $row){
	$select2 .= "<option value='$row->id'>$row->tag</option>";
}
$subfamilia = "<select name='subfamilia' style='width:200px'>".$select2."</select>";//Subfamilia
// marca **************************
foreach($marcas->all as $row){
	$select3 .= "<option value='$row->id'>".$row->tag."</option>";
}
$marca = "<select name='marca' style='width:200px'>".$select3."</select>";//Marca

echo "<h2>$title</h2>";
//Selects para ordenar la consulta
$options="<option value='0'>Elija</option>
<option value='1'>Familia</option>
<option value='2'>Subfamilia</option>
<option value='3'>Marca</option>
<option value='4'>Descripcion</option>
";
$n = 4; // numero de selects
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

</script>
<?php
$atrib=array('name'=>'report', 'target'=>"pdf");
echo form_open($ruta."/".$ruta."_reportes/{$funcion}_pdf/", $atrib) . "\n";
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
					<td colspan="0">Familia:</td>
					<td colspan="3"><?php echo $familia;?></td>
				</tr>
				<tr>
					<td colspan="0">Subfamilia</td>
					<td colspan="3"><?php echo $subfamilia;?></td>
				</tr>
				<tr>
					<td colspan="0">Marca</td>
					<td colspan="3"><?php echo $marca;?></td>
				</tr>
				<tr>
					<th colspan="4">Paso No 2 - Ordenar Por</th>
				</tr>
				<tr>
					<td>Nivel 1</td>
					<td colspan="3"><?php echo $selects[0];?></td>
				</tr>
				<tr>
					<td>Nivel 2</td>
					<td colspan="3"><?php echo $selects[1];?></td>
				</tr>
				<tr>
					<td>Nivel 3</td>
					<td colspan="3"><?php echo $selects[2];?></td>
				</tr>
				<tr>
				</tr>
				<tr>
					<td>Nivel 4</td>
					<td colspan="3"><?php echo $selects[3];?></td>
				</tr>
				<tr>
				</tr>
				<tr>
					<input type="hidden" name="title" value="<?=$title?>" />
					<td colspan="4" align="right">
						<button type="button" onclick="javascript:send1()">Informe</button>
						</form>
					</td>
				</tr>
			</table>
		</td>
		<td width="70%"><iframe src='' name="pdf" width="100%" height='700'> </iframe>
		</td>
	</tr>
</table>
</center>
