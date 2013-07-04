<script>
    var salidaDevolucion = {
        desavilitarPorEnvio : function(){
            $("input[name=tipo_envio]").change(function(){
                    if($("#tipo_envio").attr("checked")){
                        $('.por-envio').removeAttr("disabled");
                    } else {
                        $('.por-envio').attr('disabled', 'disabled');
                    }
                }
            );
        },

        loadProveedores : function(){
            $('#proveedor').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_proveedores_ajax_autocomplete/', {
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
                    return item.descripcion;
                }
            }).result(function(e, item) {
                $("#proveedor_id").val(item.id);
            });

        },

        loadProductosExistentes : function(){
            $(".prod").autocomplete('<?= base_url() ?>index.php/ajax_pet/productos_existecia_general/', {
                extraParams: { mid: 0},
                minLength: 3,
                multiple: false,
                cacheLength:0,
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
                        var linea = $(this).attr("linea");
                        $("#pid-"+linea).val(item.id);
                        $('#cantidad-'+linea).val(parseInt(item.existencia));
                        $('#costo-'+linea).val(parseInt(item.precio_compra));
                        $("#nid-"+linea).val(item.nid);
                        $("#cod_bar-"+linea).val(item.cod_bar);
                        salidaDevolucion.sum();
                        $("#linea"+linea).show();
            });

        },
        
        loadProductoCodigo: function(){
            $(".cod_bar").keypress(function(e){
            	   var cod_bar = $(this).val();
            	   var linea = $(this).attr("line");
                if(e.keyCode == 13){
                    $.ajax({
                        url: "<?=base_url()?>index.php/ajax_pet/producto_existecia_general_codigo/",
                        dataType: "json",
                        type: "POST",
                        data: { cod_bar : cod_bar },
                        success: function(item){
                            $("#pid-"+linea).val(item.id);
                            $('#cantidad-'+linea).val(parseInt(item.existencia));
                            $('#costo-'+linea).val(parseInt(item.precio_compra));
                            $("#nid-"+linea).val(item.nid);
                            $("#name-"+linea).val(item.descripcion);
                            salidaDevolucion.sum();
                            $("#linea"+linea).show();
                        
                        }
                    })                
                }	
            });	
        },

        saveDevolucion : function(){
            var proveedor_id = $("#proveedor_id").val();
            if(proveedor_id == ""){
                alert("Seleccione un proveedor");
                return;
            }

            var id = $("#id").val();
            var fecha = $("#fecha").val();

            if ($("#tipo_envio").attr("checked")){
                var transporte = $("#transporte").val();
                var numero_guia = $("#numero_guia").val();
                var domicilio = $("#domicilio").val();
                var tipo = "envio";
                var tipo_entraga = $("#tipo_entrega").val();
            } else {
                var transporte = "";
                var numero_guia = "";
                var domicilio = "";
                var tipo = "directa";
                var tipo_entraga = "";
            }

            $.ajax({
                type: "post",
                data: {
                        transporte: transporte,
                        proveedor_id : proveedor_id,
                        numero_guia : numero_guia,
                        domicilio : domicilio,
                        tipo : tipo,
                        tipo_entrega : tipo_entraga,
                        fecha : fecha,
                        id : id
                    },
                url: "<? echo base_url(); ?>index.php/almacen/trans/save_devolucion/",
                success: function (data) {
                    $("#save-button").hide();
                    $("#devolucion_proveedor_id").val(data);
                    salidaDevolucion.saveSalidas();
                }
            });
        },

        bindSaveButton : function(){
            $("#save-button").click(function(){
                salidaDevolucion.saveDevolucion();
            });
            $("#finalizar-button").click(function(){
                window.location = "<?= base_url()?>index.php/almacen/almacen_c/formulario/list_devoluciones_proveedor"
            });
        },
        
        saveSalidas: function(){
            for(i=0; i<500; i++ ){
                var pid = parseInt($("#pid-"+i).val());
                var cantidad = parseInt($("#cantidad-"+i).val());

                if(pid > 0 && cantidad > 0){
                    salidaDevolucion.saveSalida(i);
                }
            }
            $("#finalizar-button").show();
        },

        saveSalida : function(linea){
            $("#img-wait-"+linea).show();
            var proveedor_id = $("#proveedor_id").val();
            var fecha = $("#fecha").val();
            var cproductos_id = $("#pid-"+linea).val();
            var cantidad = $("#cantidad-"+linea).val();
            var cproducto_numero_id = $("#nid-"+linea).val();
            var devolucion_proveedor_id = $("#devolucion_proveedor_id").val();
            var costo_unitario = $("#costo-"+linea).val();
            var costo_total = cantidad * costo_unitario;

            $.ajax({
                type: "post",
                data: {
                    cproductos_id: cproductos_id,
                    cproveedores_id : proveedor_id,
                    devolucion_proveedor_id : devolucion_proveedor_id,
                    cantidad : cantidad,
                    cproducto_numero_id : cproducto_numero_id,
                    fecha : fecha,
                    costo_unitario : costo_unitario,
                    costo_total : costo_total
                },
                url: "<? echo base_url(); ?>index.php/almacen/salidas/save/",
                success: function (data) {
                    $("#img-wait-"+linea).hide();
                    $("#img-ok-"+linea).show();
                }
            });
        },

        sum : function(){
            var total = 0;
            for(i=0; i<500; i++){
                var cantidad = parseInt($("#cantidad-"+i).val());
                var costo = parseInt($("#costo-"+i).val());
                if(costo > 0 && cantidad > 0){
                    var subtotal = costo * cantidad;
                    $("#sub-total-"+i).val(subtotal);
                    total += subtotal;
                }
            }
            $("#total").val(total);
        },

        bindSum : function(){
            $(".cantidad").blur(salidaDevolucion.sum);
        }
    };
    $(function(){
        salidaDevolucion.desavilitarPorEnvio();
        salidaDevolucion.loadProveedores();
        salidaDevolucion.loadProductosExistentes();
        salidaDevolucion.loadProductoCodigo();
        salidaDevolucion.bindSaveButton();
        salidaDevolucion.bindSum();
        $($.date_input.initialize);
    });
</script>