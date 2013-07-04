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
    function send2(){
    document.report2.submit();
  }

</script>
<?php 
$atrib=array('name'=>'report', 'target'=>"pdf");
echo form_open($ruta."/".$ruta."_reportes/rep_reimprimir_etiquetas_pdf/", $atrib) . "\n";
echo form_hidden('subfuncion', "$subfuncion");
echo form_fieldset('<b>Argumentos de Reimpresión </b>') . "\n";
$img_row="".base_url()."images/table_row.png";
?>
<table width="100%" class='form_table'>
	<tr>
		<td width="40%" valign="top">
			<table border="1">
				<tr>
					<th colspan="4">Etiquetas Lotificadas</th>
				</tr>
				<tr>
					<th colspan="2">Sucursal</th>
					<th colspan="2">Etiqueta</th>
				</tr>
				<tr>
					<td colspan="0">Ubicaciones</td>
					<td><?php echo $espacios;?></td>
					<td colspan="0">Número del Código</td>
					<td><input type='input' name='numero_codigo' value='' size="15"></td>
				</tr>
				<tr>
					<td colspan="4" align="right"><button type="button"
							onclick="javascript:send1()">Generar Archivo</button>
						</form></td>
				</tr>
			</table> <br /> <br />
			</form> <?php 
			$atrib=array('name'=>'report2', 'target'=>"pdf");
			echo form_open($ruta."/".$ruta."_reportes/rep_reimprimir_etiquetas_anteriores_pdf/", $atrib) . "\n";
			?>
			<table border="1">
				<tr>
					<th colspan="4">Etiquetas de Códigos Anteriores</th>
				</tr>
				<tr>
					<th>Cantidad</th>
					<th>Código</th>
				</tr>
				<?php
				for($r=0;$r<10;$r++){
					echo <<<end
						<tr><td><input type='input' name='cantidad$r' value='0' size="5"></td>
						<td><input type='input' name='codigo$r' value='' size="15"></td></tr>
end;
				}
				?>
				<tr>
					<td colspan="4" align="right"><button type="button"
							onclick="javascript:send2()">Generar Etiquetas</button>
						</form></td>
				</tr>
			</table>
			</form>

		</td>
		<td><iframe src='' name="pdf" width="100%" height='900'></iframe></td>
	</tr>
</table>
</center>

