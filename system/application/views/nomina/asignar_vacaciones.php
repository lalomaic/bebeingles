<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script type="text/javascript">
    $(document).ready(function(){       
        $('#proveedor_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_proveedores_ajax_autocomplete/', {
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
            $("#cproveedor_id").val(""+item.id);
        }); 
    });
</script>
<h2><?php echo $title;?></h2>
<?php echo form_open($ruta . '/nomina_c/asignar_vacaciones', array('name' => 'form1', 'id' => 'form1'));
    echo form_fieldset('<b>Datos de las vacaciones</b>');?>
        <table class="form_table" style="width: 30%; margin-right: auto; margin-left: auto;">
            <tr>
                <th colspan="2">
                    Seleccionar empleado
                </th>
            </tr>
            <tr>
                <td class="form_tag">
                    Empleado:
                </td>
                <td class="form_input">
                    <input type="hidden" id="empleado_id" name="empleado_id"/>
                    <input type="text" id="empleado" name="empleado" size="30"/>
                </td>
            </tr>
            <tr>
                <th colspan="2">
                    Seleccionar fechas
                </th>
            </tr>
            <tr>
                <td class="form_tag">
                    Fecha inicio:
                </td>
                <td class="form_input">
                    <input type="text" class="date_input" id="fecha_inicio" name="fecha_inicio" size="10"/>
                </td>
            </tr>
            <tr>
                <td class="form_tag">
                    Fecha fin:
                </td>
                <td class="form_input">
                    <input type="text" class="date_input" id="fecha_fin" name="fecha_fin" size="10"/>
                </td>
            </tr>
            <tr>
                <td colspan='6' class="form_buttons">
                    <?php echo form_reset('form1', 'Borrar');?>
                    <button type='button' onclick="window.location='<?php echo site_url('/inicio/acceso/nomina/menu'); ?>'">Cerrar sin guardar</button>
                    <?php if (substr(decbin($permisos), 0, 1) == 1) { ?>
                        <button type="submit">Guardar Registro</button>
                    <?php } ?>
                </td>
            </tr>
        </table>
    <?php echo form_fieldset_close();
echo form_close();?>