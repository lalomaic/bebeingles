<?php
$dif=md5(rand());
//Titulo
echo "<h2>$title</h2>";
?>
<script>
    $(document).ready(function() {
        
        $('#cod_bar').keypress(function(e) { 
		if(e.keyCode==13){
                    get_producto_by_cod_bar($(this).val());
	            return false;	
            } 
                
                 
	});
        
        $('#producto_id').val('');
        $('#producto_m').val('');
        $('#producto_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_productos_numeracion/<?php echo $dif; ?>', {
            extraParams: {pid: 0, mid:0 },
            minLength: 3,
	    multiple: false,
	    cacheLength:0,
	    noCache: true,
            parse: function(data) {
                return $.map(eval(data), function(row) {
                    return {
                        data: row,
                        value: row.id,
                        value1:row.nid,
                        result: row.descripcion,
                        value2: row.codigo
                        
                        
                    }
                });
            },
            formatItem: function(item) {
                return format(item);
            }
        }).result(function(e, item) {
            $("#producto_id").val(""+item.id);
            $("#producto_m").val(""+item.nid);
          $("#cod_bar").val(""+item.codigo);
        });
        
        
        	
          
    });
    function format(r) {
        return r.descripcion;
    }
    
    function get_producto_by_cod_bar(cod_bar){
    $.post("<? echo base_url();?>index.php/ajax_pet/get_producto_by_cod_bar",{ enviarvalor: cod_bar},
    function(data){
        var producto = eval("("+data+")");
        $('#producto_drop').val(producto.descripcion);
        $("#producto_id").val(producto.id);
	$("#producto_m").val(producto.nid);
    });
}


</script>
<?php
$atrib = array('name' => 'report', 'target' => "pdf");
echo form_open($ruta . "/" . $ruta . "_reportes/busqueda_producto_pdf/", $atrib) . "\n";
echo form_hidden('subfuncion', "$subfuncion");
echo form_fieldset('<b>Argumentos del Reporte</b>') . "\n";
$img_row = "" . base_url() . "images/table_row.png";
?>
<table width="100%" class='form_table'>
    <tr>
        <td width="30%" valign="top" align="center">
            <table border="1">
                <tr>
                    <th colspan="4">Filtrar</th>
                </tr>
                <tr>
                    <td colspan="0"><label for="cod_bar">Codigo de Barra: </label></td>
                    <td colspan="3">
                        <input id='cod_bar' name='cod_bar' class='cod_bar' value='' size='60'>
                    </td>
                </tr>
                <tr>
                    <td colspan="0">Producto:</td>
                    <td colspan="3">
                        <input type='hidden' name='producto_id' id='producto_id' size='3' value='' />
                        <input type='hidden' name='producto_m' id='producto_m' size='3' value='' />
               <input id='producto_drop' value='' size='60' name='producto_drop'  />
                    </td>
                </tr>
                <tr>
                    <td colspan="4" align="right"><input type="submit" value="Informe" />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan='4'><iframe src='' name="pdf" width="100%" height='700'></iframe>
        </td>
    </tr>
</table>
</form>