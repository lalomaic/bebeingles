<?php
$select1="<option value='0'>Todos</option>";
$select2=$select1;
$select3=$select1;
$select4=$select1;
// Espacio Fisico **************************
foreach($espaciosf as $row){
	$select2 .= "<option value='$row->id'>$row->tag</option>";
}
$espaciof = "<select name='espaciof' style='width:200px'>".$select2."</select>";
// Proveedores **************************
foreach($clientes as $row){
	$select3 .= "<option value='$row->id'>".$row->razon_social."</option>";
}
$cliente = "<select name='cliente' style='width:200px'>".$select3."</select>";
// familia **************************
foreach($familias as $row){
	$select1 .= "<option value='{$row->id}'>".$row->tag."</option>";
}
$familia = "<select id='familia' name='familia' style='width:200px'>".$select1."</select>";

echo "<h2>$title</h2>";
//Selects para ordenar la consulta
$options="<option value='0'>Elija</option>
<option value='1'>Producto</option>
<option value='2'>Cantidad</option>
<option value='3'>Unidad</option>";
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

$n = 3; // numero de selects
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
jQuery(document).ready(
	function(){
		jQuery(jQuery.date_input.initialize);
		jQuery('#familia').change(
			function(){
				jQuery('#subfamilias').html('Espere...');
				jQuery.post(
					'<?php echo site_url("/ajax_pet/get_subfamilias")?>',
					{ fam_id:jQuery(this).val(),nombre:'subfamilia'},
					function(data){
						jQuery('#subfamilias').html(data);
						jQuery('#subfamilia').change(
							function(){
								jQuery('#productos').html('Espere...');
								jQuery.post(
									'<?php echo site_url("/ajax_pet/get_productos")?>',
									{subfam_id:jQuery(this).val(),nombre:'producto'},
									function(data){
										jQuery('#productos').html(data);
										}
									);
								}
							);
						jQuery('#productos').html('[ Seleccione una subfamilia ]');
						}
					);
				}
			);
		}
	);
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
					<td colspan="0">Espacio fisico</td>
					<td colspan="3"><?php echo $espaciof;?></td>
				</tr>
				<tr>
					<td colspan="0">Cliente</td>
					<td colspan="3"><?php echo $cliente;?></td>
				</tr>
				<tr>
					<td colspan="0">Familia:</td>
					<td colspan="3"><?php echo $familia;?></td>
				</tr>
				<tr>
					<td colspan="0">Subfamilia:</td>
					<td colspan="3">
						<div id="subfamilias">[ Seleccione una familia ]</div>
					</td>
				</tr>
				<tr>
					<td colspan="0">Producto:</td>
					<td colspan="3">
						<div id="productos">[ Seleccione una subfamilia ]</div>
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
				<?php
				foreach($selects as $i=>$select)
				{
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
