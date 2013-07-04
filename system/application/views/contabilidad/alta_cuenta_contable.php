<?php
//Inputs
$cta = array(
    'name' => 'cta',
    'size' => '10',
    'value' => '',
    'id' => 'cta'
);
$scta = array(
    'name' => 'scta',
    'size' => '10',
    'value' => '',
    'id' => 'scta',
    'disabled' => 'true'
);
$sscta = array(
    'name' => 'sscta',
    'size' => '10',
    'value' => '',
    'id' => 'sscta',
    'disabled' => 'true'
);
$tag = array(
    'name' => 'tag',
    'size' => '80',
    'value' => '',
    'id' => 'tag'
);
$cuenta_auto = array(
    'size' => '80',
    'value' => '',
    'id' => 'cuenta_auto'
);
$scuenta_auto = array(
    'size' => '80',
    'value' => '',
    'id' => 'scuenta_auto'
);
$sscuenta_auto = array(
    'size' => '80',
    'value' => '',
    'id' => 'sscuenta_auto'
);
//Abrir Formulario
?>
<script>
    $(document).ready(function() {
        $("#cuenta_auto").autocomplete('<?php echo base_url() ?>index.php/ajax_pet/get_cuenta_like/', {
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
                return format(item);
            }
        }).result(function(e, item) {
            if(item.id == 0) {
                return;
            }
            $("#ctipo_cuenta_contable_id").children('option[value="'+item.tipo_cuenta+'"]').attr("selected", "true");
            $("#tipo_cuenta_contable").hide();
            $("#cta").val(item.id);
            //Cuentas
            $("#spanCta").html("<strong>Cuenta</strong> " + item.descripcion);
            $("#spanCta").show();
            $("#divCta").hide();
            //Subcuenta
            $("#divScta").show();
            $("#spanScta").hide();
            //SubSubcuenta
            $("#divSscta").hide();
            $("#spanSscta").hide();
            
            $("#scta").removeAttr("disabled");
            $("#scta").focus();
        });
        $("#scuenta_auto").autocomplete('<?php echo base_url() ?>index.php/ajax_pet/get_scuenta_like/', {
            extraParams: {cta: function() { return $("#cta").val();}},
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
                return format(item);
            }
        }).result(function(e, item) {
            if(item.id == 0) {
                return;
            }
            $("#scta").val(item.id);
            //Subcuenta
            $("#spanScta").html(", <strong>Subcuenta</strong> " + item.descripcion);
            $("#divScta").hide();
            $("#spanScta").show();
            //SubSubcuenta
            $("#divSscta").show();
            $("#spanSscta").hide();
            
            $("#sscta").removeAttr("disabled");
            $("#sscta").focus();
        });
        $("#sscuenta_auto").autocomplete('<?php echo base_url() ?>index.php/ajax_pet/get_sscuenta_like/', {
            extraParams: {cta:  function() { return $("#cta").val();}, scta:  function() { return $("#scta").val();}},
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
                return format(item);
            }
        }).result(function(e, item) {
            if(item.id == 0) {
                return;
            }
            $("#sscta").val(item.id);
            //SubSubcuenta
            $("#spanSscta").html(", <strong>SubSubcuenta</strong> " + item.descripcion);
            $("#divSscta").hide();
            $("#spanSscta").show();
            
            $("#tag").focus();
        });
        
        $("#cta").change(function() {
            $.post(
            '<?php echo base_url() ?>index.php/ajax_pet/get_cuenta_by_id/'+$("#cta").val(),
            {},
            function(data) {
                if(data.id == '0') {
                    $("#tipo_cuenta_contable").show();
                    //$("#cta").val('');
                    $("#scta").attr("disabled", true);
                    $("#sscta").attr("disabled", true);
                    //Cuentas
                    $("#divCta").show();
                    $("#spanCta").hide();
                    //Subcuentas
                    $("#spanScta").hide();
                    $("#divScta").hide();
                }else{
                    $("#ctipo_cuenta_contable_id").children('option[value="'+data.tipo_cuenta+'"]').attr("selected", "true");
                    $("#tipo_cuenta_contable").hide();
                    $("#scta").removeAttr("disabled");
                    $("#spanCta").html("<strong>Cuenta</strong> " + data.descripcion);
                    //Cuentas
                    $("#spanCta").show();
                    $("#divCta").hide();
                    //Subcuenta
                    $("#divScta").show();
                    $("#spanScta").hide();
                    
                    $("#scta").focus();
                }
                //SubSubcuenta
                $("#divSscta").hide();
                $("#spanSscta").hide();
            },
            'json');
        });
        $("#scta").change(function() {
            $.post(
            '<?php echo base_url() ?>index.php/ajax_pet/get_scuenta_by_id/'+$("#scta").val()+"/"+$("#cta").val(),
            {},
            function(data) {
                $("#sscta").attr("disabled", true);
                if(data.id == '0') {
                    //$("#scta").val('');
                    $("#sscta").attr("disabled", true);
                    //Subcuentas
                    $("#spanScta").hide();
                    $("#divScta").show();
                    //SubSubcuenta
                    $("#divSscta").hide();
                    $("#spanSscta").hide();
                }else{
                    $("#sscta").removeAttr("disabled");
                    $("#spanScta").html(", <strong>Subcuenta</strong> " + data.descripcion);
                    //Subcuenta
                    $("#divScta").hide();
                    $("#spanScta").show();
                    //SubSubcuenta
                    $("#divSscta").show();
                    $("#spanSscta").hide();
                    for(var ss in data.subsubcuentas){
                        $("#subsubcuentas").append($("<div>"+data.subsubcuentas[ss]+"</div>"));
                    }
                    
                    $("#sscta").focus();
                }
            },
            'json');
        });
        $("#sscta").change(function() {
            $.post(
            '<?php echo base_url() ?>index.php/ajax_pet/get_sscuenta_by_id/'+$("#sscta").val()+"/"+$("#scta").val()+"/"+$("#cta").val(),
            {},
            function(data) {
                if(data.id == '0') {
                    //$("#sscta").val('');
                    //SubSubcuenta
                    $("#divSscta").show();
                    $("#spanSscta").hide();
                }else{
                    $("#spanSscta").html(", <strong>Sub-subcuenta</strong> " + data.descripcion);
                    //SubSubcuenta
                    $("#divSscta").hide();
                    $("#spanSscta").show();
                }
            },
            'json');
        });
        
        $("#spanCta").hide();
        $("#spanScta").hide();
        $("#divScta").hide();
        $("#spanSscta").hide();
        $("#divSscta").hide();
    });
    function format(r) {
        return r.descripcion;
    }
</script>
<h2><?php echo $title ?></h2>
<div align="left">
    <table style="margin: 0;text-align:center;">
        <tr>
            <td>
                <a href="<?php echo base_url() . "index.php/$ruta/$controller/$funcion/list_cuentas_contables" ?>">
                    <img src="<?php echo base_url() . "images/list.png" ?>" width="30px" title="Listado de Cuentas contables" alt="Listado de Cuentas contables">
                    Listado de cuentas contables
                </a>
            </td>
        </tr>
    </table>
</div>
<?php
echo form_open($ruta . '/trans/alta_cuenta_contable', array('name' => 'form1', 'id' => 'form1'));
echo form_fieldset('<b>Datos de la Nueva Cuenta Contable</b>');
$img_row = base_url() . "images/table_row.png";
?>
<table class="form_table">
    <tr>
        <td class="form_tag">
            <label for="cta">Cuenta:</label> <?php echo form_input($cta) ?>
        </td><td class="form_tag">
            <label for="scta">Sub-Cuenta:</label> <?php echo form_input($scta) ?>
        </td><td class="form_tag">
            <label for="sscta">Sub-Sub-Cuenta:</label> <?php echo form_input($sscta) ?>
        </td>
        <td class="form_tag">
            <label for="ccuenta_contable_naturaleza_id">Naturaleza cuenta:</label> 
            <?php echo form_dropdown("ccuenta_contable_naturaleza_id",$cuentas_naturalezas, 0, 'id="ccuenta_contable_naturaleza_id"') ?>
        </td>
        <td class="form_tag" id="tipo_cuenta_contable">
            <label for="ctipo_cuenta_contable_id">Tipo cuenta:</label> <?php echo form_dropdown("ctipo_cuenta_contable_id",$tipos_cuentas, 0, 'id="ctipo_cuenta_contable_id"') ?>
        </td>
    </tr><tr>
        <td class="form_tag" colspan="5">
            <label for="tag">Nombre:</label> <?php echo form_input($tag) ?>
        </td>
    </tr>
    <tr>
        <td colspan="5">
            <span id="spanCta"></span><span id="spanScta"></span><span id="spanSscta"></span>
            <div id="divCta">Sin cuenta seleccionada. Seleccion por nombre: <?php echo form_input($cuenta_auto) ?></div>
            <div id="divScta">Sin subcuenta seleccionada. Seleccion por nombre: <?php echo form_input($scuenta_auto) ?></div>
            <div id="divSscta">Sin sub-subcuenta seleccionada. Seleccion por nombre: <?php echo form_input($sscuenta_auto) ?></div>
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
<div id="subsubcuentas"></div>
<?php echo form_close() . form_fieldset_close() ?>