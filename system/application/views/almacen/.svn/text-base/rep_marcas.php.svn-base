<?php
$select1="<option value='0'>Todos</option>";
$select2=$select1;
$select3=$select1;
$select4=$select1;
// Proveedores **************************
foreach($proveedores as $row){
	$select3 .= "<option value='$row->id'>".$row->razon_social."</option>";
}
$proveedor = "<select name='proveedor' style='width:200px'>".$select3."</select>";
// Producto **************************
echo "<h2>$title</h2>";
//Selects para ordenar la consulta
$options="<option value='0'>Elija</option>
<option value='1'>Proveedor</option>
<option value='2'>Marca</option>";

$n = 2; // numero de selects
$selects = array();
for($i = 0;$i < $n;$i++) {
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
					<td colspan="0">Proveedor</td>
					<td colspan="3"><?php echo $proveedor;?></td>
				</tr>
				<tr>
					<th colspan="4">Paso No 2 - Ordenar Por</th>
				</tr>
				<?php
				foreach($selects as $i=>$select) {
					echo '<tr><td>Nivel '.($i+1).'</td><td colspan="3">'.$select.'</td></tr>';
				}
				?>
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
