<?php
# otros campos
$espacios[0]="Elija";
$nombre = array(
    'name' => 'nombre',
    'size' => '30',
    'value' => ''.$empleado->nombre,
    'id' => 'nombre',
);
$apaterno = array(
    'name' => 'apaterno',
    'size' => '30',
    'value' => ''.$empleado->apaterno,
    'id' => 'apaterno',
);
$amaterno = array(
    'name' => 'amaterno',
    'size' => '30',
    'value' => ''.$empleado->amaterno,
    'id' => 'amaterno',
);
$fecha=date_format(date_create_from_format("Y-m-d", $empleado->fecha_nacimiento), "d m Y");
$fecha_nacimiento = array(
    'class'=>'date_input',
    'name' => 'fecha_nacimiento',
    'size' => '10',
    'value' => ''.$fecha,
    'id' => 'fecha_nacimiento',
    'readonly'=>'readonly'
);
$curp = array(
    'name' => 'curp',
    'size' => '20',
    'value' => ''.$empleado->curp,
    'id' => 'curp',
);
$num_seguro = array(
    'name' => 'num_seguro',
    'size' => '20',
    'value' => ''.$empleado->num_seguro,
    'id' => 'num_seguro',
);
$fecha=date_format(date_create_from_format("Y-m-d", $empleado->fecha_ingreso), "d m Y");
$fecha_ingreso = array(
    'class'=>'date_input',
    'name' => 'fecha_ingreso',
    'size' => '10',
    'value' => ''.$fecha,
    'id' => 'fecha_ingreso',
    'readonly'=>'readonly'
);
if($empleado->fecha_ingreso_imss!=""){
$fecha=explode("-", $empleado->fecha_ingreso_imss);
$fecha1=$fecha[2]." ".$fecha[1]." ".$fecha[0];
}else{
    $fecha1="";
}
//$fecha="";
//if($empleado->fecha_ingreso_imss!="0000-00-00" ||$empleado->fecha_ingreso_imss!="" ){
//	$fecha=date_format(date_create_from_format("Y-m-d", $empleado->fecha_ingreso_imss), "d m Y");
//}else{
//    $fecha= "";
//}
$fecha_ingreso_imss = array(
    'class'=>'date_input',
    'name' => 'fecha_ingreso_imss',
    'size' => '10',
    'value' => ''.$fecha1,
    'id' => 'fecha_ingreso_imss',
);
$salario = array(
    'class' => 'input_monetario',
    'name' => 'salario',
    'size' => '10',
    'value' => ''.$empleado->salario,
    'id' => 'salario',
);
$valor_descuento_infonavit= array(
    'class' => 'subtotal',
    'name' => 'valor_descuento_infonavit',
    'size' => '10',
    'value' => ''.$empleado->valor_descuento_infonavit,
    'id' => 'salario',
);
$salario_imss = array(
    'class' => 'input_monetario',
    'name' => 'salario_imss',
    'size' => '10',
    'value' => ''.$empleado->salario_imss,
    'id' => 'salario_imss',
);
$numero_cuenta = array(
    'name' => 'numero_cuenta',
    'size' => '20',
    'value' => ''.$empleado->numero_cuenta,
    'id' => 'numero_cuenta',
);
$importe_infonavit = array(
    'class' => 'impo_info input_monetario',
    'name' => 'importe_info',
    'size' => '10',
    'value' => ''.$empleado->importe_infonavit,
    'id' => 'importe_info',
);

$bono = array(
    'class' => 'bono_inp input_monetario',
    'name' => 'pago_bono',
    'size' => '10',
    'value' => ''.$empleado->pago_bono,
    'id' => 'pago_bono',
);
$ruta_foto = array(
    'type' => 'hidden',
    'name' => 'ruta_foto',
    'id' => 'ruta_foto',
);
$this->load->view('validation_view');
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
		
    });
	
</script>
<h2><?php echo $title;?></h2>
<?
	echo form_open($ruta . '/empleado_c/alta_empleado', array('name' => 'form1', 'id' => 'form1'));
    echo form_fieldset('<b>Datos del empleado</b>');
 ?>
        <div id="validation_result" class="result_validator" align="center" width="200px"></div>
        <table width="80%" class='form_table'>
            <tr>
                <td rowspan="2" colspan="2" class='form_tag'>   
						<iframe src='<?=base_url();?>index.php/nomina/empleado_c/formulario/list_empleados/frame_foto/<?=$empleado->id;?>' name="pdf"  width="400" height='200'></iframe>
                </td>
                <td class='form_tag'>Fecha Nacimiento:<br/><?php echo form_input($fecha_nacimiento); ?></td>
                <td class='form_tag'>Nombre:<br/><?php echo form_input($nombre); ?></td>
            </tr>
            <tr>
                <td class='form_tag'>Apellido Paterno<br/><?php echo form_input($apaterno); ?></td>
                <td class='form_tag'>Apellido Materno<br/><?php echo form_input($amaterno); ?></td>
            </tr>
            <tr>
                <td class='form_tag'>CURP:</td>
                <td class="form_input"><?php echo form_input($curp); ?></td>
                <td class='form_tag'>Núm. Seguro Social:<br/><?php echo form_input($num_seguro); ?></td>
                <td class='form_tag'>Fecha Ingreso:<br/><?php echo form_input($fecha_ingreso); ?></td>
            </tr>
            <tr>
                <td class='form_tag'>Fecha Ingreso IMSS:</td>
                <td class="form_input"><?php echo form_input($fecha_ingreso_imss); ?></td>
                <td class='form_tag'>Espacio físico:<br/><?php echo form_dropdown('espacio_fisico_id', $espacios, $empleado->espacio_fisico_id, "id='espacio_fisico_id'"); ?></td>
                <td class='form_tag'>Puesto:<br/><?php echo form_dropdown('puesto_id', $puestos, $empleado->puesto_id, "id='puesto_id'"); ?></td>
            </tr>
            <tr>
                <td class='form_tag'>Salario:<br/><?php echo form_input($salario); ?></td>
                <td class="form_tag">Factor Descuento INFONAVIT:<br/><?php echo form_input($valor_descuento_infonavit); ?></td>
                <td class='form_tag'>Salario IMSS:<br/><?php echo form_input($salario_imss); ?></td>
                <td class='form_tag'>Número Cuenta:<br/><?php echo form_input($numero_cuenta); ?></td>
            </tr>
            <tr>
                <td class='form_tag'>Tipo Comisión:<br/><? echo form_dropdown('comision_id', $comisiones, $empleado->comision_id, 'id="comision_id"');?></td>
                <td class="form_tag">Zona:<br/><? echo form_dropdown('smg_zona_id', $zonas, $empleado->smg_zona_id, 'id="smg_zona_id"');?></td>
                <td class='form_tag'><br/>
					<?php //echo form_input($importe_infonavit); ?>
                </td>
                <td class='form_tag'><br/>
					<?php //echo form_input($bono); ?>
                </td>
            </tr>
            <tr>
                <td colspan='6' class="form_buttons">
                    <?php echo form_input($ruta_foto); 
                    echo form_reset('form1', 'Borrar');
                    echo form_hidden('id', $empleado->id);
                    ?>
                    <button type='button' onclick="window.location='<?php echo site_url('/' . $ruta . '/empleado_c/formulario/list_empleados'); ?>'">Cerrar sin guardar</button>
                    <?php if (substr(decbin($permisos), 0, 1) == 1) { ?>
                        <button type="submit">Guardar Registro</button>
                    <?php } ?>
                </td>
            </tr>
        </table>
    <?php echo form_fieldset_close();
echo form_close();?>
<a href="<?php echo site_url('/nomina/empleado_c/formulario/list_empleados') ?>" title="Listado de empleados">
    <img src="<?php echo base_url() . "/images/adduser.png" ?>" width=50 height=50 alt="Listado de empleados" align="absmiddle">
</a>
