<script type="text/javascript">
$(document).ready(function() {

	$('#estatus').change(function() {
		pag=<? echo $pag;?>;
		location.href="<?php echo base_url(); ?>index.php/contabilidad/contabilidad_c/formulario/list_general_compras/"+pag+"/estatus/"+$(this).val();
	});

	 $('#espacio_fisico_id').val('');
	  $('#espacio_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_espacios_ajax/', {
			//extraParams: {pid: function() { return $("#proveedor").val(); } },
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
		  $("#espacio_fisico_id").val(""+item.id);
	  });

	  $('#cproveedor_id').val('');
	  $('#proveedor_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_proveedores_ajax_autocomplete/', {
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
		  $("#cproveedor_id").val(""+item.id);
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

function fil_sucursal(){
	pag=<? echo $pag;?>;
	ped=$("#espacio_fisico_id").val();
	location.href="<?php echo base_url(); ?>index.php/almacen/almacen_c/formulario/alta_entrada/"+pag+"/sucursal/"+ped;
}
function fil_proveedor(){
	pag=<? echo $pag;?>;
	ped=$("#cproveedor_id").val();
	location.href="<?php echo base_url(); ?>index.php/almacen/almacen_c/formulario/alta_entrada/"+pag+"/proveedor/"+ped;
}
function fil_marca(){
	pag=<? echo $pag;?>;
	ped=$("#cmarca_id").val();
	location.href="<?php echo base_url(); ?>index.php/almacen/almacen_c/formulario/alta_entrada/"+pag+"/marcas/"+ped;
}
function fil_pedido_id(){
	pag=<? echo $pag;?>;
	ped=$("#pedido_id").val();
	location.href="<?php echo base_url(); ?>index.php/almacen/almacen_c/formulario/alta_entrada/"+pag+"/pedido_id/"+ped;
}

</script>
<?php
echo "<h2>$title</h2>";
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_compra\"><img src=\"".base_url()."images/consumo.png\" width=\"50px\" title=\"Nuevo Pedido de Compra\"></a><a href=\"".base_url()."index.php/".$ruta."/proveedores_c/".$funcion."/list_proveedor\"><img src=\"".base_url()."images/proveedor.png\" width=\"50px\" title=\"Listado de Proveedores\"></a></div>";

echo "<table ><tr>";
echo "<td align=\"center\"><b>Sucursal</b></td>";
echo "<td align=\"center\"><b>Proveedor</b></td>";
echo "<td align=\"center\"><b>Marca</b></td>";
echo "<td align=\"center\"><b>Id Pedido</b></td></tr>";


echo "<tr><td align=\"center\"><input type='hidden' name='espacio_fisico_id' id='espacio_fisico_id' value='' size=\"3\"><input id='espacio_drop' class='espacio' value='' size='20'></b><br/><button onclick='javascript:fil_sucursal()'>Filtrar</button></td>";

echo "<td align=\"center\"><b><input type='hidden' name='cproveedor_id' id='cproveedor_id' value='' size=\"3\"><input id='proveedor_drop' class='proveedor' value='' size='30'></b></br><button onclick='javascript:fil_proveedor()'>Filtrar</button></td>";

echo "<td align=\"center\"><b><input type='hidden' name='cmarca_id' id='cmarca_id' value='' size=\"3\"><input id='marca_drop' class='marca' value='' size='20'></b><br/><button onclick='javascript:fil_marca()'>Filtrar</button></td>";

echo "<td align=\"center\"><b><input id='pedido_id' class='pedido_id' value='' size='10'></b><br/><button onclick='javascript:fil_pedido_id()'>Filtrar</button></td>";

echo "</tr></table>";

echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";
if($paginacion==true)
	echo $this->pagination->create_links();
?>
<table class="listado" border="0">
	<tr>
		<th>Id Pedido</th>
		<th>Lote</th>
		<th>Fecha Alta</th>
		<th>Fecha Ent.</th>
		<th>Proveedor</th>
		<th>Marca</th>
		<th>Sucursal</th>
		<th>Monto Total</th>
		<th>Tipo</th>
		<th>Capt.</th>
		<th>Edición</th>
	</tr>
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"20px\" title=\"Impresión Orden de Compra\" border=\"0\">";
	$ok="<img src=\"".base_url()."images/bien.png\" width=\"25px\" title=\"Dar entrada al Pedido de Compra\" border=\"0\">";
	if($pr_pedidos==false){
		echo "Sin Registros";

	} else {

		foreach($pr_pedidos->all as $row) {
			$codbar="<img src=\"".base_url()."images/codbar.jpeg\" width=\"20px\" title=\"Generar Etiquetas de Códigos de Barras\" border=\"0\">";
			//Permiso de Borrado en el 2 Byte X1X
			$edit="<a href=\"".base_url()."index.php/compras/compras_c/formulario/list_compras/editar_pedido_compra/".$row->id."\" target='_blank'><img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Pedido\" border=\"0\"></a>";

			//Identificar la fecha para el semaforo
			if(strlen($row->fecha_entrega)>0){
				$fecha=explode("-", $row->fecha_entrega);
				$fecha_p=strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]);
				$fecha_entrega=date("d-m-Y", $fecha_p);
				$hoy=mktime();
				if($fecha_p<=$hoy){
					$alert="_rojo";
				} else if($fecha_p<=$hoy-(3 * 24* 60 *60) and $fecha_p>$hoy){
					$alert="_amarillo";
				} else
					$alert="_verde";
			} else
				$alert="_blanco";

			$fecha=explode("-", $row->fecha_alta);
			$fecha_p=strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]);
			$fecha_alta=date("d-m-Y", $fecha_p);

			if($row->tipo=='f')
				$tipo="Normal";
			else
				$tipo="Pendiente";

			echo "<tr background=\"$img_row\"><td align=\"center\"  class='detalle$alert'>$row->id</td><td align='center'>$row->lote_id</td><td align=\"center\">$row->fecha_alta</td><td align=\"center\" class='detalle$alert'>$fecha_entrega</td><td>$row->proveedor</td><td align=\"center\">$row->marca</td><td>$row->espacio_fisico</td><td align=\"right\">$ ".number_format($row->monto_total, 2, ".", ",")."</td><td align=\"center\">$tipo</td><td align=\"center\">$row->usuario</td><td>$edit<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/entrada_pr_pedido/".$row->id."\" >$ok</a><a href=\"".base_url()."index.php/compras/compras_reportes/rep_pedido_compra/".$row->id."\" target=\"_blank\">$photo</a><a href=\"".base_url()."index.php/compras/compras_reportes/rep_etiquetas_codigo_barras_pdf/".$row->id."\" target='_blank'>$codbar</a> </td></tr>";

		}
	}
	echo "</table></center>";
	if($paginacion==true)
		echo $this->pagination->create_links();
	?>