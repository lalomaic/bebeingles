<?php
//Construir Empresas
$select2="<option value='0'>Todos</option>";
$select3=$select1='';
foreach($empresas as $row){
	$select1.="<option value='$row->id'>$row->razon_social</option>";
}
$empresa="<select name='empresa' style='width:200px'>".$select1."</select>";
foreach($proveedores as $row){
	$select2.="<option value='$row->id'>$row->razon_social</option>";
}
$proveedor="<select name='proveedor' style='width:200px'>".$select2."</select>";//Titulo
# espacios **************************
foreach($espacios as $row){
	$select3 .= "<option value='{$row->id}'>".$row->tag."</option>";
}
$espacio = "<select name='espacio' style='width:200px'>".$select3."</select>";
echo "<h2>$title</h2>";
//Select para ordenar la consulta
$options="<option value='0'>Elija</option>
<option value='1'>Fecha</option>
<option value='2'>Fecha de pago</option>";
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
$select1="<select name='nivel1'>".$options."</select>";
$select2="<select name='nivel2'>".$options."</select>";
?>
<script
	src="<?php echo base_url();?>css/calendar.js"></script>
<script>
  function send1(){
    document.report.submit();
  }
  
$($.date_input.initialize);
</script>
<?php 
$atrib=array('name'=>'report', 'target'=>"pdf");
echo form_open($ruta."/".$ruta."_reportes/rep_pr_facturas_pdf/", $atrib) . "\n";
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
					<td colspan="0">Empresas:</td>
					<td colspan="3"><?php echo $empresa;?>
					</td>
				</tr>
				<tr>
					<td colspan="0">Espacio fisico:</td>
					<td colspan="3"><?php echo $espacio;?>
					</td>
				</tr>
				<tr>
					<td colspan="0">Proveedor:</td>
					<td colspan="3"><?php echo $proveedor;?>
					</td>
				</tr>
				<tr>
					<td colspan="0">Fecha:</td>
					<td colspan="3">Del <?=form_input($fecha1)?> <br />Al <?=form_input($fecha2)?>

					</td>
				</tr>
				<tr>
					<th colspan="4">Paso No 2 - Ordenar Por</th>
				</tr>
				<tr>
					<td>Nivel 1</td>
					<td colspan="3"><?php echo $select1;?>
					</td>
				</tr>
				<tr>
					<td>Nivel 2</td>
					<td colspan="3"><?php echo $select2;?>
					</td>
				</tr>
				<tr>
					<input type="hidden" name="title" value="<?=$title?>" />
					<td colspan="4" align="right"><input type="reset"
						value="Borrar campos">&nbsp;&nbsp;&nbsp;
						<button type="button" onclick="javascript:send1()">Informe</button>
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