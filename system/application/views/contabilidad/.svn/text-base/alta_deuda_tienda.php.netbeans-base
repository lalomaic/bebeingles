<script>
$(document).ready(function() {
	$($.date_input.initialize);
	<?php
	for($x=0;$x<=$rows;$x++){
		echo  "$('#monto$x').blur(function() {\n".
			"$('#row".($x+1)."').show(''); \n".
			"$('#row".($x+1)."').removeClass('invisible').addClass('visible'); \n ".
			//"enviar_renglon($x);\n".
		"});\n";
		echo  "$('#recibe_id$x').blur(function() {\n".
			"enviar_renglon($x);\n".
		"});\n";
			
		//Codigo de Barras
		echo "$('#monto$x').keyup(function(e) { \n".
           "sumar_cantidades();\n".
		"}); \n";

  }
  ?>	
});
	function enviar_renglon(linea){
		//Obtener campos clave producto_id y cantidad
		$('#content'+linea).html("<img src='<? echo base_url(); ?>images/waiting.gif' width='20px'/>");
		debe_js=$('#debe_id').val();
		recibe_js=$('#recibe_id'+linea).val();
		concepto_js=$('#concepto'+linea).val();
		fecha_js=$('#fecha'+linea).val();
		monto_js=$('#monto'+linea).val();
		recibe_js=$('#recibe_id'+linea).val();
		if(debe_js>0 && recibe_js>0 && concepto_js>0 && monto_js>0){
			$.post("<? echo base_url();?>index.php/contabilidad/trans/alta_deuda_tienda", { 
				espacio_fisico_debe_id: debe_js,
				espacio_fisico_recibe_id: recibe_js,
				ctipo_deuda_tienda_id: concepto_js,
				fecha: fecha_js,
				monto_deuda: monto_js,
				id: $('#id'+linea).val()
			}, 
				function(data){
					if(data>0){
						$('#content'+linea).html('<img src="<?=base_url()?>images/ok.png" width="20px" title="Guardado">');
						$('#id'+linea).val(data);
					}
			});
		} else{
			$('#content'+linea).html('');
			alert("Ocurrio un error al intentar guardar el concepto "+debe_js);
		}
	}

	function sumar_cantidades(){
		sum=0;  sum_cant=0;
		$('#total').val('');
		for(x=0;x<=<?=$rows?>;x++){
			if($('#monto'+x).val()>0){
				sum= parseFloat(sum)+parseFloat($('#monto'+x).val());
			}
		}
		$('#total').val(''+sum);
	}

</script>
<?php
echo "<h2>$title</h2>";
//Abrir Formulario
$atrib=array('name'=>'form1', 'id'=>"form1");
echo form_fieldset('<b>Alta de Deuda entre Tiendas</b>') . "\n";
echo "<table class='form_table'>";
$img_row="".base_url()."images/table_row.png";

//Campos del Formulario
echo "<tr><td class='form_tag' colspan='4'>Elegir Tienda que adeuda: <br/>";
echo form_dropdown('debe_id', $tiendas, 0,"id='debe_id'"); echo "</td>";
echo '</tr></table>';
?>
<div id="subform_detalle">
	<table class="row_detail" id="header" border='0' width='500px'>
		<tr>
			<th class='detalle_header'>Estatus</th>
			<th class='detalle_header'>Concepto</th>
			<th class='detalle_header'>Fecha</th>
			<th class='detalle_header'>Monto</th>
			<th class='detalle_header'>A que tienda</th>
		</tr>
		<?php
		//Imprimir valores actuales
		$r=0;
		for($r;$r<$rows;$r++){
			if($r<3)
				$class="visible";
			else
				$class="invisible";
			echo "<tr id=\"row$r\"  class=\"$class\" valign='top'><td class='detalle' width=\"50\" align='center'><input type='hidden' value='0' name='id$r' id='id$r'><div id=\"content$r\"></div></td>";
			echo "<td>";echo form_dropdown("concepto$r", $conceptos, 0, "id='concepto$r'"); echo "</td>";
			echo "<td align='right'><input type='text' name='fecha$r' id='fecha$r' size='8' value='".date("d m Y")."' onlyread='onlyread' class='date_input'></td>"	;
			echo "<td align='right'><input type='text' name='monto$r' id='monto$r' size='8' value='0' class='subtotal'></td>";
			echo "<td align='right'>"; echo form_dropdown("recibe_id$r", $tiendas, 0,"id='recibe_id$r'"); echo "</td></tr>";
		}
		echo "<tr><th align='right' colspan='3'>Total</th><th><input type='text' name='total' id='total' size='8' class='subtotal' value='0'></th>";
		echo "<th><button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Cerrar</button></th></tr>";
		echo "</table></div>";
		echo form_fieldset_close();
?>