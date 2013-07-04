<?php
//Construir Empresas
//Titulo
// Espacio Fisico **************************
$select1="<option value='0'>Todos</option>";
foreach($espacios as $row){
	$select1 .= "<option value='$row->id'>$row->tag</option>";
}
$espaciof = "<select name='espaciof' style='width:200px'>".$select1."</select>";
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
$n = 1; // numero de selects
$selects = array();
for($i = 0;$i < $n;$i++)
{
	$selects[] = "<select name='nivel_".($i+1)."'>".$options."</select>";
}
?>
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
					<th colspan="4">Paso No 1 - Filtrar Concentrado</th>
				</tr>
				<tr>
					<td colspan="0">Espacio fisico</td>
					<td colspan="3"><?php echo $espaciof;?></td>
				</tr>
				<tr>
					<th colspan="4">Periodo de Tiempo</th>
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
<script>
  function send1(){
    document.report.submit();
  }
  $($.date_input.initialize);
</script>

