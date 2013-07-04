<?php
$select1="<option value='0'>Todos</option>";
$select2=$select1;
$select3=$select1;
if($facturas){
	foreach($facturas->all as $row){
		$select1.="<option value='$row->id'>$row->folio_factura</option>";
	}
}
$factura="<select name='factura' style='width:200px'>".$select1."</select>";
if($forma_cobros){
	foreach($forma_cobros->all as $row){
		$select2.="<option value='$row->id'>$row->tag</option>";
	}
}
$forma_cobro="<select name='forma_cobro' style='width:200px'>".$select2."</select>";
if($tipo_cobros){
	foreach($tipo_cobros->all as $row){
		$select3.="<option value='$row->id'>$row->tag</option>";
	}
}
$tipo_cobro="<select name='tipo_cobro' style='width:200px'>".$select3."</select>";

//Titulo
echo "<h2>$title</h2>";
//Select para ordenar la consulta
$options="<option value='0'>Elija</option>
<option value='1'>Fecha</option>
<option value='2'>Factura</option>
<option value='3'>Forma de Cobro</option>
<option value='4'>Tipo de Cobro</option>";
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
$select3="<select name='nivel3'>".$options."</select>";
$select4="<select name='nivel4'>".$options."</select>";
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
echo form_open($ruta."/".$ruta."_reportes/rep_cobros_pdf/", $atrib) . "\n";
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
					<td colspan="0">Factura:</td>
					<td colspan="3"><?php echo $factura;?>
					</td>
				</tr>
				<tr>
					<td colspan="0">Forma de Cobro:</td>
					<td colspan="3"><?php echo $forma_cobro;?>
					</td>
				</tr>
				<tr>
					<td colspan="0">Tipo de Cobro:</td>
					<td colspan="3"><?php echo $tipo_cobro;?>
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
					<td>Nivel 3</td>
					<td colspan="3"><?php echo $select3;?>
					</td>
				</tr>
				<tr>
					<td>Nivel 4</td>
					<td colspan="3"><?php echo $select4;?>
					</td>
				</tr>
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
