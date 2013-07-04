<script>
  $(document).ready(function() { 
<?php
    for($x=0;$x<count($productos);$x++){
	echo "$('#cantidad$x').change(function() { \n".
	"$('#chk$x').attr('checked', true); \n".
	"$('#cantidad$x').removeClass(\"subtotal\").addClass(\"modificado1\");".
	"}) \n";
    }
?>
		$('#form1').submit(function() {
			$('#boton_p').hide();
			$('#menu').hide();
			alert("Los detalles de la plantilla se esta procesando, favor de no interrumpir el proceso");
			$('#msg1').html("<br/><img src='<? echo base_url(); ?>images/cargando.gif' width='50px'/><br/>Cargando...");
		});
		
	});

	function sel_chk(){
    $('.chk').attr('checked', true);
  }
  function unsel_chk(){
    $('.chk').attr('checked', false);
  }

</script>
<?php
//Titulo
echo "<h2>$title</h2>";
echo "<h3>Nombre de la Plantilla: $plantilla->nombre</h3>";
echo "<div align='right' style='background-color:#ccc; display:inline-block; width:100%'><label>Seleccionar</label>";
echo '<button type="button" id="todos" onclick="javascrip:sel_chk()">Todos</button><button type="button" id="todos" onclick="javascrip:unsel_chk()">Ninguno</button</div>';
$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta.'/trans/alta_detalles_stock', $atrib) . "\n";
echo '<table id="stock" width="100%" border="1" >';
$fid_prev=0;
for($x=0;$x< count($productos)-2; $x=$x+3){
	if($fid_prev!=$productos[$x]['cfamilia_id']){
		//Imprimir el Header de Cada familia
		echo "<tr valign=\"top\"><th colspan='3'>".$productos[$x]['cfamilia_id'] ." - ". $productos[$x]['familia']. "</td></tr><tr  valign=\"top\">";
	}
	//Generar renglones normales con tres columnas
	echo "<td class=\"stock_detalle\">"; echo form_checkbox("producto$x", $productos[$x]['id'], TRUE, "class=\"chk\" id=\"chk$x\"");echo $productos[$x]['descripcion']."<br/><div style='text-align:right;'>"; echo form_input("cantidad$x", '0', 'size="12" class="subtotal" id="cantidad'.$x.'"'); echo "</div></td>";
	$fid_prev=$productos[$x]['cfamilia_id'];

	if($productos[$x]['cfamilia_id']==$productos[$x+1]['cfamilia_id']){
		echo "<td class='stock_detalle'>"; echo form_checkbox("producto".($x+1), $productos[$x+1]['id'], TRUE, "class=\"chk\" id=\"chk".($x+1)."\""); echo $productos[$x+1]['descripcion'] ."<br/><div style='text-align:right;'>"; echo form_input("cantidad".($x+1), '0', 'size="12" class="subtotal" id="cantidad'.($x+1).'"'); echo "</div></td>";
	} else {
		echo "</tr>";
		if($fid_prev!=$productos[$x+1]['cfamilia_id'])
			echo "<tr valign=\"top\"><th colspan='3'>".$productos[$x+1]['cfamilia_id'] ." - ". $productos[$x+1]['familia']. "</td></tr><tr>";
		echo "<td class='stock_detalle'>"; echo form_checkbox("producto".($x+1), $productos[$x+1]['id'], TRUE, "class=\"chk\" id=\"chk".($x+1)."\""); echo $productos[$x+1]['descripcion'] ."<br/><div style='text-align:right;'>"; echo form_input("cantidad".($x+1), '0', 'size="12" class="subtotal" id="cantidad'.($x+1).'"'); echo "</div></td>";
		$fid_prev=$productos[$x+1]['cfamilia_id'];
	}

	if($productos[$x+1]['cfamilia_id']==$productos[$x+2]['cfamilia_id']){
		echo "<td class=\"stock_detalle\">"; echo form_checkbox("producto".($x+2), $productos[$x+2]['id'], TRUE, "class=\"chk\" id=\"chk".($x+2)."\"");echo $productos[$x+2]['descripcion']."<br/><div style='text-align:right;'>"; echo form_input("cantidad".($x+2), '0', 'size="12" class="subtotal" id="cantidad'.($x+2).'"'); echo "</div></td>";
	} else {
		echo "</tr>";
		if($fid_prev!=$productos[$x+2]['cfamilia_id'])
			echo "<tr valign=\"top\"><th colspan='3'>".$productos[$x+2]['cfamilia_id'] ." - ". $productos[$x+2]['familia']. "</td></tr><tr>";
		echo "<td class=\"stock_detalle\">"; echo form_checkbox("producto".($x+2), $productos[$x+2]['id'], TRUE, "class=\"chk\" id=\"chk".($x+2)."\"");echo $productos[$x+2]['descripcion']."<br/><div style='text-align:right;'>"; echo form_input("cantidad".($x+2), '0', 'size="12" class="subtotal" id="cantidad'.($x+2).'"'); echo "</div></td>";
		$fid_prev=$productos[$x+2]['cfamilia_id'];
	}
	echo "</tr>";
}
echo "<tr><th colspan='3' class=\"form_buttons\">";
echo form_hidden('stock_id', "$plantilla->id");
echo "<button type='button' id='menu' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Salir al Men�</button>";
//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
	echo '<button type="submit" id="boton_p">Guardar Detalles ></button><span id="msg1"></span>';
}
echo '</td></tr></table>';
echo form_close();

?>
