<style>
    .tableClass{
        width: 960px;
    }
    
    .tableClass2 td{
        border-bottom: 1px solid #CCC;
        height: 30px;
    }
</style>

<?php
echo "<h2>$title</h2>";
foreach ($espacios->all as $row) {
    $y = $row->id;
    $select2[$y] = $row->tag;
}
?>
<script>
var base_url='<?= base_url() ?>';
var LoadAutocomplete = function (line){
        $("#Descripcion"+line).autocomplete('<?= base_url() ?>index.php/ajax_pet/get_producto_num_autocomp/', {
            extraParams: {}, minLength: 3, multiple: false, cacheLength:0, noCache: true,
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
            var linea = parseInt($(this).attr("linea"));
            
            $("#CodigoBarras"+linea).val(item.codigo_barras === "" ? "Sin Codigo" : item.codigo_barras);
            var found = find_in_list(item.codigo_barras);
            if (!found) {
                if(item.id > 0 && $("#linea"+(linea+1)).length === 0) {
                    $("#pid"+linea).val(item.id);
                    $("#nid"+linea).val(item.nid);
                    insert_new_line(linea);  
                    LoadAutocomplete(linea+1);
                    bindSave();
                    $("#Cantidad"+linea).focus();
                }
            } else {
                $("#Descripcion"+linea).val("");
                $("#CodigoBarras"+linea).val("");
            }
        });   
    };
    
var SaveArqueoDetalle = function(nid, pid, cantidad, linea, espacio, detalle){
    var arqueo = parseInt($("#id").val());
    $.post("<? echo base_url();?>index.php/supervision/trans/guardar_arqueo_detalle",
    { nid: nid, pid : pid, cantidad :  cantidad, arqueo : arqueo, espacio : espacio, detalle : detalle},
    function(data){
        //use data here        
        $("#Bien"+linea).show();
        $("#detalle"+linea).val(data);
    });
};
    
function bindSave (){
    $(".CantidadSave").blur(function () {
            var self = $(this);
            var linea = self.attr("linea");
            var pid = $("#pid"+linea).val();
            var nid = $("#nid"+linea).val();
            var detalle = $("#detalle"+linea).val();
            var espacio = $("[name|=espacio_fisico_id]").val();
            SaveArqueoDetalle(nid, pid, self.val(), linea, espacio, detalle);
    });
    $(".CantidadSave").removeClass("CantidadSave");
}
    

    
$(function() { 
    $('#leyenda1').hide();

    //opciones para el formulario principal
    $($.date_input.initialize);
    var options = { 
        target:        '#archivo',   // target element(s) to be updated with server response 
        //        beforeSubmit:  form_principal,  pre-submit callback 
        success:       mostrar_subform // post-submit callback 
    }; 

    //Redirecciones a los contenedores de los estatus de los detalles y envio mediante ajax
    $('#form1').submit(function() {
        $(this).ajaxSubmit(options);
        // always return false to prevent standard browser submit and page navigation
        return false;
    });

    find_by_code();
    LoadAutocomplete(1);
    bindSave();

});
    
function find_by_code(){
        //Live para buscar por codigo en lineas creadas dinamicamente
    $(".CodigoBarras").live("keypress",function (e){
        if(e.keyCode == 13){
            var linea =  parseInt($(this).attr("linea"));
            var codigo_barras = $(this).val();                
            var found = find_in_list(codigo_barras);
            if(!found){
                //Buscar Producto por codigo de Barras
                get_producto_by_cod_bar(codigo_barras, linea);
            } else {
                $(this).val("");
            }                
        }
    });
};
    
function find_in_list(codigo_barras){
    var found = false;
    var count = 0;
    $.each($(".CodigoBarras"),function(){
        if($(this).val() == codigo_barras){
            if(!found){
                var linea = $(this).attr("linea");
                var cantidad = $("#Cantidad"+linea);
                var cantidad_val = $(cantidad).val();
                cantidad_val = cantidad_val === "" ? 1 : parseInt(cantidad_val)+1;
                cantidad.val(cantidad_val);
                cantidad.blur();
                found = true; 
            }                               
            count=count+1;
        }            
    });
    return found && (count>1);
};
    
function insert_new_line(linea){
    var newline=parseInt(linea)+1;
    //Template para clonar la linea con nuevo id
    var $newLineTemplate = $('<tr id="linea'+newline+'">'+
        '<td><input type="text" id="CodigoBarras'+newline+'" size="15" class="CodigoBarras" linea="'+newline+'"></td>'+
        '<td>'+
        '<input type="text" id="Descripcion'+newline+'" linea="'+newline+'" size="60">'+
        '<input type="hidden" id="pid'+newline+'"/>'+
        '<input type="hidden" id="nid'+newline+'"/>'+
        '<input type="hidden" id="detalle'+newline+'"/>'+
        '</td>'+
        '<td><input type="text" id="Cantidad'+newline+'" class="Cantidad CantidadSave" linea="'+newline+'"></td>'+
        '<td><img style="display:none" id="Bien'+newline+'" src="'+base_url+'images/bien.png"></td></tr>');
    //Insertar en la tabla
    $newLineTemplate.appendTo('#Productos');
}

function get_producto_by_cod_bar(codigo_barras, linea){
    
    $.post("<? echo base_url();?>index.php/ajax_pet/get_producto_by_cod_bar",{ enviarvalor: codigo_barras},
    function(data){
        var producto = eval("("+data+")");  
        //Crear linea dinamicamente si no existe
        if(producto.id > 0 && $("#linea"+(linea+1)).length == 0) {
            $("#pid"+linea).val(producto.id);
            $('#cantidad'+linea).val(parseInt(producto.existencia));  
            $("#nid"+linea).val(producto.nid);
            $("#detalle"+linea).val(producto.detalle);
            $("#CodigoBarras"+linea).val(producto.codigo_barras == "" ? "Sin Codigo" : producto.codigo_barras);
            insert_new_line(linea);  
            LoadAutocomplete(linea+1);
            bindSave();
            $("#CodigoBarras"+(linea+1)).focus();
        }
        $("#Descripcion"+linea).val(producto.descripcion);
        $('#Cantidad'+linea).val(1).blur();
    });
};

    function send1(){
        document.report.submit();
    }

function mostrar_subform(){
    $('#boton1').hide();
    $('#leyenda1').show();
    $("#Productos").show();
    $("#finalizar_btn").show();
}

function finalizar_arqueo(){
    var arqueo_id = parseInt($("#id").val());
    var espacio = $("[name|=espacio_fisico_id]").val();
    $.post("<? echo base_url();?>index.php/supervision/trans/finalizar_arqueo_total",{ arqueo_id: arqueo_id, espacio : espacio},
    function(data){
        $("#save_message").text(data);
    });
}
    
    
</script>
<?php
$atrib = array('name' => 'form1', 'id' => 'form1');
echo form_open($ruta . "/trans/alta_ajuste_inventario/", $atrib) . "\n";
$img_row = "" . base_url() . "images/table_row.png";
?>
<table class='form_table tableClass'>
    <tr>
        <td valign="top">
            <table border="1" class="tableClass">
                <tr>
                    <th colspan="0">Sucursal</th>
                    <th colspan="2">Inicio</th>
                </tr>
                <tr>
                    <td><?php echo form_dropdown('espacio_fisico_id', $select2, 0); ?></td>
                    <td>Fecha<br /> <input type='input' name='fecha_inicio'
                                           class="date_input" value='' size="15" title="Fecha de inicio"
                                           readonly='readonly'>
                    </td>
                    <td>Hora<br /> <input type='input' name='hora_inicio' value=''
                                          size="5" title="Hora en Formato 24 hrs">
                    </td>                   
                </tr>
            </table>
        </td>
    </tr>

    <?php
    echo "<tr><td class=\"form_buttons\" width=\"30%\" valign=\"top\" align=\"right\">";
    echo "<div id=\"out_form1\" align='right'>";
    //Permisos de Escritura byte 1
    if (substr(decbin($permisos), 0, 1) == 1) {
        echo '<button type="submit" id="boton1" style="display:block;">Iniciar Inventario</button>';
        echo "<h1 id='archivo' align='center'></h1>";//El id del Ajuste Registrado
    }
    echo "</div>";
    echo '</td></tr></table>';
    echo form_fieldset_close();
    echo form_close();
    ?>
    
<div class="tableClass" style="margin:auto">
    <div id="leyenda1">
        <strong>Paso 2. Ingrese el Codigo de Barras o la descripcion del producto</strong>        
        <hr>
    </div>    
</div>

<table id="Productos" style="display:none" class="tableClass tableClass2">
    <tr>
        <td>Codigo de Barras</td>
        <td>Descripcion</td>
        <td>Cantidad En tienda</td>
        <td></td>
    </tr>
    <tr id="linea1">
        <td><input type="text" id="CodigoBarras1" class="CodigoBarras" size="15" linea="1" /></td>
        <td>
            <input type="text" id="Descripcion1" class="Descripcion" linea="1" size="60"/>
            <input type="hidden" id="pid1"/>
            <input type="hidden" id="nid1"/>
            <input type="hidden" id="detalle1"/>
        </td>
        <td><input type="text" id="Cantidad1" class="Cantidad CantidadSave" linea="1"/></td>
        <td><img style="display:none" id="Bien1" src="<?= base_url()?>images/bien.png"></td>
    </tr>
</table>
<div class="tableClass" id="finalizar_btn" style="margin:auto; display:none">
    <input type="button" value="Finalizar arqueo" onclick="finalizar_arqueo()"/>
    <div id="save_message"></div>
    <hr>    
</div>