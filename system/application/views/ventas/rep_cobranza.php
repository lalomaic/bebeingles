<?php
$select2="<option value='0'>[ Seleccione ]</option>";
// empresa **************************
foreach($empresas as $row){
	$select2 .= "<option value='$row->id'>$row->razon_social</option>";
}
$empresa = "<select id='empresa' name='empresa' style='width:200px'>".$select2."</select>";

echo "<h2>$title</h2>";
//Selects para ordenar la consulta
$options="<option value='0'>Elija</option>
<option value='1'>Familia</option>
<option value='2'>Subfamilia</option>
<option value='3'>Marca</option>
<option value='4'>Descripcion</option>
";
$fecha1=array(
		'class'=>'date_input',
		'name'=>'fecha1',
		'size'=>'10',
		'readonly'=>'readonly',
		'value'=>'',
);
$n = 4; // numero de selects
$selects = array();
for($i = 0;$i < $n;$i++)
{
	$selects[] = "<select name='nivel_".($i+1)."'>".$options."</select>";
}
?>
<script
	src="<?php echo base_url();?>css/calendar.js"></script>
<script
	src="<?php echo base_url();?>css/calendar.js"></script>
<script>
function send1(){
	document.forms[0].submit();
	}

jQuery(document).ready(
	function(){
		jQuery(jQuery.date_input.initialize);
		jQuery('#empresa').change(
			function(){
				jQuery('#espacios').html('Espere...');
				jQuery.post(
					'<?php echo site_url("/ajax_pet/get_espacios")?>',
					{empresa_id:jQuery(this).val(),nombre:'espacio',todos:true},
					function(data){
						jQuery('#espacios').html(data);
						}
					);
				}
			);
		}
	);
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
					<td colspan="0">Empresa</td>
					<td colspan="3"><?php echo $empresa;?></td>
				</tr>
				<tr>
					<td colspan="0">Espacio físico</td>
					<td colspan="3">
						<div id="espacios">[ Seleccione una empresa ]</div>
					</td>
				</tr>
				<tr>
					<td colspan="0">Fecha:</td>
					<td colspan="3"><?=form_input($fecha1)?></td>
				</tr>
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
