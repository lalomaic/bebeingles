<?php
//Construir Empresas
$default="<option value='0'>Todos</option>";
$select1="";
//Construir Espacios Fisicos
$select2=$default;
foreach($espacios->all as $row){
	$y=$row->id;
	$select2.="<option value='$y'>$row->clave - $row->tag</option>";
}
$espacios="<select name='espacios'>".$select2."</select>";

//Titulo
echo "<h2>$title</h2>";

$fecha_inicio=array(
		'class'=>'date_input',
		'name'=>'fecha1',
		'size'=>'10',
		'readonly'=>'readonly',
		'value'=>'',
);
$fecha_final=array(
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

  $($.date_input.initialize);
  $(document).ready(
	function(){
		$(jQuery.date_input.initialize);
		}
	);
</script>
<?php
$atrib=array('name'=>'report', 'target'=>"pdf");
echo form_open($ruta."/".$ruta."_reportes/rep_traspasos_tiendas_pdf/", $atrib) . "\n";
echo form_hidden('subfuncion', "$subfuncion");
echo form_fieldset('<b>Argumentos del Reporte</b>') . "\n";
$img_row="".base_url()."images/table_row.png";
?>
<table width="100%" class='form_table'>
	<tr>
		<td width="30%" valign="top" align="center">
			<center>
				<table border="1">
					<tr>
						<th colspan="4">Paso No 1 - Filtrar Reporte</th>
					</tr>
					<tr>
						<td colspan="0">Tiendas</td>
						<td colspan="3"><?php echo $espacios;?></td>
					</tr>
					<tr>
						<th colspan="4">Paso No 2 - Fecha</th>
					</tr>
					<tr>
						<td>Fecha Inicio</td>
						<td><?php echo form_input($fecha_inicio);?></td>
						<td>Fecha Final</td>
						<td><?php echo form_input($fecha_final);?></td>
					</tr>
					<tr>
						<td colspan="4" align="right"><button type="button"
								onclick="javascript:send1()">Informe</button>
							</form></td>
					</tr>
				</table>
			</center>
		</td>
	</tr>
	<tr>
		<td colspan='4'><iframe src='' name="pdf" width="100%" height='700'></iframe>
		</td>
	</tr>
</table>
</center>

