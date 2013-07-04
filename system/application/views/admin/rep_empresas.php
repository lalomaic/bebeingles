<?php
//Titulo
echo "<h2>$title</h2>";
//Select para ordenar la consulta
$options="<option value='0'>Elija</option><option value='1'>Nombre</option><option value='2'>Ciudad</option>";
$select1="<select name='nivel1'>".$options."</select>";
$select2="<select name='nivel2'>".$options."</select>";
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
echo form_open($ruta."/".$ruta."_reportes/rep_empresas_pdf/", $atrib) . "\n";
echo form_hidden('subfuncion', "$subfuncion");
echo form_fieldset('<b>Argumentos del Reporte</b>') . "\n";
$img_row="".base_url()."images/table_row.png";
?>
<table width=\ "100%\" class='form_table'>
	<tr>
		<td width="30%" valign="top">
			<table border="1">
				<tr>
					<th colspan="4">Ordenar Por</th>
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
					<td colspan="4" align="right"><button type="button"
							onclick="javascript:send1()">Informe</button>
						</form>
					</td>
				</tr>
			</table>
		</td>
		<td width="70%"><iframe src='' name="pdf" width=\ "100%\" height='700'></iframe>
		</td>
	</tr>
</table>
</center>
