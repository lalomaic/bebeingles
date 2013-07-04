<?php echo "<h2>$title</h2>";
?>
<script>
$(document).ready(function(){    
		$("#porcentaje").hide();
		$("#meta").hide();
		$("#rango").hide();

	$("#tipo_comision").change(function(){
		$("#porcentaje").hide();
		$("#meta").hide();
		$("#rango").hide();
			if($(this).val()==1)
				$("#porcentaje").show();
			else  if($(this).val()==2)
				$("#meta").show();
			else if($(this).val()==3)
				$("#rango").show();
		});
});
	function enviar_porcentaje(){
		
                if($('#tag').val()!=='' && $("#criterio1").val()>0 && $('#tipo_comision').val()>0){
                $.post("<? echo base_url();?>index.php/nomina/empleado_c/alta_comision_porcentaje_meta", { 
			tag: $('#tag').val(),
			espacio_fisico_id:$('#espacio_fisico_id').val(),
			tipo_comision_id: $('#tipo_comision').val(),
			criterio1: $('#criterio1').val()
		}, 
		function(data){
			if(data>0){
				$('#validation_result').html('<img src="<?=base_url()?>images/ok.png" width="20px" title="Guardado">Guardado');
				alert("La comisión de ha guardado exitosamente")
				window.location="<? echo site_url("/$ruta/empleado_c/formulario/list_comisiones");?>";
			}
		});
                }else {
				$('#validation_result').html('<img src="<?=base_url()?>images/trash.png" width="20px" title="Error al guardar">Error al guardar Faltan Campos por llenar');
			}
	}

	function enviar_meta(){
                 if($('#tag').val()!=='' && $("#criterio1_m").val()>0 && $('#tipo_comision').val()>0 && $("#criterio2_m").val()>0){
		$.post("<? echo base_url();?>index.php/nomina/empleado_c/alta_comision_porcentaje_meta", { 
			tag: $('#tag').val(),
			tipo_comision_id: $('#tipo_comision').val(),
			criterio1: $('#criterio1_m').val(),
						espacio_fisico_id:$('#espacio_fisico_id').val(),
			criterio2: $('#criterio2_m').val()
		}, 
		function(data){
			if(data>0){
				$('#validation_result').html('<img src="<?=base_url()?>images/ok.png" width="20px" title="Guardado">Guardado');
				alert("La comisión de ha guardado exitosamente")
				window.location="<? echo site_url("/$ruta/empleado_c/formulario/list_comisiones");?>";
			} 
		});
                }else{
                $('#validation_result').html('<img src="<?=base_url()?>images/trash.png" width="20px" title="Error al guardar">Error al guardar Faltan Campos por llenar');

                }
	}
	function enviar_rangos(){
		$("#tag_r").val($('#tag').val());
			$("#espacio_fisico").val($('#espacio_fisico_id').val());
		$("#tipo_comision_id_r").val($('#tipo_comision').val());
		document.form1.submit();
	}

</script>


<div id="validation_result" class="result_validator" align="center" width="200px"></div>
<table width="80%" class='form_table'>
	<tr>
		<th valign="top"  colspan="2" align="center">
			<label>Nombre de la Comisión por ventas semanales</label><br/><input type='text' id='tag' value='' size='60' >
		</th>
		<th valign="top" align="center">
			<label>Tipo de Comision</label><br/><? echo form_dropdown('tipo_comision_id1', $tipo_comisiones, 0, 'id="tipo_comision"');?>
		</th>
		<th valign="top" align="center">
			<label>Espacio</label><br/><? echo form_dropdown('espacio_fisico_id', $espacios, 0, 'id="espacio_fisico_id"');?>
		</th>
    </tr>
    <tr>
		<td align="center" id="porcentaje">
			<h3>Porcentaje de Venta</h3>
			<input type='text' id='criterio1' value='0' size='5' class='subtotal'>%<br/>
			<button type='button' onclick="javascript:enviar_porcentaje();">Guardar</button>
		</td>
		<td align="center" id="meta">
			<h3>Meta de Venta</h3>
			<label>Meta en Dinero de Venta:</label> $ <input type='text' id='criterio1_m' value='0' size='10' class='subtotal'>pesos<br/>
			<label>Comisión por par vendido:</label> $ <input type='text' id='criterio2_m' value='0' size='10' class='subtotal'>pesos<br/> 
			<button type='button' onclick="window.location='<?php echo site_url("/$ruta/empleado_c/formulario/list_comisiones"); ?>'">Cerrar</button>
			<button type='button' onclick="javascript:enviar_meta();">Guardar</button>
			</td>
		<td align="center" id="rango">
			<h3>Porcentaje de Venta</h3>
			<?php 
				echo form_open($ruta . '/empleado_c/alta_comision_rango', array('name' => 'form1', 'id' => 'form1'));
			?>
			<input type="hidden" name="tag" id="tag_r" value="">
			<input type="hidden" name="tipo_comision_id" id="tipo_comision_id_r" value="0">
				<input type="hidden" name="espacio_fisico" id="espacio_fisico" value="0">
			<table class='listado'>
				<tr>
					<th>Min</th>
					<th>Max</th>
					<th>Comision</th>
				</tr>
			<? for($x=1;$x<$rangos;$x++) {?>
				<tr>
					<td><input type='text' name='min<?=$x?>' value='0' size='9' class='subtotal'></td>
					<td><input type='text' name='max<?=$x?>' value='0' size='9' class='subtotal'></td>
					<td><input type='text' name='comision<?=$x?>' value='0' size='9' class='subtotal'>%</td>
				</tr>
			<? }	?>
			</table>
			<input type='hidden' name="rango" value="<?=$rangos?>">
			<button type='button' onclick="window.location='<?php echo site_url("/$ruta/empleado_c/formulario/list_comisiones"); ?>'">Cerrar sin guardar</button>
			<button type='button' onclick="javascript:enviar_rangos();">Guardar</button>
			</form>
		</td>
    </tr>
</table>

