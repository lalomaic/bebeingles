<?php
$fecha_inicio = $fecha_fin = array(
		'class' => 'date_input',
		'name' => 'fecha_inicio',
		'size' => '10',
		'readonly' => 'readonly',
		'value' => '1 ' . date('m Y')
);
$fecha_fin['name'] = 'fecha_fin';
$fecha_fin['value'] = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')) . ' ' . date('m Y');
?>
<h2>
	<?php echo $title ?>
</h2>
<script>
    $(document).ready(function() {
        $('#espacios').select_autocomplete({
            fieldWidth: 30,
            width: 230
        });
//        $('#marcaDrop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_marcas_ajax/', {
//            extraParams: {pid: 0 },
//            minLength: 3,
//            multiple: false,
//            noCache: true,
//            parse: function(data) {
//                return $.map(eval(data), function(row) {
//                    return {
//                        data: row,
//                        value: row.id,
//                        result: row.descripcion
//                    }
//                });
//            },
//            formatItem: function(item) {
//                return item.descripcion;
//            }
//        }).result(function(e, item) {
//            if(item.id == 0) {
//                $('#rowProductos, #rowCorridas').hide();
//                $('#productoDrop').val('TODOS');
//                $('#productoId').val(0);
//            }else{
//                $('#rowProductos').show();
//            }
//            $("#marcaId").val(item.id);
//        });
        $('#productoDrop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_ajax_productos/', {
            extraParams: {mid: function() {return $('#marcaId').val()} },
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
            if(item.id == 0) {
                $('#rowCorridas').hide();
                $('#corridaDrop').val('TODAS');
                $('#corridaId').val(0);
            }
//            }else{
//                $('#rowCorridas').show();
//            }
            $("#productoId").val(item.id);
        });
        $('#corridaDrop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_productos_numeracion_all/', {
            extraParams: {pid: function() {return $('#productoId').val()}, mid: function() {return $('#marcaId').val()}},
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
        $($.date_input.initialize);
     $('#rowMarca,#rowCorridas,#loadingAjax').hide();
        $('input:first').focus();
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
		<td>Del <?php echo form_input($fecha_inicio) ?> al <?php echo form_input($fecha_fin) ?>
		</td>
	</tr>
	<tr>
		<th>Entradas</th>
		<td><?php echo form_dropdown('entrada_id', $entradas, 0, 'class="width100"') ?>
		</td>
	</tr>
	<tr>
		<th>Salidas</th>
		<td><?php echo form_dropdown('salida_id', $salidas, 0, 'class="width100"') ?>
		</td>
	</tr>
	<tr id="rowMarca">
		<th>Marca</th>
		<td><input type="hidden" name="marca_id" value="0" id="marcaId" /> <input
			type="text" id="marcaDrop" value="TODAS" size="30" />
		</td>
	</tr>
	<tr id="rowProductos">
		<th>Producto</th>
		<td><input type="hidden" name="producto_id" value="0" id="productoId" />
			<input type="text" id="productoDrop" value="TODOS" size="30" />
		</td>
	</tr>
	<tr id="rowCorridas">
		<th>Corrida</th>
		<td><input type="hidden" name="corrida_id" value="0" id="corridaId" />
			<input type="text" id="corridaDrop" value="TODAS" size="30" />
		</td>
	</tr>
	<tr>
		<th>Detalle</th>
		<td><input type="radio" name="order_corrida" value="0" checked />Producto
			<input type="radio" name="order_corrida" value="1" />Tallas</td>
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
