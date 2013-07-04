<?php
$select1="<option value='0'>TODOS</option>";
$select2="<option value='0'>[ SELECCIONE ]</option>";
// familia **************************
$fams = array();
foreach($familias as $row){
	$select1 .= "<option value='{$row->id}'>".$row->tag."</option>";
	$fams[$row->id] = $row->clave;
}
$familia = "<select id='familia' name='familia' style='width:200px'>".$select1."</select>";

//Tipo de Factura
$select3=array();
if($subfamilias!= false){
	foreach($subfamilias->all as $row){
		$y=$row->id;
		$select3[$y]=$row->tag;
	}
}
$select3[0]="TODOS";
// empresa **************************
foreach($empresas as $row){
	$select2 .= "<option value='$row->id'>$row->razon_social</option>";
}
$empresa = "<select id='empresa' name='empresa' style='width:200px'>".$select2."</select>";

echo "<h2>$title</h2>";
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
					'<?php echo site_url("/ajax_pet/get_espacios_venta")?>',
					{empresa_id:jQuery(this).val(),nombre:'espacio'},
					function(data){
						jQuery('#espacios').html(data);
						}
					);
				}
			);


		$('#cmarca_id').val('0');
		$('#marca_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_marcas_ajax/', {
			extraParams: {pid: 0 },
			minLength: 3,
			multiple: false,
			noCache: true,
			parse: function(data) {
				return $.map(eval(data), function(row) {
					return {
						data: row,
						value: row.id,
						result: row.descripcion
					}
				});
			},
			formatItem: function(item) {
				return format(item);
			}
		}).result(function(e, item) {
			$("#cmarca_id").val(""+item.id);
		});
	});


	function format(r) {
		return r.descripcion;
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
					<td colspan="0">Departamento:</td>
					<td colspan="3"><?php echo $familia;?></td>
				</tr>
				<tr>
					<td colspan="0">Tipo:</td>
					<td colspan="3">
						<div id="subfamilias">
							<? echo form_dropdown('subfamilia', $select3, 0);?>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="0">Marca:</td>
					<td colspan="3">
						<div id="productos">
							<input type='hidden' name='cmarca_id' id='cmarca_id' value='0'
								size="3"><input id='marca_drop' class='marca' value='TODOS'
								size='20'>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="0">Periodo de Tiempo:</td>
					<td colspan="3">Del <?=form_input($fecha1)?> <br />Al <?=form_input($fecha2)?>
					</td>
				</tr>
				<tr>
					<input type="hidden" name="title" value="<?=$title?>" />
					<td colspan="4" align="right"><input type="reset"
						value="Borrar campos">&nbsp;&nbsp;&nbsp;
						<button type="button" onclick="javascript:send1()">Informe</button>
						</form></td>
				</tr>
			</table> <br /> <!--			<p style="background-color: #FFFF99; border: 1px dashed Red; padding: 5px;text-align: justify;">
    <b style='color:red;'>NOTA</b>: Si la comision se deja en 0, el reporte se hace con la comision establecida para los
			productos y se excluyen aquellos que tienen 0% de comision. Si se establece un valor de comision,
			el reporte se hace de las ventas totales, sin excluir productos y se calcula con el valor indicado.
   </p>-->
		</td>
	</tr>
	<tr>
		<td width="70%"><iframe src='' name="pdf" width="100%" height='700'> </iframe>
		</td>
	</tr>
</table>
</center>
