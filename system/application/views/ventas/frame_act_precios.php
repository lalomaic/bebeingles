<?php
function fecha_imp($date){
	if($date!='0000-00-00'){
		$fecha=substr($date, 0, 10);
		$hora=substr($date, 11, strlen($date));
		$new = explode("-",$fecha);
		$a=array ($new[2], $new[1], $new[0]);
		return $n_date=implode("-", $a);
	} else {
		return "Sin fecha";
	}
}
$url_form=base_url()."index.php/".$ruta."/trans/act_precios_venta";
$incremento=array(
		'name'=>'incremento',
		'id'=>'incremento',
		'size'=>'4',
		'value'=>'',
		'maxlength'=>'6',
);
$tipo_incremento[1]="$";
$tipo_incremento[2]="%";
echo "<h3>$subtitle Resultados: ". count($coleccion)."<h3>";
echo "<div align='left' style='background-color:#ccc; color:#000;display:inline-block; width:20%;height:25px;'>";
echo '<button type="button" id="todos" onclick="javascrip:sel_chk()">Todos</button><button type="button" id="todos" onclick="javascrip:unsel_chk()"> Ninguno</button></div>';

echo "<div align='left' style='background-color:#ccc; color:#000; display:inline-block; width:47%; height:25px;'>";
echo '<select id="global" name="global"><option value="global">Afectación Global</option></select></div>';

echo "<div align='right' style='background-color:#ccc; color:#000; display:inline-block; width:33%;height:25px;'>";
echo "<label class='tag_act' for=\"incremento\">Incremento Global: </label>"; echo form_input($incremento); echo "\n"; echo form_dropdown('tipo_incremento', $tipo_incremento, "0", 'id="tipo"'); echo "\n"; echo "<button type=\"button\" onclick='javascript:activar()'>Aplicar</button></div>";
$img_row="".base_url()."images/table_row.png";
?>
<link
	href="<?php echo base_url();?>css/default.css" rel="stylesheet"
	type="text/css">
<style>
.subtotal {
	text-align: right;
	background: transparent;
}

.modificado {
	text-align: right;
	background: #F7E624;
}

.modificado1 {
	text-align: right;
	background: #2537f7;
}
</style>
<script
	src="<?php echo base_url();?>css/jquery.js"></script>
<script
	src="<?php echo base_url();?>css/jquery.form.js"></script>
<? $this->load->view('ventas/js_frame_act_precios'); ?>
<div id="subform_detalle">
	<table width="100%" id="act_tabla">
		<tr bgcolor="silver">
		<!----<td width="20" rowspan="2" align="center" valign="middle"></td>
		-->	<td width="20" rowspan="2" align="center" valign="middle"></td>
			<td rowspan="2" width="400" align="center" valign="middle">Clave -
				Producto - Presentacion</td>
			<td rowspan="2" align="center" valign="middle">Precio <br/>Especial
			</td>
                        <td rowspan="2" align="center" valign="middle">Descontinuar<br/>Producto</td>
			<td colspan="3"><div align="center">Ultimos precios de compra</div></td>
			<td rowspan="2" align="center" valign="middle">Precio de Venta</td>
<!--			<td rowspan="2" align="center" valign="middle">Precio2</td>-->
			<td rowspan="2" align="center" valign="middle">Ultimo precio <br/>de venta</td>
		</tr>
		<tr bgcolor="silver">
			<td align="center">Compra 1</td>
			<td align="center">Compra 2</td>
			<td align="center">Compra 3</td>
		</tr>
		<?php
		$r=0;
		foreach($coleccion as $row){
			if(isset($row['precio_compra1'])==0){
				$row['precio_compra1']=0;
			}
			if(isset($row['fecha1'])== ""){
				$row['fecha1']="----";
			}
			if(isset($row['precio_compra2'])==0){
				$row['precio_compra2']=0;
			}
			if(isset($row['fecha2'])==""){
				$row['fecha2']="----";
			}
			if(isset($row['precio_compra3'])==0){
				$row['precio_compra3']=0;
			}
			if(isset($row['fecha3'])==""){
				$row['fecha3']="----";
			}
			echo "<tr background=\"$img_row\">
 <form id=\"fdetail$r\" method=\"post\" name=\"fdetail$r\" action=\"$url_form/$r\">
 <button type=\"submit\" id=\"b$r\" style=\"display:none;\">Guardar Registro</button><div id=\"content$r\">";  echo form_hidden("cproducto_id$r", "$row[cproducto_id]");  
 
 echo "<input type='hidden' value='parcial' name='cambio_global$r' id='cambio_global$r'></div></td>".
   "<td class='detalle'><input type=\"checkbox\" name=\"chk$r\" value='1' id=\"chk$r\" class=\"chk\"></td>".
   "<td class='detalle'>$row[clave] - $row[descripcion] - $row[presentacion]</td>".
   "<td class='detalle' align='center'><input type=\"checkbox\""; 
    if($row['oferta']==1)
    echo "checked='checked' ";
    echo "name=\"bar$r\" value='1' id=\"bar$r\" class=\"bar\"></td>".
        
   "<td class='detalle' align='center'><input type=\"checkbox\"";
      if($row['status']==0)
     echo "checked='checked' ";
     echo "name=\"des$r\" value='1' id=\"des$r\" class=\"des\"></td>".
         
         
         
"<td class='detalle' align=\"center\">". fecha_imp($row['fecha1']). " <br/> ". number_format($row['precio_compra1'], 2, ".",","). "</td>".
"<td class='detalle' align=\"center\">". fecha_imp($row['fecha2']). "<br/> ". number_format($row['precio_compra2'], 2, ".",","). "</td>".
"<td class='detalle' align=\"center\">". fecha_imp($row['fecha3']). "<br/> ". number_format($row['precio_compra3'], 2, ".",","). "</td>".
"<td class='detalle' align=\"right\"><input type=\"text\" name=\"precio1$r\" size=\"8\" value=\"$row[precio1]\" id=\"precio1$r\" class='subtotal'><input type=\"hidden\" name=\"precio$r\" size=\"8\" value=\"$row[precio1]\" id=\"precio$r\" class='subtotal'><input type=\"hidden\" name=\"precio2$r\" size=\"8\" value=\"$row[precio2]\" id=\"precio2$r\" class='subtotal'></td>".
"<td class='detalle' align=\"right\" ><input type=\"text\" name=\"precio3$r\" size=\"8\" value=\"$row[precio3]\" id=\"precio3$r\" disabled=\"disabled\"   class='subtotal' ></td></form>".
"</tr> \n";
			$r++;
		}

		echo "</table>";
		echo "<div align='right'>";
		if(substr(decbin($permisos), 0, 1)==1){
			echo '<button type="button" onclick="javascript:send_detalle()">Guardar Nuevos Precios</button>';
		}
		echo "</div>";
		?>
		</div>
