<?php
$select1="<option value='0'>Todos</option>";
$seltipos=$seltipoe=$select1;
$select3='';
# tipos de salidas // 24.06.2010
foreach($tipo_s as $row)
	$seltipos.="<option value='".$row->id."'>".$row->tag."</option>";
$seltipos = "<select name='tipo_s' style='width:200px'>".$seltipos."</select>";
# tipos de entradas
foreach($tipo_e as $row)
	$seltipoe.="<option value='".$row->id."'>".$row->tag."</option>";
$seltipoe = "<select name='tipo_e' style='width:200px'>".$seltipoe."</select>";
// familia **************************
$fams = array();
foreach($familias as $row){
	$select1 .= "<option value='{$row->id}'>".$row->tag."</option>";
	$fams[$row->id] = $row->clave;
}
$familia = "<select id='familia' name='familia' style='width:200px'>".$select1."</select>";
// espacios fisicos **************************
foreach($espacios as $row){
	$select3 .= "<option ".(($row->id==2)?('selected'):'')." value='$row->id'>".$row->tag."</option>";
}
$espacio = "<select name='espacio' style='width:200px'>".$select3."</select>";

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
					<td colspan="0">Espacio fÃ­sico</td>
					<td colspan="3"><?php echo $espacio;?></td>
				</tr>
				<tr>
					<td colspan="0">Familia:</td>
					<td colspan="3"><?php echo $familia;?></td>
				</tr>
				<tr>
					<td colspan="0">Subfamilia</td>
					<td colspan="3">
						<div id="subfamilias">[ Seleccione una familia ]</div>
					</td>
				</tr>
				<tr>
					<td colspan="0">Producto</td>
					<td colspan="3">
						<div id="productos">[ Seleccione una subfamilia ]</div>
					</td>
				</tr>
				<tr>
					<td colspan="0">Tipo de entrada:</td>
					<td colspan="3"><?php echo $seltipoe?>
					</td>
				</tr>
				<tr>
					<td colspan="0">Tipo de salida:</td>
					<td colspan="3"><?php echo $seltipos?>
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
					<td colspan="4">
						<p>
							<label><input type="radio" value="familia" name="orden" checked>
								Familia</label>
						</p>
						<p>
							<label><input type="radio" value="producto" name="orden">
								Producto</label>
						</p>
					</td>
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
