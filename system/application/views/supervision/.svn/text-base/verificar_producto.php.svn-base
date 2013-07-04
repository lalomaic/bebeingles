<?php
$codigo = array(
		'name' => 'codigo',
		'size' => '30'
);
?>
<h2>
	<?php echo $title ?>
</h2>
<script>
    $(document).ready(function() {
        $('#form').ajaxForm({
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
        $('#loadingAjax').hide();
        $('input:first').focus();
    });
</script>
<?php
echo form_open("$ruta/{$ruta}_reportes/$funcion/", array('id' => 'form'));
echo form_fieldset('<b>Argumentos del Reporte</b>');
?>
<table class="verticalTitle">
	<tr>
		<th>Código</th>
		<td><?php echo form_input($codigo) ?></td>
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
	<div class="instructions">Escribe el código y da click en informe</div>
</div>
