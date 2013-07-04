<?="<h2>$title</h2>"?>
<script>
$(document).ready(function(){    

});
	function enviar_porcentaje(){
		$.post("<? echo base_url();?>index.php/nomina/empleado_c/alta_comision_porcentaje_meta", { 
			tag: $('#tag').val(),
						espacio_fisico_id: $('#espacio').val(),
			tipo_comision_id: $('#tipo_comision').val(),
			criterio1: $('#criterio1').val(),
			id: <?=$datos->id?>
		}, 
		function(data){
			if(data>0){
				$('#validation_result').html('<img src="<?=base_url()?>images/ok.png" width="20px" title="Guardado">Guardado');
				alert("La comisión de ha guardado exitosamente")
				window.location="<? echo site_url("/$ruta/empleado_c/formulario/list_comisiones");?>";
			} else {
				$('#validation_result').html('<img src="<?=base_url()?>images/trash.png" width="20px" title="Error al guardar">Error al guardar');
			}
		});
	}

	function enviar_meta(){
		$.post("<? echo base_url();?>index.php/nomina/empleado_c/alta_comision_porcentaje_meta", { 
			tag: $('#tag').val(),
			tipo_comision_id: $('#tipo_comision').val(),
			criterio1: $('#criterio1_m').val(),
				espacio_fisico_id: $('#espacio').val(),
			criterio2: $('#criterio2_m').val(),
			id: <?=$datos->id?>
		}, 
		function(data){
			if(data>0){
				$('#validation_result').html('<img src="<?=base_url()?>images/ok.png" width="20px" title="Guardado">Guardado');
				alert("La comisión de ha guardado exitosamente")
				window.location="<? echo site_url("/$ruta/empleado_c/formulario/list_comisiones");?>";
			} else {
				$('#validation_result').html('<img src="<?=base_url()?>images/trash.png" width="20px" title="Error al guardar">Error al guardar');
			}
		});
	}
	function enviar_rangos(){
		$("#tag_r").val($('#tag').val());
				$("#espacio_fisico").val($('#espacio').val());
		document.form1.submit();
	}

</script>
<div id="validation_result" class="result_validator" align="center" width="200px"></div>
<table width="80%" class='form_table'>
	<tr>
		<th valign="top"  colspan="2" align="center">
			<label>Nombre de la Comisión por ventas semanales</label><br/><input type='text' id='tag' value='<?=$datos->tag?>' size='60' >
		</th>
		<th valign="top" align="center">
			<label>Tipo de Comision</label><br/><? echo $tipo_comisiones[$datos->tipo_comision_id];?>
			<input type="hidden" name="tipo_comision_id" id="tipo_comision" value="<?=$datos->tipo_comision_id?>">
				<input type="hidden" name="espacio" id="espacio" value="<?=$datos->espacio_fisico_id?>">
		</th>
    </tr>
    <tr>
		<td align="center" id="porcentaje">
			<? if($datos->tipo_comision_id==1){ ?>
			<h3>Porcentaje de Venta</h3>
			<input type='text' id='criterio1' value='<?=$datos->criterio1?>' size='5' class='subtotal'>%<br/>
			<button type='button' onclick="javascript:enviar_porcentaje();">Guardar</button>
			<? } ?>
		</td>
		<td align="center" id="meta">
			<? if($datos->tipo_comision_id==2){ ?>
			  <h3>Meta de Venta</h3>
			  <label>Meta en Dinero de Venta:</label> $ <input type='text' id='criterio1_m' value='<?=$datos->criterio1?>' size='10' class='subtotal'>pesos<br/>
			  <label>Comisión por par vendido:</label> $ <input type='text' id='criterio2_m' value='<?=$datos->criterio2?>' size='10' class='subtotal'>pesos<br/> 
			  <button type='button' onclick="window.location='<?php echo site_url("/$ruta/empleado_c/formulario/list_comisiones"); ?>'">Cerrar</button>
			  <button type='button' onclick="javascript:enviar_meta();">Guardar</button>
			<? } ?>
			</td>
		<td align="center" id="rango">
			<? if($datos->tipo_comision_id==3){ ?>
			<h3>Porcentaje de Venta</h3>
			<?php 
				echo form_open($ruta . '/empleado_c/alta_comision_rango', array('name' => 'form1', 'id' => 'form1'));
			?>
			<input type="hidden" name="tag" id="tag_r" value="">
				<input type="hidden" name="espacio_fisico" id="espacio_fisico" value="">
			<input type="hidden" name="id" value="<?=$datos->id?>">
			<input type="hidden" name="tipo_comision_id" id="tipo_comision" value="<?=$datos->tipo_comision_id?>">
			<table class='listado'>
				<tr>
					<th>Min</th>
					<th>Max</th>
					<th>Comision</th>
				</tr>
			<? 
			$rangos_db=explode("&", $datos->criterio1);
			for($x=1;$x<$rangos;$x++) {
			  $max=0; $min=0; $comision=0;
			  if(isset($rangos_db[$x-1])){
				$linea=explode("-", $rangos_db[$x-1]);
				$min=$linea[0];
				$max=$linea[1];
				$comision=$linea[2];
			  }
			?>
				<tr>
					<td><input type='text' name='min<?=$x?>' value='<?=$min?>' size='9' class='subtotal'></td>
					<td><input type='text' name='max<?=$x?>' value='<?=$max?>' size='9' class='subtotal'></td>
					<td><input type='text' name='comision<?=$x?>' value='<?=$comision?>' size='9' class='subtotal'>%</td>
				</tr>
			<? }	?>
			</table>
			<input type='hidden' name="rango" value="<?=$rangos?>">
			<button type='button' onclick="window.location='<?php echo site_url("/$ruta/empleado_c/formulario/list_comisiones"); ?>'">Cerrar sin guardar</button>
			<button type='button' onclick="javascript:enviar_rangos();">Guardar</button>
			</form>
			<? } ?>
		</td>
    </tr>
</table>

