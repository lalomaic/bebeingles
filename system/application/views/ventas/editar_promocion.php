<?php
$dif=md5(rand());
?>
<script>
  $(document).ready(function() {
	
	$('#familias').change(function(){
		$("#csubfamilia_id").html("");
		get_select_ajax_subfamilias('csubfamilia_id', $(this).val());
	});
	
	$('#familias').change(function(){
            get_subfamilias($(this).val());
	});
	//Cargar subfamilias si una falimia esta seleccionada
        if($('#familias').val()>0){
            get_subfamilias($('#familias').val());
        };
        
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
    
function get_subfamilias(valor){
if(valor >= 0) {
        $.post("<? echo base_url(); ?>index.php/ajax_pet/subfamilias",{ enviarvalor: valor},
        function(data){
            $('#subfamilia').html(data);
            $('#subfamilia_productos').focus();
	    if($('#subfamilia_id').val()>0){
                $('#subfamilias_productos').val($('#subfamilia_id').val());
                $('#subfamilia_id').val(0);
            };
        });
    }
};
  
  </script>

<?php

//Construir Familias
$selectf[0]="Elija";
if($familias!=false){
	foreach($familias->all as $row){
		$y=$row->id;
		$selectf[$y]=$row->tag;
	}
}

//Construir Subfamilia de Productos
$select2[0]="Elija Familia";

$horas[0]="";
for($i=0;$i<24;$i++){
	if($i<10){
		$horas[$i]='0'.$i;
	}
	else{
		$horas[$i]=$i;
	}
}
$min[0]="";
for($i=0;$i<60;$i++){
	if($i<10){
		$min[$i]='0'.$i;
	}
	else{
		$min[$i]=$i;
	}
}
$fecha=explode("-",date_format(date_create($promocion->fecha_inicio), 'd-m-Y'));
$fechao=$fecha[2]." ".$fecha[1]." ".$fecha[0];

$dias=array('','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
$fecha_inicio = array(
		'class' => 'date_input',
		'name' => 'fecha_inicio',
		'id' => 'fecha_inicio',
		'size' => '10',
		'value'=>''.$fechao,
		'readonly' => 'readonly'
);

$fecha=explode("-",date_format(date_create($promocion->fecha_final), 'd-m-Y'));
$fechao=$fecha[2]." ".$fecha[1]." ".$fecha[0];

$fecha_final = array(
		'class' => 'date_input',
		'name' => 'fecha_final',
		'id' => 'fecha_final',
		'size' => '10',
		'value'=>''.$fechao,
		'readonly' => 'readonly'
);

$this->load->view('js_funciones_generales');
$this->load->view('ventas/js_alta_promocion.php');
$url_fecha = base_url() . "index.php/" . $ruta . "/trans/editar_promocion";
echo "<h2>$title</h2>";
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';
$atrib = array('name' => 'form1', 'id' => "form1");
echo form_open($ruta . '/trans/alta_promociones/', $atrib) . "\n";
echo form_fieldset('<b>Editar promoción</b>') . "\n";
echo form_hidden('id', $promocion->id);
echo "<table width=\"950\" class='form_table'>";
echo "<input type='hidden' id='subfamilia_id' value='$subfamilia_productos'/>";
echo "<tr><td class='form_tag'><label for=\"cfamilia_id\">Familia:</label><br/>"; 
echo form_dropdown('cfamilia_id', $selectf, $promocion->cproductos_familias, "id='familias'");
echo "</td><td class='form_tag'><label for=\"subfamilia_productos\">SubFamilia:</label><br/><span id='subfamilia'>"; 
echo form_dropdown('csubfamilia_id', $select2, $promocion->cproductos_subfamilias, "id='subfamilia_productos'");



if($promocion->cmarca_id==0)
	$marca_tag->tag="TODAS";
if($promocion->cproducto_id==0)
	$producto_tag->descripcion="TODAS";
?>
<?php
echo "<tr><td class='form_tag'><label for=\"cmarca_id\">Marca: </label><br/><input type='hidden' name='cmarca_id' id='cmarca_id' value='$promocion->cmarca_id' >
    <input type='text' name='marca_drop' value='$marca_tag->tag' id='marca_drop' size='50'><br/>Producto: </label><br/>
    <input type='hidden' name='producto_id' id='producto_id' value='$promocion->cproducto_id' >
    <input type='text' name='producto_drop' value='$producto_tag->descripcion' id='producto_drop' size='50'></td>";
echo "<td ><label for=\"fecha\">Fecha de inicio:</label><br/>"; echo form_input($fecha_inicio)."<br/><label for=\"fecha\">Fecha de fin:</label><br/>"; echo form_input($fecha_final). "</td></tr></table><br/>";
$conter = 0;
$total=0;
$controles=array();
echo "<h3 style=\"font-size:11px; margin-left:190px; \">Seleccione las sucursales donde sera valida la promoción</h3>";
echo "<table frame=\"box\" width=\"950\" align=\"center\" class='form_table'><tr>";
$lugares=explode(",",$promocion->espacios_fisicos);
if ($espacios != false) {
	foreach ($espacios->all as $row) {
		$conter++;
		$total++;
		$controles[]=$row->id;
		echo "<td>" . form_checkbox('espacios' . $total, $row->id, in_array($row->id, $lugares)) . "</td><td>$row->tag</td>";
		if ($conter == 3) {
			echo "</tr><tr>";
			$conter = 0;
		}
	}

	echo"</tr></table>";
}
echo "<br/>";
echo "<h3 style=\"font-size:11px; margin-left:190px; \">Seleccione los días y las horas en que aplicara la promoción</h3>";
echo "<table width\"950\"=><tr><td>";
echo "<table  width=\"500\" frame=\"box\"  class='form_table'><tr>";
echo "<td>Día</td><td>Hora inicio</td><td>Hora fin</td></tr>";
$separadias = explode(",", $promocion->dias_horas);
for ($o = 0; $o < sizeof($separadias); $o++) {
	$separados[$o] = explode("&", $separadias[$o]);
}
for($i=0; $i<sizeof($separados); $i++){
	$days[]=$separados[$i][0];
	$horai[]=$separados[$i][1];
	$horaf[]=$separados[$i][2];
}

for($j=1;$j<8;$j++){
	$js='onClick="cambiaredo(window.document.form1.dia'.$j.','.$j.')"';
	$state=False;
	$time1=array(00,00);
	$time2=array(00,00);
	for($index=0;$index<sizeof($days);$index++){
		if($days[$index]==$j){
			$state=TRUE;
			$time1=explode(":",$horai[$index]);
			$time2=explode(":",$horaf[$index]);
		}
	}


	echo "<tr><td align=\"left\" >".form_checkbox("dia$j","$j",$state,$js)."$dias[$j]"."</td><td>h: ".form_dropdown('horai'.$j, $horas, $time1[0])."min: ".form_dropdown('mini'.$j,$min,$time1[1])."</td><td>h: ".form_dropdown('horaf'.$j, $horas, $time2[0])."min: ".form_dropdown('minf'.$j,$min,$time2[1])."</td></tr>";
	echo"<script>if(!window.document.form1.dia$j.checked){
	window.document.form1.horai$j.disabled=true;
	window.document.form1.mini$j.disabled=true;
	window.document.form1.horaf$j.disabled=true;
	window.document.form1.minf$j.disabled=true;
}</script>";
}

echo"</table></td><td valign=TOP>";
echo"<table width=\"450\" class='form_table'><tr><td align=center colspan=2>Seleccione el limite y el precio de venta<font size=1> * Limite 0 significa sin limite</font></td></tr><tr><td align=right>limite_cantidad:</td><td align=right>".form_input("limite",$promocion->limite_cantidad)."</td></tr><tr><td align=right>Porcentaje  de descuento:</td><td align=right>".form_input("precio",$promocion->precio_venta)."</td></tr><tr><td><br/></td></tr><tr><td><br/></td></tr><tr><td><br/></td></tr><tr><td><br/></td></tr><tr><td valign=BOTTOM colspan=2 align=right>Haga click para guardar cambios   ". "<button type=\"button\" onClick=\"validar('$total')\" id=\"boton4\" >Guardar</button>"."</td></tr></table>";
echo"</td></tr></table>";

$img_row = "" . base_url() . "images/table_row.png";

echo "</fieldset>";
echo form_fieldset_close();
echo form_close();

echo "<div align=\"left\"><a href=\"" . base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/list_alta_promociones\"><img src=\"" . base_url() . "images/consumo.png\" width=\"50px\" title=\"Listado de salidas por merma<\"></a></div>";

?>