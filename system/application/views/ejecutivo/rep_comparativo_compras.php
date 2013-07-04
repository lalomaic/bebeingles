<script>
$(document).ready(function() {
	$($.date_input.initialize);
	$('#pdf').hide();
	$('#cmarca_id').val('');
	$('#marca_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_marcas_ajax/', {
		extraParams: {pid: 0 },
		minLength: 3,
		multiple: false,
		noCache: true,
		parse: function(data) {
			return $.map(eval(data), function(row) {
				return {
					data: row,
					value: row.id,
					result: row.descripcion
				}
			});
		},
		formatItem: function(item) {
			return format(item);
		}
		}).result(function(e, item) {
			$("#cmarca_id").val(""+item.id);
		});
});
	function format(r) {
		return r.descripcion;
	}

function send1(){
	$('#pdf').show();
	document.report.submit();
}
function marcar(){
	$(':checkbox').attr('checked', true);
}
function desmarcar(){
	$(':checkbox').attr('checked', false);
}

</script>
<?php
//Titulo
echo "<h2>$title</h2>";

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

$atrib=array('name'=>'report', 'target'=>"pdf");
echo form_open($ruta."/".$ruta."_reportes/rep_comparativo_compras_pdf/", $atrib) . "\n";
echo form_hidden('subfuncion', "$subfuncion");
echo form_fieldset('<b>Elija los Criterios para el Comparativo</b>') . "\n";
$img_row="".base_url()."images/table_row.png";
?>
<table width="100%" class='form_table'>
	<tr>
		<th><a href='javascript:marcar()'> Todos -</a>Paso No.1 Elija las
			Sucursales <a href='javascript:desmarcar()'>- Ninguno</a></th>
		<th valign="top">Paso No 2 - Periodo de los movimientos</th>
		<th valign="top">Paso No 3 - Temporada</th>
	</tr>
	<tr>
		<td align="center">
			<table>
				<?
				$c=1; $y=1;
				foreach($espacios->all as $row){
					$state=false; $r_eid=array();
					if($c==1)
						echo "<tr>";
					echo "<td><label for=\"chk$y\">";echo form_checkbox("chk$y", $row->id, FALSE); echo "$row->tag:</label></td>";
					if($c==5){
						echo "</tr>";
						$c=0;
					}
					$c+=1;
					$y+=1;
				}

				?>
			</table>
		</td>
		<td>
			<table border="0">
				<tr>
					<td colspan="3">Fecha Inicio<?=form_input($fecha1)?><br /> Fecha
						Final<?=form_input($fecha2)?><br /> Cantidad Mínima Vendida <input
						type='text' name='cantidad_minima' value='7' size='4'>
					</td>
				</tr>
			</table>
		</td>
		<td><?echo form_dropdown('temporada', $temporada, 0);?></td>

	</tr>
	<tr>
		<td colspan="3" align="center">
			<button type="button" onclick="javascript:send1()">Informe</button>
		</td>
	</tr>
	<tr>
		<td colspan='3'><iframe src='' name="pdf" width="100%" height='900'
				id='pdf'></iframe></td>
	</tr>
</table>
<? form_close();?>
</center>

