<style>
    fieldset{
        width: 960px;
        margin: 0 auto;
        border-radius: 3px;
    }
</style>
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
        });
        
        
        
        
        $('#cmarca_id').val('');
	  $('#marca_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_marcas_ajax/', {
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
			  return format(item);
		  }
	  }).result(function(e, item) {
		  $("#cmarca_id").val(""+item.id);
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
$dias=array('','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
$fecha_inicio = array(
		'class' => 'date_input',
		'name' => 'fecha_inicio',
		'id' => 'fecha_inicio',
		'size' => '10',
		'readonly' => 'readonly'
);

$fecha_final = array(
		'class' => 'date_input',
		'name' => 'fecha_final',
		'id' => 'fecha_final',
		'size' => '10',
		'readonly' => 'readonly'
);

$this->load->view('js_funciones_generales');

$this->load->view('ventas/js_alta_promocion.php');
$url_fecha = base_url() . "index.php/" . $ruta . "/trans/alta_promociones";
echo "<h2>$title</h2>";
//Link al listado
echo "<div style='width:960px;margin:5px auto;' align=\"right\">";
echo "<a style='margin-right:5px;' href=\"".base_url()."index.php/inicio/acceso/ventas/menu\"  title=\"Regresar al Menu Ventas\"><img src=\"".base_url()."images/menu_back.jpg\" width=\"30px\"> Menu </a>";
echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_promociones\"><img src=\"".base_url()."images/factura.png\" width=\"30px\" title=\"Listado de Entradas\" > Entradas </a></div>";

echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';
$atrib = array('name' => 'form1', 'id' => "form1");
echo form_open($ruta . '/trans/alta_promociones', $atrib) . "\n";
echo form_fieldset('<b>Nueva promoción</b>') . "\n";
echo "<input type='hidden' id='subfamilia_id' value='$subfamilia_productos'/>";
echo "<table>";
echo "<tr><td class='form_tag'><label for=\"cfamilia_id\">Familia:</label><br/>"; 
echo form_dropdown('cfamilia_id', $selectf, $familias, "id='familias'");
echo "</td><td class='form_tag'><label for=\"subfamilia_productos\">SubFamilia:</label><br/><span id='subfamilia'>"; 
echo form_dropdown('csubfamilia_id', $select2, $subfamilia_productos, "id='subfamilia_productos'");

echo "</table>";

echo "<table width=\"940\" class='form_table'>";
echo "<tr><td class='form_tag'><label for=\"cmarca_id\">Marca: </label><br/>
    <input type='hidden' name='cmarca_id' id='cmarca_id' value='0' >
    <input type='text' name='marca_drop' value='' id='marca_drop' size='50'><br/>
    Producto: </label><br/>
    <input type='hidden' name='producto_id' id='producto_id' size='3' value='' />
                        <input type='hidden' name='producto_m' id='producto_m' size='3' value='' />
               <input id='producto_drop' value='' size='60' name='producto_drop'  />
                    </td>";
echo "<td class='form_tag'><label for=\"fecha\">Fecha de inicio:</label><br/>"; 
echo form_input($fecha_inicio)."<br/><label for=\"fecha\">Fecha de fin:</label><br/>";
echo form_input($fecha_final)."</td></tr></table><br/>";
//echo "<td><label for=\"fecha\">Fecha de fin:</label><br/>"; echo form_input($fecha_final). "</td></tr></table><br/>";

$conter = 0;
$total=0;
$controles=array();
echo "<h3 style=\"font-size:11px; margin-left:90px; \">Seleccione las sucursales donde sera válida la promoción</h3>";
echo "<table frame=\"box\" width=\"940\" align=\"center\" class='form_table'><tr>";
if ($espacios != false) {
	foreach ($espacios->all as $row) {
		$conter++;
		$total++;
		$controles[]=$row->id;
		echo "<td>" . form_checkbox('espacios' . $total, $row->id, FALSE) . "</td><td>$row->tag</td>";
		if ($conter == 3) {
			echo "</tr><tr>";
			$conter = 0;
		}
	}

	echo"</tr></table>";
}

echo "<br/>";

echo "<h3 style=\"font-size:11px; margin-left:90px; \">Seleccione los días y las horas en que aplicara la promoción</h3>";
echo"<table width\"940\"=><tr><td>";
echo "<table  width=\"486\" frame=\"box\"  class='form_table'><tr>";
echo "<td>Día</td><td>Hora inicio</td><td>Hora fin</td></tr>";
for($j=1;$j<8;$j++){
	$js='onClick="cambiaredo(window.document.form1.dia'.$j.','.$j.')"';
	echo "<tr><td align=\"left\" >".form_checkbox("dia$j","$j",true,$js)."$dias[$j]"."</td><td>h: ".form_dropdown('horai'.$j, $horas, "0")."min: ".form_dropdown('mini'.$j,$min,"0")."</td><td>h: ".form_dropdown('horaf'.$j, $horas, "0")."min: ".form_dropdown('minf'.$j,$min,"0")."</td></tr>";

}
echo"</table></td><td valign=TOP>";
echo"<table width=\"450\" class='form_table'><tr><td align=center colspan=2><br/><br/>Seleccione el limite y el precio de venta<font size=1> * Limite 0 significa sin limite</font></td></tr><tr><td align=right>limite_cantidad:</td><td align=right>".form_input("limite","0",'size="10"')."</td></tr>";
echo "<tr><td align=right>Porcentaje Descuento:</td><td align=right>".form_input("precio","0",'size="10"')."%</td></tr>";
echo "<tr><td valign='bottom' colspan=2 align='right'><br/><br/>Haga click para guardar cambios   ". "<button type=\"button\" onClick=\"validar('$total')\" id=\"boton4\" >Guardar</button>"."</td></tr></table>";
echo"</td></tr></table>";

$img_row = "" . base_url() . "images/table_row.png";

echo "</fieldset>";
echo form_fieldset_close();
echo form_close();

?>