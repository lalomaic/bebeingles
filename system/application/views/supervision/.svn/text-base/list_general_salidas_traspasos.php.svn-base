<script type="text/javascript">
$(document).ready(function() {
	$($.date_input.initialize);
	$('#espacio_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_espacios_tiendas_oficinas_ajax/', {
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

	$('#espacio_rdrop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_espacios_tiendas_oficinas_ajax/', {
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
		$("#espacio_fisico_rid").val(""+item.id);
	});
});

function borrar_ajax(id){
		$.post("<? echo base_url();?>index.php/ajax_pet/borrar_traspaso_tienda",{ enviarvalor : id },  // create an object will all values
		//function that is called when server returns a value.
		function(data){
			document.form1.submit();
			//$(renglon).html(data);
		}
		);
	}
function format(r) {
	return r.descripcion;
}

function filtrado(){
	document.form1.submit();
}


function fil_sucursal(){
	pag=<? echo $pag;?>;
	ped=$("#espacio_fisico_id").val();
	location.href="<?php echo base_url(); ?>index.php/compras/compras_c/formulario/list_compras/"+pag+"/sucursal/"+ped;
}

function borrar_ajax(id){
	$.post("<? echo base_url();?>index.php/ajax_pet/borrar_traspaso_tienda",{ enviarvalor : id },  // create an object will all values
	//function that is called when server returns a value.
	function(data){
		document.form1.submit();
		//$(renglon).html(data);
	});
}
</script>
<?php
if(isset($espacio_id)==false){
	$espacio_id=0;
	$espacio_tag="";
	$espacio_rid=0;
	$espacio_rtag="";
	$fecha1="";
	$fecha2="";
	$descripcion="";
}
echo "<h2>$title</h2>";
$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta."/$controller/$funcion/$subfuncion/filtrado", $atrib) . "\n";
echo "<table ><tr>";
echo "<td align=\"center\"><b>Sucursal Envio</b></td>";
echo "<td align=\"center\"><b>Sucursal Recibe</b></td>";
echo "<td align=\"center\"><b>Fecha Inicio</b></td>";
echo "<td align=\"center\"><b>Fecha Final</b></td>";
echo "<td align=\"center\"><b>Descripción</b></td></tr>";

echo "<tr><td align=\"center\" valign='top'><input type='hidden' name='espacio_fisico_id' id='espacio_fisico_id' value='$espacio_id' size=\"3\"><input id='espacio_drop' class='espacio' value='$espacio_tag' size='20' name='espacio_drop'></b><br/><br/></td>";

echo "<td align=\"center\" valign='top'><input type='hidden' name='espacio_fisico_rid' id='espacio_fisico_rid' value='$espacio_rid' size=\"3\"><input id='espacio_rdrop' class='espacio' value='$espacio_rtag' size='20' name='espacio_rdrop'></b><br/><br/></td>";

echo "<td align=\"center\" valign='top'><b><input id='fecha_inicio' name='fecha_inicio' class='date_input' value='$fecha1' size='12'><br/><button onclick='javascript:filtrado()'>Filtrar</button></td>";

echo "<td align=\"center\" valign='top'><b><input id='fecha_final' name='fecha_final' class='date_input' value='$fecha2' size='12'><br/><br/></td>";

echo "<td align=\"center\" valign='top'><b><input id='descripcion' name='descripcion' class='descripcion' value='$descripcion' size='20'><br/><br/></td>";

echo "</tr></table>";
echo form_close();

echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";
if($paginacion==true)
	echo $this->pagination->create_links();
?>
<table class="listado" border="0">
	<tr>
		<th>Id Traspaso Tienda</th>
		<th>Fecha Salida</th>
		<th>Fecha Entrada</th>
		<th>Sucursal de Envio</th>
		<th>Sucursal de Recepcion</th>
		<th>Producto</th>
		<th>Estatus</th>
		<th>Edición</th>
	</tr>
	<?php
	//Function for formating the date tags YYYY/MM/DD to DD/MM/YYYY
	function fecha_imp($date, $case){
		if($date!='0000-00-00'){
			$fecha=substr($date, 1, strpos($date, ' ')-1);
			$hora=substr($date, strpos($date, ' '), strlen($date));
			$new = explode("-",$fecha);
			$a=array ($new[2], $new[1], "2".$new[0]);
			if ($case==1){
				return $n_date=implode("-", $a) . " Hora: ".$hora;
			} else {
				return $n_date=implode("-", $a);
			}
		} else {
			return "Sin fecha";
		}
	}
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$validar="<img src=\"".base_url()."images/bien.png\" width=\"25px\" title=\"Autorizar Pedido de Compra\" border=\"0\">";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"Impresión Orden de Compra\" border=\"0\">";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Cancelar Traspaso\" border=\"0\">";
	$ok="<img src=\"".base_url()."images/bien.png\" width=\"25px\" title=\"Dar entrada al Pedido de Compra\" border=\"0\">";
	if($cl_pedidos==false){
		echo "Sin Registros";
	} else {
		$edit="";
		foreach($cl_pedidos->all as $row) {
			if($row->entrada_id==0){
				$estatus="Enviado, sin ingreso";
				$delete="<a href=\"javascript:borrar_ajax($row->id);\" onclick=\"return confirm('¿Estas seguro que deseas cancelar el traspaso del calzado?');\">$trash</a>";

			} else if($row->entrada_id>0){
				$estatus="Ingresado";
				$delete="";
			}

			echo "<tr background=\"$img_row\"><td align=\"center\">$row->id</td><td align=\"center\">$row->fecha_salida</td><td align=\"center\">$row->fecha_entrada</td><td align=\"center\">$row->envia</td><td align=\"center\">$row->recibe</td><td align=\"left\">$row->descripcion # $row->numero_mm </td><td align=\"center\">$estatus</td><td>$edit <a href=\"".base_url()."index.php/almacen/almacen_reportes/rep_pedido_traspaso/$row->traspaso_id\" target=\"_blank\" class='modal_pdf' >$delete </a></td></tr>";

		}
	}

	echo "</table></center>";
	if($paginacion==true)
		echo $this->pagination->create_links();
	?>