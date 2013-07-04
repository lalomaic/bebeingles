<?php
//Productos
$productos_list[0]="";
if($productos != false){
	foreach($productos->all as $row){
		$y=$row->id;
		$productos_list[$y]=$row->clave." - ".$row->descripcion ." - ".$row->presentacion." - ".$row->unidad_medida;
	}
}
//Titulo
echo "<h2>$title</h2>";
//Abrir Formulario
?>
<div id="subform_detalle">
	<table width="600" class="row_detail_pred" id="header">
		<tr>
			<th class='detalle_header'>#</th>
			<th class='detalle_header' width="600">CLAVE - Producto -
				Presentacion U. Med</th>
			<th class='detalle_header'>Existencia FÃ­sica</th>
			<th class='detalle_header'>Stock</th>
			<th class='detalle_header'>Differencia</th>
		</tr>
		<?php
		function esPar($num){
			return !($num%2);
		}
		//Imprimir valores actuales
		$r=0;
		$class="visible";
		if($inventario!=false){
			foreach($inventario as $row){
				$stock_cantidad=0;
				if(esPar($r))
					$color="_gris";
				else
					$color="_blanco";
				foreach($stock_detalles->all as $search){
					//Buscar el item en la plantilla del stock
					if($row['id']==$search->cproducto_id){
						$stock_cantidad=$search->cantidad;
						break;
						//unset($stock_detalles["$k"]);
					}
				}
				$diferencia=$row['existencias']-$stock_cantidad;
				$limite_amarillo=$stock_cantidad*1.20;
				if($row['existencias']>$limite_amarillo)
					$color="_verde";
				else if($row['existencias']<=$limite_amarillo and $row['existencias']>=$stock_cantidad){
					$color="_amarillo";
				} else {
					$color="_rojo";
				}
				if($row['existencias']!=0 or $stock_cantidad!=0){
					echo "<tr id=\"row$r\"  class=\"$class\" ><td  class='detalle$color' align=\"center\">". ($r+1) ."</td>".
							"<td class='detall$color'  width=\"30\">{$row['descripcion']}</td>".
							"<td class='detalle$color' align=\"right\" width=\"30\">". number_format($row['existencias'], 2, ".",",") ."</td>".
							"<td class='detalle$color' align=\"right\" width=\"30\">". number_format($stock_cantidad, 2, ".",",") ."</td>".
							"<td class='detalle$color' align=\"right\" width=\"30\">". number_format($diferencia, 2, ".",",") ."</td>".
							"</tr> \n";
					$r+=1;
				}
			}
		}
		?>
	</table>
</div>
<?php
//Link al listado
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_transferencias\"><img src=\"".base_url()."images/consumo.png\" width=\"50px\" title=\"Listado de Pedidos de Traspaso\"></a></div>";
?>