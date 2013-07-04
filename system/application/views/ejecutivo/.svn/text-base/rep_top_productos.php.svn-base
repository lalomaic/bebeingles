<?php
$select3 = "<option value='0'>Todos</option>";
// espacios fisicos **************************
foreach ($espaciosf as $row) {
	$select3 .= "<option value='$row->id'>$row->tag</option>";
}
$espaciof = "<select name='espaciof' style='width:200px' id='espacios'>" . $select3 . "</select>";

$fecha1 = array(
		'class' => 'date_input',
		'name' => 'fecha1',
		'size' => '10',
		'readonly' => 'readonly',
		'value' => '',
);
$fecha2 = array(
		'class' => 'date_input',
		'name' => 'fecha2',
		'size' => '10',
		'readonly' => 'readonly',
		'value' => '',
);

echo "<h2>$title</h2>";
?>
<script>
    function send1(){
        document.forms[0].submit();
    }

    $(document).ready(function(){

        $($.date_input.initialize);
        $('#cmarca_id').val('0');
        $('#marca_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_marcas_ajax/', {
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
            $("#cmarca_id").val(""+item.id);
        });

    });

    function format(r) {
        return r.descripcion;
    }
</script>
<?php
$atrib = array('name' => 'report', 'target' => "pdf");
echo form_open($ruta . "/" . $ruta . "_reportes/{$funcion}_pdf/", $atrib) . "\n";
echo form_fieldset('<b>Argumentos del Reporte</b>') . "\n";
$img_row = "" . base_url() . "images/table_row.png";
?>
<table width="100%" class='form_table'>
	<tr>
		<td width="30%" valign="top">
			<table border="1">
				<tr>
					<th colspan="4">Paso No 1 - Filtrar Concentrado</th>
				</tr>
				<tr>
					<td colspan="0">Sucursal</td>
					<td colspan="3"><?php echo $espaciof; ?>
					</td>
				</tr>
				<tr>
					<td colspan="0">Marca:</td>
					<td colspan="3"><input type='hidden' name='cmarca_id'
						id='cmarca_id' value='' size="3" /> <input id='marca_drop'
						class='marca' name='marca_drop' value='TODAS' size='30' />
					</td>
				</tr>
				<tr>
					<td colspan="0">Periodo:</td>
					<td colspan="3">Del <input type="text" name="fecha1"
						class="date_input" size="10" /><br /> Al <input type="text"
						name="fecha2" size="10" class="date_input" />
					</td>
				</tr>
				<tr>
					<td colspan="4" align="right"><input type="hidden" name="title"
						value="<?= $title ?>" /> <input type="reset" value="Borrar campos">&nbsp;&nbsp;&nbsp;
						<button type="button" onclick="javascript:send1()">Reporte</button>
						</form>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="70%"><iframe src='' name="pdf" width="100%" height='700'></iframe>
		</td>
	</tr>
</table>
</center>
