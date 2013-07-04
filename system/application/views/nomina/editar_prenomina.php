<?php
$espacios[-1] = "Elija";
$fecha=explode("-", $prenomina->fecha_inicial);
$fecha1=$fecha[2]." ".$fecha[1]." ".$fecha[0];
$fecha_inicial = array(
        'class'=>'date_input',
        'name' => 'fecha_inicial',
        'size' => '10',
        'value' => $fecha1,
        'id' => 'fecha_inicial',
        'readonly'=>'readonly'
);
$fecha=explode("-", $prenomina->fecha_final);
$fecha1=$fecha[2]." ".$fecha[1]." ".$fecha[0];
$fecha_final = array(
        'class'=>'date_input',
        'name' => 'fecha_final',
        'size' => '10',
        'value' => $fecha1,
        'id' => 'fecha_final',
        'readonly'=>'readonly'
);
$this->load->view('validation_view');
$this->load->view('nomina/js_editar_prenomina');
?>
<h2><?php echo $title;?></h2>
<?php echo form_open('', array('name' => 'form1', 'id' => 'form1'));
    echo form_fieldset('<b>Datos de la Prenomina</b>', array('width' => '100%'));
    echo form_hidden('id', $prenomina->id);
    ?>
        <div id="validation_result" class="result_validator" align="center" width="200px"></div>
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
        </table>
        <br />
        <div id="error"></div>
        <table width="70%" class='form_table' id='empleados_table' align="center" style="margin-left: auto; margin-right: auto;">
       </table>   
       <table width="70%" class='form_table' align="center" style="margin-left: auto; margin-right: auto;"> 
            <tr>
                <td class="form_buttons">
                    <?php echo form_reset('form1', 'Borrar');?>
                    <button type='button' onclick="window.location='<?php echo site_url('inicio/acceso/nomina/menu'); ?>'">Cerrar sin generar</button>
					<button type="submit" id="generar_button">Generar</button>
                </td>
            </tr>
        </table>
    <?php echo form_fieldset_close();
echo form_close(); ?>