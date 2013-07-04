<script src="<?php echo base_url();?>css/md5.js"></script>
<script>
$(document).ready(function() {
    $(".modal_cliente").fancybox({
				'width'				: '45%',
				'height'			: '45%',
				'autoScale'			: false,
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'type'				: 'iframe'
});
}); 
</script>
<?php 
echo "<h2>$title</h2>";
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_venta\"><img src=\"".base_url()."images/consumo.png\" width=\"50px\" title=\"Nuevo Pedido de Venta\"></a><a href=\"".base_url()."index.php/".$ruta."/clientes_c/".$funcion."/alta_cliente\"><img src=\"".base_url()."images/proveedor.png\" width=\"50px\" title=\"Alta de Clientes\"></a></div>";

echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";

?>
<?php //echo $this->pagination->create_links();?>
<table class="listado" border="0" width="920px">
	<tr>
		<th>Id Pedido</th>
		<th>Fecha Alta</th>
		<th>Fecha Entrega</th>
		<th>Cliente</th>
		<th>Monto Total</th>
		<th>Estatus</th>
		<th>Promotor</th>
		<th width="100">Edición</th>
	</tr>
	<?php
	//Function for formating the date tags YYYY/MM/DD to DD/MM/YYYY
	function fecha_imp($date){
		if($date!='0000-00-00'){
			$fecha=substr($date, 0, 10);
			$hora=substr($date, 11, strlen($date));
			$new = explode("-",$fecha);
			$a=array ($new[2], $new[1], $new[0]);
			if(strlen($hora)>2){
				return $n_date=implode("-", $a) . " Hora: ".$hora;
			} else {
				return $n_date=implode("-", $a);
			}
		} else {
			return "Sin fecha";
		}
	}//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"20px\" title=\"Impresión\" border=\"0\">";
	$validar="<img src=\"".base_url()."images/bien.png\" width=\"20px\" title=\"Autorizar Pedido de Compra\" border=\"0\">";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"20px\" title=\"Eliminar Registro\" border=\"0\">";
	$delete=""; $edit=""; $auth=""; $cliente="";
	if($cl_pedidos==false){
		echo "Sin Registros";
	} else {
		$ruta_auth=base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/aut_pedido_venta/";
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
	foreach($cl_pedidos->all as $row) {
		//Permiso de Borrado en el 2 Byte X1X
		if(substr(decbin($permisos), 1, 1)==1 and $row->ccl_estatus_pedido_id == 1){
			//Evitar que el mismo usuario se borre
			$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_pedido_venta/".$row->id." \" onclick=\"return confirm('¿Estas seguro que deseas borrar el Pedido de Venta?');\">$trash</a>";
		}
		if(substr(decbin($permisos), 2, 1)==1 and $row->ccl_estatus_pedido_id==1){
			$edit="<img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Registro\" border=\"0\">";
		}

		if($row->ccl_estatus_pedido_id == 1){
			//Cotizacion
			$auth="<a href=\"javascript:pre_auth($row->id)\" >$validar</a>";
			$cliente="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/historial_cliente/". $row->cclientes_id."\" target=\"_blank\" class=\"modal_cliente\"><img src=\"".base_url()."images/consumo.png\" width=\"20px\" title=\"Detalle Cliente\" border=\"0\"></a>";
		}

		else if($row->ccl_estatus_pedido_id == 2){
			//En proceso
			$auth=""; $pass=""; $delete=""; $edit=""; $cliente="";

		}
		else if($row->ccl_estatus_pedido_id == 3){
			//FActurado y entregado
			$auth=""; $pass=""; $delete=""; $edit=""; $cliente="";

		}

		$pass="<div id='$row->id' class='aut'><input type='password' value='' id='passwd$row->id' size='8'><a href='javascript:auth($row->id)'>Autorizar</a></div>";

		echo "<tr background=\"$img_row\"><td align=\"center\">$row->id</td><td align=\"center\">".$row->fecha_alta."</td><td align=\"center\">".$row->fecha_entrega."</td><td>$row->clave - $row->cliente</td><td align=\"right\">$ ".number_format($row->monto_total, 2, ".", ",")."</td><td align=\"center\">$row->estatus</td><td align=\"center\">$row->usuario</td><td><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_pedido_venta/".$row->id."\">$edit</a>$delete<a href=\"".base_url()."index.php/ventas/ventas_reportes/rep_pedido_venta/".$row->id."\" target=\"_blank\">$photo</a>$auth $pass $cliente</td></tr>";

	}
	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	unset($cl_pedidos);
	?>