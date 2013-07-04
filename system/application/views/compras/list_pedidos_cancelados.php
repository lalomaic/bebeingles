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
	location.href="<?php echo base_url(); ?>index.php/compras/compras_c/formulario/list_pedidos_cancelados/"+pag+"/sucursal/"+ped;
}
function fil_proveedor(){
	pag=<? echo $pag;?>;
	ped=$("#cproveedor_id").val();
	location.href="<?php echo base_url(); ?>index.php/compras/compras_c/formulario/list_pedidos_cancelados/"+pag+"/proveedor/"+ped;
}
function fil_marca(){
	pag=<? echo $pag;?>;
	ped=$("#cmarca_id").val();
	location.href="<?php echo base_url(); ?>index.php/compras/compras_c/formulario/list_pedidos_cancelados/"+pag+"/marcas/"+ped;
}
function fil_pedido_id(){
	pag=<? echo $pag;?>;
	ped=$("#pedido_id").val();
	location.href="<?php echo base_url(); ?>index.php/compras/compras_c/formulario/list_pedidos_cancelados/"+pag+"/pedido_id/"+ped;
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

echo "<td align=\"center\"><b><input id='pedido_id' class='pedido_id' value='' size='10'></b><br/><button onclick='javascript:fil_pedido_id()'>Filtrar</button></td><tr>";
echo "</tr></table>";
echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";
if($paginacion==true)
	echo $this->pagination->create_links();
?>
<table class="listado" border="0" width="90%">
	<tr>
		<th>Id Pedido</th>
		<th>Fecha Alta</th>
		<th>Fecha Entrega</th>
		<th>Proveedor</th>
		<th>Marca</th>
		<th>Sucursal</th>
		<th>Monto Total</th>
		<th>Tipo</th>
		<th>Estatus</th>
		<th>Capturista</th>
		<th width="25">Edición</th>
	</tr>
	<?php
	//Function for formating the date tags YYYY/MM/DD to DD/MM/YYYY
	function fecha_imp($date){
		if($date!='0000-00-00' && $date!=''){
			$fecha=substr($date, 0, 10);
			$hora=substr($date, 11, strlen($date));
			$new = explode("-",$fecha);
			$a=array ($new[2], $new[1], $new[0]);
			if(strlen($hora)>2){
				return $n_date=implode("-", $a);
			} else {
				return $n_date=implode("-", $a);
			}
		} else {
			return "Sin fecha";
		}
	}
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$recargar="<img src=\"".base_url()."images/recargar.png\" width=\"25px\" title=\"Habilitar Registro\" border=\"0\">";
	?>
	<script>
  $(document).ready(function() {
    $('.aut').fadeOut(001);
});
</script>
	<?php
	if($pr_pedidos==false){
		echo "Sin Registros";
	} else {
		foreach($pr_pedidos->all as $row) {
			//Permiso de Borrado en el 2 Byte X1X
			if(substr(decbin($permisos), 1, 1)==1 ){
				$rec="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/habilitar_pedido/".$row->id."\" target='_blank' onclick=\"return confirm('¿Estas seguro que deseas habilitar el pedido de compra id = $row->id ?');\">$recargar</a>";
			}

			if($row->tipo=='f')
				$tipo="Normal";
			else
				$tipo="Pendiente";

			echo "<tr background=\"$img_row\"><td align=\"center\">$row->id</td><td align=\"center\">".fecha_imp($row->fecha_alta)."</td><td align=\"center\">".fecha_imp($row->fecha_entrega)."</td><td>$row->proveedor</td><td>$row->marca</td><td>$row->espacio_fisico</td><td align=\"right\">$ ".number_format($row->monto_total, 2, ".", ",")."</td><td align=\"center\">$tipo</td><td align=\"center\">$row->estatus $row->cpr_estatus_pedido_id</td><td align=\"center\">$row->usuario</td><td>$rec</td></tr>";
		}
	}
	echo "</table></center>";
	if($paginacion==true)
		echo $this->pagination->create_links();
	?>