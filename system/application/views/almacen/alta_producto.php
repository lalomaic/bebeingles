<style>
    fieldset{
        width: 920px;
        margin: auto;
        margin-bottom: 10px;
    }
</style>
<?php
//Construir Marcas de Productos
$select1[0]="Elija";
if($marca_productos!=false){
	foreach($marca_productos->all as $row){
		$y=$row->id;
		$select1[$y]=$row->tag;
	}
}
//Construir Familia de Productos
$selectf[0]="Elija";
if($familias!=false){
	foreach($familias->all as $row){
		$y=$row->id;
		$selectf[$y]=$row->tag;
	}
}

//Construir Subfamilia de Productos
$select2[0]="Elija Familia";
/*if($subfamilia_productos!=false){
	foreach($subfamilia_productos->all as $row){
		$y=$row->id;
		$select2[$y]=$row->tag;
	}
}*/


//Construir Unidad de Medida
$select3[0]="Elija";
if($unidades_medidas!=false){
	foreach($unidades_medidas->all as $row){
		$y=$row->id;
		$select3[$y]=$row->tag;
	}
}
//Construir Estatus
$select4[0]="Elija";
if(count($estatus)>0){
	foreach($estatus->all as $row){
		$y=$row->id;
		$select4[$y]=$row->tag;
	}
}

//Construir Colores de los Productos
$select6[0]="Elija";
if($colores_productos!=false){
	foreach($colores_productos->all as $row){
		$y=$row->id;
		$select6[$y]=$row->tag;
	}
}
//Inputs
$clave=array(
		'name'=>'clave',
		'size'=>'10',
		'value'=>'',
		'id'=>'clave',
);
$clave_antigua=array(
		'name'=>'clave_antigua',
		'size'=>'15',
		'value'=>'',
		'id'=>'clave_antigua',
);
$descripcion=array(
		'name'=>'descripcion',
		'size'=>'40',
		'value'=>'',
		'id'=>'descripcion',
);
$porcentaje_ganancia=array(
		'name'=>'porcentaje_ganancia',
		'size'=>'19',
		'value'=>'',
		'id'=>'presentacion',
);
$num_max=array(
		'name'=>'num_max',
		'size'=>'5',
		'value'=>'',
		'id'=>'num_max',
);
$numero_min=array(
		'name'=>'numero_min',
		'size'=>'5',
		'value'=>'',
		'id'=>'numero_min',
);

$tasa_impuesto=array(
		'name'=>'tasa_impuesto',
		'size'=>'5',
		'value'=>'16',
		'id'=>'tasa_impuesto',
);
$precio1=array(
		'name'=>'precio1',
		'size'=>'10',
		'value'=>'',
		'id'=>'precio1',
);
$precio2=array(
		'name'=>'precio2',
		'size'=>'10',
		'value'=>'',
		'id'=>'precio2',
);
$precio3=array(
		'name'=>'precio3',
		'size'=>'10',
		'value'=>'',
		'id'=>'precio3',
);
$precio_compra=array(
		'name'=>'precio_compra',
		'size'=>'10',
		'value'=>'0',
		'id'=>'precio_compra',
);
$codigo_barras=array(
		'name'=>'codigo_barras',
		'size'=>'15',
		'value'=>'',
		'id'=>'codigo_barras',
);
$observaciones=array(
		'name'=>'observaciones',
		'cols'=>'60',
		'rows'=>'2',
		'value'=>'',
);
if($producto!=false){
	//$presentacion['value']=$producto->presentacion;
	$comision_venta['value']=$producto->comision_venta;
	$clave['value']=$producto->clave;
	$descripcion['value']=$producto->descripcion;
	$precio1['value']=$producto->precio1;
	$precio2['value']=$producto->precio2;
	$precio3['value']=$producto->precio3;
	$precio4['value']=$producto->precio4;
	$precio5['value']=$producto->precio5;
	$vida_media['value']=$producto->vida_media;
	$tasa_impuesto['value']=$producto->tasa_impuesto;
	$subfamilia=$producto->csubfamilia_id;
	$familia=$producto->cfamilia_id;
	$marca=$producto->cmarca_producto_id;
	$color=$producto->ccolor_id;
	$unidad_medida=$producto->cunidad_medida_id;
	$codigo_barras=$producto->codigo_barras;
	$clave_antigua=$producto->clave_antigua;
} else {
	$subfamilia=0;
	$familia=0;
	$marca=0;
	$color=1;
	$unidad_medida=0;
}

//Titulo
echo "<h2>$title</h2>";
echo "<div style='width:920px;margin:auto' align=\"right\">";
echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_productos\"  ><img src=\"".base_url()."images/icons/icons_03.png\" width=\"30px\" title=\"Listado de Productos\">Listado de Productos</a>";
echo "<a href=\"".base_url()."index.php/almacen/almacen_reportes/formulario/rep_productos\"><img src=\"".base_url()."images/adobereader.png\" width=\"30px\" title=\"Reporte de Productos\">Reporte de Productos</a></div>";

$this->load->view('js_funciones_generales');
?>
<script>
  $(document).ready(function() {
	$('#clave').blur(function(){
	get_ajax($(this).val());
	});
	$('#familias').change(function(){
		$("#csubfamilia_id").html("");
		get_select_ajax_subfamilias('csubfamilia_id', $(this).val());
	});
	$('#descripcion').keyup(function(){
		get_ajax($(this).val());
	});
	$('#familias').change(function(){
            get_subfamilias($(this).val());
	});
	//Cargar subfamilias si una falimia esta seleccionada
        if($('#familias').val()>0){
            get_subfamilias($('#familias').val());
        };
});
  

function get_ajax(valor, obj){
   if(valor.length > 4) {
     $.post("<? echo base_url(); ?>index.php/ajax_pet/producto_descripcion",{ enviarvalor: valor, obj: obj },
     function(data){
       $('#validation_result').html(data);
     });
   }
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
function add_cod(element){
    var linea = $(element).attr("linea");
    $("#codigo_barra"+linea).attr('disabled', 'disabled');
    $("#gen_cod_bar"+linea).val(true);
    $(element).hide();
    $("#editCodeBar"+linea).show();
};
function edit_cod(element){
    var linea = $(element).attr("linea");
    $("#codigo_barra"+linea).attr('disabled', '');
    $("#gen_cod_bar"+linea).val(false);
    $("#addCodeBar"+linea).show();
    $(element).hide();
};
</script>
<?php

//Load validacion
$this->load->view('validation_view');
//Abrir Formulario
$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta.'/trans/alta_producto', $atrib) . "\n";
echo form_fieldset('<b>Datos del Producto</b>') . "\n";
//Mensajes de validacion
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';
echo "<input type='hidden' id='subfamilia_id' value='$subfamilia'/>";
echo "<table width=\"90%\" class='form_table' border='0'>";
$img_row="".base_url()."images/table_row.png";
//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"cfamilia_id\">Familia:</label><br/>"; 
echo form_dropdown('cfamilia_id', $selectf, $familia, "id='familias'");
echo "</td><td class='form_tag'><label for=\"subfamilia_productos\">SubFamilia:</label><br/><span id='subfamilia'>"; 
echo form_dropdown('csubfamilia_id', $select2, $subfamilia, "id='subfamilia_productos'");
echo "</span></td><td class='form_tag'><label for=\"marca_productos\">Marca:</label><br/>"; 
echo form_dropdown('cmarca_producto_id', $select1, $marca, "id='marca_productos'");
echo "</td><td class='form_tag'></td><td class='form_tag'><label for=\"ccolor_id\">Color:</label><br/>";
echo form_dropdown('ccolor_id', $select6, $color, "id='color'"); 
echo "</td></tr>";


echo "<tr><td class='form_tag' colspan='2'><label for=\"descripcion\">Descripcion:</label><br/>"; echo form_input($descripcion); echo "</td><td class='form_tag'><label for=\"precio1\">Precio Menudeo:</label><br/>"; echo form_input($precio1); echo "</td><td class='form_tag'><label for=\"precio2\">Precio Medio Mayoreo:</label><br/>"; echo form_input($precio2); echo "</td><td class='form_tag'><label for=\"precio3\">Precio Mayoreo:</label><br/>"; echo form_input($precio3); echo "</td></tr>";


echo "<tr><td class='form_tag'><label for=\"tasa_impuesto\">Tasa de Impuesto:</label><br/>"; echo form_input($tasa_impuesto); echo "%</td><td class='form_tag' colspan='3'><label for=\"observaciones\">Observaciones:</label><br/>"; echo form_textarea($observaciones); echo "</td><td class='form_tag'><label for=\"precio_compra\">Precio Compra:</label><br/>"; echo form_input($precio_compra); echo "</td></tr>";

//Detalle de tallas
echo "<tr><td class='form_tag' colspan='5'><div id='tallas'><h2>Ingrese las tallas del concepto</h2>";
echo "<table><tr><th>Etiqueta/Talla</th><th>Codigo Barras</th><th>Generar Codigo</th></tr>";
for($x=1;$x<=10;$x++){
	if($x==1)
		$uni="Unica";
	else
		$uni="";
	echo "<tr>
                <td align='right'> 
                    $x <input type='text' name='talla$x' id='talla$x' value='$uni' size='15'> &nbsp;&nbsp;
                </td>
                <td>
                    <input type='text' name='codigo_barra$x' id='codigo_barra$x' value='' size='15'>
                </td>
                <td align='center'>
                    <div>    
                    <img src='".base_url()."images/add_cod_bar.png' 
                        height='20px' 
                        style='cursor:pointer;'
                        title='Generar Codigo'
                        id='addCodeBar$x'
                        onclick='add_cod(this)'
                        linea='$x'>
                    <img src='".base_url()."images/boleta.png' 
                        height='20px' 
                        style='cursor:pointer; display:none;'
                        title='Editar Codigo'
                        id='editCodeBar$x'                        
                        onclick='edit_cod(this)'
                        linea='$x'>
                    </div>
                    <input type='hidden' name='gen_cod_bar$x' id='gen_cod_bar$x' value='false'>
                </td>
            </tr>";
}
echo "</table>";
echo "</div></td></tr>";
//Cerrar el Formulario
echo "<tr><td colspan='10' class=\"form_buttons\">";
echo form_reset('form1', 'Limpiar');
echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Menú</button>";
echo form_close();

//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
	echo '<button type="submit">Guardar Registro</button>';
}
echo '</td></tr></table>';
echo form_fieldset_close();

?>
