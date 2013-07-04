<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>        
        <title>Deshabilitar empleado</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <?= $this->assetlibpro->output('css'); ?>
        <?= $this->assetlibpro->output('js'); ?>
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
            $(document).ready(function(){
            	$("#boton_x").hide();
               $(".results").hide();
                $($.date_input.initialize);
                $(".money_input").each(function(){
                    $(this).val("$ "+formatNumber($(this).val()));
                });
                $(".money_input").blur(function () {
                    if(!isNaN(parseFloat($(this).val())))
                        $(this).val("$ "+formatNumber($(this).val()));         
                    else
                        $(this).val("$ 0.00");
                });
                $(".money_input").focus(function () {
                    $(this).val($(this).val().replace(/([^0-9\.\-])/g,''))
                });
                $("#form_baja").submit(function(e){
                    e.preventDefault();
                    $.post("<?php echo base_url(); ?>index.php/nomina/empleado_c/borrar_usuario", $(this).serialize(),
                        function(data){
                            if(data != "false"){
                               var count = 0;
                                $.map(eval(data), function(row) {
                                    if(count > 0)
                                        i = "_i";
                                    else
                                        i = "";
                                    $("#aguin"+i).val("$ "+formatNumber(row.aguna));
                                    $("#vacas"+i).val("$ "+formatNumber(row.vacas));
                                    $("#p_vaca"+i).val("$ "+formatNumber(row.pvaca));
                                    $("#total"+i).val("$ "+formatNumber(row.total));
                                     $("#total2"+i).val("$ "+formatNumber(row.total));
                                     $("#debe"+i).val("$ - "+row.debe1);
                                     $("#c"+i).val(" "+(row.concepto));
                                     $("#concepto"+i).val(" "+(row.concepto));
                                      $("#aguin1"+i).val("$ "+formatNumber(row.aguna));
                                    $("#vacas1"+i).val("$ "+formatNumber(row.vacas));
                                    $("#p_vaca1"+i).val("$ "+formatNumber(row.pvaca));
                                    $("#total1"+i).val(row.tor);
                                    
                                     $("#debe1"+i).val(row.debe1);
                                    count++;
                                });
                                $("#boton_aplica").fadeOut(250, function(){$(".results").fadeIn(250)});
                               
                                $("#boton_x").fadeIn(250, function(){$(".results").fadeIn(250)});
                                $('#fecha_baja1').val($('#fecha_baja').val());
                                //alert("Empleado deshabilitado");
                            } else {
                                alert("la cago");                                
                            }
                        }
                    );
                    return false;
                });
            });
        </script>
        <style type="text/css">
            .form_tag {
                color: blue;
                border-bottom: 1px solid #ccc;
                margin-bottom: 5px;            
            }
            .left_td {
                padding-right: 25px;
            }
            .info_input {
                width: 190px;
                text-align: right;
                border: none;
            }
            .div_top_td {
                border-bottom:1px solid #ddd; 
                padding-bottom: 8px;
            }
            .div_bot_td {
                padding-top: 8px;
            }
            .date_input {
                width: 75px;
                text-align: right;                
            }
        </style>
    </head>    
    <body>
 
     <form id="form_baja">
            <table style="margin-left: auto; margin-right: auto; width: 425px; margin-top: 25px;">
                <tr class="form_tag">
                    <td colspan="2">
                        Empleado:
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="div_top_td">
                        <?php echo $empleado->nombre." ".$empleado->apaterno." ".$empleado->amaterno;?>
                    </td>
                </tr>
                <tr class="form_tag">
                    <td class="left_td div_bot_td">
                        Fecha Ingreso:
                    </td>
                    <td class="div_bot_td">
                        Fecha Ingreso IMSS:
                    </td>
                </tr>
                <tr>
                    <td class="left_td">
                        <input type="text" class="info_input" id="fecha_ingreso" name="fecha_ingreso" value="<?php echo date_format(date_create_from_format("Y-m-d", $empleado->fecha_ingreso), "d m Y");?>" readonly/>
                    </td>
                    <td>
                        <input type="text" class="info_input" id="fecha_ingreso_imss" name="fecha_ingreso_imss" value="<?php echo date_format(date_create_from_format("Y-m-d", $empleado->fecha_ingreso_imss), "d m Y");?>" readonly/>
                    </td>
                </tr>
                <tr class="form_tag">
                    <td class="left_td">
                        Salario Diario:
                    </td>
                    <td>
                        Salario IMSS:
                    </td>
                </tr>
                <tr>
                    <td class="left_td div_top_td">
                        <input type="text" class="info_input money_input" id="salario" name="salario" value="<?php echo $empleado->salario;?>" readonly/>
                    </td>
                    <td class="div_top_td">
                        <input type="text" class="info_input money_input" id="salario_imss" name="salario_imss" value="<?php echo $empleado->salario_imss;?>" readonly/>
                    </td>
                </tr>
                <tr class="form_tag">
                    <td class="left_td div_bot_td">
                        Fecha Baja:
                    </td>
                </tr>
                <tr>
                    <td class="left_td div_top_td" style="text-align: right;">
                        <input type="text" class="date_input" id="fecha_baja" name="fecha_baja" readonly/>
                    </td><td class="div_top_td"></td>
                </tr>
                <tr id="boton_aplica">
                    <td colspan="2" class="div_bot_td" style="text-align: right;">                        
                        <input type="hidden" name="id" value="<?php echo $empleado->id;?>"/>
                        <input type="submit" value="Aplicar Baja"/>
                    </td>
            
                </tr>
              
                <tr class="form_tag results">
                    <td class="left_td div_bot_td">
                        Aguinaldo Real:
                    </td>
                    <td class="div_bot_td">
                        Aguinaldo IMSS:
                    </td>
                </tr>
                <tr class="results">
                    <td class="left_td">
                        <input type="text" class="info_input money_input" id="aguin" readonly/>
                    </td>
                    <td>
                        <input type="text" class="info_input money_input" id="aguin_i" readonly/>
                    </td>
                </tr>
                <tr class="form_tag results">
                    <td class="left_td">
                        Vacaciones Real:
                    </td>
                    <td>
                        Vacaciones IMSS:
                    </td>
                </tr>
                <tr class="results">
                    <td class="left_td">
                        <input type="text" class="info_input money_input" id="vacas" readonly/>
                    </td>
                    <td>
                        <input type="text" class="info_input money_input" id="vacas_i" readonly/>
                    </td>
                </tr>
                <tr class="form_tag results">
                    <td class="left_td">
                        Prima vacacional Real:
                    </td>
                    <td>
                        Prima vacacional IMSS:
                    </td>
                </tr>
                <tr class="results">
                    <td class="left_td div_top_td">
                        <input type="text" class="info_input money_input" id="p_vaca" readonly/>
                    </td>
                    <td class="div_top_td">
                        <input type="text" class="info_input money_input" id="p_vaca_i" readonly/>
                    </td>
                </tr>
                <tr class="form_tag results">
                    <td class="left_td div_bot_td">
                        Total Real:
                    </td>
                    <td class="div_bot_td">
                        Total IMSS:
                    </td>
                </tr>                               
                <tr class="results">
                    <td class="left_td">
                        <input type="text" class="info_input money_input" id="total" readonly/>
                    </td>
                    <td>
                        <input type="text" class="info_input money_input" id="total_i" readonly/>
                    </td>
                </tr>
			     <tr class="form_tag results">
                    <td class="left_td div_bot_td">
                        Adeudo de :</td>
                   
                </tr>                               
                <tr class="results">
                    <td class="left_td">
                       <input type="text" class="info_input money_input" id="debe" readonly/> 
                    </td>
                  
                </tr>                
                
                
            </table>  
     
        </form>
        
        
<?php 
				echo form_open($ruta . '/nomina_reportes/rep_finiquito_pdf', array('name' => 'form1', 'id' => 'form1'));
			?>    
			 <table style="margin-left: auto; margin-right: auto; width: 425px; margin-top: 25px;">
                <tr>
                    <td class="left_td">
                   <input type="hidden" id="empleado" name="empleado" value="<?php echo $empleado->nombre." ".$empleado->apaterno." ".$empleado->amaterno;?>"/>
                     <input type="hidden" id="e_id" name="e_id" value="<?php echo $empleado->id;?>"/>
                   
                        <input type="hidden" class="info_input" id="fecha_ingreso1" name="fecha_ingreso1" value="<?php echo date_format(date_create_from_format("Y-m-d", $empleado->fecha_ingreso), "d m Y");?>" readonly/>
                    </td>
                    <td>
                        <input type="hidden" class="info_input" id="fecha_ingreso_imss1" name="fecha_ingreso_imss1" value="<?php echo date_format(date_create_from_format("Y-m-d", $empleado->fecha_ingreso_imss), "d m Y");?>" readonly/>
                    </td>
                </tr>
           
                <tr>
                    <td class="left_td div_top_td">
                        <input type="hidden" class="info_input money_input" id="salario1" name="salario1" value="<?php echo $empleado->salario;?>" readonly/>
                    </td>
                    <td class="div_top_td">
                        <input type="hidden"  id="salario_imss" name="salario_imss" value="<?php echo $empleado->salario_imss;?>" readonly/>
                    </td>
                </tr>
                 
                <tr id="boton_x">
                <td><button type="">Generar Finiquito</button></td>
                </tr>
             
                <tr class="results">
                    <td class="left_td">
                        <input type="hidden" class="info_input money_input" id="aguin1" name="aguin1" readonly/>
                    </td>
                    <td>
                        <input type="hidden" class="info_input money_input" id="aguin1_i" name="aguin1_i"  readonly/>
                    </td>
                </tr>
        
                <tr class="results">
                    <td class="left_td">
                        <input type="hidden" class="info_input money_input" id="vacas1" name="vacas1" readonly/>
                    </td>
                    <td>
                        <input type="hidden" class="info_input money_input" id="vacas1_i" name="vacas1_i" readonly/>
                    </td>
                </tr>
           
                <tr class="results">
                    <td class="left_td div_top_td">
                        <input type="hidden" class="info_input money_input" id="p_vaca1" name="p_vaca1" readonly/>
                    </td>
                    <td class="div_top_td">
                        <input type="hidden" class="info_input money_input" id="p_vaca1_i" name="p_vaca1_i" readonly/>
                    </td>
                </tr>
                    <tr>
                    <td class="left_td div_top_td" style="text-align: right;">
                        <input type="hidden" class="date_input" id="fecha_baja1" name="fecha_baja1" readonly/>
                    </td><td class="div_top_td"></td>
                </tr>
                                    
                <tr class="results">
                    <td class="left_td">
                        <input type="hidden" id="total1" name="total1" readonly/>
                        <input type="hidden" id="total2" name="total2" readonly/>
                        <input type="hidden" id="total2_i" name="total2_i" readonly/>
                    </td>
                    <td>
                        <input type="hidden" class="info_input money_input" id="total1_i" name="total1_i" readonly/>
                    </td>
                </tr>
			                                
                <tr class="results">
                    <td class="left_td">
                       <input type="hidden"  id="debe1" name="debe1" readonly/> 
                       <input type="hidden" class="info_input money_input" id="concepto" readonly/> 
                       <input type="hidden" id="puesto" name="puesto" value="<?php echo $empleado->puesto_tag;?>" /> 
                      <input type="hidden" id="razon_social" name="razon_social" value="<?php echo $espacio->razon_social;?>" /> 
                      
                      <input type="hidden" id="calle" name="calle" value="<?php echo $espacio->calle;?>" /> 
                      <input type="hidden" id="num_int" name="num_int" value="<?php echo $espacio->numero_interior;?>" /> 
                      <input type="hidden" id="num_ext" name="num_ext" value="<?php echo $espacio->numero_exterior;?>" /> 
                     <input type="hidden" id="colonia" name="colonia" value="<?php echo $espacio->colonia;?>" />
                       <input type="hidden" id="localidad" name="localidad" value="<?php echo $espacio->localidad;?>" />
                      <input type="hidden" id="espacio_tag" name="espacio_tag" value="<?php echo $espacio->tag;?>" /> 
                    </td>
                  
                </tr>                
                
            </table>      
        
    </body>
</html>