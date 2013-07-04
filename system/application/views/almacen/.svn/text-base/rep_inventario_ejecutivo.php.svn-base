<?php
//Construir Espacios Fisicos
$select2=array();
foreach($espacios_fisicos->all as $row){
	$y=$row->id;
	$select2.="<option value='$y'>$row->tag</option>";

}
$espacios="<select name='espacios'>".$select2."</select>";

//Titulo
echo "<h2>$title</h2>";
$fecha=array(
		'class'=>'date_input',
		'name'=>'fecha',
		'size'=>'10',
		'readonly'=>'readonly',
		'value'=>'',
);
//Hora
$hora="";
for($x=1;$x<25;$x++){
	$hora.="<option value='$x'>$x</option>";
}
//Minuto
$min="";
for($x=0;$x<60;$x++){
	$min.="<option value='$x'>$x</option>";
}

?>
<script>
  function send1(){
    document.report.submit();
  }
  $($.date_input.initialize);
  jQuery(document).ready(
	function(){
		jQuery(jQuery.date_input.initialize);
		}
	);
</script>
<?php
$atrib=array('name'=>'report', 'target'=>"pdf");
echo form_open($ruta."/".$ruta."_reportes/rep_inventario_ejecutivo_pdf/", $atrib) . "\n";
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
						<td colspan="0">Tienda:</td>
						<td colspan="3"><?php echo $espacios;?></td>
					</tr>
					<tr>
						<th colspan="4">Paso No 2 - Fecha (En base al Ajuste de
							inventario)</th>
					</tr>
					<tr>
						<td>Fecha</td>
						<td colspan="3"><?php echo form_input($fecha);?> Hora: <select
							name='hora'><? echo $hora; ?>
						</select> Min: <select name='min'><? echo $min; ?>
						</select></td>
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

