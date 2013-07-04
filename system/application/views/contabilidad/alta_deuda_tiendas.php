<?php
echo "<h2>$title</h2>";
//Abrir Formulario
$atrib=array('name'=>'form1', 'id'=>"form1");
echo form_open($ruta.'/trans/alta_deuda_tienda', $atrib) . "\n";
echo form_fieldset('<b>Alta de Deuda entre Tiendas</b>') . "\n";
echo "<table class='form_table'>";
$img_row="".base_url()."images/table_row.png";

//Campos del Formulario
echo "<tr><td class='form_tag' colspan='4'>Elegir Tienda que adeuda<br/>";  echo form_dropdown('espacio_fisico_debe_id', $tiendas, 0,"id='id'"); echo "</td>";
echo '</tr></table>';
echo form_fieldset_close();
echo form_close();
?>
<div id="subform_detalle">
	<table class="row_detail" id="header" border='1'>
		<tr>
			<th class='detalle_header'>Concepto</th>
			<th class='detalle_header'>Fecha</th>
			<th class='detalle_header'>Monto</th>
			<th class='detalle_header'>Tienda</th>
		</tr>
		<?php
		//Imprimir valores actuales
		$r=0;
		echo "</table>";
		?>
		</div>
		<?php
		//Link al listado
		echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_compras\"><img src=\"".base_url()."images/consumo.png\" width=\"50px\" title=\"Listado de Pedidos de Compra\"></a><a href=\"".base_url()."index.php/".$ruta."/proveedores_c/".$funcion."/alta_proveedor\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Nuevo Usuario\"></a></div>";
?>