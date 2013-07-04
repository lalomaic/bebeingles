<script src="<?php echo base_url();?>css/md5.js"></script>
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
	location.href="<?php echo base_url(); ?>index.php/compras/compras_c/formulario/list_compras_ingresadas/"+pag+"/sucursal/"+ped;
}
function fil_proveedor(){
	pag=<? echo $pag;?>;
	ped=$("#cproveedor_id").val();
	location.href="<?php echo base_url(); ?>index.php/compras/compras_c/formulario/list_compras_ingresadas/"+pag+"/proveedor/"+ped;
}
function fil_marca(){
	pag=<? echo $pag;?>;
	ped=$("#cmarca_id").val();
	location.href="<?php echo base_url(); ?>index.php/compras/compras_c/formulario/list_compras_ingresadas/"+pag+"/marcas/"+ped;
}
</script>

<?php
echo "<h2>$title</h2>";
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_compra\"><img src=\"".base_url()."images/consumo.png\" width=\"50px\" title=\"Nuevo Pedido de Compra\"></a><a href=\"".base_url()."index.php/".$ruta."/proveedores_c/".$funcion."/list_proveedor\"><img src=\"".base_url()."images/proveedor.png\" width=\"50px\" title=\"Listado de Proveedores\"></a></div>";

echo "<div align=\"center\"><b>Filtrado por Sucursal <input type='hidden' name='espacio_fisico_id' id='espacio_fisico_id' value='' size=\"3\"><input id='espacio_drop' class='espacio' value='' size='40'></b><button onclick='javascript:fil_sucursal()'>Filtrar</button></div>";

echo "<div align=\"center\"><b>Filtrado por Proveedor <input type='hidden' name='cproveedor_id' id='cproveedor_id' value='' size=\"3\"><input id='proveedor_drop' class='proveedor' value='' size='40'></b><button onclick='javascript:fil_proveedor()'>Filtrar</button></div>";

echo "<div align=\"center\"><b>Filtrado por Marca <input type='hidden' name='cmarca_id' id='cmarca_id' value='' size=\"3\"><input id='marca_drop' class='marca' value='' size='40'></b><button onclick='javascript:fil_marca()'>Filtrar</button></div>";

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
		<th>Estatus</th>
		<th>Capturista</th>
		<th width="100">Edición</th>
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
	$validar="<img src=\"".base_url()."images/bien.png\" width=\"20px\" title=\"Autorizar Pedido de Compra\" border=\"0\">";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"20px\" title=\"Impresión Orden de Compra\" border=\"0\">";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"20px\" title=\"Deshabilitar Registro\" border=\"0\">";
	$delete="";
	$edit="";
	$auth="";
	$pass="";
	if($pr_pedidos==false){
		echo "Sin Registros";
	} else {
		$ruta_auth=base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/aut_pedido_compra/";
		?>
	<script>
  $(document).ready(function() {
    $('.aut').fadeOut(001);

});

function pre_auth(id){
    $('#'+id).fadeIn(2000);
    $('#passwd'+id).focus();
}

 function auth(id){
   key=$('#passwd'+id).val();
  if(key.length>0 && key!= ''){
    llave=hex_md5(key);
    path="<? echo $ruta_auth;?>"+id+"/"+llave;
    location.href=''+path;
  } else {
    alert("No se ha ingresado la contraseña de autorización");
  }
  }
</script>
	<?php

	foreach($pr_pedidos->all as $row) {

		$codbar="<img src=\"".base_url()."images/codbar.jpeg\" width=\"20px\" title=\"Generar Etiquetas de Códigos de Barras\" border=\"0\">";
		$edit="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_pedido_compra/".$row->id."\"><img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Pedido\" border=\"0\"></a>";

		//Permiso de Borrado en el 2 Byte X1X
		if(substr(decbin($permisos), 1, 1)==1 ){
			$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_pedido_compra/".$row->id." \" onclick=\"return confirm('¿Estas seguro que deseas deshabilitar el pedido de compra?');\">$trash</a>";
		}
		if(substr(decbin($permisos), 0, 1)==1 and $row->cpr_estatus_pedido_id<3){
			$edit="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_pedido_compra/".$row->id."\"><img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Pedido\" border=\"0\"></a>";
		}

		if(substr(decbin($permisos), 2, 1)==1 and $row->cpr_estatus_pedido_id==1){
			$edit="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_pedido_compra/".$row->id."\"><img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Pedido\" border=\"0\"></a>";
		}

		if($row->cpr_estatus_pedido_id == 1){
			$auth="<a href=\"javascript:pre_auth($row->id)\" >$validar</a>";
			$pass="<div id='$row->id' class='aut'><input type='password' value='' id='passwd$row->id' size='8'><a href='javascript:auth($row->id)'>Autorizar</a></div>";

		}

		else if($row->cpr_estatus_pedido_id == 2){
			//Autorisado
			$auth=""; $pass=""; $delete="";

		}
		else if($row->cpr_estatus_pedido_id == 3){
			//FActurado y entregado
			$auth=""; $pass=""; $delete=""; $edit="";

		}

		if($row->estatus=="Cancelado"){
			$delete=''; $edit=''; $auth=''; $photo=''; $codbar='';
		}


		echo "<tr background=\"$img_row\"><td align=\"center\">$row->id</td><td align=\"center\">".fecha_imp($row->fecha_alta)."</td><td align=\"center\">".fecha_imp($row->fecha_entrega)."</td><td>$row->proveedor</td><td>$row->marca</td><td>$row->espacio_fisico</td><td align=\"right\">$ ".number_format($row->monto_total, 2, ".", ",")."</td><td align=\"center\">$row->estatus</td><td align=\"center\">$row->usuario</td><td>$edit $delete<a href=\"".base_url()."index.php/".$ruta."/compras_reportes/rep_pedido_compra/".$row->id."\" target=\"_blank\">$photo</a>$auth $pass <a href=\"".base_url()."index.php/".$ruta."/compras_reportes/rep_etiquetas_codigo_barras_pdf/".$row->id."\" target='_blank'>$codbar</a></td></tr>";

	}
	}
	echo "</table></center>";
	if($paginacion==true)
		echo $this->pagination->create_links();
	?>