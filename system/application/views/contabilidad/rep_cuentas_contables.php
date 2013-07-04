<?php
//Construir Select cuentas principales
$select2[0]="Todos";
if($cuentas_select!=false){
	foreach($cuentas_select->all as $row){
		$y=$row->cta;
		$select2[$y]=$row->cta. " - ".$row->tag;
	}
}
?>
<script>
  function send1(){
    document.forms[0].submit();
  }
</script>
<?php
$atrib=array('name'=>'report', 'target'=>"pdf");
echo form_open($ruta."/".$ruta."_reportes/rep_cuentas_contables_pdf/", $atrib) . "\n";
echo form_fieldset('<b>Argumentos del Reporte</b>') . "\n";
$img_row=base_url()."images/table_row.png";
?>
<table width="100%" class='form_table'>
	<tr>
		<td width="30%" valign="top">
			<table border="1">
				<tr>
					<th colspan="4">Paso No 1 - Filtrar Cuentas Bancarias</th>
				</tr>
				<tr>
					<td colspan="0">Cuentas Principales</td>
					<td colspan="3"><?php echo form_dropdown("cuenta", $select2, 0, "style='width:200px'"); ?>
					</td>
				</tr>
				<tr>
					<td colspan="4" align="right"><input type="hidden" name="title"
						value="<?=$title?>" />
						<button type="button" onclick="javascript:send1()">Informe</button>
						</form></td>
				</tr>
			</table>
		</td>
	
	
	<tr>
		<td width="70%"><iframe src='' name="pdf" width="100%" height='700'></iframe>
		</td>
	</tr>
</table>
