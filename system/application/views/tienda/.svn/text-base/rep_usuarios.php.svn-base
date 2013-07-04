<?php
//Construir Empresas
$default="<option value='1000'>Todos</option>";
$select1=$default;

foreach($empresas->all as $row){
	$y=$row->id;
	$select1.="<option value='$y'>$row->razon_social</option>";
}
$empresas="<select name='empresas'>".$select1."</select>";

//print_r(array_values($select1));

//Construir Espacios Fisicos
$select2=$default;
foreach($espacios_fisicos->all as $row){
	$y=$row->id;
	$select2.="<option value='$y'>$row->clave - $row->tag</option>";

}
$espacios="<select name='espacios'>".$select2."</select>";

//Titulo
echo "<h2>$title</h2>";


//Select para ordenar la consulta
$options="<option value='0'>Elija</option><option value='1'>Empresas</option><option value='2'>Espacio F�sico</option><option value='3'>No tiket</option>";

$select1="<select name='nivel1'>".$options."</select>";
$select2="<select name='nivel2'>".$options."</select>";
$select3="<select name='nivel3'>".$options."</select>";

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
    document.report.submit();
  }
</script>
<?php 
$atrib=array('name'=>'report', 'target'=>"pdf");
echo form_open($ruta."/".$ruta."_reportes/rep_remisiones_pdf/", $atrib) . "\n";
echo form_hidden('subfuncion', "$subfuncion");
echo form_fieldset('<b>Argumentos del Reporte</b>') . "\n";
$img_row="".base_url()."images/table_row.png";
?>

<table width=\ "100%\" class='form_table'>
	<tr>
		<td width="30%" valign="top">
			<table border="1">
				<tr>
					<th colspan="4">Paso No 1 - Filtrar</th>
				</tr>
				<tr>
					<td colspan="0">Empresas:</td>
					<td colspan="3"><?php echo $empresas;?></td>
				</tr>
				<tr>
					<td colspan="0">Ubicaciones</td>
					<td colspan="3"><?php echo $espacios;?></td>
				</tr>
				<tr>
					<th colspan="4">Paso No 2 - Periodo</th>
				</tr>
				<tr>
					<td colspan="0">Fecha inicio:</td>
					<td colspan="3"><?php echo form_input($fecha1);?></td>
				</tr>
				<tr>
					<td colspan="0">Fecha final</td>
					<td colspan="3"><?php echo form_input($fecha2);?></td>
				</tr>
				<tr>
					<th colspan="4">Paso No 3 - Ordenar Por</th>
				</tr>
				<tr>
					<td>Nivel 1</td>
					<td colspan="3"><?php echo $select1;?></td>
				</tr>
				<tr>
					<td>Nivel 2</td>
					<td colspan="3"><?php echo $select2;?></td>
				</tr>
				<tr>
					<td>Nivel 3</td>
					<td colspan="3"><?php echo $select3;?></td>
				</tr>
				<tr>
					<td colspan="4" align="right"><button type="button"
							onclick="javascript:send1()">Informe</button>
						</form></td>
				</tr>
			</table>
		</td>
		<td width="70%"><iframe src='' name="pdf" width=\ "100%\" height='700'></iframe>
		</td>
	</tr>
</table>

</center>

