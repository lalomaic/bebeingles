<?php
    $espacios[-1] = "Elija";
    
    $fecha_inicial = array(
        'class'=>'date_input',
        'name' => 'fecha_inicial',
        'size' => '10',
        'value' => '',
        'id' => 'fecha_inicial',
        'readonly'=>'readonly'
    );
    $fecha_final = array(
        'class'=>'date_input',
        'name' => 'fecha_final',
        'size' => '10',
        'value' => '',
        'id' => 'fecha_final',
        'readonly'=>'readonly'
    );
    $this->load->view('validation_view');
?>
<script type="text/javascript">
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
        return splitLeft + splitRight;
    }
    function filtrar_datos_nomina(f_inicial, f_final, espacio_id){
        var fi_m = new Array();
        var ff_m = new Array();
        fi_m = f_inicial.split(" ");
        if(parseInt(fi_m[0]) < 10)
            fi_m[0] = "0"+fi_m[0];
        var fechai = fi_m[2]+"/"+fi_m[1]+"/"+fi_m[0];
        ff_m = f_final.split(" ");
        if(parseInt(ff_m[0]) < 10)
            ff_m[0] = "0"+ff_m[0];
        var fechaf = ff_m[2]+"/"+ff_m[1]+"/"+ff_m[0];
        $.post("<?php echo base_url(); ?>index.php/nomina/ajax_pet_nomina/get_empleados_prenomina_by_periodo", { fecha_inicial: fechai, fecha_final: fechaf, espacio_id: espacio_id},
            function(data){
                if(data != "false"){
                    $("#error").hide(1000);
                    $("#nomina_table").show(1000);
                    var tr1 = $(document.createElement("tr"));
                        tr1.append($(document.createElement("th")).append("Empleado"));
                        tr1.append($(document.createElement("th")).append("Salario Diario"));
                        tr1.append($(document.createElement("th")).attr("style", "width: 77px;").append("Dias Labs."));
                        tr1.append($(document.createElement("th")).append("Bono"));
                        tr1.append($(document.createElement("th")).append("Comisión"));
                        tr1.append($(document.createElement("th")).append("Infonavit"));
                        tr1.append($(document.createElement("th")).attr("id", "total_pagar").append("Total a Pagar"));
                        tr1.append($(document.createElement("th")).append("Banco"));
                        tr1.append($(document.createElement("th")).append("Efectivo")); 
                        $("#nomina_table").append(tr1);
                    add_rows_prest_table();    
                    add_rows_deduc_table();
                    return $.map(eval(data), function(row) {
                        var pdid = $(document.createElement("input"));
                        pdid.attr("type", "hidden");
                        pdid.attr("name", "pre_detalle_id[]");
                        pdid.val(row.id);
                        var emplid = $(document.createElement("input"));
                        emplid.attr("type", "hidden");
                        emplid.attr("name", "empleado_id[]");
                        emplid.val(row.empleado_id);
                        var nmbr = $(document.createElement("input"));
                        nmbr.attr("type", "text");
                        nmbr.attr("size", "40");
                        nmbr.attr("name", "empleado[]");
                        nmbr.attr("style", "border: none; background: none; background-image: none; -webkit-box-shadow: none; box-shadow: none;");
                        nmbr.attr("readonly", "readonly");
                        nmbr.val(row.empleado);
                        var slrio = $(document.createElement("input"));
                        slrio.attr("type", "text");
                        slrio.attr("size", "10");
                        slrio.attr("name", "salario_diario[]");
                        slrio.attr("style", "border: none; background: none; background-image: none; -webkit-box-shadow: none; box-shadow: none;");
                        slrio.attr("readonly", "readonly");
                        slrio.val("$ "+row.salario);
                        var dslabrds = $(document.createElement("input"));
                        dslabrds.attr("type", "text");
                        dslabrds.attr("size", "3");
                        dslabrds.attr("name", "dias_labs[]");
                        dslabrds.attr("style", "border: none; background: none; background-image: none; -webkit-box-shadow: none; box-shadow: none;");
                        dslabrds.attr("readonly", "readonly");
                        dslabrds.val(row.dias_lab);
                        var bn = $(document.createElement("input"));
                        bn.attr("type", "text");
                        bn.attr("size", "10");
                        bn.attr("name", "bonos[]");
                        bn.attr("id", "bono");
                        bn.attr("style", "border: none; background: none; background-image: none; -webkit-box-shadow: none; box-shadow: none;");
                        bn.attr("readonly", "readonly");
                        obtener_bono_por_periodo_tienda(fechai, fechaf, espacio_id, row.empleado_id);
                        var cmsn = $(document.createElement("input"));
                        cmsn.attr("type", "text");
                        cmsn.attr("size", "10");
                        cmsn.attr("name", "comisiones[]");
                        cmsn.attr("id", "comision");
                        cmsn.attr("style", "border: none; background: none; background-image: none; -webkit-box-shadow: none; box-shadow: none;");
                        cmsn.attr("readonly", "readonly");
                        cmsn.val("$ 0.00");
                        obtener_pago_total_comision(espacio_id, row.empleado_id, row.comision, row.puesto, fechai, fechaf);
                        var infnvt = $(document.createElement("input"));
                        infnvt.attr("type", "text");
                        infnvt.attr("size", "10");
                        infnvt.attr("name", "infonavit_importes[]");
                        infnvt.attr("id", "importe");
                        infnvt.attr("style", "border: none; background: none; background-image: none; -webkit-box-shadow: none; box-shadow: none;");
                        infnvt.attr("readonly", "readonly");
                        infnvt.val("$ "+formatNumber(row.importe));
                        var ttlpg = $(document.createElement("input"));
                        ttlpg.attr("type", "text");
                        ttlpg.attr("size", "10");
                        ttlpg.attr("name", "total_pagar[]");
                        ttlpg.attr("id", "total_p");
                        ttlpg.attr("style", "border: none; background: none; background-image: none; -webkit-box-shadow: none; box-shadow: none;");
                        ttlpg.attr("readonly", "readonly");
                        ttlpg.val("$ "+formatNumber(calcular_pretotal_pagar(row.salario, row.dias_lab)));
                        var bnco = $(document.createElement("input"));
                        bnco.attr("type", "text");
                        bnco.attr("size", "10");
                        bnco.attr("name", "banco_depositos[]");
                        bnco.attr("id", "banco");
                        bnco.val("$ 0.00");
                        var efctv = $(document.createElement("input"));
                        efctv.attr("type", "text");
                        efctv.attr("size", "10");
                        efctv.attr("name", "efectivo_depositos[]");
                        efctv.attr("id", "efectivo");
                        efctv.val("$ 0.00");
                        var tr = $(document.createElement("tr")).attr("id","tr"+row.empleado_id);
                        tr.append($(document.createElement("td")).attr("class", "form_input").append(pdid).append(nmbr));  
                        tr.append($(document.createElement("td")).attr("class", "form_input").append(slrio).append(emplid));
                        tr.append($(document.createElement("td")).attr("class", "form_input").append(dslabrds));
                        tr.append($(document.createElement("td")).attr("class", "form_input").append(bn));
                        tr.append($(document.createElement("td")).attr("class", "form_input").append(cmsn));
                        tr.append($(document.createElement("td")).attr("class", "form_input").append(infnvt));
                        tr.append($(document.createElement("td")).attr("class", "form_input").attr("id", "total_pagar").append(ttlpg));
                        tr.append($(document.createElement("td")).attr("class", "form_input").append(bnco));
                        tr.append($(document.createElement("td")).attr("class", "form_input").append(efctv));
                        $("#nomina_table").append(tr);
                    });
                } else {
                    $("#error").children().remove();
                    $("#prest_table").hide();
                    $("#deduc_table").hide();
                    $("#error").append("<center><h3>No se encontraron prenominas registradas. Resultados: 0</h3></center>");
                    $("#error").show(1000);
                }   
            }
        );
    }
    function obtener_bono_por_periodo_tienda(fechai, fechaf, espacio_id, empleado_id){  
        $.post("<?php echo base_url(); ?>index.php/nomina/ajax_pet_nomina/get_bono_by_periodo_tienda",
        { fecha_inicial: fechai, fecha_final: fechaf, espacio_f_id: espacio_id , empleado_id: empleado_id},
            function (data){
                $("#nomina_table").find("tr:#tr"+empleado_id).find("#bono").val("$ "+formatNumber(data));
                var t_ant = parseFloat($("#nomina_table").find("tr:#tr"+empleado_id).find("#total_p").val().replace("$ ","").replace(",",""));
                var total =  t_ant+parseFloat(data);
                $("#nomina_table").find("tr:#tr"+empleado_id).find("#total_p").val("$ "+formatNumber(total));
            }         
        );   
    }
    function obtener_pago_total_comision(espacio_id, empleado_id, tipo_comision, puesto_id, fecha_i, fecha_f){
        $.post("<?php echo base_url(); ?>index.php/nomina/ajax_pet_nomina/get_pago_comision_by_tipo",
        { fecha_inicial: fecha_i, fecha_final: fecha_f, espacio_f_id: espacio_id , empleado_id: empleado_id, tipo_c: tipo_comision, puesto_id: puesto_id},
            function (data){
                  $("#nomina_table").find("tr:#tr"+empleado_id).find("#comision").val("$ "+formatNumber(data));
                  /**Calcular el total a pagar**/
                  var comision = parseFloat(data);
                  var t_ant = parseFloat($("#nomina_table").find("tr:#tr"+empleado_id).find("#total_p").val().replace("$ ","").replace(",",""));
                  var infonavit = parseFloat($("#nomina_table").find("tr:#tr"+empleado_id).find("#importe").val().replace("$ ","").replace(",",""));
                  var total =  t_ant+comision-infonavit;
                  $("#nomina_table").find("tr:#tr"+empleado_id).find("#total_p").val("$ "+formatNumber(total));
                  /**Validar campos Banco y Efectivo**/
                  validar_banco_efectivo(empleado_id, total);
            } 
        );    
    }
    function validar_banco_efectivo(row_id, total){
        $("#nomina_table").find("tr:#tr"+row_id).find("#banco").blur(function(){
            var banco = parseFloat($("#nomina_table").find("tr:#tr"+row_id).find("#banco").val().replace("$ ","").replace(",",""));
            $("#nomina_table").find("tr:#tr"+row_id).find("#banco").val("$ "+formatNumber(banco));
            if(banco > total){
                alert("La cantidad de deposito a banco es incorrecta");
                $("#nomina_table").find("tr:#tr"+row_id).find("#banco").focus();
            } else {  
                var efectivo = parseFloat($("#nomina_table").find("tr:#tr"+row_id).find("#efectivo").val().replace("$ ","").replace(",",""));
                var suma = banco+efectivo;
                if(suma != total){
                    alert("El deposito en efectivo y banco debe ser igual al total a pagar");
                } 
            }
        });
        $("#nomina_table").find("tr:#tr"+row_id).find("#efectivo").blur(function(){
            var efectivo = parseFloat($("#nomina_table").find("tr:#tr"+row_id).find("#efectivo").val().replace("$ ","").replace(",",""));
            $("#nomina_table").find("tr:#tr"+row_id).find("#efectivo").val("$ "+formatNumber(efectivo));
            if(efectivo > total){
                alert("La cantidad de deposito en efectivo es incorrecta");
                $("#nomina_table").find("tr:#tr"+row_id).find("#efectivo").focus();
            } else {
                var banco = parseFloat($("#nomina_table").find("tr:#tr"+row_id).find("#banco").val().replace("$ ","").replace(",",""));
                var suma = banco+efectivo;
                if(suma != total){
                    alert("El deposito en efectivo y banco debe ser igual al total a pagar");
                } 
            }
        });
    }
    function calcular_pretotal_pagar(salario_diario, dias_laborados){
        var total = 0;
        total = salario_diario*dias_laborados;
        return total;
    }
    function add_rows_prest_table(){
        $.post("<?php echo base_url(); ?>index.php/nomina/ajax_pet_nomina/get_prestaciones_ajax",
            function(data){
                if(data != "false"){
                    $("#prest_table").show(1000);
                    var tr1 = $(document.createElement("tr"));"<?php echo base_url(); ?>index.php/nomina/ajax_pet_nomina/get_datos_prenomina_by_periodo"
                        tr1.append($(document.createElement("th")).attr("colspan","2").attr("style", "width: 163px;").append("Prestaciones"));
                        $("#prest_table").append(tr1);
                    return $.map(eval(data), function(row) {
                        var tg = $(document.createElement("input"));
                        tg.attr("type", "text");
                        tg.attr("size", "30");
                        tg.attr("name", "prestaciones[]");
                        tg.attr("style", "border: none; background: none; background-image: none; -webkit-box-shadow: none; box-shadow: none;");
                        tg.attr("readonly", "readonly");
                        tg.val(row.tag);
                        var chc = $(document.createElement("input"));
                        chc.attr("type", "checkbox");
                        chc.attr("name", "prest_id[]");
                        chc.attr("value", row.id);
                        var tr = $(document.createElement("tr"));
                        tr.append($(document.createElement("td")).attr("class", "form_input").append(chc));  
                        tr.append($(document.createElement("td")).attr("class", "form_input").append(tg));
                        $("#prest_table").append(tr);
                        chc.change(function() {
                           var tam_table = parseInt($("#nomina_table").attr("width"));
                           if(chc.is(":checked")){ 
                                tam_table += 5;
                                var th = $(document.createElement("th")).attr("id", "prestacion"+chc.val()).attr("style", "width: 70px;").append(tg.val());
                                $("#nomina_table").find("tr:first").find("#total_pagar").before(th);
                                for(x = 0; x < $("#nomina_table tr").length-1; x++){
                                    var cntp = $(document.createElement("input"));
                                    cntp.attr("type", "text");
                                    cntp.attr("size", "10");
                                    cntp.attr("name", "cantidad_p[]");
                                    cntp.val("$ 0.00");
                                    var id_tr = $("#nomina_table").find("input[name='empleado_id[]']").eq(x).val();
                                    var td = $(document.createElement("td")).attr("class", "form_input").attr("id", "prestacion"+chc.val()).append(cntp);
                                    $("#nomina_table").find("#tr"+id_tr).find("#total_pagar").before(td);
                                }
                                var prest_ant;
                                for(x = 0; x < $("#nomina_table tr").length-1; x++){
                                    $("#nomina_table").find("#prestacion"+chc.val()).find("input[name='cantidad_p[]']").eq(x).blur(function(){ 
                                        $(this).val("$ "+formatNumber($(this).val().replace("$ ","").replace(",",""))); 
                                    });
                                    $("#nomina_table").find("#prestacion"+chc.val()).find("input[name='cantidad_p[]']").eq(x).focus(function(){
                                        /**Obtengo el valor anterior antes de que se modifique**/
                                        prest_ant = parseFloat($(this).val().replace("$ ","").replace(",",""));
                                    });
                                    $("#nomina_table").find("#prestacion"+chc.val()).find("input[name='cantidad_p[]']").eq(x).change(function(){
                                        var tr_padre = $(this).parent().parent();
                                        tr_padre = tr_padre.attr("id");
                                        tr_padre = parseInt(tr_padre.replace("tr",""));
                                        var cant;
                                        if(parseFloat($(this).val().replace("$ ","")) == 0)
                                            /**Si el valor actual es 0 debe de restar la cantidad anterior**/
                                            cant = -prest_ant;
                                        else
                                            /**Se obtiene la diferencia para sumar o restar solo la cantidad aumentada o disminuida para la misma prestacion*/
                                            cant = parseFloat($(this).val().replace("$ ","").replace(",","")) - prest_ant;
                                        var total = recalcular_total_pagar(cant, tr_padre, 1);
                                        alert(total);
                                        $("#nomina_table").find("tr:#tr"+tr_padre).find("#total_p").val("$ "+formatNumber(total));
                                    }); 
                                }   
                           } else {
                               tam_table -= 5;
                               for(x = 0; x < $("#nomina_table tr").length-1; x++){
                                   var tr_padre = $("#nomina_table").find("#prestacion"+chc.val()).find("input[name='cantidad_p[]']").eq(x).parent().parent();
                                   tr_padre = tr_padre.attr("id");
                                   tr_padre = parseInt(tr_padre.replace("tr",""));
                                   var prest_val = $("#nomina_table").find("#prestacion"+chc.val()).find("input[name='cantidad_p[]']").eq(x).val().replace("$ ","").replace(",","");
                                   var total = $("#nomina_table").find("#tr"+tr_padre).find("#total_p").val().replace("$ ","").replace(",","");
                                   var total_nw = parseFloat(total) - parseFloat(prest_val);
                                   $("#nomina_table").find("#tr"+tr_padre).find("#total_p").val("$ "+formatNumber(total_nw));
                               }
                               $("#nomina_table").find("#prestacion"+chc.val()).remove();   
                           }
                           $("#nomina_table").attr("width", tam_table+"%");
                        });
                    }); 
                }
            }
        );
    }
    function add_rows_deduc_table(){
        $.post("<?php echo base_url(); ?>index.php/nomina/ajax_pet_nomina/get_deducciones_ajax",
            function(data){
                if(data != "false"){
                    $("#deduc_table").show(1000);
                    var tr1 = $(document.createElement("tr"));
                        tr1.append($(document.createElement("th")).attr("colspan","2").attr("style", "width: 163px;").append("Deducciones"));
                        $("#deduc_table").append(tr1);
                    return $.map(eval(data), function(row) {
                        var tg = $(document.createElement("input"));
                        tg.attr("type", "text");
                        tg.attr("size", "30");
                        tg.attr("name", "deducciones[]");
                        tg.attr("style", "border: none; background: none; background-image: none; -webkit-box-shadow: none; box-shadow: none;");
                        tg.attr("readonly", "readonly");
                        tg.val(row.tag);
                        var chc = $(document.createElement("input"));
                        chc.attr("type", "checkbox");
                        chc.attr("name", "deduc_id[]");
                        chc.attr("value", row.id);
                        var tr = $(document.createElement("tr"));
                        tr.append($(document.createElement("td")).attr("class", "form_input").append(chc));  
                        tr.append($(document.createElement("td")).attr("class", "form_input").append(tg));
                        $("#deduc_table").append(tr);
                        chc.change(function() {
                           var tam_table = parseInt($("#nomina_table").attr("width")); 
                           if(chc.is(":checked")){ 
                                tam_table += 5;
                                var th = $(document.createElement("th")).attr("id", "deduccion"+chc.val()).attr("style", "width: 70px;").append(tg.val());
                                $("#nomina_table").find("tr:first").find("#total_pagar").before(th);
                                for(x = 0; x < $("#nomina_table tr").length-1; x++){
                                    var cntd = $(document.createElement("input"));
                                    cntd.attr("type", "text");
                                    cntd.attr("size", "10");
                                    cntd.attr("name", "cantidad_d[]");
                                    cntd.val("$ 0.00");
                                    var id_tr = $("#nomina_table").find("input[name='empleado_id[]']").eq(x).val();
                                    var td = $(document.createElement("td")).attr("class", "form_input").attr("id", "deduccion"+chc.val()).append(cntd);
                                    $("#nomina_table").find("#tr"+id_tr).find("#total_pagar").before(td);
                                }
                                var deduc_ant;
                                for(x = 0; x < $("#nomina_table tr").length-1; x++){
                                    $("#nomina_table").find("#deduccion"+chc.val()).find("input[name='cantidad_d[]']").eq(x).blur(function(){
                                        $(this).val("$ "+formatNumber($(this).val().replace("$ ", "").replace(",",""))); 
                                    });
                                    $("#nomina_table").find("#deduccion"+chc.val()).find("input[name='cantidad_d[]']").eq(x).focus(function(){
                                        /**Obtengo el valor anterior antes de que se modifique**/
                                        deduc_ant = parseFloat($(this).val().replace("$ ","").replace(",",""));
                                    });
                                    $("#nomina_table").find("#deduccion"+chc.val()).find("input[name='cantidad_d[]']").eq(x).change(function(){
                                        var tr_padre = $(this).parent().parent();
                                        tr_padre = tr_padre.attr("id");
                                        tr_padre = parseInt(tr_padre.replace("tr",""));
                                        /**Se obtiene la diferencia para sumar o restar solo la cantidad aumentada o disminuida para la misma deduccion*/
                                        var cant = parseFloat($(this).val().replace("$ ","").replace(",","")) - deduc_ant;
                                        var total = recalcular_total_pagar(cant, tr_padre, 0);
                                        if(total != "false")
                                            $("#nomina_table").find("#tr"+tr_padre).find("#total_p").val("$ "+formatNumber(total));
                                        else{
                                            alert("La cantidad de deduccion es mayor que el total a pagar, favor de corregir");
                                            $(this).focus();
                                        }  
                                    }); 
                                }   
                           } else {
                               tam_table -= 5;
                               for(x = 0; x < $("#nomina_table tr").length-1; x++){
                                   var tr_padre = $("#nomina_table").find("#deduccion"+chc.val()).find("input[name='cantidad_d[]']").eq(x).parent().parent();
                                   tr_padre = tr_padre.attr("id");
                                   tr_padre = parseInt(tr_padre.replace("tr",""));
                                   var deduc_val = $("#nomina_table").find("#deduccion"+chc.val()).find("input[name='cantidad_d[]']").eq(x).val().replace("$ ","").replace(",","");
                                   var total = $("#nomina_table").find("#tr"+tr_padre).find("#total_p").val().replace("$ ","").replace(",","");
                                   var total_nw = parseFloat(total) + parseFloat(deduc_val);
                                   $("#nomina_table").find("#tr"+tr_padre).find("#total_p").val("$ "+formatNumber(total_nw));
                               }
                               $("#nomina_table").find("#deduccion"+chc.val()).remove();
                           }
                           $("#nomina_table").attr("width", tam_table+"%");
                        });
                    }); 
                }
            }
        );
    }
    function recalcular_total_pagar(cantidad, row_id, tipo){
        var total_ant = $("#nomina_table").find("tr:#tr"+row_id).find("#total_p").val().replace("$ ", "").replace(",","");
        var total_new = 0;
        /***PRESTACION***/
        if(tipo == 1){
                total_new = parseFloat(total_ant) + cantidad;
        }
        /***DEDUCCION***/
        else{
            if(Math.abs(cantidad) <= parseFloat(total_ant))
                total_new = parseFloat(total_ant) - cantidad;
            else
                /**Si la cantidad de la deduccion es menor que la del total no se le puede restar**/
                total_new = "false";
        }    
        return total_new;
    }
    $(document).ready(function(){  
        $($.date_input.initialize);
        $("#nomina_table").hide();
        $("#prest_table").hide();
        $("#deduc_table").hide();
        $("#error").hide();
        $("#espacio_fisico_id").change(function (){ 
            $("#nomina_table").children().remove();
            $("#prest_table").children().remove();
            $("#deduc_table").children().remove();
           if($("#fecha_inicial").val() != "" && $("#fecha_final").val() != ""){
                    filtrar_datos_nomina($("#fecha_inicial").val(), $("#fecha_final").val(), $("#espacio_fisico_id").val());
           }       
        });
    });   
</script>
<h2><?php echo $title;?></h2>
<div id="validation_result" class="result_validator" align="center" width="200px"></div>
<table width="50%" class='form_table' align="center" style="margin-left: auto; margin-right: auto;">
    <tr>
        <td class="form_tag">Periodo:</td>
        <td class="form_tag">De:</td>
        <td class="form_input"><?php echo form_input($fecha_inicial) ?></td>
        <td class="form_tag">A:</td>
        <td class="form_input"><?php echo form_input($fecha_final) ?></td>
        <td class="form_tag">Tienda:</td>
        <td class="form_input" style="width: 100px;"><?php echo form_dropdown('espacio_fisico_id', $espacios, -1, "id='espacio_fisico_id'"); ?></td>
    </tr>
</table>
<br />
<div id="error"></div>
<table width="73%" class='form_table' id='nomina_table' align="center" style="margin-left: auto; margin-right: auto;">
</table>     
<br />
<table width="20%">
    <tr>
        <td>
            <table class='form_table' id='prest_table' style="margin-left: auto; margin-right: auto;">
            </table>
        </td> 
        <td>
            <table class='form_table' id='deduc_table' style="margin-left: auto; margin-right: auto;">
            </table>
        </td> 
    </tr>
</table>
<center><table width="80%" class='form_table' align="center" style="margin-left: auto; margin-right: auto;"> 
    <tr>
            <td class="form_buttons">
            <button type='button' onclick="window.location='<?php echo site_url('inicio/acceso/nomina/menu'); ?>'">Cerrar sin generar</button>
        </td>
    </tr>
</table></center>
