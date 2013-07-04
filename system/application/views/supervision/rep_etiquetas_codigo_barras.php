<?php
$dif = md5(rand());
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
                        value2: row.codigo,
                        value1:row.nid,
                        result: row.descripcion
                      
                        
                        
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
        $.post("<? echo base_url(); ?>index.php/ajax_pet/get_producto_by_cod_bar",{ enviarvalor: cod_bar},
        function(data){
            var producto = eval("("+data+")");
            $('#producto_drop').val(producto.descripcion);
            $("#producto_id").val(producto.id);
            $("#producto_m").val(producto.nid);
        });
    }
 function Nom(form,boton){
            form.botPress.value = boton;
            form.submit();
        }
</script>
<?php
$select1[0] = "";
// if($productos!= false){
// 	foreach($productos->all as $row){
// 		$y=$row->id;
// 		$select1[$y]="$row->descripcion - $row->presentacion";
// 	}
// }
$atrib = array('name' => 'report', 'target' => "pdf");
echo form_open($ruta . "/" . $ruta . "_reportes/rep_etiquetas_codigo_barras_pdf/", $atrib) . "\n";
echo form_fieldset('<b>Elija</b>') . "\n";
$img_row = "" . base_url() . "images/table_row.png";
?>
<table width="100%" class='form_table'>
    <tr>
        <td width="100" valign="top" >
            <table border="1">


                <tr>
                    <th>Paso No 1 Elija Producto Descripcion</th>
                </tr>
                <tr>
                    <td>
                        <input type='hidden' name='producto_m' id='producto_m' value='' size=''/><input type='hidden' name='producto_id' id='producto_id' value='0' size="3"><input id='producto_drop' class='.prod' value='' size='60' name='prod'></td>
                </tr><tr><th>O Por Codigo de Barras</th></tr>
                        <tr>
                    <td align="center"><input type="text" name="cod_bar" id="cod_bar"  class="cod_bar" value="" size="50"></td>
                </tr>
                <tr>
                    <th>Paso No 2 Elija la cantidad de etiquetas</th>
                </tr>
                <tr>
                    <td align="center"><input type="text" name="pages"  class="subtotal" value="1" size="10"></td>
                </tr>
        				<tr>
                    <td align="center" align="right">
                        <input type="reset" value="Borrar campos">&nbsp;&nbsp;&nbsp;
                        <input type="hidden" name="botPress" id="botPress">
  <input type="button" name="operacion" value="Generar Etiquetas 3CM" onclick="Nom(this.form,'Generar Etiquetas 3CM')">
  <input type="button" name="operacion" value="Generar Etiquetas 5CM" onclick="Nom(this.form,'Generar Etiquetas 5CM')">

                    </td>
                </tr>
            </table>
            </form>
        </td>
        <td width="70%">
            <iframe src='' name="pdf"  width="100%" height='380'></iframe>
        </td>
    </tr>
</table>
</center>
