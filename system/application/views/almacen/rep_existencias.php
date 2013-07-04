<?php
$fecha = array(
		'class' => 'date_input',
		'name' => 'fecha',
		'size' => '10',
		'readonly' => 'readonly',
		'value' => date('d m Y')
);
$hora = array(
		'name' => 'hora',
		'size' => '10',
		'readonly' => 'readonly',
		'value' => date('H:i')
);
?>
<h2>
	<?php echo $title ?>
</h2>
<script>
    $(document).ready(function() {
      $('#formKardex').ajaxForm({
            success: function(data) {
                $('#divReporte').html(data);
            },
            beforeSubmit: function() {
                $('#loadingAjax, #buttonInforme').toggle();
            },
            complete: function() {
                $('#loadingAjax, #buttonInforme').toggle();
            }
        });
       
 $('#productoDrop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_ajax_productos/', {
            extraParams: {pid: 0, mid:0 },
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
                return item.descripcion;
            }
        }).result(function(e, item) {
            $("#productoId").val(item.id);
        });      
       
       
    });
</script>
<?php
echo form_open("$ruta/{$ruta}_reportes/{$funcion}_pdf/", array('id' => 'formKardex'));
echo form_fieldset('<b>Argumentos del Reporte</b>');
?>
<table class="verticalTitle">
	<tr>
		<th>Tienda</th>
		<td><?php echo form_dropdown('espacio_id', $espacios, 0, 'id="espacios"') ?>
		</td>
	</tr>
	<tr>
		<th>Fecha</th>
		<td>Día: <?php echo form_input($fecha) ?> Hora: <?php echo form_input($hora) ?>
		</td>
	</tr>
	<tr id="rowProductos">
		<th>Producto</th>
		<td><input type="hidden" name="producto_id" value="0" id="productoId" />
			<input type="text" id="productoDrop" value="TODOS" size="30" />
		</td>
	</tr>
	<tr>
		<td colspan="2" class="tdButtons"><span id="loadingAjax"
			class="loadingAjax">Cargando el reporte</span> <input type="submit"
			value="Informe" id="buttonInforme" />
		</td>
	</tr>
</table>
<?php
echo form_fieldset_close();
echo form_close();
?>
<div id="divReporte">
	<div class="instructions">Por favor, seleccione los datos para la
		generación del reporte y de clic en informe, dependiendo la
		complejidad, nivel de detalle y periodo, este proceso puede variar en
		su tiempo de procesamiento</div>
</div>
