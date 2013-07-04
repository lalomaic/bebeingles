<style>
body {
	font-size: 8pt;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 9pt;
	background-color: #fff;
}

h2 {
	font-size: 9pt;
}

table {
	background-color: #FFFFFF;
	border: 1px solid #ccc;
}

table tr td {
	border-bottom: 1px solid #ccc;
	border-right: 1px solid #ccc;
	font-size: 7pt;
}
</style>
<table width='100%' border="0" class='listado'>
	<tr>
		<th>
			<h2>
				<?=$title?>
				<br /> Periodo:
				<?=$fecha1?>
				-
				<?=$fecha2?>
				<br /> <b>Fecha de impresion: <?php echo date("d/m/Y")?><br />
		
		</th>
	</tr>
</table>
<?php
$ruta=base_url();
$img_row="".base_url()."images/table_row.png";
$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"20px\" title=\"Impresión Orden de Compra\" border=\"0\">";
echo "<h2>PEDIDOS</h2>";
echo "<div align=\"center\"><b>Total de registros:".count($pedidos->all)."</b></div>";
?>
<table class="listado" border="0" width="100%">
	<tr>
		<th>Id Pedido</th>
		<th>Id Lote</th>
		<th>Fecha Alta</th>
		<th>Fecha Entrega</th>
		<th>Proveedor</th>
		<th>Marca</th>
		<th>Sucursal</th>
		<th>Monto Total</th>
		<th>Tipo</th>
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
	if($pedidos==false){
		echo "Sin Registros";
	} else {
		foreach($pedidos->all as $row) {

			$codbar="<img src=\"".base_url()."images/codbar.jpeg\" width=\"20px\" title=\"Generar Etiquetas de Códigos de Barras\" border=\"0\">";

			if($row->tipo=='f')
				$tipo="Normal";
			else
				$tipo="Pendiente";

			echo "<tr background=\"$img_row\"><td align=\"center\">$row->id</td><td align=\"center\">$row->lote_id</td><td align=\"center\">".fecha_imp($row->fecha_alta)."</td><td align=\"center\">".fecha_imp($row->fecha_entrega)."</td><td>$row->proveedor</td><td>$row->marca</td><td>$row->espacio_fisico</td><td align=\"right\">$ ".number_format($row->monto_total, 2, ".", ",")."</td><td align=\"center\">$tipo</td><td align=\"center\">$row->estatus</td><td align=\"center\">$row->usuario</td><td><a href=\"".base_url()."index.php/compras/compras_reportes/rep_pedido_compra/".$row->id."\" target=\"_blank\">$photo</a> <a href=\"".base_url()."index.php/compras/compras_reportes/rep_etiquetas_codigo_barras_pdf/".$row->id."\" target='_blank'>$codbar</a></td></tr>";

		}
	}
	echo "</table></center>";
?>