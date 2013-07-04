<?php
//Construir catalogo de espacios fisicos
$select1="<option value='0'>Todos</option>";
// Espacio Fisico **************************
foreach($espacios as $row){
	$select1 .= "<option value='$row->id'>$row->tag</option>";
}
$espacio = "<select name='espacio' style='width:200px'>".$select1."</select>";
$fecha1=array(
		'class'=>'date_input',
		'name'=>'fecha1',
		'size'=>'10',
		'readonly'=>'readonly',
		'value'=>'',
);
?>
<script>
  function send1(){
    document.forms[0].submit();
  }
$($.date_input.initialize);
	function sel_chk(){
    $('.chk').attr('checked', true);
  }
  function unsel_chk(){
    $('.chk').attr('checked', false);
  }
</script>
<?php
echo "<h2>$title</h2>";
$atrib=array('name'=>'form1');
echo form_open($ruta."/trans/alta_polizas_ingresos/", $atrib) . "\n";
echo form_fieldset('<b>Alta de Pólizas de Ingreso de Facturas al Contado</b>') . "\n";
$img_row=base_url()."images/table_row.png";
?>
<table width="400" class='form_table'>
	<tr>
		<th colspan="2">Seleccione las Sucursales de las cuales generará las
			pólizas de ingresos</th>
	</tr>
	<tr>
		<th colspan="2"><? 
		echo "<div align='right' style='background-color:#ccc; display:inline-block; width:100%'><label>Seleccionar</label>";
			echo '<button type="button" id="todos" onclick="javascrip:sel_chk()">Todos</button><button type="button" id="todos" onclick="javascrip:unsel_chk()">Ninguno</button</div>'; ?>
		</th>
	</tr>
	<tr>
		<td>Fecha de las Pólizas:</td>
		<td><?=form_input($fecha1)?>
		</td>
	</tr>
	<tr>
		<td>Espacio fisico</td>
		<td><?php 
		$n=0;
		foreach($espacios->all as $row){
			echo form_checkbox("sucursal$n", $row->id, false, "id='sucursal$n' class='chk'");echo "$row->tag<br/>";
			$n+=1;
		}
		?>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="right"><input type="hidden" name="title"
			value="<?=$title?>" />
			<button type="button" onclick="javascript:send1()">Generar</button>
			</form></td>
	</tr>
</table>
