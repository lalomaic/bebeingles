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
if(count($familias)>0){
	foreach($familias->all as $row){
		$y=$row->id;
		$selectf[$y]=$row->tag;
	}
}

//Construir Subfamilia de Productos
$select2[0]="Elija";
if(count($subfamilia_productos)>0){
	foreach($subfamilia_productos->all as $row){
		$y=$row->id;
		$select2[$y]=$row->tag;
	}
}


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
//Construir Materiales de los Productos
$select5[0]="Elija";
if($materiales_productos!=false){
	foreach($materiales_productos->all as $row){
		$y=$row->id;
		$select5[$y]=$row->tag;
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
//Construir Temporada de los Productos
$select7[0]="Elija";
if($temporada_productos!=false){
	foreach($temporada_productos->all as $row){
		$y=$row->id;
		$select7[$y]=$row->tag;
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
		'cols'=>'60',
		'rows'=>'2',
		'value'=>''.$producto->descripcion,
		'id'=>'descripcion',
);
$porcentaje_ganancia=array(
		'name'=>'porcentaje_ganancia',
		'size'=>'19',
		'value'=>''.$producto->porcentaje_ganancia,
		'id'=>'presentacion',
);

$tasa_impuesto=array(
		'name'=>'tasa_impuesto',
		'size'=>'5',
		'value'=>''.$producto->tasa_impuesto,
		'id'=>'tasa_impuesto',
);
$precio1=array(
		'name'=>'precio1',
		'size'=>'10',
		'value'=>''.$producto->precio1,
		'id'=>'precio1',
);
$precio2=array(
		'name'=>'precio2',
		'size'=>'10',
		'value'=>''.$producto->precio2,
		'id'=>'precio2',
);
$precio3=array(
		'name'=>'precio3',
		'size'=>'10',
		'value'=>''.$producto->precio3,
		'id'=>'precio3',
);
$codigo_barras=array(
		'name'=>'codigo_barras',
		'size'=>'15',
		'value'=>'',
		'id'=>'codigo_barras',
);
$observaciones=array(
		'name'=>'observaciones',
		'cols'=>'20',
		'rows'=>'2',
		'value'=>''.$producto->observaciones,
                'id'=>'observaciones'
);

//Titulo
echo "<h2>$title</h2>";
$this->load->view('almacen/js_editar_producto_combo');
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

});

function get_ajax(valor, obj){
   if(valor.length > 3) {
    $.post("<? echo base_url(); ?>index.php/ajax_pet/clave",{ enviarvalor: valor, obj: obj },
    function(data){
      $('#validation_result').html(data);
    });
  }
}

function editar(id){
   if(id > 0 ) {
	tag=$('#tag'+id).val();
	codigo=$('#codigo_barra'+id).val();
	$.post("<? echo base_url(); ?>index.php/ajax_pet/actualizar_talla",{ llave: id, str: tag, codigo_barra: codigo},
    function(data){
		alert("La talla ha sido actualizada")
    });
  }
}
function nuevo(id){
   if(id > 0 ) {
	tag=$('#nuevo'+id).val();
	codigo=$('#codigo_barra'+id).val();
	pid=<?=$producto->id?>;
    $.post("<? echo base_url(); ?>index.php/ajax_pet/nueva_talla",{ enviarvalor: id, str: tag, pid:pid,codigo_barra: codigo},
    function(data){
		alert("La talla ha sido dada de alta")
    });
  }
}
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
function borrar(id){
   if(id > 0 ) {
    $.post("<? echo base_url(); ?>index.php/ajax_pet/borrar_talla",{ enviarvalor: id},
    function(data){
		if(data==1){
			alert("La talla ha sido deshabilitada");
			$('#tag'+id).val("");
		} else
			alert("La talla no puede eliminarse, debido a que hay entradas o salidas activas")			
    });
  }
}
</script>
<?php
//Load validacion
$this->load->view('validation_view');


//Abrir Formulario
$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta.'/trans/act_producto', $atrib) . "\n";
echo form_fieldset('<b>Datos del Producto</b>') . "\n";
//Mensajes de validacion
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';

echo "<table width=\"80%\" class='form_table' >";
$img_row="".base_url()."images/table_row.png";


//Campos del Formulario

echo "<tr><td class='form_tag'><label for=\"cfamilia_id\">Familia:</label><br/>"; echo form_dropdown('cfamilia_id', $selectf, $producto->cfamilia_id, "id='familias'");echo "</td><td class='form_tag'><label for=\"subfamilia_productos\">SubFamilia:</label><br/><span id='subfamilia'>"; echo form_dropdown('csubfamilia_id', $select2, $producto->csubfamilia_id, "id='csubfamilia_id'");echo "</span></td><td class='form_tag'><label for=\"marca_productos\">Marca:</label><br/>"; echo form_dropdown('cmarca_producto_id', $select1, $producto->cmarca_producto_id, "id='marca_productos'");echo "</td><td class='form_tag'></td><td class='form_tag'><label for=\"ccolor_id\">Color:</label><br/>"; echo form_dropdown('ccolor_id', $select6, $producto->ccolor_id, "id='color'"); echo "</td></tr>";



echo "<tr><td class='form_tag' colspan='2'><label for=\"descripcion\">Descripcion:</label><br/>"; echo form_textarea($descripcion); echo "</td><td class='form_tag'><label for=\"precio1\">Precio Menudeo:</label><br/>"; echo form_input($precio1); echo "</td><td class='form_tag'><label for=\"precio2\">Precio Medio Mayoreo:</label><br/>"; echo form_input($precio2); echo "</td><td class='form_tag'><label for=\"precio3\">Precio Mayoreo:</label><br/>"; echo form_input($precio3); echo "</td></tr>";
echo "<tr><td class='form_tag'><label for=\"tasa_impuesto\">Tasa de Impuesto:</label><br/>"; echo form_input($tasa_impuesto); echo "%</td><td class='form_tag'><label for=\"observaciones\">Observaciones:</label><br/>"; echo form_textarea($observaciones);
echo form_hidden('id', "$producto->id"); 
//echo $producto->con_num_medios;
echo '</td><td><button type="button" onclick="javascript:enviar_producto()" id="boton_p">Guardar Cambios</button></td></tr>';

$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Deshabilitar Concepto\" border=\"0\">";
$editar="<img src=\"".base_url()."images/respaldo.png\" width=\"25px\" title=\"Guardar Talla\" border=\"0\">";

//Detalle de tallas
echo "<tr><td class='form_tag' colspan='5'><div id='tallas'><h2>Tallas del concepto</h2></td></tr></table></form>";
echo "<table><tr><th>ID</th><th>TALLA</th><th>Código Barra</th><th>Editar</th></tr>";

foreach($numeracion->all as $row){

	$delete="<a href=\"javascript:borrar($row->id) \" onclick=\"return confirm('¿Estas seguro que deseas deshabilitar la talla?');\">$trash</a>";
	$edit="<a href=\"javascript:editar($row->id)\" onclick=\"return confirm('¿Estas seguro que deseas actualizar la talla?');\">$editar</a>";
	$delete="";

	echo "<tr><td>$row->id </td><td align='right'><input type='hidden' value='$row->id' id='id$row->id'><input type='hidden' value='$row->id' id='id_numeracion'><input type='text' value='$row->numero_mm' id='tag$row->id' size='5' style='text-align:left;'></td><td><input type='text' id='codigo_barra$row->id' value='$row->codigo_barras' size='15'></td><td>$edit $delete</td></tr>";
	
}
for($x=1;$x<1;$x++){
	$edit="<a href=\"javascript:nuevo($x)\" onclick=\"return confirm('¿Estas seguro que deseas dar de alta la talla?');\">$editar</a>";

	echo "<tr><td >Adicional $x</td><td align='right'><input type='text' id='nuevo$x' value='' size='3'></td><td><input type='text' name='codigo_barra$x' id='codigo_barra$x' value='' size='15'></td><td>$edit </td></tr>";
}
echo "</table>";
//Cerrar el Formulario
echo "<table width=\"80%\" class='form_table'><tr><td colspan='10' class=\"form_buttons\">";

echo '</td></tr></table>';
echo form_fieldset_close();


?>
<div id="subform_detalle" >
	<table class="row_detail" id="header" border='1' width="50%">
    <tr><td  colspan='5'><div id='tallas'><h2 style="color: white;">Seleccion De Productos Combos</h2></td></tr>
            <tr>
			<th class='detalle_header'>Estatus</th>
                        <th class='detalle_header'>Codigo de Barras</th>
			<th class='detalle_header'>Descripción</th>
			<th>Semejanza</th>
			
		</tr>
		<?php
                $i=0;
		//Imprimir valores actuales
                foreach($entra->all as $entra ){
             
       //     echo "<tr valign='top'><td class='detalle' width=\"50\" align='center'><a href=\"javascript:enviar_inventario($entra->relacion_id,$entra->relacion_numeracion)\">Borrar</a><input type='hidden' value='0' name='id' id='id'><div id=\"content$entra->relacion_id$entra->relacion_numeracion\"><img src='".base_url()."images/ok.png' width='20px'/></div></td>".
             echo "<tr id=\"row$i\"  ><td class='detalle' width=\"50\" align='center'>
             <a href=\"javascript:enviar_inventario($i)\">Borrar</a>
             <input type='hidden' value='$entra->id' name='id$i' id='id$i'>
             <div id=\"content$i\"><img src='".base_url()."images/ok.png' width='20px'/></div></td>".
								"<td class='detalle' width=\"10\">".
        "<input type=\"text\" class='cod_bar' size=\"15\" value=\"$entra->cod\"  id=\"cod_bar\" disabled=\"disabled\">".
                                        "</td>".
"<td class='detalle' width=\"300\">
<input type='hidden' name='producto_m$i' id='producto_m$i' value='$entra->relacion_numeracion' size=\"3\">
<input type='hidden' name='combo_numeracion' id='combo_numeracion' value='$entra->numeracion_combo' size=\"3\">
<input type='hidden' name='id_combo' id='id_combo' value='$entra->id' size=\"3\">
<input type='hidden' name='id_producto$i' id='id_producto$i' value='$entra->relacion_id' size=\"3\">";
echo "<input type='text' id='prod' class='prod$entra->cproductos_id' value='$entra->producto_nombre -# $entra->numero_mm' size='80' disabled='disabled'></span></td>".
"<td><input type='text' id='semejanza$i' name='semejanza$i' value='$entra->semejante' size=8 ></td>".				
					"</tr> \n";                                   
                                
             $i++;
                     }
$x = $i; $class="";                    
		for($r=$x;$r<$rows;$r++){
			if($r<10)
				$class="visible";
			else if ($r>15)
				$class="invisible";

			echo "<tr id=\"row$r\"  class=\"$class\" valign='top'><td class='detalle' width=\"50\" align='center'>
			<input type='hidden' value='0' name='id$r' id='id$r'><div id=\"content$r\"></div></td>".
                                
                                        "<td class='detalle' width=\"10\">".
                                            "<input type=\"text\" class='cod_bar' name=\"$r\" size=\"15\"  id=\"cod_bar$r\">".
                                        "</td>".

					"<td class='detalle' width=\"400\"><input type='hidden' name='producto_id$r' id='producto_id$r' value='0' size=\"3\"><input type='hidden' name='producto_nid$r' id='producto_nid$r' value='0' size=\"3\">";echo "<input type='text' id='prod$r' class='prod' value='' size='80'></span></td>".
"<td><input type='text' id='semejanza$r' name='semejanza$r' value='0' size=8></td>".
					"</tr> \n";
		}
		?>
                		<tr>
			<th class='detalle_pie' colspan="7"><?php
			echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Cancelar</button>";
			if(substr(decbin($permisos), 0, 1)==1){
				echo '<button type="button" onclick="javascript:send_detalle()" id="boton_p">Guardar Cambios</button><button type="button" onclick="javascript:guardar_semejanza()" id="boton_p">Guardar Semejanza</button><div id="msg1"></div>';
			}
			?> <span id="fin"></span><br>Al Guardar los detalles del Pedido
				verifique que se han guardado adecuadamente cada uno de ellos.</th>
		</tr>
        </table></div>
                
