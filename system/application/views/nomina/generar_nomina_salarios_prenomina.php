<?php
  $espacios[-1] = "Elija";
  $fecha=explode("-",$prenomina->fecha_inicial);
  $fecha_inicial1=$fecha[2]." ".$fecha[1]." ".$fecha[0];
  $fecha_inicial = array(
	  'class'=>'date_input',
	  'name' => 'fecha_inicial',
	  'size' => '10',
	  'value' => ''.$fecha_inicial1,
	  'id' => 'fecha_inicial',
	  'readonly'=>'readonly'
  );
  $fecha=explode("-",$prenomina->fecha_final);
  $fecha_final1=$fecha[2]." ".$fecha[1]." ".$fecha[0];
  $fecha_final = array(
	  'class'=>'date_input',
	  'name' => 'fecha_final',
	  'size' => '10',
	  'value' => ''.$fecha_final1,
	  'id' => 'fecha_final',
	  'readonly'=>'readonly'
  );
?>
<h2><?php echo $title;?></h2>
<?
if($salario_minimo==false){
  show_error("No se puede generar la Nómina sin capturar el valor de salario minimo del año final de la prenomina");
  exit();
}
  $this->load->view('validation_view');
  $this->load->view('nomina/js_nomina_salarios_prenomina');

?>
<div id="validation_result" class="result_validator" align="center" width="200px"></div>
<input type='hidden' id='prenomina_id' value="<?=$prenomina->id?>">
<input type='hidden' id='id' value="0">
<table width="50%" class='form_table' align="center" style="margin-left: auto; margin-right: auto;">
    <tr>
        <td class="form_tag">Periodo:</td>
        <td class="form_tag">De:</td>
        <td class="form_input"><?php echo form_input($fecha_inicial) ?></td>
        <td class="form_tag">A:</td>
        <td class="form_input"><?php echo form_input($fecha_final) ?></td>
        <td class="form_tag">Tienda:</td>
        <td class="form_input" style="width: 100px;"><?php echo form_dropdown('espacio_fisico_id', $espacios, $prenomina->espacio_fisico_id, "id='espacio_fisico_id'"); ?></td>
    </tr>
</table><br />
<div id="error"></div>
<table  class='form_table' id='nomina_table' align="center" style="margin-left: auto; margin-right: auto;">
  <tr>
	<th>Empleado</th>
	<th>Salario Diario</th>
	<th>Días Labs</th>
	<th>Sueldo</th>
	<th>Horas Extra</th>
	<th>Desc. Prog.</th>
	<th>Comisión</th>
	<th>INFONAVIT</th>
	<?
	  if($prestaciones!=false){
		foreach($prestaciones as $row){
		  echo "<th class='pre_$row->id' width='90px'>$row->tag</th>";
		}
	  }
	?>
	<?
	  if($deducciones!=false){
		foreach($deducciones as $row){
		  echo "<th class='ded_$row->id' width='90px'>$row->tag</th>";
		}
	  }
	?>
	<th id='total_pagar'>Total a pagar</th>
	<th>Banco</th>
	<th>Efectivo</th>
  </th>
  <?
  $r=0;
  if($detalles != false){
  foreach($detalles as $row) {?>
  <tr id="tr<?=$r?>">
	<td>
	  <div id="content<?=$r?>"></div>
	  <input type="hidden" id="id_<?=$r?>" value="0">
	  <input type="hidden" id="empleado_id_<?=$r?>" value="<?=$row['empleado_id']?>">
	  <input type="hidden" id="prenomina_detalle_id_<?=$r?>" value="<?=$row['prenomina_detalle_id']?>">
	  <?=$row['empleado']." (".round($row['pares'],2).")"?>
	</td>
	<td class="form_input">
	  <input type="text" align="right" name="salario_diario_<?=$r?>" id="salario_diario_<?=$r?>" value="<?=$row['salario']?>" size="8" class="subtotal" readonly="readonly">
	</td>
	<td class="form_input" align="right">
	  <input type="text" name="dias_lab_<?=$r?>" id="dias_lab_<?=$r?>" value="<?=$row['dias_lab']?>" size="3" class="subtotal" readonly="readonly">
	</td>
		<td class="form_input" align="right">
	  <input type="text" name="suledo_t<?=$r?>" id="sueldo_t<?=$r?>" value="<? echo round(($row['salario']*$row['dias_lab']),2);?>" size="5" class="subtotal" readonly="readonly">
	</td>
	<td class="form_input" align="right">
	  <input type="text" name="horas_extra_<?=$r?>" id="horas_extra_<?=$r?>" value="<?=$row['valor_horas_extra']?>" size="5" class="subtotal" readonly="readonly" title="Horas Extra: <?=$row['horas_extra']?>">
	</td>
	<td class="form_input" align="right">
	  <input type="text" name="desc_prog_<?=$r?>" id="desc_prog_<?=$r?>" value="<?=$row['descuentos_programados']?>" size="6" class="subtotal" readonly="readonly">
	</td>
	<td class="form_input" align="right">
	  <input type="text" name="comision_<?=$r?>" id="comision_<?=$r?>" value="<?=$row['comision']?>" size="6" class="subtotal" onkeyup="javascript:act_total_linea(<?=$r?>);">
	</td>
	<td class="form_input" align="right">
	  <input type="text" name="descuento_infonavit_<?=$r?>" id="descuento_infonavit_<?=$r?>" value="<?=number_format($row['descuento_infonavit'],2,".","");?>" size="6" class="subtotal" readonly="readonly">
  </td>
	<? 	if($prestaciones!=false){
		  foreach($prestaciones as $row1){ ?>
		<td class="form_input pre_<?=$row1->id?>" align="right">
		  <input type="text" name="pres_<?=$r?>_<?=$row1->id?>" id="pres_<?=$r?>_<?=$row1->id?>" value="0" size="6" class="subtotal" onkeyup="javascript:act_total_linea(<?=$r?>);">
		</td> 
		<?  }
		} ?>
	<? 	if($deducciones!=false){
		  foreach($deducciones as $row1){ ?>
		<td class="form_input ded_<?=$row1->id?>" align="right"><input type="text" name="ded_<?=$r?>_<?=$row1->id?>" id="ded_<?=$r?>_<?=$row1->id?>" value="0" size="6" class="subtotal" onkeyup="javascript:act_total_linea(<?=$r?>);"></td> 
	<?  } 
	  } ?>

	<td class="form_input" align="right">
	  <input type="text" name="total_pagar_<?=$r?>" id="total_pagar_<?=$r?>" value="<? echo round(($row['salario']*$row['dias_lab']+$row['valor_horas_extra']+$row['comision']-$row['descuento_infonavit']-$row['descuentos_programados']),0);?>" size="7" class="subtotal" readonly="readonly">
	</td>
	<td class="form_input" align="right">
	  <input type="text" name="banco_deposito_<?=$r?>" id="banco_deposito_<?=$r?>" value="0" size="6" class="subtotal" onkeyup="javascript:act_efectivo(<?=$r?>);">
	</td>
	<td class="form_input" align="right">
	  <input type="text" name="efectivo_deposito_<?=$r?>" id="efectivo_deposito_<?=$r?>" value="0" size="6" class="subtotal" readonly="readonly">
	</td>
  </tr>
<? $r+=1; 	
  } }
?>
</table><br />
<table class="listado" style="margin-left: auto; margin-right: auto;">
  <tr>
	  <th>Prestaciones</th>
	  <th>Deducciones</th>
  </tr><tr>
	  <td>
		<?
		 if($prestaciones!=false){
		  foreach($prestaciones as $row){
			echo form_checkbox('prestacion', $row->id, false, "class='prestacion'");
			echo "<label> $row->tag</label><br/>";
		  }
		}
		?>
	  </td>
	  <td >
		<?
		if($deducciones!=false){
		  foreach($deducciones as $row){
			echo form_checkbox('deduccion', $row->id, false, "class='deduccion'");
			echo "<label> $row->tag</label><br/>";
		  }
		}
		?>
	  </td>
  </tr>
</table>
<center>
<table width="90%" class='form_table' align="center" style="margin-left: auto; margin-right: auto;"> 
    <tr>
            <td class="form_buttons">
            <button type='button' onclick="window.location='<?php echo site_url('inicio/acceso/nomina/menu'); ?>'">Cerrar sin generar</button>
            <button id="b_guar" type='button' onclick="javascript:enviar_todo()">Generar Nómina</button>
            <button id="b_fin" type='button' onclick="window.location='<?php echo site_url('nomina/nomina_c/formulario/list_nominas/'); ?>'">Finalizar</button>

        </td>
    </tr>
</table></center>
