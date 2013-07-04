<?php
$url_form=base_url()."index.php/".$ruta."/trans/ajustar_arqueo_detalles_previo";
$this->load->view('supervision/js_editar_ajuste_inventario');
//Titulo
echo "<h2>$title para $arqueo->espacio </h2>";
echo "<table width=\"800\" class='form_table'>";
echo "<tr><td class='form_tag' style='font-size:12pt;'>
        <label for=\"empresa\" >Sucursal:</label></td><td class='form_tag' style='font-size:12pt;'> $arqueo->espacio</td></tr>";
echo "<tr><td class='form_tag' style='font-size:12pt;'>
        <label for=\"empresa\">Fecha y Hora de Levantamiento:</label></td>
        <td class='form_tag' style='font-size:12pt;'> $arqueo->fecha_final - $arqueo->hora_final</td></tr></table><br/><br/>";
?>
<div id="subform_detalle">
	<table width="90%" class="row_detail_pred" id="header">
		<tr>
			<th class='detalle_header'>#</th>
			<th class='detalle_header'>Estatus</th>
			<th class='detalle_header' width="600">
                            CLAVE - Producto - Presentacion U. Med
                        </th>
			<th class='detalle_header'>Cantidad Real</th>
			<th class='detalle_header'>Cantidad Sistema</th>
			<th class='detalle_header'>Diferencia</th>
			<th class='detalle_header'>% Error</th>
			<th class='detalle_header' colspan="2">Acción</th>
		</tr>
		<?php
		//Imprimir valores actuales
		$r=0;
		$class="visible";
		if($arqueo_detalles!=false){
			foreach($arqueo_detalles as $row){

				if($row->cantidad_sistema<0 and $row->cantidad_real==0){
					$row->porciento_error=(-1)*$row->porciento_error;
					$row->diferencia=(-1)*$row->diferencia;
					$row->ctipo_ajuste_detalle_id=3;
				} else if($row->cantidad_sistema<0 and $row->cantidad_real>0){
					$row->porciento_error=(-1)*$row->porciento_error;
					$row->diferencia=(-1)*($row->cantidad_real-$row->cantidad_sistema);
					$row->ctipo_ajuste_detalle_id=3;
				}

				if($row->ctipo_ajuste_detalle_id>0){
					if($row->ctipo_ajuste_detalle_id==1){
						$tipo_a=1;
						$error="_verde";
						$option="<option value='1'>Ninguna</option>";
					}
					else if($row->ctipo_ajuste_detalle_id==2){
						$tipo_a=2;
						$error="_rojo";
						$option="<option value='2'>Ajuste por entrada con saldo</option>
                                                    <option value='3'>Ajuste por entrada sin saldo</option>";
					}
					else if($row->ctipo_ajuste_detalle_id==3){
						$tipo_a=3;
						$error="_amarillo";
						$option="<option value='2'>Ajuste por entrada con saldo</option>
                                                    <option value='3'>Ajuste por entrada sin saldo</option>";
					}
					else if($row->ctipo_ajuste_detalle_id==4){
						$tipo_a=4;
						$error="_rojo";
						$option="<option value='4'>Ajuste por salida con cobro</option><option value='5'>Ajuste por salida sin cobro</option>";
					}
					else if($row->ctipo_ajuste_detalle_id==5){
						$tipo_a=5;
						$error="_amarillo";
						$option="<option value='5'>Ajuste por salida sin cobro</option><option value='4'>Ajuste por salida con cobro</option>";
					}
				} else {
					if($row->ctipo_ajuste_detalle_id==1 or $row->porciento_error=='0.00'){
						$tipo_a=1;
						$error="_verde";
						$option="<option value='1'>Ninguna</option>";
					} else if ($row->ctipo_ajuste_detalle_id==3 or $row->porciento_error > '0.00' and $row->porciento_error <= '2.00' ){
						$tipo_a=2;
						$error="_amarillo";
						$option="<option value='3'>Ajuste por entrada sin saldo</option><option value='2'>Ajuste por entrada con saldo</option>";
					} else if ($row->ctipo_ajuste_detalle_id==2 or $row->porciento_error>'2.00'){
						$tipo_a=3;
						$error="_rojo";
						$option="<option value='3'>Ajuste por entrada sin saldo</option><option value='2'>Ajuste por entrada con saldo</option>";

					} else if ($row->ctipo_ajuste_detalle_id==4 or $row->porciento_error<'0.00' and $row->porciento_error>'-2.00'){
						$tipo_a=4;
						$error="_amarillo";
						$option="<option value='4'>Ajuste por salida con cobro</option><option value='5'>Ajuste por salida sin cobro</option>";

					} else if ($row->ctipo_ajuste_detalle_id==5 or $row->porciento_error<='-2.00'){
						$tipo_a=5;
						$error="_rojo";
						$option="<option value='4'>Ajuste por salida con cobro</option><option value='5'>Ajuste por salida sin cobro</option>";
					}
				}

				echo "<tr id=\"row$r\"  class=\"$class\" ><td  class='detalle$error' align=\"center\">". ($r+1) ."</td>
                                        <td  class='detalle$error' width=\"50\" align=\"center\">
                                        $row->llave
                                        <form id=\"fdetail$r\" method=\"post\" name=\"fdetail$r\" action=\"$url_form/$r\"><div id=\"content$r\">"; 
                                        echo form_hidden("id$r", "$row->id");  
                                        echo form_hidden("arqueo_id$r", "$row->arqueo_id");
                                        echo form_hidden("cproducto_id$r", "$row->cproducto_id"); 
                                        echo form_hidden("cproductos_numero_id$r", "$row->cproductos_numero_id"); echo "</div></td>".
                                         	
                                                                           
						"<td class='detalle'> $row->producto # $row->numero</td>".

						"<td class='detalle'><input type=\"text\" name=\"cantidad_real$r\" size=\"7\" value='".number_format($row->cantidad_real,2)."' id=\"cantidad_real$r\" class=\"cantidad\"></td>".

						"<td class='detalle'><input type=\"text\" name=\"cantidad_sistema$r\" size=\"7\" value='".number_format($row->cantidad_sistema,2)."' id=\"cantidad_sistema$r\" class=\"cantidad\"  readonly='readonly'></td>".

						"<td class='detalle'><input type=\"text\" name=\"diferencia$r\" size=\"7\" value='" .number_format($row->diferencia,2). "' id=\"diferencia$r\" class=\"cantidad\"></td>".

						"<td class='detalle'><input type=\"text\" name=\"porciento_error$r\" size=\"4\" value='" .number_format($row->porciento_error, 0). "' id=\"porciento_error$r\" class=\"cantidad\"></td>".

						"<td class='detalle'><select name='ctipo_ajuste_detalle_id$r' id='ctipo_ajuste_detalle_id$r'>$option";
				//echo form_dropdown("ctipo_ajuste_detalle_id$r", $tipo_ajuste, "$tipo_a", "id='ctipo_ajuste_detalle_id$r'  class='tipo'");
				echo "</select></td><td class='detalle' style='display: none'>
                                        <button type=\"submit\" id=\"b$r\">Aplicar</button></form></td>".

						"</tr> \n";
				$r+=1;
			}
		}
		?>
    <table width="900" class="row_detail_prev" id="total">
        <tr>
            <th class='detalle_header' width="700" colspan="2" align="center"><?php
            echo "<button type='button' onclick=\"window.location='".base_url()."index.php/supervision/arqueos_c/formulario/list_ajuste_inventario'\">Cerrar</button>";
            if(substr(decbin($permisos), 0, 1)==1)
                    echo '<button type="button" id="guardar" onclick="send_detalle()">Solo Guardar</button>';
            echo '<button type="button" id="exe" onclick="send_detalle_final()">Ejecutar Acciones</button><span id="msg1"></span>';
            ?> <span id="fin"></span><br>Al Guardar los detalles del Traspaso
                    verifique que se han guardado adecuadamente cada uno de ellos.</th>
        </tr>
    </table>
</div>
		<?php
		//Link al listado
		echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_transferencias\"><img src=\"".base_url()."images/consumo.png\" width=\"50px\" title=\"Listado de Pedidos de Traspaso\"></a></div>";
		?>