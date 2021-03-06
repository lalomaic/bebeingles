<script type="text/javascript">  
    var tabla_list = new Array();    
    tabla_list = ["Asis", "Desc", "Falta", "Inci", "Perm", "Vac"];
    var list = new Array();
    var dias = 0;
    function filtrar_datos_prenomina(espacio_id){
        dias = calcular_periodo_dias(1);
        if(dias > 0){
            var semana = new Array();
            var dia_inicial = calcular_periodo_dias(0);
            semana = crear_array_dias(dias, semana, dia_inicial);
            var tam_table = parseInt($("#empleados_table").attr("width"));
            if(semana.length-1 <= 2){
                tam_table = 70;
                tam_table -= (semana.length-1)*12;
            } else if(semana.length-1 == 3){
                tam_table = 70;
                tam_table -= (semana.length-1)*6;  
            } else if(semana.length-1 >= 4 && semana.length-1 < 5){
                tam_table = 70;
                tam_table -= (semana.length-1)*4;  
            } else if(semana.length-1 == 5){
                tam_table = 70;
                tam_table -= (semana.length-1)*1;     
            } else if(semana.length-1 > 7){
                tam_table = 70;
                tam_table += (semana.length-1)*1;
            } else
                tam_table = 70;
            $("#empleados_table").attr("width", tam_table+"%");
            $.post("<?php echo base_url(); ?>index.php/nomina/ajax_pet_nomina/get_datos_empleados_by_tienda", { id: espacio_id},
                function(data){
                    if(data != "false"){
                        $("#error").hide(1000);
                        $("#generar_button").show();
                        $("#empleados_table").show(1000);
                        var tr1 = $(document.createElement("tr"));
                            tr1.append($(document.createElement("th")).attr("style", "width: 330px;").append("Empleados"));
                            for(x in semana)
                                tr1.append($(document.createElement("th")).attr("style", "width: 100px;").append(semana[x]));
                            tr1.append($(document.createElement("th")).attr("style", "width: 20px;").append("Horas Extra"));
                            $("#empleados_table").append(tr1);
                        return $.map(eval(data), function(row) {
                            var emplid = $(document.createElement("input"));
                            emplid.attr("type", "hidden");
                            emplid.attr("name", "empleado_id[]");
                            emplid.val(row.id);
                            var nmbr = $(document.createElement("input"));
                            nmbr.attr("type", "text");
                            nmbr.attr("size", "45");
                            nmbr.attr("name", "nombres[]");
                            nmbr.attr("style", "border: none; background: none; background-image: none; -webkit-box-shadow: none; box-shadow: none;");
                            nmbr.attr("readonly", "readonly");
                            nmbr.val(row.nombre_completo);
                            for(x in semana){
                                list[x] = $(document.createElement("select"));
                                list[x].attr("name", "lista_asist["+row.id+"]["+x+"]");
                                list[x].attr("style", "width: 50px;");
                                for(i in tabla_list)
                                    list[x].append($(document.createElement("option")).val(i).text(tabla_list[i]));
                            }
                            var hrsx = $(document.createElement("input"));
                            hrsx.attr("type", "text");
                            hrsx.attr("size", "2");
                            hrsx.attr("name", "horas_extras[]");
                            hrsx.val("0");
                            var ds = $(document.createElement("input"));
                            ds.attr("type", "hidden");
                            ds.attr("name", "dias_semana");
                            ds.val(semana.length-1);
                            var tr = $(document.createElement("tr"));
                            tr.append($(document.createElement("td")).attr("class", "form_input").append(emplid).append(nmbr));
                            for(y in semana)
                                tr.append($(document.createElement("td")).attr("class", "form_input").attr("style", "width: 100px; text-align: center;").append(list[y]));
                            tr.append($(document.createElement("td")).attr("class", "form_input").append(hrsx).append(ds));
                            $("#empleados_table").append(tr);
                        });
                    } else {
                        $("#generar_button").hide();
                        $("#error").children().remove();
                        $("#error").append("<center><h3>No se encontraron empleados registrados. Resultados: 0</h3></center>");
                        $("#error").show(1000);
                    }   
                }
            );
        } else {
            $("#generar_button").hide();
            $("#error").children().remove();
            $("#error").append("<center><h3>Error producido por las fechas, favor de corregir. Resultados: 0</h3></center>");
            $("#error").show(1000)
        }
    } 
    function calcular_periodo_dias(ind){
        var num = 0;
        var f1_m = new Array();
        var f2_m = new Array();
        f1_m = $("#fecha_inicial").val().split(" ");
        var fecha1 = new Date(f1_m[2]+","+f1_m[1]+","+f1_m[0]);
        f2_m = $("#fecha_final").val().split(" ");
        var fecha2 = new Date(f2_m[2]+","+f2_m[1]+","+f2_m[0]);
        if(ind == 1){
            var dias_trans = fecha2.getTime() - fecha1.getTime();
            num = Math.round(dias_trans/(1000 * 60 * 60 * 24))+1;
        } else 
                num = fecha1.getDay(); 
        return num;
    } 
    function crear_array_dias(ds, s, di){
        for(d = 1; d <= ds; d++){
            if(di == 1)
                 s[d] = "Lunes";
            else if (di == 2)
                 s[d] = "Martes";
            else if (di == 3) 
                s[d] = "Miércoles";
            else if (di == 4) 
                s[d] = "Jueves";
            else if (di == 5) 
                s[d] = "Viernes";
            else if (di == 6) 
                s[d] = "Sábado";   
			else if (di == 7) 
                s[d] = "Domingo";
            else{
                di = 0;
                d--;
                ds--;
            }    
            di++;
        }
        return s;
    }
    $(document).ready(function(){  
        $("#empleados_table").hide();
        $("#generar_button").hide();
        $("#error").hide();
        $($.date_input.initialize);
        filtrar_datos_prenomina($("#espacio_fisico_id").val());          
        $("#espacio_fisico_id").change(function (){ 
           $("#empleados_table").children().remove();
           if($("#fecha_inicial").val() != "" && $("#fecha_final").val() != "")
                filtrar_datos_prenomina($("#espacio_fisico_id").val());          
        });
        $("#form1").submit(function(e) {  
            e.preventDefault();
	    $.post("<?php echo base_url(); ?>index.php/nomina/prenomina_c/generar_prenomina", $(this).serialize(),
                function(data){
                    if(data == "true"){
                        alert("Se ha generado la prenomina con exito.");
//                         window.location = '<?php echo site_url('nomina/nomina_c/formulario/generar_nomina_salarios'); ?>';
                        window.location = '<?php echo site_url('nomina/prenomina_c/formulario/list_prenominas'); ?>';
                    } else {
                        alert("Hubo un error al tratar de generar la prenomina, intente de nuevo");
                        window.location = '<?php echo $ruta?>/generar_prenomina';
                    }
                });
            return false;
        });
    });
</script>