<?php
$tag = array(
    'name' => 'tag',
    'size' => '80',
    'value' => $cuenta->tag,
    'id' => 'tag'
);
?>
<h2><?php echo $title ?></h2>
<div align="left">
    <table style="margin: 0;text-align:center;">
        <tr>
            <td>
                <a href="<?php echo base_url()."index.php/$ruta/$controller/$funcion/list_cuentas_contables" ?>">
                    <img src="<?php echo base_url() . "images/list.png" ?>" width="30px" title="Listado de Cuentas contables" alt="Listado de Cuentas contables"/>
                    Listado de cuentas contables
                </a>
            </td>
        </tr>
    </table>
</div>
<?php
echo form_open($ruta . '/trans/editar_cuenta_contable', array('name' => 'form1', 'id' => 'form1'));
echo form_fieldset('<b>Datos de la Nueva Cuenta Contable</b>');
?>
<table class="form_table">
    <tr>
        <td>
            <strong>Cuenta:</strong> <?php echo $cuenta->cta ?>
        </td><?php if($cuenta->scta > 0) {?><td style="padding-left: 5px;">
            <strong>Sub-Cuenta:</strong> <?php echo $cuenta->scta ?>
        </td><?php } if($cuenta->sscta > 0) {?><td style="padding-left: 5px;">
            <strong>Sub-Sub-Cuenta:</strong> <?php echo $cuenta->sscta ?>
        </td><?php } if($cuenta->ssscta > 0) {?><td style="padding-left: 5px;">
            <strong>Sub-Sub-Sub-Cuenta:</strong> <?php echo $cuenta->ssscta ?>
        </td><?php } if($cuenta->scta == 0) {?><td class="form_tag">
            <label for="ctipo_cuenta_contable_id">Tipo cuenta:</label> <?php echo form_dropdown("ctipo_cuenta_contable_id",$tipos_cuentas, $cuenta->ctipo_cuenta_contable_id) ?>
            <br/><input type="checkbox" checked name="cascada"/>Cambiar tipo de cuenta en dependientes de esta
        </td><?php } ?>
        <td>
            <label for="ccuenta_contable_naturaleza_id">Naturaleza cuenta:</label> 
            <?php echo form_dropdown("ccuenta_contable_naturaleza_id",$cuentas_naturalezas, $cuenta->ccuenta_contable_naturaleza_id, 'id="ctipo_cuenta_contable_id"') ?>
        </td>
    </tr><tr>
        <td class="form_tag" colspan="5">
            <?php echo form_hidden("id", $cuenta->id) ?>
            <label for="tag">Nombre:</label> <?php echo form_input($tag) ?>
        </td>
    </tr>
    <tr>
        <td colspan="5" class="form_buttons">
            <?php echo form_reset('form1', 'Borrar') ?>
            <button type="button" onclick="window.location='<?php echo base_url() . "index.php/inicio/acceso/$ruta/menu" ?>'">Cerrar sin guardar</button>
            <?php
            //Permisos de Escritura byte 1
            if (substr(decbin($permisos), 0, 1) == 1) {
                ?><button type="submit" id="submit">Guardar Cuenta</button><?php
        }
            ?>
        </td>
    </tr>
</table>
<?php echo form_close() . form_fieldset_close() ?>