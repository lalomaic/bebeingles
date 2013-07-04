<?php
//Cuentas filtradas
$polizas_fil[0] = "";
if ($cuentas != false)
    foreach ($cuentas->all as $row)
        $polizas_fil[$row->cta] = "$row->cta - $row->tag";
$select_sucursales[0] = "";
if ($espacios != false)
    foreach ($espacios->all as $espacio)
        $select_sucursales[$espacio->id] = $espacio->tag;
$numero_referencia = array(
    'name' => 'numero_referencia',
    'id' => 'numero_referencia',
    'size' => '10',
    'value' => '',
    'class' => 'input'
);
$consecutivo = array(
    'name' => 'consecutivo',
    'id' => 'consecutivo',
    'size' => '10',
    'value' => '',
    'readonly' => 'readonly',
    'tabindex' => -1
);
$fecha = array(
    'class' => 'date_input',
    'name' => 'fecha',
    'id' => 'fecha',
    'size' => '10',
    'readonly' => 'readonly',
    'value' => date('d m Y')
);
$concepto = array(
    'name' => 'concepto',
    'id' => 'concepto',
    'class' => 'input'
);
//$this->load->view('contabilidad/js_poliza_egreso_manual.php');
?>
<script>
    var
    getConsecutivo = false,
    guardada = false,
    fecha_old = '<?php echo date('d m Y') ?>',
    revisado = true,
    saved = false,
    fecha_dep = -1,
    consecutivo,
    cont = 2,
    detalles = new Array();
    
    $(document).ready(function() {
        $("#saving_poliza").hide();
        $("#saved_poliza").hide();
        $('#concepto').focus();
        update_consecutivo();

        $($.date_input.initialize);
        $('#espacio_fisico_id').select_autocomplete({
            result: "setChange();"
        });
        $('#fecha').change(function() {
            arr_old = fecha_old.split(' ');
            arr_new = $('#fecha').val().split(' ');
            if(guardada && (arr_old[1] != arr_new[1] || arr_old[2] != arr_new[2]))
                if(!confirm('Ha cambiado el mes de una p�liza ya guardada, esto har� que se cancele el consecutivo del mes anterior.\n\
De cancelar si cambi� el mes por error')) {
                    $('#fecha').val(fecha_old);
                    return
                }
            fecha_old = $('#fecha').val();
            update_consecutivo();
            setChange();
        });
        $('.input').change(function () {
            setChange();
        });
        $('#numero_referencia').blur(function() {
            if(!saved)
                $('#formPoliza').submit();
        });
        $('#formPoliza').ajaxForm({
            beforeSend : function() {
                if($("#concepto").val() == "") {
                    $("#validation_result").html("Debe elegir un concepto");
                    $("#concepto").focus();
                    return false;
                }
                if(getConsecutivo) {
                    $("#validation_result").html("Hubo un error al actualizar el consecutivo, por favor intentelo de nuevo");
                    update_consecutivo();
                    return false;
                }
                $("#validation_result").html("");
                $("#saving_poliza").show();
                $("#button_send_poliza").hide();
                $("#saved_poliza").hide();
            },
            error : function() {
                $("#validation_result").html("Error al guardar la p�liza, por favor intente de nuevo");
                $("#saving_poliza").hide();
                $("#button_send_poliza").show();
            },
            success : function(data) {
                $("#saving_poliza").hide();
                if(data.id > 0) {
                    revisado = false;
                    fecha_dep = $("#fecha").val();
                    consecutivo = $("#consecutivo").val();
                    $("#saved_poliza").show();
                    $("#id").val(data.id);
                    saved = true;
                    guardada = true;
                }else{
                    $("#validation_result").html(data.error);
                    $("#button_send_poliza").show();
                }
            },
            dataType : "json"
        });
        
        $('.detalleCta:first').select_autocomplete({widthField:40, width:350});
        setSubcuentaComplete(1);
        $('.subtotal').
            focus(function (){
            $(this).val(unformatNumber($(this).val()));
        }).
            blur(function (){
            $(this).val(formatNumber($(this).val()));
        }).
            change(function() {
            updateTotal();
            $("#saveAll").show();
        });
        $('.subtotal:last').
            blur(function() {
            saveDetalle(1);
        });
        $('.subtotal:first').
            change(function() {
            $(this).parent().parent().attr('style', 'background: #fff1a8;');
            $('#subcuenta_drop1').change();
            detalles[1] = false;
            $("#saveAll").show();
            $(this).parent().siblings('.estatus:first').children('img').hide();
            $('#imgSave1').show();
        });
        $('#descripcion1').
            change(function() {
            $(this).parent().parent().attr('style', 'background: #fff1a8;');
            $('#subcuenta_drop1').change();
            detalles[1] = false;
            $("#saveAll").show();
            $(this).parent().siblings('.estatus:first').children('img').hide();
            $('#imgSave1').show();
        });
        $("#saveAll").hide();
        $("#saveAll").click(function (event) {
            event.preventDefault();
            saveAll();
        });
        $('.img').hide();
        $('#imgDel1').click(function() {deleteDetalle(1)});
        $('#imgSave1').click(function() {saveDetalle(1)});
    });
    
    function saveAll(){
        if(!saved) {
            alert("Primero se deben guardar los cambios de la cabecera, se intentara de manera automotica.\nDespues intente guardar el detalle de nuevo");
            $("#formPoliza").submit();
            return;
        }
        for(var i = 1; i < detalles.length; i++)
            if(!detalles[i])
                saveDetalle(i);
    }
    
    function deleteDetalle(id) {
        if(!confirm("Est�s seguro que deseas eliminar el detalle " + id + " de la poliza?"))
            return;
        var detalleRow = $('#detalleRow'+id);
        $.ajax({
            url:'<?php echo base_url() . "index.php/$ruta/trans/eliminar_detalle_poliza" ?>',
            type:'post',
            beforeSend: function () {
                detalleRow.attr('style', 'background:#ffd011;');
                detalleRow.children().each(function() {
                    $(this).children('input').attr('disabled', 'disabled');
                });
                detalleRow.children('.estatus:first').children('img').hide();
                $('#imgWait'+id).show();
            },
            data: {id : $('#detalle_id'+id).val()},
            dataType: 'json',
            error: function() {
                detalles[id] = false;
                detalleRow.children().each(function() {
                    $(this).children('input').removeAttr('disabled');
                });
                detalleRow.attr('style', 'background: #fff1a8;');
                detalleRow.children('.estatus:first').children('img').hide();
                $('#imgDel'+id).show();
            },
            success: function(data) {
                if(data.id > 0) {
                    detalles[id] = true;
                    detalleRow.attr('style', 'background: #FF7C8A;');
                    detalleRow.children('.estatus:first').children('img').hide();
                    $('#imgCancel'+id).show();
                    updateTotal();
                }else{
                    detalles[id] = false;
                    detalleRow.children().each(function() {
                        $(this).children('input').removeAttr('disabled');
                    });
                    detalleRow.attr('style', 'background: #fff1a8;');
                    detalleRow.children('.estatus:first').children('img').hide();
                    $('#imgDel'+id).show();
                }
            }
        });
    }
    
    function saveDetalle(id) {
        if($('#subcuenta'+id).val() == 0)
            return;
        if(!saved) {
            alert("Primero se deben guardar los cambios de la cabecera, se intentara de manera automatica.\nDespues intente guardar el detalle de nuevo");
            $("#formPoliza").submit();
            return;
        }
        var detalleRow = $('#detalleRow'+id);
        $('.formDetalle:eq('+(id-1)+')').ajaxSubmit({
            data : {
                poliza_id : $("#id").val(),
                detalle : $('#descripcion'+id).val(),
                debe : unformatNumber($('#debe'+id).val()),
                haber : unformatNumber($('#haber'+id).val()),
                cuenta_contable_id : $('#subcuenta'+id).val()
            },
            beforeSend : function() {
                detalleRow.attr('style', 'background:#ffd011;');
                detalleRow.children().each(function() {
                    $(this).children('input').attr('disabled', 'disabled');
                });
                detalleRow.children('.estatus:first').children('img').hide();
                $('#imgWait'+id).show();
            },
            error : function() {
                detalleRow.children('.estatus:first').children('img').hide();
                $('#imgSave'+id).show();
                detalleRow.children().each(function() {
                    $(this).children('input').removeAttr('disabled');
                });
                detalleRow.attr('style', 'background: #FF7C8A;');
                detalles[id] = false;
            },
            success : function(data) {
                if(data.id > 0) {
                    detalles[id] = true;
                    var i;
                    for(i = 1; i < detalles.length; i++)
                        if(!detalles[i])
                            break;
                    if(i == detalles.length)
                        $("#saveAll").hide();
                    detalleRow.children().each(function() {
                        $(this).children('input').removeAttr('disabled');
                    });
                    $("#detalle_id"+id).val(data.id);
                    detalleRow.removeAttr('style');
                    detalleRow.children('.estatus:first').children('img').hide();
                    $('#imgDel'+id).show();
                }else{
                    detalleRow.children('.estatus:first').children('img').hide();
                    $('#imgSave'+id).show();
                    detalleRow.children().each(function() {
                        $(this).children('input').removeAttr('disabled');
                    });
                    detalleRow.attr('style', 'background: #FF7C8A;');
                    detalles[id] = false;
                }
            },
            dataType : "json"
        });
    }
    
    function updateTotal() {
        var totalDebe = 0.0,
        totalHaber = 0.0;
        $('.debe').each(function() {
            if(!$(this).attr('disabled'))
                totalDebe += parseFloat(unformatNumber($(this).val()));
        });
        $('#debe_t').html(formatNumber(totalDebe));
        $('.haber').each(function() {
            if(!$(this).attr('disabled'))
                totalHaber += parseFloat(unformatNumber($(this).val()));
        });
        $('#haber_t').html(formatNumber(totalHaber));
    }
    
    function setSubcuentaComplete(id) {
        detalles[id] = true;
        $("#subcuenta_drop"+id).autocomplete('<?php echo base_url() ?>index.php/ajax_pet/get_subcuentas_poliza', {
            extraParams: {
                cta: function() {
                    return $("#cta"+id).val();
                }
            },
            minLength: 0,
            multiple: false,
            noCache: true,
            cacheLength: 0,
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
            $(this).siblings(".subcuenta:first").val(item.id);
            if(item.id > 0) {
                $(this).parent().siblings('.estatus:first').children('img').hide();
                $('#imgSave'+id).show();
                $(this).parent().parent().attr('style', 'background: #fff1a8;');
                $('#subcuenta_drop'+id).change();
                detalles[id] = false;
                $("#saveAll").show();
            }
        });
        $('#subcuenta_drop'+id).change(function() {
            if($('#cta'+id).val() > 0 && $('#subcuenta'+id).val() > 0) {
                $('#subcuenta_drop'+id).unbind('change');
                crearRow();
            }
        });
        
    }
    
    function setChange() {
        if(!revisado) {
            $("#saved_poliza").hide();
            $("#button_send_poliza").attr('value', 'Guardar cambios');
            $("#button_send_poliza").show();
            revisado = true;
            saved = false;
        }
    }
    
    function update_consecutivo() {
        getConsecutivo = true;
        var fecha = $("#fecha").val().split(' ');
        if(fecha_dep == $("#fecha").val()) {
            $('#consecutivo').val(consecutivo);
            return;
        }
        $.post(
        '<?php echo site_url("/ajax_pet/get_consecutivo_ingreso") ?>',
        {mes:fecha[1],anio:fecha[2].substring(2,4)},
        function(data){
            $('#consecutivo').val(data);
            getConsecutivo = false;
        });
    }
    
    function salida(link) {
        if(!saved && !confirm("La cabezera esta penditente de guardar, aun asi deseas salir?"))
            return;
        for(var i = 1; i < detalles.length; i++) {
            if(!detalles[i] && !confirm("Existen detalles penditentes de guardar, aun asi deseas salir?"))
                return;
        }
        window.location='<?php echo base_url() . "index.php/" ?>'+link;
    }
    
    function crearRow() {
        var tr = $('#detalleRow1').clone();
        tr.attr('id', 'detalleRow'+cont).removeAttr('style');
        tr.children().each(function() {
            $(this).children('input').removeAttr('disabled');
        });
        var td = tr.children('td:first');
        td.find('.renglon:first').html(cont);
        td.find('input:first').val(0).attr('id', 'detalle_id'+cont);
        td = td.next();
        td.find('.ac_input:first').remove();
        var select = td.find('.detalleCta:first');
        select.attr('id', 'cta' + cont);
        select.children().removeAttr('selected');
        select.children('option:first').attr('selected', 'selected');
        select.select_autocomplete({widthField:40, width:350});
        td = td.next();
        td.find('.subcuenta:first').attr('id', 'subcuenta' + cont).val(0);
        td.find('.subcuenta_drop:first').attr('id', 'subcuenta_drop' + cont).val("Elija una cuenta");
        td = td.next();
        td.find('input:first').
            attr('id', 'descripcion'+cont).
            val('');
        td = td.next();
        td.find('.subtotal:first').
            attr('id', 'debe'+cont).
            val("$0.00").focus(function (){
            $(this).val(unformatNumber($(this).val()));
        }).
            blur(function (){
            $(this).val(formatNumber($(this).val()));
        }).
            change(function() {
            updateTotal();
            $(this).parent().siblings('.estatus:first').children('img').hide();
            $('#imgSave'+id).show();
            $(this).parent().parent().attr('style', 'background: #fff1a8;');
            $('#subcuenta_drop'+id).change();
            detalles[id] = false;
            $("#saveAll").show();
        });
        td = td.next();
        var id = cont;
        td.find('.subtotal:first').
            attr('id', 'haber'+cont).
            val("$0.00").focus(function (){
            $(this).val(unformatNumber($(this).val()));
        }).
            blur(function (){
            $(this).val(formatNumber($(this).val()));
            saveDetalle(id);
        }).
            change(function() {
            updateTotal();
            $("#saveAll").show();
        });
        td = td.next();
        td.children('img').hide();
        td.children('#imgDel1').attr('id', 'imgDel'+cont).click(function() {deleteDetalle(id)});
        td.children('#imgSave1').attr('id', 'imgSave'+cont).click(function() {saveDetalle(id)});
        td.children('#imgWait1').attr('id', 'imgWait'+cont);
        td.children('#imgCancel1').attr('id', 'imgCancel'+cont);
        
        tr.insertBefore('#polizaDetallesTotales');
        setSubcuentaComplete(cont++);
    }
    
    function formatNumber(num) {
        num = parseFloat(num).toFixed(2);
        num += '';
        var splitStr = num.split('.');
        var splitLeft = splitStr[0];
        var splitRight = splitStr.length > 1 ? '.' + splitStr[1] : '';
        var regx = /(\d+)(\d{3})/;
        while (regx.test(splitLeft)) {
            splitLeft = splitLeft.replace(regx, '$1' + ',' + '$2');
        }

        return '$' + splitLeft + splitRight;
    }
    
    function unformatNumber(num) {
        return num.replace(/([^0-9\.\-])/g,'')*1;
    }
</script>
<h2><?php echo $title ?></h2>
<?php
echo form_open("$ruta/trans/$subfuncion", array('id' => 'formPoliza'));
echo form_fieldset("<b>Nueva Poliza de $titulo </b>");
?>
<input type="hidden" id="id" name="id" value="">
<table class="form_table">
    <tr>
        <td colspan="2">
            <div id="validation_result" class="result_validator" align="center"></div>
        </td>
    </tr><tr class="row">
        <td>
            <label>Empresa:</label>
        </td><td>
            <span style="color:#000;"><?php echo $empresa->razon_social ?></span>
        </td>
    </tr><tr class="row">
        <td>
            <label for="concepto">Concepto:</label>
        </td><td>
            <?php echo form_input($concepto, '', "style='width:99%'") ?>
        </td>
    </tr><tr class="row">
        <td>
            <label for="concepto">Sucursal:</label>
        </td><td>
            <?php echo form_dropdown("espacio_fisico_id", $select_sucursales, 0, "id='espacio_fisico_id'") ?>
        </td>
    </tr><tr>
        <td colspan="2">
            <table class="form_table" style="width:100%;">
                <tr class="row">
                    <td>
                        <label for="fecha">Fecha: </label><?php echo form_input($fecha) ?>
                    </td><td>
                        <label for="concepto">Numero de referencia: </label><?php echo form_input($numero_referencia) ?>
                    </td><td>
                        <label for="consecutivo">Consecutivo: </label><?php echo form_input($consecutivo) ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="form_buttons" colspan="2" align="right">
            <input type="submit" value="Capturar detalles" id="button_send_poliza" tabindex="-1"/>
            <div id="saving_poliza">
                <img src="<?php echo base_url() . "images/indicator.gif" ?>" alt="Guardando la poliza" width="16" height="16"/>
                Guardando poliza...
            </div>
            <div id="saved_poliza">
                <img src="<?php echo base_url() . "images/bien16.png" ?>" alt="Poliza guardada" width="16" height="16"/>
                Poliza guardada
            </div>
        </td>
    </tr>
</table>
<?php
echo form_fieldset_close();
echo form_close();
?>
<table class="row_detail" style="margin-top: 10px;" id="polizaDetalles">
    <tr>
        <th></th>
        <th>Cuenta</th>
        <th>Sub Sub Cuenta</th>
        <th>Descripcion</th>
        <th>Debe</th>
        <th>Haber</th>
    </tr><tr id="detalleRow1">
        <td>
            <?php echo form_open("contabilidad/trans/alta_manual_poliza_detalle", 'class="formDetalle"') ?>
            <span class="renglon">1</span>.-
            <input type="hidden" name="id" value="0" id="detalle_id1"/>
        </td><td>
            <?php echo form_dropdown("cta", $polizas_fil, 0, "class='detalleCta' id='cta1'") ?>
        </td><td>
            <input type="hidden" name="cuenta_contable_id" value="0" class="subcuenta" id="subcuenta1"/>
            <input style="width:300px;" value="Elija una cuenta" id="subcuenta_drop1" class="subcuenta_drop"/>
        </td><td>
            <input size="30" id="descripcion1"/>
        </td><td>
            <input class="subtotal debe" value="$0.00" size="12" id="debe1"/>
        </td><td>
            <input class="subtotal haber" value="$0.00" size="12" id="haber1"/>
            <?php echo form_close() ?>
        </td><td class="estatus">
            <img id="imgDel1" class="img" src="<?php echo base_url() ?>images/delete.png" width="16" heigth="16" title="Eliminar detalle" alt="Eliminar detalle"/>
            <img id="imgSave1" class="img" src="<?php echo base_url() ?>images/save16.png" width="16" heigth="16" title="Guardar Cambio" alt="Guardar detalle"/>
            <img id="imgWait1" class="img" src="<?php echo base_url() ?>images/waiting.gif" width="16" heigth="16" title="Realizando operacion"/>
            <img id="imgCancel1" class="img" src="<?php echo base_url() ?>images/cancel16.png" width="16" heigth="16" title="Detalle eliminado" alt="Detalle eliminado"/>
        </td>
    </tr><tr id="polizaDetallesTotales">
        <th colspan="4" class="money_total">
            Totales
        </th><th  class="money_total">
            <span id="debe_t">$0.00</span>
        </th><th  class="money_total">
            <span id="haber_t">$0.00</span>
        </th>
    </tr>
    <tr>
        <th class="detalle_pie" colspan="6">
            <a href="#" title="Salvar todo" onclick="" id="saveAll">
                Salvar Todo
                <img src="<?php echo base_url() . "images/save16.png" ?>" width="16" height="16" alt="Salvar todo" style="margin-bottom: -3px;"/>
            </a> 
            <button type="button" onclick="salida('inicio/acceso/<?php echo $ruta ?>/menu');">Salir al Menu</button>
            <button type="button" onclick="salida('contabilidad/poliza_c/formulario/list_polizas_ingresos');">Ir al listado</button>
            <button type="button" onclick="salida('contabilidad/poliza_c/formulario/alta_manual_poliza_ingreso');">Generar otra poliza</button>
    </tr>
</table>