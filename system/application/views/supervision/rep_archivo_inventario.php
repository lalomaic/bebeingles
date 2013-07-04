<?php
//Construir Espacios Fisicos
$select2="<option value='0'>Elija</option>";
foreach($espacios->all as $row){
	$y=$row->id;
	$select2.="<option value='$y'>$row->tag</option>";
}
$espacios="<select name='espacios'>".$select2."</select>";
//Titulo
echo "<h2>$title</h2>";
?>
<script>
  function send1(){
    document.report.submit();
  }
</script>
<?php 
$atrib=array('name'=>'report', 'target'=>"pdf");
echo form_open($ruta."/".$ruta."_reportes/rep_archivo_inventario_csv/", $atrib) . "\n";
echo form_hidden('subfuncion', "$subfuncion");
echo form_fieldset('<b>Argumentos de Generación del Archivo</b>') . "\n";
$img_row="".base_url()."images/table_row.png";
?>
<table width="100%" class='form_table'>
	<tr>
		<td width="30%" valign="top">
			<table border="1">
				<tr>
					<th colspan="4">Paso No 1 - Filtrar</th>
				</tr>
				<tr>
					<td colspan="0">Ubicaciones</td>
					<td colspan="3"><?php echo $espacios;?></td>
				</tr>
				<tr>
					<td colspan="4" align="right"><button type="button"
							onclick="javascript:send1()">Generar Archivo</button>
						</form></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
	</tr>
</table>
</center>

