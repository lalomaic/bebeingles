<?php
//Titulo
echo "<h2>$title</h2>";
?>
<script>
  function send1(){
    document.report.submit();
  }
	$($.date_input.initialize);
</script>
<?php
$atrib=array('name'=>'report', 'target'=>"pdf");
echo form_open("contabilidad/pagos_c/formulario/alta_pago_entre_tiendas/form", $atrib) . "\n";
echo form_hidden('subfuncion', "$subfuncion");
echo form_fieldset('<b>Generación de Gráficas</b>') . "\n";
$img_row="".base_url()."images/table_row.png";
?>
<table width="100%" class='form_table'>
	<tr>
		<td width="90%" valign="top">
			<table border="1">
				<tr>
					<th>Paso No 1 - Elija el Periodo</th>
				</tr>
				<tr>
					<td>Año:<? echo form_dropdown('anio', $anios, date("Y"));?><br />Año:<? echo form_dropdown('mes', $meses, 0);?>
					</td>
				</tr>
				<tr>
					<td colspan="4" align="right"><button type="button"
							onclick="javascript:send1()">Informe</button>
						</form></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><iframe src='' name="pdf" width="100%" height='900'></iframe></td>
	</tr>
</table>
</center>
