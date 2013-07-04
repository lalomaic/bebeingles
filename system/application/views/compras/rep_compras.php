<?php
// select 'proveedor'
$aux="<option value='0'>Todos</option>";
foreach($proveedores->all as $row){
	$aux.="<option value='$row->id'>$row->razon_social</option>";
}
$proveedor="<select name='proveedor' style='width:200px'>".$aux."</select>";
// select 'empresa'
$aux="<option value='0'>Todos</option>";
foreach($empresas->all as $row){
	$aux.="<option value='$row->id'>$row->razon_social</option>";
}
$empresa="<select name='empresa' style='width:200px'>".$aux."</select>";
// select 'estatus'
$aux="<option value='0'>Todos</option>";
foreach($estatus_pedidos->all as $row){
	$aux.="<option value='$row->id'>$row->tag</option>";
}
$estatus="<select name='estatus' style='width:200px'>".$aux."</select>";
echo "<h2>$title</h2>";
//Selects para ordenar la consulta
$options="<option value='0'>[- Seleccione -]</option>
<option value='1'>Empresa</option>
<option value='2'>Proveedor</option>
<option value='3'>Fecha de alta</option>
<option value='4'>Capturista</option>
<option value='5'>Estatus</option>
<option value='6'>Fecha de entrega</option>
<option value='7'>Monto total</option>
<option value='8'>Forma de pago</option>
<option value='9'>Fecha de pago</option>
";
$n = 9; // numero de selects
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
    document.report.submit();
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
					<td colspan="0">Proveedor:</td>
					<td colspan="3"><?php echo $proveedor;?>
					</td>
				</tr>
				<tr>
					<td colspan="0">Empresa:</td>
					<td colspan="3"><?php echo $empresa;?>
					</td>
				</tr>
				<tr>
					<td colspan="0">Estatus:</td>
					<td colspan="3"><?php echo $estatus;?>
					</td>
				</tr>
				<tr>
					<th colspan="4">Paso No 2 - Ordenar Por</th>
				</tr>
				<?
				foreach($selects as $i => $select)
				{
					// selects de los ordenamientos
					echo '<tr>'."\n";
					echo '<td>Nivel '.($i+1).' </td><td colspan="3">'.$select.'</td>'."\n";
					echo '</tr>';
				}
				?>
				<tr>
					<input type="hidden" name="title" value="<?=$title?>" />
					<td colspan="4" align="right"><button type="button"
							onclick="javascript:send1()">Informe</button>
						</form>
					</td>
				</tr>
			</table>
		</td>
		<td width="70%"><iframe src='' name="pdf" width="100%" height='700'></iframe>
		</td>
	</tr>
</table>
</center>
