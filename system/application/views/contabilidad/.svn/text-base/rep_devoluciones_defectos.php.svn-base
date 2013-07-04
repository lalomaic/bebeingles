<script>
$(document).ready(function() {
	$($.date_input.initialize);
	$('#pdf').hide();
	$('#proveedor').val('0');
	  $('#proveedores_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_proveedores_ajax/', {
		  minLength: 3,
		  multiple: false,
		  noCache: true,
		  cacheLength:0,
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
		  $("#proveedor").val(""+item.id);
	  });

	  $('#cmarca_id').val('');
	  $('#marca_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_marcas_ajax/', {
			extraParams: {pid: function() { 
				if ( $("#proveedores_drop").val()==''){
					$("#proveedor").val("0");
//					alert(1);
				}
				return $("#proveedor").val(); } 
			},
			minLength: 3,
			multiple: false,
			noCache: true,
			cacheLength:0,
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
		  get_proveedor_marca_ajax(item.id);
	  });

	  function format(r) {
		  return r.descripcion;
	  }
});

	function enviar(){
		$('#pdf').show('slow');
		$("#form1").submit();
	}
	function get_proveedor_marca_ajax(mid){
		//Obtener los datos via ajax_pet
		if(mid>0){
			$.post("<? echo base_url();?>index.php/ajax_pet/get_proveedor_marca_ajax/", { arg_id1: mid},       //function that is called when server returns a value.
			function(data1){
				data=JSON.parse(data1);
				$('#proveedores_drop').val(data[0].descripcion);
				$('#proveedor').val(data[0].id);

			});
		}
		//Procesarlos y escribirlos
	}
</script>

<?php 

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

echo "<h2>$title</h2>";
$atrib=array('name'=>'report', 'target'=>"pdf", 'id'=>'form1');
$img_row="".base_url()."images/table_row.png";
echo form_open($ruta."/".$ruta."_reportes/{$subfuncion}_pdf/", $atrib) . "\n";
echo form_fieldset('<b>Argumentos del Reporte</b>') . "\n";
?>
<table class='form_table' align="center">
	<?
	echo "<tr><td class='form_tag' width='200px'><label for=\"proveedor_id\">Proveedor: </label><br/><input type='hidden' name='cproveedores_id' id='proveedor' value='' size=\"3\"><input id='proveedores_drop' class='proveedor' value='' size='60'><br/><label for=\"marca_id\">Marca: </label><br/><input type='hidden' name='cmarca_id' id='cmarca_id' value='0' size=\"3\"><input id='marca_drop' class='marca' value='' size='60'></td>";

	echo "<td class='form_tag' align='left'><label for=\"fecha1\">Fecha Inicio:</label>"; echo form_input($fecha1); echo "<br/><label for=\"fecha2\">Fecha Final:</label>"; echo form_input($fecha2); echo "</td>";

	echo "<td class='form_tag' align='left'>Filtrado por :<br/><input type='radio' name='tipo' value='1'>Abonadas<br/><input type='radio' name='tipo' value='2'>Sin Abonar<br/><input type='radio' name='tipo' value='3' checked='checked'>Todas<br/></td></tr>";

	?>
	<td colspan="4" align="right"><?php echo '<button type="button" onclick="javascript:enviar();" id="boton1" >Informe</button>'?>
	</td>
	</tr>
</table>
<?
echo form_fieldset_close();
echo form_close();
?>
<table width="100%">
	<tr>
		<td><iframe src='' name="pdf" width="100%" height='700' id='pdf'> </iframe>
		</td>

</table>
