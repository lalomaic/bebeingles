<script type="text/javascript">
$(document).ready(function() {
	$(".modal_upload").fancybox({
		'width'				: '45%',
		'height'			: '75%',
		'autoScale'			: false,
		'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'				: 'iframe'
	});

	  $('#cmarca_id').val('0');
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

	  <?php
	  $i=1; $v=0;
 	if($datos['prod_mtr']!=false){
 		foreach($datos['prod_mtr'] as $t){
			foreach($datos['espacios_fisicos']->result() as $e){
				echo "$('#precio_local_".$i."_".$v."').change(function() { \n".
				"$('#chk_".$i."_".$v."').val(1); \n".
				"$('#precio_local_".$i."_".$v."').removeClass(\"subtotal\").addClass(\"modificado1\");".
				"}) \n";
				$v+=1;
			}
			$v=0;
			$i+=1;
 		}
 	}
 	?>
});
	function format(r) {
		return r.descripcion;
	}

	function marcar(){
		$(':checkbox').attr('checked', true);
	}
	function desmarcar(){
		$(':checkbox').attr('checked', false);
	}

</script>

<?php
echo "<h2>Alta de Actualización de Precios en Sucursales</h2>";
$atrib=array('name'=>'form1', 'id'=>"form1");
echo form_open($ruta.'/ventas_c/formulario/alta_actualizacion_sucursales/obtener_datos', $atrib) . "\n";
echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";
if(isset($producto_str)==true)
	$value=$producto_str;
else
	$value="";
echo "<tr><th><div align=\"center\"><b>Paso No. 1 Filtrado<br/>Producto:<input id='producto' name='producto' class='producto' value='$value' size='30'></b></div>";

echo "<div align=\"center\"><b>Marca: <input type='hidden' name='cmarca_id' id='cmarca_id' value='0' size=\"3\"><input id='marca_drop' class='marca' value='' size='40'></b></div></th></tr>";

echo "<tr><th colspan='4'><strong>Paso No.2 Elegir Tiendas</strong><br/><a href='javascript:marcar()'>Todos</a> - <a href='javascript:desmarcar()'>Ninguno</a></th></tr>";
echo "<tr><td colspan='4'><table width='100%'>";
$tr=false; $c=1; $y=1;

foreach($espacios->all as $row){
	$state=false; $r_eid=array();
	if(isset($datos)==true and $datos['espacios_fisicos']!=false){
		foreach($datos['espacios_fisicos']->result() as $pre_esp){
			$r_eid[]=$pre_esp->id;
			if($pre_esp->id==$row->id){
				$state=true;
			}
		}
	} else {
		$state=false;
	}
	if($c==1)
		echo "<tr>";
	echo "<td><label for=\"chk$y\">";echo form_checkbox("chk$y", $row->id, $state); echo "$row->tag:</label></td>";
	if($c==3){
		echo "</tr>";
		$c=0;
	}
	$c+=1;

	$y+=1;
}
?>
</table>
</th>
</tr>
<tr>
	<th><button type='reset'>Limpiar</button>
		<button type="submit" id="boton1">Ver Listado de Precios</button></th>
	</table>
	<?php
	echo form_close();
	//Listado

	if(isset($datos)==true and $datos['prod_mtr']!=false){
		$atrib=array('name'=>'form2', 'id'=>"form2");
		echo form_open($ruta.'/trans/alta_actualizacion_sucursales/'.count($r_eid)."/".count($datos['prod_mtr'])."/", $atrib) . "\n";
		$sub_colum="";
		if(strlen($producto_str)>0)
			echo "<h2>Filtrado por producto: '$producto_str'</h2>";

		echo "<table  class=\"listado\" border=\"1\">";
		echo "<tr><th rowspan='2'>Id</th><th rowspan='2' width='300px'>Descripción</th><th rowspan='2'>Depto.<br/>Ofertas</th><th rowspan='2'>Precio<br/>Lista</th>";
		foreach($datos['espacios_fisicos']->result() as $esp){
			echo "<th colspan='3'>$esp->tag</th>";
			$sub_colum.="<th>Desc.<br/>Gral</th><th>Prom</th><th>Precio<br/>Local</th>";

		}
		echo "</tr>";
		echo "<tr>$sub_colum</tr>";
		$i=1;
		foreach($datos['prod_mtr'] as $row){
			if(!($i%2))
				$clase="#ccc";
			else
				$clase="#fff";
			if($row['cfamilia_id']==5)
				$string=" checked='checked' ";
			else
				$string="";

			echo "<tr class='renglon' bgcolor='$clase'><td>{$row['id']}</td><td>{$row['descripcion']}</td><td align='center'><input type=\"checkbox\" name=\"bar$i\" value='1' id=\"bar$i\" class=\"bar\" $string></td><td>{$row['precio_lista']}</td>";
			foreach($r_eid as $v=>$k){
				if(isset($row['precio_espacio_'.$k])==false or $row['precio_espacio_'.$k]==0)
					$p=$row['precio_lista'];
				else
					$p=$row['precio_espacio_'.$k];

				$p=ceil($p-(($p*$row['descuento_espacio_'.$k])/100));
				echo "<td align='right'>".$row['descuento_espacio_'.$k]."%</td> <td align='right'>".$row['promocion_espacio_'.$k]."</td><td align='right'>".
						"<input type='text' id='precio_local_{$i}_".$v."' name='precio_local_{$i}_".$v."' value='$p' size='6' class='subtotal'>".
						"<input type='hidden' name='chk_{$i}_".$v."' value='0' id='chk_{$i}_".$v."'>".
						"<input type='hidden' name='espacio_fisico_{$i}_".$v."' value='$k' id='espacio_fisico_{$i}_".$v."'>".
						"<input type='hidden' name='cproducto_{$i}_".$v."' value='{$row['id']}' id='cproducto_{$i}_".$v."'></td>";
			}
			echo "</tr>";
			$i+=1;
		}
		echo "<tr><td colspan='2'><button type='submit' >Guardar Cambios</button></td></tr>";

		echo "</table>";
		echo form_close();
	}
	?>