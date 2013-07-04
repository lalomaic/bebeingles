<?php
//Construir Empresas
$select1="<option value='0'>Todos</option>";
foreach($empresas->all as $row){
	$y=$row->id;
	$select1.="<option value='$y'>$row->razon_social</option>";
}
$empresas="<select name='empresas'>".$select1."</select>";
$espacios_fisicos[0]="TODAS";

//Titulo
echo "<h2>$title</h2>";
//Select para ordenar la consulta
$options="<option value='0'>Elija</option><option value='1'>Empresas</option><option value='2'>Espacio Fí­sico</option><option value='3'>Descripcion</option>";
$select1="<select name='nivel1'>".$options."</select>";
$select2="<select name='nivel2'>".$options."</select>";
$select3="<select name='nivel3'>".$options."</select>";

$fecha=array(
		'class'=>'date_input',
		'name'=>'fecha',
		'size'=>'10',
		'readonly'=>'readonly',
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
echo form_open("almacen/almacen_reportes/rep_inventario_pdf/", $atrib) . "\n";
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
						<td colspan="0">Empresa:</td>
						<td colspan="3"><?php echo $empresas;?></td>
					</tr>
                                        <tr>
						<td colspan="0">Tiendas</td>
						<td colspan="3"><?php echo form_dropdown('espacios', $espacios_fisicos,0);?>
						</td>
					</tr>
					<tr>
						<td colspan="0">Departamento:</td>
						<td colspan="3"><?php echo  form_dropdown('familia', $familias, 0);?>
						</td>
					</tr>
					<tr>
						<td colspan="0">Subfamilia</td>
						<td colspan="3">
							<div id="subfamilias">
								<? echo form_dropdown('subfamilia', $subfamilias, 0, "id='subfamilia'");?>
							</div>
						</td>
					</tr>
					<tr>
						<th colspan="4">Paso No 2 - Fecha</th>
					</tr>
					<tr>
						<td>Fecha</td>
						<td colspan="3"><?php echo form_input($fecha);?> Hora: <select
							name='hora'><? echo $hora; ?>
						</select> Min: <select name='min'><? echo $min; ?>
						</select></td>
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
			</center>
		</td>
	</tr>
	<tr>
		<td colspan='4'><iframe src='' name="pdf" width="100%" height='700'></iframe>
		</td>
	</tr>
</table>
</center>

