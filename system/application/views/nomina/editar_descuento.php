<?php
# otros campos
$fecha=explode("-", $descuento->fecha_inicio);
$fecha_inicio1=$fecha[2]." ".$fecha[1]." ".$fecha[0];
$fecha_inicio= array(
    'class'=>'date_input',
    'name' => 'fecha_inicio',
    'size' => '10',
    'value' => $fecha_inicio1,
    'id' => 'fecha_inicio',
    'readonly'=>'readonly'
);

$deuda_total = array(
    'class' => 'subtotal',
    'name' => 'deuda_total',
    'size' => '10',
    'value' => ''.number_format($descuento->deuda_total,2,".",""),
    'id' => 'deuda_total',
);
$monto_descuento_semanal= array(
    'class' => 'subtotal',
    'name' => 'monto_descuento_semanal',
    'size' => '10',
    'value' => ''.number_format($descuento->monto_descuento_semanal,2,".",","),
    'id' => 'monto_descuento_semanal',
);
?>
<script type="text/javascript">    
    function formatNumber(num) {
        num = parseFloat(num).toFixed(2);
        num += '';
        var splitStr = num.split('.');
        var splitLeft = splitStr[0];
        var splitRight = splitStr.length > 1 ? '.' + splitStr[1] : '';
        var regx = /(\d+)(\d{3})/;
        while (regx.test(splitLeft)) {
            splitLeft = splitLeft.replace(regx, '$1' + ',' + '$2');
        }
        return splitLeft + splitRight;
    }
    
    $(document).ready(function(){    
        $($.date_input.initialize);
        $(".input_monetario").attr("style", "text-align: right")
        $(".input_monetario").blur(function () {
            if(!isNaN(parseFloat($(this).val())))
                $(this).val(formatNumber($(this).val()));         
            else
                $(this).val("0.00");
        });
        $(".input_monetario").focus(function () {
            $(this).val($(this).val().replace(/([^0-9\.\-])/g,''))
        });
		$('#empleado_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_empleados_ajax_autocomplete/', {
		  minLength: 5,
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
		  $("#empleado_id").val(""+item.id);
	  });
	  
    });
	
	function format(r) {
		return r.descripcion;
	}
</script>
<h2><?php echo $title;?></h2>
<?php 
	echo form_open($ruta . '/empleado_c/alta_descuento_programado', array('name' => 'form1', 'id' => 'form1'));
    echo form_fieldset('<b>Datos del Descuento a programar</b>');
    echo form_hidden("id", $descuento->id);
    ?>
	<div id="validation_result" class="result_validator" align="center" width="200px"></div>
		<table  class='form_table'>
			<tr>
				<td class='form_tag'>Empleado:</td>
				<td class='form_tag'><input type='hidden' value='<? echo $descuento->empleado_id; ?>' id='empleado_id' name='empleado_id'> <input type='text' value='<?= "$empleado->nombre $empleado->apaterno $empleado->amaterno"?>' id='empleado_drop' size='40'></td>
			</tr><tr>
				<td class='form_tag'>Concepto del Descuento</td>
				<td class='form_tag'><?php echo form_dropdown('ctipo_descuento_id',$tipos_descuentos, $descuento->ctipo_descuento_id); ?></td>
			</tr><tr>
				<td class='form_tag'>Fecha Inicio:</td>
				<td class='form_tag'><?php echo form_input($fecha_inicio); ?></td>
			</tr><tr>
				<td class='form_tag'>Adeudo Total:</td>
				<td class='form_tag'><?php echo form_input($deuda_total); ?></td>
			</tr><tr>
				<td class='form_tag'>Descuento Semanal:</td>
				<td class='form_tag'><?php echo form_input($monto_descuento_semanal); ?></td>
			</tr><tr>
                <td colspan='2' class="form_buttons">
                <?
                    echo form_reset('form1', 'Borrar');?>
                    <button type='button' onclick="window.location='<?php echo site_url('/' . $ruta . '/empleado_c/formulario/list_empleados'); ?>'">Cerrar sin guardar</button>
                    <?php if (substr(decbin($permisos), 0, 1) == 1) { ?>
                        <button type="submit">Guardar Registro</button>
                    <?php } ?>
                </td>
            </tr>
        </table>
	<?php 
	echo form_fieldset_close();
	echo form_close();
	?>
<a href="<?php echo site_url('/nomina/empleado_c/formulario/list_empleados') ?>" title="Listado de empleados">
    <img src="<?php echo base_url() . "/images/adduser.png" ?>" width=50 height=50 alt="Listado de empleados" align="absmiddle">
</a>
