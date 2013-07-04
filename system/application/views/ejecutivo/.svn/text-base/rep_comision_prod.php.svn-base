<?php
$select1="<option value='0'>Todos</option>";
$select2="<option value='0'>[ Seleccione ]</option>";
// familia **************************
$fams = array();
foreach($familias as $row){
	$select1 .= "<option value='{$row->id}'>".$row->tag."</option>";
	$fams[$row->id] = $row->clave;
}
$familia = "<select id='familia' name='familia' style='width:200px'>".$select1."</select>";
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
$fecha2=array(
		'class'=>'date_input',
		'name'=>'fecha2',
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
			function()
				{
				jQuery('#espacios').html('Espere...');
				jQuery.post(
					'<?php echo site_url("/ajax_pet/get_espacios")?>',
					{empresa_id:jQuery(this).val(),nombre:'espacio'},
					function(data){
						jQuery('#espacios').html(data);
						}
					);
				}
			);
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
					<td colspan="0">Comision (0-100):</td>
					<td colspan="3"><input type="text" maxlength="5" name="comision"
						size="6" value="0" />
					</td>
				</tr>
				<tr>
					<input type="hidden" name="title" value="<?=$title?>" />
					<td colspan="4" align="right"><input type="reset"
						value="Borrar campos">&nbsp;&nbsp;&nbsp;
						<button type="button" onclick="javascript:send1()">Informe</button>
						</form></td>
				</tr>
			</table> <br />

			<p
				style="background-color: #FFFF99; border: 1px dashed Red; padding: 5px; text-align: justify;">
				<b style='color: red;'>NOTA</b>: Si la comision se deja en 0, el
				reporte se hace con la comision establecida para los productos y se
				excluyen aquellos que tienen 0% de comision. Si se establece un
				valor de comision, el reporte se hace de las ventas totales, sin
				excluir productos y se calcula con el valor indicado.
			</p>
		</td>
		<td width="70%"><iframe src='' name="pdf" width="100%" height='700'> </iframe>
		</td>
	</tr>
</table>
</center>
